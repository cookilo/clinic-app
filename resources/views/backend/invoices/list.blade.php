@extends('adminlte::page')
@section('plugins.TempusDominusBs4', true)

@section('title', 'Doanh thu')

@section('content_header')
    <h1>Nhật ký khám bệnh</h1>
@stop

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tìm kiếm</h3>
                </div>
                <form action="{{route('invoices.index')}}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="created_at">Ngày khám bệnh</label>
                                    <input id="start_created_at" name="start_created_at" class="form-control"
                                           value="{{ old('start_created_at', request('start_created_at')) }}"
                                           placeholder="dd-mm-yyyy">
                                    ~
                                    <input id="end_created_at" name="end_created_at" class="form-control"
                                           value="{{ old('end_created_at', request('end_created_at')) }}"
                                           placeholder="dd-mm-yyyy">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="patient_name">Tên bệnh nhân</label>
                                    <input id="patient_name" name="patient_name" class="form-control"
                                           value="{{ old('patient_name', request('patient_name')) }}"
                                           placeholder="Tên bệnh nhân">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-lg fa-fw fa-search"></i>Tìm kiếm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-bordered">
                                <thead>
                                <tr class="text-center">
                                    <th>STT</th>
                                    <th>Ngày khám bệnh</th>
                                    <th>Bệnh nhân</th>
                                    <th>Số tiền</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $counter = 1; @endphp
                                @foreach ($invoices as $invoice)
                                    <tr class="text-center">
                                        <td>{{ $counter++ }}</td>
                                        <td>
                                            <a href="{{ route('invoices.show', ['invoice' => $invoice->id, 'patient_id' => request()->get('patient_id')]) }}">{{ $invoice->created_at }}</a>
                                        </td>
                                        <td><a data-id="{{ $invoice->patient->id }}" href="#" class="view-details">{{ $invoice->patient->full_name }}</a></td>
                                        <td>{{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                                {{-- Nút về đầu --}}
                                @if ($invoices->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link">«</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $invoices->previousPageUrl() }}">«</a></li>
                                @endif

                                {{-- Hiển thị trang đầu tiên --}}
                                @if ($invoices->currentPage() > 6)
                                    <li class="page-item"><a class="page-link" href="{{ $invoices->url(1) }}">1</a></li>
                                    @if ($invoices->currentPage() > 7)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                @endif

                                {{-- Hiển thị các trang xung quanh trang hiện tại (±5 trang) --}}
                                @for ($i = max(1, $invoices->currentPage() - 5); $i <= min($invoices->lastPage(), $invoices->currentPage() + 5); $i++)
                                    @if ($i == $invoices->currentPage())
                                        <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $invoices->url($i) }}">{{ $i }}</a></li>
                                    @endif
                                @endfor

                                {{-- Hiển thị trang cuối --}}
                                @if ($invoices->currentPage() < $invoices->lastPage() - 5)
                                    @if ($invoices->currentPage() < $invoices->lastPage() - 6)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                    <li class="page-item"><a class="page-link" href="{{ $invoices->url($invoices->lastPage()) }}">{{ $invoices->lastPage() }}</a></li>
                                @endif

                                {{-- Nút sang trang sau --}}
                                @if ($invoices->hasMorePages())
                                    <li class="page-item"><a class="page-link" href="{{ $invoices->nextPageUrl() }}">»</a></li>
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

    <!-- Medical_records Modal -->
    <div class="modal fade" id="patientDetailModal" tabindex="-1" role="dialog" aria-labelledby="patientDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="patientDetailModalLabel">Thông tin bệnh nhân</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div id="patient-details"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="stylesheet" href="/vendor/daterangepicker/css/daterangepicker.css"/>
@stop

@section('js')
    {{--    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>--}}

    <script type="application/javascript">
        $(document).ready(function () {
            $('.view-details').on('click', function (e) {
                e.preventDefault();
                const patientId = $(this).data('id');

                $.ajax({
                    url: '/patients/' + patientId,
                    method: 'GET',
                    success: function (data) {
                        const details = [
                            {icon: 'fa-user', label: 'Tên Bệnh nhân', value: data.full_name},
                            {icon: 'fa-calendar-alt', label: 'Ngày tháng năm sinh', value: data.date_of_birth},
                            {icon: 'fa-venus-mars', label: 'Giới tính', value: data.gender === 'male' ? 'Nam' : 'Nữ'},
                            {icon: 'fa-notes-medical', label: 'Tiền sử bệnh tật', value: data.medical_history},
                            {icon: 'fa-heartbeat', label: 'Bệnh mãn tính', value: data.chronic_conditions},
                            {icon: 'fa-allergies', label: 'Dị ứng', value: data.allergies},
                            {icon: 'fa-user-friends', label: 'Tên bố mẹ', value: data.parent_name},
                            {icon: 'fa-phone', label: 'Số điện thoại', value: data.phone},
                            {icon: 'fa-map-marker-alt', label: 'Địa chỉ', value: data.address},
                        ];

                        const htmlContent = details.map(detail => `
                                                <strong><i class="fas ${detail.icon} mr-1"></i> ${detail.label}</strong>
                                                <pre class="text-muted">${detail.value ? detail.value : ''}</pre>
                                                <hr>
                                                `).join('');

                        $('#patient-details').html(htmlContent);
                        $('#patientDetailModal').modal('show');
                    },
                    error: function () {
                        Swal.fire('Lỗi!', 'Không thể tải dữ liệu bệnh nhân.', 'error');
                    }
                });
            });

        });
    </script>


@stop
