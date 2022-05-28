<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
    protected $guarded = [];

    public function course(){
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
    public function payment(){
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    public function enroll(){
        return $this->belongsTo(Enroll::class,'payment_id','payment_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'instructor_id','id');
    }

}