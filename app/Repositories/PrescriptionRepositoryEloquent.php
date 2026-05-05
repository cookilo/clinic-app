<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PrescriptionRepository;
use App\Models\Prescription;
use App\Validators\PrescriptionValidator;

/**
 * Class PrescriptionRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PrescriptionRepositoryEloquent extends BaseRepository implements PrescriptionRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Prescription::class;
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
            if (!empty($searchParams['title'])) {
                $query->orwhere('title', 'LIKE', "%{$searchParams['title']}%");
            }
        });
    }
}
