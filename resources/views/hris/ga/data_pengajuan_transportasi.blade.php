@extends('admin.adminlayouts.adminlayout4')

@section('head')
<link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<!---Sweetalert Css-->
<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

@stop
@section('mainarea')

<div class="card-header mt-7 pt-1 pb-0">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="btn btn-white" href="{{route('hris.ga.form_pengajuan_transportasi')}}">Formulir</a>
        </li>
        <li class="nav-item">
            <a class="btn btn-primary" style="background-color:blue" href="{{route('hris.ga.data_pengajuan_transportasi')}}">Data</a>
        </li>
    </ul>
</div>

<div class="card-body p-0" style="border: 1px solid #d8d4dc">
    <div id="accordion" class="px-5">
        <div class="card mb-3">
            <div class="card-header p-0 bg-light" id="headingOne">
                <h5 class="mb-0">
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <i class="fa fa-filter" aria-hidden="true"></i> Filter
                </button>
            </h5>
        </div>
      
        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body px-0 pt-5">
                <div class="row">
                    <div class="col-2 pl-6">
                        <label class="form-label" style="font-size:11pt">Tanggal Pemberangkatan</label>
                    </div>
                    <div class="col-3 pl-0">
                        <input type="hidden" id="daterange1" name="daterange1">
                        <a class="nav-link card-title border border-secondary" id="daterange-btn1" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Klik di sini untuk pilih tanggal kehadiran"></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2 pl-6 pt-1">
                        <label class="form-label" style="font-size:11pt">Status</label>
                    </div>
                    <div class="col-3 pl-0">
                        <select class="form-control" id="value_status" style="background-color: white" onchange="perubahanan_status()">\
                            <option value=''>Pilih Status</option>
                            <option value=0>Pengajuan Baru</option>
                            <option value=1>Approved</option>
                            <option value=2>Alternative</option>
                            <option value=3>On The Way</option>
                            <option value=4>Done</option>
                            <option value=6>Late</option>
                            <option value=5>Cancel</option>
                        </select>
                    </div>
                </div>
                <div class="row pt-1">
                    <div class="col-2 pl-6 pt-1">
                    </div>
                    <div class="col-3 pl-0">
                        <button class="btn btn-success py-1 my-1" id="export_excel"><i class="fa fa-file-excel-o"></i> Export Excel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row px-3 pt-0 pb-6">
        <div class="col-12">
            <input type="hidden" id="user" value="{{$id_user}}">
            <table id="datatable" class="table table-bordered">
                <thead class="table-white text-white" style="background-color:#15435a">
                    <tr style='text-align:center; vertical-align:middle'>
                        <th>No</th>
                        <th>Nama Karyawan</th>
                        <th>Department</th>
                        <th>Keberangkatan Awal</th>
                        <th>Tujuan</th>
                        <th>Waktu Pemberangkatan</th>
                        <th>Tujuan Pemberangkatan</th>
                        <th>Status</th>
                        <th>Option</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 40%;">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                <button data-dismiss="modal" aria-label="Close" style="background-color: rgb(255, 255, 255)"><span class="fa fa-times"></span></button>
            </div>
            <div class="modal-body">
                <div class="row pb-2 pt-1">
                    <div class="col-5 pl-6">
                        <h6 style="font-size:12pt;">Nama Karyawan</h6>
                    </div>
                    <input type="hidden" id="id_request">
                    <div class="col-6 pr-5" id="nama_karyawan_modal">
                    </div>
                    <div class="col-1 text-right"></div>
                </div>
                <div class="row py-2 bg-light text-dark">
                    <div class="col-5 pl-6">
                        <h6 style="font-size:12pt;">Department</h6>
                    </div>
                    <div class="col-7 pr-5" id="department_modal">
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-5 pl-6 pt-2">
                        <h6 style="font-size:12pt;">Keberangkatan Awal</h6>
                    </div>
                    <div class="col-7 pr-5">
                        <div class="row">
                            <div class="col-12" id="detail_alamat_modal">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" id="city_modal" style="font-size: 7pt">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row py-2 bg-light text-dark">
                    <div class="col-5 pl-6 pt-2">
                        <h6 style="font-size:12pt;">Tujuan</h6>
                    </div>
                    <div class="col-7 pr-5">
                        <div class="row">
                            <div class="col-12" id="detail_alamat_tujuan_modal">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" id="city_tujuan_modal" style="font-size: 7pt">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row py-2 text-dark">
                    <div class="col-5 pl-6 pt-2">
                        <h6 style="font-size:12pt;">Tujuan Pemberangkatan</h6>
                    </div>
                    <div class="col-7 pr-5 pl-0">
                        <div class="col-12" id="tujuan_pemberangkatan_modal">
                        </div>
                    </div>
                </div>
                <div class="row py-2 bg-light text-dark">
                    <div class="col-5 pl-6 pt-2">
                        <h6 style="font-size:12pt;">Waktu Pemberangkatan</h6>
                    </div>
                    <div class="col-7 pr-5 pl-0">
                        <div class="col-12" id="waktu_pemberangkatan_modal">
                        </div>
                    </div>
                </div>
                <div class="row py-2 text-dark">
                    <div class="col-5 pl-6 pt-2">
                        <h6 style="font-size:12pt;">Jarak Tempuh</h6>
                    </div>
                    <div class="col-7 pr-5 pl-0 pt-2">
                        <div class="col-12" id="jarak_tempuh_modal">
                        </div>
                    </div>
                </div>
                <div class="row py-2 bg-light">
                    <div class="col-5 pl-6 pt-2">
                        <h6 style="font-size:12pt;">Driver</h6>
                    </div>
                    <div class="col-7 pr-5">
                        <select class="form-control" id="driver">
                            <option value="">Pilih Driver</option>
                            @foreach ($drivers as $key=>$value)
                                <option value="{{$value->enroll_id}}">{{$value->employee_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row py-2 text-dark">
                    <div class="col-5 pl-6 pt-2">
                        <h6 style="font-size:12pt;">Nomor Kendaraan</h6>
                    </div>
                    <div class="col-7 pr-5">
                        <select class="form-control" id="vehicle_id">
                            <option value="">Pilih Kendaraan</option>
                            @foreach ($vehicles as $key=>$value)
                                <option value="{{$value->id}}">{{$value->plat_no}} || {{$value->merk}} {{$value->tipe}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row pt-2">
                    <div class="col-12 text-center">
                        <button class="btn btn-success btn-sm py-1" id="approve_this_request">APPROVE</button>
                        <button class="btn btn-light btn-sm py-1" data-dismiss="modal">CANCEL</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 40%;">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                <button data-dismiss="modal" aria-label="Close" style="background-color: rgb(255, 255, 255)"><span class="fa fa-times"></span></button>
            </div>
            <div class="modal-body">
                <div class="row pb-2 pt-1">
                    <div class="col-5 pl-6">
                        <h6 style="font-size:12pt;">Nama Karyawan</h6>
                    </div>
                    <input type="hidden" id="id_request_2">
                    <div class="col-6 pr-5" id="nama_karyawan_modal_2">
                    </div>
                    <div class="col-1 text-right"></div>
                </div>
                <div class="row py-2 bg-light text-dark">
                    <div class="col-5 pl-6">
                        <h6 style="font-size:12pt;">Department</h6>
                    </div>
                    <div class="col-7 pr-5" id="department_modal_2">
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-5 pl-6 pt-2">
                        <h6 style="font-size:12pt;">Keberangkatan Awal</h6>
                    </div>
                    <div class="col-7 pr-5">
                        <div class="row">
                            <div class="col-12" id="detail_alamat_modal_2">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" id="city_modal_2" style="font-size: 7pt">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row py-2 bg-light text-dark">
                    <div class="col-5 pl-6 pt-2">
                        <h6 style="font-size:12pt;">Tujuan</h6>
                    </div>
                    <div class="col-7 pr-5">
                        <div class="row">
                            <div class="col-12" id="detail_alamat_tujuan_modal_2">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" id="city_tujuan_modal_2" style="font-size: 7pt">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row py-2 bg-light text-dark">
                    <div class="col-5 pl-6 pt-2">
                        <h6 style="font-size:12pt;">Tujuan Pemberangkatan</h6>
                    </div>
                    <div class="col-7 pr-5">
                        <div class="col-12 pl-0" id="tujuan_pemberangkatan_modal_2">
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-5 pl-6 pt-2">
                        <h6 style="font-size:12pt;">Alternative</h6>
                    </div>
                    <div class="col-7 pr-5">
                        <textarea id="alasan_reject" class="form-control" style="background-color: white"></textarea>
                    </div>
                </div>
                <div class="row pt-2">
                    <div class="col-12 text-center">
                        <button class="btn btn-success btn-sm py-1" id="reject_this_request">Save</button>
                        <button class="btn btn-light btn-sm py-1" data-dismiss="modal">CANCEL</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="alternativeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row py-2">
                    <div class="col-5 pl-6 pt-2">
                        <h6 style="font-size:12pt;">Alternative</h6>
                    </div>
                    <div class="col-7 pr-5">
                        <input type="hidden" id="alasan_reject_id">
                        <textarea id="alasan_reject_alternative" class="form-control" style="background-color: white"></textarea>
                    </div>
                </div>
                <div class="row pt-2">
                    <div class="col-12 text-center">
                        <button class="btn btn-warning text-dark btn-sm py-1" id="update_reject_request">UPDATE</button>
                        <button class="btn btn-light btn-sm py-1" data-dismiss="modal" id="cancel_reject_request">CANCEL</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="showDriverModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row py-2">
                    <div class="col-5 pl-6 pt-2">
                        <h6 style="font-size:12pt;">Driver</h6>
                    </div>
                    <div class="col-7 pr-5">
                        <input type="hidden" id="id_request_approve_modal">
                        <select class="form-control" id="show_driver_modal">
                            <option value="">Pilih Driver</option>
                            @foreach ($drivers as $key=>$value)
                                <option value="{{$value->enroll_id}}">{{$value->employee_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row py-2 bg-light text-dark">
                    <div class="col-5 pl-6 pt-2">
                        <h6 style="font-size:12pt;">Nomor Kendaraan</h6>
                    </div>
                    <div class="col-7 pr-5">
                        <select class="form-control" id="nomor_kendaraan_modal">
                            <option value="">Pilih Kendaraan</option>
                            @foreach ($vehicles as $key=>$value)
                                <option value="{{$value->id}}">{{$value->plat_no}} || {{$value->merk}} {{$value->tipe}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row pt-2">
                    <div class="col-12 text-center">
                        <button class="btn btn-success btn-sm py-1" id="approve_this_request_info">UPDATE</button>
                        <button class="btn btn-light btn-sm py-1" data-dismiss="modal" id="cancel_this_request">CANCEL</button>
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
<!-- Sweet alert js-->
<script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
<script>
    $('body').on('click', '#export_excel', function (event) {
        var status=$('#value_status').val();
        var daterange = $('#daterange1').val();
        $('#export_excel').addClass("btn-loading");
        $("#export_excel").attr("disabled", true);
        $.ajax({
            type: 'POST',
            url: '{{route('hris.ga.export_excel_transportasi')}}',
            data: {
                status:status,
                daterange:daterange
            },
            xhrFields: { responseType : 'blob' },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success:function(data){
                var blob = new Blob([data]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                let file_name = 'Report permintaan transportasi '+daterange+' '+Math.ceil(Math.random()*1000000);
                link.download = file_name+".xlsx";
                link.click();
                swal("", "Export Car Request", "success");
                $('#export_excel').removeClass("btn-loading");
                $("#export_excel").attr("disabled", false);
            },
            error: function(res){
                swal("", "Export Car Request", "error");
                $('#export_excel').removeClass("btn-loading");
                $("#export_excel").attr("disabled", false);
            }
        });
    });
    $(document).ready(function() {
        $('#datatable').DataTable();
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
        $('#daterange1').val(daterange1).trigger('change');
    })
    $('#driver').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("driver").style.border="";
        }
    });
    $('#vehicle_id').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("vehicle_id").style.border="";
        }
    });
    var start = moment().subtract(29, 'days');
    var end = moment();
    var htmlDateRange = '<span><i class="fa fa-calendar"></i> ' + start.format("D MMM YYYY").toUpperCase() + ' s/d ' + end.format("D MMM YYYY").toUpperCase() + '</span><i class="fa fa-angle-down ml-1"></i>'
    var daterange1 = start.format("YYYY-MM-DD") + " s/d " + end.format("YYYY-MM-DD");
    var dateUpdateKehadiran = end.format("DD-MM-YYYY");

    $('#daterange-btn1').html(htmlDateRange);
    $('#daterange1').val(daterange1);
    let datatable = $("#datatable").DataTable({
        ordering: true,
        processing: true,
        serverSide: true,
        paging: true,
        searching: true,
        destroy: true,
        scrollX: true,
        ajax: {
            url: '{{ route('hris.ga.get_data_pengajuan_transportasi') }}',
            data: function(d) {
                d.user = $('#user').val();
                d.status = $('#value_status').val();
                d.daterange = $('#daterange1').val();
            },
        },
        columns: [
            {
                data: null, // Kolom untuk nomor urut
                render: function (data, type, row, meta) {
                    return meta.row + 1; // Menampilkan urutan angka
                }
            }, {
                data: 'employee_name'

            },
            {
                data: 'department_name'
            },
            {
                render: function (data, type, row, meta) {
                    return `<div class="row">\
                                <div class="col-12 text-center">\
                                    `+row.detail_alamat+`\
                                </div>\
                            </div>\
                            <div class="row">\
                                <div class="col-12 text-center" style="font-size:8pt">\
                                    `+row.desa+`\
                                </div>\
                            </div>`;
                }
            },
            {
                render: function (data, type, row, meta) {
                    return `<div class="row">\
                                <div class="col-12 text-center">\
                                    `+row.detail_alamat_tujuan+`\
                                </div>\
                            </div>\
                            <div class="row">\
                                <div class="col-12 text-center" style="font-size:8pt">\
                                    `+row.desa_tujuan+`\
                                </div>\
                            </div>`;
                }
            },
            {
                data: 'tanggal_pemberangkatan'
            },
            {
                data: 'tujuan_pemberangkatan'
            },
            {
                data: null,
                render: function (data, type, row, meta) {
                    if (row.user==4241 || row.user==20 || row.user==17 || row.user==7765 || row.user==5321 || row.user==6083 || row.user==6081|| row.user==6713){
                        if(row.status==0){
                            return `<a class='btn btn-success py-0 px-2 mt-0 btn-sm btn-block text-white' style='font-size:9pt' data-toggle="modal" data-target="#approveModal" ' onclick="approve_function(` + row.id + `,'` + row.employee_name + `','` + row.department_name + `','` + row.detail_alamat + `','` + row.desa + `','` + row.detail_alamat_tujuan + `','` + row.desa_tujuan + `','` + row.tujuan_pemberangkatan + `','` + row.tanggal_pemberangkatan + `','` + row.jarak_tempuh + `')"> APPROVE </a><a class='btn btn-danger py-0 mt-1 btn-sm btn-block text-white' style='font-size:9pt' data-toggle="modal" data-target="#rejectModal" ' onclick="reject_function(` + row.id + `,'` + row.employee_name + `','` + row.department_name + `','` + row.detail_alamat + `','` + row.desa + `','` + row.detail_alamat_tujuan + `','` + row.desa_tujuan + `','` + row.tujuan_pemberangkatan + `','` + row.tanggal_pemberangkatan + `','` + row.jarak_tempuh + `')"> ALTERNATIVE </a>`;
                        }else{
                            if(row.status==2){
                                return `
                                    <select class="form-control px-1" onChange="approveButton(`+row.id+`)" id="value_status_`+row.id+`">\
                                    <option value=0 ${row.status == 0 ? 'selected' : ''}>Pilih Status</option>\
                                    <option value=1 ${row.status == 1 ? 'selected' : ''}>Approved</option>\
                                    <option value=2 ${row.status == 2 ? 'selected' : ''}>Alternative</option>\
                                    <option value=3 ${row.status == 3 ? 'selected' : ''}>On The Way</option>\
                                    <option value=4 ${row.status == 4 ? 'selected' : ''}>Done</option>\
                                    <option value=6 ${row.status == 6 ? 'selected' : ''}>Late</option>\
                                    <option value=5 ${row.status == 5 ? 'selected' : ''}>Cancel</option>\
                                    </select>`;
                            }else{
                                return `
                                    <select class="form-control px-1" onChange="approveButton(`+row.id+`)" id="value_status_`+row.id+`">\
                                    <option value=0 ${row.status == 0 ? 'selected' : ''}>Pilih Status</option>\
                                    <option value=1 ${row.status == 1 ? 'selected' : ''}>Approved</option>\
                                    <option value=2 ${row.status == 2 ? 'selected' : ''}>Alternative</option>\
                                    <option value=3 ${row.status == 3 ? 'selected' : ''}>On The Way</option>\
                                    <option value=4 ${row.status == 4 ? 'selected' : ''}>Done</option>\
                                    <option value=6 ${row.status == 6 ? 'selected' : ''}>Late</option>\
                                    <option value=5 ${row.status == 5 ? 'selected' : ''}>Cancel</option>\
                                    </select>`;
                            }
                        }
                    }else{
                        if(row.status==0){
                            return `<h6 style="font-size:11pt; margin-bottom:3px;text-align:center;color:grey;font-weight:bold">PENDING</h6>`;
                        }else if(row.status==1){
                            return `<h6 style="font-size:11pt; margin-bottom:3px;text-align:center;color:green;font-weight:bold">APPROVED</h6>`;
                        }else if(row.status==2){
                            return `<h6 style="font-size:11pt; margin-bottom:3px;text-align:center;color:red;font-weight:bold">Alternative</h6>`;
                        }else if(row.status==3){
                            return `<h6 style="font-size:11pt; margin-bottom:3px;text-align:center;color:green;font-weight:bold">ON THE WAY</h6>`;
                        }else if(row.status==4){
                            return `<h6 style="font-size:11pt; margin-bottom:3px;text-align:center;color:green;font-weight:bold">DONE</h6>`;
                        }else if(row.status==6){
                            return `<h6 style="font-size:11pt; margin-bottom:3px;text-align:center;color:orange;font-weight:bold">LATE</h6>`;
                        }else if(row.status==5){
                            return `<h6 style="font-size:11pt; margin-bottom:3px;text-align:center;color:red;font-weight:bold">CANCEL</h6>`;
                        }
                    }
                }
            },
            {
                data: null,
                render: function (data, type, row, meta) {
                    if (row.user==4241 || row.user==20 || row.user==17 || row.user==7765 || row.user==5321 || row.user==6083 || row.user==6081 || row.user==6713){
                        if(row.created_by==row.user){
                            if(row.status==0){
                                return `<button onclick="edit_detail(` + row.id + `)" class="btn btn-sm btn-block py-0 px-1 mb-1" style="font-size:9pt;background-color:orange;color:white">EDIT</button><button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-block btn-sm py-0 px-1 mb-1  mt-0" style="font-size:9pt">LIHAT DETAIL</button>`;
                            }else if(row.status!=2 && row.status!=5){
                                    return `<center><a href="#" data-toggle="modal" data-target="#showDriverModal" onclick="show_driver_function(` + row.id_driver + `,`+row.nomor_kendaraan+`,` + row.id+ `,'admin')"><img src="{{URL::asset('assets/images/brand/info.png')}}" width="23" style="padding-bottom:4px"></a></center>\
                                    <button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-block btn-sm py-0 px-1 mb-1" style="font-size:9pt">LIHAT DETAIL</button><button onclick="print_penugasan(` + row.id + `)" class="btn btn-success btn-sm btn-block px-1 py-0 mt-0" style="font-size:9pt">FORM PENUGASAN</button>`;
                            }else{
                                if(row.status==2){
                                    return `<center><a href="#" data-toggle="modal" data-target="#alternativeModal" onclick="alternative_function('` + row.alasan_status + `',` + row.id+ `,'admin')"><img src="{{URL::asset('assets/images/brand/info.png')}}" width="23" style="padding-bottom:4px"></a></center>\
                                    <button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-sm btn-block py-0 px-1 mb-1" style="font-size:9pt">LIHAT DETAIL</button>`;
                                }else{
                                    return `<button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-sm btn-block py-0 px-1 mb-1" style="font-size:9pt">LIHAT DETAIL</button>`;
                                }
                            }
                        }else{
                            if(row.status==0){
                                return `<button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-sm btn-block py-0 px-1 mb-1 " style="font-size:9pt">LIHAT DETAIL</button>`;
                            }else if(row.status!=2 && row.status!=5){
                                return `<center><a href="#" data-toggle="modal" data-target="#showDriverModal" onclick="show_driver_function(` + row.id_driver + `,`+row.nomor_kendaraan+`,` + row.id+ `,'admin')"><img src="{{URL::asset('assets/images/brand/info.png')}}" width="23" style="padding-bottom:4px"></a></center>\
                                <button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-block btn-sm py-0 px-1 mb-1" style="font-size:9pt">LIHAT DETAIL</button><button onclick="print_penugasan(` + row.id + `)" class="btn btn-success btn-sm btn-block px-1 py-0 mt-0" style="font-size:9pt">FORM PENUGASAN</button>`;
                            }else{
                                if(row.status==2){
                                    return `<center><a href="#" data-toggle="modal" data-target="#alternativeModal" ' onclick="alternative_function('` + row.alasan_status + `',` + row.id+ `,'admin')"><img src="{{URL::asset('assets/images/brand/info.png')}}" width="23" style="padding-bottom:4px"></a></center>\
                                    <button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-sm btn-block py-0 px-1 mb-1" style="font-size:9pt">LIHAT DETAIL</button>`;
                                }else{
                                    return `<button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-sm btn-block py-0 px-1 mb-1" style="font-size:9pt">LIHAT DETAIL</button>`;
                                }
                            }
                        }
                    }else{
                        if(row.created_by==row.user){
                            if(row.status==0){
                                return `<button onclick="edit_detail(` + row.id + `)" class="btn btn-sm btn-block py-0 px-1 mb-1" style="font-size:9pt;background-color:orange;color:white">EDIT</button>`;
                            }else if(row.status!=2 && row.status!=5){
                                return `<center><a href="#" data-toggle="modal" data-target="#showDriverModal" onclick="show_driver_function(` + row.id_driver + `,`+row.nomor_kendaraan+`,` + row.id+ `,'user')"><img src="{{URL::asset('assets/images/brand/info.png')}}" width="23" style="padding-bottom:4px"></a></center>\
                                <button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-block btn-sm py-0 px-1 mb-1 mt-0" style="font-size:9pt">LIHAT DETAIL</button><button onclick="print_penugasan(` + row.id + `)" class="btn btn-success btn-sm btn-block p-0 mt-0" style="font-size:9pt">FORM PENUGASAN</button>`;
                            }else{
                                if(row.status==2){
                                    return `<center><a href="#" data-toggle="modal" data-target="#alternativeModal" ' onclick="alternative_function('` + row.alasan_status + `',` + row.id+ `,'user')"><img src="{{URL::asset('assets/images/brand/info.png')}}" width="23" style="padding-bottom:4px"></a></center>\
                                    <button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-sm btn-block py-0 px-1 mb-1" style="font-size:9pt">LIHAT DETAIL</button>`;
                                }else{
                                    return `<center><a href="#" data-toggle="modal" data-target="#showDriverModal" onclick="show_driver_function(` + row.id_driver + `,`+row.nomor_kendaraan+`,` + row.id+ `,'user')"><img src="{{URL::asset('assets/images/brand/info.png')}}" width="23" style="padding-bottom:4px"></a></center>\
                                    <button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-block btn-sm py-0 px-1 mb-1 mt-0" style="font-size:9pt">LIHAT DETAIL</button>`;
                                }
                            }
                        }else{
                            if(row.status==0){
                                return `<button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-block btn-sm py-0 px-1 mb-1 mt-0" style="font-size:9pt">LIHAT DETAIL</button>`;
                            }else if(row.status!=2 && row.status!=5){
                                return `<center><a href="#" data-toggle="modal" data-target="#showDriverModal" onclick="show_driver_function(` + row.id_driver + `,`+row.nomor_kendaraan+`,` + row.id+ `,'user')"><img src="{{URL::asset('assets/images/brand/info.png')}}" width="23" style="padding-bottom:4px"></a></center>\
                                <button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-block btn-sm py-0 px-1 mb-1 mt-0" style="font-size:9pt">LIHAT DETAIL</button><button onclick="print_penugasan(` + row.id + `)" class="btn btn-success btn-sm btn-block p-0 mt-0" style="font-size:9pt">FORM PENUGASAN</button>`;
                            }else{
                                if(row.status==2){
                                    return `<center><a href="#" data-toggle="modal" data-target="#alternativeModal" ' onclick="alternative_function('` + row.alasan_status + `',` + row.id+ `,'user')"><img src="{{URL::asset('assets/images/brand/info.png')}}" width="23" style="padding-bottom:4px"></a></center>\
                                    <button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-sm btn-block py-0 px-1 mb-1" style="font-size:9pt">LIHAT DETAIL</button>`;
                                }else{
                                    return `<center><a href="#" data-toggle="modal" data-target="#showDriverModal" onclick="show_driver_function(` + row.id_driver + `,`+row.nomor_kendaraan+`,` + row.id+ `,'user')"><img src="{{URL::asset('assets/images/brand/info.png')}}" width="23" style="padding-bottom:4px"></a></center>\
                                    <button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-block btn-sm py-0 px-1 mb-1 mt-0" style="font-size:9pt">LIHAT DETAIL</button>`;
                                }
                            }
                        }
                    }
                }
            },
        ],
        columnDefs: [{ width: 115, targets: [7,8] }],
    });
    $('#value_status').on('change',function(){
        dataTableReload();
    });
    $('#daterange1').on('change',function(){
        dataTableReload();
    });
    function approveButton(id){
        var status_val=$('#value_status_'+id).val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.change_status_car_request')}}",
            data: {
                id_request:id,
                status:status_val
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                dataTableReload();
                $('#value_status_'+id).val(res);
            },
            error: function(error){
                swal("", "Status gagal di update", "error");
            }
        });
    }
    function lihat_detail(id) {
        var id=id;
        var url = 'lihat_detail?id='+id;
        window.open(url, '_self');
    }
    function edit_detail(id) {
        var id=id;
        var url = 'edit_detail?id='+id;
        window.open(url, '_self');
    }
    function print_penugasan(id) {
        var id=id;
        var url = 'print_penugasan_transportasi?id='+id;
        window.open(url, '_blank');
    }
    function approve_function(id,employee_name,department_name,detail_alamat,desa,detail_alamat_tujuan,desa_tujuan,tujuan_pemberangkatan,waktu_pemberangkatan,jarak_tempuh){
        $('#id_request').val(id);
        $("#nama_karyawan_modal").html(employee_name);
        $("#department_modal").html(department_name);
        $("#detail_alamat_modal").html(detail_alamat);
        $("#city_modal").html(desa);
        $("#detail_alamat_tujuan_modal").html(detail_alamat_tujuan);
        $("#city_tujuan_modal").html(desa_tujuan);
        $("#tujuan_pemberangkatan_modal").html(tujuan_pemberangkatan.toUpperCase());
        $("#waktu_pemberangkatan_modal").html(waktu_pemberangkatan.toUpperCase());
        $("#jarak_tempuh_modal").html(jarak_tempuh+' Km');
    }
    function reject_function(id,employee_name,department_name,detail_alamat,desa,detail_alamat_tujuan,desa_tujuan,tujuan_pemberangkatan,waktu_pemberangkatan,jarak_tempuh){
        $('#id_request_2').val(id);
        $("#nama_karyawan_modal_2").html(employee_name);
        $("#department_modal_2").html(department_name);
        $("#detail_alamat_modal_2").html(detail_alamat);
        $("#city_modal_2").html(desa);
        $("#detail_alamat_tujuan_modal_2").html(detail_alamat_tujuan);
        $("#city_tujuan_modal_2").html(desa_tujuan);
        $("#tujuan_pemberangkatan_modal_2").html(tujuan_pemberangkatan);
        $("#waktu_pemberangkatan_modal_2").html(waktu_pemberangkatan.toUpperCase());
        $("#jarak_tempuh_modal").html(jarak_tempuh+' Km');
    }
    function alternative_function(alternative,id,role){
        $('#alasan_reject_id').val(id);
        $('#alasan_reject_alternative').val(alternative);
        if(role=='user'){
            document.getElementById('alasan_reject_alternative').style.backgroundColor='white';
            $('#alasan_reject_alternative').attr('disabled',true);
            document.getElementById('update_reject_request').style.visibility='hidden';
            document.getElementById('cancel_reject_request').style.visibility='hidden';
        }else{
            document.getElementById('alasan_reject_alternative').style.backgroundColor='white';
            $('#alasan_reject_alternative').attr('disabled',false);
            document.getElementById('update_reject_request').style.visibility='visible';
            document.getElementById('cancel_reject_request').style.visibility='visible';
        }
    }
    function show_driver_function(id_driver,nomor_kendaraan,id_request,role){
        $('#show_driver_modal').val(id_driver);
        $('#nomor_kendaraan_modal').val(nomor_kendaraan);
        $('#id_request_approve_modal').val(id_request);
        if(role=='user'){
            document.getElementById('show_driver_modal').style.backgroundColor='white';
            document.getElementById('nomor_kendaraan_modal').style.backgroundColor='white';
            $('#show_driver_modal').attr('disabled',true);
            $('#nomor_kendaraan_modal').attr('disabled',true);
            document.getElementById('approve_this_request_info').style.visibility='hidden';
            document.getElementById('cancel_this_request').style.visibility='hidden';
        }else{
            document.getElementById('show_driver_modal').style.backgroundColor='white';
            document.getElementById('nomor_kendaraan_modal').style.backgroundColor='white';
            $('#show_driver_modal').attr('disabled',false);
            $('#nomor_kendaraan_modal').attr('disabled',false);
            document.getElementById('approve_this_request_info').style.visibility='visible';
            document.getElementById('cancel_this_request').style.visibility='visible';
        }
    }
    $('#approve_this_request').on('click',function(){
        var id_request=$('#id_request').val();
        var driver=$('#driver').val();
        var vehicle_id=$('#vehicle_id').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.approve_car_request')}}",
            data: {
                id_request:id_request,
                driver:driver,
                vehicle_id:vehicle_id
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                swal("", "Permintaan transportasi di approve", "success");
                $('#id_request').val('');
                $('#driver').val('');
                $('#vehicle_id').val('');
                $('#approveModal').modal('hide');
                dataTableReload();
            },
            error: function(error){
                let err_log=error.responseJSON.errors;
                if(error.status==422){
                    if(typeof(err_log.driver)!=='undefined'){
                        document.getElementById("driver").style.border = "1px solid red";
                    }else{
                        document.getElementById("driver").style.border="";
                    }
                    if(typeof(err_log.vehicle_id)!=='undefined'){
                        document.getElementById("vehicle_id").style.border = "1px solid red";
                    }else{
                        document.getElementById("vehicle_id").style.border="";
                    }
                }
            }
        });
    });
    $('#approve_this_request_info').on('click',function(){
        var id_request=$('#id_request_approve_modal').val();
        var driver=$('#show_driver_modal').val();
        var vehicle_id=$('#nomor_kendaraan_modal').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.approve_car_request')}}",
            data: {
                id_request:id_request,
                driver:driver,
                vehicle_id:vehicle_id
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                swal("", "Permintaan transportasi di update", "success");
                $('#showDriverModal').modal('hide');
                dataTableReload();
            },
            error: function(error){
                let err_log=error.responseJSON.errors;
                if(error.status==422){
                    if(typeof(err_log.driver)!=='undefined'){
                        document.getElementById("show_driver_modal").style.border = "1px solid red";
                    }else{
                        document.getElementById("show_driver_modal").style.border="";
                    }
                    if(typeof(err_log.vehicle_id)!=='undefined'){
                        document.getElementById("nomor_kendaraan_modal").style.border = "1px solid red";
                    }else{
                        document.getElementById("nomor_kendaraan_modal").style.border="";
                    }
                }
            }
        });
    });
    $('#update_reject_request').on('click',function(){
        var id_request=$('#alasan_reject_id').val();
        var alasan_reject=$('#alasan_reject_alternative').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.reject_car_request')}}",
            data: {
                id_request:id_request,
                alasan_reject:alasan_reject,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                swal("", "Permintaan transportasi di update", "success");
                $('#alternativeModal').modal('hide');
                dataTableReload();
            },
            error: function(error){
                let err_log=error.responseJSON.errors;
                if(error.status==422){
                    if(typeof(err_log.alasan_reject)!=='undefined'){
                        document.getElementById("alasan_reject_alternative").style.border = "1px solid red";
                    }else{
                        document.getElementById("alasan_reject_alternative").style.border="";
                    }
                }
            }
        });
    });
    $('#reject_this_request').on('click',function(){
        var id_request=$('#id_request_2').val();
        var alasan_reject=$('#alasan_reject').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.reject_car_request')}}",
            data: {
                id_request:id_request,
                alasan_reject:alasan_reject,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                swal("", "Permintaan transportasi di reject", "success");
                $('#rejectModal').modal('hide');
                dataTableReload();
            },
            error: function(error){
                let err_log=error.responseJSON.errors;
                if(error.status==422){
                    if(typeof(err_log.alasan_reject)!=='undefined'){
                        document.getElementById("alasan_reject").style.border = "1px solid red";
                    }else{
                        document.getElementById("alasan_reject").style.border="";
                    }
                }
            }
        });
    });
    function dataTableReload() {
        datatable.ajax.reload();
    }
</script>
@endsection