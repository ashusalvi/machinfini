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
        <h4>  College  </h4>
    </div>

    <div>
        <form method="get" style="max-width: 1039px;">
            <table id="earning_table" class="table table-striped table-bordered display">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Admin Name</th>
                        <th>Admin Email</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($companies as $company)
                    <tr>
                        <td>#</td>
                        <td>{{ $company->company_name }}</td>
                        <td>{{ $company->company_type }}</td>
                        <td>{{ $company->company_email }}</td>
                        <td>{{ $company->company_address }}</td>
                        <td>{{ $company->user['name'] }}</td>
                        <td>{{ $company->user['email'] }}</td>
                        <td>#</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>
@endsection

@section('page-js')
@endsection