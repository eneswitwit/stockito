<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('currency');
            $table->double('price');
            $table->text('description');
            $table->string('stripe_id');
            $table->string('interval');
            $table->double('storage');

            $table->timestamps();
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('title');
            $table->dropColumn('currency');
            $table->dropColumn('description');
            $table->dropColumn('storage');
            $table->dropColumn('interval');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');

    }
}
