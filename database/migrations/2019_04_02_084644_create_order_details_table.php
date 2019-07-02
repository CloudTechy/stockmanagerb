<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_id')->index();
            $table->string('product');
            $table->string('brand');
            $table->string('category');
            $table->integer('quantity');
            $table->decimal('price', 60, 4)->default(0);
            $table->decimal('cost_price', 60, 4)->default(0);
            $table->integer('discount')->nullable();
            $table->string('pku');
            $table->string('size');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
