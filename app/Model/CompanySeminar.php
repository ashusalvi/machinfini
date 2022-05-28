<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Media;
use App\User;

class CompanySeminar extends Model
{
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

    public function sections(){
        return $this->hasMany(CompanySeminarSection::class,'seminar_id')->orderBy('id', 'asc');
    }

    public function author(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department(){
        return $this->belongsTo(CompanyDepartment::class, 'department_id');
    }
    
    public function completedSeminar($course_id,$user_id){
        return CompanySeminarComplete::where('completed_seminar_id',$course_id)->where('user_id',$user_id)->count();
    }

    public function sync_everything(){
        $now = Carbon::now()->toDateTimeString();

        $seminar = $this;
        $seminar_runtime = $seminar->lectures->sum('video_time');
        $total_lectures = $seminar->lectures->count();
        $total_assignments = 0;
        $total_quiz = $seminar->quizzes->count();

        $seminar->total_video_time = $seminar_runtime;
        $seminar->total_lectures = $total_lectures;
        $seminar->total_assignments = $total_assignments;
        $seminar->total_quiz = $total_quiz;
        $seminar->last_updated_at = $now;
        $seminar->save();
    }

    public function lectures(){
        return $this->hasMany(CompanySeminarContent::class,'seminar_id')->whereItemType('lecture');
    }

    public function quizzes(){
        return $this->hasMany(CompanySeminarContent::class,'seminar_id')->whereItemType('quiz');
    }

    public function price_html($originalPriceOnRight = false, $showOff = false){

        $priceLocation = ' current-price-left ';
        if ($originalPriceOnRight){
            $priceLocation = ' current-price-right ';
        }

        $price_html = "<div class='price-html-wrap {$priceLocation}'>";
        if ( $this->paid && $this->price > 0){

            $current_price = $this->sale_price > 0 ?  price_format($this->sale_price) : price_format($this->price);

            if ( ! $originalPriceOnRight){
                $price_html .= " <span class='current-price'>{$current_price}</span>";
            }

            if ($this->sale_price > 0){
                $old_price = price_format($this->price);
                $price_html .= " <span class='old-price'><s>{$old_price}</s></span>";

                if ($showOff) {
                    $discount = number_format( 100 - ($this->sale_price * 100   / $this->price)   , 2);
                    $offText = $discount . '% ' . __t('off');
                    $price_html .= " <span class='discount-text mr-2'>{$offText}</span>";
                }
            }

            if ($originalPriceOnRight){
                $price_html .= " <span class='current-price'>{$current_price}</span>";
            }


        }else{
            $price_html .= '<span class="free-text mr-2">'.__t('free').'</span>';
        }
        $price_html .= '</div>';

        return $price_html;
    }

    public function status_html($badge = true){
        $status = $this->status;

        $class = $badge ? 'badge badge' : 'status-text text';

        $html = "<span class='{$class}-dark'> <i class='la la-pencil-square-o'></i> ".__t('draft')."</span>";

        switch ($status){
            case 1:
                $html = "<span class='{$class}-success'> <i class='la la-check-circle'></i> ".__t('published')."</span>";
                break;
            case 2:
                $html = "<span class='{$class}-info'> <i class='la la-clock-o'></i> ".__t('pending')."</span>";
                break;
            case 3:
                $html = "<span class='{$class}-danger'> <i class='la la-ban'></i> ".__t('blocked')."</span>";

                break;
            case 4:
                $html = "<span class='{$class}-warning'> <i class='la la-exclamation-circle'></i> ".__t('unpublished')."</span>";
                break;
        }

        if ($this->is_popular){
            $html .= "<span class='badge badge-primary mx-2' data-toggle='tooltip' title='Popular'> <i class='la la-bolt'></i></span>";
        }
        if ($this->is_featured){
            $html .= "<span class='badge badge-info mx-2'  data-toggle='tooltip' title='Featured'> <i class='la la-bookmark'></i></span>";
        }

        return $html;
    }

    public function media(){
        return $this->belongsTo(Media::class, 'thumbnail_id');
    }

    public function getThumbnailUrlAttribute(){
        return media_image_uri($this->media)->image_sm;
    }

    public function completed_percent($user = null){
        /**
         * If not passed user id, get user id from auth
         * if auth user is not available, return percent 0;
         */

        if ( ! $user){
            $user = Auth::user();
        }
        if ( ! $user instanceof User) {
            $user = \App\User::find($user);
        }

        // $completed_course = (array) $user->get_option('completed_courses');
        // return (int) array_get($completed_course, $this->id.".percent");

        $total_contents = (int) CompanySeminarContent::where('seminar_id',$this->id)->count();
        $total_completed = (int) CompanySeminarComplete::whereUserId($user->id)->where('seminar_id',$this->id)->count();

        if ( ! $total_contents || ! $total_completed){
            return 0;
        }

        return (int) number_format(($total_completed * 100 ) / $total_contents);

    }

    public function completed_seminar($seminar_id,$from_date=null,$to_date=null){
        $completed_seminars = CompanySeminarComplete::where('completed_seminar_id',$seminar_id)
                                                ->whereNotNull('completed_seminar_id')
                                                ->count();
        return $completed_seminars;
    }

    public function pending_seminar($seminar_id,$from_date=null,$to_date=null){
        // $from = date($from_date);
        // $to = date($to_date);
        $completed_seminars = CompanySeminarComplete::where('completed_seminar_id',$seminar_id)
                                                ->whereNotNull('completed_seminar_id')
                                                ->count();

        $attempted_seminars = CompanySeminarEnroll::where('course_id',$seminar_id)
                                                ->count();
        return $attempted_seminars - $completed_seminars;
    }
}