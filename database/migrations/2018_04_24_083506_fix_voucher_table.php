<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vouchers', function (Blueprint $blueprint) {
            $blueprint->enum('type', \App\Models\Voucher::$typesReduction);
            $blueprint->renameColumn('sale', 'amount');
            $blueprint->dropColumn('duration');
            $blueprint->dropColumn('amount_off');
            $blueprint->dropColumn('duration_in_months');
            $blueprint->dropColumn('max_redemptions');
            $blueprint->dropColumn('percent_off');
            $blueprint->dropColumn('redeem_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vouchers', function (Blueprint $blueprint) {
            $blueprint->dropColumn('type');
            $blueprint->renameColumn('amount', 'sale');
            $blueprint->string('duration');
            $blueprint->string('amount_off');
            $blueprint->string('duration_in_months');
            $blueprint->integer('max_redemptions');
            $blueprint->integer('percent_off');
            $blueprint->string('redeem_by');
        });
    }
}
