<?php

namespace App\UsesCases\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface SalesUseCaseInterface
 * @package App\UsesCases\Interfaces
 */
interface SalesUseCaseInterface
{
    /**
     * @param int $saleId
     * @return array
     */
    public function getById(int $saleId):array;

    /**
     * @param int $length
     * @param string $orderBy
     * @param string $orderByDir
     * @param string $searchValue
     * @return LengthAwarePaginator
     */
    public function getPagination(int $length, string $orderBy, string $orderByDir, string $searchValue): LengthAwarePaginator;
}
