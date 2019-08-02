<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type')->index();
            $table->integer('purchase_id')->unsigned()->index()->nullable();
            $table->string('order_id')->index()->nullable();
<<<<<<< HEAD
            $table->decimal('cost', 60, 4)->default(0);
            $table->decimal('amount', 60, 4)->default(0);
            $table->decimal('balance', 60, 4)->default(0);
=======
            $table->bigInteger('cost')->default(0);
            $table->bigInteger('amount')->default(0);
            $table->bigInteger('balance')->default(0);
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
            $table->integer('user_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('type')->references('name')->on('types');
            $table->foreign('purchase_id')->references('id')->on('purchases');
        });

        Schema::table('transactions', function ($table) {

            $table->foreign('invoice_id')->references('id')->on('invoices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
