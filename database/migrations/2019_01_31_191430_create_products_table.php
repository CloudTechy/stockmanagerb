<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('supplier_id')->unsigned()->index();
            $table->string('name')->unique();
            $table->string('image')->default("default-150x150.png");
            $table->text('description')->nullable();
            $table->string('category')->index();
            $table->string('pku')->index();
            $table->integer('discount')->default(0);
            $table->dateTime('discount_start')->nullable();
            $table->dateTime('discount_end')->nullable();
            $table->boolean('discontinued')->default(false);
            $table->integer('user_id')->unsigned()->index();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
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
