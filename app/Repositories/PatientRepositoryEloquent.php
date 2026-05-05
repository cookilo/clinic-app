<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Patient;

/**
 * Class PatientRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PatientRepositoryEloquent extends BaseRepository implements PatientRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Patient::class;
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
            if (!empty($searchParams['full_name'])) {
                $query->orwhere('full_name', 'LIKE', "%{$searchParams['full_name']}%");
            }
            if (!empty($searchParams['phone'])) {
                $query->orwhere('phone', 'LIKE', "%{$searchParams['phone']}%");
            }
            if (!empty($searchParams['parent_name'])) {
                $query->orwhere('parent_name', 'LIKE', "%{$searchParams['parent_name']}%");
            }

            if (!empty($searchParams['date_of_birth'])) {
                $date = date('Y-m-d', strtotime($searchParams['date_of_birth']));
                $query->orwhere('date_of_birth', '=', $date);
            }
        });
    }

}
