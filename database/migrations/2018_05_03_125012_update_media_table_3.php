<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMediaTable3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('media', function (Blueprint $blueprint) {
            $blueprint->string('temp', 4096)->nullable();
        });

        \App\Models\Media::all()->each(function (\App\Models\Media $media) {
            $media->temp = $media->keywords;
            $media->save();
        });

        Schema::table('media', function (Blueprint $blueprint) {
            $blueprint->dropColumn('keywords');
        });

        Schema::table('media', function (Blueprint $blueprint) {
            $blueprint->string('keywords', 4096)->nullable();
        });

        \App\Models\Media::all()->each(function (\App\Models\Media $media) {
            $media->keywords = $media->temp;
            $media->save();
        });

        Schema::table('media', function (Blueprint $blueprint) {
            $blueprint->dropColumn('temp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
