@extends('layouts.admin')

@section('content')


    <form action="{{ route('saveCoupon') }}" method="post">
        @csrf

        <div class="profile-basic-info bg-white p-3">

            <div class="form-row">
                <div class="form-group col-md-12 {{ form_error($errors, 'name')->class }}">
                    <label>{{ __t('Coupon Name') }}</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter Coupon Name"
                        value="{{ old('name') }}" required>
                    {!! form_error($errors, 'name')->message !!}
                </div>
            </div>

            <div class="form-row">

                <div class="form-group col-md-6 {{ form_error($errors, 'code')->class }}">
                    <label>{{ __t('Coupon Code') }}</label>
                    <input type="text" class="form-control" name="code" placeholder="Enter Coupon Code"
                        value="{{ old('code') }}" required>
                    {!! form_error($errors, 'code')->message !!}
                </div>

                <div class="form-group col-md-6 {{ form_error($errors, 'percentage')->class }}">
                    <label>{{ __t('Coupon Percentage') }}</label>
                    <input type="number" class="form-control" name="percentage" placeholder="Enter Coupon percentage ( % )"
                        value="{{ old('percentage') }}" required>
                    {!! form_error($errors, 'percentage')->message !!}
                </div>

            </div>

            <div class="form-row">

                <div class="form-group col-md-6 {{ form_error($errors, 'from_date')->class }}">
                    <label>{{ __t('From Date') }}</label>
                    <input type="date" class="form-control" name="from_date" placeholder="Enter From Date"
                        value="{{ old('from_date') }}" required>
                    {!! form_error($errors, 'from_date')->message !!}
                </div>

                <div class="form-group col-md-6 {{ form_error($errors, 'to_date')->class }}">
                    <label>{{ __t('To Date') }}</label>
                    <input type="date" class="form-control" name="to_date" placeholder="Enter To Date"
                        value="{{ old('to_date') }}" required>
                    {!! form_error($errors, 'to_date')->message !!}
                </div>

            </div>

            <div class="form-row">
                <div class="form-group col-md-6 {{ form_error($errors, 'set_for')->class }}">
                    <label>{{ __t('Coupon Set For') }}</label>
                    <select name="set_for" class="form-control" id="coupon_set_for">
                        <option value="all">All</option>
                        <option value="instructor">Instructor</option>
                        <option value="course">Course</option>
                        <option value="channel_partner">Channel Partner</option>
                        <option value="company">Company </option>
                    </select>
                    {!! form_error($errors, 'set_for')->message !!}
                </div>

                <div class="form-group col-md-6 {{ form_error($errors, 'instructor_name')->class }}"
                    id="coupon_instructor_select_div" style="display:none;">
                    <label>{{ __t('Instructor Name') }}</label>
                    <select name="instructor_name" class="form-control">
                        <option value="" style="display:none;">Select Instructor</option>
                        @foreach ($user as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    {!! form_error($errors, 'instructor_name')->message !!}
                </div>

                <div class="form-group col-md-6 {{ form_error($errors, 'course_name')->class }}"
                    id="coupon_course_select_div" style="display:none;">
                    <label>{{ __t('Course Name') }}</label>
                    <select name="course_name" class="form-control">
                        <option value="" style="display:none;">Select Course</option>
                        @foreach ($course as $item)
                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                        @endforeach
                    </select>
                    {!! form_error($errors, 'course_name')->message !!}
                </div>

                <div class="form-group col-md-6 {{ form_error($errors, 'channel_partner')->class }}"
                    id="channel_partner_select_div" style="display:none;">
                    <label>{{ __t('Channel Partner') }}</label>
                    <select name="channel_partner" class="form-control">
                        <option value="" style="display:none;">Select Channel Partner</option>
                        @foreach ($channel_partner as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    {!! form_error($errors, 'channel_partner')->message !!}
                </div>

                <div class="form-group col-md-6 {{ form_error($errors, 'company')->class }}" id="company_select_div"
                    style="display:none;">
                    <label>{{ __t('company') }}</label>
                    <select id="company_select_id" name="company" class="form-control">
                        <option value="" style="display:none;">Select Company</option>
                        @foreach ($company as $item)
                            <option value="{{ $item->id }}">{{ $item->company_name }}</option>
                        @endforeach
                    </select>
                    {!! form_error($errors, 'company')->message !!}
                </div>

                <div class="form-group col-md-6 {{ form_error($errors, 'department')->class }}"
                    id="company_department_select_div" style="display:none;">
                    <label>{{ __t('department') }}</label>
                    <select id="company_department_select_id" name="department" class="form-control">
                        <option value="" style="display:none;">Select Department</option>
                    </select>
                    {!! form_error($errors, 'department')->message !!}
                </div>

                <div class="form-group col-md-6 {{ form_error($errors, 'company_score')->class }}" style="display:none;"
                    id="company_score_div">
                    <label>{{ __t('Enter Score') }}</label>
                    <input type="number" class="form-control" name="company_score" placeholder="Enter score"
                        value="{{ old('company_score') }}">
                    {!! form_error($errors, 'company_score')->message !!}
                </div>

            </div>


            <button type="submit" class="btn btn-info btn-lg"> Create Coupon</button>


        </div>


        <div class="p-4 bg-white">
            <h4 class="mb-4"> Last Created Coupon </h4>
        </div>
        <div style="margin: 20px 0px;">
            <div class="student_coupon_div"
                style="display: inline-block; padding: 10px 14px; box-shadow: 3px 3px 8px 2px #4b494940; margin-right: 20px; border-radius: 8px; background: #19a8d2; color: white;cursor: pointer;">
                Student Coupon</div>
            <div class="company_coupon_div"
                style="display: inline-block; padding: 10px 14px; box-shadow: 3px 3px 8px 2px #4b494940; margin-right: 20px; border-radius: 8px;  color: #111111;cursor: pointer;">
                Company Coupon</div>
        </div>
        <div class="student_coupon">
            <table class="table table-striped table-bordered">

                <tr>
                    <th>{{ 'Coupon Name' }}</th>
                    <th>{{ 'Course Name' }}</th>
                    <th>{{ 'Coupon Code' }}</th>
                    <th>{{ 'Coupon Percentage' }}</th>
                    <th>{{ 'Coupon Set For' }}</th>
                    <th>{{ 'Company Score' }}</th>
                    <th>{{ 'From Date' }}</th>
                    <th>{{ 'To Date' }}</th>
                    <th>{{ 'Status' }}</th>
                </tr>

                @foreach ($coupons as $coupon)
                    @if ($coupon->set_for != 'company')
                        <tr>
                            <td>{{ $coupon->name }}</td>
                            @if (!empty($coupon->course))
                                <td>{{ $coupon->course->title }}</td>
                            @else
                                <td>-</td>
                            @endif
                            <td>{{ $coupon->code }}</td>
                            <td>{{ $coupon->percentage }}</td>
                            <td>{{ $coupon->set_for }}</td>
                            <td>{{ $coupon->company_score }}</td>
                            <td>{{ $coupon->from_date }}</td>
                            <td>{{ $coupon->to_date }}</td>
                            @if ($coupon->status == 1)
                                <td style="color:green;">Active</td>
                            @else
                                <td style="color:red;">In Active</td>
                            @endif
                        </tr>
                    @endif
                @endforeach


            </table>
        </div>
        <div class="company_coupon" style="display:none;">
            <table class="table table-striped table-bordered">

                <tr>
                    <th>{{ 'Coupon Name' }}</th>
                    <th>{{ 'Course Name' }}</th>
                    <th>{{ 'Company' }}</th>
                    <th>{{ 'department' }}</th>
                    <th>{{ 'Coupon Code' }}</th>
                    <th>{{ 'Coupon Percentage' }}</th>
                    <th>{{ 'Coupon Set For' }}</th>
                    <th>{{ 'Company Score' }}</th>
                    <th>{{ 'From Date' }}</th>
                    <th>{{ 'To Date' }}</th>
                    {{-- <th>{{'Status'}}</th> --}}
                </tr>

                @foreach ($coupons as $coupon)
                    @if ($coupon->set_for == 'company')
                        <tr>
                            <td>{{ $coupon->name }}</td>
                            @if (!empty($coupon->course))
                                <td>{{ $coupon->course->title }}</td>
                            @else
                                <td>-</td>
                            @endif
                            @if (!empty($coupon->company))
                                <td>{{ $coupon->company->company_name }}</td>
                            @else
                                <td>-</td>
                            @endif
                            @if (!empty($coupon->companyDepartment))
                                <td>{{ $coupon->companyDepartment->name }}</td>
                            @else
                                <td>-</td>
                            @endif
                            <td>{{ $coupon->code }}</td>
                            <td>{{ $coupon->percentage }}</td>
                            <td>{{ $coupon->set_for }}</td>
                            <td>{{ $coupon->company_score }}</td>
                            <td>{{ $coupon->from_date }}</td>
                            <td>{{ $coupon->to_date }}</td>
                            {{-- @if ($coupon->status == 1)
                            <td style="color:green;">Active</td>
                        @else
                            <td style="color:red;">In Active</td>
                        @endif --}}
                        </tr>
                    @endif
                @endforeach


            </table>
        </div>

    </form>




@endsection

@section('page-js')
    <script>
        $("#coupon_set_for").change(function() {
            $('#company_department_select_id').html(
                '<option value="" style="display:none;">Select Department</option>');
            if ($("#coupon_set_for").val() == 'all') {

                $('#coupon_instructor_select_div').css('display', 'none');
                $('#company_department_select_div').css('display', 'none');
                $('#coupon_course_select_div').css('display', 'none');
                $('#channel_partner_select_div').css('display', 'none');

            } else if ($("#coupon_set_for").val() == 'instructor') {

                $('#coupon_instructor_select_div').css('display', 'block');
                $('#coupon_course_select_div').css('display', 'none');
                $('#channel_partner_select_div').css('display', 'none');
                $('#company_department_select_div').css('display', 'none');

            } else if ($("#coupon_set_for").val() == 'course') {

                $('#coupon_instructor_select_div').css('display', 'none');
                $('#coupon_course_select_div').css('display', 'block');
                $('#channel_partner_select_div').css('display', 'none');
                $('#company_department_select_div').css('display', 'none');

            } else if ($("#coupon_set_for").val() == 'channel_partner') {

                $('#coupon_instructor_select_div').css('display', 'none');
                $('#coupon_course_select_div').css('display', 'none');
                $('#channel_partner_select_div').css('display', 'block');
                $('#company_department_select_div').css('display', 'none');

            } else if ($("#coupon_set_for").val() == 'company') {

                $('#coupon_instructor_select_div').css('display', 'none');
                $('#coupon_course_select_div').css('display', 'none');
                $('#channel_partner_select_div').css('display', 'none');
                $('#company_select_div').css('display', 'block');
                $('#company_score_div').css('display', 'block');
                $('#coupon_course_select_div').css('display', 'block');
                $('#company_department_select_div').css('display', 'block');
            }
        });

        $("#company_select_id").change(function() {
            $.ajax({
                url: "/admin/coupon/get_department?id=" + $('#company_select_id').val(),
                method: 'GET',
                success: function(result) {
                    $('#company_department_select_id').html(result);
                }
            });
        });

        $('.student_coupon_div').click(function() {
            $('.student_coupon_div').css('background', '#19a8d2');
            $('.student_coupon_div').css('color', 'white');
            $('.company_coupon_div').css('background', 'white');
            $('.company_coupon_div').css('color', '#111111');
            $('.student_coupon').css('display', 'block');
            $('.company_coupon').css('display', 'none');
        });

        $('.company_coupon_div').click(function() {
            $('.company_coupon_div').css('background', '#19a8d2');
            $('.company_coupon_div').css('color', 'white');
            $('.student_coupon_div').css('background', 'white');
            $('.student_coupon_div').css('color', '#111111');
            $('.student_coupon').css('display', 'none');
            $('.company_coupon').css('display', 'block');
        });
    </script>
@endsection
