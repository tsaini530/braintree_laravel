<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPaypalResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_transaction_responses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('booking_id',11);
            $table->binary('response')->nullable();
            $table->integer('booking_invoices_id')->unsigned()->after('id');
            $table->string('transaction_id', 50)->nullable();
            $table->string('token', 50)->nullable();
            $table->string('created_at', 40);

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_paypal_responses');
    }
}
