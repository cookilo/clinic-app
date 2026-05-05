@extends('adminlte::page')
@section('plugins.Select2', true)

@section('title', 'Cập nhật đơn thuốc')

@section('content_header')
    <h1>Cập nhật đơn thuốc</h1>
@stop

@section('content')
    <form id="createPrescriptionsForm">
        @csrf
        @method('POST')
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Tên đơn thuốc<span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input id="name" name="title" value="{{$prescription->title}}"
                                           class="form-control"
                                           placeholder="Nhập tên đơn thuốc">
                                </div>
                            </div>
                            <label for="selectMedication" class="mr-2 mt-2">Danh sách thuốc<span
                                    class="text-danger">*</span></label>
                            <div class="form-group d-flex">
                                <label for="selectMedication" class="mr-2 mt-2">Thuốc</label>
                                <select id="selectMedication" class="form-control">
                                    @foreach ($medications as $medication)
                                        <option
                                            value="{{ $medication->id }}">{{ $medication->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" id="addMedication"
                                        class="btn btn-default text-success ml-2"
                                        title="Xóa">
                                    <i class="fa fa-lg fa-fw fa-plus"></i>
                                </button>
                            </div>

                            <table class="table table-bordered table-responsive-sm medication-table">
                                <thead>
                                <tr class="text-center">
                                    <th width="5%">#</th>
                                    <th width="30%">Tên thuốc</th>
                                    <th width="10%">ĐV</th>
                                    <th width="10%">SL</th>
                                    <th width="35%">Cách sử dụng</th>
                                    <th width="10%"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $counter = 1; @endphp
                                @if($prescribedMedications->isNotEmpty())
                                    @foreach($prescribedMedications as $medication)
                                        <tr class="text-center"
                                            data-medication-id="{{ $medication['id'] }}">
                                            <td>{{ $counter++ }}</td>
                                            <td>{{ $medication['name'] }}</td>
                                            <td>{{ $medication['unit'] }}</td>
                                            <td>
                                                <input type="number" class="form-control quantity"
                                                       name="medications[{{$medication['id']}}][quantity]"
                                                       value="{{ $medication['quantity'] }}">
                                            </td>
                                            <td><input type="text"
                                                       class="form-control dosage_instructions"
                                                       name="medications[{{$medication['id']}}][dosage_instructions]"
                                                       value="{{ $medication['dosage_instructions'] }}"
                                                       placeholder="Nhập cách sử dụng"></td>
                                            <td>
                                                <button
                                                    class="btn btn-xs btn-default text-danger mx-1 shadow"
                                                    title="Xóa"
                                                    onclick="removeMedication({{ $medication['id'] }});">
                                                    <i class="fa fa-lg fa-fw fa-trash"></i>
                                                </button>
                                            </td>
                                            <input type="hidden"
                                                   name="medications[{{ $medication['id'] }}][id]"
                                                   value="{{ $medication['id'] }}">
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
                <div class="col-12 d-flex justify-content-between">
                    <a href="{{ url()->previous() }}" class="btn btn-default">Quay lại</a>
                    <button data-id="{{$prescription->id}}" id="addPrescriptionsRecords" type="button" class="btn btn-primary">Cập nhật</button>
                </div>
            </div>
        </section>
    </form>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="stylesheet" href="{{ asset('vendor/bs-stepper/css/bs-stepper.min.css') }}">

    <style>
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 8px !important;
        }

        .select2-container .select2-selection--single {
            height: 40px;
        }
    </style>
@stop

@section('js')
    {{--    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>--}}
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.js') }}"></script>

    <script>

        // Đảm bảo rằng nội dung đã sẵn sàng
        document.addEventListener('DOMContentLoaded', function () {
            $('#selectMedication').select2();
        });

        $('#addMedication').on('click', function () {
            const medicationId = $('#selectMedication').val();

            $.ajax({
                url: '/medications/' + medicationId,
                method: 'GET',
                success: function (data) {
                    // Kiểm tra xem medicationId đã được append chưa
                    const existingRow = $('.medication-table tbody').find(`tr[data-medication-id="${medicationId}"]`);

                    if (existingRow.length > 0) {
                        // Nếu đã tồn tại, không append
                        Swal.fire('Thông báo!', 'Thuốc này đã được thêm.', 'info');
                        return;
                    }

                    $('.medication-table tbody').append(`
                    <tr class="text-center" data-medication-id="${medicationId}">
                        <td></td>
                        <td>${data.name}</td>
                        <td>${data.unit}</td>
                        <td>
                            <input type="number" class="form-control quantity" name="medications[${medicationId}][quantity]" value="1">
                        </td>
                        <td>
                            <input type="text" class="form-control dosage_instructions" name="medications[${medicationId}][dosage_instructions]" placeholder="Nhập cách sử dụng">
                        <td>
                            <button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Xóa" onclick="removeMedication(${medicationId});">
                            <i class="fa fa-lg fa-fw fa-trash"></i>
                            </button>
                        </td>
                        <input type="hidden" name="medications[${medicationId}][id]" value="${medicationId}">
                    </tr>
                `);

                    updateRowNumbers();
                },
                error: function () {
                    Swal.fire('Lỗi!', 'Không thể tải dữ liệu thuốc.', 'error');
                }
            });
        });

        // Hàm đánh số thứ tự
        function updateRowNumbers() {
            $('.medication-table tbody tr').each(function (index) {
                $(this).find('td:first').text(index + 1);
            });
        }

        // Kiểm tra giá trị input quantity
        $(document).on('input', '.quantity', function () {
            const quantity = $(this).val();
            if (quantity < 1) {
                Swal.fire('Thông báo!', 'Số lượng không được để trống và phải lớn hơn 0!', 'warning');
                $(this).val(''); // Xóa giá trị nếu không hợp lệ
            }
        });

        // Hàm xóa thuốc
        function removeMedication(medicationId) {
            $('.medication-table tbody').find(`tr[data-medication-id="${medicationId}"]`).remove();
            updateRowNumbers();
            calculateGrandTotal(); // Tính lại tổng khi xóa
        }

        $('#addPrescriptionsRecords').on('click', function () {
            const formData = $('#createPrescriptionsForm').serialize();
            const prescriptionId = $(this).data('id');

            $.ajax({
                url: '/prescriptions/' + prescriptionId, // Giả sử bạn có biến medicalRecordId
                method: 'PUT', // Hoặc 'PATCH' tùy thuộc vào API của bạn
                data: formData,
                success: function (response) {
                    Swal.fire({
                        title: 'Thành công!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'Xác nhận'
                    }).then(() => {
                        window.location.href = '/prescriptions/' + prescriptionId;
                    });
                },
                error: function (xhr) {
                    // Kiểm tra xem phản hồi có chứa thông điệp lỗi không
                    const response = xhr.responseJSON;

                    // Xử lý các loại lỗi khác nhau
                    let errorMessage = 'Có lỗi xảy ra.'; // Mặc định nếu không có thông điệp cụ thể
                    if (response.status === 'error') {
                        if (response.errors) {
                            // Nếu có lỗi từ $e->getMessageBag()
                            errorMessage = response.errors.join(', '); // Kết hợp các lỗi thành chuỗi
                        } else if (response.message) {
                            // Nếu có thông điệp lỗi cụ thể
                            errorMessage = response.message;
                        }
                    } else {
                        const errors = response.errors;
                        errorMessage = '';

                        $.each(errors, function (key, messages) {
                            errorMessage += '<p>' + messages[0] + '</p>';
                        });
                    }

                    Swal.fire({
                        title: 'Lỗi!',
                        html: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'Xác nhận'
                    });
                }
            });
        })
    </script>

@stop
