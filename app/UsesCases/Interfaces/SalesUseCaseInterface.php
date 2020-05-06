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
}
