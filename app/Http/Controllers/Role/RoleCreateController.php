<?php

namespace App\Http\Controllers\Role;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\View\View;

/**
 * Class UserCreateController
 * @package App\Http\Controllers\User
 */
class RoleCreateController extends Controller
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
     * RoleCreateController constructor.
     * @param RoleRepositoryInterface $roleRepository
     * @param PermissionRepositoryInterface $permissionRepository
     */
    public function __construct(RoleRepositoryInterface $roleRepository, PermissionRepositoryInterface $permissionRepository)
    {
        $this->middleware('auth');
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @return View
     */
    public function create(): View
    {
        if (!$this->hasPermission(PermissionsConstants::ROLE_CREATE)) {
            abort(404);
        }

        $permissions = $this->permissionRepository->getAll();

        return view('roles.create', compact('permissions'));
    }

    /**
     * @param StoreRoleRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(StoreRoleRequest $request)
    {
        try {
            if (!$this->hasPermission(PermissionsConstants::ROLE_CREATE)) {
                abort(404);
            }

            $data = $request->validated();
            $role = $this->roleRepository->store($data);

            if (empty($role)) {
                return redirect()->back()->withInput()->withErrors([__('roles.error_create')]);
            }

            if (!empty($data['permissions'])) {
                $permissions = $this->permissionRepository->getByListIds($data['permissions']);
                $this->roleRepository->addPermissions($role, $permissions);
            }

            return redirect(route('roles.index'))
                ->with('message', __('roles.success_create'));

        } catch (\Exception $exception) {
            return redirect()->back()->withErrors([$exception->getMessage()]);
        }
    }
}
