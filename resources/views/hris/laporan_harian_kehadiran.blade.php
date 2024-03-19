<!DOCTYPE html>

<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<meta charset="utf-8"/>
<title>{{$setting->website}} - {{ $pageTitle }}</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="HRIS - PT NAG" name="description">
<meta content="biriodede@gmail.com" name="author">
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<meta name="csrf-token" content="{{ csrf_token() }}"/>

<!-- Favicon -->
    <link rel="icon" href="{{URL::asset('assets/images/brand/favicon.ico')}}" type="image/x-icon"/>
    <link rel="shortcut icon" type="image/x-icon" href="{{URL::asset('assets/images/brand/favicon.ico')}}" />

    <!--Bootstrap.min css-->
    <link rel="stylesheet" href="{{URL::asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}">

    <!-- Dashboard css -->
    <link href="{{URL::asset('assets/css/style.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('assets/css/dark-style.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('assets/css/skin-mode.css')}}" rel="stylesheet" />

    <!--Daterangepicker css-->
    <link href="{{URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" />

    <!-- Sidebar Accordions css -->
    <link href="{{URL::asset('assets/css/easy-responsive-tabs.css')}}" rel="stylesheet">

    <!-- Rightsidebar css -->
    <link href="{{URL::asset('assets/plugins/sidebar/sidebar.css')}}" rel="stylesheet">

    <!---Icons css-->
    <link href="{{URL::asset('assets/plugins/icons/icons.css')}}" rel="stylesheet" />

<!--Fonts-->
<link id="font" rel="stylesheet" type="text/css" media="all" href="{{URL::asset('assets/css/fonts/font1.css')}}"/>

<!-- Color-skins css -->
<link id="theme" rel="stylesheet" type="text/css" media="all" href="{{URL::asset('assets/css/colors/color.css')}}" />
<link rel="stylesheet" href="{{URL::asset('assets/css/demo-styles.css')}}"/>

<!-- Data table css -->
<link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet" />

<!--Select2 css -->
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />

<!-- Date Picker css-->
<link href="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.css')}}" rel="stylesheet" />

<!--Mutipleselect css-->
<link rel="stylesheet" href="{{URL::asset('assets/plugins/multipleselect/multiple-select.css')}}">

<!-- Notifications  css -->
<link href="{{URL::asset('assets/plugins/notify-growl/css/jquery.growl.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/notify-growl/css/notifIt.css')}}" rel="stylesheet" />

<!---Sweetalert Css-->
<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

<body>

    <!--Global-Loader-->
    <div id="global-loader">
        <img src="{{URL::asset('assets/images/brand/icon.png')}}" alt="loader">
    </div>
    <!-- app-content-->
    <div class="app-content my-3 my-md-5">
        <div class="side-app">

        <div class="mr-0 mt-0 mb-0">
            <button type="submit" id="btn-exportexcel" class="btn btn-app btn-primary mr-2 mt-0 mb-2" data-toggle="tooltip" title="Export Data ke File"><i class="ion-ios7-download"></i> Excel</button>
        </div>

    <table class="table table-sm table-striped border">
        <thead>
            <tr>
                <th>
                    Department ID
                </th>
                <th>
                    Department Name
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($department as $r_dept)
            <tr>
                <td>
                    {{$r_dept->department_id}}
                </td>
                <td>
                    {{$r_dept->department_name}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    Copyright © 2022 PT Nirwana Alabare Garment. Designed by IT Division All rights reserved.
</div>

</div>
<!-- End app-content-->

    <!-- Back to top -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- Jquery js-->
    <script src="{{URL::asset('assets/plugins/vendors/jquery.min.js')}}"></script>

    <!--Bootstrap.min js-->
    <script src="{{URL::asset('assets/plugins/bootstrap/popper.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

    <!--Moment js-->
    <script src="{{URL::asset('assets/plugins/moment/moment.min.js')}}"></script>

    <!-- Daterangepicker js-->
    <script src="{{URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>

    <!--Side-menu js-->
    <script src="{{URL::asset('assets/plugins/side-menu/sidemenu.js')}}"></script>

    <!--News Ticker js-->
    <script src="{{URL::asset('assets/plugins/newsticker/breaking-news-ticker.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/newsticker/newsticker.js')}}"></script>

    <!-- Sidebar Accordions js -->
    <script src="{{URL::asset('assets/plugins/sidemenu-responsive-tabs/js/easyResponsiveTabs.js')}}"></script>

    <!-- Rightsidebar js -->
    <script src="{{URL::asset('assets/plugins/sidebar/sidebar.js')}}"></script>

    <!--Jquery Sparkline js-->
    <script src="{{ URL::asset('assets/plugins/vendors/jquery.sparkline.min.js') }}"></script>

    <!-- Chart Circle js-->
    <script src="{{ URL::asset('assets/plugins/vendors/circle-progress.min.js') }}"></script>

    <!--Time Counter js-->
    <script src="{{ URL::asset('assets/plugins/counters/jquery.missofis-countdown.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/counters/counter.js') }}"></script>

    <!-- INTERNAL Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.js') }}"></script>

    <!--Select2 js -->
    <script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>

    <!--MutipleSelect js-->
    <script src="{{URL::asset('assets/plugins/multipleselect/multiple-select.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/multipleselect/multi-select.js')}}"></script>

    <!-- Datepicker js -->
    <script src="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/spectrum-date-picker/jquery-ui.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/input-mask/jquery.maskedinput.js')}}"></script>

    <!-- Notifications js -->
    <script src="{{URL::asset('assets/plugins/notify-growl/js/rainbow.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/notify-growl/js/sample.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/notify-growl/js/jquery.growl.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/notify-growl/js/notifIt.js')}}"></script>

    <!-- Sweet alert js-->
    <script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#btn-exportexcel', function (event) {
            $.ajax({
                type:"POST",
                url: "{{route('hris.mdabsenhadir.ajax_exportexceldeptall')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    enroll_id:"4411",
                },
                dataType: 'json',
                success: function(resA){

                }
            });
        });
    </script>

    <!-- Custom js-->
    <script src="{{URL::asset('assets/js/custom.js')}}"></script>

</body>

</html>
