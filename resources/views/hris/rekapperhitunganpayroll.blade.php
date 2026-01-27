@extends('admin.adminlayouts.adminlayout')

@section('head')
<!-- Data table css -->
<link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet" />

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
<style>
    tr#element:hover {
        color: azure;
        background-color: rgb(0, 0, 255);
        cursor: pointer;

    }

    .wrapper {
        width: 100%;
        height: 550px;
        overflow: auto;
        position: relative;
    }

    #table-responsive1 {
        table-layout: fixed;
        max-height: 510px;
    }

    thead,
    tr>th {
        position: sticky;
        background: pink;
    }

    thead {
        top: 0;
        z-index: 2;
    }

    tr>th {
        left: 0;
        z-index: 1;
    }

    thead tr>th:first-child {
        z-index: 3;
    }
</style>

<style>
    .btn-excel-real {
        display: none !important;
    }

    /* Loading Overlay */
    #excelLoadingOverlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.45);
        backdrop-filter: blur(3px);
        z-index: 99999;
        color: white;
        font-size: 22px;
        text-align: center;
        padding-top: 20%;
    }

    /* ------------------------------
    1. MAIN NAV TABS
    ------------------------------ */
    .custom-tabs {
        border-bottom: 1px solid #dfe3e6;
    }

    .custom-tabs .nav-link {
        font-weight: 600;
        border-radius: 8px 8px 0 0;
        padding: 11px 20px;
        color: #555;
        background: #f4f5f7;
        border: 1px solid #e2e6ea;
        margin-right: 6px;
        transition: 0.25s ease-in-out;
    }

    .custom-tabs .nav-link:hover {
        background: #fff;
        color: #222;
        border-color: #cfd4da;
        transform: translateY(-1px);
    }

    /* ACTIVE TAB */
    .custom-tabs .nav-link.active {
        background: #fff !important;
        color: #1a1a1a !important;
        font-weight: 700;
        border-bottom-color: transparent !important;
        box-shadow: 0 -3px 12px rgba(0, 0, 0, 0.06);
    }

    /* ------------------------------
    2. TAB CONTENT BOX
    ------------------------------ */
    .modern-tab-content {
        border: 1px solid #e2e6ea;
        border-top: none;
        padding: 25px;
        background: #ffffff;
        border-radius: 0 10px 10px 10px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
    }

    /* Container spacing */
    .result-container {
        padding: 10px 5px;
    }

    /* ------------------------------
    3. RESULT BLOCKS
    ------------------------------ */
    .sp-result-block {
        padding: 15px 0 25px 0;
        border-bottom: 1px solid #eceff1;
    }

    .sp-result-block:last-child {
        border-bottom: none;
    }

    .sp-result-block h5 {
        background: linear-gradient(to right, #f5f6f7, #ffffff);
        padding: 10px 16px;
        border-left: 4px solid #0d6efd;
        border-radius: 5px;
        font-size: 15px;
        font-weight: 700;
        color: #333;
        margin-bottom: 12px;
    }

    /* ------------------------------
   4. DATATABLES IMPROVED VISUALS
------------------------------ */
    table.dataTable {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    table.dataTable thead th {
        background: #f0f2f5;
        font-weight: 700;
        font-size: 13px;
        padding: 10px 12px;
        color: #333;
        border-bottom: 2px solid #dce1e5;
    }

    table.dataTable tbody td {
        font-size: 12.6px;
        padding: 8px 10px;
    }

    table.dataTable tbody tr:hover {
        background: #fafafa;
    }

    /* ------------------------------
   5. NICE SCROLLBAR
------------------------------ */
    ::-webkit-scrollbar {
        height: 8px;
        width: 8px;
    }

    ::-webkit-scrollbar-thumb {
        background: #c6c9cc;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #aeb2b7;
    }
</style>

@stop
@section('mainarea')

<div class="modal fade" id="progressModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Processing...</h5>
            </div>
            <div class="modal-body">
                <ul id="progressLog" style="max-height:240px; overflow:auto"></ul>
            </div>
            <div class="modal-footer">
                <button id="closeProgressBtn" class="btn btn-secondary" style="display:none">Close</button>
            </div>
        </div>
    </div>
</div>



<div id="excelLoadingOverlay" style="
        position: fixed;
        top:0; left:0;
        width:100%; height:100%;
        background: rgba(0,0,0,0.55);
        z-index:999999;
        display:none;
        color:white;
        font-size:22px;
        text-align:center;
        padding-top:20%;
        backdrop-filter: blur(2px);
        user-select:none;
        pointer-events: all;
     ">
    <div style="font-size:28px; font-weight:600">Generating Excel...</div>
    <div>Please wait, do not refresh or close this page.</div>
</div>


<!-- page-header -->
<div class="page-header p-2 shadow">
    <ol class="breadcrumb breadcrumb-arrow mt-0">
        <li><a href="#">Perhitungan</a></li>
        <li class="active"><span>Payroll Karyawan</span></li>
    </ol>
    <div class="ml-auto">
        <div class="input-group">
            <a href="#" id="btn-refresh-page" class="btn btn-secondary p-0 mr-0 text-white btn-icon"
                data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Refresh Page">
                <span>
                    <i class="fa fa-refresh"></i>
                </span>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header text-white bg-gradient-primary py-2">
                <div class="card-title">PROSES PAYROLL PER BULAN</div>
                <div class="card-options ">
                    <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                            class="fe fe-chevron-up text-white"></i></a>
                </div>
            </div>
            <div class="card-body pt-5 px-5 pb-7">
                <form id="form_proses_payroll" method="post">
                    @csrf
                    {{-- <div class="row">
                        <div class="col-3 pt-1">
                            <label class="form-label text-primary">PERIODE UMK</label>
                        </div>
                        <div class="col-4">
                            <select id="periode_umk" name="periode_umk" class="form-control PriodeUmk">
                                <option value="" selected>PILIH PERIODE UMK</option>
                                <option value="2024-01">UMK 2024 | 26 DES 2024 S/D 31 DES 2024</option>
                                <option value="2025-01">UMK 2025 | 01 JAN 2025 S/D 25 JAN 2025</option>
                                <option value="2024-2025">26 DES 2024 S/D 25 JAN 2025</option>
                            </select>
                        </div>
                    </div> --}}
                    <div class="row pt-2">
                        <div class="col-3 pt-1">
                            <label class="form-label text-primary">SKEMA PAYROLL</label>
                        </div>
                        <div class="col-8">
                            <select id="skema_payroll" name="skema_payroll" class="form-control">
                                <option value='0'>-- PILIH SKEMA PAYROLL --</option>
                                <option value='MONTHLY_PAYROLL'>MONTHLY PAYROLL (26 - 25)</option>
                                <option value='EARLY_CLOSING'>EARLY CLOSING</option>
                            </select>
                        </div>
                    </div>
                    <div class="row pt-2" id="periode_container">
                        <div class="col-3 pt-1">
                            <label class="form-label text-primary">PERIODE PAYROLL</label>
                        </div>
                        <div class="col-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                    </div>
                                </div>
                                {{-- <input id="" min="{{$minMonth}}" name="periode_payrols" id="periode" type="month"
                                    class="form-control PriodeProses" required> --}}
                                <input id="" name="periode_payrols" id="periode" type="month"
                                    class="form-control PriodeProses" required>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-2" id="periode_container_custom">
                        <div class="col-3 pt-1">
                            <label class="form-label text-primary">PERIODE PAYROLL</label>
                        </div>
                        <div class="col-auto">
                            <input type="hidden" id="daterange2" name="daterange2">
                            <a class="nav-link card-title py-2 pl-3" style="border: 1px solid #d8d4dc"
                                id="daterange-btn2" data-toggle="tooltip" title="" data-placement="bottom"
                                data-original-title="Klik di sini untuk pilih tanggal closing"></a>
                        </div>
                    </div>
                    <div class="row pt-4">
                        <div class="col-3 pt-1">
                            <label class="form-label text-primary">ENROLL ID</label>
                        </div>
                        <div class="col-8">
                            <select id="selectEmployeeID" name="selectEmployeeID[]" multiple
                                data-placeholder="Pilih karyawan" class="form-control select2 EmployeeID"
                                style="width: 699.238px;" required>
                                @foreach ($selectemployee as $r_empl)
                                <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if($loggedAdmin->email=='mega@ptnag.com' || $loggedAdmin->email=='rudy@ptnag.com' ||
                    $loggedAdmin->email=='dev_hris' || $loggedAdmin->email == 'ersa@ptnag.com' ||
                    $loggedAdmin->email=='willy@ptnag.com' || $loggedAdmin->email=='alex.herdian@ptnag.com')
                    <div class="row text-white pt-4">
                        <div class="col-12">
                            <a id="BtnProsesPayroll2"
                                class="btn btn-app btn-sm btn-primary text-white BtnProsesPayroll2"><span><i
                                        class="fa fa-download"></i></span>PROSES REKAP LEMBUR</a>
                            <a id="BtnProsesPayroll"
                                class="btn btn-app btn-sm btn-primary text-white BtnProsesPayroll"><span><i
                                        class="fa fa-download"></i></span> PROSES PAYROLL</a>
                            <button id="runProcessBtn" class="btn btn-info">PROSES PAYROLL NEW</button>
                        </div>
                    </div>
                    <div class="row pt-4">
                        <div class="col-12">
                            <label id="last_periode_payroll" style="color: black"></label>
                            <label id="last_update" style="color: black"></label>
                            <span class="fa fa-refresh" onclick="get_last_update()"
                                style="cursor: pointer;color:rgb(0, 0, 206)"></span>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header text-white bg-gradient-primary py-2">
                <div class="card-title">PROSES PAYROLL PER HARI</div>
                <div class="card-options ">
                    <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                            class="fe fe-chevron-up text-white"></i></a>
                </div>
            </div>
            <div class="card-body p-5">
                <div class="row">
                    <div class="col-3 pt-1">
                        <label class="form-label text-primary">STATUS STAFF</label>
                    </div>
                    <div class="col-8">
                        <select id="status_staff2" class="form-control">
                            <option value=''>-- PILIH STATUS STAFF --</option>
                            <option value='STAFF'>STAFF</option>
                            <option value='NON STAFF'>NON STAFF</option>
                        </select>
                    </div>
                </div>
                <div class="row pt-2">
                    <div class="col-3 pt-1">
                        <label class="form-label text-primary">ENROLL ID</label>
                    </div>
                    <div class="col-8">
                        <select id="selectEmployeeID2" name="selectEmployeeID2[]" multiple
                            data-placeholder="Pilih karyawan" class="form-control select2 EmployeeID"
                            style="width: 699.238px;" required>
                            @foreach ($selectemployee as $r_empl)
                            <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row pt-2">
                    <div class="col-3 pt-1">
                        <label class="form-label text-primary">KEHADIRAN</label>
                    </div>
                    <div class="col-auto">
                        <input type="hidden" id="daterange1" name="daterange1">
                        <a class="nav-link card-title py-2 pl-3" style="border: 1px solid #d8d4dc" id="daterange-btn1"
                            data-toggle="tooltip" title="" data-placement="bottom"
                            data-original-title="Klik di sini untuk pilih tanggal kehadiran"></a>
                    </div>
                </div>
                <div class="row pt-2 text-white">
                    <div class="col-auto">
                        @if($loggedAdmin->email=='mega@ptnag.com' || $loggedAdmin->email=='rudy@ptnag.com' ||
                        $loggedAdmin->email=='dev_hris' || $loggedAdmin->email == 'ersa@ptnag.com')
                        <button id="BtnProsesPayroll4" type="button" class="btn btn-app btn-primary text-white"><span><i
                                    class="fa fa-download"></i></span> PROSES PAYROLL HARIAN</button>
                        @endif
                        @if($loggedAdmin->role_user=='superadmin')
                        <a class="btn btn-app" style="background-color: #13b023" data-toggle="tooltip"
                            title="Export Data ke File Transfer Excel" id="recap_labor_cost_2"><i
                                class="fa fa-file-excel-o" aria-hidden="true"></i> DAILY LABOR COST</a>
                        <button class="btn btn-info" id="btnRecap">DAILY LABOR COST NEW</button>
                        @endif
                    </div>
                </div>
                <div class="row pt-1">
                    <div class="col-12">
                        <label id="last_periode_labor" style="color: black"></label>
                        <label id="last_update_labor" style="color: black"></label>
                        <span class="fa fa-refresh" onclick="get_last_update_labor()"
                            style="cursor: pointer;color:rgb(0, 0, 206)"></span>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- col end -->

</div>
<!-- End page-header -->

<!-- BEGIN FORM-->
{!! Form::open(['route' => 'hris.rekapperhitunganpayroll.ajax_exportexcel', 'id' => 'formExport', 'name' =>
'formExport','method'=>'post']) !!}

@csrf
<input id="selDepVal" name="selDepVal" type="hidden">
<div class="card shadow">
    <div class="card-header bg-primary py-2">
        <div class="card_title" id="card_title" style="font-weight: bold; font-size:12pt"></div>
        <div class="card-options ">
            <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i
                    class="fe fe-chevron-up text-white"></i></a>
        </div>
    </div>
    <div class="card-body py-3 px-4">
        <div class="row">
            <div class="col-md-2 pt-2 pl-4">
                <div class="form-group">
                    <label class="form-label">KARYAWAN </label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group m-0">
                    <select id="selectEmployee" name="selectEmployee[]" multiple data-placeholder="Pilih karyawan"
                        class="form-control form-control-sm select2 Employee">
                        @foreach ($selectemployee as $r_empl)
                        <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 pt-0 pl-4">
                <div class="form-group">
                    <label class="form-label">PERIODE PAYROLL </label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group m-0">
                    <select id="periode_payroll" name="periode_payroll" class="form-control form-control-sm">
                        @foreach ($periode_payroll as $r_periode_payroll)
                        <option value="{{$r_periode_payroll->periode_payroll}}">
                            @php
                            setlocale(LC_ALL, 'id-ID', 'id_ID');
                            $datePeriode = explode("-", $r_periode_payroll->periode_payroll);
                            echo strtoupper(date("F", mktime(0, 0, 0, $datePeriode[1], 10))) . ' ' . $datePeriode[0];
                            @endphp
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 pt-0 pl-4">
                <div class="form-group">
                    <label class="form-label">DEPARTMENT </label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group m-0">
                    <select id="department_id" name="department_id" class="form-control form-control-sm">
                        <option value="">PILIH DEPARTMENT</option>
                        @foreach ($department_id as $key=>$value)
                        <option value={{$value->department_id}}>{{$value->department_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 pt-0 pl-4">
                <div class="form-group">
                    <label class="form-label">BAGIAN</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group m-0">
                    <select id="sub_dept_id" name="sub_dept_id" class="form-control form-control-sm">
                        <option value="">PILIH BAGIAN</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 pt-0 pl-4">
                <div class="form-group">
                    <label class="form-label">STATUS STAFF</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group m-0">
                    <select id="status_staff" name="status_staff" class="form-control form-control-sm">
                        <option value="">PILIH STATUS</option>
                        <option value="STAFF">STAFF</option>
                        <option value="NON STAFF">NON STAFF</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 pt-0 pl-4">
                <div class="form-group">
                    <label class="form-label">PERIODE UMK</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group m-0">
                    <select id="periode_umks" name="periode_umks" class="form-control form-control-sm">
                        <option value="">PERIODE UMK</option>
                        <option value="2024-01">UMK 2024</option>
                        <option value="2025-12">UMK 2025</option>
                        <option value="2026-01">UMK 2026</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row pt-2 pl-2">
            <div class="col-auto">
                <div class="input-group-append">
                    <button type="submit" id="btn-exportexcel" class="btn btn-app" style="background-color: #13b023"
                        data-toggle="tooltip" title="Export Data ke File Excel"><i class="fa fa-file-excel-o"
                            aria-hidden="true"></i> EMPLOYEE PAYROLL</button>
                </div>
            </div>
            @if($loggedAdmin->role_user=='superadmin' || $loggedAdmin->email=='firmansyah@nirwanaindonesia.com' ||
            $loggedAdmin->email=='willy@ptnag.com' || $loggedAdmin->email=='ronald@ptnag.com' )
            <div class="col-auto pl-0">
                <div class="input-group-append">
                    <a onclick="func();" class="btn btn-app" style="background-color: #13b023" data-toggle="tooltip"
                        title="Export Data ke File Transfer Excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i>
                        TRANSFER LIST</a>
                </div>
            </div>
            <div class="col-auto pl-0">
                <div class="input-group-append">
                    <a onclick="export_excel_summary_department();" class="btn btn-app"
                        style="background-color: #13b023" data-toggle="tooltip"
                        title="Export Data ke File Transfer Excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i>
                        SUMMARY DEPARTMENT</a>
                </div>
            </div>
            @endif
            {!! Form::close() !!}
        </div>
    </div>
    @if($loggedAdmin->role_user=='superadmin' || $loggedAdmin->email=='firmansyah@nirwanaindonesia.com' ||
    $loggedAdmin->email=='willy@ptnag.com' || $loggedAdmin->email=='ronald@ptnag.com' )
    @if($loggedAdmin->email=='firmansyah@nirwanaindonesia.com' || $loggedAdmin->email=='ronald@ptnag.com' ||
    $loggedAdmin->email=='willy@ptnag.com' )
    <div class="card-header bg-primary py-1 pl-1">
        <ul class="nav nav-tabs mx-0" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active py-2" id="verifikasi-tab" data-toggle="tab" href="#verifikasi_tab" role="tab"
                    aria-controls="verifikasi" aria-selected="false" style="font-weight: bold; font-size:11pt">DATA
                    PAYROLL DEPARTMENT</a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-2" id="waiting-tab" data-toggle="tab" href="#waiting" role="tab"
                    aria-controls="waiting" aria-selected="true" style="font-weight: bold; font-size:11pt">DATA PAYROLL
                    EMPLOYEE</a>
            </li>
        </ul>
    </div>
    <div class="card-body px-2 py-0">
        <div id="data-lembur" class="card shadow tab-content">
            <div class="tab-pane fade show active" id="verifikasi_tab" role="tabpanel" aria-labelledby="verifikasi-tab">
                <div class="col-12 text-dark">
                    <div class="wrapper">
                        <div class="table-responsive" id="table-responsive1">
                            <table style="width:2100px" border="1">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-center" width='250px'>DEPARTMENT</th>
                                        <th class="px-4 py-2 text-center" width='150px'>JUMLAH KARYAWAN</th>
                                        <th class="px-4 py-2 text-center" width='150px'>BRUTO</th>
                                        <th class="px-4 py-2 text-center" width='50px'>PPH</th>
                                        <th class="px-4 py-2 text-center" width='150px'>NETTO</th>
                                        <th class="px-4 py-2 text-center" width='150px'>BPJS TK</th>
                                        <th class="px-4 py-2 text-center" width='150px'>BPJS KS</th>
                                        <th class="px-4 py-2 text-center" width='150px'>TOTAL POTONGAN</th>
                                        <th class="px-4 py-2 text-center" width='150px'>JUMLAH</th>
                                        <th class="px-4 py-2 text-center" width='150px'></th>
                                        <th class="px-4 py-2 text-center" width='150px'>JML KARYAWAN SEBELUMNYA</th>
                                        <th class="px-4 py-2 text-center" width='150px'>JML GAJI SEBELUMNYA</th>
                                        <th class="px-4 py-2 text-center" width='150px'>PENURUNAN/KENAIKAN JML KARYAWAN
                                        </th>
                                        <th class="px-4 py-2 text-center" width='150px'>PENURUNAN/KENAIKAN GAJI</th>
                                    </tr>
                                </thead>
                                <tbody id="payroll_department">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="waiting" role="tabpanel" aria-labelledby="waiting-tab">
                <div class="col-12 text-dark">
                    <div class="table-responsive">
                        <table style="width:2700px" id="datatable-ajax-crud"
                            class="table table-sm table-striped table-hover table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th style="background-color: white" scope="col" width="20"></th>
                                    <th style="background-color: white" scope="col" width="75"></th>
                                    <th style="background-color: white" scope="col" width="400"></th>
                                    <th style="background-color: white" scope="col" width="20"></th>
                                    <th style="background-color: white" scope="col" width="90"></th>
                                    <th style="background-color: white" scope="col" width="90"></th>
                                    <th style="background-color: white" scope="col" width="90"></th>
                                    <th style="background-color: white" scope="col" width="90"></th>
                                    <th style="background-color: white" scope="col" width="90"></th>
                                    <th style="background-color: white" scope="col" width="90"></th>
                                    <th style="background-color: white" scope="col"></th>
                                    <th style="background-color: white" scope="col"></th>
                                    <th style="background-color: white" scope="col"></th>
                                    <th style="background-color: white" scope="col"></th>
                                    <th style="background-color: white" scope="col"></th>
                                    <th style="background-color: white" scope="col"></th>
                                    <th style="background-color: white" scope="col"></th>
                                    <th style="background-color: white" scope="col"></th>
                                    <th style="background-color: white" scope="col"></th>
                                    <th style="background-color: white" scope="col"></th>
                                    <th style="background-color: white" scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="card-header bg-primary py-1 pl-1">
        <ul class="nav nav-tabs mx-0" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active py-2" id="waiting-tab" data-toggle="tab" href="#waiting" role="tab"
                    aria-controls="waiting" aria-selected="true" style="font-weight: bold; font-size:11pt">DATA PAYROLL
                    EMPLOYEE</a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-2" id="verifikasi-tab" data-toggle="tab" href="#verifikasi_tab" role="tab"
                    aria-controls="verifikasi" aria-selected="false" style="font-weight: bold; font-size:11pt">DATA
                    PAYROLL DEPARTMENT</a>
            </li>
        </ul>
    </div>
    <div class="card-body px-2 py-0">
        <div id="data-lembur" class="card shadow tab-content">
            <div class="tab-pane fade show active" id="waiting" role="tabpanel" aria-labelledby="waiting-tab">
                <div class="col-12 text-dark">
                    <div class="table-responsive">
                        <table style="width:2700px" id="datatable-ajax-crud"
                            class="table table-sm table-striped table-hover table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th style="background-color: white" scope="col" width="20"></th>
                                    <th style="background-color: white" scope="col" width="75"></th>
                                    <th style="background-color: white" scope="col" width="400"></th>
                                    <th style="background-color: white" scope="col" width="20"></th>
                                    <th style="background-color: white" scope="col" width="90"></th>
                                    <th style="background-color: white" scope="col" width="90"></th>
                                    <th style="background-color: white" scope="col" width="90"></th>
                                    <th style="background-color: white" scope="col" width="90"></th>
                                    <th style="background-color: white" scope="col" width="90"></th>
                                    <th style="background-color: white" scope="col" width="90"></th>
                                    <th style="background-color: white" scope="col"></th>
                                    <th style="background-color: white" scope="col"></th>
                                    <th style="background-color: white" scope="col"></th>
                                    <th style="background-color: white" scope="col"></th>
                                    <th style="background-color: white" scope="col"></th>
                                    <th style="background-color: white" scope="col"></th>
                                    <th style="background-color: white" scope="col"></th>
                                    <th style="background-color: white" scope="col"></th>
                                    <th style="background-color: white" scope="col"></th>
                                    <th style="background-color: white" scope="col"></th>
                                    <th style="background-color: white" scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="verifikasi_tab" role="tabpanel" aria-labelledby="verifikasi-tab">
                <div class="col-12 text-dark">
                    <div class="table-responsive" id="table-responsive1">
                        <table style="width:2100px" border="1">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-center" width='250px'>DEPARTMENT</th>
                                    <th class="px-4 py-2 text-center" width='150px'>JUMLAH KARYAWAN</th>
                                    <th class="px-4 py-2 text-center" width='150px'>BRUTO</th>
                                    <th class="px-4 py-2 text-center" width='50px'>PPH</th>
                                    <th class="px-4 py-2 text-center" width='150px'>NETTO</th>
                                    <th class="px-4 py-2 text-center" width='150px'>BPJS TK</th>
                                    <th class="px-4 py-2 text-center" width='150px'>BPJS KS</th>
                                    <th class="px-4 py-2 text-center" width='150px'>TOTAL POTONGAN</th>
                                    <th class="px-4 py-2 text-center" width='150px'>JUMLAH</th>
                                    <th class="px-4 py-2 text-center" width='150px'></th>
                                    <th class="px-4 py-2 text-center" width='150px'>JML KARYAWAN SEBELUMNYA</th>
                                    <th class="px-4 py-2 text-center" width='150px'>JML GAJI SEBELUMNYA</th>
                                    <th class="px-4 py-2 text-center" width='150px'>PENURUNAN/KENAIKAN JML KARYAWAN</th>
                                    <th class="px-4 py-2 text-center" width='150px'>PENURUNAN/KENAIKAN GAJI</th>
                                </tr>
                            </thead>
                            <tbody id="payroll_department">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <div id="loading_payroll_department">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif
    {{-- @if($loggedAdmin->role_user=='superadmin' || $loggedAdmin->email=='firmansyah@nirwanaindonesia.com' ||
    $loggedAdmin->email=='willy@ptnag.com' )
    <div class="card-header bg-primary p-2">
        <table width="100%">
            <tr>
                <td width="99%">
                    <div class="card-title" id="card_title_payroll_department"></div>
                </td>
                <td width="1%" align="right"><a href="#" class="card-options-collapse mr-2"
                        data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a></td>
            </tr>
        </table>
    </div>
    <div class="card-body" style="height: 500px">

    </div>
    <div class="card-header bg-primary p-2">
        <table width="100%">
            <tr>
                <td width="80%">
                    <div class="card-title" id="card_title"></div>
                </td>
                <td width="19%" align="right">
                    <div class="card-title pr-5" id="card_department"></div>
                </td>
                <td width="1%" align="right"><a href="#" class="card-options-collapse mr-2"
                        data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a></td>
            </tr>
        </table>
    </div>
    {{-- @endif --}}




</div>


<div class="card shadow">
    <div class="card-header bg-info py-2">
        <div class="card" id="card-data" style="font-weight: bold; font-size:12pt"></div>
        <div class="card-options ">
            <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i
                    class="fe fe-chevron-up text-white"></i></a>
        </div>
    </div>
    <div class="card-body py-3 px-4">


        <!-- PROCESS TABS -->
        <ul class="nav nav-tabs custom-tabs" id="processTabs">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab1">Presence</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab2">Overtime</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab3">Permission</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab4">Late/Early</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab5">BPJS</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab6">Salary</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab7">Per Department</a></li>
        </ul>

        <!-- TAB CONTENT -->
        <div class="tab-content modern-tab-content mt-3">

            <div id="tab1" class="tab-pane fade show active">
                <div id="container_1" class="result-container"></div>
            </div>

            <div id="tab2" class="tab-pane fade">
                <div id="container_2" class="result-container"></div>
            </div>

            <div id="tab3" class="tab-pane fade">
                <div id="container_3" class="result-container"></div>
            </div>

            <div id="tab4" class="tab-pane fade">
                <div id="container_4" class="result-container"></div>
            </div>

            <div id="tab5" class="tab-pane fade">
                <div id="container_5" class="result-container"></div>
            </div>

            <div id="tab6" class="tab-pane fade">
                <div id="container_6" class="result-container"></div>
            </div>
            <div id="tab7" class="tab-pane fade">
                <div class="table-responsive">
                    <table id="table-payroll-dept" class="table table-bordered table-sm table-payroll"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>DEPARTMENT</th>
                                <th>JUMLAH KARYAWAN</th>
                                <th>BRUTO</th>
                                <th>PPH</th>
                                <th>NETTO</th>
                                <th>BPJS TK</th>
                                <th>BPJS KS</th>
                                <th>TOTAL POTONGAN</th>
                                <th>JUMLAH</th>
                                <th>JML KARYAWAN SEBELUMNYA</th>
                                <th>JML GAJI SEBELUMNYA</th>
                                <th>PENURUNAN/KENAIKAN JML KARYAWAN</th>
                                <th>PENURUNAN/KENAIKAN GAJI</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

@section('footerjs')

<!--Jquery Sparkline js-->
<script src=" {{ URL::asset('assets/plugins/vendors/jquery.sparkline.min.js') }}"></script>

<!-- INTERNAL Data tables -->
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}">
</script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}">
</script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}">
</script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}">
</script>
<script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}">
</script>
<script src="{{ URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.js') }}">
</script>
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
<script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}">
</script>
<script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
            const skemaPayroll = document.getElementById("skema_payroll");
            const periodeContainer = document.getElementById("periode_container");
            const periodeContainerCustom = document.getElementById("periode_container_custom");

            // Sembunyikan semua input saat halaman pertama kali dimuat
            periodeContainer.style.display = "none";
            periodeContainerCustom.style.display = "none";

            skemaPayroll.addEventListener("change", function () {
                if (this.value === "MONTHLY_PAYROLL") {
                    periodeContainer.style.display = "flex"; // Tampilkan Monthly Payroll
                    periodeContainerCustom.style.display = "none"; // Sembunyikan Custom Payroll
                } else if (this.value === "EARLY_CLOSING") {
                    periodeContainer.style.display = "none"; // Sembunyikan Monthly Payroll
                    periodeContainerCustom.style.display = "flex"; // Tampilkan Custom Payroll
                } else {
                    // Jika tidak ada yang dipilih, sembunyikan keduanya
                    periodeContainer.style.display = "none";
                    periodeContainerCustom.style.display = "none";
                }
            });
        });
</script>


<script type="text/javascript">
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#btn-exportexcel', function (event) {
            notif({
                msg: "<b>Info:</b> Data sedang di proses, mohon menunggu",
                type: "info"
            });
        });
        $('body').on('click', '#btn-exportexceltransfer', function (event) {
            let periode_payroll=document.getElementsByName("periode_payroll")[0].value;
            $.ajax({
                type: "post",
                url: '/export_excel_transfer',
                data: {
                    periode_payroll: periode_payroll,
                },
                success: function(res) {
                    notif({
                        msg: "<b>Info:</b> Data sedang di proses, mohon menunggu",
                        type: "info"
                    });
                }
            });
            // notif({
            //     msg: "<b>Info:</b> Data sedang di proses, mohon menunggu",
            //     type: "info"
            // });
        });
        $('body').on('click', '#btn-refresh-page', function (event) {
            location.reload();
        });
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
        });
        $('#daterange-btn2').daterangepicker({
            minDate: moment("2025-03-22"),
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
            $('#daterange-btn2').html('<span><i class="fa fa-calendar"></i> ' + start.format("D MMM YYYY").toUpperCase() + ' s/d ' + end.format("D MMM YYYY").toUpperCase() + '</span><i class="fa fa-angle-down ml-1"></i>');
            var daterange2 = start.format("YYYY-MM-DD") + " s/d " + end.format("YYYY-MM-DD");
            $('#daterange2').val(daterange2);
        });
        $('body').on('click', '#BtnProsesPayroll4', function (event) {
            $('#BtnProsesPayroll4').addClass("btn-loading");
            $("#BtnProsesPayroll4").html('Please wait...');
            $("#BtnProsesPayroll4").attr("disabled", true);
            var enroll_id=$('#selectEmployeeID2').val();
            var daterange1 = $('#daterange1').val();

            jQuery.ajax({
                type : "post",
                url : '{{route('hris.rekapperhitunganpayroll.proses_payroll_harian')}}',
                data : {
                    enroll_id: enroll_id,
                    daterange1:daterange1,
                },
                success:function(response)
                {
                    console.log(response);
                    swal("", "Proses Payroll Harian Berhasil", "success");
                    $('#BtnProsesPayroll4').removeClass("btn-loading");
                    $("#BtnProsesPayroll4").attr("disabled", false);
                    $("#BtnProsesPayroll4").html('<i class="fa fa-download"></i></span> PROSES PAYROLL HARIAN');
                    var dates = daterange1.split(" s/d ");
                    var start_date = dates[0].trim();
                    var end_date   = dates.length > 1 ? dates[1].trim() : dates[0].trim();
                    updateLabor(start_date, end_date);
                },
                error: function(res){
                    swal("", "Proses Payroll Harian Gagal", "error");
                    $('#BtnProsesPayroll4').removeClass("btn-loading");
                    $("#BtnProsesPayroll4").attr("disabled", false);
                    $("#BtnProsesPayroll4").html('<i class="fa fa-download"></i></span> PROSES PAYROLL HARIAN');
                }
            });
        });

        function updateLabor(start_date, end_date) {
            $.ajax({
                type: "post",
                url: "http://10.10.5.62:8123/api/mgt-report-proses/update_data_labor",
                data: {
                    frequency: 'daily',
                    user: 'nds',
                    start_date: start_date,
                    end_date: end_date,
                },
                dataType: "json",
                success: function(response) {
                    // console.log("response", response);
                },
                error: function(res) {
                    // console.log("error",res);
                }
            });
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
</script>
</script>

<!-- Andri -->
<script>
    function rekapperhitunganpayroll(){
            let periode_payroll=document.getElementsByName("periode_payroll")[0].value;
            let department_id=document.getElementById("department_id").value;
            let sub_dept_id=document.getElementById("sub_dept_id").value;
            let status_staff=document.getElementById("status_staff").value;
            let periode_umk=document.getElementById("periode_umks").value;
            var table1 = $('#datatable-ajax-crud').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                "ajax": {
                    "url": "{{ route('hris.rekapperhitunganpayroll.ajax_data') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                    "data": {
                        periode_payroll:periode_payroll,
                        department_id:department_id,
                        sub_dept_id:sub_dept_id,
                        status_staff:status_staff,
                        periode_umk:periode_umk
                    }
                },
                columns: [
                    {
                        title: 'ID',
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
                        name: 'employee_name',
                        width: "200px"
                    },
                    {
                        title: 'HK',
                        data: 'total_kehadiran_net',
                        name: 'total_kehadiran_net'
                    },
                    {
                        title: 'UMK',
                        data: 'upah_per_bulan',
                        name: 'upah_per_bulan'
                    },
                    {
                        title: 'Tunjangan',
                        data: 'tunjangan_karyawan_rupiah',
                        name: 'tunjangan_karyawan_rupiah'
                    },
                    {
                        title: 'RP Lembur 1',
                        data: 'lembur1_rupiah',
                        name: 'lembur1_rupiah'
                    },
                    {
                        title: 'RP Lembur 2',
                        data: 'lembur2_rupiah',
                        name: 'lembur2_rupiah'
                    },
                    {
                        title: 'RP Lembur 3',
                        data: 'lembur3_rupiah',
                        name: 'lembur3_rupiah'
                    },
                    {
                        title: 'RP Lembur 4',
                        data: 'lembur4_rupiah',
                        name: 'lembur4_rupiah'
                    },
                    {
                        title: 'Koreksi Upah',
                        data: 'koreksi_upah_rupiah',
                        name: 'koreksi_upah_rupiah'
                    },
                    {
                        title: 'Koreksi Potongan',
                        data: 'koreksi_potongan_rupiah',
                        name: 'koreksi_potongan_rupiah'
                    },
                    {
                        title: 'Potongan Hari Kerja',
                        data: 'potongan_kehadiran_rupiah',
                        name: 'potongan_kehadiran_rupiah'
                    },
                    {
                        title: 'Potongan Jam',
                        data: 'potongan_jam',
                        name: 'potongan_jam'
                    },
                    {
                        title: 'Bruto',
                        data: 'upah_bruto_rupiah',
                        name: 'upah_bruto_rupiah'
                    },
                    {
                        title: 'PPH',
                        data: 'pph21',
                        name: 'pph21'
                    },
                    {
                        title: 'Netto',
                        data: 'upah_neto_rupiah',
                        name: 'upah_neto_rupiah'
                    },
                    {
                        title: 'Bpjamsostek',
                        data: 'total_bpjs_tk',
                        name: 'total_bpjs_tk'
                    },
                    {
                        title: 'BPJS Kesehatan',
                        data: 'total_bpjs_ks',
                        name: 'total_bpjs_ks'
                    },
                    {
                        title: 'Total Potongan',
                        data: 'total_potongan',
                        name: 'total_potongan'
                    },
                    {
                        title: 'Jumlah',
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                ],
                order: [0,'asc'],
            });
            table1.draw();
        }
        $(document).ready(function() {
            rekapperhitunganpayroll();
            rekapperhitunganpayrolldepartment();
            let periode_payroll = $('#periode_payroll').val();
            let years=periode_payroll.substring(0,4);
            let month=parseInt(periode_payroll.substring(5,7));
            let months=['DECEMBER','JANUARI','FEBRUARI','MARET','APRIL','MEI','JUNI','JULI','AGUSTUS','SEPTEMBER','OKTOBER','NOVEMBER','DECEMBER'];
            $('#card_title').text('FILTER DATA PAYROLL PERIODE 26 '+months[month-1]+' S/D 25 '+months[month]+' '+years);
            var start = moment();
            var end = moment();
            var htmlDateRange = '<span><i class="fa fa-calendar"></i> ' + start.format("D MMM YYYY").toUpperCase() + ' s/d ' + end.format("D MMM YYYY").toUpperCase() + '</span><i class="fa fa-angle-down ml-1"></i>'
            $('#daterange-btn1').html(htmlDateRange);
            var daterange1 = start.format("YYYY-MM-DD") + " s/d " + end.format("YYYY-MM-DD");
            $('#daterange1').val(daterange1);
            get_last_update();
            get_last_update_labor();
        });

        $(document).ready(function() {
            let periode_payroll = $('#periode_payroll').val();
            let years=periode_payroll.substring(0,4);
            let month=parseInt(periode_payroll.substring(5,7));
            let months=['DECEMBER','JANUARI','FEBRUARI','MARET','APRIL','MEI','JUNI','JULI','AGUSTUS','SEPTEMBER','OKTOBER','NOVEMBER','DECEMBER'];
            $('#card_title').text('FILTER DATA PAYROLL PERIODE 26 '+months[month-1]+' S/D 25 '+months[month]+' '+years);
            var start = moment();
            var end = moment();
            var htmlDateRange = '<span><i class="fa fa-calendar"></i> ' + start.format("D MMM YYYY").toUpperCase() + ' s/d ' + end.format("D MMM YYYY").toUpperCase() + '</span><i class="fa fa-angle-down ml-1"></i>'
            $('#daterange-btn2').html(htmlDateRange);
            var daterange2 = start.format("YYYY-MM-DD") + " s/d " + end.format("YYYY-MM-DD");
            $('#daterange2').val(daterange2);
        });

        function get_last_update(){
            $('#last_update').empty();
            $('#last_periode_payroll').empty();
            $.ajax({
                type: 'GET',
                url: '{{route('hris.rekapperhitunganpayroll.get_last_update_proses_payroll')}}',
                success:function(data){
                    $('#last_periode_payroll').html('<i><b>&nbsp;&nbsp;Last Period Payroll :</b> '+ data.periode_early +'&nbsp;&nbsp;</i>');
                    $('#last_update').html('<i><b>&nbsp;&nbsp;Last Update :</b> '+new Date(data.updated_at.substr(0,10)).toLocaleDateString('id-ID', { weekday: 'long', year:"numeric", month:"long", day:"numeric"})+', '+data.updated_at.substr(11,5)+'&nbsp;&nbsp;</i>');
                },
                error: function(res){

                }
            });
        }

        function get_last_update_labor(){
            $('#last_update_labor').empty();
            $('#last_periode_labor').empty();
            $.ajax({
                type: 'GET',
                url: '{{route('hris.rekapperhitunganpayroll.get_last_update_labor')}}',
                success:function(data){
                    $('#last_periode_labor').html('<i><b>&nbsp;&nbsp;Last Period Proses (Labor Cost ) :</b> '+new Date(data.tanggal_akhir).toLocaleDateString('id-ID', { weekday: 'long', year:"numeric", month:"long", day:"numeric"})+'&nbsp;&nbsp;</i>');
                    $('#last_update_labor').html('<i><b>&nbsp;&nbsp;Last Update :</b> '+new Date(data.created_at).toLocaleDateString('id-ID', { weekday: 'long', year:"numeric", month:"long", day:"numeric"})+', '+data.created_at.substr(11,5)+'&nbsp;&nbsp;</i>');
                },
                error: function(res){

                }
            });
        }
        $('body').on('change', '#periode_payroll', function (event) {
            rekapperhitunganpayrolldepartment();
            rekapperhitunganpayroll();
            let periode_payroll = $('#periode_payroll').val();
            let years=periode_payroll.substring(0,4);
            let month=parseInt(periode_payroll.substring(5,7));
            let months=['DECEMBER','JANUARI','FEBRUARI','MARET','APRIL','MEI','JUNI','JULI','AGUSTUS','SEPTEMBER','OKTOBER','NOVEMBER','DECEMBER'];
            $('#card_title').text('FILTER DATA PAYROLL PERIODE 26 '+months[month-1]+' S/D 25 '+months[month]+' '+years);
        });

        $('body').on('change', '#department_id', function (event) {
            var department_id  =document.getElementsByName("department_id")[0].value;
            if(department_id){
                jQuery.ajax({
                    url : 'get_sub_dept_name/'+department_id,
                    type : "GET",
                    dataType : "json",
                    success:function(data)
                    {
                        jQuery('select[name="sub_dept_id"]').empty();
                        $('#sub_dept_id').append('<option value="" selected hidden>PILIH BAGIAN</option>');
                        jQuery.each(data, function(key,value){
                            $('select[name="sub_dept_id"]').append('<option value="'+ data[key]['sub_dept_id'] +'">'+ data[key]['sub_dept_name'] +'</option>');
                            $('#card_department').text(data[key]['department_name']);
                        });
                        rekapperhitunganpayroll();
                    }
                });
            }else{
                jQuery('select[name="sub_dept_id"]').empty();
                $('#sub_dept_id').append('<option value="" selected hidden>PILIH BAGIAN</option>');
                rekapperhitunganpayroll();
                $('#card_department').text('');
            }
        });

        $('body').on('change', '#sub_dept_id', function (event) {
            rekapperhitunganpayroll();
        });

        $('body').on('change', '#status_staff', function (event) {
            rekapperhitunganpayroll();
            rekapperhitunganpayrolldepartment();
        });

        $('body').on('change', '#periode_umks', function (event) {
            rekapperhitunganpayroll();
        });

        let currentPayrollRequest = null;

        function rekapperhitunganpayrolldepartment(){
            $('#payroll_department').empty();
            $('#loading_payroll_department').addClass("spinner-border");

            let periode_payroll = document.getElementsByName("periode_payroll")[0].value;
            let status_staff = document.getElementById("status_staff").value;

            // Batalkan request sebelumnya jika masih berjalan
            if (currentPayrollRequest !== null && currentPayrollRequest.readyState !== 4) {
                currentPayrollRequest.abort();
            }

            // Kirim request baru
            currentPayrollRequest = jQuery.ajax({
                type: "post",
                url: '{{route('hris.rekapperhitunganpayroll.get_payroll_department')}}',
                data: {
                    periode_payroll: periode_payroll,
                    status_staff: status_staff
                },
                success: function(response) {
                    console.log('response', response);
                    let selisih_karyawan = '';
                    let selisih_gaji = '';

                    $.each(response, function (key, value) {
                        if(value.selisih_karyawan > 0){
                            selisih_karyawan = "<i class='fa fa-level-up' style='color:green'></i> " + value.selisih_karyawan;
                        } else if(value.selisih_karyawan < 0){
                            selisih_karyawan = "<i class='fa fa-level-down' style='color:red'></i> " + Math.abs(value.selisih_karyawan);
                        } else {
                            selisih_karyawan = '-';
                        }

                        if(!value.selisih_gaji.includes('-')){
                            selisih_gaji = "<i class='fa fa-level-up' style='color:green'></i> " + value.selisih_gaji;
                        } else {
                            selisih_gaji = "<i class='fa fa-level-down' style='color:red'></i> " + value.selisih_gaji.replace('-', '');
                        }

                        $('#payroll_department').append(
                            "<tr>\
                                <th style='background-color:white' class='px-3 py-1'>" + value.nama_department + "</th>\
                                <td align='center'>" + value.jumlah_karyawan + "</td>\
                                <td class='text-right pr-3'>" + value.bruto + "</td>\
                                <td class='text-right pr-3'>" + value.pph + "</td>\
                                <td class='text-right pr-3'>" + value.upah_neto_rupiah + "</td>\
                                <td class='text-right pr-3'>" + value.total_bpjs_tk + "</td>\
                                <td class='text-right pr-3'>" + value.total_bpjs_ks + "</td>\
                                <td class='text-right pr-3'>" + value.potongan + "</td>\
                                <td class='text-right pr-3'>" + value.jumlah + "</td>\
                                <td></td>\
                                <td align='center'>" + value.jumlah_karyawan_sebelum + "</td>\
                                <td class='text-right pr-3'>" + value.jumlah_sebelum + "</td>\
                                <td align='center'>" + selisih_karyawan + "</td>\
                                <td class='text-right pr-3'>" + selisih_gaji + "</td>\
                            </tr>"
                        );
                    });

                    $('#loading_payroll_department').removeClass("spinner-border");
                },
                error: function(xhr, status, error) {
                    if (status !== 'abort') {
                        console.error("Error:", error);
                    }
                    $('#loading_payroll_department').removeClass("spinner-border");
                }
            });
        }

</script>
<script>
    $('.fc-datepicker').datepicker({
            format: 'Y-MM',
            showButtonPanel: true
        })


    function func(){
        var periode_payroll  =document.getElementsByName("periode_payroll")[0].value;
        var status_staff=document.getElementById("status_staff").value;
        var department_id=document.getElementById("department_id").value;
        var sub_dept_id=document.getElementById("sub_dept_id").value;
        window.location.href = "export_excel_transfer?param1="+periode_payroll+"&param2="+status_staff+"&param3="+department_id+"&param4="+sub_dept_id;
    }

    function export_excel_summary_department(){
        var periode_payroll  =document.getElementsByName("periode_payroll")[0].value;
        var status_staff=document.getElementById("status_staff").value;
        window.location.href = "export_excel_summary_department?param1="+periode_payroll+"&param2="+status_staff;
    }

    $('#export_excel_daily_labor').click(function(e){
        var enroll_id=$('#selectEmployeeID2').val();
        var daterange = $('#daterange1').val();
        var status_staff2 = $('#status_staff2').val();
        $('#export_excel_daily_labor').addClass("btn-loading");
        $("#export_excel_daily_labor").html('Please wait...');
        $("#export_excel_daily_labor").attr("disabled", true);
        $.ajax({
            type: 'POST',
            url: '{{route('hris.rekapperhitunganpayroll.export_excel_daily_labor')}}',
            data: {
                enroll_id:enroll_id,
                daterange:daterange,
                status_staff:status_staff2,
            },
            xhrFields: { responseType : 'blob' },
            success:function(data){
                var blob = new Blob([data]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                let file_name = daterange+' Daily Labor Cost '+Math.ceil(Math.random()*1000000);
                link.download = file_name+".xlsx";
                link.click();
                swal("", "Daily Labor Export Success", "success");
                $('#export_excel_daily_labor').removeClass("btn-loading");
                $("#export_excel_daily_labor").attr("disabled", false);
                $("#export_excel_daily_labor").html('<i class="fa fa-file-excel-o" aria-hidden="true"></i> DAILY LABOR COST');
            },
            error: function(res){
                swal("", "Daily Labor Export Failed", "error");
                $('#export_excel_daily_labor').removeClass("btn-loading");
                $("#export_excel_daily_labor").attr("disabled", false);
                $("#export_excel_daily_labor").html('<i class="fa fa-file-excel-o" aria-hidden="true"></i> DAILY LABOR COST');
            }
        });
    })

    $('#recap_labor_cost').click(function(e){
        var daterange = $('#daterange1').val();
        var status_staff = $('#status_staff2').val();
        $('#recap_labor_cost').addClass("btn-loading");
        $("#recap_labor_cost").html('Please wait...');
        $("#recap_labor_cost").attr("disabled", true);
        $.ajax({
            type: 'POST',
            url: '{{route('hris.rekapperhitunganpayroll.recap_labor_cost')}}',
            data: {
                daterange:daterange,
                status_staff:status_staff,
            },
            xhrFields: { responseType : 'blob' },
            success:function(data){
                var blob = new Blob([data]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                let file_name = daterange+' Recap Labor Cost '+Math.ceil(Math.random()*1000000);
                link.download = file_name+".xlsx";
                link.click();
                swal("", "Recap Labor Export Success", "success");
                $('#recap_labor_cost').removeClass("btn-loading");
                $("#recap_labor_cost").attr("disabled", false);
                $("#recap_labor_cost").html('<i class="fa fa-file-excel-o" aria-hidden="true"></i> RECAP LABOR COST');
            },
            error: function(res){
                swal("", "Recap Labor Export Failed", "error");
                $('#recap_labor_cost').removeClass("btn-loading");
                $("#recap_labor_cost").attr("disabled", false);
                $("#recap_labor_cost").html('<i class="fa fa-file-excel-o" aria-hidden="true"></i> RECAP LABOR COST');
            }
        });
    })

    $('#recap_labor_cost_2').click(function(e){
        var daterange = $('#daterange1').val();
        var status_staff = $('#status_staff2').val();
        var enroll_id=$('#selectEmployeeID2').val();
        $('#recap_labor_cost_2').addClass("btn-loading");
        $("#recap_labor_cost_2").html('Please wait...');
        $("#recap_labor_cost_2").attr("disabled", true);
        $.ajax({
            type: 'POST',
            url: '{{route('hris.rekapperhitunganpayroll.recap_labor_cost_2')}}',
            data: {
                daterange:daterange,
                enroll_id:enroll_id,
                status_staff:status_staff
            },
            xhrFields: { responseType : 'blob' },
            success:function(data){
                var blob = new Blob([data]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                let file_name = daterange+' Recap Labor Cost 2 '+Math.ceil(Math.random()*1000000);
                link.download = file_name+".xlsx";
                link.click();
                swal("", "Recap Labor Export Success", "success");
                $('#recap_labor_cost_2').removeClass("btn-loading");
                $("#recap_labor_cost_2").attr("disabled", false);
                $("#recap_labor_cost_2").html('<i class="fa fa-file-excel-o" aria-hidden="true"></i> DAILY LABOR COST');
            },
            error: function(res){
                swal("", "Recap Labor Export Failed", "error");
                $('#recap_labor_cost_2').removeClass("btn-loading");
                $("#recap_labor_cost_2").attr("disabled", false);
                $("#recap_labor_cost_2").html('<i class="fa fa-file-excel-o" aria-hidden="true"></i> DAILY LABOR COST');
            }
        });
    })

    jQuery(document).ready(function($) {
            const BtnProsesPayroll = document.getElementsByClassName('BtnProsesPayroll')[0];
            const BtnRekapPayroll = document.getElementsByClassName('BtnRekapPayroll')[0];
             const PriodeProses = document.getElementsByClassName("PriodeProses");
            const PriodeUmk = document.getElementsByClassName("PriodeUmk");
            // BtnProsesPayroll.addEventListener('click', function(event) {
            //     let tmp = PriodeProses[0].value;
            //     const skemaPayroll = document.getElementById("skema_payroll");
            //     const date_range_2 = $('#daterange2').val();
            //     if(skemaPayroll.value == '0'){
            //         swal({
            //             title: "Harap pilih skema payroll",
            //             text: "Skema Payroll tidak boleh kosong",
            //             icon: "warning",
            //             button : false,
            //         });
            //         return;
            //     }
            //     if(tmp == ''){
            //         swal({
            //             title: "Harap pilih periode payroll",
            //             text: "Data Periode Payroll tidak boleh kosong",
            //             icon: "warning",
            //             button : false,
            //         });
            //         return;
            //     }
            //     event.preventDefault();
            //     const submited =document.getElementsByTagName('form')[0];
            //     swal({
            //         title: 'Apakah Anda Yakin ?',
            //         text: 'Proses payroll',
            //         type: "warning",
            //         showCancelButton: true,
            //         confirmButtonText: 'YES',
            //         cancelButtonText: 'NO'
            //     },function(isConfirm){
            //         console.log('isConfirm',isConfirm);
            //         if(isConfirm) {
            //             $('#BtnProsesPayroll').addClass("btn-loading");
            //             $("#BtnProsesPayroll").html('Please wait...');
            //             $("#BtnProsesPayroll").attr("disabled", true);

            //             $.ajax({
            //                 data: $('#form_proses_payroll').serialize(),
            //                 url: '{{ route("hris.proses.payroll.rekap") }}',
            //                 type: "post",
            //                 // dataType: 'json',
            //                 success: function (data) {
            //                     console.log("data",data);
            //                     swal("", "Proses payroll berhasil!", "success");

            //                     $('#BtnProsesPayroll').removeClass("btn-loading");
            //                     $("#BtnProsesPayroll").html('<span><i class="fa fa-download"></i></span> PROSES PAYROLL');
            //                     $("#BtnProsesPayroll").attr("disabled", false);

            //                 },
            //                 error: function (xhr, status, error) {
            //                     console.log(error);
            //                     swal("", "Proses payroll gagal!", "error");

            //                     $('#BtnProsesPayroll').removeClass("btn-loading");
            //                     $("#BtnProsesPayroll").attr("disabled", false);
            //                     $("#BtnProsesPayroll").html('<span><i class="fa fa-download"></i></span> PROSES PAYROLL');
            //                 }
            //             });
            //         }
            //     });
            // });
            BtnProsesPayroll.addEventListener("click", function (event) {
                event.preventDefault();
                const skemaPayroll = document.getElementById("skema_payroll");
                const selectedSkema = skemaPayroll.value;
                let tmp = PriodeProses[0].value;
                const date_range_2 = document.getElementById("daterange2").value;
                let payrollPeriod = "";
                console.log("selectedSkema", selectedSkema);
                // Validasi berdasarkan skema payroll yang dipilih
                if (selectedSkema === "MONTHLY_PAYROLL") {
                    payrollPeriod = tmp;
                    if (!tmp) {
                        swal({
                            title: "Harap pilih periode payroll",
                            text: "Data Periode Payroll tidak boleh kosong",
                            icon: "warning",
                            button: false,
                        });
                        return;
                    }else{
                        swal({
                                title: 'Apakah Anda Yakin ?',
                                text: 'Proses payroll',
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonText: 'YES',
                                cancelButtonText: 'NO'
                            },function(isConfirm){
                                console.log('isConfirm',isConfirm);
                                if(isConfirm) {
                                    $('#BtnProsesPayroll').addClass("btn-loading");
                                    $("#BtnProsesPayroll").html('Please wait...');
                                    $("#BtnProsesPayroll").attr("disabled", true);


                                    $.ajax({
                                        data: $('#form_proses_payroll').serialize(),
                                        url: '{{ route("hris.proses.payroll.rekap") }}',
                                        type: "post",
                                        // dataType: 'json',
                                        success: function (data) {
                                            console.log("data",data);
                                            swal("", "Proses payroll berhasil!", "success");

                                            $('#BtnProsesPayroll').removeClass("btn-loading");
                                            $("#BtnProsesPayroll").html('<span><i class="fa fa-download"></i></span> PROSES PAYROLL');
                                            $("#BtnProsesPayroll").attr("disabled", false);

                                        },
                                        error: function (xhr, status, error) {
                                            console.error('xhr', xhr);
                                            console.error('status', status);
                                            console.error('error', error);
                                            swal("", "Proses payroll gagal!", "error");

                                            $('#BtnProsesPayroll').removeClass("btn-loading");
                                            $("#BtnProsesPayroll").attr("disabled", false);
                                            $("#BtnProsesPayroll").html('<span><i class="fa fa-download"></i></span> PROSES PAYROLL');
                                        }
                                    });
                                }
                            });
                    }
                } else if (selectedSkema === "EARLY_CLOSING") {
                    payrollPeriod = date_range_2;
                    if (!date_range_2) {
                        swal({
                            title: "Harap pilih periode payroll",
                            text: "Data Periode Payroll tidak boleh kosong",
                            icon: "warning",
                            button: false,
                        });
                        return;
                    }else{
                        swal({
                            title: 'Apakah Anda Yakin ?',
                            text: 'Proses payroll',
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonText: 'YES',
                            cancelButtonText: 'NO'
                        },function(isConfirm){
                            console.log('isConfirm',isConfirm);
                            if(isConfirm) {
                                $('#BtnProsesPayroll').addClass("btn-loading");
                                $("#BtnProsesPayroll").html('Please wait...');
                                $("#BtnProsesPayroll").attr("disabled", true);
                                $.ajax({
                                    data: $('#form_proses_payroll').serialize(),
                                    url: '{{ route("hris.proses.payroll.rekap_early") }}',
                                    type: "post",
                                    // dataType: 'json',
                                    success: function (data) {
                                        console.log("data",data);
                                        swal("", "Proses payroll berhasil!", "success");

                                        $('#BtnProsesPayroll').removeClass("btn-loading");
                                        $("#BtnProsesPayroll").html('<span><i class="fa fa-download"></i></span> PROSES PAYROLL');
                                        $("#BtnProsesPayroll").attr("disabled", false);

                                    },
                                    error: function (xhr, status, error) {
                                        console.log(error);
                                        swal("", "Proses payroll gagal!", "error");

                                        $('#BtnProsesPayroll').removeClass("btn-loading");
                                        $("#BtnProsesPayroll").attr("disabled", false);
                                        $("#BtnProsesPayroll").html('<span><i class="fa fa-download"></i></span> PROSES PAYROLL');
                                    }
                                });
                            }
                        });
                    }
                } else {
                    swal({
                        title: "Harap pilih skema payroll",
                        text: "Skema Payroll tidak boleh kosong",
                        icon: "warning",
                        button: false,
                    });
                    return;
                }
                // Konfirmasi sebelum mengirim request

            });

                // if(tmp == '' && tmp2 == ''){
                //     swal({
                //         title: "Harap pilih periode payroll atau periode umk",
                //         text: "Data Periode Payroll atau periode umk tidak boleh kosong",
                //         icon: "warning",
                //         button : false,
                //     });
                // }
                // else if(tmp != '' && tmp2 != ''){
                //     swal({
                //         title: "Harap pilih salah satu saja diantara periode payroll atau umk",
                //         text: "Data Periode Payroll atau periode umk tidak boleh kosong",
                //         icon: "warning",
                //         button : false,
                //     });
                // }
                // else{
                //         event.preventDefault();
                //         const submited =document.getElementsByTagName('form')[0];
                //         swal({
                //             title: 'Apakah Anda Yakin ?',
                //             text: 'Proses payroll',
                //             type: "warning",
                //             showCancelButton: true,
                //             confirmButtonText: 'YES',
                //             cancelButtonText: 'NO'
                //         },function(isConfirm){
                //             if(isConfirm) {
                //                 $('#BtnProsesPayroll').addClass("btn-loading");
                //                 $("#BtnProsesPayroll").html('Please wait...');
                //                 $("#BtnProsesPayroll").attr("disabled", true);

                //                 $.ajax({
                //                     data: $('#form_proses_payroll').serialize(),
                //                     url: '{{ route("hris.proses.payroll.rekap") }}',
                //                     type: "post",
                //                     // dataType: 'json',
                //                     success: function (data) {
                //                         console.log("data",data);
                //                         swal("", "Proses payroll berhasil!", "success");

                //                         $('#BtnProsesPayroll').removeClass("btn-loading");
                //                         $("#BtnProsesPayroll").html('<span><i class="fa fa-download"></i></span> PROSES PAYROLL');
                //                         $("#BtnProsesPayroll").attr("disabled", false);

                //                     },
                //                     error: function (xhr, status, error) {
                //                         console.log(error);
                //                         swal("", "Proses payroll gagal!", "error");

                //                         $('#BtnProsesPayroll').removeClass("btn-loading");
                //                         $("#BtnProsesPayroll").attr("disabled", false);
                //                         $("#BtnProsesPayroll").html('<span><i class="fa fa-download"></i></span> PROSES PAYROLL');
                //                     }
                //                 });
                //             }
                //         });
                //     }
                // });
    });

    jQuery(document).ready(function($) {
        const BtnProsesPayroll2 = document.getElementsByClassName('BtnProsesPayroll2')[0];
        const BtnRekapPayroll = document.getElementsByClassName('BtnRekapPayroll')[0];
        const PriodeProses = document.getElementsByClassName("PriodeProses");
        const PriodeUmk = document.getElementsByClassName("PriodeUmk");
        BtnProsesPayroll2.addEventListener('click', function(event) {
            let tmp = PriodeProses[0].value;
            console.log("tmp",tmp);
            console.log("PriodeUmk",tmp);
            if(tmp == ''){
                    swal({
                        title: "Harap pilih periode payroll",
                        text: "Data Periode Payroll tidak boleh kosong",
                        icon: "warning",
                        button : false,
                    });
            }else{
                event.preventDefault();
                const submited =document.getElementsByTagName('form')[0];
                swal({
                    title: 'Apakah Anda Yakin ?',
                    text: 'Proses payroll',
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: 'YES',
                    cancelButtonText: 'NO'
                },function(isConfirm){
                    if(isConfirm) {
                        $('#BtnProsesPayroll2').addClass("btn-loading");
                        $("#BtnProsesPayroll2").html('Please wait...');
                        $("#BtnProsesPayroll2").attr("disabled", true);

                        $.ajax({
                            data: $('#form_proses_payroll').serialize(),
                            url: '{{ route("hris.proses.payroll.rekap2") }}',
                            type: "post",
                            success: function (data) {
                            console.log(data);
                                swal("", "Proses rekap lembur berhasil", "success");
                                $('#BtnProsesPayroll2').removeClass("btn-loading");
                                $("#BtnProsesPayroll2").html('<span><i class="fa fa-download"></i></span> PROSES REKAP LEMBUR');
                                $("#BtnProsesPayroll2").attr("disabled", false);

                            },
                            error: function (xhr, status, error) {
                                swal("", "Proses rekap lembur gagal", "error");
                                $('#BtnProsesPayroll2').removeClass("btn-loading");
                                $("#BtnProsesPayroll2").attr("disabled", false);
                                $("#BtnProsesPayroll2").html('<span><i class="fa fa-download"></i></span> PROSES REKAP LEMBUR');
                            }
                        });
                    }
                });
            }
            // let tmp2 = PriodeUmk[0].value;
            //     if(tmp == '' && tmp2 == ''){
            //         swal({
            //             title: "Harap pilih periode payroll atau periode umk",
            //             text: "Data Periode Payroll atau periode umk tidak boleh kosong",
            //             icon: "warning",
            //             button : false,
            //         });
            //     }
            //     else if(tmp != '' && tmp2 != ''){
            //         swal({
            //             title: "Harap pilih salah satu saja diantara periode payroll atau umk",
            //             text: "Data Periode Payroll atau periode umk tidak boleh kosong",
            //             icon: "warning",
            //             button : false,
            //         });
            //     }
            //     else{
            //     event.preventDefault();
            //     const submited =document.getElementsByTagName('form')[0];
            //     swal({
            //         title: 'Apakah Anda Yakin ?',
            //         text: 'Proses payroll',
            //         type: "warning",
            //         showCancelButton: true,
            //         confirmButtonText: 'YES',
            //         cancelButtonText: 'NO'
            //     },function(isConfirm){
            //         if(isConfirm) {
            //             $('#BtnProsesPayroll2').addClass("btn-loading");
            //             $("#BtnProsesPayroll2").html('Please wait...');
            //             $("#BtnProsesPayroll2").attr("disabled", true);

            //             $.ajax({
            //                 data: $('#form_proses_payroll').serialize(),
            //                 url: '{{ route("hris.proses.payroll.rekap2") }}',
            //                 type: "post",
            //                 success: function (data) {
            //                 console.log(data);
            //                     swal("", "Proses rekap lembur berhasil", "success");
            //                     $('#BtnProsesPayroll2').removeClass("btn-loading");
            //                     $("#BtnProsesPayroll2").html('<span><i class="fa fa-download"></i></span> PROSES REKAP LEMBUR');
            //                     $("#BtnProsesPayroll2").attr("disabled", false);

            //                 },
            //                 error: function (xhr, status, error) {
            //                     swal("", "Proses rekap lembur gagal", "error");
            //                     $('#BtnProsesPayroll2').removeClass("btn-loading");
            //                     $("#BtnProsesPayroll2").attr("disabled", false);
            //                     $("#BtnProsesPayroll2").html('<span><i class="fa fa-download"></i></span> PROSES REKAP LEMBUR');
            //                 }
            //             });
            //         }
            //     });
            //     }
        });
        // BtnProsesPayroll3.addEventListener('click', function(event) {
        //     let tmp = PriodeProses[0].value;
        //     // let tmp2 = PriodeUmk[0].value;
        //     if(tmp == ''){
        //             swal({
        //                 title: "Harap pilih periode payroll",
        //                 text: "Data Periode Payroll tidak boleh kosong",
        //                 icon: "warning",
        //                 button : false,
        //             });
        //     } else{
        //         event.preventDefault();
        //         const submited =document.getElementsByTagName('form')[0];
        //         swal({
        //             title: 'Apakah Anda Yakin ?',
        //             text: 'Proses payroll',
        //             type: "warning",
        //             showCancelButton: true,
        //             confirmButtonText: 'YES',
        //             cancelButtonText: 'NO'
        //         },function(isConfirm){
        //             if(isConfirm) {
        //                 $('#BtnProsesPayroll3').addClass("btn-loading");
        //                 $("#BtnProsesPayroll3").html('Please wait...');
        //                 $("#BtnProsesPayroll3").attr("disabled", true);

        //                 $.ajax({
        //                     data: $('#form_proses_payroll').serialize(),
        //                     url: '{{ route("hris.proses.payroll.rekap3") }}',
        //                     type: "post",
        //                     // dataType: 'json',
        //                     success: function (data) {
        //                         console.log(data);
        //                         notif({
        //                             msg: "<b>Info:</b> Data Berhasil di Proses.",
        //                             type: "info"
        //                         });

        //                         $('#BtnProsesPayroll3').removeClass("btn-loading");
        //                         $("#BtnProsesPayroll3").html('<span><i class="fa fa-download"></i></span> PROSES REKAP LEMBUR BARU');
        //                         $("#BtnProsesPayroll3").attr("disabled", false);

        //                     },
        //                     error: function (xhr, status, error) {
        //                         notif({
        //                             msg: "<b>Error:</b> Oops data gagal di Proses.",
        //                             type: "error"
        //                         });

        //                         $('#BtnProsesPayroll3').removeClass("btn-loading");
        //                         $("#BtnProsesPayroll3").attr("disabled", false);
        //                         $("#BtnProsesPayroll3").html('<span><i class="fa fa-download"></i></span> PROSES REKAP LEMBUR BARU');
        //                     }
        //                 });
        //             }
        //         });
        //     }
        //     // if(tmp == '' && tmp2 == ''){
        //     //     swal({
        //     //         title: "Harap pilih periode payroll atau periode umk",
        //     //         text: "Data Periode Payroll atau periode umk tidak boleh kosong",
        //     //         icon: "warning",
        //     //         button : false,
        //     //     });
        //     // }
        //     // else if(tmp != '' && tmp2 != ''){
        //     //     swal({
        //     //         title: "Harap pilih salah satu saja diantara periode payroll atau umk",
        //     //         text: "Data Periode Payroll atau periode umk tidak boleh kosong",
        //     //         icon: "warning",
        //     //         button : false,
        //     //     });
        //     // }
        //     // else{
        //     //     event.preventDefault();
        //     //     const submited =document.getElementsByTagName('form')[0];
        //     //     swal({
        //     //         title: 'Apakah Anda Yakin ?',
        //     //         text: 'Proses payroll',
        //     //         type: "warning",
        //     //         showCancelButton: true,
        //     //         confirmButtonText: 'YES',
        //     //         cancelButtonText: 'NO'
        //     //     },function(isConfirm){
        //     //         if(isConfirm) {
        //     //             $('#BtnProsesPayroll3').addClass("btn-loading");
        //     //             $("#BtnProsesPayroll3").html('Please wait...');
        //     //             $("#BtnProsesPayroll3").attr("disabled", true);

        //     //             $.ajax({
        //     //                 data: $('#form_proses_payroll').serialize(),
        //     //                 url: '{{ route("hris.proses.payroll.rekap3") }}',
        //     //                 type: "post",
        //     //                 // dataType: 'json',
        //     //                 success: function (data) {
        //     //                     console.log(data);
        //     //                     notif({
        //     //                         msg: "<b>Info:</b> Data Berhasil di Proses.",
        //     //                         type: "info"
        //     //                     });

        //     //                     $('#BtnProsesPayroll3').removeClass("btn-loading");
        //     //                     $("#BtnProsesPayroll3").html('<span><i class="fa fa-download"></i></span> PROSES REKAP LEMBUR BARU');
        //     //                     $("#BtnProsesPayroll3").attr("disabled", false);

        //     //                 },
        //     //                 error: function (xhr, status, error) {
        //     //                     notif({
        //     //                         msg: "<b>Error:</b> Oops data gagal di Proses.",
        //     //                         type: "error"
        //     //                     });

        //     //                     $('#BtnProsesPayroll3').removeClass("btn-loading");
        //     //                     $("#BtnProsesPayroll3").attr("disabled", false);
        //     //                     $("#BtnProsesPayroll3").html('<span><i class="fa fa-download"></i></span> PROSES REKAP LEMBUR BARU');
        //     //                 }
        //     //             });
        //     //         }
        //     //     });
        //     // }
        // });
    });
</script>


<script>
    // =====================================================
    //  PROCESS RUNNER (PLAIN VERSION) + STEP DESCRIPTIONS
    // =====================================================

    $(document).ready(function () {


        // Recap Labor Export
         $('#btnRecap').click(function() {
            let daterange = $('#daterange1').val();
            let enroll_id = $('#selectEmployeeID2').val();
            $('#btnRecap').addClass("btn-loading");
            $("#btnRecap").html('Please wait...');
            $("#btnRecap").attr("disabled", true);

            console.log('daterange', daterange);

            $.ajax({
                type: 'POST',
                url: '{{route('hris.rekapperhitunganpayroll.recap_labor_cost_new')}}',
                data: {
                    daterange: daterange,
                    enroll_id: enroll_id
                },
                xhrFields: { responseType : 'blob' },
                success:function(data){
                    var blob = new Blob([data]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    let file_name = daterange+' Recap Labor Cost New '+Math.ceil(Math.random()*1000000);
                    link.download = file_name+".xlsx";
                    link.click();
                    swal("", "Recap Labor Export Success", "success");
                    $('#btnRecap').removeClass("btn-loading");
                    $("#btnRecap").attr("disabled", false);
                    $("#btnRecap").html('DAILY LABOR COST NEW');
                },
                error: function(res){
                    swal("", "Recap Labor Export Failed", "error");
                    $('#btnRecap').removeClass("btn-loading");
                    $("#btnRecap").attr("disabled", false);
                    $("#btnRecap").html('DAILY LABOR COST NEW');
                }
            });
        });

        // -------------------------------------------------
        // RECAP PAYROLL EXPORT FUNCTION
        function getRecapPayroll(){
            $.ajax({
                type: 'POST',
                url: '{{route('hris.rekapperhitunganpayroll.export_recap_payroll')}}',
                xhrFields: { responseType : 'blob' },
                success:function(data){
                    console.log(data);
                    var blob = new Blob([data]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    const now = new Date();
                    const timestamp =
                    now.getFullYear() +
                    ('0' + (now.getMonth() + 1)).slice(-2) +
                    ('0' + now.getDate()).slice(-2) + '_' +
                    ('0' + now.getHours()).slice(-2) +
                    ('0' + now.getMinutes()).slice(-2) +
                    ('0' + now.getSeconds()).slice(-2);

                    let file_name = 'Recap Salary ' + timestamp

                    link.download = file_name+".xlsx";
                    link.click();
                    swal("", "Recap Salary Export Success", "success");

                },
                error: function(res){
                    swal("", "Recap Salary Export Failed", "error");

                }
            });
        }

        // -------------------------------------------------
        // STEP DESCRIPTIONS (OPSI A)
        // -------------------------------------------------
        const STEP_DESC = {
            1: "Load Presence",
            2: "Load Overtime",
            3: "Load Permission",
            4: "Load Late/Early",
            5: "Load BPJS",
            6: "Load Salary"
        };

        // -------------------------------------------------
        // UI HELPERS
        // -------------------------------------------------
        function uid(prefix) {
            return prefix + '_' + Math.random().toString(36).substr(2, 9);
        }

        function openProgressModal() {
            $('#progressLog').empty();
            $('#closeProgressBtn').hide();
            $('#progressModal').modal('show');
        }

        function addLog(text) {
            $('#progressLog').prepend(`<li>${text}</li>`);
        }

        function getFriendlyName(step, idx) {
            const friendlyNamesStep1 = ['Kehadiran', 'Perhitungan Kehadiran', 'Anomalous'];
            const friendlyNamesStep2 = ['OK', 'Anomalous', 'All Rows', 'Dashboard','Summary'];
            const friendlyNamesStep6 = [
                'Employee', 'Grading', 'Presence', 'Overtime',
                'Koreksi Upah', 'Koreksi Potongan', 'BPJS',
                'DTPC', 'IKS', 'Final Salary'
            ];

            if (step === 1) return friendlyNamesStep1[idx] ?? `Set ${idx + 1}`;
            if (step === 2) return friendlyNamesStep2[idx] ?? `Set ${idx + 1}`;
            if (step === 6) return friendlyNamesStep6[idx] ?? `Set ${idx + 1}`;
            return `Set ${idx + 1}`;
        }

        // -------------------------------------------------
        // DATATABLE RENDERER
        // -------------------------------------------------
        function renderDataTable(selector, data, friendlyName = "") {

            const $table = $(selector);

            if ($.fn.DataTable.isDataTable(selector)) {
                $table.DataTable().clear().destroy();
            }
            $table.empty();

            if (!data || data.length === 0) {
                $table.append(`
                    <thead><tr><th>No data</th></tr></thead>
                    <tbody><tr><td>No data</td></tr></tbody>
                `);
                return;
            }

            // Build columns
            let keySet = new Set();
            data.forEach(r => Object.keys(r).forEach(k => keySet.add(k)));
            const keys = Array.from(keySet);

            const columns = keys.map(k => ({
                data: k,
                title: k.replace(/_/g, ' ').toUpperCase()
            }));

            // Detect final salary
            const isFinalSalary =
                friendlyName.toLowerCase().includes("final") ||
                friendlyName.toLowerCase().includes("salary");

            let excelHeader = "";
            if (isFinalSalary) {
                excelHeader =
                    'Final Salary Report\n' +
                    'Generated at: ' + new Date().toLocaleString() + '\n' +
                    '--------------------------------------------\n\n';
            }

            // Init DataTable
            const dt = $table.DataTable({
                data: data.map(row => {
                    const out = {};
                    keys.forEach(k => out[k] = row[k] ?? '');
                    return out;
                }),
                columns: columns,
                scrollX: true,
                pageLength: 10,
                responsive: false,
                autoWidth: false,
                deferRender: true,
                processing: true,
                destroy: true,
                dom: 'Blfrtip',

                // Buttons
                buttons: [
                    'copy',
                    'csv',

                    // ========= EXCEL BUTTON (FIXED CONFIGURATION) =========
                    {
                        extend: 'excelHtml5', // ← TAMBAHKAN INI
                        text: isFinalSalary ? 'Export Recap Payroll' : 'Excel',
                        className: 'btn-excel-dummy',
                        title: 'Data Export ' + friendlyName, // ← BERI JUDUL
                        filename: function() {
                            const date = new Date().toISOString().split('T')[0];
                            return isFinalSalary ? `recap_payroll_${date}` : `export_${date}`;
                        },
                        exportOptions: {
                            columns: ':visible', // Ekspor semua kolom yang terlihat
                            format: {
                                header: function(text) {
                                    // Format header jika diperlukan
                                    return text ? text.toString().trim() : '';
                                }
                            }
                        },
                        customize: function(xlsx) {
                            // Optional: kustomisasi tambahan
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];

                            // Pastikan header memiliki style
                            $('row:first c', sheet).attr('s', '2'); // Style bold untuk header

                            // Atur lebar kolom otomatis
                            $('col', sheet).each(function() {
                                $(this).attr('width', 15);
                            });
                        },
                        action: function (e, dt, node, config) {
                            const overlay = document.getElementById("excelLoadingOverlay");
                            overlay.style.display = "block";

                            // Give browser time to render overlay
                            setTimeout(() => {
                                if (isFinalSalary) {
                                    getRecapPayroll(); // ← CALL FUNCTION
                                }
                                else {
                                    // Pastikan DataTable memiliki header yang benar
                                    if (dt.context && dt.context[0]) {
                                        // Force update header titles jika diperlukan
                                        const settings = api.settings()[0];
                                        dt.columns().every(function(index) {
                                            const column = this;
                                            const header = $(column.header());
                                            // if (column.title && column.title() === '') {
                                            //     const headerText = header.text().trim();
                                            //     if (headerText) {
                                            //         // Update title dari teks header
                                            //         dt.context[0].aoColumns[index].sTitle = headerText;
                                            //     }
                                            // }
                                            const headerText = $(this.header()).text().trim();
                                            if (!settings.aoColumns[index].sTitle && headerText) {
                                                settings.aoColumns[index].sTitle = headerText;
                                            }
                                        });
                                    }

                                    // Panggil action default
                                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
                                }
                            }, 150);

                            setTimeout(() => {
                                document.getElementById("excelLoadingOverlay").style.display = "none";
                            }, 1200);
                        }
                    },

                    'pdf',
                    'print'
                ],

                initComplete: function () {
                    const api = this.api();
                    requestAnimationFrame(() => {
                        requestAnimationFrame(() => {
                            api.columns.adjust().draw(false);
                            autoFormatNumericColumns($table);
                            const settings = api.settings()[0];
                            // Pastikan semua kolom memiliki title
                            api.columns().every(function(index) {
                                const column = this;
                                const header = $(column.header());
                                const headerText = header.text().trim();

                                // Jika kolom tidak punya title, set dari header HTML
                                // if (!column.title() && headerText) {
                                //     api.context[0].aoColumns[index].sTitle = headerText;
                                // }
                                if (!settings.aoColumns[index].sTitle && headerText) {
                                    settings.aoColumns[index].sTitle = headerText;
                                }
                            });
                        });
                    });
                }
            });

            // Safe adjust
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    dt.columns.adjust().draw(false);
                });
            });

        }

        // -------------------------------------------------
        // RECAP PAYROLL PROCESS HANDLER

        $('#runProcessBtn').on('click', function () {

            const skema = $('#skema_payroll').val();
            const periode = $("input[name='periode_payrols']").val(); // YYYY-MM

            if (!periode || skema !== "MONTHLY_PAYROLL") {
                alert("Silakan pilih skema MONTHLY PAYROLL dan periode terlebih dahulu!");
                return;
            }

            const [year, month] = periode.split("-");
            let intMonth = parseInt(month);

            // Hitung bulan sebelumnya
            let prevMonth = intMonth - 1;
            let prevYear = year;

            if (prevMonth === 0) {
                prevMonth = 12;
                prevYear = year - 1; // Tahun mundur
            }

            // Format bulan selalu 2 digit → 01–12
            prevMonth = String(prevMonth).padStart(2, '0');

            // Tanggal awal & akhir fix sesuai payroll
            const steps = [1, 2, 3, 4, 5, 6];
            const tanggal_awal = `${prevYear}-${prevMonth}-26`;
            const tanggal_akhir = `${year}-${month}-25`;
            // const tanggal_awal = '2025-10-26';
            // const tanggal_akhir = '2025-11-25';
            console.log("Tanggal Awal:", tanggal_awal);
            console.log("Tanggal Akhir:", tanggal_akhir);
            openProgressModal();
            addLog("Processing…");

            // Bersihkan semua tab container
            for (let s = 1; s <= 6; s++) {
                $('#container_' + s).empty();
            }

            // MAIN EXECUTOR
            (async function runSequence() {

                for (let step of steps) {

                    const desc = STEP_DESC[step] ?? "Unknown Step";

                    // Log running
                    addLog(`Running Step ${step} (${desc})...`);

                    // Request AJAX
                    let res;
                    try {
                        res = await $.ajax({
                            // url: '/run-process-step',
                            url: '{{route('hris.rekapperhitunganpayroll.runStep')}}',
                            method: 'POST',
                            data: {
                                step,
                                tanggal_awal,
                                tanggal_akhir,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                    } catch (err) {
                        addLog(`Step ${step} FAILED: ${err.responseText || err.statusText}`);
                        $('#closeProgressBtn').show();
                        break;
                    }

                    // Validasi status
                    if (res.status !== 'ok') {
                        addLog(`Step ${step} ERROR: ${res.message}`);
                        $('#closeProgressBtn').show();
                        break;
                    }

                    // Log done
                    addLog(`Step ${step} (${desc}) done. Rendering result sets...`);

                    // Tab container target
                    const container = $(`#container_${step}`);
                    const rs = res.result_sets || {};

                    // Sort keys
                    let resultKeys = Object.keys(rs).sort((a, b) => {
                        const A = parseInt(a.replace(/\D+/g, '')) || 0;
                        const B = parseInt(b.replace(/\D+/g, '')) || 0;
                        return A - B;
                    });

                    // Buang key kosong jika ada
                    if (resultKeys.length && rs[resultKeys[resultKeys.length - 1]].length === 0) {
                        resultKeys.pop();
                    }

                    if (resultKeys.length === 0) {
                        container.append(`<div class="alert alert-info mt-3">No result sets returned.</div>`);
                        continue;
                    }

                    // Build Sub Tabs
                    const subTabId = 'stepTabs_' + step + '_' + uid('g');
                    let navHtml = `<ul class="nav nav-tabs" id="${subTabId}_nav">`;
                    let contentHtml = `<div class="tab-content" id="${subTabId}_content">`;
                    let friendlyList = [];
                    resultKeys.forEach((key, idx) => {

                        let rows = rs[key];
                        if (!rows || rows.length === 0) rows = [{ info: "No data available" }];

                        const friendly = getFriendlyName(step, idx);
                        friendlyList[idx] = friendly;
                        const active = idx === 0 ? 'active' : '';
                        const paneId = `${subTabId}_pane_${idx}`;
                        const tableId = `${subTabId}_tbl_${idx}`;

                        navHtml += `
                            <li class="nav-item">
                                <a class="nav-link ${active}" data-toggle="tab" href="#${paneId}">
                                    ${friendly}
                                </a>
                            </li>
                        `;

                        contentHtml += `
                            <div id="${paneId}" class="tab-pane fade show ${active}">
                                <h6 class="mt-3">${friendly} (${rows.length} rows)</h6>
                                <table id="${tableId}" class="table table-bordered table-striped w-100"></table>
                            </div>
                        `;
                    });

                    navHtml += `</ul>`;
                    contentHtml += `</div>`;

                    container.append(`<div class="mt-3">${navHtml}${contentHtml}</div>`);

                    // Render DataTables
                    resultKeys.forEach((key, idx) => {
                        const id = `#${subTabId}_tbl_${idx}`;
                        requestAnimationFrame(() => {
                            renderDataTable(id, rs[key], friendlyList[idx]);

                        });
                    });

                    // Auto pindah tab parent
                    $(`a[href="#tab${step}"]`).tab('show');
                }

                addLog("All steps completed.");
                getSalaryPerDepartment();
                swal("", "Payroll Recap Process Completed", "success");
                $('#closeProgressBtn').show();

            })();

        });

        async function getSalaryPerDepartment() {
            try {
                // Cek jika DataTable sudah ada, hancurkan dulu agar bisa render ulang
                if ($.fn.DataTable.isDataTable('#table-payroll-dept')) {
                    $('#table-payroll-dept').DataTable().destroy();
                }
                const periode = $("input[name='periode_payrols']").val();
                $('#table-payroll-dept').DataTable({
                    processing: true,
                    serverSide: false, // Set false karena data sudah diproses sekaligus di SP
                    destroy: true,
                    ordering: false,
                    ajax: {
                        url: '{{route("hris.rekapperhitunganpayroll.getSalaryPerDepartment")}}',
                        method: 'POST',
                        data: function(d) {
                            d.periode_payroll = periode;
                            d.status_staff = '';
                            d._token = '{{csrf_token()}}';
                        },
                        dataSrc: 'data'
                    },
                    columns: [
                        { data: 'nama_department' },
                        { data: 'jumlah_karyawan', className: 'text-center' },
                        { data: 'bruto', className: 'text-right' },
                        { data: 'pph', className: 'text-right' },
                        { data: 'upah_neto_rupiah', className: 'text-right' },
                        { data: 'total_bpjs_tk', className: 'text-right' },
                        { data: 'total_bpjs_ks', className: 'text-right' },
                        { data: 'potongan', className: 'text-right' },
                        {
                            data: 'jumlah',
                            className: 'text-right font-weight-bold' // Pindahkan font-weight ke class
                        },
                        { data: 'jumlah_karyawan_sebelum', className: 'text-center' },
                        { data: 'jumlah_sebelum', className: 'text-right' },
                        {
                            data: 'selisih_karyawan',
                            className: 'text-center',
                            render: function(data) {
                                if (data == 0 || data == null) return '-';
                                let color = data > 0 ? '#28a745' : '#dc3545'; // Warna hijau/merah sukses/danger
                                let icon = data > 0 ? 'fa fa-arrow-up' : 'fa fa-arrow-down';
                                return `<span style="color:${color}; font-weight:bold;"><i class="fas ${icon}"></i> ${Math.abs(data)}</span>`;
                            }
                        },
                        {
                            data: 'selisih_gaji',
                            className: 'text-right',
                            render: function(data) {
                                // if (data == 0 || data == null) return '-';
                                let color = data > 0 ? '#28a745' : '#dc3545';
                                let icon = data > 0 ? 'fa fa-arrow-up' : 'fa fa-arrow-down';
                                let formatted = new Intl.NumberFormat('id-ID').format(Math.abs(data));
                                return `<span style="color:${color}; font-weight:bold;"><i class="fas ${icon}"></i> ${data}</span>`;
                            }
                        }
                    ],
                    columnDefs: [
                        { targets: '_all', defaultContent: '-' }
                    ],
                    createdRow: function(row, data) {
                        // Styling untuk Grand Total agar terlihat seperti di gambar
                        if (data.nama_department === 'GRAND TOTAL') {
                            $(row).css({
                                'background-color': '#f8f9fa',
                                'font-weight': 'bold',
                                'border-top': '2px solid #dee2e6'
                            });
                        }
                    }
                });
            } catch (err) {
                console.error('Error fetching salary per department:', err);
            }
        }

        // -------------------------------------------------
        // FIX DATATABLE WHEN TAB SWITCHED
        // -------------------------------------------------
        // UNIVERSAL TAB EVENT HANDLER for Bootstrap 4 & 5
        $(document).on('shown.bs.tab shown.bs.collapse', 'a[data-toggle="tab"], a[data-bs-toggle="tab"], [data-toggle="collapse"], [data-bs-toggle="collapse"]', function () {
            setTimeout(() => {
                $.fn.dataTable.tables({ visible: true, api: true })
                    .columns.adjust()
                    .draw(false);
            }, 20); // small delay = very stable
        });

        // -------------------------------------------------
        // AUTO FORMAT NUMERIC COLUMNS
        // -------------------------------------------------
        function autoFormatNumericColumns(tableSelector) {
            const table = $(tableSelector).DataTable();

            table.rows().every(function () {
                const rowData = this.data();
                const newData = {};

                for (let key in rowData) {
                    const val = rowData[key];

                    // numeric check tanpa menghapus string lain
                    if (!isNaN(val) && val !== null && val !== "") {
                        newData[key] = formatNumberNoRound(val);
                    } else {
                        newData[key] = val;
                    }
                }

                this.data(newData);
            });

            table.draw(false);
        }

        function formatNumberNoRound(value) {
            if (value === null || value === undefined || value === '') return value;

            let num = Number(value);
            if (isNaN(num)) return value;

            const isInteger = Number.isInteger(num);

            // Kurang dari 100 → tampil apa adanya, tanpa ribuan, tanpa rounding
            if (Math.abs(num) < 100) {
                return value.toString();
            }

            // Jika 100 atau lebih → format ribuan, tapi pertahankan decimal asli
            let [intPart, decPart] = value.toString().split('.');

            intPart = Number(intPart).toLocaleString("en-US"); // ribuan

            return decPart ? `${intPart}.${decPart}` : intPart;
        }

        // -------------------------------------------------
        // MODAL CLOSE BUTTON HANDLER
        // -------------------------------------------------
        $(document).on('click', '#closeProgressBtn', function () {
            $('#progressModal').modal('hide');
        });

        function showExportLoader() {
            $('#exportLoading').show();
        }

        function hideExportLoader() {
            $('#exportLoading').hide();
        }

    });


</script>
@endsection
