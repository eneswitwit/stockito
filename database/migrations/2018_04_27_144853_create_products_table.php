<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stripe_id');
            $table->string('name');
            $table->unsignedInteger('product_for_update_id')->nullable();
            $table->unsignedInteger('ftp_group_id')->nullable();
            $table->double('storage');
            $table->timestamps();

            $table->foreign('ftp_group_id')->references('id')->on('ftpgroup')->onDelete('set null');
            $table->foreign('product_for_update_id')->references('id')->on('products')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
