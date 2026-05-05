@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.TempusDominusBs4', true)

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

    @php
        // Sửa tên biến từ $actionFormSeach thành $actionFormSearch
        $actionFormSearch = '/aaaaaa';

        $configFormSearch = [
            ['name' => 'a', 'type_input' => 'text', 'label' => 'Tên bệnh nhân'],
            ['name' => 'b', 'type_input' => 'text', 'label' => 'Tên Bố mẹ'],
            ['name' => 'c', 'type_input' => 'number', 'label' => 'Số điện thoại'],
            ['name' => 'd', 'type_input' => 'date', 'label' => 'Ngày tháng năm sinh']
        ];
    @endphp

    <x-custom.form.search :action="$actionFormSearch" :config="$configFormSearch" title="{{ __('adminlte::adminlte.search_patient') }}"/>

    @php
        $heads = [
            'ID',
            'Name',
            ['label' => 'Phone', 'width' => 40],
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        $btnEdit = '<button data-toggle="modal" data-target="#editForm" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Ch">
                        <i class="fa fa-lg fa-fw fa-pen"></i>
                    </button>';
        $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow" onclick="confirmDelete();" title="Delete">
                          <i class="fa fa-lg fa-fw fa-trash"></i>
                      </button>';
        $btnDetails = '<button data-toggle="modal" data-target="#detail" class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
                           <i class="fa fa-lg fa-fw fa-eye"></i>
                       </button>';

        $config = [
            'data' => [
                [22, 'John Bender', '+02 (123) 123456789', '<nobr>'.$btnEdit.$btnDelete.$btnDetails.'</nobr>'],
                [19, 'Sophia Clemens', '+99 (987) 987654321', '<nobr>'.$btnEdit.$btnDelete.$btnDetails.'</nobr>'],
                [3, 'Peter Sousa', '+69 (555) 12367345243', '<nobr>'.$btnEdit.$btnDelete.$btnDetails.'</nobr>'],
            ],
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, ['orderable' => false]],
            'searching' => false,
            'dom' => '<"row" <"col-sm-7" B> <"col-sm-5 d-flex justify-content-end" l> >
                          <"row" <"col-12" tr> >
                          <"row" <"col-sm-6 d-flex justify-content-start" i> <"col-sm-6 d-flex justify-content-end" p> >',
            "language" => [
                "url" => "/vendor/datatables/lang/vi.json"
            ]
        ];
    @endphp

    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons/>

    @php

        $actionFormEdit = '/aaaaaa';

        $editFields = [
            ['name' => '1', 'type_input' => 'text', 'label' => 'Tên bệnh nhân'],
            ['name' => '2', 'type_input' => 'select', 'label' => 'Giới tính', 'value' => ['Nam', 'Nữ']],
            ['name' => '3', 'type_input' => 'date', 'label' => 'Ngày tháng năm sinh'],
            ['name' => '4', 'type_input' => 'text', 'label' => 'Tên bố mẹ'],
            ['name' => '5', 'type_input' => 'number', 'label' => 'Số điện thoại'],
            ['name' => '6', 'type_input' => 'text', 'label' => 'Địa chỉ'],
            ['name' => '7', 'type_input' => 'textarea', 'label' => 'Ghi chú'],
        ];
    @endphp
    <x-custom.form.edit :action="$actionFormEdit" :config="$editFields" title="Cập nhật thông tin"/>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}

    <style>
        .btn-add {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
            box-shadow: none;
        }

        .btn-add:hover {
            color: #fff;
            background-color: #0069d9;
            border-color: #0062cc;
        }
    </style>

@stop

@section('js')
    {{--    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>--}}
    <script src="/vendor/sweetalert2/sweetalert2.js"></script>

    <script type="application/javascript">
        function confirmDelete() {
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
                    document.getElementById('deleteForm').submit();
                }
            });
        }
    </script>
@stop
