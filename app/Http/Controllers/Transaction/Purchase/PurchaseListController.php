<?php

namespace App\Http\Controllers\Transaction\Purchase;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\UsesCases\Interfaces\PurchasesUseCaseInterface;
use App\UsesCases\Interfaces\SalesUseCaseInterface;
use Illuminate\Http\Request;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;

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
     * @return \Illuminate\Http\JsonResponse|DataTableCollectionResource
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

        $data = $this->purchasesUseCase->getPagination($length, $orderBy, $orderByDir, $searchValue);

        if (empty($data)) {
            return $this->response(404);
        }

        return new DataTableCollectionResource($data);
    }
}
