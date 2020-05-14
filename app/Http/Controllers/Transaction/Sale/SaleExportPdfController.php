<?php

namespace App\Http\Controllers\Transaction\Sale;

use App\Http\Controllers\Controller;
use App\UsesCases\Interfaces\SalesUseCaseInterface;
use PDF;

class SaleExportPdfController extends Controller
{
    /**
     * @var SalesUseCaseInterface
     */
    protected $salesUseCase;

    /**
     * SaleExportPdfController constructor.
     * @param SalesUseCaseInterface $salesUseCase
     */
    public function __construct(SalesUseCaseInterface $salesUseCase)
    {
        $this->middleware('auth');
        $this->salesUseCase = $salesUseCase;
    }

    /**
     * @param int $saleId
     * @return mixed
     */
    public function download(int $saleId)
    {
        $sale = $this->salesUseCase->getById($saleId);

        $pdf = PDF::loadView('transactions.sales.template_sale_pdf', ['sale' => $sale]);

        return $pdf->download($sale['prefix'] . '-' . $sale['consecutive'] . '.pdf');
    }

    /**
     * @param int $saleId
     * @return mixed
     */
    public function print(int $saleId)
    {
        $sale = $this->salesUseCase->getById($saleId);

        $pdf = PDF::loadView('transactions.sales.template_sale_pdf', ['sale' => $sale]);

        return $pdf->stream($sale['prefix'] . '-' . $sale['consecutive'] . '.pdf');
    }
}
