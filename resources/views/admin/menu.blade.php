<div class="navbar-default sidebar" role="navigation">
    <div id="adminmenuback"></div>
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li>
                <a href="{{route('admin')}}"><i class="la la-dashboard fa-fw"></i> @lang('admin.admin_home')</a>
            </li>

            @php
            do_action('admin_menu_item_before');
            @endphp

            <li>
                <a href="#"><i class="la la-newspaper-o fa-fw"></i> @lang('admin.cms')<span class="la arrow"></span></a>
                <ul class="nav nav-second-level" style="display: none;">
                    <li> <a href="{{ route('posts') }}">@lang('admin.posts')</a> </li>
                    <li> <a href="{{ route('pages') }}">@lang('admin.pages')</a> </li>
                </ul><!-- /.nav-second-level -->
            </li>

            <li>
                <a href="{{route('media_manager')}}"><i class="la la-photo-video"></i> @lang('admin.media_manager')</a>
            </li>

            {{-- college / Company section --}}
            <li>
                <a href="#"><i class="la la-building fa-fw"></i> College / Company <span class="la arrow"></span></a>
                <ul class="nav nav-second-level" style="display: none;">
                    <li> 
                        <a href="{{ route('companyCreate') }}">@lang('admin.create') @lang('College') / Company</a> 
                    </li>
                    <li> 
                        <a href="{{ route('companyList') }}">List Company</a> 
                    </li>
                    <li> 
                        <a href="{{ route('companyJobList') }}"> Company Jobs</a> 
                    </li>
                    <li> 
                        <a href="{{ route('company') }}">@lang('admin.list') @lang('College')</a> 
                    </li>
                    <li> 
                        <a href="{{ route('mis_company_count') }}">College Count</a> 
                    </li>
                    <li> 
                        <a href="{{ route('mis_course_status') }}">College Course Status</a> 
                    </li>
                    <li> 
                        <a href="{{ route('mis_personnel_data') }}">College Personnel  Data</a> 
                    </li>
                    {{-- <li> 
                        <a href="#">Student Status</a> 
                    </li> --}}
                    <li> 
                        <a href="{{ route('mis_course_request') }}">College New/Old Course request</a> 
                    </li>
                    <li> 
                        <a href="{{ route('companyCouponCreate') }}">@lang('admin.create') @lang('College') @lang('Coupon')</a> 
                    </li>
                    {{-- <li> 
                        <a href="{{ route('company') }}">@lang('admin.list') @lang('admin.create') @lang('College') @lang('Coupon')</a> 
                    </li> --}}
                </ul>
            </li>


            <li>
                <a href="{{route('category_index')}}"><i class="la la-folder"></i> @lang('admin.categories')</a>
            </li>

            <li> <a href="{{route('admin_courses')}}"><i class="la la-chalkboard"></i> {{__a('courses')}}</a> </li>

            <li>
                <a href="{{route('plugins')}}" class="{{request()->is('admin/plugins*') ? 'active' : ''}}">
                    <i class="la la-plug"></i> {{__a('plugins')}}
                </a>
            </li>

            <li>
                <a href="{{route('themes')}}" class="{{request()->is('admin/themes*') ? 'active' : ''}}">
                    <i class="la la-brush"></i> {{__a('themes')}}
                </a>
            </li>

            <li>
                <a href="#"><i class="la la-tools fa-fw"></i> @lang('admin.settings')<span class="la arrow"></span></a>
                <ul class="nav nav-second-level" style="display: none;">
                    @php
                    do_action('admin_menu_settings_item_before');
                    @endphp
                    <li> <a href="{{ route('general_settings') }}">@lang('admin.general_settings')</a> </li>
                    <li> <a href="{{ route('lms_settings') }}">@lang('admin.lms_settings')</a> </li>
                    <li> <a href="{{ route('payment_settings') }}">@lang('admin.payment_settings')</a> </li>
                    <li> <a href="{{ route('payment_gateways') }}">@lang('admin.payment_gateways')</a> </li>
                    <li> <a href="{{ route('withdraw_settings') }}">@lang('admin.withdraw')</a> </li>
                    <li> <a href="{{ route('theme_settings') }}">@lang('admin.theme_settings')</a> </li>
                    <li> <a href="{{ route('invoice_settings') }}">@lang('admin.invoice_settings')</a>
            </li>
            <li> <a href="{{ route('social_settings') }}"> {{__a('social_login_settings')}} </a> </li>
            <li> <a href="{{ route('storage_settings') }}"> {{__a('storage')}} </a> </li>
            @php
            do_action('admin_menu_settings_item_after');
            @endphp
        </ul>
        <!-- /.nav-second-level -->
        </li>

        <li> <a href="{{route('payments')}}"><i class="la la-file-invoice-dollar"></i> {{__a('payments')}}</a> </li>
        <li> <a href="{{route('earnings')}}"><i class="la la-file-invoice-dollar"></i> {{__a('earning')}}</a> </li>
        <li> <a href="{{route('withdraws')}}"><i class="la la-wallet"></i> {{__a('withdraws')}}</a> </li>

        <li> <a href="{{ route('users') }}"><i class="la la-users"></i> {{__a('users')}}</a> </li>

        <li> <a href="{{route('change_password')}}"><i class="la la-lock"></i> @lang('admin.change_password')</a> </li>

        <li> <a href="{{route('createcollage')}}"><i class="la la-building"></i> @lang('college')</a> </li>

        <li> <a href="{{route('create_collage_admin')}}"><i class="la la-user"></i> @lang('college_admin')</a> </li>

        <li> <a href="{{route('channel_partner')}}"><i class="la la-user"></i> @lang('channel partner')</a> </li>


        <li> <a href="{{route('createCoupon')}}"><i class="la la-lock"></i> @lang('coupon')</a> </li>

        <li> <a href="{{route('invoice_list')}}"><i class="la la-lock"></i> @lang('Invoices')</a> </li>

        @php
        do_action('admin_menu_item_after');
        @endphp

        <li>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="la la-sign-out"></i> {{__a('logout')}}
            </a>
        </li>

        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>