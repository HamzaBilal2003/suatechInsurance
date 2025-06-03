<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Sautech Insurance</title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="Mannatthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (Auth::user()->role == 'admin')
        <link rel="shortcut icon" href="{{asset('assets/SauTech Logo.png')}}" type="image/x-icon">
    @elseif (Auth::user()->role == 'broker')
        <link rel="shortcut icon" href="{{asset(Auth::user()->Broker->logo_path)}}" type="image/x-icon">
    @elseif (Auth::user()->role == 'client')
        <link rel="shortcut icon" href="{{asset(Auth::user()->client->broker->logo_path)}}" type="image/x-icon">
    @endif

    <!-- jvectormap -->
    <link href="{{asset("assets/plugins/jvectormap/jquery-jvectormap-2.0.2.css")}}" rel="stylesheet">
    <link href="{{asset("assets/plugins/fullcalendar/vanillaCalendar.css")}}" rel="stylesheet" type="text/css" />

    <link href="{{asset("assets/plugins/morris/morris.css")}}" rel="stylesheet">

    <link href="{{asset("assets/css/bootstrap.min.css")}}" rel="stylesheet" type="text/css">
    <link href="{{asset("assets/css/icons.css")}}" rel="stylesheet" type="text/css">
    <link href="{{asset("assets/css/style.css")}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    @yield('css')
</head>


<body class="fixed-left">

    <!-- Loader -->
    {{-- <div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div> --}}

    <!-- Begin page -->
    <div id="wrapper">

        <!-- ========== Left Sidebar Start ========== -->
        @include('layout.sidebar')
        <!-- Left Sidebar End -->

        <!-- Start right Content here -->

        <div class="content-page">
            <!-- Start content -->
            <div class="content">

                <!-- Top Bar Start -->
                @include('layout.topbar')
                <!-- Top Bar End -->

                <div class="page-content-wrapper p-3">
                    @yield('content')
                </div>
            </div> <!-- content -->

            <footer class="footer">
                ©
                <script>document.write(new Date().getFullYear())</script> Sautech Insurance. All rights reserved.
            </footer>

        </div>
        <!-- End Right content here -->

    </div>
    <!-- END wrapper -->


    <!-- jQuery  -->
    <script src="{{asset("assets/js/jquery.min.js")}}"></script>
    <script src="{{asset("assets/js/popper.min.js")}}"></script>
    <script src="{{asset("assets/js/bootstrap.min.js")}}"></script>
    <script src="{{asset("assets/js/modernizr.min.js")}}"></script>
    <script src="{{asset("assets/js/detect.js")}}"></script>
    <script src="{{asset("assets/js/fastclick.js")}}"></script>
    <script src="{{asset("assets/js/jquery.blockUI.js")}}"></script>
    <script src="{{asset("assets/js/waves.js")}}"></script>
    <script src="{{asset("assets/js/jquery.nicescroll.js")}}"></script>

    <script src="{{asset("assets/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js")}}"></script>
    <script src="{{asset("assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js")}}"></script>

    <script src="{{asset("assets/plugins/skycons/skycons.min.js")}}"></script>
    <script src="{{asset("assets/plugins/fullcalendar/vanillaCalendar.js")}}"></script>

    <script src="{{asset("assets/plugins/raphael/raphael-min.js")}}"></script>
    <script src="{{asset("assets/plugins/morris/morris.min.js")}}"></script>

    <script src="{{asset("assets/pages/dashborad.js")}}"></script>

    <!-- App js -->
    <script src="{{asset("assets/js/app.js")}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @yield('js')
</body>

</html>