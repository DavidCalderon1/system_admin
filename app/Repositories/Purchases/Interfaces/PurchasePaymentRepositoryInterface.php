<?php

namespace App\Repositories\Purchases\Interfaces;

/**
 * Interface PurchasePaymentRepositoryInterface
 * @package App\Repositories\Purchases\Interfaces
 */
interface PurchasePaymentRepositoryInterface
{
    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool;

    /**
     * @param int $purchaseId
     * @return bool
     */
    public function deletePaymentsByPurchaseId(int $purchaseId):bool;
}