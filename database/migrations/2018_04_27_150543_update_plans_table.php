<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('plans')->truncate();
        Schema::enableForeignKeyConstraints();
        Schema::table('plans', function (Blueprint $blueprint) {
            $blueprint->unsignedInteger('product_id');

            $blueprint->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $blueprint->dropForeign('plans_plan_for_upgrade_id_foreign');
            $blueprint->dropColumn('plan_for_upgrade_id');
            $blueprint->dropColumn('video_storage');
            $blueprint->dropColumn('storage');
            $blueprint->dropColumn('title');
            $blueprint->dropColumn('description');
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
            $blueprint->dropColumn('product_id');

            $blueprint->unsignedInteger('plan_for_update_id')->nullable();
            $blueprint->double('video_storage');
            $blueprint->double('storage');

            $blueprint->foreign('plan_for_update_id')->references('id')->on('plans')->onDelete('set null');
        });
    }
}
