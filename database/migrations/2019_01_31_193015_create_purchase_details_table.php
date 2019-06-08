<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_id')->unsigned()->index();
            $table->string('product');
            $table->string('brand');
            $table->integer('quantity')->default(0);
            $table->bigInteger('price')->default(0);
            $table->integer('percent_sale')->default(0);
            $table->string('pku')->index();
            $table->string('category')->index();
            $table->string('size')->index();

            $table->timestamps();
            $table->foreign('category')->references('name')->on('categories');
            $table->foreign('size')->references('name')->on('sizes');
            $table->foreign('purchase_id')->references('id')->on('purchases');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_details');
    }
}
