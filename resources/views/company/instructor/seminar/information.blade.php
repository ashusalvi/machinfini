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
            <a href="{{ route('companySeminarEditInformation',$seminar->id) }}" class="list-group-item list-group-item-action list-group-item-info active">
                <i class="la la-info-circle"></i>
                <p class="m-0">Information</p>
            </a>
            <a href="{{ route('CS_edit_curriculum',$seminar->id) }}" class="list-group-item list-group-item-action list-group-item-info ">
                <i class="la la-th-list"></i>
                <p class="m-0">Curriculum</p>
            </a>
            <a href="{{ route('CS_publish_course',$seminar->id) }}" class="list-group-item list-group-item-action list-group-item-info">
                <i class="la la-arrow-alt-circle-up"></i>
                <p class="m-0">Publish</p>
            </a>
        </div>

        <form method="post" action="" style="max-width: 1039px;">

            @csrf
            <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                <label for="title">Title</label>
                <div class="input-group mb-3">
                    <input type="text" name="title" class="form-control" id="title" placeholder="Seminar title" value="{{ $seminar->title }}" data-maxlength="120" >
                    <div class="input-group-append">
                        <span class="input-group-text">120</span>
                    </div>
                </div>
                @if ($errors->has('title'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('title') }}</strong></span>
                @endif
            </div>

            <div class="form-group">
                <label for="short_description">Short description</label>
                <div class="input-group">
                    <textarea name="short_description" id="short_description" class="form-control" placeholder="Seminar description" data-maxlength="220">{{ $seminar->short_description }}</textarea>
                    <div class="input-group-append">
                        <span class="input-group-text">220</span>
                    </div>
                </div>
            </div>

            <div class="form-group {{ $errors->has('department') ? ' has-error' : '' }}">
                <label for="department">Select Department</label>
                <div class="input-group mb-3">
                    <select class="form-control" name="department" id="department">
                        @foreach($departments as $key =>  $department)
                            <option value="{{ $key }}" {{ $seminar->short_description ==  $key ? 'selected' : '' }} >{{ $department }}</option>
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('department'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('department') }}</strong></span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('due_date') ? ' has-error' : '' }}">
                <div class="row">
                    <div class="col-md-6">
                    <label for="due_date">Due Date</label>
                    <div class="input-group mb-3">
                        <input type="date" name="due_date" class="form-control" id="due_date" placeholder="Seminar due_date" value="{{ $seminar->due_date }}" data-maxlength="120" >
                    </div>
                    @if ($errors->has('due_date'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('due_date') }}</strong></span>
                    @endif
                </div>
                {{-- <div class="col-md-6">
                    <label for="expire_date">Expire Date</label>
                    <div class="input-group mb-3">
                        <input type="date" name="expire_date" class="form-control" id="expire_date" placeholder="Seminar expire_date" value="{{ $seminar->expiry_date }}" data-maxlength="120" >
                    </div>
                    @if ($errors->has('expire_date'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('expire_date') }}</strong></span>
                    @endif
                </div> --}}
                </div>
            </div>

            <div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control ckeditor" rows="7">{{ $seminar->description }}</textarea>

                @if ($errors->has('description'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('description') }}</strong></span>
                @endif
            </div>

            {{-- <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="benefits">What learn text</label>
                        <textarea name="benefits" id="benefits" class="form-control" rows="5"> {{ $seminar->benefits }} </textarea>
                        <small id="befitsHelp" class="form-text text-muted">benefits desc</small>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="requirements">Requirements</label>
                        <textarea name="requirements" id="requirements" class="form-control" rows="5"> {{ $seminar->requirements }} </textarea>
                        <small id="requirementsHelp" class="form-text text-muted">requirements desc</small>
                    </div>
                </div>
            </div> --}}

            <div class="form-row my-3">
                <div class="col">
                    <div class="form-group">
                        <label for="requirements">Course thumbnail</label>
                        {!! image_upload_form('thumbnail_id', $seminar->thumbnail_id, [750,422]) !!}
                        <small class="form-text text-muted">course img guide </small>
                    </div>
                </div>
            </div>

            <div class="form-row my-3">
                <div class="col-md-3">
                    <label for="score">Score</label>
                    <div class="input-group mb-3">
                        <input type="number" name="score" class="form-control" id="score" placeholder="Seminar score" value="{{ $seminar->score }}" data-maxlength="120" >
                    </div>
                    @if ($errors->has('score'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('score') }}</strong></span>
                    @endif
                </div>
            </div>

            <div class="lecture-video-upload-wrap mb-5">
                @php
                    $videoSrc = 'html5';
                @endphp

                <label>Intro video</label>

                <div class="video-source-input-wrap mb-5">

                    <div class="video-source-item video_source_wrap_html5 border bg-white p-4" style="display: {{$videoSrc == 'html5'? 'block' : 'none'}};">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="video-upload-wrap text-center">
                                    <i class="la la-cloud-upload text-muted"></i>
                                    <h5>Upload video</h5>
                                    <p class="mb-2">File Format:  .mp4</p>
                                    {!! media_upload_form('video[html5_video_id]', 'upload video', null,$seminar->video_info('html5_video_id')) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="video-poster-upload-wrap text-center">
                                    <i class="la la-image text-muted"></i>
                                    <h5>Video poster</h5>
                                    <small class="text-muted mb-3 d-block">Size: 700x430 pixels. Supports: jpg,jpeg, or png</small>

                                    {!! image_upload_form('video[html5_video_poster_id]', $seminar->video_info('html5_video_poster_id')) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-row text-right">
                <button type="submit" class="btn btn-info btn-lg" style="    padding: 5px 50px;" > Create</button>
            </div>

        </form>
    </div>
@endsection

@section('page-js')
    <script src="{{ asset('assets/js/filemanager.js') }}"></script>
@endsection