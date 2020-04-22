<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
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
     * define el slug del permiso User Create
     */
    public const USER_UPDATE = 'user-update';

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
     * @param User $user
     * @return View
     */
    public function edit(User $user): View
    {
        if (!$this->hasPermission(self::USER_UPDATE)) {
            abort(404);
        }

        return view('users.edit', compact('user'));
    }

    public function update(StoreUserRequest $request)
    {
        try {
            if (!$this->hasPermission(self::USER_UPDATE)) {
                return $this->response(401);
            }

            $this->userRepository->update($request->get('id'), $request->validated());
            return $this->response(201, 'User Updated');

        } catch (\Exception $exception) {
            return $this->response(500, 'Ha ocurrido un error');
        }
    }
}
