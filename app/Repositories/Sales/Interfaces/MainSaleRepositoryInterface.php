<?php

namespace App\Repositories\Sales\Interfaces;

/**
 * Interface MainSaleRepositoryInterface
 * @package App\Repositories\Sales\Interfaces
 */
interface MainSaleRepositoryInterface
{
    /**
     * @param array $saleData
     * @param array $saleProducts
     * @param array $salePayments
     * @return array
     */
    public function create(array $saleData, array $saleProducts, array $salePayments): array;

    /**
     * @param $saleId
     * @return array
     */
    public function cancel($saleId): array;
}
