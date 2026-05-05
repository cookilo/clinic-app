<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MedicationsExport implements FromCollection, WithHeadings, WithMapping
{
    private array $filters;

    public function __construct(array $filters = [])
    {

        $this->filters = $filters;
    }

    public function collection()
    {
        $medicationRepo = app(\App\Repositories\MedicationRepository::class);
        return $medicationRepo
            ->search($this->filters)
            ->get([
                      'id',
                      'name',
                      'stock',
                      'unit',
                      'purchase_price',
                      'sale_price',
                      'manufacturer',
                      'expiry_date',
                  ]);
    }

    public function headings(): array
    {
        return [
            'STT',
            'Tên thuốc',
            'Số lượng tồn kho',
            'Đơn vị',
            'Giá nhập (VNĐ)',
            'Giá bán (VNĐ)',
            'Nhà sản xuất',
            'Hạn sử dụng',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id ?? '',
            $row->name ?? '',
            (string)($row->stock ?? '0'),
            $row->unit ?? '',
            (string)($row->purchase_price ?? '0'),
            (string)($row->sale_price ?? '0'),
            $row->manufacturer ?? '',
            $row->expiry_date
                ? \Carbon\Carbon::parse($row->expiry_date)->format('Y-m-d')
                : '',
        ];
    }
}
