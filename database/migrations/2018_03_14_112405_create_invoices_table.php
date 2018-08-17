<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

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
            $table->increments('id');
            $table->string('number')->nullable();
            $table->string('stripe_id')->nullable();
            $table->integer('amount')->nullable();
            $table->string('currency')->nullable();
            $table->string('customer')->nullable();
            $table->string('customer_email')->nullable();
            $table->timestamp('date')->nullable();
            $table->string('description')->nullable();
            $table->string('invoice')->nullable();
            $table->string('livemode')->nullable();
            $table->timestamp('period_start')->nullable();
            $table->timestamp('period_end')->nullable();
            $table->string('plan')->nullable();
            $table->string('proration')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('subscription')->nullable();
            $table->integer('unit_amount')->nullable();

            $table->timestamps();
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
