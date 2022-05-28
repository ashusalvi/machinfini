<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CollegeQuizQuestion;

class AttemptCollegeQuiz extends Model
{
    protected $table = 'attempt_college_quiz';
    protected $guarded = [];
    protected $primaryKey='id';

    public function ans(){
        return $this->hasMany(AttemptCollegeQuizAns::class,'acq_user');
    }

}