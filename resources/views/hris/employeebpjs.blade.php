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
            <li class="active"><span>BPJS TK/KS Karyawan</span></li>
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
    {!! Form::open(['route' => 'hris.employeebpjs.ajax_exportexcel', 'id' => 'formExport', 'name' => 'formExport','method'=>'post']) !!}

    @csrf
    <div class="card shadow">
        <div class="card-header bg-primary p-3">
            <div class="card-title">FILTER BPJS TK/KS KARYAWAN</div>
            <div class="card-options ">
                <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">STAFF / NON STAFF : </label>
                        <select id="status_staff" name="status_staff" class="form-control">
                            <option value="">-- PILIH STATUS STAFF --</option>
                            <option value="STAFF">STAFF</option>
                            <option value="NON STAFF">NON STAFF</option>
                            <option value="OPERATOR">OPERATOR</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4" id="injectData2">
                    <div class="form-group">
                        <label class="form-label">PERIODE KEHADIRAN : </label>
                        <select id="periode_payroll" name="periode_payroll" class="form-control">
                            @foreach ($periode_payroll as $r_periode_payroll)
                                <option  value="{{$r_periode_payroll->periode_payroll}}">
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
                <div class="col-md-4" id="injectData1">
                    <div class="form-group">
                        <label class="form-label">DASAR POT BPJS : </label>
                        <div class="input-group">
                            <select id="kode_periode_bpjs" name="kode_periode_bpjs" class="form-control">
                            @foreach ($periode_bpjs as $r_periode_bpjs)
                                <option value="{{$r_periode_bpjs->kode_periode_bpjs}}">
                                @php
                                    $datePeriode = "";
                                    $datePer = array();
                                    setlocale(LC_ALL, 'id-ID', 'id_ID');
                                    $datePeriode = $r_periode_bpjs->kode_periode_bpjs;
                                    $datePer = explode("-", $r_periode_bpjs->kode_periode_bpjs);
                                    echo $r_periode_bpjs->nama_periode_bpjs;
                                @endphp
                                </option>
                            @endforeach
                            </select>
                            <span class="input-group-append">
                                <button class="btn btn-primary btn-app" type="button" id="btn-update-bpjs"><i class="fa fa-upload"></i> Update</button>
                            </span>
                        </div>                    
                    </div>
                </div>                
                <div class="col-md-4" id="inputSearch">
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
            <div id="data-employeebpjs" class="card shadow">
                <div class="card-header bg-primary p-3">
                    <div id="title-table1" class="card-title">DATA BPJS KARYAWAN</div>
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
                                    <th class="bg-primary w-5 align-middle" scope="col" width="50px">GAJI<br>POKOK (RP)</th>
                                    <th class="bg-primary w-5 align-middle" scope="col" width="50px">BPJS TK <h6 id="bpjs_tk_persen_1"></h6></th>
                                    <th class="bg-primary w-5 align-middle" scope="col" width="50px">BPJS TK <h6 id="bpjs_tk_persen_2"></h6></th>
                                    <th class="bg-primary w-5 align-middle" scope="col" width="50px">BPJS TK <h6 id="bpjs_tk_persen_3"></h6></th>
                                    <th class="bg-primary w-5 align-middle" scope="col" width="50px">BPJS TK <h6 id="bpjs_tk_persen_4"></h6></th>
                                    <th class="bg-primary w-5 align-middle" scope="col" width="50px">BPJS KS <h6 id="bpjs_ks_persen"></h6></th>
                                    <th class="bg-primary w-5 align-middle" scope="col" width="50px">TOTAL IURAN</th>
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

<!-- boostrap show department model -->
<div class="modal fade" id="ajax-bpjs-karyawan-model" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <input type="hidden" id="kode_bpjs_modal">
        <div class="modal-content">
            <div class="modal-header bg-primary p-2">
                <h4 class="modal-title pl-2"><b>DETAIL DATA BPJS KARYAWAN</b></h4>
                <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                    <i class="fa fa-remove"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Nomor Absen : </label>
                            <input readonly type="text" class="form-control" id="enroll_id">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">NIK : </label>
                            <input readonly type="text" class="form-control" id="nik">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">GAJI POKOK (RP) : </label>
                            <input readonly type="text" class="form-control text-right" id="dasar_pot_bpjs">
                        </div>
                    </div>                            
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Nama Karyawan : </label>
                            <input readonly type="text" class="form-control" id="employee_name">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">TOTAL IURAN BPJS (RP): </label>
                            <input readonly type="text" class="form-control text-right" id="total_iuran">
                        </div>
                    </div>

                    <div class="panel panel-primary p-2">
                        <div class="tab_wrapper first_tab">
                            <ul class="tab_list">
                                <li class="text-sm">BPJS Karyawan</li>
                                <li class="text-sm">Department</li>
                                <li class="text-sm">BPJS TK</li>
                                <li class="text-sm">BPJS KS</li>
                            </ul>
                            <div class="content_wrapper">
                                <div class="tab_content active">
                                    <div class="alert alert-info alert-dismissible fade show m-0" role="alert">
                                        <span class="alert-inner--icon"><i class="fe fe-bell"></i></span>
                                        <span class="alert-inner--text"><strong>Info!</strong> Harap di isi dengan benar data di bawah ini, untuk proses perhitungan BPJS Karyawan!</span>
                                    </div>                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">Status BPJS TK : </label>
                                                <select id="status_aktif_bpjs_tk" class="form-control" data-placeholder="-- Pilih Aktif / Tidak Aktif --">
                                                    <option value="">-- Pilih Aktif / Tidak Aktif --</option>
                                                    <option value="AKTIF">AKTIF</option>
                                                    <option value="TIDAK AKTIF">TIDAK AKTIF</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">Tanggal Reg BPJS TK</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                        </div>
                                                    </div><input id="tanggal_bpjs_ketenagakerjaan" class="form-control fc-datepicker"  placeholder="DD-MM-YYYY" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">Nomor BPJS TK : </label>
                                                <input type="text" class="form-control" id="nomor_bpjs_ketenagakerjaan">
                                            </div>
                                        </div>
                    
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">Status BPJS KS : </label>
                                                    <select id="status_aktif_bpjs_ks" class="form-control" data-placeholder="-- Pilih Aktif / Tidak Aktif --">
                                                        <option value="">-- Pilih Aktif / Tidak Aktif --</option>
                                                        <option value="AKTIF">AKTIF</option>
                                                        <option value="TIDAK AKTIF">TIDAK AKTIF</option>
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">Tanggal Reg BPJS KS</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                        </div>
                                                    </div><input id="tanggal_bpjs_kesehatan" class="form-control fc-datepicker"  placeholder="DD-MM-YYYY" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">Nomor BPJS KS: </label>
                                                <input type="text" class="form-control" id="nomor_bpjs_kesehatan">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab_content">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">Divisi : </label>
                                                <input readonly type="text" class="form-control" id="site_nirwana_name">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">Department</label>
                                                <input readonly type="text" class="form-control" id="department_name">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">Bagian : </label>
                                                <input readonly type="text" class="form-control" id="sub_dept_name">
                                            </div>
                                        </div>                
                                    </div>                
                                </div>

                                <div class="tab_content">
                                    <div class="row">
                                        <div class="col-md-12 m-0 p-0">
                                            <div class="expanel expanel-success">
                                                <div class="expanel-heading">
                                                    <h4 class="expanel-title">BPJS KETENAGAKERJAAN (JAMINAN KECELAKAN KERJA)</h4>
                                                </div>
                                                <div class="expanel-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label font-weight-bold">BPJS TK (JKK <label id="bpjs_tk_jkk_persen" class="font-weight-bold"></label>%) : </label>
                                                                <input readonly type="text" class="form-control" id="bpjs_tk_jkk_rupiah">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label font-weight-bold">PERUSAHAAN (JKK <label id="bpjs_tk_jkk_perusahaan_persen" class="font-weight-bold"></label>%) : </label>
                                                                <input readonly type="text" class="form-control" id="bpjs_tk_jkk_perusahaan_rupiah">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label font-weight-bold">KARYAWAN (JKK <label id="bpjs_tk_jkk_karyawan_persen" class="font-weight-bold"></label>%) : </label>
                                                                <input readonly type="text" class="form-control" id="bpjs_tk_jkk_karyawan_rupiah">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <div class="expanel expanel-success">
                                                <div class="expanel-heading">
                                                    <h4 class="expanel-title">BPJS KETENAGAKERJAAN (JAMINAN HARI TUA)</h4>
                                                </div>
                                                <div class="expanel-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label font-weight-bold">BPJS TK (JHT <label id="bpjs_tk_jht_persen" class="font-weight-bold"></label>%) : </label>
                                                                <input readonly type="text" class="form-control" id="bpjs_tk_jht_rupiah">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label font-weight-bold">PERUSAHAAN (JHT <label id="bpjs_tk_jht_perusahaan_persen" class="font-weight-bold"></label>%) : </label>
                                                                <input readonly type="text" class="form-control" id="bpjs_tk_jht_perusahaan_rupiah">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label font-weight-bold">KARYAWAN (JHT <label id="bpjs_tk_jht_karyawan_persen" class="font-weight-bold"></label>%) : </label>
                                                                <input readonly type="text" class="form-control" id="bpjs_tk_jht_karyawan_rupiah">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <div class="expanel expanel-success">
                                                <div class="expanel-heading">
                                                    <h4 class="expanel-title">BPJS KETENAGAKERJAAN (JAMINAN KEMATIAN)</h4>
                                                </div>
                                                <div class="expanel-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label font-weight-bold">BPJS TK (JKM <label id="bpjs_tk_jkm_persen" class="font-weight-bold"></label>%) : </label>
                                                                <input readonly type="text" class="form-control" id="bpjs_tk_jkm_rupiah">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label font-weight-bold">PERUSAHAAN (JKM <label id="bpjs_tk_jkm_perusahaan_persen" class="font-weight-bold"></label>%) : </label>
                                                                <input readonly type="text" class="form-control" id="bpjs_tk_jkm_perusahaan_rupiah">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label font-weight-bold">KARYAWAN (JKM <label id="bpjs_tk_jkm_karyawan_persen" class="font-weight-bold"></label>%) : </label>
                                                                <input readonly type="text" class="form-control" id="bpjs_tk_jkm_karyawan_rupiah">
                                                            </div>
                                                        </div>                
                                                    </div>
                                                </div>
                                            </div>                                            

                                            <div class="expanel expanel-success">
                                                <div class="expanel-heading">
                                                    <h4 class="expanel-title">BPJS KETENAGAKERJAAN (JAMINAN PENSIUN)</h4>
                                                </div>
                                                <div class="expanel-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label font-weight-bold">BPJS TK (JPN <label id="bpjs_tk_jpn_persen" class="font-weight-bold"></label>%) : </label>
                                                                <input readonly type="text" class="form-control" id="bpjs_tk_jpn_rupiah">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label font-weight-bold">PERUSAHAAN (JPN <label id="bpjs_tk_jpn_perusahaan_persen" class="font-weight-bold"></label>%) : </label>
                                                                <input readonly type="text" class="form-control" id="bpjs_tk_jpn_perusahaan_rupiah">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label font-weight-bold">KARYAWAN (JPN <label id="bpjs_tk_jpn_karyawan_persen" class="font-weight-bold"></label>%) : </label>
                                                                <input readonly type="text" class="form-control" id="bpjs_tk_jpn_karyawan_rupiah">
                                                            </div>
                                                        </div>                
                                                    </div>
                                                </div>
                                            </div>                                            
                                        </div>                                            
                                    </div>                            
                                </div>

                                <div class="tab_content">
                                    <div class="row">
                                        <div class="col-md-12 m-0 p-0">
                                            <div class="expanel expanel-success">
                                                <div class="expanel-heading">
                                                    <h4 class="expanel-title">BPJS KESEHATAN (JAMINAN KESEHATAN NASIONAL)</h4>
                                                </div>
                                                <div class="expanel-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label font-weight-bold">BPJS KS (JKN <label id="bpjs_ks_jkn_persen" class="font-weight-bold"></label>%) : </label>
                                                                <input readonly type="text" class="form-control" id="bpjs_ks_jkn_rupiah">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label font-weight-bold">PERUSAHAAN (JKN <label id="bpjs_ks_jkn_perusahaan_persen" class="font-weight-bold"></label>%) : </label>
                                                                <input readonly type="text" class="form-control" id="bpjs_ks_jkn_perusahaan_rupiah">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label font-weight-bold">KARYAWAN (JKN <label id="bpjs_ks_jkn_karyawan_persen" class="font-weight-bold"></label>%) : </label>
                                                                <input readonly type="text" class="form-control" id="bpjs_ks_jkn_karyawan_rupiah">
                                                            </div>
                                                        </div>            
                                                    </div>
                                                </div>
                                            </div>                                                       
                                        </div>                                                       
                                    </div>                            
                                </div>

                            </div>
                        </div>                
                    </div>                
                </div>
            </div>
            <div class="modal-footer bg-primary p-1">
                <div class="btn-list">
                    <button type="button" id="btn-update" class="btn btn-secondary btn-app" data-dismiss="modal"><span><i class="fa fa-save"></i></span> Update</button>
                    <button type="button" id="btn-close" class="btn btn-warning btn-app" data-dismiss="modal"><span><i class="fa fa-exit"></i></span> Tutup</button>
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

        $('body').on('change', '#periode_payroll', function () {
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
            $("#data-employeebpjs").hide();
            $("#inputSearch").hide();
            $("#injectData1").hide();
            $("#injectData2").hide();
            $("#loadingProcess").hide();


            $('#searchData').keyup(function(){
                search_table($(this).val());
            });

            function search_table(value){
                $('#data-employeebpjs tbody tr').each(function(){
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
            var periode_kehadiran = $("#periode_payroll").val();      

            $("#btn-cari").addClass("btn-loading");
            $("#btn-cari").html('Loading...');
            $("#btn-cari").attr("disabled", true);

            $("#data-employeebpjs tbody").empty();
            $("#data-employeebpjs").show();
            $("#inputSearch").show();
            $("#injectData1").show();
            $("#injectData2").show();
            $("#loadingProcess").show();

            $.ajax({
                type:"POST",
                url: "{{route('hris.employeebpjs.ajax_empbpjs')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    status_staff:status_staff,
                    searchData:searchData,
                    periode_kehadiran:periode_kehadiran,        
                },
                dataType: 'json',
                success: function(res){
                    if(res.length > 0){
                        terakhirUpdate = new Date(res[0].updated_at);
                        operator = res[0].operator;

                        $("#subtitle-table1").text('Terakhir diupdate : ' + terakhirUpdate + ' oleh ' + operator);

                        $.ajax({
                            type:"POST",
                            url: "{{route('hris.employeebpjs.ajax_bpjssetting')}}",
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            data: {},
                            dataType: 'json',
                            success: function(res1){                                
                                $("#bpjs_tk_persen_1").html('JKK ' + res1[0].bpjs_tk_jkk_persen + '%');
                                $("#bpjs_tk_persen_2").html('JHT ' + res1[0].bpjs_tk_jht_persen + '%');
                                $("#bpjs_tk_persen_3").html('JKM ' + res1[0].bpjs_tk_jkm_persen + '%');
                                $("#bpjs_tk_persen_4").html('JPN ' + res1[0].bpjs_tk_jpn_persen + '%');
                                $("#bpjs_ks_persen").html('JKN ' + res1[0].bpjs_ks_jkn_persen + '%');
                            }
                        });
            

                        for(i=0;i<res.length;i++) {
                            nomor_urut = i+1;
                            
                            $htmlTable = '' +
                            '<tr class="text-center">' +
                            '   <td class="text-nowrap text-right align-middle">' + 
                                    nomor_urut + '. ' +
                            '       <input type="hidden" id="kode_bpjs_' + i + '" name="kode_bpjs" value="' + res[i].kode_bpjs + '" >' +
                            '       <input type="hidden" id="site_nirwana_name_' + i + '" name="site_nirwana_name" value="' + res[i].site_nirwana_name + '" >' +
                            '       <input type="hidden" id="department_name_' + i + '" name="department_name" value="' + res[i].department_name + '" >' +
                            '       <input type="hidden" id="sub_dept_name_' + i + '" name="sub_dept_name" value="' + res[i].sub_dept_name + '" >' +
                            '       <input type="hidden" id="status_aktif_bpjs_tk_' + i + '" name="status_aktif_bpjs_tk" value="' + res[i].status_aktif_bpjs_tk + '" >' +
                            '       <input type="hidden" id="tanggal_bpjs_ketenagakerjaan_' + i + '" name="tanggal_bpjs_ketenagakerjaan" value="' + res[i].tanggal_bpjs_ketenagakerjaan + '" >' +
                            '       <input type="hidden" id="nomor_bpjs_ketenagakerjaan_' + i + '" name="nomor_bpjs_ketenagakerjaan" value="' + res[i].nomor_bpjs_ketenagakerjaan + '" >' +
                            '       <input type="hidden" id="status_aktif_bpjs_ks_' + i + '" name="status_aktif_bpjs_ks" value="' + res[i].status_aktif_bpjs_ks + '" >' +
                            '       <input type="hidden" id="tanggal_bpjs_kesehatan_' + i + '" name="tanggal_bpjs_kesehatan" value="' + res[i].tanggal_bpjs_kesehatan + '" >' +
                            '       <input type="hidden" id="nomor_bpjs_kesehatan_' + i + '" name="nomor_bpjs_kesehatan" value="' + res[i].nomor_bpjs_kesehatan + '" >' +

                            '       <input type="hidden" id="bpjs_tk_jkm_persen_' + i + '" name="bpjs_tk_jkm_persen" value="' + res[i].bpjs_tk_jkm_persen + '" >' +
                            '       <input type="hidden" id="bpjs_tk_jkm_perusahaan_persen_' + i + '" name="bpjs_tk_jkm_perusahaan_persen" value="' + res[i].bpjs_tk_jkm_perusahaan_persen + '" >' +
                            '       <input type="hidden" id="bpjs_tk_jkm_karyawan_persen_' + i + '" name="bpjs_tk_jkm_karyawan_persen" value="' + res[i].bpjs_tk_jkm_karyawan_persen + '" >' +
                            '       <input type="hidden" id="bpjs_tk_jkm_rupiah_' + i + '" name="bpjs_tk_jkm_rupiah" value="' + res[i].bpjs_tk_jkm_rupiah + '" >' +
                            '       <input type="hidden" id="bpjs_tk_jkm_perusahaan_rupiah_' + i + '" name="bpjs_tk_jkm_perusahaan_rupiah" value="' + res[i].bpjs_tk_jkm_perusahaan_rupiah + '" >' +
                            '       <input type="hidden" id="bpjs_tk_jkm_karyawan_rupiah_' + i + '" name="bpjs_tk_jkm_karyawan_rupiah" value="' + res[i].bpjs_tk_jkm_karyawan_rupiah + '" >' +
                            
                            '       <input type="hidden" id="bpjs_tk_jkk_persen_' + i + '" name="bpjs_tk_jkk_persen" value="' + res[i].bpjs_tk_jkk_persen + '" >' +
                            '       <input type="hidden" id="bpjs_tk_jkk_perusahaan_persen_' + i + '" name="bpjs_tk_jkk_perusahaan_persen" value="' + res[i].bpjs_tk_jkk_perusahaan_persen + '" >' +
                            '       <input type="hidden" id="bpjs_tk_jkk_karyawan_persen_' + i + '" name="bpjs_tk_jkk_karyawan_persen" value="' + res[i].bpjs_tk_jkk_karyawan_persen + '" >' +
                            '       <input type="hidden" id="bpjs_tk_jkk_rupiah_' + i + '" name="bpjs_tk_jkk_rupiah" value="' + res[i].bpjs_tk_jkk_rupiah + '" >' +
                            '       <input type="hidden" id="bpjs_tk_jkk_perusahaan_rupiah_' + i + '" name="bpjs_tk_jkk_perusahaan_rupiah" value="' + res[i].bpjs_tk_jkk_perusahaan_rupiah + '" >' +
                            '       <input type="hidden" id="bpjs_tk_jkk_karyawan_rupiah_' + i + '" name="bpjs_tk_jkk_karyawan_rupiah" value="' + res[i].bpjs_tk_jkk_karyawan_rupiah + '" >' +

                            '       <input type="hidden" id="bpjs_tk_jht_persen_' + i + '" name="bpjs_tk_jht_persen" value="' + res[i].bpjs_tk_jht_persen + '" >' +
                            '       <input type="hidden" id="bpjs_tk_jht_perusahaan_persen_' + i + '" name="bpjs_tk_jht_perusahaan_persen" value="' + res[i].bpjs_tk_jht_perusahaan_persen + '" >' +
                            '       <input type="hidden" id="bpjs_tk_jht_karyawan_persen_' + i + '" name="bpjs_tk_jht_karyawan_persen" value="' + res[i].bpjs_tk_jht_karyawan_persen + '" >' +
                            '       <input type="hidden" id="bpjs_tk_jht_rupiah_' + i + '" name="bpjs_tk_jht_rupiah" value="' + res[i].bpjs_tk_jht_rupiah + '" >' +
                            '       <input type="hidden" id="bpjs_tk_jht_perusahaan_rupiah_' + i + '" name="bpjs_tk_jht_perusahaan_rupiah" value="' + res[i].bpjs_tk_jht_perusahaan_rupiah + '" >' +
                            '       <input type="hidden" id="bpjs_tk_jht_karyawan_rupiah_' + i + '" name="bpjs_tk_jht_karyawan_rupiah" value="' + res[i].bpjs_tk_jht_karyawan_rupiah + '" >' +

                            '       <input type="hidden" id="bpjs_tk_jpn_persen_' + i + '" name="bpjs_tk_jpn_persen" value="' + res[i].bpjs_tk_jpn_persen + '" >' +
                            '       <input type="hidden" id="bpjs_tk_jpn_perusahaan_persen_' + i + '" name="bpjs_tk_jpn_perusahaan_persen" value="' + res[i].bpjs_tk_jpn_perusahaan_persen + '" >' +
                            '       <input type="hidden" id="bpjs_tk_jpn_karyawan_persen_' + i + '" name="bpjs_tk_jpn_karyawan_persen" value="' + res[i].bpjs_tk_jpn_karyawan_persen + '" >' +
                            '       <input type="hidden" id="bpjs_tk_jpn_rupiah_' + i + '" name="bpjs_tk_jpn_rupiah" value="' + res[i].bpjs_tk_jpn_rupiah + '" >' +
                            '       <input type="hidden" id="bpjs_tk_jpn_perusahaan_rupiah_' + i + '" name="bpjs_tk_jpn_perusahaan_rupiah" value="' + res[i].bpjs_tk_jpn_perusahaan_rupiah + '" >' +
                            '       <input type="hidden" id="bpjs_tk_jpn_karyawan_rupiah_' + i + '" name="bpjs_tk_jpn_karyawan_rupiah" value="' + res[i].bpjs_tk_jpn_karyawan_rupiah + '" >' +

                            '       <input type="hidden" id="bpjs_ks_jkn_persen_' + i + '" name="bpjs_ks_jkn_persen" value="' + res[i].bpjs_ks_jkn_persen + '" >' +
                            '       <input type="hidden" id="bpjs_ks_jkn_perusahaan_persen_' + i + '" name="bpjs_ks_jkn_perusahaan_persen" value="' + res[i].bpjs_ks_jkn_perusahaan_persen + '" >' +
                            '       <input type="hidden" id="bpjs_ks_jkn_karyawan_persen_' + i + '" name="bpjs_ks_jkn_karyawan_persen" value="' + res[i].bpjs_ks_jkn_karyawan_persen + '" >' +
                            '       <input type="hidden" id="bpjs_ks_jkn_rupiah_' + i + '" name="bpjs_ks_jkn_rupiah" value="' + res[i].bpjs_ks_jkn_rupiah + '" >' +
                            '       <input type="hidden" id="bpjs_ks_jkn_perusahaan_rupiah_' + i + '" name="bpjs_ks_jkn_perusahaan_rupiah" value="' + res[i].bpjs_ks_jkn_perusahaan_rupiah + '" >' +
                            '       <input type="hidden" id="bpjs_ks_jkn_karyawan_rupiah_' + i + '" name="bpjs_ks_jkn_karyawan_rupiah" value="' + res[i].bpjs_ks_jkn_karyawan_rupiah + '" >' +

                            '   </td>' +
                            '   <td class="text-nowrap text-center align-middle">' + res[i].bpjs_kehadiran  + '</td>' +
                            '   <td class="text-nowrap text-right align-middle">' + res[i].enroll_id  + '</td>' +
                            '   <td class="text-nowrap text-left align-middle">' + res[i].nik  + '</td>' +
                            '   <td class="text-nowrap text-left align-middle">' + res[i].employee_name  + '</td>' +
                            '   <td class="text-nowrap text-right align-middle">' + res[i].dasar_pot_bpjs_rupiah  + '</td>' +
                            '   <td class="text-nowrap text-right align-middle">' + res[i].bpjs_tk_jkk_rupiah  + '</td>' +
                            '   <td class="text-nowrap text-right align-middle">' + res[i].bpjs_tk_jht_rupiah  + '</td>' +
                            '   <td class="text-nowrap text-right align-middle">' + res[i].bpjs_tk_jkm_rupiah  + '</td>' +
                            '   <td class="text-nowrap text-right align-middle">' + res[i].bpjs_tk_jpn_rupiah  + '</td>' +
                            '   <td class="text-nowrap text-right align-middle">' + res[i].bpjs_ks_jkn_rupiah  + '</td>' +
                            '   <td class="text-nowrap text-right align-middle">' + res[i].total_iuran  + '</td>' +
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

            var kode_bpjs = currentRow.find('input[name="kode_bpjs"]').val();
            var site_nirwana_name = currentRow.find('input[name="site_nirwana_name"]').val();
            var department_name = currentRow.find('input[name="department_name"]').val();
            var sub_dept_name = currentRow.find('input[name="sub_dept_name"]').val();
            var status_aktif_bpjs_tk = currentRow.find('input[name="status_aktif_bpjs_tk"]').val();
            var tanggal_bpjs_ketenagakerjaan = currentRow.find('input[name="tanggal_bpjs_ketenagakerjaan"]').val();
            var nomor_bpjs_ketenagakerjaan = currentRow.find('input[name="nomor_bpjs_ketenagakerjaan"]').val();
            var status_aktif_bpjs_ks = currentRow.find('input[name="status_aktif_bpjs_ks"]').val();
            var tanggal_bpjs_kesehatan = currentRow.find('input[name="tanggal_bpjs_kesehatan"]').val();
            var nomor_bpjs_kesehatan = currentRow.find('input[name="nomor_bpjs_kesehatan"]').val();

            var bpjs_tk_jkm_persen = currentRow.find('input[name="bpjs_tk_jkm_persen"]').val();
            var bpjs_tk_jkm_perusahaan_persen = currentRow.find('input[name="bpjs_tk_jkm_perusahaan_persen"]').val();
            var bpjs_tk_jkm_karyawan_persen = currentRow.find('input[name="bpjs_tk_jkm_karyawan_persen"]').val();
            var bpjs_tk_jkm_perusahaan_rupiah = currentRow.find('input[name="bpjs_tk_jkm_perusahaan_rupiah"]').val();
            var bpjs_tk_jkm_karyawan_rupiah = currentRow.find('input[name="bpjs_tk_jkm_karyawan_rupiah"]').val();

            var bpjs_tk_jkk_persen = currentRow.find('input[name="bpjs_tk_jkk_persen"]').val();
            var bpjs_tk_jkk_perusahaan_persen = currentRow.find('input[name="bpjs_tk_jkk_perusahaan_persen"]').val();
            var bpjs_tk_jkk_karyawan_rupiah = currentRow.find('input[name="bpjs_tk_jkk_karyawan_rupiah"]').val();
            var bpjs_tk_jkk_perusahaan_rupiah = currentRow.find('input[name="bpjs_tk_jkk_perusahaan_rupiah"]').val();
            var bpjs_tk_jkk_karyawan_persen = currentRow.find('input[name="bpjs_tk_jkk_karyawan_persen"]').val();

            var bpjs_tk_jht_persen = currentRow.find('input[name="bpjs_tk_jht_persen"]').val();
            var bpjs_tk_jht_perusahaan_persen = currentRow.find('input[name="bpjs_tk_jht_perusahaan_persen"]').val();
            var bpjs_tk_jht_karyawan_persen = currentRow.find('input[name="bpjs_tk_jht_karyawan_persen"]').val();
            var bpjs_tk_jht_rupiah = currentRow.find("td:eq(7)").text();
            var bpjs_tk_jht_perusahaan_rupiah = currentRow.find('input[name="bpjs_tk_jht_perusahaan_rupiah"]').val();
            var bpjs_tk_jht_karyawan_rupiah = currentRow.find('input[name="bpjs_tk_jht_karyawan_rupiah"]').val();
            
            var bpjs_tk_jpn_persen = currentRow.find('input[name="bpjs_tk_jpn_persen"]').val();
            var bpjs_tk_jpn_perusahaan_persen = currentRow.find('input[name="bpjs_tk_jpn_perusahaan_persen"]').val();
            var bpjs_tk_jpn_karyawan_persen = currentRow.find('input[name="bpjs_tk_jpn_karyawan_persen"]').val();
            var bpjs_tk_jpn_perusahaan_rupiah = currentRow.find('input[name="bpjs_tk_jpn_perusahaan_rupiah"]').val();
            var bpjs_tk_jpn_karyawan_rupiah = currentRow.find('input[name="bpjs_tk_jpn_karyawan_rupiah"]').val();

            var bpjs_ks_jkn_persen = currentRow.find('input[name="bpjs_ks_jkn_persen"]').val();
            var bpjs_ks_jkn_perusahaan_persen = currentRow.find('input[name="bpjs_ks_jkn_perusahaan_persen"]').val();
            var bpjs_ks_jkn_karyawan_persen = currentRow.find('input[name="bpjs_ks_jkn_karyawan_persen"]').val();
            var bpjs_ks_jkn_perusahaan_rupiah = currentRow.find('input[name="bpjs_ks_jkn_perusahaan_rupiah"]').val();
            var bpjs_ks_jkn_karyawan_rupiah = currentRow.find('input[name="bpjs_ks_jkn_karyawan_rupiah"]').val();

            var enroll_id = currentRow.find("td:eq(2)").text();
            var nik = currentRow.find("td:eq(3)").text();
            var employee_name = currentRow.find("td:eq(4)").text();
            var dasar_pot_bpjs = currentRow.find("td:eq(5)").text();
            var total_iuran = currentRow.find("td:eq(11)").text();
            var bpjs_tk_jkk_rupiah = currentRow.find("td:eq(6)").text();
            var bpjs_tk_jht_rupiah = currentRow.find("td:eq(7)").text();
            var bpjs_tk_jkm_rupiah = currentRow.find("td:eq(8)").text();
            var bpjs_tk_jpn_rupiah = currentRow.find("td:eq(9)").text();
            var bpjs_ks_jkn_rupiah = currentRow.find("td:eq(10)").text();
            
            $("#bpjs_tk_jkk_persen").html(bpjs_tk_jkk_persen);
            $("#bpjs_tk_jkk_perusahaan_persen").html(bpjs_tk_jkk_perusahaan_persen);
            $("#bpjs_tk_jkk_karyawan_persen").html(bpjs_tk_jkk_karyawan_persen);
            $("#bpjs_tk_jkk_rupiah").val(bpjs_tk_jkk_rupiah);
            $("#bpjs_tk_jkk_perusahaan_rupiah").val(bpjs_tk_jkk_perusahaan_rupiah);
            $("#bpjs_tk_jkk_karyawan_rupiah").val(bpjs_tk_jkk_karyawan_rupiah);

            $("#bpjs_tk_jkm_persen").html(bpjs_tk_jkm_persen);
            $("#bpjs_tk_jkm_perusahaan_persen").html(bpjs_tk_jkm_perusahaan_persen);
            $("#bpjs_tk_jkm_karyawan_persen").html(bpjs_tk_jkm_karyawan_persen);
            $("#bpjs_tk_jkm_rupiah").val(bpjs_tk_jkm_rupiah);
            $("#bpjs_tk_jkm_perusahaan_rupiah").val(bpjs_tk_jkm_perusahaan_rupiah);
            $("#bpjs_tk_jkm_karyawan_rupiah").val(bpjs_tk_jkm_karyawan_rupiah);

            $("#bpjs_tk_jht_persen").html(bpjs_tk_jht_persen);
            $("#bpjs_tk_jht_perusahaan_persen").html(bpjs_tk_jht_perusahaan_persen);
            $("#bpjs_tk_jht_karyawan_persen").html(bpjs_tk_jht_karyawan_persen);
            $("#bpjs_tk_jht_rupiah").val(bpjs_tk_jht_rupiah);
            $("#bpjs_tk_jht_perusahaan_rupiah").val(bpjs_tk_jht_perusahaan_rupiah);
            $("#bpjs_tk_jht_karyawan_rupiah").val(bpjs_tk_jht_karyawan_rupiah);
            
            $("#bpjs_tk_jpn_persen").html(bpjs_tk_jpn_persen);
            $("#bpjs_tk_jpn_perusahaan_persen").html(bpjs_tk_jpn_perusahaan_persen);
            $("#bpjs_tk_jpn_karyawan_persen").html(bpjs_tk_jpn_karyawan_persen);
            $("#bpjs_tk_jpn_rupiah").val(bpjs_tk_jpn_rupiah);
            $("#bpjs_tk_jpn_perusahaan_rupiah").val(bpjs_tk_jpn_perusahaan_rupiah);
            $("#bpjs_tk_jpn_karyawan_rupiah").val(bpjs_tk_jpn_karyawan_rupiah);

            $("#bpjs_ks_jkn_persen").html(bpjs_ks_jkn_persen);
            $("#bpjs_ks_jkn_perusahaan_persen").html(bpjs_ks_jkn_perusahaan_persen);
            $("#bpjs_ks_jkn_karyawan_persen").html(bpjs_ks_jkn_karyawan_persen);
            $("#bpjs_ks_jkn_rupiah").val(bpjs_ks_jkn_rupiah);
            $("#bpjs_ks_jkn_perusahaan_rupiah").val(bpjs_ks_jkn_perusahaan_rupiah);
            $("#bpjs_ks_jkn_karyawan_rupiah").val(bpjs_ks_jkn_karyawan_rupiah);

            $("#ajax-bpjs-karyawan-model").modal('show');            
                        
            if (!kode_bpjs) { 
                kode_bpjs = ""; 
            }
            if (!site_nirwana_name) { 
                site_nirwana_name = ""; 
            }
            if (!department_name) { 
                department_name = ""; 
            }
            if (!sub_dept_name) { 
                sub_dept_name = ""; 
            }
            if (!status_aktif_bpjs_tk) { 
                status_aktif_bpjs_tk = ""; 
            }
            if (!tanggal_bpjs_ketenagakerjaan) { 
                tanggal_bpjs_ketenagakerjaan = ""; 
            }
            if (!nomor_bpjs_ketenagakerjaan) { 
                nomor_bpjs_ketenagakerjaan = ""; 
            }
            if (!status_aktif_bpjs_ks) { 
                status_aktif_bpjs_ks = "";  
            }
            if (!tanggal_bpjs_kesehatan) { 
                tanggal_bpjs_kesehatan = ""; 
            }
            if (!nomor_bpjs_kesehatan) { 
                nomor_bpjs_kesehatan = ""; 
            }

            $("#kode_bpjs_modal").val(kode_bpjs);            
            $("#enroll_id").val(enroll_id);            
            $("#nik").val(nik);            
            $("#employee_name").val(employee_name);            
            
            $("#dasar_pot_bpjs").val(dasar_pot_bpjs);

            $("#site_nirwana_name").val(site_nirwana_name);            
            $("#department_name").val(department_name);            
            $("#sub_dept_name").val(sub_dept_name);            
            
            $("#total_iuran").val(total_iuran);            
            
            $("#status_aktif_bpjs_tk").val(status_aktif_bpjs_tk).trigger("change");
            $("#tanggal_bpjs_ketenagakerjaan").val(tanggal_bpjs_ketenagakerjaan);            
            $("#nomor_bpjs_ketenagakerjaan").val(nomor_bpjs_ketenagakerjaan);            
            $("#status_aktif_bpjs_ks").val(status_aktif_bpjs_ks).trigger("change");
            $("#tanggal_bpjs_kesehatan").val(tanggal_bpjs_kesehatan);            
            $("#nomor_bpjs_kesehatan").val(nomor_bpjs_kesehatan);       
                        
        });

        $('body').on('click', '#btn-update-bpjs', function (event) {
            var kode_periode_bpjs = $("#kode_periode_bpjs").val();                        
            var periode_payroll = $("#periode_payroll").val();                        

            var tanggal = periode_payroll.split(' s/d ');
            
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

                        $("#btn-update-bpjs").addClass("btn-loading");
                        $("#btn-update-bpjs").html('Loading...');
                        $("#btn-update-bpjs").attr("disabled", true);
                        
                        $("#datatable-ajax-crud tbody").empty();
                        $("#datatable-ajax-crud").show();
                        $("#loadingProcess").show();

                        $.ajax({
                            type:"POST",
                            url: "{{route('hris.employeebpjs.update_bpjs')}}",
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            data: {
                                kode_periode_bpjs:kode_periode_bpjs,
                                periode_payroll:periode_payroll,
                            },
                            dataType: 'json',
                            success: function(res){
                                notif({
                                    msg: "<b>Success:</b> Data berhasil di update.",
                                    type: "success"
                                });

                                $('#btn-update-bpjs').removeClass("btn-loading");
                                $("#btn-update-bpjs").html('<i class="fa fa-upload"></i> Update');
                                $("#btn-update-bpjs").attr("disabled", false);

                                $("#btn-cari").click();

                            },
                            error: function(res){
                                $('#btn-update-bpjs').removeClass("btn-loading");
                                $("#btn-update-bpjs").html('<i class="fa fa-upload"></i> Update');
                                $("#btn-update-bpjs").attr("disabled", false);
                                $("#loadingProcess").hide();

                                notif({
                                    msg: "<b>Oops!</b> An Error Occurred",
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

        function defaultDate(s) {
            if(s) {
                var bits = s.split('-');
                var d = bits[2] + '-' + bits[1] + '-' + bits[0];
            }
            return d;
        }        

        $('body').on('click', '#btn-update', function (event) {
            var kode_bpjs = $("#kode_bpjs_modal").val();
            var enroll_id = $("#enroll_id").val();
            var status_aktif_bpjs_tk = $("#status_aktif_bpjs_tk").val();
            var tanggal_bpjs_ketenagakerjaan = $("#tanggal_bpjs_ketenagakerjaan").val();
            var nomor_bpjs_ketenagakerjaan = $("#nomor_bpjs_ketenagakerjaan").val();
            var status_aktif_bpjs_ks = $("#status_aktif_bpjs_ks").val();
            var tanggal_bpjs_kesehatan = $("#tanggal_bpjs_kesehatan").val();
            var nomor_bpjs_kesehatan = $("#nomor_bpjs_kesehatan").val();
            
            $('#btn-update').addClass("btn-loading");
            $("#btn-update").html('Please wait...');
            $("#btn-update").attr("disabled", true);

            $.ajax({
                type:"POST",
                url: "{{route('hris.employeebpjs.update')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    kode_bpjs:kode_bpjs,
                    enroll_id:enroll_id,
                    status_aktif_bpjs_tk:status_aktif_bpjs_tk,
                    tanggal_bpjs_ketenagakerjaan:tanggal_bpjs_ketenagakerjaan,
                    nomor_bpjs_ketenagakerjaan:nomor_bpjs_ketenagakerjaan,
                    status_aktif_bpjs_ks:status_aktif_bpjs_ks,
                    tanggal_bpjs_kesehatan:tanggal_bpjs_kesehatan,
                    nomor_bpjs_kesehatan:nomor_bpjs_kesehatan,
                },
                dataType: 'json',
                success: function(res){
                    notif({
                        msg: "<b>Info:</b> Data berhasil di simpan.",
                        type: "info"
                    });    
                    $("#btn-cari").click();
                },
                error: function(res){
                    notif({
                        msg: "<b>Error:</b> Oops data gagal di simpan.",
                        type: "error"
                    });    
                }
            });

            $('#btn-update').removeClass("btn-loading");
            $("#btn-update").html('<span><i class="fa fa-save"></i></span> Update');
            $("#btn-update").attr("disabled", false);


        });

    </script>

@endsection
