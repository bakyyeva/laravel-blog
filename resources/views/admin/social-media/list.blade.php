@extends('layouts.admin')

@section('title')
    Sosyal Medya Listesi
@endsection

@section('css')
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
            <h2>Sosyal Medya Listesi</h2>
        </x-slot:header>
        <x-slot:body>
            <x-bootstrap.table
                :class="'table-striped table-hover table-responsive'"
                :is-responsive="1"
            >
                <x-slot:columns>
                    <th scope="col">Icon</th>
                    <th scope="col">Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Link</th>
                    <th scope="col">Order</th>
                    <th scope="col">Actions</th>
                </x-slot:columns>
                <x-slot:rows>
                    @foreach($list as $item)
                        <tr id="row-{{ $item->id }}">
                            <td>{{ $item->icon }}</td>
                            <td>{{ $item->name }}</td>
                            <td>
                                @if($item->status)
                                    <a href="javascript:void(0)" data-id="{{ $item->id }}" class="btn btn-success btn-sm btnChangeStatus">Aktif</a>
                                @else
                                    <a href="javascript:void(0)" data-id="{{ $item->id }}" class="btn btn-danger btn-sm btnChangeStatus">Pasif</a>
                                @endif
                            </td>
                            <td>{{ $item->link }}</td>
                            <td>{{ $item->order}}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('social-media.edit', ['id' => $item->id]) }}"
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
                {{ $list->onEachSide(2)->links() }}
            </div>
        </x-slot:body>
    </x-bootstrap.card>
@endsection

@section('js')
    <script>
        $(document).ready(function () {

            $('.btnChangeStatus').click(function () {
                let mediaID = $(this).data('id');
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
                            url: "{{ route('social-media.change-status') }}",
                            data: {
                                mediaID : mediaID
                            },
                            async:false,
                            success: function (data) {
                                if(data.social_media_status)
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
                let mediaID = $(this).data('id');

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
                            url: "{{ route('social-media.delete') }}",
                            data: {
                                "_method": "DELETE",
                                mediaID : mediaID
                            },
                            async:false,
                            success: function (data) {
                                $('#row-' + mediaID).remove();
                                Swal.fire({
                                    title: "Başarılı",
                                    text: "Sosya Medya Silindi",
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

        });
    </script>
@endsection
