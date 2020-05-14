<?php

namespace App\Http\Controllers\Transaction\Sale;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\UsesCases\Interfaces\SalesUseCaseInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;

class SaleListController extends Controller
{
    /**
     * @var SalesUseCaseInterface
     */
    protected $salesUseCase;

    /**
     * SaleListController constructor.
     * @param SalesUseCaseInterface $salesUseCase
     */
    public function __construct(SalesUseCaseInterface $salesUseCase)
    {
        $this->middleware('auth');
        $this->salesUseCase = $salesUseCase;
    }

    public function index()
    {
        if (!$this->hasPermission(PermissionsConstants::SALE_LIST)) {
            abort(404);
        }

        $userSessionCanCreate = $this->hasPermission(PermissionsConstants::SALE_CREATE);
        $userSessionCanView = $this->hasPermission(PermissionsConstants::SALE_VIEW);
        $userSessionCanEdit = $this->hasPermission(PermissionsConstants::SALE_EDIT);
        $userSessionCanCancel = $this->hasPermission(PermissionsConstants::SALE_CANCEL);

        return view('transactions.sales.index', compact(
            'userSessionCanCreate',
            'userSessionCanView',
            'userSessionCanEdit',
            'userSessionCanCancel'
            ));
    }


    public function list(Request $request)
    {
        if (!$this->hasPermission(PermissionsConstants::SALE_LIST)) {
            abort(404);
        }

        $length = $request->input('length', '');
        $orderBy = $request->input('column', ''); //Index
        $orderByDir = $request->input('dir', 'asc');
        $searchValue = (!empty($request->input('search', ''))) ? $request->input('search') : '';

        $data = $this->salesUseCase->getPagination($length, $orderBy, $orderByDir, $searchValue);

        if (empty($data)) {
            return $this->response(404);
        }

        return new DataTableCollectionResource($data);
    }
}
