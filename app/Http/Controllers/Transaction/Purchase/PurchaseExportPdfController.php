<?php

namespace App\Http\Controllers\Transaction\Purchase;

use App\Http\Controllers\Controller;
use App\UsesCases\Interfaces\PurchasesUseCaseInterface;
use App\UsesCases\Interfaces\SalesUseCaseInterface;
use PDF;

/**
 * Class PurchaseExportPdfController
 * @package App\Http\Controllers\Transaction\Purchase
 */
class PurchaseExportPdfController extends Controller
{
    /**
     * @var PurchasesUseCaseInterface
     */
    protected $purchasesUseCase;

    /**
     * SaleExportPdfController constructor.
     * @param PurchasesUseCaseInterface $purchasesUseCase
     */
    public function __construct(PurchasesUseCaseInterface $purchasesUseCase)
    {
        $this->middleware('auth');
        $this->purchasesUseCase = $purchasesUseCase;
    }

    /**
     * @param int $purchaseId
     * @return mixed
     */
    public function download(int $purchaseId)
    {
        $purchase = $this->purchasesUseCase->getById($purchaseId);

        $pdf = PDF::loadView('transactions.purchases.template_purchase_pdf', ['purchase' => $purchase]);

        return $pdf->download($purchase['prefix'] . '-' . $purchase['consecutive'] . '.pdf');
    }

    /**
     * @param int $purchaseId
     * @return mixed
     */
    public function print(int $purchaseId)
    {
        $purchase = $this->purchasesUseCase->getById($purchaseId);

        $pdf = PDF::loadView('transactions.purchases.template_purchase_pdf', ['purchase' => $purchase]);

        return $pdf->stream($purchase['prefix'] . '-' . $purchase['consecutive'] . '.pdf');
    }
}
