<?php

namespace App\Repositories\Sales\Interfaces;

use App\Models\SalePayment;

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
}
