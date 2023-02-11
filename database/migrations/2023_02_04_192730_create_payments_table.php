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

            $table->integer('userID');
            $table->integer('productID');
            $table->integer('quantity');
            $table->tinyInteger('status');
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