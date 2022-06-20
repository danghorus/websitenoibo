<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <style>
        #loading {
            position: fixed;
            display: block;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            text-align: center;
            opacity: 1;
            background-color: #ffffff;
            z-index: 100;
        }

        #loading-image {
            position: absolute;
            width: 300px;
            height: 300px;
            top: 40%;
            left: 45%;
            z-index: 99;
        }
    </style>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="<?php echo asset('img/logo.png'); ?>">


    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{asset('css/styles.css')}}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/datepicker.css') }}" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="https://cdn01.jotfor.ms/themes/CSS/5e6b428acc8c4e222d1beb91.css?themeRevisionID=5f7ed99c2c2c7240ba580251" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" rel="stylesheet"/>-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>

    {{--    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>--}}

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
    

<style>

.nav-item{
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 90px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}
.dropdown-content a {
  color: rgb(90, 90, 90);
  padding: 12px 10px;
  text-decoration: none;
  display: block;
  font-size:14px;
}

.dropdown-content a:hover {background-color: #e4e4e4;}

.dropdown:hover .dropdown-content {display: block;}

.dropdown:hover .nav-link{background-color: #eeeded;}
.nav-item:hover {background-color: #eeeded;}

</style>

</head>
<body>
        <div id="loading">
            <img id="loading-image" src="{{ asset('img/loading_4.gif') }}" alt="" />
        </div>
        <nav class="sb-topnav navbar navbar-expand navbar" style="background-color:#F8F9FA;">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="{{ url('/home') }}" style="color:#999da1; padding:6px 0px 0px 0px;margin:0px -50px 0px -10px;"><h4>HORUS WORK</h4></a>
            <!-- Sidebar Toggle-->
            <nav class="navbar navbar-expand-lg navbar-light bg-light" style="font-size: 20px;">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto" style="font-size:16px;" id="">
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('/home') }}">Trang chủ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/petitions') }}">Yêu cầu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/users') }}">Nhân sự</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link">Chấm công</a>
                            <div class="dropdown-content">
                                <a class="dropdown-item" href="{{ url('/time-keeping') }}">Bảng chấm công</a>
                                <a class="dropdown-item" href="{{ url('/time-keeping-report') }}">Thống kê</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link">Công việc</a>
                            <div class="dropdown-content">
                                <a class="dropdown-item" href="{{ url('/projects') }}">Dự án</a>
                                <a class="dropdown-item" href="{{ url('/my_works') }}">Việc của tôi</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link">Lương thưởng</a>
                            <div class="dropdown-content">
                                <a class="dropdown-item" href="{{ url('/time-keeping-wage') }}">Lương công việc</a>
                                <a class="dropdown-item" href="{{ url('/time-keeping-bonus') }}">Thưởng dự án</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">Báo cáo</a>
                        </li>
                    </ul>
                    @extends('petitions.create')
                    @extends('petitions.go_late')
                    @extends('petitions.quit')
                    @extends('petitions.in_day_am')
                    @extends('petitions.in_day_pm')
                    @extends('petitions.in_day')
                    @extends('petitions.multi_day')
                    @extends('petitions.OT')
                    @extends('petitions.OTWar')
                    <script>
                    function changeColor() {
                        var result = document.getElementById("result");
                        result.style.color = "red";
                    }
                    </script>
                </div>
            </nav>
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <div class="collapse navbar-collapse" id="navbarSupportedContent" style="float:right;">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto" style="float:right;">
                        @guest
                        @else
                            <li class="dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>{{ Auth::user()->fullname }}</a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <center>
                                    <div class="avatar-wrapper">
                                        <img class="profile-pic" src="{{asset('image/'.Auth::user()->avatar)}}" />
                                    </div><br>
                                        <a href="{{ route('change.password') }}" type="button" class="btn btn-default" style="text-align: center;">Đổi mật khẩu</a>
                                    </center>
                                    <hr class="dropdown-divider" />
                                    <button class="dropdown-item" href="{{ route('logout') }}"onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();" style="text-align:center; color: red;font-size:20px;"><b>{{ __('Đăng xuất') }}</b></button>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </ul>
        </nav>
        <main class="py-4">
            @yield('content')
        </main>

        <script>
            $(window).load(function() {
                $('#loading').hide();
            });
        </script>

        <script src="{{asset('js/datatables-simple-demo.js')}}"></script>
        <script src="{{asset('js/app.js')}}" defer></script>
        <script src="{{asset('js/scripts.js')}}" defer></script>
        <script src="{{asset('js/bootstrap-datepicker.js')}}" defer></script>
</body>
</html>
