<?php

function dashboard_menu(){
    $menu = [];

    //$menu['route_name'] = 'value';


    $user = \Illuminate\Support\Facades\Auth::user();

    if ($user->isInstructor()) {

        $pendingDiscusionBadge = '';
        $pendingDiscussionCount = $user->instructor_discussions->where('replied', 0)->count();
        if ($pendingDiscussionCount){
            $pendingDiscusionBadge = "<span class='badge badge-warning float-right'> {$pendingDiscussionCount} </span>";
        }

        $menu = apply_filters('dashboard_menu_for_instructor', [
            'create_course' => [
                'name' => __t('create new course'),
                'icon' => '<i class="la la-chalkboard-teacher"></i>',
                'is_active' => request()->is('dashboard/courses/new'),
            ],
            'my_courses' => [
                'name' => __t('my courses'),
                'icon' => '<i class="la la-graduation-cap"></i>',
                'is_active' => request()->is('dashboard/my-courses'),
            ],
            'earning' => [
                'name' => __t('earnings'),
                'icon' => '<i class="la la-comment-dollar"></i>',
                'is_active' => request()->is('dashboard/earning*')
            ],
            'withdraw' => [
                'name' => __t('withdraw'),
                'icon' => '<i class="la la-wallet"></i>',
                'is_active' => request()->is('dashboard/withdraw*'),
            ],
            'my_courses_reviews' => [
                'name' => __t('my courses reviews'),
                'icon' => '<i class="la la-star"></i>',
                'is_active' => request()->is('dashboard/my-courses-reviews*'),
            ],
            'courses_has_quiz' => [
                'name' => __t('quiz attempts'),
                'icon' => '<i class="la la-check-double"></i>',
                'is_active' => request()->is('dashboard/courses-has-quiz*'),
            ],
            'courses_has_assignments' => [
                'name' => __t('assignments'),
                'icon' => '<i class="la la-star"></i>',
                'is_active' => request()->is('dashboard/assignments*'),
            ],
            'instructor_discussions' => [
                'name' => __t('discussions') . $pendingDiscusionBadge,
                'icon' => '<i class="la la-question-circle-o"></i>',
                'is_active' => request()->is('dashboard/discussions*'),
            ],
            'affilite_marketing' => [
                'name' => 'Affiliate Link',
                'icon' => '<i class="la la-tools"></i>',
                'is_active' => request()->is('channel_partner/affilite_marketing'),
            ]
        ]);

    }

    $menu = $menu + apply_filters('dashboard_menu_for_users', [
        'enrolled_courses' => [
            'name' => __t('enrolled courses'),
            'icon' => '<i class="la la-pencil-square-o"></i>',
            'is_active' => request()->is('dashboard/enrolled-courses*'),
        ],
        'wishlist' => [
            'name' => __t('wishlist'),
            'icon' => '<i class="la la-heart-o"></i>',
            'is_active' => request()->is('dashboard/wishlist*'),
        ],
        'reviews_i_wrote' => [
            'name' => __t('reviews'),
            'icon' => '<i class="la la-star-half-alt"></i>',
            'is_active' => request()->is('dashboard/reviews-i-wrote*'),
        ],
        'my_quiz_attempts' => [
            'name' => __t('my quiz attempts'),
            'icon' => '<i class="la la-question-circle-o"></i>',
            'is_active' => request()->is('dashboard/my-quiz-attempts*'),
        ],
        'purchase_history' => [
            'name' => __t('purchase history'),
            'icon' => '<i class="la la-history"></i>',
            'is_active' => request()->is('dashboard/purchases*'),
        ],
        'profile_settings' => [
            'name' => __t('settings'),
            'icon' => '<i class="la la-tools"></i>',
            'is_active' => request()->is('dashboard/settings*'),
        ],
        // 'college_quiz' => [
        //     'name' => __t('college quiz'),
        //     'icon' => '<i class="la la-tools"></i>',
        //     'is_active' => request()->is('dashboard/college_quiz*'),
        // ],
    ]);

    if ($user->user_type == 'company-admin') {
        $menu = $menu + apply_filters('dashboard_menu_for_users', [
            'companyAdminDashboard' => [
                'name' => __t('Assigned Courses'),
                'icon' => '<i class="la la-building"></i>',
                'is_active' => request()->is('dashboard/enrolled-courses*'),
            ],
        ]);
    }

    if ($user->user_type == 'company-admin-user') {
        $menu = $menu + apply_filters('dashboard_menu_for_users', [
            'companyDashboard' => [
                'name' => __t('Company Dashboard'),
                'icon' => '<i class="la la-building"></i>',
                'is_active' => request()->is('dashboard/enrolled-courses*'),
            ],
        ]);
    }

    if ($user->user_type == 'company-instructor') {
        $menu = $menu + apply_filters('dashboard_menu_for_users', [
            'companyInstructorDashboard' => [
                'name' => __t('Assigned Courses'),
                'icon' => '<i class="la la-building"></i>',
                'is_active' => request()->is('dashboard/enrolled-courses*'),
            ],
        ]);
    }

    if ($user->user_type == 'company-employee') {
        $menu = $menu + apply_filters('dashboard_menu_for_users', [
            'companyEmployeeDashboard' => [
                'name' => __t('Assigned Courses'),
                'icon' => '<i class="la la-building"></i>',
                'is_active' => request()->is('dashboard/enrolled-courses*'),
            ],
        ]);
    }

    if ($user->is_admin){
        $menu['admin'] = [
            'name' => __t('go to admin'),
            'icon' => '<i class="la la-cogs"></i>',
        ];

    }

    if ($user->user_type === 'channel_partner'){
        $menu = [];
        $menu['profile_settings'] = [
            'name' => __t('settings'),
            'icon' => '<i class="la la-tools"></i>',
            'is_active' => request()->is('dashboard/settings*'),
        ];
        $menu['cp_affilite_marketing'] = [
            'name' => 'Affiliate Link',
            'icon' => '<i class="la la-tools"></i>',
            'is_active' => request()->is('channel_partner/cp_affilite_marketing'),
        ];
        $menu['cp_coupons'] = [
            'name' => 'Coupons',
            'icon' => '<i class="la la-tools"></i>',
            'is_active' => request()->is('channel_partner/coupon/create'),
        ];
        $menu['cp_interactive_course_coupons'] = [
            'name' => 'Interactive Course Coupons',
            'icon' => '<i class="la la-tools"></i>',
            'is_active' => request()->is('channel_partner/coupon/cp_interactive_course_coupons'),
        ];
        $menu['cp_interactive_course_student'] = [
        'name' => 'Interactive Course Enrollment',
        'icon' => '<i class="la la-tools"></i>',
        'is_active' => request()->is('channel_partner/coupon/cp_interactive_course_enrollment'),
        ];
    }

    if ($user->college_user_type == 2) {
        $menu = apply_filters('dashboard_menu_for_instructor',[
            'create_quiz' => [
                'name' => __t('Create College Quiz'),
                'icon' => '<i class="la la-chalkboard-teacher"></i>',
                'is_active' => request()->is('dashboard/college_quiz/new'),
            ],
            'collage_quiz_list' => [
            'name' => __t('My Created College Quiz'),
            'icon' => '<i class="la la-sign-out"></i>',
            'is_active' => request()->is('dashboard/college_quiz/collage_quiz_list'),
            ],
            'profile_settings' => [
            'name' => __t('settings'),
            'icon' => '<i class="la la-tools"></i>',
            'is_active' => request()->is('dashboard/settings*'),
            ],
        ]);
    }

    if ($user->college_user_type == 1) {
        $menu = apply_filters('dashboard_menu_for_instructor',[
            'create_collage_instructor' => [
            'name' => __t('Create College Instructor'),
            'icon' => '<i class="la la-sign-out"></i>',
            'is_active' => request()->is('dashboard/create_collage_instructor'),
            ],
            'profile_settings' => [
            'name' => __t('settings'),
            'icon' => '<i class="la la-tools"></i>',
            'is_active' => request()->is('dashboard/settings*'),
            ],
        ]);
    }

    return apply_filters('dashboard_menu_items', $menu);
}


function course_edit_navs(){

    $nav_items = apply_filters('course_edit_nav_items', [
        'edit_course_information' => [
            'name' => __t('information'),
            'icon' => '<i class="la la-info-circle"></i>',
            'is_active' => request()->is('dashboard/courses/*/information'),
        ],
        'edit_course_curriculum' => [
            'name' => __t('curriculum'),
            'icon' => '<i class="la la-th-list"></i>',
            'is_active' => request()->is('dashboard/courses/*/curriculum'),
        ],
        'edit_course_pricing' => [
            'name' => __t('pricing'),
            'icon' => '<i class="la la-cart-arrow-down"></i>',
            'is_active' => request()->is('dashboard/courses/*/pricing'),
        ],
        'edit_course_drip' => [
            'name' => __t('drip'),
            'icon' => '<i class="la la-fill-drip"></i>',
            'is_active' => request()->is('dashboard/courses/*/drip'),
        ],

    ]);

    return $nav_items;
}

function seminar_edit_navs(){

    $nav_items = apply_filters('course_edit_nav_items', [
        'edit_course_information' => [
            'name' => __t('information'),
            'icon' => '<i class="la la-info-circle"></i>',
            'is_active' => request()->is('dashboard/courses/*/information'),
        ],
        'edit_course_curriculum' => [
            'name' => __t('curriculum'),
            'icon' => '<i class="la la-th-list"></i>',
            'is_active' => request()->is('dashboard/courses/*/curriculum'),
        ],
        'edit_course_pricing' => [
            'name' => __t('pricing'),
            'icon' => '<i class="la la-cart-arrow-down"></i>',
            'is_active' => request()->is('dashboard/courses/*/pricing'),
        ],
        'edit_course_drip' => [
            'name' => __t('drip'),
            'icon' => '<i class="la la-fill-drip"></i>',
            'is_active' => request()->is('dashboard/courses/*/drip'),
        ],

    ]);

    return $nav_items;
}