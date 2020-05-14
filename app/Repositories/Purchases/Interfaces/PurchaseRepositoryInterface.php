<?php

namespace App\Repositories\Purchases\Interfaces;

use App\Models\Purchase;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface InvoiceRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface PurchaseRepositoryInterface
{
    /**
     * @param int $length
     * @param string $orderBy
     * @param string $orderByDir
     * @param string $searchValue
     * @return LengthAwarePaginator
     */
    public function getPagination(int $length, string $orderBy, string $orderByDir, string $searchValue=''): LengthAwarePaginator;

    /**
     * @param $purchaseId
     * @return array
     */
    public function get($purchaseId): array;

    /**
     * @return int
     */
    public function getLastConsecutive(): int;

    /**
     * @param array $data
     * @return Purchase
     */
    public function create(array $data): Purchase;

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data):bool ;

    /**
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function changeStatus(int $id, string $status): bool;
}
