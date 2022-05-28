<?php

namespace App\Http\Controllers\zoom;

use App\Course;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class LiveClassController extends Controller
{
    public function settings(){
        $title = "Zoom Live Class Settings";
        return view('admin.zoom.settings', compact('title'));
    }

    public function lessonLiveSettings($course_id){
        $title = "Zoom Live Class Settings";
        $course = Course::find($course_id);
        if ( ! $course || ! $course->i_am_instructor){
            abort(404);
        }

        return view('admin.zoom.course_live_class_settings', compact('title', 'course'));
    }

    public function lessonLiveSettingsPost(Request $request, $course_id){
        $rules = [
            'live_class.schedule' => 'required',
            'live_class.zoom_meeting_id' => 'required',
            'live_class.zoom_meeting_password' => 'required',
        ];

        $this->validate($request, $rules);

        $zoom_meeting_id = str_replace(' ', '', trim(array_get($request->live_class, 'zoom_meeting_id')));


        $live_class_option = (array) $request->live_class;
        $live_class_option['zoom_meeting_id'] = $zoom_meeting_id;


        $course = Course::find($course_id);
        if ( ! $course || ! $course->i_am_instructor){
            abort(404);
        }

        $videoSrc = [];
        if ($course->video_src){
            $videoSrc = (array) json_decode($course->video_src, true);
        }

        $videoSrc['live_class'] = $live_class_option;
        $course->video_src = json_encode($videoSrc);
        $course->save();

        return back();
    }

    public function liveClassStream($slug){
        $title = 'Live Class Stream';
        $course = Course::whereSlug($slug)->first();

        return view('admin.zoom.live_class_stream', compact('title', 'course'));
    }
}