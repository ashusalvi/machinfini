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
    <div class="company-header">
        <h4>  Create College coupon  </h4>
    </div>

    <div class="form-body">
        <form method="post" action="{{ route('storeCompanyCoupon') }}" style="max-width: 1039px;">

            <h5 class="title">College Details</h5>

            @csrf
            <div class="form-row">
                <div class="form-group col-md-6 {{ form_error($errors, 'company_id')->class }}">
                    <label><b>{{ __t('College') }}</b></label>
                    <select  name="company_id" class="form-control" required>
                        <option value="" style="display:none;">Select College</option>
                        @foreach ($companies as $item)
                            <option value="{{ $item->id }}">{{ $item->company_name }}</option>
                        @endforeach
                    </select>
                    {!! form_error($errors, 'company')->message !!}
                </div>
                <div class="form-group col-md-6 {{form_error($errors, 'department_name')->class}}">
                    <label><b>Department Name</b></label>
                    <input type="text" class="form-control" name="department_name" placeholder="Enter department name" value="{{ old('department_name') }}"  required>
                    {!! form_error($errors, 'department_name')->message !!}
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6 {{form_error($errors, 'coupon_name')->class}}">
                    <label><b>Coupon Name</b></label>
                    <input type="text" class="form-control" name="coupon_name" placeholder="Enter coupon name" value="{{ old('coupon_name') }}" required>
                    {!! form_error($errors, 'coupon_name')->message !!}
                </div>
                <div class="form-group col-md-6 {{form_error($errors, 'code')->class}}">
                    <label><b>Coupon Code</b></label>
                    <input type="text" class="form-control" name="code" placeholder="Enter coupon code" value="{{ old('code') }}" required>
                    {!! form_error($errors, 'code')->message !!}
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6 {{form_error($errors, 'price')->class}}">
                    <label><b>Price</b></label>
                    <input type="number" class="form-control" name="price" placeholder="Enter price" value="{{ old('price') }}" required>
                    {!! form_error($errors, 'price')->message !!}
                </div>
                <div class="form-group col-md-6 {{ form_error($errors, 'expiry_date')->class }}">
                    <label><b>{{ __t('Expiry Date') }}</b></label>
                    <input type="date" class="form-control" name='expiry_date' placeholder="Enter expiry date"
                        value="{{ old('expiry_date') }}" required>
                    {!! form_error($errors, 'expiry_date')->message !!}
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