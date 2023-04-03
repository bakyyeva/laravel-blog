<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLogin()
    {
        if(Auth::check())
        {
            return redirect()->route('home');
        }
        return view("auth.login");
    }

    public function login(LoginRequest $request)
    {
        $remember = $request->remember;
        $remember ? $remember = true : $remember = false;
        $user = User::query()->where('email', $request->email)->first();

        if($user && Hash::check($request->password, $user->password))
        {
            Auth::login($user, $remember);
            return redirect()->route('admin.home');
        }
        return back()->withErrors([
            'email' => 'Email veya şifre hatalı',
            'password' => 'Email veya şifre hatalı'
        ]);

    }

    public function logout(Request $request)
    {
        if (Auth::check())
        {
            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()->route("login");
        }
    }
}
