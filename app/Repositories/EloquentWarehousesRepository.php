<?php

namespace App\Repositories;

use App\Models\Warehouse;
use App\Repositories\Interfaces\WarehousesRepositoryInterface;

/**
 * Class EloquentWarehousesRepository
 * @package App\Repositories
 */
class EloquentWarehousesRepository implements WarehousesRepositoryInterface
{
    /**
     * @var Warehouse
     */
    protected $warehouse;

    /**
     * EloquentWarehousesRepository constructor.
     * @param Warehouse $warehouse
     */
    public function __construct(Warehouse $warehouse)
    {
        $this->warehouse = $warehouse;
    }

    /**
     * @param int $perPage
     * @param array $filters
     * @return array
     */
    public function getPagination(int $perPage, array $filters = []): array
    {
        $warehouses = $this->warehouse->with(['country', 'state', 'city']);

        if (!empty($filters['name'])) {
            $warehouses->where('name', 'like', "%{$filters['name']}%");
        }

        if (!empty($filters['country'])) {
            $warehouses->whereHas('country', function ($query) use ($filters) {
                $query->where('name', 'LIKE', "%{$filters['country']}%");
            });
        }

        if (!empty($filters['state'])) {
            $warehouses->whereHas('state', function ($query) use ($filters) {
                $query->where('name', 'LIKE', "%{$filters['state']}%");
            });
        }

        if (!empty($filters['city'])) {
            $warehouses->whereHas('state', function ($query) use ($filters) {
                $query->where('name', 'LIKE', "%{$filters['city']}%");
            });
        }

        if (!empty($filters['address'])) {
            $warehouses->where('address', 'like', "%{$filters['address']}%");

        }

        if (!empty($filters['phone_number'])) {
            $warehouses->where('phone_number', 'like', "%{$filters['phone_number']}%");
        }

        return $warehouses->paginate($perPage)->toArray();
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->warehouse->all()->toArray();
    }

    /**
     * @param int $warehouseId
     * @return array
     */
    public function get(int $warehouseId): array
    {
        return $this->warehouse->with('country', 'state', 'city')
            ->where('id', $warehouseId)
            ->first()
            ->toArray();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        return $this->warehouse->create([
            'name' => strtoupper($data['name']),
            'address' => strtoupper($data['address']),
            'phone_number' => strtoupper($data['phone_number']),
            'country_id' => strtoupper($data['country_id']),
            'state_id' => strtoupper($data['state_id']),
            'city_id' => strtoupper($data['city_id']),
        ]);
    }

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        return $this->warehouse->where('id', $id)->update([
            'name' => strtoupper($data['name']),
            'address' => strtoupper($data['address']),
            'phone_number' => strtoupper($data['phone_number']),
            'country_id' => strtoupper($data['country_id']),
            'state_id' => strtoupper($data['state_id']),
            'city_id' => strtoupper($data['city_id']),
        ]);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->warehouse->where('id', $id)->delete();
    }

    public function hasProducts()
    {
        dd($this->warehouse->with('products')->count());
        return $this->warehouse->with('products')->count();
    }
}
