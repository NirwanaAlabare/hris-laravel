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
            <li><a href="#">ABSENSI KARYAWAN</a></li>
            <li class="active"><span>KOREKSI UPAH</span></li>
        </ol>
        <div class="ml-auto">
            <div class="input-group">

                <div class="text-white">
                    <a href="{{route('hris.koreksiupah.format')}}" id="btn-examimport" class="btn btn-icon btn-orange text-white p-0 mr-1" data-toggle="tooltip" title="" data-original-title="Format File Excel"><i class="fa fa-file-excel-o"></i>Format File </a>
                    <button type="button" id="btn-import" class="btn btn-icon btn-warning text-white p-0 mr-1"  data-target="#import_koreksiupah" data-toggle="modal" title="" data-original-title="Import Data Dari File Excel"><i class="fa fa-file-excel-o"></i> Import Data</button>
                </div>
                    <!-- modal -->

                    <form id="upload" name="custForm" action="{{route ('hris.employeeatr.import.koreksiupah')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal fade" id="import_koreksiupah" role="dialog" data-backdrop="static" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary p-2">
                                                <h4 class="modal-title pl-2 font-weight-bold" >Import Koreksi Upah</h4>
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

                <a href="#" id="btn-exportexcel" class="btn btn-primary p-0 mr-1 text-white btn-icon"   data-target="#export_koreksiupah" data-toggle="modal" title="" data-original-title="Export Data to Excel">
                    <span>
                        <i class="fa fa-file-excel-o"></i>
                    </span>
                </a>
                    <!-- modal -->
                    <form id="upload" name="custForm" action="{{route ('hris.employeeatr.export.koreksiupah')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal fade" id="export_koreksiupah" role="dialog" data-backdrop="static" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary p-2">
                                                <h4 class="modal-title pl-2 font-weight-bold" >Export Koreksi Upah</h4>
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
                                                            @foreach($priode_koreksi as $key2 => $value2)
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
    <div class="modal fade" id="insentif_jabatan_modal" role="dialog" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 66%;" role="document">
            <div class="row">
                <div class="col-12">
                    <div class="modal-content">
                        <div class="modal-header bg-primary pb-1 pt-2">
                            <label class="form-label" style="font-size:13pt">Insentif Jabatan</label>
                            <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                        <div class="modal-body py-0">
                            <div class="row">
                                <div class="col-5 py-5 px-5">
                                    <table id="datatable_ins_jabatan" class="table table-sm table-hover table-striped w-100">
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
                                <div class="col-7 pt-5 px-6" style="background-color:rgb(188, 244, 244)">
                                    <div class="row py-1">
                                        <div class="col-12">
                                            <div class="form-group mb-0">
                                                <div class="row">
                                                    <div class="col-4 pt-2">
                                                        <label class="form-label" style="font-weight: bold">Periode Kehadiran</label>
                                                    </div>
                                                    <div class="col-8">
                                                        <select id="periode_tanggal_kehadiran" name="periode_tanggal_kehadiran" class="form-control" style="background-color: white">
                                                            @foreach ($periode_payroll as $r_periode_payroll)
                                                                <option value="{{$r_periode_payroll->periode_payroll}}">
                                                                @php
                                                                    setlocale(LC_ALL, 'id-ID', 'id_ID');
                                                                    $datePeriode = explode(" s/d ", $r_periode_payroll->periode_payroll);
                                                                    echo strtoupper(strftime("%d %b %Y", strtotime($datePeriode[0])) . ' s/d ' . strftime("%d %b %Y", strtotime($datePeriode[1])));
                                                                @endphp
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row py-1">
                                        <div class="col-12">
                                            <div class="form-group mb-0 pb-1">
                                                <div class="row">
                                                    <div class="col-4 pt-2">
                                                        <label class="form-label" style="font-weight: bold">Tanggal Koreksi</label>
                                                    </div>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                                </div>
                                                            </div>
                                                            <input id="tanggal_koreksi2" name="tanggal_koreksi2" style="background-color: white" type="text" class="form-control fc-datepicker" placeholder="Tanggal Koreksi" maxlength="50" size="50">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row py-1">
                                        <div class="col-12">
                                            <div class="form-group mb-0 pb-1">
                                                <div class="row">
                                                    <div class="col-4 pt-2">
                                                        <label class="form-label" style="font-weight: bold">Nama Karyawan</label>
                                                    </div>
                                                    <div class="col-8">
                                                        <select id="nama_karyawan2" name="nama_karyawan2" class="form-control" style="background-color: white">
                                                        </select>
                                                        {{-- <input id="nama_karyawan2" name="nama_karyawan2" style="background-color: white" type="text" class="form-control" maxlength="50" size="50"> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row py-1">
                                        <div class="col-12">
                                            <div class="form-group mb-0 pb-1">
                                                <div class="row">
                                                    <div class="col-4 pt-2">
                                                        <label class="form-label" style="font-weight: bold">Jumlah Rp.</label>
                                                    </div>
                                                    <div class="col-8">
                                                        <input id="jumlah_rp_potongan2" name="jumlah_rp_potongan2" type="number" class="form-control" style="background-color: white" placeholder="0" maxlength="50" size="50">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row pt-2 pb-5">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-4">
                                                </div>
                                                <div class="col-8">
                                                    <button class="btn btn-primary py-0" id="store_insentif_jabatan">&nbsp;&nbsp;<i class="fa fa-floppy-o"></i> SAVE&nbsp;&nbsp;&nbsp;</button>
                                                    <button class="btn btn-danger py-0" id="delete_insentif_jabatan" disabled><i class="fa fa-trash-o"></i> DELETE</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-header bg-primary py-1">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- row -->
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-7 col-xl-7">
            <!-- Begin Form Edit Absen Karyawan -->

            <div id="data-cari-koreksiupah" class="card shadow">
                <div class="card-header bg-primary p-3">
                    <div class="card-title">CARI DATA</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="card-body m-0 pt-2">
                    <div class="row">
                        <div class="col-4">
                            <button class="btn btn-primary pb-0 pt-1" data-target="#insentif_jabatan_modal" data-toggle="modal" id="insentif_jabatan_modal_button"><span class="fa fa-plus"></span> Insentif Jabatan</button>
                        </div>
                        <div class="col-4">
                        </div>
                    </div>
                    <div class="row pb-3">
                        <div class="col-4 pt-2">
                            <label class="form-label text-primary">PERIODE TANGGAL KOREKSI</label>
                        </div>
                        <div class="col-4">
                            <select id="periode_payroll" name="periode_payroll" class="form-control">
                                @foreach ($periode_payroll as $r_periode_payroll)
                                    <option value="{{$r_periode_payroll->periode_payroll}}">
                                    @php
                                        setlocale(LC_ALL, 'id-ID', 'id_ID');
                                        $datePeriode = explode(" s/d ", $r_periode_payroll->periode_payroll);
                                        echo strtoupper(strftime("%d %b %Y", strtotime($datePeriode[0])) . ' s/d ' . strftime("%d %b %Y", strtotime($datePeriode[1])));
                                    @endphp
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
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
            <div id="data-add-koreksiupah" class="card shadow">
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
                        KOREKSI UPAH KARYAWAN
                    </div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- BEGIN FORM-->
                    {!! Form::open(['route' => 'hris.koreksiupah.create', 'id' => 'form1', 'name' => 'form1', 'method'=>'post']) !!}

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
                                <label class="form-label">NO. KOREKSI UPAH</label>
                                <input readonly id="kode_koreksi_upah" name="kode_koreksi_upah" type="text" class="form-control" placeholder="Kode Koreksi Upah" maxlength="50" size="50">
                            </div>
                            <div class="form-group">
                                <label class="form-label">TANGGAL KOREKSI</label>
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
                                <label class="form-label">DEPARTMENT / BAGIAN : </label>
                                <input readonly id="nama_department" name="nama_department" type="text" class="form-control" placeholder="NAMA DEPARTMENT" maxlength="50" size="50">
                            </div>
                            <div class="form-group">
                                <label class="form-label">NAMA KARYAWAN : </label>
                                <input readonly id="nama_karyawan" name="nama_karyawan" type="text" class="form-control" placeholder="NAMA KARYAWAN" maxlength="50" size="50">
                            </div>
                            <div class="form-group">
                                <label class="form-label">JUMLAH RP : </label>
                                <input id="jumlah_rp_potongan" name="jumlah_rp_potongan" type="text" class="form-control" placeholder="0" maxlength="50" size="50">
                            </div>
                            <div class="form-group">
                                <label class="form-label">PERIODE KOREKSI UPAH : </label>
                                <input readonly id="periode_tanggal_koreksi" name="periode_tanggal_koreksi" type="text" class="form-control" placeholder="MM/DD/YYYY MM/DD/YYYY">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nama Koreksi : </label>
                                <select id="jenis_koreksi" class="form-control">
                                    <option value=3>LEMBUR</option>
                                    <option value=1>UPAH</option>
                                    <option value=2>INSENTIF JABATAN</option>
                                    <option value=4>INSENTIF LAINNYA</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">KETERANGAN : </label>
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
                        TAMBAH</button>
                    <button type="button" id="btn-cancel" class="btn btn-warning btn-app mt-1">
                        <span>
                            <i class="fa fa-close"></i>
                        </span>
                        CANCEL</button>
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

        function formatRupiah(angka) {
            return "Rp " + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        $('#insentif_jabatan_modal_button').on('click',function(){
            $('#store_insentif_jabatan').attr("disabled");
            $('#delete_insentif_jabatan').attr("disabled");
            $("#datatable_ins_jabatan").DataTable().ajax.reload();
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;

            $('#tanggal_koreksi2').val(today);
            $('#nama_karyawan2').val('');
            $('#jumlah_rp_potongan2').val('');
            $.ajax({
                type:"POST",
                url: "{{route('hris.koreksiupah.get_active_employee')}}",
                data:{
                    tanggal_koreksi:today
                },
                success: function(res){
                    $("#nama_karyawan2").append("<option value=''>Daftar karyawan</option>");
                    for(i=0;i<res.length;i++) {
                        $("#nama_karyawan2").append("<option value="+res[i].enroll_id+">"+res[i].employee_name+"</option>");
                    }
                },
                error: function(res){
                    notif({
                        msg: "<b>Error:</b> Oops data gagal di update.",
                        type: "error"
                    });
                }
            });

        });
        $('#tanggal_koreksi2').on('change',function(){
            testing3();
            var today=$('#tanggal_koreksi2').val();
            $('#nama_karyawan2').empty();
            $.ajax({
                type:"POST",
                url: "{{route('hris.koreksiupah.get_active_employee')}}",
                data:{
                    tanggal_koreksi:today
                },
                success: function(res){
                    $("#nama_karyawan2").append("<option value=''>Daftar karyawan</option>");
                    for(i=0;i<res.length;i++) {
                        $("#nama_karyawan2").append("<option value="+res[i].enroll_id+">"+res[i].employee_name+"</option>");
                    }
                },
                error: function(res){
                    notif({
                        msg: "<b>Error:</b> Oops data gagal di update.",
                        type: "error"
                    });
                }
            });
        });
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
        $('#store_insentif_jabatan').on('click',function(){
            var tanggal_koreksi=$('#tanggal_koreksi2').val();
            var id=$('#nama_karyawan2').val();
            var jumlah_rp_potongan=$('#jumlah_rp_potongan2').val();
            var periode_tanggal_koreksi=$('#periode_tanggal_kehadiran').val();
            $.ajax({
                type:"POST",
                url: "{{route('hris.koreksiupah.add_position_insentif')}}",
                data:{
                    tanggal_koreksi:tanggal_koreksi,
                    id:id,
                    jumlah_rp_potongan:jumlah_rp_potongan,
                    periode_tanggal_koreksi:periode_tanggal_koreksi,
                },
                success: function(res){
                    $('#nama_karyawan2').val('');
                    $('#jumlah_rp_potongan2').val('');
                    $('#periode_tanggal_kehadiran').val();
                    $('#store_insentif_jabatan').html('&nbsp;&nbsp;<i class="fa fa-floppy-o"></i> SAVE &nbsp;&nbsp;&nbsp;');
                    $('#store_insentif_jabatan').prop("disabled", true);
                    $('#delete_insentif_jabatan').prop("disabled", true);
                    $("#datatable_ins_jabatan").DataTable().ajax.reload();
                    $("#datatable-ajax-crud").DataTable().ajax.reload();
                },
                error: function(res){
                    notif({
                        msg: "<b>Error:</b> Oops data gagal di update.",
                        type: "error"
                    });
                }
            });
        });
        $('#delete_insentif_jabatan').on('click',function(){
            var id=$('#nama_karyawan2').val();
            var periode_tanggal_koreksi=$('#periode_tanggal_kehadiran').val();
            $.ajax({
                type:"POST",
                url: "{{route('hris.koreksiupah.delete_position_insentif')}}",
                data:{
                    id:id,
                    periode_tanggal_koreksi:periode_tanggal_koreksi,
                },
                success: function(res){
                    $('#nama_karyawan2').val('');
                    $('#jumlah_rp_potongan2').val('');
                    $('#periode_tanggal_kehadiran').val();
                    $('#store_insentif_jabatan').html('&nbsp;&nbsp;<i class="fa fa-floppy-o"></i> SAVE &nbsp;&nbsp;&nbsp;');
                    $('#store_insentif_jabatan').prop("disabled", true);
                    $('#delete_insentif_jabatan').prop("disabled", true);
                    $("#datatable_ins_jabatan").DataTable().ajax.reload();
                    $("#datatable-ajax-crud").DataTable().ajax.reload();
                },
                error: function(res){
                    notif({
                        msg: "<b>Error:</b> Oops data gagal di delete.",
                        type: "error"
                    });
                }
            });
        });
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
            // this.value = this.value.replace(/[^-0-9\.]/g,'');
            let value = $(this).val().replace(/[^0-9]/g, ""); // Hapus semua karakter kecuali angka
            $(this).val(formatRupiah(value));

        });


        $('body').on('click', '#btn-refresh-page', function (event) {
            location.reload();
        });

        $('body').on('click', '#btn-add', function (event) {
            $("#form1 :input").prop("disabled", false);
            $("#btn-save").prop("disabled", false);
            $("#btn-cancel").prop("disabled", false);
            $("#form1").trigger('reset');
            $('#data-cari-koreksiupah').hide("slow");
            $('#data-karyawan').show("slow");
            $('#btn-save').html('<i class="fa fa-save"></i> TAMBAH');
            $("#uuid").val(null);

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

                var kode_koreksi_upah = tglkoreksi[0] + tglkoreksi[1] + gettimenow.getMinutes() + gettimenow.getSeconds() + data['nik']
                $('#kode_koreksi_upah').val(kode_koreksi_upah);
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
                        var kode_koreksi_upah = $("#kode_koreksi_upah").val();
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
                        var keterangan = $("#keterangan").val();
                        var jenis_koreksi = $("#jenis_koreksi").val();


                        $('#btn-save').addClass("btn-loading");
                        $("#btn-save").html('Please wait...');
                        $("#btn-save").attr("disabled", true);
                        $('#progress-show-1').show();
                        $('#progress-hide-1').hide();

                        if (uuid) {

                            $.ajax({
                                type:"POST",
                                url: "{{route('hris.koreksiupah.update')}}",
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                data: {
                                    uuid:uuid,
                                    kode_koreksi_upah:kode_koreksi_upah,
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
                                    keterangan:keterangan,
                                    jenis_koreksi:jenis_koreksi,

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
                                url: "{{route('hris.koreksiupah.create')}}",
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                data: {
                                    kode_koreksi_upah:kode_koreksi_upah,
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
                                    keterangan:keterangan,
                                    jenis_koreksi:jenis_koreksi,

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
                        $("#btn-save").html('<span><i class="fa fa-save"></i></span> TAMBAH');
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
                        var kode_koreksi_upah = $("#kode_koreksi_upah").val();

                        message = "Anda Yakin Ingin Menghapus " + kode_koreksi_upah + " !!!";
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
                                    url: "{{route('hris.koreksiupah.destroy')}}",
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

                    }

                },
                error: function(res){

                }
            });

        });
        var periode_payroll = $('#periode_payroll').val();

        function testing(){
            var periode_tanggal_koreksi = $('#periode_tanggal_kehadiran').val();
            $('#datatable_ins_jabatan').DataTable().ajax.reload(null, false);
        }
        function testing2(){
            var periode_tanggal_koreksi = $('#periode_payroll').val();
            $('#periode_tanggal_kehadiran').val($('#periode_payroll').val());
            $('#datatable_ins_jabatan').DataTable().ajax.reload(null, false);
        }
        function testing3(){
            var tanggal_koreksi = $('#tanggal_koreksi2').val();
            var nama_karyawan = $('#nama_karyawan2').val();
            var jumlah_rp_potongan = $('#jumlah_rp_potongan2').val();
            var periode_tanggal_kehadiran=$('#periode_tanggal_kehadiran').val();
            if(tanggal_koreksi!='' && nama_karyawan!='' && jumlah_rp_potongan!=''){
                $('#store_insentif_jabatan').removeAttr('disabled');
                $.ajax({
                    type:"POST",
                    url: "{{route('hris.koreksiupah.cek_koreksi_upah')}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        id:nama_karyawan,
                        periode_tanggal_kehadiran:periode_tanggal_kehadiran
                    },
                    success: function(res){
                        console.log(res);
                        if(res==1){
                            $('#store_insentif_jabatan').html('&nbsp;&nbsp;<span class="fa fa-edit"> UPDATE&nbsp&nbsp')
                        }else{
                            $('#store_insentif_jabatan').html('&nbsp;&nbsp;<span class="fa fa-floppy-o"> SAVE&nbsp&nbsp')
                        }
                    }
                });
            }else{
                $('#store_insentif_jabatan').prop('disabled',true);
            }
        }
        $(document).ready(function() {
            $('#periode_tanggal_kehadiran').val($('#periode_payroll').val());
            $('#delete_insentif_jabatan').attr("disabled");
            var table1 = $('#datatable-ajax-crud').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                destroy: true,
                "language": {
                    processing: '<center><div class="dimmer active"><div class="lds-hourglass p-0 m-0"></div></div> Mohon untuk menunggu...</center> '},
                "ajax": {
                    "url": "{{ route('hris.koreksiupah.ajax_datakoreksiupah') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                    "data": {
                        periode_payroll:periode_payroll,
                    },
                },
                columns: [
                    {
                        title: 'UUID',
                        data: 'uuid',
                        name: 'uuid'
                    },
                    {
                        title: 'KODE KOREKSI UPAH',
                        data: 'kode_koreksi_upah',
                        name: 'kode_koreksi_upah'
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
                $("#kode_koreksi_upah").val(data["kode_koreksi_upah"]);
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
                $("#jumlah_rp_potongan").val(formatRupiah(data["jumlah_rp_potongan"]));
                $("#periode_tanggal_koreksi").val(data["periode_tanggal_koreksi"]);
                $("#keterangan").val(data["keterangan"]);
                $("#jenis_koreksi").val(data["jenis_koreksi"]).trigger("change");


            });
            var table2 = $('#datatable_ins_jabatan').DataTable({
                processing: true,
                serverSide: false,
                searching: false,
                paging: false,
                destroy: true,
                "language": {
                    processing: '<center><div class="dimmer active"><div class="lds-hourglass p-0 m-0"></div></div> Mohon untuk menunggu...</center> '},
                "ajax": {
                    "url": "{{ route('hris.koreksiupah.ajax_datainsjabatan') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                    "data": function (d) {
                        d.periode_tanggal_koreksi = $('#periode_tanggal_kehadiran').val();
                    }
                },
                columns: [
                    {
                        title: 'ID',
                        data: 'enroll_id',
                        name: 'enroll_id'
                    },
                    {
                        title: 'NAMA KARYAWAN',
                        data: 'employee_name',
                        name: 'employee_name'
                    },
                    {
                        title: 'INSENTIF',
                        data: 'insentif',
                        name: 'insentif'
                    },
                ],
                "createdRow": function (row, data, dataIndex) {
                    // if ((data['kode_hari'] == "5") || (data['kode_hari'] == "6") || (data['kerjalibur'] == "LIBUR")) {
                    if ((data['koreksi_upah'] == null)) {
                        $(row).css('background', 'red');
                    }else{
                        $(row).css('background', 'lime');
                    }
                },
                order: [
                    [1, 'asc']
                ]
            });
            table2.draw();
            $('#datatable_ins_jabatan tbody').on('click', 'tr', function () {
                $('#delete_insentif_jabatan').attr("disabled");
                var tr = $(this).closest('tr');
                var row = table2.row(tr);
                var data = row.data();

                $("#datatable_ins_jabatan tbody tr").removeClass('bg-cyan');
                $(this).addClass('bg-cyan');
                $("#nama_karyawan2").val(data["enroll_id"]);
                if(data["koreksi_upah"]!=null){
                    $('#store_insentif_jabatan').html('<i class="fa fa-edit"></i> UPDATE');
                    $('#delete_insentif_jabatan').removeAttr("disabled");
                    $('#store_insentif_jabatan').removeAttr("disabled");
                }else{
                    $('#store_insentif_jabatan').html('&nbsp;&nbsp;<i class="fa fa-floppy-o"></i> SAVE&nbsp;&nbsp;&nbsp;');
                    $('#delete_insentif_jabatan').attr("disabled");
                    $('#store_insentif_jabatan').removeAttr("disabled");
                }
                $("#jumlah_rp_potongan2").val(data["insentif"]);
            });
        });
    </script>

    <script>
        $('#periode_payroll').on('change',function(){
            testing2();
            $('#delete_insentif_jabatan').attr("disabled");
        });
        $('#periode_tanggal_kehadiran').on('change',function(){
            $('#nama_karyawan2').val('');
            $('#jumlah_rp_potongan2').val('');
            $('#delete_insentif_jabatan').prop("disabled", true);
            $('#store_insentif_jabatan').prop("disabled", true);
            testing();
        });
        $('#nama_karyawan2').on('change',function(){
            testing3();
        });
        $('#jumlah_rp_potongan2').on('keyup',function(){
            testing3();
        });
        $('#jumlah_rp_potongan2').on('change',function(){
            testing3();
        });
        $('body').on('click', '#btn-edit', function (event) {
            var enroll_id = $('#enroll_id').val();

            if (enroll_id.length > 0) {
                $("#form1 :input").prop("disabled", false);
                $("#btn-save").prop("disabled", false);
                $("#btn-reset").prop("disabled", true);
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
