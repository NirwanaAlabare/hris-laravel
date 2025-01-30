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

<div class="card-body px-6 py-4" style="border: 1px solid #d8d4dc">
    <div class="row">
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
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
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
                <div class="row py-2">
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
                <div class="row py-2 bg-light text-dark">
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
    <div class="modal-dialog modal-dialog-scrollable">
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
                <div class="row py-2">
                    <div class="col-5 pl-6 pt-2">
                        <h6 style="font-size:12pt;">Alasan Reject</h6>
                    </div>
                    <div class="col-7 pr-5">
                        <textarea id="alasan_reject" class="form-control" style="background-color: white"></textarea>
                    </div>
                </div>
                <div class="row pt-2">
                    <div class="col-12 text-center">
                        <button class="btn btn-danger btn-sm py-1" id="reject_this_request">REJECT</button>
                        <button class="btn btn-light btn-sm py-1" data-dismiss="modal">CANCEL</button>
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
    $(document).ready(function() {
        $('#datatable').DataTable();
    });
    
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
    let datatable = $("#datatable").DataTable({
        ordering: false,
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
                    if (row.user==4241 || row.user==20 || row.user==5321 || row.user==17 || row.user==7765 || row.user==6083){
                        if(row.created_by==row.user){
                            if(row.status==null){
                                return `<button onclick="edit_detail(` + row.id + `)" class="btn btn-sm btn-block py-0 px-1 mb-1" style="font-size:10pt;background-color:orange;color:white">EDIT</button><button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-sm py-0 px-1 mb-1 " style="font-size:10pt">LIHAT DETAIL</button><br><a class='btn btn-success py-0 px-2 btn-sm text-white' style='font-size:9pt' data-toggle="modal" data-target="#approveModal" ' onclick="approve_function(` + row.id + `,'` + row.employee_name + `','` + row.department_name + `','` + row.detail_alamat + `','` + row.desa + `','` + row.detail_alamat_tujuan + `','` + row.desa_tujuan + `')"> APPROVE </a><br><a class='btn btn-danger py-0 mt-1 btn-sm text-white' style='font-size:9pt' data-toggle="modal" data-target="#rejectModal" ' onclick="reject_function(` + row.id + `,'` + row.employee_name + `','` + row.department_name + `','` + row.detail_alamat + `','` + row.desa + `','` + row.detail_alamat_tujuan + `','` + row.desa_tujuan + `')"> REJECT </a>`;
                            }else{
                                if(row.status==1){
                                    return `<h6 style="font-size:11pt;color:green">APPROVED</h6><button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-sm py-0 px-1 mb-1" style="font-size:10pt">LIHAT DETAIL</button><button onclick="print_penugasan(` + row.id + `)" class="btn btn-success btn-sm px-1 py-0" style="font-size:10pt">FORM PENUGASAN</button>`;
                                }else{
                                    return `<h6 style="font-size:11pt;color:red">REJECTED</h6><button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-sm py-0 px-1 mb-1" style="font-size:10pt">LIHAT DETAIL</button>`;
                                }
                            }
                        }else{
                            if(row.status==null){
                                return `<button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-sm py-0 px-1 mb-1 " style="font-size:10pt">LIHAT DETAIL</button><br><a class='btn btn-success py-0 px-2 btn-sm text-white' style='font-size:9pt' data-toggle="modal" data-target="#approveModal" ' onclick="approve_function(` + row.id + `,'` + row.employee_name + `','` + row.department_name + `','` + row.detail_alamat + `','` + row.desa + `','` + row.detail_alamat_tujuan + `','` + row.desa_tujuan + `')"> APPROVE </a><br><a class='btn btn-danger py-0 mt-1 btn-sm text-white' style='font-size:9pt' data-toggle="modal" data-target="#rejectModal" ' onclick="reject_function(` + row.id + `,'` + row.employee_name + `','` + row.department_name + `','` + row.detail_alamat + `','` + row.desa + `','` + row.detail_alamat_tujuan + `','` + row.desa_tujuan + `')"> REJECT </a>`;
                            }else{
                                if(row.status==1){
                                    return `<h6 style="font-size:11pt;color:green">APPROVED</h6><button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-sm py-0 px-1 mb-1" style="font-size:10pt">LIHAT DETAIL</button><button onclick="print_penugasan(` + row.id + `)" class="btn btn-success btn-sm px-1 py-0" style="font-size:10pt">FORM PENUGASAN</button>`;
                                }else{
                                    return `<h6 style="font-size:11pt;color:red">REJECTED</h6><button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-sm py-0 px-1 mb-1" style="font-size:10pt">LIHAT DETAIL</button>`;
                                }
                            }
                        }
                    }else{
                        if(row.status==null){
                            return `<button onclick="edit_detail(` + row.id + `)" class="btn btn-sm btn-block py-0 px-1 mb-1" style="font-size:10pt;background-color:orange;color:white">EDIT</button>PENDING`;
                        }else{
                            if(row.status==1){
                                return `<h6 style="font-size:11pt;color:green">APPROVED</h6><button onclick="print_penugasan(` + row.id + `)" class="btn btn-success btn-sm p-0" style="font-size:10pt">FORM PENUGASAN</button>`;
                            }else{
                                return '<h6 style="font-size:11pt;color:red">REJECTED</h6>';
                            }
                        }
                    }
                }
            },
        ]
    });
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
    function approve_function(id,employee_name,department_name,detail_alamat,desa,detail_alamat_tujuan,desa_tujuan){
        $('#id_request').val(id);
        $("#nama_karyawan_modal").html(employee_name);
        $("#department_modal").html(department_name);
        $("#detail_alamat_modal").html(detail_alamat);
        $("#city_modal").html(desa);
        $("#detail_alamat_tujuan_modal").html(detail_alamat_tujuan);
        $("#city_tujuan_modal").html(desa_tujuan);
    }
    function reject_function(id,employee_name,department_name,detail_alamat,desa,detail_alamat_tujuan,desa_tujuan){
        $('#id_request_2').val(id);
        $("#nama_karyawan_modal_2").html(employee_name);
        $("#department_modal_2").html(department_name);
        $("#detail_alamat_modal_2").html(detail_alamat);
        $("#city_modal_2").html(desa);
        $("#detail_alamat_tujuan_modal_2").html(detail_alamat_tujuan);
        $("#city_tujuan_modal_2").html(desa_tujuan);
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
                swal("", "Permintaan transportasi gagal di reject", "success");
            }
        });
    });
    function dataTableReload() {
        datatable.ajax.reload();
    }
</script>
@endsection