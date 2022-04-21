<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{asset('css/styles.css')}}" rel="stylesheet">
     <link href="{{ asset('css/sb-admin.min.css') }}" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="https://cdn01.jotfor.ms/themes/CSS/5e6b428acc8c4e222d1beb91.css?themeRevisionID=5f7ed99c2c2c7240ba580251" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
</head>
<body>
        <nav class="sb-topnav navbar navbar-expand navbar" style="background-color:#f4f5f7;">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="{{ url('/home') }}" style="color:#999da1; padding:6px 0px 0px 0px;"><h4>HORUS WORK</h4></a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!" style=" margin-left:-48px;">
                <!--<img style="width:35px; height:35px;" src="" />-->
            </button> &ensp;&ensp;&ensp;
                        <div class="nav" style=" color: #5e6e82;">
                            <!--<div class="sb-sidenav-menu-heading">Core</div>-->
                            <a class="nav-link" href="{{url('/home') }}" style="color: #5e6e82;"> Trang chủ </a>
                            <a class="nav-link" href="{{ url('/users') }}" style="color: #5e6e82;"> Nhân sự </a>
                            <a class="nav-link" href="{{ url('/') }}" style="color: #5e6e82;"> Chấm công  </a>
                            <a class="nav-link" href="{{ url('/') }}" style="color: #5e6e82;">Việc của tôi</a>
                            <a class="nav-link" href="{{ url('/') }}" style="color: #5e6e82;"> Dự án</a>    
                            <a class="nav-link" href="{{ url('/') }}" style="color: #5e6e82;"> Yêu cầu</a>
                            <a class="nav-link" href="{{ url('/') }}" style="color: #5e6e82;">Báo cáo</a>
                            <a class="nav-link" href="{{ url('/') }}" style="color: #5e6e82;">Lương công việc</a>
                            <a class="nav-link" href="{{ url('/') }}" style="color: #5e6e82;">Thưởng dự án</a>
                        </div>  
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false" title="Thông báo" style="color:#999da1;">
                    <center>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-bell"
                        viewBox="0 0 16 16">
                        <path
                            d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z" />
                    </svg>
                    </center></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="width: 400px; height:900px; border-radius: 5px; border-width: 2px;">      
                        <center>
                            <h4>Thông báo</h4>
                        </center>
                        <hr style="height: 1px; ">
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <form action="" method="post" enctype="multipart/form-data">
                        <center>
                        
                    </form>
                    </ul>
                </li>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @guest
                        @else
                        <center>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" 
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>{{ Auth::user()->fullname }}</a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <div class="avatar-wrapper">
                                    <img class="profile-pic"src="{{URL::asset('/image/avt.png')}}" />
                                </div>
                                <hr class="dropdown-divider" />
                                <button class="dropdown-item" href="{{ route('logout') }}"onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();" style="text-align:center;">{{ __('Đăng xuất') }}</button>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        </center>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
        <script src="style/js/datatables-simple-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="{{asset('js/datatables-simple-demo.js')}}"></script>
        <script src="{{asset('js/app.js')}}" defer></script>
        <script src="{{asset('js/scripts.js')}}" defer></script>
</body>
</html>
