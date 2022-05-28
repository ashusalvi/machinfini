<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollegeQuizQuestion extends Model
{
    protected $table = 'college_quiz_question';
    protected $guarded = [];
    protected $primaryKey='id';

    public function options(){
        return $this->hasMany(CollegeQuizQuestionOption::class)->orderBy('sort_order', 'asc');
    }

    public function delete_sync(){
        $this->options()->delete();
        $this->delete();
    }

    public function quizOption(){
        return $this->hasMany(CollegeQuizQuestionOption::class,'question_id');
    }
}