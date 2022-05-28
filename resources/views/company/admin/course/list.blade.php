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
        <h5>  List of Request Course </h5>
    </div>

    <div>
        <form method="get" style="max-width: 1039px;">
            <table id="earning_table" class="table table-striped table-bordered display">
                <thead>
                    <tr>
                        <th>Course name</th>
                        <th>Department name</th>
                        <th>Request Message</th>
                        <th>Request Date</th>
                        {{-- <th>Response Date</th> --}}
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($course_request as $course)
                        <tr>
                            <td>{{ $course->course->title }}</td>
                            @if ($course->department != null)
                                <td>{{ $course->department->name }}</td>
                            @else
                                <td>NA</td>
                            @endif
                            <td>{{ $course->message }}</td>
                            {{-- <td>{{ $course->admin_message }}</td> --}}
                            @if ($course->request_created != null)
                                <td>{{ \Carbon\Carbon::parse($course->request_created)->format('d M Y  h:i') }}</td>
                            @else
                                <td>NA</td>
                            @endif

                            {{-- @if ($course->approved_at != null)
                                <td>{{ \Carbon\Carbon::parse($course->approved_at)->format('d M Y  h:i') }}</td>
                            @else
                                <td>NA</td>
                            @endif --}}
                            
                            @if ($course->status == 1)
                            <td style="color:green">Approve</td> 
                            @elseif($course->status == 2)
                            <td style="color:red">Reject</td> 
                            @else
                                <td style="color:blue">Pending</td> 
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>
@endsection

@section('page-js')
@endsection