<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('number');
            $table->string('email');
            $table->boolean('is_stock_available')->default(true);
            $table->decimal('owed', 60, 4)->default(0);
            $table->date('due_date')->nullable();
            $table->integer('user_id')->unsigned()->index();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
        Schema::table('products', function ($table) {

            $table->foreign('supplier_id')->references('id')->on('suppliers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
}
