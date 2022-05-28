@php
    use App\Category;
    $categories = Category::whereStep(0)->with('sub_categories')->orderBy('category_name', 'asc')->get();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Machinfini | Home</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('new_design_v1/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('new_design_v1/assets/vendor/icofont/icofont.min.css')}}" rel="stylesheet">
  <link href="{{asset('new_design_v1/assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
  <link href="{{asset('new_design_v1/assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{asset('new_design_v1/assets/vendor/owl.carousel/assets/owl.carousel.min.css')}}" rel="stylesheet">
  <link href="{{asset('new_design_v1/assets/vendor/venobox/venobox.css')}}" rel="stylesheet">
  <link href="{{asset('new_design_v1/assets/vendor/aos/aos.css')}}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{asset('new_design_v1/assets/css/style.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('assets/css/line-awesome.min.css')}}">

  <!-- =======================================================
  * Template Name: Vesperr - v2.3.1
  * Template URL: https://bootstrapmade.com/vesperr-free-bootstrap-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top d-flex align-items-center">
    <div class="container d-flex align-items-center">

      <div class="logo mr-auto">
        <h1 class="text-light"><a href="#"><span>Machinfini</span></a></h1>
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <a href="index.html"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
      </div>

      <nav class="nav-menu d-none d-lg-block">
        <ul>
            <li class="drop-down"><a href="#">Category</a>
                <ul>
                    @foreach($categories as $category)
                        <li class="drop-down">
                            <a href="#"> 
                                <i class="la {{$category->icon_class}}"></i> 
                                {{$category->category_name}}
                            </a>
                            @if($category->sub_categories->count())
                                @foreach($category->sub_categories as $subCategory)
                                <ul>
                                    <li>
                                        <a
                                        href="{{route('category_view', $subCategory->slug)}}">
                                        {{$subCategory->category_name}}
                                        </a>
                                    </li>
                                </ul>
                                @endforeach
                            @endif
                            
                        </li>
                    @endforeach
                </ul>
            </li>
            <li class="active"><a href="#index.html">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#services">Popular Courses</a></li>
            <li><a href="#team">Testimonial</a></li>
            <li><a href="#contact">Contact</a></li>

            <li class="get-started"><a href="#about">Login</a></li>
        </ul>
      </nav><!-- .nav-menu -->

    </div>
  </header><!-- End Header -->