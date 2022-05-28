<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class channelPartner extends Model
{
    protected $table = 'channel_partner';
    protected $guarded = [];

    public function User()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}