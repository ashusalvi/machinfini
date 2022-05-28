<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CompanyAnswer extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function question(){
        return $this->belongsTo(CompanySeminarQuestion::class, 'question_id');
    }
}