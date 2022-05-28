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
                        <span><i class="la la-user"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value">
                            <h4>{{ $company_seminars }}</h4>
                        </div>
                        <div>Total Seminars</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-user-graduate"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value">
                            <h4>{{ $active_seminars }}</h4>
                        </div>
                        <div>Active Seminar</div>
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
                            <h4>{{ $draft_seminars }}</h4>
                        </div>
                        <div>Draft Seminar</div>
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
                            <h4>{{$employees}}</h4>
                        </div>
                        <div>Employee</div>
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
                            <h4>{{ $course_count }}</h4>
                        </div>
                        <div>Courses</div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-user-graduate"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value">
                            <h4>30</h4>
                        </div>
                        <div>Category</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-graduation-cap"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value">
                            <h4>20</h4>
                        </div>
                        <div>Course</div>
                    </div>
                </div>
            </div> --}}

            {{-- <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-play"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value"><h4>{{$lectureCount}}</h4></div>
                        <div>Lecture</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-clipboard-list"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value"><h4>{{$quizCount}}</h4></div>
                        <div>Quiz</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-check-circle"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value"><h4>{{$assignmentCount}}</h4></div>
                        <div>Assignments</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-question-circle"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value"><h4>{{$questionCount}}</h4></div>
                        <div>Question Asked</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-sign-in"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value"><h4>{{$totalEnrol}}</h4></div>
                        <div>Enrolled</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-star-half-alt"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value"><h4>{{$totalReview}}</h4></div>
                        <div>Reviews</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-money"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value"><h4>{!! price_format($totalAmount) !!}</h4></div>
                        <div>Payment Total</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-sign-out"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value"><h4>{!! price_format($withdrawsTotal) !!}</h4></div>
                        <div>Withdraws Total</div>
                    </div>
                </div>
            </div> --}}

        </div>
    </div>
@endsection

@section('page-js')
@endsection