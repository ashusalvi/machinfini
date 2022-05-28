<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CollegeQuizQuestion;

class CollegeQuizAuthUser extends Model
{
    protected $table = 'college_quiz_auth_user';
    protected $guarded = [];
    protected $primaryKey='id';

    public function quiz(){
        return $this->belongsTo(CollegeQuiz::class,'quiz_id','id');
    }

    public function quizAns(){
        return $this->hasMany(AttemptCollegeQuizAns::class,'quiz_id','id');
    }


}