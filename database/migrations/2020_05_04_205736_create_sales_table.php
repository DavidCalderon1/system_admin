<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('client_id')->unsigned();
            $table->string('prefix');
            $table->integer('consecutive');
            $table->string('client_name');
            $table->string('client_last_name');
            $table->string('client_identity_number');
            $table->string('client_identity_type');
            $table->string('client_contact');
            $table->string('seller_code');
            $table->dateTime('date');
            $table->text('description');
            $table->enum('status', ['Activa', 'Anulada', 'Eliminada']);
            $table->string('file');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('third_parties');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
