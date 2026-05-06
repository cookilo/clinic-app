@extends('adminlte::page')

@section('title', 'Doanh thu theo ngày')

@section('content_header')
    <h1>Doanh thu theo ngày</h1>
@stop

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tìm kiếm</h3>
                </div>
                <form action="{{route('sale.statistics.index')}}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card-body">
                                <div class="form-group d-flex align-items-center mb-2">
                                    <label class="mb-0 mr-3" style="width: 80px; white-space: nowrap;">Ngày</label>
                                    <div class="d-flex align-items-center flex-fill">
                                        <input id="start_sale_date" name="start_sale_date" class="form-control"
                                               value="{{ old('start_sale_date', request('start_sale_date')) }}"
                                               placeholder="dd-mm-yyyy">
                                        <span class="text-muted mx-2">~</span>
                                        <input id="end_sale_date" name="end_sale_date" class="form-control"
                                               value="{{ old('end_sale_date', request('end_sale_date')) }}"
                                               placeholder="dd-mm-yyyy">
                                    </div>
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
                                    <th>Tiền bán(VNĐ)</th>
                                    <th>Tiền gốc(VNĐ)</th>
                                    <th>Tiền lãi(VNĐ)</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($salesStatistics as $salesStatistic)
                                    <tr class="text-center">
                                        <td><a target="_blank" href="{{ route('sale.details.index', ['start_sale_date' => $salesStatistic->sale_date ,'end_sale_date' => $salesStatistic->sale_date]) }}">{{ $salesStatistic->sale_date }}</a></td>
                                        <td>{{ number_format($salesStatistic->total_sales, 0, ',', '.') }}</td>
                                        <td>{{ number_format($salesStatistic->total_cost, 0, ',', '.') }}</td>
                                        <td>{{ number_format($salesStatistic->total_profit, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                                {{-- Nút về đầu --}}
                                @if ($salesStatistics->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link">«</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $salesStatistics->previousPageUrl() }}">«</a></li>
                                @endif

                                {{-- Hiển thị trang đầu tiên --}}
                                @if ($salesStatistics->currentPage() > 6)
                                    <li class="page-item"><a class="page-link" href="{{ $salesStatistics->url(1) }}">1</a></li>
                                    @if ($salesStatistics->currentPage() > 7)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                @endif

                                {{-- Hiển thị các trang xung quanh trang hiện tại (±5 trang) --}}
                                @for ($i = max(1, $salesStatistics->currentPage() - 5); $i <= min($salesStatistics->lastPage(), $salesStatistics->currentPage() + 5); $i++)
                                    @if ($i == $salesStatistics->currentPage())
                                        <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $salesStatistics->url($i) }}">{{ $i }}</a></li>
                                    @endif
                                @endfor

                                {{-- Hiển thị trang cuối --}}
                                @if ($salesStatistics->currentPage() < $salesStatistics->lastPage() - 5)
                                    @if ($salesStatistics->currentPage() < $salesStatistics->lastPage() - 6)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                    <li class="page-item"><a class="page-link" href="{{ $salesStatistics->url($salesStatistics->lastPage()) }}">{{ $salesStatistics->lastPage() }}</a></li>
                                @endif

                                {{-- Nút sang trang sau --}}
                                @if ($salesStatistics->hasMorePages())
                                    <li class="page-item"><a class="page-link" href="{{ $salesStatistics->nextPageUrl() }}">»</a></li>
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
    <link rel="stylesheet" href="/vendor/daterangepicker/css/daterangepicker.css" />
@stop

@section('js')
    {{--    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>--}}
    <!-- jQuery -->
@stop
