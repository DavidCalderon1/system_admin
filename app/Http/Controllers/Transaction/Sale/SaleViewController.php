<?php

namespace App\Http\Controllers\Transaction\Sale;

use App\Http\Controllers\Controller;
use App\UsesCases\Interfaces\SalesUseCaseInterface;
use Illuminate\View\View;

/**
 * Class SaleViewController
 * @package App\Http\Controllers\Transaction\Sale
 */
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
        $this->middleware('auth');
        $this->salesUseCase = $salesUseCase;
    }

    /**
     * @param int $saleId
     * @return View
     */
    public function __invoke(int $saleId): View
    {
        $sale = $this->salesUseCase->getById($saleId);

        return view('transactions.sales.view', compact('sale'));
    }

}
