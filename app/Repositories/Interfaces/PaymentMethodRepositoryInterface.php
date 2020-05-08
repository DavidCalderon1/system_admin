<?php

namespace App\Repositories\Interfaces;

/**
 * Interface PaymentMethodRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface PaymentMethodRepositoryInterface
{
    /**
     * @return array
     */
    public function all(): array;
}
