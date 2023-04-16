@extends('layouts.admin')

@section('title')
    Kullanıcı {{ isset($user) ? 'Güncelleme' : 'Ekleme' }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset("assets/plugins/summernote/summernote-lite.min.css") }}">
@endsection

@section('content')
    <x-bootstrap.card>
        <x-slot:header>
            <h2 class="card-title">Kullanıcı {{ isset($user) ? 'Güncelleme' : 'Ekleme' }}</h2>
        </x-slot:header>
        <x-slot:body>
            <div class="example-container">
                <div class="example-content">
                    <x-errors.display-error />
                    <form action="{{ isset($user) ? route('user.edit', ['user' => $user->username]) : route('user.create') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          id="userForm">
                        @csrf
                        <label for="username" class="form-label">Kullanıcı Adı</label>
                        <input type="text"
                               class="form-control form-control-solid-bordered m-b-sm"
                               aria-describedby="solidBoderedInputExample"
                               placeholder="Kullanıcı Adı"
                               name="username"
                               id="username"
                               value="{{ isset($user) ? $user->username : old('username') }}"
                               required
                        >
                        <label for="password" class="form-label">Parola</label>
                        <input type="password"
                               class="form-control form-control-solid-bordered m-b-sm"
                               aria-describedby="solidBoderedInputExample"
                               placeholder="Parola"
                               name="password"
                               id="password"
                               value=""
                        >
                        <label for="name" class="form-label">Kullanıcı Ad Soyad</label>
                        <input type="text"
                               class="form-control form-control-solid-bordered m-b-sm"
                               aria-describedby="solidBoderedInputExample"
                               placeholder="Kullanıcı Ad Soyad"
                               name="name"
                               id="name"
                               value="{{ isset($user) ? $user->name : old('name') }}"
                               required
                        >
                        <label for="email" class="form-label">Email</label>
                        <input type="text"
                               class="form-control form-control-solid-bordered m-b-sm"
                               aria-describedby="solidBoderedInputExample"
                               placeholder="Email"
                               name="email"
                               id="email"
                               value="{{ isset($user) ? $user->email : old('email') }}"
                               required
                        >
                        <label for="about" class="form-label">Hakkında Yazısı</label>
                        <textarea name="about" id="about" class="m-b-sm">{!! isset($article) ? $article->about : old('about') !!}</textarea>
                        <div class="row mt-5">
                            <div class="col-8">
                                <label for="image" class="form-label m-t-sm">Kullanıcı Görseli</label>
                                <select name="image" id="image" class="form-control">
                                    <option value="{{ null }}">Görsel Seçin</option>
                                    <option value="/assets/images/user-images/profile1.png"
                                        {{ isset($user->image) && $user->image=="/assets/images/user-images/profile1.png" ? 'selected' : (old('image') == "/assets/images/user-images/profile1.png" ? 'selected' : '') }}>
                                        Profile 1
                                    </option>
                                    <option value="/assets/images/user-images/profile2.png"
                                        {{ isset($user->image) && $user->image=="/assets/images/user-images/profile2.png" ? 'selected' : (old('image') == "/assets/images/user-images/profile2.png" ? 'selected' : '') }}>
                                        Profile 2
                                    </option>
                                    <option value="/assets/images/user-images/default.png"
                                        {{ isset($user->image) && $user->image=="/assets/images/user-images/default.png" ? 'selected' : (old('image') == "/assets/images/user-images/default.png" ? 'selected' : '') }}>
                                        Profile 3
                                    </option>
                                </select>
                            </div>
                            <div class="col-4">
                                <img src="{{ isset($user->image) ? asset($user->image) : old('image') }}" id="profileImage" class="img-fluid" style="max-height: 100px">
                            </div>
                        </div>
                        <div class="form-check mt-5">
                            <input class="form-check-input" type="checkbox" name="is_admin" value="1" id="is_admin"
                                {{ isset($user) && $user->is_admin ? 'checked' : (old('is_admin') ? 'checked' : '') }}>
                            <label class="form-check-label" for="is_admin">
                                Kullanıcı Admin mi?
                            </label>
                        </div>
                        <div class="form-check mt-5">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="status"
                                {{ isset($user) && $user->status ? 'checked' : (old('status') ? 'checked' : '') }}>
                            <label class="form-check-label" for="status">
                                Kullanıcı Aktif Olsun mu?
                            </label>
                        </div>
                        <hr>
                        <div class="col-6 mx-auto mt-2">
                            <button type="button" class="btn btn-success btn-rounded w-100" id="btnSave">
                                {{ isset($user) ? 'Güncelleme' : 'Kaydet' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </x-slot:body>
    </x-bootstrap.card>
@endsection

@section('js')
    <script src="{{ asset("assets/admin/plugins/summernote/summernote-lite.min.js") }}"></script>
    <script src="{{ asset("assets/admin/js/pages/text-editor.js") }}"></script>
    <script>

        let username = $('#username');
        let name = $('#name');
        let email = $('#email');

        $(document).ready(function () {

            $('#btnSave').click(function () {
                if(username.val().trim() === '' || username.val().trim() == null )
                {
                    Swal.fire({
                        title: "Uyarı",
                        text: "Kullanıcı Adı boş geçilemez.",
                        confirmButtonText: 'Tamam',
                        icon: "info",
                    });
                }
                else if(name.val().trim() === '' || name.val().trim() == null)
                {
                    Swal.fire({
                        title: "Uyarı",
                        text: "Kullanıcı Adı Soydı boş geçilemez",
                        confirmButtonText: 'Tamam',
                        icon: "info",
                    });
                }
                else if(email.val().trim() === '' || email.val().trim() == null)
                {
                    Swal.fire({
                        title: "Uyarı",
                        text: "Kullanıcı email boş geçilemez",
                        confirmButtonText: 'Tamam',
                        icon: "info",
                    });
                }
                else {
                    $('#userForm').submit();
                }
            });

            $('#image').change(function () {
               $('#profileImage').attr("src", $(this).val());
            });

        });
    </script>
@endsection
