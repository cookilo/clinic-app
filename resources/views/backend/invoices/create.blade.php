@extends('adminlte::page')
@section('plugins.TempusDominusBs4', true)
@section('plugins.Select2', true)

@section('title', 'Lịch sử khám bệnh')

@section('content_header')
    <h1>Tạo lịch sử khám bệnh</h1>
@stop

@section('content')
    <form id="createInvoicesForm">
        @csrf
        @method('POST')
        <input type="hidden" name="patient_id" value="{{ request('patient_id') }}">
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
                            <div class="form-group">
                                <label for="weight">Cân nặng(kg)<span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <input id="weight" name="weight" value="" class="form-control"
                                           placeholder="Nhập cân nặng">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="symptoms">Triệu chứng<span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <textarea id="symptoms" name="symptoms" class="form-control" rows="6"
                                              placeholder="Nhập triệu chứng"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="paraclinical">Cận lâm sàng</label>
                                <div class="input-group input-group-sm">
                                    <textarea id="paraclinical" name="paraclinical" class="form-control" rows="6"
                                              placeholder="Nhập cận lâm sàng"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="diagnosis">Chẩn đoán<span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <textarea id="diagnosis" name="diagnosis" class="form-control" rows="6"
                                              placeholder="Nhập chẩn đoán"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Thuốc điều trị</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($prescriptions->isNotEmpty())
                            <div class="form-group d-flex">
                                <label for="selectPrescription" class="mr-2 mt-2" style="width: 5%;">Đơn thuốc</label>
                                <select id="selectPrescription" class="form-control" style="width: 95%;">
                                    @foreach ($prescriptions as $prescription)
                                        <option
                                            value="{{ $prescription->id }}">{{ $prescription->title }}</option>
                                    @endforeach
                                </select>
                                <button type="button" id="addPrescription"
                                        class="btn btn-default text-success ml-2"
                                        title="Thêm">
                                    <i class="fa fa-lg fa-fw fa-plus"></i>
                                </button>
                            </div>
                            @endif
                            <div class="form-group d-flex">
                                <label for="selectMedication" class="mr-2 mt-2" style="width: 5%;">Thuốc</label>
                                <select id="selectMedication" class="form-control" style="width: 95%;">
                                    @foreach ($medications as $medication)
                                        <option
                                            value="{{ $medication->id }}">{{ $medication->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" id="addMedication"
                                        class="btn btn-default text-success ml-2"
                                        title="Thêm">
                                    <i class="fa fa-lg fa-fw fa-plus"></i>
                                </button>
                            </div>

                            <table class="table table-bordered table-responsive-sm medication-table">
                                <thead>
                                <tr class="text-center">
                                    <th width="5%">ID</th>
                                    <th width="20%">Tên thuốc</th>
                                    <th width="5%">ĐV</th>
                                    <th width="5%">SL</th>
                                    <th width="10%">Đơn giá(VNĐ)</th>
                                    <th width="10%">Thành tiền(VNĐ)</th>
                                    <th width="40%">Cách sử dụng</th>
                                    <th width="5%"></th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <div class="row mt-2">
                                <div class="col-md-12 text-right">
                                    Tổng tiền: <span id="totalPrice">0</span> VNĐ
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="notes">Lời dặn</label>
                                <div class="input-group input-group-sm">
                                    <textarea id="notes" name="notes" class="form-control" rows="6"
                                              placeholder="Nhập lời dặn"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <a href="{{ url()->previous() }}" class="btn btn-default">Quay lại</a>
                    <button id="addInvoices" type="button" class="btn btn-success">Tạo</button>
                </div>
            </div>
        </section>
    </form>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 8px !important;
        }

        .select2-container .select2-selection--single {
            height: 40px;
        }

        @media (max-width: 576px) {
            #addMedication {
                margin-top: 0.5rem !important;
                margin-left: unset !important;
                width: 80px;
            }
        }

    </style>
@stop

@section('js')
    {{--    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>--}}
    <script src="/vendor/sweetalert2/sweetalert2.js"></script>

    <script>
        // Đảm bảo rằng nội dung đã sẵn sàng
        document.addEventListener('DOMContentLoaded', function () {
            $('#selectMedication').select2();
            $('#selectPrescription').select2();
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
                                    <input type="number" class="form-control quantity" name="medications[${medicationId}][quantity]" min="1" value="1">
                                </td>
                                <td class="unit-price">${number_format(data.sale_price)}</td>
                                <td class="price">${number_format(data.sale_price)}</td>
                                <td>
                                    <input type="text" class="form-control dosage_instructions" name="medications[${medicationId}][dosage_instructions]" value="${data.dosage_instructions ? data.dosage_instructions : ''}" placeholder="Nhập cách sử dụng">
                                </td>
                                <td>
                                    <button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Xóa" onclick="removeMedication(${medicationId});">
                                    <i class="fa fa-lg fa-fw fa-trash"></i>
                                    </button>
                                </td>
                                <input type="hidden" name="medications[${medicationId}][id]" value="${medicationId}">
                                <input type="hidden" name="medications[${medicationId}][name]" value="${data.name}">
                                <input type="hidden" name="medications[${medicationId}][unit]" value="${data.unit}">
                                <input type="hidden" name="medications[${medicationId}][sale_price]" value="${data.sale_price}">
                                <input type="hidden" name="medications[${medicationId}][purchase_price]" value="${data.purchase_price}">
                            </tr>
                    `);

                    const newRowInput = $('.medication-table tbody').find(`tr[data-medication-id="${medicationId}"] .quantity`);
                    newRowInput.on('input', function () {
                        updateTotalPrice($(this), medicationId); // Pass medicationId to update hidden inputs
                        calculateGrandTotal();
                    });

                    updateRowNumbers();
                    calculateGrandTotal();
                },
                error: function () {
                    Swal.fire('Lỗi!', 'Không thể tải dữ liệu thuốc.', 'error');
                }
            });
        });

        // Hàm cập nhật thành tiền
        function updateTotalPrice(input, medicationId) {
            let quantity = parseInt(input.val()) || 1;

            if (quantity <= 1) {
                quantity = 1;
                input.val(1);
            }

            const row = input.closest('tr'); // Lấy hàng chứa input
            const unitPrice = parseFloat(row.find('.unit-price').text().replace(/\./g, '')); // Lấy giá đơn vị
            const totalPrice = quantity * unitPrice; // Tính thành tiền

            row.find('.price').text(number_format(totalPrice)); // Cập nhật thành tiền
        }

        // Hàm tính tổng thành tiền
        function calculateGrandTotal() {
            let grandTotal = 0;

            $('.medication-table tbody .price').each(function () {
                const priceText = $(this).text().replace(/\./g, ''); // Loại bỏ dấu "." và " VNĐ"
                const price = parseFloat(priceText) || 0; // Chuyển đổi thành số, mặc định là 0 nếu không hợp lệ
                grandTotal += price; // Cộng dồn vào tổng
            });

            // Cập nhật vào span có class abc
            $('#totalPrice').text(number_format(grandTotal));
        }

        // Hàm đánh số thứ tự
        function updateRowNumbers() {
            $('.medication-table tbody tr').each(function (index) {
                $(this).find('td:first').text(index + 1);
            });
        }

        // Hàm format số tiền
        function number_format(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Hàm xóa thuốc
        function removeMedication(medicationId) {
            $('.medication-table tbody').find(`tr[data-medication-id="${medicationId}"]`).remove();
            updateRowNumbers();
            calculateGrandTotal(); // Tính lại tổng khi xóa
        }

        $('#addInvoices').on('click', function () {
            const formData = $('#createInvoicesForm').serialize();

            $.ajax({
                url: '/invoices',
                method: 'POST',
                data: formData,
                success: function (response) {
                    Swal.fire({
                        title: 'Thành công!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'Xác nhận'
                    }).then(() => {
                        window.location.href = '/invoices/' + response.data.id + '?patient_id=' + +$('input[name="patient_id"]').val();
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

        $('#addPrescription').on('click', function () {
            const prescriptionId = $('#selectPrescription').val();

            $.ajax({
                url: '/prescriptions/' + prescriptionId,
                method: 'GET',
                success: function (data) {
                    $('.medication-table tbody').empty();

                    if (data.prescribedMedications && Array.isArray(data.prescribedMedications)) {
                        data.prescribedMedications.forEach(function (medication) {

                            $('.medication-table tbody').append(`
                                <tr class="text-center" data-medication-id="${medication.id}">
                                    <td></td>
                                    <td>${medication.name}</td>
                                    <td>${medication.unit}</td>
                                    <td>
                                        <input type="number" class="form-control quantity" name="medications[${medication.id}][quantity]" min="1" value="${medication.quantity}">
                                    </td>
                                    <td class="unit-price">${number_format(medication.sale_price)}</td>
                                    <td class="price">${number_format(medication.sale_price)}</td>
                                    <td>
                                        <input type="text" class="form-control dosage_instructions" name="medications[${medication.id}][dosage_instructions]" value="${medication.dosage_instructions}" placeholder="Nhập cách sử dụng">
                                    </td>
                                    <td>
                                        <button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Xóa" onclick="removeMedication(${medication.id});">
                                        <i class="fa fa-lg fa-fw fa-trash"></i>
                                        </button>
                                    </td>
                                    <input type="hidden" name="medications[${medication.id}][id]" value="${medication.id}">
                                    <input type="hidden" name="medications[${medication.id}][name]" value="${medication.name}">
                                    <input type="hidden" name="medications[${medication.id}][unit]" value="${medication.unit}">
                                    <input type="hidden" name="medications[${medication.id}][sale_price]" value="${medication.sale_price}">
                                    <input type="hidden" name="medications[${medication.id}][purchase_price]" value="${medication.purchase_price}">
                                </tr>
                            `);

                            const newRowInput = $('.medication-table tbody').find(`tr[data-medication-id="${medication.id}"] .quantity`);
                            newRowInput.on('input', function () {
                                updateTotalPrice($(this), medication.id); // Pass medicationId to update hidden inputs
                                calculateGrandTotal();
                            });
                        })
                    }

                    updateRowNumbers();

                    calculateGrandTotal();
                },
                error: function () {
                    Swal.fire('Lỗi!', 'Không thể tải dữ liệu thuốc.', 'error');
                }
            });
        });
    </script>

@stop
