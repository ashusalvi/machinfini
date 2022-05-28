<div class="navbar-default sidebar" role="navigation">
    <div id="adminmenuback"></div>
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li>
                <a href="/dashboard"><i class="la la-dashboard fa-fw"></i> Dashboard</a>
            </li>
            <li>
                <a href="{{ route('companyAdminDashboard') }}"><i class="la la-dashboard fa-fw"></i> @lang('admin.home')</a>
            </li>

            <li>
                <a href="#">
                    <i class="la la-user fa-fw"></i> 
                    Job
                    <span class="la arrow"></span>
                </a>
                <ul class="nav nav-second-level" style="display: none;">
                    <li> 
                        <a href="{{ route('company_job_create') }}">@lang('admin.create') </a> 
                    </li>
                    <li> 
                        <a href="{{ route('company_job_list') }}">@lang('admin.list') </a> 
                    </li>
                </ul>
            </li>

           

        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>