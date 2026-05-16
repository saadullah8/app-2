<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreOpening extends Model
{
    protected $fillable = [

        'opening_time' ,'closing_time' ,'status'
    ];
}
