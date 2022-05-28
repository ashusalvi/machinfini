<?php
namespace App\Plugins\LiveClass;

use App\Module\PluginBase;

class LiveClassPlugin extends PluginBase {

    public $name = 'Live Class Streaming';
    public $slug = 'live_class';
    public $url = 'https://themeqx.com';
    public $description = 'Improve your courses with Live class streaming.';
    public $author = 'Themeqx';
    public $author_url = 'https://themeqx.com';
    public $version = '1.0.0';
    public $lms_version = '1.0.0';

    public function boot(){
        $this->enableRoutes();
        $this->enableViews();

        add_action('admin_menu_item_after', [$this, 'add_admin_menu_live_class']);
        add_filter('course_edit_nav_items', [$this, 'add_course_nav_item']);
        add_action('lecture_single_after_progressbar', [$this, 'live_class_btn']);

    }


    public function add_admin_menu_live_class(){
        $settingsURL = route('live_class_settings');

        echo "<li> <a href='{$settingsURL}'><i class='la la-video-camera'></i> Live Class Settings</a>  </li>";
    }


    public function add_course_nav_item($nav_items){
        $nav_items['edit_course_live_class'] = [
            'name' => 'Live',
            'icon' => '<i class="la la-video-camera"></i>',
            'is_active' => request()->is('dashboard/courses/*/live_class*'),
        ];

        return $nav_items;
    }


    public function live_class_btn($course){
        $option = (array) array_get(json_decode($course->video_src, true), 'live_class');

        if ($option) {
            echo "<div class='mb-4 text-center'> <a href='".route('live_class_stream', $course->slug)."' class='btn btn-info'> <i class='la la-video-camera'></i> Live Class </a> </div>";
        }
    }

}
