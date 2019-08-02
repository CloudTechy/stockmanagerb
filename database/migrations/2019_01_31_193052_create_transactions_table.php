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
<<<<<<< HEAD
            $table->decimal('amount', 60, 4)->default(0);
            $table->decimal('payment', 60, 4)->default(0);
=======
            $table->bigInteger('amount')->default(0);
            $table->bigInteger('payment')->default(0);
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
            $table->string('status')->index()->default('pending');
            $table->date('due_date')->nullable();
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
