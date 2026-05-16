<?php

namespace App\Http\Controllers;

use App\DataTables\CancelOrderDataTable;
use App\DataTables\ProcessDataTable;
use App\Models\CustomizeProduct;
use App\Models\DefaultMessage;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReadyMail;
use App\Mail\CancelMail;



class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('CheckAccess');
    }

    public function pendingOrders()
    {
        $orders = Order::where('order_status', 0)->orderby('created_at', 'desc')->get();
        return view('orders.pending', compact('orders'));
    }

    public function ReadyOrders(ProcessDataTable $dataTable)
    {
        return $dataTable->render('orders.processDatatable');

    }

    public function cancelOrders(CancelOrderDataTable $dataTable)
    {
        return $dataTable->render('orders.cancelOrderDatatable');

    }

    public function orderDetails(Request $request, $id)
    {
        try {
            $id = decrypt($id);
            $order = Order::where('id', $id)->first();
            return view('orders.detail', compact('order'));
        } catch (\Exception $e) {
            abort('404');
        }
    }

    public function deleteItemOrder(Request $request)
    {
        try {
            $orderDetailId= decrypt($request->id);
            $orderDetail = OrderDetail::where('id', $orderDetailId)->first();
            if ($orderDetail) {
                $order_id = $orderDetail->order_id;
               $totalItem= OrderDetail::where('order_id',$order_id)->where('id','!=',$orderDetailId)->sum('id');
               if($totalItem==0){
                   return redirect()->back()->with('error', 'At least one item must be added in order');
               }
                DB::beginTransaction();
                $orderDetail->delete();
                $this->orderTotalUpdate($order_id);
                DB::commit();
                return redirect()->back()->with('success', 'Order item deleted successfully');

            }
            return redirect()->back()->with('error', "Id invalid");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function editSaveOrder(Request $request)
    {
        $this->validate($request, [
            'orderId' => 'required',
            'orderType' => 'required|in:Edit,Add',
            'productId' => 'required',
            'type' => 'required|in:fixed,customized',
            'qty' => 'required|numeric|min:0|not_in:0',
            'ingredient' => 'required_if:type,customized',
        ]);
        try {
            $orderStyle=$request->orderType;
            $productId = $request->productId;
            $type = $request->type;
            $excludeIng = $ingredient = array();
            $extraPrice = 0;
            if ($type == "customized") {
                $ingredient = $request->ingredient;
                $ingredientsData = Ingredient::whereIn('name', $ingredient)->get();
                foreach ($ingredientsData as $vc) {
                    $extraPrice += $vc->price;
                }
                $excludeIng = $ingredient;
            } else {
                $ingredient = ['product_id' => $productId];
            }
            DB::beginTransaction();
            $orderId = $request->orderId;
            $product = Product::find($productId);
            $price = $product->price + $extraPrice;
            $order_detail = null;
            if($orderStyle=="Edit"){
                $order_detail=OrderDetail::where('id',$request->orderDetailId)->first();
            }else{
                $order_detail =  new OrderDetail();
            }
            $order_detail->order_id = $orderId;
            $order_detail->product_name = $product->title;
            $order_detail->product_type = $type;
            $order_detail->qty = $request->qty;
            $order_detail->price = $price;
            $order_detail->detail = json_encode($ingredient);
            $order_detail->short_code = json_encode($excludeIng);
            $order_detail->exclude_item = json_encode($excludeIng);
            $order_detail->save();
            $this->orderTotalUpdate($orderId);
            DB::commit();
            return redirect()->back()->with('success', 'Order item added successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function orderTotalUpdate($orderId)
    {
        $order = Order::find($orderId);
        $totalPrice = OrderDetail::where('order_id', $orderId)->sum(DB::raw('qty * price'));
        $totalOrderPrice = $includeTax = $this->AddTax($totalPrice);
        $tax = $includeTax - $totalPrice;
        $promoCode = $order->promoCode;
        $promo = PromoCode::where('code', $promoCode)->first();
        $discountPrice = 0;
        if ($promo) {
            $discount = $promo->discountValue;
            $discountPrice = ($discount / 100) * $includeTax;
            $totalOrderPrice = $includeTax - $discountPrice;
        }
        $order->subTotal = $totalPrice;
        $order->taxAmount = $tax;
        $order->discountAmount = $discountPrice;
        $order->total_amount = $totalOrderPrice;
        $order->save();
        return true;
    }

    public function editOrder($id)
    {
        try {
            $id = decrypt($id);
            $products = Product::whereIn('category_id', [1, 2])->where('status', 0)->get();
            $order = Order::where('id', $id)->first();
            return view('orders.editOrder', compact('order', 'products'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
//
        }
    }

    public function orderPrinter($id)
    {
        try {
            $id = decrypt($id);
            $order = Order::where('id', $id)->first();
            return view('orders.printer_file', compact('order'));
        } catch (\Exception $e) {
            abort('404');
        }
    }

    public function orderMealPrint($id)
    {
        try {
            $id = decrypt($id);
            $order = Order::where('id', $id)->first();
            $tax = $this->AddTax($order->total_amount);
            return view('orders.print_meal', compact('order', 'tax'));
        } catch (\Exception $e) {
            abort('404');
        }
    }

    public function OrderStatus(Request $request)
    {

        try {
            $id = decrypt($request->id);
            $status = $request->status;
            $orderId=$id;
            $success_msg = "Order is Ready";
            $cancel_msg = "Order Cancelled";
            if ($status == 1) {
                Order::where('id', $id)->update(array('order_status' => 1));
                $result = $this->sendMessageOrder('button_ready_order', $id);
                 Mail::send(new ReadyMail($orderId));
                if ($result instanceof \Exception) {
                    return redirect()->back()->with('error', "Out of Balance Please Recharge");
                }

                if ($result == '1234') {
                    $success_msg = $success_msg . ' and also message has been send';
                }

                return redirect()->back()->with('success', $success_msg);
            } elseif ($status == 2) {
                Order::where('id', $id)->update(array('order_status' => 2));
                $result = $this->sendMessageOrder('button_cancel_order', $id);
                  Mail::send(new CancelMail($orderId));
                if ($result instanceof \Exception) {
                    return redirect()->back()->with('error', "Out of Balance Please Recharge");
                }

                if ($result == '1234') {
                    $cancel_msg = $cancel_msg . ' and also message has been send';
                }

                return redirect()->back()->with('success', $cancel_msg);
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function sendMessageOrder($type, $order_id)
    {
        try {
            $message_read = DefaultMessage::where('type', $type)->first();
         
            if ($message_read) {
                $order = Order::find($order_id);
                if ($type == 'new_order_tab') {
                    $order->count_confirm = 1;
                }
                if ($type == 'pick_up_order_tab') {
                    $order->count_ready = 1;
                }
                if ($type == 'cancel_order_tab') {
                    $order->count_delete = 1;
                }
                $order->sms_count += 1;
                $order->save();

                if ($order->userDetail->can_sms) {
                    $message_read = $message_read->message;
                    $message_read = str_replace('{{name}}', $order->userDetail->first_name, $message_read);
                    $message_read = str_replace('{{orderNo}}', $order->order_no, $message_read);
                    $number = '+1' . str_replace('-', '', $order->userDetail->phone);
                    $message = $message_read;
                    $client = new Client(env("TWILIO_SID"), env("TWILIO_AUTH_TOKEN"));
                    $client->messages->create(
                        $number,
                        array(
                            'from' => env("TWILIO_NUMBER"),
                            'body' => $message
                        )
                    );
                    return '1234';
                }
                return '1122';
            }
            return false;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function sendSMS(Request $request)
    {
        try {
            $order_id = $request->order_id;
            $order_id = decrypt($order_id);
            $orderId=$order_id;
            $typeOrder = $request->type;
         
              $result = $this->sendMessageOrder($typeOrder, $order_id);
              
            if ($result instanceof \Exception) {
                return redirect()->back()->with('error', "Out of Balance Please Recharge");
            }
            if ($result == '1234') {
                $success_msg = "message has been send";
                return redirect()->back()->with('success', $success_msg);
            }
            if ($result == '1122') {
                $success_msg = "Customer not allow to sent any message";
                return redirect()->back()->with('success', $success_msg);
            }
            return redirect()->back()->with('error', "something want wrong please try again");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Out of Balance Please Recharge");
        }
    }

}
