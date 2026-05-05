<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn Thuốc</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 10px auto;
            line-height: 1.2;
        }

        .medical-table th, .medical-table td {
            border: 1px solid;
        }

        .text-center {
            text-align: center;
        }

        body {
            font-size: 12px;
        }

        h1 {
            font-size: 16px;
        }

        h2 {
            font-size: 14px;
        }
    </style>
</head>
<body>
<div>
    <h2>PHÒNG KHÁM BÁC SĨ ANH TUẤN - CKI NHI KHOA <br> Địa chỉ: Tổ 8A, KDC Nguyễn Trãi, Cầu xóm Mận, Phường Âu Cơ, Tỉnh Phú Thọ <br>SĐT: 0984-826-831</h2>
    <h1 class="text-center">ĐƠN THUỐC</h1>
    <div class="field">
        <label>Họ tên:</label>
        <span>{{ $patient->full_name}}</span>
    </div>
    <div class="field">
        <label>Năm sinh:</label>
        <span>{{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d') }}/{{ \Carbon\Carbon::parse($patient->date_of_birth)->format('m') }}/{{ \Carbon\Carbon::parse($patient->date_of_birth)->format('Y') }}</span>
    </div>
    <div class="field">
        <label>Tuổi:</label>
        @php
            $dateOfBirth = \Carbon\Carbon::parse($patient->date_of_birth);
            $ageInYears = $dateOfBirth->age;
            $ageInMonths = $dateOfBirth->diffInMonths(now());
        @endphp

        <span>
            @if ($ageInYears <= 5)
                {{ $ageInMonths }} tháng tuổi
            @else
                {{ $ageInYears }} tuổi
            @endif
        </span>
        <label>Cân nặng:</label>
        <span>{{$invoice->weight}}kg</span>
        <label>Giới tính:</label>
        <span>{{ $patient->gender === 'male' ? 'Nam' : 'Nữ' }}</span>
    </div>
    <div class="field">
        <label>Địa chỉ liên hệ:</label>
        <span>{{ $patient->address}}</span>
    </div>
    <div class="field">
        <label>Chẩn đoán:</label>
        <span>{{$invoice->diagnosis}}</span>
    </div>
    <div class="field">
        <label>Thuốc điều trị:</label>
        <table class="medical-table" style="border-collapse: collapse; width: 100%;">
            <thead>
            <tr>
                <th width="5%">STT</th>
                <th width="25%">Tên Thuốc</th>
                <th width="10%">Đơn vị</th>
                <th width="10%">Số lượng</th>
                <th width="50%">Cách dùng</th>
            </tr>
            </thead>
            <tbody>
            @php $counter = 1; @endphp
            @foreach ($invoice->invoiceItems as $item)
                <tr class="text-center">
                    <td>{{ $counter++ }}</td>
                    <td>{{ $item->medication->name }}</td>
                    <td>{{ $item->medication->unit }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->dosage_instructions}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="field">

    </div>
    <div style="width: 100%; margin-top: 20px; margin-bottom: 60px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; padding: 16px 0 60px 5px;">
                    <span>Lời dặn:</span><br>
                    <span>{{$invoice->notes}}</span><br>
                    <span>Khám lại ngay hoặc nhập viện khi:</span><br>
                    <span>- Co giật, li bì</span><br>
                    <span>- Nôn hết tất cả mọi thứ</span><br>
                    <span>- Tím tái</span><br>
                    <span>- Bỏ bú hoặc bỏ ăn</span>
                </td>
                <td style="width: 50%; padding: 16px 0 60px 5px;" class="text-center">
                    <span>Ngày {{ \Carbon\Carbon::parse($invoice->created_at)->format('d') }} Tháng {{ \Carbon\Carbon::parse($invoice->created_at)->format('m') }} Năm {{ \Carbon\Carbon::parse($invoice->created_at)->format('Y') }}</span><br>
                    <span>Bác sỹ khám bệnh</span><br><br><br><br><br><br>
                    <span style="font-size: 14px;">BS.CKI Nguyễn Anh Tuấn</span>
                </td>
            </tr>
        </table>
    </div>

    <span>- Khám lại xin mang theo đơn này.</span><br>
    <span>- Số điện thoại liên hệ: {{ \App\Helpers\PhoneHelper::formatPhoneNumber($patient->phone) }}</span><br>
    <span>- Tên bố hoặc mẹ của trẻ hoặc người đưa trẻ đến khám, chữa bệnh: {{ $patient->parent_name}}</span>
</div>
</body>
</html>
