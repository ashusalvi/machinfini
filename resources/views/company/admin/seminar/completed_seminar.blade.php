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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')
    @if(session()->has('message'))
        <div class="alert alert-success">
            <h4>{{ session()->get('message') }}</h4>
        </div>
    @endif

    <div class="company-header">
        <h5>  Completed Seminar of {{ $employee_details->name }} </h5>
    </div>

    <div>
        <input type="text" id="daterange_value" name="daterange" value="{{ $from_date }}- {{ $to_date }}" style="float:right; margin-bottom: 10px; width: 220px; font-size: 15px; border: #aaaaaa 1px solid; padding: 5px 10px; border-radius: 4px;" />
        <form method="get" style="max-width: 1039px;">
            <table id="earning_table" class="table table-striped table-bordered display">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Total Lecture</th>
                        <th>Total Quiz</th>
                        <th>DH name</th>
                        <th>Due Date</th>
                        {{-- <th>Expire Date</th> --}}
                        {{-- <th>Enroll Date</th> --}}
                        <th>Completed Date</th>
                        <th>Total Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($completed_seminars as $completed_seminar)
                        <tr>
                            <td>{{ $completed_seminar->seminar->title }}</td>
                            <td>{{ $completed_seminar->seminar->total_lectures }}</td>
                            <td>{{ $completed_seminar->seminar->total_quiz }}</td>
                            <td>{{ $completed_seminar->seminar->author->name }}</td>
                            <td>{{ $completed_seminar->seminar->due_date }}</td>
                            {{-- <td>{{ $completed_seminar->seminar->expiry_date }}</td> --}}
                            {{-- <td>{{ $completed_seminar->completed_at }}</td> --}}
                            <td>{{ date($completed_seminar->completed_at) }}</td>
                            <td>{{ $completed_seminar->seminar->score }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>
@endsection

@section('page-js')
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    $(document).ready( function () {
        $('#earning_table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'pdf'
            ]
        });
    } );
</script>

<script>
    $(function() {
        $('input[name="daterange"]').daterangepicker({
            showDropdowns: true,
            startDate: moment('{{ date($from_date) }}'),
            endDate: moment('{{ date($to_date) }}'),
            minDate:moment('2021-05-01'),
            locale: { 
                format: 'YYYY-MM-DD'
            }
        }, function(start, end, label) {
            let start_date = start.format("YYYY-MM-DD");
            let end_date = end.format("YYYY-MM-DD");
            // console.log(start_date, end_date);
            $('#daterange_value').val(start_date+'-'+end_date);
            let url = '/company/admin/employee/employee_completed_course/{{ $employee_details->id }}/'+start_date+'/'+end_date;
            console.log(url);
            window.location.href =url ;
        });
    });
</script>

@endsection