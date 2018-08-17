<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activities', function (Blueprint $blueprint) {
            $blueprint->text('message');

            $blueprint->dropColumn('target_id');
            $blueprint->dropColumn('origin_id');
            $blueprint->dropColumn('target_type');
            $blueprint->dropColumn('origin_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activities', function (Blueprint $blueprint) {
            $blueprint->dropColumn('message');
        });
    }
}
