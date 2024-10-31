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

    <!-- Time picker css-->
    <link href="{{URL::asset('assets/plugins/time-picker/jquery.timepicker.css')}}" rel="stylesheet" />

	<!---Sweetalert Css-->
	<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

    <style>
        .table-responsive{
            height:500px;
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
            <li><a href="#">Absensi Karyawan</a></li>
            <li class="active"><span>Koreksi Potongan</span></li>
        </ol>
        <div class="ml-auto">
            <div class="input-group">
                <div class="text-white">
                    <a href="{{route('hris.koreksipotongan.format')}}" id="btn-examimport" class="btn btn-icon btn-orange text-white p-0 mr-1" data-toggle="tooltip" title="" data-original-title="Format File Excel"><i class="fa fa-file-excel-o"></i>Format File </a>
                    <button type="button" id="btn-import" class="btn btn-icon btn-warning text-white p-0 mr-1"  data-target="#import_koreksipotongan" data-toggle="modal" title="" data-original-title="Import Data Dari File Excel"><i class="fa fa-file-excel-o"></i> Import Data</button>
                </div>
                    <!-- modal -->
             
                    <form id="upload" name="custForm" action="{{route ('hris.import.koreksipotongan')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal fade" id="import_koreksipotongan" role="dialog" data-backdrop="static" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary p-2">
                                                <h4 class="modal-title pl-2 font-weight-bold" >Import Koreksi Potongan</h4>
                                                <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                                                    <i class="fa fa-remove"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">file import : </label>
                                                            <input class="form-control" name="file_import" type="file" accept=".xlsx, .xls, .csv" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-primary p-1">
                                                <div class="btn-list">
                                                    <button type="submit" id="export_grade_bpjs" class="btn btn-secondary btn-app">Simpan</button>
                                                    <!-- <button type="button" id="" class="btn btn-warning btn-app" data-dismiss="modal">Tutup</button> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- end modal -->

                <a href="#" id="btn-exportexcel" class="btn btn-primary p-0 mr-1 text-white btn-icon" data-target="#export_potonganupah" data-toggle="modal" title="" data-original-title="Export Data to Excel">
                    <span>
                        <i class="fa fa-file-excel-o"></i>
                    </span>
                </a>

                <!-- modal -->
                <form id="upload" name="custForm" action="{{route ('hris.export.koreksipotongan')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal fade" id="export_potonganupah" role="dialog" data-backdrop="static" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary p-2">
                                                <h4 class="modal-title pl-2 font-weight-bold" >Export Potongan Upah</h4>
                                                <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                                                    <i class="fa fa-remove"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">Priode payroll : </label>
                                                            <select id="" class="form-control" name="priode_payroll">
                                                            @foreach($priode_potongan as $key2 => $value2)
                                                                <option name="priode_payroll" value="{{$value2['periode']}}">{{$value2['periode']}}</option>    
                                                            @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-primary p-1">
                                                <div class="btn-list">
                                                    <button type="submit" id="export_grade_bpjs" class="btn btn-secondary btn-app">Export</button>
                                                    <!-- <button type="button" id="" class="btn btn-warning btn-app" data-dismiss="modal">Tutup</button> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- end modal -->

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
        <div class="col-sm-12 col-md-12 col-lg-7 col-xl-7">
            <!-- Begin Form Edit Absen Karyawan -->

            <div id="data-cari-koreksipotongan" class="card shadow">
                <div class="card-header bg-primary p-3">
                    <div class="card-title">CARI DATA</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="card-body m-0">
                    <div class="table-responsive">
                        <table id="datatable-ajax-crud"
                            class="table table-sm table-striped table-hover w-100">
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

            <div id="data-karyawan" class="card shadow">
                <div class="card-header bg-primary p-3">
                    <div class="card-title">CARI DATA KARYAWAN</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="card-body m-0">
                    <div class="table-responsive">
                        <table id="datatable-ajax-karyawan"
                            class="table table-sm table-striped table-hover table-bordered w-100">
                            <thead>
                                <tr class="text-center">
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
        </div>        
        <div class="col-sm-12 col-md-12 col-lg-5 col-xl-5">
            <!-- Begin Form Edit Absen Karyawan -->
            <div id="data-add-koreksipotongan" class="card shadow">
                <div class="card-header bg-primary p-2">
                    <div class="card-title">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle pl-1 pt-0 pb-0 pr-1 mr-1 text-sm" data-toggle="dropdown">
                                <i class="fa fa-navicon"></i>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="javascript:void(0)" id="btn-print"><i class="fa fa-print"></i> Print</a></li>
                                <li><a href="javascript:void(0)" id="btn-add"><i class="fa fa-plus"></i> Tambah</a></li>
                                <li><a href="javascript:void(0)" id="btn-edit"><i class="fa fa-edit"></i> Edit</a></li>
                                <li><a href="javascript:void(0)" id="btn-remove"><i class="fa fa-remove"></i> Hapus</a></li>
                            </ul>
                        </div>                        
                        KOREKSI POTONGAN KARYAWAN
                    </div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- BEGIN FORM-->
                    {!! Form::open(['route' => 'hris.koreksipotongan.create', 'id' => 'form1', 'name' => 'form1', 'method'=>'post']) !!}

                    <input id="uuid" type="hidden">
                    <input id="enroll_id" type="hidden">
                    <input id="nik" type="hidden">
                    <input id="employee_name" type="hidden">
                    <input id="site_nirwana_id" type="hidden">
                    <input id="site_nirwana_name" type="hidden">
                    <input id="department_id" type="hidden">
                    <input id="department_name" type="hidden">
                    <input id="sub_dept_id" type="hidden">
                    <input id="sub_dept_name" type="hidden">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Nomor Koreksi Potongan</label>
                                <input readonly id="kode_koreksi_potongan" name="kode_koreksi_potongan" type="text" class="form-control" placeholder="Kode Koreksi Potongan" maxlength="50" size="50">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tanggal Koreksi</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                        </div>
                                    </div>
                                    <input readonly id="tanggal_koreksi" name="tanggal_koreksi" type="text" class="form-control fc-datepicker" placeholder="Tanggal Koreksi" maxlength="50" size="50">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nama Department : </label>
                                <input readonly id="nama_department" name="nama_department" type="text" class="form-control" placeholder="NAMA DEPARTMENT" maxlength="50" size="50">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nama Karyawan : </label>
                                <input readonly id="nama_karyawan" name="nama_karyawan" type="text" class="form-control" placeholder="NAMA KARYAWAN" maxlength="50" size="50">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Jumlah Rp Potongan : </label>
                                <input id="jumlah_rp_potongan" name="jumlah_rp_potongan" type="text" class="form-control" placeholder="0" maxlength="50" size="50">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Periode Koreksi Potongan : </label>
                                <input readonly id="periode_tanggal_koreksi" name="periode_tanggal_koreksi" type="text" class="form-control" placeholder="MM/DD/YYYY MM/DD/YYYY">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nama Potongan : </label>
                                <select id="jenis_potongan" class="form-control">
                                    <option value=1>POTONGAN BPJS TK</option>
                                    <option value=2>POTONGAN BPJS KS</option>
                                    <option value=3>POTONGAN BAZAR</option>
                                    <option value=4>POTONGAN KAS BON</option>
                                    <option value=7>POTONGAN UPAH</option>
                                    <option value=8>POTONGAN LEMBUR</option>
                                    <option value=6>PIUTANG KARYAWAN</option>
                                    <option value=5>POTONGAN LAINNYA</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Keterangan : </label>
                                <textarea class="form-control mb-2" id="keterangan" name="keterangan" rows="2" placeholder="Keterangan"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- </form> --}}
                    {!! Form::close() !!}
                    <!-- END FORM-->
                </div>
                <div class="progress progress-xs mb-0">
                    <div id="progress-show-1" class="progress-bar progress-bar-indeterminate bg-green"></div>
                    <div id="progress-hide-1" class="progress-bar"></div>
                </div>
                <div class="card-footer bg-primary pl-2 pt-1 pb-2">
                    <button type="button" id="btn-save" class="btn btn-secondary btn-app mt-1">
                        <span>
                            <i class="fa fa-save"></i>
                        </span>
                        Add</button>
                    <button type="button" id="btn-cancel" class="btn btn-warning btn-app mt-1">
                        <span>
                            <i class="fa fa-close"></i>
                        </span>
                        Cancel</button>
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

    <!-- Timepicker js -->
    <script src="{{URL::asset('assets/plugins/time-picker/jquery.timepicker.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/time-picker/toggles.min.js')}}"></script>

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

        function defaultDate(s) {
            if(s) {
                var bits = s.split('/');
                var d = bits[2] + '-' + bits[0] + '-' + bits[1];
            }
            return d;
        }

        //Date range as a button
        $('#periode_tanggal_koreksi').daterangepicker({
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

        $('#progress-show-1').hide();
        $('#data-karyawan').hide();
        $("#form1 :input").prop("disabled", true);
        $("#btn-save").prop("disabled", true);
        $("#btn-cancel").prop("disabled", true);
        $("#periode_tanggal_koreksi").val('');
        
        $('body').on('keyup', '#jumlah_rp_potongan', function (event) {
            this.value = this.value.replace(/[^-0-9\.]/g,'');
        });

        $('body').on('click', '#btn-refresh-page', function (event) {
            location.reload();
        });

        $('body').on('click', '#btn-add', function (event) {
            $("#form1 :input").prop("disabled", false);
            $("#btn-save").prop("disabled", false);
            $("#btn-cancel").prop("disabled", false);
            $("#form1").trigger('reset');
            $('#data-cari-koreksipotongan').hide("slow");
            $('#data-karyawan').show("slow");
            $('#btn-save').html('<i class="fa fa-save"></i> Add');

            $("#uuid").val(null)

            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            
            today = yyyy + '-' + mm + '-' + dd;
            
            $('#tanggal_koreksi').val(today); 

            var table1 = $('#datatable-ajax-karyawan').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                "ajax": {
                    "url": "{{ route('hris.employeeatr.ajax_getempatr') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                },
                columns: [
                    {
                        title: 'Nomor Absen',
                        data: 'enroll_id',
                        name: 'enroll_id'
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
                        title: 'Department',
                        data: 'department_name',
                        name: 'department_name'
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
                ]
            });

            table1.draw();

            $('#datatable-ajax-karyawan tbody').on('click', 'tr', function () {
                var tr = $(this).closest('tr');
                var row = table1.row(tr);

                var data = row.data();
        
                $("#datatable-ajax-karyawan tbody tr").removeClass('bg-cyan');
                $(this).addClass('bg-cyan');

                $('#employee_name').val(data['employee_name']);  
                $('#site_nirwana_id').val(data['site_nirwana_id']);  
                $('#site_nirwana_name').val(data['site_nirwana_name']);  
                $('#department_id').val(data['department_id']);  
                $('#department_name').val(data['department_name']);  
                $('#sub_dept_id').val(data['sub_dept_id']);  
                $('#sub_dept_name').val(data['sub_dept_name']);  

                $('#enroll_id').val(data['enroll_id']);  
                $('#nik').val(data['nik']);
                $('#nama_karyawan').val(data['enroll_id'] + " - " + data['nik'] + " - " + data['employee_name']);
                $('#nama_department').val(data['department_name'] + " - " + data['sub_dept_name']);

                var tglkoreksi = $('#tanggal_koreksi').val().split('-')
                var gettimenow = new Date();

                var kode_koreksi_potongan = tglkoreksi[0] + tglkoreksi[1] + gettimenow.getMinutes() + gettimenow.getSeconds() + data['nik']
                $('#kode_koreksi_potongan').val(kode_koreksi_potongan);  
            });           

        });
        
        $('body').on('click', '#btn-cancel', function (event) {
            location.reload();
        });

        $('body').on('click', '#btn-save', function (event) {
            var periodetglkoreksi = $("#periode_tanggal_koreksi").val();
            var tgl = periodetglkoreksi.split(' - ');
            var tanggal = defaultDate(tgl[0]);
            
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

                        var uuid = $("#uuid").val();
                        var kode_koreksi_potongan = $("#kode_koreksi_potongan").val();
                        var tanggal_koreksi = $("#tanggal_koreksi").val();
                        var enroll_id = $("#enroll_id").val();
                        var nik = $("#nik").val();
                        var employee_name = $("#employee_name").val();
                        var site_nirwana_id = $("#site_nirwana_id").val();
                        var site_nirwana_name = $("#site_nirwana_name").val();
                        var department_id = $("#department_id").val();
                        var department_name = $("#department_name").val();
                        var sub_dept_id = $("#sub_dept_id").val();
                        var sub_dept_name = $("#sub_dept_name").val();
                        var jumlah_rp_potongan = $("#jumlah_rp_potongan").val();
                        var periode_tanggal_koreksi = $("#periode_tanggal_koreksi").val();
                        var jenis_potongan = $("#jenis_potongan").val();
                        var keterangan = $("#keterangan").val();

                        $('#btn-save').addClass("btn-loading");
                        $("#btn-save").html('Please wait...');
                        $("#btn-save").attr("disabled", true);
                        $('#progress-show-1').show();
                        $('#progress-hide-1').hide();
                        
                        if (uuid) {

                            $.ajax({
                                type:"POST",
                                url: "{{route('hris.koreksipotongan.update')}}",
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                data: {
                                    uuid:uuid,
                                    kode_koreksi_potongan:kode_koreksi_potongan,
                                    tanggal_koreksi:tanggal_koreksi,
                                    enroll_id:enroll_id,
                                    nik:nik,
                                    employee_name:employee_name,
                                    site_nirwana_id:site_nirwana_id,
                                    site_nirwana_name:site_nirwana_name,
                                    department_id:department_id,
                                    department_name:department_name,
                                    sub_dept_id:sub_dept_id,
                                    sub_dept_name:sub_dept_name,
                                    jumlah_rp_potongan:jumlah_rp_potongan,
                                    periode_tanggal_koreksi:periode_tanggal_koreksi,
                                    jenis_potongan:jenis_potongan,
                                    keterangan:keterangan,
                                },
                                dataType: 'json',
                                success: function(res){
                                    notif({
                                        msg: "<b>Info:</b> Data berhasil di update.",
                                        type: "info"
                                    });    
                                },
                                error: function(res){
                                    notif({
                                        msg: "<b>Error:</b> Oops data gagal di update.",
                                        type: "error"
                                    });    
                                }
                            });

                            setTimeout(function myFunction() {
                                $("#datatable-ajax-crud").DataTable().ajax.reload();
                            }, 3000); 

                        } else {

                            $.ajax({
                                type:"POST",
                                url: "{{route('hris.koreksipotongan.create')}}",
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                data: {
                                    kode_koreksi_potongan:kode_koreksi_potongan,
                                    tanggal_koreksi:tanggal_koreksi,
                                    enroll_id:enroll_id,
                                    nik:nik,
                                    employee_name:employee_name,
                                    site_nirwana_id:site_nirwana_id,
                                    site_nirwana_name:site_nirwana_name,
                                    department_id:department_id,
                                    department_name:department_name,
                                    sub_dept_id:sub_dept_id,
                                    sub_dept_name:sub_dept_name,
                                    jumlah_rp_potongan:jumlah_rp_potongan,
                                    periode_tanggal_koreksi:periode_tanggal_koreksi,
                                    jenis_potongan:jenis_potongan,
                                    keterangan:keterangan,
                                },
                                dataType: 'json',
                                success: function(res){
                                    notif({
                                        msg: "<b>Info:</b> Data berhasil di simpan.",
                                        type: "info"
                                    });    
                                },
                                error: function(res){
                                    notif({
                                        msg: "<b>Error:</b> Oops data gagal di simpan.",
                                        type: "error"
                                    });    
                                }
                            });

                            setTimeout(function myFunction() {
                                location.reload();
                            }, 3000); 
                        }

                        $('#progress-show-1').hide();
                        $('#progress-hide-1').show();
                        $('#btn-save').removeClass("btn-loading");
                        $("#btn-save").html('<span><i class="fa fa-save"></i></span> Add');
                        $("#form1 :input").prop("disabled", true);
                        $("#btn-save").prop("disabled", true);
                        $("#btn-cancel").prop("disabled", true);    
                        
                                  
           
                    }

                },
                error: function(res){
                                
                }
            });           
                        
        });

        $('body').on('click', '#btn-remove', function (event) {

            var periodetglkoreksi = $("#periode_tanggal_koreksi").val();
            var tgl = periodetglkoreksi.split(' - ');
            var tanggal = defaultDate(tgl[0]);

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

                        var uuid = $("#uuid").val();
                        var kode_koreksi_potongan = $("#kode_koreksi_potongan").val();

                        if (kode_koreksi_potongan.length > 0) {
                            message = "Anda Yakin Ingin Menghapus " + kode_koreksi_potongan + " !!!";
                            type = "warning";
                            swal({
                                title: message,
                                type: type,
                                showCancelButton: true,
                                confirmButtonText: 'Saya Yakin',
                                cancelButtonText: 'Tutup'
                            },function(isConfirm){
                                if(isConfirm) {
                
                                    $("#form1 :input").prop("disabled", true);
                                    $("#btn-save").prop("disabled", true);
                                    $("#btn-cancel").prop("disabled", true);    
                                    $('#progress-show-1').show();
                                    $('#progress-hide-1').hide();
                        
                                    $.ajax({
                                        type:"POST",
                                        url: "{{route('hris.koreksipotongan.destroy')}}",
                                        dataType: 'json',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                        data: {
                                            uuid:uuid,
                                        },
                                        dataType: 'json',
                                        success: function(res){
                                            notif({
                                                msg: "<b>Info:</b> Data berhasil di hapus.",
                                                type: "info"
                                            });    
                                        },
                                        error: function(res){
                                            notif({
                                                msg: "<b>Error:</b> Oops data gagal di hapus.",
                                                type: "error"
                                            });    
                                        }
                                    });
                        
                                    $('#progress-show-1').hide();
                                    $('#progress-hide-1').show();    
                
                                    setTimeout(function myFunction() {
                                        location.reload();
                                    }, 3000);                   
                
                                } else {
                                    // else everythings
                                }
                            });
                        } else {
                            notif({
                                msg: "<b>Warning:</b> Data yang dipilih tidak ada.",
                                type: "warning"
                            });
                        }

                    }

                },
                error: function(res){
                                
                }
            });           
                        
        });
        
        $(document).ready(function() {
            var table1 = $('#datatable-ajax-crud').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                destroy: true,
                "language": {
                    processing: '<center><div class="dimmer active"><div class="lds-hourglass p-0 m-0"></div></div> Mohon untuk menunggu...</center> '},
                "ajax": {
                    "url": "{{ route('hris.koreksipotongan.ajax_datakoreksipotongan') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                },
                columns: [
                    {
                        title: 'UUID',
                        data: 'uuid',
                        name: 'uuid'
                    },
                    {
                        title: 'KODE KOREKSI POTONGAN',
                        data: 'kode_koreksi_potongan',
                        name: 'kode_koreksi_potongan'
                    },
                    {
                        title: 'PERIODE KOREKSI',
                        data: 'periode_tanggal_koreksi_format',
                        name: 'periode_tanggal_koreksi_format'
                    },
                    {
                        title: 'TANGGAL',
                        data: 'tanggal_koreksi',
                        name: 'tanggal_koreksi'
                    },
                    {
                        title: 'NO. ABSEN',
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
                        title: 'RP POTONGAN',
                        data: 'jumlah_rp_potongan_format',
                        name: 'jumlah_rp_potongan_format'
                    },
                    {
                        title: 'BAGIAN',
                        data: 'sub_dept_name',
                        name: 'sub_dept_name'
                    },
                    {
                        title: 'TANGGAL DIBUAT',
                        data: 'created_at',
                        name: 'created_at'
                    },
                ],
                columnDefs: [
                    {
                        'visible': false,
                        'targets': [0,1,3,9]
                    },
                    {
                        orderable: false,
                        targets: [2,4,5,6,7,8]
                    },
                    {
                        className: "w-5 text-center text-nowrap",
                        targets: [2,4,5]
                    },
                    {
                        className: "w-5 text-right text-nowrap",
                        targets: [7]
                    },
                    {
                        className: "w-50 text-nowrap",
                        targets: [6,8]
                    }
                ],
                order: [
                    [9, 'desc']
                ]
            });

            table1.draw();

            $('#datatable-ajax-crud tbody').on('click', 'tr', function () {
                var tr = $(this).closest('tr');
                var row = table1.row(tr);

                var data = row.data();
        
                $("#form1 :input").prop("disabled", true);
                $("#btn-save").prop("disabled", true);
                $("#btn-cancel").prop("disabled", true);
                
                $("#datatable-ajax-crud tbody tr").removeClass('bg-cyan');
                $(this).addClass('bg-cyan');

                $("#uuid").val(data["uuid"]);
                $("#kode_koreksi_potongan").val(data["kode_koreksi_potongan"]);
                $("#tanggal_koreksi").val(data["tanggal_koreksi"]);

                $("#nama_karyawan").val(data["enroll_id"] + " - " + data["nik"] + " - " + data["employee_name"]);
                $("#nama_department").val(data["department_name"] + " - " + data["sub_dept_name"]);

                $("#enroll_id").val(data["enroll_id"]);
                $("#nik").val(data["nik"]);
                $("#employee_name").val(data["employee_name"]);
                $("#site_nirwana_id").val(data["site_nirwana_id"]);
                $("#site_nirwana_name").val(data["site_nirwana_name"]);
                $("#department_id").val(data["department_id"]);
                $("#department_name").val(data["department_name"]);
                $("#sub_dept_id").val(data["sub_dept_id"]);
                $("#sub_dept_name").val(data["sub_dept_name"]);
                $("#jumlah_rp_potongan").val(data["jumlah_rp_potongan"]);
                $("#periode_tanggal_koreksi").val(data["periode_tanggal_koreksi"]);
                $("#jenis_potongan").val(data["jenis_potongan"]).trigger("change");
                $("#keterangan").val(data["keterangan"]);
    
             });

        });
        
    </script>
    
    <script>
        $('body').on('click', '#btn-edit', function (event) {
            var enroll_id = $('#enroll_id').val();

            if (enroll_id.length > 0) {
                $("#form1 :input").prop("disabled", false);
                $("#btn-save").prop("disabled", false);
                $("#btn-cancel").prop("disabled", false);
                $('#btn-save').html('<i class="fa fa-edit"></i> Update');
                $("#btn-periksa_enroll_id").attr("disabled", true);
                $("#enroll_id").attr("readonly", true);
                $('#is_periksaenroll_id').val(1);
                $("#btn-periksa_nik").attr("disabled", true);
                $("#nik").attr("readonly", true);
                $('#is_periksanik').val(1);
                
            } else {
                notif({
                    msg: "<b>Warning:</b> Data belum ada yang di pilih.",
                    type: "warning"
                });
                $("#form1 :input").prop("disabled", true);
                $("#btn-save").prop("disabled", true);
                $("#btn-reset").prop("disabled", true);
                $("#btn-cancel").prop("disabled", true);
            }
        });
        
        @if(Session::has('error'))
           var meseg = "{{Session::get('error')}}";
            notif({
                msg: "<b>Error:</b> "+meseg,
                type: "error"
            });  
        @endif

        @if(Session::has('success'))
            notif({
                msg: "<b>Info:</b> Data berhasil di simpan.",
                type: "info"
            }); 
        @endif    
    </script>
    

@endsection
