<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegisteredEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Category;
use App\Models\Settings;
use App\Models\User;
use App\Models\UserVerify;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;


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

        event(new UserRegisteredEvent($user));

//        Mail::send('email.verify', compact('token'), function ($mail) use ($user){
//            $mail->to($user->email);
//            $mail->subject('Doğrulama Emaili');
//        });

         alert()->success('Başarılı', 'Mailinize onay maili gönderilmiştir. Lütfen mail kutunuzu kontrol ediniz')
             ->showConfirmButton('Tamam', '#3085d6')
             ->autoClose(5000);

        return redirect()->back();
    }

    public function verify(Request $request, $token)
    {
        $verifyQuery = UserVerify::query()->where('token', $token);
        $verifyFind = $verifyQuery->first();

        if (!is_null($verifyFind))
        {
            $user = $verifyFind->user;

            if (is_null($user->email_verified_at))
            {
                $user->email_verified_at = now();
                $user->status = 1;
                $user->save();
                $verifyQuery->delete();
                $message = 'Emailiniz doğrulandı.';
            }
            else
            {
                $message = 'Emailiniz daha önce doğrulanmıştı. Giriş yapabilirsiniz.';
            }
            alert()
                ->success('Başarılı', $message)
                ->showConfirmButton('Tamam', '#3085d6')
                ->autoClose(5000);

            return redirect()->route('auth.login');
        }
        else
        {
            abort(404);
        }

    }

    public function socialLogin($driver)
    {
        return Socialite::driver($driver)->redirect();

    }

    public function socialVerify($driver)
    {
        $user = Socialite::driver($driver)->user();

        $userCheck = User::query()->where('email', $user->getEmail())->first();

        if ($userCheck)
        {
            Auth::login($user);
            return redirect()->route('home');
        }

        $username = Str::slug($user->getName());

        $userCreate = User::create([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => bcrypt(""),
            'username' => is_null($this->checkUsername($username)) ? $username : $username . uniqid(),
            'status' => 1,
            'email_verified_at' => now(),
            $driver . '_id' => $user->getId()
        ]);

        Auth::login($userCreate);
        return redirect()->route('home');
    }

    public function checkUsername(string $username): null|object
    {
        return User::query()->where('username', $username)->first();

    }


}
