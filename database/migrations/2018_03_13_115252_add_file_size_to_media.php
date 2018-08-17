<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFileSizeToMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('media', function (Blueprint $table) {
			//  file_size - 116542
			$table->string('file_size')->nullable();
			// content_type - image/jpeg
			$table->string('content_type')->nullable();
			//  dir - adbfb3465a8b39e99a542113de019b60
			$table->string('dir')->nullable();
			// file_name - adbfb3465a8b39e99a542113de019b60.jpg
			$table->string('file_name')->nullable();
			// origin_name - point.jpg
			$table->string('origin_name')->nullable();
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
			$table->dropColumn('file_size');
			$table->dropColumn('content_type');
			$table->dropColumn('dir');
			$table->dropColumn('file_name');
			$table->dropColumn('origin_name');
		});
    }
}
