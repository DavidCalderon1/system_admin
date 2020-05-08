<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PaymentMethodsSeeder
 */
class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_methods')->insert($this->data());
    }

    public function data(): array
    {
        return [
            [
                'name' => 'Efectivo',
            ],
            [
                'name' => 'Tarjeta débito',
            ],
            [
                'name' => 'Tarjeta de crédito',
            ],
        ];
    }
}
