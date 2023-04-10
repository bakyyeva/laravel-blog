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
    <div class="row">
        <div class="col">
    <x-bootstrap.card>
        <x-slot:header>
            <h5 class="card-title">Log Listesi </h5>
        </x-slot:header>
        <x-slot:body>
{{--            <form action="">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-3 my-2">--}}
{{--                        <input type="text" name="process_id" class="form-control" placeholder="Process ID" value="{{ request()->get('process_id') }}">--}}
{{--                    </div>--}}
{{--                    <div class="col-3 my-2">--}}
{{--                        <input type="text" name="process_type" class="form-control" placeholder="Process Type" value="{{ request()->get('process_type') }}">--}}
{{--                    </div>--}}
{{--                    <div class="col-3 my-2">--}}
{{--                        <input type="text" class="form-control flatpickr2 m-b-sm log_date" placeholder="Log Oluşturulma Tarihi"--}}
{{--                               name="created_log"--}}
{{--                               id="created_log"--}}
{{--                               value="{{ request()->get('created_log') }}"--}}
{{--                        >--}}
{{--                    </div>--}}
{{--                    <div class="col-3 my-2">--}}
{{--                        <input type="text" class="form-control flatpickr2 m-b-sm log_date" placeholder="Log Güncelleme Tarihi"--}}
{{--                               name="updated_log"--}}
{{--                               id="updated_log"--}}
{{--                               value="{{ request()->get('updated_log') }}"--}}
{{--                        >--}}
{{--                    </div>--}}
{{--                    <div class="col-6 my-2">--}}
{{--                        <select class="form-select form-control" name="user_id">--}}
{{--                            <option value="{{ null }}">Users</option>--}}
{{--                            @foreach($users as $user)--}}
{{--                                <option value="{{ $user->id }}" {{ request()->get("user_id") == $user->id ? "selected" : "" }}>--}}
{{--                                    {{ $user->name }}--}}
{{--                                </option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                    <hr>--}}
{{--                    <div class="col-6 mb-2 d-flex">--}}
{{--                        <button class="btn btn-primary w-50 me-4" type="submit">Filtrele</button>--}}
{{--                        <button class="btn btn-warning w-50" type="submit" id="btnClearFilter">Filtreyi Temizle</button>--}}
{{--                    </div>--}}
{{--                    <hr>--}}
{{--                </div>--}}
{{--            </form>--}}
            <x-bootstrap.table
            :class="'table-striped table-hover table-responsive'"
            :is-responsive="1"
            >
                <x-slot:columns>
                    <th scope="col">Action</th>
                    <th scope="col">Model</th>
                    <th scope="col">Model View</th>
                    <th scope="col">User</th>
                    <th scope="col">Data</th>
                    <th scope="col">Created At</th>
                </x-slot:columns>
                <x-slot:rows>
                    @foreach($logs as $log)
                        <tr id="row-{{ $log->id }}">
                            <td>{{ $log->action }}</td>
                            <td>{{ $log->loggable_type }}</td>
                            <td>
                                <a href="javascript:void(0)"
                                   class="btn btn-info btn-sm btnModelLogDetail"
                                   data-bs-toggle="modal" data-bs-target="#contentViewModal"
                                   data-id="{{ $log->id }}"
                                >
                                    <i class="material-icons ms-0">visibility</i>
                                </a>
                            </td>
                            <td>{{ $log->user->name }}</td>
                            <td>
                                <a href="javascript:void(0)"
                                   class="btn btn-primary btn-sm btnDataDetail"
                                   data-bs-toggle="modal"
                                   data-bs-target="#contentViewModal"
                                   data-id="{{ $log->id }}"
                                >
                                    <i class="material-icons ms-0">visibility</i>
                                </a>
                            </td>
                            <td>{{ $log->created_at }}</td>
                        </tr>
                    @endforeach
                </x-slot:rows>
            </x-bootstrap.table>
            <div class="d-flex justify-content-center my-2">
                {{ $logs->appends(request()->all())->onEachSide(2)->links() }}
            </div>
        </x-slot:body>
    </x-bootstrap.card>
    </div>
    </div>

    <div class="modal fade" id="contentViewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Log Detayı</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <pre><code class="language-json" id="jsonData"></code></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset("assets2/plugins/select2/js/select2.full.min.js") }}"></script>
    <script src="{{ asset("assets2/js/pages/select2.js") }}"></script>
    <script src="{{ asset("assets2/plugins/flatpickr/flatpickr.js") }}"></script>
{{--    <script src="{{ asset("assets2/js/pages/datepickers.js") }}"></script>--}}
    <script src="{{ asset("assets2/admin/plugins/bootstrap/js/bootstrap.bundle.min.js") }}"></script>

    <script>
        $(document).ready(function () {
            $('.btnModelLogDetail').click(function () {
               let logID = $(this).data('id');
               let self = $(this);
               let route = "{{ route('dbLogs.getLog', ['id' => ":id"]) }}";
               route = route.replace(':id', logID);

               $.ajax({
                   method: "get",
                   url: route,
                   async: false,
                   success: function (data) {
                       console.log(data);
                       $('#modalBody').html(data)
                   },
                   error: function (){
                       console.log("hata geldi");
                   }
               })
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
