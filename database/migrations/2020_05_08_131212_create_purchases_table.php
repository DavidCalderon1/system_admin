<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('provider_id')->unsigned();
            $table->string('prefix');
            $table->integer('consecutive');
            $table->string('provider_invoice_number');
            $table->string('provider_name');
            $table->string('provider_identity_number');
            $table->string('provider_identity_type');
            $table->string('provider_address');
            $table->string('provider_phone_number');
            $table->string('provider_location');
            $table->text('description');
            $table->enum('status', ['Activa', 'Anulada', 'Eliminada']);
            $table->tinyInteger('include_taxes')->default(0);
            $table->string('file');
            $table->dateTime('date');
            $table->timestamps();

            $table->foreign('provider_id')->references('id')->on('third_parties');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
