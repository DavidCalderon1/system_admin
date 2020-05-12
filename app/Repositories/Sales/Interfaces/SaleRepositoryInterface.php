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
     * @param int $perPage
     * @param array $filers
     * @return array
     */
    public function getPagination(int $perPage, array $filers = []):array;

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

    /**
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function changeStatus(int $id, string $status): bool;
}
