<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMetaFieldsToMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->string('file_type')->nullable();
            $table->string('keywords', 2048)->nullable();
            $table->string('source')->nullable();
            $table->string('language')->nullable();
            $table->unsignedInteger('category_id')->nullable();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->removeColumn('file_type');
            $table->removeColumn('keywords');
            $table->removeColumn('source');
            $table->removeColumn('language');
            $table->removeColumn('category_id');
        });
    }
}
