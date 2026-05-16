<?php

namespace App\Http\Controllers;

use App\Models\StoreOpening;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StoreOpeningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('CheckAccess');
    }
    public function index()
    {

//        $OpeningTime = '11:30:00 AM';
//        $closingTime = '08:00:00 PM';
//
//        //$currentTime=strtotime(Carbon::now());
//        $data['opening_time']= strtotime($OpeningTime);
//        $data['closing_time']=strtotime($closingTime);
//
//        $data['status']=true;
//        StoreOpening::create($data);

        $store=StoreOpening::find(1);
        return view('storeTiming.index',compact('store'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StoreOpening  $storeOpening
     * @return \Illuminate\Http\Response
     */
    public function show(StoreOpening $storeOpening)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StoreOpening  $storeOpening
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $store=StoreOpening::find($id);
        return view('storeTiming.edit',compact('store'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StoreOpening  $storeOpening
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $id = decrypt($id);
        $attri=$this->validate($request,[
            'opening_time'=>'required',
            'closing_time'=>'required',
        ]);

        try{
            $OpeningTime = $attri['opening_time'];
            $closingTime = $attri['closing_time'];

            $data['opening_time']= strtotime($OpeningTime);
            $data['closing_time']=strtotime($closingTime);


           if($request->status=="true"){
               $data['status']=true;
           }else{
               $data['status']=false;
           }
            StoreOpening::find($id)->update($data);
            return redirect('storeTiming')->with('success','Time setting Updated Successfully');
        }catch (\Exception $e){
            return redirect('storeTiming')->with('error',$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StoreOpening  $storeOpening
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoreOpening $storeOpening)
    {
        //
    }
}
