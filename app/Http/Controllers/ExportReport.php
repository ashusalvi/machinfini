<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CollegeQuiz;
use App\CollegeQuizQuestion;
use App\CollegeQuizQuestionOption;
use App\collage;
use App\AttemptCollegeQuiz;
use App\AttemptCollegeQuizAns;
use App\CollegeQuizAuthUser;
use App\Content;
use Response;

class ExportReport extends Controller
{
    public function exportStudentReport(Request $request,$id)
    {     
        $quiz = CollegeQuiz::find($id);
        $quiz_attemp_user = AttemptCollegeQuiz::where('quiz_id',$id)->get();
        $quiz_question = CollegeQuizQuestion::where('quiz_id',$id)->get();

         $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=file.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
         );

         // $reviews = Reviews::getReviewExport($this->hw->healthwatchID)->get();
         $columns = array('Quiz Name','Discription','Date','Time','Division','Subject','Standard','Batch','Passing Score','Roll no','Student Name', 'Attempt Question', 'Score', 'Result');

         $callback = function() use ($quiz,$quiz_attemp_user,$quiz_question,$columns)
         {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach($quiz_attemp_user as $quser){
                $score = 0;
                foreach($quser->ans as $ans){
                    $score = $ans->score + $score; 
                }

                if($score >= $quiz->passing_per){
                    $result = 'Passed';
                }else{
                    $result = 'Faild';
                }

                $mark = count($quser->ans).'out of'.count($quiz_question);
                $time = date('g:i a', strtotime($quiz->from_time)) .'To'.date('g:i a', strtotime($quiz->to_time));

                fputcsv($file,
                array($quiz->title,$quiz->description,$quiz->date,$time,$quiz->division,$quiz->subject,$quiz->standard,$quiz->batch,$quiz->passing_per,$quser->roll_no,$quser->name,$quser->date,$mark,$score,$result));
            }
            fclose($file);
         };
         return Response::stream($callback, 200, $headers);
    }

    public function exportPerticularStudentReport(Request $request,$id,$quiz_id)
    {     
        $quiz = CollegeQuiz::find($quiz_id);
        $quiz_question = CollegeQuizQuestion::where('quiz_id',$quiz_id)->get();
        $quiz_section = $quiz_question->map(function ($query){
            $query->quizOption;
        });
        $quiz_attemp_user = AttemptCollegeQuiz::where('id',$id)->first();
        $attempt_college_quiz_ans = AttemptCollegeQuizAns::where('acq_user',$id)->get();
        // dd($attempt_college_quiz_ans);
    
         $quiz_section = $attempt_college_quiz_ans->map(function ($query){
            $query->quizTitle;
         });

         $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=file.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
         );

         // $reviews = Reviews::getReviewExport($this->hw->healthwatchID)->get();
        $columns = array('roll no','Student Name','Quiz Title','Quiz Discription', 'Quetion', 'Option', 'Attempt Option','Score','Comment','Result');

         $callback = function() use ($quiz,$quiz_attemp_user,$quiz_question,$attempt_college_quiz_ans,$columns)
         {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach($attempt_college_quiz_ans as $Aquiz){

                if ($Aquiz->quizTitle->type == 'text'){
                $option = '-';
                $submited_option = $Aquiz->submit_option_id;
                }else{
                    $option = '';
                    foreach ($Aquiz->quizTitle->quizOption as $item){
                        if ($item->is_correct == 1){
                            $option .= $item->title.' , '; 
                        }
                    }

                    $submited_ans = json_decode($Aquiz->submit_option_id); 
                    $count = 0;  
                    $submited_option = '';
                    foreach ($Aquiz->quizTitle->quizOption as $item){
                        if (count($submited_ans) > $count){
                            if ($item->is_correct == 1 && $item->id == $submited_ans[$count])
                            {
                                $count ++;  
                                $submited_option .= $item->title.' , ';
                            }
                            
                        }else{
                            // $submited_option .= $item->title.' , ';
                        }
                    }   
                }

                if ($Aquiz->score > 0){
                    $Result = 'Correct';
                }else{
                    if ($Aquiz->quizTitle->type == 'text' && $Aquiz->mark_by_lecturer == 0){
                        $Result = 'Pending';
                    }else{
                        $Result = 'In-Correct';
                    }
                }

                fputcsv($file,
                array($quiz_attemp_user->roll_no,$quiz_attemp_user->name,$quiz->title,$quiz->description,$Aquiz->quizTitle->title,$option,$submited_option,$Aquiz->score,$Aquiz->comment,$Result));
            }
            fclose($file);
         };
         return Response::stream($callback, 200, $headers);
    }
}