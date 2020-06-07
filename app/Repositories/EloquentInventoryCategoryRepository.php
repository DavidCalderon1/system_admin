<?php

namespace App\Repositories;

use App\Models\InventoryCategory;
use App\Repositories\Interfaces\InventoryCategoryRepositoryInterface;

/**
 * Class EloquentInventoryCategoryRepository
 * @package App\Repositories
 */
class EloquentInventoryCategoryRepository implements InventoryCategoryRepositoryInterface
{
    /**
     * @var InventoryCategory
     */
    protected $inventoryCategory;

    /**
     * EloquentInventoryCategoryRepository constructor.
     * @param InventoryCategory $inventoryCategory
     */
    public function __construct(InventoryCategory $inventoryCategory)
    {
        $this->inventoryCategory = $inventoryCategory;
    }

    public function getAll(): array
    {
        return $this->inventoryCategory->all()->toArray();
    }

    /**
     * @param int $length
     * @param string $orderBy
     * @param string $orderByDir
     * @param string $searchValues
     * @return array
     */
    public function getPagination(
        int $length,
        string $orderBy,
        string $orderByDir,
        string $searchValues
    ): array
    {
        $searchValues = strtoupper($searchValues);
        return $this->inventoryCategory->where('name', 'like', "%{$searchValues}%")
            ->orWhere('description', 'LIKE', "%{$searchValues}%")
            ->orderBy($orderBy, $orderByDir)
            ->paginate($length)
            ->toArray();
    }

    /**
     * @param int $id
     * @return array
     */
    public function get(int $id): array
    {
        $category = $this->inventoryCategory->where('id', $id)->first();

        return (!empty($category)) ? $category->toArray() : [];
    }


    /**
     * @param int $id
     * @param $data
     * @return mixed
     */
    public function update(int $id, $data)
    {
        return $this->inventoryCategory->where('id', $id)->update([
            'name' => strtoupper($data['name']),
            'description' => strtoupper($data['description']),
        ]);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        return $this->inventoryCategory->create([
            'name' => strtoupper($data['name']),
            'description' => strtoupper($data['description']),
        ]);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->inventoryCategory->where('id', $id)->delete();
    }
}
