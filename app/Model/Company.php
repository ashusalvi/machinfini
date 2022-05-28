<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\CompanyInstructor;
use App\Model\CompanyEmployee;
use App\Model\CompanySeminar;
use App\Coupon;

class Company extends Model
{
    public function user(){
        return $this->belongsTo(User::class, 'admin_id','id');
    }

    public function departmentHead($company){
        return CompanyInstructor::where('company_id',$company)->where('status',1)->get();
    }
    public function employeeHead($company){
        return CompanyEmployee::where('company_id',$company)->where('status',1)->get();
    }
    public function coupon($company){
        return Coupon::where('company_id',$company)->get();
    }
    public function CompanySeminar($company){
        return CompanySeminar::where('company_id',$company)->get();
    }
}