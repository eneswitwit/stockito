<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakePeopleAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people_attributes', function (Blueprint $blueprint) {
            $blueprint->increments('id');
            $blueprint->string('name');
            $blueprint->unsignedInteger('brand_id');
            $blueprint->unsignedInteger('created_by')->nullable();
            $blueprint->unsignedInteger('updated_by')->nullable();
            $blueprint->timestamps();

            $blueprint->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $blueprint->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $blueprint->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('people_attributes');
    }
}
