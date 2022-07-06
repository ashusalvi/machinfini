<?php

namespace App\Http\Controllers\curriculum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Model\Curriculum;
use App\Model\CurriculumEnquiry;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

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

        $file = $request->file('document');
        $destinationPath = 'uploadsCurriculum';
        $file->move($destinationPath,$file->getClientOriginalName());
        $submitURL = $destinationPath.'/'.$file->getClientOriginalName();

    	$result = Curriculum::insert([
    		'type' => $request->type,
    		'title' => $request->title,
    		'classes' => $request->classes,
    		'description' => $request->details,
    		'price' => $request->price,
    		'tag' => $request->tag,
    		'save' => $request->save,
            'file'=>$submitURL,
    	]);

    	if(!empty($result)){
    		return redirect()->route('curriculum.list');
    	}else{
    		return redirect()->back();
    	}
    }

    public function list(){
        $curriculums = Curriculum::orderBy('id','DESC')->get();
        return view('admin.curriculum.list',compact('curriculums'));
    }

    public function delete(Request $request){
        echo Curriculum::where('id', $request->cid)->update(['status' => $request->cvalue]);
    }

    public function curriculumEnquiry(Request $request){
        
        $rules = [
            'curriculum_id' => 'required|max:255',
            'name' => 'required|max:255',
            'email' => 'required|max:255',
            'mobile' => 'required|integer',
            'message' => 'required|max:600',
        ];
        $this->validate($request, $rules);
        $user_id = 0;
        if (Auth::check()) {
             $user_id = Auth::user()->id;
        }

        $result = CurriculumEnquiry::insert([
    		'institude_name' => $request->institute_name,
    		'curriculum_id' => $request->curriculum_id,
    		'user_id' => $user_id,
    		'name' => $request->name,
    		'email' => $request->email,
    		'mobile' => $request->mobile,
    		'message' => $request->message
    	]);

    	if(!empty($result)){
    		echo 1;
    	}else{
    		echo 0;
    	}

    }

    public function report(){
        $curriculumEnquirys = CurriculumEnquiry::with('Curriculum')->orderBy('id','DESC')->get();
        return view('admin.curriculum.report',compact('curriculumEnquirys'));
    }
}