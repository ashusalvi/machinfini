@extends('layouts.admin')

@section('page-header-right')
@endsection

@section('page-css')
    <style>
        .company-header {
            padding: 20px 10px;
            background: #b6a4298a;
            font-weight: 600;
            margin-top: 20px;
        }

        .company-header h4 {
            font-weight: 600;
        }
    </style>
@endsection

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success">
            <h4>{{ session()->get('message') }}</h4>
        </div>
    @endif

    <div class="company-header">
        <h4> Curriculum Enquiry </h4>
    </div>

    <div>
        <form method="get" style="max-width: 1039px;">
            <table id="earning_table" class="table table-striped table-bordered display">
                <thead>
                    <tr>
                        <th>Sr.No.</th>
                        <th>Institute Name</th>
                        <th>Curriculum Title</th>
                        <th>Price</th>
                        <th>Offer Price</th>
                        <th>Off %</th>
                        <th>Student Name</th>
                        <th>Student Email</th>
                        <th>Student Mobile</th>
                        <th>Student Message</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($curriculumEnquirys as $curriculum)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $curriculum->institude_name }}</td>
                            <td>{{ $curriculum->Curriculum->title }}</td>
                            <td>{{ $curriculum->Curriculum->price }}</td>
                            @if ($curriculum->cp_id != null)
                                <td>{{ $curriculum->Curriculum->price - $curriculum->Curriculum->price * ($curriculum->cp_per / 100) }}
                                </td>
                                <td>{{ $curriculum->cp_per }}%</td>
                            @else
                                <td>{{ $curriculum->Curriculum->price }}</td>
                                <td>0%</td>
                            @endif
                            <td>{{ $curriculum->name }}</td>
                            <td>{{ $curriculum->email }}</td>
                            <td>{{ $curriculum->mobile }}</td>
                            <td>{{ $curriculum->message }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>
@endsection

@section('page-js')
@endsection
