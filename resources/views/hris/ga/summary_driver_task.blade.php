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
            @if ($id_user==4241 || $id_user==20 || $id_user==5321 || $id_user==6083)
                <a class="btn btn-white" href="{{route('hris.ga.data_pengajuan_transportasi')}}">Data <span class="badge text-dark" style="font-weight:bold; background-color:#d2eafc;padding-left:5px;padding-right:5px">{{$pengajuan_transportasi}}</span></a>
            @else
            <a class="btn btn-white" href="{{route('hris.ga.data_pengajuan_transportasi')}}">Data</a>
            @endif
        </li>
        @if ($id_user==4241 || $id_user==20 || $id_user==5321 || $id_user==6083)
        <li class="nav-item">
            <a class="btn btn-primary" style="background-color:blue" href="{{route('hris.ga.summary_driver_task')}}">Summary Driver</a>
        </li>
        @endif
    </ul>
</div>
<div class="card-body py-0" style="border: 1px solid #d8d4dc">
    <div class="row mx-0 py-1 px-2 text-dark border-left border-right border-bottom ">
        <div class="col-12">
            <i class="fa fa-filter"></i> Filter
        </div>
    </div>
    <div class="row pt-3 pb-2 mx-0 px-2 border-left border-right">
        <div class="col-1"><label class="form-label pt-2">Tanggal</label></div>
        <div class="col-2"><input type="date" class="form-control" id="filter_tanggal" onchange="filterTanggalChange()"></div>
    </div>
    <div class="row pt-1 pb-2 mx-0 px-2 border-left border-right">
        <div class="col-1"><label class="form-label pt-2">Driver</label></div>
        <div class="col-2">
            <select id="filter_driver" class="form-control" onchange="filterDriverChange()">
                <option value="">Pilih Driver</option>
                @foreach($drivers as $key=>$value)
                    <option value="{{$value->enroll_id}}">{{$value->employee_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row pb-3 mx-0 px-2 border-left border-right border-bottom">
        <div class="col-1"></div>
        <div class="col-2">
            <button class="btn btn-danger" onclick="print_pdf_summary_driver()"><i class="fa fa-file-pdf-o"></i> Print PDF</button>
        </div>
    </div>
    <div class="row px-3 pt-3 pb-6">
        <div class="col-12">
            <input type="hidden" id="user" value="{{$id_user}}">
            <table id="datatable" class="table table-bordered">
                <thead class="table-white text-white" style="background-color:#15435a">
                    <tr style='text-align:center; vertical-align:middle'>
                        <th>No</th>
                        <th>Driver</th>
                        <th>Alamat Asal</th>
                        <th>Waktu Pemberangkatan</th>
                        <th>Alamat Tujuan</th>
                        <th>Waktu Kedatangan</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
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
<script>
    let datatable = $("#datatable").DataTable({
        ordering: true,
        processing: true,
        serverSide: true,
        paging: true,
        searching: true,
        destroy: true,
        scrollX: true,
        ajax: {
            url: '{{ route('hris.ga.get_data_summary_driver') }}',
            data: function(d) {
                d.tanggal = $('#filter_tanggal').val();
                d.driver = $('#filter_driver').val();
            },
        },
        columns: [
            {
                data: null, // Kolom untuk nomor urut
                render: function (data, type, row, meta) {
                    return meta.row + 1; // Menampilkan urutan angka
                }
            }, {
                data: 'nama_driver'

            },
            {
                render:function(data,type,row,meta){
                    return `<h6 style="font-weight:bold;margin-bottom:3px;font-size:11pt">`+row.instansi+`</h6><h6 style='font-size:9pt;line-height: 16px'>`+row.detail_alamat+`<br>`+row.detail_alamat_asal+`</h6>`;
                }
            },
            {
                render:function(data,type,row,meta){
                    var tanggal_pemberangkat=new Date(row.tanggal_pemberangkatan);
                    var formattedDate = tanggal_pemberangkat.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                    return formattedDate+` `+row.jam_pemberangkatan;
                }
            },
            {
                render:function(data,type,row,meta){
                    return `<h6 style="font-weight:bold;margin-bottom:3px;font-size:11pt">`+row.instansi_tujuan+`</h6><h6 style='font-size:9pt;line-height: 16px'>`+row.detail_alamat2+`<br>`+row.detail_alamat_tujuan+`</h6>`;
                }
            },
            {
                render:function(data,type,row,meta){
                    var tanggal_kedatang=new Date(row.tanggal_kedatangan);
                    var formattedDate2 = tanggal_kedatang.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                    return formattedDate2+` `+row.jam_kedatangan;
                }
            },
            {
                render:function(data,type,row,meta){
                    return `<div class="row">
                                <div class="col-5"><label class="border-bottom border-dark">`+row.tujuan_pemberangkatan.replace("_", " ")+`</label></div>
                            </div>
                            ${ row.tujuan_pemberangkatan.indexOf('antar_barang') > -1 || row.tujuan_pemberangkatan.indexOf('jemput_barang') > -1 ? 
                                `<div class="row">
                                    <div class="col-12">`+(row.keterangan_barang==null?'':row.keterangan_barang)+`</div>
                                </div>
                                <div class="row">
                                    <div class="col-5">Jenis Barang</div>
                                    <div class="col-7">: `+(row.jenis_barang==null?'':row.jenis_barang)+`</div>
                                </div>
                                <div class="row">
                                    <div class="col-5">Quantity</div>
                                    <div class="col-7">: `+row.quantity+` `+row.satuan+`</div>
                                </div>
                                <div class="row">
                                    <div class="col-5 pr-0">Nama Penerima</div>
                                    <div class="col-7">: `+row.nama_penerima+`</div>
                                </div>`: ``
                            }
                            ${ row.tujuan_pemberangkatan.indexOf('antar_dinas') > -1 || row.tujuan_pemberangkatan.indexOf('jemput_dinas') > -1 ? 
                                `<div class="row">
                                    <div class="col-3">Karyawan</div>
                                    <div class="col-9">: `+(row.karyawan_dinas_luar==null?'':row.karyawan_dinas_luar)+`</div>
                                </div>`: ``
                            }
                            ${ row.tujuan_pemberangkatan.indexOf('antar_tamu') > -1 || row.tujuan_pemberangkatan.indexOf('jemput_tamu') > -1 ? 
                                `<div class="row">
                                    <div class="col-5">Nama Tamu</div>
                                    <div class="col-7">: `+row.nama_tamu+`</div>
                                </div>
                                <div class="row">
                                    <div class="col-5">Nomor HP Tamu</div>
                                    <div class="col-7">: `+row.nomor_hp_tamu+`</div>
                                </div>`: ``
                            }
                                <div class="row mt-3">
                                    <div class="col-2 pr-0">User</div>
                                    <div class="col-10">: `+row.employee_name+`</div>
                                </div>
                                <div class="row">
                                    <div class="col-2 pr-0">Dept</div>
                                    <div class="col-10">: `+row.department_name+`</div>
                                </div>`;
                }
            }, {
                render:function(data,type,row,meta){
                    if(row.status==1){
                        return `<h6 style="font-size:11pt;color:green;font-weight:bold">APPROVED</h6>`;
                    }else if(row.status==2){
                        return `<h6 style="font-size:11pt;color:red;font-weight:bold">ALTERNATIVE</h6>`;
                    }else if(row.status==3){
                        return `<h6 style="font-size:11pt;color:green;font-weight:bold">ON THE WAY</h6>`;
                    }else if(row.status==4){
                        return `<h6 style="font-size:11pt;color:green;font-weight:bold">DONE</h6>`;
                    }else if(row.status==5){
                        return `<h6 style="font-size:11pt;color:orange;font-weight:bold">LATE</h6>`;
                    }else if(row.status==6){
                        return `<h6 style="font-size:11pt;color:orange;font-weight:bold">CANCEL</h6>`;
                    }else if(row.status==0){
                        return `<h6 style="font-size:11pt;color:grey;font-weight:bold">PENDING</h6>`;
                    }
                }
            },
        ],
        "createdRow": function (row, data, dataIndex) {
            // if ((data['kode_hari'] == "5") || (data['kode_hari'] == "6") || (data['kerjalibur'] == "LIBUR")) {
            if (data['status'] == 0) {
                $(row).css('background', '#d2eafc');
            }
        },
        columnDefs: [{ width: 1, targets: [0] },{ width: 170, targets: [1] },{ width: 170, targets: [2] },{ width: 55, targets: [3] },{ width: 170, targets: [4] },{ width: 55, targets: [5] },{ width: 235, targets: [6] },{ width: 85, targets: [7] }],
    });
    function filterTanggalChange(){
        datatable.ajax.reload();
    }
    function filterDriverChange(){
        datatable.ajax.reload();
    }
    function print_pdf_summary_driver(){
        var tanggal=$('#filter_tanggal').val();
        var driver=$('#filter_driver').val();
        var url = 'print_pdf_summary_driver?tanggal='+tanggal+'&driver='+driver;
        window.open(url, '_blank');
    }
</script>
@endsection