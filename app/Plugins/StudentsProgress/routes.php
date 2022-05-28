<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix'=>'dashboard', 'middleware' => ['auth'] ], function() {
    Route::group(['middleware' => ['instructor']], function () {

        Route::group(['prefix' => 'students-progress' ], function() {
            Route::get('/{course_id?}', 'StudentProgressController@index')->name('student_progress');
            Route::get('{course_id}/detail/{user_id}', 'StudentProgressController@details')->name('progress_report_details');
        });

    });
});

