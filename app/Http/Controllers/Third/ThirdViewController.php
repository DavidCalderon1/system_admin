<?php

namespace App\Http\Controllers\Third;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ThirdPartiesRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

/**
 * Class ThirdViewController
 * @package App\Http\Controllers\Third}
 */
class ThirdViewController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    protected $thirdPartiesRepository;

    /**
     * ThirdViewController constructor.
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
        if (!$this->hasPermission(PermissionsConstants::THIRD_VIEW)) {
            return $this->response(401);
        }

        try {

            $response = $this->thirdPartiesRepository->get($id);

            if (empty($response)) {
                throw new \Exception(__('thirds.register_not_found'), 404);
            }

            return $this->response(200, '', $response);
        } catch (\Exception $exception) {
            return $this->response($exception->getCode(), $exception->getMessage());
        }
    }
}
