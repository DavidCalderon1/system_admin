<?php

namespace App\Http\Controllers\Transaction\Sale;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\UsesCases\Interfaces\SalesUseCaseInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $userSessionCanCancel = $this->hasPermission(PermissionsConstants::SALE_CANCEL);

        return view('transactions.sales.index', compact(
            'userSessionCanCreate',
            'userSessionCanView',
            'userSessionCanCancel'
            ));
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

        $sales = $this->salesUseCase->getPagination(10, $filers);


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
