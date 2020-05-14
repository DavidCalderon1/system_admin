<?php

namespace App\Http\Controllers\Transaction\Purchase;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Repositories\Purchases\Interfaces\MainPurchaseRepositoryInterface;
use Illuminate\Http\JsonResponse;

/**
 * Class PurchaseCancelController
 * @package App\Http\Controllers\Transaction\Purchase
 */
class PurchaseCancelController extends Controller
{
    /**
     * @var MainPurchaseRepositoryInterface
     */
    protected $mainPurchaseRepository;

    /**+
     * PurchaseCancelController constructor.
     * @param MainPurchaseRepositoryInterface $mainPurchaseRepository
     */
    public function __construct(MainPurchaseRepositoryInterface $mainPurchaseRepository)
    {
        $this->middleware('auth');
        $this->mainPurchaseRepository = $mainPurchaseRepository;
    }

    /**
     * @param int $purchaseId
     * @return JsonResponse
     */
    public function __invoke(int $purchaseId):JsonResponse
    {
        if (!$this->hasPermission(PermissionsConstants::PURCHASE_CANCEL)) {
            return $this->response(401);
        }

        try {
            $response = $this->mainPurchaseRepository->cancel($purchaseId);

            if (!$response['status']) {
                throw new \Exception($response['message'], $response['code']);
            }

            return $this->response($response['code'], $response['message']);

        } catch (\Exception $exception) {
            return $this->response($exception->getCode(), $exception->getMessage());
        }
    }

}
