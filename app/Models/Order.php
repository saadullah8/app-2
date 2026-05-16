<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable=[
        'paymentType','discountAmount','promoCode','stripPaymentId','stripInvoiceURL','stripResponse',
        'subTotal','taxAmount'
    ];
    public function details()
    {
        return $this->hasMany('App\Models\OrderDetail');
    }
    public function userDetail(){
        return $this->belongsTo('App\Models\User','customer_id');
    }
}
