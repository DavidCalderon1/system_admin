<?php

namespace App\Repositories;

use App\Models\PaymentMethod;
use App\Repositories\Interfaces\PaymentMethodRepositoryInterface;

/**
 * Class EloquentPaymentMethodRepository
 * @package App\Repositories
 */
class EloquentPaymentMethodRepository implements PaymentMethodRepositoryInterface
{
    /**
     * @var PaymentMethod
     */
    protected $paymentMethod;

    /**
     * EloquentPaymentMethodRepository constructor.
     * @param PaymentMethod $paymentMethod
     */
    public function __construct(PaymentMethod $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->paymentMethod->all()->toArray();
    }
}
