<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaidStatusToInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $blueprint) {
            $blueprint->boolean('paid')->default(false);
            $blueprint->unsignedInteger('plan_id')->nullable();

            $blueprint->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $blueprint) {
            $blueprint->dropColumn('paid');
            $blueprint->dropColumn('plan_id');
        });
    }
}
