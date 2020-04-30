<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThirdPartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('third_parties', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('country_id');
            $table->unsignedInteger('state_id');
            $table->unsignedInteger('city_id');
            $table->enum('type', ['client', 'provider', 'other']);
            $table->string('identity_number', 50);
            $table->enum('identity_type', ['CC', 'NIT']);
            $table->enum('type_person', ['natural', 'juridical']);
            $table->string('name', 50);
            $table->string('last_name', 50)->default('');
            $table->string('address', 150);
            $table->string('phone_number', 10);
            $table->string('phone_extension', 5)->default('');
            $table->string('email', 50);
            $table->text('description');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('countries');
            $table->foreign('state_id')->references('id')->on('states');
            $table->foreign('city_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('third_parties');
    }
}
