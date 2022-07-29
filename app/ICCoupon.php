<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ICCoupon extends Model
{
    protected $table = 'ic_coupon';
    protected $guarded = [];
    protected $primaryKey='id';
}