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
        <h4>  Companies Coupon </h4>
    </div>

    <div>
        <form method="get" style="max-width: 1039px;">
            <table id="earning_table" class="table table-striped table-bordered display">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>company</th>
                        <th>Department Name</th>
                        <th>Coupon Name</th>
                        <th>Coupon Code</th>
                        <th>Price</th>
                        <th>Expiry Date</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($company_coupons as $company_coupon)
                    <tr>
                        <td>#</td>
                        <td>{{ $company_coupon->company->company_name }}</td>
                        <td>{{ $company_coupon->companyDepartment->name }}</td>
                        <td>{{ $company_coupon->name }}</td>
                        <td>{{ $company_coupon->code }}</td>
                        <td>{{ $company_coupon->price }}</td>
                        <td>{{ $company_coupon->expiry_date }}</td>
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