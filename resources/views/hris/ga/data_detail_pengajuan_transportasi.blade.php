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
            <a class="btn btn-white" href="{{route('hris.ga.data_pengajuan_transportasi')}}">Data</a>
        </li>
        <li class="nav-item">
            <a class="btn btn-primary" style="background-color:blue" href="#">Detail Pengajuan Transportasi</a>
        </li>
    </ul>
</div>
<div class="card-body px-6 py-4" style="border: 1px solid #d8d4dc">
    @foreach ($pengajuan_transportasi as $value)
    <div class="row py-1">
        <div class="col-6">
            <div class="row">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Tanggal Pengajuan</label>
                </div>
                <div class="col-8">
                    <input type="hidden" value="{{$value->id}}" id="id_request">
                    <label style="font-size:12pt">: {{Carbon\Carbon::parse($value->created_at)->translatedFormat('l d F Y, H:i')}}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Nama Karyawan</label>
                </div>
                <div class="col-8">
                    <label style="font-size:12pt">: {{$value->employee_name}}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> NIK</label>
                </div>
                <div class="col-8">
                    <label style="font-size:12pt">: {{$value->nik}}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Keberangkatan Awal</label>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Provinsi</label>
                </div>
                <div class="col-8">
                    <label style="font-size:12pt">: {{$value->nama_provinsi}}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Kota</label>
                </div>
                <div class="col-8">
                    <label style="font-size:12pt">: {{$value->nama_kota}}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Kecamatan</label>
                </div>
                <div class="col-8">
                    <label style="font-size:12pt">: {{$value->nama_kecamatan}}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Desa</label>
                </div>
                <div class="col-8">
                    <label style="font-size:12pt">: {{$value->nama_desa}}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Detail Alamat</label>
                </div>
                <div class="col-8">
                    <label style="font-size:12pt">: {{$value->detail_alamat}}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Waktu Pemberangkatan</label>
                </div>
                <div class="col-8">
                    <label style="font-size:12pt">: {{Carbon\Carbon::parse($value->tanggal_pemberangkatan)->translatedFormat('l, d F Y')}}, {{Carbon\Carbon::parse($value->jam_pemberangkatan)->translatedFormat('H:i')}}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Tujuan Pemberangkatan</label>
                </div>
                <div class="col-8">
                    <label style="font-size:12pt">: {{ucfirst(str_replace('_', ' ', $value->tujuan_pemberangkatan))}}</label>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="row pb-1">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Nama Department</label>
                </div>
                <div class="col-8">
                    <label style="font-size:12pt">: {{$value->department_name}}</label>
                </div>
            </div>
            <div class="row pb-1">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Nama Bagian</label>
                </div>
                <div class="col-8">
                   <label style="font-size:12pt">: {{$value->sub_dept_name}}</label>
                </div>
            </div>
            <div class="row pb-2">
                <div class="col-4 pt-1">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Tujuan</label>
                </div>
                <div class="col-8 pt-1">
                    <button class="btn btn-primary py-1" id="tujuan_lainnya" data-id="{{$value->id}}"> Tujuan Lainnya</button></label>
                </div>
            </div>
            <div class="row pb-1">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Provinsi</label>
                </div>
                <div class="col-8">
                   <label style="font-size:12pt">: {{$value->nama_provinsi_tujuan}}</label>
                </div>
            </div>
            <div class="row pb-1">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Kota</label>
                </div>
                <div class="col-8">
                   <label style="font-size:12pt">: {{$value->nama_kota_tujuan}}</label>
                </div>
            </div>
            <div class="row pb-1">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Kecamatan</label>
                </div>
                <div class="col-8">
                   <label style="font-size:12pt">: {{$value->nama_kecamatan_tujuan}}</label>
                </div>
            </div>
            <div class="row pb-1">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Desa</label>
                </div>
                <div class="col-8">
                   <label style="font-size:12pt">: {{$value->nama_desa_tujuan}}</label>
                </div>
            </div>
            <div class="row pb-1">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Detail Alamat</label>
                </div>
                <div class="col-8">
                   <label style="font-size:12pt">: {{$value->detail_alamat_tujuan}}</label>
                </div>
            </div>
            <div class="row pb-1">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Waktu Kedatangan</label>
                </div>
                <div class="col-8">
                   <label style="font-size:12pt">: {{Carbon\Carbon::parse($value->tanggal_kedatangan)->translatedFormat('l, d F Y')}}, {{Carbon\Carbon::parse($value->jam_kedatangan)->translatedFormat('H:i')}}</label>
                </div>
            </div>
            <div class="row pb-1">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Jarak Tempuh</label>
                </div>
                <div class="col-8">
                   <label style="font-size:12pt">: {{$value->jarak_tempuh}} Km</label>
                </div>
            </div>
        </div>
    </div>
    
    @if (str_contains($value->tujuan_pemberangkatan, 'antar_barang') || str_contains($value->tujuan_pemberangkatan, 'jemput_barang'))
    <div class="row pb-1 pl-1 border-left-0 border-right-0 border-bottom-0 border-dark-0 border border-secondary">
        <div class="col-6">
            <div class="row">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Jenis Barang</label>
                </div>
                <div class="col-8">
                    <label style="font-size:12pt">: {{$value->jenis_barang}}</label>
                </div>
            </div>
            <div class="row pb-1">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Quantity</label>
                </div>
                <div class="col-8">
                    <label style="font-size:12pt">: {{$value->quantity}}</label> <label style="font-size:12pt"> {{$value->satuan}}</label>
                </div>
            </div>
            <div class="row pb-1">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Keterangan Barang</label>
                </div>
                <div class="col-8">
                    <label style="font-size:12pt">: {{$value->keterangan_barang}}</label>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="row">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Instansi</label>
                </div>
                <div class="col-8">
                    <label style="font-size:12pt">: {{$value->nama_instansi}}</label>
                </div>
            </div>
            <div class="row pb-1">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Nama Penerima</label>
                </div>
                <div class="col-8">
                    <label style="font-size:12pt">: {{$value->nama_penerima}}</label>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if (str_contains($value->tujuan_pemberangkatan, 'antar_tamu') || str_contains($value->tujuan_pemberangkatan, 'jemput_tamu') )
    <div class="row pb-1 pl-0 border-left-0 border-right-0 border-bottom-0 border-dark-0 border border-secondary">
        <div class="col-6">
            <div class="row">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Nama Tamu</label>
                </div>
                <div class="col-8">
                    <label style="font-size:12pt">: {{$value->nama_tamu}}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Instansi Tamu</label>
                </div>
                <div class="col-8">
                    <label style="font-size:12pt">: {{$value->instansi_tamu}}</label>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="row">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Nomor Hp Tamu</label>
                </div>
                <div class="col-8">
                    <label style="font-size:12pt">: {{$value->nomor_hp_tamu}}</label>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    @if (str_contains($value->tujuan_pemberangkatan, 'antar_dinas') || str_contains($value->tujuan_pemberangkatan, 'jemput_dinas'))
    <div class="row pb-1 pl-3 border-left-0 border-right-0 border-bottom-0 border-dark-0 border border-secondary">
        <div class="col-6 pl-0">
            <div class="row">
                <div class="col-4">
                    <label class="form-label" style="font-weight: bold;font-size:12pt"> Karyawan yang dinas luar</label>
                </div>
                <div class="col-8">
                    <label style="font-size:12pt">: {{ucwords(strtolower($nama_karyawan_dinas_luar))}}</label>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if($id_user==4241 || $id_user==20 || $id_user==17 || $id_user==7765 || $id_user==5321 || $id_user==6083 || $id_user==6081 || $id_user==6713)
    <div class="row pb-1">
        <div class="col-2">
            <label class="form-label" style="font-weight: bold;font-size:12pt"> Pilih Status</label>
        </div>
        <div class="col-4">
            <select class="form-control col-10" id="value_status" style="background-color: white" onchange="perubahanan_status()">\
                <option value=0 @if ($value->status == 0) {{ 'selected' }} @endif>Pilih Status</option>\
                <option value=1 @if ($value->status == 1) {{ 'selected' }} @endif>Approved</option>\
                <option value=2 @if ($value->status == 2) {{ 'selected' }} @endif>Alternative</option>\
                <option value=3 @if ($value->status == 3) {{ 'selected' }} @endif>On The Way</option>\
                <option value=4 @if ($value->status == 4) {{ 'selected' }} @endif>Done</option>\
                <option value=6 @if ($value->status == 5) {{ 'selected' }} @endif>Late</option>\
                <option value=5 @if ($value->status == 6) {{ 'selected' }} @endif>Cancel</option>\
            </select>
        </div>
    </div>
    <div id="status_approved_tag">
        <div class="row">
            <div class="col-2">
                <label class="form-label" style="font-weight: bold;font-size:12pt"> Driver</label>
            </div>
            <div class="col-4">
                <select class="form-control col-10" style="background-color: white" id="driver">
                    <option value="">Pilih Driver</option>
                    @foreach ($drivers as $drive)
                        <option value="{{ $drive->enroll_id }}" {{ ( $drive->enroll_id == $value->id_driver) ? 'selected' : '' }}> {{ $drive->employee_name }} </option>
                    @endforeach
                </select>
            </div>
            <div class="col-2">
                <label class="form-label" style="font-weight: bold;font-size:12pt"> Kendaraan</label>
            </div>
            <div class="col-4">
                <select class="form-control col-10" style="background-color: white" id="vehicle">
                    <option value="">Pilih Kendaraan</option>
                    @foreach ($vehicles as $v)
                        <option value="{{$v->id}}"{{ ( $v->id == $value->nomor_kendaraan) ? 'selected' : '' }}>{{$v->plat_no}} || {{$v->merk}} {{$v->tipe}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div id="status_alternative_tag">
        <div class="row pl-0 pt-1">
            <div class="col-2">
                <label class="form-label" style="font-weight: bold;font-size:12pt"> Alternative</label>
            </div>
            <div class="col-4">
                <textarea id="status_alternative" class="form-control col-10" style="background-color: white">{{$value->alasan_status}}</textarea>
            </div>
        </div>
    </div>
    <div class="row pt-2">
        <div class="col-12 text-center">
            <a class="btn" style="background-color:rgb(236, 165, 32);color:white" href="{{route('hris.ga.data_pengajuan_transportasi')}}">Back</a>
            <button class="btn btn-success" id="approve_this_request">Save Changes</button>
        </div>
    </div>
    @else
    <div class="row pb-1 pl-0">
        <div class="col-2">
            <label class="form-label" style="font-weight: bold;font-size:12pt"> Status</label>
        </div>
        <div class="col-4">
            @if($value->status==0)
                <h6 style="font-size:11pt;color:grey;font-weight:bold">: PENDING</h6>
            @elseif($value->status==1)
                <h6 style="font-size:11pt;color:green;font-weight:bold">: APPROVED</h6>
            @elseif($value->status==2)
                <h6 style="font-size:11pt;color:red;font-weight:bold">: Alternative</h6>
            @elseif($value->status==3)
                <h6 style="font-size:11pt;color:green;font-weight:bold">: ON THE WAY</h6>
            @elseif($value->status==4)
                <h6 style="font-size:11pt;color:green;font-weight:bold">: DONE</h6>
            @elseif($value->status==6)
                <h6 style="font-size:11pt;color:orange;font-weight:bold">: LATE</h6>
            @elseif($value->status==5)
                <h6 style="font-size:11pt;color:red;font-weight:bold">: CANCEL</h6>
            @endif
        </div>
    </div>
    <div class="row pl-0">
        <div class="col-2">
            <label class="form-label" style="font-weight: bold;font-size:12pt"> Driver</label>
        </div>
        <div class="col-4">
            <label style="font-size:12pt">: {{$value->id_driver}}</label>
        </div>
        <div class="col-2">
            <label class="form-label" style="font-weight: bold;font-size:12pt"> Kendaraan</label>
        </div>
        <div class="col-4">
            <label style="font-size:12pt">: {{$value->nomor_kendaraan}}</label>
        </div>
    </div>
    <div class="row pl-0">
        <div class="col-2">
            <label class="form-label" style="font-weight: bold;font-size:12pt"> Alternative</label>
        </div>
        <div class="col-4">
            <label style="font-size:12pt">: {{$value->alasan_status}}</label>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center">
            <a class="btn" style="background-color:rgb(236, 165, 32);color:white" href="{{route('hris.ga.data_pengajuan_transportasi')}}">Back</a>
        </div>
    </div>
    @endif
    @endforeach
</div>
<div class="modal fade" id="tujuanLainnyaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 94%">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                <button aria-label="Close" data-dismiss="modal" style="background-color: rgb(255, 255, 255)"><span class="fa fa-times"></span></button>
            </div>
            <div class="modal-body" style="height:1000px">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="font-weight: bold">No</th>
                            <th style="font-weight: bold">Provinsi</th>
                            <th style="font-weight: bold">Kota</th>
                            <th style="font-weight: bold">Kecamatan</th>
                            <th style="font-weight: bold">Desa</th>
                            <th style="font-weight: bold">Detail Alamat</th>
                            <th style="font-weight: bold">Waktu Kedatangan</th>
                            <th style="font-weight: bold">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="another_route">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row py-2">
                    <div class="col-12 text-center">
                        <label class="form-label" style="font-size:12pt;font-weight:bold"> Setuju permintaan kendaraan</label>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-12 text-center">
                        <input type="hidden" id="id_request_modal">
                        <button class="btn btn-success btn-sm py-1" id="approve_this_request">Ya</button>
                        <button class="btn btn-light btn-sm py-1" data-dismiss="modal">Batal</button>
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
            <div class="modal-body">
                <div class="row py-2">
                    <div class="col-5 pl-6 pt-2">
                        <h6 style="font-size:12pt;">Alasan Reject</h6>
                    </div>
                    <div class="col-7 pr-5">
                        <textarea id="alasan_reject" class="form-control" style="background-color: white"></textarea>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-12 text-center">
                        <input type="hidden" id="id_request_reject">
                        <button class="btn btn-success btn-sm py-1" id="reject_this_request">Ya</button>
                        <button class="btn btn-light btn-sm py-1" data-dismiss="modal">Batal</button>
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
    var first_value_status=$('#value_status').val();
    $(document).ready(function() {
        console.log(first_value_status);
        if(first_value_status==2){
            document.getElementById('status_approved_tag').style.display='none';
            document.getElementById('status_alternative_tag').style.display='block';
        }else{
            if(first_value_status==0){
                document.getElementById('status_approved_tag').style.display='none';
                document.getElementById('status_alternative_tag').style.display='none';
            }else{
                document.getElementById('status_approved_tag').style.display='block';
                document.getElementById('status_alternative_tag').style.display='none';
            }
        }
    });
    function perubahanan_status(){
        var value_status=$('#value_status').val();
        if(value_status==2){
            document.getElementById('status_approved_tag').style.display='none';
            document.getElementById('status_alternative_tag').style.display='block';
            $('#driver').val('');
            $('#vehicle').val('');
            document.getElementById("driver").style.border="";
            document.getElementById("vehicle").style.border="";
            document.getElementById("value_status").style.border="";
        }else{
            if(value_status==0){
                document.getElementById('status_approved_tag').style.display='none';
                document.getElementById('status_alternative_tag').style.display='none';
                document.getElementById("driver").style.border="";
                document.getElementById("vehicle").style.border="";
                document.getElementById("status_alternative").style.border="";
                document.getElementById("value_status").style.border="";
                $('#status_alternative').val('');
                $('#driver').val('');
                $('#vehicle').val('');
            }else{
                document.getElementById('status_approved_tag').style.display='block';
                document.getElementById('status_alternative_tag').style.display='none';
                document.getElementById("status_alternative").style.border="";
                $('#status_alternative').val('');
                document.getElementById("value_status").style.border="";
            }
        }
    }
    $('#tujuan_lainnya').on('click',function(){
        $('#another_route').empty();
        var id=$(this).data('id');
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.show_another_route')}}",
            data: {
                id:id,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#tujuanLainnyaModal').modal('show');
                jQuery.each(res, function(key,value){
                    $('#another_route').append('<tr>\
                        <td>'+(key+1)+'</td>\
                        <td>'+value['prov_name']+'</td>\
                        <td>'+value['city_name']+'</td>\
                        <td>'+value['dis_name']+'</td>\
                        <td>'+value['subdis_name']+'</td>\
                        <td>'+value['detail_alamat']+'</td>\
                        <td>'+new Date(value['tanggal_kedatangan']).toLocaleDateString('id-ID', { weekday: 'long', year:"numeric", month:"long", day:"numeric"})+' - '+value['jam_kedatangan']+'</td>\
                        <td>'+value['keterangan']+'</td>\
                    </tr>');
                });
            },
            error: function(error){
                swal("", "Tampilkan rute lain gagal", "error");
            }
        });
    });
    $('#update_request').on('click',function(){
        var id=$('#id_request').val();
        var driver=$('#driver').val();
        var vehicle=$('#vehicle').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.update_car_request')}}",
            data: {
                id:id,
                driver:driver,
                vehicle_id:vehicle,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                swal("", "Permintaan transportasi di update", "success");
            },
            error: function(error){
                swal("", "Permintaan transportasi gagal di update", "error");
            }
        });
    });
    $('#approve_request').on('click',function(){
        var id=$(this).data('id');
        var driver=$('#driver').val();
        var vehicle_id=$('#vehicle').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.check_car_request')}}",
            data: {
                driver:driver,
                vehicle_id:vehicle_id
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#id_request_modal').val(id);
                $('#approveModal').modal('show');
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
                        document.getElementById("vehicle").style.border = "1px solid red";
                    }else{
                        document.getElementById("vehicle").style.border="";
                    }
                }
            }
        });
    });
    $('#approve_this_request').on('click',function(){
        var id_request=$('#id_request').val();
        var status=$('#value_status').val();
        var driver=$('#driver').val();
        var vehicle=$('#vehicle').val();
        var alternative=$('#status_alternative').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.approve_this_car_request')}}",
            data: {
                id_request:id_request,
                status:status,
                driver:driver,
                vehicle:vehicle,
                alternative:alternative
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                swal("", "Permintaan transportasi di approve", "success");
                location.reload();
            },
            error: function(error){
                let err_log=error.responseJSON.errors;
                if(error.status==422){
                    if(typeof(err_log.status)!=='undefined'){
                        document.getElementById("value_status").style.border = "1px solid red";
                    }else{
                        document.getElementById("value_status").style.border="";
                    }
                    if(typeof(err_log.alternative)!=='undefined'){
                        document.getElementById("status_alternative").style.border = "1px solid red";
                    }else{
                        document.getElementById("status_alternative").style.border="";
                    }
                    if(typeof(err_log.driver)!=='undefined'){
                        document.getElementById("driver").style.border = "1px solid red";
                    }else{
                        document.getElementById("driver").style.border="";
                    }
                    if(typeof(err_log.vehicle)!=='undefined'){
                        document.getElementById("vehicle").style.border = "1px solid red";
                    }else{
                        document.getElementById("vehicle").style.border="";
                    }
                }
            }
        });
    });
    $('#reject_request').on('click',function(){
        var id=$(this).data('id');
        $('#id_request_reject').val(id);
        $('#rejectModal').modal('show');
    });
    $('#reject_this_request').on('click',function(){
        var id_request=$('#id_request_reject').val();
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
                location.reload();
            },
            error: function(error){
                swal("", "Permintaan transportasi gagal di reject", "success");
            }
        });
    });
    $('#driver').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("driver").style.border="";
        }
    });
    $('#vehicle').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("vehicle").style.border="";
        }
    });
</script>
@endsection