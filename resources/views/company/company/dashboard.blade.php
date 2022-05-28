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
        margin-bottom: 20px;
    }
    .company-header h5 span
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
        <h5>  Hi <span>{{ Auth::user()->name }}</span>  </h5>
        {{-- <h5>  Hi  </h5> --}}
    </div>

    <div>
        <div class="row">

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-user"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value">
                            <h4>0</h4>
                        </div>
                        <div>Job</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-user-graduate"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value">
                            <h4>0</h4>
                        </div>
                        <div>Active Job</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-clipboard-list"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value">
                            <h4>0</h4>
                        </div>
                        <div>In-Active Job</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="dashboard-card mb-3 d-flex border p-3 bg-light">
                    <div class="card-icon mr-2">
                        <span><i class="la la-clipboard-list"></i> </span>
                    </div>

                    <div class="card-info">
                        <div class="text-value">
                            <h4>0</h4>
                        </div>
                        <div>No of Candidates</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('page-js')
@endsection