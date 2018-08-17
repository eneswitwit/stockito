<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtpquotalimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftpquotalimits', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name')->nullable();
            $table->enum('quota_type', ['user', 'group', 'class', 'all'])->default('user');
            $table->enum('per_session', ['false', 'true'])->default('false');
            $table->enum('limit_type', ['soft', 'hard'])->default('soft');
            $table->unsignedInteger('bytes_in_avail')->default(0);
            $table->unsignedInteger('bytes_out_avail')->default(0);
            $table->unsignedInteger('bytes_xfer_avail')->default(0);
            $table->unsignedInteger('files_in_avail')->default(0);
            $table->unsignedInteger('files_out_avail')->default(0);
            $table->unsignedInteger('files_xfer_avail')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ftpquotalimits');
    }
}
