<?php

namespace App\Http\Controllers\channelPartner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\channelPartner;
use App\channelPartnerEarning;
use App\User;
use App\Course;
use App\AffiliteMarketing;
use App\AffiliteMarketingReportController;
use Auth;
use App\Rules\affiliteCourse;

class AffiliteMarketingController extends Controller
{
    //channel_partner
    public function index()
    {   
        $courses = Course::where('status',1)->get();
        if (Auth::user()->user_type == 'instructor') 
        {
            $courses = Course::where('status',1)->where('user_id',Auth::user()->id)->get();
        }
        
        $AffiliteMarketings = AffiliteMarketing::where('user_id',Auth::user()->id)->where('is_deleted',0)->where('status',1)->get();
        return view(theme('dashboard.affilite_marketing.index'),compact('courses','AffiliteMarketings'));
    }

    public function store(Request $request)
    {   

        $rules = [
			'course_id'         => ['required',new affiliteCourse]
		];
        $this->validate($request, $rules);

        $courses = Course::where('id',$request->course_id)->where('status',1)->first();
        $AffiliteMarketing             = new AffiliteMarketing();
        $AffiliteMarketing->user_id    = Auth::user()->id;
        $AffiliteMarketing->course_id  = $request->course_id;
        $AffiliteMarketing->slug       = $courses->slug;
        $AffiliteMarketing->save();

        $courses                        = Course::where('status',1)->get();
        $AffiliteMarketings             = AffiliteMarketing::where('user_id',Auth::user()->id)->where('is_deleted',0)->where('status',1)->get();

        return redirect()->route('affilite_marketing');
        // return view(theme('dashboard.affilite_marketing.index'),compact('courses','AffiliteMarketings'))->with('message', 'Channel partner created successfully !');
    }
    
    public function delete(Request $request)
    {	
        $AffiliteMarketings = AffiliteMarketing::where('id',$request->id)->update(['status' => 0,'is_deleted' => 1]);
        return redirect()->route('affilite_marketing')->with('message', 'Affilite Marketing link deleted successfully !');
    }

}