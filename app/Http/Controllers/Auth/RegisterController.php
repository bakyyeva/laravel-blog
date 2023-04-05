<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Category;
use App\Models\Settings;
use App\Models\User;
use App\Models\UserVerify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class RegisterController extends Controller
{

    public function showRegister()
    {

        return view('front.auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = new User();

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->status = 0;

        $user->save();

        $token = Str::random(60);

        UserVerify::create([
            'user_id' => $user->id,
            'token' => $token
        ]);

        Mail::send('email.verify', compact('token'), function ($mail) use ($user){
            $mail->to($user->email);
            $mail->subject('Doğrulama Emaili');

        });

        alert()
            ->success('Başarılı', "Mailinize onay maili gönderilmiştir. Lütfen mail kutunuzu kontrol ediniz")
            ->showConfirmButton('Tamam', '#3085d6')
            ->autoClose(5000);


    }
}
