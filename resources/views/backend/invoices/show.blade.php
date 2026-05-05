@extends('adminlte::page')

@section('title', 'Lịch sử khám bệnh')

@section('content_header')
    <h1>Chi tiết lịch sử khám bệnh</h1>
@stop

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <strong><i class="fas fa-weight-hanging mr-1"></i>Cân nặng(kg)</strong>
                        <pre class="text-muted">{{$invoice->weight}}</pre>
                        <hr>
                        <strong><i class="fas fa-stethoscope mr-1"></i>Triệu chứng</strong>
                        <pre class="text-muted">{{$invoice->symptoms}}</pre>
                        <hr>
                        <strong><i class="fas fa-flask mr-1"></i>Cận lâm sàng</strong>
                        <pre class="text-muted">{{$invoice->paraclinical}}</pre>
                        <hr>
                        <strong><i class="fas fa-diagnoses mr-1"></i>Chẩn đoán</strong>
                        <pre class="text-muted">{{$invoice->diagnosis}}</pre>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Đơn thuốc</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-responsive-sm medication-table">
                            <thead>
                            <tr class="text-center">
                                <th width="10%">#</th>
                                <th width="20%">Tên thuốc</th>
                                <th width="5%">ĐV</th>
                                <th width="5%">SL</th>
                                <th width="10%">Đơn giá(VNĐ)</th>
                                <th width="10%">Thành tiền(VNĐ)</th>
                                <th width="40%">Cách sử dụng</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $counter = 1; @endphp
                            @foreach ($invoice->invoiceItems as $item)
                                <tr class="text-center">
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $item->medication->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->medication->unit }}</td>
                                    <td>{{ number_format($item->sale_price, 0, ',', '.') }}</td>
                                    <td>{{ number_format($item->total_price, 0, ',', '.') }}</td>
                                    <td>{{ $item->dosage_instructions}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="row mt-2">
                            <div class="col-md-12 text-right">
                                Tổng tiền: {{ number_format($invoice->total_amount, 0, ',', '.') }}(VNĐ)
                            </div>
                        </div>
                        <strong><i class="fas fa-notes-medical mr-1"></i>Lời dặn</strong>
                        <pre class="text-muted">{{$invoice->notes}}</pre>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('invoices.index', ['patient_id' => request('patient_id')]) }}"
                   class="btn btn-default">Quay lại</a>
                <a target="_blank" href="{{ route('invoices.print', $invoice->id) }}"
                   class="btn btn-success">In hóa đơn</a>
            </div>
        </div>
    </section>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}

@stop
