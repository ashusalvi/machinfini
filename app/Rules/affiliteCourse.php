<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\AffiliteMarketing;
use App\User;
use Auth;

class affiliteCourse implements Rule
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
        $checkcourse = AffiliteMarketing::where('course_id',$value)->where('user_id',Auth::user()->id)->where('status',1)->first();
        if(empty($checkcourse))
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
        return 'This Course is already selected.';
    }
}