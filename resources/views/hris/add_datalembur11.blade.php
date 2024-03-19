@extends('admin.adminlayouts.adminlayout')

@section('head')
    <!-- Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet" />

    <!--Select2 css -->
    <link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />

    <!-- Time picker css-->
    <link href="{{URL::asset('assets/plugins/time-picker/jquery.timepicker.css')}}" rel="stylesheet" />

    <!-- Date Picker css-->
    <link href="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.css')}}" rel="stylesheet" />

    <!--Mutipleselect css-->
    <link rel="stylesheet" href="{{URL::asset('assets/plugins/multipleselect/multiple-select.css')}}">

	<!-- Notifications  css -->
	<link href="{{URL::asset('assets/plugins/notify-growl/css/jquery.growl.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/notify-growl/css/notifIt.css')}}" rel="stylesheet" />

    <!-- Forn-wizard css-->
	<link href="{{URL::asset('assets/plugins/form-wizard/css/form-wizard.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/formwizard/smart_wizard.css')}}" rel="stylesheet">
	<link href="{{URL::asset('assets/plugins/formwizard/smart_wizard_theme_dots.css')}}" rel="stylesheet">

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
            <li><a href="#">DATA LEMBUR</a></li>
            <li class="active"><span>TAMBAH DATA</span></li>
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

    <div class="card shadow">
        <div class="card-header bg-primary p-2">
            <div class="card-title">FILTER DATA KARYAWAN</div>
            <div class="card-options ">
                <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">WAKTU LEMBUR </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                </div>
                            </div><!-- input-group-prepend -->
                            <input class="form-control" id="waktu_jam_lembur" name="waktu_jam_lembur" onChange="swl('waktu_jam_lembur');" placeholder="DD-MM-YYYY HH:MM - DD-MM-YYYY HH:MM" type="text">
                            <input id="mulai_jam_lembur" name="mulai_jam_lembur" type="hidden">
                            <input id="akhir_jam_lembur" name="akhir_jam_lembur" type="hidden">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">JMLH JAM LEMBUR : </label>
                        <input class="form-control" id="jumlah_jam_lembur" name="jumlah_jam_lembur" placeholder="Input jumlah jam" type="text">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">JMLH JAM ISTIRAHAT : </label>
                        <input class="form-control" id="jumlah_jam_istirahat" name="jumlah_jam_istirahat" type="text">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">PILIH KARYAWAN : </label>
                        <div class="input-group">
                            <input id="selectEmployee" name="selectEmployee" type="hidden">
                            <input readonly id="jumlahEmp" name="jumlahEmp" class="form-control" placeholder="Silakan Pilih Karyawan" type="text">
                            <span class="input-group-append">
                                <button class="btn btn-primary btn-app" type="button" id="btn-get-employee"><i class="fa fa-upload"></i> Karyawan</button>
                            </span>
                        </div>                    
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">PILIH BAGIAN : </label>
                        <div class="input-group">
                            <input id="selectSubDept" name="selectSubDept" type="hidden">
                            <input readonly id="jumlahSubDept" name="jumlahSubDept" class="form-control" placeholder="Silakan Pilih Bagian" type="text">
                            <span class="input-group-append">
                                <button class="btn btn-primary btn-app" type="button" id="btn-get-subdept"><i class="fa fa-upload"></i> Bagian</button>
                            </span>
                        </div>                    
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">CATATAN LEMBUR : </label>
                        <input class="form-control" id="catatan" name="catatan" type="text">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-primary m-0 p-1">
            <div class="text-white">
                <button type="submit" id="btn-examimport" class="btn btn-app btn-orange mr-0 mt-0 mb-0" data-toggle="tooltip" title="Format File Excel"><i class="fa fa-file-excel-o"></i> Format Import</button>
                <button type="submit" id="btn-import" class="btn btn-app btn-warning mr-0 mt-0 mb-0" data-toggle="tooltip" title="Import Data Dari File Excel"><i class="fa fa-file-excel-o"></i> Import</button>
                <a href="javascript:void(0)" id="btn-save" class="btn btn-app btn-secondary mr-0 mt-0 mb-0" data-toggle="tooltip" title="Simpan Data"><i class="fa fa-paste"></i> Simpan</a>
            </div>
        </div>
    </div>

    <!-- row -->
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div id="data-lembur" class="card shadow">
                <div class="card-header bg-primary p-2">
                    <div id="title-table1" class="card-title">DATA KARYAWAN LEMBUR</div>
                    <div class="card-options">
                        <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="card-body m-0 p-0">
                    <div class="table-responsive">
                        <table id="datatable-ajax-crud" class="table table-sm table-striped table-bordered table-vcenter text-nowrap table-nowrap w-100 m-0 p-0">
                            <thead class="border text-center">
                                <tr>
                                    <th class="bg-primary w-5 align-middle" scope="col">No.</th>
                                    <th class="bg-primary w-5 align-middle" scope="col">Tanggal Lembur</th>
                                    <th class="bg-primary w-5 align-middle" scope="col">Nama Hari</th>
                                    <th class="bg-primary w-5 align-middle" scope="col">NIK</th>
                                    <th class="bg-primary w-5 align-middle" scope="col">Nama Karyawan</th>
                                    <th class="bg-primary w-5 align-middle" scope="col">Absen IN</th>
                                    <th class="bg-primary w-5 align-middle" scope="col">Absen OUT</th>
                                    <th class="bg-primary w-5 align-middle" scope="col">Waktu Lembur</th>
                                    <th class="bg-primary w-5 align-middle" scope="col">Istirahat (jam)</th>
                                    <th class="bg-primary w-5 align-middle" scope="col">Jumlah (jam)</th>
                                    <th class="bg-primary w-5 align-middle" scope="col">Catatan</th>
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

    <!-- boostrap show department model -->
    <div class="modal fade" id="ajax-modal-pilih1" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="row">
                <div class="col-md-12">

                    <div class="modal-content">
                        <div class="modal-header bg-primary p-2">
                            <h4 class="modal-title pl-2"><b>DATA KARYAWAN</b></h4>
                            <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Karyawan Yang Dipilih :</label>
                                        <select class="form-control select2" id="selectEmp" name="selectEmp" multiple>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="datatable-ajax-modal" class="table table-sm table-striped table-hover table-bordered w-100">
                                            <thead>
                                                <tr class="text-center">
                                                    <th scope="col"></th>
                                                    <th scope="col"></th>
                                                    <th scope="col"></th>
                                                    <th scope="col"></th>
                                                    <th scope="col"></th>
                                                    <th scope="col"></th>
                                                    <th scope="col"></th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-primary p-1">
                            <div class="btn-list">
                                <button type="button" id="btn-pilih" class="btn btn-secondary btn-app" data-dismiss="modal">Pilih</button>
                                <button type="button" id="btn-close" class="btn btn-warning btn-app" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end bootstrap model -->

    <!-- boostrap show department model -->
    <div class="modal fade" id="ajax-modal-pilih2" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="row">
                <div class="col-md-12">

                    <div class="modal-content">
                        <div class="modal-header bg-primary p-2">
                            <h4 class="modal-title pl-2"><b>DATA DEPARTMENT & BAGIAN</b></h4>
                            <button type="button" id="btn-close2" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Bagian Yang Dipilih :</label>
                                        <select class="form-control select2" id="selectSub" name="selectSub" multiple>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="datatable-ajax-modal2" class="table table-sm table-striped table-hover table-bordered w-100">
                                            <thead>
                                                <tr class="text-center">
                                                    <th scope="col"></th>
                                                    <th scope="col"></th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-primary p-1">
                            <div class="btn-list">
                                <button type="button" id="btn-pilih2" class="btn btn-secondary btn-app" data-dismiss="modal">Pilih</button>
                                <button type="button" id="btn-close2" class="btn btn-warning btn-app" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end bootstrap model -->

@endsection

@section('footerjs')

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

    <!-- Forn-wizard js-->
    <script src="{{URL::asset('assets/plugins/formwizard/jquery.smartWizard.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/formwizard/fromwizard.js')}}"></script>

    <!-- Timepicker js -->
    <script src="{{URL::asset('assets/plugins/time-picker/jquery.timepicker.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/time-picker/toggles.min.js')}}"></script>

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

        $('.fc-datepicker').datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'yy-mm-dd'
          });

        //$(function(id) {
        function datetimerangepicker(id) {
            //id = 'waktu_jam_lembur';
            
            $('input[id="' + id + '"]').daterangepicker({
                timePicker: true,
                timePickerIncrement: 1,
                locale: {
                    format: 'DD-MM-YYYY HH:mm'
                },
                pickDate: true,
                pickSeconds: false,
                pick24HourFormat: true
            });
        }

        function jamlemburistirahat(mulai_jam_lembur, akhir_jam_lembur) {
            var dt1Split = mulai_jam_lembur.split(" ");
            var dt2Split = akhir_jam_lembur.split(" ");

            var dt1 = new Date(defaultDate(dt1Split[0]) + " " + dt1Split[1]);
            var dt2 = new Date(defaultDate(dt2Split[0]) + " " + dt2Split[1]);
            //alert(diff_hours(dt1, dt2));

            var jmljamlembur = diff_hours(dt1, dt2);
            var jamistirahatlembur = 0;
            var jmljamke1=0
            if ( (dt1.getHours() <= 18 && ( (dt2.getHours() == 18 && dt2.getMinutes() >= 30) || (dt2.getHours() > 18) )) ||(dt1Split[0] != dt2Split[0]) ) {
                jamistirahatlembur += 0.5;
                var dt_istirahat = new Date(defaultDate(dt1Split[0]) + " " + "18:30");
                var jmljamke1 = diff_hours(dt1, dt_istirahat);
            }
            console.log(jmljamke1)
            if((jmljamlembur-jmljamke1) >= 4) {
                jamistirahatlembur += 0.5;
            }
            if((jmljamlembur-(jmljamke1+0.5)) >= 8) {
                jamistirahatlembur += 0.5;
            }
            if((jmljamlembur-(jmljamke1+1)) >= 12) {
                jamistirahatlembur += 0.5;
            }
            jmljamlembur = jmljamlembur-jamistirahatlembur;

            $('#mulai_jam_lembur').val(mulai_jam_lembur);
            var mulaitgllembur = mulai_jam_lembur.split(" ");
            $('#daterange1').val(mulaitgllembur[0]);
            $('#tanggal_lembur_label').text(mulaitgllembur[0]);
            $('#tanggal_lembur').val(mulaitgllembur[0]);
            $('#akhir_jam_lembur').val(akhir_jam_lembur);
            $("#jumlah_jam_lembur").val(jmljamlembur);
            $("#jumlah_jam_istirahat").val(jamistirahatlembur);
            $("#jumlah_jam_istirahat_label").text(jamistirahatlembur);

            //alert(splitWaktuLembur[0]);
        }

        function jamlemburistirahat_edit(mulaijamlembur, akhirjamlembur, mulai_jam_lembur, akhir_jam_lembur, jumlah_jam_lembur, jumlah_jam_istirahat) {
            var dt1 = new Date(mulaijamlembur);
            var dt2 = new Date(akhirjamlembur);
            //alert(diff_hours(dt1, dt2));
            var jmljamlembur = diff_hours(dt1, dt2);
            var jamistirahatlembur = 0;

            if(jmljamlembur >= 1.5) {
                jamistirahatlembur = 0.5;
                jmljamlembur = jmljamlembur-jamistirahatlembur;
            }

            $('#' + mulai_jam_lembur).val(mulaijamlembur);
            $('#' + akhir_jam_lembur).val(akhirjamlembur);
            $('#' + jumlah_jam_lembur).val(jmljamlembur);
            $('#' + jumlah_jam_istirahat).val(jamistirahatlembur);

            //alert(splitWaktuLembur[0]);
        }

        //$('body').on('change', '#waktu_jam_lembur', function () {
        function swl(id) {
            //alert(id);
            var waktu_jam_lembur = $('#' + id).val();
            var splitWaktuLembur = waktu_jam_lembur.split(" - ");
            var mulai_jam_lembur = splitWaktuLembur[0];
            var akhir_jam_lembur = splitWaktuLembur[1];

            jamlemburistirahat(mulai_jam_lembur, akhir_jam_lembur);

        }

        function swl_edit(id, mulai_jam_lembur, akhir_jam_lembur, jumlah_jam_lembur, jumlah_jam_istirahat) {
            //alert(id);
            var waktu_jam_lembur = $('#' + id).val();
            var splitWaktuLembur = waktu_jam_lembur.split(" - ");
            var mulaijamlembur = splitWaktuLembur[0];
            var akhirjamlembur = splitWaktuLembur[1];

            jamlemburistirahat_edit(mulaijamlembur, akhirjamlembur, mulai_jam_lembur, akhir_jam_lembur, jumlah_jam_lembur, jumlah_jam_istirahat);


        }

        function tanggalSekarang() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;
            return today;
        }

        $('#daterange1').val(tanggalSekarang());
        $('#tanggal_lembur_label').text(tanggalSekarang());
        $('#tanggal_lembur').val(tanggalSekarang());

        $('body').on('change', '#daterange1', function () {
            $('#tanggal_lembur_label').text($('#daterange1').val());
            $('#tanggal_lembur').val($('#daterange1').val());
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


        //alert($('#selectDepartment option:selected').length);

        $("#check-all-karyawan").attr('disabled','disabled');

        function defaultDate(s) {
            if(s) {
                var bits = s.split('-');
                var d = bits[2] + '-' + bits[1] + '-' + bits[0];
            }
            return d;
        }        
        
        $(document).ready(function() {
            $("#loadingProcess").hide();
            $("#data-lembur").hide();
            var defaultDateJamLembur = defaultDate(tanggalSekarang()) + ' 17:00 - ' + defaultDate(tanggalSekarang()) + ' 18:00';
            $("#waktu_jam_lembur").val(defaultDateJamLembur);
            
            $("#jumlah_jam_lembur").val('1');
            $("#jumlah_jam_istirahat").val('0');
        });

        $('body').on('change', '#selectDepartment', function () {
            var department_id = $('#selectDepartment').val();
            $("#check-all-karyawan").removeAttr("disabled");
            if ($("#selectEmployeeID option:selected").length == 0) {
                $("#selectEmployeeID").empty();
                $("#selectEmployeeID").val(null).trigger("change");
                $("#check-all-karyawan").prop("checked",false);
            }

            if(department_id){
                $.ajax({
                    type:"POST",
                    url: "{{route('hris.datalembur.ajax_getemployeselectdeptid')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        department_id:department_id,
                    },
                    dataType: 'json',
                    success: function(resA){
                        if(resA){
                            for(i=0;i<resA.length;i++) {
                                $("#selectEmployeeID").append(new Option(resA[i].select_employee, resA[i].nik));
                            }
                        }
                        $('#selectEmployeeID').multipleSelect({
                            selectAll: true,
                            width: "100%",
                            filter: true,
                            sort: true,
                        });
                        var departmentNameLabel = [];
                        var deptNameSplit = $("#selectDepartment option:selected").text();
                        departmentNameLabel = deptNameSplit.split("[NAG] ");
                        var departmentJoin = departmentNameLabel.join();
                        var deptArray = departmentJoin.split(",");
                        var departmentName = '';
                        for(i=1;i<deptArray.length;i++) {
                            departmentName += deptArray[i] + ', ';
                        }
                        $("#department_name").text(departmentName);
                    }
                });
            } else {
                $.ajax({
                    type:"POST",
                    url: "{{route('hris.mdabsenhadir.ajax_getallemployeeatribut')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        department_id:department_id,
                    },
                    dataType: 'json',
                    success: function(res){
                        if(res){
                            for(i=0;i<res.length;i++) {
                                $("#selectEmployeeID").append(new Option(res[i].select_employee, res[i].nik));
                            }
                        }
                        $('#selectEmployeeID').multipleSelect({
                            selectAll: true,
                            width: "100%",
                            filter: true,
                            sort: true,
                        });
                    }
                });
            }
        });


        function diff_hours(dt2, dt1)
        {

         var diff =(dt2.getTime() - dt1.getTime()) / 1000;
         diff /= (60 * 60);
         return Math.abs(parseFloat(diff).toFixed(1));

        }

        $('body').on('click', '#check-all-karyawan', function (event) {
            var department_id = $('#selectDepartment').val();
            var selectEmployeeID = $('#selectEmployeeID').val();
            //alert($("#selectEmployeeID").val(null).length);

            if ($("#selectEmployeeID").val() == "") {
                $("#selectEmployeeID > option").prop("selected", "selected");
                $("#selectEmployeeID").trigger("change");
            } else {
                $("#selectEmployeeID").val(null).trigger("change");
            }

        });

        $('body').on('change', '#selectEmployeeID', function (event) {
            //alert("OK");
            var options = document.getElementById('selectEmployeeID').options;
            //var selctEmpID = document.getElementById("selectEmployeeID");
            //var text = options[selctEmpID.selectedIndex].text();
            var daterange1 = $("#daterange1").val();
            var tanggal_lembur = daterange1.split(" - ");
            $("#tanggal_lembur").val(tanggal_lembur[0]);
            $("#tanggal_lembur_label").text(tanggal_lembur[0]);

            var selected = $("#selectEmployeeID :selected").map((_, e) => e.value).get();
            var countSelectedEmp = $('#selectEmployeeID :selected').length;
            $('#countSelectedEmp').val(countSelectedEmp);
            $('#countSelectedEmp_label').text(countSelectedEmp);
            //alert(countSelectedEmp);

            for (let i = 0; i < options.length; i++) {
              //console.log(options[i].value);

            }
        });

        // Toolbar extra buttons
        var btnFinish = $('<button disabled id="btn-save-wizard"></button>').text('Simpan')
            .addClass('btn btn-success')
            .on('click', function(){

                var html_table_data = '';
                var arrayHtml = [];
                var bRowStarted = true;
                $('#table-ajax-edit-lembur tbody>tr').each(function () {
                    html_table_data = "";

                    $(':input', this).each(function () {
                        if (html_table_data.length == 0 || bRowStarted == true) {
                            html_table_data += '"' + $(this).attr("name") + '": "' + $(this).val() + '", ';
                            bRowStarted = false;
                        }
                        else
                            html_table_data += '"' + $(this).attr("name") + '": "' + $(this).val() + '", ';
                    });
                    html_table_data = html_table_data.slice(0, -2) + '';
                    html_table_data = "{" + html_table_data + "}";
                    arrayHtml.push(JSON.parse(html_table_data));
                    bRowStarted = true;
                    //console.log(html_table_data);
                });

                //$('#jsonData').val(arrayHtml)
                //console.log(arrayHtml);


                $.ajax({
                    type:"POST",
                    url: "{{route('hris.datalembur.store_multi')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        arrayHtml:arrayHtml,
                    },
                    dataType: 'json',
                    success: function(res){
                        text  = "Nomor Form Lembur: " + res;
                        message = "Data Berhasil Di Simpan";
                        type = "success";
                        swal({
                            title: message,
                            text: text,
                            type: type,
                            showCancelButton: true,
                            confirmButtonText: 'Tutup',
                            cancelButtonText: 'Tetap di halaman ini'
                        },function(isConfirm){
                            if(isConfirm) {
                                $(location).prop("href", "{{route('hris.datalembur.index')}}")
                            } else {
                                $('#smartwizard123').smartWizard("reset");
                            }
                        });
                    },
                    error: function(res){
                        notif({
                            msg: "<b>Oops!</b> Simpan data lembur gagal.",
                            type: "error",
                            position: "center"
                        });
                    }
                });

             });

        var btnCancel = $('<button id="btn-reset-wizard"></button>').text('Reset')
            .addClass('btn btn-danger')
            .on('click', function(){ $('#smartwizard123').smartWizard("reset"); });

        $('#smartwizard123').smartWizard({
                selected: 0,
                theme: 'dots',
                transitionEffect:'fade',
                showStepURLhash: false,
                keyNavigation: false,
                toolbarSettings: {
                                toolbarExtraButtons: [btnFinish, btnCancel]
                                },

        });

        $("#smartwizard123").on("leaveStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
            $("#daterange1").removeClass("is-invalid state-invalid");
            $("#selectEmployeeID").removeClass("is-invalid state-invalid");

            if(!$("#daterange1").val()) {
                notif({
                    msg: "<b>Warning:</b> Silakan diisi tanggal lemburnya.",
                    type: "warning"
                });

                $("#daterange1").addClass("is-invalid state-invalid");

                return false;
            }

            if($("#selectEmployeeID").val() == "") {
                notif({
                    msg: "<b>Warning:</b> Silakan pilih karyawannya.",
                    type: "warning"
                });

                return false;
            }

            if($("#jumlah_jam_lembur").val() >= 24) {
                notif({
                    msg: "<b>Warning:</b> Jumlah jam lembur lebih dari 24 jam !!!",
                    type: "warning"
                });
                $("#jumlah_jam_lembur").addClass("is-invalid state-invalid");

                return false;
            }

            if (stepNumber == 0) {
                var daterange1 = $("#daterange1").val();
                var tanggal_lembur = daterange1;
                var selectEmployeeID = $("#selectEmployeeID").val();
                var waktu_jam_lembur = $("#waktu_jam_lembur").val();
                var mulai_jam_lembur = $("#mulai_jam_lembur").val();
                var akhir_jam_lembur = $("#akhir_jam_lembur").val();
                var jumlah_jam_lembur = $("#jumlah_jam_lembur").val();
                var kode_perhitungan_lembur = $("#kode_perhitungan_lembur").text();
                var perhitungan_lembur = $("#perhitungan_lembur").val();
                var jumlah_jam_lembur_approved = $("#jumlah_jam_lembur_approved").val();
                var jumlah_jam_istirahat = $("#jumlah_jam_istirahat").val();
                var catatan_hrd = $("#catatan_hrd").val();
                var countSelectedEmp = $("#countSelectedEmp").val();
                $('#table-ajax-edit-lembur tbody>tr').empty();
                //console.log(selectEmployeeID[0]);

                var nomor_urut = 1;
                //console.log($("#selectEmployeeID").find('option:selected').text());

                $.ajax({
                    type:"POST",
                    url: "{{route('hris.datalembur.getEmployeeLembur')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        tanggal_lembur:tanggal_lembur,
                        selectEmployeeID:selectEmployeeID,
                    },
                    dataType: 'json',
                    success: function(res){
                        if(res){
                            for(i=0;i<res.length;i++) {
                                nomor_urut = i+1;
                                $htmlTable = '' +
                                '<tr class="text-center">' +
                                '    <td>' +
                                '       <div id="nomor_urut' + i + '" name="nomor_urut">' + nomor_urut + '</div>' +
                                '       <input id="uuid_master' + i + '" name="uuid_master" value="' + res[i].uuid + '" type="hidden">' +
                                '       <input id="shift_work_id' + i + '" name="shift_work_id" value="' + res[i].shift_work_id + '" type="hidden">' +
                                '       <input id="kode_hari' + i + '" name="kode_hari" value="' + res[i].kode_hari + '" type="hidden">' +
                                '       <input id="nama_hari' + i + '" name="nama_hari" value="' + res[i].nama_hari + '" type="hidden">' +
                                '       <input id="tanggal_berjalan' + i + '" name="tanggal_berjalan" value="' + res[i].tanggal_berjalan + '" type="hidden">' +
                                '       <input id="tanggal_absen' + i + '" name="tanggal_absen" value="' + res[i].tanggal_absen + '" type="hidden">' +
                                '       <input id="time_table_name' + i + '" name="time_table_name" value="' + res[i].time_table_name + '" type="hidden">' +
                                '       <input id="mulai_jam_kerja' + i + '" name="mulai_jam_kerja" value="' + res[i].mulai_jam_kerja + '" type="hidden">' +
                                '       <input id="akhir_jam_kerja' + i + '" name="akhir_jam_kerja" value="' + res[i].akhir_jam_kerja + '" type="hidden">' +
                                '       <input id="absen_masuk_kerja' + i + '" name="absen_masuk_kerja" value="' + res[i].absen_masuk_kerja + '" type="hidden">' +
                                '       <input id="absen_pulang_kerja' + i + '" name="absen_pulang_kerja" value="' + res[i].absen_pulang_kerja + '" type="hidden">' +
                                '       <input id="enroll_id' + i + '" name="enroll_id" value="' + res[i].enroll_id + '" type="hidden">' +
                                '       <input id="nik' + i + '" name="nik" value="' + res[i].nik + '" type="hidden">' +
                                '       <input id="employee_id' + i + '" name="employee_id" value="' + res[i].employee_id + '" type="hidden">' +
                                '       <input id="employee_name' + i + '" name="employee_name" value="' + res[i].employee_name + '" type="hidden">' +
                                '       <input id="site_nirwana_id' + i + '" name="site_nirwana_id" value="' + res[i].site_nirwana_id + '" type="hidden">' +
                                '       <input id="site_nirwana_name' + i + '" name="site_nirwana_name" value="' + res[i].site_nirwana_name + '" type="hidden">' +
                                '       <input id="department_id' + i + '" name="department_id" value="' + res[i].department_id + '" type="hidden">' +
                                '       <input id="department_name' + i + '" name="department_name" value="' + res[i].department_name + '" type="hidden">' +
                                '       <input id="sub_dept_id' + i + '" name="sub_dept_id" value="' + res[i].sub_dept_id + '" type="hidden">' +
                                '       <input id="sub_dept_name' + i + '" name="sub_dept_name" value="' + res[i].sub_dept_name + '" type="hidden">' +
                                '    </td>' +
                                '    <td><div id="tanggal_lembur_label_edit' + i + '" name="tanggal_lembur_label_edit">' + res[i].tanggal_berjalan + '</div></td>' +
                                '    <td><div id="nama_hari_edit' + i + '" name="nama_hari_edit">' + res[i].nama_hari + '</div></td>' +
                                '    <td><div id="nik_label_edit' + i + '" name="nik_label_edit">' + res[i].nik + '</div></td>' +
                                '    <td><div id="employee_name_label_edit' + i + '" name="employee_name_label_edit">' + res[i].employee_name + '</div></td>' +
                                '    <td><div id="absen_masuk_kerja' + i + '" name="absen_masuk_kerja">' + res[i].absen_masuk_kerja + '</div></td>' +
                                '    <td><div id="absen_pulang_kerja' + i + '" name="absen_pulang_kerja">' + res[i].absen_pulang_kerja + '</div></td>' +
                                '    <td class="w-70">' +
                                '    <div class="input-group">' +
                                '        <div class="input-group-prepend">' +
                                '            <div class="input-group-text">' +
                                '                <i class="fa fa-calendar tx-16 lh-0 op-6"></i>' +
                                '            </div>' +
                                '        </div>' +
                                '        <input class="form-control" id="waktu_jam_lembur_edit' + i + '" name="waktu_jam_lembur_edit" value="' + waktu_jam_lembur + '" onFocus="datetimerangepicker(\'waktu_jam_lembur_edit' + i + '\');"  onChange="swl_edit(\'waktu_jam_lembur_edit' + i + '\',\'mulai_jam_lembur_edit' + i + '\', \'akhir_jam_lembur_edit' + i + '\', \'jumlah_jam_lembur_edit' + i + '\', \'jumlah_jam_istirahat_edit' + i + '\');" type="text">' +
                                '        <input id="mulai_jam_lembur_edit' + i + '" name="mulai_jam_lembur_edit" value="' + mulai_jam_lembur + '" type="hidden">' +
                                '        <input id="akhir_jam_lembur_edit' + i + '" name="akhir_jam_lembur_edit" value="' + akhir_jam_lembur + '" type="hidden">' +
                                '    </div>' +
                                '    </td>' +
                                '    <td>' +
                                '        <div class="form-group">' +
                                '            <input class="form-control" id="jumlah_jam_istirahat_edit' + i + '" name="jumlah_jam_istirahat_edit" value="' + jumlah_jam_istirahat + '" type="text">' +
                                '        </div>' +
                                '    </td>' +
                                '    <td>' +
                                '        <div class="form-group">' +
                                '            <input class="form-control" id="jumlah_jam_lembur_edit' + i + '" name="jumlah_jam_lembur_edit" value="' + jumlah_jam_lembur + '" type="text">' +
                                '        </div>' +
                                '    </td>' +
                                '    <td class="w-80">' +
                                '        <div class="form-group">' +
                                '            <input class="form-control" id="catatan_hrd_edit' + i + '" name="catatan_hrd_edit" value="' + catatan_hrd + '" type="text">' +
                                '        </div>' +
                                '    </td>' +
                                '</tr>';
                                $("#table-ajax-edit-lembur tbody").append($htmlTable);
                            }

                        }
                    }
                });


                $('#btn-save-wizard').removeAttr('disabled');

            } else {
                $('#btn-save-wizard').attr('disabled','disabled');
            }

        });

        $('body').on('click', '#btn-refresh-page', function (event) {
            location.reload();
        });

        $('body').on('click', '#btn-get-employee', function (event) {
            $("#ajax-modal-pilih1").modal('show');    
            $('#datatable-ajax-modal').DataTable().clear();
            $('#datatable-ajax-modal').DataTable().destroy();
            $('#datatable-ajax-modal').empty();
            
            getemployee();
        });

        $('body').on('click', '#btn-get-subdept', function (event) {
            $("#ajax-modal-pilih2").modal('show');    
            $('#datatable-ajax-modal2').DataTable().clear();
            $('#datatable-ajax-modal2').DataTable().destroy();
            $('#datatable-ajax-modal2').empty();
            
            getsubdept();
        });

        function getemployee() {
            var table1 = $('#datatable-ajax-modal').DataTable({
                processing: true,
                serverSide: true,
                lengthChange: false,
                pageLength: 5,
                destroy: true,
                "ajax": {
                    "url": "{{ route('hris.datalembur.ajax_getemployee') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                },
                columns: [
                    {
                        title: 'NOMOR ABSEN',
                        data: 'enroll_id',
                        name: 'enroll_id'
                    },
                    {
                        title: 'NIK',
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        title: 'NAMA KARYAWAN',
                        data: 'employee_name',
                        name: 'employee_name'
                    },
                    {
                        title: 'BAGIAN',
                        data: 'sub_dept_name',
                        name: 'sub_dept_name'
                    },
                    {
                        title: 'STAFF',
                        data: 'status_staff',
                        name: 'status_staff'
                    },
                    {
                        title: 'AKTIF',
                        data: 'status_aktif',
                        name: 'status_aktif'
                    },
                    {
                        title: 'TANGGAL MASUK',
                        data: 'join_date',
                        name: 'join_date'
                    },
                    {
                        title: 'TANGGAL RESIGN',
                        data: 'tanggal_resign',
                        name: 'tanggal_resign'
                    },
                ],
                columnDefs: [
                    {
                        'visible': false,
                        'targets': []
                    }
                ],
                order: [
                    [2, 'asc']
                ],
                "createdRow": function (row, data, dataIndex) {
                    if (data['status_aktif'] == "TIDAK AKTIF") {
                            $(row).css('background', 'red');
                    }                    
                }
            });

            table1.draw();

            $('#datatable-ajax-modal tbody').on('click', 'tr', function () {
                var tr = $(this).closest('tr');
                var row = table1.row(tr);

                var data = row.data();

                $("#datatable-ajax-modal tbody tr").removeClass('bg-cyan');
                $(this).addClass('bg-cyan');


             });      
             
            $('#datatable-ajax-modal tbody').on('dblclick', 'tr', function () {
                var tr = $(this).closest('tr');
                var row = table1.row(tr);

                var data = row.data();

                if ($('#selectEmp').find("option[value='" + data['enroll_id'] + "']").length < 1) {
                    $("#selectEmp").append(new Option(data['employee_name'], data['enroll_id']));
                }

                $('#selectEmp').multipleSelect({
                    selectAll: true,
                    width: "100%",
                    filter: true,
                    sort: true,
                });

                $("#selectEmp").find("option[value='" + data['enroll_id'] +"'").attr("selected","selected");
                
            });      
             
        }

        function getsubdept() {
            var table1 = $('#datatable-ajax-modal2').DataTable({
                processing: true,
                serverSide: true,
                lengthChange: false,
                pageLength: 5,
                destroy: true,
                "ajax": {
                    "url": "{{ route('hris.datalembur.ajax_getsubdept') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                },
                columns: [
                    {
                        title: 'DIVISI',
                        data: 'site_nirwana_name',
                        name: 'site_nirwana_name'
                    },
                    {
                        title: 'DEPARTMENT',
                        data: 'department_name',
                        name: 'department_name'
                    },
                    {
                        title: 'BAGIAN',
                        data: 'sub_dept_name',
                        name: 'sub_dept_name'
                    },
                ],
                columnDefs: [
                    {
                        'visible': false,
                        'targets': []
                    }
                ],
                order: [
                    [0, 'asc'],[1, 'asc'],[2, 'asc']
                ]
            });

            table1.draw();

            $('#datatable-ajax-modal2 tbody').on('click', 'tr', function () {
                var tr = $(this).closest('tr');
                var row = table1.row(tr);

                var data = row.data();

                $("#datatable-ajax-modal2 tbody tr").removeClass('bg-cyan');
                $(this).addClass('bg-cyan');


             });      
             
            $('#datatable-ajax-modal2 tbody').on('dblclick', 'tr', function () {
                var tr = $(this).closest('tr');
                var row = table1.row(tr);

                var data = row.data();

                if ($('#selectSub').find("option[value='" + data['sub_dept_id'] + "']").length < 1) {
                    $("#selectSub").append(new Option(data['sub_dept_name'], data['sub_dept_id']));
                }

                $('#selectSub').multipleSelect({
                    selectAll: true,
                    width: "100%",
                    filter: true,
                    sort: true,
                });

                $("#selectSub").find("option[value='" + data['sub_dept_id'] +"'").attr("selected","selected");
                
            });      
             
        }

        function count(str, find) {
            return (str.split(find)).length - 1;
        }

        $('body').on('click', '#btn-pilih', function (event) {
            var selectEmp = $('#selectEmp').val();
            var selectEmployee = $('#selectEmployee').val(selectEmp);
            
            $('#jumlahEmp').val('Jumlah Karyawan : ' + selectEmp.length);

            $('#selectSubDept').val('');
            $('#jumlahSubDept').val('');
        });

        $('body').on('click', '#btn-pilih2', function (event) {
            var selectSub = $('#selectSub').val();
            var selectSubDept = $('#selectSubDept').val(selectSub);
            
            $('#jumlahSubDept').val('Jumlah Bagian : ' + selectSub.length);

            $('#selectEmployee').val('');
            $('#jumlahEmp').val('');
        });

        $('body').on('click', '#btn-save', function (event) {

            var waktulembur = $('#waktu_jam_lembur').val();
            var mulailembur = waktulembur.split(' - ');
            var tgl = mulailembur[0].split(' ');
            var tanggal = defaultDate(tgl[0]);
                        
            $.ajax({
                type:"POST",
                url: "{{route('hris.dataclosingpayroll.ajax_getclosing')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    tanggal:tanggal,
                },
                dataType: 'json',
                success: function(res){

                    if(res["ada"]) {
                        notif({
                            type: res["status"],
                            msg: res["message"],
                            position: "center",
                            width: 800,
                            height: 120,
                            opacity: 0.6,
                            autohide: false
                        });
                    } else {            
                        var waktu_lembur = $('#waktu_jam_lembur').val();

                        var splitWaktuLembur = waktu_lembur.split(" - ");
                        var mulai_jam_lembur = splitWaktuLembur[0];
                        var akhir_jam_lembur = splitWaktuLembur[1];

                        var jumlah_jam_lembur = $('#jumlah_jam_lembur').val();
                        var jumlah_jam_istirahat = $('#jumlah_jam_istirahat').val();
                        var selectEmployee = $('#selectEmployee').val();
                        var selectSubDept = $('#selectSubDept').val();
                        var catatan = $('#catatan').val();

                        $.ajax({
                            type:"POST",
                            url: "{{route('hris.datalembur.replace')}}",
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            data: {
                                mulai_jam_lembur:mulai_jam_lembur,
                                akhir_jam_lembur:akhir_jam_lembur,
                                jumlah_jam_lembur:jumlah_jam_lembur,
                                jumlah_jam_istirahat:jumlah_jam_istirahat,
                                selectEmployee:selectEmployee,
                                selectSubDept:selectSubDept,
                                catatan:catatan,
                            },
                            dataType: 'json',
                            success: function(res){
                                text  = "Nomor Form Lembur: " + res;
                                message = "Data Berhasil Di Simpan";
                                type = "success";
                                swal({
                                    title: message,
                                    text: text,
                                    type: type,
                                    showCancelButton: true,
                                    confirmButtonText: 'Tutup',
                                    cancelButtonText: 'Tetap di halaman ini'
                                },function(isConfirm){
                                    if(isConfirm) {
                                        $(location).prop("href", "{{route('hris.datalembur.index')}}")
                                    } else {
                                        location.reload();
                                    }
                                });
                            },
                            error: function(res){
                                notif({
                                    msg: "<b>Oops!</b> Simpan data lembur gagal.",
                                    type: "error",
                                    position: "center"
                                });
                            }
                        });

                    }

                },
                error: function(res){
                                
                }
            });           

        });

    </script>
@endsection
