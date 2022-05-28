<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\CompanyCoupon;
use App\Model\Company;
use App\Model\companyJob;
use Illuminate\Support\Facades\Hash;
use App\Model\CourseRequest;
use App\Model\CompanyDepartment;

class CompanyController extends Controller
{
    public function index(){
        $companies = Company::where('status',1)->where('type',0)->with('user')->get();
        return view('admin.company.index',compact('companies'));
    }

    public function companyList(){
        $companies = Company::where('status',1)->where('type',1)->with('user')->get();
        return view('admin.company.list_company',compact('companies'));
    }

    public function companyJobList(){
        $companie_jobs = companyJob::where('is_delete',0)->with('company')->get();
        return view('admin.company.job_list_company',compact('companie_jobs'));
    }

    public function create(){
        return view('admin.company.create');
    }

    public function store(Request $request)
    {   
        $rules = [
			'company_name'      => 'required|max:255|unique:companies',
			'company_type'      => 'required|max:255',
			// 'company_email'     => 'required|max:255|unique:companies',
			'data_type' => 'required',
			'company_address'   => 'required|max:255',
			'name'              => 'required|max:255',
			'email'             => 'required|max:255|unique:users',
			// 'phone'             => 'required|max:255|unique:users',
		];
        $this->validate($request, $rules);

        // company create
        $company                    = new Company;
        $company->company_name      = $request->company_name;
        $company->company_address   = $request->company_address;
        $company->company_email     = $request->company_email;
        $company->company_number    = $request->company_number;
        $company->company_type      = $request->company_type;
        $company->type = $request->data_type;
        $company->save();

        // save admin
        $admin                  = new User;
        $admin->name            = $request->name;
        $admin->email           = $request->email;
        $admin->phone           = $request->phone;
        $admin->user_type       = 'company-admin';
        if ($request->data_type == 1) {
            $admin->user_type   = 'company-admin-user';
        }
        
        $admin->company_id      = $company->id;
        $admin->password        =  Hash::make('123456789');
        $admin->save();

        // update company table
        $update_company = Company::where('id',$company->id)
                        ->update([
                            'admin_id'=>$admin->id
                            ]);
        if ($update_company) {
            if ($request->data_type == 0) {
               return redirect()->route('company')->with('message', 'college created successfully !');
            }else{
                return redirect()->route('companyList')->with('message', 'Company created successfully !');
            }
            
        }else{
            Company::where('id', '=', $company->id)->delete();
            User::where('id', '=', $admin->id)->delete();
            return redirect()->back(); 
        }                    
        
    }

    public function CompanyCount($from_date = null, $to_date = null, $company_id = null)
    {
        if ($from_date == null ) 
        {
            $from_date = date("Y-m-d", strtotime("-1 months"));
        }

        if ($to_date == null)
        {
            $to_date = date("Y-m-d");
        }

        $all_company = Company::where('status', 1)->get();

        $companies = Company::where('status', 1)
                            ->whereRaw('date(created_at) >= ?',[$from_date])
                            ->whereRaw('date(created_at) <= ?',[$to_date])
                            ->get();

        if ($company_id != null) {
            $companies = Company::where('status', 1)
                                ->where('id',$company_id)
                                ->whereRaw('date(created_at) >= ?',[$from_date])
                                ->whereRaw('date(created_at) <= ?',[$to_date])
                                ->get();
        }
        return view('admin.company.mis.mis_company_count',compact('companies', 'all_company', 'to_date', 'from_date', 'company_id'));
    }

    public function CourseStatus($from_date = null, $to_date = null, $company_id = null)
    {
        if ($from_date == null ) 
        {
            $from_date = date("Y-m-d", strtotime("-1 months"));
        }

        if ($to_date == null)
        {
            $to_date = date("Y-m-d");
        }

        $all_company = Company::where('status', 1)->get();

        $companies = Company::where('status', 1)
                            ->whereRaw('date(created_at) >= ?',[$from_date])
                            ->whereRaw('date(created_at) <= ?',[$to_date])
                            ->first();

        if ($company_id != null) {
            $companies = Company::where('status', 1)
                                ->where('id',$company_id)
                                ->whereRaw('date(created_at) >= ?',[$from_date])
                                ->whereRaw('date(created_at) <= ?',[$to_date])
                                ->first();
        }else{
            if (!empty($companies)) {
                $company_id = $companies->id;
            }
        }
        return view('admin.company.mis.mis_course_status',compact('companies', 'all_company', 'to_date', 'from_date', 'company_id'));
    }

    public function PersonnelData($from_date = null, $to_date = null, $company_id = null, $designation = 'admin')
    {
        if ($from_date == null ) 
        {
            $from_date = date("Y-m-d", strtotime("-1 months"));
        }

        if ($to_date == null)
        {
            $to_date = date("Y-m-d");
        }

        $all_company = Company::where('status', 1)->get();

        $companies = Company::where('status', 1)
                            ->whereRaw('date(created_at) >= ?',[$from_date])
                            ->whereRaw('date(created_at) <= ?',[$to_date])
                            ->first();

        if ($company_id != null) {
            $companies = Company::where('status', 1)
                                ->where('id',$company_id)
                                ->whereRaw('date(created_at) >= ?',[$from_date])
                                ->whereRaw('date(created_at) <= ?',[$to_date])
                                ->first();
        }else{
            if (!empty($companies)) {
                $company_id = $companies->id;
            }
        }

        return view('admin.company.mis.mis_personnel_data',compact('companies', 'all_company', 'to_date', 'from_date', 'company_id','designation'));
    }

    public function CourseRequest($from_date = null, $to_date = null, $company_id = null)
    {
        if ($from_date == null ) 
        {
            $from_date = date("Y-m-d", strtotime("-1 months"));
        }

        if ($to_date == null)
        {
            $to_date = date("Y-m-d");
        }

        $all_company = Company::where('status', 1)->get();

        $companies = CourseRequest::whereRaw('date(request_created) >= ?',[$from_date])
                            ->whereRaw('date(request_created) <= ?',[$to_date])
                            ->get();

        if ($company_id != null) {
            $companies = CourseRequest::where('company_id',$company_id)
                                ->whereRaw('date(request_created) >= ?',[$from_date])
                                ->whereRaw('date(request_created) <= ?',[$to_date])
                                ->get();
        }
        return view('admin.company.mis.mis_course_request',compact('companies', 'all_company', 'to_date', 'from_date', 'company_id'));
    }

    public function updateCourseRequest(Request $request)
    {
        $course_request                 = CourseRequest::where('id',$request->id)->first();
        $course_request->status         = $request->status;
        $course_request->save();
    }

    public function createCompanyCoupon(){
        $companies = Company::where('status',1)->get();
        return view('admin.company.create_company_coupon',compact('companies'));
    }

    public function storeCompanyCoupon(Request $request)
    {   
        // dd($request);
        $rules = [
			'company_id' => 'required',
			'department_name' => 'required|max:255',
			'coupon_name' => 'required|max:255',
			'code' => 'required|max:255|unique:company_coupon',
			'price' => 'required|max:255',
			'expiry_date' => 'required|max:255',
		];
        $this->validate($request, $rules);

        // save admin
        $company_department = new CompanyDepartment;
        $company_department->company_id = $request->company_id;
        $company_department->name = $request->department_name;
        $company_department->status = 1;
        $company_department->save();

        // company create
        $company                    = new CompanyCoupon;
        $company->company_id = $request->company_id;
        $company->department = $company_department->id;
        $company->name = $request->coupon_name;
        $company->code = $request->code;
        $company->price = $request->price;
        $company->expiry_date = $request->expiry_date;
        $company->save();

        if ($company) {
            return redirect()->route('listCompanyCoupon')->with('message', 'Company coupon created successfully !');
        }else{
            return redirect()->back(); 
        }                    
        
    }

    public function listCompanyCoupon(){
        $company_coupons = CompanyCoupon::all();
        return view('admin.company.list_company_coupon',compact('company_coupons'));
    }
}