@extends('layouts.admin')

@section('title')
    Sosyal Medya {{ isset($socialMedia) ? 'Güncelleme' : 'Ekleme' }}
@endsection

@section('css')
@endsection

@section('content')
    <x-bootstrap.card>
        <x-slot:header>
            <h2 class="card-title">Sosya Medya {{ isset($socialMedia) ? 'Güncelleme' : 'Ekleme' }}</h2>
        </x-slot:header>
        <x-slot:body>
            <div class="example-container">
                <div class="example-content">
                    <form action="{{ isset($socialMedia) ? route('social-media.edit', ['id' => $socialMedia->id]) : route('social-media.create') }}"
                          enctype="multipart/form-data"
                          method="POST"
                            id="socialMediaForm"
                    >
                        @csrf

                        <label for="icon" class="form-label m-t-sm">Sosyal Medya İkonu</label>
                        <input type="file" name="icon" id="icon" accept="image/png, image/jpeg, image/jpg" class="form-control
                               @if($errors->has("icon"))
                                   border-danger
                               @endif
                               "
                        >
                        @if($errors->has("icon"))
                            {{ $errors->first("icon") }}
                            <br>
                        @endif
                        @if(isset($socialMedia) && $socialMedia->icon)
                            <img src="{{ asset($socialMedia->icon) }}" alt="" class="img-fluid" style="max-height: 100px">
                        @endif

                        <label for="name" class="form-label">Sosyal Medya Adı</label>
                        <input type="text"
                               class="form-control form-control-solid-bordered m-b-sm
                               @if($errors->has("name"))
                                   border-danger
                               @endif
                               "
                               aria-describedby="solidBoderedInputExample"
                               placeholder="Sosyal Medya Adı"
                               name="name"
                               id="name"
                               value="{{ isset($socialMedia) ? $socialMedia->name : '' }}"
                               required
                        >
                        @if($errors->has("name"))
                            {{ $errors->first("name") }}
                            <br>
                        @endif

                        <label for="link" class="form-label">Sosyal Medya Linki</label>
                        <input type="text"
                               class="form-control form-control-solid-bordered m-b-sm
                               @if($errors->has("link"))
                                   border-danger
                               @endif
                               "
                               aria-describedby="solidBoderedInputExample"
                               placeholder="Sosyal Medya Linki"
                               name="link"
                               id="link"
                               value="{{ isset($socialMedia) ? $socialMedia->link : '' }}"
                               required
                        >
                        @if($errors->has("link"))
                            {{ $errors->first("link") }}
                            <br>
                        @endif
                        <label for="order" class="form-label">Sıralama</label>
                        <input type="number"
                               class="form-control form-control-solid-bordered m-b-sm
                               @if($errors->has("order"))
                                   border-danger
                               @endif
                               "
                               aria-describedby="solidBoderedInputExample"
                               placeholder="Sıralama"
                               name="order"
                               id="order"
                               value="{{ isset($socialMedia) ? $socialMedia->order : '' }}"
                        >
                        @if($errors->has("order"))
                            {{ $errors->first("order") }}
                            <br>
                        @endif
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="status" {{ isset($socialMedia) && $socialMedia->status ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">
                                Sosyal Medya Sitede Görünsün mü?
                            </label>
                        </div>
                        <hr>
                        <div class="col-6 mx-auto mt-2">
                            <button type="submit" class="btn btn-success btn-rounded w-100" id="btnSave">
                                {{ isset($socialMedia) ? 'Güncelleme' : 'Kaydet' }}
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

                let name = $('#name');
                let link = $('#link');


                console.log(name.val());

                if (name.val().length === 0)
                {
                    let text = "Ad alanı boş geçilemez";
                    errorMessage(name, text, 'nameID')
                }
                else if (name.val().length !== 0){
                    if($('#nameID'))
                    {
                        name.removeClass("border-danger");
                        $('#nameID').remove();
                    }
                    console.log("geldi");
                    $('#socialMediaForm').submit();
                }

                if(link.val() === '' || link.val() === null || link.val() === undefined)
                {
                    let text = "Link alanı boş geçilemez";
                    errorMessage(link, text, 'linkID')
                }
                else if (link.val().length !== 0)
                {
                    if($('#linkID'))
                    {
                        link.removeClass("border-danger");
                        $('#linkID').remove();
                    }
                }
                else
                {
                    console.log("geldi");
                    $('#socialMediaForm').submit();
                }
            });

        });
    </script>
@endsection
