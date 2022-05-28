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
    }
    .company-header h4
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

    <div class="company-header">
        <h5>  Create Company Job </h5>
        {{-- <h5>  Hi  </h5> --}}
    </div>

    <div class="form-body">
        <form method="post" action="{{ route('company_job_store') }}" style="max-width: 1039px;">

            <h5 class="title">Company Job</h5>

            @csrf
            <div class="form-row">
                <div class="form-group col-md-6 {{form_error($errors, 'posting')->class}}">
                    <label><b>Posting</b></label>
                    <select class="form-control" name="posting" id="posting">
                        <option value="internal">Internal</option>
                        <option value="external">External</option>
                    </select>
                    {!! form_error($errors, 'posting')->message !!}
                </div>
            </div>

            <div class="form-row mt-4">
                <div class=" col-md-12 ">
                    <b>Job Discription</b>
                    <hr>
                </div>
                <div class="form-group col-md-6 {{form_error($errors, 'title')->class}}">
                    <label><b>Title</b></label>
                    <input type="text" class="form-control" name="title" placeholder="Enter title" value="{{ old('title') }}" required>
                    {!! form_error($errors, 'title')->message !!}
                </div>
                <div class="form-group col-md-6 {{form_error($errors, 'requisition_number')->class}}">
                    <label><b>Requisition Number</b></label>
                    <input type="text" class="form-control" name="requisition_number" placeholder="Enter requisition number" value="{{ old('requisition_number') }}" required>
                    {!! form_error($errors, 'requisition_number')->message !!}
                </div>
                <div class="form-group col-md-6 {{form_error($errors, 'company_description')->class}}">
                    <label><b>Company Description</b></label>
                    <input type="text" class="form-control" name="company_description" placeholder="Enter company description" value="{{ old('company_description') }}" required>
                    {!! form_error($errors, 'company_description')->message !!}
                </div>
                <div class="form-group col-md-6 {{form_error($errors, 'job_description')->class}}">
                    <label><b>Job Description</b></label>
                    <input type="text" class="form-control" name="job_description" placeholder="Enter job description" value="{{ old('job_description') }}" required>
                    {!! form_error($errors, 'job_description')->message !!}
                </div>
                <div class="form-group col-md-6 {{form_error($errors, 'core_responsibilites')->class}}">
                    <label><b>Core Responsibilites</b></label>
                    <input type="text" class="form-control" name="core_responsibilites" placeholder="Enter job description" value="{{ old('core_responsibilites') }}" required>
                    {!! form_error($errors, 'core_responsibilites')->message !!}
                </div>
                <div class="form-group col-md-6 {{form_error($errors, 'desirable_skills')->class}}">
                    <label><b>Desirable Skills</b></label>
                    <input type="text" class="form-control" name="desirable_skills" placeholder="Enter job description" value="{{ old('desirable_skills') }}" required>
                    {!! form_error($errors, 'desirable_skills')->message !!}
                </div>
                <div class="form-group col-md-6 {{form_error($errors, 'employment')->class}}">
                    <label><b>Employment</b></label>
                    <input type="text" class="form-control" name="employment" placeholder="Enter job description" value="{{ old('employment') }}" required>
                    {!! form_error($errors, 'employment')->message !!}
                </div>
            </div>

            
            <div class="form-row mt-4">
                <div class=" col-md-12 ">
                    <b>Key Criteria</b>
                    <hr>
                </div>
                <div class="form-group col-md-4 {{form_error($errors, 'date_if_test')->class}}">
                    <label><b>Date Of test</b></label>
                    <input type="date" class="form-control" name="date_if_test" placeholder="Enter department" value="{{ old('date_if_test') }}" required>
                    {!! form_error($errors, 'date_if_test')->message !!}
                </div>
                <div class="form-group col-md-4 {{form_error($errors, 'start_time')->class}}">
                    <label><b>Start Time</b></label>
                    <input type="time" class="form-control" name="start_time" placeholder="Enter department" value="{{ old('start_time') }}" required>
                    {!! form_error($errors, 'start_time')->message !!}
                </div>
                <div class="form-group col-md-4 {{form_error($errors, 'end_time')->class}}">
                    <label><b>End Time</b></label>
                    <input type="time" class="form-control" name="end_time" placeholder="Enter department" value="{{ old('end_time') }}" required>
                    {!! form_error($errors, 'end_time')->message !!}
                </div>
                <div class="form-group col-md-4 {{form_error($errors, 'total_score')->class}}">
                    <label><b>Total Score</b></label>
                    <input type="text" class="form-control" name="total_score" placeholder="Enter total score" value="{{ old('total_score') }}" required>
                    {!! form_error($errors, 'total_score')->message !!}
                </div>
                <div class="form-group col-md-4 {{form_error($errors, 'passing_score')->class}}">
                    <label><b>Passing Score</b></label>
                    <input type="text" class="form-control" name="passing_score" placeholder="Enter passing score" value="{{ old('passing_score') }}" required>
                    {!! form_error($errors, 'passing_score')->message !!}
                </div>
            </div>

            <hr>
            <div class="form-row mt-4">
                <div class="form-group col-md-4 {{form_error($errors, 'employment')->class}}">
                    <label><b>Employment</b></label>
                    <input type="text" class="form-control" name="employment" placeholder="Enter employment" value="{{ old('employment') }}" required>
                    {!! form_error($errors, 'employment')->message !!}
                </div>
                <div class="form-group col-md-4 {{form_error($errors, 'shift_time')->class}}">
                    <label><b>Shift Time</b></label>
                    <input type="text" class="form-control" name="shift_time" placeholder="Enter shift time" value="{{ old('shift_time') }}" required>
                    {!! form_error($errors, 'shift_time')->message !!}
                </div>
                <div class="form-group col-md-4 {{form_error($errors, 'education')->class}}">
                    <label><b>Education</b></label>
                    <input type="text" class="form-control" name="education" placeholder="Enter education" value="{{ old('education') }}" required>
                    {!! form_error($errors, 'education')->message !!}
                </div>
                <div class="form-group col-md-4 {{form_error($errors, 'industry_type')->class}}">
                    <label><b>Industry Type</b></label>
                    <input type="text" class="form-control" name="industry_type" placeholder="Enter industry type" value="{{ old('industry_type') }}" required>
                    {!! form_error($errors, 'industry_type')->message !!}
                </div>
                <div class="form-group col-md-4 {{form_error($errors, 'functional_areas')->class}}">
                    <label><b>Functional Areas</b></label>
                    <input type="text" class="form-control" name="functional_areas" placeholder="Enter functional areas" value="{{ old('functional_areas') }}" required>
                    {!! form_error($errors, 'functional_areas')->message !!}
                </div>
                <div class="form-group col-md-4 {{form_error($errors, 'salary_range')->class}}">
                    <label><b>Salary Range</b></label>
                    <input type="text" class="form-control" name="salary_range" placeholder="Enter salary range" value="{{ old('salary_range') }}" required>
                    {!! form_error($errors, 'salary_range')->message !!}
                </div>
                <div class="form-group col-md-4 {{form_error($errors, 'location')->class}}">
                    <label><b>Location</b></label>
                    <input type="text" class="form-control" name="location" placeholder="Enter location" value="{{ old('location') }}" required>
                    {!! form_error($errors, 'location')->message !!}
                </div>
                <div class="form-group col-md-4 {{form_error($errors, 'designation')->class}}">
                    <label><b>Designation</b></label>
                    <input type="text" class="form-control" name="designation" placeholder="Enter designation" value="{{ old('designation') }}" required>
                    {!! form_error($errors, 'designation')->message !!}
                </div>
                <div class="form-group col-md-4 {{form_error($errors, 'diversity_preference')->class}}">
                    <label><b>Diversity Preference</b></label>
                    <input type="text" class="form-control" name="diversity_preference" placeholder="Enter diversity preference" value="{{ old('diversity_preference') }}" required>
                    {!! form_error($errors, 'diversity_preference')->message !!}
                </div>
                <div class="form-group col-md-4 {{form_error($errors, 'experience_required')->class}}">
                    <label><b>Experience Required</b></label>
                    <input type="text" class="form-control" name="experience_required" placeholder="Enter experience required" value="{{ old('experience_required') }}" required>
                    {!! form_error($errors, 'experience_required')->message !!}
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