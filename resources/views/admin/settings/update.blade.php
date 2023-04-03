@extends('layouts.admin')
@section('title')
    Ayarlar
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset("assets/plugins/summernote/summernote-lite.min.css") }}">
@endsection

@section('content')
    <x-bootstrap.card>
        <x-slot:header>
            <h2 class="card-title"> Ayarlar </h2>
        </x-slot:header>
        <x-slot:body>
            <div class="example-container">
                <div class="example-content">
                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach
                    @endif
                    <form  action="{{ route('settings') }}"
                           method="POST"
                           enctype="multipart/form-data"
                           id="settingsForm"
                    >
                        @csrf
                        <label for="header_text" class="form-label">Telegram Linki</label>
                        <input type="text"
                               class="form-control form-control-solid-bordered m-b-sm
                               @if($errors->has("telegram_link"))
                                    border-danger
                               @endif
                               "
                               placeholder="Telegram Link"
                               name="telegram_link"
                               id="telegram_link"
                               value="{{ isset($settings) ? $settings->telegram_link : "" }}"
                        >
                        <label for="header_text" class="form-label">Header Yazı</label>
                        <textarea name="header_text" id="header_text" class="m-b-sm">
                            {!! isset($settings) ? $settings->header_text : "" !!}
                        </textarea>
                        @if($errors->has("header_text"))
                            {{ $errors->first("header_text") }}
                            <br>
                        @endif
                        <label for="footer_text" class="form-label">Footer Yazı</label>
                        <textarea name="footer_text" id="footer_text" class="m-b-sm">
                            {!! isset($settings) ? $settings->footer_text : "" !!}
                        </textarea>
                        @if($errors->has("footer_text"))
                            {{ $errors->first("footer_text") }}
                            <br>
                        @endif
                        <label for="logo" class="form-label m-t-sm">Logo Görseli</label>
                        <input type="file" name="logo" id="logo" accept="image/png, image/jpeg, image/jpg" class="form-control
                               @if($errors->has("logo"))
                                   border-danger
                               @endif
                               "
                        >
                        @if($errors->has("logo"))
                            {{ $errors->first("logo") }}
                            <br>
                        @endif
                        <div class="form-text m-b-sm">Logo Görseli Maksimum 2mb olmalıdır</div>
                        @if(isset($settings) && $settings->logo)
                            <img src="{{ asset($settings->logo) }}" class="img-fluid" style="max-height: 100px">
                        @endif
                        <label for="category_default_image" class="form-label m-t-sm">Varsayılan Kategori Görseli</label>
                        <input type="file" name="category_default_image" id="category_default_image" accept="image/png, image/jpeg, image/jpg" class="form-control
                               @if($errors->has("category_default_image"))
                                   border-danger
                               @endif
                               "
                        >
                        @if($errors->has("category_default_image"))
                            {{ $errors->first("category_default_image") }}
                            <br>
                        @endif
                        <div class="form-text m-b-sm">Varsayılan Kategori Görseli Maksimum 2mb olmalıdır</div>
                        @if(isset($settings) && $settings->category_default_image)
                            <img src="{{ asset($settings->category_default_image) }}" class="img-fluid" style="max-height: 100px">
                        @endif
                        <label for="article_default_image" class="form-label m-t-sm">Varsayılan Makale Görseli</label>
                        <input type="file" name="article_default_image" id="article_default_image" accept="image/png, image/jpeg, image/jpg" class="form-control
                               @if($errors->has("article_default_image"))
                                   border-danger
                               @endif
                               "
                        >
                        @if($errors->has("article_default_image"))
                            {{ $errors->first("article_default_image") }}
                            <br>
                        @endif
                        <div class="form-text m-b-sm">Varsayılan Makale Görseli Maksimum 2mb olmalıdır</div>
                        @if(isset($settings) && $settings->article_default_image)
                            <img src="{{ asset($settings->article_default_image) }}" class="img-fluid" style="max-height: 100px">
                        @endif
                        <div class="form-check">
                            <input type="checkbox"  class="form-check-input"
                                   name="feature_categories_is_active"
                                   id="feature_categories_is_active"
                                   value="1"
                                      {{ isset($settings) && $settings->feature_categories_is_active ? "checked" :  "" }} >
                                 <label class="form-check-label" for="feature_categories_is_active">
                                     Öne Çıkarılan Kategoriler Anasayfada Görünsün mü?
                                 </label>
                             </div>
                             <div class="form-check">
                                 <input type="checkbox"  class="form-check-input"
                                        name="video_is_active"
                                        id="video_is_active"
                                        value="1"
                                     {{ isset($settings) && $settings->video_is_active ? "checked" :  "" }}>
                            <label class="form-check-label" for="video_is_active">
                                Videolar Sidebarda Görünsün mü?
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox"  class="form-check-input"
                                   name="author_is_active"
                                   id="author_is_active"
                                   value="1"
                                {{ isset($settings) && $settings->author_is_active ? "checked" :  "" }}>
                            <label class="form-check-label" for="author_is_active">
                                Yazarlar Sidebarda Görünsün mü?
                            </label>
                        </div>

                        <hr>
                        <div class="col-6 mx-auto mt-2">
                            <button type="submit" class="btn btn-success btn-rounded w-100" id="btnSave">
                                Güncelle
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
@endsection
