<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        Auth::attempt(['login' => $request->login, 'password' => $request->password, 'active' => 1]);
        return redirect(route('home'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('login'));
    }
}
