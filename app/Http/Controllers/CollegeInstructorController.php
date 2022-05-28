<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\collage;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CollegeInstructorController extends Controller
{
    public function index()
    {	
    	$Colleges = collage::where('id',Auth::user()->college_id)->first();

    	$collegeInstructors = User::where('college_user_type', 2)->where('college_id', $Colleges->id)->get();

        return view(theme('dashboard.college_instructor'),compact('Colleges', 'collegeInstructors'));
    }

    public function create(Request $request)
    {	
		$rules = [
			'name'     => 'required|max:255',
			'email'    => 'required|max:255|email|unique:users',
			'college'  => 'required',
			'standard' => 'required',
			'subject'  => 'required',
			'division' => 'required',
			'batch'    => 'required'
		];

		$this->validate($request, $rules);

    	$result = User::insert([
    		'name' => $request->name,
    		'email' => $request->email,
    		'standard' => $request->standard,
    		'subject' => $request->subject,
    		'division' => $request->division,
    		'batch' => $request->batch,
    		'password' => Hash::make($request->password),
    		'college_user_type' => 2,
    		'user_type' => 'instructor',
    		'college_id' => $request->college,
    		'active_status' => 1
    	]);

    	if(!empty($result))
    	{
    		return redirect()->route('create_collage_instructor');
    	}
    	else
    	{
    		return redirect()->back();
    	}
    }
}