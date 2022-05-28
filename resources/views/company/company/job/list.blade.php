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
@endsection

@section('content')
    @if(session()->has('message'))
        <div class="alert alert-success">
            <h4>{{ session()->get('message') }}</h4>
        </div>
    @endif

    <div class="alert alert-success"  id="showMessage" style="display:none">
    </div>

    <div class="company-header">
        <h5>  List of Company job </h5>
    </div>

    <div>
        <form method="get" style="max-width: 1039px;">
            <table id="earning_table" class="table table-striped table-bordered display">
                <thead>
                    <tr>
                        <th>Posting</th>
                        <th>Title</th>
                        <th>Company description</th>
                        <th>Requisition number</th>
                        <th>Date if test</th>
                        <th>Create date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($company_jobs as $company_job)
                    
                        <tr>
                        <td>{{ $company_job->posting }}</td>
                        <td>{{ $company_job->title }}</td>
                        <td>{{ $company_job->company_description }}</td>
                        <td>{{ $company_job->requisition_number }}</td>
                        <td>{{ $company_job->date_if_test }}</td>
                        <td>{{ $company_job->created_at }}</td>
                        <td>
                            <select class="form-control" job_id="{{ $company_job->id }}" name="active" id="active">
                                <option value="1" {{ $company_job->status == 1 ? 'selected':''}}>Active</option>
                                <option value="0" {{ $company_job->status == 0 ? 'selected':''}}>Inactive</option>
                            </select>
                        </td>
                        <td>
                            <button type="button" job_id="{{ $company_job->id }}"  class="btn btn-danger delete_button" > Delete</button>
                        </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>
@endsection

@section('page-js')
    <script>
        $('#active').change(function(){
            $.ajax({
                type: "GET",
                url: "{{ route('company_job_update_status') }}",
                data:{
                    'job_id' : $('#active').attr('job_id'),
                    'status' : $('#active').val(),
                },
                success: function(data){
                    let message= '<h4>'+data+'</h4>';
                    $('#showMessage').html(message);
                     $('#showMessage').css('display', 'block');
                     setTimeout(() => {
                        $('#showMessage').css('display', 'block'); 
                     }, 2000);
                },
                error: function(error){
                    console.error(error);
                }
            });
        });

        $('.delete_button').click(function(){
            $.ajax({
                url:"{{ route('company_job_delete') }}",
                type:"GET",
                data:{
                    'job_id':$(this).attr('job_id'),
                },
                success: function(data){
                    let message= '<h4>'+data+'</h4>';
                    $('#showMessage').html(message);
                    $('#showMessage').css('display', 'block');
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                },
                error: function(error){
                    console.error(error);
                }
            });
        });

    </script>
@endsection