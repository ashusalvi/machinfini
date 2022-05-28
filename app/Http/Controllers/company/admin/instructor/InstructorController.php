<?php

namespace App\Http\Controllers\company\admin\instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Model\CompanyDepartment;
use App\Model\CompanyInstructor;
use App\User;
use Auth;
use Redirect;
use Response;
class InstructorController extends Controller
{
    public function create()
    {   
        $company_id     = Auth::user()->company_id;
        $departments    = CompanyDepartment::where('company_id',$company_id)
                                            ->where('status',1)
                                            ->get();
        return view('company.admin.instructor.create',compact('departments'));
    }
    
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
            $user->user_type       = 'company-instructor';
            $user->company_id      = $company_id;
            $user->password        = Hash::make('123456789');
            $user->save();

            // save instructor
            $instructor                 = new CompanyInstructor;
            $instructor->company_id     = $company_id;
            $instructor->department_id  = $request->department;
            $instructor->user_id        = $user->id;
            $instructor->employee_id    = $request->employee_id; 
            $instructor->save();
            
            return Redirect::route('CPA_instructor_list')->with('message', 'Company instructor created successfully !');
        }
        
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
                $departments_array = explode(",",$departments);

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
                        
                        $department_id = '';
                        foreach ($departments_array as $key => $departments){
                            $department = CompanyDepartment::whereRaw('LOWER(`name`)LIKE ?',[trim(strtolower($departments))])->where('company_id',$company_id)->first();

                            if ($department == null) {
                                $department                 = new CompanyDepartment();
                                $department->company_id	    = $company_id;
                                $department->name	        = $departments;
                                $department->save();
                            }
                            $department_id .= $department->id;
                            if (count($departments_array) != $key+1) {
                                $department_id .= ',';
                            }
                        }
                        

                        // save user
                        $user                  = new User;
                        $user->name            = $name;
                        $user->email           = $email;
                        $user->phone           = $phone;
                        $user->user_type       = 'company-instructor';
                        $user->company_id      = $company_id;
                        $user->password        = Hash::make('123456789');
                        $user->save();

                        // save instructor
                        $instructor                 = new CompanyInstructor;
                        $instructor->company_id     = $company_id;
                        $instructor->department_id  = $department_id;
                        $instructor->user_id        = $user->id;
                        $instructor->employee_id    = $employee_id; 
                        $instructor->save();

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
        $fp = fopen('Company_instructor.csv', 'w');
        
        // Loop through file pointer and a line
        foreach ($output_array as $fields) {
            fputcsv($fp, $fields);
        }
        
        fclose($fp);
        $headers = array(
            'Content-Type' => 'text/csv',
        );

        return Response::download('Company_instructor.csv', 'Company_instructor.csv', $headers);
        
        return Redirect::route('CPA_instructor_list')->with('message', 'Company instructor created successfully !');
    }

    public function list()
    {   
        $company_id     = Auth::user()->company_id;
        $instructors = CompanyInstructor::where('company_id',$company_id)
                                          ->where('status',1)->get(); 
        return view('company.admin.instructor.index',compact('instructors'));
    }

    public function edit($id)
    {   
        $company_id     = Auth::user()->company_id;
        $departments    = CompanyDepartment::where('company_id',$company_id)
                                            ->where('status',1)
                                            ->get();
        $instructors = CompanyInstructor::where('id',$id)->with('user')->first(); 
        // dd($instructors);
        return view('company.admin.instructor.edit',compact('instructors','departments'));
    }

    public function update(Request $request,$id)
    {   
        
        $rules = [
			'employee_id'       => 'required|max:255',
			'name'              => 'required|max:255',
			'department'        => 'required|max:255',
		];

        $instructor = CompanyInstructor::where('id',$id)->with('user')->first(); 
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
            $user->user_type       = 'company-instructor';
            $user->company_id      = $company_id;
            $user->save();

            // save instructor
            $instructor                 = CompanyInstructor::where('id',$id)->first();
            $instructor->company_id     = $company_id;
            $instructor->department_id  = $request->department;
            $instructor->employee_id    = $request->employee_id; 
            $instructor->save();
            
            return Redirect::route('CPA_instructor_list')->with('message', 'Company instructor Updated successfully !');
        }
        
    }

    public function delete($id)
    {   
        $instructor = CompanyInstructor::where('id',$id)->first(); 
        $instructor->status    = 0; 
        $instructor->save();

        $user                  = User::where('id',$instructor->user_id)->first();
        $user->active_status      = 1;
        $user->save();

        return Redirect::route('CPA_instructor_list')->with('message', 'Company instructor Deleted successfully !');
    }
}