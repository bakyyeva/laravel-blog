@extends("layouts.front")
@section("css")
@endsection

@section("content")
    <div class="row">
        <div class="col-md-12">
            <x-bootstrap.card>
                <x-slot:header>
                    Giriş Yap
                </x-slot:header>
                <x-slot:body>
                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach
                    @endif
                    <form action="{{ route("user.login") }}" method="POST" class="register-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                            </div>
                            <div class="col-md-12 mt-2">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Parolanız">
                                <small>
                                    Parolanız küçük harf, büyük harf, rakam ve özel karakter içermelidir.
                                </small>
                                <hr class="my-4">
                            </div>

                            <div class="col-md-12 social-media-register">
                                <div class="d-flex justify-content-center">
                                    <a href="">
                                        <i class="fa fa-google fa-2x me-3"></i>
                                    </a>
                                    <a href="">
                                        <i class="fa fa-facebook fa-2x me-3"></i>
                                    </a>
                                    <a href="">
                                        <i class="fa fa-twitter fa-2x me-3"></i>
                                    </a>
                                    <a href="">
                                        <i class="fa fa-github fa-2x me-3"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-12 mt-4">
                                <div class="d-flex justify-content-end">
                                    <small>
                                        <a class="btn-password-reset" href="">Parolamı Unuttum</a>
                                    </small>
                                </div>
                                <hr class="m-0 mb-4">

                                <button class="btn btn-success w-100">Giriş Yap</button>
                            </div>

                        </div>
                    </form>
                </x-slot:body>
            </x-bootstrap.card>
        </div>
    </div>
@endsection

@section("js")
@endsection
