<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;


class AuthController extends Controller
{
  public function register(Request $request)
  {
    $request->validate([
      'name' => 'required|string',
      'email' => 'required|email|unique:users',
      'password' => 'required|string|min:6',
    ]);

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json(['user' => $user, 'access_token' => $token, 'token_type' => 'Bearer']);
  }

  public function login(Request $request)
  {
  //   echo 'Auth Login';

    $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {      
      return redirect()->route('login')->withErrors(['message' => 'Неверные учетные данные']);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    // Сохранение токена в сессии для Inertia
    $request->session()->put('access_token', $token);

    return redirect()->route('/admin/index');
  }

  // public function logout(Request $request)
  // {
  //   // echo 'Auth Logout';
  //   $request->user()->tokens()->delete();
  //   Auth::logout();
  //   $request->session()->invalidate();
  //   $request->session()->regenerateToken();

  //   return response()->json(['message' => 'Logged out']);
  // }
}
