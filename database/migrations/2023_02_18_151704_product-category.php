<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product-category', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('categoryID')->unsigned();
            $table->foreign('categoryID')->references('id')->on('categories')->onDelete('cascade');
            $table->integer('productID')->unsigned();
            $table->foreign('productID')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
