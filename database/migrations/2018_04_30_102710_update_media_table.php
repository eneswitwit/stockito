<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('media', function (Blueprint $blueprint) {
            $blueprint->enum('orientation', [\App\Models\Media::PORTRAIT, \App\Models\Media::LANDSCAPE])->default(\App\Models\Media::LANDSCAPE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('media', function (Blueprint $blueprint) {
            $blueprint->dropColumn('orientation');
        });
    }
}
