<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Request;

class Product extends Model
{
    protected $fillable = [

        'title' ,'image' ,'detail','status','price','category_id','type_id'
    ];

    public function getType()
    {
        return $this->belongsTo('App\Models\Type','type_id');
    }
    public function category()
    {
        return $this->belongsTo('App\Models\Category','category_id');
    }
    public function CustomizedProduct(){
        return $this->hasMany('App\Models\CustomizeProduct','product_id');
    }

}
