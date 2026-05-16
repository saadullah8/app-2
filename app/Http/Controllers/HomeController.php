<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('CheckAccess');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product=Product::get()->count();
        $customer=User::where('role_id',2)->count();
        $ingredient=Ingredient::get()->count();
        $orders=Order::get()->count();
        return view('home',compact('product','customer','ingredient','orders'));
    }


    public function logout(){

        Auth::logout();

        return redirect(url('login'));
    }
}
