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
        <h5>  Create  Course Request </h5>
        {{-- <h5>  Hi  </h5> --}}
    </div>
    <div class="form-body">
        <form method="post" action="{{ route('submit_request_course') }}" style="max-width: 1039px;">

            <h5 class="title"> Request Course</h5>

            @csrf
            <div class="form-row">
                <div class="form-group col-md-4 {{form_error($errors, 'course_id')->class}}">
                    <label><b>Select course</b></label>
                    
                    <select name="course_id" class="form-control" value="{{ old('course_id') }}">
                    <option value="" hidden>Select Course</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') ==  $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                    @endforeach
                    
                    </select>
                    {!! form_error($errors, 'course')->message !!}
                </div>

                <div class="form-group col-md-4 {{form_error($errors, 'course_id')->class}}">
                    <label><b>Select department</b></label>
                    
                    <select name="department_id" class="form-control" value="{{ old('department_id') }}">
                    <option value="" hidden>Select department</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id') ==  $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                    @endforeach
                    
                    </select>
                    {!! form_error($errors, 'department_id')->message !!}
                </div>

                <div class="form-group col-md-12 {{form_error($errors, 'message')->class}}">
                    <label><b>Message</b></label>
                    <input type="text" class="form-control" name="message" placeholder="Enter message" value="{{ old('message') }}" required>
                    {!! form_error($errors, 'message')->message !!}
                </div>

            </div>

            <div class="form-row text-right">
                <button type="submit" class="btn btn-info btn-lg" style="    padding: 5px 50px;"> Create</button>
            </div>

        </form>
    </div>
@endsection

@section('page-js')
@endsection