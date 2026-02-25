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

<!-- Tabs css-->
<link href="{{URL::asset('assets/plugins/tabs/tabs-style.css')}}" rel="stylesheet" />

@stop
@section('mainarea')
<!-- page-header -->
<div class="page-header p-2 shadow">

    <ol class="breadcrumb breadcrumb-arrow mt-0">
        <li><a href="#">Master Data</a></li>
        <li class="active"><span>Karyawan</span></li>
    </ol>
    <div class="ml-auto">
        <div class="input-group">

            @php
            if (($loggedAdmin->role_user == "admin") || ($loggedAdmin->role_user == "superadmin") ||
            ($loggedAdmin->role_user == "absensi")){
            @endphp

            <div class="text-white">
                @if($loggedAdmin->email=='mega@ptnag.com' || $loggedAdmin->email=='rudy@ptnag.com' ||
                $loggedAdmin->email=='ersa@ptnag.com' || $loggedAdmin->email=='dev_hris')
                <button type="button" class="btn btn-icon btn-primary text-white p-2 mr-1" onclick="synchEmployees()"
                    id="synchemployee"><i class="fa fa-file-excel-o"></i> Synch Karyawan</button>
                <button type="button" class="btn btn-icon btn-success text-white p-2 mr-1"
                    data-target="#import_employees" data-toggle="modal" title=""
                    data-original-title="Import Data Dari File Excel"><i class="fa fa-file-excel-o"></i> Import
                    Karyawan</button>
                @endif
                <a href="{{route('hris.employeeatr.format')}}" id="btn-examimport"
                    class="btn btn-icon btn-orange text-white p-2 mr-1" data-toggle="tooltip" title=""
                    data-original-title="Format File Excel"><i class="fa fa-file-excel-o mr-1"></i>Format File </a>
                <button type="button" id="btn-import" class="btn btn-icon btn-warning text-white p-2 mr-1"
                    data-target="#import_grade" data-toggle="modal" title=""
                    data-original-title="Import Data Dari File Excel"><i class="fa fa-file-excel-o"></i> Import
                    Data</button>
            </div>
            <!-- modal -->

            <form id="upload" name="custForm" action="{{route ('hris.employeeatr.import.grading')}}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="modal fade" id="import_grade" role="dialog" data-backdrop="static" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary p-2">
                                        <h4 class="modal-title pl-2 font-weight-bold">Import Grading & BPJS</h4>
                                        <button type="button" id="btn-close" class="close text-white ml-1"
                                            data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title=""
                                            data-placement="bottom" data-original-title="Tutup Dialog">
                                            <i class="fa fa-remove"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">file import : </label>
                                                    <input type="file" class='form-control' name='excel_file'
                                                        accept=".xlsx, .xls, .csv" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-primary p-1">
                                        <div class="btn-list">
                                            <button type="submit" id="export_grade_bpjs"
                                                class="btn btn-secondary btn-app">Simpan</button>
                                            <!-- <button type="button" id="" class="btn btn-warning btn-app" data-dismiss="modal">Tutup</button> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal fade" id="import_employee" role="dialog" data-backdrop="static" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="modal-content">
                                <form method="POST" action="{{route('hris.employeeatr.import_employee_excel')}}"
                                    enctype='multipart/form-data'>
                                    {{csrf_field()}}
                                    <div class="modal-header bg-primary p-2">
                                        <h4 class="modal-title pl-2 font-weight-bold">Import Employee</h4>
                                        <button type="button" id="btn-close" class="close text-white ml-1"
                                            data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title=""
                                            data-placement="bottom" data-original-title="Tutup Dialog">
                                            <i class="fa fa-remove"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">file import : </label>
                                                    <input class="form-control" name="excel_file" type="file"
                                                        accept=".xlsx, .xls, .csv" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-primary p-1">
                                        <div class="btn-list">
                                            <button type="submit" class="btn btn-light">Import</button>
                                            <!-- <button type="button" id="" class="btn btn-warning btn-app" data-dismiss="modal">Tutup</button> -->
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="uploadFoto" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document" style="max-width: 430px">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="modal-content">
                                <div class="modal-header bg-primary" style="font-weight:bold;font-size:13pt">
                                    UPLOAD PHOTO
                                </div>
                                <div class="modal-body px-3 pb-0">
                                    <div class="row">
                                        <div class="col">
                                            <input type="file" id="choose_photo" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row pb-2 justify-content-center">
                                        <div class="col-10 text-center" id="image_preview">

                                        </div>
                                    </div>
                                    <div class="row py-2 justify-content-center">
                                        <button class="btn btn-primary py-0" id="upload_image">UPLOAD</button>
                                    </div>
                                </div>
                                <div class="modal-footer py-2 bg-primary">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="import_employees" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document" style="max-width: 1330px">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="modal-content">
                                <div class="modal-header bg-primary p-2">
                                    <h4 class="modal-title pl-2 font-weight-bold">Import Employee</h4>
                                    <button type="button" id="btn-close" class="close text-white ml-1"
                                        data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title=""
                                        data-placement="bottom" data-original-title="Tutup Dialog">
                                        <i class="fa fa-remove"></i>
                                    </button>
                                </div>
                                <div class="modal-body p-5">
                                    <div class="row">
                                        <div class="col-12">
                                            <input class="form-control" ref="excel_filess" name="excel_filess"
                                                id="excel_filess" type="file" accept=".xlsx, .xls, .csv" required>
                                        </div>
                                    </div>
                                    <div class="row pt-2" id="row_tabler">
                                        <div class="col-12">
                                            <table class="table table-bordered" style="overflow-x:auto">
                                                <thead id="head_karyawan">
                                                    <tr>
                                                        <td width="68px"
                                                            style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">
                                                            ID</td>
                                                        <td width="120px"
                                                            style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">
                                                            NIK</td>
                                                        <td width="250px"
                                                            style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">
                                                            NAMA KARYAWAN</td>
                                                        <td width="150px"
                                                            style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">
                                                            JENIS KELAMIN</td>
                                                        <td width="150px"
                                                            style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">
                                                            JABATAN</td>
                                                        <td width="200px"
                                                            style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">
                                                            DEPARTMENT</td>
                                                        <td width="200px"
                                                            style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">
                                                            BAGIAN</td>
                                                        <td width="120px"
                                                            style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">
                                                            AKTIF/NON</td>
                                                    </tr>
                                                </thead>
                                                <tbody id="tabel_karyawan">
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-12 text-center">
                                            <div id="loading_karyawan">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="row_error_handle" style="visibility: hidden">
                                        <div class="col-4">
                                            <table>
                                                <tr>
                                                    <td width="70" style="font-weight: bold">Length : </td>
                                                    <td id="length_karyawan"></td>
                                                    <td width="50"></td>
                                                    <td width="100" style="font-weight: bold">Correct Data :</td>
                                                    <td id="correct_data"></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-4 text-center">
                                            <button type="button" id="employeeImportButton" class="btn btn-primary py-1"
                                                style="visibility: hidden"><i class="fa fa-upload"
                                                    aria-hidden="true"></i> IMPORT</button>
                                        </div>
                                        <div class="col-4 text-right">
                                            <div class="row">
                                                <div class="col-6">
                                                </div>
                                                <div class="col-6 pl-6">
                                                    <table>
                                                        <tr>
                                                            <td valign="top" width="150px"
                                                                style="background-color: red;font-size:9pt;font-weight:bold; color:white;padding-left:4px;border:1px solid grey">
                                                                <i class="fa fa-times"></i>&nbsp;DEPARTMENT/SUB
                                                                &nbsp;&nbsp;
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top"
                                                                style="background-color:lightblue;black;font-size:9pt;font-weight:bold;padding-left:4px;border:1px solid grey">
                                                                <i class="fa fa-check"></i>&nbsp;NEW EMPLOYEE
                                                                &nbsp;&nbsp;
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top"
                                                                style="background-color:rgb(255, 255, 255);font-size:9pt;font-weight:bold;padding-left:4px;border:1px solid grey">
                                                                <i class="fa fa-check"></i>&nbsp;UPDATE
                                                                EMPLOYEE&nbsp;&nbsp;
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer py-2 bg-primary"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="select_employee" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document" style="max-width: 1330px">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h4 class="modal-title font-weight-bold">Select Employee</h4>
                                    <button type="button" id="btn-close" class="close text-white ml-1"
                                        data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title=""
                                        data-placement="bottom" data-original-title="Tutup Dialog">
                                        <i class="fa fa-remove"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row" id="row_employee">
                                        <div class="col">
                                            <table class="table table-bordered" style="overflow-x:auto">
                                                <thead id="head_employees">
                                                    <tr>
                                                        <td width="10px"
                                                            style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">
                                                            <input type="checkbox" style="width: 20px;height:20px"
                                                                onClick="toggle(this)">
                                                        </td>
                                                        <td width="68px"
                                                            style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">
                                                            ID</td>
                                                        <td width="120px"
                                                            style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">
                                                            NIK</td>
                                                        <td width="200px"
                                                            style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">
                                                            NAMA KARYAWAN</td>
                                                        <td width="150px"
                                                            style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">
                                                            JENIS KELAMIN</td>
                                                        <td width="150px"
                                                            style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">
                                                            JABATAN</td>
                                                        <td width="200px"
                                                            style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">
                                                            DEPARTMENT</td>
                                                        <td width="200px"
                                                            style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">
                                                            BAGIAN</td>
                                                        <td width="120px"
                                                            style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">
                                                            AKTIF/NON</td>
                                                    </tr>
                                                </thead>
                                                <tbody id="selected_employees">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        // <div class="col text-center">
                                            // <a href="#" id="btndownloadselectedid" class="py-1"
                                                style="background-color: #f23535;color:white;padding-left:10px;padding-right:10px; border-radius:3px"
                                                data-toggle="tooltip" title="" data-placement="bottom"
                                                data-original-title="Download Id Card"><i
                                                    class="fa fa-download mr-1"></i>ID Card</a>
                                            // </div>
                                    </div>
                                </div>
                                <div class="modal-footer py-3 bg-primary">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end modal -->

            <!-- BEGIN FORM-->
            {!! Form::open(['route' => 'hris.employeeatr.ajax_exportexcel', 'id' => 'formExport', 'name' =>
            'formExport','method'=>'post']) !!}

            @csrf
            <button type="submit" id="btn-exportexcel" class="btn btn-icon btn-primary text-white p-2 mr-1"
                data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Export Data to Excel"><i
                    class="fa fa-file-excel-o"></i></button>
            {{-- </form> --}}
            {!! Form::close() !!}
            <!-- END FORM-->
            @php
            }
            @endphp

            <a href="#" id="btn-refresh-page" class="btn btn-secondary p-0 mr-0 text-white btn-icon"
                style="display:flex; justify-content:center; align-items:center;" data-toggle="tooltip" title=""
                data-placement="bottom" data-original-title="Refresh Page">
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
    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
        <!-- Begin Form Edit Absen Karyawan -->

        <div id="data-gagal-absen" class="card shadow" id="datatable-data-karyawan">
            <div class="card-header bg-primary p-3">
                <div class="card-title">CARI DATA</div>
                <div class="card-options ">
                    <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i
                            class="fe fe-chevron-up text-white"></i></a>
                </div>
            </div>
            <div class="card-body m-0 pt-3">
                <div class="row">
                    <div class="col-4">
                        <label class="form-label text-primary pt-1">Department</label>
                    </div>
                    <div class="col-6">
                        <select id="selectDepartment" name="selectDepartment" class="form-control form-control-sm">
                            <option value="">Filter Department</option>
                            @foreach ($department as $r_department)
                            <option value="{{$r_department->department_name}}">{{$r_department->department_name}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row pt-2">
                    <div class="col-4">
                        <label class="form-label text-primary pt-1">Sub Department</label>
                    </div>
                    <div class="col-6">
                        <select class="form-control form-control-sm" id="pilih_department">
                        </select>
                    </div>
                </div>
                <div class="row pt-2">
                    <div class="col-4">
                        <label class="form-label text-primary pt-1">Opsi Print</label>
                    </div>
                    <div class="col-6">
                        <select class="form-control form-control-sm" id="print_by">
                            <option value="department">Department</option>
                            <option value="bagian">Bagian</option>
                        </select>
                    </div>
                </div>
                <div class="row py-2 mt-3 px-0">
                    <div class="col-4">
                    </div>
                    <div class="col-6">
                        {{-- <a href="#" id="btndownloadiddept" class="py-1"
                            style="background-color: #f23535;color:white;padding-left:10px;padding-right:10px; border-radius:3px"
                            data-toggle="tooltip" title="" data-placement="bottom"
                            data-original-title="Download Id Card"><i class="fa fa-download mr-1"></i>ID Card</a> --}}
                        {{-- <a href="#" id="btnselectemployee" class="py-1"
                            style="background-color: #15b22d;color:white;padding-left:10px;padding-right:10px; border-radius:3px"
                            data-target="#select_employee" data-toggle="modal" title="" data-placement="bottom"
                            data-original-title="Download Id Card"><i class="fa fa-check-circle mr-1"></i>Pilih
                            Karyawan</a> --}}
                        <a href="#" id="btndownloadselectedids" class="py-1"
                            style="background-color: #f23535;color:white;padding-left:10px;padding-right:10px; border-radius:3px"
                            data-toggle="tooltip" title="" data-placement="bottom"
                            data-original-title="Download Id Card"><i class="fa fa-download mr-1"></i>ID Card</a>
                        {{-- <a href="#" id="btnselectemployee" class="py-1"
                            style="background-color: #15b22d;color:white;padding-left:10px;padding-right:10px; border-radius:3px"
                            data-target="#select_employee" data-toggle="modal" title="" data-placement="bottom"
                            data-original-title="Download Id Card" onclick="actionCheckedEmployee()"><i
                                class="fa fa-check-circle mr-1"></i>Pilih Karyawan</a> --}}
                    </div>
                </div>
                <table id="datatable-ajax-crud" class="table table-sm table-striped table-hover table-bordered w-100">
                    <thead>
                        <tr class="text-center">
                            <th scope="col">
                                <input type="checkbox" id="checkAllEmployee" onchange="actionCheckAllEmployee(this)">
                            </th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                {{-- <p class="my-3">
                    Selected : <span id="checked-employee-count" class="fw-bold">0</span>
                </p> --}}
            </div>
            <div class="card-footer bg-primary br-br-7 br-bl-7">
                <div class="text-white"></div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8">
        <!-- Begin Form Edit Absen Karyawan -->
        <div id="data-gagal-absen" class="card shadow" id="datatable-data-karyawan">
            <div class="card-header p-0">
                @if (Session::has('status'))
                <div class="alert alert-success">
                    {{ Session::get('status') }}
                    <button type="button" style="background-color: transparent; border:none;" data-dismiss="alert"
                        aria-label="Close">
                        <i class="fa fa-remove"></i>
                    </button>
                </div>
                @endif
            </div>
            <div class="card-header bg-primary p-2">
                <div class="card-title">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle pl-1 pt-0 pb-0 pr-1 mr-1 text-sm"
                            data-toggle="dropdown">
                            <i class="fa fa-navicon"></i>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="javascript:void(0)" id="btn-print"><i class="fa fa-print"></i> Print</a></li>
                            <li><a href="javascript:void(0)" id="btn-add"><i class="fa fa-plus"></i> Tambah</a></li>
                            <li><a href="javascript:void(0)" id="btn-edit"><i class="fa fa-edit"></i> Edit</a></li>
                            <!-- <li><a href="javascript:void(0)" id="btn-remove"><i class="fa fa-trash"></i> Hapus</a></li> -->
                        </ul>
                    </div>
                    DATA KARYAWAN
                </div>



                <div class="card-options ">
                    <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i
                            class="fe fe-chevron-up text-white"></i></a>
                </div>
            </div>
            <div class="card-body">
                <!-- BEGIN FORM-->
                {!! Form::open(['route' => 'hris.employeeatr.replace', 'id' => 'form1', 'name' => 'form1',
                'method'=>'post']) !!}
                @csrf

                <div class="panel panel-primary">
                    <div class="tab_wrapper first_tab">
                        <ul class="tab_list">
                            <li class="text-sm">Biodata</li>
                            <li class="text-sm">Data HRD</li>
                            <li class="text-sm">Riwayat</li>
                            <li class="text-sm">Kerabat</li>
                            <li class="text-sm">Lainnya</li>
                            <li class="text-sm">Kontrak</li>
                        </ul>
                        <div class="content_wrapper">
                            <div class="tab_content active">
                                <table class="table table-striped table-sm mb-0">
                                    <tr>
                                        <td rowspan=4>
                                            <div class="col-xl-12 col-lg-12 col-md-12 text-center">
                                                <div class="userpic" id="profile_photo">

                                                </div>
                                                <div class="form-group text-center">
                                                    <a href="#" id="btnupload" class="btn btn-primary mt-3 p-1 text-sm"
                                                        data-placement="bottom" data-original-title="Upload Foto"><i
                                                            class="fa fa-upload mr-1"></i>Upload</a>
                                                    <a href="#" id="btndownloadFoto"
                                                        class="btn btn-secondary mt-3 p-1 text-sm" data-toggle="tooltip"
                                                        title="" data-placement="bottom"
                                                        data-original-title="Download Foto"><i
                                                            class="fa fa-download mr-1"></i>Download</a>
                                                    <a href="#" id="btndownloadid"
                                                        class="btn btn-danger mt-3 p-1 text-sm" data-toggle="tooltip"
                                                        title="" data-placement="bottom"
                                                        data-original-title="Download Id Card"><i
                                                            class="fa fa-download mr-1"></i>ID Card</a>
                                                </div>
                                            </div>
                                        </td>
                                        <th>Nama Lengkap</th>
                                        <th>Jenis Kelamin</th>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control" id="employee_name"></td>
                                        <td>
                                            <select id="jenis_kelamin" class="form-control">
                                                <option value="">-- PILIH JENIS KELAMIN --</option>
                                                <option value="LAKI-LAKI">LAKI-LAKI</option>
                                                <option value="PEREMPUAN">PEREMPUAN</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tempat</th>
                                        <th>Tanggal Lahir</th>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control" id="tempat_lahir"></td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                    </div>
                                                </div><input id="tanggal_lahir" class="form-control fc-datepicker"
                                                    placeholder="DD-MM-YYYY" type="text">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="w-35">Golongan Darah</th>
                                        <th class="w-35">Email</th>
                                        <th class="w-35">Nomor Telepon</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select id="golongan_darah" class="form-control">
                                                <option value="">-- PILIH GOLONGAN DARAH --</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="AB">AB</option>
                                                <option value="O">O</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control" id="email"></td>
                                        <td><input type="text" class="form-control" id="nomor_tlpn"></td>
                                    </tr>
                                    <tr>
                                        <th>Agama</th>
                                        <th>Status Pernikahan</th>
                                        <th>NPWP</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select id="agama" class="form-control">
                                                <option value="">-- PILIH AGAMA --</option>
                                                <option value="ISLAM">ISLAM</option>
                                                <option value="KRISTEN">KRISTEN</option>
                                                <option value="PROTESTAN">KRISTEN PROTESTAN</option>
                                                <option value="KATOLIK">KRISTEN KATOLIK</option>
                                                <option value="HINDU">HINDU</option>
                                                <option value="BUDHA">BUDHA</option>
                                                <option value="KONGHUCU">KONGHUCU</option>
                                                <option value="LAINNYA">LAINNYA</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select id="status_kawin" class="form-control">
                                                <option value="">-- PILIH KAWIN --</option>
                                                <option value="TIDAK KAWIN">TIDAK KAWIN</option>
                                                <option value="KAWIN">KAWIN</option>
                                                <option value="CERAI HIDUP">CERAI HIDUP</option>
                                                <option value="CERAI MATI">CERAI MATI</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control" id="npwp"></td>
                                    </tr>
                                    <tr>
                                        <th>Nomor KTP</th>
                                        <th>Nomor KK</th>
                                        <th>PTKP</th>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control" id="nomor_ktp"></td>
                                        <td><input type="text" class="form-control" id="nomor_kk"></td>
                                        <td>
                                            <select id="ptkp" class="form-control">
                                                <option value="">-- PILIH PTKP --</option>
                                                <optgroup label="K/">
                                                    <option value="K/0">K/0</option>
                                                    <option value="K/1">K/1</option>
                                                    <option value="K/2">K/2</option>
                                                    <option value="K/3">K/3</option>
                                                    <option value="K/4">K/4</option>
                                                    <option value="K/5">K/5</option>
                                                </optgroup>
                                                <optgroup label="TK/">
                                                    <option value="TK/0">TK/0</option>
                                                    <option value="TK/1">TK/1</option>
                                                    <option value="TK/2">TK/2</option>
                                                    <option value="TK/3">TK/3</option>
                                                    <option value="TK/4">TK/4</option>
                                                    <option value="TK/5">TK/5</option>
                                                </optgroup>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Jenjang Pend. Terakhir</th>
                                        <th colspan=2>Jurusan Pend. Terakhir</th>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control" id="pendidikan_terakhir"></td>
                                        <td colspan=2><input type="text" class="form-control" id="jurusan_pendidikan">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Bank</th>
                                        <th>No Rekening</th>
                                        <th>Nama Ibu Kandung</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select id="nama_bank" class="form-control">
                                                <option value="">-- PILIH NAMA BANK --</option>
                                                <option value="TUNAI" selected>TUNAI</option>
                                                <option value="BCA">BCA</option>
                                                <option value="BNI">BNI</option>
                                                <option value="CIMB NIAGA">CIMB NIAGA</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control" id="nomor_rekening_bank"></td>
                                        <td><input type="text" class="form-control" id="ibu_kandung"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Alamat (Nama Jalan)</th>
                                        <th>RT / RW</th>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <input type="text" class="form-control" id="alamat_jalan">
                                        </td>
                                        <td>
                                            <div style="display: flex; gap: 5px;">
                                                <input type="text" class="form-control" id="rt" placeholder="RT"
                                                    style="width: 50%;">
                                                <input type="text" class="form-control" id="rw" placeholder="RW"
                                                    style="width: 50%;">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Kelurahan/Desa</th>
                                        <th>Kecamatan</th>
                                        <th>Kab/Kota</th>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control" id="kelurahan_desa"></td>
                                        <td><input type="text" class="form-control" id="kecamatan"></td>
                                        <td><input type="text" class="form-control" id="kota_kab"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Propinsi</th>
                                        <th>Kode Pos</th>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><input type="text" class="form-control" id="propinsi"></td>
                                        <td><input type="text" class="form-control" id="kode_pos"></td>
                                    </tr>
                                    <tr>
                                        <th colspan=3>Alamat (KTP)</th>
                                    </tr>
                                    <tr>
                                        <td colspan=3>
                                            <textarea type="text" class="form-control" id="alamat_rumah"
                                                rows="3"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan=3>Alamat Sementara</th>
                                    </tr>
                                    <tr>
                                        <td colspan=3>
                                            <textarea type="text" class="form-control" id="alamat_sementara"
                                                rows="3"></textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="tab_content">
                                <table class="table table-striped table-sm mb-0">
                                    <tr>
                                        <th class="w-35">Divisi</th>
                                        <th class="w-35">Department</th>
                                        <th class="w-35">Bagian</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select id="site_nirwana_id" name="site_nirwana_id" class="form-control">
                                                <option value="">-- PILIH DIVISI --</option>
                                                @foreach ($divisi as $r_div)
                                                <option value="{{$r_div->site_nirwana_id}}" {{ $r_div->
                                                    site_nirwana_id=='NAG' ? 'selected' :
                                                    ''}}>{{$r_div->site_nirwana_name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select id="department_id" name="department_id" class="form-control">
                                            </select>
                                        </td>
                                        <td>
                                            <select id="sub_dept_id" name="sub_dept_id" class="form-control">
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="w-35">Sewing / Nonsewing</th>
                                        <th class="w-35">Direct / Indirect</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select id="sewing_nonsewing" name="sewing_nonsewing" class="form-control">
                                                <option value="">-- SEWING / NON SEWING --</option>
                                                <option value="SEWING">SEWING</option>
                                                <option value="NON SEWING">NON SEWING</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select id="direct_indirect" name="direct_indirect" class="form-control">
                                                <option value="">-- DIRECT / INDIRECT --</option>
                                                <option value="DIRECT">DIRECT</option>
                                                <option value="INDIRECT">INDIRECT</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Nomor Absen</th>
                                        <th>Tanggal Masuk</th>
                                        <th>NIP</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="input-group">
                                                <input id="is_periksaenroll_id" type="hidden">
                                                <input id="enroll_id" type="text" class="form-control"
                                                    placeholder="Nomor Absen" maxlength="5" size="5">
                                                <span class="input-group-append">
                                                    <button id="btn-periksa_enroll_id"
                                                        class="btn btn-primary pl-2 pr-2 pt-1 pb-1"
                                                        type="button"><span><i class="fa fa-search"></i></span>
                                                        Cek</button>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                    </div>
                                                </div><input id="join_date" class="form-control fc-datepicker"
                                                    placeholder="DD-MM-YYYY" type="text">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input id="is_periksanik" type="hidden">
                                                <input id="nik" type="text" class="form-control" placeholder="NIP"
                                                    maxlength="10" size="10">
                                                <span class="input-group-append">
                                                    <button id="btn-periksa_nik"
                                                        class="btn btn-primary pl-2 pr-2 pt-1 pb-1"
                                                        type="button"><span><i class="fa fa-search"></i></span>
                                                        Cek</button>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Aktif / Non Aktif</th>
                                        <th>Jabatan</th>
                                        <th>Kontrak / Tetap</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select id="status_aktif" name="status_aktif" class="form-control"
                                                data-placeholder="-- Pilih Aktif/NonAktif --">
                                                <option value="">-- PILIH AKTIF --</option>
                                                <option value="AKTIF" selected>AKTIF</option>
                                                <option value="TIDAK AKTIF">TIDAK AKTIF</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select id="status_jabatan" class="form-control"
                                                data-placeholder="-- Pilih Jabatan --">
                                                <option value="">-- PILIH JABATAN --</option>
                                                @foreach($jabatan as $j)
                                                <option value="{{$j}}">{{$j}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select id="status_kontrak_tetap" class="form-control"
                                                data-placeholder="-- Pilih Status Karyawan --">
                                                <option value="">-- PILIH KONTRAK/TETAP --</option>
                                                <option value="KONTRAK" selected>KONTRAK</option>
                                                <option value="TETAP">TETAP</option>
                                                <option value="HARIAN LEPAS">HARIAN LEPAS</option>
                                                <option value="MAGANG">MAGANG</option>
                                                <option value="PERCOBAAN">PERCOBAAN</option>
                                                <option value="NON KARYAWAN">NON KARYAWAN</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Staff / Non Staff</th>
                                        <th>Tanggal Resign</th>
                                        <th>Nomor Surat</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select id="status_staff" class="form-control"
                                                data-placeholder="-- Pilih Staff / Non Staff --">
                                                <option value="">-- PILIH STAFF/NONSTAFF --</option>
                                                <option value="NON STAFF" selected>NON STAFF</option>
                                                <option value="STAFF">STAFF</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                    </div>
                                                </div><input id="tanggal_resign" class="form-control fc-datepicker"
                                                    placeholder="DD-MM-YYYY" type="text">
                                            </div>
                                        </td>
                                        {{-- <td><input type="text" class="form-control" id="tunjangan" maxlength="80"
                                                size="80"></td> --}}
                                        <td><input type="text" class="form-control" id="no_surat" maxlength="80"
                                                size="80"></td>
                                    </tr>
                                    <tr>
                                        <th>Sebab Resign</th>
                                        <th>Referensi</th>
                                        <th>Atasan Langsung</th>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control" id="sebab_resign" maxlength="80"
                                                size="80"></td>
                                        <td><input type="text" class="form-control" id="referensi" maxlength="80"
                                                size="80"></td>
                                        <td><input type="text" class="form-control" id="employee_name_atasan"
                                                maxlength="80" size="80"></td>
                                    </tr>
                                    <tr>
                                        <th>Grade</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select id="kode_grade" class="form-control"
                                                data-placeholder="-- Pilih Grade --">
                                                <option value="">-- PILIH GRADE --</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                                <option value="D">D</option>
                                                <option value="E">E</option>
                                                <option value="F">F</option>
                                                <option value="G" selected>G</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status BPJS TK</th>
                                        <th>Tanggal Reg BPJS TK</th>
                                        <th>Nomor BPJS TK</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select id="status_aktif_bpjs_tk" class="form-control"
                                                data-placeholder="-- Pilih Aktif / Tidak Aktif --">
                                                <option value="">-- PILIH AKTIF/TIDAK AKTIF --</option>
                                                <option value="AKTIF">AKTIF</option>
                                                <option value="TIDAK AKTIF" selected>TIDAK AKTIF</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                    </div>
                                                </div><input id="tanggal_bpjs_ketenagakerjaan"
                                                    class="form-control fc-datepicker" placeholder="DD-MM-YYYY"
                                                    type="text">
                                            </div>
                                        </td>
                                        <td><input type="text" class="form-control" id="nomor_bpjs_ketenagakerjaan">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status BPJS KS</th>
                                        <th>Tanggal Reg BPJS KS</th>
                                        <th>Nomor BPJS KS</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select id="status_aktif_bpjs_ks" class="form-control"
                                                data-placeholder="-- Pilih Aktif / Tidak Aktif --">
                                                <option value="">-- PILIH AKTIF/TIDAK AKTIF --</option>
                                                <option value="AKTIF">AKTIF</option>
                                                <option value="TIDAK AKTIF" selected>TIDAK AKTIF</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                    </div>
                                                </div><input id="tanggal_bpjs_kesehatan"
                                                    class="form-control fc-datepicker" placeholder="DD-MM-YYYY"
                                                    type="text">
                                            </div>
                                        </td>
                                        <td><input type="text" class="form-control" id="nomor_bpjs_kesehatan"></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="tab_content">
                                <table class="table table-striped table-sm mb-0">
                                    <tr>
                                        <th colspan=3>Pengalaman Bekerja</th>
                                    </tr>
                                    <tr>
                                        <td colspan=3>
                                            <a href="#" id="btndownloadcv" class="btn btn-primary"><i
                                                    class="fe fe-download  mr-1"></i>Download CV</a>
                                            <a href="#" id="btnuploadcv" class="btn btn-primary"><i
                                                    class="fe fe-upload  mr-1"></i>Upload CV</a>
                                            <input type="hidden" class="form-control" id="lokasi_file_cv">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan=3>
                                            <textarea type="text" class="form-control" id="pengalaman_bekerja"
                                                rows="15"></textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="tab_content">
                                <table class="table table-striped table-sm mb-0">
                                    <tr>
                                        <th class="w-30">Nama Kerabat</th>
                                        <td class="w-5">:</td>
                                        <td class="w-65"><input type="text" class="form-control" id="nama_kerabat"></td>
                                    </tr>
                                    <tr>
                                        <th>Nomor Telepon</th>
                                        <td>:</td>
                                        <td><input type="text" class="form-control" id="nomor_tlpn_kerabat"></td>
                                    </tr>
                                    <tr>
                                        <th>Hubungan</th>
                                        <td>:</td>
                                        <td><input type="text" class="form-control" id="hubungan_kerabat"></td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td>:</td>
                                        <td>
                                            <textarea type="text" class="form-control" id="alamat_kerabat"
                                                rows="3"></textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="tab_content">
                                <table class="table table-striped table-sm mb-0">
                                    <tr>
                                        <th colspan=3>Vaksin Ke 1</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                    </div>
                                                </div><input id="tanggal_vaccine1" class="form-control fc-datepicker"
                                                    placeholder="DD-MM-YYYY" type="text">
                                            </div>
                                        </td>
                                        <td colspan=2><input type="text" class="form-control" id="nama_vaksin1"></td>
                                    </tr>
                                    <tr>
                                        <th colspan=3>Vaksin Ke 2</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                    </div>
                                                </div><input id="tanggal_vaccine2" class="form-control fc-datepicker"
                                                    placeholder="DD-MM-YYYY" type="text">
                                            </div>
                                        </td>
                                        <td colspan=2><input type="text" class="form-control" id="nama_vaksin2"></td>
                                    </tr>
                                    <tr>
                                        <th colspan=3>Vaksin Ke 3</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                    </div>
                                                </div><input readonly id="tanggal_vaccine3"
                                                    class="form-control fc-datepicker" placeholder="DD-MM-YYYY"
                                                    type="text">
                                            </div>
                                        </td>
                                        <td colspan=2><input type="text" class="form-control" id="nama_vaksin3"></td>
                                    </tr>
                                    <tr>
                                        <th>Golongan SIM</th>
                                        <th>Nomor SIM</th>
                                        <th>Tanggal Berakhir SIM</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select id="golongan_sim" class="form-control"
                                                data-placeholder="-- Pilih --">
                                                <option value="">-- PILIH GOLONGAN SIM --</option>
                                                <option value="A">SIM A</option>
                                                <option value="B1">SIM B1</option>
                                                <option value="B2">SIM B2</option>
                                                <option value="C">SIM C</option>
                                                <option value="C1">SIM C1</option>
                                                <option value="C2">SIM C2</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control" id="nomor_sim"></td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                    </div>
                                                </div><input id="tanggal_expire_sim" class="form-control fc-datepicker"
                                                    placeholder="DD-MM-YYYY" type="text">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan=3>Catatan Karyawan</th>
                                    </tr>
                                    <tr>
                                        <td colspan=3>
                                            <textarea type="text" class="form-control" id="catatan"
                                                rows="15"></textarea>
                                            <input type="hidden" class="form-control" id="lokasi_foto">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Operator</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Tanggal Diupdate</th>
                                    </tr>
                                    <tr>
                                        <td><input readonly type="text" class="form-control" id="operator"></td>
                                        <td>
                                            <input readonly id="created_at" class="form-control"
                                                placeholder="YYYY-MM-DD HH:MM:SS" type="text">
                                        </td>
                                        <td>
                                            <input readonly id="updated_at" class="form-control"
                                                placeholder="YYYY-MM-DD HH:MM:SS" type="text">
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="tab_content">
                                <table class="table table-striped table-sm mb-0">
                                    <tr>
                                        <th>Dari Tanggal</th>
                                        <th>Sampai Tanggal</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                    </div>
                                                </div><input id="tanggal_mulai_kontrak"
                                                    class="form-control fc-datepicker" placeholder="DD-MM-YYYY"
                                                    type="text">
                                                <div id="tanggal_mulai_kontrak_string"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                    </div>
                                                </div><input id="tanggal_akhir_kontrak"
                                                    class="form-control fc-datepicker" placeholder="DD-MM-YYYY"
                                                    type="text">
                                                <div id="tanggal_akhir_kontrak_string"></div>
                                            </div>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan=3>Catatan Kontrak</th>
                                    </tr>
                                    <tr>
                                        <td colspan=3>
                                            <textarea type="text" class="form-control" id="catatan_kontrak"
                                                rows="6"></textarea>
                                        </td>
                                    </tr>

                                </table>
                            </div>
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
                <button id="btn-save" class="btn btn-secondary btn-app mt-1">
                    <span><i class="fa fa-save"></i></span> Save</button>
                <button id="btn-reset" class="btn btn-info btn-app  mt-1">
                    <span><i class="fa fa-undo"></i></span> Reset</button>
                <button id="btn-cancel" class="btn btn-warning btn-app  mt-1">
                    <span><i class="fa fa-close"></i></span> Cancel</button>
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

<!---Tabs js-->
<script src="{{URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js')}}"></script>
<script src="{{URL::asset('assets/plugins/tabs/tabs.js')}}"></script>
<style>
    #head_karyawan,
    #tabel_karyawan {
        display: block;
    }

    #head_employees,
    #selected_employees {
        display: block;
    }

    #tabel_karyawan {
        height: 1px;
        /* Just for the demo          */
        overflow-y: auto;
        /* Trigger vertical scroll    */
        overflow-x: hidden;
        font-size: 9pt;
        /* Hide the horizontal scroll */
    }

    .dataTables_filter {
        float: left !important;
    }

    #selected_employees {
        height: 1px;
        /* Just for the demo          */
        overflow-y: auto;
        /* Trigger vertical scroll    */
        overflow-x: hidden;
        font-size: 9pt;
        /* Hide the horizontal scroll */
    }
</style>

<script>
    function updateAlamatRumah() {
        let jalan = $('#alamat_jalan').val();
        let rt = $('#rt').val();
        let rw = $('#rw').val();
        let kodePos = $('#kode_pos').val();
        let propinsi = $('#propinsi').val();
        let kota = $('#kota_kab').val();
        let kecamatan = $('#kecamatan').val();
        let kelurahan = $('#kelurahan_desa').val();

        let alamat = '';

        if (jalan) alamat += jalan;
        if (rt || rw) alamat += (alamat ? ', ' : '') + 'RT ' + rt + '/RW ' + rw;
        if (kelurahan) alamat += (alamat ? ', ' : '') + 'Kel. ' + kelurahan;
        if (kecamatan) alamat += (alamat ? ', ' : '') + 'Kec. ' + kecamatan;
        if (kota) alamat += (alamat ? ', ' : '') + kota;
        if (propinsi) alamat += (alamat ? ', ' : '') + propinsi;
        if (kodePos) alamat += (alamat ? ', ' : '') + kodePos;

        $('#alamat_rumah').val(alamat);
    }

    $(document).ready(function() {
        $('#alamat_jalan, #rt, #rw, #kode_pos, #propinsi, #kota_kab, #kecamatan, #kelurahan_desa').on('input', updateAlamatRumah);
    });
</script>

<script type="text/javascript">
    let initialNomorRekening = null;
    $('#nomor_rekening_bank').on('input', function() {
        const currentVal = $(this).val();

        if (initialNomorRekening && currentVal !== initialNomorRekening) {
            // Jika ada perubahan dan nilai awal bukan null/kosong, reset nama_bank
            $('#nama_bank').val('');
        }
    });

    $('#btndownloadFoto').click(function(e){
        e.preventDefault();

        let nik = $('#nik').val();
        let employee_name = $('#employee_name').val();
        let poto_profil = nik + ' ' + employee_name + '.png';

        // Encode nama file
        let encodedFilename = encodeURIComponent(poto_profil);

        // URL untuk route download
        let downloadUrl = '{{ url('hris/hrd/download-foto') }}/' + encodedFilename;

        // Redirect untuk memulai download
        window.location.href = downloadUrl;
    });




    $('#btndownloadid').click(function(e){
        if($('#enroll_id').val()==''){
            alert('Pilih karyawan terlebih dahulu!');
        }else{
            var print_by = $("#print_by").val();
            var enroll_id=$('#enroll_id').val();
            var url = 'export_pdf_id_card?print_by='+print_by+'&enroll_id='+enroll_id;
            window.open(url, '_blank');
        }
    });
    $('#btndownloadiddept').click(function(e){
            var department=$('#selectDepartment').val();
            var print_by = $("#print_by").val();
            var sub_department=$('#pilih_department').val();
            var url = 'export_pdf_id_card_department?print_by='+print_by+'&department='+department+'&sub_department='+sub_department;
            window.open(url, '_blank');
    });
    $('#btnselectemployee').click(function(e){
        $("#selected_employees").empty();
        document.getElementById('selected_employees').style.height='1px';
        document.getElementById('row_employee').style.height='67px';
        $.ajax({
            type:"POST",
            url: "{{route('hris.employeeatr.select_employee')}}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                department:$('#selectDepartment').val(),
                sub_department:$('#pilih_department').val(),
            },
            success: function(data){
                jQuery.each(data, function(key,value){
                    $('#selected_employees').append("<tr>\
                            <td width='10px' style='padding-top:4px;padding-bottom:8px'><div class='form-check'><input class='form-check-input' type='checkbox' style='width: 20px;height:20px' value='"+data[key].enroll_id+"'id='employee' name='employee'></td>\
                            <td width='68px'>"+data[key].enroll_id+"</td>\
                            <td width='120px'>"+data[key].nik+"</td>\
                            <td width='200px'>"+data[key].employee_name+"</td>\
                            <td width='150px'>"+data[key].jenis_kelamin+"</td>\
                            <td width='150px'>"+data[key].status_jabatan+"</td>\
                            <td width='200px'>"+data[key].department_name+"</td>\
                            <td width='200px'>"+data[key].sub_dept_name+"</td>\
                            <td width='120px'>"+data[key].status_aktif+"</td>\
                            </label>\
                        </tr>\
                    ");
                });
                document.getElementById('row_employee').style.height='570px';
                document.getElementById('selected_employees').style.height='500px';
            }
        });
    });
    function toggle(source) {
        checkboxes = document.getElementsByName('employee');
        for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = source.checked;
        }
    }
    $('#btndownloadselectedids').click(function(e){
        var print_by = $("#print_by").val();
        var url = 'export_pdf_id_card_employee?print_by='+print_by+'&employee='+checkedEmployeeArr;
        window.open(url, '_blank');
    });
    $('#selectDepartment').on('change',function(e){
        $("#checkAllEmployee").prop("checked", false);
        $("#checkAllEmployee").trigger("change");

        testing();
        $("#pilih_department").empty();
        $("#pilih_department").val('');
        $.ajax({
            type:"POST",
            url: "{{route('hris.departmentall.getSelectSubDept')}}",
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                department_id:$('#selectDepartment').val(),
                sub_dept_id:$('#pilih_department').val(),
            },
            dataType: 'json',
            success: function(resA){
                if(resA){
                    $("#pilih_department").append(new Option('Filter Sub Department', ''));
                    for(i=0;i<resA.length;i++) {
                        $("#pilih_department").append(new Option(resA[i].sub_dept_name, resA[i].sub_dept_id));
                    }
                }
            }
        });
        $("#pilih_department").empty();
        $("#pilih_department").val('');
    });
    $('#pilih_department').on('change',function(e){
        testing();
        $("#checkAllEmployee").prop("checked", false);
        $("#checkAllEmployee").trigger("change");
    });
    $('#btnupload').click(function(e){
        let enroll_id=$('#enroll_id').val();
        $('#upload_image').attr('disabled',true);
        if(enroll_id==''){
            alert('Pilih karyawan terlebih dahulu!');
        }else{
            $('#choose_photo').val('');
            $('#uploadFoto').modal('show');
        }
    });
    $('#choose_photo').change(function(e){
        $('#image_preview').empty();
        var fileInput = document.getElementById('choose_photo');
        var filePath = fileInput.value;
        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
        if(!allowedExtensions.exec(filePath)){
            alert('Please upload file having extensions .jpeg/.jpg/.png/.gif only.');
            fileInput.value = '';
            $('#image_preview').append('<img src="{{URL::asset('assets/images/brand/foto orang.png')}}" alt="" class="user mt-3">');
            $('#upload_image').attr('disabled',true);
            return false;
        }else{
            //Image preview
            if (fileInput.files && fileInput.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image_preview').innerHTML = '<img src="'+e.target.result+' " width=255px/>';
                };
                reader.readAsDataURL(fileInput.files[0]);
                $('#upload_image').attr('disabled',false);
            }
        }
    });

    $(document).on("click","#upload_image",function(e){
        e.preventDefault();
        var formData = new FormData();
        var enroll_id=$('#enroll_id').val();
        let _token = $('meta[name="csrf-token"]').attr('content');
        var photo = $('#choose_photo').prop('files')[0];
        formData.append('enroll_id', enroll_id);
        formData.append('photo', photo);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"POST",
            url: "{{route('hris.employeeatr.store_photo')}}",
            contentType: 'multipart/form-data',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(res){
                swal("", "Profile photo update success", "success");
                $('#uploadFoto').modal('hide');
                $('#choose_photo').val('');
                $('#image_preview').empty().append('<img src="{{URL::asset('assets/images/brand/foto orang.png')}}" alt="" class="user mt-3">');
            }
        });
    });
    $('#upload_image').click(function($query){
        let enroll_id=$('#enroll_id').val();

    });
    function fill_the_table(){
        $('#loading_karyawan').addClass("spinner-border");
        $('#tabel_karyawan').empty();
        var formData = new FormData();
        var excelFile=document.getElementById("excel_filess");
        var myFile=excelFile.files[0];
        var error_handle = $("input[name='error_handle']:checked").val();
        formData.append("excel_file",myFile);
        formData.append("error_handle",error_handle);
        document.getElementById('row_tabler').style.height='67px';
        document.getElementById('tabel_karyawan').style.height='1px';
        document.getElementById('employeeImportButton').style.visibility='hidden';
        if(typeof myFile=='undefined'){
            notif({
                msg: "<b>Error:</b> Pilih File terlebih dahulu!",
                type: "error"
            });
            document.getElementById('tabel_karyawan').style.height='1px';
            document.getElementById('row_error_handle').style.visibility='hidden';
            document.getElementById('employeeImportButton').style.visibility='hidden';
            $('#loading_karyawan').removeClass("spinner-border");
            document.getElementById('row_tabler').style.height='67px';
        }else{
            $.ajax({
                type: 'POST',
                url: '{{route('hris.employeeatr.import_employees')}}',
                contentType: false,
                processData: false,
                data: formData,
                success:function(data){
                    var count=0;
                    var count2=0;
                    jQuery.each(data, function(key,value){
                        count2++;
                        $('#tabel_karyawan').append("<tr style='background-color:"+data[key].status_department+"'>\
                            <td width='68px'>"+data[key].enroll_id+"</td>\
                            <td width='120px'>"+data[key].nik+"</td>\
                            <td width='250px'>"+data[key].nama_karyawan+"</td>\
                            <td width='150px'>"+data[key].jenis_kelamin+"</td>\
                            <td width='150px'>"+data[key].jabatan+"</td>\
                            <td width='200px'>"+data[key].department+"</td>\
                            <td width='200px'>"+data[key].bagian+"</td>\
                            <td width='120px'>"+data[key].status_aktif+"</td>\
                        </tr>");
                        if(data[key].status_department!='red'){
                            count++;
                        }
                    });
                    $('#length_karyawan').text(count2);
                    $('#correct_data').text(count);
                    document.getElementById('tabel_karyawan').style.height='330px';
                    document.getElementById('employeeImportButton').style.visibility='visible';
                    document.getElementById('row_error_handle').style.visibility='visible';
                    $('#loading_karyawan').removeClass("spinner-border");
                    document.getElementById('row_tabler').style.height='400px';
                }
            })
        }
    }
    $('#excel_filess').change(function() {
        fill_the_table();
    });
    $('#employeeImportButton').click(function(){
        var formData = new FormData();
        var excelFile=document.getElementById("excel_filess");
        var myFile=excelFile.files[0];
        formData.append("excel_file",myFile);
        $('#employeeImportButton').addClass("btn-loading");
        $("#employeeImportButton").html('Please wait...');
        $("#employeeImportButton").attr("disabled", true);
        $.ajax({
            type: 'POST',
            url: '{{route('hris.employeeatr.import_employee_to_database')}}',
            contentType: false,
            processData: false,
            data: formData,
            success:function(data){
                $('#employeeImportButton').removeClass("btn-loading");
                $("#employeeImportButton").html('<i class="fa fa-upload" aria-hidden="true"></i> IMPORT');
                $("#employeeImportButton").attr("disabled", false);
                swal("", "IMPORT KARYAWAN BERHASIL!", "success");
                $('#import_employees').modal('hide');
                $('#excel_filess').val('');
                document.getElementById('tabel_karyawan').style.height='1px';
                document.getElementById('row_error_handle').style.visibility='hidden';
                document.getElementById('employeeImportButton').style.visibility='hidden';
                document.getElementById('row_tabler').style.height='67px';
            },
            error: function(res){
                swal("", "IMPORT KARYAWAN GAGAL!", "error")
                $('#employeeImportButton').removeClass("btn-loading");
                $("#employeeImportButton").html('<i class="fa fa-upload" aria-hidden="true"></i> IMPORT');
                $("#employeeImportButton").attr("disabled", false);
            }
        });
    });
</script>

<script type="text/javascript">
    $('.fc-datepicker').datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'dd-mm-yy'
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#btn-refresh-page', function (event) {
            location.reload();
        });

        function isValidDate(s) {
            var bits = s.split('-');
            var d = new Date(bits[2] + '-' + bits[1] + '-' + bits[0]);
            return !!(d && (d.getMonth() + 1) == bits[1] && d.getDate() == Number(bits[0]));
        }

        function defaultDate(s) {
            if(s) {
                var bits = s.split('-');
                var d = bits[2] + '-' + bits[1] + '-' + bits[0];
            }
            return d;
        }

        $('#progress-show-1').hide();
        $("#form1 :input").prop("disabled", true);
        $("#btn-save").prop("disabled", true);
        $("#btn-reset").prop("disabled", true);
        $("#btn-cancel").prop("disabled", true);
        // $('#alamat_rumah').val('');
        // $('#alamat_sementara').val('');
        // $('#pengalaman_bekerja').val('');
        // $('#alamat_kerabat').val('');
        // $('#catatan').val('');
        // $('#catatan_kontrak').val('');

        $('#employee_name').keyup(function(){
            var terisi = $('#employee_name').val();

            if (terisi) {
                $("#employee_name").removeClass('border-danger');
            } else {
                $("#employee_name").addClass('border-danger');
            }

        });

        $('body').on('change', '#jenis_kelamin', function () {
            var terisi = $('#jenis_kelamin').val();

            if (terisi) {
                $("#jenis_kelamin").removeClass('border-danger');
            } else {
                $("#jenis_kelamin").addClass('border-danger');
            }

        });

        $('#tempat_lahir').keyup(function(){
            var terisi = $('#tempat_lahir').val();

            if (terisi) {
                $("#tempat_lahir").removeClass('border-danger');
            } else {
                $("#tempat_lahir").addClass('border-danger');
            }

        });

        $('body').on('change', '#tanggal_lahir', function () {
            var terisi = $('#tanggal_lahir').val();

            if (terisi) {
                $("#tanggal_lahir").removeClass('border-danger');
            } else {
                $("#tanggal_lahir").addClass('border-danger');
            }

        });

        $('#nomor_ktp').keyup(function(){
            var terisi = $('#nomor_ktp').val();

            if (terisi) {
                $("#nomor_ktp").removeClass('border-danger');
            } else {
                $("#nomor_ktp").addClass('border-danger');
            }

        });

        $('body').on('change', '#site_nirwana_id', function () {
            var terisi = $('#site_nirwana_id').val();

            if (terisi) {
                $("#site_nirwana_id").removeClass('border-danger');
            } else {
                $("#site_nirwana_id").addClass('border-danger');
            }

        });

        $('body').on('change', '#department_id', function () {
            var terisi = $('#department_id').val();

            if (terisi) {
                $("#department_id").removeClass('border-danger');
            } else {
                $("#department_id").addClass('border-danger');
            }

        });

        $('body').on('change', '#sub_dept_id', function () {
            var terisi = $('#sub_dept_id').val();

            if (terisi) {
                $("#sub_dept_id").removeClass('border-danger');
            } else {
                $("#sub_dept_id").addClass('border-danger');
            }

        });

        $('#enroll_id').keyup(function(){
            var terisi = $('#enroll_id').val();

            if (terisi) {
                $("#enroll_id").removeClass('border-danger');
            } else {
                $("#enroll_id").addClass('border-danger');
            }

        });

        $('#nik').keyup(function(){
            var terisi = $('#nik').val();

            if (terisi) {
                $("#nik").removeClass('border-danger');
            } else {
                $("#nik").addClass('border-danger');
            }

        });

        $('body').on('change', '#join_date', function () {
            var terisi = $('#join_date').val();

            if (terisi) {
                $("#join_date").removeClass('border-danger');
            } else {
                $("#join_date").addClass('border-danger');
            }

        });

        $('body').on('change', '#status_aktif', function () {
            var terisi = $('#status_aktif').val();

            if (terisi) {
                $("#status_aktif").removeClass('border-danger');
            } else {
                $("#status_aktif").addClass('border-danger');
            }

        });

        $('body').on('click', '#btn-add ', function (event) {
            $("#form1 :input").prop("disabled", false);
            $("#form1").trigger('reset');
            $("#btn-save").prop("disabled", false);
            $("#btn-reset").prop("disabled", false);
            $("#btn-cancel").prop("disabled", false);
            $('#btn-save').html('<i class="fa fa-save"></i> Save');
            $('#alamat_rumah').val('');
            $("#enroll_id, #nik").prop({
                disabled: false,
                readonly: false
            });


            $('#alamat_jalan').val('');
            $('#rt').val('');
            $('#rw').val('');
            $('#kode_pos').val('');

            $('#alamat_sementara').val('');
            $('#pengalaman_bekerja').val('');
            $('#alamat_kerabat').val('');
            $('#catatan').val('');
            $('#catatan_kontrak').val('');
            $('#is_periksaenroll_id').val(0);
            $('#is_periksanik').val(0);

            $("#employee_name").addClass('border-danger');
            $("#jenis_kelamin").addClass('border-danger');
            $("#tempat_lahir").addClass('border-danger');
            $("#tanggal_lahir").addClass('border-danger');
            $("#nomor_ktp").addClass('border-danger');
            $("#site_nirwana_id").addClass('border-danger');
            $("#department_id").addClass('border-danger');
            $("#sub_dept_id").addClass('border-danger');
            $("#enroll_id").addClass('border-danger');
            $("#nik").addClass('border-danger');
            $("#join_date").addClass('border-danger');
            $("#status_aktif").addClass('border-danger');
            $("#status_staff").addClass('border-danger');

        });

        $('body').on('click', '#btn-reset', function (event) {
            $("#form1").trigger('reset');
            $('#alamat_rumah').val('');
            $('#alamat_jalan').val('');
            $('#rt').val('');
            $('#rw').val('');
            $('#kode_pos').val('');
            $('#alamat_sementara').val('');
            $('#pengalaman_bekerja').val('');
            $('#alamat_kerabat').val('');
            $('#catatan').val('');
            $('#catatan_kontrak').val('');
            $('#is_periksaenroll_id').val(0);
            $('#is_periksanik').val(0);
        });

        $('body').on('click', '#btn-edit', function (event) {
            var enroll_id = $('#enroll_id').val();

            if (enroll_id.length > 0) {
                $("#form1 :input").prop("disabled", false);
                $("#btn-save").prop("disabled", false);
                $("#btn-reset").prop("disabled", true);
                $("#btn-cancel").prop("disabled", false);
                $('#btn-save').html('<i class="fa fa-edit"></i> Update');
                // $("#btn-periksa_enroll_id").attr("disabled", true);
                // $("#enroll_id").attr("readonly", true);
                $('#is_periksaenroll_id').val(1);
                // $("#btn-periksa_nik").attr("disabled", true);
                // $("#nik").attr("readonly", true);
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

        $('body').on('click', '#btn-cancel', function (event) {
            location.reload();
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

        function testing(){
            var department_id = $('#selectDepartment').val();
            var sub_dept_id = $('#pilih_department').val();

            $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);
        }
        var currentPageCheck = 0;
        var checkedEmployeeArr = [];
        $(document).ready(function() {
            var department_id = $('#selectDepartment').val();
            $('#profile_photo').empty().append('<img src="{{URL::asset('assets/images/brand/foto orang.png')}}" alt="" class="user mt-3" height="110px">');
            var table1 = $('#datatable-ajax-crud').DataTable({
                processing: true,
                serverSide: true,
                lengthChange: false,
                pageLength: 10,
                pagingType: "simple",
                destroy: true,
                scrollY: '500px',
                scrollCollapse: true,
                "ajax": {
                    "url": "{{ route('hris.employeeatr.ajax_getemployeeatr') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                    "data": function (d) {
                        d.department_id = $('#selectDepartment').val();
                        d.sub_dept_id = $('#pilih_department').val();
                    }
                },
                columns: [
                    {
                        data: 'enroll_id',
                        orderable: false
                    },
                    {
                        title: 'NIK',
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        title: 'Nomor Absen',
                        data: 'enroll_id',
                        name: 'enroll_id'
                    },
                    {
                        title: 'Nama Karyawan',
                        data: 'employee_name',
                        name: 'employee_name'
                    }
                ],
                columnDefs: [
                    {
                        'visible': false,
                        'targets': []
                    },
                    {
                        'targets': [0],
                        'render' : function (data, row) {
                            return `
                                <div class="form-check">
                                    <input class="form-check-input employee-check" type="checkbox" name="employee-check" value="`+data+`" id='check-`+data+`' onchange="actionThisEmployeeCheck(this)">
                                </div>
                            `
                        }
                    }
                ],
                order: [
                    [2, 'asc']
                ],
                "createdRow": function (row, data, dataIndex) {
                    if (data['new_employee']) {
                        $(row).addClass('bg-green')
                    }
                    if (data['deactive']) {
                        $(row).addClass('bg-red')
                    }
                },
                rowCallback: function(row, data, dataIndex){
                    let currentEnrollId = data['enroll_id'];

                    if(checkedEmployeeArr.find((value) => value == currentEnrollId)){
                        currentPageCheck++;
                        $(row).find('input[type="checkbox"]').prop('checked', true);
                    }
                },
                drawCallback: function (settings) {
                    if (currentPageCheck == 0) {
                        $('#checkAllEmployee').prop("checked", false);
                    } else {
                        $('#checkAllEmployee').prop("checked", true);
                    }

                    currentPageCheck = 0;
                }
            });

            table1.draw();

            $('#datatable-ajax-crud tbody').on('click', 'tr', function () {
                var tr = $(this).closest('tr');
                var row = table1.row(tr);

                var data = row.data();


                $("#datatable-ajax-crud tbody tr").removeClass('bg-cyan');
                $(this).addClass('bg-cyan');

                //var data = $("#datatable-ajax-crud").DataTable().row(this).data();
                //alert(data['employee_name']);
                $('#employee_name').val(data['employee_name']);
                $("#jenis_kelamin").val(data['jenis_kelamin']).trigger("change");
                $('#tempat_lahir').val(data['tempat_lahir']);
                tanggal_lahir = defaultDate(data['tanggal_lahir']);
                $('#tanggal_lahir').val(tanggal_lahir);
                $("#golongan_darah").val(data['golongan_darah']).trigger("change");
                $('#email').val(data['email']);
                $('#nomor_tlpn').val(data['nomor_tlpn']);
                $("#agama").val(data['agama']).trigger("change");
                $("#status_kawin").val(data['status_kawin']).trigger("change");
                $('#npwp').val(data['npwp']);
                $('#nomor_ktp').val(data['nomor_ktp']);
                $('#nomor_kk').val(data['nomor_kk']);
                $('#ptkp').val(data['ptkp']);
                $('#pendidikan_terakhir').val(data['pendidikan_terakhir']);
                $('#jurusan_pendidikan').val(data['jurusan_pendidikan']);
                $("#nama_bank").val(data['nama_bank']).trigger("change");
                initialNomorRekening = data['nomor_rekening_bank'];
                $('#nomor_rekening_bank').val(data['nomor_rekening_bank']);
                $('#ibu_kandung').val(data['ibu_kandung']);
                $('#propinsi').val(data['propinsi']);
                $('#kota_kab').val(data['kota_kab']);
                $('#kecamatan').val(data['kecamatan']);
                $('#kelurahan_desa').val(data['kelurahan_desa']);
                $('#alamat_rumah').val(data['alamat_rumah']);
                $('#alamat_sementara').val(data['alamat_sementara']);

                $("#site_nirwana_id").val(data['site_nirwana_id']).trigger("change");

                $("#department_id").append(new Option(data['department_name'], data['department_id']));

                $("#department_id").val(data['department_id']).trigger("change");

                $("#sub_dept_id").append(new Option(data['sub_dept_name'], data['sub_dept_id']));

                $("#sub_dept_id").val(data['sub_dept_id']).trigger("change");
                $('#sewing_nonsewing').val(data['sewing_nonsewing']).trigger("change");
                $('#direct_indirect').val(data['direct_indirect']).trigger("change");
                $('#enroll_id').val(data['enroll_id']);
                join_date = defaultDate(data['join_date']);
                $('#join_date').val(join_date);
                $('#nik').val(data['nik']);
                $("#status_aktif").val(data['status_aktif']).trigger("change");
                $("#status_jabatan").val(data['status_jabatan']).trigger("change");
                $("#status_kontrak_tetap").val(data['status_kontrak_tetap']).trigger("change");
                $("#status_staff").val(data['status_staff']).trigger("change");
                tanggal_resign = defaultDate(data['tanggal_resign']);
                $('#tanggal_resign').val(tanggal_resign);
                $('#sebab_resign').val(data['sebab_resign']);
                $('#tunjangan').val(data['tunjangan']);
                $('#no_surat').val(data['no_surat']);
                $("#kode_grade").val(data['kode_grade']).trigger("change");
                $('#referensi').val(data['referensi']);
                $('#employee_name_atasan').val(data['employee_name_atasan']);
                $("#status_aktif_bpjs_tk").val(data['status_aktif_bpjs_tk']).trigger("change");
                tanggal_bpjs_ketenagakerjaan = defaultDate(data['tanggal_bpjs_ketenagakerjaan']);
                $('#tanggal_bpjs_ketenagakerjaan').val(tanggal_bpjs_ketenagakerjaan);
                $('#nomor_bpjs_ketenagakerjaan').val(data['nomor_bpjs_ketenagakerjaan']);
                $("#status_aktif_bpjs_ks").val(data['status_aktif_bpjs_ks']).trigger("change");
                tanggal_bpjs_kesehatan = defaultDate(data['tanggal_bpjs_kesehatan']);
                $('#tanggal_bpjs_kesehatan').val(tanggal_bpjs_kesehatan);
                $('#nomor_bpjs_kesehatan').val(data['nomor_bpjs_kesehatan']);
                $('#premi').val(data['premi']);
                $('#pengalaman_bekerja').val(data['pengalaman_bekerja']);
                $('#lokasi_file_cv').val(data['lokasi_file_cv']);
                $('#nama_kerabat').val(data['nama_kerabat']);
                $('#nomor_tlpn_kerabat').val(data['nomor_tlpn_kerabat']);
                $('#hubungan_kerabat').val(data['hubungan_kerabat']);
                $('#alamat_kerabat').val(data['alamat_kerabat']);
                tanggal_vaccine1 = defaultDate(data['tanggal_vaccine1']);
                $('#tanggal_vaccine1').val(tanggal_vaccine1);
                $('#nama_vaksin1').val(data['nama_vaksin1']);
                tanggal_vaccine2 = defaultDate(data['tanggal_vaccine2']);
                $('#tanggal_vaccine2').val(tanggal_vaccine2);
                $('#nama_vaksin2').val(data['nama_vaksin2']);
                tanggal_vaccine3 = defaultDate(data['tanggal_vaccine3']);
                $('#tanggal_vaccine3').val(tanggal_vaccine3);
                $('#nama_vaksin3').val(data['nama_vaksin3']);
                $("#golongan_sim").val(data['golongan_sim']).trigger("change");
                $('#nomor_sim').val(data['nomor_sim']);
                tanggal_expire_sim = defaultDate(data['tanggal_expire_sim']);
                $('#tanggal_expire_sim').val(tanggal_expire_sim);
                $('#catatan').val(data['catatan']);
                $('#lokasi_foto').val(data['lokasi_foto']);
                tanggal_mulai_kontrak = defaultDate(data['tanggal_mulai_kontrak']);
                tanggal_akhir_kontrak = defaultDate(data['tanggal_akhir_kontrak']);
                $('#tanggal_mulai_kontrak').val(tanggal_mulai_kontrak);
                $('#tanggal_akhir_kontrak').val(tanggal_akhir_kontrak);

                $('#alamat_jalan').val(data['alamat_jalan']);
                $('#rt').val(data['rt']);
                $('#rw').val(data['rw']);
                $('#kode_pos').val(data['kode_pos']);
                // if(data['kontrak_awal']!=null){
                //     tanggal_mulai_kontrak = defaultDate(data['kontrak_awal']);
                //     $('#tanggal_mulai_kontrak_string').val(tanggal_mulai_kontrak);
                //     document.getElementById('tanggal_mulai_kontrak_string').style.display='none';
                //     document.getElementById('tanggal_mulai_kontrak').style.display='block';


                // }else{
                //     document.getElementById('tanggal_mulai_kontrak').style.display='block';
                //     document.getElementById('tanggal_mulai_kontrak_string').style.display='none';
                // }
                // if(data['kontrak_awal']!=null){
                //     tanggal_akhir_kontrak = defaultDate(data['kontrak_akhir']);
                //     $('#tanggal_akhir_kontrak_string').val(tanggal_akhir_kontrak);
                //     document.getElementById('tanggal_akhir_kontrak_string').style.display='none';
                //     document.getElementById('tanggal_akhir_kontrak').style.display='block';
                // }else{
                //     document.getElementById('tanggal_akhir_kontrak').style.display='block';
                //     document.getElementById('tanggal_akhir_kontrak_string').style.display='none';
                // }
                $('#catatan_kontrak').val(data['catatan_kontrak']);
                $('#operator').val(data['operator']);
                $('#created_at').val(data['created_at']);
                $('#updated_at').val(data['updated_at']);
                let poto_profil=data['nik']+' '+data['employee_name']+'.png';
                $('#profile_photo').empty().append('<img src="http://10.10.5.111/hris/public/storage/app/public/images'+'/'+poto_profil+'" height="120px">');
                $('#image_preview').empty().append('<img src="http://10.10.5.111/hris/public/storage/app/public/images'+'/'+poto_profil+'" alt="" class="user mt-3">');
             });

        });

        $('body').on('click', '#btn-exportexcel', function (event) {
            notif({
                msg: "<b>Info:</b> Data sedang di proses, mohon menunggu",
                type: "info"
            });
        });

        function actionThisEmployeeCheck(element) {
            if (element.checked) {
                if(!checkedEmployeeArr.find((value) => value == element.value)) {
                    checkedEmployeeArr.push(element.value);
                }
            } else {
                if(checkedEmployeeArr.find((value) => value == element.value)) {
                    const index = checkedEmployeeArr.indexOf(element.value);
                    if (index > -1) { // only splice array when item is found
                        checkedEmployeeArr.splice(index, 1); // 2nd parameter means remove one item only
                    }
                }
            }

            document.getElementById("checked-employee-count").innerText = checkedEmployeeArr.length;
        }

        function actionCheckedEmployee () {
            let employeeCheck = document.getElementsByClassName('employee-check');

            let checkedEmployee = [];
            for (let i = 0; i < employeeCheck.length; i++) {
                if (employeeCheck[i].checked) {
                    checkedEmployee.push(employeeCheck[i].value);
                }
            }
        }

        function actionCheckAllEmployee(element) {

            if (element.checked) {
                $.ajax({
                    type:"POST",
                    url: "{{route('hris.employeeatr.ajax_getemployeeids')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        department_id:$('#selectDepartment').val(),
                        sub_dept_id:$('#pilih_department').val()
                    },
                    dataType: 'json',
                    success: function(res){
                        if(res){
                            checkedEmployeeArr = res;

                            $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);
                        }
                    }
                });
            } else {
                checkedEmployeeArr = [];

                $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);
            }
        }

    $(document).ready(function() {
        $('body').on('change', '#site_nirwana_id', function () {
            var site_nirwana_id = $('#site_nirwana_id').val();
            if (site_nirwana_id) {
                $("#department_id").empty();
                $("#department_id").val(null).trigger("change");
                $("#department_id").append(new Option('-- PILIH DEPARTMENT --', ''));
            } else {
                $("#department_id").empty();
                $("#department_id").val(null).trigger("change");
                $("#sub_dept_id").empty();
                $("#sub_dept_id").val(null).trigger("change");
                $("#department_id").append(new Option('-- PILIH DEPARTMENT --', ''));
                $("#sub_dept_id").append(new Option('-- PILIH BAGIAN --', ''));
            }

            if(site_nirwana_id){
                $.ajax({
                    type:"POST",
                    url: "{{route('hris.employeeatr.ajax_getselectdept')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        site_nirwana_id:site_nirwana_id,
                    },
                    dataType: 'json',
                    success: function(res){
                        if(res){
                            for(i=0;i<res.length;i++) {
                                // $("#department_id").append(new Option(res[i].department_name, res[i].department_id));
                                if ($("#department_id option[value='" + res[i].department_id + "']").length === 0) {
                                    $("#department_id").append(new Option(res[i].department_name, res[i].department_id));
                                }
                            }
                        }
                    }
                });
            }

        });
        $('#site_nirwana_id').trigger('change');
        });
        $('body').on('change', '#department_id', function () {
            var site_nirwana_id = $('#site_nirwana_id').val();
            var department_id = $('#department_id').val();

            if (department_id) {
                $("#sub_dept_id").empty();
                $("#sub_dept_id").val(null).trigger("change");
                $("#sub_dept_id").append(new Option('-- PILIH BAGIAN --', ''));
            } else {
                $("#sub_dept_id").empty();
                $("#sub_dept_id").val(null).trigger("change");
                $("#sub_dept_id").append(new Option('-- PILIH BAGIAN --', ''));
            }

            if(site_nirwana_id){
                $.ajax({
                    type:"POST",
                    url: "{{route('hris.employeeatr.ajax_getselectsubdept')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        site_nirwana_id:site_nirwana_id,
                        department_id:department_id,
                    },
                    dataType: 'json',
                    success: function(res){
                        if(res){
                            for(i=0;i<res.length;i++) {
                                if ($("#sub_dept_id option[value='" + res[i].sub_dept_id + "']").length === 0) {
                                    $("#sub_dept_id").append(new Option(res[i].sub_dept_name, res[i].sub_dept_id));
                                }
                            }
                        }
                    }
                });
            }

        });

        $('body').on('click', '#btn-periksa_enroll_id', function () {
            var enroll_id = $('#enroll_id').val();
            var is_periksaenroll_id = $('#is_periksaenroll_id').val();
            $('#btn-periksa_enroll_id').addClass("btn-loading");
            $("#btn-periksa_enroll_id").html('Please wait...');
            $("#btn-periksa_enroll_id").attr("disabled", true);

            if (enroll_id) {
                if ((is_periksaenroll_id == 0) || (is_periksaenroll_id == '')) {
                    $.ajax({
                        type:"POST",
                        url: "{{route('hris.employeeatr.ajax_periksaenroll_id')}}",
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        data: {
                            enroll_id:enroll_id,
                        },
                        dataType: 'json',
                        success: function(res){
                            notif({
                                msg: "<b>Info:</b> Nomor Absen BISA DIGUNAKAN.",
                                type: "info"
                            });
                            $("#btn-periksa_enroll_id").attr("disabled", true);
                            $("#enroll_id").attr("readonly", true);
                            $('#is_periksaenroll_id').val(1);
                        },
                        error: function(res){
                            notif({
                                msg: "<b>Error:</b> Nomor Absen SUDAH ADA.",
                                type: "error"
                            });
                            $("#btn-periksa_enroll_id").attr("disabled", false);
                            $("#enroll_id").attr("readonly", false);
                        }
                    });
                } else {
                    notif({
                        msg: "<b>Info:</b> Nomor Absen BISA DIGUNAKAN.",
                        type: "info"
                    });
                    $("#btn-periksa_enroll_id").attr("disabled", true);
                    $("#enroll_id").attr("readonly", true);
                    $('#is_periksaenroll_id').val(1);
                }
            } else {
                notif({
                    msg: "<b>Warning:</b> Silakan isi Nomor Absen.",
                    type: "warning"
                });
                $("#btn-periksa_enroll_id").attr("disabled", false);
            }

            $('#btn-periksa_enroll_id').removeClass("btn-loading");
            $("#btn-periksa_enroll_id").html('<span><i class="fa fa-search"></i></span> Cek');
        });

        $('body').on('click', '#btn-periksa_nik', function () {
            $('#is_periksanik').val(0);
            var nik = $('#nik').val();
            var enroll_id = $('#enroll_id').val();
            var is_periksanik = $('#is_periksanik').val();
            $('#btn-periksa_nik').addClass("btn-loading");
            $("#btn-periksa_nik").html('Please wait...');
            $("#btn-periksa_nik").attr("disabled", true);

            if (nik) {
                if ((is_periksanik == 0) || (is_periksanik == '')) {
                    $.ajax({
                        type:"POST",
                        url: "{{route('hris.employeeatr.ajax_periksanik')}}",
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        data: {
                            nik:nik,
                            enroll_id:enroll_id
                        },
                        dataType: 'json',
                        success: function(res){
                            notif({
                                msg: "<b>Info:</b> NIK BISA DIGUNAKAN.",
                                type: "info"
                            });
                            $("#btn-periksa_nik").attr("disabled", true);
                            $("#nik").attr("readonly", true);
                            $('#is_periksanik').val(1);
                        },
                        error: function(res){
                            notif({
                                msg: "NIK SUDAH ADA / NIRWANA ID SALAH / ID TIDAK SAMA",
                                type: "error"
                            });
                            $("#btn-periksa_nik").attr("disabled", false);
                            $("#nik").attr("readonly", false);
                            $('#is_periksanik').val(0);
                        }
                    });
                } else {
                    notif({
                        msg: "<b>Info:</b> NIK BISA DIGUNAKAN.",
                        type: "info"
                    });
                    $("#btn-periksa_nik").attr("disabled", true);
                    $("#nik").attr("readonly", true);
                    $('#is_periksanik').val(1);
                }
            } else {
                notif({
                    msg: "<b>Warning:</b> Silakan isi NIK.",
                    type: "warning"
                });
                $("#btn-periksa_nik").attr("disabled", false);
            }

            $('#btn-periksa_nik').removeClass("btn-loading");
            $("#btn-periksa_nik").html('<span><i class="fa fa-search"></i></span> Cek');

        });

        $('body').on('change', '#nik',function(event){
            $('#is_periksanik').val(0);
        });
        $('body').on('click', '#btn-save', function (event) {
            var is_periksaenroll_id = $('#is_periksaenroll_id').val();
            var is_periksanik = $('#is_periksanik').val();

            var employee_name = $('#employee_name').val();
            var jenis_kelamin = $('#jenis_kelamin').val();
            var tempat_lahir = $('#tempat_lahir').val();
            var tanggal_lahir = $('#tanggal_lahir').val();
            var nomor_ktp = $('#nomor_ktp').val();
            var site_nirwana_id = $('#site_nirwana_id').val();
            var department_id = $('#department_id').val();
            var sub_dept_id = $('#sub_dept_id').val();
            var enroll_id = $('#enroll_id').val();
            var join_date = $('#join_date').val();
            var tanggal_mulai_kontrak = $('#tanggal_mulai_kontrak').val();
            var tanggal_akhir_kontrak = $('#tanggal_akhir_kontrak').val();
            var nik = $('#nik').val();
            var status_aktif = $('#status_aktif').val();
            var status_staff = $('#status_staff').val();

            var alamat_jalan = $('#alamat_jalan').val();
            var rt = $('#rt').val();
            var rw = $('#rw').val();
            var kode_pos = $('#kode_pos').val();

            if(!alamat_jalan)
            {
                notif({
                    msg: "<b>Error:</b> Oops Alamat Jalan belum di isi.",
                    type: "error"
                });

                $("#alamat_jalan").addClass('border-danger');

                return false;
            }
            if(!rt)
            {
                notif({
                    msg: "<b>Error:</b> Oops RT belum di isi.",
                    type: "error"
                });

                $("#rt").addClass('border-danger');

                return false;
            }
            if(!rw)
            {
                notif({
                    msg: "<b>Error:</b> Oops RW belum di isi.",
                    type: "error"
                });

                $("#rw").addClass('border-danger');

                return false;
            }
            // if(!kode_pos)
            // {
            //     notif({
            //         msg: "<b>Error:</b> Oops Kode Pos belum di isi.",
            //         type: "error"
            //     });

            //     $("#kode_pos").addClass('border-danger');

            //     return false;
            // }

            if(!employee_name)
            {
                notif({
                    msg: "<b>Error:</b> Oops Nama Karyawan belum di isi.",
                    type: "error"
                });

                $("#employee_name").addClass('border-danger');

                return false;
            }

            if(!jenis_kelamin)
            {
                notif({
                    msg: "<b>Error:</b> Oops Jenis Kelamin belum di isi.",
                    type: "error"
                });

                $("#jenis_kelamin").addClass('border-danger');

                return false;
            }

            if(!tempat_lahir)
            {
                notif({
                    msg: "<b>Error:</b> Oops Tempat Lahir belum di isi.",
                    type: "error"
                });

                $("#tempat_lahir").addClass('border-danger');

                return false;
            }

            if(!tanggal_lahir)
            {
                notif({
                    msg: "<b>Error:</b> Oops Tanggal Lahir belum di isi.",
                    type: "error"
                });

                $("#tanggal_lahir").addClass('border-danger');

                return false;
            }

            var isDate = isValidDate(tanggal_lahir);

            if(!isDate)
            {
                notif({
                    msg: "<b>Error:</b> Oops Tanggal Lahir formatnya salah.",
                    type: "error"
                });

                return false;

            } else {
                tanggal_lahir = defaultDate(tanggal_lahir);
            }

            if(!nomor_ktp)
            {
                notif({
                    msg: "<b>Error:</b> Oops Nomor KTP belum di isi.",
                    type: "error"
                });

                $("#nomor_ktp").addClass('border-danger');

                return false;
            }

            if(!site_nirwana_id)
            {
                notif({
                    msg: "<b>Error:</b> Oops Divisi belum di pilih.",
                    type: "error"
                });

                $("#site_nirwana_id").addClass('border-danger');

                return false;
            }

            if(!department_id)
            {
                notif({
                    msg: "<b>Error:</b> Oops Department belum di pilih.",
                    type: "error"
                });

                $("#department_id").addClass('border-danger');

                return false;
            }

            if(!sub_dept_id)
            {
                notif({
                    msg: "<b>Error:</b> Oops Bagian belum di pilih.",
                    type: "error"
                });

                $("#sub_dept_id").addClass('border-danger');

                return false;
            }

            if(!enroll_id)
            {
                notif({
                    msg: "<b>Error:</b> Oops Nomor Absen belum di isi.",
                    type: "error"
                });

                $("#enroll_id").addClass('border-danger');

                return false;
            }

            if(!join_date)
            {
                notif({
                    msg: "<b>Error:</b> Oops Tanggal Masuk belum di isi.",
                    type: "error"
                });

                $("#join_date").addClass('border-danger');

                return false;
            }

            if(!nik)
            {
                notif({
                    msg: "<b>Error:</b> Oops NIK belum di isi.",
                    type: "error"
                });

                $("#nik").addClass('border-danger');

                return false;
            }

            if(!status_aktif)
            {
                notif({
                    msg: "<b>Error:</b> Oops AKTIF/TIDAK AKTIF belum di pilih.",
                    type: "error"
                });

                $("#status_aktif").addClass('border-danger');

                return false;
            }
            if(!tanggal_mulai_kontrak)
            {
                notif({
                    msg: "<b>Error:</b> Oops Tanggal Mulai Kontrak belum di pilih.",
                    type: "error"
                });

                $("#tanggal_mulai_kontrak").addClass('border-danger');

                return false;
            }
            if(!tanggal_akhir_kontrak)
            {
                notif({
                    msg: "<b>Error:</b> Oops Tanggal Akhir Kontrak belum di pilih.",
                    type: "error"
                });

                $("#tanggal_akhir_kontrak").addClass('border-danger');

                return false;
            }

            if(!status_staff)
            {
                notif({
                    msg: "<b>Error:</b> Oops STAFF/NON STAFF belum di pilih.",
                    type: "error"
                });

                $("#status_staff").addClass('border-danger');

                return false;
            }
            if($('#nama_bank').val() == '') {
                    notif({
                        msg: "<b>Error:</b> Oops Nama Bank belum di pilih.",
                        type: "error"
                        });
                $("#nama_bank").addClass('border-danger');
                return false;
                }


            if($('#tanggal_resign').val()) {
                var tgl = defaultDate($('#tanggal_resign').val());
                var tanggal = tgl.split(' s/d ');

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

                            $('#btn-save').addClass("btn-loading");
                            $("#btn-save").html('Please wait...');
                            $("#btn-save").attr("disabled", true);
                            $('#progress-show-1').show();
                            $('#progress-hide-1').hide();

                            join_date = defaultDate(join_date);
                            tanggal_resign = defaultDate($('#tanggal_resign').val());
                            tanggal_bpjs_ketenagakerjaan = defaultDate($('#tanggal_bpjs_ketenagakerjaan').val());
                            tanggal_bpjs_kesehatan = defaultDate($('#tanggal_bpjs_kesehatan').val());
                            tanggal_vaccine1 = defaultDate($('#tanggal_vaccine1').val());
                            tanggal_vaccine2 = defaultDate($('#tanggal_vaccine2').val());
                            tanggal_vaccine3 = defaultDate($('#tanggal_vaccine3').val());
                            tanggal_expire_sim = defaultDate($('#tanggal_expire_sim').val());
                            tanggal_mulai_kontrak = defaultDate(tanggal_mulai_kontrak);
                            tanggal_akhir_kontrak = defaultDate(tanggal_akhir_kontrak);

                            var form1Data = {
                                employee_name:employee_name,
                                jenis_kelamin:jenis_kelamin,
                                tempat_lahir:tempat_lahir,
                                tanggal_lahir:tanggal_lahir,
                                golongan_darah:$('#golongan_darah').val(),
                                email:$('#email').val(),
                                nomor_tlpn:$('#nomor_tlpn').val(),
                                agama:$('#agama').val(),
                                status_kawin:$('#status_kawin').val(),
                                npwp:$('#npwp').val(),
                                nomor_ktp:nomor_ktp,
                                nomor_kk:$('#nomor_kk').val(),
                                ptkp:$('#ptkp').val(),
                                pendidikan_terakhir:$('#pendidikan_terakhir').val(),
                                jurusan_pendidikan:$('#jurusan_pendidikan').val(),
                                nama_bank:$('#nama_bank').val(),
                                nomor_rekening_bank:$('#nomor_rekening_bank').val(),
                                ibu_kandung:$('#ibu_kandung').val(),
                                propinsi:$('#propinsi').val(),
                                kota_kab:$('#kota_kab').val(),
                                kecamatan:$('#kecamatan').val(),
                                kelurahan_desa:$('#kelurahan_desa').val(),
                                alamat_rumah:$('#alamat_rumah').val(),
                                alamat_sementara:$('#alamat_sementara').val(),
                                site_nirwana_id:site_nirwana_id,
                                site_nirwana_name:$('#site_nirwana_id').text(),
                                department_id:department_id,
                                department_name:$('#department_id').text(),
                                sub_dept_id:sub_dept_id,
                                sub_dept_name:$('#sub_dept_id').text(),
                                sewing_nonsewing:$('#sewing_nonsewing').val(),
                                direct_indirect:$('#direct_indirect').val(),
                                enroll_id:enroll_id,
                                join_date:join_date,
                                nik:nik,
                                status_aktif:status_aktif,
                                status_jabatan:$('#status_jabatan').val(),
                                status_kontrak_tetap:$('#status_kontrak_tetap').val(),
                                status_staff:status_staff,
                                tanggal_resign:tanggal_resign,
                                tunjangan:$('#tunjangan').val(),
                                kode_grade:$('#kode_grade').val(),
                                referensi:$('#referensi').val(),
                                employee_name_atasan:$('#employee_name_atasan').val(),
                                status_aktif_bpjs_tk:$('#status_aktif_bpjs_tk').val(),
                                tanggal_bpjs_ketenagakerjaan:tanggal_bpjs_ketenagakerjaan,
                                nomor_bpjs_ketenagakerjaan:$('#nomor_bpjs_ketenagakerjaan').val(),
                                status_aktif_bpjs_ks:$('#status_aktif_bpjs_ks').val(),
                                tanggal_bpjs_kesehatan:tanggal_bpjs_kesehatan,
                                nomor_bpjs_kesehatan:$('#nomor_bpjs_kesehatan').val(),
                                premi:$('#premi').val(),
                                pengalaman_bekerja:$('#pengalaman_bekerja').val(),
                                lokasi_file_cv:$('#lokasi_file_cv').val(),
                                nama_kerabat:$('#nama_kerabat').val(),
                                nomor_tlpn_kerabat:$('#nomor_tlpn_kerabat').val(),
                                hubungan_kerabat:$('#hubungan_kerabat').val(),
                                alamat_kerabat:$('#alamat_kerabat').val(),
                                tanggal_vaccine1:tanggal_vaccine1,
                                nama_vaksin1:$('#nama_vaksin1').val(),
                                tanggal_vaccine2:tanggal_vaccine2,
                                nama_vaksin2:$('#nama_vaksin2').val(),
                                tanggal_vaccine3:tanggal_vaccine3,
                                nama_vaksin3:$('#nama_vaksin3').val(),
                                golongan_sim:$('#golongan_sim').val(),
                                nomor_sim:$('#nomor_sim').val(),
                                tanggal_expire_sim:tanggal_expire_sim,
                                catatan:$('#catatan').val(),
                                sebab_resign:$('#sebab_resign').val(),
                                no_surat:$('#no_surat').val(),
                                lokasi_foto:$('#lokasi_foto').val(),
                                tanggal_mulai_kontrak:tanggal_mulai_kontrak,
                                tanggal_akhir_kontrak:tanggal_akhir_kontrak,
                                catatan_kontrak:$('#catatan_kontrak').val(),
                                alamat_jalan:alamat_jalan,
                                rt:rt,
                                rw:rw,
                                kode_pos:kode_pos,
                            };


                            if (is_periksaenroll_id == 1) {
                                if (is_periksanik == 1) {

                                    $.ajax({
                                        type:"POST",
                                        url: "{{route('hris.employeeatr.replace')}}",
                                        dataType: 'json',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                        data: form1Data,
                                        dataType: 'json',
                                        success: function(res){
                                            notif({
                                                msg: "<b>Info:</b> Data BERHASIL di Simpan.",
                                                type: "info"
                                            });
                                            $("#btn-periksa_enroll_id").attr("disabled", true);
                                            $("#enroll_id").attr("readonly", true);
                                            $('#is_periksaenroll_id').val(1);
                                            $('#is_periksanik').val(1);

                                            $('#progress-show-1').hide();
                                            $('#progress-hide-1').show();
                                            $('#btn-save').removeClass("btn-loading");
                                            $("#btn-save").html('<span><i class="fa fa-save"></i></span> Add');
                                            $("#form1 :input").prop("disabled", true);
                                            $("#btn-save").prop("disabled", true);
                                            $("#btn-cancel").prop("disabled", true);

                                            // setTimeout(function myFunction() {
                                            //     location.reload();
                                            // }, 3000);

                                            $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);

                                        },
                                        error: function(res){
                                            notif({
                                                msg: "<b>Error:</b> Oops Data GAGAL di Simpan.",
                                                type: "error"
                                            });
                                            $("#btn-periksa_enroll_id").attr("disabled", false);
                                            $("#enroll_id").attr("readonly", false);
                                            $('#is_periksaenroll_id').val(1);
                                            $('#is_periksanik').val(1);

                                            $('#progress-show-1').hide();
                                            $('#progress-hide-1').show();
                                            $('#btn-save').removeClass("btn-loading");
                                            $("#btn-save").html('<span><i class="fa fa-save"></i></span> Add');

                                        }
                                    });

                                } else {
                                    notif({
                                        msg: "<b>Warning:</b> Silakan Periksa NIK nya.",
                                        type: "warning"
                                    });
                                    $('#is_periksaenroll_id').val(1);
                                    $('#is_periksanik').val(0);

                                    $('#progress-show-1').hide();
                                    $('#progress-hide-1').show();
                                    $('#btn-save').removeClass("btn-loading");
                                    $("#btn-save").html('<span><i class="fa fa-save"></i></span> Add');

                                }
                            } else {
                                notif({
                                    msg: "<b>Warning:</b> Silakan Periksa Nomor Absen nya.",
                                    type: "warning"
                                });
                                $('#is_periksaenroll_id').val(0);

                                $('#progress-show-1').hide();
                                $('#progress-hide-1').show();
                                $('#btn-save').removeClass("btn-loading");
                                $("#btn-save").html('<span><i class="fa fa-save"></i></span> Add');
                            }
                        }

                    },
                    error: function(res){

                    }
                });
            } else {
                $('#btn-save').addClass("btn-loading");
                $("#btn-save").html('Please wait...');
                $("#btn-save").attr("disabled", true);
                $('#progress-show-1').show();
                $('#progress-hide-1').hide();

                join_date = defaultDate(join_date);
                tanggal_resign = defaultDate($('#tanggal_resign').val());
                tanggal_bpjs_ketenagakerjaan = defaultDate($('#tanggal_bpjs_ketenagakerjaan').val());
                tanggal_bpjs_kesehatan = defaultDate($('#tanggal_bpjs_kesehatan').val());
                tanggal_vaccine1 = defaultDate($('#tanggal_vaccine1').val());
                tanggal_vaccine2 = defaultDate($('#tanggal_vaccine2').val());
                tanggal_vaccine3 = defaultDate($('#tanggal_vaccine3').val());
                tanggal_expire_sim = defaultDate($('#tanggal_expire_sim').val());
                tanggal_mulai_kontrak = defaultDate(tanggal_mulai_kontrak);
                tanggal_akhir_kontrak = defaultDate(tanggal_akhir_kontrak);

                var form1Data = {
                    employee_name:employee_name,
                    jenis_kelamin:jenis_kelamin,
                    tempat_lahir:tempat_lahir,
                    tanggal_lahir:tanggal_lahir,
                    golongan_darah:$('#golongan_darah').val(),
                    email:$('#email').val(),
                    nomor_tlpn:$('#nomor_tlpn').val(),
                    agama:$('#agama').val(),
                    status_kawin:$('#status_kawin').val(),
                    npwp:$('#npwp').val(),
                    nomor_ktp:nomor_ktp,
                    nomor_kk:$('#nomor_kk').val(),
                    ptkp:$('#ptkp').val(),
                    pendidikan_terakhir:$('#pendidikan_terakhir').val(),
                    jurusan_pendidikan:$('#jurusan_pendidikan').val(),
                    nama_bank:$('#nama_bank').val(),
                    nomor_rekening_bank:$('#nomor_rekening_bank').val(),
                    ibu_kandung:$('#ibu_kandung').val(),
                    propinsi:$('#propinsi').val(),
                    kota_kab:$('#kota_kab').val(),
                    kecamatan:$('#kecamatan').val(),
                    kelurahan_desa:$('#kelurahan_desa').val(),
                    alamat_rumah:$('#alamat_rumah').val(),
                    alamat_sementara:$('#alamat_sementara').val(),
                    site_nirwana_id:site_nirwana_id,
                    site_nirwana_name:$('#site_nirwana_id').text(),
                    department_id:department_id,
                    department_name:$('#department_id').text(),
                    sub_dept_id:sub_dept_id,
                    sub_dept_name:$('#sub_dept_id').text(),
                    sewing_nonsewing:$('#sewing_nonsewing').val(),
                    direct_indirect:$('#direct_indirect').val(),
                    enroll_id:enroll_id,
                    join_date:join_date,
                    nik:nik,
                    status_aktif:status_aktif,
                    status_jabatan:$('#status_jabatan').val(),
                    status_kontrak_tetap:$('#status_kontrak_tetap').val(),
                    status_staff:status_staff,
                    tanggal_resign:tanggal_resign,
                    tunjangan:$('#tunjangan').val(),
                    kode_grade:$('#kode_grade').val(),
                    referensi:$('#referensi').val(),
                    employee_name_atasan:$('#employee_name_atasan').val(),
                    status_aktif_bpjs_tk:$('#status_aktif_bpjs_tk').val(),
                    tanggal_bpjs_ketenagakerjaan:tanggal_bpjs_ketenagakerjaan,
                    nomor_bpjs_ketenagakerjaan:$('#nomor_bpjs_ketenagakerjaan').val(),
                    status_aktif_bpjs_ks:$('#status_aktif_bpjs_ks').val(),
                    tanggal_bpjs_kesehatan:tanggal_bpjs_kesehatan,
                    nomor_bpjs_kesehatan:$('#nomor_bpjs_kesehatan').val(),
                    premi:$('#premi').val(),
                    pengalaman_bekerja:$('#pengalaman_bekerja').val(),
                    lokasi_file_cv:$('#lokasi_file_cv').val(),
                    nama_kerabat:$('#nama_kerabat').val(),
                    nomor_tlpn_kerabat:$('#nomor_tlpn_kerabat').val(),
                    hubungan_kerabat:$('#hubungan_kerabat').val(),
                    alamat_kerabat:$('#alamat_kerabat').val(),
                    tanggal_vaccine1:tanggal_vaccine1,
                    nama_vaksin1:$('#nama_vaksin1').val(),
                    tanggal_vaccine2:tanggal_vaccine2,
                    nama_vaksin2:$('#nama_vaksin2').val(),
                    tanggal_vaccine3:tanggal_vaccine3,
                    nama_vaksin3:$('#nama_vaksin3').val(),
                    golongan_sim:$('#golongan_sim').val(),
                    nomor_sim:$('#nomor_sim').val(),
                    tanggal_expire_sim:tanggal_expire_sim,
                    catatan:$('#catatan').val(),
                    sebab_resign:$('#sebab_resign').val(),
                    no_surat:$('#no_surat').val(),
                    lokasi_foto:$('#lokasi_foto').val(),
                    tanggal_mulai_kontrak:tanggal_mulai_kontrak,
                    tanggal_akhir_kontrak:tanggal_akhir_kontrak,
                    catatan_kontrak:$('#catatan_kontrak').val(),
                    alamat_jalan:alamat_jalan,
                    rt:rt,
                    rw:rw,
                    kode_pos:kode_pos,
                };

                if (is_periksaenroll_id == 1) {
                    if (is_periksanik == 1) {

                        $.ajax({
                            type:"POST",
                            url: "{{route('hris.employeeatr.replace')}}",
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            data: form1Data,
                            dataType: 'json',
                            success: function(res){
                                notif({
                                    msg: "<b>Info:</b> Data BERHASIL di Simpan.",
                                    type: "info"
                                });
                                $("#btn-periksa_enroll_id").attr("disabled", true);
                                $("#enroll_id").attr("readonly", true);
                                $('#is_periksaenroll_id').val(1);
                                $('#is_periksanik').val(1);

                                $('#progress-show-1').hide();
                                $('#progress-hide-1').show();
                                $('#btn-save').removeClass("btn-loading");
                                $("#btn-save").html('<span><i class="fa fa-save"></i></span> Add');
                                $("#form1 :input").prop("disabled", true);
                                $("#btn-save").prop("disabled", true);
                                $("#btn-cancel").prop("disabled", true);

                                // setTimeout(function myFunction() {
                                //     location.reload();
                                // }, 3000);

                                 $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);

                            },
                            error: function(res){
                                notif({
                                    msg: "<b>Error:</b> Oops Data GAGAL di Simpan.",
                                    type: "error"
                                });
                                $("#btn-periksa_enroll_id").attr("disabled", false);
                                $("#enroll_id").attr("readonly", false);
                                $('#is_periksaenroll_id').val(1);
                                $('#is_periksanik').val(1);

                                $('#progress-show-1').hide();
                                $('#progress-hide-1').show();
                                $('#btn-save').removeClass("btn-loading");
                                $("#btn-save").html('<span><i class="fa fa-save"></i></span> Add');

                            }
                        });

                    } else {
                        notif({
                            msg: "<b>Warning:</b> Silakan Periksa NIK nya.",
                            type: "warning"
                        });
                        $('#is_periksaenroll_id').val(1);
                        $('#is_periksanik').val(0);

                        $('#progress-show-1').hide();
                        $('#progress-hide-1').show();
                        $('#btn-save').removeClass("btn-loading");
                        $("#btn-save").html('<span><i class="fa fa-save"></i></span> Add');

                    }
                } else {
                    notif({
                        msg: "<b>Warning:</b> Silakan Periksa Nomor Absen nya.",
                        type: "warning"
                    });
                    $('#is_periksaenroll_id').val(0);

                    $('#progress-show-1').hide();
                    $('#progress-hide-1').show();
                    $('#btn-save').removeClass("btn-loading");
                    $("#btn-save").html('<span><i class="fa fa-save"></i></span> Add');
                }
            }

        });

        $('body').on('click', '#btn-remove', function (event) {
            var enroll_id = $("#enroll_id").val();
            var employee_name = $("#employee_name").val();

            if (enroll_id.length > 0) {
                message = "Anda Yakin Ingin Menghapus Karyawan [" + enroll_id + "] " + employee_name + " !!!";
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
                            url: "{{route('hris.employeeatr.destroy')}}",
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            data: {
                                enroll_id:enroll_id,
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

                        // setTimeout(function myFunction() {
                        //     location.reload();
                        //   }, 3000);
                         $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);

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
        });

</script>

<script>
    $('#export_grade_bpjs').click(function() {
            notif({
                msg: "<b>Info:</b> Data sedang di PROSES, mohon di tunggu.",
                type: "info"
            });
            $("#export_grade_bpjs").addClass("btn-loading");
            $("#export_grade_bpjs").html('Loading...');
        });

        @if(Session::has('error'))
        var meseg = "{{Session::get('error')}}";
            notif({
                msg: "<b>Error:</b>"+ meseg,
                type: "error"
            });
        @endif

        @if(Session::has('success'))
        var meseg = "{{Session::get('success')}}";
            notif({
                msg: "<b>Info:</b>"+ meseg,
                type: "info"
            });
        @endif

</script>


<script>
    function synchEmployees() {
        notif({
            msg: "<b>Info:</b> Data sedang di PROSES, mohon di tunggu.",
            type: "info"
        });

        $("#synchemployee").addClass("btn-loading");
        $("#synchemployee").html('Loading...');

        $.ajax({
            type:"POST",
            url: "{{route('hris.employeeatr.synchemployees')}}",
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },      
            success: function(res){
                console.log(res);
                notif({
                        msg: "<b>Info:</b> " + res.message,
                        type: res.status
                    });
                $("#synchemployee").removeClass("btn-loading");
                $("#synchemployee").html('<i class="fa fa-file-excel-o"></i> Synch Karyawan');
                $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);
               
            }
        });
    }
</script>
@endsection