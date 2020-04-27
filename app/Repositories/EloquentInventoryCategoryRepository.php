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

    /**
     * @param int $perPage
     * @param array $filers
     * @return array
     */
    public function getPagination(int $perPage, array $filers = []): array
    {
        $inventoryCategory = $this->inventoryCategory->select('*');

        if (!empty($filers['name'])) {
            $inventoryCategory->where('name', 'like', "%{$filers['name']}%");
        } else if (!empty($filers['description'])) {
            $inventoryCategory->where('description', 'like', "%{$filers['description']}%");
        }

        $inventoryCategory = $inventoryCategory->orderBy('name', 'asc')->paginate($perPage)->toArray();

        return (!empty($inventoryCategory['data'])) ? $inventoryCategory : [];

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
            'name' => $data['name'],
            'description' => $data['description']
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
