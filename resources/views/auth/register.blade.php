@extends("layouts.auth")
@section("title")
Kayıt Ol
@endsection
@section("css")
@endsection

@section("content")
    <div class="app app-auth-sign-up align-content-stretch d-flex flex-wrap justify-content-end">
        <div class="app-auth-background">

        </div>
        <div class="app-auth-container">
            <div class="logo">
                <a href="index.html">Neptune</a>
            </div>
            <p class="auth-description">Please enter your credentials to create an account.<br>Already have an account? <a href="sign-in.html">Sign In</a></p>

            @if($errors->any())
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger">{{ $error }}</div>
                @endforeach
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="auth-credentials m-b-xxl">
                    <label for="name" class="form-label">Ad Soyad</label>
                    <input type="text" class="form-control m-b-md"
                           aria-describedby="signUpUsername"
                           placeholder="Ad Soyad"
                           name="name"
                           id="name"
                           required
                    >

                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control m-b-md" aria-describedby="signUpEmail"
                           id="email"
                           name="email"
                           required
                           placeholder="example@neptune.com"
                    >

                    <label for="signUpPassword" class="form-label">Şifre</label>
                    <input type="password" class="form-control" aria-describedby="signUpPassword"
                           id="signUpPassword"
                           name="password"
                           required
                           placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;">
                    <div id="emailHelp" class="form-text">Password must be minimum 8 characters length*</div>

                    <label for="signUpPassword2" class="form-label">Şifre Tekrar</label>
                    <input type="password" class="form-control" aria-describedby="signUpPassword"
                           id="signUpPassword2"
                           name="password_confirmation"
                           required
                           placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;">
                    <div id="emailHelp" class="form-text">Password must be minimum 8 characters length*</div>
                </div>

                <div class="auth-submit">
                    <button class="btn btn-primary" type="submit" id="btnLogin">Kayıt Ol</button>
                </div>
                <div class="divider"></div>
                <div class="auth-alts">
                    <a href="#" class="auth-alts-google"></a>
                    <a href="#" class="auth-alts-facebook"></a>
                    <a href="#" class="auth-alts-twitter"></a>
                </div>
            </form>
        </div>
    </div>

@endsection

@section("js")
@endsection
