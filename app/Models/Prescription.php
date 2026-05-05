<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Prescription.
 *
 * @package namespace App\Models;
 */
class Prescription extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title'
    ];

    protected array $dates = ['deleted_at'];

    public function medications(): BelongsToMany
    {
        return $this->belongsToMany(Medication::class, 'medication_prescription', 'prescription_id', 'medication_id')
            ->withPivot('quantity','dosage_instructions', 'created_at', 'updated_at');
    }

}
