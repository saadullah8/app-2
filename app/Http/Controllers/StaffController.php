<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Http\Request;
use PHPUnit\Framework\Exception;

class StaffController extends Controller
{
    public  function  __construct(){
        $this->middleware('auth');
        $this->middleware('CheckAccess');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('staff_member.index');
    }
//    public function index(User)
//    {
//        $members=User::select('users.*')->where('role_id','!=',1)->get();
//        return view('staff_member.members',compact('members'));
//    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles=Role::where('role','!=',"Super admin")->get();
        return view('staff_member.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules=[
            'email' => 'required|email|max:255|unique:users',
            'first_name' => 'required|max:50|min:3',
            'last_name' => 'required|max:50|min:3',
            'phone' => 'required|string',
            'role_id' => 'required|',
            'can_sms' => 'boolean|',
            'can_marketing' => 'boolean',
            'password' => 'required|min:6|confirmed:password_confirmation',
        ];
        $data= $this->validate($request,$rules);
        try {
            $data['password']=bcrypt($request->password);
            DB::beginTransaction();
            $add_member=new User();
            $add_member->first_name = $request->first_name;
            $add_member->last_name =$request->last_name;
            $add_member->email = $request->email;
            $add_member->phone = $request->phone;
            $add_member->password = bcrypt($request->password);
            $add_member->role_id = $request->role_id;
            $add_member->can_sms = $request->can_sms? 1:0;
            $add_member->can_marketing = $request->can_marketing? 1:0;
            $add_member->save();
            DB::commit();
            return redirect('staff')->with('success','User Created Successfully');

        }catch (\Exception $e){
            DB::rollback();
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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id=decrypt($id);
        $roles=Role::where('role','!=',"Super admin")->get();
        $member=User::find($id);
        return view('staff_member.edit',compact('member','roles'));
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
        $rules=[
            'email' => 'required|email|max:255|'.Rule::unique('users')->ignore($id),
            'first_name' => 'required|max:50|min:3',
            'last_name' => 'required|max:50|min:3',
            'role_id' => 'required|',
            'phone' => 'required|string',
            'can_sms' => 'boolean|',
            'can_marketing' => 'boolean',
        ];
        if($request->input('password')!=null){
            $rules['password']='min:6|confirmed:password_confirmation';
        }
        $data= $this->validate($request,$rules);
        try {
            $data['can_sms'] = $request->can_sms? 1:0;
            $data['can_marketing'] = $request->can_marketing? 1:0;
            DB::beginTransaction();
            if($request->input('password')!=null){
                $data['password']= bcrypt($request->password);
            }
            User::find($id)->update($data);
            DB::commit();
            return redirect('staff')->with('success','User Successfully Updated');
        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
    }


    public function destroy($id)
    {
        $id=decrypt($id);
        try{
            User::find($id)->delete();
            return response()->json(['status' => true, 'message' => 'User has been deleted Successfully!']);
        }catch (Exception $e){
            return response()->json(['status' => false,'message' => $e->getMessage()]);
        }
    }
}
