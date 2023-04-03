@extends('layouts.admin')

@section('title')
    Kullanıcı Listesi
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
            <h5 class="card-title">Kullanıcı Listesi </h5>
        </x-slot:header>
        <x-slot:body>
            <x-bootstrap.table
                :class="'table-striped table-hover table-responsive'"
                :is-responsive="1"
            >
                <x-slot:columns>
                    <th scope="col">Name</th>
                    <th scope="col">Remember me</th>
                    <th scope="col">Email</th>
                    <th scope="col">Actions</th>
                </x-slot:columns>
                <x-slot:rows>
                    @foreach($users as $user)
                        <tr id="row-{{ $user->id }}">
                            <td>{{ $user->name }}</td>
                            <td>
                                @if($user->remember_token)
                                    <a href="javascript:void(0)" data-id="{{ $user->id }}" class="btn btn-success btn-sm btnChangeStatus">Aktif</a>
                                @else
                                    <a href="javascript:void(0)" data-id="{{ $user->id }}" class="btn btn-danger btn-sm btnChangeStatus">Pasif</a>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href=""
                                       class="btn btn-warning btn-sm">
                                        <i class="material-icons ms-0">edit</i>
                                    </a>
                                    <a href="javascript:void(0)"
                                       class="btn btn-danger btn-sm btnDelete"
                                       data-name="{{ $user->name }}"
                                       data-id="{{ $user->id }}">
                                        <i class="material-icons ms-0">delete</i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-slot:rows>
            </x-bootstrap.table>
            <div class="d-flex justify-content-center my-5">
{{--                appends(request()->all())->--}}
                {{ $users->onEachside(2)->links() }}
            </div>
        </x-slot:body>
    </x-bootstrap.card>
    <form action="" method="POST" id="statusChangeForm">
        @csrf
        <input type="hidden" name="id" id="inputStatus" value="">
    </form>
@endsection

@section('js')
    <script src="{{ asset("assets2/plugins/select2/js/select2.full.min.js") }}"></script>
    <script src="{{ asset("assets2/js/pages/select2.js") }}"></script>
    <script>
        $(document).ready(function(){

            $('.btnChangeStatus').click(function () {
                let userID = $(this).data('id');
                // console.log(categoryID);
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
                        $('#statusChangeForm').attr("action", "{{ route('user.change-remember-token') }}");
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
                // console.log(categoryID);
                $('#inputStatus').val(userID);

                Swal.fire({
                    title:  userName + " 'i silmek istediğinizden emin misiniz?",
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


        });
    </script>
@endsection
