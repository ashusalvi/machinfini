<?php

namespace App\Http\Controllers\curriculum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Model\Curriculum;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class curriculumController extends Controller
{
    public function create(){
        return view('admin.curriculum.create');
    }
    
    public function save(Request $request){
        $rules = [
            'type' => 'required|max:255',
            'title' => 'required|max:255',
            'classes' => 'required|integer',
            'details' => 'required|max:600',
            'price' => 'required|integer',
            'save' => 'required|integer',
        ];
        $this->validate($request, $rules);

    	$result = Curriculum::insert([
    		'type' => $request->type,
    		'title' => $request->title,
    		'classes' => $request->classes,
    		'description' => $request->details,
    		'price' => $request->price,
    		'tag' => $request->tag,
    		'save' => $request->save,
    	]);

    	if(!empty($result)){
    		return redirect()->route('curriculum.list');
    	}else{
    		return redirect()->back();
    	}
    }

    public function list(){
        $curriculums = Curriculum::all();
        return view('admin.curriculum.list',compact('curriculums'));
    }

    public function report(){
        return view('admin.curriculum.report');
    }
}