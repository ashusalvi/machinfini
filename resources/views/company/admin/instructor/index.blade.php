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
        margin-bottom: 30px;
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
        <h5>  List of Department Heads </h5>
    </div>

    <div>
        <form method="get" style="max-width: 1039px;">
            <table id="earning_table" class="table table-striped table-bordered display">
                <thead>
                    <tr>
                        <th>Employee Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        {{-- <th>Mobile</th> --}}
                        <th>Department</th>
                        <th>status</th>
                        <th>created at</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($instructors as $instructor)
                        <tr>
                        <td>{{ $instructor->employee_id }}</td>
                        <td>{{ $instructor->user->name }}</td>
                        <td>{{ $instructor->user->email }}</td>
                        {{-- <td>{{ $instructor->user->phone }}</td> --}}
                        <td>{{ $instructor->getDepartments($instructor->id) }}</td>
                        
                        @if ($instructor->status == 1)
                           <td style="color:green">Active</td> 
                        @else
                            <td style="color:red">De-Active</td> 
                        @endif
                        <td>{{ $instructor->created_at->format('d M Y  h:i') }}</td>
                        <td>
                            <a href="{{ route('CPA_instructor_edit',[$instructor->id]) }}"><i class="fa fa-edit"></i></a>
                            <a href="{{ route('CPA_instructor_delete',[$instructor->id]) }}"><i class="fa fa-trash" aria-hidden="true" style="color:red;"></i></a>
                        </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>
@endsection

@section('page-js')
@endsection