@extends('adminlte::page')
@section('plugins.TempusDominusBs4', true)

@section('title', 'Danh sách thuốc')

@section('content_header')
    <h1>Danh sách thuốc</h1>
@stop

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tìm kiếm</h3>
                </div>
                <form action="{{route('medications.index')}}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card-body">
                                <x-adminlte-input name="name" label="Tên thuốc" placeholder="Nhập tên thuốc" value="{{ old('name', request('name')) }}"></x-adminlte-input>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-body">
                                <label for="">Lọc</label>
                                <div class="form-group">
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input" type="radio" name="status" value="all" id="radio1" {{ (request('status') === 'all' || request('status') === null || request('status') === '') ? 'checked' : '' }}
                                        data-bs-toggle="tooltip" title="Tất cả các loại thuốc.">
                                        <label class="form-check-label ms-2" for="radio1">Tất cả</label>
                                    </div>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input" type="radio" name="status" value="out_of_stock" id="radio2" {{ request('status') === 'out_of_stock' ? 'checked' : '' }} data-bs-toggle="tooltip" title="Thuốc sắp hết hàng có số lượng nhỏ hơn 10.">
                                        <label class="form-check-label ms-2" for="radio2">Thuốc sắp hết hàng</label>
                                    </div>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input" type="radio" id="radio3" name="status" value="expired" {{ request('status') === 'expired' ? 'checked' : '' }} data-bs-toggle="tooltip" title="Thuốc sắp hết hạn còn hạn trong vòng 2 tháng tới.">
                                        <label class="form-check-label ms-2" for="radio3">Thuốc sắp hết hạn</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card-body">

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
            <button data-toggle="modal" data-target="#createMedicationModal" type="button" class="btn btn-success mb-3 mx-1">
                <i class="fa fa-lg fa-fw fa-plus-circle"></i>Tạo mới
            </button>
            <button type="button" class="btn btn-primary mb-3 mx-1"
                    onclick="window.location.href='{{ url('/medications/export?' . http_build_query(request()->all())) }}'">
                <i class="fa fa-lg fa-fw fa-download"></i>Export
            </button>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-bordered">
                                <thead>
                                <tr class="text-center">
                                    <th>ID</th>
                                    <th>Tên thuốc</th>
                                    <th>Đơn vị</th>
                                    <th>Số lượng tồn kho</th>
                                    <th>Giá nhập (VNĐ)</th>
                                    <th>Giá bán (VNĐ)</th>
                                    <th>Nhà sản xuất</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($medications as $medication)
                                    <tr class="text-center">
                                        <td>{{ $medication->id }}</td>
                                        <td>{{ $medication->name }}</td>
                                        <td>{{ $medication->unit }}</td>
                                        <td>{{ $medication->stock }}</td>
                                        <td>{{ number_format($medication->purchase_price, 0, ',', '.') }}</td>
                                        <td>{{ number_format($medication->sale_price, 0, ',', '.') }}</td>
                                        <td>{{ $medication->manufacturer }}</td>
                                        <td>
                                            <button data-id="{{ $medication->id }}" class="btn btn-xs btn-default text-info mx-1 shadow view-details" title="Chi tiết">
                                                <i class="fa fa-lg fa-fw fa-eye"></i>
                                            </button>
                                            <button data-id="{{ $medication->id }}" class="btn btn-xs btn-default text-primary mx-1 shadow edit-patient" title="Cập nhật">
                                                <i class="fa fa-lg fa-fw fa-pen"></i>
                                            </button>
                                            <button class="btn btn-xs btn-default text-danger mx-1 shadow" onclick="confirmDelete({{ $medication->id }});" title="Xóa">
                                                <i class="fa fa-lg fa-fw fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                                {{-- Nút về đầu --}}
                                @if ($medications->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link">«</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $medications->previousPageUrl() }}">«</a></li>
                                @endif

                                {{-- Hiển thị trang đầu tiên --}}
                                @if ($medications->currentPage() > 6)
                                    <li class="page-item"><a class="page-link" href="{{ $medications->url(1) }}">1</a></li>
                                    @if ($medications->currentPage() > 7)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                @endif

                                {{-- Hiển thị các trang xung quanh trang hiện tại (±5 trang) --}}
                                @for ($i = max(1, $medications->currentPage() - 5); $i <= min($medications->lastPage(), $medications->currentPage() + 5); $i++)
                                    @if ($i == $medications->currentPage())
                                        <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $medications->url($i) }}">{{ $i }}</a></li>
                                    @endif
                                @endfor

                                {{-- Hiển thị trang cuối --}}
                                @if ($medications->currentPage() < $medications->lastPage() - 5)
                                    @if ($medications->currentPage() < $medications->lastPage() - 6)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                    <li class="page-item"><a class="page-link" href="{{ $medications->url($medications->lastPage()) }}">{{ $medications->lastPage() }}</a></li>
                                @endif

                                {{-- Nút sang trang sau --}}
                                @if ($medications->hasMorePages())
                                    <li class="page-item"><a class="page-link" href="{{ $medications->nextPageUrl() }}">»</a></li>
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

    <!-- Info Modal -->
    <div class="modal fade" id="medicationDetailModal" tabindex="-1" role="dialog" aria-labelledby="medicationDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="medicationDetailModalLabel">Thông tin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div id="medication-details"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- create Modal -->
    <div class="modal fade" id="createMedicationModal" tabindex="-1" role="dialog" aria-labelledby="createMedicationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="patientDetailModalLabel">Tạo mới</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-primary">
                        <div class="card-body">
                            <form id="createMedicationForm">
                                @csrf
                                @method('POST')
                                <div class="form-group">
                                    <label for="name">Tên thuốc<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input id="name" name="name" class="form-control" placeholder="Nhập tên thuốc">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <div class="input-group input-group-sm">
                                        <textarea id="description" name="description" class="form-control" rows="6" placeholder="Nhập mô tả"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="unit">Đơn vị<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input id="unit" name="unit" class="form-control" placeholder="Nhập đơn vị">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="stock">Số lượng tồn kho<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input id="stock" name="stock" class="form-control" placeholder="Nhập số lượng tồn kho">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="purchase_price">Giá nhập<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input id="purchase_price" name="purchase_price" class="form-control" placeholder="Nhập giá nhập">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="sale_price">Giá bán<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input id="sale_price" name="sale_price" class="form-control" placeholder="Nhập giá bán">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="manufacturer">Nhà sản xuất</label>
                                    <div class="input-group">
                                        <input id="manufacturer" name="manufacturer" class="form-control" placeholder="Nhập nhà sản xuất">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="expiry_date_create">Hạn sử dụng</label>
                                    <div class="input-group">
                                        <input id="expiry_date_create" name="expiry_date" data-target="#expiry_date_create"
                                               data-toggle="datetimepicker" class="form-control datetimepicker" placeholder="DD-MM-YYYY">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input name="print_invoice" class="custom-control-input" type="checkbox" id="print-voice-create" value="1" checked>
                                            <label for="print-voice-create" class="custom-control-label">In hóa đơn</label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button id="createMedicationBtn" type="button" class="btn btn-success">Tạo</button>
                </div>
            </div>
        </div>
    </div>

    <!-- edit Modal -->
    <div class="modal fade" id="editPatientModal" tabindex="-1" role="dialog" aria-labelledby="editPatientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="patientDetailModalLabel">Cập nhật thông tin thuốc</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-primary">
                        <div class="card-body">
                            <form method="POST" id="editPatientForm">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="name">Tên thuốc<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input id="name" name="name" class="form-control" placeholder="Nhập tên thuốc">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <div class="input-group input-group-sm">
                                        <textarea id="description" name="description" class="form-control" rows="6" placeholder="Nhập mô tả"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="unit">Đơn vị<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input id="unit" name="unit" class="form-control" placeholder="Nhập đơn vị">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="stock">Số lượng tồn kho<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input id="stock" name="stock" class="form-control" placeholder="Nhập số lượng tồn kho">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="purchase_price">Giá nhập<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input id="purchase_price" name="purchase_price" class="form-control" placeholder="Nhập giá nhập">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="sale_price">Giá bán<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input id="sale_price" name="sale_price" class="form-control" placeholder="Nhập giá bán">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="manufacturer">Nhà sản xuất</label>
                                    <div class="input-group">
                                        <input id="manufacturer" name="manufacturer" class="form-control" placeholder="Nhập nhà sản xuất">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="expiry_date">Hạn sử dụng</label>
                                    <div class="input-group">
                                        <input id="expiry_date" name="expiry_date" data-target="#expiry_date"
                                               data-toggle="datetimepicker" class="form-control datetimepicker" placeholder="DD-MM-YYYY">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input name="print_invoice" class="custom-control-input" type="checkbox" id="print_invoice" value="1">
                                            <label for="print_invoice" class="custom-control-label">In hóa đơn</label>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button id="updatePatientBtn" type="button" class="btn btn-primary">Cập nhật</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        pre {
            font-size: 100% !important;
        }
    </style>
@stop

@section('js')
    {{--    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>--}}
    <script src="/vendor/sweetalert2/sweetalert2.js"></script>

    <script type="application/javascript">
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        $('#expiry_date_create').datetimepicker({
            format: 'DD-MM-YYYY'
        });

        $('#expiry_date').datetimepicker({
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
                        url: `/medications/${patientId}`,
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

        $(document).ready(function(){
            $('.view-details').on('click', function () {
                const patientId = $(this).data('id');

                $.ajax({
                    url: '/medications/' + patientId,
                    method: 'GET',
                    success: function (data) {
                        const details = [
                            {icon: 'fa-pills', label: 'Tên Thuốc', value: data.name},
                            {icon: 'fa-file-alt', label: 'Mô Tả', value: data.description},
                            {icon: 'fa-box', label: 'Đơn Vị', value: data.unit},
                            {icon: 'fa-cubes', label: 'Số Lượng', value: data.stock},
                            {icon: 'fa-dollar-sign', label: 'Giá nhập', value: new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(data.purchase_price)},
                            {icon: 'fa-dollar-sign', label: 'Giá bán', value: new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(data.sale_price)},
                            {icon: 'fa-calendar-times', label: 'Hạn sử dụng', value: data.expiry_date},
                            {icon: 'fa-industry', label: 'Nhà Sản Xuất', value: data.manufacturer},
                            {icon: 'fa-receipt', label: 'In hóa đơn', value: data.print_invoice ? 'Có' : 'Không'},
                        ];

                        const htmlContent = details.map(detail => `
                            <strong><i class="fas ${detail.icon} mr-1"></i> ${detail.label}</strong>
                            <pre class="text-muted">${detail.value ? detail.value : ''}</pre>
                            <hr>
                        `).join('');

                        $('#medication-details').html(htmlContent);
                        $('#medicationDetailModal').modal('show');
                    },
                    error: function () {
                        Swal.fire('Lỗi!', 'Không thể tải dữ liệu bệnh nhân.', 'error');
                    }
                });
            });

            $('.edit-patient').on('click', function () {
                const patientId = $(this).data('id');
                const modal = $('#editPatientModal');

                $.ajax({
                    url: '/medications/' + patientId + '/edit',
                    method: 'GET',
                    success: function (data) {
                        const fields = ['name','description','unit','purchase_price','sale_price','stock','manufacturer','expiry_date','dosage_instructions','side_effects', 'print_invoice'];
                        fields.forEach(function (field) {
                            if(field === 'print_invoice'){
                                if(data[field]) {
                                    $('#editPatientForm #' + field).prop('checked', true);
                                } else {
                                    $('#editPatientForm #' + field).prop('checked', false);
                                }
                            }else{
                                $('#editPatientForm #' + field + '').val(data[field]);
                            }

                        });
                        modal.data('id', patientId);
                        modal.modal('show');
                    },
                    error: function () {
                        Swal.fire('Lỗi!', 'Không thể tải dữ liệu bệnh nhân.', 'error');
                    }
                });
            });

            $('#updatePatientBtn').on('click', function() {
                const patientId = $('#editPatientModal').data('id');;
                const formData = $('#editPatientForm').serialize();

                $.ajax({
                    url: '/medications/' + patientId,
                    method: 'PUT',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            title: 'Thành công!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Xác nhận'
                        }).then(() => {
                            $('#editPatientModal').modal('hide');
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessages = '';

                        $.each(errors, function(key, messages) {
                            errorMessages += messages[0];
                        });

                        Swal.fire({
                            title: 'Lỗi!',
                            html: errorMessages,
                            icon: 'error',
                            confirmButtonText: 'Xác nhận'
                        });
                    }
                });
            });

            $('#createMedicationBtn').on('click', function() {
                const formData = $('#createMedicationForm').serialize();

                $.ajax({
                    url: '/medications',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            title: 'Thành công!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Xác nhận'
                        }).then(() => {
                            $('#createMedicationModal').modal('hide');
                            location.reload();
                        });
                    },
                    error: function(xhr) {
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
            });
        });
    </script>
@stop
