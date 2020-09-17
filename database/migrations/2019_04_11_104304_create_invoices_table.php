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
            $table->decimal('cost', 60, 4)->default(0);
            $table->decimal('amount', 60, 4)->default(0);
            $table->decimal('balance', 60, 4)->default(0);
            $table->integer('user_id')->unsigned()->index();
            $table->string('staff')->index();
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
