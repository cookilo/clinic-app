<?php

namespace App\Repositories;

use Carbon\Carbon;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\SalesDetails;

/**
 * Class SalesDetailsRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class SalesDetailsRepositoryEloquent extends BaseRepository implements SalesDetailsRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SalesDetails::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function search($searchParams)
    {
        return $this->model->where(function($query) use ($searchParams) {
            $rawStartDate = !empty($searchParams['start_sale_date']) ? trim($searchParams['start_sale_date']) : null;
            $rawEndDate = !empty($searchParams['end_sale_date']) ? trim($searchParams['end_sale_date']) : null;

            if ($rawStartDate && $rawEndDate) {
                $date1 = Carbon::parse($rawStartDate);
                $date2 = Carbon::parse($rawEndDate);

                if ($date1->gt($date2)) {
                    [$date1, $date2] = [$date2, $date1];
                }

                $startDate = $date1->startOfDay();
                $endDate = $date2->endOfDay();

                $query->whereBetween('sale_date', [$startDate, $endDate]);
            } elseif ($rawStartDate) {
                $startDate = Carbon::parse($rawStartDate)->startOfDay();
                $query->where('sale_date', '>=', $startDate);
            } elseif ($rawEndDate) {
                $endDate = Carbon::parse($rawEndDate)->endOfDay();
                $query->where('sale_date', '<=', $endDate);
            }

            if (!empty($searchParams['medication_id'])) {
                $query->where('medication_id', $searchParams['medication_id']);
            }

            if (!empty($searchParams['medication_name'])) {
                $query->where('medication_name', 'LIKE', "%{$searchParams['medication_name']}%");
            }
        });
    }
}
