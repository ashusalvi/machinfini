@extends('layouts.admin')

@section('page-header-right')
    @if(count(request()->input()))
        <a href="{{route('earnings')}}"> 
            <i class="la la-arrow-circle-left"></i> {{__a('reset_filter')}}  
        </a>
    @endif
     
@endsection

@section('content')
    <h4 style="margin:20px, 0px;">  Earning  </h4>
   
    <form method="get" style="max-width: 1027px;">

        {{-- @if($payments->count() > 0) --}}



            {{-- <p class="text-muted my-3"> <small>Showing {{$payments->count()}} from {{$payments->total()}} results</small> </p> --}}

            <table id="earning_table" class="table table-striped table-bordered display">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Courses</th>
                        <th>Instructor</th>
                        <th>Student Name</th>
                        <th>Date of enrollment</th>
                        <th>Actual Fees</th>
                        <th>Checkout Amount</th>
                        <th>Coupon Code</th>
                        <th>Channel Partner Code</th>
                        <th>Channel Partner Name</th>
                        <th>Due to Instructor</th>
                        <th>Due to Channel Partner</th>
                        <th>Net revenue of admin</th>
                    </tr>
                </thead>
                <tbody>
                @php
                    $count_number = 0;
                @endphp
                @foreach($earnings as $key => $earning)
                @php
                    $count_number = $count_number + 1;
                @endphp
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <th>{{ $earning->course['title'] }}</th>
                        <td>{{ $earning->user['name'] }}</td>
                        <td>{{ $earning->payment->user['name'] }}</td>
                        <td>{{ $earning->created_at}}</td>
                        @if(!empty($earning->course))
                            <td>{!! $earning->course->price_html() !!}</td>
                        @else
                            <td> - </td>
                        @endif
                        <td>{!! price_format($earning->amount) !!}</td>
                        <td>{{ $earning->coupon }}</td>
                        @php
                            $channel_partner = $earning->payment->channelPartner;
                        @endphp
                        @if ($channel_partner != '')
                            @if($channel_partner->where('course_id',$earning->course_id)->first() != '')
                                <td>{{ $channel_partner->where('course_id',$earning->course_id)->first()->channelpartner['channel_partner_code'] }}</td>
                            @else
                                <td>-</td>
                            @endif
                        @else
                            <td> - </td>
                        @endif

                        @if ($channel_partner != '')
                            @if($channel_partner->where('course_id',$earning->course_id)->first() != '')
                                <td>{{ $channel_partner->where('course_id',$earning->course_id)->first()->channelpartner['name'] }}</td>
                            @else
                                <td>-</td>
                            @endif
                        @else
                            <td> - </td>
                        @endif
                        
                        <td>{!! price_format($earning->instructor_amount) !!}</td>
                        @if ($channel_partner != '')
                            @if($channel_partner->where('course_id',$earning->course_id)->first() != '')
                                @php
                                    $partner_ship_share = $channel_partner->where('course_id',$earning->course_id)->first()->channelpartner['channel_partner_per'] / 100;
                                @endphp
                                <td>{!! price_format($earning->amount * $partner_ship_share) !!}</td>
                            @else
                                <td>0</td>
                            @endif
                        @else
                            <td> 0 </td>
                        @endif

                        @if ($channel_partner != '')
                            @if($channel_partner->where('course_id',$earning->course_id)->first() != '')
                                @php
                                    $admin_share = $earning->admin_amount - ($earning->amount * $partner_ship_share);
                                @endphp
                                <td>{!! price_format($admin_share) !!}</td>
                            @else
                                <td>{!! price_format($earning->admin_amount) !!}</td>
                            @endif
                        @else
                           <td>{!! price_format($earning->admin_amount) !!}</td>
                        @endif
                        
                    </tr>
                @endforeach
                </tbody>

            </table>

            {{-- {!! $payments->appends(['q' => request('q'), 'status'=> request('filter_status') ])->links() !!} --}}

        {{-- @else
            {!! no_data() !!}
        @endif --}}

    </form>


@endsection

@section('page-js')
    <script type="text/javascript">
        $(document).ready( function () {
            $('#earning_table').DataTable({
                "scrollX": true,
            });
        } );
    </script>
@endsection

