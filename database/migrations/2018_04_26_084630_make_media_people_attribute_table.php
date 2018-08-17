<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeMediaPeopleAttributeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_people_attribute', function (Blueprint $blueprint) {
            $blueprint->increments('id');
            $blueprint->unsignedInteger('media_id');
            $blueprint->unsignedInteger('people_attribute_id');

            $blueprint->foreign('media_id')->references('id')->on('media')->onDelete('cascade');
            $blueprint->foreign('people_attribute_id')->references('id')->on('people_attributes')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('media_people_attribute');
    }
}
