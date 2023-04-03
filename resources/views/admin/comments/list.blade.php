@extends('layouts.admin')

@section('title')
    Yorum Listesi
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset("assets2/plugins/select2/css/select2.min.css") }}">
    <style>
        .table-hover > tbody > tr:hover {
            --bs-table-hover-bg: transparent;
            background: #363638;
            color: #fff;
        }
    </style>
@endsection

@section('content')
    <x-bootstrap.card>
        <x-slot:header>
            <h2>Yorum Listesi</h2>
        </x-slot:header>
        <x-slot:body>
            @if($errors->any())
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger">{{ $error }}</div>
                @endforeach
            @endif
            <form action="">
                <div class="row">
                    <div class="col-3 my-2">
                        <input type="number" class="form-control" placeholder="Min Like Count" name="min_like_count" value="{{ request()->get("min_like_count") }}">
                    </div>
                    <div class="col-3 my-2">
                        <input type="number" class="form-control" placeholder="Max Like Count" name="max_like_count" value="{{ request()->get("max_like_count") }}">
                    </div>
                    <div class="col-3 my-2">
                        <input type="number" class="form-control" placeholder="Min Unlike Count" name="min_unlike_count" value="{{ request()->get("min_unlike_count") }}">
                    </div>
                    <div class="col-3 my-2">
                        <input type="number" class="form-control" placeholder="Max Unlike Count" name="max_unlike_count" value="{{ request()->get("max_unlike_count") }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 my-2">
                        <input type="text" class="form-control" placeholder="Yorum" name="comment" value="{{ request()->get("comment") }}">
                    </div>
                    <div class="col-6 my-2">
                        <select class="form-select form-control" name="status" aria-label="Status">
                            <option value="{{ null }}">Status</option>
                            <option value="0" {{ request()->get("status") === "0" ? "selected" : "" }}>Pasif</option>
                            <option value="1" {{ request()->get("status") === "1" ? "selected" : "" }}>Aktif</option>
                        </select>
                    </div>
                </div>
             <div class="row">
                    <div class="col-6 my-2">
                        <select class="form-select form-control" name="article_id">
                            <option value="{{ null }}">Makale Seçin</option>
                            @foreach($articles as $article)
                                <option value="{{ $article->id }}" {{ request()->get("article_id") == $article->id ? "selected" : "" }}>
                                    {{ $article->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 my-2">
                        <select class="form-select form-control" name="user_id">
                            <option value="{{ null }}">Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request()->get("user_id") == $user->id ? "selected" : "" }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <hr>
                    <div class="col-6 mb-2 d-flex">
                        <button class="btn btn-primary w-50 me-4" type="submit">Filtrele</button>
                        <button class="btn btn-warning w-50" type="submit" id="btnClearFilter">Filtreyi Temizle</button>
                    </div>
                    <hr>
             </div>
            </form>
            <x-bootstrap.table
                :class="'table-striped table-hover table-responsive'"
                :is-responsive="1"
            >
                <x-slot:columns>
                    <th scope="col">User</th>
                    <th scope="col">Article</th>
                    <th scope="col">Status</th>
                    <th scope="col">Yorum</th>
                    <th scope="col">Üst Yorum</th>
                    <th scope="col">Beğenme Sayısı</th>
                    <th scope="col">Beğenmeme Sayısı</th>
                    <th scope="col">Actions</th>
                </x-slot:columns>
                <x-slot:rows>
                    @foreach($list as $item)
                        <tr id="row-{{ $item->id }}">
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->article->title }}</td>
                            <td>
                                @if($item->status)
                                    <a href="javascript:void(0)" data-id="{{ $item->id }}" class="btn btn-success btn-sm btnChangeStatus">Aktif</a>
                                @else
                                    <a href="javascript:void(0)" data-id="{{ $item->id }}" class="btn btn-danger btn-sm btnChangeStatus">Pasif</a>
                                @endif
                            </td>
                            <td>{{ substr($item->comments, 0, 20) }}</td>
                            <td>{{ substr($item->parentComment?->comments, 0, 20) }}</td>
                            <td>{{ $item->like_count}}</td>
                            <td>{{ $item->unlike_count}}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('comment.edit', ['id' => $item->id]) }}"
                                       class="btn btn-warning btn-sm">
                                        <i class="material-icons ms-0">edit</i>
                                    </a>
                                    <a href="javascript:void(0)"
                                       class="btn btn-danger btn-sm btnDelete"
                                       data-id="{{ $item->id }}">
                                        <i class="material-icons ms-0">delete</i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-slot:rows>
            </x-bootstrap.table>
            <div class="d-flex justify-content-center my-2">
                {{ $list->appends(request()->all())->onEachSide(2)->links() }}
            </div>
        </x-slot:body>
    </x-bootstrap.card>
@endsection

@section('js')
    <script src="{{ asset("assets2/plugins/select2/js/select2.full.min.js") }}"></script>
    <script src="{{ asset("assets2/js/pages/select2.js") }}"></script>
    <script>
        $(document).ready(function () {

            $('.btnChangeStatus').click(function () {
                let commentID = $(this).data('id');
                let self = $(this);
                Swal.fire({
                    title: 'Status değiştirmek istediğinize emin misiniz?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Evet',
                    denyButtonText: `Hayır`,
                    cancelButtonText: "İptal"
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed)
                    {
                        $.ajax({
                            method: "POST",
                            url: "{{ route('comment.change-status') }}",
                            data: {
                                commentID : commentID
                            },
                            async:false,
                            success: function (data) {
                                if(data.comment_status)
                                {
                                    self.removeClass("btn-danger");
                                    self.addClass("btn-success");
                                    self.text("Aktif");
                                }
                                else
                                {
                                    self.removeClass("btn-success");
                                    self.addClass("btn-danger");
                                    self.text("Pasif");
                                }

                                Swal.fire({
                                    title: "Başarılı",
                                    text: "Status Güncellendi",
                                    confirmButtonText: 'Tamam',
                                    icon: "success"
                                });
                            },
                            error: function (){
                                console.log("hata geldi");
                            }
                        })
                    }
                    else if (result.isDenied)
                    {
                        Swal.fire({
                            title: "Bilgi",
                            text: "Herhangi bir işlem yapılmadı",
                            confirmButtonText: 'Tamam',
                            icon: "info"
                        });
                    }
                })

            });

            $('.btnDelete').click(function () {
                let commentID = $(this).data('id');

                Swal.fire({
                    title: "Yorumu  silmek istediğinize emin misiniz?",
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Evet',
                    denyButtonText: `Hayır`,
                    cancelButtonText: "İptal"
                }).then((result) => {
                    if (result.isConfirmed)
                    {
                        $.ajax({
                            method: "POST",
                            url: "{{ route('comment.delete') }}",
                            data: {
                                "_method": "DELETE",
                                commentID : commentID
                            },
                            async:false,
                            success: function (data) {
                                $('#row-' + commentID).remove();
                                Swal.fire({
                                    title: "Başarılı",
                                    text: "Yorum Silindi",
                                    confirmButtonText: 'Tamam',
                                    icon: "success"
                                });
                            },
                            error: function (){
                                console.log("hata geldi");
                            }
                        })
                    }
                    else if (result.isDenied)
                    {
                        Swal.fire({
                            title: "Bilgi",
                            text: "Herhangi bir işlem yapılmadı",
                            confirmButtonText: 'Tamam',
                            icon: "info"
                        });
                    }
                })
            });


            $('#btnClearFilter').click(function () {
                let inputList = $('.form-control');
                console.log(typeof inputList);
                console.log(inputList);
                inputList.each(function (index, value) {
                    if(value['value'])
                    {
                        value['value'] = '';
                    }

                });
            });

        });
    </script>
@endsection
