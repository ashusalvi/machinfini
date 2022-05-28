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

class ExportControrller extends Controller
{
    public function export()
    {
        $headers = array(
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=file.csv",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
        );

        // $reviews = Reviews::getReviewExport($this->hw->healthwatchID)->get();
        $columns = array('Roll no', 'Student Name', 'Date', 'Attempt Question', 'Score', 'Result');

        $callback = function() use ( $columns)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv('Roll no', 'Student Name', 'Date', 'Attempt Question', 'Score', 'Result');
            // foreach($reviews as $review) {
            // fputcsv($file, array($review->reviewID, $review->provider, $review->title, $review->review, $review->location,
            // $review->review_created, $review->anon, $review->escalate, $review->rating, $review->name));
            // }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }
}