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
        <h4>  College Count  </h4>
    </div>

    <div>
        <select name="company_id" id="company" style="float:right; margin-bottom: 10px; width: 220px; font-size: 15px; border: #aaaaaa 1px solid; padding: 5px 10px; border-radius: 4px;">
            <option value="" hidden>select College</option>
            @foreach ($all_company as $company)
                <option value="{{ $company->id }}" {{ $company_id == $company->id ? "selected" :"" }} >{{ $company->company_name }}</option>
            @endforeach
        </select>
        <input type="text" id="daterange_value" name="daterange" value="{{ $from_date }}- {{ $to_date }}" style="float:right; margin-bottom: 10px; width: 220px; font-size: 15px; border: #aaaaaa 1px solid; padding: 5px 10px; border-radius: 4px;" />
        <input type="hidden" name="from_date" id="from_date" value="{{ $from_date }}" >
        <input type="hidden" name="to_date" id="to_date" value="{{ $to_date }}" >
        <form method="get" style="max-width: 1039px;">
            <table id="earning_table" class="table table-striped table-bordered display">
                <thead>
                    <tr>
                        <th>College Name</th>
                        <th>Total Admin Count</th>
                        <th>Total department head </br> (active )</th>
                        <th>Total Student </br> (Active)</th>
                        <th>course count </br> ( Active )</th>
                        <th>Seminar Counts</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($companies as $company)
                    <tr>
                        <td>{{ $company->company_name }}</td>
                        <td>1</td>
                        <td>{{ $company->departmentHead($company->id)->count() }}</td>
                        {{-- <td>{{ $company->company_number }}</td> --}}
                        <td>{{ $company->employeeHead($company->id)->count() }}</td>
                        <td>{{ $company->coupon($company->id)->count() }}</td>
                        <td>{{ $company->CompanySeminar($company->id)->count() }}</td>
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
            $('#from_date').val(start_date);
            $('#to_date').val(end_date);
            $('#daterange_value').val(start_date+'-'+end_date);
            let company     = $('#company').val();
            let url = '/admin/company/mis/mis_company_count/'+start_date+'/'+end_date;
            if (company != "") {
                url += '/'+company;
            }
            
            window.location.href =url ;
        });
    });

    $("#company").change(function(){
        let company     = $('#company').val();
        let from_date   = $('#from_date').val();
        let to_date     = $('#to_date').val();
        let url = '/admin/company/mis/mis_company_count/'+from_date+'/'+to_date+'/'+company;
        window.location.href =url ;
    });
</script>


@endsection