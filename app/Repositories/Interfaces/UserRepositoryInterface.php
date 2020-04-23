<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

/**
 * Interface UserRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface UserRepositoryInterface
{
    /**
     * @param int $perPage
     * @param array $filer
     * @return array
     */
    public function getPagination(int $perPage, array $filer = []): array;


    /**
     * @param $userId
     * @return User
     */
    public function get($userId): User;

    /**
     * @param $data
     * @return mixed
     */
    public function store($data);

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data);

    /**
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id);

    /**
     * @param User $user
     * @param array $rolesIds
     */
    public function addRoles(User $user, array $rolesIds): void;

    /**
     * @param User $user
     * @param array $permissionsIds
     */
    public function addPermissions(User $user, array $permissionsIds): void;

    /**
     * @param User $user
     * @param array $roles
     * @return array
     */
    public function updateRoles(User $user, array $roles): array;

    /**
     * @param User $user
     * @return array
     */
    public function cleanRoles(User $user): array;

    /**
     * @param User $user
     * @param array $permissionsIds
     * @return array
     */
    public function updatePermissions(User $user, array $permissionsIds): array;

    /**
     * @param User $user
     * @return array
     */
    public function cleanPermissions(User $user): array;
}
