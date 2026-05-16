<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use PDF;

class APIController extends Controller
{
    public  function makeHtmlFile(Request $request){
        $order_id = $request->input('order_no');
        $order=Order::where('order_no',$order_id)->first();
        if($order){
           return view('email.htmlmail',compact('order'));
        }
        return false;

    }
    public function getOrders(Request $request){
        $order_order=$request->input('order_no');
        $key=$request->input('key');
        $output['success'] = false;
        $output['message'] = "";
        if($key != "appSetup_key"){
            try {
                switch($key){
                    case 'latest_order':
                        $order=Order::select('order_no')->where('order_no','>',$order_order)->get();
                        if(!$order->isEmpty()){
                            $output['order'] = $order;
                            $output['success'] = true;
                            $output['message']="Send Order Listing Successfully";
                            return json_encode($output);
                        }
                        $output['message']="Orders not found";
                        return json_encode($output);
                        break;
                }
                $output['message']="Key not found";
                return json_encode($output);
            } catch (\Exception $e) {
                $output['message'] = $e->getMessage();
                return json_encode($output);
            }
        }else{
            echo "Access Denied";
        }
    }
    public function makePDF(Request $request){
        $key = $request->input('key');
        $order_id = $request->input('order_no');
        $output['success'] = false;
        $output['message'] = "";
        if($key != "appSetup_key"){
            try {
                switch($key){
                    case 'pdf':
                        $order=Order::where('order_no',$order_id)->first();
                        if($order){
//

                            $customPaper = array(0,0, 900, 300);
                            $pdf = PDF::loadView('orders.pending_print_file', compact('order'))
                                //->setOptions(['dpi' => 150, 'defaultFont' => 'arial'])
                                ->setPaper($customPaper, 'landscape');
                            return $pdf->download('order.pdf');
                        }
                        $output['message']="Order No not found";
                        return json_encode($output);
                    break;
                }
                $output['message']="Key not found";
                return json_encode($output);
            } catch (\Exception $e) {
                $output['message'] = $e->getMessage();
                return json_encode($output);
            }
        }else{
            echo "Access Denied";
        }
    }
}
