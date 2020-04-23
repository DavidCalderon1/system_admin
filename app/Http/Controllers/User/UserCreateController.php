<?php

namespace App\Http\Controllers\User;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\Permission;
use App\Models\Role;
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
     * UserListController constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->middleware('auth');

        $this->userRepository = $userRepository;
    }

    /**
     * @return View
     */
    public function create(): View
    {
        if (!$this->hasPermission(PermissionsConstants::USER_CREATE)) {
            abort(404);
        }

        $permissions = Permission::all();
        $roles = Role::all();

        return view('users.create', compact('roles', 'permissions'));
    }

    public function store(StoreUserRequest $request)
    {
        try {
            if (!$this->hasPermission(PermissionsConstants::USER_CREATE)) {
                return $this->response(401);
            }

            $user = $this->userRepository->store($request->validated());

            if (!empty($request->validated()['roles'])) {
                $roles = Role::whereIn('id', $request->validated()['roles'])->get();
                foreach ($roles as $role) {
                    $user->roles()->attach($role);
                }
            }

            return redirect(route('users.index'))
                ->with('message', 'Usuario creado satisfactoriamente.');

        } catch (\Exception $exception) {
            return $this->response(500, $exception->getMessage());
        }
    }
}
