<?php
$gridClass = $grid_class ? $grid_class : 'col-md-3';
?>
<style>
    .azxster-seminar-card:hover
    {
        box-shadow: 2px 3px 20px 8px #666666 !important;
    }
    .azxster-seminar-title
    {
        font-size: 16px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        line-height: 22px;
        height: 44px;
        color: black;
        font-weight: 600;
    }
</style>

<div class="{{$gridClass}} course-card-grid-wrap " style="word-break: break-word; " >
    <div class="course-card azxster-seminar-card " style="box-shadow: 2px 3px 5px 0 #666666; padding: 10px;" >

        <div class="course-card-img-wrap">
            <a href="{{route('seminar_view', $course->slug)}}">
                <img src="{{$course->thumbnail_url}}" class="img-fluid" style="width: 100%;" />
            </a>
        </div>

        <div class="course-card-contents" style="padding: 10px; ">
            <a href="{{route('seminar_view', $course->slug)}}">
                <h4 class="course-card-title mb-3 azxster-seminar-title">{{ $course->title }}</h4>
                <p class="course-card-short-info mb-2 d-flex justify-content-between">
                    <span style="color: #29303b;">
                        <i class="la la-play-circle"></i> 
                        {{$course->total_lectures}} {{__t('lectures')}}
                    </span>
                    <span style="color: #29303b;">
                        <i class="la la-question"></i> 
                        {{$course->total_quiz}} {{__t('quiz')}}
                    </span>
                </p>
            </a>
        </div>

        <div class="course-card-footer mt-3">
            <div class="course-card-cart-wrap d-flex justify-content-between">
                <div class="course-card-btn-wrap" style="width: 100%;">
                    @if ($course->completedSeminar($course->id, Auth::user()->id))
                        <a href="{{ route('course', $course->slug) }}">
                            <button type="button" class="btn btn-sm btn-theme-primary add-to-cart-btn"
                                style="color: #2073d4 !important; background-color: #f9f9f9; border-color: #2073d4; width: 100%;">
                                Completed
                            </button>
                        </a>
                    @elseif($auth_user && in_array($course->id, $auth_user->get_option('enrolled_courses', []) ))
                        <a href="{{route('seminar_view', $course->slug)}}">
                            <button type="button" class="btn btn-sm btn-theme-primary add-to-cart-btn" style="color: #2073d4 !important; background-color: #f9f9f9; border-color: #2073d4; width: 100%;"> {{__t('enrolled')}}
                            </button>
                        </a>
                    @else
                    @php($in_cart = cart($course->id))
                        <a href="{{route('seminar_view', $course->slug)}}">
                            <button type="button" class="btn btn-sm btn-theme-primary add-to-cart-btn" style="color: #fff !important; background-color: #2073d4; border-color: #2073d4; width: 100%;"> Start Seminar
                            </button>
                        </a>
                    @endif
                </div>
            </div>
            </div>
    </div>
</div>