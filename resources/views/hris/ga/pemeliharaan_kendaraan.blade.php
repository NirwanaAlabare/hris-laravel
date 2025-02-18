@extends('admin.adminlayouts.adminlayout4')

@section('head')
<link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<!---Sweetalert Css-->
<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{ URL::asset('assets/css/iziToast.min.css') }}">
<style>
    #datatable tr td {
        height: 4px;
        line-height: 0.3;
    }
</style>

@stop
@section('mainarea')

    <div class="card-header mt-7 pb-0">
        <ul class="nav nav-tabs pl-3">
            <li class="nav-item pr-4 pb-1">
                <h6 style="font-weight:bold;color:rgb(0, 0, 91)">Master Form</h6>
            </li>
            <li class="nav-item">
                <a href="{{route('hris.ga.vehicle_monitoring')}}"><h6 style="font-weight:bold; color:rgb(195, 195, 195)">Monitoring</h6></a>
            </li>
        </ul>
    </div>
    {{-- <div class="row">
        <div class="col-6">
            <div id="accordion">
                <div class="card">
                    <div class="card-header py-2 bg-primary" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link text-white py-0" style="font-weight:bold" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Master Ketegori Item
                            </button>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body"> --}}
                            {{-- <div class="row pl-4">
                                <div class="col-4 pt-1">
                                    <h6 style="font-weight: bold; font-size:12pt">Kategori Item</h6>
                                </div>
                                <div class="col-6">
                                    <input type="hidden" id="id_kategori_item" class="form-control" style="background-color: white">
                                    <input type="text" id="category_name" class="form-control" style="background-color: white">
                                </div>
                            </div> --}}
                            {{-- <div class="row pl-4 pt-3">
                                <div class="col-12 text-center pt-1">
                                    <button class="btn btn-success py-1" id="submit_category" onclick="submit_category('add')"><i class="fa fa-save"></i> Save</button>
                                    <button class="btn btn-warning text-dark py-1" id="update_category" onclick="submit_category('update')"><i class="fa fa-edit"></i> Update</button>
                                    <button id="new_category" class="btn btn-info py-1"><i class="fa fa-plus"></i> Tambah Baru</button>
                                </div>
                            </div> --}}
                            {{-- <div class="row pl-4 pt-3">
                                <div class="col-12 pt-1">
                                    <table class="table table-bordered w-100" id="datatable_3">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="50%">Nama Kategori</th>
                                                <th width="35%">Dibuat tanggal</th>
                                                <th width="10%"><i class="fa fa-cog"></i></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div> --}}
                        {{-- </div>
                    </div>
                </div>
            </div>
        </div> --}}
        {{-- <div class="col-6">
            <div id="accordion">
                <div class="card">
                    <div class="card-header py-2 bg-primary" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link text-white py-0" style="font-weight:bold" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                Master Item Kendaraan
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row pl-4">
                                <div class="col-4 pt-1">
                                    <h6 style="font-weight: bold; font-size:12pt">Kategori Item</h6>
                                </div>
                                <div class="col-6">
                                    <input type="hidden" class="form-control bg-white" id="id_item">
                                    <select class="form-control" id="category_name_2" style="background-color:white">
                                    </select>
                                </div>
                            </div>
                            <div class="row pl-4 pt-1">
                                <div class="col-4 pt-1">
                                    <h6 style="font-weight: bold; font-size:12pt">Nama Item</h6>
                                </div>
                                <div class="col-6">
                                    <input type="type" class="form-control bg-white" id="item_name" style="background-color: white">
                                </div>
                            </div>
                            <div class="row pl-4 pt-3">
                                <div class="col-12 pt-1">
                                    <table class="table table-bordered w-100" id="datatable_4">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="35%">Nama Kategori</th>
                                                <th width="25%">Nama Item</th>
                                                <th width="25%">Dibuat tanggal</th>
                                                <th width="10%"><i class="fa fa-cog"></i></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="row py-4 bg-primary">
                                <div class="col-12 text-center">
                                    <button class="btn btn-success btn-sm" id="store_item" onclick="update_item('add')"><i class="fa fa-save"></i> Simpan</button>
                                    <button class="btn btn-warning text-dark btn-sm" id="update_item" onclick="update_item('update')" disabled><i class="fa fa-edit"></i> Update</button>
                                    <button class="btn btn-danger btn-sm" onclick="clear_item_input()"><i class="fa fa-plus"></i> Tambah Baru</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    {{-- <div class="container-fluid content-row">
        <div class="card-body px-6 pb-4 pt-6" style="border: 1px solid #d8d4dc">
            <div class="row">
                <div class="col-4">
                    <div class="row">
                        <div class="col-12 pt-4 pb-3 pl-5 border border-dark" style="background-color: rgb(216, 216, 216)">
                            <label class="form-label">FORM MASTER KATEGORI ITEM</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 px-3 pb-4 pt-0">
                            <div class="row pt-4 px-4 bg-primary">
                                <div class="col-6 pt-1">
                                    <label class="form-label">NAMA BARANG</label>
                                </div>
                                <div class="col-6">
                                    <label class="form-label"><input type="hidden" class="form-control bg-white" id="id_master_kendaraan"><input type="type" class="form-control bg-white" id="nama_barang"></label>
                                </div>
                            </div>
                            <div class="row pt-1 px-4 bg-primary">
                                <div class="col-6 pt-2">
                                    <label class="form-label">JADWAL PEMELIHARAAN</label>
                                </div>
                                <div class="col-6">
                                    <table>
                                        <tr>
                                            <td><input type="number" class="form-control" id="quantity_pemeliharaan"></td>
                                            <td>
                                                <select class="form-control" id="satuan_pemeliharaan">
                                                    <option value="">Pilih Satuan</option>
                                                    <option value="hari">Hari</option>
                                                    <option value="minggu">Minggu</option>
                                                    <option value="bulan">Bulan</option>
                                                    <option value="tahun">Tahun</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="row py-4 bg-primary">
                                <div class="col-12 text-center">
                                    <button class="btn btn-success btn-sm" onclick="update_pemeliharaan('add')"><i class="fa fa-plus"></i> Tambah</button>
                                    <button class="btn btn-warning text-dark btn-sm" id="update_jenis_pemeliharaan" onclick="update_pemeliharaan('update')" disabled><i class="fa fa-edit"></i> Update</button>
                                    <button class="btn btn-danger btn-sm" onclick="clear_jenis_pemeliharaan()"><i class="fa fa-trash"></i> Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8 pl-8 pr-6">
                    <table class="table table-bordered w-100" id="datatable">
                        <thead>
                            <tr>
                                <th width="20">No</th>
                                <th>Nama Barang</th>
                                <th>Jadwal Pemeliharaan</th>
                                <th width="10"><i class="fa fa-cog"></i></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="container-fluid content-row">
        <div class="card-body px-6 pb-4 pt-6" style="border: 1px solid #d8d4dc">
            <div class="row">
                <div class="col-4">
                    <div class="row">
                        <div class="col-12 pt-4 pb-3 pl-5 border border-dark" style="background-color: rgb(216, 216, 216)">
                            <label class="form-label">FORM MASTER ITEM KENDARAAN</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 px-3 pb-4 pt-0">
                            <div class="row pt-4 px-4 bg-primary">
                                <div class="col-6 pt-1">
                                    <label class="form-label">NAMA BARANG</label>
                                </div>
                                <div class="col-6">
                                    <label class="form-label"><input type="hidden" class="form-control bg-white" id="id_master_kendaraan"><input type="type" class="form-control bg-white" id="nama_barang"></label>
                                </div>
                            </div>
                            <div class="row pt-1 px-4 bg-primary">
                                <div class="col-6 pt-2">
                                    <label class="form-label">JADWAL PEMELIHARAAN</label>
                                </div>
                                <div class="col-6">
                                    <table>
                                        <tr>
                                            <td><input type="number" class="form-control" id="quantity_pemeliharaan"></td>
                                            <td>
                                                <select class="form-control" id="satuan_pemeliharaan">
                                                    <option value="">Pilih Satuan</option>
                                                    <option value="hari">Hari</option>
                                                    <option value="minggu">Minggu</option>
                                                    <option value="bulan">Bulan</option>
                                                    <option value="tahun">Tahun</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="row py-4 bg-primary">
                                <div class="col-12 text-center">
                                    <button class="btn btn-success btn-sm" onclick="update_pemeliharaan('add')"><i class="fa fa-plus"></i> Tambah</button>
                                    <button class="btn btn-warning text-dark btn-sm" id="update_jenis_pemeliharaan" onclick="update_pemeliharaan('update')" disabled><i class="fa fa-edit"></i> Update</button>
                                    <button class="btn btn-danger btn-sm" onclick="clear_jenis_pemeliharaan()"><i class="fa fa-trash"></i> Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8 pl-8 pr-6">
                    <table class="table table-bordered w-100" id="datatable">
                        <thead>
                            <tr>
                                <th width="20">No</th>
                                <th>Nama Barang</th>
                                <th>Jadwal Pemeliharaan</th>
                                <th width="10"><i class="fa fa-cog"></i></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid content-row">
        <div class="card-body px-6 pb-4 pt-6" style="border: 1px solid #d8d4dc">
            <div class="row">
                <div class="col-6">
                    <div class="row">
                        <div class="col-12 pt-4 pb-3 pl-5 border border-dark" style="background-color: rgb(216, 216, 216)">
                            <label class="form-label">FORM PENGAJUAN MAINTENANCE KENDARAAN</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 px-3 pb-4 pt-0">
                            <div class="row pt-4 px-4 bg-primary">
                                <div class="col-4 pt-1">
                                    <label class="form-label">TANGGAL PENGAJUAN</label>
                                </div>
                                <div class="col-8">
                                    <input type="hidden" class="form-control bg-white" id="id_vehicle_maintenance"><input type="date" class="form-control bg-white" id="tanggal_pengajuan" value="{{date('Y-m-d')}}">
                                </div>
                            </div>
                            <div class="row pt-1 px-4 bg-primary">
                                <div class="col-4 pt-2">
                                    <label class="form-label">KENDARAAN</label>
                                </div>
                                <div class="col-8">
                                    <select class="form-control" id="vehicle_id">
                                        <option value="">Pilih Kendaraan</option>
                                        @foreach ($vehicles as $key=>$value)
                                            <option value="{{$value->id}}">{{$value->plat_no}} || {{$value->merk}} {{$value->tipe}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="daftar_item">
                            </div>
                            <div class="row py-4 bg-primary">
                                <div class="col-12 text-center">
                                    <button class="btn btn-success btn-sm" onclick="update_pemeliharaan_2('add')" id="button_store_maintenance"><i class="fa fa-save"></i> Simpan</button>
                                    <button class="btn btn-warning text-dark btn-sm" id="update_jenis_pemeliharaan_2" onclick="update_pemeliharaan_2('update')" disabled><i class="fa fa-edit"></i> Update</button>
                                    <button class="btn btn-danger btn-sm" onclick="print_vehicle_maintenance()"><i class="fa fa-file-pdf-o"></i> Print</button>
                                    <button class="btn btn-info btn-sm" onclick="clear_jenis_pemeliharaan_2()"><i class="fa fa-plus"></i> Tambah Baru</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 pl-6 pr-3  ">
                    <table class="table table-bordered w-100" id="datatable_2">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Tanggal Pengajuan</th>
                                <th>Kendaraan</th>
                                <th width="20%">Last Update</th>
                                <th width="6%"><i class="fa fa-cog"></i></th>
                            </tr>
                        </thead>
                    </table>
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
<script src="{{URL::asset('assets/js/iziToast.min.js')}}"></script>
<script>
    var array_item=[];
    var array_quantity=[];
    var array_price=[];
    $(document).ready(function() {
        $('#datatable').DataTable();
        // document.getElementById('update_category').disabled=true;
        add_array_item();
        get_kategori_items();
    });
    $('#category_name').on('change',function(){
        if($(this).val()!=''){
            document.getElementById("category_name").style.border="";
        }
    });
    function submit_category(status){
        var id=$('#id_kategori_item').val();
        var category_name=$('#category_name').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.store_kategori_item')}}",
            data: {
                id:id,
                category_name:category_name,
                status:status
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                if(res=='add'){
                    iziToast.success({
                        message: 'Data kategori item berhasil di tambah',
                        position: 'topCenter'
                    });
                }else{
                    iziToast.success({
                        message: 'Data kategori item berhasil di update',
                        position: 'topCenter'
                    });
                }
                $('#id_kategori_item').val('');
                $('#category_name').val('');
                document.getElementById("category_name").style.border="";
                datatable_3.ajax.reload();
                get_kategori_items();
            },
            error: function(error){
                let err_log=error.responseJSON.errors;
                if(error.status==422){
                    if(typeof(err_log.category_name)!=='undefined'){
                        document.getElementById("category_name").style.border = "1px solid red";
                    }else{
                        document.getElementById("category_name").style.border="";
                    }
                }
            }
        });
    };
    function update_item(status){
        var id=$('#id_item').val();
        var category=$('#category_name_2').val();
        var item_name=$('#nama_barang').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.store_item')}}",
            data: {
                id:id,
                category:category,
                item_name:item_name,
                status:status
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                if(res=='add'){
                    iziToast.success({
                        message: 'Data item berhasil di tambah',
                        position: 'topCenter'
                    });
                }else{
                    iziToast.success({
                        message: 'Data item berhasil di update',
                        position: 'topCenter'
                    });
                }
                $('#id_item').val('');
                $('#category_name_2').val('');
                $('#item_name').val('');
                document.getElementById("category_name_2").style.border="";
                document.getElementById("item_name").style.border="";
                datatable_4.ajax.reload();
                get_items();
            },
            error: function(error){
                let err_log=error.responseJSON.errors;
                if(error.status==422){
                    if(typeof(err_log.category)!=='undefined'){
                        document.getElementById("category_name_2").style.border = "1px solid red";
                    }else{
                        document.getElementById("category_name_2").style.border="";
                    }
                    if(typeof(err_log.item_name)!=='undefined'){
                        document.getElementById("item_name").style.border = "1px solid red";
                    }else{
                        document.getElementById("item_name").style.border="";
                    }
                }
            }
        });
    };
    $('#new_category').on('click',function(){
        $('#category_name').val('');
        document.getElementById('update_category').disabled=true;
        $("#submit_category").html('<i class="fa fa-save"></i> Simpan');
        $("#datatable_3 tbody tr").removeClass('bg-cyan');
    });
    function get_kategori_items(){
        $('#category_name_2').empty();
        $.ajax({
            type:"GET",
            url: "{{route('hris.ga.get_kategori_item')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#category_name_2').append('<option value="">Pilih Kategori</option>');
                jQuery.each(res, function(key,val){
                    $('#category_name_2').append(`<option value=`+val.id+`>`+val.category_name+`</option>`);
                });
            },
            error: function(error){
                swal("", "Gagal mengambil data", "error");
            }
        });
    }
    function add_array_item(){
        array_item.push('');
        array_quantity.push('');
        array_price.push('');
        show_list_item();
    }
    function show_list_item(){
        $('#daftar_item').empty();
        jQuery.each(array_item, function(key,val){
            if(key==array_item.length-1)
            {
                if(key==0){
                    $('#daftar_item').append('<div class="row pt-1 px-4 bg-primary">\
                        <div class="col-4 pt-2"><label class="form-label">ITEM</label></div>\
                        <div class="col-8 pr-0">\
                            <table class="w-100">\
                                <tr>\
                                    <td width="48%">\
                                        <select class="form-control" id="vehicle_item_ke_'+key+'" name="vehicle_item[]" onchange="isi_vehicle_item(this.value,'+key+')">\
                                            <option value="">Pilih Item</option>\
                                            @foreach ($vehicle_item as $key=>$value)\
                                                <option value="{{$value->id}}">{{$value->nama_barang}}</option>\
                                            @endforeach\
                                        </select>\
                                    </td>\
                                    <td width="23%">\
                                        <input type="number" class="form-control" id="quantity_ke_'+key+'" name="quantity[]" placeholder="Quantity" onchange="isi_vehicle_quantity(this.value,'+key+')">\
                                    </td>\
                                    <td width="23%">\
                                        <input type="number" class="form-control" id="vehicle_item_price_ke_'+key+'" name="vehicle_item_price[]" placeholder="harga" onchange="isi_vehicle_item_price(this.value,'+key+')">\
                                    </td>\
                                    <td width="6%">\
                                        <a style="color: yellow;cursor:pointer" onclick="add_array_item()"><i class="fa fa-plus"></i></a>\
                                    </td>\
                                </tr>\
                            </table>\
                        </div>\
                    </div>');
                }else{
                    $('#daftar_item').append('<div class="row pt-1 px-4 bg-primary">\
                        <div class="col-4 pt-2">\
                        </div>\
                        <div class="col-8 pr-0">\
                            <table class="w-100">\
                                <tr>\
                                    <td width="48%">\
                                        <select class="form-control" id="vehicle_item_ke_'+key+'" name="vehicle_item[]" onchange="isi_vehicle_item(this.value,'+key+')">\
                                            <option value="">Pilih Item</option>\
                                            @foreach ($vehicle_item as $key=>$value)\
                                                <option value="{{$value->id}}">{{$value->nama_barang}}</option>\
                                            @endforeach\
                                        </select>\
                                    </td>\
                                    <td width="23%">\
                                        <input type="number" class="form-control" id="quantity_ke_'+key+'" name="quantity[]" placeholder="Quantity" onchange="isi_vehicle_quantity(this.value,'+key+')">\
                                    </td>\
                                    <td width="23%">\
                                        <input type="number" class="form-control" id="vehicle_item_price_ke_'+key+'" name="vehicle_item_price[]" placeholder="harga" onchange="isi_vehicle_item_price(this.value,'+key+')">\
                                    </td>\
                                    <td width="6%">\
                                        <a style="color: yellow;cursor:pointer" onclick="add_array_item()"><i class="fa fa-plus"></i></a>\
                                        <a style="color: yellow;cursor:pointer" onclick="delete_item('+key+')"><i class="fa fa-minus"></i></a>\
                                    </td>\
                                </tr>\
                            </table>\
                        </div>\
                    </div>');
                }
            }else{
                if(key==0){
                    $('#daftar_item').append('<div class="row pt-1 px-4 bg-primary">\
                        <div class="col-4 pt-2"><label class="form-label">ITEM</label></div>\
                        <div class="col-8 pr-0">\
                            <table class="w-100">\
                                <tr>\
                                    <td width="48%">\
                                        <select class="form-control" id="vehicle_item_ke_'+key+'" name="vehicle_item[]" onchange="isi_vehicle_item(this.value,'+key+')">\
                                            <option value="">Pilih Item</option>\
                                            @foreach ($vehicle_item as $key=>$value)\
                                                <option value="{{$value->id}}">{{$value->nama_barang}}</option>\
                                            @endforeach\
                                        </select>\
                                    </td>\
                                    <td width="23%">\
                                        <input type="number" class="form-control" id="quantity_ke_'+key+'" name="quantity[]" placeholder="Quantity" onchange="isi_vehicle_quantity(this.value,'+key+')">\
                                    </td>\
                                    <td width="23%">\
                                        <input type="number" class="form-control" id="vehicle_item_price_ke_'+key+'" name="vehicle_item_price[]" placeholder="harga" onchange="isi_vehicle_item_price(this.value,'+key+')">\
                                    </td>\
                                    <td width="6%">\
                                        <a style="color: yellow;cursor:pointer" onclick="delete_item('+key+')"><i class="fa fa-minus"></i></a>\
                                    </td>\
                                </tr>\
                            </table>\
                        </div>\
                    </div>');
                }else{
                    $('#daftar_item').append('<div class="row pt-1 px-4 bg-primary">\
                        <div class="col-4 pt-2">\
                        </div>\
                        <div class="col-8 pr-0">\
                            <table class="w-100">\
                                <tr>\
                                    <td width="48%">\
                                        <select class="form-control" id="vehicle_item_ke_'+key+'" name="vehicle_item[]" onchange="isi_vehicle_item(this.value,'+key+')">\
                                            <option value="">Pilih Item</option>\
                                            @foreach ($vehicle_item as $key=>$value)\
                                                <option value="{{$value->id}}">{{$value->nama_barang}}</option>\
                                            @endforeach\
                                        </select>\
                                    </td>\
                                    <td width="23%">\
                                        <input type="number" class="form-control" id="quantity_ke_'+key+'" name="quantity[]" placeholder="Quantity" onchange="isi_vehicle_quantity(this.value,'+key+')">\
                                    </td>\
                                    <td width="23%">\
                                        <input type="number" class="form-control" id="vehicle_item_price_ke_'+key+'" name="vehicle_item_price[]" placeholder="harga" onchange="isi_vehicle_item_price(this.value,'+key+')">\
                                    </td>\
                                    <td width="6%">\
                                        <a style="color: yellow;cursor:pointer" onclick="delete_item('+key+')"><i class="fa fa-minus"></i></a>\
                                    </td>\
                                </tr>\
                            </table>\
                        </div>\
                    </div>');
                }
            }
            $('#vehicle_item_ke_'+key).val(val);
            $('#quantity_ke_'+key).val(array_quantity[key]);
            $('#vehicle_item_price_ke_'+key).val(array_price[key]);
        });
    }
    function isi_vehicle_item(value,key){
        array_item[key]=value;
    }
    function isi_vehicle_item_price(value,key){
        array_price[key]=value;
    }
    function isi_vehicle_quantity(value,key){
        array_quantity[key]=value;
    }
    function show_list_item_check(){
        console.log(array_item);
        console.log(array_quantity);
        console.log(array_price);
    }
    function delete_item(key){
        array_item.splice(key,1);
        array_quantity.splice(key,1);
        array_price.splice(key,1);
        show_list_item();
    }
    
    let datatable = $("#datatable").DataTable({
        ordering: true,
        processing: true,
        serverSide: true,
        paging: true,
        searching: true,
        destroy: true,
        scrollX: true,
        ajax: {
            url: '{{ route('hris.ga.get_vehicle_item_data') }}',
        },
        fixedColumns:   {
            heightMatch: 'none'
        },
        columns: [
            {
                data: null, // Kolom untuk nomor urut
                render: function (data, type, row, meta) {
                    return meta.row + 1; // Menampilkan urutan angka
                }
            }, {
                data: 'nama_barang'

            },
            {
                data: null,
                render: function (data, type, row, meta) {
                    return row.quantity_pemeliharaan + ` `+row.satuan_pemeliharaan;
                }
            },
            {
                data: null,
                render: function (data, type, row, meta) {
                    return `<a href="#" onclick="delete_pemeliharaan(` + row.id + `)" style="color:red"><i class="fa fa-trash"></i></a>`;
                }
            },
        ],
    });
    let datatable_2 = $("#datatable_2").DataTable({
        ordering: true,
        processing: true,
        serverSide: true,
        paging: true,
        searching: true,
        destroy: true,
        scrollX: true,
        ajax: {
            url: '{{ route('hris.ga.get_vehicle_maintenance_data') }}',
        },
        fixedColumns:   {
            heightMatch: 'none'
        },
        columns: [
            {
                data: null, // Kolom untuk nomor urut
                render: function (data, type, row, meta) {
                    return meta.row + 1; // Menampilkan urutan angka
                }
            }, {
                data: 'tanggal_pengajuan'

            }, {
                data: 'vehicle_merk'

            }, {
                data: 'last_update'

            },
            {
                data: null,
                render: function (data, type, row, meta) {
                    return `<a href="#" onclick="delete_maintenance(` + row.id + `)" style="color:red"><i class="fa fa-trash"></i></a>`;
                }
            },
        ],
    });
    let datatable_3 = $("#datatable_3").DataTable({
        ordering: true,
        processing: true,
        serverSide: true,
        paging: true,
        searching: true,
        destroy: true,
        scrollX: true,
        ajax: {
            url: '{{ route('hris.ga.get_vehicle_category_name') }}',
        },
        fixedColumns:   {
            heightMatch: 'none'
        },
        columns: [
            {
                data: null, // Kolom untuk nomor urut
                render: function (data, type, row, meta) {
                    return meta.row + 1; // Menampilkan urutan angka
                }
            }, {
                data: 'category_name'

            }, {
                data: 'created_at'

            },
            {
                data: null,
                render: function (data, type, row, meta) {
                    return `<a href="#" onclick="delete_maintenance(` + row.id + `)" style="color:red"><i class="fa fa-trash"></i></a>`;
                }
            },
        ],
    });
    $('#datatable tbody').on('click', 'tr', function () {
        var tr = $(this).closest('tr');
        var row = datatable.row(tr);

        var data = row.data();

        $("#datatable tbody tr").removeClass('bg-cyan');
        $(this).addClass('bg-cyan');

        $('#id_master_kendaraan').val(data['id']);
        $('#nama_barang').val(data['nama_barang']);
        $('#quantity_pemeliharaan').val(data['quantity_pemeliharaan']);
        $('#satuan_pemeliharaan').val(data['satuan_pemeliharaan']);
        document.getElementById('update_jenis_pemeliharaan').disabled=false;
    });
    $('#datatable_2 tbody').on('click', 'tr', function () {
        var tr = $(this).closest('tr');
        var row = datatable_2.row(tr);

        var data = row.data();

        $("#datatable_2 tbody tr").removeClass('bg-cyan');
        $(this).addClass('bg-cyan');
        
        $('#id_vehicle_maintenance').val(data['id']);
        $('#tanggal_pengajuan').val(data['tanggal']);
        $('#vehicle_id').val(data['vehicle_id']);
        document.getElementById('update_jenis_pemeliharaan_2').disabled=false;
        $("#button_store_maintenance").html('<i class="fa fa-clone"></i> Duplicate');
        array_item=[];
        array_quantity=[];
        array_price=[];
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_vehicle_item_maintenance_price')}}",
            data: {
                id:data['id'],
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                jQuery.each(res, function(key,val){
                    array_item.push(val['vehicle_item_id']);
                    array_quantity.push(val['quantity']);
                    array_price.push(val['price']);
                    show_list_item();
                });
            },
            error: function(error){
                swal("", "Gagal mengambil data", "error");
            }
        });
    });
    $('#datatable_3 tbody').on('click', 'tr', function () {
        var tr = $(this).closest('tr');
        var row = datatable_3.row(tr);

        var data = row.data();

        $("#datatable_3 tbody tr").removeClass('bg-cyan');
        $(this).addClass('bg-cyan');
        
        $('#id_kategori_item').val(data['id']);
        $('#category_name').val(data['category_name']);
        $('#vehicle_id').val(data['vehicle_id']);
        document.getElementById('update_category').disabled=false;
        $("#submit_category").html('<i class="fa fa-clone"></i> Duplicate');
    });
    $('#jenis_pemeliharaan').on('change',function($query){
        var jenis_pemeliharaan=$('#jenis_pemeliharaan').val();
        if(jenis_pemeliharaan!=''){
            document.getElementById("jenis_pemeliharaan").style.border="";
        }
    });
    $('#jadwal_pemeliharaan').on('change',function($query){
        var jadwal_pemeliharaan=$('#jadwal_pemeliharaan').val();
        if(jadwal_pemeliharaan!=''){
            document.getElementById("jadwal_pemeliharaan").style.border="";
        }
    });
    $('#km').on('change',function($query){
        var km=$('#km').val();
        if(km!=''){
            document.getElementById("km").style.border="";
        }
    });
    function clear_jenis_pemeliharaan(){
        $('#id_master_kendaraan').val('');
        $('#nama_barang').val('');
        $('#quantity_pemeliharaan').val('');
        $('#satuan_pemeliharaan').val('');
        document.getElementById('update_jenis_pemeliharaan').disabled=true;
    }
    function clear_jenis_pemeliharaan_2(){
        $('#id_vehicle_maintenance').val('');
        $('#tanggal_pengajuan').val('');
        $('#vehicle_id').val('');
        $('#vehicle_item').val('');
        $('#vehicle_item_price').val('');
        array_item=[];
        array_quantity=[];
        array_price=[];
        add_array_item();
        document.getElementById('update_jenis_pemeliharaan_2').disabled=true;
        $("#button_store_maintenance").html('<i class="fa fa-save"></i> Simpan');
    }
    function print_vehicle_maintenance(id){
        var id=$('#id_vehicle_maintenance').val();
        var url = 'print_vehicle_maintenance?id='+id;
        window.open(url, '_blank');
    }
    function update_pemeliharaan(status){
        var id_master_kendaraan=$('#id_master_kendaraan').val();
        var nama_barang=$('#nama_barang').val();
        var quantity_pemeliharaan=$('#quantity_pemeliharaan').val();
        var satuan_pemeliharaan=$('#satuan_pemeliharaan').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.store_vehicle_item_master')}}",
            data: {
                id:id_master_kendaraan,
                nama_barang:nama_barang,
                quantity_pemeliharaan:quantity_pemeliharaan,
                satuan_pemeliharaan:satuan_pemeliharaan,
                status:status
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                console.log(res);
                document.getElementById("nama_barang").style.border="";
                document.getElementById("quantity_pemeliharaan").style.border="";
                document.getElementById("satuan_pemeliharaan").style.border="";
                $('#nama_barang').val('');
                $('#quantity_pemeliharaan').val('');
                $('#satuan_pemeliharaan').val('');
                document.getElementById('update_jenis_pemeliharaan').disabled=true;
                datatable.ajax.reload();
            },
            error: function(error){
                let err_log=error.responseJSON.errors;
                if(error.status==422){
                    if(typeof(err_log.nama_barang)!=='undefined'){
                        document.getElementById("nama_barang").style.border = "1px solid red";
                    }else{
                        document.getElementById("nama_barang").style.border="";
                    }
                    if(typeof(err_log.quantity_pemeliharaan)!=='undefined'){
                        document.getElementById("quantity_pemeliharaan").style.border = "1px solid red";
                    }else{
                        document.getElementById("quantity_pemeliharaan").style.border="";
                    }
                    if(typeof(err_log.satuan_pemeliharaan)!=='undefined'){
                        document.getElementById("satuan_pemeliharaan").style.border = "1px solid red";
                    }else{
                        document.getElementById("satuan_pemeliharaan").style.border="";
                    }
                }
            }
        });
    };
    function update_pemeliharaan_2(status){
        var id=$('#id_vehicle_maintenance').val();
        var tanggal_pengajuan=$('#tanggal_pengajuan').val();
        var vehicle_id=$('#vehicle_id').val();
        var vehicle_item=array_item;
        var vehicle_quantity=array_quantity;
        var vehicle_item_price=array_price;
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.store_vehicle_item_maintenance')}}",
            data: {
                id:id,
                tanggal_pengajuan:tanggal_pengajuan,
                vehicle_id:vehicle_id,
                vehicle_item:vehicle_item,
                vehicle_quantity:vehicle_quantity,
                vehicle_item_price:vehicle_item_price,
                status:status
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                document.getElementById("tanggal_pengajuan").style.border="";
                $('#vehicle_id').val('');
                array_item=[];
                array_quantity=[];
                array_price=[];
                add_array_item();
                document.getElementById('update_jenis_pemeliharaan_2').disabled=true;
                $("#button_store_maintenance").html('<i class="fa fa-save"></i> Simpan');
                datatable_2.ajax.reload();
            },
            error: function(error){
                let err_log=error.responseJSON.errors;
                if(error.status==422){
                    if(typeof(err_log.tanggal_pengajuan)!=='undefined'){
                        document.getElementById("tanggal_pengajuan").style.border = "1px solid red";
                    }else{
                        document.getElementById("tanggal_pengajuan").style.border="";
                    }
                    if(typeof(err_log.vehicle_id)!=='undefined'){
                        document.getElementById("vehicle_id").style.border = "1px solid red";
                    }else{
                        document.getElementById("vehicle_id").style.border="";
                    }
                }
            }
        });
    };
    function delete_pemeliharaan(id){
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.delete_vehicle_item')}}",
            data: {
                id:id,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                datatable.ajax.reload();
                clear_jenis_pemeliharaan();
            },error:function(error){
                swal("", "gagal di hapus", "error");
            }
        });
    }
    function delete_maintenance(id){
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.delete_vehicle_maintenance')}}",
            data: {
                id:id,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                datatable_2.ajax.reload();
                clear_jenis_pemeliharaan_2();
                array_item=[];
                array_quantity=[];
                array_price=[];
                add_array_item();
            },error:function(error){
                swal("", "gagal di hapus", "error");
            }
        });
    }
</script>
@endsection