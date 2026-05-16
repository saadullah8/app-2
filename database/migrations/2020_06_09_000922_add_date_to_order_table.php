<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateToOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('remark')->nullable()->after('order_status');
            $table->timestamp('pickup_date')->nullable()->after('remark');
            $table->boolean('is_pickup')->default(false)->after('pickup_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('remark');
            $table->dropColumn('pickup_date');
            $table->dropColumn('is_pickup');
        });
    }
}
