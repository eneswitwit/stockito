<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlanForUpgradeToPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plans', function (Blueprint $blueprint) {
            $blueprint->unsignedInteger('plan_for_upgrade_id')->nullable();

            $blueprint->foreign('plan_for_upgrade_id')->references('id')->on('plans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plans', function (Blueprint $blueprint) {
            $blueprint->dropColumn('plan_for_upgrade_id');
        });
    }
}
