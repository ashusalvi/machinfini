<?php
namespace App\Plugins\Certificate;

use App\Module\PluginBase;
use Illuminate\Support\Facades\Auth;

class CertificatePlugin extends PluginBase {

    public $name = 'Certificate';
    public $slug = 'certificate';
    public $url = 'https://themeqx.com';
    public $description = 'Generate certificate after completing the course';
    public $author = 'Themeqx';
    public $author_url = 'https://themeqx.com';
    public $version = '1.0.0';
    public $lms_version = '1.0.0';

    public function boot(){
        $this->enableRoutes();
        $this->enableViews();

        add_action('lecture_single_after_progressbar', [$this, 'download_certificate_btn']);
        add_action('admin_menu_item_after', [$this, 'add_admin_menu_certificate']);

    }

    public function download_certificate_btn($course){

        if (Auth::check()){
            $user = Auth::user();

            $isCourseComplete = $user->is_completed_course($course->id);
            if ($isCourseComplete){
                $certURL = route('download_certificate', $course->id);

                echo "<div class='mb-4 text-center'> <a href='{$certURL}' class='btn btn-success'> <i class='la la-certificate'></i> Download Certificate</a> </div>";
            }

        }

    }


    public function add_admin_menu_certificate(){
        $settingsURL = route('certificate_settings');

        echo "<li> <a href='{$settingsURL}'><i class='la la-certificate'></i> Certificate Settings</a>  </li>";
    }


}
