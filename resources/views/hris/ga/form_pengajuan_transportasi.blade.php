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
            <a class="btn btn-primary" style="background-color:blue" href="{{route('hris.ga.form_pengajuan_transportasi')}}">Formulir</a>
        </li>
        <li class="nav-item">
            <a class="btn btn-white" href="{{route('hris.ga.data_pengajuan_transportasi')}}">Data</a>
        </li>
    </ul>
</div>

<div class="card-body px-6 py-4" style="border: 1px solid #d8d4dc">
    <div class="row">
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Nama Karyawan</label>
        </div>
        <div class="col-4">
            <select id="selectEmployeeID" name="selectEmployeeID[]" multiple data-placeholder="Pilih karyawan" class="form-control select2 EmployeeID col-11">
                <option value="">Pilih Karyawan</option>
                @foreach ($selectemployee as $r_empl)
                    <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                @endforeach
            </select>
            <h6 id="warning_employee" style="margin-bottom: 0px;padding-top:1px;color:red"></h6>
        </div>
    </div>
    <div class="row pb-2 pt-1">
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Keberangkatan Awal</label>
        </div>
        <div class="col-4 pt-2">
            <a href="#" id="change_initial_destination" style="text-decoration-line: underline">PT. NAG</a><img src="{{URL::asset('assets/images/brand/shortcut.png')}}" width="18">
        </div>
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Tujuan</label>
        </div>
        <div class="col-4 pt-1">
            <button class="btn btn-primary py-1" id="tujuan_lainnya" style="font-weight:bold"> + Daftar Tujuan</button></label>
        </div>
    </div>
    <div class="row pb-2">
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Provinsi</label>
        </div>
        <div class="col-4">
            <select class="form-control col-11" id="provinsi" style="background-color: white">
                <option value="">Pilih Provinsi</option>
                @foreach($provincies as $prov)
                    <option value="{{$prov->prov_id}}">{{$prov->prov_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Provinsi</label>
        </div>
        <div class="col-4">
            <select class="form-control col-11" id="provinsi_2" style="background-color: white">
                <option value="">Pilih Provinsi</option>
                @foreach($provincies as $prov)
                    <option value="{{$prov->prov_id}}">{{$prov->prov_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row pb-2">
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Kabupaten/Kota</label>
        </div>
        <div class="col-4">
            <select class="form-control col-11" id="cities" style="background-color: white">
            </select>
        </div>
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Kabupaten/Kota</label>
        </div>
        <div class="col-4">
            <select class="form-control col-11" id="cities_2" style="background-color: white">
            </select>
        </div>
    </div>
    <div class="row pb-2">
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Kecamatan</label>
        </div>
        <div class="col-4">
            <select class="form-control col-11" id="districts" style="background-color: white">
            </select>
        </div>
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Kecamatan</label>
        </div>
        <div class="col-4">
            <select class="form-control col-11" id="districts_2" style="background-color: white">
            </select>
        </div>
    </div>
    <div class="row pb-2">
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Kelurahan/Desa</label>
        </div>
        <div class="col-4">
            <select class="form-control col-11" id="sub_districts" style="background-color: white">
            </select>
        </div>
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Kelurahan/Desa</label>
        </div>
        <div class="col-4">
            <select class="form-control col-11" id="sub_districts_2" style="background-color: white">
            </select>
        </div>
    </div>
    <div class="row pb-2">
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Detail Alamat</label>
        </div>
        <div class="col-4">
            <input id="detail_alamat" class="form-control col-11" style="background-color: white" placeholder="Nama Gedung, Jalan atau Blok">
        </div>
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Detail Alamat</label>
        </div>
        <div class="col-4">
            <input id="detail_alamat_2" class="form-control col-11" style="background-color: white" placeholder="Nama Gedung, Jalan atau Blok">
        </div>
    </div>
    <div class="row pb-2">
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Tanggal & Jam Pemberangkatan</label>
        </div>
        <div class="col-2">
            <input type="date" id="tanggal_pemberangkatan" class="form-control" style="background-color: white">
        </div>
        <div class="col-2 pr-0">
            <input type="time" id="jam_pemberangkatan" class="form-control col-9" style="background-color: white">
        </div>
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Tanggal & Jam Kedatangan</label>
        </div>
        <div class="col-2">
            <input type="date" id="tanggal_kedatangan" class="form-control" style="background-color: white">
        </div>
        <div class="col-2 pr-0">
            <input type="time" id="jam_kedatangan" class="form-control col-9" style="background-color: white">
        </div>
    </div>
    <div class="row pb-2">
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Tujuan Pemberangkatan</label>
        </div>
        <div class="col-4 pl-4">
            <div class="row">
                <div class="col-xl-4">
                  <input type="checkbox" name="tujuan_pemberangkatan" value="antar_tamu" id="checkbox_1" >&nbsp;Antar tamu
                </div>
                <div class="col-xl-4">
                  <input type="checkbox" name="tujuan_pemberangkatan" value="antar_barang" id="checkbox_3">&nbsp;Antar barang
                </div>
                <div class="col-xl-4">
                  <input type="checkbox" name="tujuan_pemberangkatan" value="antar_dinas" id="checkbox_5">&nbsp;Antar dinas
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4">
                    <input type="checkbox" name="tujuan_pemberangkatan" value="jemput_tamu" id="checkbox_2" >&nbsp;Jemput tamu
                </div>
                <div class="col-xl-4">
                    <input type="checkbox" name="tujuan_pemberangkatan" value="jemput_barang" id="checkbox_4">&nbsp;Jemput barang
                </div>
                <div class="col-xl-4">
                    <input type="checkbox" name="tujuan_pemberangkatan" value="jemput_dinas" id="checkbox_6">&nbsp;Jemput dinas
                </div>
            </div>
        </div>
        <div class="col-2 pt-1">
            <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Jarak Tempuh</label>
        </div>
        <div class="col-1">
            <input type="number" id="jarak_tempuh" class="form-control" style="background-color: white" value="0">
        </div>
        <div class="col-2 pl-0 pt-2">
            <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:10pt">KM</label>
        </div>
    </div>
    <div style="display:none" id="tag_nama_tamu">
        <div class="row pb-2">
            <div class="col-12 pt-1">
                <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Keterangan Tamu</label>
            </div>
        </div>
        <div class="row pb-2">
            <div class="col-2 pt-1">
                <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Nama Tamu</label>
            </div>
            <div class="col-4">
                <input type="text" id="nama_tamu" class="form-control col-11" style="background-color: white" placeholder="Masukkan Nama Tamu">
            </div>
            <div class="col-2 pt-1">
                <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Nomor Hp Tamu</label>
            </div>
            <div class="col-4">
                <input type="text" id="nomor_tamu" class="form-control col-11" style="background-color: white" placeholder="E.g. 089501940612">
            </div>
        </div>
        <div class="row pb-2">
            <div class="col-2 pt-1">
                <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Instansi</label>
            </div>
            <div class="col-4">
                <input type="text" id="instansi_tamu" class="form-control col-11" style="background-color: white" placeholder="Masukkan Instansi Tamu">
            </div>
        </div>
    </div>
    <div style="display:none" id="tag_jenis_barang">
        <div class="row pb-2">
            <div class="col-12 pt-1">
                <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Keterangan Barang</label>
            </div>
        </div>
        <div class="row pb-2">
            <div class="col-6">
                <div class="row pb-2">
                    <div class="col-4 pt-1">
                        <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Jenis Barang</label>
                    </div>
                    <div class="col-8">
                        <input type="text" id="jenis_barang" class="form-control col-11" style="background-color: white" placeholder="Masukkan Jenis Barang">
                    </div>
                </div>
                <div class="row pb-2">
                    <div class="col-4 pt-1">
                        <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Quantity</label>
                    </div>
                    <div class="col-2">
                        <input type="number" id="quantity" class="form-control" style="background-color: white">
                    </div>
                    <div class="col-3 pl-0">
                        <input type="text" id="satuan" name="satuan" class="form-control" style="background-color: white" placeholder="Masukkan satuan">
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="row pb-2">
                    <div class="col-4 pt-1">
                        <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Keterangan</label>
                    </div>
                    <div class="col-8">
                        <textarea id="keterangan_barang" rows="3" cols="7" class="form-control col-11" style="background-color: white"></textarea>
                    </div>
                </div>
                <div class="row pb-2">
                    <div class="col-4 pt-1">
                        <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Instansi</label>
                    </div>
                    <div class="col-8">
                        <input type="text" id="instansi" class="form-control col-11" style="background-color: white" placeholder="Masukkan Instansi">
                    </div>
                </div>
                <div class="row pb-2">
                    <div class="col-4 pt-1">
                        <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Nama Penerima</label>
                    </div>
                    <div class="col-8">
                        <input type="text" id="nama_instansi" class="form-control col-11" style="background-color: white" placeholder="Masukkan Nama">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center">
            <button id="save_request" class="btn btn-success">Submit</button>
        </div>
    </div>
</div>
<div class="modal fade" id="tujuanLainnyaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 94%">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                <button onclick="simpan_array_tujuan()" aria-label="Close" style="background-color: rgb(255, 255, 255)"><span class="fa fa-times"></span></button>
            </div>
            <div class="modal-body" style="height:1000px">
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
                        <th>
                            Detail Alamat
                        </th>
                        <th>
                            Tgl Kedatangan
                        </th>
                        <th>
                            Jam Kedatangan
                        </th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" value="1" id="tujuan_yang_ke_1" name="tujuan_ke[]">
                            <select class="form-control" id="provinsi_yang_ke_1" name="provinsi_ke[]" style="background-color: white" disabled>
                                <option value="">Pilih Provinsi</option>
                                @foreach($provincies as $prov)
                                    <option value="{{$prov->prov_id}}">{{$prov->prov_name}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="form-control" id="kota_yang_ke_1" name="kota_ke[]" style="background-color: white" disabled>
                                <option value="">Pilih Kabupaten/Kota</option>
                                @foreach($cities as $city)
                                    <option value="{{$city->city_id}}">{{$city->city_name}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="form-control" id="kecamatan_yang_ke_1" name="kecamatan_ke[]" style="background-color: white" disabled>
                                <option value="">Pilih Kecamatan</option>
                                @foreach($districts as $dis)
                                    <option value="{{$dis->dis_id}}">{{$dis->dis_name}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="form-control" id="desa_yang_ke_1" name="desa_ke[]" style="background-color: white" disabled>
                                <option value="">Pilih Kelurahan/Desa</option>
                                @foreach($subdistricts as $subdis)
                                    <option value="{{$subdis->subdis_id}}">{{$subdis->subdis_name}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input id="detail_alamat_yang_ke_1" name="detail_alamat_ke[]" class="form-control" disabled>
                        </td>
                        <td>
                            <input type="date" id="tanggal_kedatangan_yang_ke_1" name="tanggal_kedatangan_ke[]" class="form-control" disabled>
                        </td>
                        <td>
                            <input type="time" id="jam_kedatangan_yang_ke_1" name="jam_kedatangan_ke[]" class="form-control" disabled>
                        </td>
                        <td>
                            <a href="#" id="add_route_satu" class="btn btn-primary px-1" onclick="add_route()"><i class="fa fa-plus"></i></a>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-body" style="height:90px">
                <div class="row">
                    <div class="col-12 text-center">
                        <button class="btn btn-success" onclick="simpan_array_tujuan()">Simpan</button>
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
    var count=1;
    function add_route(){
        count++;
        document.getElementById('add_route_satu').style.display='none';
        ambil_nama_provinsi(count);
        $('#route_adding').append('<tr id="row_new_route_'+count+'" >\
            <td>\
                <input type="hidden" value="'+count+'" id="tujuan_yang_ke_'+count+'" name="tujuan_ke[]">\
                <select id="provinsi_yang_ke_'+count+'" name="provinsi_ke[]" class="form-control" style="background-color:white" onchange="ambil_nama_kabupaten('+count+')">\
                </select>\
            </td>\
            <td>\
                <select id="kota_yang_ke_'+count+'" name="kota_ke[]" class="form-control" style="background-color:white" disabled onchange="ambil_nama_kecamatan('+count+')">\
                    <option value="">Pilih Kabupaten/Kota</option>\
                </select>\
            </td>\
            <td>\
                <select id="kecamatan_yang_ke_'+count+'" name="kecamatan_ke[]" class="form-control" style="background-color:white" disabled onchange="ambil_nama_desa('+count+')">\
                    <option value="">Pilih Kecamatan</option>\
                </select>\
            </td>\
            <td>\
                <select id="desa_yang_ke_'+count+'" name="desa_ke[]" class="form-control" style="background-color:white" disabled onchange="ambil_desa('+count+')">\
                    <option value="">Pilih Kelurahan/Desa</option>\
                </select>\
            </td>\
            <td>\
                <input id="detail_alamat_yang_ke_'+count+'" name="detail_alamat_ke[]" class="form-control" style="background-color:white" onkeyup="isi_detail_alamat('+count+')">\
            </td>\
            <td>\
                <input type="date" id="tanggal_kedatangan_yang_ke_'+count+'" name="tanggal_kedatangan_ke[]" class="form-control" style="background-color:white" onchange="isi_tanggal_kedatangan('+count+')">\
            </td>\
            <td>\
                <input type="time" id="jam_kedatangan_yang_ke_'+count+'" name="jam_kedatangan_ke[]" class="form-control" style="background-color:white" onchange="isi_jam_kedatangan('+count+')">\
            </td>\
            <td>\
                <a href="#" class="btn btn-primary px-1" onclick="add_route_more('+count+')" id="add_route_more_button_'+count+'"><i class="fa fa-plus"></i></a>\
                <a href="#" class="btn btn-danger px-1" onclick="delete_this_route('+count+')" id="delete_this_route_button_'+count+'"><i class="fa fa-minus"></i></a>\
            </td>\
        </tr>');
    }
    function simpan_array_tujuan(){
        var tujuan = $("input[name='tujuan_ke[]']").map(function(){return $(this).val();}).get();
        var province = $("select[name='provinsi_ke[]']").map(function(){return $(this).val();}).get();
        var city = $("select[name='kota_ke[]']").map(function(){return $(this).val();}).get();
        var district = $("select[name='kecamatan_ke[]']").map(function(){return $(this).val();}).get();
        var subdistrict = $("select[name='desa_ke[]']").map(function(){return $(this).val();}).get();
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
            $('#tujuanLainnyaModal').modal('hide');
        }
        console.log(province);
        console.log(city);
        console.log(district);
        console.log(subdistrict);
        console.log(detail_alamat);
    }
    function delete_this_route(count){
        var tujuan = $("input[name='tujuan_ke[]']").map(function(){return $(this).val();}).get();
        tujuan.splice(count, 1);
        var province = $("select[name='provinsi_ke[]']").map(function(){return $(this).val();}).get();
        province.splice(count, 1);
        var city = $("select[name='kota_ke[]']").map(function(){return $(this).val();}).get();
        city.splice(count, 1);
        var district = $("select[name='kecamatan_ke[]']").map(function(){return $(this).val();}).get();
        district.splice(count, 1);
        var subdistrict = $("select[name='desa_ke[]']").map(function(){return $(this).val();}).get();
        subdistrict.splice(count, 1);
        var detail_alamat = $("input[name='detail_alamat_ke[]']").map(function(){return $(this).val();}).get();
        detail_alamat.splice(count, 1);
        var tanggal_kedatangan = $("input[name='tanggal_kedatangan_ke[]']").map(function(){return $(this).val();}).get();
        tanggal_kedatangan.splice(count, 1);
        var jam_kedatangan = $("input[name='jam_kedatangan_ke[]']").map(function(){return $(this).val();}).get();
        jam_kedatangan.splice(count, 1);
        var row = document.getElementById('row_new_route_'+count);
        row.parentNode.removeChild(row);
        if(tujuan.length==2){
            document.getElementById('add_route_satu').style.display='block';
        }
    }
    function add_route_more(count){
        var provinsi=$('#provinsi_yang_ke_'+count).val();
        var city=$('#kota_yang_ke_'+count).val();
        var districts=$('#kecamatan_yang_ke_'+count).val();
        var subdistricts=$('#desa_yang_ke_'+count).val();
        var detail_alamat=$('#detail_alamat_yang_ke_'+count).val();
        var tanggal_kedatangan=$('#tanggal_kedatangan_yang_ke_'+count).val();
        var jam_kedatangan=$('#jam_kedatangan_yang_ke_'+count).val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.add_another_route')}}",
            data: {
                provinsi:provinsi,
                city:city,
                districts:districts,
                subdistricts:subdistricts,
                detail_alamat:detail_alamat,
                tanggal_kedatangan:tanggal_kedatangan,
                jam_kedatangan:jam_kedatangan
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                document.getElementById("provinsi_yang_ke_"+count).style.border="";
                document.getElementById("kota_yang_ke_"+count).style.border="";
                document.getElementById("kecamatan_yang_ke_"+count).style.border="";
                document.getElementById("desa_yang_ke_"+count).style.border="";
                document.getElementById("detail_alamat_yang_ke_"+count).style.border="";
                document.getElementById("tanggal_kedatangan_yang_ke_"+count).style.border="";
                document.getElementById("jam_kedatangan_yang_ke_"+count).style.border="";
                document.getElementById("add_route_more_button_"+count).style.display="none";
                add_route();
            },
            error: function(error){
                let err_log=error.responseJSON.errors;
                if(typeof(err_log.provinsi)!=='undefined'){
                    document.getElementById("provinsi_yang_ke_"+count).style.border = "1px solid red";
                }else{
                    document.getElementById("provinsi_yang_ke_"+count).style.border="";
                }
                if(typeof(err_log.city)!=='undefined'){
                    document.getElementById("kota_yang_ke_"+count).style.border = "1px solid red";
                }else{
                    document.getElementById("kota_yang_ke_"+count).style.border="";
                }
                if(typeof(err_log.districts)!=='undefined'){
                    document.getElementById("kecamatan_yang_ke_"+count).style.border = "1px solid red";
                }else{
                    document.getElementById("kecamatan_yang_ke_"+count).style.border="";
                }
                if(typeof(err_log.subdistricts)!=='undefined'){
                    document.getElementById("desa_yang_ke_"+count).style.border = "1px solid red";
                }else{
                    document.getElementById("desa_yang_ke_"+count).style.border="";
                }
                if(typeof(err_log.detail_alamat)!=='undefined'){
                    document.getElementById("detail_alamat_yang_ke_"+count).style.border = "1px solid red";
                }else{
                    document.getElementById("detail_alamat_yang_ke_"+count).style.border="";
                }
                if(typeof(err_log.tanggal_kedatangan)!=='undefined'){
                    document.getElementById("tanggal_kedatangan_yang_ke_"+count).style.border = "1px solid red";
                }else{
                    document.getElementById("tanggal_kedatangan_yang_ke_"+count).style.border="";
                }
                if(typeof(err_log.jam_kedatangan)!=='undefined'){
                    document.getElementById("jam_kedatangan_yang_ke_"+count).style.border = "1px solid red";
                }else{
                    document.getElementById("jam_kedatangan_yang_ke_"+count).style.border="";
                }
            }
        });
    }
    function ambil_nama_provinsi(count){
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_province')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#provinsi_yang_ke_'+count).empty().append('<option value="">Pilih Provinsi</option>');
                jQuery.each(res, function(key,value){
                    $('#provinsi_yang_ke_'+count).append('<option value="'+ value['prov_id'] +'">'+ value['prov_name'] +'</option>');
                });
            },
            error: function(res){
                swal({
                    title: "Ambil data kota",
                    text: "Data kota gagal di ambil",
                    icon: "danger",
                });
            }
        });
    }
    function ambil_nama_kabupaten(count){
        var provinsi=$('#provinsi_yang_ke_'+count).val();
        if(provinsi==''){
            document.getElementById("kota_yang_ke_"+count).disabled=true;
            $('#kota_yang_ke_').empty().append('<option value="">Pilih Kabupaten/Kota</option>');
            $('#kota_yang_ke_'+count).val('');
            document.getElementById("kecamatan_yang_ke_"+count).disabled=true;
            $('#kecamatan_yang_ke_').empty().append('<option value="">Pilih Kecamatan</option>');
            $('#kecamatan_yang_ke_'+count).val('');
            document.getElementById("desa_yang_ke_"+count).disabled=true;
            $('#desa_yang_ke_').empty().append('<option value="">Pilih Kelurahan/Desa</option>');
            $('#desa_yang_ke_'+count).val('');
        }else{
            $.ajax({
                type:"POST",
                url: "{{route('hris.ga.get_cities')}}",
                data: {
                    provinsi:provinsi,
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(res){
                    $('#kota_yang_ke_'+count).empty().append('<option value="">Pilih Kabupaten/Kota</option>');
                    document.getElementById("kota_yang_ke_"+count).disabled=false;
                    $('#kota_yang_ke_'+count).val('');
                    jQuery.each(res, function(key,value){
                        $('#kota_yang_ke_'+count).append('<option value="'+ value['city_id'] +'">'+ value['city_name'] +'</option>');
                    });
                    document.getElementById("provinsi_yang_ke_"+count).style.border="";
                    $('#kecamatan_yang_ke_').empty().append('<option value="">Pilih Kecamatan</option>');
                    document.getElementById("kecamatan_yang_ke_"+count).disabled=true;
                    $('#kecamatan_yang_ke_'+count).val('');
                    $('#desa_yang_ke_').empty().append('<option value="">Pilih Kelurahan/Desa</option>');
                    document.getElementById("desa_yang_ke_"+count).disabled=true;
                    $('#desa_yang_ke_'+count).val('');
                },
                error: function(res){
                    swal({
                        title: "Ambil data kota",
                        text: "Data kota gagal di ambil",
                        icon: "danger",
                    });
                }
            });
        }
    }
    function ambil_nama_kecamatan(count){
        var cities=$('#kota_yang_ke_'+count).val();
        if(cities==''){
            document.getElementById("kecamatan_yang_ke_"+count).disabled=true;
            $('#kecamatan_yang_ke_').empty().append('<option value="">Pilih Kecamatan</option>');
            $('#kecamatan_yang_ke_'+count).val('');
            document.getElementById("desa_yang_ke_"+count).disabled=true;
            $('#desa_yang_ke_').empty().append('<option value="">Pilih Kelurahan/Desa</option>');
            $('#desa_yang_ke_'+count).val('');
        }else{
            $.ajax({
                type:"POST",
                url: "{{route('hris.ga.get_districts')}}",
                data: {
                    cities:cities,
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(res){
                    $('#kecamatan_yang_ke_'+count).empty().append('<option value="">Pilih Kecamatan</option>');
                    document.getElementById("kecamatan_yang_ke_"+count).disabled=false;
                    $('#kecamatan_yang_ke_'+count).val('');
                    document.getElementById("kota_yang_ke_"+count).style.border="";
                    jQuery.each(res, function(key,value){
                        $('#kecamatan_yang_ke_'+count).append('<option value="'+ value['dis_id'] +'">'+ value['dis_name'] +'</option>');
                    });
                    $('#desa_yang_ke_').empty().append('<option value="">Pilih Kelurahan/Desa</option>');
                    $('#desa_yang_ke_'+count).val('');
                },
                error: function(res){
                    swal({
                        title: "Ambil data desa",
                        text: "Data desa gagal di ambil",
                        icon: "danger",
                    });
                }
            });
        }
    }
    function ambil_nama_desa(count){
        var districts=$('#kecamatan_yang_ke_'+count).val();
        if(districts==''){
            document.getElementById("desa_yang_ke_"+count).disabled=true;
            $('#desa_yang_ke_').empty().append('<option value="">Pilih Kelurahan/Desa</option>');
            $('#desa_yang_ke_'+count).val('');
        }else{
            $.ajax({
                type:"POST",
                url: "{{route('hris.ga.get_subdistricts')}}",
                data: {
                    districts:districts,
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(res){
                    $('#desa_yang_ke_'+count).empty().append('<option value="">Pilih Kelurahan/Desa</option>');
                    document.getElementById("desa_yang_ke_"+count).disabled=false;
                    document.getElementById("kecamatan_yang_ke_"+count).style.border="";
                    $('#desa_yang_ke_'+count).val('');
                    jQuery.each(res, function(key,value){
                        $('#desa_yang_ke_'+count).append('<option value="'+ value['subdis_id'] +'">'+ value['subdis_name'] +'</option>');
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
        }
    }
    function ambil_desa(count){
        var districts=$('#kecamatan_yang_ke_'+count).val();
        if(districts!=''){
            document.getElementById("desa_yang_ke_"+count).style.border="";
        }
    }
    function isi_detail_alamat(count){
        var detail_alamat=$('#detail_alamat_yang_ke_'+count).val();
        if(detail_alamat!=''){
            document.getElementById("detail_alamat_yang_ke_"+count).style.border="";
        }
    }
    function isi_tanggal_kedatangan(count){
        var tanggal_kedatangan=$('#tanggal_kedatangan_yang_ke_'+count).val();
        if(tanggal_kedatangan!=''){
            document.getElementById("tanggal_kedatangan_yang_ke_"+count).style.border="";
        }
    }
    function isi_jam_kedatangan(count){
        var jam_kedatangan=$('#jam_kedatangan_yang_ke_'+count).val();
        if(jam_kedatangan!=''){
            document.getElementById("jam_kedatangan_yang_ke_"+count).style.border="";
        }
    }
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
            dropdownCssClass: 'hover-success',// disabling search
        });
        $('#tujuan_lainnya').on('click',function(){
            var provinsi=$('#provinsi_2').val();
            var city=$('#cities_2').val();
            var districts=$('#districts_2').val();
            var subdistricts=$('#sub_districts_2').val();
            var detail_alamat=$('#detail_alamat_2').val();
            var tanggal_kedatangan=$('#tanggal_kedatangan').val();
            var jam_kedatangan=$('#jam_kedatangan').val();
            $.ajax({
                type:"POST",
                url: "{{route('hris.ga.add_another_route')}}",
                data: {
                    provinsi:provinsi,
                    city:city,
                    districts:districts,
                    subdistricts:subdistricts,
                    detail_alamat:detail_alamat,
                    tanggal_kedatangan:tanggal_kedatangan,
                    jam_kedatangan:jam_kedatangan
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(res){
                    document.getElementById("provinsi_2").style.border="";
                    document.getElementById("cities_2").style.border="";
                    document.getElementById("districts_2").style.border="";
                    document.getElementById("sub_districts_2").style.border="";
                    document.getElementById("tanggal_kedatangan").style.border="";
                    document.getElementById("jam_kedatangan").style.border="";
                    $('#tujuanLainnyaModal').modal('show');
                    $('#provinsi_yang_ke_1').val(provinsi);
                    $('#kota_yang_ke_1').val(city);
                    $('#kecamatan_yang_ke_1').val(districts);
                    $('#desa_yang_ke_1').val(subdistricts);
                    $('#detail_alamat_yang_ke_1').val(detail_alamat);
                    $('#tanggal_kedatangan_yang_ke_1').val(tanggal_kedatangan);
                    $('#jam_kedatangan_yang_ke_1').val(jam_kedatangan);
                },
                error: function(error){
                    let err_log=error.responseJSON.errors;
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
                }
            });
        });
        $('#change_initial_destination').on('click',function(){
            var province=12;
            var city=161;
            var district=2196;
            var subdistrict=30109;
            $.ajax({
                type:"POST",
                url: "{{route('hris.ga.get_province')}}",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(res){
                    $('#provinsi').empty().append('<option value="">Pilih Provinsi</option>');
                    jQuery.each(res, function(key,value){
                        $('#provinsi').append('<option value="'+ value['prov_id'] +'">'+ value['prov_name'] +'</option>');
                    });
                    $('#provinsi').val(province);
                },
                error: function(res){
                    swal({
                        title: "Ambil data provinsi",
                        text: "Data provinsi gagal di ambil",
                        icon: "danger",
                    });
                }
            });
            $.ajax({
                type:"POST",
                url: "{{route('hris.ga.get_cities')}}",
                data: {
                    provinsi:province,
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(res){
                    $('#cities').empty().append('<option value="">Pilih Kabupaten/Kota</option>');
                    document.getElementById("cities").disabled=false;
                    jQuery.each(res, function(key,value){
                        $('#cities').append('<option value="'+ value['city_id'] +'">'+ value['city_name'] +'</option>');
                    });
                    $('#cities').val(city)
                },
                error: function(res){
                    swal({
                        title: "Ambil data kota",
                        text: "Data kota gagal di ambil",
                        icon: "danger",
                    });
                }
            });
            $.ajax({
                type:"POST",
                url: "{{route('hris.ga.get_districts')}}",
                data: {
                    cities:city,
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(res){
                    $('#districts').empty().append('<option value="">Pilih Kecamatan</option>');
                    document.getElementById("districts").disabled=false;
                    jQuery.each(res, function(key,value){
                        $('#districts').append('<option value="'+ value['dis_id'] +'">'+ value['dis_name'] +'</option>');
                    });
                    $('#districts').val(district);
                },
                error: function(res){
                    swal({
                        title: "Ambil data desa",
                        text: "Data desa gagal di ambil",
                        icon: "danger",
                    });
                }
            });
            $.ajax({
                type:"POST",
                url: "{{route('hris.ga.get_subdistricts')}}",
                data: {
                    districts:district,
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(res){
                    $('#sub_districts').empty().append('<option value="">Pilih Kelurahan/Desa</option>');
                    document.getElementById("sub_districts").disabled=false;
                    jQuery.each(res, function(key,value){
                        $('#sub_districts').append('<option value="'+ value['subdis_id'] +'">'+ value['subdis_name'] +'</option>');
                    });
                    $('#sub_districts').val(subdistrict);
                },
                error: function(res){
                    swal({
                        title: "Ambil data desa",
                        text: "Data desa gagal di ambil",
                        icon: "danger",
                    });
                }
            });
            $('#detail_alamat').val('PT. Nirwana Alabare Garment');
        });
        $('#provinsi').on('change',function(){
            var provinsi=$('#provinsi').val();
            if(provinsi==''){
                document.getElementById("cities").disabled=true;
                $('#cities').empty().append('<option value="">Pilih Kabupaten/Kota</option>');
                document.getElementById("districts").disabled=true;
                $('#districts').empty().append('<option value="">Pilih Kecamatan/option>');
                document.getElementById("sub_districts").disabled=true;
                $('#sub_districts').empty().append('<option value="">Pilih Kelurahan/Desa</option>');
            }else{
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
            }
        });
        $('#cities').on('change',function(){
            var cities=$('#cities').val();
            if(cities==''){
                document.getElementById("districts").disabled=true;
                $('#districts').empty().append('<option value="">Pilih Kecamatan</option>');
            }else{
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
            }
        });
        $('#districts').on('change',function(){
            var districts=$('#districts').val();
            if(districts==''){
                document.getElementById("sub_districts").disabled=true;
                $('#sub_districts').empty().append('<option value="">Pilih Kelurahan/Desa</option>');
            }else{
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
            }
        });
        $('#provinsi_2').on('change',function(){
            var provinsi=$('#provinsi_2').val();
            if(provinsi==''){
                document.getElementById("cities_2").disabled=true;
                $('#cities_2').empty().append('<option value="">Pilih Kabupaten/Kota</option>');
                document.getElementById("districts_2").disabled=true;
                $('#districts_2').empty().append('<option value="">Pilih Kecamatan</option>');
                document.getElementById("sub_districts_2").disabled=true;
                $('#sub_districts_2').empty().append('<option value="">Pilih Kelurahan/Desa</option>');
            }else{
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
            }
        });
        $('#cities_2').on('change',function(){
            var cities=$('#cities_2').val();
            if(cities==''){
                document.getElementById("districts_2").disabled=true;
                $('#districts_2').empty().append('<option value="">Pilih Kecamatan</option>');
                document.getElementById("sub_districts_2").disabled=true;
                $('#sub_districts_2').empty().append('<option value="">Pilih Kelurahan/Desa</option>');
            }else{
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
            }
        });
        $('#districts_2').on('change',function(){
            var districts=$('#districts_2').val();
            if(districts==''){
                document.getElementById("sub_districts_2").disabled=true;
                $('#sub_districts_2').empty().append('<option value="">Pilih Kelurahan/Desa</option>');
            }else{
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
                    $('#satuan').val('');
                    $('#instansi').val('');
                    $('#nama_instansi').val('');
                    $('#keterangan_barang').val('');
                }
            }
        });
        $('#selectEmployeeID').on('change',function(){
            var employee_array = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
            if(employee_array.length>0){
                document.getElementById("warning_employee").innerHTML='';
            }
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
        $('#save_request').on('click',function(){
            var employee='';
            var employee_array = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
            if(employee_array.length>0){
                var employee=employee_array.toString();
            }
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
            var nama_tamu=$("#nama_tamu").val();
            var nomor_tamu=$("#nomor_tamu").val();
            var instansi_tamu=$("#instansi_tamu").val();
            var jenis_barang=$("#jenis_barang").val();
            var quantity=$("#quantity").val();
            var satuan=$("#satuan").val();
            var instansi=$("#instansi").val();
            var nama_instansi=$("#nama_instansi").val();
            var keterangan_barang=$("#keterangan_barang").val();
            $('#provinsi_yang_ke_1').val(provinsi_2);
            $('#kota_yang_ke_1').val(cities_2);
            $('#kecamatan_yang_ke_1').val(districts_2);
            $('#desa_yang_ke_1').val(sub_districts_2);
            $('#detail_alamat_yang_ke_1').val(detail_alamat_2);
            $('#tanggal_kedatangan_yang_ke_1').val(tanggal_kedatangan);
            $('#jam_kedatangan_yang_ke_1').val(jam_kedatangan);
            var tujuan_array = $("input[name='tujuan_ke[]']").map(function(){return $(this).val();}).get();
            var provinsi_array = $("select[name='provinsi_ke[]']").map(function(){return $(this).val();}).get();
            var city_array = $("select[name='kota_ke[]']").map(function(){return $(this).val();}).get();
            var district_array = $("select[name='kecamatan_ke[]']").map(function(){return $(this).val();}).get();
            var subdistrict_array = $("select[name='desa_ke[]']").map(function(){return $(this).val();}).get();
            var detail_alamat_array = $("input[name='detail_alamat_ke[]']").map(function(){return $(this).val();}).get();
            var tanggal_kedatangan_array = $("input[name='tanggal_kedatangan_ke[]']").map(function(){return $(this).val();}).get();
            var jam_kedatangan_array = $("input[name='jam_kedatangan_ke[]']").map(function(){return $(this).val();}).get();
            $.ajax({
                type:"POST",
                url: "{{route('hris.ga.post_car_request')}}",
                data: {
                    employee:employee,
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
                    tujuan:tujuan,
                    tujuan_array:tujuan_array,
                    provinsi_array:provinsi_array,
                    city_array:city_array,
                    district_array:district_array,
                    subdistrict_array:subdistrict_array,
                    detail_alamat_array:detail_alamat_array,
                    tanggal_kedatangan_array:tanggal_kedatangan_array,
                    jam_kedatangan_array:jam_kedatangan_array
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(res){
                    console.log(res);
                    swal("", "Permintaan transportasi terkirim", "success");
                    $('#provinsi').val('');
                    $('#cities').val('');
                    $('#districts').val('');
                    $('#sub_districts').val('');
                    $('#detail_alamat').val('');
                    $('#provinsi_2').val('');
                    $('#cities_2').val('');
                    $('#districts_2').val('');
                    $('#sub_districts_2').val('');
                    $('#detail_alamat_2').val('');
                    $('#tanggal_pemberangkatan').val('');
                    $('#jam_pemberangkatan').val('');
                    $('#tanggal_kedatangan').val('');
                    $('#jam_pemberangkatan').val('');
                    $('#jam_kedatangan').val('');
                    $('#jarak_tempuh').val(0);
                    $('#selectEmployeeID').val(null).trigger('change');
                    document.getElementById("checkbox_1").checked=false;
                    document.getElementById("checkbox_2").checked=false;
                    document.getElementById("checkbox_3").checked=false;
                    document.getElementById("checkbox_4").checked=false;
                    document.getElementById("checkbox_5").checked=false;
                    document.getElementById("checkbox_6").checked=false;
                    document.getElementById('tag_nama_tamu').style.display='none';
                    document.getElementById('tag_jenis_barang').style.display='none';
                    $("#nama_tamu").val('');
                    $("#nomor_tamu").val('');
                    $("#instansi_tamu").val('');
                    $("#jenis_barang").val('');
                    $("#quantity").val('');
                    $("#satuan").val('');
                    $("#instansi").val('');
                    $("#nama_instansi").val('');
                    $("#keterangan_barang").val('');
                    document.getElementById("warning_employee").innerHTML='';
                    document.getElementById("provinsi").style.border="";
                    document.getElementById("cities").style.border="";
                    document.getElementById("districts").style.border="";
                    document.getElementById("sub_districts").style.border="";
                    document.getElementById("detail_alamat").style.border="";
                    document.getElementById("tanggal_pemberangkatan").style.border="";
                    document.getElementById("jam_pemberangkatan").style.border="";
                    document.getElementById("tanggal_kedatangan").style.border="";
                    document.getElementById("jam_kedatangan").style.border="";
                    document.getElementById("provinsi_2").style.border="";
                    document.getElementById("cities_2").style.border="";
                    document.getElementById("districts_2").style.border="";
                    document.getElementById("sub_districts_2").style.border="";
                    document.getElementById("nama_tamu").style.border="";
                    document.getElementById("nomor_tamu").style.border="";
                    document.getElementById("instansi_tamu").style.border="";
                    document.getElementById("nama_tamu").style.border="";
                    document.getElementById("nomor_tamu").style.border="";
                    document.getElementById("instansi").style.border="";
                    document.getElementById("jenis_barang").style.border="";
                    document.getElementById("quantity").style.border="";
                    document.getElementById("satuan").style.border="";
                    document.getElementById("instansi").style.border="";
                    document.getElementById("nama_instansi").style.border="";
                    document.getElementById("keterangan_barang").style.border="";
                },
                error: function(error){
                    let err_log=error.responseJSON.errors;
                    if(error.status==422){
                        if(typeof(err_log.employee)!=='undefined'){
                            document.getElementById("warning_employee").innerHTML='Karyawan '+error.responseJSON.errors.employee[0];
                        }else{
                            document.getElementById("warning_employee").innerHTML='';
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
    });
    $(document).ready(function() {
        document.getElementById('cities').disabled=true;
        document.getElementById('districts').disabled=true;
        document.getElementById('sub_districts').disabled=true;
        $('#cities').append('<option value="">Pilih Kabupaten/Kota</option>');
        $('#districts').append('<option value="">Pilih Kecamatan</option>');
        $('#sub_districts').append('<option value="">Pilih Kelurahan/Desa</option>');
        document.getElementById('cities_2').disabled=true;
        document.getElementById('districts_2').disabled=true;
        document.getElementById('sub_districts_2').disabled=true;
        $('#cities_2').append('<option value="">Pilih Kabupaten/Kota</option>');
        $('#districts_2').append('<option value="">Pilih Kecamatan</option>');
        $('#sub_districts_2').append('<option value="">Pilih Kelurahan/Desa</option>');
    });
</script>
@endsection