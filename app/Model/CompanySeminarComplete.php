<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\CompanySeminar;
use App\Model\CompanySeminarEnroll;
use App\Model\CompanyDepartment;
use App\User;

class CompanySeminarComplete extends Model
{
    protected $guarded = [];
    protected $dates = ['completed_at'];
    public $timestamps = false;

    public function seminar()
    {
        return $this->belongsTo(CompanySeminar::class,'completed_seminar_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function companyEmployee($user_id){
        return CompanyEmployee::where('user_id',$user_id)->first();
    }

    public function companyEmployeeDepartment($user_id){
        $CompanyEmployee = CompanyEmployee::where('user_id',$user_id)->first();
        return CompanyDepartment::where('id',$CompanyEmployee->department_id)->first();
    }
}