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

        if (empty($users['data'])) {
            return [];
        }

        return [
            'users' => $users['data'],
            'current_page' => $users['current_page'],
            'first_page_url' => $users['first_page_url'],
            'from' => $users['from'],
            'last_page' => $users['last_page'],
            'last_page_url' => $users['last_page_url'],
            'next_page_url' => $users['next_page_url'],
            'per_page' => $users['per_page'],
            'prev_page_url' => $users['prev_page_url'],
            'to' => $users['to'],
            'total' => $users['total'],
        ];
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
     * @param array $roles
     * @return string
     */
    private function getRolesStr(array $roles): string
    {
        $rolesStr = '';
        array_walk($roles, function ($role) use (&$rolesStr) {
            $rolesStr .= $role['name'] . ', ';
        });

        return trim($rolesStr, ', ');
    }

    /**
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        return $this->user->create([
            'name' => $data['name'],
            'email' => $data['email'],
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
            'name' => $data['name'],
            'email' => $data['email'],
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
}
