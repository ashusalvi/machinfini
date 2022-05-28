<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{get_option('enable_rtl')? 'rtl' : 'auto'}}">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="{{theme_url('favicon.png')}}" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title> @if(! empty($title)) {{$title}} @else {{ get_option('site_title') }} @endif</title>

        <!-- bootstrap v4.3.1 css -->
        <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/line-awesome.min.css')}}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/select2-4.0.3/css/select2.css') }}"/>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        @yield('page-css')

        <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
        <script type="text/javascript">
            var pageData = @json(pageJsonData())
        </script>

    </head>

    <body class="{{get_option('enable_rtl')? 'rtl' : ''}}">

        <nav class="navbar navbar-expand-lg navbar-light dashboard-top-nav">
            <div class="dashboard-top-navbar-brand">
                <a class="navbar-brand" href="{{route('home')}}">
                    @php
                    $logoUrl = media_file_uri(get_option('site_logo'));
                    @endphp

                    @if($logoUrl)
                    <img src="{{media_file_uri(get_option('site_logo'))}}" alt="{{get_option('site_title')}}" />
                    @else
                    <img src="{{asset('assets/images/teachify-lms-logo.svg')}}" alt="{{get_option('site_title')}}" />
                    @endif
                </a>
            </div>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                
                <ul class="navbar-nav dashboard-user-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                            class="nav-link dropdown-toggle nav-user-profile">
                            {!! $auth_user->get_photo !!}
                            <span class="user-name">{{$auth_user->name}}</span>
                        </a>

                        <div role="menu" class="dropdown-menu">
                            <a href="{{route('profile_settings')}}" class="dropdown-item" target="_blank"><i
                                    class="la la-user"></i> {{__('admin.profile')}}</a>
                            <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">

                                <i class="la la-sign-out"></i> {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>

            </div>
        </nav>

        <div class="dashboard-wrap">
            <div class="container-fluid">
                <div id="wrapper" >
                    @if (Auth::user()->user_type == 'company-instructor')
                        @include('company.instructor.menu')
                    @elseif (Auth::user()->user_type == 'company-employee')
                        @include('company.employee.menu')
                    @elseif (Auth::user()->user_type == 'company-admin-user')
                        @include('company.company.menu')
                    @else
                        @include('company.admin.menu')
                    @endif
                    

                    <div id="page-wrapper" style="width:80%;">
                        @if( ! empty($title))
                        <div class="page-header px-4">
                            <h4 class="page-header-left"> @yield('title-before') {{$title}} @yield('title-after') </h4>
                            @yield('page-header-right')
                        </div>
                        @endif

                        <div id="admin-page-body">
                            @php
                            do_action( 'admin_notices' );
                            @endphp

                            @include('inc.flash_msg')
                            @yield('content')
                        </div>
                        <div class="admin-footer">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="m-0"><a href="https://www.machinfini.com/"
                                                target="_blank">Machinfini</a> Version {{config('app.version')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- all js here -->
        <!-- jquery latest version -->
        <script src="{{asset('assets/js/vendor/jquery-1.12.0.min.js')}}"></script>
        <script src="{{asset('assets/js/jquery-ui.min.js')}}"></script>
        <!-- bootstrap js -->
        <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
        <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/select2-4.0.3/js/select2.min.js') }}"></script>
        <script src="{{ asset('assets/js/admin.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" integrity="sha512-Tn2m0TIpgVyTzzvmxLNuqbSJH3JP8jm+Cy3hvHrW7ndTDcJ1w5mBiksqDBb8GpE2ksktFvDB/ykZ0mDpsZj20w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            var toastr_options = {closeButton : true};
        </script>
        @yield('page-js')

        

    </body>

</html>