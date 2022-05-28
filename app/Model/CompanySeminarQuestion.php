<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Media;

class CompanySeminarQuestion extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function options(){
        return $this->hasMany(CompanySeminarQuestionOption::class,'question_id')->orderBy('sort_order', 'asc');
    }

    public function media(){
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function getImageUrlAttribute(){
        return media_image_uri($this->media);
    }
    
    public function delete_sync(){
        $this->options()->delete();
        $this->delete();
    }

}