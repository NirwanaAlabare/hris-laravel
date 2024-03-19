<!DOCTYPE html>

<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
@include('admin.include.head')

<!-- Notifications  css -->
<link href="{{URL::asset('assets/plugins/notify-growl/css/jquery.growl.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/notify-growl/css/notifIt.css')}}" rel="stylesheet" />

<body class="app sidebar-mini">

    <!--Global-Loader-->
    <div id="global-loader">
        <img src="{{URL::asset('assets/images/brand/icon.png')}}" alt="HRIS">
    </div>

    <div class="page">
        <div class="page-main">
            <!--app-header-->
            <div class="app-header header d-flex">
                <div class="container-fluid">

                    @include('layouts.components.app-header')

                </div>
            </div>
            <!--/app-header-->

{{--              <!--News Ticker-->
            <div class="container-fluid bg-white news-ticker">

                @include('layouts.components.news-ticket')

            </div>
            <!--/News Ticker-->  --}}

            @include('layouts.components.sidebar-menu')

            <!-- app-content-->
            <div class="app-content my-3 my-md-5">
                <div class="side-app">

                    @yield('mainarea')

                </div>

            </div>
            <!-- End app-content-->
        </div>

        @include('layouts.components.footer')

    </div>
    <!-- End Page -->

    @include('layouts.verticalmenu.closed-sidebar.scripts')

    <!-- Notifications js -->
    <script src="{{URL::asset('assets/plugins/notify-growl/js/rainbow.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/notify-growl/js/sample.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/notify-growl/js/jquery.growl.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/notify-growl/js/notifIt.js')}}"></script>

    <script>
        var keepAliveTimeout = 60000 * 10;

        function keepSessionAlive()
        {
            var waktu = 0;
            $.ajax(
            {
                type: 'POST',
                url: '{{route('admin.admin.keepalive')}}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data)
                {
                    setTimeout(function()
                    {
                        keepSessionAlive();
                    }, keepAliveTimeout);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown)
                {
                    $.growl.error({
                        message: "Koneksi jaringan Anda terputus."
                    });

                    setTimeout(function()
                    {
                        keepSessionAlive();
                    }, keepAliveTimeout);
                }
            });
        }

        keepSessionAlive();

    </script>

</body>

</html>
