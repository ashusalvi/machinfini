<?php

namespace App\Http\Controllers\company\admin\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Model\CompanyDepartment;
use App\Model\CompanyInstructor;
use App\Model\CompanyEmployee;
use App\Model\CompanySeminar;
use App\Model\CompanySeminarEnroll;
use App\User;
use Auth;
use Redirect;
use Response;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company_id     = Auth::user()->company_id;
        $departments    = CompanyDepartment::where('company_id',$company_id)
                                            ->where('status',1)
                                            ->get();
        return view('company.admin.employee.create',compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
			'employee_id'       => 'required|max:255',
			'name'              => 'required|max:255',
			'department'        => 'required|max:255',
			'email'             => 'required|email|max:255|unique:users',
			// 'phone'            => 'required|integer|min:10|unique:users',
		];
        $this->validate($request, $rules);

        $company_id = (Auth::user()->company_id != null) ?  Auth::user()->company_id : null;

        if ($company_id == null) {
            // redirect back with error message login
            return Redirect::back()->withErrors(['message', 'Please Login']);
        } else {
            // save user
            $user                  = new User;
            $user->name            = $request->name;
            $user->email           = $request->email;
            $user->phone           = $request->phone;
            $user->user_type       = 'company-employee';
            $user->company_id      = $company_id;
            $user->password        = Hash::make('123456789');
            $user->save();

            // save employee
            $employee                 = new CompanyEmployee;
            $employee->company_id     = $company_id;
            $employee->department_id  = $request->department;
            $employee->user_id        = $user->id;
            $employee->employee_id    = $request->employee_id; 
            $employee->save();
            
            return Redirect::route('CPA_employee_list')->with('message', 'Company employee created successfully !');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $company_id     = Auth::user()->company_id;
        $employees = CompanyEmployee::where('company_id',$company_id)
                                          ->where('status',1)->get(); 
        return view('company.admin.employee.index',compact('employees'));
    }

    public function upload(Request $request)
    {
        $rules = [
            'file' => 'required|file',
        ];
        $this->validate($request, $rules);

        $company_id = (Auth::user()->company_id != null) ?  Auth::user()->company_id : null;

        if ($company_id == null) {
            // redirect back with error message login
            return Redirect::back()->withErrors(['message', 'Please Login']);
        }
        
        $file = $_FILES['file']['tmp_name'];
        $userfile_extn = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        if ($userfile_extn != 'csv') {
            return Redirect::back()->withErrors(['message', 'Please upload CSV files']);
        }
        
        $handle = fopen($file, "r");
        $output_array = [
            [
                'Employee Id',
                'Name',
                'Email',
                'Phone',
                'Department',
                'Status',
                'Message',
            ]
        ];
        $loop   = 0;
        while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
        { 
            if($loop != 0)
            {
                $employee_id    = $filesop[0];
                $name           = $filesop[1];
                $email          = $filesop[2];
                $phone          = $filesop[3];
                $departments    = $filesop[4];

                // check data is exists or not
                if ($employee_id == '' || $name == '' || $email == '' || $departments == ''){
                    $output_array[] = [
                        $employee_id,
                        $name,
                        $email,
                        $phone,
                        $departments,
                        'Error',
                        'Please fill up all the fileds.'
                    ];
                }else{
                    $unique_user = User::where('email',$email)->count();
                    if ($unique_user > 0) {
                        $output_array[] = [
                            $employee_id,
                            $name,
                            $email,
                            $phone,
                            $departments,
                            'Error',
                            'Email is already exists.'
                        ];
                    }else{
                        
                        $department = CompanyDepartment::whereRaw('LOWER(`name`)LIKE ?',[trim(strtolower($departments))])->where('company_id',$company_id)->first();

                        if ($department == null) {
                            $department                 = new CompanyDepartment();
                            $department->company_id	    = $company_id;
                            $department->name	        = $departments;
                            $department->save();
                        }
                        $department_id = $department->id;

                        // save user
                        $user                  = new User;
                        $user->name            = $name;
                        $user->email           = $email;
                        $user->phone           = $phone;
                        $user->user_type       = 'company-employee';
                        $user->company_id      = $company_id;
                        $user->password        = Hash::make('123456789');
                        $user->save();

                        // save employee
                        $employee                 = new CompanyEmployee;
                        $employee->company_id     = $company_id;
                        $employee->department_id  = $department_id;
                        $employee->user_id        = $user->id;
                        $employee->employee_id    = $employee_id; 
                        $employee->save();

                        $output_array[] = [
                            $employee_id,
                            $name,
                            $email,
                            $phone,
                            $departments,
                            'Success',
                            ''
                        ];
                    }
                }

            }
            $loop ++;
        }

        // Open a file in write mode ('w')
        $fp = fopen('Company_employee.csv', 'w');
        
        // Loop through file pointer and a line
        foreach ($output_array as $fields) {
            fputcsv($fp, $fields);
        }
        
        fclose($fp);
        $headers = array(
            'Content-Type' => 'text/csv',
        );

        return Response::download('Company_employee.csv', 'Company_employee.csv', $headers);
        
        return Redirect::route('CPA_employee_list')->with('message', 'Company employee created successfully !');
    }

    public function allSeminar(Request $request)
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

        $companySeminars = CompanySeminar::where('department_id',$employee_details->department_id)->where('status',1)->get();

        return view('company.employee.all_seminar',compact('companySeminars'));
    }

    public function attemptedSeminar(Request $request)
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

        $companySeminars = CompanySeminar::whereIn('user_id',$instructor_id)->get();

        $companySeminars   = $companySeminars->map(function($query) use ($user_id){
            
            $seminar_id = $query->id;
            $attended_seminar = CompanySeminarEnroll::where('course_id',$seminar_id)
                                                    ->where('user_id',$user_id)
                                                    ->count();
            if ($attended_seminar == 1) {
                return $query;
            }
        });

        return view('company.employee.atempted_seminar',compact('companySeminars'));
    }

    public function edit($id)
    {   
        $company_id     = Auth::user()->company_id;
        $departments    = CompanyDepartment::where('company_id',$company_id)
                                            ->where('status',1)
                                            ->get();
        $instructors = CompanyEmployee::where('id',$id)->with('user')->first(); 
        // dd($instructors);
        return view('company.admin.employee.edit',compact('instructors','departments'));
    }

    public function update(Request $request,$id)
    {   
        
        $rules = [
			'employee_id'       => 'required|max:255',
			'name'              => 'required|max:255',
			'department'        => 'required|max:255',
		];

        $instructor = CompanyEmployee::where('id',$id)->with('user')->first(); 
        if ($instructor->user->email != $request->email ) {
            $rules = [
                'email'             => 'required|email|max:255|unique:users',
            ];
        }

        $this->validate($request, $rules);

        $company_id = (Auth::user()->company_id != null) ?  Auth::user()->company_id : null;

        if ($company_id == null) {
            // redirect back with error message login
            return Redirect::back()->withErrors(['message', 'Please Login']);
        } else {
            // save user
            $user                  = User::where('id',$instructor->user_id)->first();
            $user->name            = $request->name;
            $user->email           = $request->email;
            $user->phone           = $request->phone;
            $user->user_type       = 'company-employee';
            $user->company_id      = $company_id;
            $user->save();

            // save instructor
            $instructor                 = CompanyEmployee::where('id',$id)->first();
            $instructor->company_id     = $company_id;
            $instructor->department_id  = $request->department;
            $instructor->employee_id    = $request->employee_id; 
            $instructor->save();
            
            return Redirect::route('CPA_employee_list')->with('message', 'Company employee Updated successfully !');
        }
        
    }

    public function delete($id)
    {   
        $instructor = CompanyEmployee::where('id',$id)->first(); 
        $instructor->status    = 0; 
        $instructor->save();

        $user                  = User::where('id',$instructor->user_id)->first();
        $user->active_status      = 1;
        $user->save();

        return Redirect::route('CPA_employee_list')->with('message', 'Company employee Deleted successfully !');
    }
}