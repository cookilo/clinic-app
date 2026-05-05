<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Medication.
 *
 * @package namespace App\Models;
 */
class Medication extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            'name',
            'description',
            'unit',
            'purchase_price',
            'sale_price',
            'stock',
            'manufacturer',
            'production_date',
            'expiry_date',
            'dosage_instructions',
            'side_effects',
            'print_invoice'
    ];

    protected array $dates = ['deleted_at', 'expiry_date'];

    public function prescriptions(): BelongsToMany
    {
        return $this->belongsToMany(Prescription::class, 'medication_prescription')
                    ->withPivot('quantity', 'dosage_instructions')
                    ->withTimestamps();
    }

    public function getExpiryDateAttribute($value): string
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function setExpiryDateAttribute($value)
    {
        $this->attributes['expiry_date'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
    }
}
