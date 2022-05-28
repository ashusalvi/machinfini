<?php

namespace App\Http\Controllers\channelPartner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Rules\pancard;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

use App\channelPartner;
use App\channelPartnerEarning;
use App\User;

class channgelPartnerController extends Controller
{
    //channel_partner
    public function index()
    {
        $channel_partners = channelPartner::where('is_deleted',0)->get();
        return view('channelPartner.index',compact('channel_partners'));
    }

    public function store(Request $request)
    {   
        
        $six_digit_random_number = mt_rand(100000, 999999);
        $rules = [
			'name'          => 'required|max:255',
			'email'         => 'required|email|max:255|unique:users',
			'mobile_no'     => 'required|numeric|digits:10',
			'adhar_no'      => 'required|numeric|digits:12',
			'pan_no'        => ['required', new pancard],
			'partnership'   => 'required|numeric|max:100',
		];

		$this->validate($request, $rules);
    	$result = User::create([
    		'name' => $request->name,
    		'email' => $request->email,
    		'phone' => $request->mobile_no,
    		'password' => Hash::make($request->password),
    		'user_type' => 'channel_partner',
            'reference_code' => 'CPOFF-'.$six_digit_random_number,
            'channel_partner_code' => 'CPOFF-'.$six_digit_random_number,
            'channel_partner_per' =>$request->partnership,
    		'active_status' => 1
    	]);
        
    	if(!empty($result))
    	{   
            $fileName = '';
            if (!empty($request->file('logo'))) {
                $uploadedFile = $request->file('logo');
                $fileName =
                'uploads/channel_partner_logo/channel_partner_logo_'.strtotime("now").'.'.$uploadedFile->extension();
                $note_img = Image::make($request->file('logo'))->stream();
                Storage::disk('public')->put($fileName, $note_img);
            }

            $channel_partner = channelPartner::create([
                'user_id' => $result->id,
                'adhar_number' => $request->adhar_no,
                'pan_number' => $request->pan_no,
                'partnership' => $request->partnership,
                'logo' => $fileName
            ]);

    		return redirect()->route('channel_partner')->with('message', 'Channel partner created successfully !');
    	}
    	else
    	{
    		return redirect()->back();
    	}
    }

	public function delete(Request $request)
    {	
        $channel_partners = channelPartner::where('id', $request->id)->delete();
        return redirect()->route('channel_partner')->with('message', 'Channel partner deleted successfully !');
    }
}