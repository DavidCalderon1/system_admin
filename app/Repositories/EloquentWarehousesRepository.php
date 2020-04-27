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

    public function getPagination(int $perPage, array $filters = []): array
    {
        $warehouses = $this->warehouse->with(['country', 'state', 'city']);

        if (!empty($filters['name'])) {
            $warehouses->where('name', 'like', "%{$filters['name']}%");
        } elseif (!empty($filters['country'])) {
            $warehouses->whereHas('country', function ($query) use ($filters) {
                $query->where('name', 'LIKE', "%{$filters['country']}%");
            });
        } elseif (!empty($filters['state'])) {
            $warehouses->whereHas('state', function ($query) use ($filters) {
                $query->where('name', 'LIKE', "%{$filters['state']}%");
            });
        } elseif (!empty($filters['city'])) {
            $warehouses->whereHas('state', function ($query) use ($filters) {
                $query->where('name', 'LIKE', "%{$filters['city']}%");
            });
        } elseif (!empty($filters['address'])) {
            $warehouses->where('address', 'like', "%{$filters['address']}%");

        } elseif (!empty($filters['phone_number'])) {
            $warehouses->where('phone_number', 'like', "%{$filters['phone_number']}%");
        }

        return $warehouses->paginate($perPage)->toArray();
    }
}
