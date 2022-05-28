@php
$previous = $content->previous;
$next = $content->next($content->id,$content->seminar_id);
$is_completed = false;
if ($auth_user && $content->is_completed){
$is_completed = true;
}

if(isset($quiz) && $quiz == 1){

$attempt = $auth_user->get_attempt($content->id);
$passing_percent = 0;
if($attempt != null){
if($attempt->passed == 0){
$is_completed = false;
}
}
// dd($attempt);
}
@endphp
<div class="lecture-header d-flex">
    <div class="lecture-header-left d-flex">
        <a href="{{route('seminar_view', $course->slug)}}" class="back-to-curriculum" data-toggle="tooltip"
            title="{{__t('Go to seminar')}}">
            <i class="la la-angle-left"></i>
        </a>

        <a href="javascript:;" class="nav-icon-list d-sm-block d-md-none d-lg-none"><i class="la la-list"></i> </a>

        @if($auth_user && ! $auth_user->is_completed_seminar($course->id) && $course->completed_percent() == 100)

        <form action="{{route('seminar_course_complete', $course->id)}}" method="post" class="ml-auto">
            @csrf
            <button type="submit" href="javascript:;" class="nav-icon-complete-course btn btn-success ml-auto"
                data-toggle="tooltip" title="Completed Seminar">
                <i class="la la-check-circle"></i>
            </button>
        </form>
        @endif
    </div>
    <div class="lecture-header-right d-flex">

        @if($previous)
        <a class="nav-btn" href="{{route('seminar_single_'.$previous->item_type, [$course->slug, $previous->id ] )}}"
            id="lecture_previous_button">

            <span class="nav-text">
                <i class="la la-arrow-left"></i>
                {{__t('previous')}} {{__t($previous->item_type)}}
            </span>
        </a>
        @else
        <a class="nav-btn disabled" id="lecture_previous_button">
            <span class="nav-text"><i class="la la-arrow-left"></i>{{__t('previous')}}</span>
        </a>
        @endif

        @if($next)
        @if($content->item_type === 'lecture')
        <a class="nav-btn" href="{{route('seminar_content_complete', $content->id )}}" id="lecture_complete_button">
            <span class="nav-text">
                @if($is_completed)
                {{__t('next')}} {{$next ? __t($next->item_type) : ''}}
                @else
                {{__t('complete_continue')}}
                @endif

                <i class="la la-arrow-right"></i>
            </span>
        </a>
        @else
        <a class="nav-btn" href="{{route('seminar_single_'.$next->item_type, [$course->slug, $next->id ] )}}"
            id="lecture_complete_button">
            <span class="nav-text">{{__t('next')}} {{$next ? __t($next->item_type) : ''}} <i
                    class="la la-arrow-right"></i></span>

        </a>
        @endif
        @else

        @if($content->item_type === 'lecture')
        @if($is_completed)
        <a class="nav-btn disabled" id="lecture_complete_button">
            <span class="nav-text">{{__t('complete')}} </span>
        </a>
        @else
        <a class="nav-btn" href="{{route('seminar_content_complete', $content->id)}}" id="lecture_complete_button">
            <span class="nav-text"> <i class="la la-check-circle"></i> {{__t('complete')}} </span>
        </a>
        @endif
        @else
        <a class="nav-btn disabled" id="lecture_complete_button">
            <span class="nav-text">{{__t('next')}} <i class="la la-arrow-right"></i></span>
        </a>
        @endif

        @endif

    </div>
</div>