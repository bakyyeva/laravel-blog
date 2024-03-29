@extends('layouts.admin')

@section('title')
    Kategory Listesi
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2/css/select2.min.css") }}">
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
            <h5 class="card-title">Kategory Listesi </h5>
        </x-slot:header>
        <x-slot:body>
            <x-errors.display-error />
            <form action="" method="GET" id="formFilter">
                <div class="row">
                    <div class="col-3 my-2">
                        <input type="text" class="form-control" placeholder="Name" name="name" value="{{ request()->get("name") }}">
                    </div>
                    <div class="col-3 my-2">
                        <input type="text" class="form-control" placeholder="Slug" name="slug" value="{{ request()->get("slug") }}">
                    </div>
                    <div class="col-3 my-2">
                        <input type="text" class="form-control" placeholder="Description" name="description" value="{{ request()->get("description") }}">
                    </div>
                    <div class="col-3 my-2">
                        <input type="text" class="form-control" placeholder="Order" name="order" value="{{ request()->get("order") }}">
                    </div>
                    <div class="col-3 my-2">
                        <select class="form-select" name="parent_id">
                            <option value="{{ null }}">Üst Kategori Seçin</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ request()->get("parent_id") == $parent->id ? "selected" : "" }}>{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3 my-2">
                        <select class="form-select" name="user_id">
                            <option value="{{ null }}">Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request()->get("user_id") == $user->id ? "selected" : "" }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3 my-2">
                        <select class="form-select" name="status" aria-label="Status">
                            <option value="{{ null }}">Status</option>
                            <option value="0" {{ request()->get("status") === "0" ? "selected" : "" }}>Pasif</option>
                            <option value="1" {{ request()->get("status") === "1" ? "selected" : "" }}>Aktif</option>
                        </select>
                    </div>
                    <div class="col-3 my-2">
                        <select class="form-select" name="feature_status" aria-label="Feature Status">
                            <option value="{{ null }}">Feature Status</option>
                            <option value="0" {{ request()->get("feature_status") === "0" ? "selected" : "" }}>Pasif</option>
                            <option value="1" {{ request()->get("feature_status") === "1" ? "selected" : "" }}>Aktif</option>
                        </select>
                    </div>
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
                    <th scope="col">Image</th>
                    <th scope="col">Name</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Status</th>
                    <th scope="col">Feature Status</th>
                    <th scope="col">Description</th>
                    <th scope="col">Order</th>
                    <th scope="col">Parent Category</th>
                    <th scope="col">User</th>
                    <th scope="col">Actions</th>
                </x-slot:columns>
                <x-slot:rows>
                @foreach($categories as $category)
                        <tr id="row-{{ $category->id }}">
                            <td>
                                @if(!empty($category->image))
                                    <img src="{{asset($category->image)}}" class="img-fluid" style="max-height: 100px">
                                @endif
                            </td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>
                                @if($category->status)
                                    <a href="javascript:void(0)" data-id="{{ $category->id }}" class="btn btn-success btn-sm btnChangeStatus">Aktif</a>
                                @else
                                    <a href="javascript:void(0)" data-id="{{ $category->id }}" class="btn btn-danger btn-sm btnChangeStatus">Pasif</a>
                                @endif
                            </td>
                            <td>
                                @if($category->feature_status)
                                    <a href="javascript:void(0)" data-id="{{ $category->id }}" class="btn btn-success btn-sm btnChangeFeatureStatus">Aktif</a>
                                @else
                                    <a href="javascript:void(0)" data-id="{{ $category->id }}" class="btn btn-danger btn-sm btnChangeFeatureStatus">Pasif</a>
                                @endif
                            </td>
                            <td>{{ substr($category->description, 0, 20) }}</td>
                            <td>{{ $category->order }}</td>
                            <td>{{ $category->parentCategory?->name }}</td>
                            <td>{{ $category->user->name }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('category.edit', ['id' => $category->id]) }}"
                                       class="btn btn-warning btn-sm">
                                        <i class="material-icons ms-0">edit</i>
                                    </a>
                                    <a href="javascript:void(0)"
                                       class="btn btn-danger btn-sm btnDelete"
                                       data-name="{{ $category->name }}"
                                       data-id="{{ $category->id }}">
                                        <i class="material-icons ms-0">delete</i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-slot:rows>
            </x-bootstrap.table>
            <div class="d-flex justify-content-center my-5">
                {{ $categories->appends(request()->all())->onEachside(2)->links() }}
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
                let categoryID = $(this).data('id');
                $('#inputStatus').val(categoryID);

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
                        $('#statusChangeForm').attr("action", "{{ route('category.change-status') }}");
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

            $('.btnChangeFeatureStatus').click(function () {
                let categoryID = $(this).data('id');
                // console.log(categoryID);
                $('#inputStatus').val(categoryID);

                Swal.fire({
                    title: 'Feature Status değiştirmek istediğinizden emin misiniz?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Evet',
                    denyButtonText: `Hayır`,
                    cancelButtonText: 'Iptal'
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed)
                    {
                        $('#statusChangeForm').attr("action", "{{ route('category.change-feature-status') }}");
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
                let categoryID = $(this).data('id');
                let categoryName = $(this).data('name');
                // console.log(categoryID);
                $('#inputStatus').val(categoryID);

                Swal.fire({
                    title:  categoryName + ' silmek istediğinizden emin misiniz?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Evet',
                    denyButtonText: `Hayır`,
                    cancelButtonText: 'Iptal'
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed)
                    {
                        $('#statusChangeForm').attr("action", "{{ route('category.delete') }}");
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
