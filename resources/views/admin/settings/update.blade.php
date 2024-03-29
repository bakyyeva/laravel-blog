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
                    <x-errors.display-error />
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

                        <label for="seo_keywords_home" class="form-label">Seo Anahtar Kelimeler Anasayfa</label>
                        <textarea
                            class="form-control form-control-solid-bordered m-b-sm"
                            name="seo_keywords_home"
                            id="seo_keywords_home"
                            cols="30"
                            rows="5"
                            placeholder="Seo Anahtar Kelimeler Anasayfa"
                            style="resize: none">{{ isset($settings) ? $settings->seo_keywords_home : "" }}</textarea>

                        <label for="seo_description_home" class="form-label">Seo Description Anasayfa</label>
                        <textarea
                            class="form-control form-control-solid-bordered m-b-sm"
                            name="seo_description_home"
                            id="seo_description_home"
                            cols="30"
                            rows="5"
                            placeholder="Seo Description Anasayfa"
                            style="resize: none">{{ isset($settings) ? $settings->seo_description_home : "" }}</textarea>

                        <label for="seo_keywords_articles" class="form-label">Seo Anahtar Kelimeler Makale</label>
                        <textarea
                            class="form-control form-control-solid-bordered m-b-sm"
                            name="seo_keywords_articles"
                            id="seo_keywords_articles"
                            cols="30"
                            rows="5"
                            placeholder="Seo Anahtar Kelimeler Makale"
                            style="resize: none">{{ isset($settings) ? $settings->seo_keywords_articles : "" }}</textarea>

                        <label for="seo_description_articles" class="form-label">Seo Description Makale</label>
                        <textarea
                            class="form-control form-control-solid-bordered m-b-sm"
                            name="seo_description_articles"
                            id="seo_description_articles"
                            cols="30"
                            rows="5"
                            placeholder="Seo Description Makale"
                            style="resize: none">{{ isset($settings) ? $settings->seo_description_articles : "" }}</textarea>

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
                        <hr>
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
                        <hr>
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
                        <hr>
                        <label for="reset_password_image" class="form-label m-t-sm">Varsayılan Parola Sıfırlama Görseli</label>
                        <input type="file" name="reset_password_image" id="reset_password_image" accept="image/png, image/jpeg, image/jpg" class="form-control
                               @if($errors->has("reset_password_image"))
                                   border-danger
                               @endif
                               "
                        >
                        @if($errors->has("reset_password_image"))
                            {{ $errors->first("reset_password_image") }}
                            <br>
                        @endif
                        <div class="form-text m-b-sm">Varsayılan Parola Sıfırlama Görseli Maksimum 2mb olmalıdır</div>
                        @if(isset($settings) && $settings->reset_password_image)
                            <img src="{{ asset($settings->reset_password_image) }}" class="img-fluid" style="max-height: 100px">
                        @endif
                        <hr>
                        <label for="default_comment_profile_image" class="form-label m-t-sm">Varsayılan Kullanıcı Yorum Görseli</label>
                        <input type="file" name="default_comment_profile_image" id="default_comment_profile_image" accept="image/png, image/jpeg, image/jpg" class="form-control
                               @if($errors->has("default_comment_profile_image"))
                                   border-danger
                               @endif
                               "
                        >
                        @if($errors->has("default_comment_profile_image"))
                            {{ $errors->first("default_comment_profile_image") }}
                            <br>
                        @endif
                        <div class="form-text m-b-sm">Varsayılan Kullanıcı Yorum Görseli Maksimum 2mb olmalıdır</div>
                        @if(isset($settings) && $settings->default_comment_profile_image)
                            <img src="{{ asset($settings->default_comment_profile_image) }}" class="img-fluid" style="max-height: 100px">
                        @endif

                        <hr>
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
    <script src="{{ asset("assets/admin/js/custom.js") }}"></script>
    <script>
        $(document).ready(function () {
            $('#btnSave').click(function () {
               let logoCheckStatus = imageCheck($('#logo'));
               let category_default_imageStatus = imageCheck($('#category_default_image'));
               let article_default_imageStatus = imageCheck($('#article_default_image'));
               let default_comment_profile_imageStatus = imageCheck($('#default_comment_profile_image'));
               let reset_password_imageStatus = imageCheck($('#reset_password_image'));

               if (!logoCheckStatus || !category_default_imageStatus || !article_default_imageStatus || !default_comment_profile_imageStatus || !reset_password_imageStatus)
               {
                   return false;
               }
               else
               {
                   $('#settingsForm').submit();
               }
            });
        });
    </script>
@endsection
