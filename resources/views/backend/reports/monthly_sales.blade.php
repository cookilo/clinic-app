@extends('adminlte::page')

@section('title', 'Doanh thu theo tháng')

@section('content_header')
    <h1>Doanh thu theo tháng</h1>
@stop

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tìm kiếm</h3>
                </div>
                <form method="GET" action="{{ route('reports.monthly_sales') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="year">Năm</label>
                                    <select name="year" id="year" class="form-control">
                                        <option value="">-- Chọn năm --</option>
                                        @for ($y = now()->year; $y >= 2020; $y--)
                                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endfor
                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="month">Tháng</label>
                                    <select name="month" id="month" class="form-control">
                                        <option value="">-- Chọn tháng --</option>
                                        @for ($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                                {{ str_pad($m, 2, '0', STR_PAD_LEFT) }}
                                            </option>
                                        @endfor
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
                                    <th>Tháng</th>
                                    <th>Tiền bán(VNĐ)</th>
                                    <th>Tiền gốc(VNĐ)</th>
                                    <th>Tiền lãi(VNĐ)</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($sales as $sale)
                                    <tr class="text-center">
                                        <td>{{ str_pad($sale->sale_month, 2, '0', STR_PAD_LEFT) . '-' . $sale->sale_year }}</td>
                                        <td>{{ number_format($sale->total_sales, 0, ',', '.') }}</td>
                                        <td>{{ number_format($sale->total_cost, 0, ',', '.') }}</td>
                                        <td>{{ number_format($sale->total_profit, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                                {{-- Nút về đầu --}}
                                @if ($sales->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link">«</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $sales->previousPageUrl() }}">«</a></li>
                                @endif

                                {{-- Hiển thị trang đầu tiên --}}
                                @if ($sales->currentPage() > 6)
                                    <li class="page-item"><a class="page-link" href="{{ $sales->url(1) }}">1</a></li>
                                    @if ($sales->currentPage() > 7)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                @endif

                                {{-- Hiển thị các trang xung quanh trang hiện tại (±5 trang) --}}
                                @for ($i = max(1, $sales->currentPage() - 5); $i <= min($sales->lastPage(), $sales->currentPage() + 5); $i++)
                                    @if ($i == $sales->currentPage())
                                        <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $sales->url($i) }}">{{ $i }}</a></li>
                                    @endif
                                @endfor

                                {{-- Hiển thị trang cuối --}}
                                @if ($sales->currentPage() < $sales->lastPage() - 5)
                                    @if ($sales->currentPage() < $sales->lastPage() - 6)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                    <li class="page-item"><a class="page-link" href="{{ $sales->url($sales->lastPage()) }}">{{ $sales->lastPage() }}</a></li>
                                @endif

                                {{-- Nút sang trang sau --}}
                                @if ($sales->hasMorePages())
                                    <li class="page-item"><a class="page-link" href="{{ $sales->nextPageUrl() }}">»</a></li>
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
@stop

@section('js')

    <script type="application/javascript">
        $(document).ready(function(){

            $('#start_time').datetimepicker({
                format: 'MM-YYYY'
            });
        })
    </script>
@stop
