<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
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
     * define el slug del permiso User Create
     */
    public const USER_CREATE = 'user-create';

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
        if (!$this->hasPermission(self::USER_CREATE)) {
            abort(404);
        }

        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        try {
            if (!$this->hasPermission(self::USER_CREATE)) {
                return $this->response(401);
            }

            $this->userRepository->store($request->validated());

            return $this->response(201, 'Created');

        } catch (\Exception $exception) {
            return $this->response(500, $exception->getMessage());
        }
    }
}
