<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SenderEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
class EmailController extends Controller
{
    public function emailSend(){
//       $id = decrypt($id);
        $email=SenderEmail::find(1)->email;
        $order = Order::orderBy('id','desc')->first();
        $tax= $this->AddTax($order->total_amount);
        $data['order']=$order;
        $data['tax']=$tax;
        Mail::send(['html'=>'email.file'],$data,function($message) use($email){
            $message->from('contactus@gyrojoint.net', 'Gyro Joint');
            $message->to($email)->subject('order');
        });

        Mail::send(['html'=>'email.customfile'],$data,function($message) use($email){
            $message->from('contactus@gyrojoint.net', 'Gyro Joint');
            $message->to($email)->subject('order');
        });

    }
}
