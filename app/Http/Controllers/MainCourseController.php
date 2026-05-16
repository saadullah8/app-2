<?php

namespace App\Http\Controllers;

use App\Models\CustomizeProduct;
use App\Models\MainCourse;
use Illuminate\Http\Request;

class MainCourseController extends Controller
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
        $courses=MainCourse::all();
        return view('main_course.index',compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('main_course.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|max:50|min:3|unique:main_courses',
            'sorting'=>'required|integer',
        ]);
        try{

            $attrib=$request->only(['name','sorting']);
            MainCourse::create($attrib);
            return redirect('main-course')->with('success','Main course Added!');
        }
        catch(\Exception $e){
            return redirect()->back()->with('error',$e->getMessage().'!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MainCourse  $mainCourse
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MainCourse  $mainCourse
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $course= MainCourse::find($id);
        return view('main_course.edit',compact('course'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MainCourse  $mainCourse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'name'=>'required|max:50|min:3',
            'sorting'=>'required|integer'
        ]);
        try{
            $id = decrypt($id);

            $attrib=$request->only(['name','sorting']);
            MainCourse::find($id)->update($attrib);
            return redirect('main-course')->with('success','Main course Updated!');
        }
        catch(\Exception $e){
            return redirect()->back()->with('error',$e->getMessage().'!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MainCourse  $mainCourse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try{
            $id = decrypt($request->id);
            $Delete = MainCourse::find($id);
            CustomizeProduct::where('main_course_id',$id)->delete();
            $Delete->delete();
            return redirect('main-course')->with('success','Main course has been Deleted Successfully!');
        }
        catch(\Exception $e){
            return redirect('main-course')->with('error',$e->getMessage().'!');
        }
    }
}
