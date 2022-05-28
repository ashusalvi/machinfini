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
        <h5>  Create  Department Heads </h5>
        {{-- <h5>  Hi  </h5> --}}
    </div>

    <div class="form-body">
        <form method="post" action="{{ route('CPA_instructor_upload') }}" style="max-width: 1039px;" enctype="multipart/form-data">

            <h5 class="title">Upload Department Heads</h5>

            @csrf
            <div class="form-group{{ $errors->has('csv_file') ? ' has-error' : '' }}">
                <label for="file" class="col-md-4 control-label">CSV file to import</label>

                <div class="col-md-6">
                    <input id="file" type="file" class="form-control" name="file" required>

                    @if ($errors->has('file'))
                        <span class="help-block">
                        <strong>{{ $errors->first('file') }}</strong>
                    </span>
                    @endif
                </div>
                <p>Note * : Click on Choose file and then select CSV file from the location. Columns – Employee Id, Employee Name, Employee Email, Employee Phone (optional),Employee Department.</p>
            </div>

            <div class="form-row text-right">
                <button type="submit" class="btn btn-info btn-lg" style="    padding: 5px 50px;"> Upload</button>
            </div>

        </form>
    </div>

    <div class="form-body">
        <form method="post" action="{{ route('CPA_instructor_store') }}" style="max-width: 1039px;">

            <h5 class="title"> Department Heads</h5>

            @csrf
            <div class="form-row">
                <div class="form-group col-md-4 {{form_error($errors, 'employee_id')->class}}">
                    <label><b>Employee Id</b></label>
                    <input type="text" class="form-control" name="employee_id" placeholder="Enter employee id" value="{{ old('employee_id') }}" required>
                    {!! form_error($errors, 'employee_id')->message !!}
                </div>

                <div class="form-group col-md-4 {{form_error($errors, 'name')->class}}">
                    <label><b>Employee Name</b></label>
                    <input type="text" class="form-control" name="name" placeholder="Enter employee name" value="{{ old('name') }}" required>
                    {!! form_error($errors, 'name')->message !!}
                </div>

                <div class="form-group col-md-4 {{form_error($errors, 'department')->class}}">
                    <label><b>Employee Department</b></label>
                    
                    <select name="department" class="form-control" value="{{ old('department') }}">
                    <option value="" hidden>Select department</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department') ==  $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                    @endforeach
                    
                    </select>
                    {!! form_error($errors, 'department')->message !!}
                </div>

                <div class="form-group col-md-6 {{form_error($errors, 'email')->class}}">
                    <label><b>Employee Email</b></label>
                    <input type="text" class="form-control" name="email" placeholder="Enter employee email" value="{{ old('email') }}" required>
                    {!! form_error($errors, 'email')->message !!}
                </div>

                <div class="form-group col-md-6 {{form_error($errors, 'phone')->class}}">
                    <label><b>Employee phone number</b></label>
                    <input type="number" class="form-control" name="phone" placeholder="Enter employee phone" value="{{ old('phone') }}">
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