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
    <style>
        #head_kehadiran, #tabel_data_kehadiran { display: block; }

        #tabel_data_kehadiran {
            height: 1px;
            overflow-y: auto;    /* Trigger vertical scroll    */
            overflow-x: hidden;
            font-size: 10pt; /* Hide the horizontal scroll */
        }
    </style>

@stop
@section('mainarea')
    <!-- page-header -->
    <div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
        <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
            <li><a href="#">ABSENSI KARYAWAN</a></li>
            <li class="active"><span>DATA KEHADIRAN</span></li>
        </ol>
        <div class="ml-auto">
            <div class="input-group">
                <a href="#" id="btn-refresh-data" class="btn btn-icon btn-secondary p-0 m-0" data-toggle="tooltip"
                    title="" data-placement="bottom" data-original-title="Refresh Halaman">
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

        <div class="col-md-6">
            <div class="card shadow card-collapsed">
                <div class="card-header text-white bg-gradient-primary py-2">
                    <div class="card-title">MESIN ABSENSI</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="card-body p-5">
                    <div class="row pb-3">
                        <div class="col-3">
                            <label class="form-label text-primary pt-1">TANGGAL</label>
                        </div>
                        <div class="col-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                    </div>
                                </div>
                                <input id="tanggal_mesin_absensi" type="text" class="form-control data_range" required></input>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label class="form-label text-primary pt-1">KARYAWAN</label>
                        </div>
                        <div class="col-9">
                            <select id="selectEmployee" name="selectEmployee[]" multiple data-placeholder="Pilih karyawan" class="form-control select2 Employee" style="width: 699.238px;" required>
                                @foreach ($selectemployee as $r_empl)
                                    <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer py-2">
                    <a id="btn-updateKehadiran" class="btn btn-app btn-primary text-white"><span><i class="fa fa-download"></i></span> UPDATE ABSENSI</a>
                </div>
            </div>
        </div><!-- col end -->
 
        <div class="col-md-6">
            <div class="card shadow card-collapsed">
                <div class="card-header text-white bg-gradient-primary py-2">
                    <div class="card-title">MESIN ABSENSI LINTAS HARI</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <form id="form_update_lintashari" method="post">
                    @csrf
                    <div class="card-body p-5">
                        <div class="row pb-3">
                            <div class="col-3">
                                <label class="form-label text-primary pt-1">TANGGAL</label>
                            </div>
                            <div class="col-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                        </div>
                                    </div>
                                    <input name="periode_absen" type="text" class="form-control data_range" required></input>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <label class="form-label text-primary pt-1">KARYAWAN</label>
                            </div>
                            <div class="col-9">
                                <select id="selectEmployeeID" name="selectEmployeeID[]" multiple data-placeholder="Pilih karyawan" class="form-control select2 EmployeeID" style="width: 699.238px;" required>
                                    @foreach ($selectemployee as $r_empl)
                                        <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card-footer py-2">
                    <a id="BtnUpdateLintasHari" class="btn btn-app btn-primary text-white BtnUpdateLintasHari"><span><i class="fa fa-download"></i></span> UPDATE ABSENSI</a>
                </div>
            </div>
        </div><!-- col end -->

        <div class="col-md-12">
            <!-- BEGIN FORM-->
            {!! Form::open(['route' => 'hris.mdabsenhadir.ajax_exportexcel', 'id' => 'formExport', 'name' => 'formExport','method'=>'post']) !!}

            @csrf
            <div class="card shadow">
                <div class="card-header text-white bg-gradient-primary py-2">
                    <div class="card-title">KEHADIRAN : </div>
                    <input type="hidden" id="daterange1" name="daterange1">
                    <a class="nav-link card-title" id="daterange-btn1" data-toggle="tooltip"
                    title="" data-placement="bottom" data-original-title="Klik di sini untuk pilih tanggal kehadiran">
                    </a>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="card-body px-5 pb-2 pt-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">DEPARTMENT : </label>
                                <select id="selectDepartment" name="selectDepartment" class="form-control">
                                    <option value="">-- PILIH DEPARTMENT --</option>
                                    @foreach ($department as $r_department)
                                        <option value="{{$r_department->department_name}}">{{$r_department->department_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">CHOOSE SECTION : </label>
                                <select id="selectBagian" name="selectBagian" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">STAFF / NON STAFF : </label>
                                <select id="status_staff" name="status_staff" class="form-control">
                                    <option value="">-- CHOOSE STAFF --</option>
                                    <option value="STAFF">STAFF</option>
                                    <option value="NON STAFF">NON STAFF</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-label">CARI DATA : </label>
                                <select id="selectEmployeeID" name="selectEmployeeID[]" multiple data-placeholder="Pilih karyawan" class="form-control select2 EmployeeID">
                                    @foreach ($selectemployee as $r_empl)
                                        <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">FACTORY : </label>
                                <select id="siteNirwana" name="siteNirwana" class="form-control">
                                    <option value="">-- PILIH FACTORY --</option>
                                    @foreach ($site as $s)
                                        <option value="{{$s->site_nirwana_id}}">{{$s->site_nirwana_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light py-3">
                    <div class="text-white">
                        <button type="submit" id="btn-exportexcel" class="btn btn-app btn-primary mr-0 mt-0 mb-0" data-toggle="tooltip" title="Export Data ke File Excel"><i class="ion-ios7-download"></i> EXPORT</button>
                        <button type="button" id="btn-importexcel" class="btn btn-app btn-success mr-0 mt-0 mb-0" title="Import ke database" data-target="#import_data_kehadiran" data-toggle="modal"><i class="fa fa-file-excel-o"></i> IMPORT</button>
                        <a id="btn-caridata" class="btn btn-app btn-primary mr-0 mt-0 mb-0 text-white" data-toggle="tooltip" title="Cari Data"><i class="ion-search"></i> CARI</a>
                        <a id="btn-exportpdf" class="btn btn-app mr-0 mt-0 mb-0 text-white" style="background-color: #e73a3a" data-toggle="tooltip" title="Preview by pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> View PDF</a>
                        <a id="btn-view_excel" class="btn btn-success btn-app mr-0 mt-0 mb-0 text-white" data-toggle="tooltip" title="Preview by excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> View Excel</a>
                    </div>
                </div>

            </div>
        </div><!-- col end -->
        {{-- </form> --}}
        {!! Form::close() !!}
        <!-- END FORM-->

        

        <div id="data-absensi-karyawan" class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card shadow">
                <div class="card-header text-white bg-gradient-primary py-2">
                    <div class="card-title">DATA ABSENSI KARYAWAN</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="datatable-ajax-crud" class="table table-sm table-striped table-hover w-100">
                                    <thead>
                                        <tr class="text-center">
                                            <th scope="col" class="bg-primary border-primary w-5 align-middle"></th>
                                            <th scope="col" class="bg-primary border-primary w-5 align-middle"></th>
                                            <th scope="col" class="bg-primary border-primary w-5 align-middle"></th>
                                            <th scope="col" class="bg-primary border-primary w-5 align-middle"></th>
                                            <th scope="col" class="bg-primary border-primary w-5 align-middle"></th>
                                            <th scope="col" class="bg-primary border-primary w-5 align-middle"></th>
                                            <th scope="col" class="bg-primary border-primary w-5 align-middle"></th>
                                            <th scope="col" class="bg-primary border-primary w-50 align-middle"></th>
                                            <th scope="col" class="bg-primary border-primary w-50 align-middle"></th>
                                            <th scope="col" class="bg-primary border-primary w-5 align-middle"></th>
                                            <th scope="col" class="bg-primary border-primary w-5 align-middle"></th>
                                            <th scope="col" class="bg-primary border-primary w-5 align-middle"></th>
                                            <th scope="col" class="bg-primary border-primary w-5 align-middle"></th>
                                            <th scope="col" class="bg-primary border-primary w-5 align-middle"></th>
                                            <th scope="col" class="bg-primary border-primary w-5 align-middle"></th>
                                            <th scope="col" class="bg-primary border-primary w-5 align-middle"></th>
                                            <th scope="col" class="bg-primary border-primary w-5 align-middle"></th>
                                            <th scope="col" class="bg-primary border-primary w-5 align-middle"></th>
                                            <th scope="col" class="bg-primary border-primary w-5 align-middle"></th>
                                            <th scope="col" class="bg-primary border-primary w-5 align-middle"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="import_data_kehadiran" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 1330px">
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-content">
                        <div class="modal-header bg-success p-2">
                            <h4 class="modal-title pl-2 font-weight-bold" >Import Data Kehadiran</h4>
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
                                <div class="col-2">
                                </div>
                                <div class="col-10">
                                </div>
                                <div class="col-2" id="length" style="font-weight: bold; color:rgb(34, 189, 203)">
                                </div>
                            </div>
                            <div class="row pt-2 justify-content-center">
                                <div class="col-12">
                                    <table class="table table-bordered" style="overflow-x:auto" width="2500">
                                        <thead id="head_kehadiran">
                                            <tr>
                                                <td width="70px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px;font-size:10pt">NO</td>
                                                <td width="110px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">NIK</td>
                                                <td width="58px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px;font-size:10pt">ID</td>
                                                <td style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px;font-size:10pt" width="100px">TANGGAL</td>
                                                <td style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px;font-size:10pt" width="250px">NAMA KARYAWAN</td>
                                                <td style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px;font-size:10pt" width="113px">KERJA/LIBUR</td>
                                                <td style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px;font-size:10pt" width="122px">SCHEDULE IN</td>
                                                <td style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px;font-size:10pt" width="122px">SCHEDULE OUT</td>
                                                <td style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px;font-size:10pt" width="122px">ABSEN IN</td>
                                                <td style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px;font-size:10pt" width="122px">ABSEN OUT</td>
                                            </tr>
                                        </thead>
                                        <tbody id="tabel_data_kehadiran">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-12 text-center">
                                    <div id="loading_data_hadir">
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-0 pb-3 pr-3">
                                <div class="col text-center pt-2">
                                    <button type="button" id="presenceImportButton" class="btn btn-success py-1" style="visibility: hidden"><i class="fa fa-upload" aria-hidden="true"></i> IMPORT</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-success p-2">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- boostrap show department model -->
    <div class="modal fade" id="ajax-absenijin-model-add" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary p-2">
                    <h4 class="modal-title pl-2" id="ajaxAbsenIjinModel"></h4>
                    <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                        <i class="fa fa-remove"></i>
                    </button>
                </div>
                <div class="progress progress-xs mb-0">
                    <div id="progress-show-1" class="progress-bar progress-bar-indeterminate bg-green"></div>
                    <div id="progress-hide-1" class="progress-bar"></div>
                </div>
                <div class="modal-body">
                    <!-- BEGIN FORM-->
                    {!! Form::open(['route' => 'hris.employeeatr.create', 'id' => 'form1', 'name' => 'form1', 'method'=>'post']) !!}
                    <input id="uuid" type="hidden">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">No. Form Perizinan : </label>
                                <input type="text" readonly class="form-control" id="nomor_form_perizinan" name="nomor_form_perizinan" placeholder="Nomor Form Perizinan">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Tanggal Permohonan Izin</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                        </div>
                                    </div>
                                    <input id="tanggal_perijinan" name="tanggal_perijinan" type="text" class="form-control fc-datepicker" placeholder="Tanggal Permohonan Izin" maxlength="50" size="50">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Nomor Absen : </label>
                                <input type="text" readonly class="form-control" id="enroll_id" name="enroll_id" placeholder="Nomor Absen">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">NIK : </label>
                                <input type="text" class="form-control" id="nik" name="nik" placeholder="NIK">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Nama Karyawan : </label>
                                <input type="text" readonly class="form-control" id="employee_name" name="employee_name" placeholder="Nama Karyawan">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Bagian : </label>
                                <input type="text" readonly class="form-control" id="sub_dept_name" name="sub_dept_name" placeholder="Bagian">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Jabatan : </label>
                                <input type="text" readonly class="form-control" id="posisi_name" name="posisi_name" placeholder="Jabatan">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label"> Aktif/Non Aktif : </label>
                                <input type="text" readonly class="form-control" id="work_status" name="work_status">
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-primary">
                        <div class="tab_wrapper first_tab">
                            <ul class="tab_list">
                                <li class="text-sm" id="tab-izin">Perizinan</li>
                                <li class="text-sm" id="tab-iks">Izin Keluar Sementara (IKS)</li>
                            </ul>
                            <div class="content_wrapper">
                                <div class="tab_content active">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Tanggal Mulai Izin</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                        </div>
                                                    </div>
                                                    <input id="tanggal_mulai_ijin" name="tanggal_mulai_ijin" type="text" class="form-control fc-datepicker" maxlength="50" size="50">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Tanggal Akhir Izin</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                        </div>
                                                    </div>
                                                    <input id="tanggal_akhir_ijin" name="tanggal_akhir_ijin" type="text" class="form-control fc-datepicker" maxlength="50" size="50">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Jenis Perizinan</label>
                                                <select id="kode_absen_ijin" class="form-control" data-placeholder="-- Pilih Jenis Perijinan --">
                                                    <option value="">-- Pilih Jenis Perizinan --</option>
                                                    @foreach ($refabsenijin as $r_refabsenijin)
                                                        @php
                                                            if (($r_refabsenijin->kode_absen_ijin <> 'IKS') && ($r_refabsenijin->kode_absen_ijin <> 'M')) {
                                                        @endphp
                                                            <option value="{{$r_refabsenijin->kode_absen_ijin}}">{{$r_refabsenijin->kode_nama_absen_ijin}}</option>
                                                        @php
                                                            }
                                                        @endphp
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-0">
                                                <label class="form-label">Keterangan</label>
                                                <textarea class="form-control" id="absen_alasan_izin" name="absen_alasan_izin" rows="2" placeholder="Keterangan"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12 pt-3">
                                            <div class="btn-list">
                                                <button type="button" id="btn-save-izin" class="btn btn-secondary btn-app"><i class="fa fa-save"></i> Simpan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab_content">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Jam Mulai Izin</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-clock-o tx-16 lh-0 op-6"></i>
                                                        </div>
                                                    </div><!-- input-group-prepend -->
                                                    <input id="time_mulai_ijin" name="time_mulai_ijin" class="form-control" id="tpBasic" placeholder="--:--" maxlength="5" onChange="hitungtotaljam();" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Jam Akhir Izin</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-clock-o tx-16 lh-0 op-6"></i>
                                                        </div>
                                                    </div><!-- input-group-prepend -->
                                                    <input id="time_akhir_ijin" name="time_akhir_ijin" class="form-control" id="tpBasic" placeholder="--:--" maxlength="5" onChange="hitungtotaljam();"  type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Total Jam Izin</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-clock-o tx-16 lh-0 op-6"></i>
                                                        </div>
                                                    </div><!-- input-group-prepend -->
                                                    <input id="total_time_ijin" name="total_time_ijin" class="form-control" id="tpBasic" placeholder="Set time" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Jenis Perizinan</label>
                                                <select id="kode_absen_ijin_iks" class="form-control" data-placeholder="-- Pilih Jenis Perijinan --">
                                                    @foreach ($refabsenijin as $r_refabsenijin)
                                                        @php
                                                            if ($r_refabsenijin->kode_absen_ijin == 'IKS') {
                                                        @endphp
                                                            <option value="{{$r_refabsenijin->kode_absen_ijin}}">{{$r_refabsenijin->kode_nama_absen_ijin}}</option>
                                                        @php
                                                            }
                                                        @endphp
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-0">
                                                <label class="form-label">Keterangan</label>
                                                <textarea class="form-control" id="absen_alasan_iks" name="absen_alasan_iks" rows="2" placeholder="Keterangan"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12 pt-3">
                                            <div class="btn-list">
                                                <button type="button" id="btn-save-iks" class="btn btn-secondary btn-app"><i class="fa fa-save"></i> Simpan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- </form> --}}
                    {!! Form::close() !!}
                    <!-- END FORM-->
                </div>
                <div class="modal-footer bg-primary p-1">
                    <div class="btn-list">
                        <button type="button" id="btn-close" class="btn btn-warning btn-app" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
          </div>
    </div>
    <!-- end bootstrap model -->


    <!-- modal edit jadwal -->
    <div class="modal fade" id="modal_edit_jadwal" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary p-2">
                    <h4 class="modal-title pl-2"><b>EDIT JADWAL</b></h4>
                    <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                        <i class="fa fa-remove"></i>
                    </button>
                </div>
               
                <div class="modal-body">
                    <!-- BEGIN FORM-->
                    <form id="form_edit_jadwal" method="post">
                        <input id="uuid_edit"  name="uuid" type="hidden">
                        <input id="absen_in_edit"  name="absen_masuk_kerja" type="hidden">
                        <input id="absen_out_edit"  name="absen_pulang_kerja" type="hidden">
                        <input id="status_absen_edit"  name="status_absen" type="hidden">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">NIK : </label>
                                    <input type="text" id="enroll_id_edit" class="form-control" name="enroll_id" placeholder="NIK" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">No Absen : </label>
                                    <input type="text" id="nik_edit" class="form-control" name="nik" placeholder="NIK" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Nama : </label>
                                    <input type="text" id="employee_name_edit" class="form-control" name="employee_name" placeholder="NIK" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Bagian : </label>
                                    <input type="text" id="sub_dept_name_edit" class="form-control" name="sub_dept_name" placeholder="NIK" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Jabatan : </label>
                                    <input type="text" id="posisi_name_edit" class="form-control" name="posisi_name" placeholder="NIK" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Aktif/Non Aktif : </label>
                                    <input type="text" id="work_status_edit" class="form-control" name="work_status" placeholder="NIK" readonly>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Tanggal: </label>
                                    <input type="date" id="tanggal_berjalan_edit" class="form-control" name="tanggal_berjalan" placeholder="NIK" readonly>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Jadwal In : </label>
                                    <input type="time" id="jadwal_in_edit" class="form-control" name="mulai_jam_kerja" placeholder="NIK">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Jadwal Out : </label>
                                    <input type="time" id="jadwal_out_edit" class="form-control" name="akhir_jam_kerja" placeholder="NIK">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 pt-3">
                            <div class="btn-list">
                                <button type="submit" class="btn btn-secondary btn-app"><i class="fa fa-save"></i> Simpan</button>
                            </div>
                        </div>
                    </form>
                    <!-- END FORM-->
                </div>
                <div class="modal-footer bg-primary p-1">
                    <div class="btn-list">
                        <button type="button" id="btn-close" class="btn btn-warning btn-app" data-dismiss="modal">Tutup</button>
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

    <!-- Timepicker js -->
    <script src="{{URL::asset('assets/plugins/time-picker/jquery.timepicker.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/time-picker/toggles.min.js')}}"></script>

    <!---Tabs js-->
    <script src="{{URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/tabs/tabs.js')}}"></script>

    <!-- Popover js -->
    <script src="{{URL::asset('assets/js/popover.js')}}"></script>

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

        $('#excel_filess').change(function() {
            fill_the_table();
        });
        $('#btn-exportpdf').click(function(e){
            var employee = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
            var department = $('#selectDepartment').val();
            var section = $('#selectBagian').val();
            var status_staff = $('#status_staff').val();
            var siteNirwana = $('#siteNirwana').val();
            var tanggal_awal=$('#daterange1').val();
            var factory=$('#siteNirwana').val();
            var url = 'export_pdf?employee='+employee+'&department='+department+'&section='+section+'&status_staff='+status_staff+'&siteNirwana='+siteNirwana+'&tanggal_awal='+tanggal_awal+'&factory='+factory;
            window.open(url, '_blank');
        });
        $('#btn-view_excel').click(function(e){
            var employee = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
            var department = $('#selectDepartment').val();
            var section = $('#selectBagian').val();
            var status_staff = $('#status_staff').val();
            var siteNirwana = $('#siteNirwana').val();
            var tanggal_awal=$('#daterange1').val();
            var factory=$('#siteNirwana').val();
            $('#btn-view_excel').addClass("btn-loading");
            $("#btn-view_excel").html('Please wait...');
            $("#btn-view_excel").attr("disabled", true);
            $.ajax({
                type: 'POST',
                url: '{{route('hris.mdabsenhadir.view_excel')}}',
                data: {
                    employee:employee,
                    department:department,
                    section:section,
                    status_staff:status_staff,
                    siteNirwana:siteNirwana,
                    tanggal_awal:tanggal_awal,
                    factory:factory
                },
                xhrFields: { responseType : 'blob' },
                success:function(data){
                    var blob = new Blob([data]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    let file_name = tanggal_awal.substring(17, 19)+tanggal_awal.substring(20,22)+' TNA DETAIL '+Math.ceil(Math.random()*1000000);
                    if(employee.length===1){
                        let file_name = tanggal_awal.substring(17, 19)+tanggal_awal.substring(20,22)+' '+employee+' '+' TNA DETAIL '+Math.ceil(Math.random()*1000000);
                    }
                    link.download = file_name+".xlsx";
                    link.click();
                    swal("", "Export detail kehadiran berhasil", "success");
                    $('#btn-view_excel').removeClass("btn-loading");
                    $("#btn-view_excel").attr("disabled", false);
                    $("#btn-view_excel").html('<i class="fa fa-file-excel-o" aria-hidden="true"></i> View Excel');
                },
                error: function(res){
                    swal("", "Export detail kehadiran gagal", "error");
                    $('#btn-view_excel').removeClass("btn-loading");
                    $("#btn-view_excel").attr("disabled", false);
                    $("#btn-view_excel").html('<i class="fa fa-file-excel-o" aria-hidden="true"></i> View Excel');
                }
            });
        })
        $('#presenceImportButton').click(function(e){
            $('#presenceImportButton').addClass("btn-loading");
            $("#presenceImportButton").html('Please wait...');
            $("#presenceImportButton").attr("disabled", true);
            var formData = new FormData();
            var excelFile=document.getElementById("excel_filess");
            var myFile=excelFile.files[0];
            formData.append("excel_file",myFile);
            $.ajax({
                type: 'POST',
                url: '{{route('hris.mdabsenhadir.importing_datahadir')}}',
                contentType: false,
                processData: false,
                data: formData,
                success:function(data){
                    console.log(data);
                    swal({
                        title: "Update Absensi",
                        text: "Data Kehadiran berhasil di update",
                        icon: "success",
                    });
                    $('#tabel_data_lembur').empty();
                    document.getElementById('presenceImportButton').style.visibility='hidden';
                    document.getElementById('tabel_data_kehadiran').style.height='1px';
                    $('#excel_filess').val('');
                    $('#length').text('');
                    $("#import_data_kehadiran").modal('hide');
                    $('#presenceImportButton').removeClass("btn-loading");
                    $("#presenceImportButton").html('<i class="fa fa-upload" aria-hidden="true"></i> IMPORT');
                    $("#presenceImportButton").attr("disabled", false);
                },
                error: function(res){
                    swal("", "IMPORT DATA KEHADIRAN GAGAL!", "error");
                    $('#tabel_data_lembur').empty();
                    document.getElementById('presenceImportButton').style.visibility='hidden';
                    document.getElementById('tabel_data_kehadiran').style.height='1px';
                    $('#excel_filess').val('');
                    $('#length').text('');
                    $("#import_data_kehadiran").modal('hide');
                    $('#presenceImportButton').removeClass("btn-loading");
                    $("#presenceImportButton").html('<i class="fa fa-upload" aria-hidden="true"></i> IMPORT');
                    $("#presenceImportButton").attr("disabled", false);
                }
            });
        });
        function fill_the_table(){
            $('#loading_data_hadir').addClass("spinner-border");
            $('#tabel_data_kehadiran').empty();
            var formData = new FormData();
            var excelFile=document.getElementById("excel_filess");
            var myFile=excelFile.files[0];
            formData.append("excel_file",myFile);
            if(typeof myFile=='undefined'){
                notif({
                    msg: "<b>Error:</b> Pilih File terlebih dahulu!",
                    type: "error"
                });
                document.getElementById('tabel_data_kehadiran').style.height='1px';
                document.getElementById('presenceImportButton').style.visibility='hidden';
                document.getElementById('length').style.visibility='hidden';
                $('#loading_data_hadir').removeClass("spinner-border");
                $('#length').text('');
            }else{
                $.ajax({
                    type: 'POST',
                    url: '{{route('hris.mdabsenhadir.import_datahadir')}}',
                    contentType: false,
                    processData: false,
                    data: formData,
                    success:function(data){
                        console.log(data);
                        $('#length').text('LENGTH : '+data[1].jumlah_data);
                        jQuery.each(data, function(key,value){
                            $('#tabel_data_kehadiran').append("<tr>\
                                <td width='70px'>"+data[key].no+"</td>\
                                <td width='110px'>"+data[key].nik+"</td>\
                                <td width='58px'>"+data[key].enroll_id+"</td>\
                                <td width='100px'>"+data[key].tanggal_berjalan+"</td>\
                                <td width='250px'>"+data[key].employee_name+"</td>\
                                <td width='113px'>"+data[key].kerja_libur+"</td>\
                                <td align='center' width='122px'>"+data[key].jadwal_masuk_kerja+"</td>\
                                <td align='center' width='122px'>"+data[key].jadwal_pulang_kerja+"</td>\
                                <td align='center' width='122px'>"+data[key].absen_masuk_kerja+"</td>\
                                <td align='center' width='122px'>"+data[key].absen_pulang_kerja+"</td>\
                            </tr>");
                        });
                        document.getElementById('presenceImportButton').style.visibility='visible';
                        document.getElementById('tabel_data_kehadiran').style.height='400px';
                        $('#loading_data_hadir').removeClass("spinner-border");
                    },
                    error: function(res){
                        swal("", "IMPORT DATA KEHADIRAN GAGAL!", "error");
                        document.getElementById('tabel_data_kehadiran').style.height='1px';
                        document.getElementById('presenceImportButton').style.visibility='hidden';
                        document.getElementById('length').style.visibility='hidden';
                        $('#loading_data_hadir').removeClass("spinner-border");
                        $('#length').text('');
                    }
                });
            }
        }
        $('body').on('click', '#btn-refresh-data', function (event) {
            $("#datatable-ajax-crud").DataTable().ajax.reload();
            notif({
                msg: "<b>Success:</b> Data sudah di refresh.",
                type: "success"
            });
        });

        $('.fc-datepicker').datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'dd-mm-yy'
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

        function format(d) {
            // `d` is the original data object for the row
            var adaPerizinan = "";
            var adaGagalAbsen = "";
            var adaSPL = "";

            if ((d.nomor_absen_ijin !== "") && (d.nomor_absen_ijin !== null) && (d.status_absen !== "IKS") && (d.status_absen !== "M")) {
                adaPerizinan = '<div class="col-md-4">' +
                    '<div class="expanel expanel-light p-1 mt-1 mb-1">' +
                        '<div class="expanel-heading p-1">' +
                            ' <b>PERIZINAN (CUTI DAN IZIN)</b>' +
                        '</div>' +
                        '<div class="expanel-body collapse" id="collapse03' + d.uuid +'">' +
                            '<div class="row">' +
                                '<div class="col-md-12">' +
                                    '<table class="table table-sm w-100">' +
                                        '<tbody>' +
                                            '<tr><th>Nomor Form Perizinan</th></tr>' +
                                            '<tr><td>' + d.nomor_absen_ijin + '</td></tr>' +
                                            '<tr><th>Nama Perizinan</th></tr>' +
                                            '<tr><td>' + d.nama_absen_ijin + '</td></tr>' +
                                            '<tr><th>Kode Payroll</th></tr>' +
                                            '<tr><td>' + d.kode_ijin_payroll + '</td></tr>' +
                                            '<tr><th>Tanggal Izin Dari</th></tr>' +
                                            '<tr><td>' + d.tanggal_mulai_ijin + '</td></tr>' +
                                            '<tr><th>Tanggal Izin Sampai</th></tr>' +
                                            '<tr><td>' + d.tanggal_akhir_ijin + '</td></tr>' +
                                        '</tbody>' +
                                    '</table>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>';
            } else if ((d.nomor_absen_ijin !== "") && (d.nomor_absen_ijin !== null) && (d.status_absen == "IKS")) {
                adaPerizinan = '<div class="col-md-4">' +
                    '<div class="expanel expanel-light p-1 mt-1 mb-1">' +
                        '<div class="expanel-heading p-1">' +
                            ' <b>PERIZINAN (IKS)</b>' +
                        '</div>' +
                        '<div class="expanel-body collapse" id="collapse03' + d.uuid +'">' +
                            '<div class="row">' +
                                '<div class="col-md-12">' +
                                    '<table class="table table-sm w-100">' +
                                        '<tbody>' +
                                            '<tr><th>Nomor Form IKS</th></tr>' +
                                            '<tr><td>' + d.nomor_absen_ijin + '</td></tr>' +
                                            '<tr><th>Nama Perizinan</th></tr>' +
                                            '<tr><td>' + d.nama_absen_ijin + '</td></tr>' +
                                            '<tr><th>Kode Payroll</th></tr>' +
                                            '<tr><td>' + d.kode_ijin_payroll + '</td></tr>' +
                                            '<tr><th>Dari Pukul</th></tr>' +
                                            '<tr><td>' + d.permits_dari_pukul + '</td></tr>' +
                                            '<tr><th>Sampai Pukul</th></tr>' +
                                            '<tr><td>' + d.permits_sampai_pukul + '</td></tr>' +
                                            '<tr><th>Total Jam IKS</th></tr>' +
                                            '<tr><td>' + d.total_menit_permits + '</td></tr>' +
                                        '</tbody>' +
                                    '</table>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>';
            }

            if (d.nomor_form_perubahan_absen) {
                adaGagalAbsen = '<div class="col-md-4">' +
                    '<div class="expanel expanel-light p-1 mt-1 mb-1">' +
                        '<div class="expanel-heading p-1">' +
                            ' <b>GAGAL ABSEN</b>' +
                        '</div>' +
                        '<div class="expanel-body collapse" id="collapse03' + d.uuid +'">' +
                            '<div class="row">' +
                                '<div class="col-md-12">' +
                                    '<table class="table table-sm w-100">' +
                                        '<tr><th>Nomor Form Gagal Absen</th></tr>' +
                                        '<tr><td>' + d.nomor_form_perubahan_absen + '</td></tr>' +
                                        '<tr><th>Tanggal Izin Dari</th></tr>' +
                                        '<tr><td>' + d.tanggal_mulai_ijin + '</td></tr>' +
                                        '<tr><th>Tanggal Izin Sampai</th></tr>' +
                                        '<tr><td>' + d.tanggal_akhir_ijin + '</td></tr>' +
                                    '</table>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>';
            }

            if (d.nomor_form_lembur) {
                adaSPL = '<div class="col-md-4">' +
                    '<div class="expanel expanel-light p-1 mt-1 mb-1">' +
                        '<div class="expanel-heading p-1">' +
                            ' <b>LEMBUR (SPL)</b>' +
                        '</div>' +
                        '<div class="expanel-body collapse" id="collapse03' + d.uuid +'">' +
                            '<div class="row">' +
                                '<div class="col-md-12">' +
                                    '<table class="table table-sm w-100">' +
                                        '<tbody>' +
                                            '<tr><th>Nomor Form SPL</th></tr>' +
                                            '<tr><td>' + d.nomor_form_lembur + '</td></tr>' +
                                            '<tr><th>Waktu Lembur Dari</th></tr>' +
                                            '<tr><td>' + d.mulai_jam_lembur + '</td></tr>' +
                                            '<tr><th>Waktu Lembur Sampai</th></tr>' +
                                            '<tr><td>' + d.akhir_jam_lembur + '</td></tr>' +
                                            '<tr><th>Jumlah Jam Lembur</th></tr>' +
                                            '<tr><td>' + d.jumlah_jam_lembur + '</td></tr>' +
                                        '</tbody>' +
                                    '</table>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>';
            }
            return (

                '<div class="expanel expanel-success">' +
                    '<div class="expanel-heading p-1">' +
                        '<h4 class="expanel-title">' +
                            '<div class="btn-group">' +
                                '<button id="btn-add" class="btn btn-icon btn-sm text-sm btn-light pl-1 pr-1 pt-0 pb-0 m-0" type="button"><i class="fa fa-plus"></i> ADD PERIZINAN</button>' +
                            '</div>' +
                            '<div class="btn-group">' +
                                '<button class="btn btn-icon btn-sm text-sm btn-light pl-1 pr-1 pt-0 pb-0 m-0" onclick="edit_jadwal(this)" uuid="' + d.uuid +'"  type="button"><i class="fa fa-plus"></i>EDIT JADWAL</button>' +
                            '</div>' +
                        ' <b>DETAIL DATA KEHADIRAN KARYAWAN</b></h4>' +
                    '</div>' +
                    '<input type="hidden" id="uuid_detail" name="uuid_detail" value="' + d.uuid +'">' +
                    '<input type="hidden" id="nomor_absen_ijin" name="nomor_absen_ijin" value="' + d.nomor_absen_ijin +'">' +
                    '<div class="expanel-body">' +
                        '<div class="row">' +
                            '<div class="col-md-12">' +
                                '<div class="expanel expanel-light p-0 mt-1 mb-1">' +
                                    '<div class="expanel-heading p-1 clearfix">' +
                                        '<button class="btn btn-icon btn-sm btn-light pl-0 pt-0 pb-0 pr-0 mr-0" type="button" data-toggle="collapse" data-target="#collapse01' + d.uuid +'" aria-expanded="false" aria-controls="collapse01' + d.uuid +'"><i class="fa fa-info"></i></button>' +
                                        ' <b>DIVISI</b>' +
                                    '</div>' +
                                    '<div class="expanel-body collapse" id="collapse01' + d.uuid +'">' +
                                        '<div class="row">' +
                                            '<div class="col-md-4">' +
                                                '<div class="form-group">' +
                                                    '<label class="form-label">Divisi</label>' +
                                                    '<div>' + d.site_nirwana_name + '</div>' +
                                                '</div>' +
                                            '</div>' +
                                            '<div class="col-md-4">' +
                                                '<div class="form-group">' +
                                                    '<label class="form-label">Department</label>' +
                                                    '<div>' + d.department_name + '</div>' +
                                                '</div>' +
                                            '</div>' +
                                            '<div class="col-md-4">' +
                                                '<div class="form-group">' +
                                                    '<label class="form-label">Bagian</label>' +
                                                    '<div>' + d.sub_dept_name + '</div>' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-12">' +
                                '<div class="expanel expanel-light p-0 mt-1 mb-1">' +
                                    '<div class="expanel-heading p-1 clearfix">' +
                                        '<button class="btn btn-icon btn-sm btn-light pl-0 pt-0 pb-0 pr-0 mr-0" type="button" data-toggle="collapse" data-target="#collapse02' + d.uuid +'" aria-expanded="false" aria-controls="collapse02' + d.uuid +'"><i class="fa fa-info"></i></button>' +
                                        ' <b>STATUS KARYAWAN</b>' +
                                    '</div>' +
                                    '<div class="expanel-body collapse" id="collapse02' + d.uuid +'">' +
                                        '<div class="row">' +
                                            '<div class="col-md-4">' +
                                                '<div class="form-group">' +
                                                    '<label class="form-label">Aktif/Non Aktif</label>' +
                                                    '<div>' + d.work_status + '</div>' +
                                                '</div>' +
                                            '</div>' +
                                            '<div class="col-md-4">' +
                                                '<div class="form-group">' +
                                                    '<label class="form-label">Kontrak/Tetap</label>' +
                                                    '<div>' + d.employee_status + '</div>' +
                                                '</div>' +
                                            '</div>' +
                                            '<div class="col-md-4">' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-12">' +
                                '<div class="expanel expanel-light p-0 mt-1 mb-1">' +
                                    '<div class="expanel-heading p-1 clearfix">' +
                                        '<button class="btn btn-icon btn-sm btn-light pl-0 pt-0 pb-0 pr-0 mr-0" type="button" data-toggle="collapse" data-target="#collapse03' + d.uuid +'" aria-expanded="false" aria-controls="collapse03' + d.uuid +'"><i class="fa fa-info"></i></button>' +
                                        ' <b>GAGAL ABSEN, PERIZINAN DAN SPL</b>' +
                                    '</div>' +
                                    '<div class="expanel-body collapse" id="collapse03' + d.uuid +'">' +
                                        '<div class="row">' +
                                            '<div class="col-md-6">' +
                                                '<div class="form-group">' +
                                                    '<label class="form-label">Alasan Absen</label>' +
                                                    '<div class="border">' + d.absen_alasan + '</div>' +
                                                '</div>' +
                                            '</div>' +
                                            '<div class="col-md-6">' +
                                                '<div class="form-group">' +
                                                    '<label class="form-label">Catatan HRD</label>' +
                                                    '<div class="border">' + d.catatan_hrd + '</div>' +
                                                '</div>' +
                                            '</div>' +
                                            adaGagalAbsen + adaPerizinan + adaSPL +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>'
            );
        }

        $("#check-all-karyawan").attr('disabled','disabled');

        $(document).ready(function() {
            var start = moment().subtract(29, 'days');
            var end = moment();
            var htmlDateRange = '<span><i class="fa fa-calendar"></i> ' + start.format("D MMM YYYY").toUpperCase() + ' s/d ' + end.format("D MMM YYYY").toUpperCase() + '</span><i class="fa fa-angle-down ml-1"></i>'
            var daterange1 = start.format("YYYY-MM-DD") + " s/d " + end.format("YYYY-MM-DD");
            var dateUpdateKehadiran = end.format("DD-MM-YYYY");

            $('#daterange-btn1').html(htmlDateRange);
            $('#daterange1').val(daterange1);

            
            $("#data-absensi-karyawan").hide();
            $("#selectBagian").append(new Option("-- PILIH BAGIAN --", ""));
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

        $('body').on('change', '#selectDepartment', function () {
            var department_id = $('#selectDepartment').val();

            $("#selectBagian").empty();

            $("#selectBagian").append(new Option("-- PILIH BAGIAN --", ""));

            if(department_id){
                $.ajax({
                    type:"POST",
                    url: "{{route('hris.departmentall.getSelectSubDept')}}",
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
                                $("#selectBagian").append(new Option(resA[i].sub_dept_name, resA[i].sub_dept_name));
                            }
                        }
                    }
                });
            }
        });

        //Date range as a button
        $('#daterange-btn1').daterangepicker({
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
            $('#daterange-btn1').html('<span><i class="fa fa-calendar"></i> ' + start.format("D MMM YYYY").toUpperCase() + ' s/d ' + end.format("D MMM YYYY").toUpperCase() + '</span><i class="fa fa-angle-down ml-1"></i>');
            var daterange1 = start.format("YYYY-MM-DD") + " s/d " + end.format("YYYY-MM-DD");
            $('#daterange1').val(daterange1);
        })

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

        $('body').on('click', '#btn-caridata', function (event) {

            $("#data-absensi-karyawan").show('slow');

            $('#datatable-ajax-crud').DataTable().clear();
            $('#datatable-ajax-crud').DataTable().destroy();
            $('#datatable-ajax-crud').empty();

            //myHeadDataTable();

            var selectDepartment = $('#selectDepartment').val();
            var selectBagian = $('#selectBagian').val();
            var daterange1 = $('#daterange1').val();
            var status_staff = $('#status_staff').val();
            var searchData = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
            var siteNirwana = $('#siteNirwana').val();

            var table = $('#datatable-ajax-crud').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                destroy: true,
                lengthMenu: [
                    [ 100, 50, 25, 10 -1 ],
                    [ '100', '50', '25', '10', 'Show all' ]
                ],
                "language": {
                    processing: '<center><div class="dimmer active"><div class="lds-hourglass p-0 m-0"></div></div> Mohon untuk menunggu...</center> '},
                "ajax": {
                    "url": "{{ route('hris.mdabsenhadir.ajax_datahadir') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                    "data": {
                        selectDepartment:selectDepartment,
                        selectBagian:selectBagian,
                        daterange1:daterange1,
                        status_staff:status_staff,
                        searchData:searchData,
                        siteNirwana:siteNirwana,
                    },

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
                        title: 'TANGGAL<br>KEHADIRAN',
                        data: 'tanggal_berjalan',
                        name: 'tanggal_berjalan'
                    },
                    {
                        title: 'KODE HARI',
                        data: 'kode_hari',
                        name: 'kode_hari'
                    },
                    {
                        title: 'HARI',
                        data: 'nama_hari',
                        name: 'nama_hari'
                    },
                    {
                        title: 'NIK',
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        title: 'NO.<br>ABSEN',
                        data: 'enroll_id',
                        name: 'enroll_id'
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
                        title: 'STAFF/<br>NON STAFF',
                        data: 'status_staff',
                        name: 'status_staff'
                    },
                    {
                        title: 'KERJA/<br>LIBUR',
                        data: 'kerjalibur',
                        name: 'kerjalibur'
                    },
                    {
                        title: 'JADWAL<br>KERJA (IN)',
                        data: 'mulai_jam_kerja',
                        name: 'mulai_jam_kerja'
                    },
                    {
                        title: 'JADWAL<br>KERJA (OUT)',
                        data: 'akhir_jam_kerja',
                        name: 'akhir_jam_kerja'
                    },
                    {
                        title: 'ABSEN<br>(IN)',
                        data: 'absen_masuk_kerja',
                        name: 'absen_masuk_kerja'
                    },
                    {
                        title: 'ABSEN<br>(OUT)',
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
                        title: 'Jumlah<br>DTPC',
                        data: 'jumlah_menit_absen_dtpc',
                        name: 'jumlah_menit_absen_dtpc'
                    },
                    {
                        title: 'STATUS<br>ABSEN',
                        data: 'status_absen',
                        name: 'status_absen'
                    },
                    {
                        title: 'STATUS<br>PAYROLL',
                        data: 'kode_ijin_payroll',
                        name: 'kode_ijin_payroll'
                    },
                    {
                        title: 'STATUS<br>AKTIF',
                        data: 'status_aktif',
                        name: 'status_aktif'
                    },
                ],
                order: [],
                columnDefs: [
                    {
                        'visible': false,
                        'targets': [0,1,3]
                    },
                    {
                        orderable: false,
                        targets: [2,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]
                    },
                    {
                        className: "w-5 text-center text-nowrap",
                        targets: [2,4,5,9,10,11,12,13,14,19,20]
                    },
                    {
                        className: "w-5 text-right text-nowrap",
                        targets: [15,16,17]
                    },
                    {
                        className: "w-50 text-nowrap",
                        targets: [6,7,8]
                    }
                ],
                "createdRow": function (row, data, dataIndex) {
                    // if ((data['kode_hari'] == "5") || (data['kode_hari'] == "6") || (data['kerjalibur'] == "LIBUR")) {
                    if ((data['kerjalibur'] == "LIBUR")) {

                        $(row).css('background', 'yellow');
                    } else if ((data['status_absen'] == "M") || (data['status_absen'] == "TL")) {
                            $(row).css('background', 'red');
                     }
                    if (data['nomor_form_perubahan_absen'] !== null) {
                        $(row).css('background', 'lime');
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

             table.draw();

        });

        $('body').on('click', '#btn-exportexcel', function (event) {
            notif({
                msg: "<b>Info:</b> Data sedang di proses, mohon menunggu",
                type: "info"
            });
        });

        $('body').on('change', '#tanggal_perijinan', function (event) {
            var kode_absen_ijin = $('#kode_absen_ijin').val();
            var date_permohonan = $(this).val();
            $('#tanggal_mulai_ijin').val(date_permohonan);
            $('#tanggal_akhir_ijin').val(date_permohonan);
            var tglform = date_permohonan;
            var tgl = tglform.split('-');
            var thn = tgl[0].substring(4,2);
            var bln = tgl[1];

            if ((kode_absen_ijin == 'DL') || (kode_absen_ijin == 'I') || (kode_absen_ijin == 'S')) {
                $('#nomor_form_perizinan').val('FPI/HR/' + thn + bln + '/');
            } else {
                $('#nomor_form_perizinan').val('FPC/HR/' + thn + bln + '/');
            }

        });

        $('body').on('click', '#btn-add', function (event) {
            var uuid = $('#uuid_detail').val();
            var tanggal_perijinan = "";

            $.ajax({
                type:"POST",
                url: "{{route('hris.dataabsenperijinan.ajax_getkehadiran')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    uuid:uuid,
                },
                dataType: 'json',
                success: function(res){
                    $('#uuid').val(res['uuid']);

                    var nomor_form_perizinan = res['nomor_absen_ijin'];
                    var status_absen = res['status_absen'];

                    if (nomor_form_perizinan && status_absen!='TL' && status_absen!=null) {
                        notif({
                            msg: "<b>Warning:</b> Data sudah memiliki Nomor Form Perizinan.",
                            type: "warning"
                        });
                        return false;
                    } else {

                        var tanggal = res['tanggal_berjalan'];
                        var tanggal_resmi=tanggal.substr(8,2)+'-'+tanggal.substr(5,2)+'-'+tanggal.substr(0,4);
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
                            success: function(resA){
            
                                if(resA["ada"]) {
                                    notif({
                                        type: resA["status"],
                                        msg: resA["message"],
                                        position: "center",
                                        width: 800,
                                        height: 120,
                                        opacity: 0.6,
                                        autohide: false
                                    });
                                } else {

                                    $('#tanggal_perijinan').val(tanggal_resmi);
                                    $('#tanggal_mulai_ijin').val(tanggal_resmi);
                                    $('#tanggal_akhir_ijin').val(tanggal_resmi);
                                    $('#enroll_id').val(res['enroll_id']);
                                    $('#nik').val(res['nik']);
                                    $('#employee_name').val(res['employee_name']);
                                    $('#sub_dept_name').val(res['sub_dept_name']);
                                    $('#posisi_name').val(res['posisi_name']);
                                    $('#work_status').val(res['work_status']);
                                    $('#ajax-absenijin-model-add').modal('show');
                                    $('#ajaxAbsenIjinModel').html('<b>[ADD] ABSEN PERIZINAN & IKS</b>');
                                    var tglform = res['tanggal_berjalan'];
                                    var tgl = tglform.split('-');
                                    var thn = tgl[0].substring(4,2);
                                    var bln = tgl[1];
                                    $('#nomor_form_perizinan').val('FPI/HR/' + thn + bln + '/');
                                    
                                }

                            },
                            error: function(resA){
                                            
                            }
                        });                       

                    }
                },
                error: function(res){
                    $('#ajax-absenijin-model-add').modal('hide');
                }
            });
        });

        $('body').on('click', '#tab-iks', function (event) {
            var tglform = $('#tanggal_perijinan').val();
            var tgl = tglform.split('-');
            var thn = tgl[0].substring(4,2);
            var bln = tgl[1];
            $('#nomor_form_perizinan').val('FPI/HR/' + thn + bln + '/');
        });

        $('body').on('click', '#tab-izin', function (event) {
            $("#kode_absen_ijin").val(null).trigger("change");

            var tglform = $('#tanggal_perijinan').val();
            var tgl = tglform.split('-');
            var thn = tgl[0].substring(4,2);
            var bln = tgl[1];
            $('#nomor_form_perizinan').val('FPI/HR/' + thn + bln + '/');
        });


        $('body').on('change', '#kode_absen_ijin', function (event) {
            var tanggal_perijinan = $('#tanggal_perijinan').val();
            var kode_absen_ijin = $('#kode_absen_ijin').val();
            var tglform = tanggal_perijinan;
            var tgl = tglform.split('-');
            var thn = tgl[0].substring(4,2);
            var bln = tgl[1];

            if ((kode_absen_ijin == 'DL') || (kode_absen_ijin == 'I') || (kode_absen_ijin == 'S')) {
                $('#nomor_form_perizinan').val('FPI/HR/' + thn + bln + '/');
            } else {
                $('#nomor_form_perizinan').val('FPC/HR/' + thn + bln + '/');
            }

        });

        function diff_hours(dt2, dt1)
        {

         var diff =(dt2.getTime() - dt1.getTime()) / 1000;
         diff /= (60 * 60);
         return Math.abs(parseFloat(diff).toFixed(1));

        }

        function hitungtotaljam() {
            var tglform = $('#tanggal_perijinan').val();
            var tm1 = new Date(tglform + " " + $('#time_mulai_ijin').val());
            var tm2 = new Date(tglform + " " + $('#time_akhir_ijin').val());
            var total_time_ijin = diff_minutes(tm1, tm2);
            $('#total_time_ijin').val(total_time_ijin);
        };

        function diff_minutes(dt2, dt1)
        {

         var diff =(dt2.getTime() - dt1.getTime()) / 1000;
         diff /= 60;
         return Math.abs(Math.round(diff));

        }

        function defaultDate(s) {
            if(s) {
                var bits = s.split('-');
                var d = bits[2] + '-' + bits[1] + '-' + bits[0];
            }
            return d;
        }

        $('body').on('click', '#btn-updateKehadiran', function (event) {

            var tgl = $('#tanggal_mesin_absensi').val();
            
            // LAGI COBA TEST CLOSING PAYROLL
            $.ajax({
                type:"POST",
                url: "{{route('hris.dataclosingpayroll.ajax_getclosing_datahadir')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    tanggal:tgl,
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
                        var enroll_id=$('select[name="selectEmployee[]"]').val();
                        var tanggal_mesin_absensi = $('#tanggal_mesin_absensi').val();
                        var range_tanggal = tanggal_mesin_absensi.split(' - ');
                        var tgl_absensi_satu = new Date(range_tanggal[0]);
                        var tgl_absensi_dua = new Date(range_tanggal[1]);
                        var days = ['MINGGU', 'SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU'];
                        var months = ['JAN','FEB','MARET','APRIL','MAY','JUNI','JULI','AGU','SEP','OKT','NOV','DES'];

                        tgl_absensi_satu = days[tgl_absensi_satu.getDay()] + ", " + tgl_absensi_satu.getDate() + " " + months[tgl_absensi_satu.getMonth()] + " " + tgl_absensi_satu.getFullYear();
                        tgl_absensi_dua = days[tgl_absensi_dua.getDay()] + ", " + tgl_absensi_dua.getDate() + " " + months[tgl_absensi_dua.getMonth()] + " " + tgl_absensi_dua.getFullYear();
                        text  = "DATA KEHADIRAN HARI " + tgl_absensi_satu + " S/D "+tgl_absensi_dua+" AKAN DI REPLACE OLEH DATA TERBARU DARI MESIN ABSENSI !!!";
                        message = "APAKAH ANDA YAKIN ?";
                        type = "warning";
                        swal({
                            title: message,
                            text: text,
                            type: type,
                            showCancelButton: true,
                            confirmButtonText: 'UPDATE',
                            cancelButtonText: 'TUTUP'
                        },function(isConfirm){
                            if(isConfirm) {
                                notif({
                                    msg: "<b>Info:</b> Data sedang di PROSES, mohon di tunggu.",
                                    type: "info"
                                });

                                $('#btn-updateKehadiran').addClass("btn-loading");
                                $("#btn-updateKehadiran").html('Please wait...');
                                $("#btn-updateKehadiran").attr("disabled", true);

                                $.ajax({
                                    type:"POST",
                                    url: "{{route('hris.mdabsenhadir.download_mesin_kehadiran')}}",
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                    data: {
                                        tanggal_mesin_absensi:tanggal_mesin_absensi,
                                        enroll_id:enroll_id
                                    },
                                    success: function(res){
                                    console.log(res);
                                        swal({
                                            title: "Update Absensi",
                                            text: "Data berhasil di update",
                                            icon: "success",
                                        });
                                        $('#btn-updateKehadiran').removeClass("btn-loading");
                                        $("#btn-updateKehadiran").html('<span><i class="fa fa-download"></i></span> UPDATE ABSENSI');
                                        $("#btn-updateKehadiran").attr("disabled", false);
                                        $("#btn-caridata").click();
                                    },
                                    error: function(res){
                                        swal({
                                            title: "Update Absensi",
                                            text: "Data gagal di update",
                                            icon: "danger",
                                        });
                                        $('#btn-updateKehadiran').removeClass("btn-loading");
                                        $("#btn-updateKehadiran").attr("disabled", false);
                                        $("#btn-updateKehadiran").html('<span><i class="fa fa-download"></i></span> UPDATE ABSENSI');
                                    }
                                });
                            }
                        });
                    }

                },
                error: function(res){
                                
                }
            });           
        });

        $('body').on('click', '#btn-save-izin', function (event) {
            var uuid = $('#uuid').val();
            var tanggal_perizinan = $('#tanggal_perijinan').val();
            var nomor_form_perizinan = $('#nomor_form_perizinan').val();
            var enroll_id = $('#enroll_id').val();
            var nik = $('#nik').val();
            var employee_name = $('#employee_name').val();
            var kode_absen_ijin = $('#kode_absen_ijin').val();
            var absen_alasan = $('#absen_alasan_izin').val();
            var tanggal_mulai_ijin = $('#tanggal_mulai_ijin').val();
            var tanggal_akhir_ijin = $('#tanggal_akhir_ijin').val();

            $('#btn-save').addClass("btn-loading");
            $("#btn-save").html('Please wait...');
            $("#btn-save").attr("disabled", true);
            $('#progress-show-1').show();
            $('#progress-hide-1').hide();

            $.ajax({
                type:"POST",
                url: "{{route('hris.dataabsenperijinan.create_perizinan')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    uuid:uuid,
                    tanggal_perizinan:tanggal_perizinan,
                    nomor_form_perizinan:nomor_form_perizinan,
                    enroll_id:enroll_id,
                    nik:nik,
                    employee_name:employee_name,
                    kode_absen_ijin:kode_absen_ijin,
                    absen_alasan:absen_alasan,
                    tanggal_mulai_ijin:tanggal_mulai_ijin,
                    tanggal_akhir_ijin:tanggal_akhir_ijin,
                },
                dataType: 'json',
                success: function(res){
                    console.log(res);
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

            $('#progress-show-1').hide();
            $('#progress-hide-1').show();
            $('#btn-save').removeClass("btn-loading");
            $('#ajax-absenijin-model-add').modal('hide');
            $("#btn-save").html('<span><i class="fa fa-save"></i></span> Simpan');
            $("#datatable-ajax-crud").DataTable().ajax.reload();

        });

        $('body').on('click', '#btn-save-iks', function (event) {
            var uuid = $('#uuid').val();
            var tanggal_perizinan = $('#tanggal_perijinan').val();
            var nomor_form_perizinan = $('#nomor_form_perizinan').val();
            var enroll_id = $('#enroll_id').val();
            var nik = $('#nik').val();
            var employee_name = $('#employee_name').val();
            var kode_absen_ijin = $('#kode_absen_ijin_iks').val();
            var absen_alasan = $('#absen_alasan_iks').val();
            var time_mulai_ijin = $('#time_mulai_ijin').val();
            var time_akhir_ijin = $('#time_akhir_ijin').val();
            var total_time_ijin = $('#total_time_ijin').val();

            $('#btn-save').addClass("btn-loading");
            $("#btn-save").html('Please wait...');
            $("#btn-save").attr("disabled", true);
            $('#progress-show-1').show();
            $('#progress-hide-1').hide();

            $.ajax({
                type:"POST",
                url: "{{route('hris.dataabsenperijinan.create_iks')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    uuid:uuid,
                    tanggal_perizinan:tanggal_perizinan,
                    nomor_form_perizinan:nomor_form_perizinan,
                    enroll_id:enroll_id,
                    nik:nik,
                    employee_name:employee_name,
                    kode_absen_ijin:kode_absen_ijin,
                    absen_alasan:absen_alasan,
                    time_mulai_ijin:time_mulai_ijin,
                    time_akhir_ijin:time_akhir_ijin,
                    total_time_ijin:total_time_ijin,
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

            $('#progress-show-1').hide();
            $('#progress-hide-1').show();
            $('#btn-save').removeClass("btn-loading");
            $('#ajax-absenijin-model-add').modal('hide');
            $("#btn-save").html('<span><i class="fa fa-save"></i></span> Simpan');
            $("#datatable-ajax-crud").DataTable().ajax.reload();
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
        $('#time_mulai_ijin').keyup(function(){
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
        $('#time_mulai_ijin').blur(function(){
            var val = this.value;
            val = (/^(([01][0-9]|2[0-3])h)|(([01][0-9]|2[0-3]):[0-5][0-9])$/.test(val) ? val : "");
            this.value = val;
        });

        // Apply input rules as the user types or pastes input
        $('#time_akhir_ijin').keyup(function(){
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
        $('#time_akhir_ijin').blur(function(){
            var val = this.value;
            val = (/^(([01][0-9]|2[0-3])h)|(([01][0-9]|2[0-3]):[0-5][0-9])$/.test(val) ? val : "");
            this.value = val;
        });

    </script>

    <script>
        $('.data_range').daterangepicker({
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
            // startDate: moment().startOf('month'),
            // endDate: moment().endOf('month')
            }, function(start, end) {
                $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        })

        jQuery(document).ready(function($) {
            const BtnUpdateLintasHari = document.getElementsByClassName('BtnUpdateLintasHari')[0];
            const EmployeeID = document.getElementsByClassName("EmployeeID");

            BtnUpdateLintasHari.addEventListener('click', function(event) {
                // let tmp = EmployeeID[0].value;
                
                // if (tmp == ''||tmp==null) {
                //     swal({
                //         title: "Harap Pilih Karyawan",
                //         text: "Data karyawan tidak boleh kosong",
                //         icon: "warning",
                //         button : false,
                //     });
                // } else{
                    event.preventDefault();
                    const submited =document.getElementsByTagName('form')[0];
                    swal({
                        title: 'Apakah Anda Yakin ?',
                        text: 'Update Absen Lintas Hari',
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'UPDATE',
                        cancelButtonText: 'TUTUP'
                    },function(isConfirm){
                        if(isConfirm) {
                            $('#BtnUpdateLintasHari').addClass("btn-loading");
                            $("#BtnUpdateLintasHari").html('Please wait...');
                            $("#BtnUpdateLintasHari").attr("disabled", true);

                            $.ajax({
                                data: $('#form_update_lintashari').serialize(),
                                url: '{{ route("hris.mdabsenhadir.download_mesin_kehadiran_lintas") }}',           
                                type: "post",
                                // dataType: 'json',           
                                success: function (data) {
                                    
                                    console.log(data);
                                        notif({
                                            msg: "<b>Info:</b> Data berhasil di UPDATE.",
                                            type: "info"
                                        });

                                    $('#BtnUpdateLintasHari').removeClass("btn-loading");
                                    $("#BtnUpdateLintasHari").html('<span><i class="fa fa-download"></i></span> UPDATE ABSENSI');
                                    $("#BtnUpdateLintasHari").attr("disabled", false);
                                    $("#btn-caridata").click();
                                   
                                },
                                error: function (xhr, status, error) {
                                    notif({
                                        msg: "<b>Error:</b> Oops data gagal di UPDATE.",
                                        type: "error"
                                    });

                                    $('#BtnUpdateLintasHari').removeClass("btn-loading");
                                    $("#BtnUpdateLintasHari").attr("disabled", false);
                                    $("#BtnUpdateLintasHari").html('<span><i class="fa fa-download"></i></span> UPDATE ABSENSI');
                                }
                            }); 
                        }
                    });    
                // }
            });
        });
    </script>

    <script>

        function edit_jadwal(element) {
            event.preventDefault();
            var uuid = element.getAttribute('uuid');

            $.ajax({
                type:"POST",
                url: "{{route('hris.dataabsenperijinan.ajax_getkehadiran')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    uuid:uuid,
                },
                dataType: 'json',
                success: function(res){
                    $('#uuid_edit').val(res['uuid']);
                    $('#tanggal_berjalan_edit').val(res['tanggal_berjalan']);
                    $('#enroll_id_edit').val(res['enroll_id']);
                    $('#nik_edit').val(res['nik']);
                    $('#employee_name_edit').val(res['employee_name']);
                    $('#sub_dept_name_edit').val(res['sub_dept_name']);
                    $('#posisi_name_edit').val(res['posisi_name']);
                    $('#work_status_edit').val(res['work_status']);
                    $('#jadwal_in_edit').val(res['mulai_jam_kerja']);
                    $('#jadwal_out_edit').val(res['akhir_jam_kerja']);
                    $('#absen_in_edit').val(res['absen_masuk_kerja']);
                    $('#absen_out_edit').val(res['absen_pulang_kerja']);
                    $('#status_absen_edit').val(res['status_absen']);
                    $('#modal_edit_jadwal').modal('show');
                    
                },
                error: function(res){
                    $('#ajax-absenijin-model-add').modal('hide');
                }
            });
        }

        $('#form_edit_jadwal').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{route('hris.mdabsenhadir.edit_jadwal')}}",
                data: $(this).serialize(),
                success: function(response)
                {
                    notif({
                        msg: "<b>Info:</b> Data berhasil di simpan.",
                        type: "info"
                    });  
                    $('#modal_edit_jadwal').modal('hide');

                    $("#btn-caridata").click();

                }
            });
        });
    </script>

@endsection
