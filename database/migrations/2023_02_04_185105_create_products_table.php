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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pname');
            $table->string('description');
            $table->bigInteger('price');
            $table->integer('quantity');
            $table->integer('userID');
            $table->string( 'photoURL');
            $table->integer('cate_id');
       
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
        Schema::dropIfExists('products');
    }
};