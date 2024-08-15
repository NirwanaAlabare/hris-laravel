@extends('admin.adminlayouts.adminlayout3')

@section('head')
<link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />

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
                <div class="row py-2">
                    <div class="col-4 pl-4" id="reason_text" style="font-weight: bold">
                    </div>
                    <div class="col-7">
                        <input type="text" class="form-control py-0 px-2" id="reason_input" style="background-color:white">
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                    </div>
                    <div class="col-7">
                        <button class="btn btn-danger py-0" onclick="export_sk()"><span class="fa fa-file-pdf-o" style="font-size:9pt"></span> PRINT SK KERJA</button>
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
                    <div class="col-4 pl-4" id="reason_text_2" style="font-weight: bold">
                    </div>
                    <div class="col-7">
                        <input type="text" class="form-control py-0 px-2" id="reason_input_2" style="background-color:white">
                    </div>
                </div>
                <div class="row py-2 bg-light text-dark">
                    <div class="col-4 pl-4" style="font-weight: bold">
                        Tanggal Pembuatan
                    </div>
                    <div class="col-7">
                        <input type="date" class="form-control py-0 px-2" id="date_input" style="background-color:white">
                    </div>
                </div>
                <div class="row">
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
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card shadow">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <table id="datatable-ajax-crud" class="table table-sm table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col" class="text-center"></th>
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
<script type="text/javascript">
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
                "url": "{{ route('hris.employeeatr.ajax_getemployeeatr') }}",
                "dataType": "json",
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "dataSrc": "data",
            },
            columns: [
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
                },
                {
                    title: '<span class="fa fa-cog"></span>',
                }
            ],
            order: [
                [2, 'asc']
            ],
            columnDefs: [
                {
                    'targets': [3],
                    'render' : function (data, type, row) {
                        return `
                            <button class='btn btn-danger' style='padding-top:0px;padding-bottom:0px; font-family:monospace; font-size:10pt' data-target="#user-form-modal" data-toggle="modal" onclick="send_to_modal(` + row.enroll_id + `)">
                                PRINT SK KERJA
                            </button>
                            <button class='btn btn-primary' style='padding-top:0px;padding-bottom:0px; font-family:monospace; font-size:10pt' data-target="#user-form-modal_2" data-toggle="modal" onclick="send_to_modal_2(` + row.enroll_id + `)">
                                SK BNI
                            </button>
                        `
                    }
                }
            ],
        });
        
        table1.draw();
    });
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
        var reason=$('#reason_input').val();
        var url = 'export_pdf_sk_kerja?enroll_id='+enroll_id+'&no_form='+no_form+'&reason='+reason;
        window.open(url, '_blank');
    }
    function export_sk_bni(){
        var enroll_id=$('#enroll_id_input_2').val();
        var no_form='NO. '+$('#no_form_input_2').val()+'/'+$('#no_form_input_next_2').val();
        var reason=$('#reason_input_2').val();
        var created_date=$('#date_input').val();
        var url = 'export_pdf_sk_bni?enroll_id='+enroll_id+'&no_form='+no_form+'&reason='+reason+'&created_date='+created_date;
        window.open(url, '_blank');
    }
</script>
@endsection