<!doctype html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="HRIS - PT NAG" name="description">
		<meta content="biriodede@gmail.com" name="author">
		<meta name="keywords" content="pt nirwana alabare garment"/>

		<!-- Title -->
		<title>HRISsss - PT NAG</title>

        @include('layouts.verticalmenu.closed-sidebar.styles')

	</head>

	<body class="app sidebar-mini">

		<!--Global-Loader-->
		<div id="global-loader">
			<img src="{{URL::asset('assets/images/brand/icon.png')}}" alt="loader">
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

{{--  				<!--News Ticker-->
				<div class="container-fluid bg-white news-ticker">

					@include('layouts.components.news-ticket')

				</div>
				<!--/News Ticker-->
  --}}
                @include('layouts.components.sidebar-menu')

                <!-- app-content-->
				<div class="app-content  my-3 my-md-5">
					<div class="side-app">

                        @yield('content')

					</div>

                    @include('layouts.components.right-sidebar')

					@yield('modals')

				</div>
				<!-- End app-content-->
			</div>

            @include('layouts.components.footer')

		</div>
		<!-- End Page -->

        @include('layouts.verticalmenu.closed-sidebar.scripts')

	</body>
</html>
