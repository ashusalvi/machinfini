<?php

namespace App\Http\Controllers\company\instructor\seminar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Model\CompanySeminar;
use App\Model\CompanyInstructor;
use App\Model\CompanySeminarSection;
use App\Model\CompanySeminarContent;
use App\Model\CompanySeminarQuestion;
use App\Model\CompanySeminarQuestionOption;
use App\Model\CompanyQuiz;
use App\Model\CompanyAnswer;
use App\Model\CompanyAttempt;

class QuizController extends Controller
{   

    public function start(Request $request){
        $user = Auth::user();

        $quiz_id = $request->quiz_id;
        $previous_attempt = $user->get_seminar_attempt($quiz_id);

        if ( ! $previous_attempt) {
            $quiz = CompanyQuiz::find($quiz_id);
            $passing_percent = (int) $quiz->option('passing_score');

            $data = [
                'seminar_id' => $quiz->seminar_id,
                'quiz_id' => $quiz_id,
                'user_id' => $user->id,
                'questions_limit' => $quiz->option('questions_limit'),
                'status' => 'started',
                'quiz_gradable' => $quiz->quiz_gradable,
                'passing_percent' =>  $passing_percent,
            ];

            CompanyAttempt::create($data);
            session()->forget('current_question');
        }
        return ['success' => 1, 'quiz_url' => route('seminar_quiz_attempt_url', $quiz_id)];
    }

    public function quizAttempting($quiz_id){
        $quiz = CompanyQuiz::find($quiz_id);
        if ( ! $quiz){
            abort(404);
        }
        
        $user = Auth::user();
        $attempt = $user->get_seminar_attempt($quiz_id);
        if ( ! $attempt || $attempt->status !== 'started') {
            abort(404);
        }

        // $isEnrolled = $user->isSeminarEnrolled($quiz->course_id);
        // dd($isEnrolled);
        // if ( ! $isEnrolled){
        //     abort(404);
        // }


        /**
         * Finished The attempt if answered equal to question limit
         */
        $answered = CompanyAnswer::whereQuizId($quiz_id)->whereUserId($user->id)->get();
        
        $question_count = $quiz->questions()->count();
        $question_limits = $question_count > $attempt->questions_limit ? $attempt->questions_limit :  $question_count;
        
        if ($answered->count() >= $question_limits){
            //Finished Quiz

            $reviewRequired = CompanyAnswer::query()->where('quiz_id',$quiz_id)->where(function($q){
                $q->where('q_type', 'text')->orWhere('q_type', 'textarea');
            })->count();
            
            $q_score = $attempt->answers->sum('q_score');
            $attempt->total_answered = $attempt->answers->count();
            $attempt->total_scores = $q_score;
            
            if ($reviewRequired){
                $attempt->status = 'in_review';
            }else{
                $attempt->status = 'finished';
            }
            $attempt->ended_at = Carbon::now()->toDateTimeString();
            $attempt->save_and_sync();

            return redirect($quiz->url);
        }
        $q_number = $answered->count() +1;
        $title = $quiz->title;
        $answered_q_ids = $answered->pluck('question_id')->toArray();

        $current_q_id = session('current_question');
        if ($current_q_id){
            $question = CompanySeminarQuestion::find($current_q_id);
        }else{
            $question = $quiz->questions()->whereNotIn('id', $answered_q_ids)->inRandomOrder()->first();
        }

        session(['current_question' => $question->id]);
        // dd($question);
        return view('company.seminar-view.quiz_attempt', compact( 'title', 'quiz', 'attempt', 'question', 'answered', 'q_number'));
    }

    public function answerSubmit(Request $request, $quiz_id){
        $user = Auth::user();
        if (is_array($request->questions) && count($request->questions)){
            $attempt = $user->get_seminar_attempt($quiz_id);
            
            foreach ($request->questions as $question_id => $answer) {
                $question = CompanySeminarQuestion::find($question_id);
                $answer = is_string($answer) ? $answer : json_encode($answer);

                $is_correct = 0;
                $r_score = 0;

                if ($question->type === 'radio'){
                    $option = CompanySeminarQuestionOption::whereQuestionId($question_id)->whereIsCorrect(1)->first();
                    if ($option && $option->id == $answer){
                        $is_correct = 1;
                        $r_score = $question->score;
                    }
                }elseif ($question->type === 'checkbox'){
                    $options = CompanySeminarQuestionOption::whereQuestionId($question_id)->whereIsCorrect(1)->pluck('id')->toArray();
                    if ( ! count(array_diff($options, json_decode($answer, true)))){
                        $is_correct = 1;
                        $r_score = $question->score;
                    }
                }

                $answerData = [
                    'quiz_id'       => $quiz_id,
                    'question_id'   => $question_id,
                    'user_id'       => $user->id,
                    'attempt_id'    => $attempt->id,
                    'answer'        => $answer,
                    'q_type'        => $question->type,
                    'q_score'       => $question->score,
                    'r_score'       => $r_score,
                    'is_correct'    => $is_correct,
                ];
                CompanyAnswer::create($answerData);
                session()->forget('current_question');
            }
        }

        return ['success' => 1, 'quiz_url' => route('seminar_quiz_attempt_url', $quiz_id)];
    }

    public function reattendQuiz(Request $request,$quiz_id){
        
        $attempt = CompanyAttempt::where('quiz_id',$quiz_id)->where('user_id',Auth::user()->id)->delete();

        $answer = CompanyAnswer::where('quiz_id',$quiz_id)->where('user_id',Auth::user()->id)->delete();
        return redirect()->back();
    }

    public function newQuiz(Request $request, $seminar_id){
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
            'item_type'     => 'quiz',
            'status'        => 1,
            'sort_order'   => $sort_order,
        ];

        $lecture = CompanySeminarContent::create($data);
        $lecture->save_and_sync();

        return ['success' => true, 'item_id' => $lecture->id];
    }

    public function updateQuiz(Request $request, $seminar_id, $item_id){
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
            'options'       => json_encode($request->quiz_option),
            'quiz_gradable' => $request->quiz_gradable,
        ];

        $item = CompanySeminarContent::find($item_id);
        $item->save_and_sync($data);

        return ['success' => true];
    }

    public function createQuestion(Request $request, $course_id, $quiz_id){
        $validation = Validator::make($request->input(), ['question_title' => 'required']);

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
            'user_id'       => $user->id,
            'quiz_id'       => $quiz_id,
            'title'         => clean_html($request->question_title),
            'image_id'      => $request->image_id,
            'type'          => $request->question_type,
            'score'         => $request->score,
            'sort_order'   => $sort_order,
        ];

        $question = CompanySeminarQuestion::create($questionData);

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
                    CompanySeminarQuestionOption::create($optionData);
                }
            }
        }
        return ['success' => true, 'quiz_id' => $quiz_id];
    }
    
    public function updateQuestion(Request $request){
        $validation = Validator::make($request->input(), ['question_title' => 'required']);

        if ($validation->fails()){
            $errors = $validation->errors()->toArray();
            $error_msg = "<div class='alert alert-danger mb-3'>";
            foreach ($errors as $error){
                $error_msg .= "<p class='m-0'>{$error[0]}</p>";
            }
            $error_msg .= "</div>";
            return ['success' => false, 'error_msg' => $error_msg];
        }

        $question_id = $request->question_id;

        $questionData = [
            'title' => clean_html($request->question_title),
            'image_id' => $request->image_id,
            'score' => $request->score,
        ];

        CompanySeminarQuestion::whereId($question_id)->update($questionData);

        if (is_array($request->options) && count($request->options)) {
            $options = array_except($request->options, '{index}');

            $sort = 0;
            foreach ($options as $option) {
                $sort++;

                $option_id = array_get($option, 'option_id');
                $optionData = [
                    'question_id' => $question_id,
                    'title' => array_get($option, 'title'),
                    'image_id' => array_get($option, 'image_id'),
                    'd_pref' => array_get($option, 'd_pref'),
                    'is_correct' => (int)array_get($option, 'is_correct'),
                    'sort_order' => $sort,
                ];
                if ($option_id) {
                    CompanySeminarQuestionOption::whereId($option_id)->update($optionData);
                } else {
                    CompanySeminarQuestionOption::create($optionData);
                }
            }
        }
        $question = CompanySeminarQuestion::find($request->question_id);

        return ['success' => true, 'quiz_id' => $question->quiz_id];
    }

    public function next_question_sort_id($quiz_id){
        $sort = (int) DB::table('company_seminar_questions')->where('quiz_id', $quiz_id)->max('sort_order');
        return $sort +1;
    }

    public function next_question_option_sort_id($question_id){
        $sort = (int) DB::table('company_seminar_question_options')->where('question_id', $question_id)->max('sort_order');
        return $sort +1;
    }

    public function editQuestion(Request $request){
        $question = CompanySeminarQuestion::find($request->question_id);
        $html = view_seminar_template_part('company.instructor.seminar.quiz.edit_question', compact('question'));
        return ['success' => 1, 'html' => $html];
    }

    public function loadQuestions(Request $request){
        $quiz = CompanySeminarContent::find($request->quiz_id);
        $html = view_template_part( 'dashboard.courses.quiz.questions', compact('quiz'));
        return ['success' => 1, 'html' => $html];
    }

    // quiz section
    public function quizView($slug, $quiz_id){
        $quiz = CompanySeminarContent::find($quiz_id);
        $course = $quiz->course;
        $title = $quiz->title;

        $isEnrolled = false;
        if (Auth::check()){
            $user = Auth::user();
            $isEnrolled = $user->isSeminarEnrolled($course->id);
        }

        return view('company.seminar-view.quiz', compact('course', 'title', 'isEnrolled', 'quiz'));
    }

    public function deleteQuestion(Request $request){
        $question = CompanySeminarQuestion::find($request->question_id);
        $question->delete_sync();
    }
}