<?php

namespace App\Http\Controllers\Inventory\Invoice;

use App\Http\Controllers\Controller;
use App\UsesCases\Interfaces\SalesUseCaseInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SaleViewController extends Controller
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
        $this->salesUseCase = $salesUseCase;
    }

    /**
     * @param int $saleId
     * @return View
     */
    public function __invoke(int $saleId): View
    {
        $sale = $this->salesUseCase->getById($saleId);

        return view('inventory.invoices.view', compact('sale'));
    }

}
