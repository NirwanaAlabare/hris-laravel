@extends('admin.adminlayouts.adminlayout')

@section('head')
    <!-- Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet" />

    <!-- Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />

	<!-- Notifications  css -->
	<link href="{{URL::asset('assets/plugins/notify-growl/css/jquery.growl.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/notify-growl/css/notifIt.css')}}" rel="stylesheet" />

    <!-- Time picker css-->
    <link href="{{URL::asset('assets/plugins/time-picker/jquery.timepicker.css')}}" rel="stylesheet" />

    <!-- Date Picker css-->
    <link href="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.css')}}" rel="stylesheet" />

    <!--Mutipleselect css-->
    <link rel="stylesheet" href="{{URL::asset('assets/plugins/multipleselect/multiple-select.css')}}">

	<!---Sweetalert Css-->
	<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

    <style>
        .table-responsive{
            height:450px;
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
    <div class="page-header shadow">
        <ol class="breadcrumb breadcrumb-arrow mt-0">
            <li><a href="#">ABSEN KARYAWAN</a></li>
            <li class="active"><span>DATA GAGAL ABSEN</span></li>
        </ol>
        <div class="ml-auto">
            <div class="input-group">
                <a href="#" id="btn-refresh-data" class="btn btn-secondary text-white mr-1 p-0 btn-icon" data-toggle="tooltip"
                    title="" data-placement="bottom" data-original-title="refresh data">
                    <span>
                        <i class="fa fa-refresh"></i>
                    </span>
                </a>
                <a href="#" id="btn-refresh-data-log" class="btn btn-secondary text-white mr-1 p-0 btn-icon" data-toggle="tooltip"
                    title="" data-placement="bottom" data-original-title="refresh data log">
                    <span>
                        <i class="fa fa-refresh"></i>
                    </span>
                </a>
                <a href="{{ url('lockscreen') }}" class="btn btn-primary text-white mr-1 p-0 btn-sm" data-toggle="tooltip"
                    title="" data-placement="bottom" data-original-title="lock">
                    <span>
                        <i class="fa fa-lock"></i>
                    </span>
                </a>
            </div>
        </div>
    </div>
    <!-- End page-header -->

    <!-- BEGIN FORM-->
    {!! Form::open(['route' => 'hris.gagalabsen.ajax_exportexcel', 'id' => 'formExport', 'name' => 'formExport','method'=>'post']) !!}

    @csrf
    <input id="selDepVal" name="selDepVal" type="hidden">
    <div class="card shadow">
        <div class="card-header text-white bg-gradient-primary p-2">
            <div class="card-title">FILTER GAGAL ABSEN KARYAWAN</div>
            <div class="card-options ">
                <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">TANGGAL ABSEN : </label>
                        <input id="daterange1" name="daterange1" type="text" class="form-control" placeholder="Tanggal Absen" maxlength="50" size="50">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label">CARI DATA : </label>
                        <input id="searchData" name="searchData" class="form-control" type="text">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-light m-0 p-1">
            <div class="text-white">
                <button type="submit" id="btn-exportexcel" class="btn btn-app btn-primary mr-0 mt-0 mb-0" data-toggle="tooltip" title="Export Data ke File"><i class="ion-ios7-download"></i> EXPORT</button>
                <a href="javascript:void(0)" id="btn-caridata" class="btn btn-app btn-primary mr-0 mt-0 mb-0" data-toggle="tooltip" title="Cari Data"><i class="ion-search"></i> CARI</a>
            </div>
        </div>    
    </div>
    {{-- </form> --}}
    {!! Form::close() !!}
    <!-- END FORM-->

    <!-- Begin Form Edit Absen Karyawan -->
    <div id="data-gagal-absen" class="card shadow" id="datatable-data-karyawan">
        <div class="card-header text-white bg-gradient-primary p-2">
            <div class="card-title">DATA GAGAL ABSEN</div>
            <div class="card-options ">
                <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatable-ajax-crud"
                    class="table table-sm table-striped table-hover table-bordered w-100">
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
        <div class="card-footer bg-primary br-br-7 br-bl-7">
            <div class="text-white"></div>
        </div>
    </div>

    <div id="data-log-gagal-absen" class="card shadow" id="datatable-log-gagal-absen">
        <div class="card-header text-white bg-gradient-primary p-2">
            <div class="card-title">DATA LOG GAGAL ABSEN</div>
            <div class="card-options ">
                <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatable-ajax-log-gagal-absen-crud"
                    class="table table-sm table-striped display table-hover w-100">
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
        <div class="card-footer bg-primary br-br-7 br-bl-7">
            <div class="text-white"></div>
        </div>
    </div>

    <!-- boostrap show department model -->
    <div class="modal fade" id="ajax-gagalabsen-model-edit" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <!-- BEGIN FORM-->
                {!! Form::open(['url' => 'javascript:void(0)', 'id' => 'formSaveChanges1', 'name' => 'formSaveChanges1']) !!}
                <input id="uuid_master" type="hidden">
                <input id="enroll_id" type="hidden">
                <input id="employee_id" type="hidden">
                <input id="nik" type="hidden">
                <input id="kode_hari" type="hidden">
                <input id="nama_hari" type="hidden">
                <input id="employee_name" type="hidden">
                <input id="shift_work_id" type="hidden">
                <input id="jadwal_masuk_kerja" type="hidden">
                <input id="jadwal_pulang_kerja" type="hidden">
                <input id="department_id" type="hidden">
                <input id="department_name" type="hidden">
                <input id="sub_dept_id" type="hidden">
                <input id="sub_dept_name" type="hidden">
                <input id="status_absen_old" type="hidden">
                <input id="absen_in_old" type="hidden">
                <input id="absen_out_old" type="hidden">
                <input id="absen_alasan_old" type="hidden">
                <div class="modal-header bg-primary p-2">
                    <h4 class="modal-title" id="ajaxGagalAbsenModel"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                        <i class="fa fa-remove text-white"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="control-label">NOMOR FORM GAGAL ABSEN :</label>
                                <input readonly class="form-control" id="no_form" name="no_form" placeholder="Nomor Form Gagal Absen" type="text">
                            </div>
                            <div class="form-group">
                                <label for="name" class="control-label">TANGGAL ABSEN :</label>
                                <div id="tanggal_berjalan_label"></div>
                                <input id="tanggal_absen" type="hidden">
                            </div>
                            <div class="form-group">
                                <label for="name" class="control-label">JADWAL KERJA (IN/OUT) :</label>
                                <div id="jadwal_jam_kerja"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="control-label">STATUS ABSEN :</label>
                                <select id="status_absen" name="status_absen" class="form-control">
                                    <option value="L">LIBUR</option>
                                    <option value="KERJA">KERJA</option>
                                    <option value="M">MANGKIR</option>
                                    <option value="TL">TIDAK LENGKAP</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="name" class="control-label">ABSEN IN :</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-clock-o tx-16 lh-0 op-6"></i>
                                        </div>
                                    </div><!-- input-group-prepend -->
                                    <input class="form-control" id="absen_masuk_kerja" name="absen_masuk_kerja" placeholder="--:--" maxlength="5" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="control-label">ABSEN OUT :</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-clock-o tx-16 lh-0 op-6"></i>
                                        </div>
                                    </div><!-- input-group-prepend -->
                                    <input class="form-control" id="absen_pulang_kerja" name="absen_pulang_kerja" placeholder="--:--" maxlength="5" type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name" class="control-label">ALASAN GAGAL ABSEN :</label>
                                <textarea class="form-control" id="absen_alasan" name="absen_alasan" rows="2" placeholder="tulis alasan absen di sini ..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-primary pt-0 pb-1 pr-1">
                    <div class="btn-list">
                        <button type="button" id="btn-save-changes" class="btn btn-secondary btn-app"><i class="fa fa-save"></i> SIMPAN</button>
                        <button type="button" id="btn-tutup" class="btn btn-warning btn-app" data-dismiss="modal"><i class="fa fa-close"></i> TUTUP</button>
                    </div>
                </div>
                {{-- </form> --}}
                {!! Form::close() !!}
                <!-- END FORM-->
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

    <!--Select2 js -->
    <script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>

    <!--Time Counter js-->
    <script src="{{URL::asset('assets/plugins/counters/jquery.missofis-countdown.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/counters/counter.js')}}"></script>

    <!-- Timepicker js -->
    <script src="{{URL::asset('assets/plugins/time-picker/jquery.timepicker.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/time-picker/toggles.min.js')}}"></script>

    <!-- Datepicker js -->
    <script src="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/spectrum-date-picker/jquery-ui.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/input-mask/jquery.maskedinput.js')}}"></script>

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

    <!-- Popover js -->
    <script src="{{URL::asset('assets/js/popover.js')}}"></script>

    <!-- Notifications js -->
    <script src="{{URL::asset('assets/plugins/notify-growl/js/rainbow.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/notify-growl/js/sample.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/notify-growl/js/jquery.growl.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/notify-growl/js/notifIt.js')}}"></script>

    <!--MutipleSelect js-->
    <script src="{{URL::asset('assets/plugins/multipleselect/multiple-select.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/multipleselect/multi-select.js')}}"></script>

    <!-- Sweet alert js-->
    <script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

    <script type="text/javascript">

        $('#progress-show-1').hide();

        $("#formExport").on("keypress", function (event) {

            var keyPressed = event.keyCode || event.which;
            if (keyPressed === 13) {

                event.preventDefault();
                return false;
            }
        });

        $("#formExport").on('keypress',function(e) {
            if(e.which == 13) {
                $('#btn-caridata').click();
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#btn-refresh-data', function (event) {
            $("#datatable-ajax-crud").DataTable().ajax.reload();
            notif({
                msg: "<b>Success:</b> Data sudah di refresh.",
                type: "success"
            });
        });

        $('body').on('click', '#btn-refresh-data-log', function (event) {
            $("#datatable-ajax-log-gagal-absen-crud").DataTable().ajax.reload();
            notif({
                msg: "<b>Success:</b> Data Log sudah di refresh.",
                type: "success"
            });
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

        $('body').on('click', '#btn-caridata', function (event) {
            $("#data-gagal-absen").show('slow');
            $("#data-log-gagal-absen").show('slow');

            $('#datatable-ajax-crud').DataTable().clear();
            $('#datatable-ajax-crud').DataTable().destroy();
            $('#datatable-ajax-crud').empty();

            $('#datatable-ajax-log-gagal-absen-crud').DataTable().clear();
            $('#datatable-ajax-log-gagal-absen-crud').DataTable().destroy();
            $('#datatable-ajax-log-gagal-absen-crud').empty();

            var daterange1 = $('#daterange1').val();
            var searchData = $('#searchData').val();

            var table1 = $('#datatable-ajax-crud').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                destroy: true,
                "language": {
                    processing: '<center><div class="dimmer active"><div class="lds-hourglass p-0 m-0"></div></div> Mohon untuk menunggu...</center> '},
                "ajax": {
                    "url": "{{ route('hris.gagalabsen.ajax_gagalabsen') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                    "data": {
                        daterange1:daterange1,
                        searchData:searchData,
                    }
                },
                columns: [
                    {
                        title: 'UUID',
                        data: 'uuid',
                        name: 'uuid'
                    },
                    {
                        title: 'Nomor Form Perubahan Absen',
                        data: 'nomor_form_perubahan_absen',
                        name: 'nomor_form_perubahan_absen'
                    },
                    {
                        title: 'Tanggal',
                        data: 'tanggal_berjalan_format',
                        name: 'tanggal_berjalan_format'
                    },
                    {
                        title: 'Kode Hari',
                        data: 'kode_hari',
                        name: 'kode_hari'
                    },
                    {
                        title: 'Nama Hari',
                        data: 'nama_hari',
                        name: 'nama_hari'
                    },
                    {
                        title: 'Nomor Absen',
                        data: 'enroll_id',
                        name: 'enroll_id'
                    },
                    {
                        title: 'Employee ID',
                        data: 'employee_id',
                        name: 'employee_id'
                    },
                    {
                        title: 'NIK',
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        title: 'Nama Karyawan',
                        data: 'employee_name',
                        name: 'employee_name'
                    },
                    {
                        title: 'Jadwal (IN)',
                        data: 'mulai_jam_kerja',
                        name: 'mulai_jam_kerja'
                    },
                    {
                        title: 'Jadwal (OUT)',
                        data: 'akhir_jam_kerja',
                        name: 'akhir_jam_kerja'
                    },
                    {
                        title: 'Absen (IN)',
                        data: 'absen_masuk_kerja',
                        name: 'absen_masuk_kerja'
                    },
                    {
                        title: 'Absen (OUT)',
                        data: 'absen_pulang_kerja',
                        name: 'absen_pulang_kerja'
                    },
                    {
                        title: 'Status Absen',
                        data: 'status_absen',
                        name: 'status_absen'
                    },
                ],
                columnDefs: [
                    {
                        'visible': false,
                        'targets': [0,1,3,6]
                    },
                    {
                        orderable: false,
                        targets: [2,4,5,7,8,9,10,11,12,13]
                    },
                    {
                        className: "w-5 text-center text-nowrap",
                        targets: [2,4,5,9,10,11,12,13]
                    },
                    {
                        className: "w-50 text-nowrap",
                        targets: [7,8]
                    }
                ],
                order: [],
                "createdRow": function (row, data, dataIndex) {
                    if ((data['kode_hari'] == "5") || (data['kode_hari'] == "6")) {
                        $(row).css('background', 'yellow');
                    }
                    if (data['holiday_name']) {
                        $(row).css('background', 'yellow');
                    }
                }
            });

            table1.draw();

            $('#datatable-ajax-crud tbody').on('click', 'tr', function () {
                var tr = $(this).closest('tr');
                var row = table1.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(format(row.data())).show();
                    tr.addClass('shown');
                }

                $("#datatable-ajax-crud tbody tr").removeClass('bg-cyan');
                $(this).addClass('bg-cyan');
             });

             $('#datatable-ajax-crud tbody').on('dblclick', 'tr', function () {
                var data = $("#datatable-ajax-crud").DataTable().row(this).data();

                var tanggal = data['tanggal_berjalan'];
                
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
                            var editData = data['uuid'];
                
                            $("#absen_masuk_kerja").removeClass("is-invalid state-invalid");
                            $("#absen_pulang_kerja").removeClass("is-invalid state-invalid");
                            $("#absen_alasan").removeClass("is-invalid state-invalid");
                
                            $.ajax({
                                "type":"POST",
                                "url": "{{route('hris.gagalabsen.form_gagalabsen')}}",
                                "data": { editData: editData },
                                "dataType": 'json',
                                "headers": {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                "success": function(res){
                                    $('#ajaxGagalAbsenModel').html("<b>FORM GAGAL ABSEN ["+res.enroll_id+" - "+res.nik+" - "+res.employee_name+"]</b>");
                                    $('#ajax-gagalabsen-model-edit').modal('show');
                                    $('#uuid_master').val(res.uuid);
                                    $('#nik_label').text(res.nik);
                                    $('#nik').val(res.nik);
                                    $('#enroll_id_label').text(res.enroll_id);
                                    $('#enroll_id').val(res.enroll_id);
                                    $('#employee_id').val(res.employee_id);
                                    $('#employee_name_label').text(res.employee_name);
                                    $('#employee_name').val(res.employee_name);
                                    $('#department_name').text(res.department_name);
                                    $('#sub_dept_name').text(res.sub_dept_name);
                                    $('#tanggal_berjalan_label').text(res.tanggal_berjalan);
                                    $('#tanggal_absen').val(res.tanggal_berjalan);
                                    $('#kode_hari').val(res.kode_hari);
                                    $('#nama_hari').val(res.nama_hari);
                                    $('#jadwal_jam_kerja').text(res.jadwal_jam_kerja);
                                    $('#jadwal_masuk_kerja').val(res.mulai_jam_kerja);
                                    $('#jadwal_pulang_kerja').val(res.akhir_jam_kerja);
                                    $('#absen_masuk_kerja').val(res.absen_masuk_kerja);
                                    $('#absen_pulang_kerja').val(res.absen_pulang_kerja);
                                    $('#department_id').val(res.department_id);
                                    $('#department_name').val(res.department_name);
                                    $('#sub_dept_id').val(res.sub_dept_id);
                                    $('#sub_dept_name').val(res.sub_dept_name);
                                    $('#absen_alasan').val(res.absen_alasan);
                                    $('#absen_in_old').val(res.absen_masuk_kerja);
                                    $('#absen_out_old').val(res.absen_pulang_kerja);
                                    $('#absen_alasan_old').val(res.absen_alasan);
                
                                    if((res.absen_masuk_kerja !== res.absen_pulang_kerja) && ((res.absen_masuk_kerja !== null) || (res.absen_pulang_kerja !== null)))
                                    {
                                        $('#status_absen').val(res.status_absen);
                                        $('#status_absen_old').val(res.status_absen);
                                        //alert("OK");
                                    } else {
                                        $('#status_absen').val("M");
                                        $('#status_absen_old').val("M");
                                    }
                
                
                                    $('#no_form').val(res.nomor_form_perubahan_absen);
                                }
                            });
    
                        }

                    },
                    error: function(res){
                                    
                    }
                });           
    
             });
    
            var table2 = $('#datatable-ajax-log-gagal-absen-crud').DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                    "url": "{{ route('hris.gagalabsen.ajax_loggagalabsen') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data"
                },
                columns: [
                    {
                        title: 'UUID',
                        data: 'uuid',
                        name: 'uuid'
                    },
                    {
                        title: 'Nomor Form Gagal Absen',
                        data: 'nomor_form_gagal_absen',
                        name: 'nomor_form_gagal_absen'
                    },
                    {
                        title: 'Tanggal Absen',
                        data: 'tanggal_absen',
                        name: 'tanggal_absen'
                    },
                    {
                        title: 'Kode Hari',
                        data: 'kode_hari',
                        name: 'kode_hari'
                    },
                    {
                        title: 'Nama Hari',
                        data: 'nama_hari',
                        name: 'nama_hari'
                    },
                    {
                        title: 'Nomor Absen',
                        data: 'enroll_id',
                        name: 'enroll_id'
                    },
                    {
                        title: 'Employee ID',
                        data: 'employee_id',
                        name: 'employee_id'
                    },
                    {
                        title: 'NIK',
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        title: 'Nama Karyawan',
                        data: 'employee_name',
                        name: 'employee_name'
                    },
                    {
                        title: 'Status Absen',
                        data: 'status_absen',
                        name: 'status_absen'
                    },
                    {
                        title: 'Update At',
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                ],
                columnDefs: [
                    {
                        'visible': false,
                        'targets': [0,3,6]
                    }
                ],
                order: [
                    [10, 'desc']
                ],
                "createdRow": function (row, data, dataIndex) {
                    if ((data['kode_hari'] == "5") || (data['kode_hari'] == "6")) {
                        $(row).css('background', 'yellow');
                    }
                },
            });

            $('#datatable-ajax-log-gagal-absen-crud tbody').on('click', 'tr', function () {
                var tr = $(this).closest('tr');
                var row = table2.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(format(row.data())).show();
                    tr.addClass('shown');
                }

                $("#datatable-ajax-log-gagal-absen-crud tbody tr").removeClass('bg-cyan');
                $(this).addClass('bg-cyan');
             });

             table2.draw();

        });

        function format(d) {
            // `d` is the original data object for the row
            return (

                '<div class="expanel expanel-success">' +
                    '<div class="expanel-heading">' +
                        '<h4 class="expanel-title"><i class="fa fa-database"></i> DETAIL GAGAL ABSEN</h4>' +
                    '</div>' +
                    '<div class="expanel-body">' +
                        '<table cellpadding="5" cellspacing="0" border="0" width="100%">' +
                            '<tr>' +
                                '<td>UUID Master</td>' +
                                '<td>:</td>' +
                                '<td>' + d.uuid_master + '</td>' +
                                '<td></td>' +
                                '<td>Operator</td>' +
                                '<td>:</td>' +
                                '<td>' + d.operator + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td>Status Absen</td>' +
                                '<td>:</td>' +
                                '<td>' + d.status_absen + '</td>' +
                                '<td></td>' +
                                '<td>Status Absen (Sebelum Di Ubah)</td>' +
                                '<td>:</td>' +
                                '<td>' + d.status_absen_old + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td>Absen IN</td>' +
                                '<td>:</td>' +
                                '<td>' + d.absen_in + '</td>' +
                                '<td></td>' +
                                '<td>Absen IN (Sebelum Di Ubah)</td>' +
                                '<td>:</td>' +
                                '<td>' + d.absen_in_old + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td>Absen OUT</td>' +
                                '<td>:</td>' +
                                '<td>' + d.absen_out + '</td>' +
                                '<td></td>' +
                                '<td>Absen OUT (Sebelum Di Ubah)</td>' +
                                '<td>:</td>' +
                                '<td>' + d.absen_out_old + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td>Alasan</td>' +
                                '<td>:</td>' +
                                '<td>' + d.absen_alasan + '</td>' +
                                '<td></td>' +
                                '<td>Alasan (Sebelum Di Ubah)</td>' +
                                '<td>:</td>' +
                                '<td>' + d.absen_alasan + '</td>' +
                            '</tr>' +
                        '</table>' +
                    '</div>' +
                '</div>'
            );
        }

        $(document).ready(function() {
            $("#data-gagal-absen").hide();
            $("#data-log-gagal-absen").hide();
        });

        $('#datatable-ajax-crud tbody').on('click', 'tr', function () {
            $("#datatable-ajax-crud tbody tr").removeClass('bg-cyan');
            $(this).addClass('bg-cyan');
         });

        $('#absen_masuk_kerja').on('change', function () {

            $absen_masuk_kerja = $('#absen_masuk_kerja').val();
            $absen_pulang_kerja = $('#absen_pulang_kerja').val();

            if(($absen_masuk_kerja !== $absen_pulang_kerja) && (($absen_masuk_kerja !== null) && ($absen_pulang_kerja !== null) && ($absen_masuk_kerja !== "") && ($absen_pulang_kerja !== "")))
            {
                $('#status_absen').val("KERJA");
                $('#status_absen_old').val("KERJA");
            } else {
                $('#status_absen').val("M");
                $('#status_absen_old').val("M");
            }
        });

        $('#absen_pulang_kerja').on('change', function () {
            $absen_masuk_kerja = $('#absen_masuk_kerja').val();
            $absen_pulang_kerja = $('#absen_pulang_kerja').val();

            if(($absen_masuk_kerja !== $absen_pulang_kerja) && (($absen_masuk_kerja !== null) && ($absen_pulang_kerja !== null) && ($absen_masuk_kerja !== "") && ($absen_pulang_kerja !== "")))
            {
                $('#status_absen').val("KERJA");
                $('#status_absen_old').val("KERJA");
            } else {
                $('#status_absen').val("M");
                $('#status_absen_old').val("M");
            }
        });

         $('body').on('click', '#btn-save-changes', function (event) {
            var uuid_master = $("#uuid_master").val();
            var tanggal_absen = $("#tanggal_absen").val();
            var kode_hari = $("#kode_hari").val();
            var nama_hari = $("#nama_hari").val();
            var no_form = $("#no_form").val();
            var nik = $("#nik").val();
            var enroll_id = $("#enroll_id").val();
            var employee_id = $("#employee_id").val();
            var employee_name = $("#employee_name").val();
            var jadwal_masuk_kerja = $("#jadwal_masuk_kerja").val();
            var jadwal_pulang_kerja = $("#jadwal_pulang_kerja").val();
            var department_id = $("#department_id").val();
            var department_name = $("#department_name").val();
            var sub_dept_id = $("#sub_dept_id").val();
            var sub_dept_name = $("#sub_dept_name").val();
            var shift_work_id = $("#shift_work_id").val();
            var status_absen = $("#status_absen").val();
            var absen_masuk_kerja = $("#absen_masuk_kerja").val();
            var absen_pulang_kerja = $("#absen_pulang_kerja").val();
            var absen_alasan = $("#absen_alasan").val();
            var status_absen_old = $("#status_absen_old").val();
            var absen_in_old = $("#absen_in_old").val();
            var absen_out_old = $("#absen_out_old").val();
            var absen_alasan_old = $("#absen_alasan_old").val();

            $("#no_form").removeClass("is-invalid state-invalid");
            $("#status_absen").removeClass("is-invalid state-invalid");
            $("#absen_masuk_kerja").removeClass("is-invalid state-invalid");
            $("#absen_pulang_kerja").removeClass("is-invalid state-invalid");

            if(!status_absen) {
                $("#status_absen").addClass("is-invalid state-invalid");
                notif({
                    msg: "<b>Warning:</b> Silakan di isi Status Absen nya.",
                    type: "warning"
                });
                return false;
            } else if(!absen_masuk_kerja) {
                $("#absen_masuk_kerja").addClass("is-invalid state-invalid");
                notif({
                    msg: "<b>Warning:</b> Silakan di isi Absen IN nya.",
                    type: "warning"
                });
                return false;
            } 
            // else if(!absen_pulang_kerja) {
            //     $("#absen_pulang_kerja").addClass("is-invalid state-invalid");
            //     notif({
            //         msg: "<b>Warning:</b> Silakan di isi Absen OUT nya.",
            //         type: "warning"
            //     });
            //     return false;
            // }
            $('#btn-save-changes').addClass("btn-loading");
            $("#btn-save-changes").attr("disabled", true);

            $.ajax({
                type:"POST",
                url: "{{route('hris.logdatagagalabsen.store')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    uuid_master:uuid_master,
                    tanggal_absen:tanggal_absen,
                    kode_hari:kode_hari,
                    nama_hari:nama_hari,
                    no_form:no_form,
                    enroll_id:enroll_id,
                    nik:nik,
                    employee_id:employee_id,
                    employee_name:employee_name,
                    status_absen:status_absen,
                    jadwal_masuk_kerja:jadwal_masuk_kerja,
                    jadwal_pulang_kerja:jadwal_pulang_kerja,
                    department_id:department_id,
                    department_name:department_name,
                    sub_dept_id:sub_dept_id,
                    sub_dept_name:sub_dept_name,
                    shift_work_id:shift_work_id,
                    absen_masuk_kerja:absen_masuk_kerja,
                    absen_pulang_kerja:absen_pulang_kerja,
                    absen_alasan:absen_alasan,
                    status_absen_old:status_absen_old,
                    absen_in_old:absen_in_old,
                    absen_out_old:absen_out_old,
                    absen_alasan_old:absen_alasan_old,
                },
                dataType: 'json',
                success: function(res){ console.log(res);
                    $.ajax({
                        type:"POST",
                        url: "{{route('hris.gagalabsen.update')}}",
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        data: {
                            uuid:uuid_master,
                            tanggal_absen:tanggal_absen,
                            status_absen:status_absen,
                            status_absen_old:status_absen_old,
                            absen_masuk_kerja:absen_masuk_kerja,
                            absen_pulang_kerja:absen_pulang_kerja,
                            absen_alasan:absen_alasan,
                            nomor_form_perubahan_absen:res,
                        },
                        dataType: 'json',
                        success: function(res){
                            $('#btn-save-changes').removeClass("btn-loading");
                            $("#btn-save-changes").html('Save all changes');
                            $("#btn-save-changes"). attr("disabled", false);


                            notif({
                                msg: "<b>Success:</b> Data berhasil di update.",
                                type: "success"
                            });

                            $("#datatable-ajax-crud").DataTable().ajax.reload();
                            $("#ajax-gagalabsen-model-edit").modal('hide');
                        },
                        error: function(res){
                            $('#btn-save-changes').removeClass("btn-loading");
                            $("#btn-save-changes").html('Save all changes');
                            $("#btn-save-changes"). attr("disabled", false);

                            notif({
                                msg: "<b>Oops!</b> An Error Occurred (Update Master)",
                                type: "error",
                                position: "center"
                            });
                        }
                    });

                },
                error: function(res){
                    $('#btn-save-changes').removeClass("btn-loading");
                    $("#btn-save-changes").html('Save all changes');
                    $("#btn-save-changes"). attr("disabled", false);

                    notif({
                        msg: "<b>Oops!</b> An Error Occurred (Create Log Data)",
                        type: "error",
                        position: "center"
                    });
                }
            });

        });

        $("#check-all-karyawan").attr('disabled','disabled');

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

        $('body').on('change', '#selectDepartment', function () {
            var department_id = $('#selectDepartment').val();
            $('#selDepVal').val($('#selectDepartment').val());

            $("#check-all-karyawan").removeAttr("disabled");
            if ($("#selectEmployeeID option:selected").length == 0) {
                $("#selectEmployeeID").empty();
                $("#selectEmployeeID").val(null).trigger("change");
                $("#check-all-karyawan").prop("checked",false);
            }

            if (department_id == "") {
                $("#selectPosisiName").attr("disabled",false);
                $("#selectEmployeeID").empty();
                $("#selectEmployeeID").val(null).trigger("change");
            } else {
                $("#selectPosisiName").attr("disabled",true);
            }

            if(department_id){
                $.ajax({
                    type:"POST",
                    url: "{{route('hris.mdabsenhadir.ajax_getemployeselectdeptid')}}",
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

        $('body').on('change', '#selectPosisiName', function () {
            selectPosisiName = $('#selectPosisiName').val();

            $("#check-all-karyawan").removeAttr("disabled");
            if ($("#selectEmployeeID option:selected").length == 0) {
                $("#selectEmployeeID").empty();
                $("#selectEmployeeID").val(null).trigger("change");
                $("#check-all-karyawan").prop("checked",false);
            }

            $("#selectEmployeeID").empty();
            $("#selectEmployeeID").val(null).trigger("change");
            //$("#check-all-karyawan").removeAttr("disabled");
            if (selectPosisiName == "") {
                $("#selectDepartment").attr("disabled",false);
            } else {
                $("#selectDepartment").attr("disabled",true);
            }

            $.ajax({
                type:"POST",
                url: "{{route('hris.mdabsenhadir.ajax_getemployeselectposisi')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    selectPosisiName:selectPosisiName,
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
                    //$("#selectPosisiName").text()
                }
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
            $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        })

        $('body').on('click', '#btn-exportexcel', function (event) {
            notif({
                msg: "<b>Info:</b> Data sedang di proses, mohon menunggu",
                type: "info"
            });
        });

        function replaceBadInputs(val) {
            // Replace impossible inputs as the appear
            val = val.replace(/[^\dh:]/, "");
            val = val.replace(/^[^0-2]/, "");
            val = val.replace(/^([2-9])[4-9]/, "$1");
            val = val.replace(/^\d[:h]/, "");
            val = val.replace(/^([01][0-9])[^:h]/, "$1");
            val = val.replace(/^(2[0-3])[^:h]/, "$1");      
            val = val.replace(/^(\d{2}[:h])[^0-5]/, "$1");
            val = val.replace(/^(\d{2}h)./, "$1");      
            val = val.replace(/^(\d{2}:[0-5])[^0-9]/, "$1");
            val = val.replace(/^(\d{2}:\d[0-9])./, "$1");
            return val;
        }
        
        // Apply input rules as the user types or pastes input
        $('#absen_masuk_kerja').keyup(function(){
            var val = this.value;
            var lastLength;
            do {
                // Loop over the input to apply rules repeately to pasted inputs
                lastLength = val.length;
                val = replaceBadInputs(val);
            } while(val.length > 0 && lastLength !== val.length);
            this.value = val;
        });
        
        // Check the final result when the input has lost focus
        $('#absen_masuk_kerja').blur(function(){
            var val = this.value;
            val = (/^(([01][0-9]|2[0-3])h)|(([01][0-9]|2[0-3]):[0-5][0-9])$/.test(val) ? val : "");
            this.value = val;
        });

        // Apply input rules as the user types or pastes input
        $('#absen_pulang_kerja').keyup(function(){
            var val = this.value;
            var lastLength;
            do {
                // Loop over the input to apply rules repeately to pasted inputs
                lastLength = val.length;
                val = replaceBadInputs(val);
            } while(val.length > 0 && lastLength !== val.length);
            this.value = val;
        });
        
        // Check the final result when the input has lost focus
        $('#absen_pulang_kerja').blur(function(){
            var val = this.value;
            val = (/^(([01][0-9]|2[0-3])h)|(([01][0-9]|2[0-3]):[0-5][0-9])$/.test(val) ? val : "");
            this.value = val;
        });

    </script>

@endsection
