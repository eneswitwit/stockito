<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFTPFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftp_files', function (Blueprint $table) {
            $table->increments('id');
            $table->text('file');
            $table->unsignedBigInteger('size');
            $table->string('username');
            $table->timestamps();
            $table->boolean('handled')->default(false);
            $table->dateTime('handled_at')->nullable();
            $table->unsignedInteger('media_id')->nullable();

            $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ftp_files');
    }
}
