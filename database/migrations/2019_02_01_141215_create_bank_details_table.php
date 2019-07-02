<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_name');
            $table->string('account_number');
            $table->string('bank')->index();
            $table->integer('supplier_id')->unsigned()->index();
            $table->unique(['account_name', 'account_number', 'bank']);

            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('bank')->references('name')->on('banks');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_details');
    }
}
