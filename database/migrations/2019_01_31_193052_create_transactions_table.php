<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('invoice_id')->index();
            $table->bigInteger('cost')->default(0);
            $table->decimal('amount', 60, 4)->default(0);
            $table->decimal('payment', 60, 4)->default(0);
            $table->string('status')->index()->default('pending');
            $table->string('payment_mode')->index()->default('cash');
            $table->date('due_date')->nullable();
            $table->string('staff')->index();
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
        Schema::dropIfExists('transactions');
    }
}
