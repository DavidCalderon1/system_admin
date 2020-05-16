<?php

namespace App\Repositories;

use App\Models\Tax;
use App\Repositories\Interfaces\TaxesRepositoryInterface;

/**
 * Class EloquentTaxesRepository
 * @package App\Repositories
 */
class EloquentTaxesRepository implements TaxesRepositoryInterface
{
    /**
     * @var Tax
     */
    protected $tax;

    /**
     * EloquentTaxesRepository constructor.
     * @param Tax $tax
     */
    public function __construct(Tax $tax)
    {
        $this->tax = $tax;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->tax->all()->toArray();
    }

    /**
     * @param int $length
     * @param string $orderBy
     * @param string $orderByDir
     * @param string $searchValues
     * @return array
     */
    public function getPagination(int $length, string $orderBy, string $orderByDir, string $searchValues): array
    {
        $taxes = $this->tax->where('name', 'LIKE', "%{$searchValues}%")
            ->orWhere('type', 'LIKE', "%{$searchValues}%")
            ->orWhere('percentage', 'LIKE', "%{$searchValues}%")
            ->orderBy($orderBy, $orderByDir);

        return $taxes->paginate($length)->toArray();
    }

    /**
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->tax->create([
            'name' => strtoupper($data['name']),
            'type' => $data['type'],
            'percentage' => $data['percentage'],
        ])->toArray();
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return $this->tax->where('id', $id)
            ->update([
                'name' => $data['name'],
                'type' => $data['type'],
                'percentage' => $data['percentage'],
            ]);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->tax->where('id', $id)->delete();
    }
}
