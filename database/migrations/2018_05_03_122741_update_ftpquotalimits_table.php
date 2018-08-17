<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFtpquotalimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ftpquotalimits', function (Blueprint $blueprint) {
            $blueprint->dropColumn('bytes_out_avail');
        });

        Schema::table('ftpquotalimits', function (Blueprint $blueprint) {
            $blueprint->unsignedBigInteger('bytes_out_avail');
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
