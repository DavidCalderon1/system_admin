<?php

use Illuminate\Database\Seeder;

class RegisterFaker extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\ThirdParties::class,50)->create();
        factory(\App\Models\Product::class,100)->create();
        factory(\App\Models\Warehouse::class,20)->create();
        factory(\App\Models\Tax::class,10)->create();
        factory(\App\Models\Concept::class,10)->create();
        factory(\App\Models\CostCenter::class,10)->create();

        $client = factory(\App\Models\ThirdParties::class)->create([
            'type' => 'client'
        ]);

        $sale = factory(\App\Models\Sale::class)->create([
            'client_id' => $client->id,
            'client_identity_type' => $client->identity_type,
            'client_identity_number' => $client->identity_number,
            'client_name' => $client->name,
            'client_last_name' => $client->last_name,
            'client_contact' => $client->email,
        ]);

        factory(\App\Models\SaleProduct::class, 10)->create([
            'sale_id' => $sale->id
        ]);

        factory(\App\Models\SalePayment::class, 10)->create([
            'sale_id' => $sale->id
        ]);
    }
}
