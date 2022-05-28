<?php

namespace App\Http\Controllers\company\company\job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use Redirect;
use App\Model\companyJob;

class JobController extends Controller
{
    public function create()
    {
        return view('company.company.job.create');
    }

    public function store(Request $request)
    {   
        $company_id = Auth::user()->company_id;
        $admin_id = Auth::user()->id;
        $rules = [
            'posting' => 'required|max:255',
            'title' => 'required|max:255',
            'requisition_number' => 'required|max:255',
            'company_description' => 'required|max:255',
            'job_description' => 'required|max:255',
            'core_responsibilites' => 'required|max:255',
            'desirable_skills' => 'required|max:255',
            'employment' => 'required|max:255',
            'date_if_test' => 'required|max:255',
            'start_time' => 'required|max:255',
            'end_time' => 'required|max:255',
            'total_score' => 'required|max:255',
            'passing_score' => 'required|max:255',
            'shift_time' => 'required|max:255',
            'education' => 'required|max:255',
            'industry_type' => 'required|max:255',
            'functional_areas' => 'required|max:255',
            'salary_range' => 'required|max:255',
            'location' => 'required|max:255',
            'designation' => 'required|max:255',
            'diversity_preference' => 'required|max:255',
            'experience_required' => 'required|max:255',
        ];
        $this->validate($request, $rules);
        $data = [
            'company_id' =>$company_id,
            'admin_id' =>$admin_id,
            'posting' =>$request->posting,
            'title' =>$request->title,
            'requisition_number' =>$request->requisition_number,
            'company_description' =>$request->company_description,
            'job_description' =>$request->job_description,
            'core_responsibilites' =>$request->core_responsibilites,
            'desirable_skills' =>$request->desirable_skills,
            'employment' =>$request->employment,
            'date_if_test' =>$request->date_if_test,
            'start_time' =>$request->start_time,
            'end_time' =>$request->end_time,
            'total_score' =>$request->total_score,
            'passing_score' =>$request->passing_score,
            'shift_time' =>$request->shift_time,
            'education' =>$request->education,
            'industry_type' =>$request->industry_type,
            'functional_areas' =>$request->functional_areas,
            'salary_range' =>$request->salary_range,
            'location' =>$request->location,
            'designation' =>$request->designation,
            'diversity_preference' =>$request->diversity_preference,
            'experience_required' =>$request->experience_required,
        ];
        companyJob::insert($data);
        return Redirect::route('company_job_list')->with('message', 'Company job created successfully !');
    }

    public function list()
    {
        $company_id = Auth::user()->company_id;
        $admin_id = Auth::user()->id;
        $company_jobs = companyJob::where(['company_id' => $company_id,'is_delete' => 0])->get();
        return view('company.company.job.list',compact('company_id', 'admin_id', 'company_jobs'));
    }

    public function updateStatus(Request $request){
        $update_company_jobs = companyJob::where(['id' => $request->job_id])->update(['status' => $request->status]);
        return response()->json('Status updated', 202);
    }

    public function delete(Request $request){
        $update_company_jobs = companyJob::where(['id' => $request->job_id])->update(['is_delete' => 1]);
        return response()->json('Job deleted successfully', 202);
    }
}