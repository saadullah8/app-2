<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CustomizeProduct;
use App\Events\OrderEmailEvent;
use App\Models\Ingredient;
use App\Models\MainCourse;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\PromoCode;
use App\Models\User;
use App\Services\StripeService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('CheckOrder')->except(['UserDashBoard','OrderDetails','cartPage']);

    }
    public function cartPage()
    {
        if(Session::has('orders')){
            $card=Card::where('userId',Auth::id())->where('isDefault',1)->first();
            return view('index.cart',compact('card'));
        }
        else{
                return view('site.empty');
            }
    }
    public function redirectURL()
    {
        return redirect('/')->with('success_cart','open');
    }

    public function index(Request $request){
        if(Session::has('orders')){
            if($request->pickup_time){
                //"7:00 PM";//change it to 11:00 AM,21:00 PM it works
                $timeHours = date('H:i',strtotime($request->pickup_date));
                $time = Carbon::parse($timeHours)->format('H');
                $request['time'] =intval($time);
                $validate = Validator::make($request->all(), [
                    'pickup_date'=>['required','after:yesterday'],
                    'time' => ['required','integer','between:11,21']
                ]);
                if($validate->fails()){
                    return back()->withErrors($validate->errors())->withInput();
                }

            }
            try{
                DB::beginTransaction();
                $date=Carbon::now();
                $is_pickup=false;
                if($request->is_cart_form){
                    if($request->pickup_time){
                        $is_pickup=true;
                        $date=date('y-m-d H:i:s',strtotime($request->pickup_date));
                    }
                    $remark=$request->remark;
                    if($request->phone!='') {
                        $array['phone'] = $request->phone;
                    }
                    $array['can_sms']=$request->sms_update;
                    User::find(Auth::id())->update($array);
                }
                $orders =   session('orders');

                $create_order = new Order();
                $create_order->pickup_date = $date;
                $create_order->is_pickup = $is_pickup;
                $create_order->remark = $remark;
                $create_order->customer_id = Auth::id();
                $create_order->as_guest = 0;
                $create_order->save();
                $total_amount = 0;
                $type_='';
                foreach ($orders as $order){
                    $type_=isset($order['type'])?$order['type']:'Type Product';
                    $order_detail = new OrderDetail();
                    $order_detail->order_id = $create_order->id;
                    $order_detail->product_name = isset($order['product_name'])?$order['product_name']:'Test Product';
                    $order_detail->product_type = $type_;
                    if($type_=='meal'){
                        $dataArrang=array();
                        $sorting_aray=array();
                        $sorting_number=100;
                        $sortingfinaldata=array();
                        $main_course_array=array();
                        $dataArrang=$order['data'];
                        foreach ($dataArrang as $key=>$maincourse){
                            foreach($maincourse as $sub_key=>$row_){
                                if($sub_key=="main_course"){
                                    $main_course_array[$row_][]=array('title'=> "title");
                                }
                            }
                        }
                        foreach($main_course_array as $key => $main_c_details){
                            $name_=str_replace('_', ' ',$key);
                            $sorting= MainCourse::select('sorting')->where('name',$name_)->first();
                            if($sorting){
                                if($sorting->sorting!=null){
                                    $sorting_aray[$sorting->sorting]=$key;
                                }else{
                                    $sorting_aray[$sorting_number]=$key;
                                    $sorting_number--;
                                }
                            }else{
                                $sorting_aray[$sorting_number]=$key;
                                $sorting_number--;
                            }

                        }
                        ksort($sorting_aray);

                        foreach($sorting_aray as $sub_key=>$value){

                            foreach ($dataArrang as $key=>$maincourse){
                                foreach($maincourse as $subkey=>$row){
                                    if($subkey=="main_course"){
                                        if($value==$row){
                                            $maincourse['main_course']=str_replace('_extra','',$row);
                                            $sortingfinaldata[$key]=$maincourse;
                                        }
                                    }
                                }

                            }

                        }
                        $order_detail->detail=json_encode($sortingfinaldata);
                    }else{
                        $order_detail->detail = json_encode($order['data']);
                    }

                    $order_detail->qty = $order['qty'];
                    $order_detail->price = $order['price'];
                    if(isset($order['serial_number'])){
                        $order_detail->serial_number = $order['serial_number']? $order['serial_number']:'';
                    }
                    if(isset($order['note'])){
                        $order_detail->note = $order['note'];
                    }
                    if(isset($order['name'])){
                        $order_detail->name = $order['name'];
                    }
                    if(isset($order['exclude_item'])){
                        $order_detail->exclude_item = json_encode($order['exclude_item']);
                    }
                    if(isset($order['short_code'])){
                        $order_detail->short_code = json_encode($order['short_code']);
                    }
                    $total_amount+=$order['price'];
                    $order_detail->save();
                }
                $create_order->order_no = sprintf('%05d', $create_order->id);
                $total_amount=round($total_amount,2);
                $create_order->subTotal = $total_amount;
                $totalAmount=$this->AddTax($total_amount);
                $create_order->taxAmount = $totalAmount - $total_amount;
                $stripService=new StripeService();
                $paymentType=$request->paymentMethod;
                $stripeCustomerId=Auth::user()->stripeCustomerId;
                $promo=$request->discountCode;
                $promoInformation=PromoCode::where('code',$promo)->first();
                if($promoInformation){
                    $discountValue=$promoInformation->discountValue;
                    $priceDiscount=($discountValue/100)*$totalAmount;
                    $afterDiscountedAmount=$totalAmount-$priceDiscount;
                    $totalAmount=round($afterDiscountedAmount,2);
                    $create_order->discountAmount=$priceDiscount;
                    $create_order->promoCode=$promo;
                }
                if($paymentType=='COC'){
                    $cardInfo=Card::where('id',decrypt($request->cardSessionId))->first();
                    if($cardInfo){
                        $create_order->paymentType='CashOfCreditCard';
                        $stripeCardId=$cardInfo->cardId;

                        if(!$cardInfo->isDefault){
                            $stripService->setCustomerCardDefault([
                                'cardId' => $cardInfo->cardId,
                                'customerId' => $stripeCustomerId,
                            ]);
                            Card::where('userId',Auth::id())->where('id','!=',$cardInfo->id)->update(['isDefault'=>0]);
                            $cardInfo->isDefault=1;
                            $cardInfo->save();
                        }
                        $charge= $stripService->charge($stripeCustomerId, $totalAmount, $create_order->order_no);
                        info($charge);
                        /* getting array of result from stripe response */
                        if ($charge) {
                            $create_order->stripPaymentId = isset($charge['id'])?$charge['id']:null;
                            $create_order->stripInvoiceURL = isset($charge['receipt_url'])?$charge['receipt_url']:null;
                            $create_order->stripResponse = isset($charge['id'])?json_encode($charge):null;;
                        }else{
                            return redirect()->back()->with('error','Online payment is not completed please check save valid information!');
                        }

                        if($request->isSavedCard=="savedCard"){

                        }else{
                            if ($cardInfo->delete()) {
                                $stripService->deleteCustomerCard(['customerId' => $stripeCustomerId, 'cardId' => $stripeCardId]);
                            }
                        }
                    }
                }
                elseif ($paymentType=='CODC'){
                    $create_order->paymentType='CashOfCreditCard';
                    $charge= $stripService->charge($stripeCustomerId, $totalAmount, $create_order->order_no);
                    if ($charge) {
                        $create_order->stripPaymentId = isset($charge['id'])?$charge['id']:null;
                        $create_order->stripInvoiceURL = isset($charge['receipt_url'])?$charge['receipt_url']:null;
                        $create_order->stripResponse = isset($charge['id'])?json_encode($charge):null;;
                    }else{
                        return redirect()->back()->with('error','Online payment is not completed please check save valid information!');
                    }
                }else{
                    $create_order->paymentType="CashOfPickup";
                }
                $create_order->total_amount = $totalAmount;
                $create_order->save();
                $order_id = $create_order->order_no;
                DB::commit();
                Session::forget('orders');
                event(new OrderEmailEvent($create_order->id));
                return view('site.thankyou',compact('order_id'));
            }
            catch (\Exception $e){
                DB::rollback();
                $error=$e->getMessage();
                return view('site.order_not_place',compact('error'));

            }
        }else{
            return view('site.empty');
        }

    }
    public function UserDashBoard(){
        $cards=Card::where('userId',Auth::id())->get();
        $orders=Order::where('customer_id',Auth::id())->orderBy('created_at','desc')->get();
        return view('user_order.index',compact('orders','cards'));
    }
    public function OrderDetails($id){
        $id=decrypt($id);
        $order=Order::find($id);
        return view('user_order.order_detail',compact('order'));
    }
    public function reOrder(Request $request)
    {

        try{
            Session::forget('orders');
            $orderID=decrypt($request->order_id);
            $orderDetails=OrderDetail::where('order_id',$orderID)->get();
            $SerialNumber=$this->getSerialNumber();
            foreach ($orderDetails as $value){
                $product_id=Product::where('title',$value->product_name)->first();
                if($value->product_type=="customized"){
                    $extraPrice=0;
                    $checkItems=json_decode($value->detail,true);
                    $ingredientsData=Ingredient::whereIn('name', $checkItems)->get();
                    foreach($ingredientsData as $vc){
                        $extraPrice +=$vc->price ;
                    }
                    $meal= array(
                        "data"=>json_decode($value->detail),
                        "price"=>$value->qty*($product_id->price+$extraPrice),
                        'qty'=>$value->qty,
                        'extraPrice'=>$extraPrice,
                        'product_name'=>$value->product_name,
                        'type'=>'customized','product_id'=>$product_id->id,
                        'note'=>$value->note,'name'=>$value->name,
                        'exclude_item'=>json_decode($value->exclude_item));
                    \session()->push('orders',$meal);
                }elseif($value->product_type=="fixed"){
                    $product_fixed= array(
                        "data"=>json_decode($value->detail),
                        "price"=> $value->qty*$product_id->price,
                        'qty'=>$value->qty,
                        'product_name'=>$value->product_name,
                        'type'=>'fixed'
                    );
                    \session()->push('orders',$product_fixed);
                }elseif($value->product_type=="meal"){
                    $checkItems=json_decode($value->detail,true);
                    $qty_=$value->qty;
                    $product_price=$product_id->price;
                    $final_ing_price=0;
                    $sum_all_ing_price=0;
                    foreach ($checkItems as $keys=> $itemPriceC){
                        $extraPriceId=$itemPriceC['main_course_id'];
                        $extra_price=0;
                        $ing_price=0;
                        $final_ing_price=0;
                        $ingredientName=$itemPriceC['product_name'];
                        $isExtraIng=$itemPriceC['ingredient_id'];
                        $break_isExtraIng=explode('_',$isExtraIng);
                        if(isset($break_isExtraIng[1]) && $break_isExtraIng[1]=="extra"){
                            $extra_price_=CustomizeProduct::where('id',$extraPriceId)->first();
                            if($extra_price_){
                                $extra_price=$extra_price_->extra_price;
                            }
                            $name_ing= str_replace('Extra ','',$ingredientName);
                            $ingredientDetail=Ingredient::where('name',$name_ing)->first();
                            if($ingredientDetail){
                                $ing_price=$ingredientDetail->price;
                            }
                            $final_ing_price=$extra_price+$ing_price;
                        }else{
                            $ingredientDetail=Ingredient::where('name',$ingredientName)->first();
                            if($ingredientDetail){
                                $ing_price=$ingredientDetail->price;
                            }
                            $final_ing_price=$ing_price;
                        }
                        $checkItems[$keys]['ingredient_price']=$final_ing_price;
                        $sum_all_ing_price +=$final_ing_price;
                    }
                    $final_price=($sum_all_ing_price+$product_price)*$qty_;
                    $meal= array("data"=>$checkItems,
                        "price"=>$final_price,
                        "item"=>count($checkItems),
                        'qty'=>$value->qty,
                        'type'=>'meal',
                        'product_name'=>$value->product_name,
                        'serial_number'=>$SerialNumber,
                        'note'=>$value->note,
                        'name'=>$value->name);
                    \session()->push('orders',$meal);
                    $SerialNumber= $this->addSerialNumberPlus();
                }
            }

            return redirect('/')->with('success_cart','open');
        }catch (\Exception $e){
            return redirect()->back()->with('error',$e->getMessage());
//
        }
    }
    public function directOrder($orderID)
    {
        try{
            $orderDetail= Order::find($orderID);
            DB::beginTransaction();
            $date=Carbon::now();
            $create_order = new Order();
            $create_order->pickup_date = $date;
            $create_order->is_pickup = 0;
            $create_order->remark =$orderDetail->remark;
            $create_order->customer_id = $orderDetail->customer_id;
            $create_order->as_guest = 0;
            $create_order->order_status = 0;
            $create_order->order_no = $create_order->id;
            $create_order->total_amount = $orderDetail->total_amount;
            $create_order->save();
            $create_order->order_no = sprintf('%05d', $create_order->id);
            $create_order->save();
            $order_id = $create_order->order_no;

            $orderDetails=OrderDetail::where('order_id',$orderID)->get();
            foreach ($orderDetails as $value){
                $newOrder=new OrderDetail();
                $newOrder->order_id=$create_order->id;
                $newOrder->name=$value->name;
                $newOrder->serial_number=$value->serial_number;
                $newOrder->product_name=$value->product_name;
                $newOrder->product_type=$value->product_type;
                $newOrder->detail=$value->detail;
                $newOrder->exclude_item=$value->exclude_item;
                $newOrder->qty=$value->qty;
                $newOrder->price=$value->price;
                $newOrder->note=$value->note;
                $newOrder->save();
            }
            DB::commit();
            event(new OrderEmailEvent($create_order->id));
            return $order_id;
        }catch (\Exception $e){
            DB::rollBack();
            return $e;
        }

    }
    public function customerPromocode($promo){
        try {
            $promoCode = PromoCode::whereCode($promo)->first();
            if ($promoCode) {
                if ($promoCode->status == "Expired") {
                    return response()->json(['status' => false, 'message' => 'Promo code has been expired!']);
                }
                /* if today is token expiry date or already expired before today */
                if (date('Y-m-d', $promoCode->expiredAt) <= date('Y-m-d', strtotime(now()))) {
                    return response()->json(['status' => false, 'message' =>'Promo code has been expired!']);
                }
                $todayBuyDeal = Order::where('customer_id',Auth::id())->where('promoCode',$promo)->count();
                if ($todayBuyDeal>0) {
                    return response()->json(['status' => false, 'message' =>'Sorry! promo code already used']);
                }
                return response()->json(['status' => true, 'message' => 'Congratulations! You can buy product with discount','data'=>$promoCode]);
            }
            return response()->json(['status' => false, 'message' => 'Promo code is invalid!']);

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' =>$e->getMessage()]);
        }
    }
}
