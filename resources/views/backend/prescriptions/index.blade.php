@extends('adminlte::page')
@section('plugins.TempusDominusBs4', true)

@section('title', 'Danh sách đơn thuốc')

@section('content_header')
    <h1>Danh sách đơn thuốc</h1>
@stop

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tìm kiếm</h3>
                </div>
                <form action="{{route('prescriptions.index')}}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card-body">
                                <x-adminlte-input name="title" label="Tên đơn thuốc" placeholder="Nhập tên đơn thuốc" value="{{ old('title', request('title')) }}"></x-adminlte-input>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-lg fa-fw fa-search"></i>Tìm kiếm</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <a href="{{ route('prescriptions.create') }}" class="btn btn-success mb-3 mx-1">
                <i class="fa fa-lg fa-fw fa-plus-circle"></i>Tạo mới
            </a>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-bordered">
                                <thead>
                                <tr class="text-center">
                                    <th>ID</th>
                                    <th>Tên đơn thuốc</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($prescriptions as $prescription)
                                    <tr class="text-center">
                                        <td>{{ $prescription->id }}</td>
                                        <td>{{ $prescription->title }}</td>
                                        <td>
                                            <a class="btn btn-xs btn-default text-info mx-1 shadow view-details" href="{{ route('prescriptions.show', $prescription->id) }}">
                                                <i class="fa fa-lg fa-fw fa-eye"></i>
                                            </a>
                                            <a class="btn btn-xs btn-default text-primary mx-1 shadow" href="{{ route('prescriptions.edit', $prescription->id) }}">
                                                <i class="fa fa-lg fa-fw fa-pen"></i>
                                            </a>
                                            <button class="btn btn-xs btn-default text-danger mx-1 shadow" onclick="confirmDelete({{ $prescription->id }});" title="Xóa">
                                                <i class="fa fa-lg fa-fw fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                                {{-- Nút về đầu --}}
                                @if ($prescriptions->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link">«</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $prescriptions->previousPageUrl() }}">«</a></li>
                                @endif

                                {{-- Hiển thị trang đầu tiên --}}
                                @if ($prescriptions->currentPage() > 6)
                                    <li class="page-item"><a class="page-link" href="{{ $prescriptions->url(1) }}">1</a></li>
                                    @if ($prescriptions->currentPage() > 7)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                @endif

                                {{-- Hiển thị các trang xung quanh trang hiện tại (±5 trang) --}}
                                @for ($i = max(1, $prescriptions->currentPage() - 5); $i <= min($prescriptions->lastPage(), $prescriptions->currentPage() + 5); $i++)
                                    @if ($i == $prescriptions->currentPage())
                                        <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $prescriptions->url($i) }}">{{ $i }}</a></li>
                                    @endif
                                @endfor

                                {{-- Hiển thị trang cuối --}}
                                @if ($prescriptions->currentPage() < $prescriptions->lastPage() - 5)
                                    @if ($prescriptions->currentPage() < $prescriptions->lastPage() - 6)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                    <li class="page-item"><a class="page-link" href="{{ $prescriptions->url($prescriptions->lastPage()) }}">{{ $prescriptions->lastPage() }}</a></li>
                                @endif

                                {{-- Nút sang trang sau --}}
                                @if ($prescriptions->hasMorePages())
                                    <li class="page-item"><a class="page-link" href="{{ $prescriptions->nextPageUrl() }}">»</a></li>
                                @else
                                    <li class="page-item disabled"><span class="page-link">»</span></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        pre {
            font-size: 100% !important;
        }
    </style>
@stop

@section('js')
    {{--    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>--}}
    <script src="/vendor/sweetalert2/sweetalert2.js"></script>

    <script type="application/javascript">
        function confirmDelete(patientId) {
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Bạn sẽ không thể hoàn tác điều này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Vâng, xóa nó!',
                cancelButtonText: 'Hủy bỏ',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary ml-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/prescriptions/${patientId}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Đã xóa!',
                                text: response.message,
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Có lỗi xảy ra!',
                                text: xhr.responseJSON.message || 'Không thể xóa bệnh nhân.',
                            });
                        }
                    });
                }
            });
        }
    </script>
@stop
