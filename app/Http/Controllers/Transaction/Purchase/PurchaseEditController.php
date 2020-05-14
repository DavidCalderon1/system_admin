<?php

namespace App\Http\Controllers\Transaction\Purchase;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\PaymentMethodRepositoryInterface;
use App\Repositories\Purchases\Interfaces\MainPurchaseRepositoryInterface;
use App\UsesCases\Interfaces\PurchasesUseCaseInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class PurchaseEditController
 * @package App\Http\Controllers\Transaction\Purchase
 */
class PurchaseEditController extends Controller
{
    /**
     * @var PurchasesUseCaseInterface
     */
    protected $purchasesUseCase;

    /**
     * @var PaymentMethodRepositoryInterface
     */
    protected $methodRepository;

    /**
     * @var MainPurchaseRepositoryInterface
     */
    protected $mainPurchaseRepository;

    /**
     * PurchaseEditController constructor.
     * @param PurchasesUseCaseInterface $purchasesUseCase
     * @param PaymentMethodRepositoryInterface $methodRepository
     * @param MainPurchaseRepositoryInterface $mainPurchaseRepository
     */
    public function __construct(
        PurchasesUseCaseInterface $purchasesUseCase,
        PaymentMethodRepositoryInterface $methodRepository,
        MainPurchaseRepositoryInterface $mainPurchaseRepository
    )
    {
        $this->purchasesUseCase = $purchasesUseCase;
        $this->methodRepository = $methodRepository;
        $this->mainPurchaseRepository = $mainPurchaseRepository;
    }


    /**
     * @param int $id
     * @return mixed
     */
    public function edit(int $id)
    {
        if (!$this->hasPermission(PermissionsConstants::PURCHASE_EDIT)) {
            abort(404);
        }

        $purchase = $this->purchasesUseCase->getByIdForEdit($id);

        if($purchase['status'] === 'Anulada'){
            abort(404);
        }

        $paymentsMethods = $this->methodRepository->all();

        return view('transactions.purchases.edit', compact('paymentsMethods', 'purchase'));
    }

    public function update(Request $request)
    {
        if (!$this->hasPermission(PermissionsConstants::PURCHASE_EDIT)) {
            abort(404);
        }

        $request->merge(['products' => json_decode($request['products'], true)]);
        $request->merge(['payments' => json_decode($request['payments'], true)]);

        $data = Validator::make($request->all(), [
            'id' => 'numeric|min:1|required',
            'provider_id' => 'numeric|min:1|required',
            'provider_name' => 'string|required',
            'provider_identity_number' => 'string|required',
            'provider_identity_type' => 'string|required',
            'provider_address' => 'string|required',
            'provider_phone_number' => 'string|required',
            'provider_location' => 'string|required',
            'provider_invoice_number' => 'string|required',
            'include_taxes' => 'numeric|required|min:0|max:1',
            'date' => 'date',
            'description' => 'string|nullable',
            'file' => 'file|nullable',

            'products' => 'array|required',
            'products.*.id' => 'numeric|min:1|required',
            'products.*.name' => 'string|required',
            'products.*.description' => 'string|nullable',
            'products.*.warehouse_id' => 'numeric|min:1|required',
            'products.*.quantity' => 'numeric|required|min:1',
            'products.*.withholding_tax_percentage' => 'numeric|required|min:0',
            'products.*.vat' => 'numeric|required|min:0',
            'products.*.total' => 'numeric|required|min:0',

            'payments' => 'array|required',
            'payments.*.way_to_pay' => 'required|string',
            'payments.*.amount' => 'required|numeric|min:1',
            'payments.*.method' => 'string',
            'payments.*.days_to_pay' => 'numeric',
        ]);

        $request = $data->validated();

        $purchaseData = [
            'provider_id' => $request['provider_id'],
            'provider_name' => $request['provider_name'],
            'provider_identity_number' => $request['provider_identity_number'],
            'provider_identity_type' => $request['provider_identity_type'],
            'provider_address' => $request['provider_address'],
            'provider_phone_number' => $request['provider_phone_number'],
            'provider_location' => $request['provider_location'],
            'provider_invoice_number' => $request['provider_invoice_number'],
            'include_taxes' => $request['include_taxes'],
            'date' => $request['date'],
            'description' => (!empty($request['description'])) ? $request['description'] : '',
            'file' => (!empty($request['file'])) ? $request['file'] : '',
        ];

        $purchaseProductsData = array_map(function ($purchaseProducts) {
            return [
                'product_id' => $purchaseProducts['product_id'],
                'name' => $purchaseProducts['name'],
                'description' => (!empty($purchaseProducts['description'])) ? $purchaseProducts['description'] : '',
                'warehouse_id' => $purchaseProducts['warehouse_id'],
                'quantity' => $purchaseProducts['quantity'],
                'withholding_tax_percentage' => $purchaseProducts['withholding_tax_percentage'],
                'vat' => $purchaseProducts['vat'],
                'total' => $purchaseProducts['total'],
            ];
        }, $request['products']);

        $purchasePaymentsData = array_map(function ($purchasePayments) {
            return [
                'way_to_pay' => $purchasePayments['way_to_pay'],
                'amount' => $purchasePayments['amount'],
                'method' => (!empty($purchasePayments['method'])) ? $purchasePayments['method'] : '',
                'days_to_pay' => (!empty($purchasePayments['days_to_pay'])) ? $purchasePayments['days_to_pay'] : 0,
            ];
        }, $request['payments']);

        $response = $this->mainPurchaseRepository->update($request['id'], $purchaseData, $purchaseProductsData, $purchasePaymentsData);

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
                'purchase' => $response['purchase']
            ]
        ], $response['code']);
    }

}
