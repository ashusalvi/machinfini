<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use App\collage;

class CreateCollageController extends Controller
{
    public function index(){

        // get collage data
        $collages = collage::all();

        return view('admin.collage',compact('collages'));
    }

    public function create(Request $request){

        $rules = [
            'name' => 'required|max:255',
            'location' => 'required|max:255'
        ];

        $this->validate($request, $rules);

        $data = [
            'name'=> $request->name,
            'location' => $request->location
        ];

        $insertCoupon = collage::insert($data);
        return redirect::back();

    }
}