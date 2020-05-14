<?php

namespace App\Repositories;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\ProductWarehouseRepositoryInterface;
use App\Repositories\Interfaces\WarehousesRepositoryInterface;

/**
 * Class EloquentProductWarehouseRepository
 * @package App\Repositories
 */
class EloquentProductWarehouseRepository implements ProductWarehouseRepositoryInterface
{
    /**
     * @var
     */
    protected $productRepository;

    /**
     * @var WarehousesRepositoryInterface
     */
    protected $warehousesRepository;

    /**
     * EloquentProductWarehouseRepository constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param WarehousesRepositoryInterface $warehousesRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository, WarehousesRepositoryInterface $warehousesRepository)
    {
        $this->productRepository = $productRepository;
        $this->warehousesRepository = $warehousesRepository;
    }

    /**
     * @param $filter
     * @return array
     */
    public function filterForSelect($filter): array
    {
        $products = $this->productRepository->filter($filter);

        return $this->getProductForSelect($products);
    }

    /**
     * @param array $productsWarehouses
     * @param array $warehouses
     */
    private function pushWarehousesDiff(array &$productsWarehouses, array $warehouses): void
    {
        $warehouseIds = $this->getArrayMultiIds($warehouses);
        $productWarehouseIds = $this->getArrayMultiIds($productsWarehouses);
        $diff = array_diff($warehouseIds, $productWarehouseIds);

        foreach ($warehouses as $warehouse) {

            if (!in_array($warehouse['id'], $diff)) {
                continue;
            }

            array_push($productsWarehouses, $warehouse);
        }
    }

    /**
     * @param $warehouses
     * @return array[]
     */
    private function getWarehousesFormatSelect($warehouses)
    {
        return array_map(function ($warehouse) {
            $quantity = (empty($warehouse['pivot'])) ? 0 : $warehouse['pivot']['quantity'];

            return [
                'id' => $warehouse['id'],
                'text' => $warehouse['name'] . " = {$quantity}und",
            ];
        }, $warehouses);
    }

    /**
     * @param $a
     * @param $b
     * @return mixed
     */
    private function cmp($a, $b)
    {
        return $a["id"] - $b["id"];
    }

    /**
     * @param array $data
     * @return array
     */
    private function getArrayMultiIds(array $data): array
    {
        $ids = [];
        foreach ($data as $datum) {
            $ids[] = $datum['id'];
        }
        return $ids;
    }

    /**
     * @param array $products
     * @return array
     */
    public function getProductForSelect(array $products): array
    {
        $warehouses = $this->warehousesRepository->getAll();

        foreach ($products as $key => $product) {

            $products[$key]['id'] = $product['id'];
            $products[$key]['text'] = $product['code'] . ' - ' . $product['reference'];

            if (empty($product['warehouses'])) {
                $products[$key]['warehouses'] = $this->getWarehousesFormatSelect($warehouses);
                continue;
            }

            $this->pushWarehousesDiff($products[$key]['warehouses'], $warehouses);

            usort($products[$key]['warehouses'], [self::class, 'cmp']);
            $products[$key]['warehouses'] = $this->getWarehousesFormatSelect($products[$key]['warehouses']);
        }
        return $products;
    }
}
