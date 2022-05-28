@extends('layouts.theme')

@section('content')

@foreach($seminar->sections as $section)
    @if($section->items->count())
        @foreach($section->items as $item)
            @php
                $continue_url = route('seminar_single_'.$item->item_type, [$seminar->slug, $item->id ] );
                break;
            @endphp
        @endforeach
    @endif
@endforeach

@php
// dd($continue_url);
$contine_url = $continue_url;
@endphp

<div class="page-header-jumborton py-5">

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="page-header-left">
                    <h1>{{clean_html($seminar->title)}}</h1>
                    @if($seminar->short_description)
                    <p class="page-header-subtitle m-0">{{clean_html($seminar->short_description)}}</p>
                    @endif
                    <p>
                        <span class="created-by mr-3">
                            <i class="la la-user"></i> {{__t('created_by')}} {{$seminar->author->name}}
                        </span>
                    </p>
                </div>
            </div>

            <div class="col-md-4">

                <div class="page-header-right-enroll-box p-3 mt-sm-4 mt-md-0 bg-white shadow">

                    @if($isEnrolled)

                    <p class="text-muted"><strong>Enrolled At</strong> :
                        {{date('F d, Y', strtotime($isEnrolled->enrolled_at))}} </p>

                    <a href="{{$contine_url}}" class="btn btn-info btn-lg btn-block"><i class="la la-play-circle"></i>
                        Continue Seminar</a>

                    @else

                        <div class="course-landing-page-price-wrap">
                            {!! $seminar->price_html(false, true) !!}
                        </div>
                        <form action="{{route('seminar_free_enroll')}}" class="course-free-enroll" method="post">
                            @csrf
                            <input type="hidden" name="seminar_id" value="{{$seminar->id}}">
                            <button type="submit" class="btn btn-warning btn-lg btn-block">{{__t('Enroll seminar now')}}</button>
                        </form>

                    @endif
                </div>

            </div>

        </div>
    </div>

</div>


<div class="container my-4">

    <div class="row">
        <div class="col-md-10 offset-md-1">


            <div class="course-details-wrap">


                <div class="course-intro-stats-wrapper mb-4">

                    <div class="row">
                        <div class="col-md-6">

                            <div class="course-whats-included-box course-widget p-4">
                                <h4 class="mb-4">Whats Included</h4>

                                @php
                                    $lectures_count = $seminar->lectures->count();
                                @endphp

                                <ul>
                                    @if($seminar->total_video_time)
                                    <li> <i class="la la-video"></i>
                                        {{seconds_to_time_format($seminar->total_video_time)}} {{__t('on_demand_video')}}
                                    </li>
                                    @endif

                                    <li> <i class="la la-book"></i> {{$lectures_count}} {{__t('lectures')}} </li>

                                    <li> <i class="la la-mobile"></i> Access on tablet and phone </li>

                                    <li> <i class="la la-certificate"></i> Certificate of completion </li>
                                </ul>
                            </div>

                        </div>

                        <div class="col-md-6">
                            
                            @if($seminar->video_info())

                            @if ($seminar->video_info()['html5_video_id'] != NULL)
                                @include(theme('video-player'), ['model' => $seminar])
                            @else
                                <img src="{{media_image_uri($seminar->thumbnail_id)->image_md}}" class="img-fluid" />
                            @endif
                           
                            @else
                            <img src="{{media_image_uri($seminar->thumbnail_id)->image_md}}" class="img-fluid" />
                            @endif


                        </div>
                    </div>

                </div>



                @if($seminar->benefits_arr)
                <div class="course-widget mb-4 p-4">
                    <h4 class="mb-4">What Learn </h4>

                    <div class="content-expand-wrap">
                        <div class="content-expand-inner">
                            <ul class="benefits-items row">
                                @foreach($seminar->benefits_arr as $benefit)
                                <li class="col-12 benefit-item d-flex mb-2">
                                    <i class="la la-check-square"></i>
                                    <span class="benefit-item-text ml-2">{{$benefit}}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                @if($seminar->sections->count())

                <div class="course-curriculum-header d-flex mt-5">
                    <h4 class="mb-4 course-curriculum-title flex-grow-1">Course Curriculum</h4>

                    <p id="expand-collapse-all-sections">
                        <a href="javascript:;" data-action="expand">Expand all</a>
                        <a href="javascript:;" data-action="collapse" style="display: none;">Collapse all</a>
                    </p>

                    <p class="ml-3 course-total-lectures-info">{{$seminar->total_lectures}} {{__t('lectures')}}</p>
                    <p class="ml-3 mr-3 course-runtime-info"> {{seconds_to_time_format($seminar->total_video_time)}}</p>
                </div>

                <div class="course-curriculum-wrap mb-4">

                    @foreach($seminar->sections as $section)

                    <div id="course-section-{{$section->id}}" class="course-section bg-white border mb-2">

                        <div class="course-section-header bg-light p-3 border-bottom d-flex">
                            <span class="course-section-name flex-grow-1 ml-2">
                                <strong>
                                    {{-- {{$loop->first ? 'minus' : 'plus'}} --}}
                                    <i class="la la-minus"></i>
                                    {{$section->section_name}}
                                </strong>
                            </span>

                            <span class="course-section-lecture-count">
                                {{$section->items->count()}} {{__t('lectures')}}
                            </span>
                        </div>
                        {{-- {{$loop->first ? 'block' : 'none'}} --}}
                        <div class="course-section-body" style="display: block;">

                            @if($section->items->count())
                                @foreach($section->items as $item)
                                    <div class="course-curriculum-item border-bottom pl-4 d-flex">
                                        <p class="curriculum-item-title m-0 flex-grow-1">

                                            <a href="{{route('seminar_single_'.$item->item_type, [$seminar->slug, $item->id ] )}}">
                                                <span class="curriculum-item-icon mr-2">
                                                    {!! $item->icon_html !!}
                                                </span>
                                                <span class="curriculum-item-title">
                                                    {{clean_html($item->title)}}
                                                </span>
                                            </a>
                                        </p>

                                        <p class="course-section-item-details d-flex m-0">
                                            <span class="section-item-preview flex-grow-1">
                                                @if($item->is_preview)
                                                <a href="{{route('seminar_single_lecture', [$seminar->slug, $item->id ] )}}">
                                                    <i class="la la-eye"></i> {{__t('preview')}}
                                                </a>
                                                @endif
                                            </span>


                                            <span class="section-item-duration ml-auto">
                                                {{$item->runtime}}
                                            </span>
                                        </p>

                                    </div>
                                @endforeach
                            @endif

                        </div>

                    </div>
                    @endforeach

                </div>
                @endif

                @if($seminar->requirements_arr)
                    <h4 class="mb-4">Requirements</h4>

                    <div class="course-widget mb-4 p-4">
                        <ul class="benefits-items row">
                            @foreach($seminar->requirements_arr as $requirement)
                            <li class="col-6 benefit-item d-flex mb-2">
                                <i class="la la-info-circle"></i>
                                <span class="benefit-item-text ml-2">{{$requirement}}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($seminar->description)
                    <div class="course-description mt-4 mb-5">
                        <h4 class="mb-4 course-description-title">Short description</h4>

                        <div class="content-expand-wrap">
                            <div class="content-expand-inner">
                                {!! $seminar->description !!}
                            </div>
                        </div>
                    </div>
                @endif

            </div>

        </div>

    </div>

</div>



@endsection