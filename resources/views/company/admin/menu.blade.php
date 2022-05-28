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
                    @lang('admin.company') @lang('admin.department')
                    <span class="la arrow"></span>
                </a>
                <ul class="nav nav-second-level" style="display: none;">
                    <li> 
                        <a href="{{ route('CPA_department_create') }}">@lang('admin.create') </a> 
                    </li>
                    <li> 
                        <a href="{{ route('CPA_department_list') }}">@lang('admin.list') </a> 
                    </li>
                </ul>
            </li>

            <li>
                <a href="#">
                    <i class="la la-user fa-fw"></i> 
                    Department Heads
                    <span class="la arrow"></span>
                </a>
                <ul class="nav nav-second-level" style="display: none;">
                    <li> 
                        <a href="{{ route('CPA_instructor_create') }}">@lang('admin.create') </a> 
                    </li>
                    <li> 
                        <a href="{{ route('CPA_instructor_list') }}">@lang('admin.list') </a> 
                    </li>
                </ul>
            </li>

            <li>
                <a href="#"><i class="la la-user fa-fw"></i> Learner<span class="la arrow"></span></a>
                <ul class="nav nav-second-level" style="display: none;">
                    <li> 
                        <a href="{{ route('CPA_employee_create') }}">@lang('admin.create') </a> 
                    </li>
                    <li> 
                        <a href="{{ route('CPA_employee_list') }}">@lang('admin.list') </a> 
                    </li>
                </ul>
            </li>

            <li>
                <a href="{{ route('CPA_employee_score') }}"><i class="la la-clipboard-list"></i> Learner @lang('admin.score')</a>
            </li>

            <li>
                <a href="{{ route('CPA_seminar_list') }}"><i class="la la-chalkboard-teacher"></i> @lang('admin.seminar') @lang('admin.list')</a>
            </li>

            <li>
                <a href="{{ route('all_completed_seminar') }}"><i class="la la-chalkboard-teacher"></i> Completed Seminar</a>
            </li>

            <li>
                <a href="{{ route('all_pending_seminar') }}"><i class="la la-chalkboard-teacher"></i> Pending Seminar</a>
            </li>
            
            <li>
                <a href="{{ route('list_all_course') }}"><i class="la la-chalkboard-teacher"></i> Company Course
                    List</a>
            </li>

            <li>
                <a href="{{ route('all_completed_course') }}"><i class="la la-chalkboard-teacher"></i> Completed Course</a>
            </li>

            <li>
                <a href="{{ route('all_pending_course') }}"><i class="la la-chalkboard-teacher"></i> Pending Course</a>
            </li>

            <li>
                <a href="#">
                    <i class="la la-clipboard-list"></i> 
                    Course Request
                    <span class="la arrow"></span>
                </a>
                <ul class="nav nav-second-level" style="display: none;">
                    <li> 
                        <a href="{{ route('request_course') }}">Create Request</a> 
                    </li>
                    <li> 
                        <a href="{{ route('list_request_course') }}">List</a> 
                    </li>
                </ul>
            </li>

            <li class="active">
                <a href="/dashboard/settings"> <i class="la la-tools"></i> settings </a>
            </li>
            

        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>