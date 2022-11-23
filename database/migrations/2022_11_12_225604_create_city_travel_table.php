<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('city_travel', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('travel_id');
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('type_id');
            $table->dateTime('port_call');

            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('travel_id')->references('id')->on('travels');
            $table->foreign('type_id')->references('id')->on('city_travel_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('city_travel');
    }
};
