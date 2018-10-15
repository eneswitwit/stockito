<?php

// use
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateUsageLicensesTable
 */
class CreateUsageLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usage_licenses', function (Blueprint $table) {
            $table->increments('id');

            // foreign key
            $table->integer('license_id');

            // data
            $table->string('usage')->nullable();
            $table->string('printrun')->nullable();

            $table->string('bill_file')->nullable();
            $table->string('bill_file_origin_name')->nullable();

            $table->string('invoice_number')->nullable();
            $table->string('invoice_number_by')->nullable();

            $table->dateTime('start_at')->nullable();
            $table->dateTime('expired_at')->nullable();

            $table->string('any_limitations', 4096)->nullable();
            $table->string('territory')->nullable();

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
        Schema::dropIfExists('usage_licenses');
    }
}
