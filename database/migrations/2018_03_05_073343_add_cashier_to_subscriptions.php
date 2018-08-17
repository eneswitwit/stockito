<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCashierToSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('subscriptions', function (Blueprint $table) {
			$table->unsignedInteger('user_id');
			$table->string('name');
			$table->string('stripe_plan');
			$table->integer('quantity');
			$table->timestamp('trial_ends_at')->nullable();
			$table->timestamp('ends_at')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('subscriptions', function (Blueprint $table) {
			$table->dropColumn('user_id');
			$table->dropColumn('name');
			$table->dropColumn('stripe_id');
			$table->dropColumn('stripe_plan');
			$table->dropColumn('quantity');
			$table->dropColumn('trial_ends_at')->nullable();
			$table->dropColumn('ends_at')->nullable();
		});
    }
}
