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
            <button class="btn btn-primary py-1" id="tujuan_lainnya" data-id="{{$value->id}}"> Tujuan Lainnya</button></label>
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
                    <option value="{{$subdis->subdis_id}}" {{ ( $subdis->subdis_id == $value->sub_dis_tujuan) ? 'selected' : '' }}>{{$subdis->subdis_name}}</option>
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
            <input type="time" id="jam_pemberangkatan" class="form-control col-8" style="background-color: white" value="{{$value->jam_pemberangkatan}}">
        </div>
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold;font-size:12pt">Tanggal & Jam Kedatangan</label>
        </div>
        <div class="col-2">
            <input type="date" id="tanggal_kedatangan" class="form-control" style="background-color: white" value="{{$value->tanggal_kedatangan}}">
        </div>
        <div class="col-2">
            <input type="time" id="jam_kedatangan" class="form-control col-8" style="background-color: white" value="{{$value->jam_kedatangan}}">
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
        <div class="row pb-2">
            <div class="col-12 pt-1">
                <label class="form-label" style="font-weight: bold;font-size:12pt">Keterangan Tamu</label>
            </div>
        </div>
        <div class="row pb-2">
            <div class="col-2 pt-1">
                <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Nama Tamu</label>
            </div>
            <div class="col-4">
                <input type="text" id="nama_tamu" class="form-control col-10" style="background-color: white" placeholder="Masukkan Nama Tamu" value="{{$value->nama_tamu}}">
            </div>
            <div class="col-2 pt-1">
                <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Nomor Hp Tamu</label>
            </div>
            <div class="col-4">
                <input type="text" id="nomor_tamu" class="form-control col-10" style="background-color: white" placeholder="E.g. 089501940612" value="{{$value->nomor_hp_tamu}}">
            </div>
        </div>
        <div class="row pb-2">
            <div class="col-2 pt-1">
                <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Instansi</label>
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
        <div class="row pb-2">
            <div class="col-12 pt-1">
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
                    <div class="col-8">
                        <input type="number" id="quantity" class="form-control col-2" style="background-color: white" value="{{$value->quantity}}">
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
            <div class="col-6">
                <div class="row">
                    <div class="col-4 pt-1">
                        <label class="form-label" style="font-weight: bold;font-size:12pt">Keterangan</label>
                    </div>
                    <div class="col-8">
                        <textarea id="keterangan_barang" rows="4" cols="7" class="form-control col-10" style="background-color: white">{{$value->keterangan_barang}}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="modal fade" id="tujuanLainnyaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 94%">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                <button aria-label="Close" style="background-color: rgb(255, 255, 255)" data-dismiss="modal"><span class="fa fa-times"></span></button>
            </div>
            <div class="modal-body" style="height:1000px">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered" id="route_adding">
                            <tr>
                                <th>
                                    Provinsi
                                </th>
                                <th>
                                    Kota/Kabupaten
                                </th>
                                <th>
                                    Kecamatan
                                </th>
                                <th>
                                    Kelurahan/Desa
                                </th>
                                <th></th>
                            </tr>
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
<script>
    var array_tujuan_id=[];
    var array_provinsi=[];
    var array_kota=[];
    var array_kecamatan=[];
    var array_desa=[];
    $(document).ready(function() {
        pass_to_array();
    });
    function pass_to_array(){
        ambil_nama_provinsi();
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
                    array_provinsi.push(value.subdistrict.district.city.prov.prov_id);
                    array_kota.push(value.subdistrict.district.city.city_id);
                    array_kecamatan.push(value.subdistrict.district.dis_id);
                    array_desa.push(value.subdistrict.subdis_id);
                });
            }
        });
    };
    function changeFirstElementOfArray(){
        var provinsi=$('#provinsi_2').val();
        array_provinsi[0]=parseInt(provinsi);
        var cities=$('#cities_2').val();
        array_kota[0]=parseInt(cities);
        var districts=$('#districts_2').val();
        array_kecamatan[0]=parseInt(districts);
        var subdistricts=$('#sub_districts_2').val();
        array_desa[0]=parseInt(subdistricts);
    }
    $('#tujuan_lainnya').on('click',function(){
        $('#tujuanLainnyaModal').modal('show');
        $('#route_adding').empty();
        jQuery.each(array_tujuan_id, function(key,value){
            ambil_nama_provinsi(value,array_provinsi[key])
            ambil_nama_kabupaten(value,array_provinsi[key],array_kota[key])
            ambil_nama_kecamatan(value,array_kota[key],array_kecamatan[key])
            ambil_nama_desa(value,array_kecamatan[key],array_desa[key])
            $('#route_adding').append('<tr id="row_new_route_'+value.tujuan_id+'" >\
                <td width="25%">\
                    <input type="hidden" id="tujuan_yang_ke_'+value+'" name="tujuan_ke[]" class="form-control" value='+value+'>\
                    <select id="provinsi_yang_ke_'+value+'" name="provinsi_ke[]" class="form-control" onchange="ambil_nama_kabupaten('+value+','+null+','+null+')">\
                    </select>\
                </td>\
                <td width="25%">\
                    <select id="kota_yang_ke_'+value+'" name="kota_ke[]" class="form-control" onchange="ambil_nama_kecamatan('+value+','+null+','+null+')">\
                    </select>\
                </td>\
                <td width="25%">\
                    <select id="kecamatan_yang_ke_'+value+'" name="kecamatan_ke[]" class="form-control" onchange="ambil_nama_desa('+value+','+null+','+null+')">\
                    </select>\
                </td>\
                <td width="20%">\
                    <select id="desa_yang_ke_'+value+'" name="desa_ke[]" class="form-control" >\
                    </select>\
                </td>\
                <td width="5%">\
                    <a href="#" class="btn btn-primary px-1" onclick="add_route_more('+value+')" id="add_route_more_button_'+value+'"><i class="fa fa-plus"></i></a>\
                    <a href="#" class="btn btn-danger px-1" onclick="delete_this_route('+value+')" id="delete_this_route_button_'+value+'"><i class="fa fa-minus"></i></a>\
                </td>\
            </tr>');
        });
    });
    $('#save_edited_route').on('click',function(){
        array_tujuan_id=[];
        array_provinsi=[];
        array_kota=[];
        array_kecamatan=[];
        array_desa=[];
        var array_tujuan=($("input[name='tujuan_ke[]']").map(function(){return $(this).val();}).get());
        array_tujuan_id.push(array_tujuan);
        var array_prov=($("select[name='provinsi_ke[]']").map(function(){return $(this).val();}).get());
        array_provinsi.push(array_prov);
        var array_city=($("select[name='kota_ke[]']").map(function(){return $(this).val();}).get());
        array_kota.push(array_city);
        var array_district=($("select[name='kecamatan_ke[]']").map(function(){return $(this).val();}).get());
        array_kecamatan.push(array_district);
        var array_subdistrict=($("select[name='desa_ke[]']").map(function(){return $(this).val();}).get());
        array_desa.push(array_subdistrict);
    })
    function ambil_nama_provinsi(tujuan_id,prov_id){
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_province')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#provinsi_yang_ke_'+tujuan_id).empty().append('<option value="">Pilih Provinsi</option>');
                jQuery.each(res, function(key,value){
                    $('#provinsi_yang_ke_'+tujuan_id).append('<option value="'+ value['prov_id'] +'">'+ value['prov_name'] +'</option>');
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
            $('#kecamatan_yang_ke_'+tujuan_id).empty().append('<option value="">Pilih Kecamatan</option>');
            $('#desa_yang_ke_'+tujuan_id).empty().append('<option value="">Pilih Kelurahan/Desa</option>');
        }
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_cities')}}",
            data: {
                provinsi:prov_id,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                if(res){
                    $('#kota_yang_ke_'+tujuan_id).empty().append('<option value="">Pilih Kabupaten/Kota</option>');
                    jQuery.each(res, function(key,value){
                        $('#kota_yang_ke_'+tujuan_id).append('<option value="'+ value['city_id'] +'">'+ value['city_name'] +'</option>');
                    });
                    $('#kota_yang_ke_'+tujuan_id).val(city_id);
                    $('#kecamatan_yang_ke_'+tujuan_id).empty().append('<option value="">Pilih Kecamatan</option>');
                    $('#desa_yang_ke_'+tujuan_id).empty().append('<option value="">Pilih Kelurahan/Desa</option>');
                }else{
                    $('#kota_yang_ke_'+tujuan_id).empty().append('<option value="">Pilih Kabupaten/Kota</option>');
                    $('#kecamatan_yang_ke_'+tujuan_id).empty().append('<option value="">Pilih Kecamatan</option>');
                    $('#desa_yang_ke_'+tujuan_id).empty().append('<option value="">Pilih Kelurahan/Desa</option>');
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
    function ambil_nama_kecamatan(tujuan_id,city_id,dis_id){
        if(city_id==null){
            city_id=$('#kota_yang_ke_'+tujuan_id).val();
        }
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_districts')}}",
            data: {
                cities:city_id,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                if(res){
                    $('#kecamatan_yang_ke_'+tujuan_id).empty().append('<option value="">Pilih Kecamatan</option>');
                    jQuery.each(res, function(key,value){
                        $('#kecamatan_yang_ke_'+tujuan_id).append('<option value="'+ value['dis_id'] +'">'+ value['dis_name'] +'</option>');
                    });
                    $('#kecamatan_yang_ke_'+tujuan_id).val(dis_id);
                    $('#desa_yang_ke_'+tujuan_id).empty().append('<option value="">Pilih Kelurahan/Desa</option>');
                }else{
                    $('#kecamatan_yang_ke_'+tujuan_id).empty().append('<option value="">Pilih Kecamatan</option>');
                    $('#desa_yang_ke_'+tujuan_id).empty().append('<option value="">Pilih Kelurahan/Desa</option>');
                }
            },
            error: function(res){
                swal({
                    title: "Ambil data Kecamatan",
                    text: "Data kecamatan gagal di ambil",
                    icon: "danger",
                });
            }
        });
    }
    function ambil_nama_desa(tujuan_id,dis_id,subdis_id){
        if(dis_id==null){
            dis_id=$('#kecamatan_yang_ke_'+tujuan_id).val();
        }
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_subdistricts')}}",
            data: {
                districts:dis_id,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                if(res){
                    $('#desa_yang_ke_'+tujuan_id).empty().append('<option value="">Pilih Kelurahan/Desa</option>');
                    jQuery.each(res, function(key,value){
                        $('#desa_yang_ke_'+tujuan_id).append('<option value="'+ value['subdis_id'] +'">'+ value['subdis_name'] +'</option>');
                    });
                    $('#desa_yang_ke_'+tujuan_id).val(subdis_id);
                }else{
                    $('#desa_yang_ke_'+tujuan_id).empty().append('<option value="">Pilih Kelurahan/Desa</option>');
                }
            },
            error: function(res){
                swal({
                    title: "Ambil Data Desa",
                    text: "Data desa gagal di ambil",
                    icon: "danger",
                });
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
                $('#instansi').val('');
                $('#nama_instansi').val('');
                $('#keterangan_barang').val('');
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
                $('#instansi').val('');
                $('#nama_instansi').val('');
                $('#keterangan_barang').val('');
            }
        }
    });
</script>
@endsection