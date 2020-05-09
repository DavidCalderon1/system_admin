<?php

namespace App\Repositories\Purchases\Interfaces;

use App\Models\Purchase;

/**
 * Interface InvoiceRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface PurchaseRepositoryInterface
{
    /**
     * @param int $perPage
     * @param array $filers
     * @return array
     */
    public function getPagination(int $perPage, array $filers = []):array;

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
     * @param string $status
     * @return bool
     */
    public function changeStatus(int $id, string $status): bool;
}
