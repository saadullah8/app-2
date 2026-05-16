<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountConfirmSmsInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('count_confirm')->default(0)->after('sms_count');
            $table->integer('count_ready')->default(0)->after('count_confirm');
            $table->integer('count_delete')->default(0)->after('count_confirm');
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
            $table->dropColumn('count_confirm');
            $table->dropColumn('count_ready');
            $table->dropColumn('count_delete');
        });
    }
}
