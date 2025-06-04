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
        <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M48.8798 17.5919L48.9298 17.5484L48.8233 17.4234C45.6072 7.33148 36.1443 0 25 0C11.2145 0 0 11.2153 0 25C0 38.7855 11.2153 50 25 50C38.7847 50 50 38.7855 50 25C50.0008 22.4218 49.608 19.9339 48.8798 17.5919ZM47.0024 17.0751L34.6613 27.6243L40.8145 7.78803C43.5653 10.3169 45.7088 13.4936 47.0024 17.0751ZM39.4774 6.65327L34.6588 22.1888L28.8073 1.92744C32.7919 2.58308 36.4379 4.24913 39.4774 6.65327ZM32.2146 19.8564L32.3323 19.9451L32.3662 20.062C32.3185 19.992 32.2646 19.9257 32.2146 19.8564ZM32.2589 25C32.2589 28.6226 29.588 31.625 26.1137 32.1637L24.9774 32.2565C24.0065 32.254 23.0799 32.0581 22.2339 31.7065L20.2815 30.5032C19.8597 30.1403 19.4823 29.7299 19.1524 29.2798L18.129 27.3186C17.8822 26.5896 17.7427 25.8113 17.7427 24.9992C17.7427 23.3734 18.2863 21.8758 19.1927 20.6653L20.8918 19.0225C22.0612 18.216 23.4765 17.7411 25.0007 17.7411C29.0025 17.7419 32.2589 20.9983 32.2589 25ZM25 1.61294C25.6959 1.61294 26.383 1.64929 27.0645 1.70971L31.5838 17.3565L14.2588 4.23629C17.4782 2.56375 21.129 1.61294 25 1.61294ZM12.7298 5.10155L27.9927 16.6596C27.0564 16.3225 26.0516 16.129 25 16.129H3.36536C5.2532 11.5428 8.55571 7.68463 12.7298 5.10155ZM1.61294 25C1.61294 22.4661 2.0234 20.0282 2.77176 17.7419H19.9161C19.1831 18.2573 18.5347 18.8806 17.9871 19.5887L3.32332 33.7637C2.22424 31.0548 1.61294 28.0984 1.61294 25ZM4.02424 35.3283L16.1001 23.6549L14.9234 46.0888C10.2 43.821 6.34511 40.0218 4.02424 35.3283ZM25 48.3871C22.0016 48.3871 19.1379 47.8105 16.5016 46.7782L17.4065 29.5274C17.5266 29.7283 17.6378 29.9354 17.7734 30.1257L27.2411 48.2773C26.5032 48.3476 25.7565 48.3871 25 48.3871ZM28.9419 48.0484L21.0589 32.9355C21.2 33.0056 21.3395 33.0782 21.4839 33.1404L38.8347 43.8419C35.9646 45.954 32.596 47.4258 28.9419 48.0484ZM40.1759 42.7734L25.6855 33.8363C25.8807 33.821 26.0734 33.7976 26.2645 33.7702L47.3024 32.042C45.967 36.2612 43.4636 39.962 40.1759 42.7734ZM47.7548 30.3863L32.4411 31.6443L47.5314 18.7452C48.0862 20.7379 48.3879 22.8339 48.3879 25.0008C48.3879 26.8541 48.1645 28.6557 47.7548 30.3863Z" fill="#182D49" class="loader-path"/>
        </svg>
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

            @include('layouts.components.sidebar-menu4')

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

    {{-- <script>
        // CSRF token for ajax
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var keepAliveTimeout =  60000 * 60 * 3

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

    </script> --}}

</body>

</html>
