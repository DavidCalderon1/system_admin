<?php

namespace App\Http\Controllers\User;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\View\View;

/**
 * Class UserCreateController
 * @package App\Http\Controllers\User
 */
class UserUpdateController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * @var RoleRepositoryInterface
     */
    protected $roleRepository;

    /**
     * @var PermissionRepositoryInterface
     */
    protected $permissionRepository;

    /**
     * UserUpdateController constructor.
     * @param UserRepositoryInterface $userRepository
     * @param RoleRepositoryInterface $roleRepository
     * @param PermissionRepositoryInterface $permissionRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        RoleRepositoryInterface $roleRepository,
        PermissionRepositoryInterface $permissionRepository
    )
    {
        $this->middleware('auth');
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @param int $userId
     * @return View
     */
    public function edit(int $userId): View
    {
        if (!$this->hasPermission(PermissionsConstants::USER_UPDATE)) {
            abort(404);
        }

        $user = $this->userRepository->get($userId);
        $roles = $this->roleRepository->getAll();
        $permissions = $this->permissionRepository->getAll();

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    /**
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StoreUserRequest $request)
    {
        try {
            if (!$this->hasPermission(PermissionsConstants::USER_UPDATE)) {
                return redirect()->back()->withErrors([__('permissions.unauthorized')]);
            }

            $data = $request->validated();
            $data['roles'] = (!empty($data['roles'])) ? $data['roles'] : [];
            $data['permissions'] = (!empty($data['permissions'])) ? $data['permissions'] : [];

            $saved = $this->userRepository->update($request->get('id'), $data);

            if (!$saved) {
                return redirect()->back()->withErrors([__('users.error_update')]);
            }

            $user = $this->userRepository->get($request->get('id'));
            $this->updateRoles($user, $data['roles']);
            $this->updatePermissions($user, $data['permissions']);

            return redirect(route('users.index'))->with('message', __('users.success_update'));

        } catch (\Exception $exception) {
            return redirect()->back()->withErrors([$exception->getMessage()]);
        }
    }

    /**
     * @param User $user
     * @param array $rolesIds
     */
    private function updateRoles(User $user, array $rolesIds): void
    {
        if (!empty($rolesIds)) {
            $roles = $this->roleRepository->getByListIds($rolesIds);
            $this->userRepository->updateRoles($user, $roles);
        } elseif (!$this->isUserAdmin($user)) {
            $this->userRepository->cleanRoles($user);
        }
    }

    /**
     * @param User $user
     * @param array $permissionsIds
     */
    private function updatePermissions(User $user, array $permissionsIds): void
    {
        if (!empty($permissionsIds)) {
            $permissions = $this->permissionRepository->getByListIds($permissionsIds);
            $this->userRepository->updatePermissions($user, $permissions);
        } elseif (!$this->isUserAdmin($user)) {
            $this->userRepository->cleanPermissions($user);
        }
    }

    /**
     * @param User $user
     * @return bool
     */
    private function isUserAdmin(User $user): bool
    {
        return $user->id == PermissionsConstants::ROLE_ADMIN_ID;
    }
}
