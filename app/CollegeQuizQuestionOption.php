<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollegeQuizQuestionOption extends Model
{
    protected $table = 'college_quiz_question_options';
    protected $guarded = [];
    protected $primaryKey='id';
}