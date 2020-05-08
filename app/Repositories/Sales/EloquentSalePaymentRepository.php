<?php

namespace App\Repositories\Sales;

use App\Models\SalePayment;
use App\Repositories\Sales\Interfaces\SalePaymentRepositoryInterface;

/**
 * Class EloquentSalePaymentRepository
 * @package App\Repositories\Sales
 */
class EloquentSalePaymentRepository implements SalePaymentRepositoryInterface
{
    /**
     * @var SalePayment
     */
    protected $salePayment;

    /**
     * EloquentSalePaymentRepository constructor.
     * @param SalePayment $salePayment
     */
    public function __construct(SalePayment $salePayment)
    {
        $this->salePayment = $salePayment;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        return $this->salePayment->insert($data);
    }
}
