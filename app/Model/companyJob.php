<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class companyJob extends Model
{
    protected $table = 'company_job';
    protected $guarded = [];

    public function company()
    {
        return $this->hasOne(Company::class,'id','company_id');
    }
}