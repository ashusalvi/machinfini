@extends('layouts.admin')

@section('content')


<form action="{{route('savecollegeadmin')}}" method="post">
    @csrf

    <div class="profile-basic-info bg-white p-3">

        <div class="form-row">
            <div class="form-group col-md-12 {{form_error($errors, 'college')->class}}">
                <label>{{__t('College Name')}}</label>
                <select class="form-control" name="college">
                    <option value="">Select College</option>
                    @foreach ($collages as $collage)
                    <option value="{{$collage->id}}">{{$collage->name}}</option>
                    @endforeach
                </select>
                {!! form_error($errors, 'college')->message !!}
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-12 {{form_error($errors, 'name')->class}}">
                <label>{{__t('College Admin Name')}}</label>
                <input type="text" class="form-control" name="name" placeholder="Enter Collage Admin Name" value="{{ old('name') }}" required>
                {!! form_error($errors, 'name')->message !!}
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-12 {{form_error($errors, 'email')->class}}">
                <label>{{__t('College Admin Email')}}</label>
                <input type="text" class="form-control" name="email" placeholder="Enter Collage Admin Email" value="{{ old('email') }}" required>
                {!! form_error($errors, 'email')->message !!}
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-12 {{form_error($errors, 'password')->class}}">
                <label>{{__t('College Admin Password')}}</label>
                <input type="text" class="form-control" name="password" placeholder="Enter Collage Admin Password" value="123456789" readonly="true">
                {!! form_error($errors, 'password')->message !!}
            </div>
        </div>

        <button type="submit" class="btn btn-info btn-lg"> Create College Admin</button>


    </div>


    <div class="p-4 bg-white">
        <h4 class="mb-4"> Created College Admin </h4>
    </div>

    <div>
        <table class="table table-striped table-bordered">

            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>College</th>
                <th>Action</th>
            </tr>

            @foreach ($collegeAdmins as $value)
                <tr>
                    <td>{{$value->name}}</td>
                    <td>{{$value->email}}</td>
                    <td>{{$value->college->name}}</td>
                    <td><i class="la la-trash" style="color:red; font-size:20px;"></i></td>
                </tr>
            @endforeach


        </table>
    </div>



</form>




@endsection

@section('page-js')

@endsection