@extends('layouts.admin')

@section('title')
    Kategori {{ isset($category) ? 'Güncelleme' : 'Ekleme' }}
@endsection

@section('css')
@endsection

@section('content')
    <x-bootstrap.card>
        <x-slot:header>
            <h2 class="card-title">Kategori {{ isset($category) ? 'Güncelleme' : 'Ekleme' }}</h2>
        </x-slot:header>
        <x-slot:body>
            <div class="example-container">
                <div class="example-content">
                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach
                    @endif
                    <form action="{{ isset($category) ? route('category.edit', ['id' => $category->id]) : route('category.create') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          id="categoryForm">
                        @csrf
                        <label for="color">Kategorinin Rengi</label>
                        <input type="color" name="color" id="color" class="m-b-sm" value="{{ isset($category) ? $category->color : ""}}">
                        <br>
                        <label for="name" class="form-label">Kategori Adı</label>
                        <input type="text"
                               class="form-control form-control-solid-bordered m-b-sm"
                               aria-describedby="solidBoderedInputExample"
                               placeholder="Kategori Adı"
                               name="name"
                               id="name"
                               value="{{ isset($category) ? $category->name : '' }}"
                               required
                        >
                        <label for="slug" class="form-label">Kategori Slug</label>
                        <input type="text"
                               class="form-control form-control-solid-bordered m-b-sm"
                               aria-describedby="solidBoderedInputExample"
                               placeholder="Kategori Slug"
                               name="slug"
                               id="slug"
                               value="{{ isset($category) ? $category->slug : '' }}"
                        >
                        <label for="description" class="form-label">Kategori Açıklaması</label>
                        <textarea
                            class="form-control form-control-solid-bordered m-b-sm"
                            name="description"
                            id="description"
                            cols="30"
                            rows="5"
                            placeholder="Kategori Açıklama"
                            style="resize: none">{{ isset($category) ? $category->description : '' }}</textarea>
                        <label for="order" class="form-label">Sıralama</label>
                        <input type="number"
                               class="form-control form-control-solid-bordered m-b-sm"
                               aria-describedby="solidBoderedInputExample"
                               placeholder="Sıralama"
                               name="order"
                               id="order"
                               value="{{ isset($category) ? $category->order : '' }}"
                        >
                        <label for="parent_id" class="form-label">Üst Kategori Seçimi</label>
                        <select
                            class="form-select form-control form-control-solid-bordered m-b-sm"
                            aria-label="Üst Kategori Seçimi"
                            name="parent_id"
                            id="parent_id"
                        >
                            <option value="{{ null }}">Üst Kategori Seçimi</option>
                            @foreach($categories as $item)
                                <option value="{{ $item->id }}" {{ isset($category) && $category->parent_id === $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                        <label for="seo_keywords" class="form-label">Kategori Seo Keywords</label>
                        <textarea
                            class="form-control form-control-solid-bordered m-b-sm"
                            name="seo_keywords"
                            id="seo_keywords"
                            cols="30"
                            rows="5"
                            placeholder="Seo Keywords"
                            style="resize: none">{{ isset($category) ? $category->seo_keywords : '' }}</textarea>
                        <label for="seo_description" class="form-label">Kategori Seo Description</label>
                        <textarea
                            class="form-control form-control-solid-bordered m-b-sm"
                            name="seo_description"
                            id="seo_description"
                            cols="30"
                            rows="5"
                            placeholder="Seo Description"
                            style="resize: none">{{ isset($category) ? $category->seo_description : '' }}</textarea>
{{--                        <label for="image" class="form-label m-t-sm">Kategori Görseli</label>--}}
{{--                        <input type="file" name="image" id="image" accept="image/png, image/jpeg, image/jpg" class="form-control--}}
{{--                               @if($errors->has("image"))--}}
{{--                                   border-danger--}}
{{--                               @endif--}}
{{--                               "--}}
{{--                        >--}}
{{--                        @if($errors->has("image"))--}}
{{--                            {{ $errors->first("image") }}--}}
{{--                            <br>--}}
{{--                        @endif--}}
{{--                        <div class="form-text m-b-sm">Kategori Görseli Maksimum 2mb olmalıdır</div>--}}
{{--                        @if(isset($category) && $category->image)--}}
{{--                            <img src="{{ asset($category->image) }}" alt="" class="img-fluid" style="max-height: 100px">--}}
{{--                        @endif--}}
                        <label for="image" class="form-label m-t-sm">Kategori Görseli</label>
                        <div class="input-group">
                           <span class="input-group-btn">
                             <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                               <i class="fa fa-picture-o"></i> Choose File
                             </a>
                           </span>
                            <input id="thumbnail" class="form-control ms-1" type="text" name="image">
                        </div>
                        <img id="holder" style="margin-top:15px;max-height:100px;">
                        {{-- !empty($article->image)--}}
                        @if(isset($category) && $category->image)
                            <img src="{{asset($category->image)}}" class="img-fluid" style="max-height: 100px">
                        @endif
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="status" {{ isset($category) && $category->status ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">
                                Kategori Sitede Görünsün mü?
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="feature_status" value="1" id="feature_status" {{ isset($category) && $category->feature_status ? 'checked' : '' }}>
                            <label class="form-check-label" for="feature_status">
                                Kategori Anasayfada Öne Çıkarılsın mı?
                            </label>
                        </div>
                        <hr>
                        <div class="col-6 mx-auto mt-2">
                            <button type="button" class="btn btn-success btn-rounded w-100" id="btnSave">
                                {{ isset($category) ? 'Güncelleme' : 'Kaydet' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </x-slot:body>
    </x-bootstrap.card>
@endsection

@section('js')
    <script src="{{ asset("/vendor/laravel-filemanager/js/stand-alone-button.js") }}"></script>
    <script>

        $('#lfm').filemanager('image');

        $(document).ready(function () {

            function errorAlert($input, text, $id) {
                $input.addClass("border-danger");
                let pElement = document.createElement("p");
                pElement.innerText = text;
                pElement.setAttribute('id', $id);
                $input.after(pElement);
            }

            $('#btnSave').click(function () {
               let categoryName = $('#name');
               if(categoryName.val().length === 0)
               {
                   let text = "Name alanı boş geçilemez";
                   errorAlert(categoryName, text, 'nameID');
               }
               else if(categoryName.val().length !== 0)
               {
                   if($('#nameID'))
                   {
                       categoryName.removeClass('border-danger');
                       $('#nameID').remove();
                   }

                   $('#categoryForm').submit();
               }
            });
        });
    </script>
@endsection
