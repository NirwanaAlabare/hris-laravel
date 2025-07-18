@extends('admin.adminlayouts.adminlayout3')

@section('head')
<link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{ URL::asset('assets/css/iziToast.min.css') }}">
<link href="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.css')}}" rel="stylesheet" />
<style>
.loading-overlay {
    position: relative;
}
.loading-overlay::after {
    content: "Loading...";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(255, 255, 255, 0.7);
    padding: 20px;
    border-radius: 8px;
    z-index: 10;
    font-weight: bold;
}

</style>
<style>
#datatable-loading-overlay {
    display: none;
    position: absolute;
    top: 0;
    left: 0;
    background: rgba(255,255,255,0.6);
    width: 100%;
    height: 100%;
    z-index: 99;
    display: flex;
    justify-content: center;
    align-items: center;

    font-size: 18px;
    font-weight: bold;
    color: #333;
}
</style>

@stop
@section('mainarea')

<!-- page-header -->
<div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
    <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
        <li><a href="{{route('hris.dashboard.tes')}}">DASHBOARD</a></li>
        <li class="active"><span>Layoff & Termination</span></li>
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
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card shadow">
            <div class="accordion mb-0" id="accordionExample">
                <div class="card mb-0">
                    <div class="card-header p-0 bg-light" id="headingTwo" style="border: 1px solid rgb(210, 210, 210);">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="font-weight:bold;font-size:12pt">
                                <i class="fa fa-filter pl-4" aria-hidden="true"></i> Filter
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body px-6" style="border: 1px solid rgb(210, 210, 210);">
                            <div class="row pb-2">
                                <div class="col-2 pt-1">
                                    <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Nama Karyawan</label>
                                </div>
                                <div class="col-2 pr-0">
                                    <select id="selectEmployeeID" name="selectEmployeeID[]" multiple data-placeholder="Pilih karyawan" class="form-control select2 EmployeeID">
                                        @foreach ($selectEmployee as $r_empl)
                                            <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-1 pt-1"></div>
                                   <div class="col-1 pt-1">
                                    <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Periode</label>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex">
                                            <div class="form-group m-0">
                                                <input type="hidden" id="daterange1" name="daterange1">
                                                <a class="nav-link card-title py-2 pl-3" style="border: 1px solid #d8d4dc" id="daterange-btn1" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Klik di sini untuk pilih tanggal kehadiran"></a>
                                            </div>
                                            <button id="clear-daterange" style="height: 36px; justify-content: center; align-items: center;"
                                                class="btn btn-outline-danger ml-1" data-toggle="tooltip" title="Hapus Periode">
                                                <i class="fa fa-times m-0 p-0"></i>
                                            </button>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button class="btn btn-success btn-app" id="btn_export_excel_layoff_currday" onclick="export_excel()">
                                        <i class="fa fa-file-pdf-o"></i> Rekap Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-6 pt-2 pb-5">
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="datatable-wrapper">
                            <div style="position: relative;">
                            <div id="datatable-loading-overlay">Loading...</div>
                            <table id="datatable" class="table table-bordered table-sm w-100 table-hover text-nowrap">
                                <thead class="table-info">
                                    <tr style='text-align:center;'>
                                        <th rowspan="2" width="3%" style="vertical-align: middle;font-weight:bold">Id</th>
                                        <th rowspan="2" width="14%" style="vertical-align: middle;font-weight:bold">Employee Name</th>
                                        <th rowspan="2" width="14%" style="vertical-align: middle;font-weight:bold">Department</th>
                                        <th colspan="2" width="20%" style="vertical-align: middle;font-weight:bold">Mangkir</th>
                                        <th rowspan="2" width="3%" style="vertical-align: middle;font-weight:bold;border:1px solid rgb(195, 195, 195)">Jumlah (Hari)</th>
                                        <th rowspan="2" width="5%" style="vertical-align: middle;font-weight:bold;border:1px solid rgb(195, 195, 195)"><span class="fa fa-cog"></span></th>
                                    </tr>
                                    <tr style='text-align:center; vertical-align:middle'>
                                        <th style="font-weight:bold">Mulai</th>
                                        <th style="font-weight:bold">Sampai</th>
                                    </tr>
                                </thead>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajax-modal-no-form-pengajuan" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document" style="max-width:30%;">
        <div class="row">
            <div class="col-md-12">
                <div class="modal-content">
                    <div class="modal-header bg-primary p-2">
                        <h4 class="modal-title pl-2 font-weight-bold">Nomor Form</h4>
                        <div class="mt-0 p-0">
                            <button onclick="closeModalNoForm()" class="btn btn-danger btn-sm w-100" data-toggle="tooltip" title="Tutup">x</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <input id="enroll_id_layoff" type="hidden">
                        <div class="row">
                            <div class="col-md-12" style="margin-top: 1px; margin-bottom: 3px; padding-top: 10px;">
                                <h6 style="font-weight: bold;">No Form :</h6>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input id="edit_no_form" required name="edit_no_form" class="form-control" rows="3" placeholder="Nomor Form" maxlength="500"></input>
                                    <small class="error-message text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-5">
                            </div>
                             <div class="col-md-3">
                                <button class="btn btn-success w-100" id="btn-send-to-whatsapp" data-toggle="tooltip" title="Kirim laporan ke whatsapp" style="margin-top: 10px;">
                                    <i class="fa fa-send" aria-hidden="true"></i>
                                    whatsapp
                                </button>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary w-100" id="btn-update-form" data-toggle="tooltip" title="Simpan & Print" style="margin-top: 10px;">
                                    <i class="fa fa-save" aria-hidden="true"></i>
                                    Simpan & Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('footerjs')
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{URL::asset('assets/js/iziToast.min.js')}}"></script>
 <!-- Datepicker js -->
 <script src="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.js')}}"></script>
 <script src="{{URL::asset('assets/plugins/spectrum-date-picker/jquery-ui.js')}}"></script>
 <script src="{{URL::asset('assets/plugins/input-mask/jquery.maskedinput.js')}}"></script>
<style>
    #datatable {
    table-layout: fixed;
    }

</style>
<script type="text/javascript">

    // $(document).ready(function() {
    //     const start = moment().subtract(29, 'days');
    //     const end = moment();

    //     // Set nilai awal
    //     updateRange(start, end);
    //     function updateRange(start, end) {
    //         $('#daterange-btn1').html(
    //             '<span><i class="fa fa-calendar"></i> ' +
    //             start.format("D MMM YYYY").toUpperCase() +
    //             ' s/d ' +
    //             end.format("D MMM YYYY").toUpperCase() +
    //             '</span><i class="fa fa-angle-down ml-1"></i>'
    //         );

    //         const daterange1 = start.format("YYYY-MM-DD") + " s/d " + end.format("YYYY-MM-DD");
    //         $('#daterange1').val(daterange1);
    //     }

    //     // Inisialisasi dan isi daterange1 terlebih dahulu
    //     $('#daterange-btn1').daterangepicker({
    //         ranges: {
    //             'Hari ini': [moment(), moment()],
    //             'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    //             '7 Hari Kemarin': [moment().subtract(6, 'days'), moment()],
    //             '30 Hari Kemarin': [moment().subtract(29, 'days'), moment()],
    //             'Bulan Sekarang': [moment().startOf('month'), moment().endOf('month')],
    //             'Bulan Kemarin': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    //         },
    //         startDate: start,
    //         endDate: end,
    //         maxDate: moment()
    //     }, function (start, end) {
    //         updateRange(start, end);
    //         datatable.ajax.reload(); // reload setelah user pilih tanggal
    //     });

    // });

    // Tombol clear
$('#clear-daterange').on('click', function () {
    $('#daterange1').val('');
    $('#daterange-btn1').html(
        '<span><i class="fa fa-calendar"></i> Pilih Tanggal</span><i class="fa fa-angle-down ml-1"></i>'
    );
    datatable.ajax.reload(); // reload data tanpa filter tanggal
});


    $(document).ready(function() {
    const start = moment().subtract(29, 'days');
    const end = moment();

    // Hapus updateRange() di awal, biarkan kosong
    $('#daterange-btn1').html(
        '<span><i class="fa fa-calendar mr-2"></i>   Pilih Range Tanggal   </span><i class="fa fa-angle-down ml-2"></i>'
    );

    $('#daterange1').val(''); // Pastikan input filter kosong di awal

    // Fungsi updateRange tetap ada untuk dipanggil setelah user memilih tanggal
    function updateRange(start, end) {
        $('#daterange-btn1').html(
            '<span><i class="fa fa-calendar"></i> ' +
            start.format("D MMM YYYY").toUpperCase() +
            ' s/d ' +
            end.format("D MMM YYYY").toUpperCase() +
            '</span><i class="fa fa-angle-down ml-1"></i>'
        );

        const daterange1 = start.format("YYYY-MM-DD") + " s/d " + end.format("YYYY-MM-DD");
        $('#daterange1').val(daterange1);
    }

    $('#daterange-btn1').daterangepicker({
        ranges: {
            'Hari ini': [moment(), moment()],
            'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '7 Hari Kemarin': [moment().subtract(6, 'days'), moment()],
            '30 Hari Kemarin': [moment().subtract(29, 'days'), moment()],
            'Bulan Sekarang': [moment().startOf('month'), moment().endOf('month')],
            'Bulan Kemarin': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: start,
        endDate: end,
        maxDate: moment()
    }, function (start, end) {
        updateRange(start, end);
        datatable.ajax.reload(); // reload setelah user pilih tanggal
    });

});

</script>
<script type="text/javascript">

    $('body').on('click', '#btn-update-form', function (event) {
        var edit_no_form = $('#edit_no_form').val();
        if(edit_no_form === '') {
            iziToast.error({
                title: 'Error',
                message: 'Nomor Form tidak boleh kosong',
                position: 'topRight'
            });
            return;
        }
        export_sp_kerja($('#enroll_id_layoff').val(), edit_no_form);
    });
    $('body').on('click', '#btn-send-to-whatsapp', function (event) {
        var edit_no_form = $('#edit_no_form').val();
        if(edit_no_form === '') {
            iziToast.error({
                title: 'Error',
                message: 'Nomor Form tidak boleh kosong',
                position: 'topRight'
            });
            return;
        }
        var enroll_id = $('#enroll_id_layoff').val();
        $("#btn-send-to-whatsapp").addClass("btn-loading");
        $("#btn-send-to-whatsapp").attr("disabled", true);
        $.ajax({
            type: "post",
            url: '{{ route('hris.hrd.send_to_whatsapp_laporan_pemanggilan') }}',
            data: {
                enroll_id: enroll_id,
                no_form: edit_no_form,
            },
            success: function(response) {
                {
                    if(response.status){
                        swal("WhatsApp", "Berhasil kirim ke whatsapp karyawan.", "success");
                        $('#btn-send-to-whatsapp').removeClass("btn-loading");
                        $("#btn-send-to-whatsapp").attr("disabled", false);
                    }else{
                        swal("WhatsApp", "Gagal kirim ke whatsapp karyawan.", "error");
                        $('#btn-send-to-whatsapp').removeClass("btn-loading");
                        $("#btn-send-to-whatsapp").attr("disabled", false);
                    }
                }
            },
            error: function(res){
                swal("WhatsApp", "Gagal kirim ke whatsapp karyawan.", "error");
                $('#btn-send-to-whatsapp').removeClass("btn-loading");
                $("#btn-send-to-whatsapp").attr("disabled", false);
            }
        });
    });

    function openModalNomorForm(id) {
        $("#ajax-modal-no-form-pengajuan").modal('show');
        $("#enroll_id_layoff").val(id);
    }
    function closeModalNoForm() {
        $("#edit_no_form").val('');
        $("#enroll_id_layoff").val('');
        $("#ajax-modal-no-form-pengajuan").modal('hide');
    }

    $(document).ready(function() {
        let datatableFilter = document.getElementById("datatable_filter");
        datatableFilter.innerHTML = `<span> Search : </span><input type="text" class="form-control form-control-sm" id="search_variable" onkeyup="dataTableReload()">`;
    });

    $('#excel_file_kontrak').change(function() {
        fill_the_table();
    });

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
    var currentPageCheck = 0;
    var checkedEmployeeArr = [];

    let datatable = $("#datatable").DataTable({
        ordering: true,
        processing: true,
        serverSide: true,
        paging: true,
        searching: true,
        destroy: true,
        scrollX: false,
        ajax: {
            url: '{{ route('hris.hrd.sp_hadir_adjustment') }}',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
                data: function(d) {
                d.enroll_id = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
                d.periode_payroll =  $('#daterange1').val();
                d.search_variable = $('#search_variable').val();
            },
        },
        columns: [
            {
                data: 'enroll_id',
            },
            {
                data: 'employee_name',
            },
            {
                data: 'department_name',
            },
            {
                data: 'mulai',
                orderable: false
            },
            {
                data: 'selesai',
                orderable: false
            },
            {
                data: 'jumlah_hari_mangkir',
            },
            {
                data: 'enroll_id',
                orderable: false
            },
        ],
        order: [
            [1, 'asc']
        ],

        columnDefs: [

            {
                targets: [0],
                render: (data, type, row, meta) => {
                    return `
                     <div class="row">
                        <div class="col text-center">
                                `+row.enroll_id+`
                        </div>
                    </div>
                    `
                }
            },
            {
                targets: [5],
                render: (data, type, row, meta) => {
                    return `
                     <div class="row">
                        <div class="col text-center">
                                `+row.jumlah_hari_mangkir+`
                        </div>
                    </div>
                    `
                }
            },
            {
                targets: [6],
                render: (data, type, row, meta) => {
                    let warnaBtn = 'btn-info'; // default
                    let btn_check = '';
                    if (row.kategori === 'SP-2') {
                        warnaBtn = 'btn-warning';
                    } else if (row.kategori === 'RESIGNED') {
                        warnaBtn = 'btn-danger';
                    }
                    if(row.kategori != row.sp_kerja){
                        btn_check = `<button class='btn btn-primary' id="btn_tandai_sp_kerja"
                                style='padding-top:0px;padding-bottom:0px; font-family:monospace; font-size:10pt'
                                onclick="handelTandaiSpKerja('${row.enroll_id} ','${row.kategori}')" title="Tandai Telah Diberikan SP Kerja">
                                <i class="fa fa-check" style="font-size:11pt"></i>
                                </button>`;
                    }else{
                         btn_check = `<a class='btn btn-outline-default text-default cursor-default'
                                id="btn_cancel_tandai_sp_kerja"
                                style='padding-top:0px;padding-bottom:0px; font-family:monospace; font-size:10pt'
                                title="Batalkan Telah Diberikan SP Kerja" onclick="handelCancelTandaiSpKerja('${row.enroll_id}')">
                                <i class="fa fa-minus-square-o" style="font-size:11pt"></i>
                                </a>`;
                    }
                    return `
                     <div class="row">
                        <div class="col text-center">
                           <button class='btn ${warnaBtn}'
                                style='padding-top:0px;padding-bottom:0px; font-family:monospace; font-size:10pt'
                                onclick="openModalNomorForm('${row.enroll_id}')"
                                >
                                ${row.kategori}
                            </button>
                            ${btn_check}
                        </div>
                    </div>
                    `
                }
            }
        ],
        "createdRow": function (row, data, dataIndex) {
            if ((data['tanggal_resign'] != null)) {
                if(new Date(data['tanggal_resign']).getTime()<=new Date()){
                    $(row).css('background', 'red');
                }else{
                    $(row).css('background', 'white');
                }
            }else{
                $(row).css('background', 'white');
            }
        },
    });

    $('#selectEmployeeID').on('change',function(){
        datatable.ajax.reload();
    });
    $('#periode_payroll').on('change',function(){
        datatable.ajax.reload();
    });
    $('#searchIbuKandung').on('keyup',function(){
        datatable.ajax.reload();
    });
    $('#searchNoKTP').on('keyup',function(){
        datatable.ajax.reload();
    });
    $('#status_kontrak').on('change',function(){
        datatable.ajax.reload();
    });
    $('#status_aktif').on('change',function(){
        datatable.ajax.reload();
    });
    function dataTableReload() {
        datatable.ajax.reload();
    }

    function export_sp_kerja(enroll_id){
        var url = 'export_sp_kehadiran_karyawan_adjustment?enroll_id='+enroll_id+ '&no_form=' + $('#edit_no_form').val();
        window.open(url, '_blank');
    }

    function handelTandaiSpKerja(enroll_id,status){
        $("#btn_tandai_sp_kerja").addClass("btn-loading");
        $("#btn_tandai_sp_kerja").attr("disabled", true);
         $("#datatable-loading-overlay").fadeIn();
        $.ajax({
            type: "post",
            url: '{{ route('hris.hrd.tandai_sp_kerja') }}',
            data: {
                enroll_id: enroll_id,
                status: status
            },
            success: function(response) {
                {
                    datatable.ajax.reload();
                    swal("", "Tandai telah dipanggil", "success");
                    $('#btn_tandai_sp_kerja').removeClass("btn-loading");
                    $("#btn_tandai_sp_kerja").html('<i class="fa fa-check" style="font-size:11pt"></i>');
                    $("#btn_tandai_sp_kerja").attr("disabled", false);
                }
            },
            error: function(res){
                datatable.ajax.reload();
                swal("", "Tandai telah dipanggil", "error");
                $('#btn_tandai_sp_kerja').removeClass("btn-loading");
                $("#btn_tandai_sp_kerja").attr("disabled", false);
                $("#btn_tandai_sp_kerja").html('<i class="fa fa-check" style="font-size:11pt"></i>');
            }
        });
    }

    function handelCancelTandaiSpKerja(enroll_id){
        $("#btn_cancel_tandai_sp_kerja").addClass("btn-loading");
        $("#btn_cancel_tandai_sp_kerja").attr("disabled", true);
         $("#datatable-loading-overlay").fadeIn();
        $.ajax({
            type: "post",
            url: '{{ route('hris.hrd.tandai_sp_kerja') }}',
            data: {
                enroll_id: enroll_id,
                status: null
            },
            success: function(response) {
                {
                    datatable.ajax.reload();
                    swal("", "Tandai telah dibatalkan", "success");
                    $('#btn_cancel_tandai_sp_kerja').removeClass("btn-loading");
                    $("#btn_cancel_tandai_sp_kerja").html('<i class="fa fa-minus-square-o" style="font-size:11pt"></i>');
                    $("#btn_cancel_tandai_sp_kerja").attr("disabled", false);
                }
            },
            error: function(res){
                datatable.ajax.reload();
                swal("", "Tandai telah dipanggil", "error");
                $('#btn_cancel_tandai_sp_kerja').removeClass("btn-loading");
                $("#btn_cancel_tandai_sp_kerja").attr("disabled", false);
                $("#btn_cancel_tandai_sp_kerja").html('<i class="fa fa-minus-square-o" style="font-size:11pt"></i>');
            }
        });
    }

    datatable.on('xhr.dt', function () {
        $("#datatable-loading-overlay").fadeOut();
    });


    function export_excel(){
        $("#btn_export_excel_layoff_currday").addClass("btn-loading");
        $("#btn_export_excel_layoff_currday").html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Loading...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
        $("#btn_export_excel_layoff_currday").attr("disabled", true);
        let enroll_id = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
        let periode_payroll = $('#daterange1').val();
        var today=new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        var hour = today.getHours();
        var minutes = today.getMinutes();
        var seconds = today.getSeconds();
        today_date = yyyy + '-' + mm + '-' + dd + ' '+ hour +'.'+minutes+'.'+seconds;
        $.ajax({
            type: "get",
            url: '{{ route('hris.hrd.export_rekap_hadir_layoff') }}',
            data: {
                enroll_id: enroll_id,
                periode_payroll: periode_payroll,
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response) {
                {
                    $('#btn_export_excel_layoff_currday').removeClass("btn-loading");
                    $("#btn_export_excel_layoff_currday").html('<i class="fa fa-file-excel-o" style="font-size:11pt"></i> Rekap Excel');
                    $("#btn_export_excel_layoff_currday").attr("disabled", false);
                    var blob = new Blob([response]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "Rekap Layoff "+today_date+" "+Math.ceil(Math.random()*1000000)+".xlsx";
                    link.click();
                }
            },
            error: function(res){
                swal("", "Export Rekap Layoff gagal", "error");
                $('#btn_export_excel_layoff_currday').removeClass("btn-loading");
                $("#btn_export_excel_layoff_currday").attr("disabled", false);
                $("#btn_export_excel_layoff_currday").html('<i class="fa fa-file-excel-o" style="font-size:11pt"></i> Rekap Excel');
            }
        });
    }

</script>
@endsection
