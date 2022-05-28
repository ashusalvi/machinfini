<?php

namespace App\Http\Controllers\company\instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\CompanyDepartment;
use App\Model\CompanyInstructor;
use App\Model\CompanyEmployee;
use App\Model\CompanySeminar;
use App\Model\CompanySeminarEnroll;
use App\Model\CompanySeminarComplete;
use App\Model\CourseRequest;
use App\User;
use App\Coupon;
use App\Course;
use App\Complete;
use Auth;
use Redirect;
use Response;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company_id         = Auth::user()->company_id;
        $instructor_id      = Auth::user()->id;
        $instructor         = CompanyInstructor::where('user_id',$instructor_id)->first();

        $company_seminars   = CompanySeminar::where('company_id', $company_id)
                            ->whereIn('department_id',explode(",",$instructor->department_id))
                            ->count();

        $draft_seminars   = CompanySeminar::where('company_id', $company_id)
                            // ->where('user_id',$instructor_id)
                            ->whereIn('department_id',explode(",",$instructor->department_id))
                            ->where('status',0)
                            ->count();
        $active_seminars   = CompanySeminar::where('company_id', $company_id)
                            // ->where('user_id',$instructor_id)
                            ->whereIn('department_id',explode(",",$instructor->department_id))
                            ->where('status',1)
                            ->count();

        $employees         = CompanyEmployee::where('company_id',$company_id)
                                ->whereIn('department_id',explode(",",$instructor->department_id))
                                ->count();
        
        $course_count       = CourseRequest::where('company_id',$company_id)
                                            ->whereIn('department_id',explode(",",$instructor->department_id))
                                            ->where('status',1)
                                            ->count();

        return view('company.instructor.dashboard',compact('company_seminars','draft_seminars','active_seminars','employees','course_count'));
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
        $instructor_id = Auth::user()->id;
        $instructor         = CompanyInstructor::where('user_id',$instructor_id)->first();

        $company_seminars = CompanySeminar::where('company_id', $company_id)
                                        // ->where('user_id',$instructor_id)
                                        ->whereIn('department_id',explode(",",$instructor->department_id))
                                        ->get();
        $seminar_ids = [];
        foreach ($company_seminars as $key => $company_seminar)
        {   
            $seminar_ids[]= $company_seminar->id;
        }

        $completed_seminars = CompanySeminarComplete::whereIn('completed_seminar_id',$seminar_ids)
                                                ->whereNotNull('completed_seminar_id')
                                                ->whereRaw('date(completed_at) >= ?',[$from_date])
                                                ->whereRaw('date(completed_at) <= ?',[$to_date])
                                                ->get();
                                                // dd($completed_seminars);
        return view('company.instructor.seminar.mis.completed_seminar',compact('completed_seminars','company_seminars','to_date','from_date'));
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
        $instructor_id = Auth::user()->id;

        $instructor = CompanyInstructor::where('user_id',$instructor_id)->first();

        // all seminar in company
        $company_seminars = CompanySeminar::where('company_id', $company_id)
                                // ->where('user_id',$instructor_id)
                                ->whereIn('department_id',explode(",",$instructor->department_id))
                                ->whereRaw('date(created_at) >= ?',[$from_date])
                                ->whereRaw('date(created_at) <= ?',[$to_date])
                                ->get();
        // all employee of company 
        $employees = CompanyEmployee::where('company_id',$company_id)
                                ->whereIn('department_id',explode(",",$instructor->department_id))
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
                                                
        return view('company.instructor.seminar.mis.pending_seminar',compact('response_array','to_date','from_date'));
    }

    public function AllPendingCourse()
    {
        $response_array = array();
        $company_id = Auth::user()->company_id;
        $instructor_id = Auth::user()->id;
        $instructor = CompanyInstructor::where('user_id',$instructor_id)->first();

        // get company coupon
        $coupons_courses = Coupon::with('course')->where('company_id',$company_id)->where('department_id',$instructor->department_id)->get();
        // all employee of company 
        $employees = CompanyEmployee::where('company_id',$company_id)
                    ->whereIn('department_id',explode(",",$instructor->department_id))
                    ->get();
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
        return view('company.instructor.seminar.mis.pending_course',compact('response_array'));
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

        $company_id = Auth::user()->company_id;
        $instructor_id = Auth::user()->id;
        $instructor = CompanyInstructor::where('user_id',$instructor_id)->first();

        // get company coupon
        $coupons_courses = Coupon::with('course')->where('company_id',$company_id)->get();
        $seminar_ids = [];
        foreach ($coupons_courses as $key => $coupons_course)
        {   
            if (!empty($coupons_course->course))
            {
                $seminar_ids[]= $coupons_course->course->id;
            }
        }

        // all employee of company 
        $employees = CompanyEmployee::where('company_id',$company_id)
                    ->whereIn('department_id',explode(",",$instructor->department_id))
                    ->get();
        $employee_ids = [];
        foreach ($employees as $key => $employee)
        {
            $employee_ids[] = $employee->user_id;
        }

        $completed_courses = Complete::whereIn('completed_course_id',$seminar_ids)
                            ->whereIn('user_id',$employee_ids)
                            ->whereNotNull('completed_course_id')
                            ->whereRaw('date(completed_at) >= ?',[$from_date])
                            ->whereRaw('date(completed_at) <= ?',[$to_date])
                            ->get();
                            
        return view('company.instructor.seminar.mis.completed_course',compact('completed_courses','coupons_courses','to_date','from_date'));
    }
    
    public function listAllCourse()
    {
        $company_id = Auth::user()->company_id;
        $user_id    = Auth::user()->id;
        $instructor      = CompanyInstructor::where('user_id',$user_id)->first();
        $course_request  = CourseRequest::where('company_id', $company_id)->whereIn('department_id',explode(",",$instructor->department_id))->where('status',1)->get();
        return view('company.admin.course.list_all_course',compact('course_request'));
    }
    
    public function EmployeeScore($from_date = null, $to_date = null)
    {   
        $from_date = $from_date;
        $to_date = $to_date;
        if ($from_date == null) {
            $from_date = date("Y-m-d", strtotime("-1 months"));
        }
        if ($to_date == null) {
            $to_date = date("Y-m-d");
        }
        $company_id     = Auth::user()->company_id;
        $user_id        = Auth::user()->id;
        $instructor      = CompanyInstructor::where('user_id',$user_id)->first();
        $employees = CompanyEmployee::where('company_id',$company_id)
                                            ->whereIn('department_id',explode(",",$instructor->department_id))
                                            ->where('status',1)
                                            ->get();
        return view('company.instructor.score.score',compact('employees','from_date','to_date'));
    }
}