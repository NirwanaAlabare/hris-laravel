@extends('admin.adminlayouts.adminlayout3')

@section('head')
<link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />

@stop
@section('mainarea')

<!-- page-header -->
<div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
    <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
        <li><a href="{{route('hris.dashboard.tes')}}">DASHBOARD</a></li>
        <li class="active"><span>KEPERSONALIAAN</span></li>
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
<div class="modal fade" id="user-form-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger p-2">
                <h4 class="modal-title pl-2 font-weight-bold" >Export PDF</h4>
                <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                    <i class="fa fa-remove"></i>
                </button>
            </div>
            <div class="modal-body pt-0">
                <div class="row pt-3 pb-2 bg-light text-dark">
                    <div class="col-4 pl-4" style="font-weight: bold">
                        ID
                    </div>
                    <div class="col-5" id="enroll_id_text">
                    </div>
                    <input type="hidden" id="enroll_id_input">
                </div>
                <div class="row py-2">
                    <div class="col-4 pl-4" style="font-weight: bold">
                        Name
                    </div>
                    <div class="col-7" id="employee_name_text">
                    </div>
                </div>
                <div class="row py-2 bg-light text-dark">
                    <div class="col-4 pl-4" style="font-weight: bold">
                        Join Date
                    </div>
                    <div class="col-5" id="join_date_text">
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 pl-4" style="font-weight: bold">
                        Last Date
                    </div>
                    <div class="col-5" id="last_date_text">
                    </div>
                </div>
                <div class="row py-2 bg-light text-dark">
                    <div class="col-4 pl-4" style="font-weight: bold">
                        No. Form
                    </div>
                    <div class="col-8">
                        <div class="row">
                            <div class="col-1 pr-5 pt-1">
                                NO.
                            </div>
                            <div class="col-3 px-0">
                                <input type="number" id="no_form_input" class="form-control py-0 px-2" style="background-color:white">
                            </div>
                            <div class="col-7 px-1 pt-1" id="no_form_must_be">
                            </div>
                            <input type="hidden" id="no_form_input_next">
                        </div>
                    </div>
                </div>
                <div class="row py-2 text-dark">
                    <div class="col-4 pl-4" style="font-weight: bold" id="reason_text">
                    </div>
                    <div class="col-8">
                        <input type="text" id="reason_3_input" class="form-control py-0 px-2" style="background-color:white">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-center pt-2">
                        <button class="btn btn-danger py-0" onclick="export_sk()"><span class="fa fa-file-pdf-o" style="font-size:9pt"></span> PRINT SK KERJA</button>
                        <button class="btn btn-danger py-0" onclick="export_paklaring()"><span class="fa fa-file-pdf-o" style="font-size:9pt"></span> PRINT PAKLARING</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="user-form-modal_2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary p-2">
                <h4 class="modal-title pl-2 font-weight-bold" >Surat Keterangan BNI</h4>
                <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                    <i class="fa fa-remove"></i>
                </button>
            </div>
            <div class="modal-body pt-0">
                <div class="row pt-3 pb-2 bg-light text-dark">
                    <div class="col-4 pl-4" style="font-weight: bold">
                        ID
                    </div>
                    <div class="col-5" id="enroll_id_text_2">
                    </div>
                    <input type="hidden" id="enroll_id_input_2">
                </div>
                <div class="row py-2">
                    <div class="col-4 pl-4" style="font-weight: bold">
                        Name
                    </div>
                    <div class="col-7" id="employee_name_text_2">
                    </div>
                </div>
                <div class="row py-2 bg-light text-dark">
                    <div class="col-4 pl-4" style="font-weight: bold">
                        Join Date
                    </div>
                    <div class="col-5" id="join_date_text_2">
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 pl-4" style="font-weight: bold">
                        Last Date
                    </div>
                    <div class="col-5" id="last_date_text_2">
                    </div>
                </div>
                <div class="row py-2 bg-light text-dark">
                    <div class="col-4 pl-4" style="font-weight: bold">
                        No. Form
                    </div>
                    <div class="col-8">
                        <div class="row">
                            <div class="col-1 pr-5 pt-1">
                                NO.
                            </div>
                            <div class="col-3 px-0">
                                <input type="number" id="no_form_input_2" class="form-control py-0 px-2" style="background-color:white">
                            </div>
                            <div class="col-7 px-1 pt-1" id="no_form_must_be_2">
                            </div>
                            <input type="hidden" id="no_form_input_next_2">
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 pl-4" style="font-weight: bold">
                        Barang
                    </div>
                    <div class="col-7">
                        <input type="text" class="form-control py-0 px-2" id="reason_input" style="background-color:white">
                    </div>
                </div>
                <div class="row py-2 bg-light text-dark">
                    <div class="col-4 pl-4" id="reason_text_2" style="font-weight: bold">
                    </div>
                    <div class="col-7">
                        <input type="text" class="form-control py-0 px-2" id="reason_input_2" style="background-color:white">
                    </div>
                </div>
                <div class="row py-2 text-dark">
                    <div class="col-4 pl-4" style="font-weight: bold">
                        Tanggal Pembuatan
                    </div>
                    <div class="col-7">
                        <input type="date" class="form-control py-0 px-2" id="date_input" style="background-color:white">
                    </div>
                </div>
                <div class="row bg-light">
                    <div class="col-4">
                    </div>
                    <div class="col-7">
                        <button class="btn btn-danger py-0" onclick="export_sk_bni()"><span class="fa fa-file-pdf-o" style="font-size:9pt"></span> PRINT SK BNI</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="print_sk_checked" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 70%;" role="document">
        <div class="modal-content">
            <form target="_blank" action="{{route('hris.hrd.export_pdf_print_sk')}}" method="post">
                {{csrf_field()}}
                <input type="hidden" id="bulan_nomor_form" name="no_form">
                <div class="modal-header bg-primary p-2">
                    <label class="form-label">CHECKED ID</label>
                    <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                        <i class="fa fa-remove"></i>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-danger pb-0 py-1"><span class="fa fa-file-pdf-o"></span> Print SK</button>
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-12 text-left">
                            <table id="datatable-ajax-crud-modal" class="table table-bordered table-sm w-100 table-hover text-nowrap">
                                <thead class="table-primary">
                                    <tr style='text-align:center; vertical-align:middle'>
                                        <th>ID</th>
                                        <th>NIK</th>
                                        <th>Nama Karyawan</th>
                                        <th>Tanggal Resign</th>
                                        <th>Masa kerja</th>
                                        <th>Sebab Resign</th>
                                        <th>Tipe Surat</th>
                                        <th scope="col" class="text-center">
                                            <span class="fa fa-print"></span><span class="fa fa-check text-success"></span> <input type="checkbox" style='width: 15px; height: 15px;' id="checkAllEmployeeModal" onchange="actionCheckAllEmployeeModal(this)">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </form>
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
                                <div class="col-4">
                                    <input type="number" id="searchNoKTP" name="searchNoKTP" class="form-control" style="background-color:white" placeholder="Masukkan No. KTP">
                                </div>
                            </div>
                            <div class="row pb-2">
                                <div class="col-2 pt-1">
                                    <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Nama Karyawan</label>
                                </div>
                                <div class="col-4">
                                    <select id="selectEmployeeID" name="selectEmployeeID[]" multiple data-placeholder="Pilih karyawan" class="form-control select2 EmployeeID">
                                        @foreach ($selectEmployee as $r_empl)
                                            <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2 pt-1">
                                    <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Nama Ibu Kandung</label>
                                </div>
                                <div class="col-4">
                                    <input type="text" id="searchIbuKandung" name="searchIbuKandung" class="form-control" style="background-color:white" placeholder="Masukkan Nama Ibu Kandung">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-6 pt-2 pb-5">
                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-primary pb-0 py-1" data-target="#print_sk_checked" data-toggle="modal" id="print_sk_button" style="visibility: hidden"><span class="fa fa-check"></span> Show Checked Employee</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <table id="datatable-ajax-crud" class="table table-sm table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" width="3%">
                                        <input type="checkbox" id="checkAllEmployee" onchange="actionCheckAllEmployee(this)">
                                    </th>
                                    <th scope="col" width="3%"></th>
                                    <th scope="col" width="5%"></th>
                                    <th scope="col" width="14%"></th>
                                    <th scope="col" width="14%"></th>
                                    <th scope="col" width="14%"></th>
                                    <th scope="col" width="8%"></th>
                                    <th scope="col" width="8%"></th>
                                    <th scope="col" width="18%"></th>
                                    <th scope="col" width="9%" class="text-center"></th>
                                    <th scope="col" width="5%" class="text-right"></th>
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
</div>
@endsection
@section('footerjs')
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
<script type="text/javascript">
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
    $(document).ready(function() {
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
                "url": "{{ route('hris.employeeatr.ajax_getemployeeatr2') }}",
                "dataType": "json",
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "dataSrc": "data",
                "data": function (d) {
                    d.searchData = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
                    d.selectNoKTP = $("#searchNoKTP").val();
                    d.searchIbuKandung = $("#searchIbuKandung").val();
                }
            },
            columns: [
                {
                    data: 'enroll_id',
                    orderable: false
                },
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
                    name: 'employee_name'
                },
                {
                    title: 'Department',
                    data: 'department_name',
                    name: 'department_name'
                },
                {
                    title: 'Bagian',
                    data: 'sub_dept_name',
                    name: 'sub_dept_name'
                },
                {
                    title: 'Aktif',
                    data: 'status_aktif',
                },
                {
                    title: 'Resign',
                    data: 'tanggal_resign',
                },
                {
                    title : 'Notes',
                    data: 'catatan',
                },
                {
                    title: '<span class="fa fa-file-pdf-o"></span>',
                    orderable: false
                },
                {
                    title: '<span class="fa fa-print"></span><span class="fa fa-check text-success"></span>',
                    orderable: false
                }
            ],
            order: [
                [1, 'asc']
            ],
            columnDefs: [
                {
                    'targets': [0],
                    'render' : function (data,type, row) {
                        return `
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" style='width: 20px; height: 20px;' value="`+data+`" style='width: 20px; height: 20px;' id="checked_enroll_id_` + row.enroll_id + `" onchange="actionThisEmployeeCheck(this)" >
                            </div>
                        `
                    }
                },
                {
                    'targets': [9],
                    'render' : function (data, type, row) {
                        if(row.sudah_diprint!=null){
                            return `
                                <div class="row"><div class="col text-center"><button class='btn btn-danger' style='padding-top:0px;padding-bottom:0px; font-family:monospace; font-size:10pt' data-target="#user-form-modal" data-toggle="modal" onclick="send_to_modal(` + row.enroll_id + `)">
                                    SK KERJA
                                </button>
                                <button class='btn btn-primary mt-1' style='padding-top:0px;padding-bottom:0px; font-family:monospace; font-size:10pt' data-target="#user-form-modal_2" data-toggle="modal" onclick="send_to_modal_2(` + row.enroll_id + `)">
                                    SK BNI &nbsp;
                                </button></div></div>
                            `
                        }else{
                            return `
                                <div class="row"><div class="col text-center"><button class='btn btn-danger' style='padding-top:0px;padding-bottom:0px; font-family:monospace; font-size:10pt' data-target="#user-form-modal" data-toggle="modal" onclick="send_to_modal(` + row.enroll_id + `)">
                                     SK KERJA
                                </button>
                                <button class='btn btn-primary mt-1' style='padding-top:0px;padding-bottom:0px; font-family:monospace; font-size:10pt' data-target="#user-form-modal_2" data-toggle="modal" onclick="send_to_modal_2(` + row.enroll_id + `)">
                                    SK BNI &nbsp;
                                </button></div></div>
                            `
                        }
                    }
                },
                {
                    'targets': [10],
                    'render' : function (data, type, row) {
                        if(row.sudah_diprint!=null){
                            return `
                                <div class="row"><div class="col text-center"><div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="`+row.enroll_id+`" style='width: 20px; height: 20px;' id="checked_enroll_id_print_` + row.enroll_id + `" onchange="alreadyPrintEmployeeCheck(this)" checked >
                                </div></div></div>
                            `
                        }else{
                            return `
                                <div class="row"><div class="col text-center"><div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="`+row.enroll_id+`" style='width: 20px; height: 20px;' id="checked_enroll_id_print_` + row.enroll_id + `" onchange="alreadyPrintEmployeeCheck(this)">
                                </div></div></div>
                            `
                        }
                    }
                }
            ],
            rowCallback: function(row, data, dataIndex){
                let currentEnrollId = data['enroll_id'];

                checkedEmployeeArr.forEach((item, index, array) => {
                    if(item==currentEnrollId){
                        currentPageCheck++;
                        $(row).find('input[id="checked_enroll_id_'+item+'"]').prop('checked', true);
                    }
                });
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
    });
    $('#print_sk_button').on('click',function(){
        var today=new Date();
        var month_now=today.getMonth();
        var year_now=today.getFullYear();
        var no_form=integerToRoman(month_now+1)+'/'+year_now;
        $('#bulan_nomor_form').val(no_form);
        $('#datatable-ajax-crud-modal thead tr').clone(true).appendTo('#datatable-ajax-crud-modal thead');
        $('#datatable-ajax-crud-modal thead tr:eq(1) th').each(function(i) {
            if (i != 7) {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control form-control-sm" />');

                $('input', this).on('keyup change', function() {
                    if (table2.column(i).search() !== this.value) {
                        table2
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            } else {
                $(this).empty();
            }
        });
        let table2 = $('#datatable-ajax-crud-modal').DataTable({
            ordering: false,
            processing: true,
            destroy: true,
            scrollX: true,
            "ajax": {
                "url": "{{ route('hris.employeeatr.ajax_getemployeeatr3') }}",
                "dataType": "json",
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "dataSrc": "data",
                "data": function (d) {
                    d.checked_employees = checkedEmployeeArr;
                }
            },
            columns: [
                {
                    data: 'enroll_id',
                },
                {
                    data: 'nik',
                },
                {
                    data: 'employee_name',
                },
                {
                    data: 'tanggal_resign',
                },
                {
                    data: 'masa_kerja',
                },
                {
                    data: 'sebab_resign',
                },
                {
                    data: 'tipe_surat',
                },
                {
                    data: 'enroll_id',
                    orderable: false
                },
            ],
            columnDefs: [
                {
                    "className": "text-center",
                    "targets": [4]
                },
                {
                    'targets': [0],
                    width : 10,
                },
                {
                    'targets': [6],
                    'render' : function (data, type, row) {
                        return `<select class="form-control" name="tipe_surat[` + row.enroll_id + `]">
                                    <option value='Paklaring' `+(data == 'Paklaring' ? 'selected' : '')+`>Paklaring</option>
                                    <option value='SK Kerja' `+(data == 'SK Kerja' ? 'selected' : '')+`>SK Kerja</option>
                                </select>`
                    }
                },
                {
                    'targets': [7],
                    'render' : function (data, type, row) {
                        if(row.sudah_diprint!=null){
                            return `
                                <div class="row"><div class="col text-center"><div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="`+row.enroll_id+`" style='width: 20px; height: 20px;' id="checked_enroll_id_print_modal_` + row.enroll_id + `" onchange="alreadyPrintEmployeeCheck(this)" checked  disabled>
                                </div></div></div>
                            `
                        }else{
                            return `
                                <div class="row"><div class="col text-center"><div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="`+row.enroll_id+`" style='width: 20px; height: 20px;' id="checked_enroll_id_print_modal_` + row.enroll_id + `" onchange="alreadyPrintEmployeeCheck(this)">
                                </div></div></div>
                            `
                        }
                    }
                }
            ],
                "createdRow": function (row, data, dataIndex) {
                    // if ((data['kode_hari'] == "5") || (data['kode_hari'] == "6") || (data['kerjalibur'] == "LIBUR")) {
                    if ((data['sudah_diprint'] == 1)) {

                        $(row).css('background', '#bfbdbd');
                    } 
                },
            order: [
                [0, 'asc']
            ],
        });
        $('#datatable-ajax-crud-modal').DataTable().ajax.reload(null, false);
    });
    $('#selectEmployeeID').on('change',function(){
        var searchData = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
        var selectNoKTP = $("#searchNoKTP").val();
        var searchIbuKandung = $("#searchIbuKandung").val();

        $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);
    });
    $('#searchNoKTP').on('keyup',function(){
        var searchData = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
        var selectNoKTP = $("#searchNoKTP").val();
        var searchIbuKandung = $("#searchIbuKandung").val();

        $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);
    });
    $('#searchIbuKandung').on('keyup',function(){
        var searchData = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
        var selectNoKTP = $("#searchNoKTP").val();
        var searchIbuKandung = $("#searchIbuKandung").val();

        $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);
    });
    function actionCheckAllEmployee(element) {
        if (element.checked) {
            $.ajax({
                type:"POST",
                url: "{{route('hris.employeeatr.ajax_getemployeeid')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: 'json',
                success: function(res){
                    if(res){
                        checkedEmployeeArr = res;

                        $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);
                        
                        document.getElementById("print_sk_button").style.visibility = "visible";
                    }
                }
            });
        } else {
            console.log("test");
            checkedEmployeeArr = [];

            document.getElementById("print_sk_button").style.visibility = "hidden";
            $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);
        }
    }
    function actionCheckAllEmployeeModal(element) {
        if (element.checked) {
            $.ajax({
                type:"POST",
                url: "{{route('hris.employeeatr.set_already_print_sk')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data : {
                    employees:checkedEmployeeArr,
                },
                success: function(res){
                    $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);
                    $('#datatable-ajax-crud-modal').DataTable().ajax.reload(null, false);
                    checkedEmployeeArr=[];
                }
            });
        }
    }
    function alreadyPrintEmployeeCheck(element){
        var enroll_id=element.value;
        if (element.checked) {
            $.ajax({
                type:"POST",
                url: "{{route('hris.employeeatr.already_print')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    id:enroll_id,
                },
                success: function(data){
                    $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);
                }
            });
        }else{
            $.ajax({
                type:"POST",
                url: "{{route('hris.employeeatr.not_yet_printed')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    id:enroll_id,
                },
                success: function(data){
                    $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);
                }
            });
        }
    }
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
            document.getElementById("print_sk_button").style.visibility = "visible";
        }else{
            document.getElementById("print_sk_button").style.visibility = "hidden";
        }
    }
    function tes(){
        var today=new Date();
        var month_now=today.getMonth();
        var year_now=today.getFullYear();
        var no_form=integerToRoman(month_now+1)+'/'+year_now;
        var tipe_surat_enroll_id = $("select[name='tipe_surat[]']").map(function(){return $(this).val();}).get();
        var heavy_fruits = [];
        myfruit = {};
        checkedEmployeeArr.forEach(function(item,index) {
            myfruit ["enroll_id"] = item;
            heavy_fruits.push(myfruit);
        });
        console.log(heavy_fruits);
    }
    function send_to_modal(enroll_id){
        var id=enroll_id;
        $.ajax({
            type:"POST",
            url: "{{route('hris.employeeatr.get_employee')}}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                id:id,
            },
            success: function(data){
                var join_date=new Date(data[0].join_date);
                var tahun = join_date.getFullYear();
                var bulan = join_date.getMonth();
                var tanggal = join_date.getDate();
                var tanggal_resign_format='SEKARANG';
                var no_form='';
                if(data[0].tanggal_resign!=null){
                    var join_date=new Date(data[0].tanggal_resign);
                    var tahun_resign = join_date.getFullYear();
                    var bulan_resign = join_date.getMonth();
                    var tanggal_resign = join_date.getDate();
                    switch(bulan_resign) {
                        case 0: bulan_resign = "Januari"; break;
                        case 1: bulan_resign = "Februari"; break;
                        case 2: bulan_resign = "Maret"; break;
                        case 3: bulan_resign = "April"; break;
                        case 4: bulan_resign = "Mei"; break;
                        case 5: bulan_resign = "Juni"; break;
                        case 6: bulan_resign = "Juli"; break;
                        case 7: bulan_resign = "Agustus"; break;
                        case 8: bulan_resign = "September"; break;
                        case 9: bulan_resign = "Oktober"; break;
                        case 10: bulan_resign = "November"; break;
                        case 11: bulan_resign = "Desember"; break;
                    }
                    tanggal_resign_format=tanggal_resign+' '+bulan_resign+' '+tahun_resign;
                }
                switch(bulan) {
                    case 0: bulan = "Januari"; break;
                    case 1: bulan = "Februari"; break;
                    case 2: bulan = "Maret"; break;
                    case 3: bulan = "April"; break;
                    case 4: bulan = "Mei"; break;
                    case 5: bulan = "Juni"; break;
                    case 6: bulan = "Juli"; break;
                    case 7: bulan = "Agustus"; break;
                    case 8: bulan = "September"; break;
                    case 9: bulan = "Oktober"; break;
                    case 10: bulan = "November"; break;
                    case 11: bulan = "Desember"; break;
                }
                var join_date_local=tanggal+' '+bulan+' '+tahun;
                $('#enroll_id_text').text(data[0].enroll_id);
                $('#enroll_id_input').val(data[0].enroll_id);
                $('#employee_name_text').text(data[0].employee_name);
                $('#join_date_text').text(join_date_local);
                $('#last_date_text').text(tanggal_resign_format);
                var today=new Date();
                var month_now=today.getMonth();
                var year_now=today.getFullYear();
                var reason='';
                if(data[0].tanggal_resign==null){
                    no_form='HRD/NAG-REF'+'/'+integerToRoman(month_now+1)+'/'+year_now;
                    reason='Alasan Pembuatan';
                }else{
                    var tanggal_masuk = new Date(data[0].join_date);
                    var tanggal_resign2 = new Date(data[0].tanggal_resign);
                    var selisih_tahun = diff_years(tanggal_resign2, tanggal_masuk);
                    if(selisih_tahun<1){
                        no_form='HRD/NAG-REF'+'/'+integerToRoman(month_now+1)+'/'+year_now;
                        reason='Alasan Pembuatan';
                    }else{
                        no_form='HRD/NAG/PK'+'/'+integerToRoman(month_now+1)+'/'+year_now;
                        reason='Sebab Resign';
                    }
                }
                $('#no_form_must_be').text(no_form);
                $('#no_form_input_next').val(no_form);
                $('#reason_text').text(reason);
            }
        });
    }
    function send_to_modal_2(enroll_id){
        var id=enroll_id;
        $.ajax({
            type:"POST",
            url: "{{route('hris.employeeatr.get_employee')}}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                id:id,
            },
            success: function(data){
                var join_date=new Date(data[0].join_date);
                var tahun = join_date.getFullYear();
                var bulan = join_date.getMonth();
                var tanggal = join_date.getDate();
                var tanggal_resign_format='SEKARANG';
                var no_form='';
                if(data[0].tanggal_resign!=null){
                    var join_date=new Date(data[0].tanggal_resign);
                    var tahun_resign = join_date.getFullYear();
                    var bulan_resign = join_date.getMonth();
                    var tanggal_resign = join_date.getDate();
                    switch(bulan_resign) {
                        case 0: bulan_resign = "Januari"; break;
                        case 1: bulan_resign = "Februari"; break;
                        case 2: bulan_resign = "Maret"; break;
                        case 3: bulan_resign = "April"; break;
                        case 4: bulan_resign = "Mei"; break;
                        case 5: bulan_resign = "Juni"; break;
                        case 6: bulan_resign = "Juli"; break;
                        case 7: bulan_resign = "Agustus"; break;
                        case 8: bulan_resign = "September"; break;
                        case 9: bulan_resign = "Oktober"; break;
                        case 10: bulan_resign = "November"; break;
                        case 11: bulan_resign = "Desember"; break;
                    }
                    tanggal_resign_format=tanggal_resign+' '+bulan_resign+' '+tahun_resign;
                }
                switch(bulan) {
                    case 0: bulan = "Januari"; break;
                    case 1: bulan = "Februari"; break;
                    case 2: bulan = "Maret"; break;
                    case 3: bulan = "April"; break;
                    case 4: bulan = "Mei"; break;
                    case 5: bulan = "Juni"; break;
                    case 6: bulan = "Juli"; break;
                    case 7: bulan = "Agustus"; break;
                    case 8: bulan = "September"; break;
                    case 9: bulan = "Oktober"; break;
                    case 10: bulan = "November"; break;
                    case 11: bulan = "Desember"; break;
                }
                var join_date_local=tanggal+' '+bulan+' '+tahun;
                $('#enroll_id_text_2').text(data[0].enroll_id);
                $('#enroll_id_input_2').val(data[0].enroll_id);
                $('#employee_name_text_2').text(data[0].employee_name);
                $('#join_date_text_2').text(join_date_local);
                $('#last_date_text_2').text(tanggal_resign_format);
                var today=new Date();
                var month_now=today.getMonth();
                var year_now=today.getFullYear();
                var reason='';
                // if(data[0].tanggal_resign==null){
                    no_form='HRD/NAG-REF'+'/'+integerToRoman(month_now+1)+'/'+year_now;
                    reason='Alasan Pembuatan';
                // }else{
                //     var tanggal_masuk = new Date(data[0].join_date);
                //     var tanggal_resign2 = new Date(data[0].tanggal_resign);
                //     var selisih_tahun = diff_years(tanggal_resign2, tanggal_masuk);
                //     if(selisih_tahun<1){
                //         no_form='HRD/NAG-REF'+'/'+integerToRoman(month_now+1)+'/'+year_now;
                //         reason='Alasan Pembuatan';
                //     }else{
                //         no_form='HRD/NAG/PK'+'/'+integerToRoman(month_now+1)+'/'+year_now;
                //         reason='Sebab Resign';
                //     }
                // }
                $('#no_form_must_be_2').text(no_form);
                $('#no_form_input_next_2').val(no_form);
                $('#reason_text_2').text(reason);
            }
        });
    }
    function diff_years(dt2, dt1) 
    {
        // Calculate the difference in milliseconds between the two dates
        var diff = (dt2.getTime() - dt1.getTime()) / 1000;
        // Convert the difference from milliseconds to days
        diff /= (60 * 60 * 24);
        // Calculate the approximate number of years by dividing the difference in days by the average number of days in a year (365.25)
        return Math.abs(Math.round(diff / 365.25));
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
    function export_sk(){
        var enroll_id=$('#enroll_id_input').val();
        var no_form='NO. '+$('#no_form_input').val()+'/'+$('#no_form_input_next').val();
        var reason=$('#reason_3_input').val();
        var url = 'export_pdf_sk_kerja?enroll_id='+enroll_id+'&no_form='+no_form+'&reason='+reason;
        window.open(url, '_blank');
    }
    function export_paklaring(){
        var enroll_id=$('#enroll_id_input').val();
        var no_form='NO. '+$('#no_form_input').val()+'/'+$('#no_form_input_next').val();
        var reason=$('#reason_3_input').val();
        var url = 'export_pdf_paklaring?enroll_id='+enroll_id+'&no_form='+no_form+'&reason='+reason;
        window.open(url, '_blank');
    }
    function export_sk_bni(){
        var enroll_id=$('#enroll_id_input_2').val();
        var no_form='NO. '+$('#no_form_input_2').val()+'/'+$('#no_form_input_next_2').val();
        var item=$('#reason_input').val();
        var newitem=item.replace("&","%26");
        var reason=$('#reason_input_2').val();
        var newreason=reason.replace("&","%26");
        var created_date=$('#date_input').val();
        var url = 'export_pdf_sk_bni?enroll_id='+enroll_id+'&no_form='+no_form+'&item='+newitem+'&reason='+newreason+'&created_date='+created_date;
        window.open(url, '_blank');
    }
</script>
@endsection