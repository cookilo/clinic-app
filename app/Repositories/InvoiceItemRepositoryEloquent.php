<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\InvoiceItem;

/**
 * Class InvoiceItemRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class InvoiceItemRepositoryEloquent extends BaseRepository implements InvoiceItemRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return InvoiceItem::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Tìm một invoice item theo invoice_id và medication_id.
     *
     * @param int $invoiceId
     * @param int $medicationId
     * @return InvoiceItem|null
     */
    public function findByInvoiceAndMedication(int $invoiceId, int $medicationId): ?InvoiceItem
    {
        return $this->model->where('invoice_id', $invoiceId)
                          ->where('medication_id', $medicationId)
                          ->first();
    }

    public function deleteByInvoiceId($invoiceId)
    {
        return $this->model->where('invoice_id', $invoiceId)->delete();
    }

}
