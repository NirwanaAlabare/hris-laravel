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

    <!--Mutipleselect css-->
    <link rel="stylesheet" href="{{URL::asset('assets/plugins/multipleselect/multiple-select.css')}}">

	<!-- Notifications  css -->
	<link href="{{URL::asset('assets/plugins/notify-growl/css/jquery.growl.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/notify-growl/css/notifIt.css')}}" rel="stylesheet" />

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
        th {
    white-space: normal !important;
    word-wrap: break-word;
}

    </style>

@stop
@section('mainarea')
    <!-- page-header -->
    <div class="page-header p-3 shadow">
        <ol class="breadcrumb breadcrumb-arrow mt-0">
            <li><a href="#">ABSENSI KARYAWAN</a></li>
            <li class="active"><span>DATA LEMBUR</span></li>
        </ol>
        <div class="ml-auto">
            <div class="input-group">
                <a href="#" id="btn-refresh-data" class="btn btn-secondary text-white mr-2 btn-icon" data-toggle="tooltip"
                    title="" data-placement="bottom" data-original-title="refresh data">
                    <span>
                        <i class="fa fa-refresh"></i>
                    </span>
                </a>
                <a href="{{route('hris.datalembur.add_datalembur')}}" id="btn-add-data-lembur" class="btn btn-warning text-white mr-2 btn-icon" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tambah Data Lembur">
                    <span>
                        <i class="fa fa-plus"></i>
                    </span>
                </a>
                <a href="{{ url('lockscreen') }}" class="btn btn-primary text-white mr-2 btn-icon" data-toggle="tooltip"
                    title="" data-placement="bottom" data-original-title="lock">
                    <span>
                        <i class="fa fa-lock"></i>
                    </span>
                </a>
                <a href="javascript:void(0)" id="btn-search" class="btn btn-secondary mr-0 mt-0 mb-0" data-toggle="tooltip" title="Cari Data"><i class="ion-search"></i></a>
            </div>
        </div>
    </div>
    <!-- End page-header -->

    <!-- BEGIN FORM-->
    {!! Form::open(['route' => 'hris.datalembur.ajax_exportexcel', 'id' => 'formExport', 'name' => 'formExport','method'=>'post']) !!}

    @csrf
    <div class="card shadow">
        <div class="card-header bg-primary p-3">
            <div class="card-title">FILTER DATA LEMBUR KARYAWAN</div>
            <div class="card-options ">
                <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">PERIODE LEMBUR : </label>
                        <select id="periode_lembur" name="periode_lembur" class="form-control">
                            @foreach ($periode_lembur as $r_periode_lembur)
                                <option value="{{$r_periode_lembur->periode_payroll}}">
                                @php
                                    setlocale(LC_ALL, 'id-ID', 'id_ID');
                                    $datePeriode = explode(" s/d ", $r_periode_lembur->periode_payroll);
                                    echo strtoupper(strftime("%d %b %Y", strtotime($datePeriode[0])) . ' s/d ' . strftime("%d %b %Y", strtotime($datePeriode[1])));
                                @endphp
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6" id="inputSearch1">
                    <div class="form-group">
                        <label class="form-label">NOMOR SPL : </label>
                        <div class="form-group">
                            <select id="selectNoSPL" name="selectNoSPL" multiple class="form-control select2">
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">CARI DATA : </label>
                        <input id="searchData" name="searchData" class="form-control" type="text">
                    </div>
                </div>
                <div class="col-md-6" id="inputSearch1">
                    <div class="form-group">
                        <label class="form-label">VERIFIKASI STATUS : </label>
                        <div class="form-group">
                            <select id="selectVerificationStatus" name="selectVerificationStatus" class="form-control">
                                <option value="">Select Status</option>
                                <option value=1>Verified</option>
                                <option value=0>Unverified</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-auto">
                    <div class="form-group">
                        <label class="form-label">FILTER SERAH TERIMA : </label>
                            <input type="hidden" id="daterange_serah_terima" name="daterange_serah_terima">
                            <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                                <a class="nav-link card-title m-0" style="border: 1px solid #d8d4dc" id="daterange-btn1" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Klik di sini untuk pilih tanggal kehadiran"></a>
                                <button type="button" class="btn btn-success btn-app"
                                    onclick="ExportSuratTandaTerimaExcelAllDate()"
                                    data-toggle="tooltip" title="Export SPL All"
                                    id="btn_export_excel_tanda_terima_lembur_all_date">
                                <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                Export SPL Serah Terima
                            </button>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-primary m-0 p-1">
            <div class="text-white">
                <button type="submit" id="btn-exportexcel" class="btn btn-app btn-warning mr-0 mt-0 mb-0" data-toggle="tooltip" title="Export Data ke File Excel"><i class="ion-ios7-download"></i> Export</button>
                <a href="javascript:void(0)" id="btn-cari" class="btn btn-app btn-secondary mr-0 mt-0 mb-0" data-toggle="tooltip" title="Cari Data"><i class="ion-search"></i> Cari</a>
                <button type="button" id="btn-update_edit2" class="btn btn-secondary btn-app" data-dismiss="modal">Tambah Karyawan</button>
                <button type="button" id="btn-hapus-nospl" class="btn btn-danger btn-app" data-dismiss="modal">Hapus NO SPL</button>
                <button type="button" class="btn btn-success btn-app"
                        onclick="ExportSuratTandaTerimaExcelLembur()"
                        data-toggle="tooltip" title="Export SPL Checked"
                        id="btn_export_excel_tanda_terima_lembur">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    Export Serah Terima Checked
                </button>
                @if(in_array($enroll_id_loggin, [7765, 4241, 20,6713,8083,1885]))
                    <button type="button" id="btn_buat_serah_terima"
                    class="btn btn-info btn-app"
                    data-dismiss="modal">
                    <i class="ion-plus"></i> Serah Terima
                </button>
                @endif
            </div>
        </div>
    </div>
    {{-- </form> --}}
    {!! Form::close() !!}
    <!-- END FORM-->

    <!-- row -->
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 ">
            <div clasl="card-header">
                <ul class="nav nav-tabs mx-0 mb-3" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="waiting-tab" data-toggle="tab" href="#waiting" role="tab" aria-controls="waiting" aria-selected="true">Waiting</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="verifikasi-tab" data-toggle="tab" href="#verifikasi_tab" role="tab" aria-controls="verifikasi" aria-selected="false">verifikasi</a>
                    </li>
                </ul>
            </div>
            <div id="data-lembur" class="card shadow tab-content">
                <div class="card-header bg-primary p-3">
                    <div id="title-table1" class="card-title">DATA LEMBUR KARYAWAN</div>
                    <div class="card-options">
                        <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="tab-pane fade show active" id="waiting" role="tabpanel" aria-labelledby="waiting-tab">
                    <form id="verifikasi" method="post">
                        <div class="d-flex align-items-center justify-content-start">
                            <button type="submit" class="btn mr-2 ml-1 mb-2 btn-secondary BtnVerifikasiOt" data-dismiss="modal" >Verifikasi</button>
                            <div class="checkedAll">
                                <input type="checkbox" id="checkAllVerif" class="check1 checkAllVerif" />
                                <label for="checkAllVerif" class="title-14">Select All</label>
                            </div>
                        </div>

                        <div class="card-body m-0 p-0">
                            <div class="table-responsive">
                                <table id="datatable-ajax-crud" class="table table-sm table-striped table-bordered table-vcenter text-nowrap table-nowrap w-100 m-0 p-0">
                                    <thead class="border text-center">
                                        <tr>
                                            <th class="bg-primary w-5 align-middle" scope="col">ACTION</th>
                                            <th class="bg-primary w-5 align-middle" scope="col"></th>
                                            <th class="bg-primary w-5 align-middle" scope="col">NOMOR<br>SPL</th>
                                            <th class="bg-primary w-5 align-middle" scope="col">TANGGAL<br>LEMBUR</th>
                                            <th class="bg-primary w-5 align-middle" scope="col">HARI</th>
                                            <th class="bg-primary w-5 align-middle" scope="col">KERJA</th>
                                            <th class="bg-primary w-5 align-middle" scope="col">NOMOR<br>ABSEN</th>
                                            <th class="bg-primary w-5 align-middle" scope="col">NIK</th>
                                            <th class="bg-primary w-5 align-middle" scope="col">NAMA KARYAWAN</th>
                                            <th class="bg-primary w-5 align-middle" scope="col" width="50px">JADWAL<br>KERJA (IN)</th>
                                            <th class="bg-primary w-5 align-middle" scope="col" width="50px">JADWAL<br>KERJA (OUT)</th>
                                            <th class="bg-primary w-5 align-middle" scope="col" width="50px">ABSEN<br>KERJA (IN)</th>
                                            <th class="bg-primary w-5 align-middle" scope="col" width="50px">ABSEN<br>KERJA (OUT)</th>
                                            <th class="bg-primary w-5 align-middle" scope="col" width="50px">WAKTU<br>LEMBUR (IN)</th>
                                            <th class="bg-primary w-5 align-middle" scope="col" width="50px">WAKTU<br>LEMBUR (OUT)</th>
                                            <th class="bg-primary w-5 align-middle" scope="col" width="50px">JUMLAH<br>JAM LEMBUR</th>
                                            <th class="bg-primary w-5 align-middle" scope="col" width="50px">JUMLAH<br>JAM ISTIRAHAT</th>
                                            <th class="bg-primary w-5 align-middle" scope="col" width="50px">AKTIF</th>
                                            <th class="bg-primary w-5 align-middle" scope="col" width="50px">STAFF</th>
                                            <th class="bg-primary w-5 align-middle" scope="col" width="50px">TANGGAL<br>RESIGN</th>
                                            <th class="bg-primary w-5 align-middle" scope="col" width="50px">BAGIAN</th>
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
                    </form>
                </div>
                <div class="tab-pane fade" id="verifikasi_tab" role="tabpanel" aria-labelledby="verifikasi-tab">
                    <div class="card-body m-0 p-0">
                        <div class="table-responsive">
                            <table id="datatable-verifikasi" class="table table-sm table-striped table-bordered table-vcenter text-nowrap table-nowrap w-100 m-0 p-0">
                                <thead class="border text-center">
                                    <tr>
                                        @if($loggedAdmin->email=='willy@ptnag.com' || $loggedAdmin->role_user === 'superadmin')
                                        <th class="bg-primary w-5 align-middle" scope="col">ACTION</th>
                                        @endif
                                        <th class="bg-primary w-5 align-middle" scope="col">NOMOR<br>SPL</th>
                                        <th class="bg-primary w-5 align-middle" scope="col">TANGGAL<br>LEMBUR</th>
                                        <th class="bg-primary w-5 align-middle" scope="col">HARI</th>
                                        <th class="bg-primary w-5 align-middle" scope="col">KERJA</th>
                                        <th class="bg-primary w-5 align-middle" scope="col">NOMOR<br>ABSEN</th>
                                        <th class="bg-primary w-5 align-middle" scope="col">NIK</th>
                                        <th class="bg-primary w-5 align-middle" scope="col">NAMA KARYAWAN</th>
                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">JADWAL<br>KERJA (IN)</th>
                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">JADWAL<br>KERJA (OUT)</th>
                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">ABSEN<br>KERJA (IN)</th>
                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">ABSEN<br>KERJA (OUT)</th>
                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">WAKTU<br>LEMBUR (IN)</th>
                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">WAKTU<br>LEMBUR (OUT)</th>
                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">JUMLAH<br>JAM LEMBUR</th>
                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">JUMLAH<br>JAM ISTIRAHAT</th>
                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">AKTIF</th>
                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">STAFF</th>
                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">TANGGAL<br>RESIGN</th>
                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">BAGIAN</th>
                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">APROVAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <i><div class="text-left mt-1 mb-1 text-sm ml-1" id="subtitle-table_verif"></div></i>
                    </div>
                    <div class="card-footer bg-primary br-br-7 br-bl-7">
                        <div class="text-white"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ajax-modal-datalembur" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 95%;" role="document">
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-content">
                        <div class="modal-header bg-success p-2">
                            <h4 class="modal-title pl-2"><b>Verification Confirm</b></h4>
                            <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                        <div class="modal-body p-0">
                            <div clasl="card-header">
                                <ul class="nav nav-tabs mx-0 mb-3" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="waiting-tab-data-lembur" data-toggle="tab" href="#waiting_data_lembur" role="tab" aria-controls="waiting" aria-selected="true">Waiting</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="verifikasi-tab-data-lembur" data-toggle="tab" href="#verifikasi_data_lembur" role="tab" aria-controls="verifikasi" aria-selected="false">verifikasi</a>
                                    </li>
                                </ul>
                            </div>
                            <div id="data-lembur" class="card shadow tab-content">
                                <div class="tab-pane fade show active" id="waiting_data_lembur" role="tabpanel" aria-labelledby="waiting-tab">
                                    <div class="card-body">
                                        <input type="hidden" id="all_uuid" class="form-control">
                                        <a id="btn-verifikasi" class="btn btn-block btn-app btn-secondary mr-0 mt-0 mb-0" data-toggle="tooltip" title="Cari Data">Verification All</a>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped table-bordered table-vcenter text-nowrap table-nowrap w-100 m-0 p-0">
                                                <thead class="border text-center">
                                                    <tr>
                                                        <th class="bg-primary w-5 align-middle" scope="col">NOMOR<br>SPL</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col">Tanggal<br>Lembur</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col">Hari</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col">Kerja</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col">NOMOR<br>ABSEN</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col">NAMA KARYAWAN</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">JADWAL<br>KERJA (IN)</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">JADWAL<br>KERJA (OUT)</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">ABSEN<br>KERJA (IN)</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">ABSEN<br>KERJA (OUT)</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">WAKTU<br>LEMBUR (IN)</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">WAKTU<br>LEMBUR (OUT)</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">JUMLAH<br>JAM LEMBUR</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">JUMLAH<br>JAM ISTIRAHAT</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">AKTIF</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">STAFF</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">TANGGAL<br>RESIGN</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">BAGIAN</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="cruddatalembur">
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col pt-2">
                                                Total Data :&nbsp;<label id="total_data"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="verifikasi_data_lembur" role="tabpanel" aria-labelledby="verifikasi-tab">
                                    <div class="card-body br-br-7 br-bl-7">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped table-bordered table-vcenter text-nowrap table-nowrap w-100 m-0 p-0">
                                                <thead class="border text-center">
                                                    <tr>
                                                        <th class="bg-primary w-5 align-middle" scope="col">NOMOR<br>SPL</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col">Tanggal<br>Lembur</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col">Hari</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col">Kerja</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col">NOMOR<br>ABSEN</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col">NAMA KARYAWAN</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">JADWAL<br>KERJA (IN)</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">JADWAL<br>KERJA (OUT)</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">ABSEN<br>KERJA (IN)</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">ABSEN<br>KERJA (OUT)</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">WAKTU<br>LEMBUR (IN)</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">WAKTU<br>LEMBUR (OUT)</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">JUMLAH<br>JAM LEMBUR</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">JUMLAH<br>JAM ISTIRAHAT</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">AKTIF</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">STAFF</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">TANGGAL<br>RESIGN</th>
                                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">BAGIAN</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="cruddatalemburverify">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer br-br-7 br-bl-7">
                                        Total Data :&nbsp;<label id="total_data"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="verif_confirm" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-content">
                        <div class="modal-header bg-success p-2">
                            <h4 class="modal-title pl-2"><b>Verification Confirm</b></h4>
                            <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col text-center">
                                    <label>Are You Sure?</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col text-center">
                                    <button type="button" class="btn-success" id="verificating">Yes</button>
                                    <button type="button" class="btn-secondary" data-dismiss="modal">No</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- boostrap show department model -->
    <div class="modal fade" id="ajax-lembur-model-add" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <input id="uuid_master" type="hidden">
                <input id="enroll_id" type="hidden">
                <input id="employee_id" type="hidden">
                <input id="nik" type="hidden">
                <input id="tanggal_lembur" type="hidden">
                <input id="kode_hari" type="hidden">
                <input id="nama_hari" type="hidden">
                <input id="employee_name" type="hidden">
                <input id="shift_work_id" type="hidden">
                <input id="jadwal_masuk_kerja" type="hidden">
                <input id="jadwal_pulang_kerja" type="hidden">
                <input id="site_nirwana_id" type="hidden">
                <input id="department_id" type="hidden">
                <input id="sub_dept_id" type="hidden">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title" id="ajaxGagalAbsenModel"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                        <i class="fa fa-remove"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <table cellpadding="5" class="table table-striped table-sm" width="100%">
                        <tbody>
                        <tr>
                            <td class="w-40" colspan=3>No. Form Lembur</td>
                            <td colspan=3>Jumlah Jam Istirahat</td>
                        </tr>
                        <tr>
                            <td colspan=3><input readonly class="form-control" id="nomor_form_lembur" name="nomor_form_lembur" placeholder="Nomor Form Lembur" type="text"></td>
                            <td colspan=3><input class="form-control" id="jumlah_jam_istirahat" name="jumlah_jam_istirahat" type="text"></td>
                        </tr>
                        <tr>
                            <td colspan=3>Tanggal Lembur</td>
                            <td colspan=3>Jumlah Jam Lembur</td>
                        </tr>
                        <tr>
                            <td colspan=3>
                                <div id="tanggal_lembur_label"></div>
                            </td>
                            <td colspan=3><input class="form-control" id="jumlah_jam_lembur" name="jumlah_jam_lembur" placeholder="Input jumlah jam" type="text"></td>
                        </tr>
                        <tr>
                            <td colspan=3>Department</td>
                            <td colspan=3>Waktu Lembur</td>
                        </tr>
                        <tr>
                            <td colspan=3><div id="department_name"></div></td>
                            <td colspan=3>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                        </div>
                                    </div><!-- input-group-prepend -->
                                    <input class="form-control" id="waktu_jam_lembur" name="waktu_jam_lembur" onChange="swl('waktu_jam_lembur');" type="text">
                                    <input id="mulai_jam_lembur" name="mulai_jam_lembur" type="hidden">
                                    <input id="akhir_jam_lembur" name="akhir_jam_lembur" type="hidden">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=6>Catatan dari HRD</td>
                        </tr>
                        <tr>
                            <td colspan=6><input class="form-control" id="catatan_hrd" name="catatan_hrd" type="text"></td>
                        </tr>
                    </tbody>
                    </table>
                </div>
                <div class="modal-footer bg-primary pb-3 pt-3">
                    <div class="btn-list">
                        <button type="button" id="btn-tutup" class="btn btn-warning btn-app" data-dismiss="modal">Tutup</button>
                        <button type="button" id="btn-save-changes" class="btn btn-secondary btn-app">Save all changes</button>
                        <button type="button" id="btn-delete-lembur" class="btn btn-danger btn-app">Hapus Data Ini</button>
                    </div>
                </div>
            </div>
          </div>
    </div>
    <!-- end bootstrap model -->

    <!-- boostrap show department model -->
    <div class="modal fade" id="ajax-modal-edit1" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="row">
                <div class="col-md-12">

                    <div class="modal-content">
                        <div class="modal-header bg-primary p-2">
                            <h4 class="modal-title pl-2 font-weight-bold" id="title-modal-edit1"></h4>
                            <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                        <input id="tanggal_berjalan_edit1" name="tanggal_berjalan_edit1" type="hidden">
                        <input id="enroll_id_edit1" name="enroll_id_edit1" type="hidden">
                        <input id="nik_edit1" name="nik_edit1" type="hidden">
                        <input id="employee_name_edit1" name="employee_name_edit1" type="hidden">

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">NOMOR SPL : </label>
                                        <input readonly class="form-control" id="nomor_form_lembur_edit1" name="nomor_form_lembur_edit1" type="text">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="form-label">WAKTU LEMBUR </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                </div>
                                            </div><!-- input-group-prepend -->
                                            <input class="form-control" id="waktu_jam_lembur_edit1" name="waktu_jam_lembur_edit1" onChange="swl('waktu_jam_lembur_edit1');" placeholder="DD-MM-YYYY HH:MM - DD-MM-YYYY HH:MM" type="text">
                                            <input id="mulai_jam_lembur_edit1" name="mulai_jam_lembur_edit1" type="hidden">
                                            <input id="akhir_jam_lembur_edit1" name="akhir_jam_lembur_edit1" type="hidden">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">JMLH JAM LEMBUR : </label>
                                        <input class="form-control" id="jumlah_jam_lembur_edit1" name="jumlah_jam_lembur_edit1" placeholder="Input jumlah jam" type="text">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">JMLH JAM ISTIRAHAT : </label>
                                        <input class="form-control" id="jumlah_jam_istirahat_lembur_edit1" name="jumlah_jam_istirahat_lembur_edit1" type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">PILIH KARYAWAN : </label>
                                        <div class="input-group">
                                            <span class="input-group-append">
                                                <label class="custom-switch">
                                                    <input type="checkbox" id="checkAll" name="checkAll" class="custom-switch-input">
                                                    <span class="custom-switch-indicator"></span>
                                                    <span class="custom-switch-description">Check di sini untuk perubahan semuanya, <br>berdasarkan Nomor SPL yang di pilih?</span>
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">CATATAN LEMBUR : </label>
                                        <input class="form-control" id="catatan_edit1" name="catatan" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-primary p-1">
                            <div class="btn-list">
                                <button type="button" id="btn-update_edit1" class="btn btn-secondary btn-app" data-dismiss="modal">Simpan</button>
                                <button type="button" id="btn-close_edit1" class="btn btn-warning btn-app" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end bootstrap model -->

    <!-- boostrap show department model -->
    <div class="modal fade" id="ajax-modal-pilih1" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="row">
                <div class="col-md-12">

                    <div class="modal-content">
                        <div class="modal-header bg-primary p-2">
                            <h4 class="modal-title pl-2"><b>TAMBAH KARYAWAN</b></h4>
                            <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Nomor SPL :</label>
                                        <select class="form-control select2-show-search" id="selectNoSPL_pilih1" name="selectNoSPL_pilih1">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">WAKTU LEMBUR </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                </div>
                                            </div><!-- input-group-prepend -->
                                            <input class="form-control" id="waktu_jam_lembur_tambah" name="waktu_jam_lembur_tambah" onChange="swl('waktu_jam_lembur_tambah');" placeholder="DD-MM-YYYY HH:MM - DD-MM-YYYY HH:MM" type="text">
                                            <input id="mulai_jam_lembur_tambah" name="mulai_jam_lembur_tambah" type="hidden">
                                            <input id="akhir_jam_lembur_tambah" name="akhir_jam_lembur_tambah" type="hidden">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">JMLH JAM LEMBUR : </label>
                                        <input class="form-control" id="jumlah_jam_lembur_tambah" name="jumlah_jam_lembur_tambah" placeholder="Input jumlah jam" type="text">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">JMLH JAM ISTIRAHAT : </label>
                                        <input class="form-control" id="jumlah_jam_istirahat_tambah" name="jumlah_jam_istirahat_tambah" type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">CATATAN LEMBUR : </label>
                                        <input class="form-control" id="catatan_tambah" name="catatan_tambah" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Karyawan Yang Dipilih :</label>
                                        <select class="form-control select2" id="selectEmp_tambah" name="selectEmp_tambah" multiple>
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
                                <button type="button" id="btn-save2" class="btn btn-secondary btn-app" data-dismiss="modal">Simpan</button>
                                <button type="button" id="btn-close2" class="btn btn-warning btn-app" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end bootstrap model -->

    <!-- boostrap show department model -->
    <div class="modal fade" id="ajax-modal-hapus2" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="row">
                <div class="col-md-12">

                    <div class="modal-content">
                        <div class="modal-header bg-primary p-2">
                            <h4 class="modal-title pl-2"><b>HAPUS NO SPL</b></h4>
                            <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card-body p-1">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">NOMOR SPL : </label>
                                            <div class="form-group">
                                                <select id="selectNoSPLHapus2" name="selectNoSPLHapus2" multiple class="form-control select2 w-100">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-primary p-1">
                            <div class="btn-list">
                                <button type="button" id="btn-remove2" class="btn btn-danger btn-app" data-dismiss="modal"><span><i class="fa fa-save"></i> Hapus NO SPL</span></button>
                                <button type="button" id="btn-close3" class="btn btn-warning btn-app" data-dismiss="modal"><span><i class="fa fa-close"></i> Tutup</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end bootstrap model -->

     <div class="modal fade" id="ajax-modal-buat-serah-terima" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-2xl modal-dialog-scrollable" role="document" style="max-width: 50%; height: 90%;">
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-content">
                        <div class="modal-header bg-primary p-2">
                            <h4 class="modal-title pl-2"><b>FORM SERAH TERIMA LEMBUR KARYAWAN</b></h4>
                            {{-- <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                                <i class="fa fa-remove"></i>
                            </button> --}}
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="datatable-ajax-modal-list-spl-checklist" class="table table-lg table-striped table-hover table-bordered w-100">
                                            <thead>
                                                <tr class="text-center">
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
                                <button type="button" id="btn-save-serah-terima" class="btn btn-secondary btn-app" >Simpan</button>
                                <button type="button" id="btn-close-serah-terima" class="btn btn-warning btn-app">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

        function ExportSuratTandaTerimaExcelLembur(){
            var tanggal = $('#daterange_serah_terima').val();
            $.ajax({
                type: "get",
                url: '{{ route('hris.datalembur.export_excel_tanda_terima_lembur') }}',
                data: {
                    tanggal: tanggal,
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
                    {
                        $('#btn_export_excel_tanda_terima_lembur').removeClass("btn-loading");
                        $("#btn_export_excel_tanda_terima_lembur").html('<i class="fa fa-check" style="font-size:11pt"></i> Export Serah Terima');
                        $("#btn_export_excel_tanda_terima_lembur").attr("disabled", false);
                        var blob = new Blob([response]);
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "Laporan Serah Terima Lembur "+Math.ceil(Math.random()*1000000)+".xlsx";
                        link.click();
                    }
                },
                error: function(res){
                    swal("", "Export kontrak kerja gagal", "error");
                    $('#btn_export_excel_tanda_terima_lembur').removeClass("btn-loading");
                    $("#btn_export_excel_tanda_terima_lembur").attr("disabled", false);
                    $("#btn_export_excel_tanda_terima_lembur").html('<i class="fa fa-check" style="font-size:11pt"></i> Export Serah Terima');
                }
            });
        }

        function ExportSuratTandaTerimaExcelAllDate(){
            var tanggal = $('#daterange_serah_terima').val();
            $.ajax({
                type: "get",
                url: '{{ route('hris.datalembur.export_excel_tanda_terima_lembur_all_date') }}',
                data: {
                    tanggal: tanggal,
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
                    {
                        $('#btn_export_excel_tanda_terima_lembur_all_date').removeClass("btn-loading");
                        $("#btn_export_excel_tanda_terima_lembur_all_date").html('<i class="fa fa-file-excel-o" style="font-size:11pt"></i> Export Serah Terima');
                        $("#btn_export_excel_tanda_terima_lembur_all_date").attr("disabled", false);
                        var blob = new Blob([response]);
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "Laporan Serah Terima Lembur "+Math.ceil(Math.random()*1000000)+".xlsx";
                        link.click();
                    }
                },
                error: function(res){
                    swal("", "Export kontrak kerja gagal", "error");
                    $('#btn_export_excel_tanda_terima_lembur_all_date').removeClass("btn-loading");
                    $("#btn_export_excel_tanda_terima_lembur_all_date").attr("disabled", false);
                    $("#btn_export_excel_tanda_terima_lembur_all_date").html('<i class="fa fa-file-excel-o" style="font-size:11pt"></i> Export Serah Terima');
                }
            });
        }

         $(document).ready(function() {
            var start = moment().subtract(1, 'months').date(26);   // Tanggal 26 bulan lalu
            var end = moment().date(25);
            var htmlDateRange = '<span><i class="fa fa-calendar"></i> ' + start.format("D MMM YYYY").toUpperCase() + ' s/d ' + end.format("D MMM YYYY").toUpperCase() + '</span><i class="fa fa-angle-down ml-1"></i>'
            $('#daterange-btn1').html(htmlDateRange);
            var daterange_serah_terima = start.format("YYYY-MM-DD") + " s/d " + end.format("YYYY-MM-DD");
            $('#daterange_serah_terima').val(daterange_serah_terima);
        });

         $('#daterange-btn1').daterangepicker({
            ranges: {
                'Hari ini': [moment(), moment()],
                'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 Hari Kemarin': [moment().subtract(6, 'days'), moment()],
                '30 Hari Kemarin': [moment().subtract(29, 'days'), moment()],
                'Bulan Sekarang': [moment().startOf('month'), moment().endOf('month')],
                'Bulan Kemarin': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Periode 26-25': [moment().subtract(1, 'months').date(26), moment().date(25)]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment()
        }, function(start, end) {
            $('#daterange-btn1').html('<span><i class="fa fa-calendar"></i> ' + start.format("D MMM YYYY").toUpperCase() + ' s/d ' + end.format("D MMM YYYY").toUpperCase() + '</span><i class="fa fa-angle-down ml-1"></i>');
            var daterange_serah_terima = start.format("YYYY-MM-DD") + " s/d " + end.format("YYYY-MM-DD");
            $('#daterange_serah_terima').val(daterange_serah_terima);
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#btn-refresh-data', function (event) {
            // $("#datatable-ajax-crud").DataTable().ajax.reload();
            location.reload();
            notif({
                msg: "<b>Success:</b> Data sudah di refresh.",
                type: "success"
            });
        });

        datetimerangepicker('waktu_jam_lembur');

        //$(function(id) {
        function datetimerangepicker(id) {
            //id = 'waktu_jam_lembur';
            $('input[id="' + id + '"]').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                locale: {
                    format: 'YYYY/MM/DD HH:mm'
                },
                pickDate: false,
                pickSeconds: false,
                pick24HourFormat: true
            });
        }

        function swl(id) {
            //alert(id);
            var waktu_jam_lembur = $('#' + id).val();
            var splitWaktuLembur = waktu_jam_lembur.split(" - ");
            var mulai_jam_lembur = splitWaktuLembur[0];
            var akhir_jam_lembur = splitWaktuLembur[1];

            jamlemburistirahat(mulai_jam_lembur, akhir_jam_lembur);

        }

        function jamlemburistirahat(mulai_jam_lembur, akhir_jam_lembur) {
            var dt1 = new Date(mulai_jam_lembur);
            var dt2 = new Date(akhir_jam_lembur);
            //alert(diff_hours(dt1, dt2));
            var jmljamlembur = diff_hours(dt1, dt2);
            var jamistirahatlembur = 0;

            if((jmljamlembur > 4) && (jmljamlembur <=8)) {
                jamistirahatlembur = 0.5;
                jmljamlembur = jmljamlembur-jamistirahatlembur;
            }
            if((jmljamlembur > 8) && (jmljamlembur <=12)) {
                jamistirahatlembur = 0.5 * 2;
                jmljamlembur = jmljamlembur-jamistirahatlembur;
            }

            if((jmljamlembur > 12) && (jmljamlembur <=16)) {
                jamistirahatlembur = 0.5 * 3;
                jmljamlembur = jmljamlembur-jamistirahatlembur;
            }

            if((jmljamlembur > 16) && (jmljamlembur <=20)) {
                jamistirahatlembur = 0.5 * 4;
                jmljamlembur = jmljamlembur-jamistirahatlembur;
            }

            if((jmljamlembur > 20) && (jmljamlembur <24)) {
                jamistirahatlembur = 0.5 * 5;
                jmljamlembur = jmljamlembur-jamistirahatlembur;
            }

            var mulaitgllembur = mulai_jam_lembur.split(" ");
            $('#mulai_jam_lembur').val(mulai_jam_lembur);
            $('#daterange1').val(mulaitgllembur[0]);
            $('#tanggal_lembur_label').text(mulaitgllembur[0]);
            $('#tanggal_lembur').val(mulaitgllembur[0]);
            $('#akhir_jam_lembur').val(akhir_jam_lembur);
            $("#jumlah_jam_lembur").val(jmljamlembur);
            $("#jumlah_jam_istirahat").val(jamistirahatlembur);
            $("#jumlah_jam_istirahat_label").text(jamistirahatlembur);

            //alert(splitWaktuLembur[0]);
        }

        function diff_hours(dt2, dt1)
        {

         var diff =(dt2.getTime() - dt1.getTime()) / 1000;
         diff /= (60 * 60);
         return Math.abs(parseFloat(diff).toFixed(1));

        }

        function format(d) {
            // `d` is the original data object for the row
            return (

                '<div class="expanel expanel-success">' +
                    '<div class="expanel-heading">' +
                        '<h4 class="expanel-title">Detail Data Kehadiran Karyawan</h4>' +
                    '</div>' +
                    '<div class="expanel-body">' +
                        '<table cellpadding="5" cellspacing="0" border="0" width="100%">' +
                            '<tbody>' +
                            '<tr>' +
                                '<td>UUD Master</td>' +
                                '<td>:</td>' +
                                '<td>' + d.uuid + '</td>' +
                                '<td>Operator</td>' +
                                '<td>:</td>' +
                                '<td>' + d.operator + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td>Waktu Perubahan</td>' +
                                '<td>:</td>' +
                                '<td>' + d.updated_at + '</td>' +
                                '<td>Waktu Jam Kerja</td>' +
                                '<td>:</td>' +
                                '<td>' + d.jadwal_jam_kerja + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td>Site Nirwana</td>' +
                                '<td>:</td>' +
                                '<td>' + d.site_nirwana_name + '</td>' +
                                '<td>Absen IN</td>' +
                                '<td>:</td>' +
                                '<td>' + d.absen_masuk_kerja + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td>Department</td>' +
                                '<td>:</td>' +
                                '<td>' + d.department_name + '</td>' +
                                '<td>Absen OUT</td>' +
                                '<td>:</td>' +
                                '<td>' + d.absen_pulang_kerja + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td>Sub Department</td>' +
                                '<td>:</td>' +
                                '<td>' + d.sub_dept_name + '</td>' +
                                '<td>Status Absen</td>' +
                                '<td>:</td>' +
                                '<td>' + d.status_absen + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td>Catatan dari HRD</td>' +
                                '<td>:</td>' +
                                '<td colspan=4>' + d.catatan_hrd + '</td>' +
                            '</tr>' +
                        '</tbody>' +
                        '</table>' +
                    '</div>' +
                '</div>'
            );
        }

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

        $(document).ready(function() {
            $("#data-lembur").hide();
            getnomorspl();
        });

        function getallnomorspl()
        {
            var periode_lembur = $('#periode_lembur').val();
            var nomor_form_lembur = $('#selectNoSPL_pilih1').val();

            if(periode_lembur){
                $.ajax({
                    type:"POST",
                    url: "{{route('hris.datalembur.ajax_getnomorspl')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        periode_lembur:periode_lembur,
                        nomor_form_lembur:nomor_form_lembur,
                    },
                    dataType: 'json',
                    success: function(res){
                        $('#catatan_tambah').val(res[0].catatan);
                        split_mulai_jam_lembur = res[0].mulai_jam_lembur.split(' ');
                        split_akhir_jam_lembur = res[0].akhir_jam_lembur.split(' ');
                        mulai_jam_lembur = defaultDate(split_mulai_jam_lembur[0]) + " " + split_mulai_jam_lembur[1];
                        akhir_jam_lembur = defaultDate(split_akhir_jam_lembur[0]) + " " + split_akhir_jam_lembur[1];
                        $('#waktu_jam_lembur_tambah').val(defaultDate(split_mulai_jam_lembur[0]) + " " + split_mulai_jam_lembur[1] + " - " + defaultDate(split_akhir_jam_lembur[0]) + " " + split_akhir_jam_lembur[1]);
                        $('#jumlah_jam_istirahat_tambah').val(res[0].jumlah_jam_istirahat_lembur);
                        $('#jumlah_jam_lembur_tambah').val(res[0].jumlah_jam_lembur);
                    }
                });
            }
        };

        $('body').on('change', '#selectNoSPL_pilih1', function () {
            var selectNoSPL_pilih1 = $('#selectNoSPL_pilih1').val();

            if(selectNoSPL_pilih1){
                getallnomorspl();
            }
        });

        $('body').on('change', '#daterange1', function () {
            var daterange1 = $('#daterange1').val();
            $("#selectEmployeeID").empty();
            $("#selectEmployeeID").val(null).trigger("change");
            $("#check-all-karyawan").prop("checked",false);
            $("#selectNomorFormLembur").empty();
            $("#selectNomorFormLembur").val(null).trigger("change");
            $("#selectDepartment").val(null).trigger("change");

            if(daterange1){
                $.ajax({
                    type:"POST",
                    url: "{{route('hris.datalembur.ajax_gettanggalnfl')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        daterange1:daterange1,
                    },
                    dataType: 'json',
                    success: function(resA){
                        if(resA.length>0){
                            for(i=0;i<resA.length;i++) {
                                $("#selectNomorFormLembur").append(new Option(resA[i].nomor_form_lembur, resA[i].nomor_form_lembur));
                            }
                        }
                        $('#selectNomorFormLembur').multipleSelect({
                            selectAll: true,
                            width: "100%",
                            filter: true,
                            sort: true,
                        });
                    }
                });
            }
        });

        $('body').on('change', '#selectNomorFormLembur', function () {
            var nomor_form_lembur = $('#selectNomorFormLembur').val();
            $("#selectEmployeeID").empty();
            $("#selectEmployeeID").val(null).trigger("change");
            $("#check-all-karyawan").prop("checked",false);

            if(nomor_form_lembur.length > 0){
                $("#selectDepartment").val(null).trigger("change");
                $("#selectDepartment").prop("disabled", true);

                $.ajax({
                    type:"POST",
                    url: "{{route('hris.datalembur.ajax_getemployeselectnfl')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        nomor_form_lembur:nomor_form_lembur,
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
                $("#selectDepartment").removeAttr("disabled");
            }

        });

        $('body').on('change', '#selectDepartment', function () {
            var department_id = $('#selectDepartment').val();
            $("#selectEmployeeID").empty();
            $("#selectEmployeeID").val(null).trigger("change");
            $("#check-all-karyawan").prop("checked",false);


            if(department_id.length > 0){
                $("#selectNomorFormLembur").val(null).trigger("change");
                $("#selectNomorFormLembur").prop("disabled", true);

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
                    }
                });
            } else {
                $("#selectNomorFormLembur").removeAttr("disabled");
            }
        });

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

        $('body').on('click', '#btn-caridata', function (event) {
            TampilDataLembur();
        });

        $('body').on('click', '#btn-save-changes', function (event) {
            var uuid_master = $("#uuid_master").val();
            var nomor_form_lembur = $("#nomor_form_lembur").val();
            var tanggal_lembur = $("#tanggal_absen").val();
            var jumlah_jam_istirahat = $("#jumlah_jam_istirahat").val();
            var jumlah_jam_lembur = $("#jumlah_jam_lembur").val();
            var mulai_jam_lembur = $("#mulai_jam_lembur").val();
            var akhir_jam_lembur = $("#akhir_jam_lembur").val();
            var catatan_hrd = $("#catatan_hrd").val();

            $("#no_form").removeClass("is-invalid state-invalid");
            $("#status_absen").removeClass("is-invalid state-invalid");
            $("#absen_masuk_kerja").removeClass("is-invalid state-invalid");
            $("#absen_pulang_kerja").removeClass("is-invalid state-invalid");

            if(!uuid_master) {
                notif({
                    msg: "<b>Warning:</b> Data Master tidak ada.",
                    type: "warning"
                });
                return false;
            } else if(!nomor_form_lembur) {
                notif({
                    msg: "<b>Warning:</b> Nomor Form Lembur tidak ada.",
                    type: "warning"
                });
                return false;
            } else if(!waktu_jam_lembur) {
                $("#waktu_jam_lembur").addClass("is-invalid state-invalid");
                notif({
                    msg: "<b>Warning:</b> Waktu jam lembur tidak lengkap.",
                    type: "warning"
                });
                return false;
            } else if(!akhir_jam_lembur) {
                $("#waktu_jam_lembur").addClass("is-invalid state-invalid");
                notif({
                    msg: "<b>Warning:</b> Waktu jam lembur tidak lengkap.",
                    type: "warning"
                });
                return false;
            }
            $('#btn-save-changes').addClass("btn-loading");
            $("#btn-save-changes").attr("disabled", true);

            $.ajax({
                type:"POST",
                url: "{{route('hris.datalembur.update')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    uuid_master:uuid_master,
                    nomor_form_lembur:nomor_form_lembur,
                    tanggal_lembur:tanggal_lembur,
                    jumlah_jam_istirahat:jumlah_jam_istirahat,
                    jumlah_jam_lembur:jumlah_jam_lembur,
                    mulai_jam_lembur:mulai_jam_lembur,
                    akhir_jam_lembur:akhir_jam_lembur,
                    catatan_hrd:catatan_hrd,
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

                    // $("#datatable-ajax-crud").DataTable().ajax.reload();
                    location.reload();

                    $("#ajax-lembur-model-add").modal('hide');
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

        $('body').on('click', '#btn-delete-lembur', function (event) {
            var uuid_master = $("#uuid_master").val();

            $('#btn-delete-lembur').addClass("btn-loading");
            $("#btn-delete-lembur").attr("disabled", true);

            $.ajax({
                type:"POST",
                url: "{{route('hris.datalembur.delete')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    uuid_master:uuid_master,
                },
                dataType: 'json',
                success: function(res){

                    $('#btn-delete-lembur').removeClass("btn-loading");
                    $("#btn-delete-lembur").html('Hapus Data Ini');
                    $("#btn-delete-lembur"). attr("disabled", false);


                    notif({
                        msg: "<b>Success:</b> Data berhasil di hapus.",
                        type: "success"
                    });

                    // $("#datatable-ajax-crud").DataTable().ajax.reload();
                    location.reload();

                    $("#ajax-lembur-model-add").modal('hide');
                },
                error: function(res){
                    $('#btn-delete-lembur').removeClass("btn-loading");
                    $("#btn-delete-lembur").html('Hapus Data Ini');
                    $("#btn-delete-lembur"). attr("disabled", false);

                    notif({
                        msg: "<b>Oops!</b> An Error Occurred",
                        type: "error",
                        position: "center"
                    });
                }
            });

        });

        function getnomorspl()
        {
            var periode_lembur = $('#periode_lembur').val();

            if(periode_lembur){
                $.ajax({
                    type:"POST",
                    url: "{{route('hris.datalembur.ajax_getnomorspl')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        periode_lembur:periode_lembur,
                    },
                    dataType: 'json',
                    success: function(resA){
                        if(resA){
                            for(i=0;i<resA.length;i++) {
                                $("#selectNoSPL").append(new Option(resA[i].tanggal_nomor_spl, resA[i].nomor_form_lembur));
                            }
                        }
                        $('#selectNoSPL').multipleSelect({
                            selectAll: true,
                            width: "100%",
                            filter: true,
                            sort: true,
                        });
                    }
                });
            }
        };

        function getnomorsplhapus()
        {
            var tgl = $('#periode_lembur').val();
            var tanggal = tgl.split(' s/d ');

            // LAGI COBA TEST CLOSING PAYROLL
            $.ajax({
                type:"POST",
                url: "{{route('hris.dataclosingpayroll.ajax_getclosing')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    tanggal:tanggal[0],
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
                        var periode_lembur = $('#periode_lembur').val();

                        $("#ajax-modal-hapus2").modal('show');
                        $("#selectNoSPLHapus2").empty();
                        $("#selectNoSPLHapus2").val(null).trigger("change");

                        if(periode_lembur){
                            $.ajax({
                                type:"POST",
                                url: "{{route('hris.datalembur.ajax_getnomorspl')}}",
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                data: {
                                    periode_lembur:periode_lembur,
                                },
                                dataType: 'json',
                                success: function(resA){
                                    if(resA){
                                        $("#selectNoSPLHapus2").append('<option value="' + resA[0].nomor_form_lembur + '" selected="selected">' + resA[0].tanggal_nomor_spl + '</option>');
                                        for(i=1;i<resA.length;i++) {
                                            $("#selectNoSPLHapus2").append(new Option(resA[i].tanggal_nomor_spl, resA[i].nomor_form_lembur));
                                        }
                                    }
                                    $('#selectNoSPLHapus2').multipleSelect({
                                        selectAll: true,
                                        width: "100%",
                                        filter: true,
                                        sort: true,
                                    });
                                }
                            });
                        }
                    }

                },
                error: function(res){

                }
            });

        };

        $('body').on('change', '#periode_lembur', function () {
            var periode_lembur = $('#periode_lembur').val();
            $("#selectNoSPL").empty();
            $("#selectNoSPL").val(null).trigger("change");
            getnomorspl();
        });

        $('body').on('click', '#btn-cari', function () {
            TampilDataLembur();
        });
        $('body').on('click', '#verificating', function () {
            var alluuid = $('#all_uuid').val();
            $.ajax({
                type:'POST',
                url:"{{route('hris.datalembur.verificating')}}",
                data:{all_uuid:alluuid},
                success:function(data){
                    $("#verif_confirm").modal('hide');
                    $("#ajax-modal-datalembur").modal('hide');
                }
            });
        });
        $('body').on('click', '#btn-verifikasi', function () {
            $("#verif_confirm").modal('show');
        });

        $('body').on('click', '#btn-search', function () {
            $("#cruddatalembur").empty();
            $("#cruddatalemburverify").empty();
            var periode_lembur = $('#periode_lembur').val();
            var selectNoSPL = $('#selectNoSPL').val();
            var searchData = $('#searchData').val();
            $("#ajax-modal-datalembur").modal('show');
            $.ajax({
                type:'POST',
                url:"{{route('hris.datalembur.ajax_datalembur2')}}",
                data:{periode_lembur:periode_lembur, selectNoSPL:selectNoSPL, searchData:searchData},
                success:function(data){
                    $('#total_data').text(data.length);
                    var all_uuid=[];
                    for(i=0;i<data.length;i++) {
                        all_uuid.push(data[i].uuid);
                        var status_kerja='';
                        if(data[i].kode_hari!=5 && data[i].kode_hari!=6 && data[i].holiday_name!=null){
                            status_kerja='Libur';
                        }else if(data[i].kode_hari==5 || data[i].kode_hari==6 && data[i].holiday_name!=null){
                            status_kerja='Libur';
                        }else if(data[i].kode_hari==5 || data[i].kode_hari==6 && data[i].holiday_name==null){
                            status_kerja='Kerja';
                        }else if(data[i].kode_hari!=5 && data[i].kode_hari!=6 && data[i].holiday_name==null){
                            status_kerja='Kerja';
                        }
                        var tanggal_resign='';
                        if(data[i].employee_atribut.tanggal_resign==null){
                            tanggal_resign='';
                        }
                        else{
                            tanggal_resign=data[i].employee_atribut.tanggal_resign;
                        }
                        var mulai_jam_kerja='';
                        if(data[i].mulai_jam_kerja==null){
                            mulai_jam_kerja='';
                        }
                        else{
                            mulai_jam_kerja=data[i].mulai_jam_kerja.substring(0, 5)
                        }
                        var akhir_jam_kerja='';
                        if(data[i].akhir_jam_kerja==null){
                            akhir_jam_kerja='';
                        }
                        else{
                            akhir_jam_kerja=data[i].akhir_jam_kerja.substring(0, 5)
                        }
                        var absen_masuk_kerja='';
                        if(data[i].absen_masuk_kerja==null){
                            absen_masuk_kerja='';
                        }
                        else{
                            absen_masuk_kerja=data[i].absen_masuk_kerja.substring(0, 5)
                        }
                        var absen_pulang_kerja='';
                        if(data[i].absen_pulang_kerja==null){
                            absen_pulang_kerja='';
                        }
                        else{
                            absen_pulang_kerja=data[i].absen_pulang_kerja.substring(0, 5)
                        }
                        var absen_pulang_kerja='';
                        if(data[i].absen_pulang_kerja==null){
                            absen_pulang_kerja='';
                        }
                        else{
                            absen_pulang_kerja=data[i].absen_pulang_kerja.substring(0, 5)
                        }
                        var mulai_jam_lembur='';
                        if(data[i].mulai_jam_lembur==null){
                            mulai_jam_lembur='';
                        }
                        else{
                            mulai_jam_lembur=data[i].mulai_jam_lembur
                        }
                        var akhir_jam_lembur='';
                        if(data[i].akhir_jam_lembur==null){
                            akhir_jam_lembur='';
                        }
                        else{
                            akhir_jam_lembur=data[i].akhir_jam_lembur
                        }
                        var jumlah_jam_lembur='';
                        if(data[i].jumlah_jam_lembur==null){
                            jumlah_jam_lembur='';
                        }
                        else{
                            jumlah_jam_lembur=data[i].jumlah_jam_lembur
                        }
                        var jumlah_jam_istirahat_lembur='';
                        if(data[i].jumlah_jam_istirahat_lembur==null){
                            jumlah_jam_istirahat_lembur='';
                        }
                        else{
                            jumlah_jam_istirahat_lembur=data[i].jumlah_jam_istirahat_lembur
                        }
                        var status_aktif='';
                        if(data[i].employee_atribut.status_aktif==null){
                            status_aktif='';
                        }
                        else{
                            status_aktif=data[i].employee_atribut.status_aktif
                        }
                        var status_staff='';
                        if(data[i].employee_atribut.status_staff==null){
                            status_staff='';
                        }
                        else{
                            status_staff=data[i].employee_atribut.status_staff
                        }
                        var sub_dept_name='';
                        if(data[i].employee_atribut.dept.sub_dept_name==null){
                            sub_dept_name='';
                        }
                        else{
                            sub_dept_name=data[i].employee_atribut.dept.sub_dept_name
                        }

                        if (data[i].data_lembur.is_verifikasi==0) {
                            htmlTable = '' +
                            '<tr class="text-center">' +
                            '   <td class="text-nowrap text-center align-middle">' + data[i].data_lembur.nomor_form_lembur  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + moment(data[i].tanggal_berjalan).format('DD MMM YYYY')  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + data[i].nama_hari  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + status_kerja  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + data[i].enroll_id  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + data[i].employee_atribut.employee_name  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + mulai_jam_kerja  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + akhir_jam_kerja  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + absen_masuk_kerja  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + absen_pulang_kerja  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + mulai_jam_lembur  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + akhir_jam_lembur + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + jumlah_jam_lembur + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + jumlah_jam_istirahat_lembur + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + status_aktif + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + status_staff + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + tanggal_resign + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + sub_dept_name + '</td>' +
                            '</tr>';

                            $("#cruddatalembur").append(htmlTable);
                        }
                        else{
                            htmlTable2 = '' +
                            '<tr class="text-center">' +
                            '   <td class="text-nowrap text-center align-middle">' + data[i].data_lembur.nomor_form_lembur  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + moment(data[i].tanggal_berjalan).format('DD MMM YYYY')  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + data[i].nama_hari  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + status_kerja  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + data[i].enroll_id  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + data[i].employee_atribut.employee_name  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + mulai_jam_kerja  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + akhir_jam_kerja  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + absen_masuk_kerja  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + absen_pulang_kerja  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + mulai_jam_lembur  + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + akhir_jam_lembur + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + jumlah_jam_lembur + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + jumlah_jam_istirahat_lembur + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + status_aktif + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + status_staff + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + tanggal_resign + '</td>' +
                            '   <td class="text-nowrap text-center align-middle">' + sub_dept_name + '</td>' +
                            '</tr>';

                            $("#cruddatalemburverify").append(htmlTable2);
                        }
                    }
                    var text=all_uuid.toString();
                    $('#all_uuid').val(text);
                }
            });
        });

        $('#datatable-ajax-crud tbody').on( 'click', 'tr', function () {
            $("#datatable-ajax-crud tbody tr").each(function () {
                $(this).removeClass('bg-cyan');
            });

            $(this).addClass('bg-cyan');
        } );

        $('body').on('change', '#selectNoSPL', function () {
           // $("#btn-cari").click();
        });

        $('#searchData').keyup(function(){
            search_table($(this).val());
        });

        function search_table(value){
            $('#data-lembur tbody tr').each(function(){
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

        function hapus_data(hapusData)
        {
            console.log("hapusData",hapusData);
            var uuid_master = $("#uuid_master").val();
            var splitHapusData = hapusData.split('|');
            var tanggal_berjalan = splitHapusData[0];
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
                        var enroll_id = splitHapusData[1];
                        var nomor_form_lembur = splitHapusData[2];
                        var employee_name = splitHapusData[3];
                        var idBtn = splitHapusData[4];
                        var idBtn = "#" + idBtn;

                        $(idBtn).addClass("btn-loading");
                        $(idBtn).html('...');
                        $(idBtn).attr("disabled", true);

                        text  = nomor_form_lembur + " [" + enroll_id + "] " + employee_name;
                        message = "Data ini akan di HAPUS!!!";
                        type = "error";
                        swal({
                            title: message,
                            text: text,
                            type: type,
                            showCancelButton: true,
                            confirmButtonText: 'YA',
                            cancelButtonText: 'BATAL'
                        },function(isConfirm){
                            if(isConfirm) {
                                if(hapusData){

                                    $(idBtn).addClass("btn-loading");
                                    $(idBtn).attr("disabled", true);

                                    $.ajax({
                                        type:"POST",
                                        url: "{{route('hris.datalembur.remove')}}",
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                        data: {
                                            tanggal_berjalan:tanggal_berjalan,
                                            enroll_id:enroll_id,
                                            nomor_form_lembur:nomor_form_lembur,
                                            uuid_master:uuid_master,
                                        },
                                        dataType: 'json',
                                        success: function(res){
                                            notif({
                                                msg: "<b>Success:</b> Data berhasil di hapus.",
                                                type: "success"
                                            });

                                            $(idBtn).removeClass("btn-loading");
                                            $(idBtn).html('<span><i class="fa fa-trash"></i></span>');
                                            $(idBtn). attr("disabled", false);

                                            setTimeout(function myFunction() {
                                                location.reload();
                                            }, 3000);

                                        },
                                        error: function(res){
                                            notif({
                                                msg: "<b>Oops!</b> An Error Occurred",
                                                type: "error",
                                                position: "center"
                                            });

                                            $(idBtn).removeClass("btn-loading");
                                            $(idBtn).html('<span><i class="fa fa-trash"></i></span>');
                                            $(idBtn). attr("disabled", false);

                                        }
                                    });
                                }
                            } else {
                                $(idBtn).removeClass("btn-loading");
                                $(idBtn).html('<span><i class="fa fa-trash"></i></span>');
                                $(idBtn). attr("disabled", false);
                            }
                        });
                    }

                },
                error: function(res){

                }
            });

        };

        function edit_data(editData)
        {
            var splitEditData = editData.split('|');
            var tanggal_lembur = splitEditData[0];

            var tanggal = tanggal_lembur;

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
                        var rowid = splitEditData[1];

                        if (editData) {
                            $("#ajax-modal-edit1").modal('show');

                            var currentRow = $('#datatable-ajax-crud').find("tr:eq(" + rowid + ")");
                            var nomor_form_lembur = currentRow.find("td:eq(2)").html();
                            var enroll_id = currentRow.find("td:eq(6)").html();
                            var nik = currentRow.find("td:eq(7)").html();
                            var employee_name = currentRow.find("td:eq(8)").html();
                            var jumlah_jam_lembur = currentRow.find("td:eq(15)").html();
                            var jumlah_jam_istirahat_lembur = currentRow.find("td:eq(16)").html();
                            var catatan = currentRow.find('input[name="catatan"]').val();
                            var mulai_jam_lembur = currentRow.find('input[name="mulai_jam_lembur_edit1"]').val();
                            var akhir_jam_lembur = currentRow.find('input[name="akhir_jam_lembur_edit1"]').val();
                            var tanggal_berjalan = currentRow.find('input[name="tanggal_berjalan_edit1"]').val();

                            $('#title-modal-edit1').text('EDIT DATA LEMBUR KARYAWAN : [' + enroll_id + '][' + nik + '] ' + employee_name);
                            $('#tanggal_berjalan_edit1').val(tanggal_berjalan);
                            $('#enroll_id_edit1').val(enroll_id);
                            $('#nik_edit1').val(nik);
                            $('#employee_name_edit1').val(employee_name);
                            $('#nomor_form_lembur_edit1').val(nomor_form_lembur);
                            $('#jumlah_jam_lembur_edit1').val(jumlah_jam_lembur);
                            $('#jumlah_jam_istirahat_lembur_edit1').val(jumlah_jam_istirahat_lembur);
                            $('#catatan_edit1').val(catatan);
                            $('#waktu_jam_lembur_edit1').val(mulai_jam_lembur + ' - ' + akhir_jam_lembur);

                        }

                    }

                },
                error: function(res){

                }
            });

        };

        function defaultDate(s) {
            if(s) {
                var bits = s.split('-');
                var d = bits[2] + '-' + bits[1] + '-' + bits[0];
            }
            return d;
        }

        function jamlemburistirahat(mulai_jam_lembur, akhir_jam_lembur) {
            var dt1 = new Date(mulai_jam_lembur);
            var dt2 = new Date(akhir_jam_lembur);
            //alert(diff_hours(dt1, dt2));
            var jmljamlembur = diff_hours(dt1, dt2);
            var jamistirahatlembur = 0;

            if(jmljamlembur >= 1.5) {
                jamistirahatlembur = 0.5;
                jmljamlembur = jmljamlembur-jamistirahatlembur;
            }

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

        function tanggalSekarang() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;
            return today;
        }

        function diff_hours(dt2, dt1)
        {

         var diff =(dt2.getTime() - dt1.getTime()) / 1000;
         diff /= (60 * 60);
         return Math.abs(parseFloat(diff).toFixed(1));

        }

        $('body').on('click', '#checkAll', function (event) {
            if (this.checked) {
                $('#title-modal-edit1').text('EDIT DATA LEMBUR KARYAWAN : SEMUA KARYAWAN');
            } else {

                var enroll_id = $('#enroll_id_edit1').val();
                var nik = $('#nik_edit1').val();
                var employee_name = $('#employee_name_edit1').val();

                $('#title-modal-edit1').text('EDIT DATA LEMBUR KARYAWAN : [' + enroll_id + '][' + nik + '] ' + employee_name);
            }

        });

        $('body').on('click', '#btn-update_edit1', function (event) {
            var waktu_lembur = $('#waktu_jam_lembur_edit1').val();
            var tanggal_berjalan = $('#tanggal_berjalan_edit1').val();
            var nomor_form_lembur = $('#nomor_form_lembur_edit1').val();
            var enroll_id = $('#enroll_id_edit1').val();

            var splitWaktuLembur = waktu_lembur.split(" - ");
            var mulai_jam_lembur = splitWaktuLembur[0];
            var akhir_jam_lembur = splitWaktuLembur[1];

            var jumlah_jam_lembur = $('#jumlah_jam_lembur_edit1').val();
            var jumlah_jam_istirahat = $('#jumlah_jam_istirahat_lembur_edit1').val();
            var checkAll = $("#checkAll").is(":checked");
            var catatan = $('#catatan_edit1').val();

            $('#btn-update_edit1').addClass("btn-loading");
            $('#btn-update_edit1').html('Loading...');
            $('#btn-update_edit1').attr("disabled", true);


            if(checkAll) {
                $.ajax({
                    type:"POST",
                    url: "{{route('hris.datalembur.updatelemburall')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        tanggal_berjalan:tanggal_berjalan,
                        nomor_form_lembur:nomor_form_lembur,
                        enroll_id:enroll_id,
                        mulai_jam_lembur:mulai_jam_lembur,
                        akhir_jam_lembur:akhir_jam_lembur,
                        jumlah_jam_lembur:jumlah_jam_lembur,
                        jumlah_jam_istirahat:jumlah_jam_istirahat,
                        catatan:catatan,
                    },
                    dataType: 'json',
                    success: function(res){
                        notif({
                            msg: "<b>Success:</b> Data berhasil di simpan.",
                            type: "success"
                        });

                        $("#btn-update_edit1").removeClass("btn-loading");
                        $("#btn-update_edit1").html('Simpan');
                        $("#btn-update_edit1").attr("disabled", false);

                        $("#ajax-modal-edit1").modal('hide');
                        setTimeout(function myFunction() {
                            location.reload();
                          }, 3000);

                    },
                    error: function(res){
                        notif({
                            msg: "<b>Oops!</b> Simpan data lembur gagal.",
                            type: "error",
                            position: "center"
                        });

                        $("#btn-update_edit1").removeClass("btn-loading");
                        $("#btn-update_edit1").html('Simpan');
                        $("#btn-update_edit1").attr("disabled", false);

                    }
                });
            } else {
                $.ajax({
                    type:"POST",
                    url: "{{route('hris.datalembur.updatelembur')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        tanggal_berjalan:tanggal_berjalan,
                        nomor_form_lembur:nomor_form_lembur,
                        enroll_id:enroll_id,
                        mulai_jam_lembur:mulai_jam_lembur,
                        akhir_jam_lembur:akhir_jam_lembur,
                        jumlah_jam_lembur:jumlah_jam_lembur,
                        jumlah_jam_istirahat:jumlah_jam_istirahat,
                        catatan:catatan,
                    },
                    dataType: 'json',
                    success: function(res){
                        notif({
                            msg: "<b>Success:</b> Data berhasil di simpan.",
                            type: "success"
                        });

                        $("#btn-update_edit1").removeClass("btn-loading");
                        $("#btn-update_edit1").html('Simpan');
                        $("#btn-update_edit1").attr("disabled", false);

                        $("#ajax-modal-edit1").modal('hide');
                        setTimeout(function myFunction() {
                            location.reload();
                          }, 3000);

                    },
                    error: function(res){
                        notif({
                            msg: "<b>Oops!</b> Simpan data lembur gagal.",
                            type: "error",
                            position: "center"
                        });

                        $("#btn-update_edit1").removeClass("btn-loading");
                        $("#btn-update_edit1").html('Simpan');
                        $("#btn-update_edit1").attr("disabled", false);

                    }
                });
            }
        });

        $('body').on('click', '#btn-update_edit2', function (event) {

            var tgl = $('#periode_lembur').val();
            var tanggal = tgl.split(' s/d ');

            // LAGI COBA TEST CLOSING PAYROLL
            $.ajax({
                type:"POST",
                url: "{{route('hris.dataclosingpayroll.ajax_getclosing')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    tanggal:tanggal[0],
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

                        $("#ajax-modal-pilih1").modal('show');
                        $('#datatable-ajax-modal2').DataTable().clear();
                        $('#datatable-ajax-modal2').DataTable().destroy();
                        $('#datatable-ajax-modal2').empty();

                        $("#selectNoSPL_pilih1").empty();
                        $("#selectNoSPL_pilih1").val(null).trigger("change");

                        var periode_lembur = $('#periode_lembur').val();

                        if(periode_lembur){
                            $.ajax({
                                type:"POST",
                                url: "{{route('hris.datalembur.ajax_getnomorspl')}}",
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                data: {
                                    periode_lembur:periode_lembur,
                                },
                                dataType: 'json',
                                success: function(resA){
                                    if(resA){
                                        $("#selectNoSPL_pilih1").append(new Option("-- Pilih Nomor SPL --", ""));
                                        for(i=0;i<resA.length;i++) {
                                            $("#selectNoSPL_pilih1").append(new Option(resA[i].tanggal_nomor_spl, resA[i].nomor_form_lembur));
                                        }
                                    }
                                }
                            });
                        }

                        getemployee();

                    }

                },
                error: function(res){

                }
            });

        });

        $('body').on('click', '#btn_buat_serah_terima', function (event) {

            $("#ajax-modal-buat-serah-terima").modal('show');
            $('#datatable-ajax-modal-list-spl-checklist').DataTable().clear();
            $('#datatable-ajax-modal-list-spl-checklist').DataTable().destroy();
            $('#datatable-ajax-modal-list-spl-checklist').empty();

            $("#selectNoSPL_pilih1").empty();
            $("#selectNoSPL_pilih1").val(null).trigger("change");

            var periode_lembur = $('#periode_lembur').val();

            var tanggal = $('#daterange_serah_terima').val();
            console.log("tanggal", tanggal);
            if(tanggal){
                var table1 = $('#datatable-ajax-modal-list-spl-checklist').DataTable({
                processing: true,
                serverSide: true,
                lengthChange: false,
                pageLength: 10,
                destroy: true,
                "ajax": {
                    "url": "{{ route('hris.datalembur.ajax_getnomorspl_list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                    data: function (d) {
                        d.periode_lembur = tanggal; // tambahkan parameter di sini
                    },
                    onSuccess: function (data) {
                        console.log(data);
                    }
                },
                columns: [
                   {
                        title: 'NO',
                        data: null,           // Penting: null agar tidak mengambil data dari field apa pun
                        orderable: false,     // Jika tidak ingin bisa di-sort
                        searchable: false,    // Jika tidak ingin bisa di-search
                        render: function (data, type, row, meta) {
                            return meta.row + 1;   // meta.row dimulai dari 0, jadi tambahkan 1
                        }
                    },
                    {
                        title: 'TANGGAL PENGINPUTAN',
                        data: 'created_at',
                        orderable: false,
                        name: 'created_at',
                        render: function (data, type, row) {
                            if (!data) return '-';

                            // Ubah format tanggal di sini
                            const bulanIndo = [
                                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                            ];

                            const tanggal = new Date(data);
                            const hari = tanggal.getDate();
                            const bulan = bulanIndo[tanggal.getMonth()];
                            const tahun = tanggal.getFullYear();

                            return `${hari} ${bulan} ${tahun}`;
                        }
                    },
                    {
                        title: 'TANGGAL SPL',
                        data: 'tanggal_berjalan',
                        orderable: false,
                        name: 'tanggal_berjalan',
                        render: function (data, type, row) {
                            if (!data) return '-';

                            // Ubah format tanggal di sini
                            const bulanIndo = [
                                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                            ];

                            const tanggal = new Date(data);
                            const hari = tanggal.getDate();
                            const bulan = bulanIndo[tanggal.getMonth()];
                            const tahun = tanggal.getFullYear();

                            return `${hari} ${bulan} ${tahun}`;
                        }
                    },
                    {
                        title: 'NO SPL',
                        data: 'nomor_form_lembur',
                        orderable: false,
                        name: 'nomor_form_lembur'
                    },
                    {
                        title: 'JML DATA',
                        data: 'jml_data',
                        orderable: false,
                        name: 'jml_data',
                        className: 'text-center align-middle',
                    },
                  {
                        title: 'SERAH TERIMA',
                        orderable: false,
                        data: null,
                        className: 'text-center align-middle',
                        render: function (data, type, row, meta) {
                            return `
                                <input type="checkbox"
                                    class="checkbox-serah-terima"
                                    data-nomor="${row.nomor_form_lembur}"
                                    data-uuid="${row.uuid}"
                                    data-tanggal="${row.tanggal_berjalan}"
                                    data-default="${row.serah_terima ? '1' : '0'}"
                                    ${row.serah_terima ? 'checked' : ''}
                                    style="transform: scale(1.2); vertical-align: middle;">
                            `;
                        }
                    }

                ],
            });

            table1.draw();
            }
        });

        $('body').on('click', '#btn-save-serah-terima', function () {

            let daftarSPLTambah = []; // checked baru
            let daftarSPLHapus = [];  // uncheck dari sebelumnya

            $('.checkbox-serah-terima').each(function () {
                const isChecked = $(this).is(':checked');
                const isDefault = $(this).data('default') == '1'; // dari database

                const dataSPL = {
                    nomor: $(this).data('nomor'),
                    tanggal: $(this).data('tanggal'),
                    uuid: $(this).data('uuid')
                };

                if (isChecked && !isDefault) {
                    // baru dicentang
                    daftarSPLTambah.push(dataSPL);
                } else if (!isChecked && isDefault) {
                    // sebelumnya dicentang, sekarang uncheck
                    daftarSPLHapus.push(dataSPL);
                }
            });

            console.log('Tambah:', daftarSPLTambah);
            console.log('Hapus:', daftarSPLHapus);

             $.ajax({
                type:"POST",
                url: "{{route('hris.datalembur.create_serah_terima_lembur')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    daftarSPLTambah:daftarSPLTambah,
                    daftarSPLHapus:daftarSPLHapus,
                },
                dataType: 'json',
                success: function(res){
                        notif({
                            msg: "<b>Success:</b> Data berhasil di simpan.",
                            type: "success"
                        });
                        $("#ajax-modal-buat-serah-terima").modal('hide');

                        // $('#datatable-ajax-modal-list-spl-checklist').on('draw.dt', function () {
                        //     $('.checkbox-serah-terima').prop('checked', false);
                        // });


                       if ( $.fn.DataTable.isDataTable('#datatable-ajax-modal-list-spl-checklist') ) {
                            $('#datatable-ajax-modal-list-spl-checklist').DataTable().clear().destroy();
                        }


                    },
                error: function(res){
                    notif({
                        msg: "<b>Oops!</b> Simpan realisasi lembur gagal.",
                        type: "error",
                        position: "center"
                    });
                }
            });
        });


        $('body').on('click', '#btn-close-serah-terima', function () {
            if ($.fn.DataTable.isDataTable('#datatable-ajax-modal-list-spl-checklist')) {
                $('#datatable-ajax-modal-list-spl-checklist').DataTable().clear().destroy();
            }
            $("#ajax-modal-buat-serah-terima").modal('hide');
        });


        function getemployee() {
            var table1 = $('#datatable-ajax-modal2').DataTable({
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

                if ($('#selectEmp_tambah').find("option[value='" + data['enroll_id'] + "']").length < 1) {
                    $("#selectEmp_tambah").append(new Option(data['employee_name'], data['enroll_id']));
                }

                $('#selectEmp_tambah').multipleSelect({
                    selectAll: true,
                    width: "100%",
                    filter: true,
                    sort: true,
                });

                $("#selectEmp_tambah").find("option[value='" + data['enroll_id'] +"'").attr("selected","selected");

            });

        }

        $('body').on('click', '#btn-save2', function (event) {
            var selectEmp = $('#selectEmp_tambah').val();
            var nomor_form_lembur = $('#selectNoSPL_pilih1').val();

            var waktu_lembur = $('#waktu_jam_lembur_tambah').val();

            var splitWaktuLembur = waktu_lembur.split(" - ");
            var mulai_jam_lembur = splitWaktuLembur[0];
            var akhir_jam_lembur = splitWaktuLembur[1];

            var jumlah_jam_lembur = $('#jumlah_jam_lembur_tambah').val();
            var jumlah_jam_istirahat = $('#jumlah_jam_istirahat_tambah').val();
            var catatan = $('#catatan_tambah').val();

            $.ajax({
                type:"POST",
                url: "{{route('hris.datalembur.tambahkaryawan')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    mulai_jam_lembur:mulai_jam_lembur,
                    akhir_jam_lembur:akhir_jam_lembur,
                    jumlah_jam_lembur:jumlah_jam_lembur,
                    jumlah_jam_istirahat:jumlah_jam_istirahat,
                    selectEmp:selectEmp,
                    nomor_form_lembur:nomor_form_lembur,
                    catatan:catatan,
                },
                dataType: 'json',
                success: function(res){
                        notif({
                            msg: "<b>Success:</b> Data berhasil di simpan.",
                            type: "success"
                        });

                        $("#btn-save2").removeClass("btn-loading");
                        $("#btn-save2").html('Simpan');
                        $("#btn-save2").attr("disabled", false);

                        $("#ajax-modal-pilih1").modal('hide');
                        setTimeout(function myFunction() {
                            location.reload();
                          }, 3000);

                    },
                error: function(res){
                    notif({
                        msg: "<b>Oops!</b> Simpan data lembur gagal.",
                        type: "error",
                        position: "center"
                    });

                    $("#btn-save2").removeClass("btn-loading");
                    $("#btn-save2").html('Simpan');
                    $("#btn-save2").attr("disabled", false);

                    setTimeout(function myFunction() {
                            location.reload();
                          }, 3000);
                }
            });
        });


        $('body').on('click', '#btn-hapus-nospl', function (event) {
            getnomorsplhapus();
        });

        $('body').on('click', '#btn-remove2', function (event) {
            hapus_nospl_data();
        });

        function hapus_nospl_data()
        {
            var selectNoSPL = $("#selectNoSPLHapus2").val();

            text  = "Ada " + selectNoSPL.length + " Nomor SPL yang akan di hapus!!!";
            message = "Data ini akan di HAPUS!!!";
            type = "error";
            swal({
                title: message,
                text: text,
                type: type,
                showCancelButton: true,
                confirmButtonText: 'YA',
                cancelButtonText: 'BATAL'
            },function(isConfirm){
                if(isConfirm) {
                    if(selectNoSPL){

                        $('#btn-remove2').addClass("btn-loading");
                        $('#btn-remove2').html('Loading...');
                        $('#btn-remove2').attr("disabled", true);

                        $.ajax({
                            type:"POST",
                            url: "{{route('hris.datalembur.removenospl')}}",
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            data: {
                                selectNoSPL:selectNoSPL,
                            },
                            dataType: 'json',
                            success: function(res){
                                notif({
                                    msg: "<b>Success:</b> Data berhasil di hapus.",
                                    type: "success"
                                });

                                $('#btn-remove2').removeClass("btn-loading");
                                $('#btn-remove2').html('<span><i class="fa fa-save"></i> Hapus NO SPL</span>');
                                $('#btn-remove2'). attr("disabled", false);

                                setTimeout(function myFunction() {
                                    location.reload();
                                  }, 3000);

                            },
                            error: function(res){
                                notif({
                                    msg: "<b>Oops!</b> An Error Occurred",
                                    type: "error",
                                    position: "center"
                                });

                                $('#btn-remove2').removeClass("btn-loading");
                                $('#btn-remove2').html('<span><i class="fa fa-save"></i> Hapus NO SPL</span>');
                                $('#btn-remove2'). attr("disabled", false);

                            }
                        });
                    }
                } else {
                    $('#btn-remove2').removeClass("btn-loading");
                    $('#btn-remove2').html('<span><i class="fa fa-save"></i> Hapus NO SPL</span>');
                    $('#btn-remove2'). attr("disabled", false);
                }
            });
        };
    </script>
    <script>
        $('#verifikasi').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{route('hris.datalembur.verifikasi')}}",
                data: $(this).serialize(),
                success: function(response)
                {
                    notif({
                        msg: "<b>Info:</b> Data berhasil di verifikasi.",
                        type: "info"
                    });
                }
            });
            TampilDataLembur();
        });
        function TampilDataLembur() {
            $("#subtitle-table1").empty();
            $("#subtitle-table_verif").empty();

            var periode_lembur = $('#periode_lembur').val();
            var selectNoSPL = $('#selectNoSPL').val();
            var searchData = $('#searchData').val();
            var verificationStatus = $('#selectVerificationStatus').val();

            $("#btn-cari").addClass("btn-loading");
            $("#btn-cari").html('Loading...');
            $("#btn-cari").attr("disabled", true);

            $("#data-lembur tbody").empty();
            $("#data-lembur").show();
            $("#loadingProcess").show();

            if(periode_lembur){

                if(selectNoSPL.length == 0){
                }

                $.ajax({
                    type:"POST",
                    url: "{{route('hris.datalembur.ajax_datalembur')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        periode_lembur:periode_lembur,
                        selectNoSPL:selectNoSPL,
                        searchData:searchData,
                        verificationStatus:verificationStatus
                    },
                    dataType: 'json',
                    success: function(res){
                        if(res.length > 0){
                            nomor_urut=0;
                            for(i=0;i<res.length;i++) {
                                if (res[i].data_lembur.is_verifikasi==0) {
                                    nomor_urut=nomor_urut+1;
                                    var uuid = res[i].data_lembur.uuid
                                    var tanggal_lembur = moment(res[i].tanggal_berjalan).format('DD MMM YYYY');
                                    var status_kerja = res[i].kode_hari;
                                    if(status_kerja!=5 && status_kerja!=6 && res[i].holiday_name!=null){
                                        status_kerja='Libur';
                                    }else if(status_kerja==5 || status_kerja==6 && res[i].holiday_name!=null){
                                        status_kerja='Libur';
                                    }else if(status_kerja==5 || status_kerja==6 && res[i].holiday_name==null){
                                        status_kerja='Kerja';
                                    }else if(status_kerja!=5 && status_kerja!=6 && res[i].holiday_name==null){
                                        status_kerja='Kerja';
                                    }
                                    var tanggal_resign = res[i].employee_atribut.tanggal_resign;
                                    var mulai_jam_kerja = res[i].mulai_jam_kerja;
                                    var akhir_jam_kerja = res[i].akhir_jam_kerja;
                                    var absen_masuk_kerja = res[i].absen_masuk_kerja;
                                    var absen_pulang_kerja = res[i].absen_pulang_kerja;
                                    var mulai_jam_lembur = res[i].data_lembur.mulai_jam_lembur;
                                    var akhir_jam_lembur = res[i].data_lembur.akhir_jam_lembur;
                                    var sub_dept_name = res[i].employee_atribut.dept ? res[i].employee_atribut.dept.sub_dept_name : "-";
                                    var jumlah_jam_lembur = res[i].data_lembur.jumlah_jam_lembur;
                                    var jumlah_jam_istirahat_lembur = res[i].data_lembur.jumlah_jam_istirahat_lembur;
                                    var catatan = res[i].data_lembur.catatan;
                                    var mulai_jam_lembur_edit1 = res[i].data_lembur.mulai_jam_lembur;
                                    var akhir_jam_lembur_edit1 = res[i].data_lembur.akhir_jam_lembur;
                                    var kode_hari = res[i].kode_hari;
                                    var bgwarna = '';
                                    if(status_kerja == 'LIBUR') { bgwarna = 'style="background: yellow"'; }
                                    if(!tanggal_lembur) { bgwarna = 'style="background: red"'; tanggal_lembur = ''; }
                                    if(tanggal_resign) { bgwarna = 'style="background: red"'; tanggal_resign = moment(res[i].tanggal_resign).format('DD MMM YYYY'); } else { tanggal_resign = ''; }
                                    if(!mulai_jam_kerja) { bgwarna = 'style="background: red"'; mulai_jam_kerja = ''; }
                                    if(!akhir_jam_kerja) { bgwarna = 'style="background: red"'; akhir_jam_kerja = ''; }
                                    if(!absen_masuk_kerja) { bgwarna = 'style="background: red"'; absen_masuk_kerja = ''; }
                                    if(!absen_pulang_kerja) { bgwarna = 'style="background: red"'; absen_pulang_kerja = ''; }
                                    if(!mulai_jam_lembur) { bgwarna = 'style="background: red"'; mulai_jam_lembur = ''; } else { split_mulai_jam_lembur = mulai_jam_lembur.split(' '); mulai_jam_lembur = moment(split_mulai_jam_lembur[0]).format('DD MMM YYYY') + " " + split_mulai_jam_lembur[1]; }
                                    if(!akhir_jam_lembur) { bgwarna = 'style="background: red"'; akhir_jam_lembur = ''; } else { split_akhir_jam_lembur = akhir_jam_lembur.split(' '); akhir_jam_lembur = moment(split_akhir_jam_lembur[0]).format('DD MMM YYYY') + " " + split_akhir_jam_lembur[1]; }
                                    if(!mulai_jam_lembur_edit1) { mulai_jam_lembur_edit1 = ''; } else { split_mulai_jam_lembur_edit1 = mulai_jam_lembur_edit1.split(' '); mulai_jam_lembur_edit1 = defaultDate(split_mulai_jam_lembur_edit1[0]) + " " + split_mulai_jam_lembur_edit1[1]; }
                                    if(!akhir_jam_lembur_edit1) { akhir_jam_lembur_edit1 = ''; } else { split_akhir_jam_lembur_edit1 = akhir_jam_lembur_edit1.split(' '); akhir_jam_lembur_edit1 = defaultDate(split_akhir_jam_lembur_edit1[0]) + " " + split_akhir_jam_lembur_edit1[1]; }
                                    if(!sub_dept_name) { bgwarna = 'style="background: red"'; sub_dept_name = ''; }
                                    if(!jumlah_jam_lembur) { jumlah_jam_lembur = 0; }
                                    if(!jumlah_jam_istirahat_lembur) { jumlah_jam_istirahat_lembur = 0; }
                                    if(!catatan) { catatan = ''; }
                                    if((kode_hari == 5) || (kode_hari == 6)) { bgwarna = 'style="background: yellow"'; }
                                    var editData = res[i].tanggal_berjalan + '|' + nomor_urut;
                                    var hapusData = res[i].tanggal_berjalan + '|' + res[i].enroll_id + '|' + res[i].data_lembur.nomor_form_lembur + '|' + res[i].employee_atribut.employee_name + '|' + 'btn-remove_' + nomor_urut  + res[i].uuid_master;

                                    htmlTable = '' +
                                    '<tr ' + bgwarna + ' class="text-center">' +
                                    '   <td class="text-nowrap text-right align-middle">' +
                                    '       <button id="btn-remove_' + nomor_urut + '" class="btn btn-danger p-0 mr-0 text-white border-white btn-icon" ' +
                                    ' onClick=\'hapus_data("' + hapusData + '")\' ' +
                                    '           data-toggle="tooltip" title="Hapus Data">' +
                                    '           <span>' +
                                    '               <i class="fa fa-trash"></i>' +
                                    '           </span>' +
                                    '       </button>' +
                                    '       <button type="button" id="btn-edit_' + nomor_urut + '" class="btn btn-secondary p-0 mr-1 text-white border-white btn-icon" ' +
                                    ' onClick=\'edit_data("' + editData + '")\' ' +
                                    '           data-toggle="tooltip" title="Edit Data">' +
                                    '           <span>' +
                                    '               <i class="fa fa-edit"></i>' +
                                    '           </span>' +
                                    '       </button>' +
                                    '       <input type="hidden" id="tanggal_berjalan_edit1_' + i + '" name="tanggal_berjalan_edit1" value="' + res[i].tanggal_berjalan + '" >' +
                                    '       <input type="hidden" id="catatan_' + i + '" name="catatan" value="' + catatan + '" >' +
                                    '       <input type="hidden" id="mulai_jam_lembur_edit1_' + i + '" name="mulai_jam_lembur_edit1" value="' + mulai_jam_lembur_edit1 + '" >' +
                                    '       <input type="hidden" id="akhir_jam_lembur_edit1_' + i + '" name="akhir_jam_lembur_edit1" value="' + akhir_jam_lembur_edit1 + '" >' +
                                    '   </td>' +

                                    '   <td> <input type="checkbox" class="checked" name="uuid[]"  value="'+res[i].data_lembur.uuid+'">'+'</td>'+

                                    '   <td class="text-nowrap text-center align-middle">' + res[i].data_lembur.nomor_form_lembur  + '</td>' +
                                    '   <td class="text-nowrap text-center align-middle">' + tanggal_lembur  + '</td>' +
                                    '   <td class="text-nowrap text-left align-middle">' + res[i].nama_hari  + '</td>' +
                                    '   <td class="text-nowrap text-left align-middle">' + status_kerja  + '</td>' +
                                    '   <td class="text-nowrap text-left align-middle">' + res[i].enroll_id  + '</td>' +
                                    '   <td class="text-nowrap text-left align-middle">' + res[i].employee_atribut.nik  + '</td>' +
                                    '   <td class="text-left align-middle">' + res[i].employee_atribut.employee_name  + '</td>' +
                                    '   <td class="text-nowrap text-center align-middle">' + mulai_jam_kerja  + '</td>' +
                                    '   <td class="text-nowrap text-center align-middle">' + akhir_jam_kerja  + '</td>' +
                                    '   <td class="text-nowrap text-center align-middle">' + absen_masuk_kerja  + '</td>' +
                                    '   <td class="text-nowrap text-center align-middle">' + absen_pulang_kerja  + '</td>' +
                                    '   <td class="text-nowrap text-center align-middle">' + mulai_jam_lembur  + '</td>' +
                                    '   <td class="text-nowrap text-center align-middle">' + akhir_jam_lembur  + '</td>' +
                                    '   <td class="text-nowrap text-right align-middle">' + jumlah_jam_lembur  + '</td>' +
                                    '   <td class="text-nowrap text-right align-middle">' + jumlah_jam_istirahat_lembur  + '</td>' +
                                    '   <td class="text-nowrap text-center align-middle">' + res[i].employee_atribut.status_aktif  + '</td>' +
                                    '   <td class="text-nowrap text-center align-middle">' + res[i].employee_atribut.status_staff  + '</td>' +
                                    '   <td class="text-nowrap text-center align-middle">' + tanggal_resign  + '</td>' +
                                    '   <td class="text-left align-middle">' + sub_dept_name  + '</td>' +
                                    '</tr>';

                                    $("#datatable-ajax-crud tbody").append(htmlTable);
                                }
                            }
                            $("#subtitle-table1").append("");
                            $("#subtitle-table1").append("Total data yang ditemukan ada: " + nomor_urut);

                            nomor_urut2= 0;
                            for(i=0;i<res.length;i++) {
                                // nomor_urut2=0;
                                if (res[i].data_lembur.is_verifikasi==1) {
                                    var nomor_urut2 = nomor_urut2+1;
                                    var uuid = res[i].data_lembur.uuid
                                    var tanggal_lembur = moment(res[i].tanggal_berjalan).format('DD MMM YYYY');
                                    var status_kerja = res[i].kode_hari;
                                    if(status_kerja!=5 && status_kerja!=6 && res[i].holiday_name!=null){
                                        status_kerja='Libur';
                                    }else if(status_kerja==5 || status_kerja==6 && res[i].holiday_name!=null){
                                        status_kerja='Libur';
                                    }else if(status_kerja==5 || status_kerja==6 && res[i].holiday_name==null){
                                        status_kerja='Kerja';
                                    }else if(status_kerja!=5 && status_kerja!=6 && res[i].holiday_name==null){
                                        status_kerja='Kerja';
                                    }
                                    var tanggal_resign = res[i].employee_atribut.tanggal_resign;
                                    var mulai_jam_kerja = res[i].mulai_jam_kerja;
                                    var akhir_jam_kerja = res[i].akhir_jam_kerja;
                                    var absen_masuk_kerja = res[i].absen_masuk_kerja;
                                    var absen_pulang_kerja = res[i].absen_pulang_kerja;
                                    var mulai_jam_lembur = res[i].data_lembur.mulai_jam_lembur;
                                    var akhir_jam_lembur = res[i].data_lembur.akhir_jam_lembur;
                                    var sub_dept_name = res[i].employee_atribut.dept ? res[i].employee_atribut.dept.sub_dept_name : "-";
                                    var jumlah_jam_lembur = res[i].data_lembur.jumlah_jam_lembur;
                                    var jumlah_jam_istirahat_lembur = res[i].data_lembur.jumlah_jam_istirahat;
                                    var catatan = res[i].data_lembur.catatan;
                                    var mulai_jam_lembur_edit1 = res[i].mulai_jam_lembur;
                                    var akhir_jam_lembur_edit1 = res[i].akhir_jam_lembur;
                                    var kode_hari = res[i].kode_hari;
                                    var bgwarna = '';
                                    if(status_kerja == 'LIBUR') { bgwarna = 'style="background: yellow"'; }
                                    if(!tanggal_lembur) { bgwarna = 'style="background: red"'; tanggal_lembur = ''; }
                                    if(tanggal_resign) { bgwarna = 'style="background: red"'; tanggal_resign = moment(res[i].employee_atribut.tanggal_resign).format('DD MMM YYYY'); } else { tanggal_resign = ''; }
                                    if(!mulai_jam_kerja) { bgwarna = 'style="background: red"'; mulai_jam_kerja = ''; }
                                    if(!akhir_jam_kerja) { bgwarna = 'style="background: red"'; akhir_jam_kerja = ''; }
                                    if(!absen_masuk_kerja) { bgwarna = 'style="background: red"'; absen_masuk_kerja = ''; }
                                    if(!absen_pulang_kerja) { bgwarna = 'style="background: red"'; absen_pulang_kerja = ''; }
                                    if(!mulai_jam_lembur) { bgwarna = 'style="background: red"'; mulai_jam_lembur = ''; } else { split_mulai_jam_lembur = mulai_jam_lembur.split(' '); mulai_jam_lembur = moment(split_mulai_jam_lembur[0]).format('DD MMM YYYY') + " " + split_mulai_jam_lembur[1]; }
                                    if(!akhir_jam_lembur) { bgwarna = 'style="background: red"'; akhir_jam_lembur = ''; } else { split_akhir_jam_lembur = akhir_jam_lembur.split(' '); akhir_jam_lembur = moment(split_akhir_jam_lembur[0]).format('DD MMM YYYY') + " " + split_akhir_jam_lembur[1]; }
                                    if(!mulai_jam_lembur_edit1) { mulai_jam_lembur_edit1 = ''; } else { split_mulai_jam_lembur_edit1 = mulai_jam_lembur_edit1.split(' '); mulai_jam_lembur_edit1 = defaultDate(split_mulai_jam_lembur_edit1[0]) + " " + split_mulai_jam_lembur_edit1[1]; }
                                    if(!akhir_jam_lembur_edit1) { akhir_jam_lembur_edit1 = ''; } else { split_akhir_jam_lembur_edit1 = akhir_jam_lembur_edit1.split(' '); akhir_jam_lembur_edit1 = defaultDate(split_akhir_jam_lembur_edit1[0]) + " " + split_akhir_jam_lembur_edit1[1]; }
                                    if(!sub_dept_name) { bgwarna = 'style="background: red"'; sub_dept_name = ''; }
                                    if(!jumlah_jam_lembur) { jumlah_jam_lembur = 0; }
                                    if(!jumlah_jam_istirahat_lembur) { jumlah_jam_istirahat_lembur = 0; }
                                    if(!catatan) { catatan = ''; }
                                    if((kode_hari == 5) || (kode_hari == 6)) { bgwarna = 'style="background: yellow"'; }

                                    var hapusData = res[i].tanggal_berjalan + '|' + res[i].employee_atribut.enroll_id + '|' + res[i].data_lembur.nomor_form_lembur + '|' + res[i].employee_atribut.employee_name + '|' + 'btn-remove_' + nomor_urut;
                                    var editData = res[i].tanggal_berjalan + '|' + nomor_urut;

                                    var url = "{{ route('hris.datalembur.unverifikasi',['uuid'=>'random_id']) }}";
                                    url=url.replace('random_id',uuid);

                                    htmlTable = '' +
                                    '<tr ' + bgwarna + ' class="text-center">' +
                                    @if($loggedAdmin->email === 'willy@ptnag.com' || $loggedAdmin->role_user === 'superadmin' )
                                    '  <td class="text-nowrap text-right align-middle">' +
                                    '  <a class="btn btn-secondary p-0 mr-1 text-white border-white unverif" onclick="handleClick(this)" href="'+ url + '"  no_spl="' + res[i].data_lembur.nomor_form_lembur  + '">UNVERIFY</a>'+
                                    '  </td>' +
                                    @endif
                                    '   <td class="text-nowrap text-center align-middle">' + res[i].data_lembur.nomor_form_lembur  + '</td>' +
                                    '   <td class="text-nowrap text-center align-middle">' + tanggal_lembur  + '</td>' +
                                    '   <td class="text-nowrap text-left align-middle">' + res[i].nama_hari  + '</td>' +
                                    '   <td class="text-nowrap text-left align-middle">' + status_kerja  + '</td>' +
                                    '   <td class="text-nowrap text-left align-middle">' + res[i].enroll_id  + '</td>' +
                                    '   <td class="text-nowrap text-left align-middle">' + res[i].employee_atribut.nik  + '</td>' +
                                    '   <td class="text-left align-middle">' + res[i].employee_atribut.employee_name  + '</td>' +
                                    '   <td class="text-nowrap text-center align-middle">' + mulai_jam_kerja  + '</td>' +
                                    '   <td class="text-nowrap text-center align-middle">' + akhir_jam_kerja  + '</td>' +
                                    '   <td class="text-nowrap text-center align-middle">' + absen_masuk_kerja  + '</td>' +
                                    '   <td class="text-nowrap text-center align-middle">' + absen_pulang_kerja  + '</td>' +
                                    '   <td class="text-nowrap text-center align-middle">' + mulai_jam_lembur  + '</td>' +
                                    '   <td class="text-nowrap text-center align-middle">' + akhir_jam_lembur  + '</td>' +
                                    '   <td class="text-nowrap text-right align-middle">' + jumlah_jam_lembur  + '</td>' +
                                    '   <td class="text-nowrap text-right align-middle">' + jumlah_jam_istirahat_lembur  + '</td>' +
                                    '   <td class="text-nowrap text-center align-middle">' + res[i].employee_atribut.status_aktif  + '</td>' +
                                    '   <td class="text-nowrap text-center align-middle">' + res[i].employee_atribut.status_staff  + '</td>' +
                                    '   <td class="text-nowrap text-center align-middle">' + tanggal_resign  + '</td>' +
                                    '   <td class="text-left align-middle">' + sub_dept_name  + '</td>' +
                                    '   <td class="text-left align-middle">' + res[i].data_lembur.verifikasi_by  + '</td>' +
                                    '</tr>';

                                    $("#datatable-verifikasi tbody").append(htmlTable);
                                }
                            }
                            $("#subtitle-table_verif").append("");
                            $("#subtitle-table_verif").append("Total data yang ditemukan ada: " + nomor_urut2);
                        }


                        else {
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

            }
        }

        $('#datatable-verifikasi tbody').on( 'click', 'tr', function () {
            $("#datatable-verifikasi tbody tr").each(function () {
                $(this).removeClass('bg-cyan');
            });

            $(this).addClass('bg-cyan');
        } );

        $('#checkAllVerif').click(function() {
            if (this.checked) {
            $(':checkbox').each(function() {
                this.checked = true;
            });
            } else {
            $(':checkbox').each(function() {
                this.checked = false;
            });
            }
        });

        function handleClick(element) {
            event.preventDefault();
            var href = element.href;
            var no_spl = element.getAttribute('no_spl');
            swal({
                title: 'UNVERIFY SPL',
                text: 'Apakah yakin no spl '+ no_spl +'di un verifikasi ? ',
                type: 'error',
                showCancelButton: true,
                confirmButtonText: 'YA',
                cancelButtonText: 'BATAL'
            }, function(isConfirm) {
                if (isConfirm) {
                $.ajax({
                    type: "GET",
                    url: href,
                    data: $(this).serialize(),
                    success: function(response)
                    {
                        notif({
                            msg: "<b>Info:</b> Data berhasil di simpan.",
                            type: "info"
                        });
                    }
                });
                TampilDataLembur();
                }
            });
        }

        const user_login= <?php echo json_encode($loggedAdmin->email); ?>;

    </script>

@endsection
