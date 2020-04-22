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
     * @return array
     */
    public function getPagination(int $perPage): array
    {
        $users = $this->user->paginate($perPage)->toArray();

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
        return $this->user->where('id', $id)->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
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
