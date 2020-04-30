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
     * @param int $perPage
     * @param array $filers
     * @return array
     */
    public function getPagination(int $perPage, array $filers = []): array
    {
        $thirds = $this->thirdParties->select('id', 'name', 'last_name', 'identity_number', 'email', 'phone_number', 'identity_type')
            ->where('status', 1);

        if (!empty($filers['name'])) {
            $thirds->where('name', 'like', "%{$filers['name']}%");
            $thirds->orWhere('last_name', 'like', "%{$filers['name']}%");
            $names = explode(' ', $filers['name']);

            if (count($names) > 1) {
                foreach ($names as $name) {
                    $thirds->orWhere('name', 'like', "%{$name}%");
                    $thirds->orWhere('last_name', 'like', "%{$name}%");
                }
            }
        } elseif (!empty($filers['last_name'])) {
            $thirds->where('last_name', 'like', "%{$filers['last_name']}%");
        } elseif (!empty($filers['identity_number'])) {
            $thirds->where('identity_number', 'like', "%{$filers['identity_number']}%");
        } elseif (!empty($filers['email'])) {
            $thirds->where('email', 'like', "%{$filers['email']}%");
        } elseif (!empty($filers['phone_number'])) {
            $thirds->where('phone_number', 'like', "%{$filers['phone_number']}%");
        } elseif (!empty($filers['identity_type'])) {
            $thirds->where('identity_type', 'like', "%{$filers['identity_type']}%");
        }

        $thirds = $thirds->orderBy('id', 'asc')->paginate($perPage)->toArray();

        return (!empty($thirds['data'])) ? $thirds : [];
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
}
