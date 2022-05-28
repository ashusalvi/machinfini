<?php

namespace App\Http\Controllers\company\admin\department;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;
use Redirect;
use App\Model\CompanyDepartment;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   $company_id     = Auth::user()->company_id;
        $departments    = CompanyDepartment::where('company_id',$company_id)->get();
        return view('company.admin.department.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        return view('company.admin.department.create');
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
			'department_name'      => 'required|max:255',
		];
        $this->validate($request, $rules);
        // check user is login
        $company_id = (Auth::user()->company_id != null) ?  Auth::user()->company_id : null;

        if ($company_id == null) {
            // redirect back with error message login
            return Redirect::back()->withErrors(['message', 'Please Login']);
        } else {
            // create object of department object
            $department                 = new CompanyDepartment();
            $department->company_id	    = Auth::user()->company_id;
            $department->name	        = $request->department_name;
            $department->save();
            return Redirect::route('CPA_department_list')->with('message', 'Company department created successfully !');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

   
}