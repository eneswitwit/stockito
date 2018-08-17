<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('brand_name');
            $table->string('company_name');
            $table->string('address_1');
            $table->string('address_2');
            $table->string('city');
            $table->string('zip');
            $table->unsignedInteger('country_id');
            $table->string('eur_uid');
            $table->string('homepage');
            $table->string('phone');
            $table->string('contact_first_name');
            $table->string('contact_last_name');
            $table->string('contact_title');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brands');
    }
}
