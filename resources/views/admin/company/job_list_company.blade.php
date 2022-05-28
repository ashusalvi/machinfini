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
</style>
@endsection

@section('content')
    @if(session()->has('message'))
        <div class="alert alert-success">
            <h4>{{ session()->get('message') }}</h4>
        </div>
    @endif

    <div class="company-header">
        <h4>  Company's job list  </h4>
    </div>

    <div>
        <form method="get" style="max-width: 1039px;">
            <table id="earning_table" class="table table-striped table-bordered display">
                <thead>
                    <tr>
                        <th>Company name</th>
                        <th>Job title</th>
                        <th>Requisition number</th>
                        <th>Posting</th>
                        <th>Date if test</th>
                        <th>Start time</th>
                        <th>End time</th>
                        <th>Total score</th>
                        <th>Passing score</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($companie_jobs as $companie_job)
                    <tr>
                        <td>{{ $companie_job->company->company_name }}</td>
                        <td>{{ $companie_job->title }}</td>
                        <td>{{ $companie_job->requisition_number }}</td>
                        <td>{{ $companie_job->posting }}</td>
                        <td>{{ $companie_job->date_if_test }}</td>
                        <td>{{ $companie_job->start_time }}</td>
                        <td>{{ $companie_job->end_time }}</td>
                        <td>{{ $companie_job->total_score }}</td>
                        <td>{{ $companie_job->passing_score }}</td>
                        <td>
                            @if($companie_job->status == 1)
                            <p style="color:green;">Active</p>
                            @else
                            <p style="color:red"></p>
                            @endif
                        </td>
                        <td>
                            @if($companie_job->status == 1)
                            <a href="#">Assign College</a>
                            @endif
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