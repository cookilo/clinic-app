@extends('adminlte::page')
@section('plugins.TempusDominusBs4', true)

@section('title', 'Lịch sử khám bệnh')

@section('content_header')
    <h1>Lịch sử khám bệnh của bệnh nhân {{$patient->full_name}}</h1>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <a class="btn btn-success mb-3 mx-1" href="{{ route('invoices.create', ['patient_id' => request()->get('patient_id')]) }}">
                <i class="fa fa-lg fa-fw fa-plus-circle"></i>Tạo
            </a>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                <form action="{{ route('invoices.index') }}" method="GET">
                                    <div class="input-group input-group-sm">
                                        <input type="hidden" name="patient_id" value="{{ request()->get('patient_id') }}">
                                        <input id="created_at_search" name="created_at" class="form-control  float-right"
                                               value="{{ old('created_at', request('created_at')) }}"
                                               data-target="#created_at_search" data-toggle="datetimepicker"
                                               placeholder="tìm kiếm">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card-body table-responsive p-0" style="height: 400px;">
                            <table class="table table-head-fixed text-nowrap table-bordered table-hove">
                                <thead>
                                <tr class="text-center">
                                    <th width="50%">Ngày khám bệnh</th>
                                    <th width="50%"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr class="text-center">
                                        <td>{{ \Carbon\Carbon::parse($invoice->created_at)->format('Y-m-d') }}</td>
                                        <td>
                                            <a class="btn btn-xs btn-default text-info mx-1 shadow" href="{{ route('invoices.show', ['invoice' => $invoice->id, 'patient_id' => request()->get('patient_id')]) }}">
                                                <i class="fa fa-lg fa-fw fa-eye"></i>
                                            </a>
                                            <a class="btn btn-xs btn-default text-primary mx-1 shadow" href="{{ route('invoices.edit', ['invoice' => $invoice->id, 'patient_id' => request()->get('patient_id')]) }}">
                                                <i class="fa fa-lg fa-fw fa-pen"></i>
                                            </a>
                                            <button class="btn btn-xs btn-default text-danger mx-1 shadow" onclick="confirmDelete({{ $invoice->id }});" title="Xóa">
                                                <i class="fa fa-lg fa-fw fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
@stop

@section('js')
    {{--    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>--}}
    <script src="/vendor/sweetalert2/sweetalert2.js"></script>
    <script>
        $('#created_at_search').datetimepicker({
            format: 'DD-MM-YYYY'
        });

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
                        url: `/invoices/${patientId}`,
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
