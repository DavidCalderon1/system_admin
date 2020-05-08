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
     * @param int $perPages
     * @param array $filters
     * @return mixed
     */
    public function getPagination(int $perPages, array $filters=[]): array;
}
