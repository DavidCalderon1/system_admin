<?php

namespace App\Http\Controllers\Transaction\Purchase;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\UsesCases\Interfaces\PurchasesUseCaseInterface;
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
        $userSessionCanEdit = $this->hasPermission(PermissionsConstants::PURCHASE_EDIT);
        $userSessionCanCancel = $this->hasPermission(PermissionsConstants::PURCHASE_CANCEL);

        return view(
            'transactions.purchases.index',
            compact(
                'userSessionCanCreate',
                'userSessionCanView',
                'userSessionCanEdit',
                'userSessionCanCancel'
            )
        );
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        if (!$this->hasPermission(PermissionsConstants::PURCHASE_LIST)) {
            abort(404);
        }

        $length = $request->input('length', '');
        $orderBy = $request->input('column', ''); //Index
        $orderByDir = $request->input('dir', 'asc');
        $searchValue = (!empty($request->input('search', ''))) ? $request->input('search') : '';
        $draw = $request->input('draw', 0);


        $data = $this->purchasesUseCase->getPagination($length, $orderBy, $orderByDir, $searchValue);

        if (empty($data)) {
            return $this->response(404);
        }

        return responseDataTable($data, $length, $orderBy, $orderByDir, $draw, $searchValue);
    }
}
