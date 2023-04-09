@extends("layouts.front")
@section("css")
@endsection

@section("content")
    <div class="row">
        <div class="col-md-12">
            <x-bootstrap.card>
                <x-slot:header>
                    Parolamı Sıfırla
                </x-slot:header>
                <x-slot:body>
                    <x-errors.display-error />
                    <form action="{{ isset($token) ? route('passwordResetToken', ['token' => $token]) : route("passwordReset") }}" method="POST" class="register-form">
                        @csrf
                        <div class="row">
                            @isset($token)
                                <div class="col-md-12 mt-2">
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Parola">
                                </div>
                                <div class="col-md-12 mt-2">
                                    <input type="password" name="password_confirmation" id="password" class="form-control" placeholder="Parola Doğrulama">
                                </div>
                            @else
                                <div class="col-md-12 mt-2">
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                                </div>
                            @endisset
                            <div class="col-md-12 mt-4">
                                <hr class="m-0 mb-4">
                                <button class="btn btn-success w-100">Parolamı Sıfırla</button>
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

