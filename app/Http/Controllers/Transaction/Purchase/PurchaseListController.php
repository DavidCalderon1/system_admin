<?php

namespace App\Http\Controllers\Transaction\Purchase;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\UsesCases\Interfaces\PurchasesUseCaseInterface;
use App\UsesCases\Interfaces\SalesUseCaseInterface;
use Illuminate\Http\Request;

/**
 * Class PurchaseListController
 * @package App\Http\Controllers\Transaction\Purchase
 */
class PurchaseListController extends Controller
{
    /**
     * @var PurchasesUseCaseInterface
     */
    protected $purchasesUseCase;

    /**
     * PurchaseListController constructor.
     * @param PurchasesUseCaseInterface $purchasesUseCase
     */
    public function __construct(PurchasesUseCaseInterface $purchasesUseCase)
    {
        $this->middleware('auth');

        $this->purchasesUseCase = $purchasesUseCase;
    }


    public function index()
    {
        if (!$this->hasPermission(PermissionsConstants::PURCHASE_LIST)) {
            abort(404);
        }

        $userSessionCanCreate = $this->hasPermission(PermissionsConstants::PURCHASE_CREATE);
        $userSessionCanView = $this->hasPermission(PermissionsConstants::PURCHASE_VIEW);
        $userSessionCanCancel = $this->hasPermission(PermissionsConstants::PURCHASE_CANCEL);

        return view(
            'transactions.purchases.index',
            compact(
                'userSessionCanCreate',
                'userSessionCanView',
                'userSessionCanCancel'
            )
        );
    }


    public function list(Request $request)
    {
        if (!$this->hasPermission(PermissionsConstants::SALE_LIST)) {
            abort(404);
        }

        $filers = $request->validate([
            'consecutive' => 'string|nullable',
            'client_name' => 'string|nullable',
            'status' => 'string|nullable'
        ]);

        $sales = $this->purchasesUseCase->getPagination(10, $filers);


        if (empty($sales)) {
            return $this->response(404, 'Ventas no encontrados');
        }

        $response = [
            'data' => $sales['data'],
            'pagination' => [
                'current_page' => $sales['current_page'],
                'first_page_url' => $sales['first_page_url'],
                'from' => $sales['from'],
                'last_page' => $sales['last_page'],
                'last_page_url' => $sales['last_page_url'],
                'next_page_url' => $sales['next_page_url'],
                'per_page' => $sales['per_page'],
                'prev_page_url' => $sales['prev_page_url'],
                'to' => $sales['to'],
                'total' => $sales['total']
            ],
        ];

        return response()->json($response, 200);
    }
}
