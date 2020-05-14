<?php

namespace App\UsesCases\Interfaces;


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
     * @param int $saleId
     * @return mixed
     */
    public function getByIdForEdit(int $saleId):array;

    /**
     * @param int $length
     * @param string $orderBy
     * @param string $orderByDir
     * @param string $searchValue
     * @return array
     */
    public function getPagination(int $length, string $orderBy, string $orderByDir, string $searchValue): array ;
}
