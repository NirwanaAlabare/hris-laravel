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

<!---Sweetalert Css-->
<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />





<!-- 1. Bootstrap 5 CSS (Used by DataTables integration) -->
{{--
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

<!-- 2. DataTables Core CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">

<!-- 3. DataTables Bootstrap 5 Integration CSS (Essential for proper look) -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">

<!-- 4. DataTables Buttons CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.min.css">


<style>
    :root {
        font-family: 'Inter', sans-serif;
    }

    /* ------------------------------------- */
    /* Core Dashboard and Tab Styling */
    /* ------------------------------------- */
    .tab-container {
        max-width: 100%;
        margin: 0 auto;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding-bottom: 20px;
    }

    .tab-nav {
        border-bottom: 1px solid #dee2e6;
        padding: 0 20px;
        display: flex;
    }

    .tab-link {
        background-color: #fff;
        color: #6c757d;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        transition: all 0.3s;
        border-bottom: 3px solid transparent;
        font-weight: 500;
        margin-right: 5px;
    }

    .tab-link.active {
        color: #007bff;
        border-bottom: 3px solid #007bff;
        font-weight: 600;
    }

    .tab-link:hover:not(.active) {
        color: #007bff;
        background-color: #f1f1f1;
    }

    .tab-content {
        display: none;
        padding: 20px;
    }

    .tab-content.active {
        display: block;
    }

    /* ------------------------------------- */
    /* DataTables Custom Styling & Overrides (High Specificity) */
    /* ------------------------------------- */

    /* 1. Wrapper Fixes */
    .dataTables_wrapper {
        padding: 0 0 15px 0;
        /* Adjusted padding within the tab-content */
    }

    .dataTables_wrapper .row {
        /* Fix for potential negative margins from Bootstrap in DataTables wrapper */
        margin: 0 !important;
    }

    /* 2. Table General Appearance */
    .display {
        width: 100% !important;
        font-size: 0.85rem !important;
        /* Ensures small font size is respected */
        border-collapse: collapse;
        /* Ensure clean borders */
    }

    /* 3. Table Header (THEAD) Styling */
    .display thead th {
        /* Using !important for strong override against DataTables defaults */
        background-color: #f8f9fa !important;
        color: #495057 !important;
        padding: 8px 6px !important;
        font-weight: 600 !important;
        white-space: normal !important;
        line-height: 1.2;
        text-align: left;
        /* Default text alignment */
    }

    /* 4. Table Body (TBODY) Styling */
    .display tbody td {
        padding: 6px 6px !important;
        color: #495057;
        white-space: nowrap;
        /* Keep content tight */
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* 5. Custom Alignment/Formatting Classes (High Specificity) */
    .text-center {
        text-align: center !important;
    }

    .text-right {
        text-align: right !important;
    }

    /* 6. Anomaly Status Coloring */
    .display .anomaly_status {
        font-weight: 600;
    }

    .display .anomaly_status[data-content="OK"] {
        color: #28a745;
        /* Green */
    }

    .display .anomaly_status[data-content*="Missing"],
    .display .anomaly_status[data-content*="Duplicate"],
    .display .anomaly_status[data-content*="Unverified"] {
        color: #dc3545;
        /* Red */
    }

    /* 7. Button styling for export/visibility */
    .dt-buttons .dt-button {
        padding: 0.375rem 0.75rem;
        border-radius: 0.25rem;
        margin-right: 5px;
        background-color: #007bff;
        color: white;
        border: none;
        box-shadow: 0 2px 4px rgba(0, 123, 255, 0.2);
        transition: background-color 0.2s;
    }

    .dt-buttons .dt-button:hover {
        background-color: #0056b3;
        color: white;
    }
</style>
@stop
@section('mainarea')
<!-- page-header -->
<div class="page-header p-2 shadow">
    <ol class="breadcrumb breadcrumb-arrow mt-0">
        <li><a href="#">Master Data</a></li>
        <li class="active"><span>Rekap Lembur</span></li>
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
<!-- End page-header -->
<div class="card shadow card-collapsed">
    <div class="card-header bg-primary p-2">
        <div class="card-title">PROSES REKAP LEMBUR</div>
        <div class="card-options ">
            <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i
                    class="fe fe-chevron-up text-white"></i></a>
        </div>
    </div>
    <form id="form_proses_Lembur" method="post">
        @csrf
        <div class="card-body p-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">PERIODE REKAP LEMBUR : </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                </div>
                            </div>
                            <input name="periode_payrols" min="{{$month}}" type="month"
                                class="form-control PriodeProses fc-datepicker" required></input>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="card-footer bg-light m-0 p-1">
            <div class="text-white">
                <a id="BtnProsesLembur"
                    class="btn btn-app btn-primary mr-0 mt-0 mb-0 text-white BtnProsesLembur"><span><i
                            class="fa fa-download"></i></span> PROSES Lembur</a>
            </div>
        </div> --}}
    </form>
</div>

<!-- BEGIN FORM-->
{!! Form::open(['route' => 'hris.rekapperhitunganlembur.ajax_exportexcel', 'id' => 'formExport', 'name' =>
'formExport','method'=>'post']) !!}

@csrf
<input id="selDepVal" name="selDepVal" type="hidden">
<div class="card shadow">
    <div class="card-header bg-primary p-3">
        <div class="card-title">FILTER DATA LEMBUR</div>
        <div class="card-options ">
            <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i
                    class="fe fe-chevron-up text-white"></i></a>
        </div>
    </div>
    <div class="card-body p-3">
        <div class="row">
            <div class="col-md-12">
                <div class="expanel expanel-default mb-0">
                    <div class="expanel-body mr-0 mt-0 mb-0">
                        <div class="form-group m-0 p-0">
                            <label class="form-label">Tanggal Lembur : </label>
                            <input type="hidden" id="daterange1" name="daterange1">
                            <a class="nav-link card-title py-2 pl-3" style="border: 1px solid #d8d4dc"
                                id="daterange-btn1" data-toggle="tooltip" title="" data-placement="bottom"
                                data-original-title="Klik di sini untuk pilih tanggal kehadiran"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="progress progress-xs mb-0">
        <div id="progress-show-1" class="progress-bar progress-bar-indeterminate bg-green"></div>
        <div id="progress-hide-1" class="progress-bar"></div>
    </div>
    <div class="card-footer bg-primary m-0 p-1">
        <div class="text-white">
            <button type="submit" id="btn-exportexcel" class="btn btn-app btn-warning mr-0 mt-0 mb-0"
                data-toggle="tooltip" title="Export Data ke File Excel"><i class="ion-ios7-download"></i>
                Export</button>
            <a href="javascript:void(0)" id="btn-caridata" class="btn btn-app btn-secondary mr-0 mt-0 mb-0"
                data-toggle="tooltip" title="Cari Data"><i class="ion-search"></i> Cari</a>
            <button type="button" id="btn-overtime" class="btn btn-app btn-info mr-0 mt-0 mb-0" data-toggle="tooltip"><i
                    class="ion-ios7-download"></i>
                Calculate Overtime</button>
        </div>
    </div>
</div>
{{-- </form> --}}
{!! Form::close() !!}
<!-- END FORM-->

<!-- row Rekap Lembur-->
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div id="data-rekap-perhitungan-lembur" class="card shadow">
            <div class="card-header bg-primary p-3">
                <div class="card-title">REKAP PERHITUNGAN LEMBUR KARYAWAN</div>
                <div class="card-options ">
                    <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i
                            class="fe fe-chevron-up text-white"></i></a>
                </div>
            </div>
            <div class="card-body m-0">
                <div class="table-responsive">
                    <table id="datatable-ajax-crud"
                        class="table table-sm table-striped table-hover table-bordered w-100">
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
    </div>
</div>
<!-- row end -->


<!-- row Data Lembur -->
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div id="data-lembur" class="card shadow">
            <div class="card-header bg-primary p-3">
                <div class="card-title">Data Lembur</div>
                <div class="card-options ">
                    <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i
                            class="fe fe-chevron-up text-white"></i></a>
                </div>
            </div>
            <div class="card-body m-0">
                <div class="tab-container">
                    <div class="tab-nav">
                        <button class="tab-link active" onclick="openTab(event, 'Tab1')">📊 Dashboard</button>
                        <button class="tab-link" onclick="openTab(event, 'Tab2')">📝 Ok Data</button>
                        <button class="tab-link" onclick="openTab(event, 'Tab3')">👤 Not Ok Data</button>
                        <button class="tab-link" onclick="openTab(event, 'Tab4')">📈 All Data</button>
                    </div>

                    <div id="Tab1" class="tab-content active">
                        <h3>Dashboard Summary</h3>
                        <div class="table-responsive">
                            <table id="table1" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Report Type</th>
                                        <th>Status</th>
                                        <th>Total Records</th>
                                        <th>Total Hours</th>
                                        <th>Total Rupiah</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div id="Tab2" class="tab-content">
                        <h3>OK Data (Final Results)</h3>
                        <div class="table-responsive">
                            <table id="table2" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Enroll ID</th>
                                        <th>Date</th>
                                        <th>Hours</th>
                                        <th>Rupiah</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div id="Tab3" class="tab-content">
                        <h3>Not OK Data (Anomalies for Audit)</h3>
                        <div class="table-responsive">
                            <table id="table3" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Enroll ID</th>
                                        <th>Date</th>
                                        <th>Anomaly Status</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div id="Tab4" class="tab-content">
                        <h3>All Raw Data</h3>
                        <div class="table-responsive">
                            <table id="table4" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Enroll ID</th>
                                        <th>Date</th>
                                        <th>Employee Name</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-primary br-br-7 br-bl-7">
                <div class="text-white"></div>
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
            var daterange1 = start.format("YYYY-MM-DD") + " - " + end.format("YYYY-MM-DD");
            $('#daterange1').val(daterange1);
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
            var start = moment().subtract(29, 'days');
            var end = moment();
            var htmlDateRange = '<span><i class="fa fa-calendar"></i> ' + start.format("D MMM YYYY").toUpperCase() + ' s/d ' + end.format("D MMM YYYY").toUpperCase() + '</span><i class="fa fa-angle-down ml-1"></i>'
            $('#daterange-btn1').html(htmlDateRange);
            var daterange1 = start.format("YYYY-MM-DD") + " - " + end.format("YYYY-MM-DD");
            $('#daterange1').val(daterange1);
        });

        $(document).ready(function() {
            $("#data-rekap-perhitungan-lembur").hide();
        });

        function cari_rekap_lembur()
        {
            $("#data-rekap-perhitungan-lembur").show('slow');

            $('#datatable-ajax-crud').DataTable().clear();
            $('#datatable-ajax-crud').DataTable().destroy();
            $('#datatable-ajax-crud').empty();

            var daterange1 = $('#daterange1').val();
            var searchName = $('#searchName').val();

            console.log('daterange1',daterange1);
            var table1 = $('#datatable-ajax-crud').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                "ajax": {
                    "url": "{{ route('hris.rekapperhitunganlembur.ajax_rekap') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                    "data": {
                        daterange1:daterange1,
                        searchName:searchName,
                    },
                    // success: function (data) {
                    //                 console.log('data',data);
                    //                 notif({
                    //                     msg: "<b>Info:</b> Data Berhasil di Proses.",
                    //                     type: "info"
                    //                 });
                    //             },
                },
                columns: [
                    {
                        title: 'UUID',
                        data: 'uuid',
                        name: 'uuid'
                    },
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
                        title: 'NOMOR FORM SPL',
                        data: 'nomor_form_lembur',
                        name: 'nomor_form_lembur'
                    },
                    {
                        title: 'NAMA KARYAWAN',
                        data: 'employee_name',
                        name: 'employee_name'
                    },
                    {
                        title: 'JABATAN',
                        data: 'posisi_name',
                        name: 'posisi_name'
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
                    {
                        title: 'TANGGAL',
                        data: 'tanggal_berjalan',
                        name: 'tanggal_berjalan',
                        render: function (data) {
                            if (!data) return '';
                            let date = new Date(data);
                            let day = ("0" + date.getDate()).slice(-2);
                            let month = ("0" + (date.getMonth() + 1)).slice(-2);
                            let year = date.getFullYear();
                            return `${day}-${month}-${year}`;
                        }
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
                        title: 'KERJA/LIBUR',
                        data: 'kerjalibur',
                        name: 'kerjalibur'
                    },
                    {
                        title: 'JADWAL (IN)',
                        data: 'mulai_jam_kerja',
                        name: 'mulai_jam_kerja'
                    },
                    {
                        title: 'JADWAL (OUT)',
                        data: 'akhir_jam_kerja',
                        name: 'akhir_jam_kerja'
                    },
                    {
                        title: 'JUMLAH JAM KERJA',
                        data: 'jumlah_jam_kerja',
                        name: 'jumlah_jam_kerja'
                    },
                    {
                        title: 'ABSEN (IN)',
                        data: 'absen_masuk_kerja',
                        name: 'absen_masuk_kerja'
                    },
                    {
                        title: 'ABSEN (OUT)',
                        data: 'absen_pulang_kerja',
                        name: 'absen_pulang_kerja'
                    },
                    {
                        title: 'EFEKTIF KERJA (JAM)',
                        data: 'jam_efektif_kerja',
                        name: 'jam_efektif_kerja'
                    },
                    {
                        title: 'LEMBUR (IN)',
                        data: 'mulai_jam_lembur',
                        name: 'mulai_jam_lembur'
                    },
                    {
                        title: 'LEMBUR (OUT)',
                        data: 'akhir_jam_lembur',
                        name: 'akhir_jam_lembur'
                    },
                    {
                        title: 'FINAL (IN)',
                        data: 'final_mulai_jam_lembur',
                        name: 'final_mulai_jam_lembur'
                    },
                    {
                        title: 'FINAL (OUT)',
                        data: 'final_selesai_jam_lembur',
                        name: 'final_selesai_jam_lembur'
                    },
                    {
                        title: 'FINAL JAM',
                        data: 'final_total_jam_lembur',
                        name: 'final_total_jam_lembur'
                    },
                    {
                        title: 'BREAK',
                        data: 'final_jam_istirahat_lembur',
                        name: 'final_jam_istirahat_lembur'
                    },
                    {
                        title: 'TOTAL MENIT',
                        data: 'final_total_menit_lembur',
                        name: 'final_total_menit_lembur'
                    },
                    {
                        title: 'JAM',
                        data: 'final_jam_lembur_roundown',
                        name: 'final_jam_lembur_roundown'
                    },
                    {
                        title: 'MENIT',
                        data: 'final_menit_lembur_roundown',
                        name: 'final_menit_lembur_roundown'
                    },
                    {
                        title: 'L1',
                        data: 'lembur_1',
                        name: 'lembur_1'
                    },
                    {
                        title: 'L2',
                        data: 'lembur_2',
                        name: 'lembur_2'
                    },
                    {
                        title: 'L3',
                        data: 'lembur_3',
                        name: 'lembur_3'
                    },
                    {
                        title: 'L4',
                        data: 'lembur_4',
                        name: 'lembur_4'
                    },
                    {
                        title: 'TOTAL L',
                        data: 'total_lembur_1234',
                        name: 'total_lembur_1234'
                    },
                    {
                        title: 'Salary',
                        data: 'salary',
                        name: 'salary'
                    },
                    {
                        title: 'Lembur 1 (Rp)',
                        data: 'lembur1_rupiah',
                        name: 'lembur1_rupiah'
                    },
                    {
                        title: 'Lembur 2 (Rp)',
                        data: 'lembur2_rupiah',
                        name: 'lembur2_rupiah'
                    },
                    {
                        title: 'Lembur 3 (Rp)',
                        data: 'lembur3_rupiah',
                        name: 'lembur3_rupiah'
                    },
                    {
                        title: 'Lembur 4 (Rp)',
                        data: 'lembur4_rupiah',
                        name: 'lembur4_rupiah'
                    },
                    {
                        title: 'TOTAL Lembur (Rp)',
                        data: 'total_lembur_rupiah',
                        name: 'total_lembur_rupiah'
                    },
                ],
                columnDefs: [
                    {
                        'visible': false,
                        'targets': [0,9]
                    },
                    {
                        "targets": [1,11,12,13,15,16,17,18,19,20,21,22,23,24],
                        "className": "w-5 text-center",
                    },
                    {
                        "targets": [2,3,4,5,6,7,8,10],
                        "className": "text-nowrap",
                    },
                    {
                        "targets": [14,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37],
                        "className": "w-10 text-right",
                    },
                ],
                order: [
                    [3, 'asc'],[4, 'asc']
                ],
                "createdRow": function (row, data, dataIndex) {
                    if ((data['kode_hari'] == "5") || (data['kode_hari'] == "6") || (data['holiday_name'])) {
                        $(row).css('background', 'yellow');
                    }
                    if (data['total_lembur_1234'] == "0") {
                        $(row).addClass('bg-danger');
                    }
                }
            });

            table1.draw();

            $('#datatable-ajax-crud tbody').on('click', 'tr', function () {
                var tr = $(this).closest('tr');
                var row = table1.row(tr);

                var data = row.data();

                $("#datatable-ajax-crud tbody tr").removeClass('bg-cyan');
                $(this).addClass('bg-cyan');

             });


        }
        $('body').on('click', '#btn-caridata', function (event) {
            cari_rekap_lembur()
        });

</script>

<script>
   
    function formatRupiah(data, type, row) {
        if (type === 'display' || type === 'filter') {
            if (data === null || data === undefined || isNaN(data)) {
                return 'Rp 0';
            }
            
            // Ensure data is a number and fix to 2 decimal places for safety
            const number = parseFloat(data).toFixed(2);
            
            // Use Intl.NumberFormat for proper Indonesian formatting
            return new Intl.NumberFormat('id-ID', {
                style: 'decimal',
                // currency: 'IDR',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(number);
        }
        return data; // Return raw data for sorting/other types
    }

   
    function formatTimeOnly(data, type, row) {
        if (type === 'display' || type === 'filter') {
            if (!data || typeof data !== 'string') {
                return '';
            }
            
            // Find the index of the space separating date and time
            const spaceIndex = data.indexOf(' ');
            if (spaceIndex !== -1 && data.length > spaceIndex + 1) {
                // Get the time part (e.g., '17:00:00' or '17:00')
                const timePart = data.substring(spaceIndex + 1);
                
                // Return only the hour and minute (e.g., '17:00')
                return timePart.substring(0, 5); 
            }
            
            // If it's just a time string (e.g., '17:00:00'), return first 5 chars
            if (data.includes(':')) {
                return data.substring(0, 5);
            }
        }
        return data; // Return raw data for sorting/other types
    }

    // --- DataTables Column Definitions ---

    // Define a static mapping for the column headers where you need a specific, non-DB display name.
    // This MAP ensures consistent, human-readable Indonesian titles for all dynamic tables (Tab 2, 3, 4).
    const DETAIL_COLUMN_MAP = {
        // --- Core/Original Fields ---
        'enroll_id': 'Enroll ID',
        'employee_name': 'Nama Karyawan',
        '_department_name': 'Department',
        'tanggal_berjalan': 'Tanggal Berjalan',
        'kode_hari': 'Kode Hari',
        'mulai_jam_kerja': 'Mulai Jam Kerja (Jadwal)',
        'akhir_jam_kerja': 'Akhir Jam Kerja (Jadwal)',
        'status_absen': 'Status Absen',
        'nomor_form_lembur': 'No. Form Lembur',
        'kode_grade': 'Kode Grade',
        'salary_bulanan': 'Gaji Bulanan',
        'absen_masuk_kerja': 'Absen Masuk',
        'absen_pulang_kerja': 'Absen Pulang',
        'is_verifikasi': 'Status Verifikasi',
        'jumlah_jam_lembur': 'Jam Lembur Diajukan',
        'jumlah_jam_istirahat_lembur': 'Istirahat Lembur (Jam)',
        'mulai_jam_lembur': 'Mulai Jam Lembur (Form)',
        'capai_target': 'Capai Target (Jam)', 
        
        // --- Calculated/Intermediate Fields ---
        'finish_in': 'Final Mulai Lembur',
        'finish_out': 'Final Akhir Lembur',
        'selisih_jam': 'Selisih Jam Mentah',
        'selisih_menit': 'Selisih Menit Mentah',
        'final_total': 'Selisih Jam Total (Raw)',
        'konversi_jam': 'Konversi Menit (Jam)',
        'total_jam_lembur_finis': 'Jam Lembur Final',
        'kerjalibur': 'Status Kerja/Libur',
        
        // --- L1/L2/L3/L4 Hour Breakdown ---
        'L1': 'L1 (Jam)',
        'L2': 'L2 (Jam)',
        'L3': 'L3 (Jam)',
        'L4': 'L4 (Jam)',
        
        // --- Final Calculated Fields ---
        'anomaly_status': 'Status Anomali', // CRITICAL: Used for filtering
        'final_total_menit_lembur': 'Total Menit Mentah',
        'final_jam_lembur_roundown': 'Jam Mentah Round Down',
        'final_menit_lembur_roundown': 'Menit Mentah Round Down',
        'total_lembur_1234': 'Total Jam Lembur (Final)',
        
        // --- Rupiah Fields ---
        'l1_rupiah': 'L1 Rupiah',
        'l2_rupiah': 'L2 Rupiah',
        'l3_rupiah': 'L3 Rupiah',
        'l4_rupiah': 'L4 Rupiah',
        'total_lembur_rupiah': 'Total Rupiah Lembur',
        
        // Note: 'selisih_detik' is usually too verbose, so we exclude it from the map.
        // It will be dynamically formatted if it ever appears in the data, but is usually hidden.
    };

    /**
     * Dynamically generates column definitions based on data keys, using the map for display titles.
     * @param {string[]} dataKeys - Array of keys from the first row of data
     * @returns {Object[]} DataTables columns array
     */
    function getDetailColumns(dataKeys) {
        return dataKeys.map(key => ({
            data: key,
            // Use the custom map title. If not found, fall back to generic Title Case formatting.
            title: DETAIL_COLUMN_MAP[key] || key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) 
        }));
    }

    // Function for the Dashboard Summary table (Tab1) - The structure is fixed
    function getSummaryColumns(dataKeys) {
        return [
            { data: 'ReportType', title: 'Report Type' }, // Added in SP SELECT
            { data: 'anomaly_status', title: 'Status' },
            { data: 'TotalRecords', title: 'Total Records' },
            { data: 'TotalFinalOvertimeHours', title: 'Total Hours' },
            { data: 'TotalRupiahValue', title: 'Total Rupiah' }
        ];
    }
    
    /**
     * Destroys, replaces THEAD, and re-initializes a DataTable with new data and dynamic columns.
     */

     
    function loadAndInitializeDataTable(tableId, dataArray, columnFunction) {
        const $table = $(tableId);
        
        // 1. Destroy existing instance if it exists
        if ($.fn.DataTable.isDataTable(tableId)) {
            $table.DataTable().clear().destroy();
            $table.find('tbody').empty(); 
        }
        
        // 2. Determine Columns dynamically (THIS BLOCK MUST COME FIRST)
        let columns = [];
        // let displayKeys = dataArray.length > 0 ? Object.keys(dataArray[0]) : [];

        // If data is empty for detail tables, we use a structural fallback
        // if (displayKeys.length === 0 && tableId !== '#table1') {
        //     // Fallback: Use the map keys for structure even if the data is empty
        //     displayKeys = Object.keys(DETAIL_COLUMN_MAP);
        // }

        if (tableId !== '#table1') {
            // FIX: Prioritize column order from the map (for tables 2, 3, 4)
            let displayKeys = Object.keys(DETAIL_COLUMN_MAP);
            
            // Add any extra keys present in the data but NOT in the map to the end
            if (dataArray.length > 0) {
                const dataKeys = Object.keys(dataArray[0]);
                dataKeys.forEach(key => {
                    // Only add keys from the data if they are NOT already in the fixed map order
                    if (!displayKeys.includes(key)) {
                        displayKeys.push(key);
                    }
                });
            }
            
            columns = columnFunction(displayKeys);

        } else {
                // Logic for #table1 (Dashboard) remains dynamic based on its own data 
            let displayKeys = dataArray.length > 0 ? Object.keys(dataArray[0]) : [];
            columns = columnFunction(displayKeys); 
        }

        // columns = columnFunction(displayKeys); // <-- 'columns' array is now populated!
        
        // 3. CRITICAL STEP: Replace the table's THEAD content
        const $thead = $table.find('thead');
        $thead.empty();
        let headerRow = '<tr>';
        columns.forEach(col => {
            headerRow += `<th>${col.title}</th>`;
        });
        headerRow += '</tr>';
        $thead.append(headerRow);

        // 4. --- Dynamic Column Definitions (Formatting & Alignment) ---
        // (THIS BLOCK IS MOVED HERE, AFTER 'columns' IS DEFINED)
        let columnDefsArray = [];
        
        if (tableId !== '#table1') {
            
            const rupiahKeys = ['l1_rupiah', 'l2_rupiah', 'l3_rupiah', 'l4_rupiah', 'total_lembur_rupiah'
                                ,'salary_bulanan'
                                
                            ];
            const numericKeys = [
                'jumlah_jam_lembur', 'final_total_menit_lembur', 'final_jam_lembur_roundown', 
                'final_menit_lembur_roundown', 'total_lembur_1234', 'L1', 'L2', 'L3', 'L4'
            ];
            const timeOnlyKeys = ['mulai_jam_lembur', 'akhir_jam_kerja', 'mulai_jam_kerja', 'finish_in', 'finish_out'];
            
            // Map keys to their column index (target)
            // NOTE: The 'columns' variable is now available and correct.
            const getTargets = (keys) => columns.map((col, idx) => keys.includes(col.data) ? idx : null).filter(idx => idx !== null);

            const rupiahTargets = getTargets(rupiahKeys);
            const numericTargets = getTargets(numericKeys);
            const timeOnlyTargets = getTargets(timeOnlyKeys);

            // A. Right align and format Rupiah columns
            columnDefsArray.push({
                "targets": rupiahTargets,
                "className": "text-right",
                "render": formatRupiah
            });
            
            // B. Center align general numeric columns (excluding Rupiah)
            columnDefsArray.push({
                "targets": numericTargets.filter(idx => !rupiahTargets.includes(idx)),
                "className": "text-center"
            });
            
            // C. Format Time-only columns
            columnDefsArray.push({
                "targets": timeOnlyTargets,
                "render": formatTimeOnly
            });
            
            // D. Center align and add data-content for anomaly status coloring
            const anomalyTarget = getTargets(['anomaly_status']);
            if (anomalyTarget.length > 0) {
                columnDefsArray.push({
                    "targets": anomalyTarget,
                    "className": "text-center anomaly_status", // Added anomaly_status class
                    "createdCell": function (td, cellData, rowData, row, col) {
                        // This is for custom CSS coloring/styling
                        $(td).attr('data-content', cellData); 
                    }
                });
            }
        }
        
        // 5. Re-initialize DataTable
        $table.DataTable({
            "data": dataArray,
            "columns": columns,
            "autoWidth": false,
            "deferRender": true,
            "paging": true,
            "ordering": true,
            "info": true,
            "searching": true,
            "order": tableId === '#table1' ? [[0, 'asc']] : [[1, 'asc']], // Default ordering
            "columnDefs": columnDefsArray, // <-- Now uses the correctly generated array
            "dom": '<"row"<"col-sm-12 col-md-4"l><"col-sm-12 col-md-4 dt-buttons-center"B><"col-sm-12 col-md-4"f>><"row"<"col-sm-12"t>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            "buttons": [
                        'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
                    ],
            "createdRow": function (row, data, dataIndex) {
                if ((data['kode_hari'] == "5") || (data['kode_hari'] == "6") || (data['holiday_name'])) {
                    $(row).css('background', 'yellow'); 
                }
                if (data['total_lembur_1234'] == "0") {
                    $(row).css('background', '#f5d5d5'); 
                }
            }
        });
        
        // 6. Adjust columns if the table is currently visible
        if ($table.is(':visible')) {
            $table.DataTable().columns.adjust().draw();
        }
    }

    // Tab switching function
    function openTab(evt, tabName) {
        $('.tab-content').removeClass('active');
        $('.tab-link').removeClass('active');
        $('#' + tabName).addClass('active');
        $(evt.currentTarget).addClass('active');

        const tableSelector = '#' + tabName + ' table';
        if ($.fn.DataTable.isDataTable(tableSelector)) {
            $(tableSelector).DataTable().columns.adjust().draw();
        }
    }
    window.openTab = openTab; // Make function globally accessible
    // --- Document Ready and AJAX Logic ---

    $(document).ready(function(){
        // Initial setup: Initialize the first tab's table structure (starts empty)
        // Since we are using loadAndInitializeDataTable after AJAX, 
        // we can skip initial setup and rely on the AJAX call to load the first view.
        // If you need a structural table on load, use:
        // loadAndInitializeDataTable('#table1', [], getSummaryColumns); 
        
        
        // 1. Click handler for the calculation button
        $('#btn-overtime').on('click', function(e) {
            e.preventDefault(); 
            const $button = $(this);
            
            // Collect Data
            const daterange = $('#daterange1').val();
            const enrollIds = $('#selected_enroll_ids').val(); 
            
            // Split the date range string using ' - '
            let tanggalAwal = null;
            let tanggalAkhir = null;
            if (daterange) {
                const dates = daterange.split(' - '); 
                if (dates.length === 2) {
                    tanggalAwal = dates[0].trim();
                    tanggalAkhir = dates[1].trim();
                }
            }
            
            // Basic validation
            // if (!tanggalAwal || !tanggalAkhir || !enrollIds) {
            //     alert('Please ensure the date range and employee IDs are selected correctly.');
            //     return;
            // }

            // 2. Send AJAX Request
            $.ajax({
                url: '{{ route("overtime.calculate") }}', 
                type: 'POST', 
                dataType: 'json', 
                data: {
                    tanggal_awal: tanggalAwal,
                    tanggal_akhir: tanggalAkhir,
                    enroll_ids: enrollIds,
                    _token: '{{ csrf_token() }}' 
                },
                beforeSend: function() {
                    $button.prop('disabled', true).text('Processing...');
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        
                        if (response.data) {
                            // --- DYNAMICALLY LOAD AND INITIALIZE TABLES ---
                            
                            // Tab 1: Dashboard (Summary Data - SP Result 4)
                            loadAndInitializeDataTable('#table1', response.data.tab1_data, getSummaryColumns); 
                            
                            // Tab 2: OK Data (Detail Data - SP Result 1)
                            loadAndInitializeDataTable('#table2', response.data.tab2_data, getDetailColumns); 
                            
                            // Tab 3: Not OK Data (Detail Data - SP Result 2)
                            loadAndInitializeDataTable('#table3', response.data.tab3_data, getDetailColumns); 
                            
                            // Tab 4: All Data (Detail Data - SP Result 3)
                            loadAndInitializeDataTable('#table4', response.data.tab4_data, getDetailColumns); 
                            
                            // Ensure the currently visible table (Tab1) is correctly drawn
                            $('#table1').DataTable().columns.adjust().draw();
                        }
                    } else {
                        alert('Error: ' + (response.message || 'An unknown server error occurred.'));
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'An unexpected error occurred.';
                    if (xhr.responseJSON) {
                        errorMessage = xhr.responseJSON.message || JSON.stringify(xhr.responseJSON);
                    }
                    alert('Calculation Failed: ' + errorMessage);
                    console.error("AJAX Error:", xhr.responseText);
                },
                complete: function() {
                    $button.prop('disabled', false).text('Calculate Overtime');
                }
            });
        });
    });
</script>

@endsection