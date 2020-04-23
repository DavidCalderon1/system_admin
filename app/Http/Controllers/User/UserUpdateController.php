<?php

namespace App\Http\Controllers\User;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\Role;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
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
     * UserListController constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->middleware('auth');

        $this->userRepository = $userRepository;
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
        $roles = Role::all();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * @param StoreUserRequest $request
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(StoreUserRequest $request)
    {
        try {
            if (!$this->hasPermission(PermissionsConstants::USER_UPDATE)) {
                return $this->response(401);
            }

            $saved = $this->userRepository->update($request->get('id'), $request->validated());

            if (!$saved) {
                return redirect()->back()->withErrors(
                    ['Ha ocurrido un error actualizando el usuario, por favor intente de nuevo o comuniquese con el administrador.']
                );
            }

            $user = $this->userRepository->get($request->get('id'));

            if (!empty($request->validated()['roles'])) {
                $roles = Role::whereIn('id', $request->validated()['roles'])->get('id')->pluck('id')->toArray();
                $user->roles()->sync($roles);
            }

            return redirect(route('users.index'))
                ->with('message', 'Usuario actualizado satisfactoriamente.');

        } catch (\Exception $exception) {
            return $this->response(500, $exception->getMessage());
        }
    }
}
