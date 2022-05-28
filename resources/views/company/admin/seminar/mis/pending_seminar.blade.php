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
        <h5>  Pending Seminar </h5>
    </div>

    <div class="table-responsive">
        <input type="text" id="daterange_value" name="daterange" value="{{ $from_date }}- {{ $to_date }}" style="float:right; margin-bottom: 10px; width: 220px; font-size: 15px; border: #aaaaaa 1px solid; padding: 5px 10px; border-radius: 4px;" />
        <form method="get" style="max-width: 1039px;">
            <table id="earning_table" class="table table-striped table-bordered display">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Learner ID</th>
                        <th>Learner Name</th>
                        <th>Learner Email</th>
                        <th>Learner Number</th>
                        <th>Due date</th>
                        <th>Breach days</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($response_array as$key =>  $response)
                        
                        @php
                            $employee = $response['employees'];
                            $seminar = $response['seminar'];
                            $due_date = \Carbon\Carbon::parse($seminar->due_date);
                            $completed_date = \Carbon\Carbon::now();
                        @endphp
                        <tr>
                            <td>{{ $seminar->title }}</td>
                            <td>{{ $employee->employee_id}}</td>
                            <td>{{ $employee->user->name}}</td>
                            <td>{{ $employee->user->email}}</td>
                            <td>{{ $employee->user->phone}}</td>
                            {{-- <td>-</td> --}}
                            <td>{{ $seminar->due_date }}</td>
                            <td>{{ strtotime($due_date) < strtotime($completed_date)?$due_date->diffInDays($completed_date) : 0 }}</td>
                            <td>
                                @php
                                    $completed_percent = $seminar->completed_percent($employee->user->id); 
                                @endphp
                                <div class="lecture-page-course-progress mb-4 px-4 text-center">
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-info" style="width: {{$completed_percent}}%"></div>
                                    </div>
                                    <div class="course-progress-percentage text-info d-flex justify-content-between">
                                        <p class="m-0">
                                        <span class="percentage">
                                            {{$completed_percent}}%
                                        </span>
                                            {{__t('complete')}}
                                        </p>
                                    </div>
                                </div>
                            </td>
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
            responsive: true,
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
            $('#daterange_value').val(start_date+'-'+end_date);
            let url = '/company/admin/seminar/all_pending_seminar/'+start_date+'/'+end_date;
            window.location.href =url ;
        });
    });
</script>

@endsection