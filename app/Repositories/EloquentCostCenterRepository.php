<?php

namespace App\Repositories;

use App\Models\CostCenter;
use App\Repositories\Interfaces\CostCenterRepositoryInterface;

/**
 * Class EloquentCostCenterRepository
 * @package App\Repositories
 */
class EloquentCostCenterRepository implements CostCenterRepositoryInterface
{
    /**
     * @var CostCenter
     */
    protected $costCenter;

    /**
     * EloquentCostCenterRepository constructor.
     * @param CostCenter $costCenter
     */
    public function __construct(CostCenter $costCenter)
    {
        $this->costCenter = $costCenter;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->costCenter->all()->toArray();
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
        $taxes = $this->costCenter->where('name', 'LIKE', "%{$searchValues}%")
            ->orWhere('code', 'LIKE', "%{$searchValues}%")
            ->orderBy($orderBy, $orderByDir);

        return $taxes->paginate($length)->toArray();
    }

    /**
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->costCenter->create([
            'name' => strtoupper($data['name']),
            'code' => $data['code'],
        ])->toArray();
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return $this->costCenter->where('id', $id)
            ->update([
                'name' => strtoupper($data['name']),
                'code' => $data['code'],
            ]);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->costCenter->where('id', $id)->delete();
    }
}
