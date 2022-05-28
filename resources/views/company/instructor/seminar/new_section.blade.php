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
            <a href="#" class="list-group-item list-group-item-action list-group-item-info">
                <i class="la la-arrow-alt-circle-up"></i>
                <p class="m-0">Publish</p>
            </a>
        </div>

        <div class="card">
            <div class="card-body">

                <form action="" method="post">
                    @csrf
                    <div class="form-group {{ $errors->has('section_name') ? ' has-error' : '' }}">
                        <label for="section_name">Section name</label>
                        <input type="text" name="section_name" class="form-control" id="section_name" placeholder="Section name eg" value="" >

                        @if ($errors->has('section_name'))
                            <span class="invalid-feedback"><strong>{{ $errors->first('section_name') }}</strong></span>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-warning" name="save" value="save">
                        <i class="la la-save"></i> Create section
                    </button>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('page-js')
    {{-- <script src="{{ asset('assets/js/filemanager.js') }}"></script> --}}
@endsection