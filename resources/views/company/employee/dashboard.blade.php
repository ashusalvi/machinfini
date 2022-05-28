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
</style>
@endsection

@section('content')
    @if(session()->has('message'))
        <div class="alert alert-success">
            <h4>{{ session()->get('message') }}</h4>
        </div>
    @endif

    <div class="company-header">
        <h5>  Hi <span>{{ Auth::user()->name }}</span>  </h5>
        {{-- <h5>  Hi  </h5> --}}
    </div>

    <div>
        <div class="row">

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-chalkboard-teacher"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value">
                            <h4> {{ $companySeminars->count() }} </h4>
                        </div>
                        <div>Seminar</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-chalkboard-teacher"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value">
                            <h4>{{ $enroller_seminar }}</h4>
                        </div>
                        <div>Enrolled Seminar</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-clipboard-list"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value">
                            <h4> {{ $completed_seminar_count }} </h4>
                        </div>
                        <div>completed Seminar</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-chalkboard-teacher"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value">
                            <h4> {{ $score }} </h4>
                        </div>
                        <div>Total Score</div>
                    </div>
                </div>
            </div>

            {{-- course board --}}
            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-chalkboard-teacher"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value">
                            <h4> {{ $courses->count() }} </h4>
                        </div>
                        <div>Course</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-chalkboard-teacher"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value">
                            <h4>{{ $enroller_course_count }}</h4>
                        </div>
                        <div>Enrolled Course</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-clipboard-list"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value">
                            <h4> {{ $completed_course_count }} </h4>
                        </div>
                        <div>completed Course</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-chalkboard-teacher"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value">
                            <h4> {{ $course_scoure }} </h4>
                        </div>
                        <div>Total Course Score</div>
                    </div>
                </div>
            </div>

            @if($companySeminars->count())
                <div class=" col-12 home-section-wrap home-fatured-courses-wrapper py-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="section-header-wrap">
                                    <h3 class="section-title">
                                        All Seminars
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="popular-courses-cards-wrap mt-3">
                            <div class="row">
                                @foreach($companySeminars as $course)
                                {!! seminar_card($course) !!}
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($courses->count())
                <div class=" col-12 home-section-wrap home-fatured-courses-wrapper py-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="section-header-wrap">
                                    <h3 class="section-title">
                                        All Courses
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="popular-courses-cards-wrap mt-3">
                            <div class="row">
                                @foreach($courses as $course)
                                {!! company_course_card($course->course) !!}
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection

@section('page-js')
@endsection