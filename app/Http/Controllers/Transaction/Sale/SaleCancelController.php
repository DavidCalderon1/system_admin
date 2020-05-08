<?php

namespace App\Http\Controllers\Transaction\Sale;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Repositories\Sales\Interfaces\MainSaleRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

/**
 * Class SaleCancelController
 * @package App\Http\Controllers\Transaction\Sale
 */
class SaleCancelController extends Controller
{
    /**
     * @var MainSaleRepositoryInterface
     */
    protected $mainSaleRepository;

    /**
     * SaleCancelController constructor.
     * @param MainSaleRepositoryInterface $mainSaleRepository
     */
    public function __construct(MainSaleRepositoryInterface $mainSaleRepository)
    {
        $this->middleware('auth');
        $this->mainSaleRepository = $mainSaleRepository;
    }

    /**
     * @param int $saleId
     * @return JsonResponse
     */
    public function __invoke(int $saleId):JsonResponse
    {
        if (!$this->hasPermission(PermissionsConstants::SALE_CANCEL)) {
            return $this->response(401);
        }

        try {
            $response = $this->mainSaleRepository->cancel($saleId);

            if (!$response['status']) {
                throw new \Exception($response['message'], $response['code']);
            }

            return $this->response($response['code'], $response['message']);

        } catch (\Exception $exception) {
            return $this->response($exception->getCode(), $exception->getMessage());
        }
    }

}
