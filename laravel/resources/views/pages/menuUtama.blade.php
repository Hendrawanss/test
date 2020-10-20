<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/index.ico') }}">
    <title>Dacita Login</title>
	<link rel="canonical" href="https://www.wrappixel.com/templates/elegant-admin/" />
    <!-- page css -->
    <link href="{{ asset('dist/css/pages/login-register-lock.css') }}" rel="stylesheet">
    <!--alerts CSS -->
    <link href="{{ asset('assets/node_modules/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/dacita.css') }}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="horizontal-nav ">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Dacita Dashboard</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <section id="wrapper" class="login-register menu-utama" style="background-image:url(images/datacenter/ttc_tbs.png); height: 100%; width: 100%;">
    <h1 class="text-center title-menu">MENU UTAMA {{strtoupper(session("auth.level"))}}</h1>
    @if(session('auth.level') == "Admin" || session('auth.level') == "User")
    <div class="row">
        <div class="col-6-menu-left">
            <div class="login-box card card-menu">
                <a href="{{ route('home') }}">
                    <div class="card-body text-center white">
                        <b><h2 class=""><b>Dacita</b></h2></b>
                        <img src="{{ asset('images/icon/dashboard-256.png') }}" width="30%" height="30%" alt="Icon for Gita">
                        <h5>Data Center -</h5>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-6-menu-right">
            <div class="login-box card card-menu">
                <a href="{{route('user.home')}}" >
                    <div class="card-body text-center white">
                        <b><h2 class=""><b>Sibaku</b></h2></b>
                        <img src="{{ asset('images/icon/literature-256.png') }}" width="30%" height="30%" alt="Icon for Sibaku">
                        <h5>Dashboard </h5>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-6-menu-left">
            <div class="login-box card card-menu">
                <a href="../gita" >
                    <div class="card-body text-center white">
                        <b><h2 class=""><b>Gita</b></h2></b>
                        <img src="{{ asset('images/icon/guestbook.png') }}" width="30%" height="30%" alt="Icon for Gita">
                        <h5>Guestbook IT Area</h5>
                    </div>
                </a>
            </div>
        </div>
    </div>
    @elseif(session('auth.level') == "Super Admin")
    <div class="row">
        <div class="col-6-menu-left">
            <div class="login-box card card-menu">
                <a href="{{ route('home') }}">
                    <div class="card-body text-center white">
                        <b><h2 class=""><b>Dacita</b></h2></b>
                        <img src="{{ asset('images/icon/dashboard-256.png') }}" width="30%" height="30%" alt="Icon for Gita">
                        <h5>Data Center -</h5>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-6-menu-right">
            <div class="login-box card card-menu">
                <a href="{{route('user.home')}}" >
                    <div class="card-body text-center white">
                        <b><h2 class=""><b>Sibaku</b></h2></b>
                        <img src="{{ asset('images/icon/literature-256.png') }}" width="30%" height="30%" alt="Icon for Sibaku">
                        <h5>Dashboard </h5>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-6-menu-left">
            <div class="login-box card card-menu">
                <a href="../gita" >
                    <div class="card-body text-center white">
                        <b><h2 class=""><b>Gita</b></h2></b>
                        <img src="{{ asset('images/icon/guestbook.png') }}" width="30%" height="30%" alt="Icon for Gita">
                        <h5>Guestbook IT Area</h5>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-6-menu-right">
            <div class="login-box card card-menu">
                <a href="{{route('user.home')}}" >
                    <div class="card-body text-center white">
                        <b><h2 class=""><b>User Management</b></h2></b>
                        <img src="{{ asset('images/icon/add-user-256.png') }}" width="30%" height="30%" alt="Icon for Gita">
                        <h5>User Management</h5>
                    </div>
                </a>
            </div>
        </div>
    </div>
    @endif
    </section>
    <div class="container-fluid">
        
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{ asset('assets/node_modules/jquery/jquery-3.2.1.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('assets/node_modules/popper/popper.min.js') }}"></script>
    <script src="{{ asset('assets/node_modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- Sweet-Alert  -->
    <script src="{{ asset('assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('dist/js/login.js') }}"></script>
    <script type="text/javascript">
    $(function() {
        $(".preloader").fadeOut();
        loginEvent.init();
    });
    </script>
</body>

</html>