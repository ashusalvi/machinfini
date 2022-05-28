<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Course;
use App\User;

class CourseRequest extends Model
{
    protected $table = 'company_course_request';
    protected $guarded = [];
    public $timestamps = false;

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    
    public function department()
    {
        return $this->belongsTo(CompanyDepartment::class, 'department_id');
    }
    
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}