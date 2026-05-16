<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TypeController extends Controller
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
        $categoryName=Category::all();
        return view('category.index',compact('categoryName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('category.create');
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
            'name'=>'required|max:50|min:3|unique:categories',
        ]);
        try{
            Category::create($attri);
            return redirect('type')->with('success','Category Created Successfully!');
        }catch (\Exception $e){
            return redirect('type')->with('error',$e->getMessage().'!');
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
        $category= Category::find($id);
        return view('type.edit',compact('category'));
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
            Category::find($id)->update($attri);
            return redirect('type')->with('success','Category Updated Successfully');
        }catch (\Exception $e){
            return redirect('type')->with('error',$e->getMessage());
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
            Category::find($id)->delete();
            return redirect('type')->with('success','Category Deleted Successfully');
        }catch (\Exception $e){
            return redirect('type')->with('error',$e->getMessage().'!');
        }
    }
}
