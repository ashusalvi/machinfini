@extends('layouts.admin')

@section('content')


    <div class="p-4 bg-white">
        <h4 class="mb-4"> Student Purchase Record </h4>
    </div>

    <div>
        <table class="table table-striped table-bordered">

            <tr>
                <th>Student name</th>
                <th>Course Name</th>
                <th>Lecture Name</th>
                <th>Course Price</th>
            </tr>

            @foreach ($earning as $row)
                <tr>
                    <td><a href="{{ route('purchase_view',['id'=>$row->id]) }}">{{$row->enroll['user']['name']}}</a></td>
                    <td>{{$row->course['title']}}</td>
                    <td>{{$row->user['name']}}</td>
                    <td>{{$row->amount}}</td>
                </tr>
            @endforeach


        </table>
    </div>


@endsection

@section('page-js')

@endsection