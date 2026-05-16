<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Type;
use Illuminate\Http\Request;

class  CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('CheckAccess');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories=Type::all();
        return view('type.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types=Category::all();
        return view('type.create',compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attri= $this->validate($request,[
            'name'=>'required|max:50|min:3|unique:types',
            'category_id'=>'required',
        ]);

        try{
            Type::create($attri);
            return redirect('category')->with('success','Type Created Successfully!');
        }catch (\Exception $e){
            return redirect('category')->with('error',$e->getMessage().'!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $cat= Type::find($id);
        $types= Category::all();

        return view('type.edit',compact('types','cat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $attri=$this->validate($request,[
            'name'=>'required|max:50|min:3|'
        ]);
        try{
            $id = decrypt($id);
            Type::find($id)->update($attri);
            return redirect('category')->with('success','Type Updated Successfully');
        }catch (\Exception $e){
            return redirect('category')->with('error',$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try{
            $id = decrypt($request->id);
            Type::find($id)->delete();
            return redirect('category')->with('success','Type Deleted Successfully');
        }catch (\Exception $e){
            return redirect('category')->with('error',$e->getMessage().'!');
        }
    }
}
