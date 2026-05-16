<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('userId');
            $table->string('cardId', 500);
            $table->string('lastDigits');
            $table->string('brand', 255);
            $table->string('brandImageURL', 255);
            $table->boolean('isDefault')->default(1);
            $table->unsignedInteger('createdAt')->nullable();
            $table->unsignedInteger('updatedAt')->nullable();
            $table->foreign('userId')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }
}
