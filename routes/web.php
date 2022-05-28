<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/phpinfo', function() {
    return phpinfo();
});

Route::get('/export','ExportControrller@export');

Route::get('term_condition', function(){
    return view('term_condition');
})->name('term_condition');

Route::get('/', 'HomeController@index')->name('home');
Route::get('/new_home', 'HomeController@newHome')->name('newHome');
Route::get('clear', 'HomeController@clearCache')->name('clear_cache');

Route::get('send_contact_us', 'HomeController@sendContactUs')->name('send_contact_us');


// Route::get('installations', 'InstallationController@installations')->name('installations');
// Route::get('installations/step/2', 'InstallationController@installationsTwo')->name('installations_step_two');
// Route::post('installations/step/2', 'InstallationController@installationPost');
// Route::get('installations/step/final', 'InstallationController@installationFinal')->name('installation_final');

/**
 * Authentication
 */

Route::get('login/{code?}', 'AuthController@login')->name('login')->middleware('guest');
Route::post('login', 'AuthController@loginPost');
Route::any('logout', 'AuthController@logoutPost')->name('logout');

Route::get('register', 'AuthController@register')->name('register')->middleware('guest');
Route::post('register', 'AuthController@registerPost');

Route::get('forgot-password', 'AuthController@forgotPassword')->name('forgot_password');
Route::post('forgot-password', 'AuthController@sendResetToken');
Route::get('forgot-password/reset/{token}', 'AuthController@passwordResetForm')->name('reset_password_link');
Route::post('forgot-password/reset/{token}', 'AuthController@passwordReset');

Route::get('profile/{id}', 'UserController@profile')->name('profile');
Route::get('review/{id}', 'UserController@review')->name('review');


Route::get('courses', 'HomeController@courses')->name('courses');
Route::get('featured-courses', 'HomeController@courses')->name('featured_courses');
Route::get('popular-courses', 'HomeController@courses')->name('popular_courses');

Route::get('courses/{slug?}/{id?}/{cp_id?}/{session?}', 'CourseController@view')->name('course');
Route::get('courses_lecture/{slug}/lecture/{lecture_id}', 'CourseController@lectureView')->name('single_lecture');
Route::get('courses_lecture/{slug}/assignment/{assignment_id}', 'CourseController@assignmentView')->name('single_assignment');
Route::get('courses_lecture/{slug}/quiz/{quiz_id}', 'QuizController@quizView')->name('single_quiz');


Route::get('topics', 'CategoriesController@home')->name('categories');
Route::get('topics/{category_slug}', 'CategoriesController@show')->name('category_view');
//Get Topics Dropdown for course creation category select
Route::post('get-topic-options', 'CategoriesController@getTopicOptions' )->name('get_topic_options');

Route::post('courses/free-enroll', 'CourseController@freeEnroll')->name('free_enroll');

//Attachment Download
Route::get('attachment-download/{hash}', 'CourseController@attachmentDownload')->name('attachment_download');

Route::get('payment-thank-you', 'PaymentController@thankYou')->name('payment_thank_you_page');
Route::post('payment-thank-you-post/{transaction_id?}/{user_id?}/{courses?}/{erning?}/{coupons?}/{coure_affilite_marketing?}/{amount?}/{razorpay_signature?}', 'PaymentController@thankYouPost')->name('payment_thank_you_page_post');

Route::group(['prefix'=>'login'], function(){
    //Social login route
    Route::get('facebook', 'AuthController@redirectFacebook')->name('facebook_redirect');
    Route::get('facebook/callback', 'AuthController@callbackFacebook')->name('facebook_callback');

    Route::get('google', 'AuthController@redirectGoogle')->name('google_redirect');
    Route::get('google/callback', 'AuthController@callbackGoogle')->name('google_callback');

    Route::get('twitter', 'AuthController@redirectTwitter')->name('twitter_redirect');
    Route::get('twitter/callback', 'AuthController@callbackTwitter')->name('twitter_callback');

    Route::get('linkedin', 'AuthController@redirectLinkedIn')->name('linkedin_redirect');
    Route::get('linkedin/callback', 'AuthController@callbackLinkedIn')->name('linkin_callback');
});


Route::group(['middleware' => ['auth'] ], function() {
    Route::post('courses_submit/{slug}/assignment/{assignment_id}', 'CourseController@assignmentSubmitting');
    Route::get('content_complete/{content_id}', 'CourseController@contentComplete')->name('content_complete');
    Route::post('courses-complete/{course_id}', 'CourseController@complete')->name('course_complete');

    Route::group(['prefix' => 'checkout' ], function() {
        Route::get('/', 'CartController@checkout')->name('checkout');
        Route::post('bank-transfer', 'GatewayController@bankPost')->name('bank_transfer_submit');
        Route::post('paypal', 'GatewayController@paypalRedirect')->name('paypal_redirect');
        Route::post('cashfree', 'GatewayController@cashfreeRedirect')->name('cashfree_redirect');
        Route::post('offline', 'GatewayController@payOffline')->name('pay_offline');
    });

    Route::post('save-review/{course_id?}', 'CourseController@writeReview')->name('save_review');
    Route::post('update-wishlist', 'UserController@updateWishlist')->name('update_wish_list');

    Route::post('discussion/ask-question', 'DiscussionController@askQuestion')->name('ask_question');
    Route::post('discussion/reply/{id}', 'DiscussionController@replyPost')->name('discussion_reply_student');

    Route::post('quiz-start', 'QuizController@start')->name('start_quiz');
    Route::get('quiz/{id}', 'QuizController@quizAttempting')->name('quiz_attempt_url');
    Route::post('quiz/{id}', 'QuizController@answerSubmit');
    Route::get('re-quiz/{id}', 'QuizController@reattendQuiz');

    //Route::get('quiz/answer/submit', 'QuizController@answerSubmit')->name('quiz_answer_submit');

    Route::group(['prefix' => 'channel_partner'], function(){
        Route::get('affilite_marketing','channelPartner\AffiliteMarketingController@index')->name('affilite_marketing');
        Route::get('cp_affilite_marketing','channelPartner\AffiliteMarketingController@index')->name('cp_affilite_marketing');
        Route::post('store','channelPartner\AffiliteMarketingController@store')->name('store');
        Route::get('delete/{id}','channelPartner\AffiliteMarketingController@delete')->name('cp_affilite_marketing_delete');
        
        Route::group(['prefix' => 'coupon'], function(){
            Route::get('create','CouponController@cpIndex')->name('cp_coupons');
            Route::post('save','CouponController@cpStore')->name('cp_saveCoupon');
            Route::get('delete/{id}','CouponController@delete')->name('cp_delete');
        });
    });
});

/**
 * Add and remove to Cart
 */
Route::post('add-to-cart', 'CartController@addToCart')->name('add_to_cart');
Route::post('remove-cart', 'CartController@removeCart')->name('remove_cart');

/**
 * Payment Gateway Silent Notification
 * CSRF verification skipped
 */
Route::group(['prefix' => 'gateway-ipn' ], function() {
    Route::post('stripe', 'GatewayController@stripeCharge')->name('stripe_charge');
    Route::any('paypal/{transaction_id?}', 'IPNController@paypalNotify')->name('paypal_notify');
});

/**
 * Users,Instructor dashboard area
 */


Route::group(['prefix'=>'dashboard', 'middleware' => ['auth'] ], function() {
    Route::get('/', 'DashboardController@index')->name('dashboard');

    /**
     * Only instructor has access in this group
     */
    Route::group(['middleware' => ['instructor'] ], function() {

         //collage quiz routes
         Route::group(['prefix' => 'college_quiz' ], function() {
            Route::get('new', 'CollegeQuizController@create')->name('create_quiz');
            Route::get('{id}/edit', 'CollegeQuizController@edit')->name('edit_quiz');
            Route::get('collage_quiz_list', 'CollegeQuizController@getQuiz')->name('collage_quiz_list');
            Route::post('saveQuiz', 'CollegeQuizController@saveQuiz')->name('saveQuiz');
            Route::post('editQuiz', 'CollegeQuizController@editQuiz')->name('editQuiz');
            Route::get('{id}/addQuestion', 'CollegeQuizController@addQuestion')->name('addQuestion');
            Route::post('edit-question', 'CollegeQuizController@editQuestion')->name('college_edit_question_form');
            Route::post('submit_edit-question', 'CollegeQuizController@updateCollegeQuestion')->name('college_update_question_form');
            Route::get('{id}/addAuthUsers', 'CollegeQuizController@addAuthUsers')->name('addAuthUsers');
            Route::post('{id}/uploadcsv', 'CollegeQuizController@uploadCSV')->name('uploadCSV');
            Route::get('{id}/viewQuestion', 'CollegeQuizController@viewQuizQuestion')->name('viewQuizQuestion');
            Route::get('{id}/publish_college_quiz',
            'CollegeQuizController@publishCollegeQuiz')->name('publishCollegeQuiz');
            Route::get('{email}/{quiz_id}/view_submited_quiz', 'CollegeQuizController@viewSubmitedQuiz')->name('viewSubmitedQuiz');
            Route::post('addCollegeQuestion', 'CollegeQuizController@addCollegeQuestion')->name('addCollegeQuestion');
            Route::post('loadCollegeQuizQuestions','CollegeQuizController@loadCollegeQuizQuestions')->name('loadCollegeQuizQuestions');
            Route::get('correct_mark_college_quiz','CollegeQuizController@correctMarkCollegeQuiz')->name('correctMarkCollegeQuiz');
            Route::get('incorrect_mark_college_quiz','CollegeQuizController@incorrectMarkCollegeQuiz')->name('incorrectMarkCollegeQuiz');
            Route::get('comment_mark_college_quiz','CollegeQuizController@commentMarkCollegeQuiz')->name('commentMarkCollegeQuiz');
         });
         Route::post('delete_collage_quiz_question', 'CollegeQuizController@deleteQuestion')->name('deleteCollegeQuestion');
        
         //collage quiz routes

        Route::post('update-section/{id}', 'CourseController@updateSection')->name('update_section');
        Route::post('delete-section', 'CourseController@deleteSection')->name('delete_section');

        Route::get('create_collage_instructor','CollegeInstructorController@index')->name('create_collage_instructor');
        Route::post('savecollegeinstructor','CollegeInstructorController@create')->name('savecollegeinstructor');

        Route::group(['prefix' => 'courses' ], function() {
            Route::get('new', 'CourseController@create')->name('create_course');
            Route::post('new', 'CourseController@store');

            Route::get('{course_id}/information', 'CourseController@information')->name('edit_course_information');
            Route::post('{course_id}/information', 'CourseController@informationPost');

            Route::group(['prefix' => '{course_id}/curriculum' ], function() {
                Route::get('', 'CourseController@curriculum')->name('edit_course_curriculum');
                Route::get('new-section', 'CourseController@newSection')->name('new_section');
                Route::post('new-section', 'CourseController@newSectionPost');

                Route::post('new-lecture', 'CourseController@newLecture')->name('new_lecture');
                Route::post('update-lecture/{id}', 'CourseController@updateLecture')->name('update_lecture');

                Route::post('new-assignment', 'CurriculumController@newAssignment')->name('new_assignment');
                Route::post('update-assignment/{id}', 'CurriculumController@updateAssignment')->name('update_assignment');

                Route::group(['prefix' => 'quiz' ], function() {
                    Route::post('create', 'QuizController@newQuiz')->name('new_quiz');
                    Route::post('update/{id}', 'QuizController@updateQuiz')->name('update_quiz');

                    Route::post('{quiz_id}/create-question', 'QuizController@createQuestion')->name('create_question');
                });
            });

            Route::post('quiz/edit-question', 'QuizController@editQuestion')->name('edit_question_form');
            Route::post('quiz/update-question', 'QuizController@updateQuestion')->name('edit_question');
            Route::post('load-quiz-questions', 'QuizController@loadQuestions')->name('load_questions');
            Route::post('sort-questions', 'QuizController@sortQuestions')->name('sort_questions');
            Route::post('delete-question', 'QuizController@deleteQuestion')->name('delete_question');
            Route::post('delete-option', 'QuizController@deleteOption')->name('option_delete');

            Route::post('edit-item', 'CourseController@editItem')->name('edit_item_form');
            Route::post('delete-item', 'CourseController@deleteItem')->name('delete_item');
            Route::post('curriculum_sort', 'CurriculumController@sort')->name('curriculum_sort');

            Route::post('delete-attachment', 'CurriculumController@deleteAttachment')->name('delete_attachment_item');

            Route::post('load-section-items', 'CourseController@loadContents')->name('load_contents');

            Route::get('{id}/pricing', 'CourseController@pricing')->name('edit_course_pricing');
            Route::post('{id}/pricing', 'CourseController@pricingSet');
            Route::get('{id}/drip', 'CourseController@drip')->name('edit_course_drip');
            Route::post('{id}/drip', 'CourseController@dripPost');
            Route::get('{id}/publish', 'CourseController@publish')->name('publish_course');
            Route::post('{id}/publish', 'CourseController@publishPost');
        });

        Route::get('my-courses', 'CourseController@myCourses')->name('my_courses');
        Route::get('my-courses-reviews', 'CourseController@myCoursesReviews')->name('my_courses_reviews');

        Route::group(['prefix' => 'courses-has-quiz' ], function() {
            Route::get('/', 'QuizController@quizCourses')->name('courses_has_quiz');
            Route::get('quizzes/{id}', 'QuizController@quizzes')->name('courses_quizzes');
            Route::get('attempts/{quiz_id}', 'QuizController@attempts')->name('quiz_attempts');
            Route::get('attempt/{attempt_id}', 'QuizController@attemptDetail')->name('attempt_detail');
            Route::post('attempt/{attempt_id}', 'QuizController@attemptReview');
        });

        Route::group(['prefix' => 'assignments' ], function() {
            Route::get('/', 'AssignmentController@index')->name('courses_has_assignments');
            Route::get('course/{course_id}', 'AssignmentController@assignmentsByCourse')->name('courses_assignments');
            Route::get('submissions/{assignment_id}', 'AssignmentController@submissions')->name('assignment_submissions');
            Route::get('submission/{submission_id}', 'AssignmentController@submission')->name('assignment_submission');
            Route::post('submission/{submission_id}', 'AssignmentController@evaluation');
        });

        Route::group(['prefix' => 'earning' ], function() {
            Route::get('/', 'EarningController@earning')->name('earning');
            Route::get('report', 'EarningController@earningReport')->name('earning_report');
        });
        Route::group(['prefix' => 'withdraw' ], function() {
            Route::get('/', 'EarningController@withdraw')->name('withdraw');
            Route::post('/', 'EarningController@withdrawPost');

            Route::get('preference', 'EarningController@withdrawPreference')->name('withdraw_preference');
            Route::post('preference', 'EarningController@withdrawPreferencePost');
        });

        Route::group(['prefix'=>'discussions'], function() {
            Route::get('/', 'DiscussionController@index')->name('instructor_discussions');
            Route::get('reply/{id}', 'DiscussionController@reply')->name('discussion_reply');
            Route::post('reply/{id}', 'DiscussionController@replyPost');

        });

    });

    Route::group(['prefix'=>'media'], function() {
        Route::post('upload', 'MediaController@store' )->name('post_media_upload');
        Route::get('load_filemanager', 'MediaController@loadFileManager' )->name('load_filemanager');
        Route::post('delete', 'MediaController@delete' )->name('delete_media');
    });

    Route::group(['prefix' => 'settings' ], function() {
        Route::get('/', 'DashboardController@profileSettings')->name('profile_settings');
        Route::post('/', 'DashboardController@profileSettingsPost');

        Route::get('reset-password', 'DashboardController@resetPassword')->name('profile_reset_password');
        Route::post('reset-password', 'DashboardController@resetPasswordPost');
    });

    Route::get('enrolled-courses', 'DashboardController@enrolledCourses')->name('enrolled_courses');
    Route::get('reviews-i-wrote', 'DashboardController@myReviews')->name('reviews_i_wrote');
    Route::get('wishlist', 'DashboardController@wishlist')->name('wishlist');

    Route::get('my-quiz-attempts', 'QuizController@myQuizAttempts')->name('my_quiz_attempts');

    Route::get('college_quiz', 'CollegeQuizController@myCollegeQuiz')->name('college_quiz');
    Route::get('{id}/{quiz_id}/view_submited_quiz', 'CollegeQuizController@viewSubmitedQuizStudent')->name('viewSubmitedQuizStudent');

    Route::group(['prefix' => 'purchases' ], function() {
        Route::get('/', 'DashboardController@purchaseHistory')->name('purchase_history');
        Route::get('view/{id}', 'DashboardController@purchaseView')->name('purchase_view');
    });

    Route::post('apply_coupon','CouponController@applyCoupon')->name('apply_coupon');
    Route::get('cancel_coupon/{id}','CouponController@cancelCoupon')->name('cancel_coupon');

    Route::get('auth_collage_quiz_submit/{encryptString?}','CollegeQuizController@authCollegeQuizViewSection')->name('authcollageQuizSubmit');

});


/**
 * Admin Area
 */


Route::group(['prefix'=>'admin', 'middleware' => ['auth', 'admin'] ], function() {
    Route::get('/', 'AdminController@index')->name('admin');

    Route::group(['prefix'=>'cms'], function(){
        Route::get('/', 'PostController@posts')->name('posts');
        Route::get('post/create', 'PostController@createPost')->name('create_post');
        Route::post('post/create', 'PostController@storePost');
        Route::get('post/edit/{id}', 'PostController@editPost')->name('edit_post');
        Route::post('post/edit/{id}', 'PostController@updatePost');

        Route::get('page', 'PostController@index')->name('pages');
        Route::get('page/create', 'PostController@create')->name('create_page');
        Route::post('page/create', 'PostController@store');
        Route::get('page/edit/{id}', 'PostController@edit')->name('edit_page');
        Route::post('page/edit/{id}', 'PostController@updatePage');
    });

    Route::group(['prefix'=>'media_manager'], function() {
        Route::get('/', 'MediaController@mediaManager')->name('media_manager');
        Route::post('media-update', 'MediaController@mediaManagerUpdate')->name('media_update');
    });

    Route::group(['prefix'=>'categories'], function() {
        Route::get('/', 'CategoriesController@index' )->name('category_index');
        Route::get('create', 'CategoriesController@create' )->name('category_create');
        Route::post('create', 'CategoriesController@store' );
        Route::get('edit/{id}', 'CategoriesController@edit' )->name('category_edit');
        Route::post('edit/{id}', 'CategoriesController@update' );
        Route::post('delete', 'CategoriesController@destroy' )->name('delete_category');
    });

    Route::group(['prefix'=>'courses'], function(){
        Route::get('/', 'AdminController@adminCourses' )->name('admin_courses');
        Route::get('popular', 'AdminController@popularCourses' )->name('admin_popular_courses');
        Route::get('featured', 'AdminController@featureCourses' )->name('admin_featured_courses');
    });

    Route::group(['prefix' => 'plugins' ], function() {
        Route::get('/', 'ExtendController@plugins')->name('plugins');
        Route::get('find', 'ExtendController@findPlugins')->name('find_plugins');
        Route::get('action', 'ExtendController@pluginAction')->name('plugin_action');
    });
    Route::group(['prefix' => 'themes' ], function() {
        Route::get('/', 'ExtendController@themes')->name('themes');
        Route::post('activate', 'ExtendController@activateTheme')->name('activate_theme');
        Route::get('find', 'ExtendController@findThemes')->name('find_themes');
    });

    Route::group(['prefix'=>'settings'], function(){
        Route::get('theme-settings', 'SettingsController@ThemeSettings')->name('theme_settings');
        Route::get('invoice-settings', 'SettingsController@invoiceSettings')->name('invoice_settings');
        Route::get('general', 'SettingsController@GeneralSettings')->name('general_settings');
        Route::get('lms-settings', 'SettingsController@LMSSettings')->name('lms_settings');

        Route::get('social', 'SettingsController@SocialSettings')->name('social_settings');
        //Save settings / options
        Route::post('save-settings', 'SettingsController@update')->name('save_settings');
        Route::get('payment', 'PaymentController@PaymentSettings')->name('payment_settings');
        Route::get('storage', 'SettingsController@StorageSettings')->name('storage_settings');
    });

    Route::get('gateways', 'PaymentController@PaymentGateways')->name('payment_gateways');
    Route::get('withdraw', 'SettingsController@withdraw')->name('withdraw_settings');

    Route::group(['prefix'=>'payments'], function() {
        Route::get('/', 'PaymentController@index')->name('payments');
        Route::get('view/{id}', 'PaymentController@view')->name('payment_view');
        Route::get('delete/{id}', 'PaymentController@delete')->name('payment_delete');

        Route::post('update-status/{id}', 'PaymentController@updateStatus')->name('update_status');
    });

    Route::group(['prefix'=>'withdraws'], function() {
        Route::get('/', 'AdminController@withdrawsRequests')->name('withdraws');
    });

    Route::group(['prefix'=>'users'], function(){
        Route::get('/', ['as'=>'users', 'uses' => 'UserController@users']);

        Route::get('/edit_userprofile/{id}', 'UserController@setting')->name('user_setting_by_admin');
        Route::post('/edit_userprofile/{id}', 'UserController@saveSetting')->name('submit_user_setting_by_admin');

        Route::get('create', ['as'=>'add_administrator', 'uses' => 'UserController@addAdministrator']);
        Route::post('create', ['uses' => 'UserController@storeAdministrator']);

        Route::post('block-unblock', ['as'=>'administratorBlockUnblock','uses' => 'UserController@administratorBlockUnblock']);
    });

    Route::group(['prefix'=>'earning'],function(){
        Route::get('/','channelPartner\AffiliteMarketingReportController@index')->name('earnings');
    });

    Route::group(['prefix'=>'company'],function(){
        Route::get('/','company\CompanyController@index')->name('company');
        Route::get('/company_list','company\CompanyController@companyList')->name('companyList');
        Route::get('/company_job_list','company\CompanyController@companyJobList')->name('companyJobList');
        Route::get('/edit/{id}','company\CompanyController@edit')->name('companyEdit');
        Route::get('/create','company\CompanyController@create')->name('companyCreate');
        Route::post('/store','company\CompanyController@store')->name('companyStore');
        Route::put('/update','company\CompanyController@update')->name('companyUpdate');

        Route::group(['prefix' => 'mis'],function(){
            Route::get('mis_company_count/{from_date?}/{to_date?}/{company_id?}','company\CompanyController@CompanyCount')->name('mis_company_count');
            Route::get('mis_course_status/{from_date?}/{to_date?}/{company_id?}','company\CompanyController@CourseStatus')->name('mis_course_status');
            Route::get('mis_personnel_data/{from_date?}/{to_date?}/{company_id?}/{designation?}','company\CompanyController@PersonnelData')->name('mis_personnel_data');
            Route::get('mis_course_request/{from_date?}/{to_date?}/{company_id?}','company\CompanyController@CourseRequest')->name('mis_course_request');
            Route::get('update_course_request','company\CompanyController@updateCourseRequest')->name('update_course_request');
        });

        Route::get('/create_company_coupon','company\CompanyController@createCompanyCoupon')->name('companyCouponCreate');
        Route::post('/store_company_coupon','company\CompanyController@storeCompanyCoupon')->name('storeCompanyCoupon');
        Route::get('/list_company_coupon','company\CompanyController@listCompanyCoupon')->name('listCompanyCoupon');
    });

    /**
     * Change Password route
     */
    Route::group(['prefix' => 'account'], function() {
        Route::get('change-password', 'UserController@changePassword')->name('change_password');
        Route::post('change-password', 'UserController@changePasswordPost');
    });

    Route::group(['prefix' => 'coupon'], function(){
        Route::get('create','CouponController@index')->name('createCoupon');
        Route::post('save','CouponController@store')->name('saveCoupon');
        Route::get('get_department/{id?}','CouponController@getDepartment')->name('getDepartment');
    });

    Route::get('invoice','InvoiceController@index')->name('invoice_list');
    Route::get('invoice_details/{id}','InvoiceController@details')->name('invoice_details');

    Route::get('create_collage','CreateCollageController@index')->name('createcollage');
    Route::post('create_collage','CreateCollageController@create')->name('savecollage');

    Route::get('create_collage_admin','CollegeAdminController@index')->name('create_collage_admin');
    Route::post('savecollegeadmin','CollegeAdminController@create')->name('savecollegeadmin');

    // channel partner
    Route::get('channel_partner','channelPartner\channgelPartnerController@index')->name('channel_partner');
    Route::POST('save_partner','channelPartner\channgelPartnerController@store')->name('save_partner');
    Route::get('delete_partner/{id}','channelPartner\channgelPartnerController@delete')->name('delete_partner');
    Route::get('edit_partner','channelPartner\channgelPartnerController@edit')->name('edit_partner');
    
});

Route::group(['prefix'=>'company', 'middleware' => ['auth'] ],function(){
    // route for company
    Route::group(['prefix' => 'company'], function(){
        Route::get('dashboard','company\company\DashboardController@index')->name('companyDashboard');
        Route::group(['prefix' => 'job'],function(){
             Route::get('create','company\company\job\JobController@create')->name('company_job_create');
             Route::get('list','company\company\job\JobController@list')->name('company_job_list');
             Route::post('store','company\company\job\JobController@store')->name('company_job_store');
             Route::get('job_update_status','company\company\job\JobController@updateStatus')->name('company_job_update_status');
             Route::get('job_delete','company\company\job\JobController@delete')->name('company_job_delete');
        });
    });
    // route for company admin
    Route::group(['prefix' =>'admin'],function(){
        Route::get('dashboard','company\admin\DashboardController@index')->name('companyAdminDashboard');

        // route for company admin access of company instructors
        Route::group(['prefix' => 'instroctor'],function(){
            Route::get('create','company\admin\instructor\InstructorController@create')->name('CPA_instructor_create');
            Route::post('store','company\admin\instructor\InstructorController@store')->name('CPA_instructor_store');
            Route::post('upload','company\admin\instructor\InstructorController@upload')->name('CPA_instructor_upload');
            Route::get('list','company\admin\instructor\InstructorController@list')->name('CPA_instructor_list');
            Route::get('edit/{id?}','company\admin\instructor\InstructorController@edit')->name('CPA_instructor_edit');
            Route::post('update{id?}','company\admin\instructor\InstructorController@update')->name('CPA_instructor_update');
            Route::get('delete/{id?}','company\admin\instructor\InstructorController@delete')->name('CPA_instructor_delete');
        });

        // route for company admin access of company department
        Route::group(['prefix' => 'department'],function(){
            Route::get('create','company\admin\department\DepartmentController@create')->name('CPA_department_create');
            Route::post('store','company\admin\department\DepartmentController@store')->name('CPA_department_store');
            Route::get('list','company\admin\department\DepartmentController@index')->name('CPA_department_list');
        });

        // route for company admin access of company employee
        Route::group(['prefix' => 'employee'],function(){
            Route::get('create','company\admin\employee\EmployeeController@create')->name('CPA_employee_create');
            Route::post('store','company\admin\employee\EmployeeController@store')->name('CPA_employee_store');
            Route::post('upload','company\admin\employee\EmployeeController@upload')->name('CPA_employee_upload');
            Route::get('list','company\admin\employee\EmployeeController@list')->name('CPA_employee_list');
            Route::get('employee_completed_course/{employee_id?}/{from_date?}/{to_date?}','company\instructor\seminar\SeminarController@employeeCompletedSeminar')->name('completed_employee_seminar_list');
            Route::get('employee_pending_course/{employee_id?}/{from_date?}/{to_date?}','company\instructor\seminar\SeminarController@employeePendingSeminar')->name('pending_employee_seminar_list');

            Route::get('edit/{id?}','company\admin\employee\EmployeeController@edit')->name('CPA_employee_edit');
            Route::post('update/{id?}','company\admin\employee\EmployeeController@update')->name('CPA_employee_update');
            Route::get('delete/{id?}','company\admin\employee\EmployeeController@delete')->name('CPA_employee_delete');
        });

        // route for company admin access of company employee
        Route::group(['prefix' => 'score'],function(){
            Route::get('score/{from_date?}/{to_date?}','company\admin\DashboardController@EmployeeScore')->name('CPA_employee_score');
        });

        Route::group(['prefix' => 'seminar'],function(){
            Route::get('list','company\admin\DashboardController@SeminarList')->name('CPA_seminar_list');
            Route::get('completed_seminar_list/{seminar_id?}','company\admin\DashboardController@CompletedSeminarList')->name('completed_seminar_list');
            Route::get('all_completed_seminar/{from_date?}/{to_date?}','company\admin\DashboardController@AllCompletedSeminar')->name('all_completed_seminar');
            Route::get('all_pending_seminar/{from_date?}/{to_date?}','company\admin\DashboardController@AllPendingSeminar')->name('all_pending_seminar');
            
            Route::get('all_pending_course','company\admin\DashboardController@AllPendingCourse')->name('all_pending_course');
            Route::get('all_completed_course/{from_date?}/{to_date?}','company\admin\DashboardController@AllCompletedCourse')->name('all_completed_course');
            Route::get('all_admin_employee_completed_course/{user_id?}/{from_date?}/{to_date?}','company\admin\DashboardController@AllEmployeeCompletedCourse')->name('all_admin_employee_completed_course');
        });

        Route::group(['prefix' => 'request-course'],function(){
            Route::get('request-course','company\admin\DashboardController@requestCourse')->name('request_course');
            Route::post('submit-request-course','company\admin\DashboardController@submitRequestCourse')->name('submit_request_course');
            Route::get('list-request-course','company\admin\DashboardController@listRequestCourse')->name('list_request_course');
            Route::get('list-all-course','company\admin\DashboardController@listAllCourse')->name('list_all_course');
        });
    });

    // route for company instroctor
    Route::group(['prefix' => 'instroctor'],function(){

        Route::group(['prefix' => 'seminar'],function(){
            $path = 'company\instructor\seminar';
            Route::post('sem-load-section-items', $path.'\SeminarController@loadContents')->name('sem_load_contents');
            
            Route::get('new',$path.'\SeminarController@index')->name('companySeminarNew');
            Route::post('store',$path.'\SeminarController@store')->name('companySeminarNewStore');

            Route::get('{seminar_id}/information', $path.'\SeminarController@information')->name('companySeminarEditInformation');
            Route::post('{seminar_id}/information', $path.'\SeminarController@informationPost');

            Route::group(['prefix' => '{seminar_id}/curriculum' ], function() use ($path) {
                Route::get('', $path.'\SeminarController@curriculum')->name('CS_edit_curriculum');

                Route::get('new-section', $path.'\SeminarController@newSection')->name('CS_new_section');
                Route::post('new-section', $path.'\SeminarController@newSectionPost');

                Route::post('new-lecture', $path.'\SeminarController@newLecture')->name('CS_new_lecture');
                Route::post('update-lecture/{id}', $path.'\SeminarController@updateLecture')->name('CS_update_lecture');

                Route::group(['prefix' => 'quiz' ], function() use ($path) {
                    Route::post('create', $path.'\QuizController@newQuiz')->name('CS_new_quiz');
                    Route::post('update/{id}', $path.'\QuizController@updateQuiz')->name('CS_update_quiz');

                    Route::post('{quiz_id}/create-question', $path.'\QuizController@createQuestion')->name('CS_create_question');
                });
            });

            Route::post('update-section/{id}', $path.'\SeminarController@updateSection')->name('CS_update_section');
            Route::post('delete-section', $path.'\SeminarController@deleteSection')->name('CS_delete_section');

            Route::post('quiz/edit-question', $path.'\QuizController@editQuestion')->name('CS_edit_question_form');
            Route::post('quiz/update-question', $path.'\QuizController@updateQuestion')->name('CS_edit_question');
            Route::post('cs_load-quiz-questions', $path.'\QuizController@loadQuestions')->name('CS_load_questions');
            Route::post('sort-questions', $path.'\QuizController@sortQuestions')->name('CS_sort_questions');
            Route::post('delete-question', $path.'\QuizController@deleteQuestion')->name('CS_delete_question');
            Route::post('delete-option', 'QuizController@deleteOption')->name('CS_option_delete');

            Route::post('edit-item', $path.'\SeminarController@editItem')->name('CS_edit_item_form');
            Route::post('delete-item', $path.'\SeminarController@deleteItem')->name('CS_delete_item');

            Route::get('{id}/publish', $path.'\SeminarController@publish')->name('CS_publish_course');
            Route::post('{id}/publish', $path.'\SeminarController@publishPost');

            Route::get('my-seminar', $path.'\SeminarController@mySeminar')->name('my_seminars');

            Route::get('seminar-content_complete/{content_id}', $path.'\SeminarController@contentComplete')->name('seminar_content_complete');
            Route::post('seminar-courses-complete/{course_id}', $path.'\SeminarController@complete')->name('seminar_course_complete');

        });
        
        // route for company admin access of company employee
        Route::group(['prefix' => 'score'],function(){
            Route::get('score/{from_date?}/{to_date?}','company\instructor\DashboardController@EmployeeScore')->name('dh_employee_score');
        });

        Route::get('dashboard','company\instructor\DashboardController@index')->name('companyInstructorDashboard');

        Route::get('all_instructor_completed_seminar/{from_date?}/{to_date?}','company\instructor\DashboardController@AllCompletedSeminar')->name('all_instructor_completed_seminar');
        Route::get('all_instructor_pending_seminar/{from_date?}/{to_date?}','company\instructor\DashboardController@AllPendingSeminar')->name('all_instructor_pending_seminar');

        // course
        Route::get('all_pending_course','company\instructor\DashboardController@AllPendingCourse')->name('instructor_all_pending_course');
        Route::get('all_completed_course/{from_date?}/{to_date?}','company\instructor\DashboardController@AllCompletedCourse')->name('instructor_all_completed_course');
        Route::get('all_department_course','company\instructor\DashboardController@listAllCourse')->name('instructor_all_department_course');

    });
    // route for company employee
    Route::group(['prefix' => 'employee'],function(){
        Route::get('dashboard','company\employee\DashboardController@index')->name('companyEmployeeDashboard');
        Route::get('all_seminar','company\admin\employee\EmployeeController@allSeminar')->name('CPA_all_seminar');
        // Route::get('attempted_seminar','company\admin\employee\EmployeeController@attemptedSeminar')->name('CPA_attempted_seminar');

        Route::get('all_employee_completed_seminar/{from_date?}/{to_date?}','company\employee\DashboardController@AllCompletedSeminar')->name('all_employee_completed_seminar');
        Route::get('all_employee_pending_seminar/{from_date?}/{to_date?}','company\employee\DashboardController@AllPendingSeminar')->name('all_employee_pending_seminar');

        Route::get('all_employee_pending_course/{from_date?}/{to_date?}','company\employee\DashboardController@AllPendingCourse')->name('all_employee_pending_course');
        Route::get('all_employee_completed_course/{from_date?}/{to_date?}','company\employee\DashboardController@AllCompletedCourse')->name('all_employee_completed_course');
    });

    $path = 'company\instructor\seminar';
    Route::get('seminar/{slug?}', $path.'\SeminarController@view')->name('seminar_view');
    Route::get('seminar_leture/{slug}/lecture/{lecture_id}', $path.'\SeminarController@lectureView')->name('seminar_single_lecture');
    Route::get('seminar_leture/{slug}/quiz/{quiz_id}', $path.'\QuizController@quizView')->name('seminar_single_quiz');
    Route::post('seminar/free-enroll', $path.'\SeminarController@freeEnroll')->name('seminar_free_enroll');
    
    Route::post('seminar_quiz-start', $path.'\QuizController@start')->name('seminar_start_quiz');
    Route::get('seminar_quiz/{id}', $path.'\QuizController@quizAttempting')->name('seminar_quiz_attempt_url');
    Route::post('seminar_quiz/{id}', $path.'\QuizController@answerSubmit');
    Route::get('seminar_re-quiz/{id}', $path.'\QuizController@reattendQuiz')->name('seminar_quiz_re-quiz');


});


/**
 * Single Page
 */
//Route::get('{slug}', 'PostController@singlePage')->name('page');

Route::get('blog', 'PostController@blog')->name('blog');
Route::get('{slug}', 'PostController@postSingle')->name('post');
Route::get('post/{id?}', 'PostController@postProxy')->name('post_proxy');

// collage-quiz-submit-buy student
Route::get('collage_quiz_submit/{encryptString?}','CollegeQuizController@collegeQuizViewSection')->name('collageQuizSubmit');
Route::post('collage_quiz_submit','CollegeQuizController@collegeQuizSubmit');
Route::post('college_quiz_start','CollegeQuizController@collegeQuizStart');

Route::get('{id}/export_student_report', 'ExportReport@exportStudentReport');
Route::get('{id}/{quiz_id}/export_perticular_student_report', 'ExportReport@exportPerticularStudentReport');



// plugin live-zoom
Route::group(['prefix'=>'admin', 'middleware' => ['auth', 'admin'] ], function() {
    Route::group(['prefix'=>'settings'], function() {
        Route::get('live-class', 'zoom\LiveClassController@settings')->name('live_class_settings');
    });
});

Route::group(['prefix'=>'dashboard', 'middleware' => ['auth'] ], function() {
    /**
     * Only instructor has access in this group
     */
    Route::group(['middleware' => ['instructor'] ], function() {
        Route::group(['prefix' => 'courses/zoom' ], function() {
            Route::group(['prefix' => '{course_id}/live_class' ], function() {

                Route::get('/', 'zoom\LiveClassController@lessonLiveSettings')->name('edit_course_live_class');
                Route::post('/', 'zoom\LiveClassController@lessonLiveSettingsPost');

            });
        });
    });

});

Route::get('courses-live/{slug}/live-class', 'zoom\LiveClassController@liveClassStream')->name('live_class_stream');