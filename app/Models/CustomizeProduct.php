<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomizeProduct extends Model
{
    protected $fillable = [
        'product_id','main_course_id','half','dressing','skip','extra_charge','min_value','max_value','extra_price','min_extra_limit','max_extra_limit'
    ];

    public function getMainCourse(){
        return $this->belongsTo('App\Models\MainCourse','main_course_id');
    }
    public function MainCourseList(){
        return $this->hasMany('App\Models\MainCourseList','main_course_id');
    }
}
