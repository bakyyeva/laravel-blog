@extends("layouts.admin")
@section("title")
    Makale {{ isset($article) ? "Güncelleme" : "Ekleme" }}
@endsection
@section("css")
    <link rel="stylesheet" href="{{ asset("assets/plugins/summernote/summernote-lite.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/plugins/flatpickr/flatpickr.min.css") }}">
@endsection

@section("content")
    <x-bootstrap.card>
        <x-slot:header>
            <h2 class="card-title"> Makale {{ isset($article) ? "Güncelleme" : "Ekleme" }} </h2>
        </x-slot:header>
        <x-slot:body>
            <p class="card-description">Makale {{ isset($article) ? "Güncelleme" : "Ekleme" }}  açıklaması</p>
            <div class="example-container">
                <div class="example-content">
                    <x-errors.display-error />
                    <form  action="{{ isset($article) ? route("article.edit", ["id" => $article->id]) : route("article.create") }}"
                           method="POST"
                           enctype="multipart/form-data"
                           id="articleForm">
                        @csrf
                        <label for="title" class="form-label">Makale Başlığı</label>
                        <input type="text"
                               class="form-control form-control-solid-bordered m-b-sm"
                               aria-describedby="solidBoderedInputExample"
                               placeholder="Makale Başlığı"
                               name="title"
                               id="title"
                               value="{{ isset($article) ? $article->title : "" }}"
                               required
                        >
                        <label for="slug" class="form-label">Makale Slug</label>
                        <input type="text"
                               class="form-control form-control-solid-bordered m-b-sm"
                               aria-describedby="solidBoderedInputExample"
                               placeholder="Makale Slug"
                               name="slug"
                               id="slug"
                               value="{{ isset($article) ? $article->slug : "" }}"
                        >
                        <label for="summernote" class="form-label">İçerik</label>
                        <textarea name="body" id="summernote" class="m-b-sm">{!! isset($article) ? $article->body : "" !!}</textarea>

                        <label for="tags" class="form-label">Etiketler</label>
                        <input type="text"
                               class="form-control form-control-solid-bordered m-b-sm"
                               aria-describedby="solidBoderedInputExample"
                               placeholder="Etiket"
                               name="tags"
                               id="tags"
                               value="{{ isset($article) ? $article->tags : "" }}"
                        >
                        <label for="view_count" class="form-label">Makale Görüntüleme Sayısı</label>
                        <input type="number"
                               class="form-control form-control-solid-bordered m-b-sm"
                               aria-describedby="solidBoderedInputExample"
                               placeholder="Görüntülenme Sayısı"
                               name="view_count"
                               id="view_count"
                               value="{{ isset($article) ? $article->view_count : "" }}"
                        >
                        <label for="like_count" class="form-label">Makale Beğenme Sayısı</label>
                        <input type="number"
                               class="form-control form-control-solid-bordered m-b-sm"
                               aria-describedby="solidBoderedInputExample"
                               placeholder="Beğenme Sayısı"
                               name="like_count"
                               id="like_count"
                               value="{{ isset($article) ? $article->like_count : "" }}"
                        >
                        <label for="read_time" class="form-label">Okuma Süresi</label>
                        <input type="number"
                               class="form-control form-control-solid-bordered m-b-sm"
                               aria-describedby="solidBoderedInputExample"
                               placeholder="Okuma süresi"
                               name="read_time"
                               id="read_time"
                               value="{{ isset($article) ? $article->read_time : "" }}"
                        >
                        <label for="publish_date" class="form-label">Makale Yayınlanma Tarihi</label>
                        <input type="text"
                               class="form-control form-control-solid-bordered m-b-sm"
                               aria-describedby="solidBoderedInputExample"
                               placeholder="Yayınlanma tarihi"
                               name="publish_date"
                               id="publish_date"
                               value="{{  isset($article) ? $article->publish_date : "" }}"
                        >
                        <label for="seo_keywords" class="form-label">Makale Seo Keywords</label>
                        <textarea
                            class="form-control form-control-solid-bordered m-b-sm"
                            name="seo_keywords"
                            id="seo_keywords"
                            cols="30"
                            rows="5"
                            placeholder="Seo Keywords"
                            style="resize: none">{{ isset($article) ? $article->seo_keywords : "" }}</textarea>
                        <label for="seo_description" class="form-label">Makale Seo Description</label>
                        <textarea
                            class="form-control form-control-solid-bordered m-b-sm"
                            name="seo_description"
                            id="seo_description"
                            cols="30"
                            rows="5"
                            placeholder="Seo Description"
                            style="resize: none">{{ isset($article) ? $article->soe_description : "" }}</textarea>
                        <label for="category_id" class="form-label">Kategori Seçimi</label>
                        <select
                            class="form-select form-control form-control-solid-bordered m-b-sm"
                            aria-label="Kategori Seçimi"
                            name="category_id"
                            id="category_id"
                            required
                        >
                            <option value="{{ null }}">Kategori Seçimi</option>
                            @foreach($categories as $item)
                                <option value="{{ $item->id }}" {{ isset($article) && $article->category_id == $item->id ? 'selected' : ""}}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-check">
                            <input type="checkbox"  class="form-check-input" name="status" value="1" id="status"
                                {{ isset($article) && $article->status ? 'checked' :  "" }}>
                            <label class="form-check-label" for="status">
                                Makale Sitede Görünsün mü?
                            </label>
                        </div>
                        <div class="row">
                            <label for="image" class="form-label m-t-sm">Makale Görseli</label>
                            <div class="col-6">
                                <a href="javascript:void(0)" id="articleImage" data-input="article-image" data-preview="articleImg" class="btn btn-primary w-100">
                                    Makale Görseli
                                </a>
                                <input type="hidden" name="image" id="article-image" value="{{ isset($article) ? $article->image : '' }}">
                            </div>
                            @if(isset($article) && $article->image)
                            <div class="col-6" id="articleImg">
                                <img src="{{ $article->image }}" height="100">
                            </div>
                            @endif
                        </div>
                        <hr>
                        <div class="col-6 mx-auto mt-2">
                            <button type="button" class="btn btn-success btn-rounded w-100" id="btnSave">
                                {{ isset($article) ? "Güncelle" : "Kaydet" }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </x-slot:body>
    </x-bootstrap.card>
@endsection

@section("js")
    <script src="{{ asset("assets/plugins/flatpickr/flatpickr.js") }}"></script>
    <script src="{{ asset("assets/js/pages/datepickers.js") }}"></script>
    <script src="{{ asset("assets/admin/plugins/summernote/summernote-lite.min.js") }}"></script>
    <script src="{{ asset("assets/admin/js/pages/text-editor.js") }}"></script>
    <script src="{{ asset("/vendor/laravel-filemanager/js/stand-alone-button.js") }}"></script>
    <script>

        $('#articleImage').filemanager();

        let title = $('#title');
        let tags = $('#tags');
        let categoryID = $('#category_id');

        $(document).ready(function () {

            $('#btnSave').click(function () {
                if(title.val().trim() === '' || title.val().trim() == null )
                {
                    Swal.fire({
                        title: "Uyarı",
                        text: "Makale Başlığı boş geçilemez.",
                        confirmButtonText: 'Tamam',
                        icon: "info",
                    });
                }
                else if(tags.val().trim().length < 3)
                {
                    Swal.fire({
                        title: "Uyarı",
                        text: "Etiket alanı boş geçilemez.",
                        confirmButtonText: 'Tamam',
                        icon: "info",
                    });
                }
                else if(categoryID.val().trim() === '' || categoryID.val().trim() == null)
                {
                    Swal.fire({
                        title: "Uyarı",
                        text: "Kategori seçin.",
                        confirmButtonText: 'Tamam',
                        icon: "info",
                    });
                }
                else {
                    $('#articleForm').submit();
                }
            });

        });
    </script>
@endsection
