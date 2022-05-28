<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\CompanySeminarComplete;
use App\Model\CompanySeminar;
use App\Model\CompanyAttempt;
use App\Model\CompanySeminarEnroll;
use App\Complete;
use App\Coupon;

class CompanyEmployee extends Model
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

    public function employee_score()
    {
        return $this->hasMany(CompanySeminarEnroll::class,'user_id','user_id');
    }

    public function employe_score_calculated($employee_id,$from_date=null,$to_date=null){
        $from = date($from_date);
        $to = date($to_date);
        $completed_seminars = CompanySeminarComplete::where('user_id',$employee_id)
                                                ->whereNotNull('completed_seminar_id')
                                                ->whereRaw('date(completed_at) >= ?',[$from])
                                                ->whereRaw('date(completed_at) <= ?',[$to])
                                                ->get();
        $score_calculate = 0;
        foreach ($completed_seminars as $completed_seminar)
        {
            $seminar_score = CompanySeminar::select('score')
                            ->where('id',$completed_seminar->completed_seminar_id)
                            ->first();
            $score_calculate = $score_calculate + $seminar_score->score;
        }
        return $score_calculate;
    }

    public function employee_course_score_calculate($employee_id,$from_date=null,$to_date=null,$company_id=null)
    {
        $from   = date($from_date);
        $to     = date($to_date);
        $completed_courses = Complete::where('user_id',$employee_id)
                                    ->whereNotNull('completed_course_id')
                                    ->whereRaw('date(completed_at) >= ?',[$from])
                                    ->whereRaw('date(completed_at) <= ?',[$to])
                                    ->get();
        $course_calculate = 0;
        foreach ($completed_courses as $completed_course)
        {
            $course_score = Coupon::select('company_score')
                            ->where('course_id',$completed_course->completed_course_id)
                            ->where('company_id',$company_id)
                            ->first();
            if (!empty($course_score)) {
                $course_calculate = $course_calculate + $course_score->company_score;
            }
        }
        return $course_calculate;
    }

    public function employe_completed_seminar($employee_id,$from_date=null,$to_date=null){
        $from = date($from_date);
        $to = date($to_date);
        $completed_seminars = CompanySeminarComplete::where('user_id',$employee_id)
                            ->whereNotNull('completed_seminar_id')
                            ->whereRaw('date(completed_at) >= ?',[$from])
                            ->whereRaw('date(completed_at) <= ?',[$to])
                            ->count();
        return $completed_seminars;
    }

    public function employe_completed_course($employee_id,$from_date=null,$to_date=null,$company_id=null){
        $from = date($from_date);
        $to = date($to_date);
        $completed_seminars = Complete::where('user_id',$employee_id)
                            ->whereNotNull('completed_course_id')
                            ->whereRaw('date(completed_at) >= ?',[$from])
                            ->whereRaw('date(completed_at) <= ?',[$to])
                            ->get();
        $course_count = 0;
        foreach ($completed_seminars as $completed_seminar)
        {
            $course_score   = Coupon::select('company_score')
                            ->where('course_id',$completed_seminar->completed_course_id)
                            ->where('company_id',$company_id)
                            ->first();
            if (!empty($course_score)) {
                $course_count = $course_count + 1;
            }
        }
        return $course_count;
    }

    public function employe_pending_seminar($employee_id,$from_date=null,$to_date=null){
        $from = date($from_date);
        $to = date($to_date);
        $completed_seminars = CompanySeminarComplete::where('user_id',$employee_id)
                                                ->whereNotNull('completed_seminar_id')
                                                ->whereRaw('date(completed_at) >= ?',[$from])
                                                ->whereRaw('date(completed_at) <= ?',[$to])
                                                ->count();

        $attempted_seminars = CompanySeminarEnroll::where('user_id',$employee_id)
                                                ->whereRaw('date(enrolled_at) >= ?',[$from])
                                                ->whereRaw('date(enrolled_at) <= ?',[$to])
                                                ->count();
        return $attempted_seminars - $completed_seminars;
    }
}