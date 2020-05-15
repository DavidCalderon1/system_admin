<?php

namespace App\Repositories\Interfaces;

/**
 * Interface ProductWarehouseRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface ProductWarehouseRepositoryInterface
{
    /**
     * @param $filter
     * @return array
     */
    public function filterForSelect($filter): array;

    /**
     * @param array $products
     * @return array
     */
    public function getProductForSelect(array $products): array;

    /**
     * @param array $product
     * @return array
     */
    public function mergeWarehousesProduct(array $product): array;

}
