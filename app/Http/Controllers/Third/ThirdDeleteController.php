<?php

namespace App\Http\Controllers\Third;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ThirdPartiesRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

/**
 * Class ThirdDeleteController
 * @package App\Http\Controllers\Third
 */
class ThirdDeleteController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    protected $thirdPartiesRepository;

    /**
     * ThirdDeleteController constructor.
     * @param ThirdPartiesRepositoryInterface $thirdPartiesRepository
     */
    public function __construct(ThirdPartiesRepositoryInterface $thirdPartiesRepository)
    {
        $this->middleware('auth');

        $this->thirdPartiesRepository = $thirdPartiesRepository;
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->hasPermission(PermissionsConstants::THIRD_DELETE)) {
            return $this->response(401);
        }

        try {

            $response = $this->thirdPartiesRepository->deactivate($id);

            if (empty($response)) {
                throw new \Exception(__('thirds.register_not_found'));
            }

            return $this->response(200, __('thirds.success_delete'));
        } catch (\Exception $exception) {
            return $this->response(500, $exception->getMessage());
        }
    }
}
