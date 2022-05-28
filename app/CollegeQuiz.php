<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CollegeQuizQuestion;

class CollegeQuiz extends Model
{
    protected $table = 'college_quiz';

    public function questions()
    {
        return $this->hasMany(CollegeQuizQuestion::class, 'quiz_id')->orderBy('sort_order', 'asc');
    }
}
