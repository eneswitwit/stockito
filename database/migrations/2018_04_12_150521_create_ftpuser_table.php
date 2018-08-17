<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtpuserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftpuser', function (Blueprint $table) {
            $table->increments('id');
            $table->string('userid', 32)->unique()->default('');
            $table->string('passwd', 32)->default('');
            $table->smallInteger('uid')->default(5500);
            $table->smallInteger('gid')->default(5500);
            $table->string('homedir', 255)->default('');
            $table->string('shell', 16)->default('/sbin/nologin');
            $table->integer('count')->default(0);
            $table->dateTime('accessed')->default(\Carbon\Carbon::now());
            $table->dateTime('modified')->default(\Carbon\Carbon::now());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ftpuser');
    }
}
