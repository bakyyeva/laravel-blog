<h1>Doğrulama Emaili</h1>

<p>
    Merhba {{ $user->name }}, hoşgeldiniz.
</p>
<p>
    Lütfen aşağıdaki linkten mailinizi doğrulayınız.
</p>

<a href="{{ route('verify.token', ['token' => $token]) }}">
    Mailimi doğrulama
</a>
