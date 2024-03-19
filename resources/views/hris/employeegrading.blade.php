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

	<!-- Tabs css-->
	<link href="{{URL::asset('assets/plugins/tabs/tabs-style.css')}}" rel="stylesheet" />

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
            <li><a href="#">Payroll</a></li>
            <li class="active"><span>Grading Karyawan</span></li>
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
    <div class="card shadow">
        <div class="card-header bg-primary p-3">
            <div class="card-title">FILTER GRADING KARYAWAN</div>
            <div class="card-options ">
                <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">STAFF / NON STAFF : </label>
                        <select id="status_staff" name="status_staff" class="form-control">
                            <option value="">-- PILIH STATUS STAFF --</option>
                            <option value="STAFF">STAFF</option>
                            <option value="NON STAFF">NON STAFF</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3" id="injectData">
                    <div class="form-group">
                        <label class="form-label">PERIODE UMR TAHUN : </label>
                        <div class="input-group">
                            <select id="periode_umk" name="periode_umk" class="form-control">
                                @foreach ($periode_umr as $r_periode_umr)
                                    <option  value="{{$r_periode_umr->periode_umk}}">
                                    @php
                                        setlocale(LC_ALL, 'id-ID', 'id_ID');
                                        $datePeriode = $r_periode_umr->periode_umk;
                                        echo substr($datePeriode, 0, 4);
                                    @endphp
                                    </option>
                                @endforeach
                            </select>
                            <span class="input-group-append">
                                <button class="btn btn-primary btn-app" type="button" id="btn-update-grading"><i class="fa fa-upload"></i> Update</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" id="inputSearch">
                    <div class="form-group">
                        <label class="form-label">CARI DATA : </label>
                        <input id="searchData" name="searchData" class="form-control" type="text">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-primary m-0 p-1">
            <div class="text-white">
                <button type="submit" id="btn-exportexcel" class="btn btn-app btn-warning mr-0 mt-0 mb-0" data-toggle="tooltip" title="Export Data ke File Excel"><i class="ion-ios7-download"></i> Export</button>
                <a href="javascript:void(0)" id="btn-cari" class="btn btn-app btn-secondary mr-0 mt-0 mb-0" data-toggle="tooltip" title="Cari Data"><i class="ion-search"></i> Cari</a>
            </div>
        </div>
    </div>
    {{-- </form> --}}
    {!! Form::close() !!}
    <!-- END FORM-->

    <!-- row -->
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div id="data-employeesalary" class="card shadow">
                <div class="card-header bg-primary p-3">
                    <div id="title-table1" class="card-title">DATA GRADING KARYAWAN</div>
                    <div class="card-options">
                        <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="card-body m-0 p-0">
                    <div class="table-responsive">
                        <table id="datatable-ajax-crud" class="table table-sm table-striped table-bordered table-vcenter text-nowrap table-nowrap w-100 m-0 p-0">
                            <thead class="border text-center">
                                <tr>
                                    <th class="bg-primary w-5 align-middle" scope="col">NO.</th>
                                    <th class="bg-primary w-5 align-middle" scope="col">PERIODE</th>
                                    <th class="bg-primary w-5 align-middle" scope="col">ABSEN</th>
                                    <th class="bg-primary w-5 align-middle" scope="col">NIK</th>
                                    <th class="bg-primary w-5 align-middle" scope="col">NAMA KARYAWAN</th>
                                    <th class="bg-primary w-5 align-middle" scope="col">STAFF</th>
                                    <th class="bg-primary w-5 align-middle" scope="col">AKTIF</th>
                                    <th class="bg-primary w-5 align-middle" scope="col">KODE GRADE</th>
                                    <th class="bg-primary w-5 align-middle" scope="col">GAJI POKOK (RP)</th>
                                    <th class="bg-primary w-5 align-middle" scope="col">BAGIAN</th>
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

    <!---Tabs js-->
    <script src="{{URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/tabs/tabs.js')}}"></script>

    <script type="text/javascript">
        $('.fc-datepicker').datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'yy-mm-dd'
        });

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

        $('body').on('change', '#periode_umk', function () {
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
            $("#inputSearch").hide();
            $("#injectData").hide();
            $("#loadingProcess").hide();


            $('#searchData').keyup(function(){
                search_table($(this).val());
            });

            function search_table(value){
                $('#data-employeesalary tbody tr').each(function(){
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

        $('body').on('click', '#btn-cari', function (event) {
            var status_staff = $("#status_staff").val();
            var searchData = $("#searchData").val();
            var periode_umk = $("#periode_umk").val();

            $("#btn-cari").addClass("btn-loading");
            $("#btn-cari").html('Loading...');
            $("#btn-cari").attr("disabled", true);

            $("#data-employeesalary tbody").empty();
            $("#data-employeesalary").show();
            $("#inputSearch").show();
            $("#injectData").show();
            $("#loadingProcess").show();

            $.ajax({
                type:"POST",
                url: "{{route('hris.employeegrading.ajax_data')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    status_staff:status_staff,
                    searchData:searchData,
                    periode_umk:periode_umk,
                },
                dataType: 'json',
                success: function(res){
                    if(res.length > 0){
                        terakhirUpdate = new Date(res[0].updated_at);
                        operator = res[0].operator;

                        $("#subtitle-table1").text('Terakhir diupdate : ' + terakhirUpdate + ' oleh ' + operator);

                        for(i=0;i<res.length;i++) {
                            nomor_urut = i+1;

                            $htmlTable = '' +
                            '<tr class="text-center">' +
                            '   <td class="text-nowrap text-right align-middle">' +
                                    nomor_urut + '. ' +
                            '   <input type="hidden" id="salary_bulanan_' + nomor_urut + '" name="salary_bulanan">' +
                            '   </td>' +
                            '   <td class="text-nowrap text-center align-middle">' + res[i].periode_umk  + '</td>' +
                            '   <td class="text-nowrap text-left align-middle">' + res[i].enroll_id  + '</td>' +
                            '   <td class="text-nowrap text-left align-middle">' + res[i].nik  + '</td>' +
                            '   <td class="text-nowrap text-left align-middle">' + res[i].employee_name  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + res[i].status_staff  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + res[i].status_aktif  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + res[i].kode_grade  + '</td>' +
                            '   <td class="text-nowrap text-right align-middle">' + res[i].salary_bulanan_rupiah  + '</td>' +
                            '   <td class="text-nowrap text-left align-middle">' + res[i].sub_dept_name+ '</td>' +
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

        $('#datatable-ajax-crud tbody').on('dblclick', 'tr', function (e) {
            // get the current row
            var currentRow = $(this).closest("tr");



        });

        $('body').on('click', '#btn-update-grading', function (event) {
            var periode_umk = $("#periode_umk").val();

            $("#btn-update-grading").addClass("btn-loading");
            $("#btn-update-grading").html('Loading...');
            $("#btn-update-grading").attr("disabled", true);

            $("#datatable-ajax-crud tbody").empty();
            $("#datatable-ajax-crud").show();
            $("#loadingProcess").show();

            $.ajax({
                type:"POST",
                url: "{{route('hris.employeegrading.update_grading')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    periode_umk:periode_umk,
                },
                dataType: 'json',
                success: function(res){
                    notif({
                        msg: "<b>Success:</b> Data berhasil di update.",
                        type: "success"
                    });

                    $('#btn-update-grading').removeClass("btn-loading");
                    $("#btn-update-grading").html('<i class="fa fa-upload"></i> Update');
                    $("#btn-update-grading").attr("disabled", false);

                    $("#btn-cari").click();

                },
                error: function(res){
                    $('#btn-update-grading').removeClass("btn-loading");
                    $("#btn-update-grading").html('<i class="fa fa-upload"></i> Update');
                    $("#btn-update-grading").attr("disabled", false);
                    $("#loadingProcess").hide();

                    notif({
                        msg: "<b>Oops!</b> An Error Occurred",
                        type: "error",
                        position: "center"
                    });
                }
            });

        });

        function defaultDate(s) {
            if(s) {
                var bits = s.split('-');
                var d = bits[2] + '-' + bits[1] + '-' + bits[0];
            }
            return d;
        }

    </script>

@endsection
