<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session;

class SessionController extends Controller
{
  // Включить глобальные продажи
  public function openAllSales(Request $request)
  {
    Session::updateOrCreate(
      ['key' => 'sales_globally_open'],
      ['value' => true]
    );

    return response()->json(['success' => true, 'sales_globally_open' => true]);
  }

  // Отключить глобальные продажи
  public function closeAllSales(Request $request)
  {
    Session::updateOrCreate(
      ['key' => 'sales_globally_open'],
      ['value' => false]
    );

    return response()->json(['success' => true, 'sales_globally_open' => false]);
  }

  public function status(Request $request)
  {
    try {
      // Получаем настройку
      $setting = Session::where('key', 'sales_globally_open')
        ->select('value')
        ->first();

      // Получаем значение или устанавливаем значение по умолчанию
      $salesGloballyOpen = $setting ? (bool)$setting->value : true;

      return response()->json([
        'status' => 'success',
        'data' => [
          'sales_globally_open' => $salesGloballyOpen
        ]
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Ошибка при получении статуса продаж',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
