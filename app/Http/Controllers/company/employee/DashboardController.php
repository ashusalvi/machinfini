<?php

namespace App\Http\Controllers\company\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Redirect;
use Response;
use App\Model\CompanySeminar;
use App\Model\CompanyEmployee;
use App\Model\CompanyInstructor;
use App\Model\CompanySeminarEnroll;
use App\Model\CompanySeminarComplete;
use App\Model\CourseRequest;
use App\Coupon;
use App\Enroll;
use App\Complete;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company_id     = Auth::user()->company_id;
        $user_id        = Auth::user()->id;

        $employee_details = CompanyEmployee::where('user_id',$user_id)->first();

        $instructors    = CompanyInstructor::where('department_id',$employee_details->department_id)->where('company_id',$company_id)->get();

        $instructor_id = [];
        foreach($instructors as $instructor)
        {
            $instructor_id[] = $instructor->user_id;
        }

        // $companySeminars = CompanySeminar::whereIn('user_id',$instructor_id)->where('status',1)->get();
        $companySeminars = CompanySeminar::where('department_id',$employee_details->department_id)->where('status',1)->get();
        
        $completed_seminar = CompanySeminarComplete::where('user_id',$user_id)->whereNotNull('completed_seminar_id')->get();

        $score = 0;
        foreach($completed_seminar as $seminar){
            $score +=  $seminar->seminar->score;
        }

        // enrolled seminar
        $enroller_seminar = CompanySeminarEnroll::where('user_id',$user_id)->count();

        // course section start
        $courses = Coupon::with('course')->whereNotNull('course_id')->where('company_id',$company_id)->where('department_id',$employee_details->department_id)->get();
        $course_scoure = 0;
        $enroller_course_count = 0;
        $completed_course_count = 0;
        foreach ($courses as $key => $course) {
            if (!empty($course->course))
            {
                $enroll = Enroll::where('course_id',$course->course_id)->where('user_id',$user_id)->count();
                if ($enroll > 0)
                {
                    $enroller_course_count = $enroller_course_count + 1;
                }

                $completed_course = Complete::where('completed_course_id',$course->course_id) 
                            ->where('user_id',$user_id) 
                            ->whereNotNull('completed_course_id')
                            ->count();
                if ($completed_course > 0)
                {
                    $completed_course_count = $completed_course_count + 1;
                    $course_scoure = $course_scoure + $course->company_score;
                }
            }
        }
        // course section end`

        $completed_seminar_count = CompanySeminarComplete::where('user_id',$user_id)->whereNotNull('completed_seminar_id')->count();

        return view('company.employee.dashboard',compact('companySeminars','enroller_seminar','score','completed_seminar_count', 'courses','enroller_course_count','course_scoure','completed_course_count'));
    }

    public function AllCompletedSeminar($from_date = null, $to_date = null)
    {
        if ($from_date == null ) 
        {
            $from_date = date("Y-m-d", strtotime("-1 months"));
        }

        if ($to_date == null)
        {
            $to_date = date("Y-m-d");
        }

        $company_id = Auth::user()->company_id;
        $employee_id = Auth::user()->id;

        $company_seminars = CompanySeminar::where('company_id', $company_id)
                                        ->get();
        $seminar_ids = [];
        foreach ($company_seminars as $key => $company_seminar)
        {   
            $seminar_ids[]= $company_seminar->id;
        }

        $completed_seminars = CompanySeminarComplete::whereIn('completed_seminar_id',$seminar_ids)
                                    ->whereNotNull('completed_seminar_id')
                                    ->where('user_id',$employee_id)
                                    ->whereRaw('date(completed_at) >= ?',[$from_date])
                                    ->whereRaw('date(completed_at) <= ?',[$to_date])
                                    ->get();
        return view('company.employee.seminar.mis.completed_seminar',compact('completed_seminars','company_seminars','to_date','from_date'));
    }

    public function AllPendingSeminar($from_date = null, $to_date = null)
    {
        if ($from_date == null ) 
        {
            $from_date = date("Y-m-d", strtotime("-1 months"));
        }

        if ($to_date == null)
        {
            $to_date = date("Y-m-d");
        }

        $response_array = array();

        $company_id = Auth::user()->company_id;
        $employee_id = Auth::user()->id;

        $employee = CompanyEmployee::where('user_id',$employee_id)->first();

        // all seminar in company
        $company_seminars = CompanySeminar::where('company_id', $company_id)
                                ->whereRaw('date(created_at) >= ?',[$from_date])
                                ->whereRaw('date(created_at) <= ?',[$to_date])
                                ->get();
        // all employee of company 
        $employees = CompanyEmployee::where('user_id',$employee_id)
                                ->get();

        $count = 0;
        foreach ($employees as $key => $employee) {
            foreach ($company_seminars as $key => $company_seminar) {
                if ($employee->department_id == $company_seminar->department_id) {
                    $completed_seminars = CompanySeminarComplete::where('completed_seminar_id',$company_seminar->id) 
                        ->where('user_id',$employee->user_id) 
                        ->whereNotNull('completed_seminar_id')
                        ->count();
                    if ($completed_seminars == 0) {
                        
                        $response_array[$count]['employees'] = $employee;
                        $response_array[$count]['seminar'] = $company_seminar;
                        $count ++;
                    }
                }
            }
        }
                                
        return view('company.employee.seminar.mis.pending_seminar',compact('response_array','to_date','from_date'));
    }

    public function AllPendingCourse()
    {
        $response_array = array();
        $company_id = Auth::user()->company_id;
        $employee_id = Auth::user()->id;

        $employees = CompanyEmployee::where('user_id',$employee_id)->get();
        $employee_details = CompanyEmployee::where('user_id',$employee_id)->first();
        // get company coupon
        $coupons_courses = Coupon::with('course')->where('company_id',$company_id)->where('department_id',$employee_details->department_id)->get();
        // all employee of company 
        
        $count = 0;
        foreach ($employees as $key => $employee)
        {
            foreach ($coupons_courses as $key => $coupons_course)
            {
                if (!empty($coupons_course->course))
                {
                    $completed_course = Complete::where('completed_course_id',$coupons_course->course_id) 
                        ->where('user_id',$employee->user_id) 
                        ->whereNotNull('completed_course_id')
                        ->count();
                    if ($completed_course == 0) 
                    {
                        $response_array[$count]['employees']    = $employee;
                        $response_array[$count]['seminar']      = $coupons_course;
                        $count ++;
                    }
                }
            }
        }                         
        return view('company.employee.seminar.mis.pending_course',compact('response_array'));
    }

    public function AllCompletedCourse($from_date = null, $to_date = null)
    {   
        if ($from_date == null ) 
        {
            $from_date = date("Y-m-d", strtotime("-1 months"));
        }

        if ($to_date == null)
        {
            $to_date = date("Y-m-d");
        }

        $company_id     = Auth::user()->company_id;
        $employee_id    = Auth::user()->id;

        $employee_details = CompanyEmployee::where('user_id',$employee_id)->first();
        // get company coupon
        $coupons_courses = Coupon::with('course')->where('company_id',$company_id)->where('department_id',$employee_details->department_id)->get();
        $seminar_ids = [];
        foreach ($coupons_courses as $key => $coupons_course)
        {   
            if (!empty($coupons_course->course))
            {
                $seminar_ids[]= $coupons_course->course->id;
            }
        }

        $completed_courses = Complete::whereIn('completed_course_id',$seminar_ids)
                            ->where('user_id',$employee_id)
                            ->whereNotNull('completed_course_id')
                            ->whereRaw('date(completed_at) >= ?',[$from_date])
                            ->whereRaw('date(completed_at) <= ?',[$to_date])
                            ->get();
                            
        return view('company.employee.seminar.mis.completed_course',compact('completed_courses','coupons_courses','to_date','from_date'));
    }
}