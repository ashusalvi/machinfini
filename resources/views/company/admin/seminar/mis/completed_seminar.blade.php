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
        <h5>  Completed Seminar </h5>
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
                        <th>Due Date</th>
                        <th>Completed Date</th>
                        <th>Within Due date</th>
                        <th>Quiz Score</th>
                        <th>Percentage</th>
                        <th>Grade</th>
                        <th>Score</th>
                        <th>Breach days</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($completed_seminars as $completed_seminar)
                        @php
                            $due_date = \Carbon\Carbon::parse($completed_seminar->seminar->due_date);
                            $completed_date = \Carbon\Carbon::parse($completed_seminar->completed_at);
                        @endphp
                        <tr>
                            <td>{{ $completed_seminar->seminar->title }}</td>
                            <td>{{ $completed_seminar->companyEmployee($completed_seminar->user_id)->employee_id }}</td>
                            <td>{{ $completed_seminar->companyEmployee($completed_seminar->user_id)->user->name }}</td>
                            <td>{{ $completed_seminar->companyEmployee($completed_seminar->user_id)->user->email }}</td>
                            <td>{{ $completed_seminar->companyEmployee($completed_seminar->user_id)->user->phone }}</td>
                           
                            <td>{{ $due_date->format('Y-m-d') }}</td>
                            <td>{{ $completed_date->format('Y-m-d') }}</td>
                            @if (strtotime($due_date->format('Y-m-d')) >= strtotime($completed_date->format('Y-m-d')))
                                <td>Within Due Date</td>
                            @else
                                <td>Outside Due Date</td>
                            @endif
                            {{-- <td>{{ $completed_seminar->seminar->due_date }}</td> --}}
                            
                            <?php 
                                if(getCompanyScore($completed_seminar->seminar->id,$completed_seminar->companyEmployee($completed_seminar->user_id)->user_id ) != '-1'){
                            ?>
                            <td>{{ getCompanyScore($completed_seminar->seminar->id,$completed_seminar->companyEmployee($completed_seminar->user_id)->user_id) }} / {{ TotalCompanyScore($completed_seminar->seminar->id,$completed_seminar->companyEmployee($completed_seminar->user_id)->user_id) }}</td>
                            
                            <?php 
                                }else{
                            ?>
                                <td>-</td>
                            <?php
                                }
                            ?>
                            
                            <?php 
                                if(getCompanyScore($completed_seminar->seminar->id,$completed_seminar->companyEmployee($completed_seminar->user_id)->user_id ) != '-1'){
                            ?>
                            <td>{{ round((getCompanyScore($completed_seminar->seminar->id,$completed_seminar->companyEmployee($completed_seminar->user_id)->user_id) / TotalCompanyScore($completed_seminar->seminar->id,$completed_seminar->companyEmployee($completed_seminar->user_id)->user_id) )*100,2) }}%</td>
                            
                            <?php 
                                }else{
                            ?>
                                <td>-</td>
                            <?php
                                }
                            ?>
                            
                            <?php 
                                if(getCompanyScore($completed_seminar->seminar->id,$completed_seminar->companyEmployee($completed_seminar->user_id)->user_id ) != '-1'){
                            ?>
                                <td>
                                    <?php
                                        $percentage_value = (getCompanyScore($completed_seminar->seminar->id,$completed_seminar->companyEmployee($completed_seminar->user_id)->user_id) / TotalCompanyScore($completed_seminar->seminar->id,$completed_seminar->companyEmployee($completed_seminar->user_id)->user_id) )*100;
                                        if($percentage_value <= 32){
                                            echo "E1";
                                        }else if($percentage_value > 32 && $percentage_value <= 40){
                                            echo "D";
                                        }else if($percentage_value > 40 && $percentage_value <= 50){
                                            echo "C2";
                                        }else if($percentage_value > 50 && $percentage_value <= 60){
                                            echo "C1";
                                        }else if($percentage_value > 60 && $percentage_value <= 70){
                                            echo "B2";
                                        }else if($percentage_value > 70 && $percentage_value <= 80){
                                            echo "B1";
                                        }else if($percentage_value > 80 && $percentage_value <= 90){
                                            echo "A2";
                                        }else if($percentage_value > 90){
                                            echo "A1";
                                        }
                                    ?>
                                </td>
                            <?php 
                                }else{
                            ?>
                                <td>-</td>
                            <?php
                                }
                            ?>
                            
                            <td>{{ $completed_seminar->seminar->score }}</td>
                            
                            <td>{{ strtotime($due_date) < strtotime($completed_date)?$due_date->diffInDays($completed_date) : 0 }}</td>
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
            let url = '/company/admin/seminar/all_completed_seminar/'+start_date+'/'+end_date;
            window.location.href =url ;
        });
    });
</script>

@endsection