<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class InvoiceItem.
 *
 * @package namespace App\Models;
 */
class InvoiceItem extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_id',
        'medication_id',
        'quantity',
        'purchase_price',
        'sale_price',
        'total_price',
        'dosage_instructions',
    ];

    protected array $dates = ['deleted_at'];

    public function medication(): HasOne
    {
        return $this->hasOne(Medication::class, 'id', 'medication_id')->withTrashed();
    }

}
