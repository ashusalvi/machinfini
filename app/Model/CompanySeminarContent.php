<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;
class CompanySeminarContent extends Model
{
    protected $guarded = [];

    public function course(){
        return $this->belongsTo(CompanySeminar::class,'seminar_id')->with('sections', 'sections.items');
    }

    public function section(){
        return $this->belongsTo(CompanySeminarSection::class);
    }


    public function seminar(){
        return $this->belongsTo(CompanySeminar::class,'seminar_id','id')->with('sections', 'sections.items');
    }

    public function save_and_sync($data = []){
        if (is_array($data) && count($data)){
            $data['video_time'] = $this->runtime_seconds;
            $this->update($data);
        }else{
            $this->video_time = $this->runtime_seconds;
            $this->save();
        }

        $this->seminar->sync_everything();

        return $this;
    }

    public function is_completed(){
        if (Auth::user()){
            $user_id = Auth::user()->id;
            return $this->hasOne(CompanySeminarComplete::class,'content_id')->whereUserId($user_id);
        }
        return false;
    }

    public function video_info($key = null){
        $video_info = null;
        if ($this->video_src){
            $video_info = json_decode($this->video_src, true);
        }
        if ($key && is_array($video_info)){
            return array_get($video_info, $key);
        }

        return $video_info;
    }

    public function questions(){
        return $this->hasMany(CompanySeminarQuestion::class, 'quiz_id')->with('media')->orderBy('sort_order', 'asc');
    }

    public function option($key = null, $default = null){
        $options = null;
        if ($this->options){
            $options = json_decode($this->options, true);
        }
        if ($key){
            if (is_array($options) && array_get($options, $key)){
                return array_get($options, $key);
            }else{
                return $default;
            }
        }

        return $options;
    }
    
    // public function previous(){
    //     return $this->hasOne(CompanySeminarContent::class, 'seminar_id', 'seminar_id')->where('sort_order', $this->id-1)->orderBy('sort_order', 'desc');
    // }
    public function next($id = 0,$seminar_id = 0){
        // return $sort_order+1;
        return CompanySeminarContent::where('seminar_id',$seminar_id)->where('id', $id+1)->orderBy('sort_order', 'asc')->first();
    }


}