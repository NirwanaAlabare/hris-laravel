@extends('admin.adminlayouts.adminlayout')

@section('head')
    <!--Select2 css -->
    <link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />

    <!-- Notifications  css -->
	<link href="{{URL::asset('assets/plugins/notify-growl/css/jquery.growl.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/notify-growl/css/notifIt.css')}}" rel="stylesheet" />

    <!-- Date Picker css-->
    <link href="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.css')}}" rel="stylesheet" />

	<!---Sweetalert Css-->
	<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

    <style>
        .table-responsive{
            height:400px;
            overflow:scroll;
          }
          thead tr:nth-child(1) th{
            background: white;
            position: sticky;
            top: 0;
            z-index: 10;
          }
    </style>

@stop
@section('mainarea')
    <!-- page-header -->
    <div class="page-header p-2 shadow">
        <ol class="breadcrumb breadcrumb-arrow mt-0">
            <li><a href="#">Perhitungan</a></li>
            <li class="active"><span>Rekap Kehadiran Karyawan</span></li>
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
    <!-- End page-header -->

    <!-- BEGIN FORM-->
    {!! Form::open(['route' => 'hris.rekapkehadirankaryawan.ajax_exportexcel', 'id' => 'formExport', 'name' => 'formExport','method'=>'post']) !!}

    @csrf
    <input id="selDepVal" name="selDepVal" type="hidden">
    <div class="card shadow">
        <div class="card-header bg-primary p-3">
            <div class="card-title">FILTER REKAP KEHADIRAN KARYAWAN</div>
            <div class="card-options ">
                <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Periode Payroll : </label>
                        <select id="periode_payroll" name="periode_payroll" class="form-control">
                        @foreach ($periode_payroll as $r_periode_payroll)
                            <option value="{{$r_periode_payroll->periode_payroll}}">
                                @php
                                    setlocale(LC_ALL, 'id-ID', 'id_ID');
                                    $datePeriode = explode(" s/d ", $r_periode_payroll->periode_payroll);
                                    
                                    if (count($datePeriode) === 2) {
                                        echo strtoupper(
                                            strftime("%A, %d %b %Y", strtotime($datePeriode[0])) . ' s/d ' . 
                                            strftime("%A, %d %b %Y", strtotime($datePeriode[1]))
                                        );
                                    } else {
                                        echo "Format periode tidak valid";
                                    }
                                @endphp
                            </option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">STAFF / NON STAFF : </label>
                        <select id="status_staff" name="status_staff" class="form-control">
                            <option value="">-- PILIH STATUS STAFF --</option>
                            <option value="STAFF">STAFF</option>
                            <option value="NON STAFF">NON STAFF</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12" id="inputSearch">
                    <div class="form-group">
                        <label class="form-label">Cari Data : </label>
                        <input id="searchData" name="searchData" class="form-control" type="text">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-primary m-0 p-1">
            <div class="text-white">
                <button type="submit" id="btn-exportexcel" class="btn btn-app btn-warning mr-0 mt-0 mb-0" data-toggle="tooltip" title="Export Data ke File Excel"><i class="ion-ios7-download"></i> Export</button>
                <a id="btn-daily-report" class="btn btn-app btn-secondary" title="Export data ke file rekap absen"><i class="fa fa-file-excel-o" aria-hidden="true"></i>  Daily Report</a>
                <a id="btn-summary-report" class="btn btn-app btn-secondary" title="Export data ke file rekap summary absensi"><i class="fa fa-file-excel-o" aria-hidden="true"></i>  Summary Report</a>
                <a href="javascript:void(0)" id="btn-cari" class="btn btn-app btn-secondary mr-0 mt-0 mb-0" data-toggle="tooltip" title="Cari Data"><i class="ion-search"></i> Cari</a>
                <a href="javascript:void(0)" id="btn-update" class="btn btn-app btn-info mr-0 mt-0 mb-0" data-toggle="tooltip" title="Update Data"><i class="ion-ios7-reload"></i> UPDATE</a>
            </div>
        </div>
    </div>
    {{-- </form> --}}
    {!! Form::close() !!}
    <!-- END FORM-->

    <!-- row -->
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div id="data-rekap-kehadiran-karyawan" class="card shadow">
                <div class="card-header bg-primary p-3">
                    <div id="title-table1" class="card-title"></div>
                    <div class="card-options">
                        <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="card-body m-0 p-0">
                    <div class="table-responsive">
                        <table id="datatable-ajax-crud" class="table table-sm table-striped table-bordered table-vcenter text-nowrap table-nowrap w-100 m-0 p-0">
                            <thead class="border text-center">
                                <tr>
                                    <th class="bg-primary w-5" scope="col">NO.</th>
                                    <th class="bg-primary w-5" scope="col">ABSEN</th>
                                    <th class="bg-primary w-5" scope="col">NIK</th>
                                    <th class="bg-primary w-5" scope="col">NAMA KARYAWAN</th>
                                    <th class="bg-primary w-5" scope="col">BAGIAN</th>
                                    <th class="bg-primary w-5" scope="col">AKTIF</th>
                                    <th class="bg-primary w-5" scope="col">STAFF</th>
                                    <th class="bg-primary w-5" scope="col" width="50px">IBY</th>
                                    <th class="bg-primary w-5" scope="col" width="50px">ITB</th>
                                    <th class="bg-primary w-5" scope="col" width="50px">LBY</th>
                                    <th class="bg-primary w-5" scope="col" width="50px">LSM</th>
                                    <th class="bg-primary w-5" scope="col" width="50px">DT</th>
                                    <th class="bg-primary w-5" scope="col" width="50px">PC</th>
                                    <th class="bg-primary w-5" scope="col" width="50px">DTPC</th>
                                    <th class="bg-primary w-5" scope="col" width="50px">M</th>
                                    <th class="bg-primary w-5" scope="col" width="50px">R</th>
                                    <th class="bg-primary w-5" scope="col" width="50px">TK</th>
                                    <th class="bg-primary w-5" scope="col" width="50px">OK</th>
                                    <th class="bg-primary w-5" scope="col" width="50px">TOTAL</th>
                                    <th class="bg-primary w-5" scope="col" width="50px">NET</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div id="loadingProcess">
                            <div class="dimmer active">
                                <div class="lds-hourglass mb-0"></div>
                            </div>
                            <h5 class="text-center m-0 p-0 text-dark"><i>data sedang di proses...</i></h5>
                        </div>
                    </div>
                    <i><div class="text-left mt-1 mb-1 text-sm ml-1" id="subtitle-table1"></div></i>
                </div>
                <div class="card-footer bg-primary br-br-7 br-bl-7">
                    <div class="text-white"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- row end -->
</div>

@endsection

@section('footerjs')

    <!--Jquery Sparkline js-->
    <script src="{{ URL::asset('assets/plugins/vendors/jquery.sparkline.min.js') }}"></script>

    <!--Select2 js -->
    <script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>

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

        $('#progress-show-1').hide();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function(){
            'use strict';

            $('.select2').select2({
              minimumResultsForSearch: Infinity
            });

            // Select2 by showing the search
            $('.select2-show-search').select2({
              minimumResultsForSearch: ''
            });

            // Colored Hover
            $('#select2').select2({
              dropdownCssClass: 'hover-success',
              minimumResultsForSearch: Infinity // disabling search
            });

            $('#select3').select2({
              dropdownCssClass: 'hover-danger',
              minimumResultsForSearch: Infinity // disabling search
            });

            // Outline Select
            $('#select4').select2({
              containerCssClass: 'select2-outline-success',
              dropdownCssClass: 'bd-success hover-success',
              minimumResultsForSearch: Infinity // disabling search
            });

            $('#select5').select2({
              containerCssClass: 'select2-outline-info',
              dropdownCssClass: 'bd-info hover-info',
              minimumResultsForSearch: Infinity // disabling search
            });

            // Full Colored Select Box
            $('#select6').select2({
              containerCssClass: 'select2-full-color select2-primary',
              minimumResultsForSearch: Infinity // disabling search
            });

            $('#select7').select2({
              containerCssClass: 'select2-full-color select2-danger',
              dropdownCssClass: 'hover-danger',
              minimumResultsForSearch: Infinity // disabling search
            });

            // Full Colored Dropdown
            $('#select8').select2({
              dropdownCssClass: 'select2-drop-color select2-drop-primary',
              minimumResultsForSearch: Infinity // disabling search
            });

            $('#select9').select2({
              dropdownCssClass: 'select2-drop-color select2-drop-indigo',
              minimumResultsForSearch: Infinity // disabling search
            });

            // Full colored for both box and dropdown
            $('#select10').select2({
              containerCssClass: 'select2-full-color select2-primary',
              dropdownCssClass: 'select2-drop-color select2-drop-primary',
              minimumResultsForSearch: Infinity // disabling search
            });

            $('#select11').select2({
              containerCssClass: 'select2-full-color select2-indigo',
              dropdownCssClass: 'select2-drop-color select2-drop-indigo',
              minimumResultsForSearch: Infinity // disabling search
            });
        });

        //Date range as a button
        $('#daterange1').daterangepicker({
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
            //
        })

        $('body').on('change', '#periode_payroll', function () {
            $("#btn-cari").click();
        });

        $('body').on('change', '#status_staff', function () {
            $("#btn-cari").click();
        });

        $('body').on('click', '#btn-exportexcel', function (event) {
            notif({
                msg: "<b>Info:</b> Data sedang di proses, mohon menunggu",
                type: "info"
            });
        });

        $('body').on('click', '#btn-refresh-page', function (event) {
            location.reload();
        });

        $(document).ready(function() {
            $("#data-rekap-kehadiran-karyawan").hide();
            $("#inputSearch").hide();
            $("#loadingProcess").hide();


            $('#searchData').keyup(function(){
                search_table($(this).val());
           });
           function search_table(value){
                $('#data-rekap-kehadiran-karyawan tbody tr').each(function(){
                     var found = 'false';
                     $(this).each(function(){
                          if($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0)
                          {
                               found = 'true';
                          }
                     });
                     if(found == 'true')
                     {
                          $(this).show();
                     }
                     else
                     {
                          $(this).hide();
                     }
                });
           }


        });

        $('body').on('click', '#btn-update', function (event) {

            $("#btn-update").addClass("btn-loading");
            $("#btn-update").html('Loading...');
            $("#btn-update").attr("disabled", true);
            var periode_payroll=$("#periode_payroll").val();
            var status_staff=$("#status_staff").val();
            var searchData = $("#searchData").val();
            $.ajax({
                url: "{{route('hris.rekapkehadirankaryawan.update_rekap_absen')}}",
                type: "POST",
                data: {
                    periode_payroll: periode_payroll,
                    status_staff : status_staff,
                    searchData : searchData,
                },
                cache: false,
                success: function(data){
                    console.log('data',data);
                    swal({
                        title: "Rekap Kehadiran",
                        text: "Rekap kehadiran berhasil di UPDATE",
                        icon: "info",
                    });
                    $('#btn-update').removeClass("btn-loading");
                    $("#btn-update").html('<span><i class="ion-ios7-reload"></i></span> UPDATE');
                    $("#btn-update").attr("disabled", false);
                }
            });
        });
        $('body').on('click', '#btn-cari', function (event) {

            $("#btn-cari").addClass("btn-loading");
            $("#btn-cari").html('Loading...');
            $("#btn-cari").attr("disabled", true);

            $("#periode_payroll").removeClass("border-danger");

            if(!$("#periode_payroll").val()) {
                notif({
                    msg: "<b>Warning:</b> Anda belum memilih periode payroll.",
                    type: "warning"
                });

                $("#periode_payroll").addClass('border-danger');
                $('#btn-cari').removeClass("btn-loading");
                $("#btn-cari").html('<i class="fa fa-paste"></i> Cari');
                $("#btn-cari").attr("disabled", false);

                return false;
            }

            $("#data-rekap-kehadiran-karyawan tbody").empty();
            $("#data-rekap-kehadiran-karyawan").show();
            $("#inputSearch").show();
            $("#loadingProcess").show();

            var periode_payroll = $("#periode_payroll").val();
            var status_staff = $("#status_staff").val();
            var searchData = $("#searchData").val();

            $.ajax({
                type:"POST",
                url: "{{route('hris.rekapkehadirankaryawan.ajax_rekap')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    periode_payroll:periode_payroll,
                    status_staff:status_staff,
                    searchData:searchData,
                },
                dataType: 'json',
                success: function(res){
                    if(res.length > 0){
                        periodeArray = res[0].periode_payroll.split(" s/d ");
                        periode1 = new Date(periodeArray[0]).toLocaleDateString('id-ID', { weekday:"long", year:"numeric", month:"short", day:"numeric"});
                        periode2 = new Date(periodeArray[1]).toLocaleDateString('id-ID', { weekday:"long", year:"numeric", month:"short", day:"numeric"}) ;
                        terakhirUpdate = new Date(res[0].updated_at);

                        $("#title-table1").text('PERIODE PAYROLL ' + periode1.toUpperCase() + " S/D " + periode2.toUpperCase());
                        $("#subtitle-table1").text('Terakhir diupdate : ' + terakhirUpdate);

                        for(i=0;i<res.length;i++) {
                            nomor_urut = i+1;
                            bgwarna = 0;
                            if (res[i].selisih > 0) { bgwarna = 'bg-danger'; }
                            if (res[i].periode_payroll) { periode_payroll = res[i].periode_payroll; }

                            $htmlTable = '' +
                            '<tr class="text-center ' + bgwarna + '">' +
                            '   <td class="text-nowrap text-right">' + nomor_urut + '.</td>' +
                            '   <td class="text-nowrap text-right">' + res[i].enroll_id  + '</td>' +
                            '   <td class="text-nowrap text-left">' + res[i].nik  + '</td>' +
                            '   <td class="text-nowrap text-left">' + res[i].employee_name  + '</td>' +
                            '   <td class="text-nowrap text-left">' + res[i].sub_dept_name  + '</td>' +
                            '   <td class="text-nowrap">' + res[i].status_aktif  + '</td>' +
                            '   <td class="text-nowrap">' + res[i].status_staff  + '</td>' +
                            '   <td class="text-nowrap text-right">' + res[i].kehadiran_iby  + '</td>' +
                            '   <td class="text-nowrap text-right">' + res[i].kehadiran_itb  + '</td>' +
                            '   <td class="text-nowrap text-right">' + res[i].kehadiran_lby  + '</td>' +
                            '   <td class="text-nowrap text-right">' + res[i].kehadiran_lsm  + '</td>' +
                            '   <td class="text-nowrap text-right">' + res[i].kehadiran_dt  + '</td>' +
                            '   <td class="text-nowrap text-right">' + res[i].kehadiran_pc  + '</td>' +
                            '   <td class="text-nowrap text-right">' + res[i].kehadiran_dtpc  + '</td>' +
                            '   <td class="text-nowrap text-right">' + res[i].kehadiran_m  + '</td>' +
                            '   <td class="text-nowrap text-right">' + res[i].kehadiran_r  + '</td>' +
                            '   <td class="text-nowrap text-right">' + res[i].kehadiran_tk  + '</td>' +
                            '   <td class="text-nowrap text-right">' + res[i].kehadiran_ok  + '</td>' +
                            '   <td class="text-nowrap text-right">' + res[i].total_kehadiran  + '</td>' +
                            '   <td class="text-nowrap text-right">' + res[i].total_kehadiran_net  + '</td>' +
                            '</tr>';

                            $("#datatable-ajax-crud tbody").append($htmlTable);

                        }

                    } else {
                        notif({
                            msg: "<b>Warning:</b> Data tidak ditemukan.",
                            type: "warning"
                        });
                    }


                    $('#btn-cari').removeClass("btn-loading");
                    $("#btn-cari").html('<i class="fa fa-search"></i> Cari');
                    $("#btn-cari").attr("disabled", false);
                    $("#loadingProcess").hide();

                },
                error: function(res){
                    $('#btn-cari').removeClass("btn-loading");
                    $("#btn-cari").html('<i class="fa fa-search"></i> Cari');
                    $("#btn-cari").attr("disabled", false);
                    $("#loadingProcess").hide();

                    notif({
                        msg: "<b>Oops!</b> An Error Occurred",
                        type: "error",
                        position: "center"
                    });
                }
            });

        });

        $('#datatable-ajax-crud tbody').on( 'click', 'tr', function () {
            $("#datatable-ajax-crud tbody tr").each(function () {
                $(this).removeClass('bg-cyan');
            });

            $(this).addClass('bg-cyan');
        } );
        function func(){
            var periode_payroll  =document.getElementsByName("periode_payroll")[0].value;
            window.location.href = "/rekap_absen?param1="+periode_payroll;
        }
        $('#btn-summary-report').click(function(e){
            notif({
                msg: "Export summary report sedang di proses, mohon menunggu",
                type: "info"
            });
            $('#btn-summary-report').addClass("btn-loading");
            $("#btn-summary-report").html('Please wait...');
            $("#btn-summary-report").attr("disabled", true);
            var periode_payroll  = document.getElementsByName("periode_payroll")[0].value;
            $.ajax({
                url: "{{route('hris.rekapkehadirankaryawan.summary_report')}}",
                type:"post",
                data: {
                    periode_payroll:periode_payroll,
                },
                xhrFields: { responseType : 'blob' },
                success: function(data){
                    var d = new Date(),
                        month = '' + (d.getMonth() + 1),
                        day = '' + d.getDate(),
                        year = d.getFullYear();

                    if (month.length < 2) 
                        month = '0' + month;
                    if (day.length < 2) 
                        day = '0' + day;

                    var full_date= [year, month, day].join('-');

                    $('#btn-summary-report').removeClass("btn-loading");
                    $("#btn-summary-report").html('<i class="fa fa-file-excel-o" aria-hidden="true"></i>  Summary Report');
                    $("#btn-summary-report").attr("disabled", false);
                    var blob = new Blob([data]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "Summary_report_"+full_date+'_'+Math.ceil(Math.random()*1000000)+".xlsx";
                    link.click();
                    notif({
                        msg: "Summary report export telah selesai",
                        type: "success"
                    });
                }
            });
        });
        $('body').on('click', '#btn-daily-report', function (event) {
            notif({
                msg: "Export daily report sedang di proses, mohon menunggu",
                type: "info"
            });
            $('#btn-daily-report').addClass("btn-loading");
            $("#btn-daily-report").html('Please wait...');
            $("#btn-daily-report").attr("disabled", true);
            var periode_payroll  = document.getElementsByName("periode_payroll")[0].value;
            $.ajax({
                url: "{{route('hris.rekapkehadirankaryawan.daily_report')}}",
                type:"post",
                data: {
                    periode_payroll:periode_payroll,
                },
                xhrFields: { responseType : 'blob' },
                success: function(data){
                    var d = new Date(),
                        month = '' + (d.getMonth() + 1),
                        day = '' + d.getDate(),
                        year = d.getFullYear();

                    if (month.length < 2) 
                        month = '0' + month;
                    if (day.length < 2) 
                        day = '0' + day;

                    var full_date= [year, month, day].join('-');

                    $('#btn-daily-report').removeClass("btn-loading");
                    $("#btn-daily-report").html('<i class="fa fa-file-excel-o" aria-hidden="true"></i>  Daily Report');
                    $("#btn-daily-report").attr("disabled", false);
                    var blob = new Blob([data]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "Daily_report_"+full_date+'_'+Math.random()*1000+".xlsx";
                    link.click();
                    notif({
                        msg: "Daily report export telah selesai",
                        type: "success"
                    });
                }
            });
        });

    </script>

@endsection
