@extends("layouts.admin")
@section("title")
    @if($page === 'commenList')
        Yorum Listesi
    @else
        Onay Bekleyen Yorum Listesi
    @endif
@endsection
@section("css")
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2/css/select2.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/plugins/flatpickr/flatpickr.min.css") }}">
    <style>
        .table-hover > tbody > tr:hover {
            --bs-table-hover-bg: transparent;
            background: #363638;
            color: #fff;
        }
        table td{
            vertical-align: middle;
        }
    </style>
@endsection

@section("content")
    <x-bootstrap.card>
        <x-slot:header>
            <h2>
                @if($page === 'commenList')
                    Yorum Listesi
                @else
                    Onay Bekleyen Yorum Listesi
                @endif
            </h2>
        </x-slot:header>
        <x-slot:body>
            @if($errors->any())
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger">{{ $error }}</div>
                @endforeach
            @endif
            <form action="{{ $page == "commentList" ? route('article.comment.list') : route('article.pending-approval') }}" method="GET" id="formFilter">
                <div class="row">
                    <div class="col-4 my-2">
                        <input type="text" class="form-control" placeholder="Comment, Name, Email" name="search_text" value="{{ request()->get("search_text") }}">
                    </div>
                    <div class="col-4 my-2">
                        <input type="date" class="form-control flatpickr2 m-b-sm" placeholder="Yorum Tarihi" id="created_at" name="created_at" value="{{ request()->get("created_at") }}">
                    </div>
                    <div class="col-4 my-2">
                        <select class="form-select" name="user_id">
                            <option value="{{ null }}">Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request()->get("user_id") == $user->id ? "selected" : "" }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if($page === 'commenList')
                        <div class="col-4 my-2">
                            <select class="form-select" name="status" aria-label="Status">
                                <option value="{{ null }}">Status</option>
                                <option value="0" {{ request()->get("status") === "0" ? "selected" : "" }}>Pasif</option>
                                <option value="1" {{ request()->get("status") === "1" ? "selected" : "" }}>Aktif</option>
                            </select>
                        </div>
                    @endif
                    <hr>
                    <div class="col-6 mb-2 d-flex">
                        <button class="btn btn-primary w-50 me-4" type="submit">Filtrele</button>
                        <button class="btn btn-warning w-50" type="button" id="btnClearFilter">Filtreyi Temizle</button>
                    </div>
                    <hr>
                </div>
            </form>
            <x-bootstrap.table
                :class="'table-striped table-hover table-responsive'"
                :is-responsive="1"
            >
                <x-slot:columns>
                    <th scope="col">Makale Link</th>
                    <th scope="col">User Name</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    @if(isset($page))
                        <th scope="col">Approve Status</th>
                    @else
                        <th scope="col">Status</th>
                    @endif
                    <th scope="col">Comment</th>
                    <th scope="col">Created Date</th>
                    <th scope="col">Actions</th>
                </x-slot:columns>
                <x-slot:rows>
                    @foreach($comments as $comment)
                        <tr id="row-{{ $comment->id }}">
                            @isset($comment->article)
                            <td>
                                <a href="{{ route('front.articleDetail', [
                                    'user' => $comment->article?->user->username,
                                    'article' => $comment->article?->slug]) }}" target="_blank">
                                    <span class="material-icons-outlined">visibility</span>
                                </a>
                            </td>
                            @else
                                <td></td>
                            @endisset
                            <td>{{ $comment->user?->name}}</td>
                            <td>{{ $comment->name}}</td>
                            <td>{{ $comment->email}}</td>
                            <td>
                                @if(isset($page))
                                    @if($comment->approve_status)
                                        <a href="javascript:void(0)" data-id="{{ $comment->id }}" class="btn btn-success btn-sm btnChangeStatus">Aktif</a>
                                    @else
                                        <a href="javascript:void(0)" data-id="{{ $comment->id }}" class="btn btn-danger btn-sm btnChangeStatus">Pasif</a>
                                    @endif
                                @else
                                    @if($comment->status)
                                        <a href="javascript:void(0)" data-id="{{ $comment->id }}" class="btn btn-success btn-sm btnChangeStatus">Aktif</a>
                                    @else
                                        <a href="javascript:void(0)" data-id="{{ $comment->id }}" class="btn btn-danger btn-sm btnChangeStatus">Pasif</a>
                                    @endif
                                @endif
                            </td>
                            <td>
                                 <span data-bs-container="body" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ substr( $comment->comments , 0, 200) }}">
                                    {{ substr( $comment->comments, 0, 10 ) }}
                                </span>
                                <button type="button" class="btn btn-primary lookComment btn-sm p-0 px-2" data-comment="{{ $comment->comments }}" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <span class="material-icons-outlined" style="line-height: unset; font-size: 20px">visibility</span>
                                </button>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($comment->created_at)->translatedFormat("d F Y H:i:s")}}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="javascript:void(0)"
                                       class="btn btn-danger btn-sm btnDelete"
                                       data-id="{{ $comment->id }}">
                                        <i class="material-icons ms-0">delete</i>
                                    </a>
                                    @if($comment->deleted_at)
                                        <a href="javascript:void(0)"
                                           class="btn btn-primary btn-sm btnRestore"
                                           data-id="{{ $comment->id }}"
                                           title="Geri al"
                                        >
                                            <i class="material-icons ms-0">undo</i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-slot:rows>
            </x-bootstrap.table>
            <div class="d-flex justify-content-center my-2">
                {{ $comments->appends(request()->all())->onEachSide(2)->links() }}
            </div>
        </x-slot:body>
    </x-bootstrap.card>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yorum</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("js")
    <script src="{{ asset("assets/plugins/select2/js/select2.full.min.js") }}"></script>
    <script src="{{ asset("assets/js/pages/select2.js") }}"></script>
    <script src="{{ asset("assets/plugins/flatpickr/flatpickr.js") }}"></script>
{{--    <script src="{{ asset("assets/js/pages/datepickers.js") }}"></script>--}}
    <script src="{{ asset("assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset("assets/admin/plugins/bootstrap/js/popper.min.js") }}"></script>
    <script>
        $(document).ready(function () {

            @if(isset($page))
            $('.btnChangeStatus').click(function () {
                let id = $(this).data('id');
                let self = $(this);
                Swal.fire({
                    title: 'Onaylamak istediğinize emin misiniz?',
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
                            url: "{{ route('article.pending-approval.change-status') }}",
                            data: {
                                id : id,
                                page: "{{ $page }}"
                            },
                            async:false,
                            success: function (data) {
                                $('#row-' + id).remove();
                                Swal.fire({
                                    title: "Bilgi",
                                    text: "Onaylanmıştır",
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
                            title: "Başarılı",
                            text: "Herhangi bir işlem yapılmadı",
                            confirmButtonText: 'Tamam',
                            icon: "info"
                        });
                    }
                })

            });
            @else
            $('.btnChangeStatus').click(function () {
                let id = $(this).data('id');
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
                            url: "{{ route('article.pending-approval.change-status') }}",
                            data: {
                                id : id
                            },
                            async:false,
                            success: function (data) {
                                if(data.comment_status)
                                {
                                    self.removeClass('btn-danger');
                                    self.addClass('btn-success');
                                    self.text('Aktif');
                                    Swal.fire({
                                        title: "Başarılı",
                                        text: "Yorumun durumu aktif olarak güncellendi",
                                        confirmButtonText: 'Tamam',
                                        icon: "success"
                                    });
                                }
                                else
                                {
                                    self.removeClass('btn-success');
                                    self.addClass('btn-danger');
                                    self.text('Pasif');
                                    Swal.fire({
                                        title: "Başarılı",
                                        text: "Yorumun durumu pasif olarak güncellendi",
                                        confirmButtonText: 'Tamam',
                                        icon: "success"
                                    });
                                }
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
            @endif


            $('.btnDelete').click(function () {
                let commentID = $(this).data('id');

                Swal.fire({
                    title: commentID + " 'li Yorumu silmek istediğinize emin misiniz?",
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
                            url: "{{ route('article.pending-approval.delete') }}",
                            data: {
                                "_method": "DELETE",
                                id : commentID
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

            $('.btnRestore').click(function () {
                let commentID = $(this).data('id');
                let self = $(this);
                Swal.fire({
                    title: commentID + " ID'li Yorumu Geri almak istediğinize emin misiniz?",
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
                            url: "{{ route('article.comment.restore') }}",
                            data: {
                                id : commentID
                            },
                            async:false,
                            success: function (data) {
                                self.remove();
                                Swal.fire({
                                    title: "Başarılı",
                                    text: "Yorum Yayına Geri Alındı",
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

            $(".lookComment").click(function (){
                let comment = $(this).data("comment");
                $('#modalBody').text(comment);
            });


        });
    </script>
    <script>
        $("#created_at").flatpickr({
            dateFormat: "Y-m-d",
        });
        const popover = new bootstrap.Popover('.example-popover', {
            container: 'body'
        })
    </script>
@endsection

