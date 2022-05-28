<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class CompanyAttempt extends Model
{
    protected $guarded = [];

    protected $dates = ['ended_at'];

    public function answers(){
        return $this->hasMany(CompanyAnswer::class,'attempt_id')->with('question');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function quiz(){
        return $this->belongsTo(CompanySeminarContent::class, 'quiz_id');
    }
    public function course(){
        return $this->belongsTo(CompanySeminar::class, 'course_id');
    }

    public function getStatusHtmlAttribute(){
        $statusClass = "";
        $iclass = "";
        switch ($this->status){
            case 'started':
                $statusClass .= "dark";
                $iclass = "clock-o";
                break;
            case 'in_review':
                $statusClass .= "warning";
                $iclass = "hourglass";
                break;
            case 'finished':
                $statusClass .= "success";
                $iclass = "check-circle";
                break;
        }

        $html = "<span class='badge payment-status-{$this->status} badge-{$statusClass}'> <i class='la la-{$iclass}'></i> {$this->status}</span>";
        return $html;

    }


    public function save_and_sync($data = []){
        if (is_array($data) && count($data)){
            $this->update($data);
        }else{
            $this->save();
        }

        $q_score = $this->answers->sum('q_score');
        $r_score = $this->answers->sum('r_score');

        $earned_percent = 0;
        if ($r_score > 0){
            $earned_percent = (100 * $r_score) / $q_score;
        }

        $passing_percent = (int) $this->quiz->option('passing_score');

        $passed = $earned_percent >= $passing_percent ? 1 : 0;

        $this->earned_scores = $r_score;
        $this->earned_percent = $earned_percent;
        $this->passed = $passed;
        $this->save();

        $content = CompanySeminarContent::find($this->quiz_id);
        if($passed != 0){
            complete_seminar_content($content, $this->user);
        }   
        
        
        return $this;
    }

}