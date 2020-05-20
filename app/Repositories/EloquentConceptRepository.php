<?php

namespace App\Repositories;

use App\Models\Concept;
use App\Models\CostCenter;
use App\Repositories\Interfaces\ConceptRepositoryInterface;

/**
 * Class EloquentConceptRepository
 * @package App\Repositories
 */
class EloquentConceptRepository implements ConceptRepositoryInterface
{
    /**
     * @var CostCenter
     */
    protected $concept;

    /**
     * EloquentConceptRepository constructor.
     * @param Concept $concept
     */
    public function __construct(Concept $concept)
    {
        $this->concept = $concept;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->concept->all()->toArray();
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
        $taxes = $this->concept->where('name', 'LIKE', "%{$searchValues}%")
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
        return $this->concept->create([
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
        return $this->concept->where('id', $id)
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
        return $this->concept->where('id', $id)->delete();
    }
}
