<?php

namespace App\Repositories\Purchases\Interfaces;

/**
 * Interface MainPurchaseRepositoryInterface
 * @package App\Repositories\Purchases\Interfaces
 */
interface MainPurchaseRepositoryInterface
{
    /**
     * @param array $purchaseData
     * @param array $purchaseProducts
     * @param array $purchasePayments
     * @return array
     */
    public function create(array $purchaseData, array $purchaseProducts, array $purchasePayments): array;

    /**
     * @param $purchaseId
     * @return array
     */
    public function cancel($purchaseId): array;
}
