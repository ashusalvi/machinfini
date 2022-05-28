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
    .company-button
    {
        color: #212529;
        background-color: #b6a4298a;
        border-color: #D7CE8B;
    }
    .bg-gold
    {
        background-color: #b6a4298a !important;
        border-color: #e1d159 !important;
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
    </div>

    <div class="form-body">

        <div class="course-edit-nav list-group list-group-horizontal-md mb-3 text-center  ">    
            <a href="{{ route('companySeminarEditInformation',$seminar->id) }}" class="list-group-item list-group-item-action list-group-item-info">
                <i class="la la-info-circle"></i>
                <p class="m-0">Information</p>
            </a>
            <a href="{{ route('CS_edit_curriculum',$seminar->id) }}" class="list-group-item list-group-item-action list-group-item-info active">
                <i class="la la-th-list"></i>
                <p class="m-0">Curriculum</p>
            </a>
            <a href="{{ route('CS_publish_course',$seminar->id) }}" class="list-group-item list-group-item-action list-group-item-info">
                <i class="la la-arrow-alt-circle-up"></i>
                <p class="m-0">Publish</p>
            </a>
        </div>

        <div class="curriculum-top-nav d-flex bg-white mb-5 p-2 border">
            <a href="{{route('CS_new_section', $seminar->id)}}" class="btn company-button ">new section</a>
        </div>
        @if($seminar->sections->count())
            <div class="dashboard-curriculum-wrap">

                <div id="dashboard-curriculum-sections-wrap">
                    @foreach($seminar->sections as $section)
                        <div id="dashboard-section-{{$section->id}}" class="dashboard-course-section bg-white border mb-4">
                            <div class="dashboard-section-header p-3 border-bottom d-flex">
                                <i class="la la-bars section-move-handler"></i>

                                <span class="dashboard-section-name flex-grow-1 ml-2"><strong>{{$section->section_name}}</strong>
                                </span>

                                <button class="section-item-btn-tool btn px-1 py-0 section-edit-btn "><i class="la la-pencil"></i> </button>

                                <button class="section-item-btn-tool btn btn-outline-danger text-danger px-1 py-0 section-delete-btn ml-3" data-section-id="{{$section->id}}"><i class="la la-trash"></i> </button>
                            </div>


                            <!-- Section Edit Form -->
                            <div class="card-body section-edit-form-wrap" style="display: none;">
                                <form action="{{route('CS_update_section', $section->id)}}" method="post" class="section-edit-form">
                                    @csrf
                                    <div class="form-group">
                                        <label for="section_name">Section name</label>
                                        <input type="text" name="section_name" class="form-control" value="{{$section->section_name}}" >
                                    </div>
                                    <button type="submit" class="btn btn-warning" name="save" value="save">
                                        <i class="la la-save"></i> update section
                                    </button>
                                </form>
                            </div>
                            <!-- END #Section Edit Form -->


                            <div class="dashboard-section-body bg-light p-3">
                                @include('company.instructor.seminar.section-items')
                            </div>

                            <div class="section-item-form-wrap"></div>

                            <div class="section-add-item-wrap p-3 bg-gold">
                                <a href="javascript:;" class="add-item-lecture mr-3"> <i class="la la-plus-square"></i> {{__t('lecture')}}</a>
                                <a href="javascript:;" class="create-new-quiz mr-3"> <i class="la la-plus-square"></i> {{__t('quiz')}}</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!--  New Lecture Hidden Form HTML -->
                <div id="section-lecture-form-html" style="display: none;">
                    <div class="section-item-form-html  p-4 border">
                        <div class="new-lecture-form-header d-flex mb-3 pb-3 border-bottom">
                            <h5 class="flex-grow-1">{{__t('Add lecture')}}</h5>
                            <a href="javascript:;" class="btn btn-outline-dark btn-sm btn-cancel-form" ><i class="la la-close"></i> </a>
                        </div>

                        <form class="curriculum-lecture-form" action="{{route('CS_new_lecture', $seminar->id)}}" method="post">

                            <div class="lecture-request-response"></div>

                            @csrf
                            <div class="form-group">
                                <label for="title">{{__t('title')}}</label>
                                <input type="text" name="title" class="form-control"  >
                            </div>

                            <div class="form-group">
                                <label for="description">{{__t('description')}}</label>
                                <textarea name="description" class="form-control ajaxCkeditor" rows="5"></textarea>
                            </div>

                            <div class="form-group d-flex">
                                <span class="mr-4">{{__t('free preview')}}</span>
                                <label class="switch">
                                    <input type="checkbox" name="is_preview" value="1" checked="checked" >
                                    <span></span>
                                </label>
                            </div>

                            <div class="form-group text-right">
                                <button type="button" class="btn btn-outline-info btn-cancel-form"> {{__t('cancel')}}</button>
                                <button type="submit" class="btn btn-info btn-add-lecture"  name="save" value="save_next"> <i class="la la-save"></i> {{__t('Add lecture')}}</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!--  New Quiz Hidden Form HTML -->
                <div id="section-quiz-form-html" style="display: none;">
                    <div class="section-item-form-html p-4 border">
                        <div class="new-quiz-form-header d-flex mb-3 pb-3 border-bottom">
                            <h5 class="flex-grow-1">{{__t('Create quiz')}}</h5>
                            <a href="javascript:;" class="btn btn-outline-dark btn-sm btn-cancel-form" ><i class="la la-close"></i> </a>
                        </div>

                        <form class="curriculum-quiz-form" action="{{route('CS_new_quiz', $seminar->id)}}" method="post">

                            <div class="quiz-request-response"></div>

                            @csrf
                            <div class="form-group">
                                <label for="title">{{__t('title')}}</label>
                                <input type="text" name="title" class="form-control"  >
                            </div>

                            <div class="form-group">
                                <label for="description">{{__t('description')}}</label>
                                <textarea name="description" class="form-control ajaxCkeditor" rows="5"></textarea>
                            </div>

                            <div class="form-group">
                                <button type="button" class="btn btn-outline-info btn-cancel-form"> {{__t('cancel')}}</button>
                                <button type="submit" class="btn btn-info btn-add-quiz"  name="save" value="save_next"> <i class="la la-save"></i> {{__t('Create new quiz')}}</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        @else

            <div class="card">
                <div class="card-body">
                    {!! no_data(null, null, 'my-5') !!}
                    <div class="no-data-wrap text-center my-5">
                        <a href="{{route('CS_new_section', $seminar->id)}}" class="btn btn-lg company-button">new section</a>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection

@section('page-js')
    <script src="{{ asset('assets/js/jquery-ui.min.js')}}"></script>
    <script src="{{ asset('assets/js/filemanager.js') }}"></script>
    <script src="{{ asset('assets/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/plugins/ckeditor/adapters/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/seminar/main.js') }}"></script>
@endsection