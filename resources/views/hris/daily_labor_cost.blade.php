@extends('admin.adminlayouts.adminlayout')

@section('head')
    <!-- Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet" />

	<!-- Notifications  css -->
	<link href="{{URL::asset('assets/plugins/notify-growl/css/jquery.growl.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/notify-growl/css/notifIt.css')}}" rel="stylesheet" />

    <!-- Date Picker css-->
    <link href="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.css')}}" rel="stylesheet" />

	<!---Sweetalert Css-->
	<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

@stop
@section('mainarea')
<div class="page-header p-2 shadow">
    <ol class="breadcrumb breadcrumb-arrow mt-0">
        <li><a href="#">Perhitungan</a></li>
        <li class="active"><span>Payroll Karyawan Per Hari</span></li>
    </ol>
    <div class="ml-auto">
        <div class="input-group">
            <a href="#" id="btn-refresh-page" class="btn btn-secondary p-0 mr-0 text-white btn-icon" data-toggle="tooltip"
                title="" data-placement="bottom" data-original-title="Refresh Page">
                <span>
                    <i class="fa fa-refresh"></i>
                </span>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-primary py-3">
                <div class="card-title">DAILY LABOR COST RECAP PROCESS</div>
            </div>
            <div class="card-body px-6">
                <div class="row">
                    <div class="col-2 pt-3">
                        <div class="card-title">DATE RANGE : </div>
                    </div>
                    <div class="col-4">
                        <input type="hidden" id="daterange1" name="daterange1">
                        <a class="nav-link card-title col-8 pl-3 pr-6 border border-secondary" id="daterange-btn1" data-toggle="tooltip"
                        title="" data-placement="bottom" data-original-title="Klik di sini untuk pilih tanggal kehadiran">
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light py-3">
                <div class="row">
                    <div class="col-2 pt-3">
                    </div>
                    <div class="col-4">
                        <button type="button" id="btn-proses-report" class="btn btn-app btn-primary mr-0 mt-0 mb-0" data-toggle="tooltip" title="Export Data ke File Excel"><i class="ion-ios7-download"></i> PROSES</button>
                        <button type="button" id="btn-report-excel" class="btn btn-app btn-success mr-0 mt-0 mb-0" data-toggle="tooltip" title="Export Data ke File Excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> EXPORT EXCEL</button>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- col end -->
</div>

@endsection
@section('footerjs')

    <!--Jquery Sparkline js-->
    <script src="{{ URL::asset('assets/plugins/vendors/jquery.sparkline.min.js') }}"></script>

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

    <!-- Notifications js -->
    <script src="{{URL::asset('assets/plugins/notify-growl/js/rainbow.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/notify-growl/js/sample.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/notify-growl/js/jquery.growl.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/notify-growl/js/notifIt.js')}}"></script>

    <!-- Datepicker js -->
    <script src="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/spectrum-date-picker/jquery-ui.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/input-mask/jquery.maskedinput.js')}}"></script>

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
            notif({
                msg: "<b>Info:</b> Data sedang di proses, mohon menunggu",
                type: "info"
            });
        });

        $('body').on('click', '#btn-proses-report', function (event) {
            var periode_kehadiran=$('#daterange1').val();
            $('#btn-proses-report').addClass("btn-loading");
            $("#btn-proses-report").attr("disabled", true);
            $.ajax({
                type:"POST",
                url: "{{route('hris.daily_labor.proses')}}",
                data:{
                    periode_kehadiran:periode_kehadiran
                },
                success: function(res){
                    console.log(res);
                    $('#btn-proses-report').removeClass("btn-loading");
                    $("#btn-proses-report").attr("disabled", false);
                    swal("", "Report daily labor cost", "success");
                },
                error: function(res){
                    $('#btn-proses-report').removeClass("btn-loading");
                    $("#btn-proses-report").attr("disabled", false);
                    swal("", "Report daily labor cost", "error");
                }
            });
        });
        $('body').on('click', '#btn-report-excel', function (event) {
            var periode_kehadiran=$('#daterange1').val();
            $('#btn-report-excel').addClass("btn-loading");
            $.ajax({
                type: 'POST',
                url: '{{route('hris.daily_labor.export_excel')}}',
                data: {
                    periode_kehadiran:periode_kehadiran,
                },
                xhrFields: { responseType : 'blob' },
                success:function(data){
                    var blob = new Blob([data]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    let file_name = 'Daily Labor Cost '+periode_kehadiran+' '+Math.ceil(Math.random()*1000000);
                    link.download = file_name+".xlsx";
                    link.click();
                    swal("", "Export Daily Labor Cost", "success");
                    $('#btn-report-excel').removeClass("btn-loading");
                    $("#btn-report-excel").attr("disabled", false);
                },
                error: function(res){
                    swal("", "Export detail kehadiran gagal", "error");
                    $('#btn-report-excel').removeClass("btn-loading");
                    $("#btn-report-excel").attr("disabled", false);
                }
            });
        });
        $(document).ready(function() {
            var start = moment().subtract(29, 'days');
            var end = moment();
            var htmlDateRange = '<span><i class="fa fa-calendar"></i> ' + start.format("D MMM YYYY").toUpperCase() + ' s/d ' + end.format("D MMM YYYY").toUpperCase() + '</span><i class="fa fa-angle-down ml-1"></i>'
            var daterange1 = start.format("YYYY-MM-DD") + " s/d " + end.format("YYYY-MM-DD");
            var dateUpdateKehadiran = end.format("DD-MM-YYYY");

            $('#daterange-btn1').html(htmlDateRange);
            $('#daterange1').val(daterange1);
        });
        $('#daterange-btn1').daterangepicker({
            ranges: {
                'Hari ini': [moment(), moment()],
                'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 Hari Kemarin': [moment().subtract(6, 'days'), moment()],
                '30 Hari Kemarin': [moment().subtract(29, 'days'), moment()],
                'Bulan Sekarang': [moment().startOf('month'), moment().endOf('month')],
                'Bulan Kemarin': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment()
        }, function(start, end) {
            $('#daterange-btn1').html('<span><i class="fa fa-calendar"></i> ' + start.format("D MMM YYYY").toUpperCase() + ' s/d ' + end.format("D MMM YYYY").toUpperCase() + '</span><i class="fa fa-angle-down ml-1"></i>');
            var daterange1 = start.format("YYYY-MM-DD") + " s/d " + end.format("YYYY-MM-DD");
            $('#daterange1').val(daterange1);
        })
    </script>

@endsection