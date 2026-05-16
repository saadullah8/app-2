<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [

            'first_name' => 'required|max:50|min:3',
            'last_name' => 'required|max:50|min:3',
            'phone' => 'required|string',
            'address' => 'string',
            'email' => 'required|email|max:255|unique:users',
            'can_sms' => 'boolean|',
            'can_marketing' => 'boolean',
            'term_condition' => 'required|boolean',
            'password' => ['required', 'string', 'min:6', 'confirmed'],


        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     */
    protected function create(array $data)
    {
        $store_array = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'can_sms' =>array_key_exists('can_sms',$data)? true:false,
            'can_marketing' =>array_key_exists('can_marketing',$data)? true:false,
            'password' => bcrypt($data['password']),
            'role_id' => $data['role']=2,
        ];
        return User::create($store_array);
    }


}
