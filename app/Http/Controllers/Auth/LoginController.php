<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use App\Traits\Loggable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    use Loggable;

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

    public function showPasswordReset()
    {
        return view('front.auth.reset-password');
    }

    public function showPasswordResetConfirm(Request $request, string $token)
    {
      //$token = $request->token;

      $tokenExist = DB::table("password_reset_tokens")->where('token', $token)->first();

      if (!$tokenExist)
          abort(404);

      return view('front.auth.reset-password', compact('token'));
    }

    public function login(LoginRequest $request)
    {
        $remember = $request->remember;
        $remember ? $remember = true : $remember = false;
        $user = User::query()->where('email', $request->email)->first();

        if($user && Hash::check($request->password, $user->password))
        {
            Auth::login($user, $remember);

            $this->log('login', $user->id, $user->toArray(), User::class);

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

            $this->log('logout', \auth()->id(), \auth()->user()->toArray(), User::class);

            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            if (!$isAdmin)
                return redirect()->route('home');

            return redirect()->route("auth.login");
        }
    }

    public function sendPasswordReset(Request $request)
    {
        $email = $request->email;
        $find = User::query()->where('email', $email)->firstOrFail();

        $tokenFind = DB::table("password_reset_tokens")->where('email', $email)->first();

        if ($tokenFind)
        {
            $token = $tokenFind->token;
        }
        else
        {
            $token = Str::random(60);
            DB::table("password_reset_tokens")->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => now()
            ]);
        }

        if ($tokenFind && now()->diffInHours($tokenFind->created_at) < 5)
        {
            alert()
                ->success('Başarılı', "Daha önce doğrulama maili gönderilmiştir.")
                ->showConfirmButton('Tamam', '#3085d6')
                ->autoClose(5000);

            return  redirect()->back();
        }

        Mail::to($find->email)->send(new ResetPasswordMail($find, $token));
        $this->log('password reset mail send', $find->id, $find->toArray(), User::class);

        alert()
            ->success('Başarılı', "Parola Sıfırlama Mailiniz gönderilmiştir")
            ->showConfirmButton('Tamam', '#3085d6')
            ->autoClose(5000);

        return redirect()->back();
    }

    public function passwordReset(PasswordResetRequest $request, string $token)
    {
         $tokenQuery = DB::table("password_reset_tokens")->where('token', $token);
         $tokenExist = $tokenQuery->first();

         if (!$tokenExist)
             abort(404);

//         $userExist = DB::table("password_reset_tokens")->where('email', $tokenExist->email)->first();
         $userExist = User::query()->where('email', $tokenExist->email)->first();

         if (!$userExist)
             abort(400, 'Lütfen yönetici ile iletişime geçin');

         $userExist->update(['password' => Hash::make($request->password)]);

        $tokenQuery->delete();

        alert()
            ->success('Başarılı', "Parola sıfırlanmıştır. Giriş yapabilirsiniz.")
            ->showConfirmButton('Tamam', '#3085d6')
            ->autoClose(5000);

         return redirect()->route('user.login');
    }

}
