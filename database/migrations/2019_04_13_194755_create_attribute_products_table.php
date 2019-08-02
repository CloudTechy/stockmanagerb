<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_product', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('product_id')->index();
            $table->integer('attribute_id')->unsigned()->index();
            $table->string('size')->index();
<<<<<<< HEAD
            $table->decimal('purchase_price', 60, 4)->default(0);
            $table->decimal('sale_price', 60, 4)->default(0);
=======
            $table->bigInteger('purchase_price')->default(0);
            $table->bigInteger('sale_price')->default(0);
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
            $table->integer('percent_sale')->default(0);
            $table->Integer('available_stock')->default(0);
            $table->unique(['product_id', 'size', 'attribute_id']);
            $table->integer('user_id')->unsigned()->index();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');

            $table->foreign('size')->references('name')->on('sizes');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('attribute_id')->references('id')->on('attributes');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute_product');
    }
}
