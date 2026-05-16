<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = [

        'name','category_id'
    ];
    public function Category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
    public function getProduct(){
        return $this->hasMany('App\Models\Product');
    }
}
