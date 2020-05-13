<?php

namespace App\Repositories\Purchases;

use App\Models\PurchasePayment;
use App\Repositories\Purchases\Interfaces\PurchasePaymentRepositoryInterface;

/**
 * Class EloquentPurchasePaymentRepository
 * @package App\Repositories\Purchases
 */
class EloquentPurchasePaymentRepository implements PurchasePaymentRepositoryInterface
{
    /**
     * @var PurchasePayment
     */
    protected $purchasePayment;

    /**
     * EloquentPurchasePaymentRepository constructor.
     * @param PurchasePayment $purchasePayment
     */
    public function __construct(PurchasePayment $purchasePayment)
    {
        $this->purchasePayment = $purchasePayment;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        return $this->purchasePayment->insert($data);
    }

    /**
     * @param int $purchaseId
     * @return bool
     */
    public function deletePaymentsByPurchaseId(int $purchaseId): bool
    {
        return $this->purchasePayment->where('purchase_id', $purchaseId)->delete();
    }
}
