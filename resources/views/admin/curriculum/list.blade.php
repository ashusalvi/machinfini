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
        <h4> Curriculum List </h4>
    </div>

    <div>
        <form method="get" style="max-width: 1039px;">
            <table id="earning_table" class="table table-striped table-bordered display">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Classes</th>
                        <th>Details</th>
                        <th>Price</th>
                        <th>Tag</th>
                        <th>save</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($curriculums as $curriculum)
                        <tr>
                            <td>{{ $curriculum->title }}</td>
                            <td>{{ $curriculum->type }}</td>
                            <td>{{ $curriculum->classes }}</td>
                            <td>{{ $curriculum->description }}</td>
                            <td>{{ $curriculum->price }}</td>
                            <td>{{ $curriculum->tag }}</td>
                            <td>{{ $curriculum->save }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>
@endsection

@section('page-js')
@endsection
