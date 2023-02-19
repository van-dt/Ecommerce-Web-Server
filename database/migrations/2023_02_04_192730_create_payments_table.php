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
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userID')->unsigned();
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->integer('productID')->unsigned();
            $table->foreign('productID')->references('id')->on('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->tinyInteger('status');
            $table->tinyInteger('select');
            $table->timestamp('created_at')->default(now()->toDateTimeString());
            $table->timestamp('updated_at')->default(now()->toDateTimeString());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};