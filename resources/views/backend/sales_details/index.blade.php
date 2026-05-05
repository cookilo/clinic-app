@extends('adminlte::page')
@section('plugins.Select2', true)

@section('title', 'Chi tiết doanh thu')

@section('content_header')
    <h1>Chi tiết doanh thu</h1>
@stop

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tìm kiếm</h3>
                </div>
                <form action="{{route('sale.details.index')}}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="sale_date">Ngày</label>
                                    <input id="start_sale_date" name="start_sale_date" class="form-control" value="{{ request('start_sale_date') }}" placeholder="dd-mm-yyyy">
                                    ~
                                    <input id="end_sale_date" name="end_sale_date" class="form-control" value="{{ request('end_sale_date') }}" placeholder="dd-mm-yyyy">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="medication_name">Tên thuốc</label>
                                    <input id="medication_name" name="medication_name" class="form-control" value="{{ request('medication_name') }}" placeholder="Nhập từ khóa">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="selectMedication">Thuốc</label>
                                    <select id="selectMedication" name="medication_id" class="form-control">
                                        @foreach ($medications as $medication)
                                            <option value="">Tất cả thuốc</option>
                                            <option value="{{ $medication->id }}" {{ request('medication_id') == $medication->id ? 'selected' : '' }}>
                                                {{ $medication->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-bordered">
                                <thead>
                                <tr class="text-center">
                                    <th>Ngày</th>
                                    <th>Tên thuốc</th>
                                    <th>ĐV</th>
                                    <th>Số lượng</th>
                                    <th>Tiền bán(VNĐ)</th>
                                    <th>Tiền gốc(VNĐ)</th>
                                    <th>Tiền lãi(VNĐ)</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($salesDetails as $salesDetail)
                                    <tr class="text-center">
                                        <td>{{ $salesDetail->sale_date }}</td>
                                        <td>{{ $salesDetail->medication_name }}</td>
                                        <td>{{ $salesDetail->unit }}</td>
                                        <td>{{ $salesDetail->quantity }}</td>
                                        <td>{{ number_format($salesDetail->sale_price, 0, ',', '.') }}</td>
                                        <td>{{ number_format($salesDetail->cost_price, 0, ',', '.') }}</td>
                                        <td>{{ number_format($salesDetail->profit, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                                {{-- Nút về đầu --}}
                                @if ($salesDetails->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link">«</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $salesDetails->previousPageUrl() }}">«</a></li>
                                @endif

                                {{-- Hiển thị trang đầu tiên --}}
                                @if ($salesDetails->currentPage() > 6)
                                    <li class="page-item"><a class="page-link" href="{{ $salesDetails->url(1) }}">1</a></li>
                                    @if ($salesDetails->currentPage() > 7)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                @endif

                                {{-- Hiển thị các trang xung quanh trang hiện tại (±5 trang) --}}
                                @for ($i = max(1, $salesDetails->currentPage() - 5); $i <= min($salesDetails->lastPage(), $salesDetails->currentPage() + 5); $i++)
                                    @if ($i == $salesDetails->currentPage())
                                        <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $salesDetails->url($i) }}">{{ $i }}</a></li>
                                    @endif
                                @endfor

                                {{-- Hiển thị trang cuối --}}
                                @if ($salesDetails->currentPage() < $salesDetails->lastPage() - 5)
                                    @if ($salesDetails->currentPage() < $salesDetails->lastPage() - 6)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                    <li class="page-item"><a class="page-link" href="{{ $salesDetails->url($salesDetails->lastPage()) }}">{{ $salesDetails->lastPage() }}</a></li>
                                @endif

                                {{-- Nút sang trang sau --}}
                                @if ($salesDetails->hasMorePages())
                                    <li class="page-item"><a class="page-link" href="{{ $salesDetails->nextPageUrl() }}">»</a></li>
                                @else
                                    <li class="page-item disabled"><span class="page-link">»</span></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 d-flex justify-content-between">
                <a href="{{ url()->previous() }}" class="btn btn-default">Quay lại</a>
            </div>
        </div>
    </section>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="stylesheet" href="/vendor/daterangepicker/css/daterangepicker.css" />
    <style>
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 8px !important;
        }

        .select2-container .select2-selection--single {
            height: 38px;
        }
    </style>
@stop

@section('js')
    {{--    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>--}}
    <!-- jQuery -->
    <script src="/vendor/moment/moment.min.js"></script>
    <script src="/vendor/daterangepicker/js/daterangepicker.min.js"></script>
    <script type="application/javascript">

        document.addEventListener('DOMContentLoaded', function () {
            $('#selectMedication').select2();
        });
    </script>
@stop
