@extends(theme('dashboard.layout'))

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success">
            <h4>{{ session()->get('message') }}</h4>
        </div>
    @endif
    <form action="{{ route('icSaveCoupon') }}" method="post">
        @csrf

        <div class="profile-basic-info bg-white p-3">

            <div class="form-row">
                <div class="form-group col-md-12 {{ form_error($errors, 'name')->class }}">
                    <label>{{ __t('Interactive Course Coupon Name') }}</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter Coupon Name"
                        value="{{ old('name') }}" required>
                    {!! form_error($errors, 'name')->message !!}
                </div>
            </div>

            <div class="form-row">

                <div class="form-group col-md-6 {{ form_error($errors, 'code')->class }}">
                    <label>{{ __t('Coupon Code') }}</label>
                    <span style="display: flex;">
                        <input value="IC{{ Auth::user()->id }}-" style="width: 20%;" readonly="" disabled="">
                        <input type="text" class="form-control" name="code" placeholder="Enter Coupon Code"
                            value="{{ old('code') }}" required="">
                    </span>
                    {!! form_error($errors, 'code')->message !!}
                </div>

                <div class="form-group col-md-6 {{ form_error($errors, 'percentage')->class }}">
                    <label>{{ __t('Coupon Percentage (Less than 50%)') }}</label>
                    <input type="text" class="form-control" name="percentage" placeholder="Enter Coupon percentage ( % )"
                        value="{{ old('percentage') }}" min="1" max="50" id="percentage_value"
                        onkeypress="return isNumber(event)" required>
                    {!! form_error($errors, 'percentage')->message !!}
                </div>

            </div>

            <div class="form-row">

                <div class="form-group col-md-6 {{ form_error($errors, 'from_date')->class }}">
                    <label>{{ __t('From Date') }}</label>
                    <input id="from_date_input" type="date" class="form-control" name="from_date"
                        placeholder="Enter From Date" value="{{ old('from_date') }}" min="<?= date('Y-m-d') ?>" required>
                    {!! form_error($errors, 'from_date')->message !!}
                </div>

                <div class="form-group col-md-6 {{ form_error($errors, 'to_date')->class }}">
                    <label>{{ __t('To Date') }}</label>
                    <input id="to_date_input" type="date" class="form-control" name="to_date" placeholder="Enter To Date"
                        value="{{ old('to_date') }}" min="<?= date('Y-m-d') ?>" required>
                    {!! form_error($errors, 'to_date')->message !!}
                </div>

            </div>


            <button type="submit" class="btn btn-info btn-lg"> Create Coupon</button>


        </div>


        <div class="p-4 bg-white">
            <h4 class="mb-4"> Last Created Coupon </h4>
        </div>
        <div class="student_coupon">
            <table class="table table-striped table-bordered">

                <tr>
                    <th>{{ 'Name' }}</th>
                    <th>{{ 'Code' }}</th>
                    <th>{{ 'Coupon %' }}</th>
                    <th>{{ 'From Date' }}</th>
                    <th>{{ 'To Date' }}</th>
                    <th>{{ 'Status' }}</th>
                    <th>{{ 'Action' }}</th>
                </tr>

                @foreach ($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->name }}</td>
                        <td>{{ $coupon->code }}</td>
                        <td>{{ $coupon->percentage }}</td>
                        <td>{{ $coupon->from_date }}</td>
                        <td>{{ $coupon->to_date }}</td>
                        @if ($coupon->is_deleted == 1)
                            <td style="color:green;">Active</td>
                        @else
                            <td style="color:red;">In Active</td>
                        @endif
                        <td>
                            <input type="text"
                                value="{{ route('cihome', ['ic_code' => base64_encode($coupon->code)]) }}"
                                id="linked{{ $coupon->id }}" style="opacity: 0; position: absolute;z-index: -1;">
                            <a href="{{ route('ic_delete', [$coupon->id]) }}"><button type="button"
                                    class="btn btn-danger">Delete</button></a>
                            <button type="button" class="btn btn-primary" style="cursor: pointer;"
                                onclick="copyLink({{ $coupon->id }})">Link</button>
                        </td>
                    </tr>
                @endforeach


            </table>
        </div>

    </form>
@endsection

@section('page-js')
    <script>
        $('#from_date_input').change(function() {
            let from_date = $(this).val();
            let to_date = addDays(from_date, 3);
            var dd = to_date.getDate();
            var mm = to_date.getMonth() + 1; //January is 0!
            var yyyy = to_date.getFullYear();

            if (dd < 10) {
                dd = '0' + dd;
            }

            if (mm < 10) {
                mm = '0' + mm;
            }

            to_date = yyyy + '-' + mm + '-' + dd;
            $('#to_date_input').val('');
            $('#to_date_input').attr('min', from_date);
            $('#to_date_input').attr('max', to_date);
        });

        function addDays(date, number) {
            const newDate = new Date(date);
            return new Date(newDate.setDate(newDate.getDate() + number));
        }

        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }

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

        function copyLink(id) {
            console.log(id);
            let tempId = '#linked' + id;
            var copyText = $(tempId);
            copyText.select();
            document.execCommand("copy");
        }
    </script>
@endsection
