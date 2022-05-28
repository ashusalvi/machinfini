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
        <h5>  Create Company Department </h5>
        {{-- <h5>  Hi  </h5> --}}
    </div>

    <div class="form-body">
        <form method="post" action="{{ route('CPA_department_store') }}" style="max-width: 1039px;">

            <h5 class="title">Company Department</h5>

            @csrf
            <div class="form-row">
                <div class="form-group col-md-6 {{form_error($errors, 'department_name')->class}}">
                    <label><b>Department Name</b></label>
                    <input type="text" class="form-control" name="department_name" placeholder="Enter department" value="{{ old('department_name') }}" required>
                    {!! form_error($errors, 'department_name')->message !!}
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