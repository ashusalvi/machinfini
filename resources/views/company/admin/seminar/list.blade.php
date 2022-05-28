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
        <h5>  List of Company Seminar </h5>
    </div>

    <div>
        <form method="get" style="max-width: 1039px;">
            <table id="earning_table" class="table table-striped table-bordered display">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Score</th>
                        <th>Department</th>
                        <th>Instructor</th>
                        <th>Total Lecture</th>
                        <th>Total Quiz</th>
                        <th>Due Date</th>
                        <th>Completed Employee</th>
                        {{-- <th>Pending Employee</th> --}}
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($seminars as $seminar)
                        <tr>
                        <td>{{ $seminar->title }}</td>
                        <td>{{ $seminar->score }}</td>
                        @if ( $seminar->department != '')
                            <td>{{ $seminar->department->name }}</td>
                        @else
                            <td> - </td>
                        @endif
                        
                        <td>{{ $seminar->author->name }}</td>
                        <td>{{ $seminar->total_lectures }}</td>
                        <td>{{ $seminar->total_quiz }}</td>
                        <td>{{ $seminar->due_date }}</td>
                        <td><a href="{{route('completed_seminar_list',[$seminar->id])}}">{{ $seminar->completed_seminar($seminar->id) }}</a></td>
                        {{-- <td><a href="#">{{ $seminar->pending_seminar($seminar->id) }}</a></td> --}}
                        @if($seminar->due_date < date('Y-m-d'))
                            <td>Cross due date</td>
                        @else
                            <td>On-time</td>
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