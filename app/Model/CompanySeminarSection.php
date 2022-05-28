<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;
class CompanySeminarSection extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function items(){
        if (Auth::check()){
            return $this->hasMany(CompanySeminarContent::class,'section_id')->orderBy('sort_order', 'asc')->with('is_completed');
        }
        return $this->hasMany(CompanySeminarContent::class,'section_id')->orderBy('sort_order', 'asc');
    }

    public function seminar(){
        return $this->belongsTo(CompanySeminar::class,'section_id','id')->with('sections', 'sections.items');
    }
}