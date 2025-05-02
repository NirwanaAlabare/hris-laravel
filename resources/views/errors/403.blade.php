
		<!-- Favicon -->
		<link rel="icon" href="{{URL::asset('assets/images/brand/favicon.ico')}}" type="image/x-icon"/>
		<link rel="shortcut icon" type="image/x-icon" href="{{URL::asset('assets/images/brand/favicon.ico')}}" />

		<!--Bootstrap.min css-->
		<link rel="stylesheet" href="{{URL::asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}">

		<!-- Dashboard css -->
		<link href="{{URL::asset('assets/css/style.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('assets/css/dark-style.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('assets/css/skin-mode.css')}}" rel="stylesheet" />

		<!-- Perfect scroll bar css-->
		<link href="{{URL::asset('assets/plugins/pscrollbar/perfect-scrollbar.css')}}" rel="stylesheet" />

		<!-- Sidemenu css -->
		<link rel="stylesheet" href="{{URL::asset('assets/css/sidemenu-icon.css')}}">

		<!--Daterangepicker css-->
		<link href="{{URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" />

		<!-- Sidebar Accordions css -->
		<link href="{{URL::asset('assets/css/easy-responsive-tabs.css')}}" rel="stylesheet">

		<!-- Rightsidebar css -->
		<link href="{{URL::asset('assets/plugins/sidebar/sidebar.css')}}" rel="stylesheet">

		<!--News ticker css -->
		<link href="{{URL::asset('assets/plugins/newsticker/breaking-news-ticker.css')}}" rel="stylesheet" />

		<!---Icons css-->
		<link href="{{URL::asset('assets/plugins/icons/icons.css')}}" rel="stylesheet" />

		@yield('styles')

		<!--Fonts-->
		<link id="font" rel="stylesheet" type="text/css" media="all" href="{{URL::asset('assets/css/fonts/font1.css')}}"/>

		<!-- Color-skins css -->
		<link id="theme" rel="stylesheet" type="text/css" media="all" href="{{URL::asset('assets/css/colors/color.css')}}" />


        <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="text-center py-5">
                <h1 style="font-size: 100px; line-height: 10px">404</h1>
                <p class="text-xl mt-4">Anda tidak memiliki akses ke halaman ini.</p>
                <img src="{{ URL::asset('assets/image/not-found-img.png') }}" alt="loader" class="mt-4" style="width: 20%;">
                <a href="{{ url()->previous() }}" class="mt-4 w-25 btn btn-danger d-block mx-auto">
                    Kembali ke sebelumnya
                </a>

            </div>
        </div>

