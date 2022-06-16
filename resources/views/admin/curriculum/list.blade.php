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
                        <th>Status</th>
                        <th>Action</th>
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
                            <td>
                                {{ $curriculum->status == 1 ? 'Active' : 'Deleted' }}
                            </td>
                            <td>
                                @php
                                    if ($curriculum->status == 1) {
                                        echo '<button type="button" cvalue="0" cid="' . $curriculum->id . '" class="btn btn-danger cAction">Delete</button>';
                                    } else {
                                        echo '<button type="button" cvalue="1" cid="' . $curriculum->id . '" class="btn btn-success cAction">Active</button>';
                                    }
                                @endphp

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>
@endsection

@section('page-js')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(".cAction").click(function() {
            swal({
                    title: "Are you sure?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        var cid = $(this).attr("cid");
                        var cvalue = $(this).attr("cvalue");
                        var actionUrl = "{{ route('curriculum.delete') }}";
                        $.ajax({
                            type: "GET",
                            url: actionUrl,
                            data: {
                                'cid': cid,
                                'cvalue': cvalue
                            },
                            success: function(data) {
                                swal("Curriculum updated successfully!", {
                                    icon: "success",
                                });
                                location.reload();
                            }
                        });
                    } else {
                        swal("Your curriculum not updated!");
                    }
                });
        });
    </script>
@endsection
