<?php

namespace App\Repositories\Sales\Interfaces;

use App\Models\Sale;

/**
 * Interface InvoiceRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface SaleRepositoryInterface
{
    /**
     * @param $saleId
     * @return array
     */
    public function get($saleId): array;

    /**
     * @return int
     */
    public function getLastConsecutive(): int;

    /**
     * @param array $data
     * @return Sale
     */
    public function create(array $data): Sale;
}
