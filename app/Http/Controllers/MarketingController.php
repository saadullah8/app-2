<?php

namespace App\Http\Controllers;

use App\Jobs\EmailSendingJob;
use App\Models\User;
use App\Services\TwilioService;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    public  function  __construct(){
        $this->middleware('auth');
        $this->middleware('CheckAccess');
    }

    public function index(){
        $customers=User::select('users.*')->where('role_id',2)->get();
        return view('marketing.index',compact('customers'));
    }
    public function marketing(Request $request){
       $rules= [
            'message'=>'required',
            'subject'=>'nullable'
        ];
        if(!$request->all_customer){
            $rules['customers']='required';
            $rules['customers.*']='required';
        }else{
            $rules['all_customer']='required';
        }
        $this->validate($request,$rules);
        try {
            $allow=false;
            if($request->has('email')){
                $allow=true;
            }elseif($request->has('sms')){
                $allow=true;
            }
            if(!$allow){
                return redirect()->back()->withInput()
                    ->with('error',"At least one marketing media is required");
            }
            $customers=$request->customers;
            $customersData=array();
            if($request->all_customer){
                $customersData= User::where('role_id',2)->get();
            }else{
                $customersData=User::whereIn('id',$customers)->get();

            }
            $message=$request->message;
            $subject=$request->subject?$request->subject:env('APP_NAME').' Mail';
            if(!$customersData->isEmpty()){
                foreach ($customersData as $value){
                    $phoneNumber=$value->phone;
                    $email=$value->email;
                    if($request->email=="email" && $email!=null){
                        EmailSendingJob::dispatch($email,$subject,$message)->delay(now()->addSeconds(10));
                    }if($request->sms=="SMS" && $phoneNumber!=null){
                        $twilio=new TwilioService();
                        $phone='+1'.$phoneNumber;
                        $twilio->bulkMessages($phone,$message);
                    }
                }
                return redirect()->back()->with('success',"Successfully send messages to customer");
            }
            return redirect()->back()->with('error',"Customer listing not founds");

        }catch (\Exception $exception){
            return redirect()->back()->withInput()->with('error',$exception->getMessage());
        }
    }
}
