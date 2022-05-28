<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CollegeQuizQuestion;

class AttemptCollegeQuizAns extends Model
{
    protected $table = 'attempt_college_quiz_ans';
    protected $guarded = [];
    protected $primaryKey='id';

    public function quizTitle()
    {
        return $this->belongsTo(CollegeQuizQuestion::class, 'question_id','id');
    }


}