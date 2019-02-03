<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFtpUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ftpuser', function (Blueprint $table) {
            $table->integer('user_id')->nullable();
            $table->integer('brand_id')->nullable();
            $table->integer('creative_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ftpuser', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('brand_id');
            $table->dropColumn('creative_id');
        });
    }
}
