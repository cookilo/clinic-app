<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Medication;

/**
 * Class MedicationRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class MedicationRepositoryEloquent extends BaseRepository implements MedicationRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Medication::class;
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
            if (!empty($searchParams['name'])) {
                $query->where('name', 'LIKE', "%{$searchParams['name']}%");
            }
            if (!empty($searchParams['status'])) {
                if ($searchParams['status'] === 'expired') {
                    $query->where('expiry_date', '<=', now()->addMonths(2));
                } elseif ($searchParams['status'] === 'out_of_stock') {
                    $query->where('stock', '<=', config('constants.out_of_stock_threshold'));
                }
            }
        });
    }

}
