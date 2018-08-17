<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtpgroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftpgroup', function (Blueprint $table) {
            $table->increments('id');
            $table->string('groupname')->default('');
            $table->smallInteger('gid')->default(5500);
            $table->string('members', 4096)->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ftpgroup');
    }
}
