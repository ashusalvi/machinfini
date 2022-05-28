<?php

namespace App\Http\Controllers\company\instructor\seminar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Model\CompanySeminar;
use App\Model\CompanyInstructor;
use App\Model\CompanySeminarSection;
use App\Model\CompanySeminarContent;
use App\Model\CompanySeminarEnroll;
use App\Model\CompanyDepartment;
use App\Model\CompanySeminarComplete;
use Carbon\Carbon;

class SeminarController extends Controller
{   
    public function view(Request $request,$slug){
        $seminar = CompanySeminar::whereSlug($slug)->with('sections', 'sections.items')->first();
       
        if ( ! $seminar)
        {
            abort(404);
        }

        $title = $seminar->title;
        $seminar_slug = $seminar->slug;
        $is_open = 0;

        $isEnrolled = false;
        if (Auth::check())
        {
            $user = Auth::user();

            $enrolled = $user->isSeminarEnrolled($seminar->id);
            if ($enrolled){
                $isEnrolled = $enrolled;
            }
        }
        return view('company.seminar-view.seminar', compact('seminar', 'title', 'isEnrolled','is_open'));
    }

    public function index()
    {   
        $user = Auth::user();
        $company_user_details = CompanyInstructor::where('user_id',$user->id)->first();
        $departments_array = explode(",",$company_user_details->department_id);
        $departments = [];
        foreach ($departments_array as $key =>  $department)
        {
            $companyDepartment = CompanyDepartment::where('id',$department)->first();
            $departments[$department] = $companyDepartment->name;
        }

        return view('company.instructor.seminar.create',compact('departments'));
    }

    public function freeEnroll(Request $request){
        $seminar_id = $request->seminar_id;

        if ( ! Auth::check()){
            return redirect(route('login'));
        }

        $user = Auth::user();
        $seminar = CompanySeminar::find($seminar_id);

        $isEnrolled = $user->isSeminarEnrolled($seminar_id);

        if (! $isEnrolled){
            $carbon                 = Carbon::now()->toDateTimeString();
            $enroll                 = new CompanySeminarEnroll();
            $enroll->course_id      = $seminar_id;
            $enroll->user_id        = $user->id;
            $enroll->course_price   = 0;
            $enroll->payment_id     = 0;
            $enroll->status         = 'success';
            $enroll->enrolled_at    = Carbon::now()->toDateTimeString();;
            $enroll->save();
        }

        return redirect(route('seminar_view', $seminar->slug));
    }

    public function lectureView($slug, $lecture_id){
        $lecture = CompanySeminarContent::find($lecture_id);
        $course = $lecture->course;
        $title = $lecture->title;
        
        $isEnrolled = false;

        $isOpen = (bool) $lecture->is_preview;


        $user = Auth::user();

        $isOpen = true;
    
        return view('company.seminar-view.lecture',compact('course', 'title', 'isEnrolled', 'lecture', 'isOpen'));
    }

    public function store(Request $request)
    {   
        // dd($request);
        $rules = [
            'title'             => 'required|max:120',
            'short_description' => 'max:220',
            'score' => 'required',
        ];
        $this->validate($request, $rules);
        $user_id        = Auth::user()->id;
        $company_id     = Auth::user()->company_id;
        $department     = CompanyInstructor::where('user_id',$user_id)->first();
        $department_id  = $request->department;
        $slug           = unique_slug($request->title).'-'.rand(999,99999);
        $now            = Carbon::now()->toDateTimeString();

        $video_source = $request->input('video.source');
        if ($video_source === '-1'){
            $video_src = null;
        }else{
            $video_src = json_encode($request->video);
        }

        $seminar                    = new CompanySeminar();
        $seminar->user_id           = $user_id;
        $seminar->company_id        = $company_id;
        $seminar->department_id     = $department_id;
        $seminar->due_date          = $request->due_date;
        $seminar->expiry_date       = $request->expire_date;
        $seminar->title             = clean_html($request->title);
        $seminar->slug              = $slug;
        $seminar->short_description = clean_html($request->short_description);
        $seminar->thumbnail_id      = $request->thumbnail_id;
        $seminar->video_src         = $video_src;
        $seminar->score             = $request->score;
        $seminar->save();

        return redirect(route('companySeminarEditInformation', $seminar->id));
    }

    public function information($seminar_id)
    {   $title          = __t('Information');
        $seminar        = CompanySeminar::find($seminar_id);
        $user_id        = Auth::user()->id;

        $user = Auth::user();
        $company_user_details = CompanyInstructor::where('user_id',$user->id)->first();
        $departments_array = explode(",",$company_user_details->department_id);
        $departments = [];
        foreach ($departments_array as $key =>  $department)
        {
            $companyDepartment = CompanyDepartment::where('id',$department)->first();
            $departments[$department] = $companyDepartment->name;
        }

        if (!$seminar || $seminar->user_id != $user_id ) 
        {
            abort(404);
        }
        return view('company.instructor.seminar.information',compact('title','seminar','departments'));
    }

    public function informationPost( Request $request, $seminar_id)
    {
        $rules = [
            'title'             => 'required|max:120',
            'short_description' => 'max:220',
        ];
        $this->validate($request, $rules);
        $user_id        = Auth::user()->id;
        $company_id     = Auth::user()->company_id;
        $department_id  = $request->department;
        $now            = Carbon::now()->toDateTimeString();

        $video_source = $request->input('video.source');
        if ($video_source === '-1'){
            $video_src = null;
        }else{
            $video_src = json_encode($request->video);
        }

        $seminar                    = CompanySeminar::find($seminar_id);
        $seminar->user_id           = $user_id;
        $seminar->company_id        = $company_id;
        $seminar->department_id     = $department_id;
        $seminar->title             = clean_html($request->title);
        $seminar->short_description = clean_html($request->short_description);
        $seminar->description       = clean_html($request->description);
        // $seminar->benefits          = clean_html($request->benefits);
        // $seminar->requirements      = clean_html($request->requirements);
        $seminar->thumbnail_id      = $request->thumbnail_id;
        $seminar->video_src         = $video_src;
        $seminar->score             = $request->score;
        $seminar->save();

        return redirect(route('CS_edit_curriculum', $seminar_id));
    }

    public function curriculum($seminar_id){
        $title          = __t('Curriculum');
        $seminar        = CompanySeminar::find($seminar_id);
        $user_id        = Auth::user()->id;
        if (!$seminar || $seminar->user_id != $user_id ) 
        {
            abort(404);
        }
        return view('company.instructor.seminar.curriculum',compact('title','seminar'));
    }

    public function newSection($seminar_id){
        $title          = __t('Curriculum');
        $seminar        = CompanySeminar::find($seminar_id);
        return view('company.instructor.seminar.new_section', compact('title', 'seminar'));
    }

    public function newSectionPost(Request $request, $seminar_id){
        $rules = [
            'section_name' => 'required',
        ];
        $this->validate($request, $rules);

        CompanySeminarSection::create([
                'seminar_id' => $seminar_id,
                'section_name' => clean_html($request->section_name)
            ]
        );
        return redirect(route('CS_edit_curriculum', $seminar_id));
    }

    public function updateSection(Request $request, $id){
        $rules = [
            'section_name' => 'required',
        ];
        $this->validate($request, $rules);

        CompanySeminarSection::whereId($id)->update(['section_name' => clean_html($request->section_name)]);
    }

    public function deleteSection(Request $request){
        if(config('app.is_demo')) return ['success' => false, 'msg' => __t('demo_restriction')];

        $section = CompanySeminarSection::find($request->section_id);
        $seminar = $section->seminar;

        CompanySeminarContent::query()->where('section_id', $request->section_id)->delete();
        $section->delete();
        // $seminar->sync_everything();

        return ['success' => true];
    }

    public function newLecture(Request $request, $seminar_id){
        $rules = [
            'title' => 'required'
        ];

        $validation = Validator::make($request->input(), $rules);

        if ($validation->fails()){
            $errors = $validation->errors()->toArray();

            $error_msg = "<div class='alert alert-danger mb-3'>";
            foreach ($errors as $error){
                $error_msg .= "<p class='m-0'>{$error[0]}</p>";
            }
            $error_msg .= "</div>";

            return ['success' => false, 'error_msg' => $error_msg];
        }

        $user_id = Auth::user()->id;

        $lesson_slug = unique_slug($request->title, 'Content');
        $sort_order = next_curriculum_item_id($seminar_id);

        $data = [
            'user_id'       => $user_id,
            'seminar_id'     => $seminar_id,
            'section_id'    => $request->section_id,
            'title'         => clean_html($request->title),
            'slug'          => $lesson_slug,
            'text'          => clean_html($request->description),
            'item_type'     => 'lecture',
            'status'        => 1,
            'sort_order'   => $sort_order,
            'is_preview'    => $request->is_preview,
        ];

        $lecture = CompanySeminarContent::create($data);
        $lecture->save_and_sync();

        return ['success' => true, 'item_id' => $lecture->id];
    }

    public function updateLecture(Request $request, $seminar_id, $item_id){
        $rules = [
            'title' => 'required'
        ];
        $validation = Validator::make($request->input(), $rules);

        if ($validation->fails()){
            $errors = $validation->errors()->toArray();
            $error_msg = "<div class='alert alert-danger mb-3'>";
            foreach ($errors as $error){
                $error_msg .= "<p class='m-0'>{$error[0]}</p>";
            }
            $error_msg .= "</div>";
            return ['success' => false, 'error_msg' => $error_msg];
        }

        $user_id = Auth::user()->id;

        $lesson_slug = unique_slug($request->title, 'Content', $item_id);
        $data = [
            'title'         => clean_html($request->title),
            'slug'          => $lesson_slug,
            'text'          => clean_html($request->description),
            'is_preview'    => clean_html($request->is_preview),
        ];

        $video_source = $request->input('video.source');
        if ($video_source === '-1'){
            $data['video_src'] = null;
        }else{
            $data['video_src'] = json_encode($request->video);
        }

        $item = CompanySeminarContent::find($item_id);
        $item->save_and_sync($data);

        return ['success' => true];
    }

    public function editItem(Request $request){
        $item_id = $request->item_id;
        $item = CompanySeminarContent::find($item_id);
        
        $form_html = '';

        if ($item->item_type === 'lecture'){
            $form_html = view_seminar_template_part( 'company.instructor.seminar.lecture.edit_lecture_form', compact('item'));
        }elseif ($item->item_type === 'quiz'){
            $form_html = view_seminar_template_part( 'company.instructor.seminar..quiz.edit_quiz', compact('item'));
        }

        return ['success' => true, 'form_html' => $form_html];
    }

    public function deleteItem(Request $request){
        $item_id = $request->item_id;
        CompanySeminarContent::destroy($item_id);
        return ['success' => true];
    }

    public function publish($seminar_id){
        $title = __t('publish seminar');
        $seminar  = CompanySeminar::find($seminar_id);
        return view('company.instructor.seminar.publish', compact('title', 'seminar'));
    }

    public function publishPost(Request $request, $seminar_id){
        $seminar  = CompanySeminar::find($seminar_id);
        if ($request->publish_btn == 'publish'){
            $seminar->status = 1;
        }elseif ($request->publish_btn == 'unpublish'){
            $seminar->status = 0;
        }
        $seminar->save();

        return back();
    }

    public function mySeminar(){
        $title = __t('My Seminar');
        $user_id        = Auth::user()->id;
        $company_id     = Auth::user()->company_id;
        $instructor      = CompanyInstructor::where('user_id',$user_id)->first();
        $seminars  = CompanySeminar::whereIn('department_id',explode(",",$instructor->department_id))->where('company_id',$company_id)->get();
        return view('company.instructor.seminar.my_seminar', compact('title','seminars'));
    }

    public function loadContents(Request $request){
        $section = CompanySeminarSection::find($request->section_id);

        $html = view_template_part('dashboard.courses.section-items', compact('section'));

        return ['success' => true, 'html' => $html];
    }

    public function contentComplete($content_id){
        $content = CompanySeminarContent::find($content_id);
        $user = Auth::user();

        complete_seminar_content($content, $user);

        $go_content = $content->next($content->id,$content->seminar_id);
        if ( ! $go_content){
            $go_content = $content;
        }
        // dd($content->next($content->id,$content->seminar_id));
        return redirect(route('seminar_single_'.$go_content->item_type, [$go_content->seminar->slug, $go_content->id ] ));
    }

    public function complete(Request $request, $course_id){
        $user = Auth::user();
        $user->complete_seminar($course_id);

        return back();
    }

    public function employeeCompletedSeminar(Request $request, $employee_id=null,$from_date = null, $to_date = null){
        
        if ($employee_id == null ) 
        {
            abort(404);
        }
        $from_date = $from_date;
        $to_date = $to_date;
        if ($from_date == null) {
            $from_date = date("Y-m-d", strtotime("-1 months"));
        }
        if ($to_date == null) {
            $to_date = date("Y-m-d");
        }

        $employee_details = User::where('id',$employee_id)->first();

        $completed_seminars = CompanySeminarComplete::where('user_id',$employee_id)
                                                ->whereNotNull('completed_seminar_id')
                                                ->whereRaw('date(completed_at) >= ?',[$from_date])
                                                ->whereRaw('date(completed_at) <= ?',[$to_date])
                                                ->get();
        return view('company.admin.seminar.completed_seminar',compact('completed_seminars','employee_details','from_date','to_date'));
    }

    public function employeePendingSeminar(Request $request, $employee_id=null,$from_date = null, $to_date = null){
        
        if ($employee_id == null ) 
        {
            abort(404);
        }
        $from_date = $from_date;
        $to_date = $to_date;
        if ($from_date == null) {
            $from_date = date("Y-m-d", strtotime("-1 months"));
        }
        if ($to_date == null) {
            $to_date = date("Y-m-d");
        }

        $employee_details = User::where('id',$employee_id)->first();

        $attempted_seminars = CompanySeminarEnroll::where('user_id',$employee_id)
                                                ->whereRaw('date(enrolled_at) >= ?',[$from_date])
                                                ->whereRaw('date(enrolled_at) <= ?',[$to_date])
                                                ->get();
        return view('company.admin.seminar.pending_seminar',compact('attempted_seminars','employee_details','from_date','to_date'));
    }


}