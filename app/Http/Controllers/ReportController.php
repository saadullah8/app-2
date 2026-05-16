<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public  function  __construct(){
        $this->middleware('auth');
        $this->middleware('CheckAccess');
    }
    public function index(){
        $orders=Order::all();
        $status='active';
        $fromDate=null;
        $toDate=null;
        $counter=$orders->count();
        return view('orders.filterOrders',compact('orders','counter','status','fromDate','toDate'));
    }
    public function filterOrder(Request  $request){
        $query = Order::query();
        if (isset($request->status)){
            if($request->status=="active"){
                $query = $query->where('order_status',0);
            }elseif ($request->status=="completed"){
                $query = $query->where('order_status',1);
            }else{
                $query = $query->where('order_status',2);
            }

        }
        if($request->fromDate && $request->toDate) {
            $startedDate=Carbon::parse($request->fromDate)->startOfDay();
            $endDate=Carbon::parse($request->toDate)->endOfDay();
            $query = $query->whereBetween('created_at',[$startedDate,$endDate]);

        }
        $orders = $query->orderby('id','desc')->get();
        $counter=$orders->count();
        $status=$request->status?$request->status:'active';
        $fromDate=$request->fromDate;
        $toDate=$request->toDate;
        return view('orders.filterOrders',compact('orders','counter','status','fromDate','toDate'));
    }
}
