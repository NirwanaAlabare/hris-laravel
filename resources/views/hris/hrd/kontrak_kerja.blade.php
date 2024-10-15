@extends('admin.adminlayouts.adminlayout3')

@section('head')
<link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{ URL::asset('assets/css/iziToast.min.css') }}">

@stop
@section('mainarea')

<!-- page-header -->
<div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
    <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
        <li><a href="{{route('hris.dashboard.tes')}}">DASHBOARD</a></li>
        <li class="active"><span>KONTRAK KERJA</span></li>
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
                                    <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> No. KTP</label>
                                </div>
                                <div class="col-3 pr-0">
                                    <input type="number" id="searchNoKTP" name="searchNoKTP" class="form-control" style="background-color:white" placeholder="Masukkan No. KTP">
                                </div>
                                <div class="col-1"></div>
                                <div class="col-2 pt-1">
                                    <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Status Aktif</label>
                                </div>
                                <div class="col-3 pr-0">
                                    <select class="form-control" id="status_aktif">
                                        <option value="">Pilih Status Aktif</option>
                                        <option value="AKTIF">Aktif</option>
                                        <option value="TIDAK AKTIF">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row pb-2">
                                <div class="col-2 pt-1">
                                    <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Nama Karyawan</label>
                                </div>
                                <div class="col-3 pr-0">
                                    <select id="selectEmployeeID" name="selectEmployeeID[]" multiple data-placeholder="Pilih karyawan" class="form-control select2 EmployeeID">
                                        @foreach ($selectEmployee as $r_empl)
                                            <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-1"></div>
                                <div class="col-2 pt-1">
                                    <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Status Kontrak</label>
                                </div>
                                <div class="col-3 pr-0">
                                    <select class="form-control" id="status_kontrak">
                                        <option value="">Pilih Status Kontrak</option>
                                        <option value="Active">Aktif</option>
                                        <option value="Nonactive">Tidak Aktif</option>
                                        <option value="One Day">1 Hari Lagi</option>
                                        <option value="Thirty Day">30 Hari Lagi</option>
                                        <option value="Not yet extended">Belum Diperpanjang</option>
                                        <option value="Unfilled">Belum Diisi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row pb-2">
                                <div class="col-2 pt-1">
                                    <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Nama Ibu Kandung</label>
                                </div>
                                <div class="col-3 pr-0">
                                    <input type="text" id="searchIbuKandung" name="searchIbuKandung" class="form-control" style="background-color:white" placeholder="Masukkan Nama Ibu Kandung">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-6 pt-2 pb-5">
                <div class="row pb-2">
                    <button type="button" class="btn btn-app btn-success mr-0 mt-0 mb-0" data-target="#import_kontrak" data-toggle="modal" style="font-size:11pt"><i class="fa fa-file-excel-o" style="font-size:11pt"></i> Import Kontrak Kerja</button>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-sm w-100 table-hover text-nowrap">
                                <thead class="table-info">
                                    <tr style='text-align:center;'>
                                        <th rowspan="2" style="vertical-align: middle;font-weight:bold">ID</th>
                                        <th rowspan="2" style="vertical-align: middle;font-weight:bold">NIK</th>
                                        <th rowspan="2" style="vertical-align: middle;font-weight:bold">Employee Name</th>
                                        <th rowspan="2" style="vertical-align: middle;font-weight:bold">Department</th>
                                        <th rowspan="2" style="vertical-align: middle;font-weight:bold">Bagian</th>
                                        <th colspan="2" style="vertical-align: middle;font-weight:bold">Kontrak</th>
                                        <th rowspan="2" style="vertical-align: middle;font-weight:bold;border:1px solid rgb(195, 195, 195)"><span class="fa fa-cog"></span></th>
                                    </tr>
                                    <tr style='text-align:center; vertical-align:middle'>
                                        <th style="font-weight:bold">Awal</th>
                                        <th style="font-weight:bold">Akhir</th>
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
<div class="modal fade" id="import_kontrak" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 60%;" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary p-2">
                <label class="form-label">IMPORT KONTRAK KERJA</label>
                <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                    <i class="fa fa-remove"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <input class="form-control" ref="excel_file_kontrak" name="excel_file_kontrak" id="excel_file_kontrak" type="file" accept=".xlsx, .xls, .csv" required>
                    </div>
                </div>
                <div class="row pt-2 justify-content-center">
                    <div class="col-12">
                        <table class="table table-bordered" style="overflow:auto;height:300px;">
                            <thead id="head_kontrak_kerja">
                                <tr>
                                    <td width="100px">NIK</td>
                                    <td width="200px">Nama Karyawan</td>
                                    <td width="200px">Department</td>
                                    <td width="100px">Kontrak</td>
                                    <td width="150px">Awal</td>
                                    <td width="150px">Akhir</td>
                                </tr>
                            </thead>
                            <tbody id="tabel_kontrak_kerja">
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12 text-center">
                        <div id="loading_kontrak_kerja">
                        </div>
                    </div>
                </div>
                <div class="row pt-0 pb-3 pr-3">
                    <div class="col-2"></div>
                    <div class="col-8 text-center pt-2">
                        <button type="button" id="contractImportButton" class="btn btn-success py-1" style="visibility: hidden"><i class="fa fa-upload" aria-hidden="true"></i> IMPORT</button>
                    </div>
                    <div class="col-2 pl-8 pt-1" id="keterangan" style="visibility: hidden">
                        <label class="mb-0" style="font-size:10pt">L : Waktu Lembur</label><br>
                        <label style="font-size:10pt">I &nbsp;: Waktu Istirahat</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="extendContractModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 54%;" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary p-2">
                <label class="form-label">WORKING CONTRACT</label>
                <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                    <i class="fa fa-remove"></i>
                </button>
            </div>
            <div class="modal-body px-3">
                <div class='row px-3'>
                    <div class='col-2 pt-2 pb-1' style='font-weight:bold'></div>
                    <div class='col-3 pt-2 pb-1 text-center border border-body' style='font-weight:bold'>Kontrak Awal</div>
                    <div class='col-3 pt-2 pb-1 text-center border border-body' style='font-weight:bold'>Akhir</div>
                    <div class='col-4 pt-2 pb-1 text-center border border-body' style="font-size:9pt;font-family:Arial;font-weight:bold"><span class="fa fa-cog"></span> Option</div>
                </div>
                <div id="working_contract_extend">
                </div>
                <div id="working_contract_active">
                </div>
            </div>
            <div class="modal-footer bg-primary">
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
<style>
    #head_kontrak_kerja, #tabel_kontrak_kerja { display: block; }

    #tabel_kontrak_kerja {
        height: 1px;
        overflow-y: auto;    /* Trigger vertical scroll    */
        overflow-x: hidden;
        font-size: 9pt; /* Hide the horizontal scroll */
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        let datatableFilter = document.getElementById("datatable_filter");
        datatableFilter.innerHTML = `<span> Search : </span><input type="text" class="form-control form-control-sm" id="search_variable" onkeyup="dataTableReload()">`;
    });
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
    $('#excel_file_kontrak').change(function() {
        fill_the_table();
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    function fill_the_table(){
        $('#loading_kontrak_kerja').addClass("spinner-border");
        $('#tabel_kontrak_kerja').empty();
        var formData = new FormData();
        var excelFile=document.getElementById("excel_file_kontrak");
        var myFile=excelFile.files[0];
        formData.append("excel_file",myFile);
        if(typeof myFile=='undefined'){
            notif({
                msg: "<b>Error:</b> Pilih File terlebih dahulu!",
                type: "error"
            });
            document.getElementById('tabel_kontrak_kerja').style.height='1px';
            document.getElementById('contractImportButton').style.visibility='hidden';
            document.getElementById('keterangan').style.visibility='hidden';
            $('#loading_kontrak_kerja').removeClass("spinner-border");
        }else{
            $.ajax({
                type: 'POST',
                url: '{{route('hris.hrd.import_kontrak_kerja')}}',
                contentType: false,
                processData: false,
                data: formData,
                success:function(data){
                    $('#loading_kontrak_kerja').removeClass("spinner-border");
                    no=2;
                    jQuery.each(data, function(key,value){
                        contract = new Date(value.contract).toLocaleDateString('id-ID', { weekday: 'long', year:"numeric", month:"long", day:"numeric"});
                        contract_end = new Date(value.contract_end).toLocaleDateString('id-ID', { weekday: 'long', year:"numeric", month:"long", day:"numeric"});
                        if(key!=0){
                            if(value.nik==data[key-1].nik){
                                $('#tabel_kontrak_kerja').append("<tr>\
                                    <td width='100px'></td>\
                                    <td width='200px'></td>\
                                    <td width='200px'></td>\
                                    <td width='100px'>Kontrak ke "+(no++)+"</td>\
                                    <td width='150px'>"+contract+"</td>\
                                    <td width='150px'>"+contract_end+"</td>\
                                </tr>");
                            }else{
                                no=2;
                                $('#tabel_kontrak_kerja').append("<tr>\
                                    <td width='100px'>"+value.nik+"</td>\
                                    <td width='200px'>"+value.employee_name+"</td>\
                                    <td width='200px'>"+value.department+"</td>\
                                    <td width='100px'>Kontrak ke 1</td>\
                                    <td width='150px'>"+contract+"</td>\
                                    <td width='150px'>"+contract_end+"</td>\
                                </tr>");
                            }
                        }else{
                            $('#tabel_kontrak_kerja').append("<tr>\
                                <td width='100px'>"+value.nik+"</td>\
                                <td width='200px'>"+value.employee_name+"</td>\
                                <td width='200px'>"+value.department+"</td>\
                                <td width='100px'>Kontrak ke 1</td>\
                                <td width='150px'>"+contract+"</td>\
                                <td width='150px'>"+contract_end+"</td>\
                            </tr>");
                        }
                    });
                    document.getElementById('tabel_kontrak_kerja').style.height='300px';
                    document.getElementById('contractImportButton').style.visibility='visible';
                },
                error: function(res){
                    swal("", "IMPORT KONTRAK KERJA GAGAL!", "error")
                    document.getElementById('tabel_kontrak_kerja').style.height='1px';
                    document.getElementById('contractImportButton').style.visibility='hidden';
                    document.getElementById('keterangan').style.visibility='hidden';
                    $('#loading_kontrak_kerja').removeClass("spinner-border");
                }
            });
        }
    }
    $('#contractImportButton').on('click',function(){
        $("#contractImportButton").addClass("btn-loading");
        $("#contractImportButton").html('Loading...');
        $("#contractImportButton").attr("disabled", true);
        var formData = new FormData();
        var excelFile=document.getElementById("excel_file_kontrak");
        var myFile=excelFile.files[0];
        formData.append("excel_file",myFile);
        $.ajax({
            type: 'POST',
            url: '{{route('hris.hrd.import_kontrak_kerja_to_database')}}',
            contentType: false,
            processData: false,
            data: formData,
            success:function(data){
                $('#tabel_kontrak_kerja').empty();
                document.getElementById('contractImportButton').style.visibility='hidden';
                document.getElementById('tabel_kontrak_kerja').style.height='1px';
                $("#contractImportButton").removeClass("btn-loading");
                $("#contractImportButton").html('<i class="fa fa-upload"></i> IMPORT');
                $("#contractImportButton").attr("disabled", false);
                $('#excel_file_kontrak').val('');
                $("#import_kontrak").modal('hide');
                swal({
                    title: "Kontrak Kerja",
                    text: "Kontrak kerja berhasil di import",
                    icon: "success",
                    button : false,
                });
                datatable.ajax.reload();
            },
            error: function(res){
                swal("", "IMPORT KONTRAK KERJA GAGAL!", "error")
                $("#contractImportButton").removeClass("btn-loading");
                $("#contractImportButton").html('<i class="fa fa-upload"></i> IMPORT');
                $("#contractImportButton").attr("disabled", false);
            }
        });
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
    let datatable = $("#datatable").DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        paging: true,
        searching: true,
        destroy: true,
        scrollX: true,
        ajax: {
            url: '{{ route('hris.hrd.get_employee_contract') }}',
            data: function(d) {
                d.enroll_id = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
                d.ibu_kandung = $('#searchIbuKandung').val();
                d.no_ktp = $('#searchNoKTP').val();
                d.status_kontrak = $('#status_kontrak').val();
                d.status_aktif = $('#status_aktif').val();
                d.search_variable = $('#search_variable').val();
            },
        },
        columns: [
            {
                data: 'enroll_id'
            }, {
                data: 'nik'
            },
            {
                data: 'employee_name'
            },
            {
                data: 'department_name'
            },
            {
                data: 'sub_dept_name'
            },
            {
                data: 'enroll_id'
            },
            {
                data: 'enroll_id'
            },
            {
                data: 'enroll_id'
            },
        ],
        columnDefs: [
            {
                targets: [5],
                render: (data, type, row, meta) => {
                    var options = { weekday: 'long',  year: 'numeric', month: 'long', day: 'numeric' };
                    var tes=new Date(row.contract);
                    if(row.contract==null){
                        return '';
                    }else{
                        return tes.toLocaleDateString("id-ID", options) 
                    }
                }
            },
            {
                targets: [6],
                render: (data, type, row, meta) => {
                    var options = { weekday: 'long',  year: 'numeric', month: 'long', day: 'numeric' };
                    var tes=new Date(row.contract_end);
                    if(row.contract_end==null){
                        return '';
                    }else{
                        return tes.toLocaleDateString("id-ID", options) 
                    }
                }
            },
            {
                targets: [7],
                render: (data, type, row, meta) => {
                    return `
                        <div class='d-flex gap-1'>
                            <a data-toggle="modal" data-target="#extendContractModal" onclick="getDetail('` + row.enroll_id + `');">
                                <i class='fa fa-pencil-square-o' style='color:black;background-color:orange;font-size:14pt;border:1px solid #838584;padding:2pt;cursor:pointer'></i>
                            </a>
                        </div>
                    `
                }
            }
        ]
    });
    $('#selectEmployeeID').on('change',function(){
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
    function getDetail(enroll_id){
        $('#working_contract_active').empty();
        $('#working_contract_extend').empty();
        $.ajax({
            type: "post",
            url: '{{ route('hris.hrd.get_employee_contract2') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: enroll_id,
            },
            success: function(res) {
                var this_day=new Date();
                this_day.setHours(0, 0, 0, 0);
                for(var i=res.length-1;i>=0;i--){
                    var options = { weekday: 'long',  year: 'numeric', month: 'long', day: 'numeric' };
                    var start=new Date(res[i]['contract']);
                    var end=new Date(res[i]['contract_end']);
                    end.setHours(0, 0, 0, 0);
                    
                    if(i==res.length-1){
                        if(res.length===1){
                            if(this_day.getTime()>end.getTime()){
                                $('#working_contract_active').append("<div class='row px-3'>\
                                    <div class='col-2 pt-2 pb-1 border border-body border-left-0 bg-danger text-dark' style='font-weight:bold'>Nonactive Contract</div>\
                                    <div class='col-3 pt-2 pb-1 border border-body text-dark'>"+start.toLocaleDateString("id-ID", options)+"<input type='hidden' class='form-control form-control-sm' id='start_contract' value="+res[i]['contract']+"></div>\
                                    <div class='col-3 py-1 border border-body text-dark' style='font-weight:bold'><input type='hidden' class='form-control form-control-sm' id='last_contract_end' value="+res[i]['contract_end']+" style='display:block'><h6 id='warning_fill' style='display:none;margin-bottom:0;color:red'>please fill this</h6><h6 id='warning_date' style='display:none;margin-bottom:0;color:red'>too small</h6><input type='hidden' value="+res[i]['id']+" id='last_id'><label class='py-1 mb-0' id='last_label_end' style='display:none'>"+end.toLocaleDateString("id-ID", options)+"</label></div>\
                                    <div class='col-4 py-1 border border-body text-dark'><a href='#' class='btn btn-sm btn-primary py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='extendContract("+res[i]['enroll_id']+");' id='extendButton' style='visibility:visible'><span class='fa fa-arrow-up' style='font-size:9pt'></span> Extend</a>  <a href='#' class='btn btn-sm btn-danger py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial;display:none' onClick='deleteContract("+res[i]['id']+","+res[i]['enroll_id']+");' id='deleteButton' style='visibility:visible'><span class='fa fa-trash' style='font-size:9pt'></span> Delete</a></div>\
                                </div>");
                            }else{
                                $('#working_contract_active').append("<div class='row px-3'>\
                                    <div class='col-2 pt-2 pb-1 border border-body border-left-0 text-dark'>Active Contract</div>\
                                    <div class='col-3 pt-2 pb-1 border border-body text-dark'>"+start.toLocaleDateString("id-ID", options)+"<input type='hidden' class='form-control form-control-sm' id='start_contract' value="+res[i]['contract']+"></div>\
                                    <div class='col-3 py-1 border border-body text-dark'>"+end.toLocaleDateString("id-ID", options)+"<input type='hidden' class='form-control form-control-sm' id='last_contract_end' value="+res[i]['contract_end']+" style='display:block'><h6 id='warning_fill' style='display:none;margin-bottom:0;color:red'>please fill this</h6><h6 id='warning_date' style='display:none;margin-bottom:0;color:red'>too small</h6><input type='hidden' value="+res[i]['id']+" id='last_id'><label class='py-1 mb-0' id='last_label_end' style='display:none'>"+end.toLocaleDateString("id-ID", options)+"</label></div>\
                                    <div class='col-4 py-1 border border-body text-dark'><a href='#' class='btn btn-sm btn-primary py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='extendContract("+res[i]['enroll_id']+");' id='extendButton' style='visibility:visible'><span class='fa fa-arrow-up' style='font-size:9pt'></span> Extend</a> <a href='#' class='btn btn-sm btn-danger py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial;display:none' onClick='deleteContract("+res[i]['id']+","+res[i]['enroll_id']+");' id='deleteButton' style='visibility:visible'><span class='fa fa-trash' style='font-size:9pt'></span> Delete</a></div>\
                                </div>");
                            }
                        }else{
                            if(this_day.getTime()>end.getTime()){
                                $('#working_contract_active').append("<div class='row px-3'>\
                                    <div class='col-2 pt-2 pb-1 border border-body border-left-0 bg-danger text-dark' style='font-weight:bold'>Nonactive Contract</div>\
                                    <div class='col-3 pt-2 pb-1 border border-body text-dark'>"+start.toLocaleDateString("id-ID", options)+"<input type='hidden' class='form-control form-control-sm' id='start_contract' value="+res[i]['contract']+"></div>\
                                    <div class='col-3 py-1 border border-body text-dark' style='font-weight:bold'>"+start.toLocaleDateString("id-ID", options)+"<input type='hidden' class='form-control form-control-sm' id='last_contract_end' value="+res[i]['contract_end']+" style='display:block'><h6 id='warning_fill' style='display:none;margin-bottom:0;color:red'>please fill this</h6><h6 id='warning_date' style='display:none;margin-bottom:0;color:red'>too small</h6><input type='hidden' value="+res[i]['id']+" id='last_id'><label class='py-1 mb-0' id='last_label_end' style='display:none'>"+end.toLocaleDateString("id-ID", options)+"</label></div>\
                                    <div class='col-4 py-1 border border-body text-dark'><a href='#' class='btn btn-sm btn-primary py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='extendContract("+res[i]['enroll_id']+");' id='extendButton' style='visibility:visible'><span class='fa fa-arrow-up' style='font-size:9pt'></span> Extend</a>  <a href='#' class='btn btn-sm btn-danger py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='deleteContract("+res[i]['id']+","+res[i]['enroll_id']+");' id='deleteButton' style='visibility:visible'><span class='fa fa-trash' style='font-size:9pt'></span> Delete</a></div>\
                                </div>");
                            }else{
                                $('#working_contract_active').append("<div class='row px-3'>\
                                    <div class='col-2 pt-2 pb-1 border border-body border-left-0 text-dark'>Active Contract</div>\
                                    <div class='col-3 pt-2 pb-1 border border-body text-dark'>"+start.toLocaleDateString("id-ID", options)+"<input type='hidden' class='form-control form-control-sm' id='start_contract' value="+res[i]['contract']+"></div>\
                                    <div class='col-3 py-1 border border-body text-dark'>"+end.toLocaleDateString("id-ID", options)+"<input type='hidden' class='form-control form-control-sm' id='last_contract_end' value="+res[i]['contract_end']+" style='display:block'><h6 id='warning_fill' style='display:none;margin-bottom:0;color:red'>please fill this</h6><h6 id='warning_date' style='display:none;margin-bottom:0;color:red'>too small</h6><input type='hidden' value="+res[i]['id']+" id='last_id'><label class='py-1 mb-0' id='last_label_end' style='display:none'>"+end.toLocaleDateString("id-ID", options)+"</label></div>\
                                    <div class='col-4 py-1 border border-body text-dark'><a href='#' class='btn btn-sm btn-primary py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='extendContract("+res[i]['enroll_id']+");' id='extendButton' style='visibility:visible'><span class='fa fa-arrow-up' style='font-size:9pt'></span> Extend</a>  <a href='#' class='btn btn-sm btn-danger py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='deleteContract("+res[i]['id']+","+res[i]['enroll_id']+");' id='deleteButton' style='visibility:visible'><span class='fa fa-trash' style='font-size:9pt'></span> Delete</a></div>\
                                </div>");
                            }
                        }
                    }else{
                        $('#working_contract_active').append("<div class='row px-3'>\
                            <div class='col-2 py-1 border border-left-0 border-body'>Kontrak ke -"+(i+1)+"</div>\
                            <div class='col-3 py-1 border border-body'>"+start.toLocaleDateString("id-ID", options)+"</div>\
                            <div class='col-3 py-1 border border-body'>"+end.toLocaleDateString("id-ID", options)+"</div>\
                            <div class='col-4 py-1 border border-body'></div>\
                        </div>");
                    }
                }
            }
        });
    }
    function extendContract(enroll_id){
        document.getElementById("extendButton").style.visibility="hidden";
        
        document.getElementById("deleteButton").style.visibility="hidden";
        document.getElementById("last_contract_end").style.display="none";
        document.getElementById("last_label_end").style.display="block";
        $.ajax({
            type: "post",
            url: '{{ route('hris.hrd.get_employee_contract2') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: enroll_id,
            },
            success: function(res) {
                var last_date=new Date(res[res.length-1]['contract_end']);
                last_date.setDate(last_date.getDate()+1);
                var dd = String(last_date.getDate()).padStart(2, '0');
                var mm = String(last_date.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = last_date.getFullYear();

                today = yyyy + '-' + mm + '-' + dd;
                
                var start=new Date(today);
                var options = {  year: 'numeric', month: 'long', day: 'numeric' };
                $('#working_contract_extend').append("<div class='row px-3'>\
                    <div class='col-2 pt-2 pb-1 border border-body bg-warning text-dark' style='font-weight:bold'>New Contract</div>\
                    <div class='col-3 pt-2 pb-1 border border-body text-dark'><input type='hidden' class='form-control form-control-sm' id='new_start_contract' value="+today+">"+start.toLocaleDateString("id-ID", options)+"</div>\
                    <div class='col-3 py-1 border border-body text-dark'><input type='date' class='form-control form-control-sm' id='new_end_contract'><h6 id='warning_fill2' style='display:none;margin-bottom:0;color:red'>please fill this</h6><h6 id='warning_date2' style='display:none;margin-bottom:0;color:red'>too small</h6></div>\
                    <div class='col-4 py-1 border border-body text-dark'><a href='#' class='btn btn-sm btn-warning text-dark py-0 px-2 border border-body' style='font-weight:bold;font-size:9pt;font-family:Arial' id='cancelExtendButton' style='visibility:visible' onclick='cancelExtend()'><i class='fa fa-times-circle' style='font-size:9pt'></i> Cancel</a> <a href='#' class='btn btn-sm btn-warning text-dark border border-body py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' id='saveExtendButton' style='visibility:visible' onclick='newExtend("+res[0]['enroll_id']+")'><i class='fa fa-arrow-circle-down' style='font-size:9pt'></i> Save</a></div>\
                </div>");
            }
        });
    }
    function cancelExtend(){
        document.getElementById('cancelExtendButton').style.visibility='hidden';
        document.getElementById('extendButton').style.visibility='visible';
        document.getElementById("deleteButton").style.visibility="visible";
        
        document.getElementById("last_contract_end").style.display="block";
        document.getElementById("last_label_end").style.display="none";
        $('#working_contract_extend').empty();
    }
    function updateContract(enroll_id){
        var last_contract=($('#last_contract_end').val());
        if(last_contract==''){
            document.getElementById('last_contract_end').style.border='1px solid red';
            document.getElementById('last_contract_end').style.textDecorationColor='red';
            document.getElementById('warning_fill').style.display='block';
        }else{
            document.getElementById('last_contract_end').style.border='';
            document.getElementById('last_contract_end').style.textDecorationColor='';
            document.getElementById('warning_fill').style.display='none';
            var start_contract=new Date($('#start_contract').val());
            var end_contract=new Date($('#last_contract_end').val());
            if(start_contract>end_contract){
                document.getElementById('last_contract_end').style.border='1px solid red';
                document.getElementById('last_contract_end').style.textDecorationColor='red';
                document.getElementById('warning_date').style.display='block';
            }else{
                $.ajax({
                    type: "post",
                    url: '{{ route('hris.hrd.update_employee_contract') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: enroll_id,
                        last_date:$('#last_contract_end').val(),
                        last_id:$('#last_id').val(),
                    },
                    success: function(res) {
                        iziToast.success({
                            message: 'update kontrak berhasil',
                            position: 'center',
                            timeout:1300,
                        });
                        getDetail(res);
                        datatable.ajax.reload();
                    }
                });
            }
        }
    }
    function newExtend(enroll_id){
        var last_contract=($('#new_end_contract').val());
        if(last_contract==''){
            document.getElementById('new_end_contract').style.border='1px solid red';
            document.getElementById('new_end_contract').style.textDecorationColor='red';
            document.getElementById('warning_fill2').style.display='block';
        }else{
            document.getElementById('new_end_contract').style.border='';
            document.getElementById('new_end_contract').style.textDecorationColor='';
            document.getElementById('warning_fill2').style.display='none';
            var start_contract=new Date($('#new_start_contract').val());
            var end_contract=new Date($('#new_end_contract').val());
            if(start_contract>end_contract){
                document.getElementById('new_end_contract').style.border='1px solid red';
                document.getElementById('new_end_contract').style.textDecorationColor='red';
                document.getElementById('warning_date2').style.display='block';
            }else{
                $.ajax({
                    type: "post",
                    url: '{{ route('hris.hrd.new_employee_contract') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: enroll_id,
                        contract:$('#new_start_contract').val(),
                        end_contract:$('#new_end_contract').val(),
                    },
                    success: function(res) {
                        iziToast.success({
                            message: 'tambah kontrak berhasil',
                            position: 'center',
                            timeout:1300,
                        });
                        getDetail(res);
                    }
                });
            }
        }
    }
    function deleteContract(id,enroll_id){
        $.ajax({
            type: "post",
            url: '{{ route('hris.hrd.delete_employee_contract') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id,
                enroll_id:enroll_id
            },
            success: function(res) {
                iziToast.success({
                    message: 'delete kontrak berhasil',
                    position: 'center',
                    timeout:1300,
                });
                getDetail(res);
            }
        });
    }
</script>
@endsection