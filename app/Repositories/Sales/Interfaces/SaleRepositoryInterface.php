<?php

namespace App\Repositories\Sales\Interfaces;

use App\Models\Sale;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface InvoiceRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface SaleRepositoryInterface
{
    /**
     * @param int $length
     * @param string $orderBy
     * @param string $orderByDir
     * @param string $searchValue
     * @return array
     */
    public function getPagination(int $length, string $orderBy, string $orderByDir, string $searchValue = ''): array;

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
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function changeStatus(int $id, string $status): bool;
}
