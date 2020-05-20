<?php

namespace App\Http\Controllers\Transaction\Purchase;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\UsesCases\Interfaces\PurchasesUseCaseInterface;
use App\UsesCases\Interfaces\SalesUseCaseInterface;
use Illuminate\View\View;

/**
 * Class PurchaseViewController
 * @package App\Http\Controllers\Transaction\Sale
 */
class PurchaseViewController extends Controller
{
    /**
     * @var PurchasesUseCaseInterface
     */
    protected $purchasesUseCase;

    /**
     * PurchaseViewController constructor.
     * @param PurchasesUseCaseInterface $purchasesUseCase
     */
    public function __construct(PurchasesUseCaseInterface $purchasesUseCase)
    {
        $this->middleware('auth');
        $this->purchasesUseCase = $purchasesUseCase;
    }

    /**
     * @param int $purchaseId
     * @return View
     */
    public function __invoke(int $purchaseId): View
    {
        $purchase = $this->purchasesUseCase->getById($purchaseId);
        $userCanEdit = $this->hasPermission(PermissionsConstants::PURCHASE_EDIT);
        $userCanCancel = $this->hasPermission(PermissionsConstants::PURCHASE_CANCEL);

        return view('transactions.purchases.view', compact('purchase', 'userCanEdit', 'userCanCancel'));
    }

}
