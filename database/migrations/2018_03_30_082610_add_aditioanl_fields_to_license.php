<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAditioanlFieldsToLicense extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('licenses', function(Blueprint $table) {
            $table->unsignedInteger('media_id')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('expired_at')->nullable()->change();
            $table->string('invoice_number')->default('');
            $table->string('invoice_number_by')->default('');
            $table->string('any_limitations', 4096)->nullable();
            $table->string('territory')->nullable();

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
        Schema::table('licenses', function(Blueprint $table) {
            $table->dropColumn('start_at');
            $table->dropColumn('expired_at');
            $table->dropColumn('invoice_number');
            $table->dropColumn('invoice_number_by');
            $table->dropColumn('any_limitations');
            $table->dropColumn('territory');
            $table->dropColumn('media_id');
        });
    }
}
