<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainCourseList extends Model
{
    public function MainCourse(){
       return $this->belongsTo('App\Models\MainCourse');
    }
    public function Ingredients(){
       return $this->belongsTo('App\Models\Ingredient','ingredient_id');
    }
}
