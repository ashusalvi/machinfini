<div class="navbar-default sidebar" role="navigation">
    <div id="adminmenuback"></div>
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li>
                <a href="/dashboard"><i class="la la-dashboard fa-fw"></i> Dashboard</a>
            </li>
            <li>
                <a href="{{ route('companyInstructorDashboard') }}"><i class="la la-dashboard fa-fw"></i> @lang('admin.home')</a>
            </li>
            
            <li>
                <a href="#">
                    <i class="la la-clipboard-list fa-fw"></i> 
                    @lang('admin.company') @lang('admin.seminar')
                    <span class="la arrow"></span>
                </a>
                <ul class="nav nav-second-level" style="display: none;">
                    <li> 
                        <a href="{{ route('companySeminarNew') }}">@lang('admin.create') </a> 
                    </li>
                    <li> 
                        <a href="{{ route('my_seminars') }}">@lang('admin.list') </a> 
                    </li>
                </ul>
            </li>
            
            <li>
                <a href="{{ route('dh_employee_score') }}"><i class="la la-clipboard-list"></i>
                    @lang('admin.employee') @lang('admin.score')</a>
            </li>

            <li>
                <a href="{{ route('all_instructor_completed_seminar') }}"><i class="la la-chalkboard-teacher"></i> Completed Seminar</a>
            </li>

            <li>
                <a href="{{ route('all_instructor_pending_seminar') }}"><i class="la la-chalkboard-teacher"></i> Pending Seminar</a>
            </li>
            
            <li>
                <a href="{{ route('instructor_all_department_course') }}"><i class="la la-chalkboard-teacher"></i>
                    Course List</a>
            </li>
            
            <li>
                <a href="{{ route('instructor_all_completed_course') }}"><i class="la la-chalkboard-teacher"></i> Completed Course</a>
            </li>

            <li>
                <a href="{{ route('instructor_all_pending_course') }}"><i class="la la-chalkboard-teacher"></i> Pending Course</a>
            </li>

            <li class="active">
                <a href="/dashboard/settings"> <i class="la la-tools"></i> settings </a>
            </li>
            

        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>