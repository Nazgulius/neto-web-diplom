<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Seat;
use Illuminate\Support\Str;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\ErrorCorrectionLevel;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return Ticket::all();
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $movie = Ticket::create($request->all());
    return response()->json($movie, 201);
  }

  /**
   * Display the specified resource.
   */
  public function show($uuid)
  {
    // return Ticket::findOrFail($id);

    $ticket = Ticket::where('uuid', $uuid)->firstOrFail();
    // П /П: Можно вернуть JSON или отображать Blade шаблон
    return response()->json([
        'ticket' => $ticket,
        'qr_code_url' => asset($ticket->qr_code_path),
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    $movie = Ticket::findOrFail($id);
    $movie->update($request->all());
    return response()->json($movie);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    Ticket::destroy($id);
    return response()->json(null, 204);
  }

  public function book(Request $request)
  {
    $request->validate([
      'seat_id' => 'required|integer|exists:seats,id',
      'session_id' => 'required|integer|exists:sessions,id',
      'client_id' => 'required|integer|exists:clients,id', // или получать клиента из авторизации
    ]);

    $seat = Seat::find($request->seat_id);
    if ($seat->status !== 'available') {
      return response()->json(['error' => 'Место недоступно'], 400);
    }

    // Создание билета
    $ticket = new Ticket();
    $ticket->seat_id = $seat->id;
    $ticket->session_id = $request->session_id;
    $ticket->client_id = $request->client_id;
    $ticket->uuid = Str::uuid(); // Уникальный идентификатор для QR
    $ticket->save();

    // Обновляем статус места
    $seat->status = 'booked';
    $seat->save();

    // Генерация QR-кода
    $qrData = route('tickets.show', ['uuid' => $ticket->uuid]); // или любой маршрут для проверки билета
    $qrImagePath = storage_path('app/public/qrcodes/' . $ticket->uuid . '.png');

    \QRCode::png($qrData, $qrImagePath, 'H', 10, 2);

    // сохраняем путь к QR
    $ticket->qr_code_path = 'storage/qrcodes/' . $ticket->uuid . '.png';
    $ticket->save();

    return response()->json([
      'ticket' => $ticket,
      'qr_code_url' => asset($ticket->qr_code_path),
    ]);
  }

  public function getQrCode()
    {
        // $qrCode = QrCode::create('Привет! Это qr-код! ура!'); // старый вариант с ошибкой
      try {
        $qrCode = new QrCode('Привет! Это qr-код! ура!'); // текст или ссылка
        // Получим изображение в base64
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $base64 = base64_encode($result->getString());

        // Передадим в представление или API
        return response()->json(['qr_code' => 'data:image/png;base64,' . $base64]);
      } catch (\Exception $e) {
        // Логируем ошибку
        \Log::error('QR code generation failed: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to generate QR code'], 500);
      }
    }
}
