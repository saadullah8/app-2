<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Session;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public  function AddTax($price){
        $tax=6/100; // 6% tax
        $amount=$price*$tax;
        $total_price=$price+$amount;
        return round($total_price,2);
    }

    public function getSerialNumber(){
        if(Session::has('serial_number')){
            return Session::get('serial_number');
        }
        session(['serial_number'=>1]);
        return  Session::get('serial_number');
    }
    public function addSerialNumberPlus(){
        if(Session::has('serial_number')){
            $serial_number= Session::get('serial_number');
            $serial_number=$serial_number+1;
            session(['serial_number'=>$serial_number]);
        }
    }
    public function forgetSerialNumber(){
        Session::forget('serial_number');
    }
}
