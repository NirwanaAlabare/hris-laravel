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
                <button type="button" class="btn btn-app btn-success mr-0 mt-0 mb-0" data-target="#import_data_lembur" data-toggle="modal"><i class="fa fa-file-excel-o"></i> Import</button>
                @if($loggedAdmin->email=='mega@ptnag.com' || $loggedAdmin->email=='indri@nag.nirwanaindonesia.com' || $loggedAdmin->email=='ersa@ptnag.com' || $loggedAdmin->email == 'fadli' || $loggedAdmin->email == 'rudy@ptnag.com')
                <button type="button" class="btn btn-app btn-success mr-0 mt-0 mb-0" data-target="#import_data_lembur_from_nds" data-toggle="modal"><i class="fa fa-database"></i> Import HRIS</button>
                @endif
                <a href="javascript:void(0)" id="btn-paste" class="btn btn-app btn-secondary mr-0 mt-0 mb-0" data-toggle="tooltip" title="Tempel Data"><i class="fa fa-paste"></i> Tempel</a>
            </div>
        </div>
    </div>

    <div class="modal fade" id="import_data_lembur" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 1330px">
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-content">
                        <div class="modal-header bg-primary p-2">
                            <h4 class="modal-title pl-2 font-weight-bold" >Import Data Lembur</h4>
                            <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                        <div class="modal-body pt-5 pb-0 px-6">
                            <div class="row">
                                <div class="col-12">
                                    <input class="form-control" ref="excel_filess" name="excel_filess" id="excel_filess" type="file" accept=".xlsx, .xls, .csv" required>
                                </div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-4 pl-5">
                                    <table>
                                        <tr>
                                            <td id="nomor_form_lembur" style="font-weight: bold; color:rgb(34, 189, 203)">
                                            </td>
                                            <td>
                                                <a href="#" onclick="getLastNomorFormLembur()" id="btn-icon-refresh2"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-4 text-center" style="font-weight: bold; color:rgb(203, 34, 34);visibility: hidden" id="warning_error">
                                </div>
                                <div class="col-4 text-right pr-6" id="length" style="font-weight: bold; color:rgb(34, 189, 203)">
                                </div>
                            </div>
                            <div class="row pt-2 justify-content-center">
                                <div class="col-12">
                                    <table class="table table-bordered" style="overflow-x:auto">
                                        <thead id="head_karyawan">
                                            <tr>
                                                <td width="60px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">ID</td>
                                                <td width="100px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">NIK</td>
                                                <td width="100px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">TANGGAL</td>
                                                <td width="150px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">NAMA KARYAWAN</td>
                                                <td width="90px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">DARI</td>
                                                <td width="90px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">SAMPAI</td>
                                                <td width="90px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">ACT IN</td>
                                                <td width="90px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">ACT OUT</td>
                                                <td width="50px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">L</td>
                                                <td width="50px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">I</td>
                                                <td width="150px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">KETERANGAN</td>
                                                <td width="150px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">STATUS ABSEN</td>
                                            </tr>
                                        </thead>
                                        <tbody id="tabel_data_lembur">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-12 text-center">
                                    <div id="loading_karyawan_lembur">
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-0 pb-3 pr-3">
                                <div class="col-2"></div>
                                <div class="col-8 text-center pt-2">
                                    <button type="button" id="overtimeImportButton" class="btn btn-primary py-1" style="visibility: hidden"><i class="fa fa-upload" aria-hidden="true"></i> IMPORT</button>
                                </div>
                                <div class="col-2 pl-8 pt-1" id="keterangan" style="visibility: hidden">
                                    <label class="mb-0" style="font-size:10pt">L : Waktu Lembur</label><br>
                                    <label style="font-size:10pt">I &nbsp;: Waktu Istirahat</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-primary pt-3 pb-2">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="import_data_lembur_from_nds" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 1200px">
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title pl-2 font-weight-bold" >Import data lembur dari NDS</h4>
                            <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-2">
                                    <h5 class="modal-title pl-2 pt-1 font-weight-bold" >Tanggal Lembur</h5>
                                </div>
                                <div class="col-3">
                                    <input type="date" class="form-control" id="tanggal_lembur_nds" style="background-color: white">
                                </div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-2">
                                    <h5 class="modal-title pl-2 pt-1 font-weight-bold" >Daftar Karyawan</h5>
                                </div>
                                <div class="col-3">
                                    <select id="selectNoForm" name="selectNoForm" class="form-control" style="background-color: white">
                                    </select>
                                </div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-2">
                                    <h5 class="modal-title pl-2 pt-1 font-weight-bold" >Nomor Form Lembur</h5>
                                </div>
                                <div class="col-3">
                                    <input class="form-control pt-1" id="new_nomor_form_lembur" readonly>
                                </div>
                                <div class="col-1 pt-1 pl-0">
                                    <a href="#" onclick="get_new_nomor_form_lembur()" id="btn-icon-refresh_new_form_lembur" style="visibility: hidden; font-size: 12pt"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-12">
                                    <table class="table table-bordered" style="overflow-x:auto">
                                        <thead id="head_overtime_from_nds">
                                            <tr>
                                                <td rowspan="2" width="45px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">NO</td>
                                                <td rowspan="2" width="60px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">ID</td>
                                                <td rowspan="2" width="100px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">NIK</td>
                                                <td rowspan="2" width="200px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">NAMA KARYAWAN</td>
                                                <td colspan="3" width="180px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">Actual Absent</td>
                                                <td rowspan="2" width="115px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">DARI</td>
                                                <td rowspan="2" width="115px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">SAMPAI</td>
                                                <td rowspan="2" width="80px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">I</td>
                                                <td rowspan="2" width="80px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">T</td>
                                                <td rowspan="2" width="125px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">KET</td>
                                                <td rowspan="2" width="40px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px"><span class="fa fa-cog"></span></td>
                                            </tr>
                                            <tr>
                                                <td width="60px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">IN</td>
                                                <td width="60px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">OUT</td>
                                                <td width="60px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">Stats</td>
                                            </tr>
                                        </thead>
                                        <tbody id="tabel_overtime_from_nds">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row py-0">
                                <div class="col-4" id="data_ada_dan_tidak" style="visibility: hidden">
                                    <table style="font-weight: bold">
                                        <tr>
                                            <td>Total</td>
                                            <td><input type="text" class="form-control col-3" id="total_new_data_lembur" readonly style="background-color: white"></td>
                                        </tr>
                                        <tr>
                                            <td width="150">Data will be import </td>
                                            <td><input type="text" class="form-control col-3" id="total_new_data_lembur_import" readonly style="background-color: white"></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-4 text-center pt-1">
                                    <button class="btn btn-primary py-1" style="visibility: hidden" id="import_data_lembur_button">IMPORT</button>
                                </div>
                                <div class="col-2"></div>
                                <div class="col-2 pt-1" id="data_sudah_ada" style="visibility: hidden">
                                    <i class="fa fa-square" aria-hidden="true" style="color:red;"></i>&nbsp;Data Sudah ada
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-primary">
                        </div>
                    </div>
                </div>
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
                    <div class="row py-1 px-2">
                        <div class="col">
                            <table>
                                <tr>
                                    <td width="160">
                                        Nomor Form Lembur
                                    </td>
                                    <td id="last_nomor_form_lembur" style="padding-right: 4px">
                                    </td>
                                    <td><a href="#" onclick="getLastNomorFormLembur()" id="btn-icon-refresh"><i class="fa fa-refresh" aria-hidden="true"></i></a></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="table-responsive">
                                <table id="table-ajax-edit-lembur" class="table table-sm table-striped table-bordered table-vcenter text-nowrap table-nowrap w-100 m-0 p-0">
                                    <thead class="border text-center">
                                        <tr>
                                            <th class="text-nowrap w-5" scope="col">
                                                <label class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="selectAllData" name="selectAllData" value="selectAllData" checked>
                                                    <span class="custom-control-label"></span>
                                                </label>
                                            </th>
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
                    </div>
                </div>
                <div class="card-footer bg-primary br-br-7 br-bl-7">
                    <div class="text-white"></div>
                </div>

                <div class="card-footer bg-primary m-0 p-0">
                    <a href="javascript:void(0)" id="btn-simpan" class="btn btn-app btn-secondary ml-1 mt-1 mb-2 p-1" title="Simpan Data Yang Di Pilih"><i class="fa fa-save"></i> Simpan</a>
                    <a href="javascript:void(0)" id="btn-remove" class="btn btn-app btn-danger ml-1 mt-1 mb-2 p-1" title="Hapus Data Yang Di Pilih"><i class="fa fa-trash"></i> Hapus</a>
                    <!-- <a href="{{route('hris.datajadwalkerjalog.index')}}" id="btn-reset" class="btn btn-app btn-gray ml-1 mt-1 mb-2 p-1" title="Refresh Halaman"><i class="fa fa-refresh"></i> Refresh</a> -->
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

    <!-- INTERNAL Data tables -->data-lembur
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

    <style>
        #head_karyawan, #tabel_data_lembur, #head_overtime_from_nds, #tabel_overtime_from_nds { display: block; }

        #tabel_data_lembur {
            height: 1px;
            overflow-y: auto;    /* Trigger vertical scroll    */
            overflow-x: hidden;
            font-size: 9pt; /* Hide the horizontal scroll */
        }
        #tabel_overtime_from_nds {
            height: 1px;
            overflow-y: auto;    /* Trigger vertical scroll    */
            overflow-x: hidden;
            font-size: 10pt; /* Hide the horizontal scroll */
        }
    </style>
    <script type="text/javascript">

        $('body').on('change', '#tanggal_lembur_nds', function () {
            var tanggal_lembur = $('#tanggal_lembur_nds').val();
            $("#selectNoForm").empty();
            $("#selectNoForm").val(null).trigger("change");
            document.getElementById('tabel_overtime_from_nds').style.height='1px';
            document.getElementById('import_data_lembur_button').style.visibility='hidden';
            document.getElementById('data_sudah_ada').style.visibility='hidden';
            document.getElementById('data_ada_dan_tidak').style.visibility='hidden';
            getnomorform();
        });
        function getnomorform()
        {
            var tanggal_lembur = $('#tanggal_lembur_nds').val();
            $.ajax({
                type:"POST",
                url: "{{route('hris.datalembur.getnomorform')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    tanggal_lembur:tanggal_lembur,
                },
                success: function(response){
                    $("#selectNoForm").append("<option value=''>Daftar karyawan</option>");
                    for(i=0;i<response.length;i++) {
                        $("#selectNoForm").append("<option value="+response[i].no_form+">"+response[i].dept+" => "+response[i].jumlah+" karyawan</option>");
                    }
                },
                error: function (xhr, status, error) {
                    swal("", "Pilih nomor form gagal!", "error");
                }
            });
        }
        function get_new_nomor_form_lembur(){
            $.ajax({
                type:"GET",
                url: "{{route('hris.datalembur.get_last_nomor_form_lembur')}}",
                success: function(res){
                    $('#new_nomor_form_lembur').val(res);
                    document.getElementById('btn-icon-refresh_new_form_lembur').style.visibility='visible';
                }
            });
        }
        $('#selectNoForm').on('change',function(){
            var tanggal_lembur = $('#tanggal_lembur_nds').val();
            $('#tabel_overtime_from_nds').empty();
            let no_form=$('#selectNoForm').val();
            document.getElementById('tabel_overtime_from_nds').style.height='1px';
            document.getElementById('import_data_lembur_button').style.visibility='hidden';
            document.getElementById('data_sudah_ada').style.visibility='hidden';
            document.getElementById('data_ada_dan_tidak').style.visibility='hidden';
            document.getElementById('btn-icon-refresh_new_form_lembur').style.visibility='hidden';
            $('#new_nomor_form_lembur').val('');
            $.ajax({
                type:"POST",
                url: "{{route('hris.datalembur.getkaryawanlembur')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    no_form:no_form,
                    tanggal_lembur:tanggal_lembur
                },
                success: function(data){
                    let total_data=data.length;
                    let total_data_imported=0;
                    jQuery.each(data, function(key,value){
                        let status_absen=data[key].status_absen;
                        if(data[key].status_absen==null){
                            status_absen='';
                        }
                        let time1 = data[key].jam_lembur_awal_rencana;
                        let time2 = data[key].jam_lembur_akhir_rencana;
                        let date1 = new Date(`2000-01-01T${time1}Z`);
                        let date2 = new Date(`2000-01-01T${time2}Z`);
                        if (date2 < date1) {
                            date2.setDate(date2.getDate() + 1);
                        }
                        let jam_lembur = ((date2 - date1)/3600000)-(data[key].jam_lembur_istirahat/60);
                        let jam_lembur_istirahat=data[key].jam_lembur_istirahat/60;
                        let absen_masuk_kerja=data[key].absen_masuk_kerja;
                        if(data[key].absen_masuk_kerja==null){
                            absen_masuk_kerja='';
                        }
                        let absen_pulang_kerja=data[key].absen_pulang_kerja;
                        if(data[key].absen_pulang_kerja==null){
                            absen_pulang_kerja='';
                        }
                        let textcolor='';
                        if(data[key].nomor_form_lembur!=null){
                            textcolor='red';
                        }else{
                            total_data_imported+=1;
                            textcolor='black';
                        }
                        $('#tabel_overtime_from_nds').append("<tr style='color:"+textcolor+"' id='row_overtime_employee_"+key+"'>\
                            <td width='45px'>"+(key+1)+"</td>\
                            <td width='60px'><input type='hidden' name='enroll_id_from_nds[]' class='form-control form-control-sm' value='"+data[key].enroll_id+"''>"+data[key].enroll_id+"</td>\
                            <td width='100px'>"+data[key].nik+"</td>\
                            <td width='200px'>"+data[key].employee_name+"</td>\
                            <td width='60px'>"+absen_masuk_kerja+"</td>\
                            <td width='60px'>"+absen_pulang_kerja+"</td>\
                            <td width='60px'>"+status_absen+"</td>\
                            <td width='115px'><input name='jam_lembur_awal_rencana[]' type='time' id='jam_lembur_awal_rencana_"+key+"' class='form-control form-control-sm px-2' value='"+(data[key].jam_lembur_awal_rencana)+"'' onChange='calculateTotalLembur("+key+")'></td>\
                            <td width='115px'><input name='jam_lembur_akhir_rencana[]' type='time' id='jam_lembur_akhir_rencana_"+key+"' class='form-control form-control-sm px-2' value='"+(data[key].jam_lembur_akhir_rencana)+"' onChange='calculateTotalLembur("+key+")'></td>\
                            <td width='80px'><input name='jam_lembur_istirahat[]' type='number' id='jam_lembur_istirahat_"+key+"' class='form-control form-control-sm px-1' value='"+(data[key].jam_lembur_istirahat)+"' onChange='calculateTotalLembur("+key+")'></td>\
                            <td width='80px'><input name='total_lembur[]' type='number' id='total_lembur_"+key+"' class='form-control form-control-sm px-1' value='"+(jam_lembur)+"' onChange='calculateAkhirLembur("+key+")'></td>\
                            <td width='125px' style='font-size:8pt;padding-left:5px;padding-right:5px; word-break:break-all;'><input type='hidden' name='keterangan_lembur[]' id='keterangan_lembur_"+key+"' value='"+data[key].ket+"'>"+data[key].ket+"</td>\
                            <td width='40px'><a href='#' onClick='deletePengajuan("+key+")' style='color:red;font-size:12pt'><span class='fa fa-trash'></span></a></td>\
                        </tr>");
                        document.getElementById('tabel_overtime_from_nds').style.height='300px';
                        document.getElementById('import_data_lembur_button').style.visibility='visible';
                        document.getElementById('data_sudah_ada').style.visibility='visible';
                        document.getElementById('data_ada_dan_tidak').style.visibility='visible';
                        $('#total_new_data_lembur').val(total_data);
                        $('#total_new_data_lembur_import').val(total_data_imported);
                        get_new_nomor_form_lembur();
                    });
                },
                error: function (xhr, status, error) {
                    swal("", "Pilih nomor form gagal!", "error");
                }
            });
        });
        function deletePengajuan(key){
            var enroll_id = $("input[name='enroll_id_from_nds[]']").map(function(){return $(this).val();}).get();
            enroll_id.splice(key, 1);
            var jawa_rencana = $("input[name='jam_lembur_awal_rencana[]']").map(function(){return $(this).val();}).get();
            jawa_rencana.splice(key, 1);
            var jakir_rencana = $("input[name='jam_lembur_akhir_rencana[]']").map(function(){return $(this).val();}).get();
            jakir_rencana.splice(key, 1);
            var istirahat = $("input[name='jam_lembur_istirahat[]']").map(function(){return $(this).val();}).get();
            istirahat.splice(key, 1);
            var jakir_rencana = $("input[name='keterangan_lembur[]']").map(function(){return $(this).val();}).get();
            jakir_rencana.splice(key, 1);
            var row = document.getElementById('row_overtime_employee_'+key);
            row.parentNode.removeChild(row);
            var total_data=$('#total_new_data_lembur').val()-1;
            $('#total_new_data_lembur').val(total_data);
            var total_data_imported=$('#total_new_data_lembur_import').val()-1;
            $('#total_new_data_lembur_import').val(total_data_imported);
        }
        function minsToStr(t) {
            return Math.trunc(t / 60).toLocaleString('id-ID', {
                minimumIntegerDigits: 2,
                useGrouping: false
            }) + ':' + ('00' + t % 60).slice(-2);
        }
        function strToMins(t) {
            var s = t.split(":");
            return Number(s[0]) * 60 + Number(s[1]);
        }
        function calculateTotalLembur(e){
            let time1 = $("#jam_lembur_awal_rencana_" + e).val();
            let time2 = $("#jam_lembur_akhir_rencana_" + e).val();
            let istirahat = $("#jam_lembur_istirahat_" + e).val();

            var result = minsToStr(strToMins(time2) - strToMins(time1) - istirahat);
            let time3 = time1+':00';
            let time4 = time2+':00';
            let date1 = new Date(`2000-01-01T${time3}Z`);
            let date2 = new Date(`2000-01-01T${time4}Z`);
            if (date2 < date1) {
                date2.setDate(date2.getDate() + 1);
            }
            let jam_lembur = ((date2 - date1)/3600000)-(istirahat/60);
            $('#total_lembur_'+e).val(jam_lembur);
        }
        function calculateAkhirLembur(e){
            let time1 = $("#jam_lembur_awal_rencana_" + e).val();
            let time2 = $("#jam_lembur_akhir_rencana_" + e).val();
            let istirahat = $("#jam_lembur_istirahat_" + e).val();

            let time3 = time1+':00';
            let time4 = time2+':00';
            let date1 = new Date(`2000-01-01T${time3}Z`);
            let date2 = new Date(`2000-01-01T${time4}Z`);
            if (date2 < date1) {
                date2.setDate(date2.getDate() + 1);
            }
            let jam_lembur = $('#total_lembur_'+e).val();
            var decimalTime = jam_lembur * 60 * 60;
            var hours = Math.floor((decimalTime / (60 * 60)));
            decimalTime = decimalTime - (hours * 60 * 60);
            var minutes = Math.floor((decimalTime / 60));
            decimalTime = decimalTime - (minutes * 60);
            var seconds = Math.round(decimalTime);
            if(hours < 10)
            {
                hours = "0" + hours;
            }
            if(minutes < 10)
            {
                minutes = "0" + minutes;
            }
            if(seconds < 10)
            {
                seconds = "0" + seconds;
            }
            var waktu_tambahan=("" + hours + ":" + minutes);
            var result = minsToStr(strToMins(waktu_tambahan) + strToMins(time1) + strToMins(minsToStr(istirahat)));
            document.getElementById('jam_lembur_akhir_rencana_' + e).value = result;
        }
        $('#import_data_lembur_button').on('click',function(){
            $("#import_data_lembur_button").addClass("btn-loading");
            $("#import_data_lembur_button").html('Loading...');
            $("#import_data_lembur_button").attr("disabled", true);
            var tanggal_lembur = $('#tanggal_lembur_nds').val();
            var enroll_id = $("input[name='enroll_id_from_nds[]']").map(function(){return $(this).val();}).get();
            var jam_lembur_awal_rencana = $("input[name='jam_lembur_awal_rencana[]']").map(function(){return $(this).val();}).get();
            var jam_lembur_akhir_rencana = $("input[name='jam_lembur_akhir_rencana[]']").map(function(){return $(this).val();}).get();
            var jam_lembur_istirahat = $("input[name='jam_lembur_istirahat[]']").map(function(){return $(this).val();}).get();
            var jam_lembur = $("input[name='total_lembur[]']").map(function(){return $(this).val();}).get();
            var keterangan = $("input[name='keterangan_lembur[]']").map(function(){return $(this).val();}).get();
            // let no_form=$('#selectNoForm').val();
            $.ajax({
                type:"POST",
                url: "{{route('hris.datalembur.importkaryawanlembur')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    tanggal_lembur:tanggal_lembur,
                    enroll_id:enroll_id,
                    jam_lembur_awal_rencana:jam_lembur_awal_rencana,
                    jam_lembur_akhir_rencana:jam_lembur_akhir_rencana,
                    jam_lembur_istirahat:jam_lembur_istirahat,
                    jam_lembur:jam_lembur,
                    keterangan:keterangan,
                },
                success: function(data){
                    console.log(data);
                    swal("", "Data lembur berhasil di import", "success");
                    $('#tabel_overtime_from_nds').empty();
                    document.getElementById('tabel_overtime_from_nds').style.height='1px';
                    document.getElementById('import_data_lembur_button').style.visibility='hidden';
                    document.getElementById('data_sudah_ada').style.visibility='hidden';
                    $("#import_data_lembur_button").removeClass("btn-loading");
                    $("#import_data_lembur_button").html('IMPORT');
                    $("#import_data_lembur_button").attr("disabled", false);
                    $('#import_data_lembur_from_nds').modal('hide');
                    document.getElementById('tanggal_lembur_nds').value='';
                    document.getElementById('selectNoForm').value='';
                    document.getElementById('btn-icon-refresh_new_form_lembur').style.visibility='hidden';
                    $('#new_nomor_form_lembur').val('');
                    document.getElementById('data_ada_dan_tidak').style.visibility='hidden';
                },
                error: function (xhr, status, error) {
                    swal("", "Data lembur gagal di import", "error");
                    $("#import_data_lembur_button").removeClass("btn-loading");
                    $("#import_data_lembur_button").html('IMPORT');
                    $("#import_data_lembur_button").attr("disabled", false);
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

        // function jamlemburistirahat_edit(mulaijamlembur, akhirjamlembur, mulai_jam_lembur, akhir_jam_lembur, jumlah_jam_lembur, jumlah_jam_istirahat) {
        //     var dt1 = new Date(mulaijamlembur);
        //     var dt2 = new Date(akhirjamlembur);
        //     //alert(diff_hours(dt1, dt2));
        //     var jmljamlembur = diff_hours(dt1, dt2);
        //     var jamistirahatlembur = 0;

        //     if(jmljamlembur >= 1.5) {
        //         jamistirahatlembur = 0.5;
        //         jmljamlembur = jmljamlembur-jamistirahatlembur;
        //     }

        //     $('#' + mulai_jam_lembur).val(mulaijamlembur);
        //     $('#' + akhir_jam_lembur).val(akhirjamlembur);
        //     $('#' + jumlah_jam_lembur).val(jmljamlembur);
        //     $('#' + jumlah_jam_istirahat).val(jamistirahatlembur);

        //     //alert(splitWaktuLembur[0]);
        // }

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
            document.getElementById('btn-icon-refresh2').style.visibility='hidden';
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

    <script>
    // $("#smartwizard123").on("leaveStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
        function jamlemburistirahat_edit(mulaijamlembur, akhirjamlembur, mulai_jam_lembur, akhir_jam_lembur, jumlah_jam_lembur, jumlah_jam_istirahat) {

            var dt1Split = mulaijamlembur.split(" ");
            var dt2Split = akhirjamlembur.split(" ");

            var dt1 = new Date(defaultDate(dt1Split[0]) + " " + dt1Split[1]);
            var dt2 = new Date(defaultDate(dt2Split[0]) + " " + dt2Split[1]);

            var jmljamlembur = diff_hours(dt1, dt2);
            var jamistirahatlembur = 0;
            var jmljamke1=0
            if ( (dt1.getHours() <= 18 && ( (dt2.getHours() == 18 && dt2.getMinutes() >= 30) || (dt2.getHours() > 18) )) ||(dt1Split[0] != dt2Split[0]) ) {
                jamistirahatlembur += 0.5;
                var dt_istirahat = new Date(defaultDate(dt1Split[0]) + " " + "18:30");
                var jmljamke1 = diff_hours(dt1, dt_istirahat);
            }
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

            $('#' + mulai_jam_lembur).val(dt1);
            $('#' + akhir_jam_lembur).val(dt2);
            $('#' + jumlah_jam_lembur).val(jmljamlembur);
            $('#' + jumlah_jam_istirahat).val(jamistirahatlembur);

        }
        function fill_the_table(){
            $('#loading_karyawan_lembur').addClass("spinner-border");
            $('#tabel_data_lembur').empty();
            var formData = new FormData();
            var excelFile=document.getElementById("excel_filess");
            var myFile=excelFile.files[0];
            formData.append("excel_file",myFile);
            if(typeof myFile=='undefined'){
                notif({
                    msg: "<b>Error:</b> Pilih File terlebih dahulu!",
                    type: "error"
                });
                document.getElementById('tabel_data_lembur').style.height='1px';
                document.getElementById('overtimeImportButton').style.visibility='hidden';
                document.getElementById('keterangan').style.visibility='hidden';
                document.getElementById('btn-icon-refresh2').style.visibility='hidden';
                $('#nomor_form_lembur').text('');
                $('#length').text('');
                document.getElementById('warning_error').style.visibility='hidden';
                $('#loading_karyawan_lembur').removeClass("spinner-border");
            }else{
                $.ajax({
                    type: 'POST',
                    url: '{{route('hris.datalembur.import_data_lembur')}}',
                    contentType: false,
                    processData: false,
                    data: formData,
                    success:function(data){
                        console.log(data);
                        var myarray = [];
                        $('#nomor_form_lembur').text('NOMOR FORM LEMBUR : '+data[1].nomor_form_lembur);
                        $('#length').text('LENGTH : '+data[1].jumlah_data);
                        jQuery.each(data, function(key,value){
                            $('#tabel_data_lembur').append("<tr style='color:"+data[key].absen_lembur+"'>\
                                <td width='60px'>"+data[key].enroll_id+"</td>\
                                <td width='100px'>"+data[key].nik+"</td>\
                                <td width='100px'>"+data[key].tanggal+"</td>\
                                <td width='150px'>"+data[key].employee_name+"</td>\
                                <td width='90px'>"+data[key].dari+"</td>\
                                <td width='90px'>"+data[key].sampai+"</td>\
                                <td width='90px'>"+data[key].act_in+"</td>\
                                <td width='90px'>"+data[key].act_out+"</td>\
                                <td width='50px'>"+data[key].jumlah_lembur+"</td>\
                                <td width='50px'>"+data[key].jumlah_jam_istirahat+"</td>\
                                <td width='150px'>"+data[key].keterangan_lembur+"</td>\
                                <td width='150px'>"+data[key].status_absen+"</td>\
                            </tr>");
                            myarray.push(data[key].absen_lembur);
                        });
                        if(!myarray.includes('red')==true){
                            document.getElementById('overtimeImportButton').style.visibility='visible';
                            document.getElementById('keterangan').style.visibility='visible';
                            document.getElementById('keterangan').style.border='1px solid #E7E7E7';
                            document.getElementById('tabel_data_lembur').style.height='300px';
                            document.getElementById('btn-icon-refresh2').style.visibility='visible';
                            document.getElementById('warning_error').style.visibility='hidden';
                            $('#warning_error').text('');
                        }else{
                            document.getElementById('overtimeImportButton').style.visibility='visible';
                            document.getElementById('warning_error').style.visibility='visible';
                            $('#warning_error').text('Beberapa data tak akan tersimpan!');
                            document.getElementById('keterangan').style.visibility='visible';
                            document.getElementById('keterangan').style.border='1px solid #E7E7E7';
                            document.getElementById('tabel_data_lembur').style.height='300px';
                            document.getElementById('btn-icon-refresh2').style.visibility='visible';
                        }
                        $('#loading_karyawan_lembur').removeClass("spinner-border");
                    },
                    error: function(res){
                        swal("", "IMPORT KARYAWAN LEMBUR GAGAL!", "error")
                        document.getElementById('tabel_data_lembur').style.height='1px';
                        document.getElementById('overtimeImportButton').style.visibility='hidden';
                        document.getElementById('keterangan').style.visibility='hidden';
                        document.getElementById('btn-icon-refresh2').style.visibility='hidden';
                        $('#nomor_form_lembur').text('');
                        $('#length').text('');
                        document.getElementById('warning_error').style.visibility='hidden';
                        $('#loading_karyawan_lembur').removeClass("spinner-border");
                    }
                });
            }
        }
        function uploadingTheFile(){
            var formData = new FormData();
            var excelFile=document.getElementById("excel_filess");
            var myFile=excelFile.files[0];
            formData.append("excel_file",myFile);
            $.ajax({
                type: 'POST',
                url: '{{route('hris.datalembur.importing_data_lembur')}}',
                contentType: false,
                processData: false,
                data: formData,
                success:function(data){
                    $('#tabel_data_lembur').empty();
                    document.getElementById('overtimeImportButton').style.visibility='hidden';
                    document.getElementById('btn-icon-refresh2').style.visibility='hidden';
                    document.getElementById('tabel_data_lembur').style.height='1px';
                    $('#excel_filess').val('');
                    $('#nomor_form_lembur').text('');
                    $('#length').text('');
                    $("#import_data_lembur").modal('hide');
                    console.log(data);
                    swal({
                        title: "Data lembur",
                        text: "Data lembur berhasil di import",
                        icon: "success",
                        button : false,
                    });
                }
            });
        }
        $('#excel_filess').change(function() {
            fill_the_table();
        });
        $('#overtimeImportButton').click(function() {
            uploadingTheFile();
        });
        $('body').on('click', '#btn-paste', function (event) {

            $("#waktu_jam_lembur").removeClass("is-invalid state-invalid");
            $("#jumlahEmp").removeClass("is-invalid state-invalid");
            $("#jumlah_jam_lembur").removeClass("is-invalid state-invalid");


            let waktu_lembur=$("#waktu_jam_lembur").val()
            let karyawan=$("#selectEmployee").val()
            let jumlah_jam_lembur=$("#jumlah_jam_lembur").val()
            let jumlah_jam_istirahat=$("#jumlah_jam_istirahat").val()

            let catatan_hrd=$("#catatan").val()
            $.ajax({
                type:"GET",
                url: "{{route('hris.datalembur.get_last_nomor_form_lembur')}}",
                success: function(res){
                    $('#last_nomor_form_lembur').text(': '+res);
                }
            });

            if(!waktu_lembur) {
                notif({
                    msg: "<b>Warning:</b> Silakan diisi tanggal lemburnya.",
                    type: "warning"
                });

                $("#waktu_jam_lembur").addClass("is-invalid state-invalid");

                return false;
            }

            if(karyawan == "") {
                notif({
                    msg: "<b>Warning:</b> Silakan pilih karyawannya.",
                    type: "warning"
                });
                $("#jumlahEmp").addClass("is-invalid state-invalid");

                return false;
            }

            if(jumlah_jam_lembur >= 24) {
                notif({
                    msg: "<b>Warning:</b> Jumlah jam lembur lebih dari 24 jam !!!",
                    type: "warning"
                });
                $("#jumlah_jam_lembur").addClass("is-invalid state-invalid");

                return false;
            }

            $("#data-lembur").show();
                $.ajax({
                    type:"POST",
                    url: "{{route('hris.datalembur.getEmployeeLembur')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        tanggal_lembur:waktu_lembur,
                        selectEmployee:karyawan,
                    },
                    dataType: 'json',
                    success: function(res){
                        if(res){
                            for(i=0;i<res.length;i++) {
                                isCheckedData = "checked";
                                nomor_urut = i+1;
                                $htmlTable = '' +
                                '<tr class="text-center">' +
                                '    <td>' +
                                '       <label class="custom-control custom-checkbox">' +
                                '          <input type="checkbox" class="custom-control-input" id="checkjadwal_' + i + '" name="checkjadwal" value="' + i + '" ' + isCheckedData + '>' +
                                '          <span class="custom-control-label"></span>' +
                                '       </label>' +
                                '    </td>' +
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
                                '    <td><div id="absen_masuk_kerja' + i + '" name="absen_masuk_kerja">' + (res[i].absen_masuk_kerja ? res[i].absen_masuk_kerja : '-') + '</div></td>' +
                                '    <td><div id="absen_pulang_kerja' + i + '" name="absen_pulang_kerja">' + (res[i].absen_pulang_kerja ? res[i].absen_pulang_kerja : '-') + '</div></td>' +
                                '    <td class="w-70">' +
                                '    <div class="input-group">' +
                                '        <div class="input-group-prepend">' +
                                '            <div class="input-group-text">' +
                                '                <i class="fa fa-calendar tx-16 lh-0 op-6"></i>' +
                                '            </div>' +
                                '        </div>' +
                                '        <input class="form-control" id="waktu_jam_lembur_edit' + i + '" name="waktu_jam_lembur_edit" value="' + waktu_lembur + '" onFocus="datetimerangepicker(\'waktu_jam_lembur_edit' + i + '\');"  onChange="swl_edit(\'waktu_jam_lembur_edit' + i + '\',\'mulai_jam_lembur_edit' + i + '\', \'akhir_jam_lembur_edit' + i + '\', \'jumlah_jam_lembur_edit' + i + '\', \'jumlah_jam_istirahat_edit' + i + '\');" type="text">' +
                                // '        <input id="mulai_jam_lembur_edit' + i + '" name="mulai_jam_lembur_edit" value="' + mulai_jam_lembur + '" type="hidden">' +
                                // '        <input id="akhir_jam_lembur_edit' + i + '" name="akhir_jam_lembur_edit" value="' + akhir_jam_lembur + '" type="hidden">' +
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
                    },
                    error: function(res){
                        // console.log('err',res);
                    }
                });
        });
        function getLastNomorFormLembur(){
            $("#btn-icon-refresh").addClass("btn-loading");
            $("#btn-icon-refresh").html('Loading...');
            $("#btn-icon-refresh").attr("disabled", true);
            $("#btn-icon-refresh2").addClass("btn-loading");
            $("#btn-icon-refresh2").html('Loading...');
            $("#btn-icon-refresh2").attr("disabled", true);
            $.ajax({
                type:"GET",
                url: "{{route('hris.datalembur.get_last_nomor_form_lembur')}}",
                success: function(res){
                    $('#nomor_form_lembur').text('NOMOR FORM LEMBUR : '+res);
                    $('#last_nomor_form_lembur').text(': '+res);
                    $("#btn-icon-refresh").removeClass("btn-loading");
                    $("#btn-icon-refresh").html('<i class="fa fa-refresh" aria-hidden="true"></i>');
                    $("#btn-icon-refresh").attr("disabled", false);
                    $("#btn-icon-refresh2").removeClass("btn-loading");
                    $("#btn-icon-refresh2").html('<i class="fa fa-refresh" aria-hidden="true"></i>');
                    $("#btn-icon-refresh2").attr("disabled", false);
                }
            });
        }

        $('#selectAllData').click(function() {
            var isChecked = $(this).prop("checked");
            $('#table-ajax-edit-lembur tr:has(td)').find('input[type="checkbox"]').prop('checked', isChecked);
        });


        $('#table-ajax-edit-lembur').on('click', 'tbody td, thead th:first-child', function(e){

            var isChecked = $(this).prop("checked");
            var isHeaderChecked = $("#selectAllData").prop("checked");
            if (isChecked == false && isHeaderChecked)
                $("#selectAllData").prop('checked', isChecked);
            else {
                $('#table-ajax-edit-lembur tbody td, thead th:first-child').find('input[type="checkbox"]').each(function() {
                    if ($(this).prop("checked") == false)
                    isChecked = false;
                });
                //console.log(isChecked);
                $("#selectAllData").prop('checked', isChecked);
            }
        });



        $('#btn-remove').click(function() {
            var notFirstRow = true;
            var isHeaderChecked = $("#selectAllData").prop("checked");
            $("#table-ajax-edit-lembur input[type=checkbox]:checked").each(function () {
                if (notFirstRow && isHeaderChecked)
                {
                    notFirstRow = false;
                } else {
                    $(this).parents("tr").remove();
                }
            });

        });

        $('#btn-simpan').click(function() {

            var html_table_data = "";
            var arrayHtml = [];
            var firstRow = true;

            $("#btn-simpan").addClass("btn-loading");
            $("#btn-simpan").html('Loading...');
            $("#btn-simpan").attr("disabled", true);

            //Loop through all checked CheckBoxes in GridView.
            $("#table-ajax-edit-lembur input[type=checkbox]:checked").each(function () {
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
                }
            });

            console.log("arrayHtml",arrayHtml);

            $.ajax({
                type:"POST",
                url: "{{route('hris.datalembur.create')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    arrayHtml:arrayHtml,
                },
                dataType: 'json',
               success: function(res){
                    $('#btn-simpan').removeClass("btn-loading");
                    $("#btn-simpan").html('<i class="fa fa-save"></i> Simpan');
                    $("#btn-simpan").attr("disabled", false);
                    var message ="";
                    if (res !== null && res.length > 0) {
                        for (var i = 0; i < res.length; i++) {
                            message += "" + res[i].employee_name + " ";
                            message += "(" + res[i].nik + "), ";
                        }
                        message +="";

                        swal({
                            title: 'DATA SUDAH ADA:',
                            text: message,
                            icon: 'info',
                        });
                    }else{
                        notif({
                            msg: "<b>Success:</b> Data berhasil di Inject di Data Kehadiran.",
                            type: "success"
                        });
                        setTimeout(function myFunction() {
                            $(location).prop("href", "{{route('hris.datalembur.index')}}")
                        }, 3000);
                    }
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
    </script>
@endsection
