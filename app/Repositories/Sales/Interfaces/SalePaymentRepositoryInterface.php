<?php

namespace App\Repositories\Sales\Interfaces;


/**
 * Interface SalePaymentRepositoryInterface
 * @package App\Repositories\Sales\Interfaces
 */
interface SalePaymentRepositoryInterface
{
    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool;

    /**
     * @param int $saleId
     * @return bool
     */
    public function deletePaymentsBySaleId(int $saleId): bool;
}
