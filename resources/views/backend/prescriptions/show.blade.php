@extends('adminlte::page')
@section('plugins.Select2', true)

@section('title', 'Chi tiết đơn thuốc')

@section('content_header')
    <h1>Chi tiết đơn thuốc</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body" style="display: block;">
            <div class="row">
                <div class="col-12">
                    <p>
                        <b class="d-block">Tên đơn thuốc</b>
                        {{$prescription->title}}
                    </p>
                    <p><b class="d-block">Danh sách thuốc</b></p>
                    <table class="table table-bordered table-responsive-sm medication-table">
                        <thead>
                        <tr class="text-center">
                            <th width="10%">#</th>
                            <th width="30%">Tên thuốc</th>
                            <th width="10%">ĐV</th>
                            <th width="10%">SL</th>
                            <th width="40%">Cách sử dụng</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $counter = 1; @endphp
                        @if($prescribedMedications->isNotEmpty())
                            @foreach($prescribedMedications as $medication)
                                <tr class="text-center">
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $medication['name'] }}</td>
                                    <td>{{ $medication['unit'] }}</td>
                                    <td>{{ $medication['quantity'] }}</td>
                                    <td>{{ $medication['dosage_instructions'] }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <div class="row">
        <div class="col-12">
            <a href="{{ url()->previous() }}" class="btn btn-default">Quay lại</a>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    {{-- Add here extra script --}}
@stop
