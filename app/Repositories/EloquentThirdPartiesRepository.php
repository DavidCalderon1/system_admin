<?php

namespace App\Repositories;

use App\Models\ThirdParties;
use App\Repositories\Interfaces\ThirdPartiesRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * Class EloquentThirdPartiesRepository
 * @package App\Repositories
 */
class EloquentThirdPartiesRepository implements ThirdPartiesRepositoryInterface
{
    /**
     * @var ThirdParties
     */
    protected $thirdParties;

    /**
     * EloquentThirdPartiesRepository constructor.
     * @param ThirdParties $thirdParties
     */
    public function __construct(ThirdParties $thirdParties)
    {
        $this->thirdParties = $thirdParties;
    }

    /**
     * @param int $thirdId
     * @return array
     */
    public function get(int $thirdId): array
    {
        return $this->thirdParties->with('country', 'state', 'city')
            ->where('id', $thirdId)
            ->first()
            ->toArray();
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return $this->thirdParties->where('id', $id)->update([
            'identity_type' => strtoupper($data['identity_type']),
            'identity_number' => strtoupper($data['identity_number']),
            'type_person' => strtoupper($data['type_person']),
            'type' => strtoupper($data['type']),
            'name' => strtoupper($data['name']),
            'last_name' => strtoupper($data['last_name']),
            'address' => strtoupper($data['address']),
            'country_id' => strtoupper($data['country_id']),
            'state_id' => strtoupper($data['state_id']),
            'city_id' => strtoupper($data['city_id']),
            'phone_number' => strtoupper($data['phone_number']),
            'phone_extension' => strtoupper($data['phone_extension']),
            'email' => strtoupper($data['email']),
            'description' => strtoupper($data['description']),
        ]);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deactivate(int $id): bool
    {
        return $this->thirdParties->where('id', $id)->update([
            'status' => 0
        ]);
    }

    /**
     * @param int $length
     * @param string $orderBy
     * @param string $orderByDir
     * @param string $searchValue
     * @return array
     */
    public function getPagination(int $length, string $orderBy, string $orderByDir, string $searchValue): array
    {
        if($orderBy === 'type_trans'){
            $orderBy = 'type';
        }

        $thirds = $this->thirdParties->select('id', 'name', 'type', 'last_name', 'identity_number', 'email', 'phone_number', 'identity_type', 'status')
            ->where('status', 1)
            ->where(function ($query) use ($searchValue) {
                $query->orwhere('name', 'LIKE', "%{$searchValue}%")
                    ->orWhere('last_name', "LIKE", "%{$searchValue}%")
                    ->orWhere('identity_number', "LIKE", "%{$searchValue}%")
                    ->orWhere('identity_type', "LIKE", "%{$searchValue}%")
                    ->orWhere('email', "LIKE", "%{$searchValue}%")
                    ->orWhere('phone_number', "LIKE", "%{$searchValue}%")
                    ->orWhere('type', "LIKE", "%{$searchValue}%");
            })
            ->orderBy($orderBy, $orderByDir);

        return $thirds->paginate($length)->toArray();
    }

    /**
     * @param $data
     * @return ThirdParties
     */
    public function store($data): ThirdParties
    {
        return $this->thirdParties->create([
            'identity_type' => strtoupper($data['identity_type']),
            'identity_number' => strtoupper($data['identity_number']),
            'type_person' => strtoupper($data['type_person']),
            'type' => strtoupper($data['type']),
            'name' => strtoupper($data['name']),
            'last_name' => strtoupper($data['last_name']),
            'address' => strtoupper($data['address']),
            'country_id' => strtoupper($data['country_id']),
            'state_id' => strtoupper($data['state_id']),
            'city_id' => strtoupper($data['city_id']),
            'phone_number' => strtoupper($data['phone_number']),
            'phone_extension' => strtoupper($data['phone_extension']),
            'email' => strtoupper($data['email']),
            'description' => strtoupper($data['description']),
        ]);
    }

    /**
     * @param string $filter
     * @return array
     */
    public function filterClientByIdentityNumber(string $filter): array
    {
        $persons = $this->thirdParties->where('type', 'client')
            ->where('identity_number', 'LIKE', "%{$filter}%")
            ->get();

        if (empty($persons)) {
            return [];
        }

        return $this->transformThirdDataSelect($persons->toArray());
    }

    /**
     * @param string $filter
     * @return array
     */
    public function filterProviderByIdentityNumberOrName(string $filter): array
    {
        $persons = $this->thirdParties->with('country', 'city')->where('type', 'provider')
            ->where(function ($query) use ($filter) {
                $query->where('identity_number', 'LIKE', "%{$filter}%")
                    ->orWhere('name', 'LIKE', "%{$filter}%");
            })->get();

        if (empty($persons)) {
            return [];
        }

        return $this->transformThirdDataSelect($persons->toArray());
    }

    /**
     * @param array $persons
     * @return array
     */
    private function transformThirdDataSelect(array $persons): array
    {
        foreach ($persons as $key => $person) {
            $persons[$key]['text'] = $person['identity_number'] . ' - ' . $person['name'] . ' ' . $person['last_name'];
            $ext = (!empty($person['phone_extension'])) ? ' Ext: ' . $person['phone_extension'] : '';
            $persons[$key]['contacts'] = [
                $person['email'],
                $person['phone_number'] . $ext,
            ];
        }

        return $persons;
    }
}
