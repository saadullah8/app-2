<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'first_name' ,'last_name' ,'email' , 'password' ,'role_id','phone' ,
        'address','image','can_sms','can_marketing','stripeCustomerId'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role(){

        return $this->belongsTo('App\Models\Role','role_id');
    }

    public function cards(){
        return $this->hasMany(Card::class, 'userId');
    }
}
