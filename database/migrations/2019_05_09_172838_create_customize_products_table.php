<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomizeProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customize_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('product_id');
            $table->integer('main_course_id');
            $table->integer('min_value')->default(1);
            $table->integer('max_value')->default(100);

            $table->integer('half')->default(0);
            $table->integer('dressing')->default(0);
            $table->integer('skip')->default(0);
            $table->integer('extra_charge')->default(0);

            $table->float('extra_price')->nullable();
            $table->integer('min_extra_limit')->default(1);
            $table->integer('max_extra_limit')->default(100);

            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customize_products');
    }
}
