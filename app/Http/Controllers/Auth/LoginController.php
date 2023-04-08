<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function showLogin()
    {
        if(Auth::check())
        {
            return redirect()->route('admin.home');
        }
        return view("auth.login");
    }

    public function showLoginUser()
    {
        if(Auth::check())
        {
            return redirect()->route('home');
        }
        return view("front.auth.login");
    }

    public function login(LoginRequest $request)
    {
        $remember = $request->remember;
        $remember ? $remember = true : $remember = false;
        $user = User::query()->where('email', $request->email)->first();

        if($user && Hash::check($request->password, $user->password))
        {
            Auth::login($user, $remember);

            $userIsAdmin = Auth::user()->is_admin;

            if (!$userIsAdmin)
                return redirect()->route('home');

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
            $isAdmin = Auth::user()->is_admin;
            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            if (!$isAdmin)
                return redirect()->route('home');

            return redirect()->route("auth.login");
        }
    }


}
