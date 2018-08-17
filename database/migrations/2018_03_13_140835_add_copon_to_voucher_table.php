<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoponToVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('vouchers', function (Blueprint $table) {

			$table->string('duration')->nullable();
			$table->string('amount_off')->nullable();
			$table->string('currency')->nullable();
			$table->string('duration_in_months')->nullable();
			$table->integer('max_redemptions')->nullable();
			$table->integer('percent_off')->nullable();
			$table->string('redeem_by')->nullable();

		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('vouchers', function (Blueprint $table) {

			$table->dropColumn('duration');
			$table->dropColumn('amount_off');
			$table->dropColumn('currency');
			$table->dropColumn('duration_in_months');
			$table->dropColumn('max_redemptions');
			$table->dropColumn('percent_off');
			$table->dropColumn('redeem_by');
		});
    }
}
