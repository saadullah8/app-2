<?php

namespace App\Http\Controllers;

use App\Models\CustomizeProduct;
use App\Models\Ingredient;
use App\Models\MainCourseList;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function __construct()
    {
        // $this->middleware('CheckOrder');
    }

    public function MenuMeal($Pid)
    {
        $serial_number = $this->getSerialNumber();
        $meals = Product::find($Pid);
        $id = '';
        return view('index.menu', compact('meals', 'id', 'serial_number'));
    }

    public function addCartSession(Request $request)
    {
        $this->validate($request, [
            'ingredient' => 'required',
            'note' => 'nullable|string|max:200|min:1',
            'name' => 'string|nullable|max:100|min:1',
        ]);

        try {
            if ($request->has('edit_id')) {
                $id = $request->input('edit_id');
                $serial_number = $this->mealRemoved($id);
            } else {
                $serial_number = $this->getSerialNumber();
            }
            $data_cart = $request->ingredient;
            $dataSorting = array();
            $sorting = 0;
            foreach ($data_cart as $key => $value) {
                $sorting += 1;
                $id = str_replace('product_', '', $key);
                $key_main = $key;
                $sortingData = MainCourseList::find($id);
                if ($sortingData) {
                    if ($sortingData->sorting != null) {
                        $sorting = $sortingData->sorting;
                    }

                } else {
                    $id_ = str_replace('product_', '', $key);
                    $id = str_replace('_extra', '', $id_);
                    $sortingData = MainCourseList::find($id);
                    if ($sortingData) {
                        if ($sortingData->sorting != null) {
                            $sorting = $sortingData->sorting;
                        }
                    }
                }

                $value['sorting'] = (int)$sorting;
                $dataSorting[$key_main] = $value;
                $sorting++;
            }

            $data_cart_ = $this->array_msort($dataSorting, array('sorting' => SORT_ASC));

            $total_amount = $request->total_price;
            $total_item = $request->total_item;
            $product_name = $request->product_name;
            $note = $request->note ? $request->note : '';
            $name = $request->name ? $request->name : '';
            $qty = $request->qty ? $request->qty : 1;

            $meal = array("data" => $data_cart_, "price" => $total_amount * $qty, "item" => $total_item, 'qty' => $qty,
                'type' => 'meal', 'product_name' => $product_name, 'serial_number' => $serial_number, 'note' => $note, 'name' => $name);
            \session()->push('orders', $meal);
            $this->addSerialNumberPlus();

            return redirect('/')->with('success_cart', 'open');
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function array_msort($array, $cols)
    {
        $colarr = array();
        foreach ($cols as $col => $order) {
            $colarr[$col] = array();
            foreach ($array as $k => $row) {
                $colarr[$col]['_' . $k] = strtolower($row[$col]);
            }
        }
        $eval = 'array_multisort(';
        foreach ($cols as $col => $order) {
            $eval .= '$colarr[\'' . $col . '\'],' . $order . ',';
        }
        $eval = substr($eval, 0, -1) . ');';
        eval($eval);
        $ret = array();
        foreach ($colarr as $col => $arr) {
            foreach ($arr as $k => $v) {
                $k = substr($k, 1);
                if (!isset($ret[$k])) $ret[$k] = $array[$k];
                $ret[$k][$col] = $array[$k][$col];
            }
        }
        return $ret;

    }

    public function editFixed(Request $request)
    {
        $this->validate($request, [
            'qty' => 'required|numeric|min:0|not_in:0'
        ]);
//        dd($request->all());
        try {
            $id_session = $request->id;
            $product_id = $request->product_id;
            $qty = $request->qty;
            $total_amount = $this->getTotalPrices($product_id, $qty);
            $this->mealRemoved($id_session);
            $data_cart = array(
                'product_id' => $product_id
            );
            $product_name = $request->product_name;
            $total_qty = $request->qty;

            $product_fixed = array("data" => $data_cart, "price" => $total_amount, 'qty' => $total_qty, 'product_name' => $product_name, 'type' => 'fixed');
            \session()->push('orders', $product_fixed);
            return redirect('/')->with('success_cart', 'open');

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function editMeal($id)
    {
        if (Session::get('orders')) {
            $data_cart = Session::get('orders');
            $data = $data_cart[$id];
            $name = $data['product_name'];
            $serial_number = $data['serial_number'];
            $meals = Product::where('title', $name)->first();
            return view('index.menu', compact('meals', 'id', 'serial_number'));
        } else {
            return redirect()->back();
        }
    }

    public function mealRemoved($id)
    {

        $serial_number = '';
        if (Session::has('orders')) {
            $orders = Session::get('orders');
            Session::forget('orders');
            foreach ($orders as $key => $value) {
                if ($key != $id) {
                    \session()->push('orders', $value);
                } else {
                    if (array_key_exists('serial_number', $value)) {
                        $serial_number = $value['serial_number'];
                    }

                }
            }
        }
        return $serial_number;
    }

    public function RemovedItemCart($id)
    {

        if (Session::has('orders')) {
            $orders = Session::get('orders');
            Session::forget('orders');
            foreach ($orders as $key => $value) {
                if ($key != $id) {
                    \session()->push('orders', $value);
                }
            }
        }
        return redirect()->back()->with('success_cart', 'open');

    }

    public function editCustomized(Request $request)
    {
        $this->validate($request, [

            'qty' => 'required|numeric|min:0|not_in:0'
        ]);

        try {
            $id_session = $request->id;

            $orders = Session::get('orders');
            $product = Product::where('title', $orders[$id_session]['product_name'])->first();
            $extraPrice = $orders[$id_session]['extraPrice'];
            $qty = $request->qty;
            $total_amount = ($product->price + $extraPrice) * $qty;
            $orders[$id_session]['qty'] = $qty;
            $orders[$id_session]['price'] = $total_amount;
            Session::forget('orders');
            foreach ($orders as $key => $value) {
                \session()->push('orders', $value);
            }
            return redirect('/')->with('success_cart', 'open');

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function fixedProduct(Request $request)
    {
        $this->validate($request, [
            'qty' => 'required|numeric|min:0|not_in:0'
        ]);
        try {

            $product_id = $request->product_id;
            $data_cart = array(
                'product_id' => $product_id
            );
            $product_name = $request->product_name;
            $total_qty = $request->qty;
            $total_amount = $this->getTotalPrices($product_id, $total_qty);  // $request->total_price;
            $meal = array("data" => $data_cart, "price" => $total_amount, 'qty' => $total_qty, 'product_name' => $product_name, 'type' => 'fixed');
            \session()->push('orders', $meal);
            return redirect('/')->with('success_cart', 'open');
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function CustomizedProductAdd(Request $request)
    {
        $this->validate($request, [
//            'ingredient'=>'required',
            'qty' => 'required|numeric|min:0|not_in:0',
            'note' => 'nullable|string|max:200|min:1',
            'name' => 'string|nullable|max:100|min:1',
        ]);
        if (Product::where('id', $request->product_id)->where('status', 1)->exists()) {
            toastr()->error('Sorry! This Product is Temporarily Out of Stock');
            return redirect()->back();
        }

        $listOfIngredients = array();
        if ($request->ingredient) {
            $listOfIngredients = $request->ingredient;
        }
        $ingredientsData = Ingredient::whereIn('name', $listOfIngredients)->get();
        $ingredients = Ingredient::select('id')->whereIn('name', $listOfIngredients)->get()->toArray();

        $product = CustomizeProduct::select('main_course_id')->where('product_id', $request->product_id)->get()->toArray();

        $main_course_ids = MainCourseList::select('ingredient_id')->whereIn('main_course_id', $product)
            ->whereIn('ingredient_id', $ingredients)->get()->toArray();


        $ingredient_exclude = Ingredient::select('name', 'short_code')->whereIn('id', $main_course_ids)->get();
        $list_of_exclude = Null;
        $list_code = Null;
        foreach ($ingredient_exclude as $value) {
            $list_of_exclude[] = $value->name;
        }
        foreach ($ingredient_exclude as $value) {
            $list_code[] = $value->short_code != null ? $value->short_code : $value->name;
        }
        $list_of_include = null;
        $extraPrice = 0;
        foreach ($ingredientsData as $value) {
            $extraPrice += $value->price;
        }
        try {

            $total_item = '';
            $product_id = $request->product_id;
            $data_cart = $listOfIngredients;
            $product_name = $request->product_name;
            $total_qty = $request->qty;
            $total_amount = $this->getTotalPrices($product_id, $total_qty, $extraPrice); //$request->total_price;

            $note = $request->note ? $request->note : '';
            $name = $request->name ? $request->name : '';

            $meal = array("data" => $data_cart, "price" => $total_amount, 'extraPrice' => $extraPrice, 'qty' => $total_qty, 'product_name' => $product_name,
                'type' => 'customized', 'product_id' => $product_id, 'note' => $note, 'name' => $name, 'exclude_item' => $list_of_exclude, 'short_code' => $list_code);
            \session()->push('orders', $meal);
            return redirect('/')->with('success_cart', 'open');
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getTotalPrices($product_id, $qty, $extraPrice = 0)
    {
        $product = Product::find($product_id);
        $price = $product->price + $extraPrice;
        $total = $price * $qty;
        return $total;
    }
}
