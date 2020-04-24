<?php

namespace App\Http\Controllers\Role;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\View\View;

/**
 * Class RoleUpdateController
 * @package App\Http\Controllers\Role
 */
class RoleUpdateController extends Controller
{
    /**
     * @var RoleRepositoryInterface
     */
    protected $roleRepository;

    /**
     * @var PermissionRepositoryInterface
     */
    protected $permissionRepository;

    /**
     * RoleUpdateController constructor.
     * @param RoleRepositoryInterface $roleRepository
     * @param PermissionRepositoryInterface $permissionRepository
     */
    public function __construct(
        RoleRepositoryInterface $roleRepository,
        PermissionRepositoryInterface $permissionRepository
    )
    {
        $this->middleware('auth');
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @param int $userId
     * @return View
     */
    public function edit(int $userId): View
    {
        if (!$this->hasPermission(PermissionsConstants::ROLE_UPDATE)) {
            abort(404);
        }

        $role = $this->roleRepository->get($userId);
        $permissions = $this->permissionRepository->getAll();

        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * @param StoreRoleRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(StoreRoleRequest $request)
    {
        try {
            if (!$this->hasPermission(PermissionsConstants::USER_UPDATE)) {
                return redirect()->back()->withErrors([__('permissions.unauthorized')]);
            }

            $data = $request->validated();
            $data['permissions'] = (!empty($data['permissions'])) ? $data['permissions'] : [];

            $saved = $this->roleRepository->update($request->get('id'), $data);

            if (!$saved) {
                return redirect()->back()->withErrors([__('roles.error_update')]);
            }

            $role = $this->roleRepository->get($request->get('id'));

            $this->updatePermissions($role, $data['permissions']);

            return redirect(route('roles.index'))->with('message', __('roles.success_update'));

        } catch (\Exception $exception) {
            return redirect()->back()->withErrors([$exception->getMessage()]);
        }
    }

    /**
     * @param Role $role
     * @param array $permissionsIds
     */
    private function updatePermissions(Role $role, array $permissionsIds): void
    {
        if (!empty($permissionsIds)) {
            $this->roleRepository->updatePermissions($role, $permissionsIds);
        } else {
            $this->roleRepository->cleanPermissions($role);
        }
    }
}
