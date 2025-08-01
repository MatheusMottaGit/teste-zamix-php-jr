<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductComposesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_composes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('simple_product_id');
            $table->unsignedInteger('compound_product_id');
            $table->integer('simple_product_quantity');
            $table->timestamps();

            $table->foreign('simple_product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('compound_product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_composes');
    }
}
