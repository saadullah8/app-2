<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\MainCourse;
use App\Models\MainCourseList;
use Illuminate\Http\Request;

class MainCourseListController extends Controller
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
        $courses=MainCourse::get();
        return view('main_course_list.index',compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('main-course')->with('success','Please Main Course Select');
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
            'ingredient'=>'required',
            'main_course_id'=>'required'
        ]);

        try{
            $id=decrypt($request->main_course_id);
            $ingredient_list=$request->ingredient;
            MainCourseList::where('main_course_id',$id)->delete();
            foreach($ingredient_list as $value){
                $value_id=decrypt($value);

                    $add =new MainCourseList();
                    $add->main_course_id=$id;
                    $add->ingredient_id=$value_id;
                    $add->save();

            }
            foreach ($request->sorting as $key=> $value){
                if($value!=null){
                  $test=  MainCourseList::where('main_course_id',$id)->where('ingredient_id',$key)->update(['sorting'=>$value]);

                }
            }
            return redirect('main-course-list')->with('success','Main course list Added!');
        }
        catch(\Exception $e){
            return redirect()->back()->with('error',$e->getMessage().'!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MainCourseList  $mainCourse
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        echo "show";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MainCourseList  $mainCourse
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MainCourseList  $mainCourse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MainCourseList  $mainCourse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try{
            $id = decrypt($request->id);
            MainCourseList::where('main_course_id',$id)->delete();
            return redirect('main-course-list')->with('success','Main course lists has been Deleted Successfully!');
        }
        catch(\Exception $e){
            return redirect('main-course-list')->with('error',$e->getMessage().'!');
        }

    }
    public function addList($id){
        try{
            $id = decrypt($id);
            $course_name=MainCourse::find($id);
            $ingredients=Ingredient::all();
            return view('main_course_list.create',compact('id','course_name','ingredients'));
        }
        catch(\Exception $e){
            return redirect()->back()->with('error',$e->getMessage().'!');
        }
    }
}
