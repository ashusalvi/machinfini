<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AffiliteMarketing extends Model
{
    protected $table = 'affilite_marketings';
    protected $guarded = [];
    protected $primaryKey='id';

    public function Course(){
        return $this->belongsTo(Course::class,'course_id','id');
    }

    public function clickCount()
    {
            return $this->hasMany(AffiliteMarketingClickReport::class,'affiliate_id','id');
    }

    public function buyInCount()
    {
            return $this->hasMany(channelPartnerEarning::class,'user_id','user_id');
    }
}