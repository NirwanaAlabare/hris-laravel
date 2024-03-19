@extends('admin.adminlayouts.adminlayout')

@section('head')
    <!-- Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet" />

    <!--Select2 css -->
    <link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />

    <!-- Date Picker css-->
    <link href="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.css')}}" rel="stylesheet" />

    <!-- Time picker css-->
    <link href="{{URL::asset('assets/plugins/time-picker/jquery.timepicker.css')}}" rel="stylesheet" />

    <!--Mutipleselect css-->
    <link rel="stylesheet" href="{{URL::asset('assets/plugins/multipleselect/multiple-select.css')}}">

	<!-- Notifications  css -->
	<link href="{{URL::asset('assets/plugins/notify-growl/css/jquery.growl.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/notify-growl/css/notifIt.css')}}" rel="stylesheet" />

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
            <li><a href="#">Master Data</a></li>
            <li class="active"><span>Jadwal Kerja</span></li>
        </ol>
        <div class="ml-auto">
            <div class="input-group">
                <a href="#" id="btn-exportexcel" class="btn btn-primary p-0 mr-1 text-white btn-icon" data-toggle="tooltip"
                    title="" data-placement="bottom" data-original-title="Export Data to Excel">
                    <span>
                        <i class="fa fa-file-excel-o"></i>
                    </span>
                </a>
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

    <!-- row -->
    <div class="row">

        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card shadow">
                <div class="card-header bg-primary p-2">
                    <div class="card-title">INJECT JADWAL KERJA</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Tanggal Pilihan : </label>
                                <input readonly id="daterange1" name="daterange1" type="text" class="form-control" placeholder="YYYY-MM-DD YYYY-MM-DD " maxlength="21" size="21">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Jadwal (IN):</label>
                                <input id="set_mulai_jam_kerja" name="set_mulai_jam_kerja" type="text" class="form-control" placeholder="--:--" maxlength="5" size="5">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Jadwal (OUT):</label>
                                <input id="set_akhir_jam_kerja" name="set_akhir_jam_kerja" type="text" class="form-control" placeholder="--:--" maxlength="5" size="5">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="form-label">Department : </label>
                                <select id="selectDepartment" name="selectDepartment" class="form-control">
                                    <option value="">-- Pilih Department --</option>
                                    @foreach ($department as $r_dept)
                                        <option  value="{{$r_dept->department_id}}">{{$r_dept->department_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <span class="input-group-append">
                                    <select id="selectEmployeeID" name="selectEmployeeID" multiple data-placeholder="Pilih karyawan" class="form-control select2">
                                        @foreach ($selectemployee as $r_empl)
                                            <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                                        @endforeach
                                    </select>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-primary m-0 p-0">
                    <a href="javascript:void(0)" id="btn-paste" class="btn btn-app btn-secondary m-1 p-1" data-toggle="tooltip" title="Gunakan data Inject ini"><i class="fa fa-paste"></i> Tempel</a>
                </div>
            </div>
        </div><!-- col end -->
        <!-- END FORM-->

        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div id="data-karyawan" class="card shadow">
                <div class="card-header bg-primary p-3">
                    <div class="card-title">EDIT JADWAL KERJA KARYAWAN</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="card-body m-0">
                    <div class="table-responsive">
                        <table id="data-jadwalkerja-karyawan" class="table table-sm table-striped table-hover w-100 m-0 p-0">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-nowrap w-5" scope="col">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="selectAllData" name="selectAllData" value="selectAllData" checked>
                                            <span class="custom-control-label"></span>
                                        </label>
                                    </th>
                                    <th class="text-nowrap w-5" scope="col">No.</th>
                                    <th class="text-nowrap w-5" scope="col">Tanggal</th>
                                    <th class="text-nowrap w-5" scope="col">Hari</th>
                                    <th class="text-nowrap w-5" scope="col">No<br>Absen</th>
                                    <th class="text-nowrap w-5" scope="col">NIK</th>
                                    <th class="text-nowrap" scope="col">Nama<br>Karyawan</th>
                                    <th class="text-nowrap w-20" scope="col">Jadwal<br>IN</th>
                                    <th class="text-nowrap w-20" scope="col">Jadwal<br>OUT</th>
                                    <th class="text-nowrap w-20" scope="col">Jadwal IN<br>(Di Kehadiran)</th>
                                    <th class="text-nowrap w-20" scope="col">Jadwal OUT<br>(Di Kehadiran)</th>
                                    <th class="text-nowrap w-20" scope="col">Absen<br>IN</th>
                                    <th class="text-nowrap w-20" scope="col">Absen<br>OUT</th>
                                    <th class="text-nowrap" scope="col">Bagian</th>
                                    <th class="text-nowrap" scope="col">Staff /<br>Non Staff</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-primary m-0 p-0">
                    <a href="javascript:void(0)" id="btn-simpan" class="btn btn-app btn-secondary ml-1 mt-1 mb-2 p-1" title="Simpan Data Yang Di Pilih"><i class="fa fa-save"></i> Simpan</a>
                    <a href="javascript:void(0)" id="btn-remove" class="btn btn-app btn-danger ml-1 mt-1 mb-2 p-1" title="Hapus Data Yang Di Pilih"><i class="fa fa-trash"></i> Hapus</a>
                    <a href="{{route('hris.datajadwalkerjalog.index')}}" id="btn-reset" class="btn btn-app btn-gray ml-1 mt-1 mb-2 p-1" title="Refresh Halaman"><i class="fa fa-refresh"></i> Refresh</a>
                </div>
            </div>            
        </div>        
        
    </div>
    <!-- row end -->

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

    <!--Select2 js -->
    <script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>

    <!--MutipleSelect js-->
    <script src="{{URL::asset('assets/plugins/multipleselect/multiple-select.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/multipleselect/multi-select.js')}}"></script>

    <!-- Datepicker js -->
    <script src="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/spectrum-date-picker/jquery-ui.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/input-mask/jquery.maskedinput.js')}}"></script>

    <!-- Timepicker js -->
    <script src="{{URL::asset('assets/plugins/time-picker/jquery.timepicker.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/time-picker/toggles.min.js')}}"></script>

    <!---Tabs js-->
    <script src="{{URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/tabs/tabs.js')}}"></script>

    <!-- Popover js -->
    <script src="{{URL::asset('assets/js/popover.js')}}"></script>

    <script type="text/javascript">

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.select2').select2({
            minimumResultsForSearch: Infinity
          });

        $('.fc-datepicker').datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'yy-mm-dd'
        });

        $('#progress-show-1').hide();
        $("#btn-process").prop("disabled", true);
        
        $("#data-karyawan").hide();

        $('body').on('click', '#btn-refresh-page', function (event) {
            location.reload();
        });

        $('body').on('click', '#btn-close', function (event) {
            location.reload();
        });

        $('body').on('change', '#selectDepartment', function () {
            var department_id = $('#selectDepartment').val();

            if ($("#selectEmployeeID option:selected").length == 0) {
                $("#selectEmployeeID").empty();
                $("#selectEmployeeID").val(null).trigger("change");
            }

            if (department_id == "") {
                $("#selectEmployeeID").empty();
                $("#selectEmployeeID").val(null).trigger("change");
            }

            if(department_id){
                $.ajax({
                    type:"POST",
                    url: "{{route('hris.datajadwalkerjalog.ajax_getemployeselectdeptid')}}",
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
                    }
                });
            } else {
                $.ajax({
                    type:"POST",
                    url: "{{route('hris.datajadwalkerjalog.ajax_getallemployeeatribut')}}",
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

        $('body').on('click', '#btn-paste', function (event) {

            $("#btn-paste").addClass("btn-loading");
            $("#btn-paste").html('Loading...');
            $("#btn-paste").attr("disabled", true);

            $("#daterange1").removeClass("border-danger");
            $("#selectEmployeeID").removeClass("border-danger");
            $("#set_mulai_jam_kerja").removeClass("border-danger");
            $("#set_akhir_jam_kerja").removeClass("border-danger");

            if(!$("#daterange1").val()) {
                notif({
                    msg: "<b>Warning:</b> Silakan diisi tanggal kehadirannya.",
                    type: "warning"
                });

                $("#daterange1").addClass('border-danger');
                $('#btn-paste').removeClass("btn-loading");
                $("#btn-paste").html('<i class="fa fa-paste"></i> Tempel');
                $("#btn-paste").attr("disabled", false);
    
                return false;
            }

            if(!$("#set_mulai_jam_kerja").val()) {
                notif({
                    msg: "<b>Warning:</b> Silakan diisi Jadwal Kerja (IN).",
                    type: "warning"
                });

                $("#set_mulai_jam_kerja").addClass('border-danger');
                $('#btn-paste').removeClass("btn-loading");
                $("#btn-paste").html('<i class="fa fa-paste"></i> Tempel');
                $("#btn-paste").attr("disabled", false);
    
                return false;
            }

            if(!$("#set_akhir_jam_kerja").val()) {
                notif({
                    msg: "<b>Warning:</b> Silakan diisi Jadwal Kerja (OUT).",
                    type: "warning"
                });

                $("#set_akhir_jam_kerja").addClass('border-danger');
                $('#btn-paste').removeClass("btn-loading");
                $("#btn-paste").html('<i class="fa fa-paste"></i> Tempel');
                $("#btn-paste").attr("disabled", false);
    
                return false;
            }

            if($("#selectEmployeeID").val() == "") {

                if($("#selectDepartment").val() == "") {
                    notif({
                        msg: "<b>Warning:</b> Silakan pilih karyawannya.",
                        type: "warning"
                    });

                    $("#selectEmployeeID").addClass('border-danger');
                    $('#btn-paste').removeClass("btn-loading");
                    $("#btn-paste").html('<i class="fa fa-paste"></i> Tempel');
                    $("#btn-paste").attr("disabled", false);
        
                    return false;                    
                } else {
                    //
                }
            }

            $("#data-karyawan").show();

            var selectEmp = $("#selectEmployeeID").val();
            var daterange1 = $("#daterange1").val();
            var selectDepartment = $("#selectDepartment").val();
            
            $.ajax({
                type:"POST",
                url: "{{route('hris.datajadwalkerjalog.getEmployeeKehadiran')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    daterange1:daterange1,
                    selectEmp:selectEmp,
                    selectDepartment:selectDepartment,
                },
                dataType: 'json',
                success: function(res){
                    if(res){
                        for(i=0;i<res.length;i++) {
                            nomor_urut = i+1;
                            set_mulai_jam_kerja = $("#set_mulai_jam_kerja").val();
                            set_akhir_jam_kerja = $("#set_akhir_jam_kerja").val();
                            mulai_jam_kerja_kehadiran = "";
                            akhir_jam_kerja_kehadiran = "";
                            absen_masuk_kerja = "";
                            absen_pulang_kerja = "";
                            sub_dept_name = "";
                            status_staff = "";
                            isCheckedData = "checked";
                            bgwarna = 0;
                
                            if (res[i].mulai_jam_kerja) { mulai_jam_kerja_kehadiran = res[i].mulai_jam_kerja; }
                            if (res[i].akhir_jam_kerja) { akhir_jam_kerja_kehadiran = res[i].akhir_jam_kerja; }
                            if (res[i].absen_masuk_kerja) { absen_masuk_kerja = res[i].absen_masuk_kerja; }
                            if (res[i].absen_pulang_kerja) { absen_pulang_kerja = res[i].absen_pulang_kerja; }
                            if (res[i].sub_dept_name) { sub_dept_name = res[i].sub_dept_name; }
                            if (res[i].status_staff) { status_staff = res[i].status_staff; }
                            if (res[i].kode_hari == 5) { bgwarna = 'style="background: yellow"'; set_mulai_jam_kerja = ""; set_akhir_jam_kerja = ""; isCheckedData = ""; }
                            if (res[i].kode_hari == 6) { bgwarna = 'style="background: yellow"'; set_mulai_jam_kerja = ""; set_akhir_jam_kerja = ""; isCheckedData = ""; }
                            if (res[i].holiday_name) { bgwarna = 'style="background: yellow"'; set_mulai_jam_kerja = ""; set_akhir_jam_kerja = ""; isCheckedData = ""; }

                            $htmlTable = '' +
                            '<tr ' + bgwarna + ' class="text-center">' +
                            '   <td class="text-nowrap">' +
                            '       <label class="custom-control custom-checkbox">' +
                            '          <input type="checkbox" class="custom-control-input" id="checkjadwal_' + i + '" name="checkjadwal" value="' + i + '" ' + isCheckedData + '>' +
                            '          <span class="custom-control-label"></span>' +
                            '       </label>' +
                            '       <input id="uuid' + i + '" name="uuid" value="' + res[i].uuid + '" type="hidden">' +
                            '       <input id="tanggal_berjalan' + i + '" name="tanggal_berjalan" value="' + res[i].tanggal_berjalan + '" type="hidden">' +
                            '       <input id="kode_hari' + i + '" name="kode_hari" value="' + res[i].kode_hari + '" type="hidden">' +
                            '       <input id="nama_hari' + i + '" name="nama_hari" value="' + res[i].nama_hari + '" type="hidden">' +
                            '       <input id="enroll_id' + i + '" name="enroll_id" value="' + res[i].enroll_id + '" type="hidden">' +
                            '       <input id="nik' + i + '" name="nik" value="' + res[i].nik + '" type="hidden">' +
                            '       <input id="employee_name' + i + '" name="employee_name" value="' + res[i].employee_name + '" type="hidden">' +
                            '       <input id="absen_masuk_kerja' + i + '" name="absen_masuk_kerja" value="' + res[i].absen_masuk_kerja + '" type="hidden">' +
                            '       <input id="absen_pulang_kerja' + i + '" name="absen_pulang_kerja" value="' + res[i].absen_pulang_kerja + '" type="hidden">' +
                            '   </td>' +
                            '   <td class="text-nowrap">' +
                            '       <div id="nomor_urut_' + i + '" name="nomor_urut">' + nomor_urut + '</div>' +
                            '   </td>' +
                            '   <td class="text-nowrap">' +
                            '       <div id="tanggal_berjalan' + i + '" name="tanggal_berjalan">' + res[i].tanggal_berjalan + '</div>' +
                            '   </td>' +
                            '   <td class="text-nowrap">' +
                            '       <div id="nama_hari' + i + '" name="nama_hari">' + res[i].nama_hari + '</div>' +
                            '   </td>' +
                            '   <td class="text-nowrap">' +
                            '       <div id="enroll_id' + i + '" name="enroll_id">' + res[i].enroll_id + '</div>' +
                            '   </td>' +
                            '   <td class="text-nowrap">' +
                            '       <div id="nik' + i + '" name="nik">' + res[i].nik + '</div>' +
                            '   </td>' +
                            '   <td class="text-nowrap">' +
                            '       <div id="employee_name' + i + '" name="employee_name">' + res[i].employee_name + '</div>' +
                            '   </td>' +
                            '   <td class="text-nowrap">' +
                            '       <input class="form-control text-sm" id="mulai_jam_kerja_' + i + '" name="mulai_jam_kerja" value="' + set_mulai_jam_kerja + '" placeholder="--:--" type="text" maxlength="5">' +
                            '   </td>' +
                            '   <td class="text-nowrap">' +
                            '       <input class="form-control text-sm" id="akhir_jam_kerja_' + i + '" name="akhir_jam_kerja" value="' + set_akhir_jam_kerja + '" placeholder="--:--" type="text" maxlength="5">' +
                            '   </td>' +
                            '   <td class="text-nowrap">' +
                            '       <div id="mulai_jam_kerja_kehadiran' + i + '" name="mulai_jam_kerja_kehadiran">' + mulai_jam_kerja_kehadiran + '</div>' +
                            '   </td>' +
                            '   <td class="text-nowrap">' +
                            '       <div id="akhir_jam_kerja_kehadiran' + i + '" name="akhir_jam_kerja_kehadiran">' + akhir_jam_kerja_kehadiran + '</div>' +
                            '   </td>' +
                            '   <td class="text-nowrap">' +
                            '       <div id="absen_masuk_kerja' + i + '" name="absen_masuk_kerja">' + absen_masuk_kerja + '</div>' +
                            '   </td>' +
                            '   <td class="text-nowrap">' +
                            '       <div id="absen_pulang_kerja' + i + '" name="absen_pulang_kerja">' + absen_pulang_kerja + '</div>' +
                            '   </td>' +
                            '   <td class="text-nowrap">' +
                            '       <div id="sub_dept_name' + i + '" name="sub_dept_name">' + sub_dept_name + '</div>' +
                            '   </td>' +
                            '   <td class="text-nowrap">' +
                            '       <div id="status_staff' + i + '" name="status_staff">' + status_staff + '</div>' +
                            '   </td>' +
                            '</tr>';
                            
                            $("#data-jadwalkerja-karyawan tbody").append($htmlTable);
                        }
        
                    }

                    notif({
                        msg: "<b>Success:</b> Data berhasil di Tempel.",
                        type: "success"
                    });

                    $('#btn-paste').removeClass("btn-loading");
                    $("#btn-paste").html('<i class="fa fa-paste"></i> Tempel');
                    $("#btn-paste").attr("disabled", false);
        
                },
                error: function(res){
                    $('#btn-paste').removeClass("btn-loading");
                    $("#btn-paste").html('<i class="fa fa-paste"></i> Tempel');
                    $("#btn-paste").attr("disabled", false);

                    notif({
                        msg: "<b>Oops!</b> An Error Occurred",
                        type: "error",
                        position: "center"
                    });
                }
            });
            
        });
          

        $('#selectAllData').click(function() {            
            var isChecked = $(this).prop("checked");
            $('#data-jadwalkerja-karyawan tr:has(td)').find('input[type="checkbox"]').prop('checked', isChecked);
        });
        
        $('#data-jadwalkerja-karyawan').on('click', 'tbody td, thead th:first-child', function(e){

            var isChecked = $(this).prop("checked");
            var isHeaderChecked = $("#selectAllData").prop("checked");
            if (isChecked == false && isHeaderChecked)
                $("#selectAllData").prop('checked', isChecked);
            else {
                $('#data-jadwalkerja-karyawan tbody td, thead th:first-child').find('input[type="checkbox"]').each(function() {
                    if ($(this).prop("checked") == false)
                    isChecked = false;
                });
                //console.log(isChecked);
                $("#selectAllData").prop('checked', isChecked);
            }
        });

        $('#btn-simpan').click(function() {            

            var html_table_data = "";
            var arrayHtml = [];
            var firstRow = true;

            $("#btn-simpan").addClass("btn-loading");
            $("#btn-simpan").html('Loading...');
            $("#btn-simpan").attr("disabled", true);

            //Loop through all checked CheckBoxes in GridView.
            $("#data-jadwalkerja-karyawan input[type=checkbox]:checked").each(function () {
                html_table_data = "";
                if (firstRow) {
                    firstRow = false;
                } else {

                    $(this).closest('tr').find(':input').each(function () {
                        html_table_data += '"' + $(this).attr("name") + '": "' + $(this).val() + '", ';
                    });

                    html_table_data = html_table_data.slice(0, -2) + '';
                    html_table_data = "{" + html_table_data + "}";
                    arrayHtml.push(JSON.parse(html_table_data));
                    //console.log(html_table_data);
                }
           });
 
            console.log(arrayHtml);

            $.ajax({
                type:"POST",
                url: "{{route('hris.datajadwalkerjalog.process')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    arrayHtml:arrayHtml,
                },
                dataType: 'json',
                success: function(res){
                    notif({
                        msg: "<b>Success:</b> Data berhasil di Inject di Data Kehadiran.",
                        type: "success"
                    });

                    $('#btn-simpan').removeClass("btn-loading");
                    $("#btn-simpan").html('<i class="fa fa-save"></i> Simpan');
                    $("#btn-simpan").attr("disabled", false);

                    setTimeout(function myFunction() {
                        location.reload();
                      }, 3000);           
                
                },
                error: function(res){
                    notif({
                        msg: "<b>Oops!</b> Inject data jadwal kerja GAGAL.",
                        type: "error",
                        position: "center"
                    });

                    $('#btn-simpan').removeClass("btn-loading");
                    $("#btn-simpan").html('<i class="fa fa-save"></i> Simpan');
                    $("#btn-simpan").attr("disabled", false);
        
                }
            });

        });
        
        $('#btn-remove').click(function() {            
            var notFirstRow = true;
            var isHeaderChecked = $("#selectAllData").prop("checked");
            $("#data-jadwalkerja-karyawan input[type=checkbox]:checked").each(function () {
                if (notFirstRow && isHeaderChecked)
                {
                    notFirstRow = false;
                } else {
                    $(this).parents("tr").remove();                    
                }                
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
        $('#set_mulai_jam_kerja').keyup(function(){
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
        $('#set_mulai_jam_kerja').blur(function(){
        var val = this.value;
        val = (/^(([01][0-9]|2[0-3])h)|(([01][0-9]|2[0-3]):[0-5][0-9])$/.test(val) ? val : "");
        this.value = val;
        });
                
        // Apply input rules as the user types or pastes input
        $('#set_akhir_jam_kerja').keyup(function(){
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
        $('#set_akhir_jam_kerja').blur(function(){
        var val = this.value;
        val = (/^(([01][0-9]|2[0-3])h)|(([01][0-9]|2[0-3]):[0-5][0-9])$/.test(val) ? val : "");
        this.value = val;
        });

    </script>

@endsection
