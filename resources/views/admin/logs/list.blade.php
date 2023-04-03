@extends('layouts.admin')

@section('title')
    Log Listesi
@endsection

@section('css')
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

@section('content')
    <x-bootstrap.card>
        <x-slot:header>
            <h5 class="card-title">Log Listesi </h5>
        </x-slot:header>
        <x-slot:body>
            <form action="">
                <div class="row">
                    <div class="col-3 my-2">
                        <input type="text" name="process_id" class="form-control" placeholder="Process ID" value="{{ request()->get('process_id') }}">
                    </div>
                    <div class="col-3 my-2">
                        <input type="text" name="process_type" class="form-control" placeholder="Process Type" value="{{ request()->get('process_type') }}">
                    </div>
                    <div class="col-3 my-2">
                        <input type="text" class="form-control flatpickr2 m-b-sm log_date" placeholder="Log Oluşturulma Tarihi"
                               name="created_log"
                               id="created_log"
                               value="{{ request()->get('created_log') }}"
                        >
                    </div>
                    <div class="col-3 my-2">
                        <input type="text" class="form-control flatpickr2 m-b-sm log_date" placeholder="Log Güncelleme Tarihi"
                               name="updated_log"
                               id="updated_log"
                               value="{{ request()->get('updated_log') }}"
                        >
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
                    <th scope="col">Process ID</th>
                    <th scope="col">Process Type</th>
                    <th scope="col">Log Oluşturulduğu Tarih</th>
                    <th scope="col">Log Güncelleme Tarih</th>
                    <th scope="col">Actions</th>
                </x-slot:columns>
                <x-slot:rows>
                    @foreach($logs as $log)
                        <tr id="row-{{ $log->id }}">
                            <td>{{ $log->user->name }}</td>
                            <td>{{ $log->process_id }}</td>
                            <td>{{ $log->process_type }}</td>
                            <td>{{ $log->created_at }}</td>
                            <td>{{ $log->updated_at }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('log.edit', ['id' => $log->id]) }}"
                                       class="btn btn-warning btn-sm">
                                        <i class="material-icons ms-0">edit</i>
                                    </a>
                                    <a href="javascript:void(0)"
                                       class="btn btn-danger btn-sm btnDelete"
                                       data-name="{{ $log->user->name }}"
                                       data-id="{{ $log->id }}">
                                        <i class="material-icons ms-0">delete</i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-slot:rows>
            </x-bootstrap.table>
            <div class="d-flex justify-content-center my-2">
                {{ $logs->appends(request()->all())->onEachSide(2)->links() }}
            </div>
        </x-slot:body>
    </x-bootstrap.card>
@endsection

@section('js')
    <script src="{{ asset("assets2/plugins/select2/js/select2.full.min.js") }}"></script>
    <script src="{{ asset("assets2/js/pages/select2.js") }}"></script>
    <script src="{{ asset("assets2/plugins/flatpickr/flatpickr.js") }}"></script>
{{--    <script src="{{ asset("assets2/js/pages/datepickers.js") }}"></script>--}}
    <script src="{{ asset("assets2/admin/plugins/bootstrap/js/bootstrap.bundle.min.js") }}"></script>

    <script>
        $(document).ready(function () {

            $('.btnDelete').click(function () {
               let logID = $(this).data('id');
               let userName = $(this).data('name')

                Swal.fire({
                    title: userName + " 'in Log bilgierini silmek istediğinize emin misiniz?",
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
                            url: "{{ route('log.delete') }}",
                            data: {
                                "_method": "DELETE",
                                logID : logID
                            },
                            async:false,
                            success: function (data) {
                                $('#row-' + logID).remove();
                                Swal.fire({
                                    title: "Başarılı",
                                    text: "Log Silindi",
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
        $(".log_date").flatpickr({
           enableTime: true,
           enableSeconds: true,
            // dateFormat: "Y-m-d H:i:S",
       });
        // const popover = new bootstrap.Popover('.example-popover', {
        //     container: 'body'
        // })

         $("#updated_log").flatpickr({
             enableTime: true,
             dateFormat: "Y-m-d H:i:s",
         });
         const popover = new bootstrap.Popover('.example-popover', {
             container: 'body'
         })
    </script>

@endsection
