<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Course;

class channelPartnerEarning extends Model
{
    protected $table = 'channel_partner_earning';
    protected $guarded = [];

    public function User()
    {
        return $this->hasOne('App\User', 'id', 'student_id');
    }

    public function channelpartner()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function Course()
    {
        return $this->hasOne('App\Course', 'id', 'course_id');
    }
}