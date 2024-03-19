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

@stop
@section('mainarea')
    <!-- page-header -->
    <div class="page-header shadow">
        <ol class="breadcrumb breadcrumb-arrow mt-0">
            <li><a href="#">Master Data</a></li>
            <li class="active"><span>Absen IN/OUT</span></li>
        </ol>
        <div class="ml-auto">
            <div class="input-group">
                <a href="#" id="btn-refresh-data-log" class="btn btn-secondary text-white mr-2 btn-icon" data-toggle="tooltip"
                    title="" data-placement="bottom" data-original-title="refresh data log">
                    <span>
                        <i class="fa fa-refresh"></i>
                    </span>
                </a>
                <a href="{{ url('lockscreen') }}" class="btn btn-primary text-white mr-2 btn-sm" data-toggle="tooltip"
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
        {!! Form::open(['route' => 'hris.mdabsenhadir.ajax_exportexcel', 'id' => 'formExport', 'name' => 'formExport','method'=>'post']) !!}

        @csrf
        <div class="card shadow">
            <div class="card-header bg-primary p-3">
                <div class="card-title">FILTER KEHADIRAN KARYAWAN</div>
                <div class="card-options ">
                    <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="expanel expanel-default">
                            <div class="expanel-body mr-0 mt-0 mb-5">
                                <div class="form-group">
                                    <label class="form-label">Tanggal Absen : </label>
                                    <input id="daterange1" name="daterange1" type="text" class="form-control" placeholder="Tanggal Absen" maxlength="50" size="50">
                                </div>
                            </div>
                            <div class="expanel-footer mr-0 mt-0 mb-0">
                                <button type="button" id="btn-preview" class="btn btn-app btn-primary mr-2 mt-0 mb-2" data-toggle="tooltip" title="Cetak Data"><i class="ion-printer"></i> Preview</button>
                                <button type="submit" id="btn-exportexcel" class="btn btn-app btn-primary mr-2 mt-0 mb-2" data-toggle="tooltip" title="Export Data ke File"><i class="ion-ios7-download"></i> Export</button>
                                <a href="javascript:void(0)" id="btn-importexcel" class="btn btn-app btn-primary mr-2 mt-0 mb-2" data-toggle="tooltip" title="Import Data dari File"><i class="ion-ios7-upload"></i> Import</a>
                                <a href="javascript:void(0)" id="btn-gopayroll" class="btn btn-app btn-primary mr-2 mt-0 mb-2" data-toggle="tooltip" title="Ke Menu Payroll"><i class="ion-thumbsup"></i> Ke Payroll</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="expanel expanel-default">
                            <div class="expanel-body mr-0 mt-0 mb-1">
                                <div class="form-group">
                                    <label class="form-label">Department : </label>
                                    <select id="selectDepartment" name="selectDepartment" multiple data-placeholder="Pilih department" class="form-control select2">
                                        @foreach ($department as $r_dept)
                                            <option  value="{{$r_dept->department_id}}">{{$r_dept->department_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body pb-0">
                        <label class="form-label">Pilih Karyawan : </label>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label class="custom-switch">
                                        <input type="checkbox" id="check-all-karyawan" class="custom-switch-input">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Pilih semua karyawan</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-11 col-xl-11">
                                <div class="form-group">
                                    <select id="selectEmployeeID" name="selectEmployeeID" multiple data-placeholder="Pilih karyawan" class="form-control select2">
                                        @foreach ($selectemployee as $r_empl)
                                            <option  value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-1 col-xl-1">
                                <div class="form-group">
                                    <a href="javascript:void(0)" id="btn-caridata" class="btn btn-app btn-primary mr-2 mt-0 mb-2" data-toggle="tooltip" title="Cari Data"><i class="ion-search"></i> Cari</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-primary br-br-7 br-bl-7">
                <div class="text-white"></div>
            </div>
        </div>
    {{-- </form> --}}
    {!! Form::close() !!}
    <!-- END FORM-->


    <!-- Begin Form Edit Absen Karyawan -->
    <div id="data-kehadiran" class="card shadow">
        <div class="card-header bg-primary p-3">
            <div class="card-title">DATA KEHADIRAN KARYAWAN</div>
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

    <div id="data-kehadiran-edited" class="card shadow">
        <div class="card-header bg-primary p-3">
            <div class="card-title">DATA KEHADIRAN YANG SUDAH DI EDIT</div>
            <div class="card-options ">
                <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatable-ajax-kehadiraninout-edited"
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
    <div class="modal fade" id="ajax-kehadiraninout-edited" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <!-- BEGIN FORM-->
                {!! Form::open(['url' => 'javascript:void(0)', 'id' => 'formSaveChanges1', 'name' => 'formSaveChanges1']) !!}
                <input id="kode_hari" type="hidden">
                <input id="site_nirwana_id" type="hidden">
                <input id="site_nirwana_name" type="hidden">
                <input id="holiday_name" type="hidden">
                <input id="department_id" type="hidden">
                <input id="sub_dept_id" type="hidden">
                <input id="mulai_jam_lembur" type="hidden">
                <input id="akhir_jam_lembur" type="hidden">
                <input id="nomor_form_lembur" type="hidden">
                <input id="lama_bekerja_bulan" type="hidden">
                <input id="operator" value="{{ $loggedAdmin->email }}" type="hidden">
                <div class="modal-header bg-primary p-2">
                    <h4 class="modal-title" id="ajaxKehadiranINOUTModel"><b>EDIT ABSEN IN / OUT (ANDA LOGIN SEBAGAI : {{ $loggedAdmin->email }})</b></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                        <i class="fa fa-remove text-white"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-striped w-100">
                        <tbody>
                            <tr>
                                <th colspan=2>NAMA KARYAWAN</th>
                                <th>NOMOR ABSEN</th>
                                <th>NIK</th>
                            </tr>
                            <tr>
                                <td colspan=2><input readonly class="form-control" id="employee_name" name="employee_name" placeholder="NAMA KARYAWAN" type="text"></td>
                                <td><input readonly class="form-control" id="enroll_id" name="enroll_id" placeholder="NOMOR ABSEN" type="text"></td>
                                <td><input readonly class="form-control" id="nik" name="nik" placeholder="NIK" type="text"></td>
                            </tr>
                            <tr>
                                <th colspan=2>DEPARTMENT</th>
                                <th colspan=2>BAGIAN</th>
                            </tr>
                            <tr>
                                <td colspan=2><input readonly class="form-control" id="department_name" name="department_name" placeholder="DEPARTMENT" type="text"></td>
                                <td colspan=2><input readonly class="form-control" id="sub_dept_name" name="sub_dept_name" placeholder="BAGIAN" type="text"></td>
                            </tr>
                            <tr>
                                <th>TANGGAL ABSEN</th>
                                <th>NAMA HARI</th>
                                <th>TANGGAL MASUK</th>
                                <th>TANGGAL RESIGN</th>
                            </tr>
                            <tr>
                                <td><input readonly class="form-control" id="tanggal_berjalan" name="tanggal_berjalan" placeholder="YYYY-MM-DD" type="text"></td>
                                <td><input readonly class="form-control" id="nama_hari" name="nama_hari" placeholder="NAMA HARI" type="text"></td>
                                <td><input readonly class="form-control" id="join_date" name="join_date" placeholder="YYYY-MM-DD" type="text"></td>
                                <td><input readonly class="form-control" id="tanggal_resign" name="tanggal_resign" placeholder="YYYY-MM-DD" type="text"></td>
                            </tr>
                            <tr>
                                <th>JADWAL (IN)</th>
                                <th>JADWAL (OUT)</th>
                            </tr>
                            <tr>
                                <td><input class="form-control" id="mulai_jam_kerja" name="mulai_jam_kerja" placeholder="--:--" type="text"></td>
                                <td><input class="form-control" id="akhir_jam_kerja" name="akhir_jam_kerja" placeholder="--:--" type="text"></td>
                            </tr>
                            <tr>
                                <th>ABSEN IN</th>
                                <th>ABSEN OUT</th>
                                <th colspan=2>STATUS ABSEN</th>
                            </tr>
                            <tr>
                                <td><input class="form-control" id="absen_masuk_kerja" name="absen_masuk_kerja" placeholder="--:--" type="text" maxlength="5"></td>
                                <td><input class="form-control" id="absen_pulang_kerja" name="absen_pulang_kerja" placeholder="--:--" type="text" maxlength="5"></td>
                                <td colspan=2>
                                    <select id="status_absen" class="form-control" data-placeholder="-- Pilih Jenis Perijinan --">
                                        <option value="">-- Pilih Satus Absen --</option>
                                        <option value="TL">TL - TIDAK LENGKAP</option>
                                        <option value="R">R - RESIGN</option>
                                        @foreach ($refabsenijin as $r_refabsenijin)
                                            <option value="{{$r_refabsenijin->kode_absen_ijin}}">{{$r_refabsenijin->kode_nama_absen_ijin}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer bg-primary pt-0 pb-1 pr-1">
                    <div class="btn-list">
                        <button type="button" id="btn-tutup" class="btn btn-warning btn-app" data-dismiss="modal">Tutup</button>
                        <button type="button" id="btn-save-changes" class="btn btn-secondary btn-app">Simpan</button>
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
            $("#datatable-ajax-kehadiraninout-edited").DataTable().ajax.reload();
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
            $("#data-kehadiran").show('slow');
            $("#data-kehadiran-edited").show('slow');

            $('#datatable-ajax-crud').DataTable().clear();
            $('#datatable-ajax-crud').DataTable().destroy();
            $('#datatable-ajax-crud').empty();

            $('#datatable-ajax-kehadiraninout-edited').DataTable().clear();
            $('#datatable-ajax-kehadiraninout-edited').DataTable().destroy();
            $('#datatable-ajax-kehadiraninout-edited').empty();

            var department_id = $('#selectDepartment').val();
            var sub_dept_id = $('#selectSubDep').val();
            var daterange1 = $('#daterange1').val();
            var selectEmployeeID = $('#selectEmployeeID').val();

            var table1 = $('#datatable-ajax-crud').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                "ajax": {
                    "url": "{{ route('admin.datakehadiraninoutedited.ajax_abseninout') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                    "data": {
                        department_id:department_id,
                        sub_dept_id:sub_dept_id,
                        daterange1:daterange1,
                        selectEmployeeID:selectEmployeeID,
                    }
                },
                columns: [
                    {
                        title: 'UUID',
                        data: 'uuid',
                        name: 'uuid'
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
                        title: 'No. Absen',
                        data: 'enroll_id',
                        name: 'enroll_id'
                    },
                    {
                        title: 'Tanggal Absen',
                        data: 'tanggal_berjalan',
                        name: 'tanggal_berjalan'
                    },
                    {
                        title: 'Nama Hari',
                        data: 'nama_hari',
                        name: 'nama_hari'
                    },
                    {
                        title: 'Nama Karyawan',
                        data: 'employee_name',
                        name: 'employee_name'
                    },
                    {
                        title: 'Kode Hari',
                        data: 'kode_hari',
                        name: 'kode_hari'
                    },
                    {
                        title: 'Jabatan',
                        data: 'posisi_name',
                        name: 'posisi_name'
                    },
                    {
                        title: 'Kerja/Libur',
                        data: 'kerjalibur',
                        name: 'kerjalibur'
                    },
                    {
                        title: 'Jadwal Masuk Kerja',
                        data: 'mulai_jam_kerja',
                        name: 'mulai_jam_kerja'
                    },
                    {
                        title: 'Jadwal Pulang Kerja',
                        data: 'akhir_jam_kerja',
                        name: 'akhir_jam_kerja'
                    },
                    {
                        title: 'Absen IN',
                        data: 'absen_masuk_kerja',
                        name: 'absen_masuk_kerja'
                    },
                    {
                        title: 'Absen OUT',
                        data: 'absen_pulang_kerja',
                        name: 'absen_pulang_kerja'
                    },
                    {
                        title: 'DT',
                        data: 'jumlah_menit_absen_dt',
                        name: 'jumlah_menit_absen_dt'
                    },
                    {
                        title: 'PC',
                        data: 'jumlah_menit_absen_pc',
                        name: 'jumlah_menit_absen_pc'
                    },
                    {
                        title: 'Jumlah DTPC',
                        data: 'jumlah_menit_absen_dtpc',
                        name: 'jumlah_menit_absen_dtpc'
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
                        'targets': [0,1,7]
                    }
                ],
                order: [
                    [4, 'asc'],[5, 'desc']
                ],
                "iDisplayLength": 100,
                "createdRow": function (row, data, dataIndex) {
                    if ((data['kode_hari'] == "5") || (data['kode_hari'] == "6") || (data['kerjalibur'] == "LIBUR")) {
                        $(row).css('background', 'yellow');
                    } else if ((data['status_absen'] == "M") || (data['status_absen'] == "TL")) {
                            $(row).css('background', 'red');
                     }
                    if (data['nomor_form_perubahan_absen'] !== null) {
                        $(row).css('background', 'lime');
                    }
                }
            });

            table1.draw();

            $('#datatable-ajax-crud tbody').on('click', 'tr', function () {
                $("#datatable-ajax-crud tbody tr").removeClass('bg-cyan');
                $(this).addClass('bg-cyan');
             });

             $('#datatable-ajax-crud tbody').on('dblclick', 'tr', function () {
                var data = $("#datatable-ajax-crud").DataTable().row(this).data();

                var tanggal_berjalan = data['tanggal_berjalan'];
                var enroll_id = data['enroll_id'];

                var tanggal = tanggal_berjalan;
                
                // LAGI COBA TEST CLOSING PAYROLL
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
                            $("#absen_masuk_kerja").removeClass("is-invalid state-invalid");
                            $("#absen_pulang_kerja").removeClass("is-invalid state-invalid");
                            $("#status_absen").removeClass("is-invalid state-invalid");

                            $.ajax({
                                "type":"POST",
                                "url": "{{route('admin.datakehadiraninoutedited.form_kehadiraninout_edited')}}",
                                "data": {
                                    tanggal_berjalan: tanggal_berjalan,
                                    enroll_id: enroll_id,
                                },
                                "dataType": 'json',
                                "headers": {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                "success": function(res){
                                    $('#ajax-kehadiraninout-edited').modal('show');

                                    $('#nik').val(res[0]['nik']);
                                    $('#enroll_id').val(res[0]['enroll_id']);
                                    $('#employee_name').val(res[0]['employee_name']);
                                    $('#department_name').val(res[0]['department_name']);
                                    $('#sub_dept_name').val(res[0]['sub_dept_name']);
                                    $('#nama_hari').val(res[0]['nama_hari']);
                                    $('#mulai_jam_kerja').val(res[0]['mulai_jam_kerja']);
                                    $('#akhir_jam_kerja').val(res[0]['akhir_jam_kerja']);
                                    $('#absen_masuk_kerja').val(res[0]['absen_masuk_kerja']);
                                    $('#absen_pulang_kerja').val(res[0]['absen_pulang_kerja']);
                                    $("#status_absen").val(data['status_absen']).trigger("change");
                                    
                                    $('#join_date').val(res[0]['join_date']);
                                    $('#tanggal_resign').val(res[0]['tanggal_resign']);
                                    $('#tanggal_berjalan').val(res[0]['tanggal_berjalan']);

                                    $('#kode_hari').val(res[0]['kode_hari']);
                                    $('#site_nirwana_id').val(res[0]['site_nirwana_id']);
                                    $('#site_nirwana_name').val(res[0]['site_nirwana_name']);
                                    $('#holiday_name').val(res[0]['holiday_name']);
                                    $('#department_id').val(res[0]['department_id']);
                                    $('#sub_dept_id').val(res[0]['sub_dept_id']);
                                    $('#mulai_jam_lembur').val(res[0]['mulai_jam_lembur']);
                                    $('#akhir_jam_lembur').val(res[0]['akhir_jam_lembur']);
                                    $('#nomor_form_lembur').val(res[0]['nomor_form_lembur']);

                                }
                            });

                        }

                    },
                    error: function(res){
                                    
                    }
                });                                           

             });

             var table2 = $('#datatable-ajax-kehadiraninout-edited').DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                    "url": "{{ route('admin.datakehadiraninoutedited.ajax_abseninout_edited') }}",
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
                        title: 'NAMA KARYAWAN',
                        data: 'employee_name',
                        name: 'employee_name'
                    },
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
                        title: 'TANGGAL ABSEN',
                        data: 'tanggal_absen',
                        name: 'tanggal_absen'
                    },
                    {
                        title: 'KODE HARI',
                        data: 'kode_hari',
                        name: 'kode_hari'
                    },
                    {
                        title: 'NAMA HARI',
                        data: 'nama_hari',
                        name: 'nama_hari'
                    },
                    {
                        title: 'BAGIAN',
                        data: 'sub_dept_name',
                        name: 'sub_dept_name'
                    },
                    {
                        title: 'ABSEN (IN)',
                        data: 'absen_masuk_kerja',
                        name: 'absen_masuk_kerja'
                    },
                    {
                        title: 'ABSEN (OUT)',
                        data: 'absen_pulang_kerja',
                        name: 'absen_pulang_kerja'
                    },
                    {
                        title: 'STATUS ABSEN',
                        data: 'status_absen',
                        name: 'status_absen'
                    },
                    {
                        title: 'DI UBAH OLEH',
                        data: 'operator',
                        name: 'operator'
                    },
                    {
                        title: 'TERAKHIR DI UBAH',
                        data: 'created_at',
                        name: 'created_at'
                    },
                ],
                columnDefs: [
                    {
                        'visible': false,
                        'targets': [0,5]
                    }
                ],
                order: [
                    [12, 'desc']
                ],
                "createdRow": function (row, data, dataIndex) {
                    if ((data['kode_hari'] == "5") || (data['kode_hari'] == "6")) {
                        $(row).css('background', 'yellow');
                    }
                    if (data['holiday_name']) {
                        $(row).css('background', 'yellow');
                    }
                }
            });

            $('#datatable-ajax-kehadiraninout-edited tbody').on('click', 'tr', function () {
                var tr = $(this).closest('tr');
                var row = table2.row(tr);

                $("#datatable-ajax-kehadiraninout-edited tbody tr").removeClass('bg-cyan');
                $(this).addClass('bg-cyan');
             });

             table2.draw();

        });

        $(document).ready(function() {
            $("#data-kehadiran").hide();
            $("#data-kehadiran-edited").hide();
        });

        $('#datatable-ajax-crud tbody').on('click', 'tr', function () {
            $("#datatable-ajax-crud tbody tr").removeClass('bg-cyan');
            $(this).addClass('bg-cyan');
         });

        $('body').on('click', '#btn-save-changes', function (event) {
            var tanggal_berjalan = $("#tanggal_berjalan").val();
            var kode_hari = $("#kode_hari").val();
            var nama_hari = $("#nama_hari").val();
            var site_nirwana_id = $("#site_nirwana_id").val();
            var site_nirwana_name = $("#site_nirwana_name").val();
            var holiday_name = $("#holiday_name").val();
            var nik = $("#nik").val();
            var enroll_id = $("#enroll_id").val();
            var employee_name = $("#employee_name").val();
            var department_id = $("#department_id").val();
            var department_name = $("#department_name").val();
            var sub_dept_id = $("#sub_dept_id").val();
            var sub_dept_name = $("#sub_dept_name").val();
            var status_absen = $("#status_absen").val();
            var absen_masuk_kerja = $("#absen_masuk_kerja").val();
            var absen_pulang_kerja = $("#absen_pulang_kerja").val();
            var mulai_jam_kerja = $("#mulai_jam_kerja").val();
            var akhir_jam_kerja = $("#akhir_jam_kerja").val();
            var mulai_jam_lembur = $("#mulai_jam_lembur").val();
            var akhir_jam_lembur = $("#akhir_jam_lembur").val();
            var nomor_form_lembur = $("#nomor_form_lembur").val();
            var join_date = $("#join_date").val();
            var tanggal_resign = $("#tanggal_resign").val();
            var operator = $("#operator").val();

            $("#absen_masuk_kerja").removeClass("is-invalid state-invalid");
            $("#absen_pulang_kerja").removeClass("is-invalid state-invalid");

            //    if(!absen_masuk_kerja && absen_pulang_kerja) {
        //         $("#absen_masuk_kerja").addClass("is-invalid state-invalid");
        //         notif({
        //             msg: "<b>Warning:</b> Silakan di isi Absen OUT nya.",
        //             type: "warning"
        //         });
        //         return false;
        //     } else if(!absen_pulang_kerja && absen_masuk_kerja) {
        //         $("#absen_pulang_kerja").addClass("is-invalid state-invalid");
        //         notif({
        //             msg: "<b>Warning:</b> Silakan di isi Absen OUT nya.",
        //             type: "warning"
        //         });
        //         return false;
        //     }
            $('#btn-save-changes').addClass("btn-loading");
            $("#btn-save-changes").attr("disabled", true);

            $.ajax({
                type:"POST",
                url: "{{route('admin.datakehadiraninoutedited.store')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    tanggal_berjalan:tanggal_berjalan,
                    kode_hari:kode_hari,
                    nama_hari:nama_hari,
                    site_nirwana_id:site_nirwana_id,
                    site_nirwana_name:site_nirwana_name,
                    holiday_name:holiday_name,
                    nik:nik,
                    enroll_id:enroll_id,
                    employee_name:employee_name,
                    department_id:department_id,
                    department_name:department_name,
                    sub_dept_id:sub_dept_id,
                    sub_dept_name:sub_dept_name,
                    status_absen:status_absen,
                    absen_masuk_kerja:absen_masuk_kerja,
                    absen_pulang_kerja:absen_pulang_kerja,
                    mulai_jam_kerja:mulai_jam_kerja,
                    akhir_jam_kerja:akhir_jam_kerja,
                    mulai_jam_lembur:mulai_jam_lembur,
                    akhir_jam_lembur:akhir_jam_lembur,
                    nomor_form_lembur:nomor_form_lembur,
                    join_date:join_date,
                    tanggal_resign:tanggal_resign,
                    operator:operator,
                },
                dataType: 'json',
                success: function(res){
                    notif({
                        msg: "<b>Info:</b> Data berhasil di simpan.",
                        type: "info"
                    });

                    $("#ajax-kehadiraninout-edited").modal('hide');
                    $("#datatable-ajax-crud").DataTable().ajax.reload();
                    $("#datatable-ajax-kehadiraninout-edited").DataTable().ajax.reload();

                },
                error: function(res){
                    notif({
                        msg: "<b>Oops!</b> An Error Occurred",
                        type: "error",
                        position: "center"
                    });
                }
            });

            $('#btn-save-changes').removeClass("btn-loading");
            $("#btn-save-changes").html('Simpan');
            $("#btn-save-changes").attr("disabled", false);

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
