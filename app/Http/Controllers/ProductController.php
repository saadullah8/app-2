<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CustomizeProduct;
use App\Models\Ingredient;
use App\Models\MainCourse;
use App\Models\Product;
use App\Models\Type;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('CheckAccess');
        ini_set('upload_max_filesize', '60M');
        ini_set('max_execution_time', '999');
        ini_set('memory_limit', '128M');
        ini_set('post_max_size', '60M');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type=null)
    {
        if($type==null){
            $products = Product::all();
        }else{
            $products = Product::where('category_id',$type)->get();
        }
        $main_courses=MainCourse::all();
        return view('product.index',compact('products','main_courses','type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $types=$cat_id=$request->type;
       // $type=decrypt($request->type);
        $types=Type::where('category_id',$cat_id)->get();
        if($cat_id==2){
            $main_courses=MainCourse::all();
            return view('product.create_customized',compact('cat_id','main_courses','types'));
        }else{
            return view('product.create',compact('cat_id','types'));
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation_rules = [
        'title'=>'required|max:100|min:2|unique:products',
        'price'=>'required|numeric',
        'image'=>'required|image|mimes:jpeg,png,jpg|max:1024',
        ];
        $type=$request->category_id;
        if($type==2){
            $validation_rules['main_course']='required';
            $validation_rules['type']='required';
        }
        if($type==1){
            $validation_rules['type']='required';
        }
        $this->validate($request,$validation_rules);
        try{
            $attri=$request->only(['title','detail','price']);
            $attri['category_id']=$type;
            $attri['type_id']=$request->type;
            if($request->hasFile('image')){
                $attri['image']=$this->fileUpload($request->file('image'));
            }
            $product_created = Product::create($attri);
            if($type==2){
                foreach ($request->main_course as $key=>$value){
                    $customize_product['main_course_id']=$value;
                    $customize_product['product_id']=$product_created->id;
                    CustomizeProduct::create($customize_product);
                }
            }
            return redirect('product/category/'.$type)->with('success','Product Successfully Created');
        }catch (\Exception $e){
            return redirect()->back()->with('error',$e->getMessage());
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
         $id = decrypt($id);
         $product=Product::find($id);
         return view('product.view',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $id = decrypt($id);
            $product= Product::find($id);
            $main_courses=MainCourse::all();
           // $types=Type::all();
            $types=Type::where('category_id',$product->category_id)->get();
            return view('product.edit',compact('product','main_courses','types'));
        }catch (\Exception $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
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
        $id = decrypt($id);
        $edit=Product::find($id);
        $validation_rules = [
            'title'=>'required|max:100|min:2',

            'price'=>'required|numeric',
//            'type'=>'required',
            'image'=>'image|mimes:jpeg,png,jpg|max:1024',
        ];
        if($edit->category_id==2){
            $validation_rules['main_course']='required';
            $validation_rules['type']='required';
        }
        if($edit->category_id==1){
            $validation_rules['type']='required';
        }
        $this->validate($request, $validation_rules);
        try{

            $attri=$request->only(['title','detail','price']);
            if ($request->hasFile('image')) {
                if($edit->image != NULL)
                {
                    $this->fileDeleted($edit->image);
                }
                $attri['image']=$this->fileUpload($request->file('image'));
            }
            $attri['type_id']=$request->type;
            if($edit->category_id==2){
                $this->MainCourseAddCustomizedProduct($edit->id,$request->main_course);
            }
            $edit->update($attri);
            return redirect('product/category/'.$edit->category_id)->with('success','Product has been Updated');
        }catch (\Exception $e){

            return redirect()->back()->with('error',$e->getMessage());

        }

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$var)
    {
        try{
            $id = decrypt($request->id);
            $Delete = Product::find($id);
            $this->deletedCustomProduct($id);
            if($Delete->image != NULL)
            {
                $this->fileDeleted($Delete->image);
            }
            $Delete->delete();
            return redirect()->back()->with('success','Product has been Deleted Successfully!');
        }
        catch(\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage() . '!');
        }
    }
    private function fileUpload($photo){
        $imagename = uniqid().'.'.$photo->getClientOriginalExtension();
        $destinationPath =public_path('/site_images/product_images/');
        $photo->move($destinationPath, $imagename);
        return $imagename;
    }
    private function fileDeleted($photo){
        $image_path = public_path('/site_images/product_images/'.$photo);
        if(File::exists($image_path)) {
            File::delete($image_path);
        }
    }
    public function addProduct(Request $request){
        $validation_rules = [
            'category'=>'required',
        ];
        $this->validate($request, $validation_rules);
        try{
            return redirect('product/create?type='.$request->category);
        }catch (\Exception $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
    }
    private function deletedCustomProduct($p_id){
        CustomizeProduct::where('product_id',$p_id)->delete();
    }
    private function MainCourseAddCustomizedProduct($p_id,$mainC_id){
        CustomizeProduct::where('product_id',$p_id)->delete();
        foreach ($mainC_id as $key=>$value){
            $customize_product['main_course_id']=$value;
            $customize_product['product_id']=$p_id;
            CustomizeProduct::create($customize_product);
        }
    }
    public function addProductMainCourse(Request $request){
        $validation_rules = [
            'product_id'=>'required',
            'main_course_id'=>'required',
        ];
        $this->validate($request, $validation_rules);
        try{
            $attri=$request->only(['product_id','main_course_id','skip','dressing','half','extra_charge','extra_price']);
            $attri['min_value']=$request->min_value? $request->min_value:1;
            $attri['max_value']=$request->max_value? $request->max_value:100;
            $attri['min_extra_limit']=$request->min_extra_limit? $request->min_extra_limit:1;
            $attri['max_extra_limit']=$request->max_extra_limit? $request->max_extra_limit:100;
            CustomizeProduct::create($attri);
            return redirect()->back()->with('success','Main Course has been added Successfully!');
        }catch (\Exception $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
    }
    public function removeMainCourse(Request $request){
        try{
            $id = decrypt($request->id);
            CustomizeProduct::find($id)->delete();
            return redirect()->back()->with('success','Main Course has been Removed Successfully!');
        }
        catch(\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage() . '!');
        }
    }
    public function EditMainCourse(Request $request){

        $validation_rules = [
            'edit_id'=>'required',
            'main_course_id'=>'required',
        ];
        $this->validate($request, $validation_rules);
        try{
            $id = decrypt($request->edit_id);
                $attri=$request->only(['main_course_id','extra_price']);
                $attri['skip']=$request->skip? $request->skip:0;
                $attri['dressing']=$request->dressing? $request->dressing:0;
                $attri['half']=$request->half? $request->half:0;
                $attri['extra_charge']=$request->extra_charge? $request->extra_charge:0;
                $attri['min_value']=$request->min_value? $request->min_value:1;
                $attri['max_value']=$request->max_value? $request->max_value:100;
                $attri['min_extra_limit']=$request->min_extra_limit? $request->min_extra_limit:1;
                $attri['max_extra_limit']=$request->max_extra_limit? $request->max_extra_limit:100;
                CustomizeProduct::find($id)->update($attri);
                return redirect()->back()->with('success','Main Course has been updated Successfully!');
            }catch (\Exception $e){
                return redirect()->back()->with('error',$e->getMessage());
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
            Product::find($id)->update($array);
            return redirect('product')->with('success','Product has been '.$status.' Successfully!');
        }
        catch(\Exception $e){
            return redirect('product')->with('error',$e->getMessage().'!');
        }
    }
}
