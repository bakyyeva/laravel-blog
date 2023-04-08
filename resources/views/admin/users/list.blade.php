@extends('layouts.admin')

@section('title')
    Kullanıcı Listesi
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2/css/select2.min.css") }}">
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

@section('content')
    <x-bootstrap.card>
        <x-slot:header>
            <h5 class="card-title">Kullanıcı Listesi </h5>
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
                        <input type="text" class="form-control" placeholder="Name, Username, Email" name="search_text" value="{{ request()->get("search_text") }}">
                    </div>
                    <div class="col-3 my-2">
                        <select class="form-select form-control" name="status" aria-label="Status">
                            <option value="{{ null }}">Status</option>
                            <option value="0" {{ request()->get("status") === "0" ? "selected" : "" }}>Pasif</option>
                            <option value="1" {{ request()->get("status") === "1" ? "selected" : "" }}>Aktif</option>
                        </select>
                    </div>
                    <div class="col-3 my-2">
                        <select class="form-select form-control" name="is_admin" aria-label="Is Admin">
                            <option value="{{ null }}">User Role</option>
                            <option value="0" {{ request()->get("is_admin") === "0" ? "selected" : "" }}>User</option>
                            <option value="1" {{ request()->get("is_admin") === "1" ? "selected" : "" }}>Admin</option>
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
                    <th scope="col">Name</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Status</th>
                    <th scope="col">Is Admin</th>
                    <th scope="col">Actions</th>
                </x-slot:columns>
                <x-slot:rows>
                @foreach($users as $user)
                        <tr id="row-{{ $user->id }}">
                            <td>
                                @if(!empty($user->image))
                                    <img src="{{asset($user->image)}}" height="60">
                                @endif
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->status)
                                    <a href="javascript:void(0)" data-id="{{ $user->id }}" class="btn btn-success btn-sm btnChangeStatus">Aktif</a>
                                @else
                                    <a href="javascript:void(0)" data-id="{{ $user->id }}" class="btn btn-danger btn-sm btnChangeStatus">Pasif</a>
                                @endif
                            </td>
                            <td>
                                @if($user->is_admin)
                                    <a href="javascript:void(0)" data-id="{{ $user->id }}" class="btn btn-primary btn-sm btnChangeIsAdmin">Admin</a>
                                @else
                                    <a href="javascript:void(0)" data-id="{{ $user->id }}" class="btn btn-secondary btn-sm btnChangeIsAdmin">User</a>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('user.edit', ['user' => $user->username]) }}"
                                       class="btn btn-warning btn-sm">
                                        <i class="material-icons ms-0">edit</i>
                                    </a>
                                    <a href="javascript:void(0)"
                                       class="btn btn-danger btn-sm btnDelete"
                                       data-name="{{ $user->name }}"
                                       data-id="{{ $user->id }}">
                                        <i class="material-icons ms-0">delete</i>
                                    </a>
                                    @if($user->deleted_at)
                                        <a href="javascript:void(0)"
                                           class="btn btn-primary btn-sm btnRestore"
                                           data-name="{{ $user->name }}"
                                           data-id="{{ $user->id }}">
                                            <i class="material-icons ms-0">undo</i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-slot:rows>
            </x-bootstrap.table>
            <div class="d-flex justify-content-center my-5">
                {{ $users->appends(request()->all())->onEachside(2)->links() }}
            </div>
        </x-slot:body>
    </x-bootstrap.card>
    <form action="" method="POST" id="statusChangeForm">
        @csrf
        <input type="hidden" name="id" id="inputStatus" value="">
    </form>
@endsection

@section('js')
    <script src="{{ asset("assets/plugins/select2/js/select2.full.min.js") }}"></script>
    <script src="{{ asset("assets/js/pages/select2.js") }}"></script>
    <script>
        $(document).ready(function(){

            $('.btnChangeStatus').click(function () {
                let userID = $(this).data('id');

                $('#inputStatus').val(userID);

                Swal.fire({
                    title: 'Status değiştirmek istediğinizden emin misiniz?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Evet',
                    denyButtonText: `Hayır`,
                    cancelButtonText: 'Iptal'
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed)
                    {
                        $('#statusChangeForm').attr("action", "{{ route('user.change-status') }}");
                        $('#statusChangeForm').submit();
                    } else if (result.isDenied)
                    {
                        Swal.fire({
                           title: 'Bilgi',
                            text: 'Herhangi bir işlem yapılmadı',
                            confirmButtonText: 'Tamam',
                            icon: 'info'
                        });
                    }
                })
            });

            $('.btnChangeIsAdmin').click(function () {
                let userID = $(this).data('id');

                $('#inputStatus').val(userID);

                Swal.fire({
                    title: 'Admin durumunu değiştirmek istediğinizden emin misiniz?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Evet',
                    denyButtonText: `Hayır`,
                    cancelButtonText: 'Iptal'
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed)
                    {
                        $('#statusChangeForm').attr("action", "{{ route('user.change-is-admin') }}");
                        $('#statusChangeForm').submit();
                    } else if (result.isDenied)
                    {
                        Swal.fire({
                           title: 'Bilgi',
                            text: 'Herhangi bir işlem yapılmadı',
                            confirmButtonText: 'Tamam',
                            icon: 'info'
                        });
                    }
                })
            });

            $('.btnDelete').click(function () {
                let userID = $(this).data('id');
                let userName = $(this).data('name');

                $('#inputStatus').val(userID);

                Swal.fire({
                    title:  userName + ' silmek istediğinizden emin misiniz?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Evet',
                    denyButtonText: `Hayır`,
                    cancelButtonText: 'Iptal'
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed)
                    {
                        $('#statusChangeForm').attr("action", "{{ route('user.delete') }}");
                        $('#statusChangeForm').submit();
                    } else if (result.isDenied)
                    {
                        Swal.fire({
                            title: 'Bilgi',
                            text: 'Herhangi bir işlem yapılmadı',
                            confirmButtonText: 'Tamam',
                            icon: 'info'
                        });
                    }
                })
            });

            $('.btnRestore').click(function () {
                let userID = $(this).data('id');
                let userName = $(this).data('name');

                $('#inputStatus').val(userID);

                Swal.fire({
                    title:  userName + ' geri getirmek istediğinize emin misiniz?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Evet',
                    denyButtonText: `Hayır`,
                    cancelButtonText: 'Iptal'
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed)
                    {
                        $('#statusChangeForm').attr("action", "{{ route('user.restore') }}");
                        $('#statusChangeForm').submit();
                        $(this).remove();
                    } else if (result.isDenied)
                    {
                        Swal.fire({
                            title: 'Bilgi',
                            text: 'Herhangi bir işlem yapılmadı',
                            confirmButtonText: 'Tamam',
                            icon: 'info'
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
