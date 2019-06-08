<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->string('name')->primary();
        });

        Schema::table('purchase_details', function ($table) {

            $table->foreign('pku')->references('name')->on('units');
        });
        Schema::table('products', function ($table) {

            $table->foreign('pku')->references('name')->on('units');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('units');
    }
}
