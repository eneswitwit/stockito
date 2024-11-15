<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastLoginToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('users', function ($table) {
			$table->timestamp('last_login')->nullable();
		});

		Schema::table('admins', function ($table) {
			$table->timestamp('last_login')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('users', function ($table) {
			$table->dropColumn('last_login');
		});

		Schema::table('admins', function ($table) {
			$table->dropColumn('last_login');
		});
    }
}
