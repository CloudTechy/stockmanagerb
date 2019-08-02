<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('number');
            $table->string('email')->nullable();
            $table->string('notes')->nullable();
<<<<<<< HEAD
            $table->decimal('owing', 60, 4)->default(0);
=======
            $table->bigInteger('owing')->default(0);
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
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
        Schema::dropIfExists('customers');
    }
}
