<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateProductsTable
 */
class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->string('code', 6);
            $table->string('reference', 150);
            $table->mediumText('description');
            $table->float('base_price');
            $table->enum('vat', [0, 5, 19]);
            $table->float('price');
            $table->string('image');
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('inventory_categories');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
