@extends('layouts.admin')

@section('content')
@if(session()->has('message'))
    <div class="alert alert-success">
        <h4>{{ session()->get('message') }}</h4>
    </div>
@endif

<form action="{{route('save_partner')}}" method="post" enctype="multipart/form-data">
    @csrf

    <div class="profile-basic-info bg-white p-3">

        <div class="form-row">
            <div class="form-group col-md-12 {{form_error($errors, 'name')->class}}">
                <label>{{__t('Channel partner Name')}}</label>
                <input type="text" class="form-control" name="name" placeholder="Enter Name" value="{{ old('name') }}" required>
                {!! form_error($errors, 'name')->message !!}
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-12 {{form_error($errors, 'email')->class}}">
                <label>{{__t('Channel partner Email')}}</label>
                <input type="text" class="form-control" name="email" placeholder="Enter Email" value="{{ old('email') }}" required>
                {!! form_error($errors, 'email')->message !!}
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-12 {{form_error($errors, 'mobile_no')->class}}">
                <label>{{__t('Channel partner mobile number')}}</label>
                <input type="text" class="form-control" name="mobile_no" placeholder="Enter mobile number" value="{{ old('mobile_no') }}" required>
                {!! form_error($errors, 'mobile_no')->message !!}
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-12 {{form_error($errors, 'adhar_no')->class}}">
                <label>{{__t('Channel partner adhar number')}}</label>
                <input type="text" class="form-control" name="adhar_no" placeholder="Enter adhar number" value="{{ old('adhar_no') }}" required>
                {!! form_error($errors, 'adhar_no')->message !!}
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-12 {{form_error($errors, 'pan_no')->class}}">
                <label>{{__t('Channel partner pan number')}}</label>
                <input type="text" class="form-control" name="pan_no" placeholder="Enter pan number" value="{{ old('pan_no') }}" required>
                {!! form_error($errors, 'pan_no')->message !!}
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-12 {{form_error($errors, 'partnership')->class}}">
                <label>{{__t('Channel partnership (%)')}}</label>
                <input type="number" class="form-control" name="partnership" placeholder="Enter partnership " value="{{ old('partnership') }}" required>
                {!! form_error($errors, 'partnership')->message !!}
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-12 {{form_error($errors, 'password')->class}}">
                <label>{{__t('Channel partner password')}}</label>
                <input type="text" class="form-control" name="password" placeholder="Enter Collage Admin Password" value="123456789" readonly="true">
                {!! form_error($errors, 'password')->message !!}
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-12 {{form_error($errors, 'logo')->class}}">
                <label>{{__t('Channel partner logo')}}</label>
                <input type="file" class="form-control" name="logo" >
                {!! form_error($errors, 'logo')->message !!}
            </div>
        </div>

        <button type="submit" class="btn btn-info btn-lg"> Create</button>


    </div>


    <div class="p-4 bg-white">
        <h4 class="mb-4"> Created channel partner </h4>
    </div>

    <div>
        <table class="table table-striped table-bordered">

            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>mobile no</th>
                <th>pan card</th>
                <th>adhar card</th>
                <th>partnership</th>
                <th>Ref Code</th>
                <th>Action</th>
            </tr>

            @foreach ($channel_partners as $channel_partner)
                @if(!empty($channel_partner->User))
                    <tr>
                        <td>{{$channel_partner->User->name}}</td>
                        <td>{{$channel_partner->User->email}}</td>
                        <td>{{$channel_partner->User->phone}}</td>
                        <td>{{$channel_partner->pan_number}}</td>
                        <td>{{$channel_partner->adhar_number}}</td>
                        <td>{{$channel_partner->User->channel_partner_per}}</td>
                        <td>{{$channel_partner->User->channel_partner_code}}</td>
                        <td>
                            <a href="{{ route('delete_partner',[$channel_partner->id]) }}">
                                <button type="button" class="btn btn-danger">Delete</button>
                            </a>
                            <!--<a href="{{ route('edit_partner') }}">-->
                            <!--<button type="button" class="btn btn-primary">Edit</button>-->
                            <!--</a>-->
                        </td>
                    </tr>
                @endif
            @endforeach


        </table>
    </div>



</form>




@endsection

@section('page-js')

@endsection