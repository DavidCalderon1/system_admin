<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalePayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_payments', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('sale_id')->unsigned();
            $table->string('way_to_pay');
            $table->double('amount');
            $table->string('method')->default('');
            $table->integer('days_to_pay')->default(0);
            $table->dateTime('credit_expiration_date')->nullable();
            $table->dateTime('date');

            $table->foreign('sale_id')->references('id')->on('sales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_payments');
    }
}
