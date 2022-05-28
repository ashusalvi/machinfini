<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Model\CompanyEmployee;
use App\Coupon;

class Complete extends Model
{
    protected $guarded = [];
    protected $dates = ['completed_at'];
    public $timestamps = false;

    public function course(){
        return $this->belongsTo(Course::class,'completed_course_id');
    }

    public function companyEmployee($user_id){
        return CompanyEmployee::where('user_id',$user_id)->first();
    }

    public function couponEmployee($company_id, $course_id){
        return Coupon::where('company_id',$company_id)->where('course_id',$course_id)->first();
    }
}