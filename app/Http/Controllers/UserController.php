<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use File;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->except('logout');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function logout(){
        Auth::logout();
        return redirect('login');
    }
    public function changePassword(){

        return view('change_password/change_password');
    }
    public function updatePassword(Request $request){
        $rules=[
            'password'=>'required|min:6',
            'confirm_password'=>'same:password'
        ];
         $this->validate($request,$rules);
         $user = User::find(Auth::id());
        try {

            if(Hash::check($request->oldpassword, $user->password))
            {
                $user->update(array("password" => bcrypt($request->password)));
                return redirect()->back()->with('success', 'Password Updated');
            }
            else{
                return redirect()->back()->with('error', 'Old Password miss match!');
            }

        }catch (\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function profileUpdate(Request $request){
        $rules=[
            'first_name' => 'required|max:50|min:3',
            'last_name' => 'required|max:50|min:3',
            'image'=>'image|mimes:jpeg,png,jpg|max:1024',
        ];
        $this->validate($request,$rules);
        $user = User::find(Auth::id());
        $attri=$request->only(['first_name','last_name','address','phone']);
        $attri['can_sms']=$request->can_sms? 1:0;
        $attri['can_marketing']=$request->can_marketing? 1:0;
        try {
            if ($request->hasFile('image')) {
                if(Auth::user()->image != NULL)
                {
                    $this->fileDeleted(Auth::user()->image);
                }
                $attri['image']=$this->fileUpload($request->file('image'));
            }
            User::find(Auth::id())->update($attri);
            return redirect()->back()->with('success', 'Profile Updated!');
        }catch (\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    private function fileUpload($photo){
        $imagename = uniqid().'.'.$photo->getClientOriginalExtension();
        $destinationPath =public_path('/site_images/profile_images/');
        $photo->move($destinationPath, $imagename);
        return $imagename;
    }
    private function fileDeleted($photo){
        $image_path = public_path('/site_images/profile_images/'.$photo);
        if(File::exists($image_path)) {
            File::delete($image_path);
        }
    }
}
