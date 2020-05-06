<?php

namespace App\Http\Controllers\Inventory\Invoice;

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
        $this->salesUseCase= $salesUseCase;
    }

    /**
     * @param int $saleId
     * @return mixed
     */
    public function __invoke(int $saleId)
    {
        $sale = $this->salesUseCase->getById($saleId);

        $pdf = PDF::loadView('inventory.invoices.template_pdf', ['sale' => $sale]);

        return $pdf->download($sale['prefix'].'-'.$sale['consecutive'].'.pdf');
    }
}
