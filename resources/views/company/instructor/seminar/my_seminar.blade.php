@extends('layouts.company')

@section('page-header-right')
@endsection

@section('page-css')
<style>
    .company-header
    {
        padding: 20px 10px;
        background: #b6a4298a;
        font-weight: 600;
        margin-top: 20px;
        margin-bottom: 20px;
    }
    .company-header h5 span
    {
        font-weight: 600;
    }

    .form-body
    {
        margin-top: 20px;
        background: #8080806b;
        padding: 20px;
    }
    .form-body .title{
        font-weight: 600;
        border-bottom: 1px solid white;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
</style>
@endsection

@section('content')
    @if(session()->has('message'))
        <div class="alert alert-success">
            <h4>{{ session()->get('message') }}</h4>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <h4>{{$errors->first()}}</h4>
        </div>
    @endif

    {{-- <div class="company-header">
        <h5>  My Seminar </h5>
    </div> --}}

    <div class="form-body">
        <div class="my_courses_div">
            @if($seminars->count())
                <table class="table table-bordered bg-white">

                    <tr>
                        <th>{{__t('thumbnail')}}</th>
                        <th>{{__t('title')}}</th>
                        {{-- <th>{{__t('status')}}</th> --}}
                    </tr>

                    @foreach($seminars as $course)
                        <tr>
                            <td>
                                <img src="{{$course->thumbnail_url}}" width="80" />
                            </td>
                            <td>
                                <p class="mb-3">
                                    <strong>{{$course->title}}</strong>
                                    {!! $course->status_html() !!}
                                </p>

                                <p class="m-0 text-muted">
                                    @php
                                        $lectures_count = $course->lectures->count();
                                        $quizzes_count = $course->quizzes->count();
                                    @endphp

                                    <span class="course-list-lecture-count">{{$lectures_count}} {{__t('lectures')}}</span>

                                    @if($quizzes_count)
                                        , <span class="course-list-quiz-count">{{$quizzes_count}} {{__t('quizzes')}}</span>
                                    @endif
                                </p>

                                <div class="courses-action-links mt-1">
                                    @if ($course->status == 0)
                                         <a href="{{route('companySeminarEditInformation', $course->id)}}" class="font-weight-bold mr-3">
                                            <i class="la la-pencil-square-o"></i> {{__t('edit')}}
                                        </a>

                                    @endif
                                   
                                    <a href="{{route('completed_seminar_list', $course->id)}}" class="font-weight-bold mr-3">
                                        <i class="la la-pencil-square-o"></i> 
                                        Completed Seminar Information
                                    </a>

                                    {{-- @if($course->status == 1)
                                        <a href="{{route('course', $course->slug)}}" class="font-weight-bold mr-3" target="_blank"><i class="la la-eye"></i> {{__t('view')}} </a>
                                    @else
                                        <a href="{{route('course', $course->slug)}}" class="font-weight-bold mr-3" target="_blank"><i class="la la-eye"></i> {{__t('preview')}} </a>
                                    @endif --}}

                                    {{-- @php do_action('my_courses_list_actions_after', $course); @endphp --}}

                                </div>
                            </td>
                            {{-- <td>{!! $course->price_html() !!}</td> --}}

                        </tr>

                    @endforeach

                </table>
            @else
                {!! no_data(null, null, 'my-5' ) !!}
                <div class="no-data-wrap text-center">
                    <a href="{{route('companySeminarNew')}}" class="btn btn-lg btn-warning">{{__t('create seminar')}}</a>
                </div>
            @endif

        </div>        
    </div>
@endsection

@section('page-js')
    {{-- <script src="{{ asset('assets/js/filemanager.js') }}"></script> --}}
@endsection