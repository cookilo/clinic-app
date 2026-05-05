@extends('adminlte::page')
@section('plugins.TempusDominusBs4', true)

@section('title', 'Danh sách bệnh nhân')

@section('content_header')
    <h1>Danh sách bệnh nhân</h1>
@stop

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tìm kiếm bệnh nhân</h3>
                </div>
                <form action="{{route('patients.store')}}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card-body">
                                <x-adminlte-input name="full_name" label="Tên bệnh nhân" placeholder="Nhập tên bệnh nhân" value="{{ old('full_name', request('full_name')) }}"></x-adminlte-input>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-body">
                                <x-adminlte-input name="parent_name" label="Tên bố mẹ"
                                                  placeholder="Nhập tên bố mẹ" value="{{ old('parent_name', request('parent_name')) }}"></x-adminlte-input>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-body">
                                <x-adminlte-input name="phone" label="Số điện thoại"
                                                  placeholder="Nhập số điện thoại liên hệ" value="{{ old('phone', request('phone')) }}"></x-adminlte-input>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="date_of_birth">Ngày tháng năm sinh</label>
                                    <input id="date_of_birth_search" name="date_of_birth" class="form-control"
                                           value="{{ old('date_of_birth', request('date_of_birth')) }}"
                                           data-target="#date_of_birth_search" data-toggle="datetimepicker" placeholder="Nhập ngày tháng năm sinh">
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
            <button data-toggle="modal" data-target="#createPatientModal" type="button" class="btn btn-success mb-3 mx-1">
                <i class="fa fa-lg fa-fw fa-plus-circle"></i>Tạo mới
            </button>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-bordered">
                                <thead>
                                <tr class="text-center">
                                    <th>ID</th>
                                    <th>Tên bệnh nhân</th>
                                    <th>Ngày tháng năm sinh</th>
                                    <th>Tên Bố mẹ</th>
                                    <th>Số điện thoại</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($patients as $patient)
                                    <tr class="text-center">
                                        <td>{{ $patient->id }}</td>
                                        <td>{{ $patient->full_name }}</td>
                                        <td>{{ $patient->date_of_birth }}</td>
                                        <td>{{ $patient->parent_name }}</td>
                                        <td>{{ \App\Helpers\PhoneHelper::formatPhoneNumber($patient->phone) }}</td>
                                        <td>
                                            <button data-id="{{ $patient->id }}" class="btn btn-xs btn-default text-info mx-1 shadow view-details" title="Chi tiết">
                                                <i class="fa fa-lg fa-fw fa-eye"></i>
                                            </button>
                                            <button data-id="{{ $patient->id }}" class="btn btn-xs btn-default text-primary mx-1 shadow edit-patient" title="Cập nhật">
                                                <i class="fa fa-lg fa-fw fa-pen"></i>
                                            </button>
                                            <button class="btn btn-xs btn-default text-danger mx-1 shadow" onclick="confirmDelete({{ $patient->id }});" title="Xóa">
                                                <i class="fa fa-lg fa-fw fa-trash"></i>
                                            </button>
                                            <a class="btn btn-xs btn-default text-secondary mx-1 shadow history-patient" target="_blank" href="{{ route('invoices.index', ['patient_id' => $patient->id]) }}">
                                                <i class="fa fa-lg fa-fw fa-calendar-check"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>

                        </div>
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                                {{-- Nút về đầu --}}
                                @if ($patients->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link">«</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $patients->previousPageUrl() }}">«</a></li>
                                @endif

                                {{-- Hiển thị trang đầu tiên --}}
                                @if ($patients->currentPage() > 6)
                                    <li class="page-item"><a class="page-link" href="{{ $patients->url(1) }}">1</a></li>
                                    @if ($patients->currentPage() > 7)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                @endif

                                {{-- Hiển thị các trang xung quanh trang hiện tại (±5 trang) --}}
                                @for ($i = max(1, $patients->currentPage() - 5); $i <= min($patients->lastPage(), $patients->currentPage() + 5); $i++)
                                    @if ($i == $patients->currentPage())
                                        <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $patients->url($i) }}">{{ $i }}</a></li>
                                    @endif
                                @endfor

                                {{-- Hiển thị trang cuối --}}
                                @if ($patients->currentPage() < $patients->lastPage() - 5)
                                    @if ($patients->currentPage() < $patients->lastPage() - 6)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                    <li class="page-item"><a class="page-link" href="{{ $patients->url($patients->lastPage()) }}">{{ $patients->lastPage() }}</a></li>
                                @endif

                                {{-- Nút sang trang sau --}}
                                @if ($patients->hasMorePages())
                                    <li class="page-item"><a class="page-link" href="{{ $patients->nextPageUrl() }}">»</a></li>
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

    <!-- create Modal -->
    <div class="modal fade" id="createPatientModal" tabindex="-1" role="dialog" aria-labelledby="createPatientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="patientDetailModalLabel">Tạo bệnh nhân mới</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-primary">
                        <div class="card-body">
                            <form id="createPatientForm">
                                @csrf
                                @method('POST')

                                <x-adminlte-input name="full_name" label="Tên bệnh nhân"
                                                  placeholder="Nhập tên bệnh nhân"></x-adminlte-input>

                                <div class="form-group">
                                    <label for="date_of_birth_create">Ngày tháng năm sinh</label>
                                    <div class="input-group">
                                        <input id="date_of_birth_create" name="date_of_birth" data-target="#date_of_birth_create" data-toggle="datetimepicker" class="form-control datetimepicker" placeholder="DD-MM-YYYY">
                                    </div>
                                </div>

                                <x-adminlte-select name="gender" label="Giới tính">
                                    <option value="male">Nam</option>
                                    <option value="female">Nữ</option>
                                </x-adminlte-select>

                                <x-adminlte-textarea name="medical_history" label="Tiền sử bệnh tật" rows=6
                                                     igroup-size="sm" placeholder="Nhập tiền sử bệnh tật"
                                ></x-adminlte-textarea>


                                <x-adminlte-textarea name="chronic_conditions" label="Bệnh mãn tính" rows=6
                                                     igroup-size="sm" placeholder="Nhập các bệnh mãn tĩnh"
                                ></x-adminlte-textarea>

                                <x-adminlte-textarea name="allergies" label="Dị ứng" rows=6 igroup-size="sm"
                                                     placeholder="Nhập các thuốc hoặc thực phẩm dị ứng"
                                ></x-adminlte-textarea>

                                <x-adminlte-input name="parent_name" label="Tên bố mẹ"
                                                  placeholder="Nhập tên bố mẹ"></x-adminlte-input>

                                <x-adminlte-input name="phone" label="Số điện thoại"
                                                  placeholder="Nhập số điện thoại liên hệ"></x-adminlte-input>

                                <x-adminlte-input name="address" label="Địa chỉ"
                                                  placeholder="Nhập địa chỉ "></x-adminlte-input>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button id="createPatientBtn" type="button" class="btn btn-success">Thêm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- edit Modal -->
    <div class="modal fade" id="editPatientModal" tabindex="-1" role="dialog" aria-labelledby="editPatientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="patientDetailModalLabel">Cập nhật thông tin bệnh nhân</h5>
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

                                    <x-adminlte-input name="full_name" label="Tên bệnh nhân"
                                                      placeholder="Nhập tên bệnh nhân"></x-adminlte-input>

                                    <x-adminlte-input-date name="date_of_birth" :config="['format' => 'DD-MM-YYYY']"
                                                           placeholder="DD-MM-YYYY"
                                                           label="Ngày tháng năm sinh"></x-adminlte-input-date>

                                    <x-adminlte-select name="gender" label="Giới tính">
                                        <option value="male">Nam</option>
                                        <option value="female">Nữ</option>
                                    </x-adminlte-select>

                                    <x-adminlte-textarea name="medical_history" label="Tiền sử bệnh tật" rows=6
                                                         igroup-size="sm" placeholder="Nhập tiền sử bệnh tật"
                                                        ></x-adminlte-textarea>


                                    <x-adminlte-textarea name="chronic_conditions" label="Bệnh mãn tính" rows=6
                                                         igroup-size="sm" placeholder="Nhập các bệnh mãn tĩnh"
                                                        ></x-adminlte-textarea>

                                    <x-adminlte-textarea name="allergies" label="Dị ứng" rows=6 igroup-size="sm"
                                                         placeholder="Nhập các thuốc hoặc thực phẩm dị ứng"
                                                        ></x-adminlte-textarea>

                                    <x-adminlte-input name="parent_name" label="Tên bố mẹ"
                                                      placeholder="Nhập tên bố mẹ"></x-adminlte-input>

                                    <x-adminlte-input name="phone" label="Số điện thoại"
                                                      placeholder="Nhập số điện thoại liên hệ"></x-adminlte-input>

                                    <x-adminlte-input name="address" label="Địa chỉ"
                                                      placeholder="Nhập địa chỉ "></x-adminlte-input>

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

    <!-- Medical_records Modal -->
    <div class="modal fade" id="medicalRecordsDetailModal" tabindex="-1" role="dialog" aria-labelledby="medicalRecordsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="medicalRecordsDetailModalLabel">Hồ sơ khám bệnh</h5>
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
                        url: `/patients/${patientId}`,
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

            $('.edit-patient').on('click', function () {
                const patientId = $(this).data('id');
                const modal = $('#editPatientModal');

                $.ajax({
                    url: '/patients/' + patientId + '/edit',
                    method: 'GET',
                    success: function (data) {
                        const fields = ['full_name', 'date_of_birth', 'gender', 'medical_history', 'chronic_conditions', 'allergies', 'parent_name', 'phone', 'address'];
                        fields.forEach(function (field) {
                            $('#editPatientForm #' + field + '').val(data[field]);
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
                    url: '/patients/' + patientId,
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
                            errorMessages += '<p>' + messages[0] + '</p>';
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

            $('#date_of_birth_search').datetimepicker({
                format: 'DD-MM-YYYY'
            });

            $('#date_of_birth_create').datetimepicker({
                format: 'DD-MM-YYYY'
            });

            $('#createPatientBtn').on('click', function() {
                const formData = $('#createPatientForm').serialize();

                $.ajax({
                    url: '/patients',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            title: 'Thành công!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Xác nhận'
                        }).then(() => {
                            $('#createPatientModal').modal('hide');
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
