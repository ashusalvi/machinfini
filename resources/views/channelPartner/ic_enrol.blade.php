@extends(theme('dashboard.layout'))

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success">
            <h4>{{ session()->get('message') }}</h4>
        </div>
    @endif
    <div class="p-4 bg-white">
        <h4 class="mb-4"> Interactive Course Enrollment</h4>
    </div>
    <div class="student_coupon">
        <table class="table table-striped table-bordered">

            <tr>
                <th>{{ 'Student Name' }}</th>
                <th>{{ 'Mobile Number' }}</th>
                <th>{{ 'Course Name' }}</th>
                <th>{{ 'Course Price' }}</th>
                <th>{{ 'Coupon %' }}</th>
                <th>{{ 'Final Price' }}</th>
            </tr>

            @foreach ($curriculumEnquirys as $curriculumEnquiry)
                <tr>
                    <td>{{ $curriculumEnquiry->name }}</td>
                    <td>{{ $curriculumEnquiry->mobile }}</td>
                    <td>{{ $curriculumEnquiry->Curriculum->title }}</td>
                    <td>{{ $curriculumEnquiry->Curriculum->price }}</td>
                    <td>{{ $curriculumEnquiry->cp_per }}%</td>
                    <td>{{ round($curriculumEnquiry->Curriculum->price * ($curriculumEnquiry->cp_per / 100), 2) }}</td>
                </tr>
            @endforeach


        </table>
    </div>
@endsection

@section('page-js')
    <script></script>
@endsection
