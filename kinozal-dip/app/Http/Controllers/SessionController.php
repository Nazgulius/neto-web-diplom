<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session;

class SessionController extends Controller
{
  // Открыть продажи для сеанса
  public function openSales(Request $request, $sessionId)
  {
      $session = Session::findOrFail($sessionId);
      $session->is_open = true;
      $session->save();

      return response()->json(['success' => true, 'session' => $session]);
  }

  // Закрыть продажи
  public function closeSales(Request $request, $sessionId)
  {
      $session = Session::findOrFail($sessionId);
      $session->is_open = false;
      $session->save();

      return response()->json(['success' => true, 'session' => $session]);
  }

  public function store(Request $request)
  {
      $validated = $request->validate([
          'session_id' => 'required|exists:sessions,id',
          // другие поля бронирования
      ]);

      $session = Session::findOrFail($validated['session_id']);

      if (!$session->is_open) {
          return response()->json(['message' => 'Продажа билетов закрыта для этого сеанса.'], 403);
      }

      // Продолжение процесса бронирования и оплаты
      // ...
  }
}
