<?php
namespace App\Http\Controllers;
session_start();

use Illuminate\Http\Request;
use App\CollegeQuiz;
use App\CollegeQuizQuestion;
use App\CollegeQuizQuestionOption;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use App\User;
use App\collage;
use App\AttemptCollegeQuiz;
use App\AttemptCollegeQuizAns;
use App\CollegeQuizAuthUser;
use App\Content;

class CollegeQuizController extends Controller
{
    public function create()
    {   
        $subjects = []; 
        $standard = [];
        $division = [];
        $batch    = [];
        if (Auth::user()->subject != '') {
            $subjects = explode(",",Auth::user()->subject);
        }
        if (Auth::user()->standard != '') {
            $standard = explode(",",Auth::user()->standard);
        }
        if (Auth::user()->division != '') {
            $division = explode(",",Auth::user()->division);
        }
        if(Auth::user()->batch != ''){
            $batch = explode(",",Auth::user()->batch);
        }

    	return view(theme('dashboard.college_quiz.create'),compact('subjects','standard','division','batch'));
    }

    public function edit($id)
    {   
        $collegeQuiz = CollegeQuiz::where('id',$id)->first();
        
        $subjects = [];
        $standard = [];
        $division = [];
        $batch = [];
        if (Auth::user()->subject != '') {
            $subjects = explode(",",Auth::user()->subject);
        }
        if (Auth::user()->standard != '') {
            $standard = explode(",",Auth::user()->standard);
        }
        if (Auth::user()->division != '') {
            $division = explode(",",Auth::user()->division);
        }
        if(Auth::user()->batch != ''){
            $batch = explode(",",Auth::user()->batch);
        }

        return view(theme('dashboard.college_quiz.edit_create'),compact('subjects','standard','division','batch','collegeQuiz'));
    }

    public function saveQuiz(Request $request)
    {   
        // dd($request);
        $from_time = $request->date . ' '.$request->from_time;
        $to_time = $request->date . ' '.$request->to_time;
        $user_id = Auth::user()->id;
    	$id = CollegeQuiz::insertGetId(['title' => $request->title, 'description' => $request->description,'user_id'
    	=>$user_id, 'passing_per' => $request->score,'total_score' => $request->total_score,'date' => $request->date,
    	'batch' => $request->batch ,
    	'division' => $request->div , 'subject' => $request->subject, 'standard' => $request->standard,'from_time'
    	=>$from_time, 'to_time' =>$to_time ]);

    	return redirect()->route('addQuestion', ['id' => $id]);
    }

    public function editQuiz(Request $request)
    {
        $from_time = $request->date . ' '.$request->from_time;
        $to_time = $request->date . ' '.$request->to_time;
        $user_id = Auth::user()->id;
        $update_query = CollegeQuiz::where('id',$request->quiz_id)
                                    ->update(
                                        [
                                            'title' => $request->title,
                                            'description' => $request->description,
                                            'user_id' =>$user_id, 
                                            'passing_per' =>$request->score,
                                            'total_score' => $request->total_score,
                                            'date' => $request->date,
                                            'batch' => $request->batch ,
                                            'division' => $request->div , 
                                            'subject' => $request->subject, 
                                            'standard' => $request->standard,
                                            'from_time' =>$from_time,
                                            'to_time' =>$to_time 
                                        ]);

        return redirect()->route('addQuestion', ['id' => $request->quiz_id]);
    }

    public function addQuestion($id)
    {
        $item = CollegeQuiz::find($id);
        $encryptString = Crypt::encryptString($id);
    	return view(theme('dashboard.college_quiz.addQuestion'), compact('item','encryptString'));
    }

    public function editQuestion(Request $request){
        $question = CollegeQuizQuestion::find($request->question_id);
        // dd($question);
        $html = view_template_part( 'dashboard.college_quiz.editQuestion', compact('question'));
        return ['success' => 1, 'html' => $html];
    }

    public function addAuthUsers($id)
    {   
        $students = CollegeQuizAuthUser::where('quiz_id',$id)->groupBy('email')->get();
        return view(theme('dashboard.college_quiz.add_auth_user'), compact('id','students'));
    }

    public function loadCollegeQuizQuestions(Request $request){
        $id = $request->quiz_id;
        return redirect()->route('addQuestion', ['id' => $id]);
    }

    public function uploadCSV(Request $request , $id)
    {   
        $rules = [
            'file' => 'required|max:255',
        ];
        $this->validate($request, $rules);
        
        $file = $_FILES['file']['tmp_name'];
        $handle = fopen($file, "r");
        $c = 0;
        $quiz_id = $id;
        while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
        {   
            if($c != 0){
                $roll_id = $filesop[0];
                $name = $filesop[1];
                $email = $filesop[2];

                $CollegeQuizAuthUser = CollegeQuizAuthUser::where('email',$email)->where('quiz_id',$quiz_id)->count();
                
                if ($CollegeQuizAuthUser == 0) {
                    $CollegeQuizAuthUser = CollegeQuizAuthUser::insert(array(
                        'roll_no' => $roll_id,
                        'name' => $name,
                        'email' => $email,
                        'quiz_id' => $quiz_id
                    ));
                }
                
            }
            $c = $c + 1;
        }

        return redirect()->route('addAuthUsers', ['id' => $id]);
    }

    public function viewQuizQuestion($id)
    {   
        $quiz = CollegeQuiz::find($id);
        $quiz_attemp_user = AttemptCollegeQuiz::where('quiz_id',$id)->get();
        $quiz_quetion = CollegeQuizQuestion::where('quiz_id',$id)->get();
        return view(theme('dashboard.college_quiz.viewattemptquiz'),compact('quiz','quiz_attemp_user','quiz_quetion'));

    } 

    public function publishCollegeQuiz(Request $request)
    {
       $quiz = CollegeQuiz::where('id',$request->id)->update(['publish'=> 1]);
    }

    public function viewSubmitedQuiz($id,$quiz_id)
    {   
        $quiz = CollegeQuiz::find($quiz_id);
        $quiz_question = CollegeQuizQuestion::where('quiz_id',$quiz_id)->get();
        $quiz_section = $quiz_question->map(function ($query){
            $query->quizOption;
        });
        $quiz_attemp_user = AttemptCollegeQuiz::where('id',$id)->first();
        $attempt_college_quiz_ans = AttemptCollegeQuizAns::where('acq_user',$id)->get();

         $quiz_section = $attempt_college_quiz_ans->map(function ($query){
            $query->quizTitle;
         });
        return
        view(theme('dashboard.college_quiz.viewsubmitquiz'),compact('quiz','quiz_attemp_user','quiz_section','attempt_college_quiz_ans','id','quiz_id'));
    }

    public function viewSubmitedQuizStudent($email,$quiz_id)
    {
        $quiz = CollegeQuiz::find($quiz_id);
        $quiz_question = CollegeQuizQuestion::where('quiz_id',$quiz_id)->get();
        $quiz_section = $quiz_question->map(function ($query){
        $query->quizOption;
        });
        $quiz_attemp_user = AttemptCollegeQuiz::where('email',$email)->where('quiz_id',$quiz_id)->first();
  
        $attempt_college_quiz_ans = AttemptCollegeQuizAns::where('acq_user',$quiz_attemp_user->id)->get();

        $quiz_section = $attempt_college_quiz_ans->map(function ($query){
            $query->quizTitle;
        });


        return
        view(theme('dashboard.college_quiz.viewsubmitquiz_student'),compact('quiz','quiz_attemp_user','quiz_section','attempt_college_quiz_ans'));
    }

    

    public function addCollegeQuestion(Request $request)
    {   
        $quiz_id = $request->quizId;
        $validation = Validator::make($request->input(), ['question_title' => 'required', 'score' => 'required']);

        if ($validation->fails()){
            
            $errors = $validation->errors()->toArray();
            $error_msg = "<div class='alert alert-danger mb-3'>";
            foreach ($errors as $error){
                $error_msg .= "<p class='m-0'>{$error[0]}</p>";
            }
            $error_msg .= "</div>";
            return ['success' => false, 'error_msg' => $error_msg];
            
        }

        $user = Auth::user();
        
        $sort_order = $this->next_question_sort_id($quiz_id);

        $questionData = [
            'user_id' => $user->id,
            'quiz_id' => $quiz_id,
            'title' => clean_html($request->question_title),
            'type' => $request->question_type,
            'score' => $request->score,
            'sort_order' => $sort_order,
        ];

        $question = CollegeQuizQuestion::create($questionData);

        if (is_array($request->options) && count($request->options)) {
            $options = array_except($request->options, '{index}');
            $sort = 0;
            foreach ($options as $option) {
                $sort++;

                if ($sort) {
                    $optionData = [
                        'question_id' => $question->id,
                        'title' => array_get($option, 'title'),
                        'image_id' => array_get($option, 'image_id'),
                        'd_pref' => array_get($option, 'd_pref'),
                        'is_correct' => (int)array_get($option, 'is_correct'),
                        'sort_order' => $sort,
                    ];
                    CollegeQuizQuestionOption::create($optionData);
                }
            }
        }

        return ['success' => true, 'quiz_id' => $quiz_id];
    }

    public function updateCollegeQuestion(Request $request)
    {   
        $CollegeQuizquestion = CollegeQuizQuestion::where('id',$request->question_id)->first(); 
      
        $quiz_id = $CollegeQuizquestion->quiz_id;
        
        $validation = Validator::make($request->input(), ['question_title' => 'required', 'score' => 'required']);

        if ($validation->fails()){
            
            $errors = $validation->errors()->toArray();
            $error_msg = "<div class='alert alert-danger mb-3'>";
            foreach ($errors as $error){
                $error_msg .= "<p class='m-0'>{$error[0]}</p>";
            }
            $error_msg .= "</div>";
            return ['success' => false, 'error_msg' => $error_msg];
            
        }

        $user = Auth::user();
        
        $sort_order = $this->next_question_sort_id($quiz_id);

        $questionData = [
            'user_id' => $user->id,
            'quiz_id' => $quiz_id,
            'title' => clean_html($request->question_title),
            'type' => $request->question_type,
            'score' => $request->score,
            'sort_order' => $sort_order,
        ];

        $question = CollegeQuizQuestion::where('id',$request->question_id)->update($questionData);

        if (is_array($request->options) && count($request->options)) {
            $options = array_except($request->options, '{index}');
            $sort = 0;
            foreach ($options as $option) {
                $sort++;
                if ($sort) {
                    $optionData = [
                        'question_id' => $request->question_id,
                        'title' => array_get($option, 'title'),
                        'is_correct' => (int)array_get($option, 'is_correct'),
                    ];
                    CollegeQuizQuestionOption::where('id',array_get($option, 'option_id'))->update($optionData);
                }
            }
        }

        return ['success' => true, 'quiz_id' => $quiz_id];
    }

    public function next_question_sort_id($quiz_id){
        $sort = (int) DB::table('questions')->where('quiz_id', $quiz_id)->max('sort_order');
        return $sort +1;
    }

    public function getQuiz(){
        $title = __t('My College Quiz');
        $user_id = Auth::user()->id;

        $my_quiz = CollegeQuiz::where('user_id',$user_id)->get();

        return view(theme('dashboard.my_collage_quiz'), compact('title','my_quiz'));
    }

    public function deleteQuestion(Request $request){
        // dd($request->question_id);
        $question = CollegeQuizQuestion::where('id',$request->question_id)->delete();
        // dd($question);
    }

    public function deleteOption(Request $request){
        QuestionOption::whereId($request->option_id)->delete();
    }

    public function collegeQuizViewSection(Request $request){

        $id = Crypt::decryptString($request->encryptString);
        
        // quiz data
        $quiz = CollegeQuiz::where('id',$id)->first();
        $from_time = date("Y-m-d H:i");
        $to_time = date('Y-m-d H:i');
        if ($quiz->from_time != NULL && $quiz->to_time != NULL) {
            $from_time = date('Y-m-d H:i',strtotime($quiz->from_time));
            $to_time = date('Y-m-d H:i',strtotime($quiz->to_time));
        }

        $date_quiz = 0;
        if (strtotime($from_time) <= strtotime(date("Y-m-d H:i")) && strtotime($to_time)>= strtotime(date("Y-m-d H:i")))
            {
            $date_quiz = 1;
        }
        // $hours = intdiv($quiz->time, 60).':'. ($quiz->time % 60);
        $hours = 0;

        // collage instructor 
        $user = User::where('id',$quiz->user_id)->first();

        // get college details
        $college = collage::where('id',$user->college_id)->first();

        // get quiz quetion
        $quiz_question = CollegeQuizQuestion::where('quiz_id',$id)->get();

        $quiz_section = $quiz_question->map(function ($query){
            $query->quizOption;
        });

        $quiz_start = 0;
        $quiz_submit = 0;
        $quiz_over = 0;
        $quiz_time_remaning = 0;
        $get_attempt_que = 0;

        if (isset($_SESSION['start_quiz'.$id])) {
            // $_SESSION['start_quiz_name'] = $request->name;
            // $_SESSION['start_quiz_roll_no'] = $request->roll_no;
            // $_SESSION['inserted_id'] = $result->id;
            $quiz_start = 1;

            // get attempt data
            $attempt_user = AttemptCollegeQuiz::where('id',$_SESSION['inserted_id'])->first();
            $quiz_time_remaning = $quiz->time * 60;
            

            //get attempt quistion
            $get_attempt_que = AttemptCollegeQuizAns::where('acq_user',$_SESSION['inserted_id'])->get();


        }

        $get_score = 0;
        $attemp_question = 0;

        if(isset($_SESSION['submit_quiz'.$id])){
            $quiz_submit = 1;
            $get_score = $_SESSION['total_score'];
            $attemp_question = $_SESSION['attemp_question'];
        }
        
        return
        view(theme('college-quiz.college-quiz-submit'),compact('quiz','quiz_question','college','user','hours','quiz_start','quiz_over','quiz_time_remaning','get_attempt_que','get_score','attemp_question','quiz_submit','date_quiz'));

    }

    public function collegeQuizStart(Request $request){

        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|max:255',
            'roll_no' => 'required|max:50'
        ];

        $this->validate($request, $rules);

        date_default_timezone_set('Asia/Kolkata');
        $date = date('Y-m-d');
        $time = date('H:i');

        $result = AttemptCollegeQuiz::create([
            'name' => $request->name,
            'email' => $request->email,
            'roll_no' => $request->roll_no,
            'quiz_id' => $request->quiz_id,
            'date' => $date,
            'start_time' => $time
        ]);

        if(!empty($result))
        {   
            $_SESSION['start_quiz'.$request->quiz_id] = 1;
            $_SESSION['start_quiz_name'] = $request->name;
            $_SESSION['start_quiz_roll_no'] = $request->roll_no;
            $_SESSION['inserted_id'] = $result->id;
            $encryptString = Crypt::encryptString($request->quiz_id);
            return redirect()->route('collageQuizSubmit',$encryptString);
        }
        else
        {
            return redirect()->back();
        }

    }

    public function collegeQuizSubmit(Request $request){
        $user_id = $request->user_id;
        $quiz_id = $request->quiz_id;
        $attemp_question = 0;
        $total_score = 0;
        
        foreach ($request->quiz_que_no as $value) {
            $question_id = $value;
            $que_type = 'que_type_'.$question_id;
            $correct_ans_no = 'correct_ans_no_'.$question_id;
            $que_ans = 'que_ans_'.$question_id;
            $quiz_score = 'quiz_score_'.$question_id;

            if(isset($request->$que_ans)){

                $attemp_question ++;

                if($request->$que_type == 'radio'){

                    $submited_ans = json_encode([$request->$que_ans]);
                    $submited_correct_ans = json_encode([$request->$correct_ans_no]);
                    if($request->$que_ans == $request->$correct_ans_no){
                        $score = $request->$quiz_score;
                    }else{
                        $score = 0;
                    }

                    $result = AttemptCollegeQuizAns::insert([
                        'acq_user' => $user_id,
                        'quiz_id' => $quiz_id,
                        'question_id' => $question_id,
                        'submit_option_id' => $submited_ans,
                        'correct_option_id' => $submited_correct_ans,
                        'score' => $score,
                    ]);

                }else if($request->$que_type == 'checkbox'){
                    $submited_ans = json_encode($request->$que_ans);
                    $submited_correct_ans = json_encode($request->$correct_ans_no);
                    if($request->$que_ans == $request->$correct_ans_no){
                        $score = $request->$quiz_score;
                    }else{
                        $score = 0;
                    }

                    $result = AttemptCollegeQuizAns::insert([
                        'acq_user' => $user_id,
                        'quiz_id' => $quiz_id,
                        'question_id' => $question_id,
                        'submit_option_id' => $submited_ans,
                        'correct_option_id' => $submited_correct_ans,
                        'score' => $score,
                    ]);
                }else if($request->$que_type == 'text'){
                    $result = AttemptCollegeQuizAns::insert([
                        'acq_user' => $user_id,
                        'quiz_id' => $quiz_id,
                        'question_id' => $question_id,
                        'submit_option_id' => $request->$que_ans,
                    ]);
                    
                }

                $total_score = $total_score + $score;

            }
            
        }
        $_SESSION['total_score'] = $total_score;
        $_SESSION['attemp_question'] = $attemp_question;
        $_SESSION['submit_quiz'.$quiz_id] = 1;
        $encryptString = Crypt::encryptString($request->quiz_id);
        return redirect()->route('collageQuizSubmit',$encryptString);
    }

    public function myCollegeQuiz(Request $request){
        $quizInvites = CollegeQuizAuthUser::where('email',auth::user()->email)->orderBy('id', 'DESC')->get();
        $quizInvitesData = $quizInvites->map(function ($query){
            $attentend_user =
            AttemptCollegeQuiz::where('email',$query->email)->where('quiz_id',$query->quiz_id)->first();
            $query->submited = 0;
            if(isset($attentend_user) == true){
                $query->submited = $this->checkSubmitQuiz($attentend_user->id,$query->quiz_id);
            }
            $query->quiz;
            $query->quizAns;
            $query->attempt = 1;
            if ($attentend_user == null) {
                $query->attempt = 0;
            }
        });
        return view(theme('dashboard.college_quiz.auth_user_college_quiz'),compact('quizInvites'));
    }

    public function authCollegeQuizViewSection(Request $request){
        
    
        $id = Crypt::decryptString($request->encryptString);

        $attentend_user = AttemptCollegeQuiz::where('email',Auth::user()->email)->where('quiz_id',$id)->first();

        if(isset($attentend_user) == true){
            $_SESSION['start_quiz'.$id] = 1;
            $_SESSION['inserted_id'] = $attentend_user->id;
            $_SESSION['start_quiz_name'] = $attentend_user->name;
            $_SESSION['start_quiz_roll_no'] = $attentend_user->roll_no;
            $this->checkSubmitQuiz($attentend_user->id,$id);
        }else{
            unset($_SESSION);
        }

        // quiz data
        $quiz = CollegeQuiz::where('id',$id)->first();
        $date_quiz = 0;
        $from_time = date("Y-m-d H:i");
        $to_time = date('Y-m-d H:i');
        if ($quiz->from_time != NULL && $quiz->to_time != NULL) {
            $from_time = date('Y-m-d H:i',strtotime($quiz->from_time));
            $to_time = date('Y-m-d H:i',strtotime($quiz->to_time));
        }

        $date_quiz = 0;
    
        if (strtotime($from_time) <= strtotime(date("Y-m-d H:i")) && strtotime($to_time) >= strtotime(date("Y-m-d H:i")))
        {   
            $date_quiz = 1;
        }
        
        $hours = 0;

        // collage instructor
        $user = User::where('id',$quiz->user_id)->first();

        // get college details
        $college = collage::where('id',$user->college_id)->first();

        // get quiz quetion
        $quiz_question = CollegeQuizQuestion::where('quiz_id',$id)->get();

        $quiz_section = $quiz_question->map(function ($query){
            $query->quizOption;
        });

        $quiz_start = 0;
        $quiz_submit = 0;
        $quiz_over = 0;
        $quiz_time_remaning = 0;
        $get_attempt_que = 0;

        if (isset($_SESSION['start_quiz'.$id])) {
            $quiz_start = 1;

            // get attempt data
            $attempt_user = AttemptCollegeQuiz::where('id',$_SESSION['inserted_id'])->first();
            $quiz_time_remaning = $quiz->time * 60;


            //get attempt quistion
            $get_attempt_que = AttemptCollegeQuizAns::where('acq_user',$_SESSION['inserted_id'])->get();
        }

        $get_score = 0;
        $attemp_question = 0;

        if(isset($_SESSION['submit_quiz'.$id])){
            $quiz_submit = 1;
            $get_score = $_SESSION['total_score'];
            $attemp_question = $_SESSION['attemp_question'];
        }

        return
        view(theme('college-quiz.college-quiz-submit-auth'),compact('quiz','quiz_question','college','user','hours','quiz_start','quiz_over','quiz_time_remaning','get_attempt_que','get_score','attemp_question','quiz_submit','date_quiz'));

    }

    static public function checkSubmitQuiz($user_id, $id)
    {   
        $getSubmitedData = AttemptCollegeQuizAns::where('acq_user',$user_id)->where('quiz_id',$id)->get();
       
        
        if(count($getSubmitedData) > 0){
            $attemp_question = 0;
            $total_score = 0;
            foreach ($getSubmitedData as $row) {
                $attemp_question ++;
                $total_score = $total_score + $row->score;
            }
            $_SESSION['total_score'] = $total_score;
            $_SESSION['attemp_question'] = $attemp_question;
            $_SESSION['submit_quiz'.$id] = 1;
            return 1;
        }
        return 0;
    }

    public function correctMarkCollegeQuiz(Request $request){
        echo $result = AttemptCollegeQuizAns::where('id',$request->id)->update(['score' =>
        $request->value,'mark_by_lecturer' => 1]);
    }

    public function incorrectMarkCollegeQuiz(Request $request)
    {
        echo $result = AttemptCollegeQuizAns::where('id',$request->id)->update(['score' =>
        $request->value,'mark_by_lecturer' => 1]);
    }

    public function commentMarkCollegeQuiz(Request $request)
    {
        echo $result = AttemptCollegeQuizAns::where('id',$request->id)->update(['comment' => $request->value]);
    }
}