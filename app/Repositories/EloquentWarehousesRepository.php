<?php

namespace App\Repositories;

use App\Models\Warehouse;
use App\Repositories\Interfaces\WarehousesRepositoryInterface;
use Illuminate\Support\Facades\DB;

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
        return $this->warehouse->with(['country', 'state', 'city'])
            ->where('name', 'like', "%{$searchValues}%")
            ->orWhere('address', 'like', "%{$searchValues}%")
            ->orWhere('phone_number', 'like', "%{$searchValues}%")
            ->orWhereHas('country', function ($query) use ($searchValues) {
                $query->where(DB::raw('UPPER(name)'), 'LIKE', "%{$searchValues}%");
            })
            ->orWhereHas('state', function ($query) use ($searchValues) {
                $query->where(DB::raw('UPPER(name)'), 'LIKE', "%{$searchValues}%");
            })
            ->orWhereHas('city', function ($query) use ($searchValues) {
                $query->where(DB::raw('UPPER(name)'), 'LIKE', "%{$searchValues}%");
            })
            ->orderBy($orderBy, $orderByDir)
            ->paginate($length)
            ->toArray();
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
}
