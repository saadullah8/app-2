<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CancelMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $orderId;
    public $order;
    public function __construct($orderId)
    {
        $this->orderId=$orderId;
        // $email=SenderEmail::find(1)->email;
        $order = Order::find($this->orderId);
        $data['order']=$order;
        $this->order=$order;

        $customerEmail=$order->userDetail?$order->userDetail->email:"";
        Mail::send(['html'=>'email.cancelMail'],$data,function($message) use($customerEmail){
        $message->from('contactus@gyrojoint.net', 'Gyro Joint');
        $message->to($customerEmail)->subject('order');
    });
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('contactus@gyrojoint.net', 'Gyro Joint')  // Set the sender's email
        ->subject('Cancel Order')
        ->view('email.cancelMail')
        ->with('order', $this->order);
    }
}
