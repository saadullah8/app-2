<?php

namespace App\Http\Controllers;

use App\Models\CustomizeProduct;
use App\Models\Ingredient;
use App\Models\MainCourseList;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\SenderEmail;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
class IndexController extends Controller
{
    public function index($title=null){
        $optionalCategory=Type::select('name')->whereIn('name',[
            'Rice Platters','Shawarma'
        ])->get()->pluck('name')->toArray();
        $categories=Type::whereIn('category_id',[1])->get();
        $sectionProducts=Type::whereIn('category_id',[2])->get();
        return view('index.index',compact('categories','title','sectionProducts','optionalCategory'));
    }
    public function StoreClosed(){
        return view('site.orderClosed');
    }
    public function EmptyCart(){
        Session::forget('orders');
        Session::forget('serial_number');
        return redirect('/')->with('success_cart','open');
    }
    public function getCookie() {
       // Session::forget('orders');
         dd(Session::get('orders'));

    }
    public function contactUs(){
        return view('site.contactus');
    }
    public function contactForm(Request $request){
        $this->validate($request,[
            'email'=>'email|required|max:150|min:3',
            'name'=>'string|required|max:100',
            'phone'=>'string|required|max:100|',
            'message'=>'string|required|max:1000|',
        ]);
        try{
            $email=$request->email;
            $name=$request->name;
            $phone=$request->phone;
            $txt ="email".$email."\r\n"."name: ".$name. "\r\n" . "phone: ".$phone. "\r\n" . "Message: " .$request->message;

            $email_=SenderEmail::find(2)->email;
            Mail::raw($txt,function($message) use($email_){
                $message->from('noreply@aromagrillhouse.com', env('APP_NAME'));
                $message->to($email_)->subject('Contact Us');
            });

            return redirect()->back()->with('message',"Thanks for Contact us");

        }catch (\Exception $e){
            return redirect()->back()->withInput()->with('message',$e->getMessage());
        }

    }
    public function aboutUs(){

        return view('site.aboutus');
    }

    public function thankyou(){
        return view('site.thankyou');
    }



}
