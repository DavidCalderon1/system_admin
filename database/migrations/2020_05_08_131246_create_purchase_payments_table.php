<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_payments', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('purchase_id')->unsigned();
            $table->enum('way_to_pay', ['credit', 'cash']);
            $table->double('amount');
            $table->string('method')->default('');
            $table->integer('days_to_pay')->default(0);
            $table->dateTime('credit_expiration_date')->nullable();
            $table->dateTime('date');

            $table->foreign('purchase_id')->references('id')->on('sales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_payments');
    }
}
