<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainCourse extends Model
{
    protected $fillable = [

        'name','detail','status','sorting'
    ];
    public function MainCourseList(){
        return $this->hasMany('App\Models\MainCourseList','main_course_id')->orderBy('sorting','asc');
    }
}
