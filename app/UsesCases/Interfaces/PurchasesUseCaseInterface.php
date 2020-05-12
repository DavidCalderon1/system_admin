<?php

namespace App\UsesCases\Interfaces;

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
    public function getById(int $purchaseId):array;

    /**
     * @param int $perPages
     * @param array $filters
     * @return array
     */
    public function getPagination(int $perPages, array $filters=[]): array;
}
