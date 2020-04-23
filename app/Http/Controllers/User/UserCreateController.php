<?php

namespace App\Http\Controllers\User;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\View\View;

/**
 * Class UserCreateController
 * @package App\Http\Controllers\User
 */
class UserCreateController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * @var PermissionRepositoryInterface
     */
    protected $permissionRepository;

    /**
     * @var RoleRepositoryInterface
     */
    protected $roleRepository;

    /**
     * UserCreateController constructor.
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
     * @return View
     */
    public function create(): View
    {
        if (!$this->hasPermission(PermissionsConstants::USER_CREATE)) {
            abort(404);
        }

        $roles = $this->roleRepository->getAll();
        $permissions = $this->permissionRepository->getAll();

        return view('users.create', compact('roles', 'permissions'));
    }

    /**
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        try {
            if (!$this->hasPermission(PermissionsConstants::USER_CREATE)) {
                abort(404);
            }

            $data = $request->validated();

            $user = $this->userRepository->store($data);

            if (empty($user)) {
                return redirect()->back()->withInput()->withErrors([__('users.error_create')]);
            }

            if (!empty($data['roles'])) {
                $roles = $this->roleRepository->getByListIds($data['roles']);
                $this->userRepository->addRoles($user, $roles);
            }

            if (!empty($data['permissions'])) {
                $permissions = $this->permissionRepository->getByListIds($data['permissions']);
                $this->userRepository->addPermissions($user, $permissions);
            }

            return redirect(route('users.index'))
                ->with('message', __('users.success_create'));

        } catch (\Exception $exception) {
            return redirect()->back()->withErrors([$exception->getMessage()]);
        }
    }
}
