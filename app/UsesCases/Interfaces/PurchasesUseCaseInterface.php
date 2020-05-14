<?php

namespace App\UsesCases\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface PurchasesUseCaseInterface
 * @package App\UsesCases\Interfaces
 */
interface PurchasesUseCaseInterface
{
    /**
     * @param int $purchaseId
     * @return array
     */
    public function getById(int $purchaseId): array;

    /**
     * @param $purchaseId
     * @return array
     */
    public function getByIdForEdit($purchaseId): array;

    /**
     * @param int $length
     * @param string $orderBy
     * @param string $orderByDir
     * @param string $searchValues
     * @return LengthAwarePaginator
     */
    public function getPagination(int $length, string $orderBy, string $orderByDir, string $searchValues): LengthAwarePaginator;
}
