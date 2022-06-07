@extends('layouts.admin')

@section('page-header-right')
@endsection

@section('page-css')
    <style>
        .company-header {
            padding: 20px 10px;
            background: #b6a4298a;
            font-weight: 600;
            margin-top: 20px;
        }

        .company-header h4 {
            font-weight: 600;
        }

        .form-body {
            margin-top: 20px;
            background: #8080806b;
            padding: 20px;
        }

        .form-body .title {
            font-weight: 600;
            border-bottom: 1px solid white;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

    </style>
@endsection

@section('content')
    <div class="company-header">
        <h4> Create Curriculum </h4>
    </div>

    <div class="form-body">
        <form method="post" action="{{ route('curriculum.save') }}" enctype="multipart/form-data"
            style="max-width: 1039px;">

            <h5 class="title"> Details</h5>

            @csrf
            <div class="form-row">
                <div class="form-group col-md-4 {{ form_error($errors, 'type')->class }}">
                    <label><b>Type <span>*</span></b></label>
                    <select class="form-control" name="type" id="type" required>
                        <option value="Beginner">Beginner</option>
                        <option value="Intermediate">Intermediate</option>
                        <option value="Advance">Advance</option>
                        <option value="Professional">Professional</option>
                        <option value="Applied tech">Applied tech</option>
                    </select>
                    {!! form_error($errors, 'type')->message !!}
                </div>
                <div class="form-group col-md-4 {{ form_error($errors, 'title')->class }}">
                    <label><b>Title <span>*</span></b></label>
                    <input type="text" class="form-control" name="title" placeholder="Enter title"
                        value="{{ old('title') }}" required>
                    {!! form_error($errors, 'title')->message !!}
                </div>
                <div class="form-group col-md-4 {{ form_error($errors, 'classes')->class }}">
                    <label><b>Classes <span>*</span><sub>(number of classes)</sub></b></label>
                    <input type="text" class="form-control" name="classes" placeholder="Enter classes"
                        value="{{ old('classes') }}" required>
                    {!! form_error($errors, 'classes')->message !!}
                </div>

            </div>

            <div class="form-row">
                <div class="form-group col-md-12 {{ form_error($errors, 'details')->class }}">
                    <label><b>Curriculum Includes/Details <span>*</span><sub>(max:600 characters)</sub></b></label>
                    <textarea class="form-control" name="details" rows="4" cols="50" placeholder="Enter company address"
                        required> {{ old('details') }} </textarea>
                    {!! form_error($errors, 'details')->message !!}
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4 {{ form_error($errors, 'price')->class }}">
                    <label><b>price <span>*</span></b></label>
                    <input type="text" class="form-control" name="price" placeholder="Enter price"
                        value="{{ old('price') }}" required>
                    {!! form_error($errors, 'price')->message !!}
                </div>
                {{-- <div class="form-group col-md-4 {{ form_error($errors, 'oprice')->class }}">
                    <label><b>Original price <span>*</span></b></label>
                    <input type="text" class="form-control" name="oprice" placeholder="Enter original price"
                        value="{{ old('oprice') }}" required>
                    {!! form_error($errors, 'oprice')->message !!}
                </div> --}}
                <div class="form-group col-md-4 {{ form_error($errors, 'tag')->class }}">
                    <label><b>Tag</b></label>
                    <select class="form-control" name="tag" id="tag">
                        <option value="">Select Tag</option>
                        <option value="Most Popular">Most Popular</option>
                        <option value="Best Value">Best Value</option>
                    </select>
                    {!! form_error($errors, 'tag')->message !!}
                </div>
                <div class="form-group col-md-4 {{ form_error($errors, 'save')->class }}">
                    <label><b>Save <span>*</span><sub>(%)</sub></b></label>
                    <input type="text" class="form-control" name="save" placeholder="Enter save amount in percent"
                        value="{{ old('save') }}" required>
                    {!! form_error($errors, 'save')->message !!}
                </div>

                <div class="form-group col-md-4 {{ form_error($errors, 'document')->class }}">
                    <label><b>Upload Document <span>*</span></b></label>
                    <input type="file" class="form-control" name="document" placeholder="Enter document amount in percent"
                        value="{{ old('document') }}" required>
                    {!! form_error($errors, 'document')->message !!}
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
