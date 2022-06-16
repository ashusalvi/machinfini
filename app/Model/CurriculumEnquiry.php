<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CurriculumEnquiry extends Model
{
    protected $table = 'curriculum_enquiry';
    protected $guarded = [];

    public function Curriculum(){
        return $this->belongsTo(Curriculum::class,'curriculum_id','id');
    }
}