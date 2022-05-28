<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\CompanySeminar;

class CompanySeminarEnroll extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function seminar()
    {
        return $this->belongsTo(CompanySeminar::class,'course_id','id');
    }
}