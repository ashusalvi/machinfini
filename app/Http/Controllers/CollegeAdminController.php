<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\collage;
use App\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\Rule;

class CollegeAdminController extends Controller
{
    public function index()
    {
    	$collages = collage::all();

    	$collegeAdmins = User::where('college_user_type', 1)->get();

        return view('admin.college_admin',compact('collages', 'collegeAdmins'));
    }

    public function create(Request $request)
    {	
		$rules = [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
		];

		$this->validate($request, $rules);
    	$result = User::insert([
    		'name' => $request->name,
    		'email' => $request->email,
    		'password' => Hash::make($request->password),
    		'college_user_type' => 1,
    		'user_type' => 'instructor',
    		'college_id' => $request->college,
    		'active_status' => 1
    	]);

    	if(!empty($result))
    	{
    		return redirect()->route('create_collage_admin');
    	}
    	else
    	{
    		return redirect()->back();
    	}
    }
}