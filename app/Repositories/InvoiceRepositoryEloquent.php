<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Invoice;

/**
 * Class InvoiceRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class InvoiceRepositoryEloquent extends BaseRepository implements InvoiceRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Invoice::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }


    public function findInvoicesByDate(string $date, array $conditions = []): Collection|array|null
    {
        try {
            // Chuyển đổi từ định dạng d-m-Y sang Y-m-d H:i:s để tìm kiếm
            $startDate = Carbon::createFromFormat('d-m-Y', $date)->startOfDay();
            $endDate = Carbon::createFromFormat('d-m-Y', $date)->endOfDay();

            // Tạo query bằng query builder
            $query = $this->model->newQuery();

            // Thêm điều kiện từ $conditions
            foreach ($conditions as $key => $value) {
                $query->where($key, $value);
            }

            // Thêm điều kiện whereBetween với created_at gốc
            $query->whereBetween('created_at', [$startDate, $endDate]);

            // Thực thi query và trả về kết quả
            return $query->orderBy('created_at', 'desc')->get();
        } catch (\Exception $e) {
            return null; // Trả về null nếu có lỗi
        }
    }

    public function search($searchParams)
    {
        return $this->model->where(function ($query) use ($searchParams) {
            $rawStartDate = !empty($searchParams['start_created_at']) ? trim($searchParams['start_created_at']) : null;
            $rawEndDate = !empty($searchParams['end_created_at']) ? trim($searchParams['end_created_at']) : null;

            if ($rawStartDate && $rawEndDate) {
                $date1 = Carbon::parse($rawStartDate);
                $date2 = Carbon::parse($rawEndDate);

                if ($date1->gt($date2)) {
                    [$date1, $date2] = [$date2, $date1];
                }

                $startDate = $date1->startOfDay();
                $endDate = $date2->endOfDay();

                $query->whereBetween('created_at', [$startDate, $endDate]);
            } elseif ($rawStartDate) {
                $startDate = Carbon::parse($rawStartDate)->startOfDay();
                $query->where('created_at', '>=', $startDate);
            } elseif ($rawEndDate) {
                $endDate = Carbon::parse($rawEndDate)->endOfDay();
                $query->where('created_at', '<=', $endDate);
            }

            if (!empty($searchParams['patient_name'])) {
                $patientName = trim($searchParams['patient_name']);
                $query->whereHas('patient', function ($q) use ($patientName) {
                    $q->where('full_name', 'LIKE', "%{$patientName}%");
                });
            }
        });
    }
}
