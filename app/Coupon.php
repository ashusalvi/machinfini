<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Model\CompanyEmployee;
use App\Model\Company;
use App\Model\CompanyDepartment;

class Coupon extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'coupon';


    public function course(){
        return $this->belongsTo(Course::class, 'course_id');
    }
    
    public function company(){
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function companyEmployee($user_id){
        return CompanyEmployee::where('user_id',$user_id)->first();
    }
    
    public function companyDepartment(){
        return $this->belongsTo(CompanyDepartment::class, 'department_id');
    }

}