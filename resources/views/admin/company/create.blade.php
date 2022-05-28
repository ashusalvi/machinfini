@extends('layouts.admin')

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
    <div class="company-header">
        <h4>  Create Company / College </h4>
    </div>

    <div class="form-body">
        <form method="post" action="{{ route('companyStore') }}" style="max-width: 1039px;">

            <h5 class="title"> Details</h5>

            @csrf
            <div class="form-row">
                <div class="form-group col-md-4 {{form_error($errors, 'company_name')->class}}">
                    <label><b>Company/College Name</b></label>
                    <input type="text" class="form-control" name="company_name" placeholder="Enter company name" value="{{ old('company_name') }}" required>
                    {!! form_error($errors, 'company_name')->message !!}
                </div>
                <div class="form-group col-md-4 {{form_error($errors, 'company_type')->class}}">
                    <label><b>Company/College Type</b></label>
                    <input type="text" class="form-control" name="company_type" placeholder="Enter company type" value="{{ old('company_type') }}" required>
                    {!! form_error($errors, 'company_type')->message !!}
                </div>
                <div class="form-group col-md-4 {{form_error($errors, 'data_type')->class}}">
                    <label><b>Type</b></label>
                    <select class="form-control" name="data_type" id="data_type">
                        <option value="0">College</option>
                        <option value="1">Company</option>
                    </select>
                    {!! form_error($errors, 'data_type')->message !!}
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6 {{form_error($errors, 'company_email')->class}}">
                    <label><b>Company/College Email</b></label>
                    <input type="text" class="form-control" name="company_email" placeholder="Enter company email" value="{{ old('company_email') }}" >
                    {!! form_error($errors, 'company_email')->message !!}
                </div>
                <div class="form-group col-md-6 {{form_error($errors, 'company_number')->class}}">
                    <label><b>Company/College Number</b></label>
                    <input type="text" class="form-control" name="company_number" placeholder="Enter company number" value="{{ old('company_number') }}" >
                    {!! form_error($errors, 'company_number')->message !!}
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-12 {{form_error($errors, 'company_address')->class}}">
                    <label><b>Company/College Address</b></label>
                    <textarea class="form-control" name="company_address" rows="4" cols="50" placeholder="Enter company address" required> {{ old('company_address') }} </textarea>
                    {!! form_error($errors, 'company_address')->message !!}
                </div>
            </div>

             <h5 class="title">Company/College Admin</h5>

            <div class="form-row">
                <div class="form-group col-md-4 {{form_error($errors, 'name')->class}}">
                    <label><b>Name</b></label>
                    <input type="text" class="form-control" name="name" placeholder="Enter name" value="{{ old('name') }}" required>
                    {!! form_error($errors, 'name')->message !!}
                </div>
                <div class="form-group col-md-4 {{form_error($errors, 'email')->class}}">
                    <label><b>Email</b></label>
                    <input type="text" class="form-control" name="email" placeholder="Enter email" value="{{ old('email') }}" required>
                    {!! form_error($errors, 'email')->message !!}
                </div>
                <div class="form-group col-md-4 {{form_error($errors, 'phone')->class}}">
                    <label><b>Phone</b></label>
                    <input type="text" class="form-control" name="phone" placeholder="Enter Phone number" value="{{ old('phone') }}" >
                    {!! form_error($errors, 'phone')->message !!}
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