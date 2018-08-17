<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBrandsUsedStorage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brands', function (Blueprint $blueprint) {
            $blueprint->unsignedInteger('used_storage_photo')->default(0);
            $blueprint->unsignedInteger('used_storage_illustration')->default(0);
            $blueprint->unsignedInteger('used_storage_video')->default(0);
            $blueprint->dropColumn('used_storage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('brands', function (Blueprint $blueprint) {
            $blueprint->dropColumn('used_storage_photo');
            $blueprint->dropColumn('used_storage_illustration');
            $blueprint->dropColumn('used_storage_video');
            $blueprint->unsignedInteger('used_storage')->default(0);
        });
    }
}
