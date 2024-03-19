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
                <a href="{{ url('lockscreen') }}" class="btn btn-primary text-white mr-0 btn-icon" data-toggle="tooltip"
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
                                <option value="{{$r_periode_lembur->periode_tanggal}}">
                                @php
                                    setlocale(LC_ALL, 'id-ID', 'id_ID');
                                    $datePeriode = explode(" s/d ", $r_periode_lembur->periode_tanggal);
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
                <div class="col-md-12" id="inputSearch2">
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
                <button type="button" id="btn-update_edit2" class="btn btn-secondary btn-app" data-dismiss="modal">Tambah Karyawan</button>
                <button type="button" id="btn-hapus-nospl" class="btn btn-danger btn-app" data-dismiss="modal">Hapus NO SPL</button>
            </div>
        </div>
    </div>
    {{-- </form> --}}
    {!! Form::close() !!}
    <!-- END FORM-->

    <!-- row -->
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div id="data-lembur" class="card shadow">
                <div class="card-header bg-primary p-3">
                    <div id="title-table1" class="card-title">DATA LEMBUR KARYAWAN</div>
                    <div class="card-options">
                        <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="card-body m-0 p-0">
                    <div class="table-responsive">
                        <table id="datatable-ajax-crud" class="table table-sm table-striped table-bordered table-vcenter text-nowrap table-nowrap w-100 m-0 p-0">
                            <thead class="border text-center">
                                <tr>
                                    <th class="bg-primary w-5 align-middle" scope="col">ACTION</th>
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
                        $('#jumlah_jam_istirahat_tambah').val(res[0].jumlah_jam_istirahat);
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


            $("#data-absensi-karyawan").show('slow');

            $('#datatable-ajax-crud').DataTable().clear();
            $('#datatable-ajax-crud').DataTable().destroy();
            $('#datatable-ajax-crud').empty();

            //myHeadDataTable();

            //$("#selectSubDep").append('<option value="">--- Pilih Sub Dept ---</option>');

            var department_id = $('#selectDepartment').val();
            var selectNomorFormLembur = $('#selectNomorFormLembur').val();
            var daterange1 = $('#daterange1').val();
            var selectEmployeeID = $('#selectEmployeeID').val();

            var table = $('#datatable-ajax-crud').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                destroy: true,
                "ajax": {
                    "url": "{{ route('hris.datalembur.ajax_datahadir') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                    "data": {
                        department_id:department_id,
                        selectNomorFormLembur:selectNomorFormLembur,
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
                        title: 'Nomor Form Lembur',
                        data: 'nomor_form_lembur',
                        name: 'nomor_form_lembur'
                    },
                    {
                        title: 'Tanggal Form',
                        data: 'tanggal_berjalan',
                        name: 'tanggal_berjalan'
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
                        title: 'Absen IN (Lembur)',
                        data: 'mulai_jam_lembur',
                        name: 'mulai_jam_lembur'
                    },
                    {
                        title: 'Absen OUT (Lembur)',
                        data: 'akhir_jam_lembur',
                        name: 'akhir_jam_lembur'
                    },
                    {
                        title: 'Jumlah Jam Istirahat',
                        data: 'jumlah_jam_istirahat_lembur',
                        name: 'jumlah_jam_istirahat_lembur'
                    },
                    {
                        title: 'Jumlah Jam Lembur',
                        data: 'jumlah_jam_lembur',
                        name: 'jumlah_jam_lembur'
                    },
                ],
                columnDefs: [
                    {
                        'visible': false,
                        'targets': [0,1,4]
                    }
                ],
                order: [
                    [2, 'desc'],[7, 'asc']
                ],
                "createdRow": function (row, data, dataIndex) {
                    if ((data['kode_hari'] == "5") || (data['kode_hari'] == "6")) {
                        $(row).css('background', 'yellow');
                    } else {
                        if (data['jumlah_jam_kerja'] > data['jam_kerja']) {
                            $(row).css('background', 'red');
                        }
                    }
                }
            });


            $('#datatable-ajax-crud tbody').on('click', 'tr', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

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
                //alert(data['uuid']);
                var editData = data['uuid'];

                $("#absen_masuk_kerja").removeClass("is-invalid state-invalid");
                $("#absen_pulang_kerja").removeClass("is-invalid state-invalid");
                $("#absen_alasan").removeClass("is-invalid state-invalid");

                $("#is_approved").prop('checked', false);
                $("#no_form").prop("disabled", true);
                $("#status_absen").prop("disabled", true);
                $("#absen_masuk_kerja").prop("disabled", true);
                $("#absen_pulang_kerja").prop("disabled", true);
                $("#absen_alasan").prop("disabled", true);

                $.ajax({
                    "type":"POST",
                    "url": "{{route('hris.datalembur.form_datalembur')}}",
                    "data": { editData: editData },
                    "dataType": 'json',
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    "success": function(res){
                        $('#ajaxGagalAbsenModel').html("<b>FORM LEMBUR ["+res.enroll_id+" - "+res.nik+" - "+res.employee_name+"]");
                        $('#ajax-lembur-model-add').modal('show');
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
                        $('#tanggal_lembur_label').text(res.tanggal_berjalan);
                        $('#tanggal_lembur').val(res.tanggal_berjalan);
                        $('#kode_hari').val(res.kode_hari);
                        $('#nama_hari').val(res.nama_hari);
                        $('#jadwal_jam_kerja').text(res.jadwal_jam_kerja);
                        $('#jadwal_masuk_kerja').val(res.mulai_jam_kerja);
                        $('#jadwal_pulang_kerja').val(res.akhir_jam_kerja);
                        $('#absen_masuk_kerja').val(res.absen_masuk_kerja);
                        $('#absen_pulang_kerja').val(res.absen_pulang_kerja);
                        $('#site_nirwana_id').val(res.site_nirwana_id);
                        $('#site_nirwana_name').text(res.site_nirwana_name);
                        $('#department_id').val(res.department_id);
                        $('#department_name').val(res.department_name);
                        $('#sub_dept_id').val(res.sub_dept_id);
                        $('#sub_dept_name').val(res.sub_dept_name);
                        $('#shift_work_id_label').text(res.shift_work_id);
                        $('#shift_work_id').val(res.shift_work_id);
                        $('#jumlah_jam_lembur').val(res.jumlah_jam_lembur);
                        $('#status_lembur').val(res.status_lembur);
                        $('#waktu_jam_lembur').val(res.waktu_jam_lembur);
                        $('#mulai_jam_lembur').val(res.mulai_jam_lembur);
                        $('#akhir_jam_lembur').val(res.akhir_jam_lembur);
                        $('#updated_at').val(res.updated_at);
                        $('#nomor_form_lembur').val(res.nomor_form_lembur);
                        $('#catatan_hrd').val(res.catatan_hrd);
                   }
                });

            });

                 table.draw();

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

                    $("#datatable-ajax-crud").DataTable().ajax.reload();
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

                    $("#datatable-ajax-crud").DataTable().ajax.reload();
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
            $("#subtitle-table1").empty();

            var periode_lembur = $('#periode_lembur').val();
            var selectNoSPL = $('#selectNoSPL').val();
            var searchData = $('#searchData').val();

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
                    },
                    dataType: 'json',
                    success: function(res){
                        if(res.length > 0){
                            for(i=0;i<res.length;i++) {
                                nomor_urut = i+1;
                                var tanggal_lembur = moment(res[i].tanggal_berjalan).format('DD MMM YYYY');
                                var status_kerja = res[i].status_kerja;
                                var tanggal_resign = res[i].tanggal_resign;
                                var mulai_jam_kerja = res[i].mulai_jam_kerja;
                                var akhir_jam_kerja = res[i].akhir_jam_kerja;
                                var absen_masuk_kerja = res[i].absen_masuk_kerja;
                                var absen_pulang_kerja = res[i].absen_pulang_kerja;
                                var mulai_jam_lembur = res[i].mulai_jam_lembur;
                                var akhir_jam_lembur = res[i].akhir_jam_lembur;
                                var sub_dept_name = res[i].sub_dept_name;
                                var jumlah_jam_lembur = res[i].jumlah_jam_lembur;
                                var jumlah_jam_istirahat_lembur = res[i].jumlah_jam_istirahat_lembur;
                                var catatan = res[i].catatan;
                                var mulai_jam_lembur_edit1 = res[i].mulai_jam_lembur;
                                var akhir_jam_lembur_edit1 = res[i].akhir_jam_lembur;
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

                                var hapusData = res[i].tanggal_berjalan + '|' + res[i].enroll_id + '|' + res[i].nomor_form_lembur + '|' + res[i].employee_name + '|' + 'btn-remove_' + nomor_urut;
                                var editData = res[i].tanggal_berjalan + '|' + nomor_urut;

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
                                '       <button id="btn-edit_' + nomor_urut + '" class="btn btn-secondary p-0 mr-1 text-white border-white btn-icon" ' +
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
                                '   <td class="text-nowrap text-center align-middle">' + res[i].nomor_form_lembur  + '</td>' +
                                '   <td class="text-nowrap text-center align-middle">' + tanggal_lembur  + '</td>' +
                                '   <td class="text-nowrap text-left align-middle">' + res[i].nama_hari  + '</td>' +
                                '   <td class="text-nowrap text-left align-middle">' + status_kerja  + '</td>' +
                                '   <td class="text-nowrap text-left align-middle">' + res[i].enroll_id  + '</td>' +
                                '   <td class="text-nowrap text-left align-middle">' + res[i].nik  + '</td>' +
                                '   <td class="text-left align-middle">' + res[i].employee_name  + '</td>' +
                                '   <td class="text-nowrap text-center align-middle">' + mulai_jam_kerja  + '</td>' +
                                '   <td class="text-nowrap text-center align-middle">' + akhir_jam_kerja  + '</td>' +
                                '   <td class="text-nowrap text-center align-middle">' + absen_masuk_kerja  + '</td>' +
                                '   <td class="text-nowrap text-center align-middle">' + absen_pulang_kerja  + '</td>' +
                                '   <td class="text-nowrap text-center align-middle">' + mulai_jam_lembur  + '</td>' +
                                '   <td class="text-nowrap text-center align-middle">' + akhir_jam_lembur  + '</td>' +
                                '   <td class="text-nowrap text-right align-middle">' + jumlah_jam_lembur  + '</td>' +
                                '   <td class="text-nowrap text-right align-middle">' + jumlah_jam_istirahat_lembur  + '</td>' +
                                '   <td class="text-nowrap text-center align-middle">' + res[i].status_aktif  + '</td>' +
                                '   <td class="text-nowrap text-center align-middle">' + res[i].status_staff  + '</td>' +
                                '   <td class="text-nowrap text-center align-middle">' + tanggal_resign  + '</td>' +
                                '   <td class="text-left align-middle">' + sub_dept_name  + '</td>' +
                                '</tr>';

                                $("#datatable-ajax-crud tbody").append(htmlTable);

                            }
                            $("#subtitle-table1").append("");
                            $("#subtitle-table1").append("Total data yang ditemukan ada: " + nomor_urut);
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

            }
        });

        $('#datatable-ajax-crud tbody').on( 'click', 'tr', function () {
            $("#datatable-ajax-crud tbody tr").each(function () {
                $(this).removeClass('bg-cyan');
            });

            $(this).addClass('bg-cyan');
        } );

        $('body').on('change', '#selectNoSPL', function () {
            $("#btn-cari").click();
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
                                        dataType: 'json',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                        data: {
                                            tanggal_berjalan:tanggal_berjalan,
                                            enroll_id:enroll_id,
                                            nomor_form_lembur:nomor_form_lembur,
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
                            var nomor_form_lembur = currentRow.find("td:eq(1)").html();
                            var enroll_id = currentRow.find("td:eq(5)").html();
                            var nik = currentRow.find("td:eq(6)").html();
                            var employee_name = currentRow.find("td:eq(7)").html();
                            var jumlah_jam_lembur = currentRow.find("td:eq(14)").html();
                            var jumlah_jam_istirahat_lembur = currentRow.find("td:eq(15)").html();
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

        function swl(id) {
            //alert(id);
            var waktu_jam_lembur = $('#' + id).val();
            var splitWaktuLembur = waktu_jam_lembur.split(" - ");
            var mulai_jam_lembur = splitWaktuLembur[0];
            var akhir_jam_lembur = splitWaktuLembur[1];

            jamlemburistirahat(mulai_jam_lembur, akhir_jam_lembur);

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

@endsection
