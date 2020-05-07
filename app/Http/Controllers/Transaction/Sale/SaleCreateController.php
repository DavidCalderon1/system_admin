<?php

namespace App\Http\Controllers\Transaction\Sale;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\ThirdPartiesRepositoryInterface;
use App\Repositories\Sales\Interfaces\MainSaleRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

/**
 * Class InvoiceCreateController
 * @package App\Http\Controllers\Inventory\Invoice
 */
class SaleCreateController extends Controller
{
    /**
     * @var MainSaleRepositoryInterface
     */
    protected $mainSaleRepository;

    /**
     * @var ThirdPartiesRepositoryInterface
     */
    protected $thirdPartiesRepository;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * InvoiceCreateController constructor.
     * @param MainSaleRepositoryInterface $mainSaleRepository
     * @param ThirdPartiesRepositoryInterface $thirdPartiesRepository
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        MainSaleRepositoryInterface $mainSaleRepository,
        ThirdPartiesRepositoryInterface $thirdPartiesRepository,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->middleware('auth');
        $this->mainSaleRepository = $mainSaleRepository;
        $this->thirdPartiesRepository = $thirdPartiesRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @return View
     */
    public function create():View
    {
        $clients = $this->thirdPartiesRepository->filterAllByType('client');
        $products = $this->productRepository->getAll();

        return view('transactions.sales.create', compact('clients', 'products'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->merge(['products' => json_decode($request['products'], true)]);
        $request->merge(['payments' => json_decode($request['payments'], true)]);

        $data = Validator::make($request->all(), [
            'client_id' => 'numeric|min:1|required',
            'client_name' => 'string|required',
            'client_last_name' => 'string|required',
            'client_identity_number' => 'string|required',
            'client_identity_type' => 'string|required',
            'client_contact' => 'string|required',
            'seller_code' => 'string|required',
            'date' => 'date',
            'description' => 'string|nullable',
            'file' => 'file|nullable',
            'products' => 'array|required',
            'products.*.id' => 'numeric|min:1|required',
            'products.*.name' => 'string|required',
            'products.*.warehouse_id' => 'numeric|min:1|required',
            'products.*.quantity' => 'numeric|required|min:1',
            'products.*.price' => 'numeric|required',
            'products.*.discount_percentage' => 'numeric|required|min:0',
            'products.*.vat' => 'numeric|required|min:0',
            'products.*.description' => 'string|nullable',
            'payments' => 'array|required',
            'payments.*.way_to_pay' => 'required|string',
            'payments.*.amount' => 'required|numeric|min:1',
            'payments.*.method' => 'required|string',
        ]);

        $request = $data->validated();

        $saleData = [
            'client_id' => $request['client_id'],
            'client_name' => $request['client_name'],
            'client_last_name' => $request['client_last_name'],
            'client_identity_number' => $request['client_identity_number'],
            'client_identity_type' => $request['client_identity_type'],
            'client_contact' => $request['client_contact'],
            'seller_code' => $request['seller_code'],
            'date' => $request['date'],
            'description' => (!empty($request['description'])) ? $request['description'] : '',
            'file' => (!empty($request['file'])) ? $request['file'] : '',
        ];

        $saleProductsData = array_map(function ($saleProducts) {
            return [
                'product_id' => $saleProducts['id'],
                'name' => $saleProducts['name'],
                'description' => (!empty($saleProducts['description'])) ? $saleProducts['description'] : '',
                'warehouse_id' => $saleProducts['warehouse_id'],
                'quantity' => $saleProducts['quantity'],
                'price' => $saleProducts['price'],
                'discount_percentage' => $saleProducts['discount_percentage'],
                'vat' => $saleProducts['vat'],
            ];
        }, $request['products']);

        $salePaymentsData = array_map(function ($salePayments) {
            return [
                'way_to_pay' => $salePayments['way_to_pay'],
                'amount' => $salePayments['amount'],
                'method' => $salePayments['method'],
            ];
        }, $request['payments']);

        $response = $this->mainSaleRepository->create($saleData, $saleProductsData, $salePaymentsData);

        if (!$response['status']) {
            return response()->json(['errors' => [
                'message' => $response['message'],
                'code' => $response['code'],
            ]], $response['code']);
        }

        return response()->json([
            'data' => [
                'message' => $response['message'],
                'code' => $response['code'],
                'sale' => $response['sale']
            ]
        ], $response['code']);
    }
}
