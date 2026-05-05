<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class SalesStatistics.
 *
 * @package namespace App\Models;
 */
class SalesStatistics extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    protected array $dates = ['sale_date'];

    public function getSaleDateAttribute($value): string
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

}
