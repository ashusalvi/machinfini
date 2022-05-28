<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CompanyInstructor extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(\App\User::class,'user_id');
    }

    public function department()
    {
        return $this->belongsTo(CompanyDepartment::class,'department_id');
    }

    public function getDepartments($id)
    {   
        $company_user_details = CompanyInstructor::where('id',$id)->first();
        $departments_array = explode(",",$company_user_details->department_id);
        $departments = '';
        foreach ($departments_array as $key =>  $department)
        {   
            $companyDepartment = CompanyDepartment::where('id',$department)->first();
            $departments .= $companyDepartment->name;
            if (count($departments_array) != $key+1) {
                $departments .=', ';
            }
            
        }
        return $departments;
    }


}