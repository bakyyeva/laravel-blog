@extends("layouts.auth")
@section("title")
    Giriş Yap
@endsection
@section("css")
@endsection

@section("content")
    <div class="app app-auth-sign-in align-content-stretch d-flex flex-wrap justify-content-end">
        <div class="app-auth-background">

        </div>
        <div class="app-auth-container">
            <div class="logo">
                <a href="index.html">Neptune</a>
            </div>
            <p class="auth-description">Please sign-in to your account and continue to the dashboard.<br>Don't have an
                account? <a href="sign-up.html">Sign Up</a></p>

            @if($errors->any())
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger">{{ $error }}</div>
                @endforeach
            @endif

            <form action="" method="POST">
                @csrf
                <div class="auth-credentials m-b-xxl">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control m-b-md" aria-describedby="signInEmail"
                           placeholder="example@neptune.com"
                           name="email"
                           id="email"
                           value="{{ old('email') }}"
                    >

                    <label for="password" class="form-label">Parola</label>
                    <input type="password" class="form-control" aria-describedby="signInPassword"
                           name="password"
                           id="password"
                           placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;">
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember"
                        {{ old("remember") ? "checked" : "" }}
                        >
                        <label class="form-check-label" for="remember">
                           Beni Hatırla
                        </label>
                    </div>
                </div>

                <div class="auth-submit">
                    <button class="btn btn-primary" type="submit" id="btnLogin">Giriş Yap</button>
                    <a href="#" class="auth-forgot-password float-end">Forgot password?</a>
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
    <script>
        $(document).ready(function () {

            function emailControl(email) {
                var regex= /([a-zA-Z0-9_.+-])+@(([a-zA-Z0-9-])+\.)+([a-z]{2,4})+$/;
                return regex.test(email);
            }

            /*function passwordControl(password) {
                var regex= /([a-zA-Z0-9])+$/;
                return regex.test(password);

            }*/


            $("#btnLogin").click(function () {
                var email=$("#email");
                let password=$("#password");

                if (email.val().trim() == null || email.val().trim() === "")
                {
                    var p = document.createElement("p");
                    p.innerText="Bu alan boş geçilemez";
                    $(".inputDiv").append(p);
                    p.setAttribute("id", 'hataMsj');
                    email.addClass("border-danger");

                }
                else if (emailControl(email.val().trim()) == false)
                {
                    email.addClass("border-danger");

                    if($('#hataMsj'))
                    {
                        $('#hataMsj').text("Emaili lütfen doğru şekilde yazınız.");
                        $('#hata2').text("");
                    }
                    $('#hataMsj').text("");
                    var text = document.createElement("p");
                    text.innerText="Emaili lütfen doğru şekilde yazınız.";
                    $(".inputDiv").append(text);
                }
                if(password.val().trim().length == 0 || password.val().trim() === ""  || password.val().trim() == null)
                {
                    var p = document.createElement("p");
                    p.innerText="Parola boş geçilemez, lütfen parolanızı giriniz.";
                    $(".passwordDiv").append(p);
                    p.setAttribute("id", 'passMsj');
                    password.addClass("border-danger");
                }
            });

            $("#password").blur(function () {
                if('#passMsj')
                {
                    $('#passMsj').text("");
                    $('#password').removeClass("border-danger");
                }
            });

        });
    </script>
@endsection
