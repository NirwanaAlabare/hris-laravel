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
                                <div class="col-3 pr-0">
                                    <select id="selectEmployeeID" name="selectEmployeeID[]" multiple data-placeholder="Pilih karyawan" class="form-control select2 EmployeeID">
                                        @foreach ($selectEmployee as $r_empl)
                                            <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-6 pt-2 pb-5">
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-sm w-100 table-hover text-nowrap">
                                <thead class="table-info">
                                    <tr style='text-align:center;'>
                                        <th rowspan="2" width="3%" style="vertical-align: middle;font-weight:bold">Id</th>
                                        <th rowspan="2" width="14%" style="vertical-align: middle;font-weight:bold">Employee Name</th>
                                        <th rowspan="2" width="14%" style="vertical-align: middle;font-weight:bold">Department</th>
                                        <th colspan="2" width="14%" style="vertical-align: middle;font-weight:bold">Mangkir</th>
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
    #datatable {
    table-layout: fixed;
    }

</style>
<script type="text/javascript">
       $(document).ready(function() {
        let datatableFilter = document.getElementById("datatable_filter");
        datatableFilter.innerHTML = `<span> Search : </span><input type="text" class="form-control form-control-sm" id="search_variable" onkeyup="dataTableReload()">`;
    });
    function export_excel_kontrak(){
        $("#btn_export_excel_kontrak").addClass("btn-loading");
        $("#btn_export_excel_kontrak").html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Loading...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
        $("#btn_export_excel_kontrak").attr("disabled", true);
        let search_variable=$('#search_variable').val();
        let no_ktp = document.getElementById("searchNoKTP").value;
        let enroll_id = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
        let ibu_kandung = document.getElementById("searchIbuKandung").value;
        let status_aktif = document.getElementById("status_aktif").value;
        let status_kontrak = document.getElementById("status_kontrak").value;
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
            url: '{{ route('hris.hrd.export_excel_kontrak') }}',
            data: {
                search_variable: search_variable,
                no_ktp: no_ktp,
                enroll_id: enroll_id,
                ibu_kandung: ibu_kandung,
                status_aktif: status_aktif,
                status_kontrak: status_kontrak
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response) {
                {
                    $('#btn_export_excel_kontrak').removeClass("btn-loading");
                    $("#btn_export_excel_kontrak").html('<i class="fa fa-file-excel-o" style="font-size:11pt"></i> Export Kontrak Kerja');
                    $("#btn_export_excel_kontrak").attr("disabled", false);
                    var blob = new Blob([response]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "Kontrak kerja "+today_date+" "+Math.ceil(Math.random()*1000000)+".xlsx";
                    link.click();
                }
            },
            error: function(res){
                swal("", "Export kontrak kerja gagal", "error");
                $('#btn_export_excel_kontrak').removeClass("btn-loading");
                $("#btn_export_excel_kontrak").attr("disabled", false);
                $("#btn_export_excel_kontrak").html('<i class="fa fa-file-excel-o" style="font-size:11pt"></i> Export Kontrak Kerja');
            }
        });
    }
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
                            if(value.nik==data[key-1].nik[0]){
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
            url: '{{ route('hris.hrd.sp_hadir') }}',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
                data: function(d) {
                d.enroll_id = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
                d.search_variable = $('#search_variable').val();
            },
        //     success: function(res) {
        //     console.log(res);  // Debugging response data here
        // }
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
                    return `
                     <div class="row">
                        <div class="col text-center">
                            <button class='btn btn-danger' style='padding-top:0px;padding-bottom:0px; font-family:monospace; font-size:10pt' data-target="#user-form-modal" data-toggle="modal" onclick="export_sp_kerja(` + row.enroll_id + `)">
                                            SP KERJA
                            </button>
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
        if(checkedEmployeeArr.length>0){
            document.getElementById("print_kontrak_kerja").style.visibility = "visible";
        }else{
            document.getElementById("print_kontrak_kerja").style.visibility = "hidden";
        }
    }
    $('#print_kontrak_kerja').on('click',function(){
        var enroll_id=checkedEmployeeArr;
        var today=new Date();
        var month_now=today.getMonth();
        var year_now=today.getFullYear();
        var no_form='HRD-NAG/PKWT'+'/'+integerToRoman(month_now+1)+'/'+year_now;
        var url = 'print_all_pdf_kontrak?enroll_id='+enroll_id+'&no_form='+no_form;
        window.open(url, '_blank');
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

    function export_sp_kerja(enroll_id){
        var url = 'export_sp_kehadiran_karyawan?enroll_id='+enroll_id;
        window.open(url, '_blank');
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
                            <div class='col-4 py-1 border border-body'><a href='#' class='btn btn-sm btn-danger py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='printContract("+res[i]['enroll_id']+","+"\""+res[i]['contract']+"\""+","+"\""+res[i]['contract_end']+"\""+");'>&nbsp;<span class='fa fa-file-pdf-o' style='font-size:9pt'></span> Print&nbsp;&nbsp;</a></div>\
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
    function print_pdf(enroll_id){
        var today=new Date();
        var month_now=today.getMonth();
        var year_now=today.getFullYear();
        var no_form='HRD-NAG/PKWT'+'/'+integerToRoman(month_now+1)+'/'+year_now;
        var url = 'print_pdf_kontrak?enroll_id='+enroll_id+'&no_form='+no_form;
        window.open(url, '_blank');
    }
    function printContract(enroll_id,contract,contract_end){
        var today=new Date();
        var month_now=today.getMonth();
        var year_now=today.getFullYear();
        var contract2=contract;
        var contract_end2=contract_end;
        var no_form='HRD-NAG/PKWT'+'/'+integerToRoman(month_now+1)+'/'+year_now;
        var url = 'print_pdf_kontrak_2?enroll_id='+enroll_id+'&no_form='+no_form+'&contract='+contract2+'&contract_end='+contract_end2;
        window.open(url, '_blank');
    }
    function integerToRoman(num) {
        const romanValues = {
            M: 1000,
            CM: 900,
            D: 500,
            CD: 400,
            C: 100,
            XC: 90,
            L: 50,
            XL: 40,
            X: 10,
            IX: 9,
            V: 5,
            IV: 4,
            I: 1
        };
        let roman = '';
        for (let key in romanValues) {
            while (num >= romanValues[key]) {
                roman += key;
                num -= romanValues[key];
            }
        }
        return roman;
    }

</script>
@endsection
