<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveMediaIdFromShare extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $shares = \App\Models\Share::all();

        $shares->each(function (\App\Models\Share $share){
            $share->medias()->attach([$share->media_id]);
        });

        Schema::table('shares', function (Blueprint $table){
            $table->dropForeign('shares_media_id_foreign');
            $table->dropColumn('media_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
