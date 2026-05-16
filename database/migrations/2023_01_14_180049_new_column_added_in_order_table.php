<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewColumnAddedInOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('promoCode')->after('as_guest')->nullable();
            $table->decimal('subTotal',10,2)->default(0)->after('promoCode');
            $table->decimal('taxAmount',10,2)->default(0)->after('subTotal');
            $table->decimal('discountAmount',10,2)->default(0)->after('taxAmount');
            $table->enum('paymentType',['CashOfPickup','CashOfCreditCard'])->after('total_amount')->default('CashOfPickup');
            $table->text('stripPaymentId')->after('paymentType')->nullable();
            $table->text('stripInvoiceURL')->after('stripPaymentId')->nullable();
            $table->text('stripResponse')->after('stripInvoiceURL')->nullable();
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
            $table->dropColumn(['stripResponse','paymentType','discountAmount','promoCode','stripPaymentId','stripInvoiceURL','subTotal','taxAmount']);
        });
    }
}
