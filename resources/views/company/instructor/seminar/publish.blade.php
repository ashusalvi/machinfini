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

    <div class="company-header">
        <h5>  Create Company Seminar </h5>
        {{-- <h5>  Hi  </h5> --}}
    </div>

    <div class="form-body">

        {{-- @include(view('company.instructor.seminar.seminar_nav')) --}}
        <div class="course-edit-nav list-group list-group-horizontal-md mb-3 text-center  ">    
            <a href="{{ route('companySeminarEditInformation',$seminar->id) }}" class="list-group-item list-group-item-action list-group-item-info">
                <i class="la la-info-circle"></i>
                <p class="m-0">Information</p>
            </a>
            <a href="{{ route('CS_edit_curriculum',$seminar->id) }}" class="list-group-item list-group-item-action list-group-item-info ">
                <i class="la la-th-list"></i>
                <p class="m-0">Curriculum</p>
            </a>
            <a href="{{ route('CS_publish_course',$seminar->id) }}" class="list-group-item list-group-item-action list-group-item-info active">
                <i class="la la-arrow-alt-circle-up"></i>
                <p class="m-0">Publish</p>
            </a>
        </div>

        <form action="" method="post">
        @csrf

            <div class="row">
                <div class="col-md-10 offset-md-1 mt-3">
                    <div class="publish-course-wrap">

                        @if($seminar->status == 0)
                            <div class="card">
                                <div class="card-header d-flex">
                                    <h3>Draft</h3>
                                </div>
                                <div class="card-body  pt-3 pb-5 text-center">
                                    <p class="course-publish-icon m-0">
                                        <i class="la la-pencil-square-o"></i>
                                    </p>
                                    <p class="pl-5 pr-5">
                                        Your seminar is in a draft state. Employee cannot view.
                                    </p>
                                </div>
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-dark btn-lg" name="publish_btn" value="publish"><i class="la la-arrow-circle-up"></i> Publish Seminar</button>
                                </div>
                            </div>

                        @elseif($seminar->status == 1)
                            <div class="text-center">
                                <div class="alert alert-success py-5">
                                    <p class="course-publish-icon m-0"> <i class="la la-smile-o"></i></p>
                                    <h3>Your seminar has been published</h3>
                                </div>

                                <button type="submit" class="btn btn-warning btn-lg mt-4" name="publish_btn" value="unpublish"><i class="la la-arrow-circle-down"></i> Unpublish seminar</button>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </form>
    </div>
@endsection

@section('page-js')
    {{-- <script src="{{ asset('assets/js/filemanager.js') }}"></script> --}}
@endsection