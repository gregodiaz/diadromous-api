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
        Schema::create('travels', function (Blueprint $table) {
            $table->id();

            $table->float('price');
            $table->dateTime('departure_time');
            $table->dateTime('arrival_time');
            $table->string('departure_place');
            $table->string('arrival_place');
            $table->unsignedInteger('total_passengers');
            $table->unsignedInteger('available_passengers');
            $table->boolean('done');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('travels');
    }
};
