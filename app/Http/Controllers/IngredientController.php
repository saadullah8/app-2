<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\MainCourseList;
use Illuminate\Http\Request;
use File;
use Illuminate\Validation\Rule;

class IngredientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('CheckAccess');
        ini_set('upload_max_filesize', '150M');
        ini_set('max_execution_time', '999');
        ini_set('memory_limit', '128M');
        ini_set('post_max_size', '60M');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ingredients=Ingredient::all();
        return view('ingredient.index',compact('ingredients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ingredient.create');
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
            'name'=>'required|max:50|min:3|unique:ingredients',
            'short_code'=>'nullable|max:12|min:2|unique:ingredients',
            'image'=>'image|mimes:jpeg,png,jpg|max:1024',
            'price'=>'nullable|numeric',
            'detail'=>'nullable|string',
        ]);
        try{

            $attrib=$request->only(['name','detail','short_code']);
            if( $request->hasFile('image')){
                $attrib['image']=$this->fileUpload($request->file('image'));
            }
            $attrib['price']=$request->price? $request->price:0;
            Ingredient::create($attrib);
            return redirect('ingredient')->with('success','Ingredient Added!');
        }
        catch(\Exception $e){
            return redirect()->back()->with('error',$e->getMessage().'!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ingredient  $ingredient
     * @return \Illuminate\Http\Response
     */
    public function show(Ingredient $ingredient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ingredient  $ingredient
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $ingredient= Ingredient::find($id);
        return view('ingredient.edit',compact('ingredient'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ingredient  $ingredient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = decrypt($id);
        $this->validate($request,[
            'name'=>'required|max:50|min:3|'.Rule::unique('ingredients')->ignore($id),
            'short_code'=>'nullable|max:12|min:2|'.Rule::unique('ingredients')->ignore($id),
            'image'=>'image|mimes:jpeg,png,jpg|max:1024',
            'price'=>'numeric',
            'detail'=>'nullable|string',
        ]);

        $edit= Ingredient::find($id);
        try{

            $attrib=$request->only(['name','detail','short_code']);
            if($request->hasFile('image')){

                if($edit->image != NULL)
                {
                    $this->fileDeleted($edit->image);
                }
                $attrib['image']=$this->fileUpload($request->file('image'));
            }
            $attrib['price']=$request->price;
            Ingredient::find($id)->update($attrib);
            return redirect('ingredient')->with('success','Ingredient Has been Updated!');
        }
        catch(\Exception $e){
            return redirect()->back()->with('error',$e->getMessage().'!');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ingredient  $ingredient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$var)
    {
       try{
        $id = decrypt($request->id);
        $Delete = Ingredient::find($id);
        MainCourseList::where('ingredient_id',$id)->delete();
           if($Delete->image != NULL)
           {
               $this->fileDeleted($Delete->image);
           }
        $Delete->delete();
           return redirect('ingredient')->with('success','Ingredient has been Deleted Successfully!');
       }
       catch(\Exception $e){
            return redirect('ingredient')->with('error',$e->getMessage().'!');
       }
    }
    private function fileUpload($photo){
        $imagename = uniqid().'.'.$photo->getClientOriginalExtension();
        $destinationPath =public_path('/site_images/ingredient_images/');
        $photo->move($destinationPath, $imagename);
        return $imagename;
    }
    private function fileDeleted($photo){
        $image_path = public_path('/site_images/ingredient_images/'.$photo);
        if(File::exists($image_path)) {
            File::delete($image_path);
        }
    }
    public function activeStatus(Request $request){
        try{
            $status=$request->status;
            if($status=='Active'){
                $array['status']=0;
            }else{
                $array['status']=1;
            }
             $id = decrypt($request->id);
             Ingredient::find($id)->update($array);
            return redirect('ingredient')->with('success','Ingredient has been '.$status.' Successfully!');
        }
        catch(\Exception $e){
            return redirect('ingredient')->with('error',$e->getMessage().'!');
        }
    }
}
