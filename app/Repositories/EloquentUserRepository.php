<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

/**
 * Class EloquentUserRepository
 * @package App\Repositories
 */
class EloquentUserRepository implements UserRepositoryInterface
{
    /**
     * @var User
     */
    protected $user;

    /**
     * EloquentUserRepository constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param int $perPage
     * @param array $filter
     * @return array
     */
    public function getPagination(int $perPage, array $filter = []): array
    {
        $users = $this->user->select('id', 'name', 'email')->with(['roles']);

        if (!empty($filter['name'])) {
            $users->where('name', 'like', "%{$filter['name']}%");
        } elseif (!empty($filter['email'])) {
            $users->where('email', 'like', "%{$filter['email']}%");
        }

        $users = $users->orderBy('id', 'asc')->paginate($perPage)->toArray();

        return (!empty($users['data'])) ? $users : [];
    }

    /**
     * @param $userId
     * @return User
     */
    public function get($userId): User
    {
        return $this->user->with('roles')->where('id', $userId)->first();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        return $this->user->create([
            'name' => ucwords(strtolower($data['name'])),
            'email' => strtolower($data['email']),
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        $userData = [
            'name' => ucwords(strtolower($data['name'])),
            'email' => strtolower($data['email']),
        ];

        if (!empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        return $this->user->where('id', $id)->update($userData);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        return $this->user->where('id', $id)->delete();
    }

    /**
     * @param User $user
     * @param array $rolesIds
     */
    public function addRoles(User $user, array $rolesIds): void
    {
        $user->roles()->attach($rolesIds);
    }

    /**
     * @param User $user
     * @param array $permissionsIds
     */
    public function addPermissions(User $user, array $permissionsIds): void
    {
        $user->permissions()->attach($permissionsIds);
    }

    /**
     * @param User $user
     * @param array $roles
     * @return array
     */
    public function updateRoles(User $user, array $roles): array
    {
        return $user->roles()->sync($roles);
    }

    /**
     * @param User $user
     * @return array
     */
    public function cleanRoles(User $user): array
    {
        return $this->updateRoles($user, []);
    }

    /**
     * @param User $user
     * @param array $permissionsIds
     * @return array
     */
    public function updatePermissions(User $user, array $permissionsIds): array
    {
        return $user->permissions()->sync($permissionsIds);
    }

    /**
     * @param User $user
     * @return array
     */
    public function cleanPermissions(User $user): array
    {
        return $this->updatePermissions($user, []);
    }

}
