<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CompanyQuiz extends Model
{
    protected $table = 'company_seminar_contents';
    protected $guarded = [];

    public function questions(){
        return $this->hasMany(CompanySeminarQuestion::class, 'quiz_id')->with('media');
    }
    public function attempts(){
        return $this->hasMany(Attempt::class, 'quiz_id');
    }
    public function option($key = null, $default = null){
        $options = null;
        if ($this->options){
            $options = json_decode($this->options, true);
        }
        if ($key){
            if (is_array($options) && array_get($options, $key)){
                return array_get($options, $key);
            }else{
                return $default;
            }
        }

        return $options;
    }

    public function getUrlAttribute(){
        return route('seminar_single_quiz', [$this->seminar_id, $this->id]);
    }
}