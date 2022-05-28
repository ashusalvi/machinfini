<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Model\Company;
use App\Model\CompanyDepartment;

class CompanyCoupon extends Model
{
    
    protected $table = 'company_coupon';

    public function company(){
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function companyDepartment(){
        return $this->belongsTo(CompanyDepartment::class, 'department');
    }

}