@extends('layouts.admin')

@section('title')
    Yorum {{ isset($comment) ? 'Güncelleme' : 'Ekleme' }}
@endsection

@section('css')
@endsection

@section('content')
    <x-bootstrap.card>
        <x-slot:header>
            <h2 class="card-title">Yorum {{ isset($comment) ? 'Güncelleme' : 'Ekleme' }}</h2>
        </x-slot:header>
        <x-slot:body>
            <div class="example-container">
                <div class="example-content">
                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach
                    @endif
                    <form action="{{ isset($comment) ? route('comment.edit', ['id' => $comment->id]) : route('comment.create') }}" method="POST" id="commentForm">
                        @csrf

                        <label for="article_id" class="form-label">Makale Seçimi</label>
                        <select
                            class="form-select form-control form-control-solid-bordered m-b-sm"
                            aria-label="Makale Seçimi"
                            name="article_id"
                            id="article_id"
                        >
                            <option value="{{ null }}">Makale Seçimi</option>
                            @foreach($articles as $article)
                                <option value="{{ $article->id }}" {{ isset($comment) && $comment->article_id === $article->id ? 'selected' : '' }}>
                                    {{ $article->title }}
                                </option>
                            @endforeach
                        </select>

                        <label for="parent_id" class="form-label">Üst Yorum Seçimi</label>
                        <select
                            class="form-select form-control form-control-solid-bordered m-b-sm"
                            aria-label="Üst Yorum Seçimi"
                            name="parent_id"
                            id="parent_id"
                        >
                            <option value="{{ null }}">Üst Yorum Seçimi</option>
                            @foreach($comments as $item)
                                <option value="{{ $item->id }}" {{ isset($comment) && $comment->parent_id === $item->id ? 'selected' : '' }}>
                                    {{ substr($item->comments, 0, 20) }}
                                </option>
                            @endforeach
                        </select>

                        <label for="comments" class="form-label">Yorumunuz</label>
                        <textarea
                            class="form-control form-control-solid-bordered m-b-sm"
                            name="comments"
                            id="comments"
                            cols="30"
                            rows="5"
                            placeholder="Yorumunuz"
                            style="resize: none">{{ isset($comment) ? $comment->comments : '' }}</textarea>

                        <label for="like_count" class="form-label">Beğenme Sayısı</label>
                        <input type="number"
                               class="form-control form-control-solid-bordered m-b-sm"
                               aria-describedby="solidBoderedInputExample"
                               placeholder="Beğenme Sayısı"
                               name="like_count"
                               id="like_count"
                               value="{{ isset($comment) ? $comment->like_count : '' }}"
                        >

                        <label for="unlike_count" class="form-label">Beğenmeme Sayısı</label>
                        <input type="number"
                               class="form-control form-control-solid-bordered m-b-sm"
                               aria-describedby="solidBoderedInputExample"
                               placeholder="Beğenmeme Sayısı"
                               name="unlike_count"
                               id="unlike_count"
                               value="{{ isset($comment) ? $comment->unlike_count : '' }}"
                        >

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="status" {{ isset($comment) && $comment->status ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">
                                Yorum Sitede Görünsün mü?
                            </label>
                        </div>
                        <hr>
                        <div class="col-6 mx-auto mt-2">
                            <button type="submit" class="btn btn-success btn-rounded w-100" id="btnSave">
                                {{ isset($comment) ? 'Güncelleme' : 'Kaydet' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </x-slot:body>
    </x-bootstrap.card>
@endsection

@section('js')
    <script>
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
