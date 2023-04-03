@extends("layouts.admin")
@section("title")
    Makale Listeleme
@endsection
@section("css")
    <link rel="stylesheet" href="{{ asset("assets2/plugins/select2/css/select2.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets2/plugins/flatpickr/flatpickr.min.css") }}">
    <style>
        .table-hover > tbody > tr:hover {
            --bs-table-hover-bg: transparent;
            background: #363638;
            color: #fff;
        }
    </style>
@endsection

@section("content")
    <x-bootstrap.card>
        <x-slot:header>
            <h2>Makale Listesi</h2>
        </x-slot:header>
        <x-slot:body>
            @if($errors->any())
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger">{{ $error }}</div>
                @endforeach
            @endif
            <form action="">
                <div class="row">
                    <div class="col-6 my-2">
                        <input type="text" class="form-control" placeholder="Title,Body,Slug,Tags" name="search_text" value="{{ request()->get("search_text") }}">
                    </div>
                    <div class="col-6 my-2">
                        <input type="date" class="form-control flatpickr2 m-b-sm" placeholder="Yayınlanma Tarihi" id="publish_date" name="publish_date" value="{{ request()->get("publish_date") }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-3 my-2">
                        <input type="number" class="form-control" placeholder="Min View Count" name="min_view_count" value="{{ request()->get("min_view_count") }}">
                    </div>
                    <div class="col-3 my-2">
                        <input type="number" class="form-control" placeholder="Max View Count" name="max_view_count" value="{{ request()->get("max_view_count") }}">
                    </div>
                    <div class="col-3 my-2">
                        <input type="number" class="form-control" placeholder="Min Like Count" name="min_like_count" value="{{ request()->get("min_like_count") }}">
                    </div>
                    <div class="col-3 my-2">
                        <input type="number" class="form-control" placeholder="Max Like Count" name="max_like_count" value="{{ request()->get("max_like_count") }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 my-2">
                        <select class="form-select form-control" name="user_id">
                            <option value="{{ null }}">Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request()->get("user_id") == $user->id ? "selected" : "" }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4 my-2">
                        <select class="form-select form-control" name="category_id">
                            <option value="{{ null }}">Kategory Seçin</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request()->get("category_id") == $category->id ? "selected" : "" }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4 my-2">
                        <select class="form-select form-control" name="status" aria-label="Status">
                            <option value="{{ null }}">Status</option>
                            <option value="0" {{ request()->get("status") === "0" ? "selected" : "" }}>Pasif</option>
                            <option value="1" {{ request()->get("status") === "1" ? "selected" : "" }}>Aktif</option>
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
                    <th scope="col">Image</th>
                    <th scope="col">Title</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Status</th>
                    <th scope="col">Body</th>
                    <th scope="col">Tags</th>
                    <th scope="col">View Count</th>
                    <th scope="col">Like Count</th>
                    <th scope="col">Publish Date</th>
                    <th scope="col">Category</th>
                    <th scope="col">User</th>
                    <th scope="col">Actions</th>
                </x-slot:columns>
                <x-slot:rows>
                    @foreach($articles as $article)
                        <tr id="row-{{ $article->id }}">
                            <td>
                                @if(!empty($article->image))
                                    <img src="{{asset($article->image)}}" class="img-fluid" style="max-height: 100px">
                                @endif

                            </td>
                            <td>{{ $article->title }}</td>
                            <td>{{ $article->slug }}</td>
                            <td>
                                @if($article->status)
                                    <a href="javascript:void(0)" data-id="{{ $article->id }}" class="btn btn-success btn-sm btnChangeStatus">Aktif</a>
                                @else
                                    <a href="javascript:void(0)" data-id="{{ $article->id }}" class="btn btn-danger btn-sm btnChangeStatus">Pasif</a>
                                @endif
                            </td>
                            <td>
                                <span data-toggle="tooltip" data-placement="bottom" title="{{ substr($article->body, 0, 300) }}">
                                    {!! substr($article->body, 0, 20) !!}
                                </span>
                            </td>
{{--                            <td>{{ $article->tags }}</td>--}}
                            <td>{{ $article->view_count }}</td>
                            <td>{{ $article->like_count }}</td>
                            <td>{{ \Carbon\Carbon::parse($article->publish_date)->translatedFormat("d F Y H:i:s")}}</td>
                            <td>{{ $article->category->name }}</td>
                            <td>{{ $article->user->name }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('article.edit', ['id' => $article->id]) }}"
                                       class="btn btn-warning btn-sm">
                                        <i class="material-icons ms-0">edit</i>
                                    </a>
                                    <a href="javascript:void(0)"
                                       class="btn btn-danger btn-sm btnDelete"
                                       data-title="{{ $article->title }}"
                                       data-id="{{ $article->id }}">
                                        <i class="material-icons ms-0">delete</i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-slot:rows>
            </x-bootstrap.table>
            <div class="d-flex justify-content-center my-2">
                {{ $articles->appends(request()->all())->onEachSide(2)->links() }}
            </div>
        </x-slot:body>
    </x-bootstrap.card>
    <form action="" method="POST" id="statusChangeForm">
        @csrf
        <input type="hidden" name="id" id="inputStatus" value="">
    </form>
@endsection

@section("js")
    <script src="{{ asset("assets2/plugins/select2/js/select2.full.min.js") }}"></script>
    <script src="{{ asset("assets2/js/pages/select2.js") }}"></script>
    <script src="{{ asset("assets2/plugins/flatpickr/flatpickr.js") }}"></script>
    <script src="{{ asset("assets2/js/pages/datepickers.js") }}"></script>
    <script src="{{ asset("assets2/admin/plugins/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset("assets2/admin/plugins/bootstrap/js/popper.min.js") }}"></script>
    <script>
        $(document).ready(function () {

            $('.btnChangeStatus').click(function () {
                let articleID = $(this).data('id');
                //console.log($(this).data('id'));
                $('#inputStatus').val(articleID);

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
                        $('#statusChangeForm').attr("action", "{{ route('article.change-status') }}");
                        $('#statusChangeForm').submit();
                    }
                    else if (result.isDenied)
                    {
                        Swal.fire({
                            title: "Bilgi",
                            text: "Herhangi bir işlem yapılmadı",
                            confirmButtonText: 'Tamam',
                            icon: "info",
                        });
                    }
                });
            });


            $('.btnDelete').click(function () {
                let articleID = $(this).data('id');
                let articleTitle = $(this).data('title');
                $('#inputStatus').val(articleID);

                Swal.fire({
                    title: articleTitle + ' i Silmek istediğinize emin misiniz?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Evet',
                    denyButtonText: `Hayır`,
                    cancelButtonText: "İptal"
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed)
                    {
                        $('#statusChangeForm').attr("action", "{{ route('article.delete') }}");
                        $('#statusChangeForm').submit();
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
    <script>
        $("#publish_date").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d",
        });
        const popover = new bootstrap.Popover('.example-popover', {
            container: 'body'
        })
    </script>
@endsection

