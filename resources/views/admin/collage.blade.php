@extends('layouts.admin')

@section('content')


<form action="{{route('savecollage')}}" method="post">
    @csrf

    <div class="profile-basic-info bg-white p-3">

        <div class="form-row">
            <div class="form-group col-md-12 {{form_error($errors, 'name')->class}}">
                <label>{{__t('College Name')}}</label>
                <input type="text" class="form-control" name="name" placeholder="Enter College Name" value="{{ old('name') }}" required>
                {!! form_error($errors, 'name')->message !!}
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-12 {{form_error($errors, 'location')->class}}">
                <label>{{__t('College Location')}}</label>
                <input type="text" class="form-control" name="location" placeholder="Enter College Location" value="{{ old('location') }}" required>
                {!! form_error($errors, 'location')->message !!}
            </div>
        </div>

        <button type="submit" class="btn btn-info btn-lg"> Create College</button>


    </div>


    <div class="p-4 bg-white">
        <h4 class="mb-4"> Created College  </h4>
    </div>

    <div>
        <table class="table table-striped table-bordered">

            <tr>
                <th>College Name</th>
                <th>College Status</th>
                <th>Location</th>
                <th>Action</th>
            </tr>

            @foreach ($collages as $collage)
                <tr>
                    <td>{{$collage->name}}</td>
                    <td>{{$collage->active}}</td>
                    <td>{{$collage->location}}</td>
                    <td><i class="la la-trash" style="color:red; font-size:20px;"></i></td>
                </tr>
            @endforeach


        </table>
    </div>



</form>




@endsection

@section('page-js')

@endsection