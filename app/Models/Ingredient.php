<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [

        'name' ,'image' ,'price','detail','status','short_code'
    ];
}
