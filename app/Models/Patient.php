<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Patient.
 *
 * @package namespace App\Models;
 */
class Patient extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'parent_name',
        'date_of_birth',
        'gender',
        'phone',
        'address',
        'allergies',
        'chronic_conditions',
        'medical_history'
    ];

    protected array $dates = ['deleted_at', 'date_of_birth'];

    public function getDateOfBirthAttribute($value): string
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function setDateOfBirthAttribute($value)
    {
        $this->attributes['date_of_birth'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
    }
}
