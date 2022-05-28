<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Coupon;
use App\User;
use Illuminate\Support\Facades\Auth;

class couponValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {   
        if(Auth::user()->user_type == 'channel_partner')
        {   
            $value = 'MI'.Auth::user()->id.$value;
            $checkCoupon = Coupon::where('code',$value)->where('status',1)->first();
        }else{
            $checkCoupon = Coupon::where('code',$value)->where('status',1)->first();
        }
        if(empty($checkCoupon))
        {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Coupon already Exists!';
    }
}