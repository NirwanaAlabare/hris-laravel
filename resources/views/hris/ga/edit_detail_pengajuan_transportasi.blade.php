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
            <a class="btn btn-primary" href="#">Edit Pengajuan Transportasi</a>
        </li>
    </ul>
</div>
<div class="card-body px-6 py-4" style="border: 1px solid #d8d4dc">
    @foreach ($pengajuan_transportasi as $value)
    <div class="row py-1">
        <div class="col-2">
            <label class="form-label" style="font-weight: bold;font-size:12pt"> Tanggal Pengajuan</label>
        </div>
        <div class="col-4">
            <input type="hidden" value="{{$value->created_by}}" id="user_login">
            <input type="hidden" value="{{$value->id}}" id="id_request">
            <label style="font-size:12pt">: {{Carbon\Carbon::parse($value->created_at)->translatedFormat('l d F Y, H:i')}}</label>
        </div>
    </div>
    <div class="row pb-1">
        <div class="col-2">
            <label class="form-label" style="font-weight: bold;font-size:12pt"> Nama Karyawan</label>
        </div>
        <div class="col-4">
           <label style="font-size:12pt">: {{$value->employee_name}}</label>
        </div>
        <div class="col-2">
            <label class="form-label" style="font-weight: bold;font-size:12pt"> Nama Department</label>
        </div>
        <div class="col-4">
            <label style="font-size:12pt">: {{$value->department_name}}</label>
        </div>
    </div>
    <div class="row pb-1" style="border-bottom: 1px solid grey">
        <div class="col-2">
            <label class="form-label" style="font-weight: bold;font-size:12pt"> NIK</label>
        </div>
        <div class="col-4">
           <label style="font-size:12pt">: {{$value->nik}}</label>
        </div>
        <div class="col-2">
            <label class="form-label" style="font-weight: bold;font-size:12pt"> Nama Bagian</label>
        </div>
        <div class="col-4">
           <label style="font-size:12pt">: {{$value->sub_dept_name}}</label>
        </div>
    </div>
    <div class="row pb-1 pt-2">
        <div class="col-6 pt-1">
            <label class="form-label" style="font-weight: bold;font-size:12pt"> Keberangkatan Awal</label>
        </div>
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold;font-size:12pt"> Tujuan</label>
        </div>
        <div class="col-4 pt-1">
            <button class="btn btn-primary py-1" id="tujuan_lainnya" data-id="{{$value->id}}"> Daftar Tujuan</button></label>
        </div>
    </div>
    <div class="row pb-1">
        <div class="col-2">
            <label class="form-label" style="font-weight: bold;font-size:12pt"> Provinsi</label>
        </div>
        <div class="col-4">
            <select class="form-control col-10" id="provinsi" style="background-color: white">
                @foreach($provincies as $prov)
                    <option value="{{$prov->prov_id}}" {{ ( $prov->prov_id == $value->prov_id) ? 'selected' : '' }}>{{$prov->prov_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-2">
            <label class="form-label" style="font-weight: bold;font-size:12pt"> Provinsi</label>
        </div>
        <div class="col-4">
            <select class="form-control col-10" id="provinsi_2" style="background-color: white">
                @foreach($provincies as $prov)
                    <option value="{{$prov->prov_id}}" {{ ( $prov->prov_id == $value->prov_tujuan) ? 'selected' : '' }}>{{$prov->prov_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row pb-1">
        <div class="col-2">
            <label class="form-label" style="font-weight: bold;font-size:12pt"> Kabupaten/Kota</label>
        </div>
        <div class="col-4">
            <select class="form-control col-10" id="cities" style="background-color: white">
                @foreach($cities as $city)
                    <option value="{{$city->city_id}}" {{ ($city->city_id == $value->city_id) ? 'selected' : '' }}>{{$city->city_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-2">
            <label class="form-label" style="font-weight: bold;font-size:12pt"> Kabupaten/Kota</label>
        </div>
        <div class="col-4">
            <select class="form-control col-10" id="cities_2" style="background-color: white">
                @foreach($cities_2 as $city)
                    <option value="{{$city->city_id}}" {{ ( $city->city_id == $value->city_tujuan) ? 'selected' : '' }}>{{$city->city_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row pb-1">
        <div class="col-2">
            <label class="form-label" style="font-weight: bold;font-size:12pt"> Kecamatan</label>
        </div>
        <div class="col-4">
            <select class="form-control col-10" id="districts" style="background-color: white">
                @foreach($districts as $dis)
                    <option value="{{$dis->dis_id}}" {{ ( $dis->dis_id == $value->dis_id) ? 'selected' : '' }}>{{$dis->dis_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-2">
            <label class="form-label" style="font-weight: bold;font-size:12pt"> Kecamatan</label>
        </div>
        <div class="col-4">
            <select class="form-control col-10" id="districts_2" style="background-color: white">
                @foreach($districts_2 as $dis)
                    <option value="{{$dis->dis_id}}" {{ ( $dis->dis_id == $value->dis_tujuan) ? 'selected' : '' }}>{{$dis->dis_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row pb-1">
        <div class="col-2">
            <label class="form-label" style="font-weight: bold;font-size:12pt"> Kelurahan/Desa</label>
        </div>
        <div class="col-4">
            <select class="form-control col-10" id="sub_districts" style="background-color: white">
                @foreach($subdistricts as $val)
                    <option value="{{$val->subdis_id}}" {{ ( $val->subdis_id == $value->id_desa) ? 'selected' : '' }}>{{$val->subdis_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-2">
            <label class="form-label" style="font-weight: bold;font-size:12pt"> Kelurahan/Desa</label>
        </div>
        <div class="col-4">
            <select class="form-control col-10" id="sub_districts_2" style="background-color: white">
                @foreach($subdistricts_2 as $subdis)
                    <option value="{{$subdis->subdis_id}}" {{ ( $subdis->subdis_id == $value->id_desa_tujuan) ? 'selected' : '' }}>{{$subdis->subdis_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row pb-2">
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold;font-size:12pt">Detail Alamat</label>
        </div>
        <div class="col-4">
            <textarea id="detail_alamat" rows="2" cols="7" class="form-control col-10" style="background-color: white">{{$value->detail_alamat}}</textarea>
        </div>
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold;font-size:12pt">Detail Alamat</label>
        </div>
        <div class="col-4">
            <textarea id="detail_alamat_2" rows="2" cols="7" class="form-control col-10" style="background-color: white">{{$value->detail_alamat_tujuan}}</textarea>
        </div>
    </div>
    <div class="row pb-2">
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold;font-size:12pt">Tanggal & Jam Pemberangkatan</label>
        </div>
        <div class="col-2">
            <input type="date" id="tanggal_pemberangkatan" class="form-control" style="background-color: white" value="{{$value->tanggal_pemberangkatan}}">
        </div>
        <div class="col-2">
            <input class="form-control col-8" id="jam_pemberangkatan" name="jam_pemberangkatan" type="text" style="background-color: white; cursor:pointer;" value="{{substr($value->jam_pemberangkatan,0,5)}}">
        </div>
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold;font-size:12pt">Tanggal & Jam Kedatangan</label>
        </div>
        <div class="col-2">
            <input type="date" id="tanggal_kedatangan" class="form-control" style="background-color: white" value="{{$value->tanggal_kedatangan}}">
        </div>
        <div class="col-2">
            <input class="form-control col-8" id="jam_kedatangan" name="jam_kedatangan" type="text" style="background-color: white; cursor:pointer;" value="{{substr($value->jam_kedatangan,0,5)}}">
        </div>
    </div>
    <div class="row pb-2">
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold;font-size:12pt">Tujuan Pemberangkatan</label>
        </div>
        <div class="col-4 pl-4">
            <div class="row">
                <div class="col-xl-4">
                  <input type="checkbox" name="tujuan_pemberangkatan" value="antar_tamu" id="checkbox_1" {{ str_contains($value->tujuan_pemberangkatan, 'antar_tamu') ? 'checked' : '' }}>&nbsp;Antar tamu
                </div>
                <div class="col-xl-4">
                  <input type="checkbox" name="tujuan_pemberangkatan" value="antar_barang" id="checkbox_3"{{ str_contains($value->tujuan_pemberangkatan, 'antar_barang') ? 'checked' : '' }}>&nbsp;Antar barang
                </div>
                <div class="col-xl-3">
                  <input type="checkbox" name="tujuan_pemberangkatan" value="antar_dinas" id="checkbox_5"{{ str_contains($value->tujuan_pemberangkatan, 'antar_dinas') ? 'checked' : '' }}>&nbsp;Antar dinas
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4">
                    <input type="checkbox" name="tujuan_pemberangkatan" value="jemput_tamu" id="checkbox_2" {{ str_contains($value->tujuan_pemberangkatan, 'jemput_tamu') ? 'checked' : '' }}>&nbsp;Jemput tamu
                </div>
                <div class="col-xl-4">
                    <input type="checkbox" name="tujuan_pemberangkatan" value="jemput_barang" id="checkbox_4" {{ str_contains($value->tujuan_pemberangkatan, 'jemput_barang') ? 'checked' : '' }}>&nbsp;Jemput barang
                </div>
                <div class="col-xl-4">
                    <input type="checkbox" name="tujuan_pemberangkatan" value="jemput_dinas" id="checkbox_6" {{ str_contains($value->tujuan_pemberangkatan, 'jemput_dinas') ? 'checked' : '' }}>&nbsp;Jemput dinas
                </div>
            </div>
        </div>
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold;font-size:12pt">Jarak Tempuh</label>
        </div>
        <div class="col-1">
            <input type="number" id="jarak_tempuh" class="form-control" style="background-color: white" value="{{$value->jarak_tempuh}}">
        </div>
        <div class="col-2 pl-0 pt-2">
            <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:10pt">KM</label>
        </div>
    </div>
    @if (str_contains($value->tujuan_pemberangkatan, 'jemput_tamu')||str_contains($value->tujuan_pemberangkatan, 'antar_tamu'))
    <div style="display:block" id="tag_nama_tamu">
    @else
    <div style="display:none" id="tag_nama_tamu">
    @endif
        <div class="row pb-2 border-left-0 border-right-0 border-bottom-0 border-dark-0 border border-secondary">
            <div class="col-12 pt-1 pl-0">
                <label class="form-label" style="font-weight: bold;font-size:12pt">Keterangan Tamu</label>
            </div>
        </div>
        <div class="row pb-2">
            <div class="col-2 pt-1 pl-3">
                <label class="form-label" style="font-weight: bold;font-size:12pt"> Nama Tamu</label>
            </div>
            <div class="col-4">
                <input type="text" id="nama_tamu" class="form-control col-10" style="background-color: white" placeholder="Masukkan Nama Tamu" value="{{$value->nama_tamu}}">
            </div>
            <div class="col-2 pt-1">
                <label class="form-label" style="font-weight: bold;font-size:12pt"> Nomor Hp Tamu</label>
            </div>
            <div class="col-4">
                <input type="text" id="nomor_tamu" class="form-control col-10" style="background-color: white" placeholder="E.g. 089501940612" value="{{$value->nomor_hp_tamu}}">
            </div>
        </div>
        <div class="row pb-2">
            <div class="col-2 pt-1 pl-3">
                <label class="form-label" style="font-weight: bold;font-size:12pt"> Instansi</label>
            </div>
            <div class="col-4">
                <input type="text" id="instansi_tamu" class="form-control col-10" style="background-color: white" placeholder="Masukkan Instansi Tamu" value="{{$value->instansi_tamu}}">
            </div>
        </div>
    </div>
    @if (str_contains($value->tujuan_pemberangkatan, 'antar_barang')||str_contains($value->tujuan_pemberangkatan, 'jemput_barang'))
    <div style="display:block" id="tag_jenis_barang">
    @else
    <div style="display:none" id="tag_jenis_barang">
    @endif
        <div class="row pb-2 border-left-0 border-right-0 border-bottom-0 border-dark-0 border border-secondary">
            <div class="col-12 pt-1 pl-0">
                <label class="form-label" style="font-weight: bold;font-size:12pt">Keterangan Barang</label>
            </div>
        </div>
        <div class="row pb-2">
            <div class="col-6">
                <div class="row pb-2">
                    <div class="col-4 pt-1">
                        <label class="form-label" style="font-weight: bold;font-size:12pt">Jenis Barang</label>
                    </div>
                    <div class="col-8">
                        <input type="text" id="jenis_barang" class="form-control col-10 " style="background-color: white" placeholder="Masukkan Jenis Barang" value="{{$value->jenis_barang}}">
                    </div>
                </div>
                <div class="row pb-2">
                    <div class="col-4 pt-1">
                        <label class="form-label" style="font-weight: bold;font-size:12pt">Quantity</label>
                    </div>
                    <div class="col-2">
                        <input type="number" id="quantity" class="form-control" style="background-color: white" value="{{$value->quantity}}">
                    </div>
                    <div class="col-3 pl-0">
                        <input type="text" id="satuan" name="satuan" class="form-control" style="background-color: white" placeholder="Masukkan satuan" value="{{$value->satuan}}">
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="row pb-2">
                    <div class="col-4 pt-1">
                        <label class="form-label" style="font-weight: bold;font-size:12pt">Keterangan</label>
                    </div>
                    <div class="col-8">
                        <textarea id="keterangan_barang" rows="4" cols="7" class="form-control col-10" style="background-color: white">{{$value->keterangan_barang}}</textarea>
                    </div>
                </div>
                <div class="row pb-2">
                    <div class="col-4 pt-1">
                        <label class="form-label" style="font-weight: bold;font-size:12pt">Instansi</label>
                    </div>
                    <div class="col-8">
                        <input type="text" id="instansi" class="form-control col-10" style="background-color: white" placeholder="Masukkan Instansi" value="{{$value->nama_instansi}}">
                    </div>
                </div>
                <div class="row pb-2">
                    <div class="col-4 pt-1">
                        <label class="form-label" style="font-weight: bold;font-size:12pt">Nama</label>
                    </div>
                    <div class="col-8">
                        <input type="text" id="nama_instansi" class="form-control col-10" style="background-color: white" placeholder="Masukkan Nama" value="{{$value->nama_penerima}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (str_contains($value->tujuan_pemberangkatan, 'antar_dinas')||str_contains($value->tujuan_pemberangkatan, 'jemput_dinas'))
    <div style="display:block" id="tag_antar_dinas">
    @else
    <div style="display:none" id="tag_antar_dinas">
    @endif
        <div class="row pb-2 border-left-0 border-right-0 border-bottom-0 border-dark-0 border border-secondary">
            <div class="col-12 pl-0 pt-1">
                <label class="form-label" style="font-weight: bold;font-size:12pt">Keterangan Dinas</label>
            </div>
        </div>
        <div class="row pb-2">
            <div class="col-6 pl-3">
                <div class="row pb-2">
                    <div class="col-4 pt-1">
                        <label class="form-label" style="font-weight: bold;font-size:12pt">Karyawan Yang Dinas Luar</label>
                    </div>
                    <div class="col-8">
                        <input type="hidden" id="EmployeeDinas" value="{{$value->karyawan_dinas}}">
                        <select id="selectEmployeeID" name="selectEmployeeDinas[]" multiple class="form-control select2 EmployeeID col-11">
                            @foreach ($selectemployee as $r_empl)
                                <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                            @endforeach
                        </select>
                        <h6 id="warning_employee_dinas" style="margin-bottom: 0px;padding-top:1px;color:red"></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 text-center">
            <button class="btn btn-warning text-dark" id="save_edited_form">Save Changes</button>
        </div>
    </div>
    @endforeach
</div>
<div class="modal fade" id="tujuanLainnyaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 94%">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                <button aria-label="Close" style="background-color: rgb(255, 255, 255)" data-dismiss="modal"><span class="fa fa-times"></span></button>
            </div>
            <div class="modal-body" style="height:1000px">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Provinsi</th>
                                    <th>Kota/Kabupaten</th>
                                    <th>Kecamatan</th>
                                    <th>Kelurahan/Desa</th>
                                    <th>Detail Alamat</th>
                                    <th>Waktu Kedatangan</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="route_adding">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-center">
                        <button class="btn btn-success" id="save_edited_route">Save</button>
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
<script src="{{URL::asset('assets/js/timepicker.js') }}"></script>
<script>
    $("#jam_pemberangkatan").timepicker({
      timeFormat: "%H:%i"
    });
    $("#jam_kedatangan").timepicker({
      timeFormat: "%H:%i"
    });
    
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
        dropdownCssClass: 'hover-success',// disabling search
    });
    var array_tujuan_id=[];
    var array_provinsi=[];
    var array_kota=[];
    var array_kecamatan=[];
    var array_desa=[];
    var array_detail_alamat=[];
    var array_tanggal_kedatangan=[];
    var array_jam_kedatangan=[];
    var array_keterangan=[];
    $(document).ready(function() {
        pass_to_array();
        var arr_emp=$('#EmployeeDinas').val();
        $('#selectEmployeeID').val(arr_emp.split(",")).change();
    });
    function pass_to_array(){
        var id=$('#id_request').val();
        var provinsi=$('#provinsi_2').val();
        var city=$('#cities_2').val();
        var districts=$('#districts_2').val();
        var subdistricts=$('#sub_districts_2').val();
        var detail_alamat=$('#detail_alamat_2').val();
        var tanggal_kedatangan=$('#tanggal_kedatangan').val();
        var jam_kedatangan=$('#jam_kedatangan').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.add_another_route_2')}}",
            data: {
                id:id,
                provinsi:provinsi,
                city:city,
                districts:districts,
                subdistricts:subdistricts,
                detail_alamat:detail_alamat,
                tanggal_kedatangan:tanggal_kedatangan,
                jam_kedatangan:jam_kedatangan
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(data){
                jQuery.each(data, function(key,value){
                    array_tujuan_id.push(value.tujuan_id);
                    array_provinsi.push(value.provinsi);
                    array_kota.push(value.city);
                    array_kecamatan.push(value.district);
                    array_desa.push(value.subdistrict);
                    array_detail_alamat.push(value.detail_alamat);
                    array_tanggal_kedatangan.push(value.tanggal_kedatangan);
                    array_jam_kedatangan.push(value.jam_kedatangan);
                    array_keterangan.push(value.keterangan);
                });
            }
        });
    };
    $('#tujuan_lainnya').on('click',function(){
        array_keterangan[0]=$('#keterangan_barang').val();
        var id=$('#id_request').val();
        var provinsi=$('#provinsi_2').val();
        var city=$('#cities_2').val();
        var districts=$('#districts_2').val();
        var subdistricts=$('#sub_districts_2').val();
        var detail_alamat=$('#detail_alamat_2').val();
        var tanggal_kedatangan=$('#tanggal_kedatangan').val();
        var jam_kedatangan=$('#jam_kedatangan').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.add_another_route_2')}}",
            data: {
                id:id,
                provinsi:provinsi,
                city:city,
                districts:districts,
                subdistricts:subdistricts,
                detail_alamat:detail_alamat,
                tanggal_kedatangan:tanggal_kedatangan,
                jam_kedatangan:jam_kedatangan
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(data){
                $.ajax({
                    type:"POST",
                    url: "{{route('hris.ga.get_all_zone_name')}}",
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        subdis_id:subdistricts,
                        detail_alamat:detail_alamat,
                        tanggal_kedatangan:tanggal_kedatangan,
                        jam_kedatangan:jam_kedatangan,
                    },
                    success: function(res){
                        console.log(res);
                        array_provinsi[0]=res[0].prov_name;
                        array_kota[0]=res[0].city_name;
                        array_kecamatan[0]=res[0].dis_name;
                        array_desa[0]=res[0].subdis_name;
                        array_detail_alamat[0]=res[0].detail_alamat;
                        array_tanggal_kedatangan[0]=res[0].tanggal_kedatangan;
                        array_jam_kedatangan[0]=res[0].jam_kedatangan;
                    },
                    error: function(res){
                        swal({
                            title: "Ambil data Provinsi",
                            text: "Data provinsi gagal di ambil",
                            icon: "danger",
                        });
                    }
                });
                $('#tujuanLainnyaModal').modal('show');
                $('#route_adding').empty();
                addListTujuan();
                document.getElementById("tanggal_kedatangan").style.border="";
                document.getElementById("jam_kedatangan").style.border="";
                document.getElementById("provinsi_2").style.border="";
                document.getElementById("cities_2").style.border="";
                document.getElementById("districts_2").style.border="";
                document.getElementById("sub_districts_2").style.border="";
                document.getElementById("detail_alamat_2").style.border="";
            },
            error: function(error){
                let err_log=error.responseJSON.errors;
                if(error.status==422){
                    if(typeof(err_log.tanggal_kedatangan)!=='undefined'){
                        document.getElementById("tanggal_kedatangan").style.border = "1px solid red";
                    }else{
                        document.getElementById("tanggal_kedatangan").style.border="";
                    }
                    if(typeof(err_log.jam_kedatangan)!=='undefined'){
                        document.getElementById("jam_kedatangan").style.border = "1px solid red";
                    }else{
                        document.getElementById("jam_kedatangan").style.border="";
                    }
                    if(typeof(err_log.provinsi)!=='undefined'){
                        document.getElementById("provinsi_2").style.border = "1px solid red";
                    }else{
                        document.getElementById("provinsi_2").style.border="";
                    }
                    if(typeof(err_log.city)!=='undefined'){
                        document.getElementById("cities_2").style.border = "1px solid red";
                    }else{
                        document.getElementById("cities_2").style.border="";
                    }
                    if(typeof(err_log.districts)!=='undefined'){
                        document.getElementById("districts_2").style.border = "1px solid red";
                    }else{
                        document.getElementById("districts_2").style.border="";
                    }
                    if(typeof(err_log.subdistricts)!=='undefined'){
                        document.getElementById("sub_districts_2").style.border = "1px solid red";
                    }else{
                        document.getElementById("sub_districts_2").style.border="";
                    }
                    if(typeof(err_log.detail_alamat)!=='undefined'){
                        document.getElementById("detail_alamat_2").style.border = "1px solid red";
                    }else{
                        document.getElementById("detail_alamat_2").style.border="";
                    }
                }
            }
        });
        
    });
    function add_route_more(key,tujuan_id){
        var id=$('#tujuan_yang_ke_'+tujuan_id).val();
        var provinsi=$('#provinsi_yang_ke_'+tujuan_id).val();
        var city=$('#kota_yang_ke_'+tujuan_id).val();
        var districts=$('#kecamatan_yang_ke_'+tujuan_id).val();
        var subdistricts=$('#desa_yang_ke_'+tujuan_id).val();
        var detail_alamat=$('#detail_alamat_yang_ke_'+tujuan_id).val();
        var tanggal_kedatangan=$('#tanggal_kedatangan_yang_ke_'+tujuan_id).val();
        var jam_kedatangan=$('#jam_kedatangan_yang_ke_'+tujuan_id).val();
        var keterangan=$('#keterangan_yang_ke_'+tujuan_id).val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.add_another_route_3')}}",
            data: {
                id:id,
                provinsi:provinsi,
                city:city,
                districts:districts,
                subdistricts:subdistricts,
                detail_alamat:detail_alamat,
                tanggal_kedatangan:tanggal_kedatangan,
                jam_kedatangan:jam_kedatangan
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(data){
                array_tujuan_id.push(tujuan_id+1);
                array_provinsi.push('');
                array_kota.push('');
                array_kecamatan.push('');
                array_desa.push('');
                array_detail_alamat.push('');
                array_tanggal_kedatangan.push('');
                array_jam_kedatangan.push('');
                array_keterangan.push('');
                array_provinsi[key]=provinsi;
                array_kota[key]=city;
                array_kecamatan[key]=districts;
                array_desa[key]=subdistricts;
                array_detail_alamat[key]=detail_alamat;
                array_tanggal_kedatangan[key]=tanggal_kedatangan;
                array_jam_kedatangan[key]=jam_kedatangan;
                array_keterangan[key]=keterangan;
                addListTujuan();
            },
            error: function(error){
                let err_log=error.responseJSON.errors;
                if(error.status==422){
                    if(typeof(err_log.tanggal_kedatangan)!=='undefined'){
                        document.getElementById("tanggal_kedatangan_yang_ke_"+tujuan_id).style.border = "1px solid red";
                    }else{
                        document.getElementById("tanggal_kedatangan_yang_ke_"+tujuan_id).style.border="";
                    }
                    if(typeof(err_log.jam_kedatangan)!=='undefined'){
                        document.getElementById("jam_kedatangan_yang_ke_"+tujuan_id).style.border = "1px solid red";
                    }else{
                        document.getElementById("jam_kedatangan_yang_ke_"+tujuan_id).style.border="";
                    }
                    if(typeof(err_log.provinsi)!=='undefined'){
                        document.getElementById("provinsi_yang_ke_"+tujuan_id).style.border = "1px solid red";
                    }else{
                        document.getElementById("provinsi_yang_ke_"+tujuan_id).style.border="";
                    }
                    if(typeof(err_log.city)!=='undefined'){
                        document.getElementById("kota_yang_ke_"+tujuan_id).style.border = "1px solid red";
                    }else{
                        document.getElementById("kota_yang_ke_"+tujuan_id).style.border="";
                    }
                    if(typeof(err_log.districts)!=='undefined'){
                        document.getElementById("kecamatan_yang_ke_"+tujuan_id).style.border = "1px solid red";
                    }else{
                        document.getElementById("kecamatan_yang_ke_"+tujuan_id).style.border="";
                    }
                    if(typeof(err_log.subdistricts)!=='undefined'){
                        document.getElementById("desa_yang_ke_"+tujuan_id).style.border = "1px solid red";
                    }else{
                        document.getElementById("desa_yang_ke_"+tujuan_id).style.border="";
                    }
                    if(typeof(err_log.detail_alamat)!=='undefined'){
                        document.getElementById("detail_alamat_yang_ke_"+tujuan_id).style.border = "1px solid red";
                    }else{
                        document.getElementById("detail_alamat_yang_ke_"+tujuan_id).style.border="";
                    }
                }
            }
        });
    }
    function addListTujuan(){
        $('#route_adding').empty();
        pass_to_dropdown();
        jQuery.each(array_tujuan_id, function(key,value){
            if(array_tujuan_id.length==1){
                $('#route_adding').append('<tr>\
                    <td style="padding:4px" width="2%">\
                        '+(key+1)+'\
                    </td>\
                    <td style="padding:4px" width="15%">\
                        <input type="hidden" id="tujuan_yang_ke_'+value+'" name="tujuan_ke[]" class="form-control" value='+value+'>\
                        <input id="provinsi_yang_ke_'+value+'" name="provinsi_ke[]" value="'+array_provinsi[key]+'" class="form-control" disabled>\
                    </td>\
                    <td style="padding:4px" width="10%">\
                        <input id="kota_yang_ke_'+value+'" name="kota_ke[]" class="form-control" value="'+array_kota[key]+'" disabled>\
                    </td>\
                    <td style="padding:4px" width="15%">\
                        <input id="kecamatan_yang_ke_'+value+'" name="kecamatan_ke[]" class="form-control" value="'+array_kecamatan[key]+'" disabled>\
                    </td>\
                    <td style="padding:4px" width="15%">\
                        <input id="desa_yang_ke_'+value+'" name="desa_ke[]" class="form-control" value="'+array_desa[key]+'" disabled>\
                    </td>\
                    <td style="padding:4px" width="15%">\
                        <input id="detail_alamat_yang_ke_'+value+'" name="detail_alamat_ke[]" class="form-control" value="'+array_detail_alamat[key]+'" disabled>\
                    </td>\
                    <td style="padding:4px" width="10%">\
                        <input id="tanggal_kedatangan_yang_ke_'+value+'" name="tanggal_kedatangan_ke[]" type="date" class="form-control" value="'+array_tanggal_kedatangan[key]+'" disabled>\
                        <input id="jam_kedatangan_yang_ke_'+value+'" name="jam_kedatangan_ke[]" type="time" class="form-control" value="'+array_jam_kedatangan[key]+'" disabled>\
                    </td>\
                    <td style="padding:4px" width="15%">\
                        <input id="keterangan_yang_ke_'+value+'" name="keterangan_ke[]" class="form-control" value="'+array_keterangan[key]+'" style="background-color:white">\
                    </td>\
                    <td style="padding:4px" width="3%">\
                        <a href="#" class="btn btn-primary px-1" onclick="add_route_more('+key+','+value+')" id="add_route_more_button_'+value+'"><i class="fa fa-plus"></i></a>\
                    </td>\
                </tr>');
            }else{
                if(value==1){
                    $('#route_adding').append('<tr>\
                        <td style="padding:4px" width="2%">\
                            '+(key+1)+'\
                        </td>\
                        <td style="padding:4px" width="15%">\
                            <input type="hidden" id="tujuan_yang_ke_'+value+'" name="tujuan_ke[]" class="form-control" value='+value+'>\
                            <input id="provinsi_yang_ke_'+value+'" name="provinsi_ke[]" value="'+array_provinsi[key]+'" class="form-control" disabled>\
                        </td>\
                        <td style="padding:4px" width="10%">\
                            <input id="kota_yang_ke_'+value+'" name="kota_ke[]" class="form-control" value="'+array_kota[key]+'" disabled>\
                        </td>\
                        <td style="padding:4px" width="15%">\
                            <input id="kecamatan_yang_ke_'+value+'" name="kecamatan_ke[]" class="form-control" value="'+array_kecamatan[key]+'" disabled>\
                        </td>\
                        <td style="padding:4px" width="15%">\
                            <input id="desa_yang_ke_'+value+'" name="desa_ke[]" class="form-control" value="'+array_desa[key]+'" disabled>\
                        </td>\
                        <td style="padding:4px" width="15%">\
                            <input id="detail_alamat_yang_ke_'+value+'" name="detail_alamat_ke[]" class="form-control" value="'+array_detail_alamat[key]+'" disabled>\
                        </td>\
                        <td style="padding:4px" width="10%">\
                            <input id="tanggal_kedatangan_yang_ke_'+value+'" name="tanggal_kedatangan_ke[]" type="date" class="form-control" value="'+array_tanggal_kedatangan[key]+'" disabled>\
                            <input id="jam_kedatangan_yang_ke_'+value+'" name="jam_kedatangan_ke[]" type="time" class="form-control" value="'+array_jam_kedatangan[key]+'" disabled>\
                        </td>\
                        <td style="padding:4px" width="15%">\
                            <input id="keterangan_yang_ke_'+value+'" name="keterangan_ke[]" class="form-control" value="'+array_keterangan[key]+'" style="background-color:white">\
                        </td>\
                        <td style="padding:4px" width="3%">\
                        </td>\
                    </tr>');
                }else{
                    if(key==(array_tujuan_id.length-1)){
                        $('#route_adding').append('<tr>\
                            <td style="padding:4px" width="2%">\
                                '+(key+1)+'\
                            </td>\
                            <td style="padding:4px" width="15%">\
                                <input type="hidden" id="tujuan_yang_ke_'+value+'" name="tujuan_ke[]" class="form-control" value='+value+'>\
                                <input id="provinsi_yang_ke_'+value+'" name="provinsi_ke[]" value="'+array_provinsi[key]+'" class="form-control" style="background-color:white">\
                                <select class="form-control form-control-sm" id="pilihan_alamat_yang_ke_'+value+'" style="margin-top:6px;background-color:white" name="history_alamat" onchange="change_alamat('+key+','+value+')">\
                                    <option value="">History Alamat</option>\
                                </select>\
                            </td>\
                            <td style="padding:4px" width="10%">\
                                <input id="kota_yang_ke_'+value+'" name="kota_ke[]" class="form-control" value="'+array_kota[key]+'" style="background-color:white">\
                            </td>\
                            <td style="padding:4px" width="15%">\
                                <input id="kecamatan_yang_ke_'+value+'" name="kecamatan_ke[]" class="form-control" value="'+array_kecamatan[key]+'" style="background-color:white">\
                            </td>\
                            <td style="padding:4px" width="15%">\
                                <input id="desa_yang_ke_'+value+'" name="desa_ke[]" class="form-control" value="'+array_desa[key]+'" style="background-color:white">\
                            </td>\
                            <td style="padding:4px" width="15%">\
                                <input id="detail_alamat_yang_ke_'+value+'" name="detail_alamat_ke[]" class="form-control" value="'+array_detail_alamat[key]+'" style="background-color:white">\
                            </td>\
                            <td style="padding:4px" width="10%">\
                                <input id="tanggal_kedatangan_yang_ke_'+value+'" name="tanggal_kedatangan_ke[]" type="date" class="form-control" value="'+array_tanggal_kedatangan[key]+'" style="background-color:white">\
                                <input id="jam_kedatangan_yang_ke_'+value+'" name="jam_kedatangan_ke[]" type="text" class="form-control" value="'+(array_jam_kedatangan[key]).substring(0,5)+'" style="background-color:white; cursor:pointer;">\
                            </td>\
                            <td style="padding:4px" width="15%">\
                                <input id="keterangan_yang_ke_'+value+'" name="keterangan_ke[]" class="form-control" value="'+array_keterangan[key]+'" style="background-color:white">\
                            </td>\
                            <td style="padding:4px" width="3%">\
                                <a href="#" class="btn btn-primary px-1" onclick="add_route_more('+key+','+value+')" id="add_route_more_button_'+value+'"><i class="fa fa-plus"></i></a>\
                                <a href="#" class="btn btn-danger px-1" onclick="delete_this_route('+value+')" id="delete_this_route_button_'+value+'"><i class="fa fa-minus"></i></a>\
                            </td>\
                        </tr>');
                    }else{
                        $('#route_adding').append('<tr>\
                            <td style="padding:4px" width="2%">\
                                '+(key+1)+'\
                            </td>\
                            <td style="padding:4px" width="15%">\
                                <input type="hidden" id="tujuan_yang_ke_'+value+'" name="tujuan_ke[]" class="form-control" value='+value+'>\
                                <input id="provinsi_yang_ke_'+value+'" name="provinsi_ke[]" value="'+array_provinsi[key]+'" class="form-control" style="background-color:white">\
                                <select id="pilihan_alamat_yang_ke_'+value+'" class="form-control form-control-sm" style="margin-top:6px;background-color:white" name="history_alamat" onchange="change_alamat('+key+','+value+')">\
                                    <option value="">History Alamat</option>\
                                </select>\
                            </td>\
                            <td style="padding:4px" width="10%">\
                                <input id="kota_yang_ke_'+value+'" name="kota_ke[]" class="form-control" value="'+array_kota[key]+'" style="background-color:white">\
                            </td>\
                            <td style="padding:4px" width="15%">\
                                <input id="kecamatan_yang_ke_'+value+'" name="kecamatan_ke[]" class="form-control" value="'+array_kecamatan[key]+'" style="background-color:white">\
                            </td>\
                            <td style="padding:4px" width="15%">\
                                <input id="desa_yang_ke_'+value+'" name="desa_ke[]" class="form-control" value="'+array_desa[key]+'" style="background-color:white">\
                            </td>\
                            <td style="padding:4px" width="15%">\
                                <input id="detail_alamat_yang_ke_'+value+'" name="detail_alamat_ke[]" class="form-control" value="'+array_detail_alamat[key]+'" style="background-color:white">\
                            </td>\
                            <td style="padding:4px" width="10%">\
                                <input id="tanggal_kedatangan_yang_ke_'+value+'" name="tanggal_kedatangan_ke[]" type="date" class="form-control" value="'+array_tanggal_kedatangan[key]+'" style="background-color:white">\
                                <input id="jam_kedatangan_yang_ke_'+value+'" name="jam_kedatangan_ke[]" type="text" class="form-control" value="'+(array_jam_kedatangan[key]).substring(0,5)+'" style="background-color:white; cursor:pointer;">\
                            </td>\
                            <td style="padding:4px" width="15%">\
                                <input id="keterangan_yang_ke_'+value+'" name="keterangan_ke[]" style="background-color:white;" class="form-control" value="'+array_keterangan[key]+'" style="background-color:white;">\
                            </td>\
                            <td style="padding:4px" width="3%">\
                                <a href="#" class="btn btn-danger px-1" onclick="delete_this_route('+value+')" id="delete_this_route_button_'+value+'"><i class="fa fa-minus"></i></a>\
                            </td>\
                        </tr>');
                    }
                }
            }
            $("#jam_kedatangan_yang_ke_"+value).timepicker({
                timeFormat: "%H:%i"
            });
        });
    }
    function pass_to_dropdown(){
        var user=$('#user_login').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_route_from_user')}}",
            data: {
                user:user,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(data){
                jQuery.each(data, function(key,value){
                    $('select[name="history_alamat"]').append('<option value="'+value.detail_alamat+' | '+value.subdistrict+' | '+value.district+' | '+value.city+' | '+value.provinsi+'">'+value.detail_alamat+' ('+value.subdistrict+' - '+value.district+' - '+value.city+' - '+value.provinsi+')</option>');
                });
            }
        });
    };
    function change_alamat(key,value){
        myArray=[];
        var tujuan_id = $('#pilihan_alamat_yang_ke_'+value).val();
        myArray = tujuan_id.split(" | ");
        $('#detail_alamat_yang_ke_'+value).val(myArray[0]);
        $('#desa_yang_ke_'+value).val(myArray[1]);
        $('#kecamatan_yang_ke_'+value).val(myArray[2]);
        $('#kota_yang_ke_'+value).val(myArray[3]);
        $('#provinsi_yang_ke_'+value).val(myArray[4]);
        $('#pilihan_alamat_yang_ke_'+value).val('');
        array_provinsi[key]=myArray[4];
        array_kota[key]=myArray[3];
        array_kecamatan[key]=myArray[2];
        array_desa[key]=myArray[1];
        array_detail_alamat[key]=myArray[0];
    }
    function delete_this_route(count){
        var index_array=(array_tujuan_id.indexOf(count));
        array_tujuan_id.splice(index_array, 1);
        array_kota.splice(index_array, 1);
        array_provinsi.splice(index_array, 1);
        array_kecamatan.splice(index_array, 1);
        array_desa.splice(index_array, 1);
        array_detail_alamat.splice(index_array, 1);
        array_tanggal_kedatangan.splice(index_array, 1);
        array_jam_kedatangan.splice(index_array, 1);
        array_keterangan.splice(index_array, 1);
        addListTujuan();
    }
    function changeFirstElementOfArray(){
        var subdistricts=$('#sub_districts_2').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_all_zone_name')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                subdis_id:subdistricts,
            },
            success: function(res){
                array_provinsi[0]=res[0].prov_name;
                array_kota[0]=res[0].city_name;
                array_kecamatan[0]=res[0].dis_name;
                array_desa[0]=res[0].subdis_name;
            },
            error: function(res){
                swal({
                    title: "Ambil data Provinsi",
                    text: "Data provinsi gagal di ambil",
                    icon: "danger",
                });
            }
        });
    }
    $('#save_edited_route').on('click',function(){
        saveEditedRoute();
    });
    $('#close_route_adding').on('click',function(){
        saveEditedRoute();
    });
    function saveEditedRoute(){
        var tujuan = $("input[name='tujuan_ke[]']").map(function(){return $(this).val();}).get();
        var province = $("input[name='provinsi_ke[]']").map(function(){return $(this).val();}).get();
        var city = $("input[name='kota_ke[]']").map(function(){return $(this).val();}).get();
        var district = $("input[name='kecamatan_ke[]']").map(function(){return $(this).val();}).get();
        var subdistrict = $("input[name='desa_ke[]']").map(function(){return $(this).val();}).get();
        var detail_alamat = $("input[name='detail_alamat_ke[]']").map(function(){return $(this).val();}).get();
        var tanggal_kedatangan = $("input[name='tanggal_kedatangan_ke[]']").map(function(){return $(this).val();}).get();
        var jam_kedatangan = $("input[name='jam_kedatangan_ke[]']").map(function(){return $(this).val();}).get();
        for(var i=1; i<tujuan.length; i++) {
            if(!province[i]){
                document.getElementById("provinsi_yang_ke_"+tujuan[i]).style.border = "1px solid red";
            }else{
                document.getElementById("provinsi_yang_ke_"+tujuan[i]).style.border = "";
            }
            if(!city[i]){
                document.getElementById("kota_yang_ke_"+tujuan[i]).style.border = "1px solid red";
            }else{
                document.getElementById("kota_yang_ke_"+tujuan[i]).style.border = "";
            }
            if(!district[i]){
                document.getElementById("kecamatan_yang_ke_"+tujuan[i]).style.border = "1px solid red";
            }else{
                document.getElementById("kecamatan_yang_ke_"+tujuan[i]).style.border = "";
            }
            if(!subdistrict[i]){
                document.getElementById("desa_yang_ke_"+tujuan[i]).style.border = "1px solid red";
            }else{
                document.getElementById("desa_yang_ke_"+tujuan[i]).style.border = "";
            }
            if(!detail_alamat[i]){
                document.getElementById("detail_alamat_yang_ke_"+tujuan[i]).style.border = "1px solid red";
            }else{
                document.getElementById("detail_alamat_yang_ke_"+tujuan[i]).style.border = "";
            }
            if(!tanggal_kedatangan[i]){
                document.getElementById("tanggal_kedatangan_yang_ke_"+tujuan[i]).style.border = "1px solid red";
            }else{
                document.getElementById("tanggal_kedatangan_yang_ke_"+tujuan[i]).style.border = "";
            }
            if(!jam_kedatangan[i]){
                document.getElementById("jam_kedatangan_yang_ke_"+tujuan[i]).style.border = "1px solid red";
            }else{
                document.getElementById("jam_kedatangan_yang_ke_"+tujuan[i]).style.border = "";
            }
        }
        if(!province.includes('') && !city.includes('') && !district.includes('') && !subdistrict.includes('') && !detail_alamat.includes('') && !tanggal_kedatangan.includes('') && !jam_kedatangan.includes('')){
            array_tujuan_id=[];
            array_provinsi=[];
            array_kota=[];
            array_kecamatan=[];
            array_desa=[];
            array_detail_alamat=[];
            array_tanggal_kedatangan=[];
            array_jam_kedatangan=[];
            array_keterangan=[];
            var array_tujuan=($("input[name='tujuan_ke[]']").map(function(){return $(this).val();}).get());
            array_tujuan.forEach(function(value){
                array_tujuan_id.push(parseInt(value));
            });
            var array_prov=($("input[name='provinsi_ke[]']").map(function(){return $(this).val();}).get());
            array_prov.forEach(function(value){
                array_provinsi.push(value);
            });
            var array_city=($("input[name='kota_ke[]']").map(function(){return $(this).val();}).get());
            array_city.forEach(function(value){
                array_kota.push(value);
            });
            var array_district=($("input[name='kecamatan_ke[]']").map(function(){return $(this).val();}).get());
            array_district.forEach(function(value){
                array_kecamatan.push(value);
            });
            var array_subdistrict=($("input[name='desa_ke[]']").map(function(){return $(this).val();}).get());
            array_subdistrict.forEach(function(value){
                array_desa.push(value);
            });
            var array_detail_alm=($("input[name='detail_alamat_ke[]']").map(function(){return $(this).val();}).get());
            array_detail_alm.forEach(function(value){
                array_detail_alamat.push(value);
            });
            var array_tanggal_kdt=($("input[name='tanggal_kedatangan_ke[]']").map(function(){return $(this).val();}).get());
            array_tanggal_kdt.forEach(function(value){
                array_tanggal_kedatangan.push(value);
            });
            var array_jam_kdt=($("input[name='jam_kedatangan_ke[]']").map(function(){return $(this).val();}).get());
            array_jam_kdt.forEach(function(value){
                array_jam_kedatangan.push(value);
            });
            var array_keter=($("input[name='keterangan_ke[]']").map(function(){return $(this).val();}).get());
            array_keter.forEach(function(value){
                array_keterangan.push(value);
            });
            $('#keterangan_barang').val(array_keter[0]);
            $('#tujuanLainnyaModal').modal('hide');
        }
    }
    function ambil_nama_provinsi(tujuan_id,prov_id){
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_province')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                jQuery.each(res, function(key,value){
                    $('#brow_provinsi_yang_ke_'+tujuan_id).append('<option value="'+value['prov_name']+'">');
                });
                $('#provinsi_yang_ke_'+tujuan_id).val(prov_id);
            },
            error: function(res){
                swal({
                    title: "Ambil data Provinsi",
                    text: "Data provinsi gagal di ambil",
                    icon: "danger",
                });
            }
        });
    }
    function ambil_nama_kabupaten(tujuan_id,prov_id,city_id){
        if(prov_id==null){
            prov_id=$('#provinsi_yang_ke_'+tujuan_id).val();
            $('#kecamatan_yang_ke_'+tujuan_id).empty().append('');
            $('#desa_yang_ke_'+tujuan_id).empty().append('');
        }
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_cities_name')}}",
            data: {
                provinsi:prov_id,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                if(res){
                    $('#brow_kota_yang_ke_'+tujuan_id).empty();
                    jQuery.each(res, function(key,value){
                        $('#brow_kota_yang_ke_'+tujuan_id).append('<option value="'+value['city_name']+'">');
                    });
                    $('#kota_yang_ke_'+tujuan_id).val(city_id);
                    $('#kecamatan_yang_ke_'+tujuan_id).val('');
                    $('#brow_kecamatan_yang_ke_'+tujuan_id).empty().append('');
                    $('#desa_yang_ke_'+tujuan_id).val('');
                    $('#brow_desa_yang_ke_'+tujuan_id).empty().append('');
                }else{
                    $('#kecamatan_yang_ke_'+tujuan_id).val('');
                    $('#kota_yang_ke_'+tujuan_id).empty().append('');
                    $('#desa_yang_ke_'+tujuan_id).val('');
                    $('#desa_yang_ke_'+tujuan_id).empty().append('');
                }
            },
            error: function(res){
                swal({
                    title: "Ambil data Kota",
                    text: "Data kota gagal di ambil",
                    icon: "danger",
                });
            }
        });
    }
    function ambil_nama_kecamatan(tujuan_id,prov_id,city_id,dis_id){
        if(prov_id==null){
            prov_id=$('#provinsi_yang_ke_'+tujuan_id).val();
        }
        if(city_id==null){
            city_id=$('#kota_yang_ke_'+tujuan_id).val();
        }
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_districts_name')}}",
            data: {
                prov:prov_id,
                cities:city_id,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                if(res){
                    $('#brow_kecamatan_yang_ke_'+tujuan_id).empty();
                    jQuery.each(res, function(key,value){
                        $('#brow_kecamatan_yang_ke_'+tujuan_id).append('<option value="'+value['dis_name']+'">');
                    });
                    $('#kecamatan_yang_ke_'+tujuan_id).val(dis_id);
                    $('#desa_yang_ke_'+tujuan_id).val('');
                    $('#brow_desa_yang_ke_'+tujuan_id).empty().append('');
                }else{
                    $('#kecamatan_yang_ke_'+tujuan_id).val('');
                    $('#brow_kecamatan_yang_ke_'+tujuan_id).empty().append('');
                    $('#desa_yang_ke_'+tujuan_id).val('');
                    $('#brow_desa_yang_ke_'+tujuan_id).empty().append('');
                }
            },
            error: function(res){
                swal({
                    title: "Ambil data Kecamatan",
                    text: "Data kecamatan gagal di ambil",
                    icon: "danger",
                });
                $('#kecamatan_yang_ke_'+tujuan_id).val('');
                $('#brow_kecamatan_yang_ke_'+tujuan_id).empty().append('');
                $('#desa_yang_ke_'+tujuan_id).val('');
                $('#brow_desa_yang_ke_'+tujuan_id).empty().append('');
            }
        });
    }
    function ambil_nama_desa(tujuan_id,prov_id,city_id,dis_id,subdis_id){
        if(prov_id==null){
            prov_id=$('#provinsi_yang_ke_'+tujuan_id).val();
        }
        if(city_id==null){
            city_id=$('#kota_yang_ke_'+tujuan_id).val();
        }
        if(dis_id==null){
            dis_id=$('#kecamatan_yang_ke_'+tujuan_id).val();
        }
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_subdistricts_name')}}",
            data: {
                prov:prov_id,
                city:city_id,
                districts:dis_id,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                if(res){
                    $('#brow_desa_yang_ke_'+tujuan_id).empty();
                    jQuery.each(res, function(key,value){
                        $('#brow_desa_yang_ke_'+tujuan_id).append('<option value="'+value['subdis_name']+'">');
                    });
                    $('#desa_yang_ke_'+tujuan_id).val(subdis_id);
                }else{
                    $('#desa_yang_ke_'+tujuan_id).val('');
                    $('#brow_desa_yang_ke_'+tujuan_id).empty().append('');
                }
            },
            error: function(res){
                swal({
                    title: "Ambil Data Desa",
                    text: "Data desa gagal di ambil",
                    icon: "danger",
                });
                $('#desa_yang_ke_'+tujuan_id).val('');
                $('#brow_desa_yang_ke_'+tujuan_id).empty().append('');
            }
        });
    }
    $('#provinsi').on('change',function(){
        var provinsi=$('#provinsi').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_cities')}}",
            data: {
                provinsi:provinsi,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#cities').empty().append('<option value="">Pilih Kabupaten/Kota</option>');
                document.getElementById("cities").disabled=false;
                $('#cities').val('');
                jQuery.each(res, function(key,value){
                    $('#cities').append('<option value="'+ value['city_id'] +'">'+ value['city_name'] +'</option>');
                });
                $('#districts').empty().append('<option value="">Pilih Kecamatan</option>');
                document.getElementById("districts").disabled=true;
                $('#sub_districts').empty().append('<option value="">Pilih Kelurahan/Desa</option>');
                document.getElementById("sub_districts").disabled=true;
            },
            error: function(res){
                swal({
                    title: "Ambil data kota",
                    text: "Data kota gagal di ambil",
                    icon: "danger",
                });
            }
        });
    });
    $('#cities').on('change',function(){
        var cities=$('#cities').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_districts')}}",
            data: {
                cities:cities,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#districts').empty().append('<option value="">Pilih Kecamatan</option>');
                document.getElementById("districts").disabled=false;
                $('#districts').val('');
                jQuery.each(res, function(key,value){
                    $('#districts').append('<option value="'+ value['dis_id'] +'">'+ value['dis_name'] +'</option>');
                });
                $('#sub_districts').empty().append('<option value="">Pilih Kelurahan/Desa</option>');
                document.getElementById("sub_districts").disabled=true;
            },
            error: function(res){
                swal({
                    title: "Ambil data desa",
                    text: "Data desa gagal di ambil",
                    icon: "danger",
                });
            }
        });
    });
    $('#districts').on('change',function(){
        var districts=$('#districts').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_subdistricts')}}",
            data: {
                districts:districts,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#sub_districts').empty().append('<option value="">Pilih Kelurahan/Desa</option>');
                document.getElementById("sub_districts").disabled=false;
                $('#sub_districts').val('');
                jQuery.each(res, function(key,value){
                    $('#sub_districts').append('<option value="'+ value['subdis_id'] +'">'+ value['subdis_name'] +'</option>');
                });
            },
            error: function(res){
                swal({
                    title: "Ambil data desa",
                    text: "Data desa gagal di ambil",
                    icon: "danger",
                });
            }
        });
    });
    $('#provinsi_2').on('change',function(){
        var provinsi=$('#provinsi_2').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_cities')}}",
            data: {
                provinsi:provinsi,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#cities_2').empty().append('<option value="">Pilih Kabupaten/Kota</option>');
                document.getElementById("cities_2").disabled=false;
                $('#cities_2').val('');
                jQuery.each(res, function(key,value){
                    $('#cities_2').append('<option value="'+ value['city_id'] +'">'+ value['city_name'] +'</option>');
                });
                $('#districts_2').empty().append('<option value="">Pilih Kecamatan</option>');
                document.getElementById("districts_2").disabled=true;
                $('#sub_districts_2').empty().append('<option value="">Pilih Kelurahan/Desa</option>');
                document.getElementById("sub_districts_2").disabled=true;
            },
            error: function(res){
                swal({
                    title: "Ambil data kota",
                    text: "Data kota gagal di ambil",
                    icon: "danger",
                });
            }
        });
    });
    $('#cities_2').on('change',function(){
        var cities=$('#cities_2').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_districts')}}",
            data: {
                cities:cities,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#districts_2').empty().append('<option value="">Pilih Kecamatan</option>');
                document.getElementById("districts_2").disabled=false;
                $('#districts_2').val('');
                jQuery.each(res, function(key,value){
                    $('#districts_2').append('<option value="'+ value['dis_id'] +'">'+ value['dis_name'] +'</option>');
                });
                $('#sub_districts_2').empty().append('<option value="">Pilih Kelurahan/Desa</option>');
                document.getElementById("sub_districts_2").disabled=true;
            },
            error: function(res){
                swal({
                    title: "Ambil data desa",
                    text: "Data desa gagal di ambil",
                    icon: "danger",
                });
            }
        });
    });
    $('#districts_2').on('change',function(){
        var districts=$('#districts_2').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_subdistricts')}}",
            data: {
                districts:districts,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#sub_districts_2').empty().append('<option value="">Pilih Kelurahan/Desa</option>');
                document.getElementById("sub_districts_2").disabled=false;
                $('#sub_districts_2').val('');
                jQuery.each(res, function(key,value){
                    $('#sub_districts_2').append('<option value="'+ value['subdis_id'] +'">'+ value['subdis_name'] +'</option>');
                });
            },
            error: function(res){
                swal({
                    title: "Ambil data desa",
                    text: "Data desa gagal di ambil",
                    icon: "danger",
                });
            }
        });
    });
    $('#provinsi').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("provinsi").style.border="";
        }
    });
    $('#cities').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("cities").style.border="";
        }
    });
    $('#districts').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("districts").style.border="";
        }
    });
    $('#sub_districts').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("sub_districts").style.border="";
        }
    });
    $('#detail_alamat').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("detail_alamat").style.border="";
        }
    });
    $('#provinsi_2').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("provinsi_2").style.border="";
        }
    });
    $('#cities_2').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("cities_2").style.border="";
        }
    });
    $('#districts_2').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("districts_2").style.border="";
        }
    });
    $('#districts_2').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("districts_2").style.border="";
        }
    });
    $('#sub_districts_2').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("sub_districts_2").style.border="";
        }
        changeFirstElementOfArray();
    });
    $('#detail_alamat_2').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("detail_alamat_2").style.border="";
        }
    });
    $('#tanggal_pemberangkatan').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("tanggal_pemberangkatan").style.border="";
        }
    });
    $('#jam_pemberangkatan').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("jam_pemberangkatan").style.border="";
        }
    });
    $('#tanggal_kedatangan').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("tanggal_kedatangan").style.border="";
        }
    });
    $('#jam_kedatangan').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("jam_kedatangan").style.border="";
        }
    });
    $('#nama_tamu').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("nama_tamu").style.border="";
        }
    });
    $('#nomor_tamu').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("nomor_tamu").style.border="";
        }
    });
    $('#instansi_tamu').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("instansi_tamu").style.border="";
        }
    });
    $('#jenis_barang').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("jenis_barang").style.border="";
        }
    });
    $('#quantity').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("quantity").style.border="";
        }
    });
    $('#satuan').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("satuan").style.border="";
        }
    });
    $('#instansi').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("instansi").style.border="";
        }
    });
    $('#nama_instansi').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("nama_instansi").style.border="";
        }
    });
    $('#checkbox_1').on('change', function() { 
        if (this.checked) {
            document.getElementById('tag_nama_tamu').style.display='block';
        }else{
            if(!document.getElementById("checkbox_2").checked){
                document.getElementById('tag_nama_tamu').style.display='none';
                $('#nama_tamu').val('');
                $('#nomor_tamu').val('');
                $('#instansi_tamu').val('');
            }
        }
    });
    $('#checkbox_2').on('change', function() { 
        if (this.checked) {
            document.getElementById('tag_nama_tamu').style.display='block';
        }else{
            if(!document.getElementById("checkbox_1").checked){
                document.getElementById('tag_nama_tamu').style.display='none';
                $('#nama_tamu').val('');
                $('#nomor_tamu').val('');
                $('#instansi_tamu').val('');
            }
        }
    });
    $('#checkbox_3').on('change', function() { 
        if (this.checked) {
            document.getElementById('tag_jenis_barang').style.display='block';
        }else{
            if(!document.getElementById("checkbox_4").checked){
                document.getElementById('tag_jenis_barang').style.display='none';
                $('#jenis_barang').val('');
                $('#quantity').val('');
                $('#satuan').val('');
                $('#instansi').val('');
                $('#nama_instansi').val('');
            }
        }
    });
    $('#checkbox_4').on('change', function() { 
        if (this.checked) {
            document.getElementById('tag_jenis_barang').style.display='block';
        }else{
            if(!document.getElementById("checkbox_3").checked){
                document.getElementById('tag_jenis_barang').style.display='none';
                $('#jenis_barang').val('');
                $('#quantity').val('');
                $('#satuan').val('');
                $('#instansi').val('');
                $('#nama_instansi').val('');
            }
        }
    });
    $('#checkbox_5').on('change', function() { 
        if (this.checked) {
            document.getElementById('tag_antar_dinas').style.display='block';
        }else{
            if(!document.getElementById("checkbox_6").checked){
                document.getElementById('tag_antar_dinas').style.display='none';
                $('#selectEmployeeID').val(null).trigger('change');
            }
        }
    });
    $('#checkbox_6').on('change', function() { 
        if (this.checked) {
            document.getElementById('tag_antar_dinas').style.display='block';
        }else{
            if(!document.getElementById("checkbox_5").checked){
                document.getElementById('tag_antar_dinas').style.display='none';
                $('#selectEmployeeID').val(null).trigger('change');
            }
        }
    });
    
    $('#selectEmployeeID').on('change',function(){
        var employee_array = $("select[name='selectEmployeeDinas[]']").map(function(){return $(this).val();}).get();
        if(employee_array.length>0){
            document.getElementById("warning_employee_dinas").innerHTML='';
        }
    });
    $('#save_edited_form').on('click',function(){
        var id_request=$('#id_request').val();
        var provinsi=$('#provinsi').val();
        var cities=$('#cities').val();
        var districts=$('#districts').val();
        var sub_districts=$('#sub_districts').val();
        var detail_alamat=$('#detail_alamat').val();
        var provinsi_2=$('#provinsi_2').val();
        var cities_2=$('#cities_2').val();
        var districts_2=$('#districts_2').val();
        var sub_districts_2=$('#sub_districts_2').val();
        var detail_alamat_2=$('#detail_alamat_2').val();
        var tanggal_pemberangkatan=$('#tanggal_pemberangkatan').val();
        var jam_pemberangkatan=$('#jam_pemberangkatan').val();
        var tanggal_kedatangan=$('#tanggal_kedatangan').val();
        var jam_kedatangan=$('#jam_kedatangan').val();
        var jarak_tempuh=$('#jarak_tempuh').val();
        var tujuan_pemberangkatan = [];
        $("input:checkbox[name=tujuan_pemberangkatan]:checked").each(function() {
            tujuan_pemberangkatan.push($(this).val());
        });
        var tujuan=tujuan_pemberangkatan.toString();
        var cb_antar_tamu=document.getElementById("checkbox_1").checked?1:0;
        var cb_jemput_tamu=document.getElementById("checkbox_2").checked?1:0;
        var cb_antar_barang=document.getElementById("checkbox_3").checked?1:0;
        var cb_jemput_barang=document.getElementById("checkbox_4").checked?1:0;
        var cb_antar_dinas=document.getElementById("checkbox_5").checked?1:0;
        var cb_jemput_dinas=document.getElementById("checkbox_6").checked?1:0;
        var nama_tamu=$("#nama_tamu").val();
        var nomor_tamu=$("#nomor_tamu").val();
        var instansi_tamu=$("#instansi_tamu").val();
        var jenis_barang=$("#jenis_barang").val();
        var quantity=$("#quantity").val();
        var satuan=$("#satuan").val();
        var instansi=$("#instansi").val();
        var nama_instansi=$("#nama_instansi").val();
        var keterangan_barang=$("#keterangan_barang").val();
        var employee_dinas='';
        var employee_dinas_array = $("select[name='selectEmployeeDinas[]']").map(function(){return $(this).val();}).get();
        if(employee_dinas_array.length>0){
            var employee_dinas=employee_dinas_array.toString();
        }
        var tujuan_array = array_tujuan_id;
        var provinsi_array = array_provinsi;
        var city_array = array_kota;
        var district_array = array_kecamatan;
        var subdistrict_array = array_desa;
        var detail_alamat_array = array_detail_alamat;
        var tanggal_kedatangan_array = array_tanggal_kedatangan;
        var jam_kedatangan_array = array_jam_kedatangan;
        var keterangan_array = array_keterangan;
        
        changeFirstElementOfArray();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.update_car_request_user')}}",
            data: {
                id_request:id_request,
                provinsi:provinsi,
                cities:cities,
                districts:districts,
                sub_districts:sub_districts,
                detail_alamat:detail_alamat,
                tanggal_pemberangkatan:tanggal_pemberangkatan,
                jam_pemberangkatan:jam_pemberangkatan,
                tanggal_kedatangan:tanggal_kedatangan,
                jam_kedatangan:jam_kedatangan,
                jarak_tempuh:jarak_tempuh,
                provinsi_2:provinsi_2,
                cities_2:cities_2,
                districts_2:districts_2,
                sub_districts_2:sub_districts_2,
                detail_alamat_2:detail_alamat_2,
                cb_antar_tamu:cb_antar_tamu,
                cb_jemput_tamu:cb_jemput_tamu,
                nama_tamu:nama_tamu,
                nomor_tamu:nomor_tamu,
                instansi_tamu:instansi_tamu,
                cb_antar_barang:cb_antar_barang,
                cb_jemput_barang:cb_jemput_barang,
                jenis_barang:jenis_barang,
                quantity:quantity,
                satuan:satuan,
                instansi:instansi,
                nama_instansi:nama_instansi,
                keterangan_barang:keterangan_barang,
                cb_antar_dinas:cb_antar_dinas,
                cb_jemput_dinas:cb_jemput_dinas,
                employee_dinas:employee_dinas,
                tujuan:tujuan,
                tujuan_array:tujuan_array,
                provinsi_array:provinsi_array,
                city_array:city_array,
                district_array:district_array,
                subdistrict_array:subdistrict_array,
                detail_alamat_array:detail_alamat_array,
                tanggal_kedatangan_array:tanggal_kedatangan_array,
                jam_kedatangan_array:jam_kedatangan_array,
                keterangan_array:keterangan_array
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                if(res=='sudah di approve'){
                    swal("", "Status permintaan transportasi telah di ubah oleh administrator", "error");
                }else{
                    swal("", "Update Permintaan Transportasi Berhasil", "success");
                    var url = 'data_pengajuan_transportasi';
                    window.open(url, '_self');
                }
            },
            error: function(error){
                let err_log=error.responseJSON.errors;
                if(error.status==422){
                    if(typeof(err_log.employee_dinas)!=='undefined'){
                        document.getElementById("warning_employee_dinas").innerHTML='Karyawan '+error.responseJSON.errors.employee_dinas[0];
                    }else{
                        document.getElementById("warning_employee_dinas").innerHTML='';
                    }
                    if(typeof(err_log.provinsi)!=='undefined'){
                        document.getElementById("provinsi").style.border = "1px solid red";
                    }else{
                        document.getElementById("provinsi").style.border="";
                    }
                    if(typeof(err_log.cities)!=='undefined'){
                        document.getElementById("cities").style.border = "1px solid red";
                    }else{
                        document.getElementById("cities").style.border="";
                    }
                    if(typeof(err_log.districts)!=='undefined'){
                        document.getElementById("districts").style.border = "1px solid red";
                    }else{
                        document.getElementById("districts").style.border="";
                    }
                    if(typeof(err_log.sub_districts)!=='undefined'){
                        document.getElementById("sub_districts").style.border = "1px solid red";
                    }else{
                        document.getElementById("sub_districts").style.border="";
                    }
                    if(typeof(err_log.detail_alamat)!=='undefined'){
                        document.getElementById("detail_alamat").style.border = "1px solid red";
                    }else{
                        document.getElementById("detail_alamat").style.border="";
                    }
                    if(typeof(err_log.tanggal_pemberangkatan)!=='undefined'){
                        document.getElementById("tanggal_pemberangkatan").style.border = "1px solid red";
                    }else{
                        document.getElementById("tanggal_pemberangkatan").style.border="";
                    }
                    if(typeof(err_log.jam_pemberangkatan)!=='undefined'){
                        document.getElementById("jam_pemberangkatan").style.border = "1px solid red";
                    }else{
                        document.getElementById("jam_pemberangkatan").style.border="";
                    }
                    if(typeof(err_log.tanggal_kedatangan)!=='undefined'){
                        document.getElementById("tanggal_kedatangan").style.border = "1px solid red";
                    }else{
                        document.getElementById("tanggal_kedatangan").style.border="";
                    }
                    if(typeof(err_log.jam_kedatangan)!=='undefined'){
                        document.getElementById("jam_kedatangan").style.border = "1px solid red";
                    }else{
                        document.getElementById("jam_kedatangan").style.border="";
                    }
                    if(typeof(err_log.provinsi_2)!=='undefined'){
                        document.getElementById("provinsi_2").style.border = "1px solid red";
                    }else{
                        document.getElementById("provinsi_2").style.border="";
                    }
                    if(typeof(err_log.cities_2)!=='undefined'){
                        document.getElementById("cities_2").style.border = "1px solid red";
                    }else{
                        document.getElementById("cities_2").style.border="";
                    }
                    if(typeof(err_log.districts_2)!=='undefined'){
                        document.getElementById("districts_2").style.border = "1px solid red";
                    }else{
                        document.getElementById("districts_2").style.border="";
                    }
                    if(typeof(err_log.sub_districts_2)!=='undefined'){
                        document.getElementById("sub_districts_2").style.border = "1px solid red";
                    }else{
                        document.getElementById("sub_districts_2").style.border="";
                    }
                    if(typeof(err_log.detail_alamat_2)!=='undefined'){
                        document.getElementById("detail_alamat_2").style.border = "1px solid red";
                    }else{
                        document.getElementById("detail_alamat_2").style.border="";
                    }
                    if(typeof(err_log.nama_tamu)!=='undefined'){
                        document.getElementById("nama_tamu").style.border = "1px solid red";
                    }else{
                        document.getElementById("nama_tamu").style.border="";
                    }
                    if(typeof(err_log.nomor_tamu)!=='undefined'){
                        document.getElementById("nomor_tamu").style.border = "1px solid red";
                    }else{
                        document.getElementById("nomor_tamu").style.border="";
                    }
                    if(typeof(err_log.instansi_tamu)!=='undefined'){
                        document.getElementById("instansi_tamu").style.border = "1px solid red";
                    }else{
                        document.getElementById("instansi_tamu").style.border="";
                    }
                    if(typeof(err_log.jenis_barang)!=='undefined'){
                        document.getElementById("jenis_barang").style.border = "1px solid red";
                    }else{
                        document.getElementById("jenis_barang").style.border="";
                    }
                    if(typeof(err_log.quantity)!=='undefined'){
                        document.getElementById("quantity").style.border = "1px solid red";
                    }else{
                        document.getElementById("quantity").style.border="";
                    }
                    if(typeof(err_log.satuan)!=='undefined'){
                        document.getElementById("satuan").style.border = "1px solid red";
                    }else{
                        document.getElementById("satuan").style.border="";
                    }
                    if(typeof(err_log.instansi)!=='undefined'){
                        document.getElementById("instansi").style.border = "1px solid red";
                    }else{
                        document.getElementById("instansi").style.border="";
                    }
                    if(typeof(err_log.nama_instansi)!=='undefined'){
                        document.getElementById("nama_instansi").style.border = "1px solid red";
                    }else{
                        document.getElementById("nama_instansi").style.border="";
                    }
                }
            }
        });
    });
</script>
@endsection