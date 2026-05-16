<?php

namespace App\Listeners;

use App\Events\OrderEmailEvent;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\SenderEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
class OrderEmailListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */

    public function handle(OrderEmailEvent $order_id)
    {
        $order_id=$order_id->order_id;

        $email=SenderEmail::find(1)->email;
        $order = Order::find($order_id);
        $data['order']=$order;

        Mail::send(['html'=>'email.common'],$data,function($message) use($email){
            $message->from('contactus@gyrojoint.net', 'Gyro Joint');
            $message->to($email)->subject('order');
        });
        $customerEmail=$order->userDetail?$order->userDetail->email:"";

        Mail::send(['html'=>'email.customerEmail'],$data,function($message) use($customerEmail){
            $message->from('contactus@gyrojoint.net', 'Gyro Joint');
            $message->to($customerEmail)->subject('order');
        });
    }
    public  function AddTax($price){
        $tax=6/100; // 6% tax
        $amount=$price*$tax;
        $total_price=$price+$amount;
        return round($total_price,2);
    }
}
