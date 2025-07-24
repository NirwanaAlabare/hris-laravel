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
    <ul class="nav nav-tabs" style="">
        {{-- <li class="nav-item">
            <a class="btn btn-white" href="{{route('hris.ga.form_pengajuan_transportasi')}}">Formulir</a>
        </li> --}}
        <li class="nav-item">
            @if ($id_user==4241 || $id_user==20 || $id_user==5321 || $id_user==6083 || $id_user==0 || $id_user==7765 || $id_user==109)
                <a class="btn btn-primary" style="background-color:rgb(228, 228, 228); position: relative; padding-right: 40px; padding-left: 40px" href="{{route('hris.ga.data_pengajuan_transportasi')}}">Data <span class="text-dark h-5 w-5" style="font-weight:bold; background-color:#d2eafc; border:1px solid #0091ff;font-size:10px; display: flex; justify-content: center; align-items: center;position: absolute; top: 7px; right: 10px; border-radius: 100%;">{{$pengajuan_transportasi}}</span></a>
            @else
            <a class="btn btn-primary" style="background-color:rgb(228, 228, 228);" href="{{route('hris.ga.data_pengajuan_transportasi')}}">Data</a>
            @endif
        </li>
        @if ($id_user==4241 || $id_user==20 || $id_user==5321 || $id_user==6083 || $id_user==0)
        <li class="nav-item">
            <a class="btn btn-white" style="background-color:rgb(228, 228, 228);" href="{{route('hris.ga.summary_driver_task')}}">Summary Driver</a>
        </li>
        @endif
    </ul>
</div>

<div class="card-body p-0" style="border: 1px solid #d8d4dc">
    <div id="accordion" class="px-5 mt-5">
        <div class="card mb-3">
            <div class="card-header p-0 bg-light" id="headingOne">
                <h5 class="mb-0">
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <i class="fa fa-filter" aria-hidden="true"></i> Filter
                </button>
            </h5>
        </div>

        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body px-0">
                <div class="row">
                    <div class="col-2 pl-6">
                        <label class="form-label pt-1" style="font-size:11pt">Tanggal Pemberangkatan</label>
                    </div>
                    <div class="col-3 pl-0">
                        <input type="hidden" id="daterange1" name="daterange1">
                        <a class="nav-link card-title p-3" style="border:1px solid #d8d4dc;border-radius: 4px;" id="daterange-btn1" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Klik di sini untuk pilih tanggal kehadiran"></a>
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
                            <option value=5>Late</option>
                            <option value=6>Cancel</option>
                        </select>
                    </div>
                </div>
                <div class="row pt-1">
                    <div class="col-2 pl-6 pt-1">
                    </div>
                    <div class="col-3 pl-0">
                        @if ($id_user==4241 || $id_user==20 || $id_user==5321 || $id_user==6083 || $id_user==0 || $id_user==7765 || $id_user==109)
                            <button class="btn btn-success py-1 my-1" id="export_excel"><i class="fa fa-file-excel-o"></i> Export Excel</button>
                        @endif
                    </div>
                    <div class="col-5">
                    </div>
                    <div class="col-2">
                        <div class="row">
                            <div class="col-6 p-0 border border-dark border-left-0 border-right-0 border-top-0" style="font-weight:bold">
                                Keterangan
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 pt-2 pl-0">
                                <table>
                                    <tr>
                                        <td width="1%"><h6 style="background-color:#d2eafc; border:1px solid grey;height:20px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h6></td>
                                        <td style="vertical-align:top">Pengajuan Baru</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
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
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title pl-4" id="exampleModalLabel">CHANGE STATUS</h3>
                <button data-dismiss="modal" aria-label="Close" style="background-color: rgb(255, 255, 255)"><span class="fa fa-times"></span></button>
            </div>
            <div class="modal-body">
                <div class="row pb-2 pt-1">
                    <div class="col-3 pl-6">
                        <h6 style="font-size:12pt;">Nama Karyawan</h6>
                    </div>
                    <input type="hidden" id="id_request">
                    <div class="col-6 pr-5" id="nama_karyawan_modal">
                    </div>
                    <div class="col-1 text-right"></div>
                </div>
                <div class="row py-2 bg-light text-dark">
                    <div class="col-3 pl-6">
                        <h6 style="font-size:12pt;">Department</h6>
                    </div>
                    <div class="col-7 pr-5" id="department_modal">
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-3 pl-6 pt-2">
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
                    <div class="col-3 pl-6">
                        <h6 style="font-size:12pt;">Waktu Pemberangkatan</h6>
                    </div>
                    <div class="col-7 pr-5" id="waktu_pemberangkatan_modal">
                    </div>
                </div>
                <div>
                    <table class="table table-bordered">
                        <thead id="header_advanced">
                        </thead>
                        <tbody id="tujuan_advanced">
                        </tbody>
                    </table>
                </div>
                <div id="tag_alternative_form" style="display:none">
                    <div class="row py-2 bg-light text-dark">
                        <div class="col-3 pl-6">
                            <h6 style="font-size:12pt;">Alternative</h6>
                        </div>
                        <div class="col-7 pr-5">
                            <textarea class="form-control" id="alternative_form" style="background-color:white"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row pt-2">
                    <div class="col-12 text-center">
                        <div id="approve_this_request">
                            <button class="btn btn-success btn-sm py-1" id="approve_this_request">SAVE</button>
                            <button class="btn btn-light btn-sm py-1" data-dismiss="modal">CANCEL</button>
                        </div>
                        <div id="alternate_this_request">
                            <button class="btn btn-success btn-sm py-1" id="alternate_this_request_button">ALTERNATIVE</button>
                            <button class="btn btn-light btn-sm py-1" data-dismiss="modal">CANCEL</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 30%;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row py-3">
                    <div class="col-12 text-center">
                        <input type="hidden" id="id_request_on_delete_modal">
                        <label class="form-label" style="font-size:15pt">Apakah anda yakin untuk menghapus data ini?</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-center">
                        <button class="btn btn-danger" onclick="hapus_pengajuan()">Ya, Hapus</button>
                        <button class="btn" style="background-color: rgb(175, 175, 175);color:white" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                <button data-dismiss="modal" aria-label="Close" style="background-color: rgb(255, 255, 255)"><span class="fa fa-times"></span></button>
            </div>
            <div class="modal-body">
                <div class="row pb-2 pt-1">
                    <div class="col-3 pl-6">
                        <h6 style="font-size:12pt;">Nama Karyawan</h6>
                    </div>
                    <input type="text" id="id_request_3">
                    <div class="col-6 pr-5" id="nama_karyawan_modal_3">
                    </div>
                    <div class="col-1 text-right"></div>
                </div>
                <div class="row py-2 bg-light text-dark">
                    <div class="col-3 pl-6">
                        <h6 style="font-size:12pt;">Department</h6>
                    </div>
                    <div class="col-7 pr-5" id="department_modal_3">
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-3 pl-6 pt-2">
                        <h6 style="font-size:12pt;">Keberangkatan Awal</h6>
                    </div>
                    <div class="col-7 pr-5">
                        <div class="row">
                            <div class="col-12" id="detail_alamat_modal_3">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" id="city_modal" style="font-size: 7pt">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row py-2 bg-light text-dark">
                    <div class="col-3 pl-6">
                        <h6 style="font-size:12pt;">Waktu Pemberangkatan</h6>
                    </div>
                    <div class="col-7 pr-5" id="waktu_pemberangkatan_modal_3">
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="3%">No</th>
                                    <th width="19%">Tujuan</th>
                                    <th width="8%">Waktu Kedatangan</th>
                                    <th width="22%">Keterangan</th>
                                    <th width="16%">Driver</th>
                                    <th width="16%">Kendaraan</th>
                                    <th width="16%">Status</th>
                                </tr>
                            </thead>
                            <tbody id="tujuan_advanced_3">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-3 pl-6 pt-2">
                        <h6 style="font-size:12pt;">Status</h6>
                    </div>
                    <div class="col-3 pr-5">
                        <select class="form-control" id="value_status_2" style="background-color: white" onchange="perubahanan_status_2()">\
                            <option value=0>Pilih Status</option>
                            <option value=1>Approved</option>
                            <option value=2>Alternative</option>
                            <option value=3>On The Way</option>
                            <option value=4>Done</option>
                            <option value=5>Late</option>
                            <option value=6>Cancel</option>
                        </select>
                    </div>
                </div>
                <div id="tag_alternanative_choosed" style="display:none">
                    <div class="row py-2">
                        <div class="col-3 pl-6 pt-2">
                            <h6 style="font-size:12pt;">Alternative</h6>
                        </div>
                        <div class="col-3 pr-5">
                            <textarea class="form-control" id="value_alternative" style="background-color: white" onchange="perubahanan_alternative()"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row pt-2">
                    <div class="col-12 text-center">
                        <button class="btn btn-success btn-sm py-1" onclick="change_status_this_request()">Save</button>
                        <button class="btn btn-light btn-sm py-1" data-dismiss="modal">CANCEL</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="statusUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                <button data-dismiss="modal" aria-label="Close" style="background-color: rgb(255, 255, 255)"><span class="fa fa-times"></span></button>
            </div>
            <div class="modal-body">
                <div class="row pb-2 pt-1">
                    <div class="col-3 pl-6">
                        <h6 style="font-size:12pt;">Nama Karyawan</h6>
                    </div>
                    <div class="col-6 pr-5" id="nama_karyawan_modal_4">
                    </div>
                    <div class="col-1 text-right"></div>
                </div>
                <div class="row py-2">
                    <div class="col-3 pl-6">
                        <h6 style="font-size:12pt;">Department</h6>
                    </div>
                    <div class="col-7 pr-5" id="department_modal_4">
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-3 pl-6 pt-2">
                        <h6 style="font-size:12pt;">Status</h6>
                    </div>
                    <div class="col-3 pr-5" id="status_modal_4">
                    </div>
                </div>
                <div id="tag_alternanative_choosed_user" style="display:none">
                    <div class="row py-2">
                        <div class="col-3 pl-6 pt-2">
                            <h6 style="font-size:12pt;">Alternative</h6>
                        </div>
                        <div class="col-3 pr-5" id="alternative_modal_4">
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-3 pl-6 pt-2">
                        <h6 style="font-size:12pt;">Keberangkatan Awal</h6>
                    </div>
                    <div class="col-7 pr-5">
                        <div class="row">
                            <div class="col-12" id="detail_alamat_modal_4">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" id="city_modal_4" style="font-size: 7pt">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="3%">No</th>
                                    <th width="19%">Tujuan</th>
                                    <th width="8%">Waktu Kedatangan</th>
                                    <th width="22%">Keterangan</th>
                                    <th width="16%">Driver</th>
                                    <th width="16%">Kendaraan</th>
                                    <th width="16%">Status</th>
                                </tr>
                            </thead>
                            <tbody id="tujuan_advanced_4">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                <button data-dismiss="modal" aria-label="Close" style="background-color: rgb(255, 255, 255)"><span class="fa fa-times"></span></button>
            </div>
            <div class="modal-body px-5">
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
                    <div class="col-5 pl-6">
                        <h6 style="font-size:12pt;">Waktu Pemberangkatan</h6>
                    </div>
                    <div class="col-7 pr-5" id="waktu_pemberangkatan_modal_2">
                    </div>
                </div>
                <div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="4%">No</th>
                                <th width="37%">Tujuan</th>
                                <th width="20%">Waktu Kedatangan</th>
                                <th width="39%">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="tujuan_advanced_2">
                        </tbody>
                    </table>
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
    var array_provinsi=[];
    var array_kota=[];
    var array_kecamatan=[];
    var array_desa=[];
    var array_instansi=[];
    var array_detail_alamat=[];
    var array_tanggal_pemberangkatan=[];
    var array_jam_pemberangkatan=[];
    var array_tujuan_pemberangkatan=[];
    var array_jarak_tempuh=[];
    var array_jenis_barang=[];
    var array_quantity=[];
    var array_satuan=[];
    var array_nama_penerima=[];
    var array_keterangan_barang=[];
    var array_nama_tamu=[];
    var array_nomor_hp_tamu=[];
    var array_karyawan_dinas_luar=[];
    var array_driver=[];
    var array_driver_name=[];
    var array_vehicle=[];
    var array_status=[];
    var array_alternative=[];
    var array_vehicle_name=[];
    var array_keterangan=[];
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
                    var alasan_status_alternative='';
                    if(row.alasan_status!==null){
                        alasan_status_alternative=row.alasan_status;
                    }
                    if (row.user==4241 || row.user==20 || row.user==6083 || row.user==5321 || row.user== 7765 || row.user == 109 || row.user == 1932){
                        if(row.status==0){
                            return `<a class='btn btn-success py-0 px-2 mt-0 btn-sm btn-block text-white' style='font-size:9pt' data-toggle="modal" data-target="#approveModal" ' onclick="approve_function(` + row.id + `,'` + row.employee_name + `','` + row.department_name + `','` + row.detail_alamat + `','` + row.detail_address +  `','` + row.desa + `','` + row.detail_alamat_tujuan + `','` + row.desa_tujuan + `','` + row.tujuan_pemberangkatan + `','` + row.tanggal_pemberangkatan + `','` + row.jarak_tempuh + `','` + (row.alasan_status===null?'':row.alasan_status) + `','approve')">APPROVE</a><a class='btn btn-danger py-0 px-2 mt-1 btn-sm btn-block text-white' style='font-size:9pt' data-toggle="modal" data-target="#approveModal" ' onclick="approve_function(` + row.id + `,'` + row.employee_name + `','` + row.department_name + `','` + row.detail_alamat + `','` + row.detail_address +  `','` + row.desa + `','` + row.detail_alamat_tujuan + `','` + row.desa_tujuan + `','` + row.tujuan_pemberangkatan + `','` + row.tanggal_pemberangkatan + `','` + row.jarak_tempuh + `','` + (row.alasan_status===null?'':row.alasan_status) + `','alternative')">ALTERNATIVE</a>`;
                        }else if(row.status==1){
                            return `<h6 style="font-size:11pt; margin-bottom:3px;text-align:center;color:green;font-weight:bold">APPROVED</h6><center><a href="#" data-target="#approveModal" data-toggle="modal" onclick="approve_function(` + row.id + `,'` + row.employee_name + `','` + row.department_name + `','` + row.detail_alamat + `','` + row.detail_address +  `','` + row.desa + `','` + row.detail_alamat_tujuan + `','` + row.desa_tujuan + `','` + row.tujuan_pemberangkatan + `','` + row.tanggal_pemberangkatan + `','` + row.jarak_tempuh + `','` + (row.alasan_status===null?'':row.alasan_status) + `','approve')"><i class="fa fa-edit"></i>Change Status</a><center>`;
                        }else{
                            return `<h6 style="font-size:11pt; margin-bottom:3px;text-align:center;color:red;font-weight:bold">ALTERNATIVE</h6><center><a href="#" style='font-size:9pt' data-toggle="modal" data-target="#approveModal" ' onclick="approve_function(` + row.id + `,'` + row.employee_name + `','` + row.department_name + `','` + row.detail_alamat + `','` + row.detail_address +  `','` + row.desa + `','` + row.detail_alamat_tujuan + `','` + row.desa_tujuan + `','` + row.tujuan_pemberangkatan + `','` + row.tanggal_pemberangkatan + `','` + row.jarak_tempuh + `','` + (row.alasan_status===null?'':row.alasan_status) + `','alternative')"><i class="fa fa-edit"></i>Change Notes</a></a>`;
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
                        }else if(row.status==5){
                            return `<h6 style="font-size:11pt; margin-bottom:3px;text-align:center;color:orange;font-weight:bold">LATE</h6>`;
                        }else if(row.status==6){
                            return `<h6 style="font-size:11pt; margin-bottom:3px;text-align:center;color:red;font-weight:bold">CANCEL</h6>`;
                        }
                    }
                }
            },
            {
                data: null,
                render: function (data, type, row, meta) {
                    var alasan_status_alternative='';
                    if(row.alasan_status!==null){
                        alasan_status_alternative=row.alasan_status;
                    }
                    if (row.user==4241 || row.user==20 || row.user==6083 || row.user==5321 || row.user== 7765 || row.user == 109){
                        if(row.created_by==row.user){
                            if(row.status==0){
                                return `<button onclick="edit_detail(` + row.id + `)" class="btn btn-sm btn-block py-0 px-1 mb-1" style="font-size:9pt;background-color:orange;color:white">EDIT</button><button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-block btn-sm py-0 px-1 mb-1  mt-0" style="font-size:9pt">LIHAT DETAIL</button><button onclick="delete_detail(`+row.id+`)" class="btn btn-danger btn-block btn-sm py-0 px-1 mb-1  mt-0" style="font-size:9pt">DELETE</button>`;
                            }else if(row.status!=2 && row.status!=6){
                                return `<button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-block btn-sm py-0 px-1 mb-1" style="font-size:9pt">LIHAT DETAIL</button><button onclick="print_penugasan(` + row.id + `)" class="btn btn-success btn-sm btn-block px-1 py-0 mt-0" style="font-size:9pt">FORM PENUGASAN</button>`;
                            }else{
                                return `<button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-sm btn-block py-0 px-1 mb-1" style="font-size:9pt">LIHAT DETAIL</button>`;
                            }
                        }else{
                            if(row.status!=2 && row.status!=6){
                                return `<button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-block btn-sm py-0 px-1 mb-1" style="font-size:9pt">LIHAT DETAIL</button><button onclick="print_penugasan(` + row.id + `)" class="btn btn-success btn-sm btn-block px-1 py-0 mt-0" style="font-size:9pt">FORM PENUGASAN</button>`;
                            }else{
                                return `<button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-sm btn-block py-0 px-1 mb-1" style="font-size:9pt">LIHAT DETAIL</button>`;
                            }
                        }
                    }else{
                        if(row.created_by==row.user){
                            if(row.status==0){
                                return `<button onclick="edit_detail(` + row.id + `)" class="btn btn-sm btn-block py-0 px-1 mb-1" style="font-size:9pt;background-color:orange;color:white">EDIT</button><button onclick="delete_detail(`+row.id+`)" class="btn btn-danger btn-block btn-sm py-0 px-1 mb-1  mt-0" style="font-size:9pt">DELETE</button>`;
                            }else if(row.status!=2 && row.status!=6){
                                return `<center><a href="#" data-target="#statusUserModal" data-toggle="modal" onclick="change_status_user_function(` + row.id + `,'` + row.employee_name + `','` + row.department_name + `','` + row.detail_alamat + `','` + row.detail_address +  `','` + row.desa + `','` + row.detail_alamat_tujuan + `','` + row.desa_tujuan + `','` + row.tujuan_pemberangkatan + `','` + row.tanggal_pemberangkatan + `','` + row.jarak_tempuh + `','` + row.status + `','` + alasan_status_alternative + `')"><img src="{{URL::asset('assets/images/brand/info.png')}}" width="23" style="padding-bottom:4px"></a></a><center>
                                <button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-block btn-sm py-0 px-1 mb-1 mt-0" style="font-size:9pt">LIHAT DETAIL</button><button onclick="print_penugasan(` + row.id + `)" class="btn btn-success btn-sm btn-block p-0 mt-0" style="font-size:9pt">FORM PENUGASAN</button>`;
                            }else{
                                return `<center><a href="#" data-target="#statusUserModal" data-toggle="modal" onclick="change_status_user_function(` + row.id + `,'` + row.employee_name + `','` + row.department_name + `','` + row.detail_alamat + `','` + row.detail_address +  `','` + row.desa + `','` + row.detail_alamat_tujuan + `','` + row.desa_tujuan + `','` + row.tujuan_pemberangkatan + `','` + row.tanggal_pemberangkatan + `','` + row.jarak_tempuh + `','` + row.status + `','` + alasan_status_alternative + `')"><img src="{{URL::asset('assets/images/brand/info.png')}}" width="23" style="padding-bottom:4px"></a></a><center>
                                <button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-sm btn-block py-0 px-1 mb-1" style="font-size:9pt">LIHAT DETAIL</button>`;
                            }
                        }else{
                            if(row.status!=2 && row.status!=6){
                                return `<center><a href="#" data-target="#statusUserModal" data-toggle="modal" onclick="change_status_user_function(` + row.id + `,'` + row.employee_name + `','` + row.department_name + `','` + row.detail_alamat + `','` + row.detail_address +  `','` + row.desa + `','` + row.detail_alamat_tujuan + `','` + row.desa_tujuan + `','` + row.tujuan_pemberangkatan + `','` + row.tanggal_pemberangkatan + `','` + row.jarak_tempuh + `','` + row.status + `','` + alasan_status_alternative + `')"><img src="{{URL::asset('assets/images/brand/info.png')}}" width="23" style="padding-bottom:4px"></a></a><center>
                                <button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-block btn-sm py-0 px-1 mb-1 mt-0" style="font-size:9pt">LIHAT DETAIL</button>`;
                            }else{
                                return `<center><a href="#" data-target="#statusUserModal" data-toggle="modal" onclick="change_status_user_function(` + row.id + `,'` + row.employee_name + `','` + row.department_name + `','` + row.detail_alamat + `','` + row.detail_address +  `','` + row.desa + `','` + row.detail_alamat_tujuan + `','` + row.desa_tujuan + `','` + row.tujuan_pemberangkatan + `','` + row.tanggal_pemberangkatan + `','` + row.jarak_tempuh + `','` + row.status + `','` + alasan_status_alternative + `')"><img src="{{URL::asset('assets/images/brand/info.png')}}" width="23" style="padding-bottom:4px"></a></a><center>
                                <button onclick="lihat_detail(` + row.id + `)" class="btn btn-primary btn-sm btn-block py-0 px-1 mb-1" style="font-size:9pt">LIHAT DETAIL</button>`;
                            }
                        }
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
    function delete_detail(id){
        $('#id_request_on_delete_modal').val(id);
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_pengajuan_status')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data:{
                id:id,
            },
            success: function(res){
                if(res!=0){
                    swal("", "Status telah di perbaharui oleh administator", "error");
                }else{
                    $('#deleteModal').modal('show');
                }
            },error: function(res){
                swal({
                    title: "Ambil data",
                    text: "Status gagal di ambil",
                    icon: "danger",
                });
            }
        });
    }
    function hapus_pengajuan(){
        var id=$('#id_request_on_delete_modal').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.delete_pengajuan_transportasi')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data:{
                id:id,
            },
            success: function(res){
                swal("", "Data berhasil di hapus", "success");
                $('#deleteModal').modal('hide');
                dataTableReload();
            },
            error: function(res){
                swal({
                    title: "Hapus Data",
                    text: "Data gagal di hapus",
                    icon: "danger",
                });
            }
        });
    }
    function print_penugasan(id) {
        var id=id;
        var url = 'print_penugasan_transportasi?id='+id;
        window.open(url, '_blank');
    }
    function approve_function(id,employee_name,department_name,detail_alamat,detail_address,desa,detail_alamat_tujuan,desa_tujuan,tujuan_pemberangkatan,waktu_pemberangkatan,jarak_tempuh,alasan_status,action){
        $('#id_request').val(id);
        $("#nama_karyawan_modal").html(employee_name);
        $("#department_modal").html(department_name);
        $("#detail_alamat_modal").html(detail_alamat+' '+detail_address);
        $("#city_modal").html(desa);
        $("#detail_alamat_tujuan_modal").html(detail_alamat_tujuan);
        $("#city_tujuan_modal").html(desa_tujuan);
        $("#tujuan_pemberangkatan_modal").html(tujuan_pemberangkatan.toUpperCase());
        $("#waktu_pemberangkatan_modal").html(waktu_pemberangkatan.toUpperCase());
        $("#jarak_tempuh_modal").html(jarak_tempuh+' Km');
        $('#alternative_form').val(alasan_status);
        if(action=='alternative'){
            document.getElementById('tag_alternative_form').style.display='block';
            document.getElementById('approve_this_request').style.display='none';
            document.getElementById('alternate_this_request').style.display='block';
            $('#header_advanced').empty().append(`<tr>
                <th width="3%">No</th>
                <th width="35%">Tujuan</th>
                <th width="24%">Waktu Kedatangan</th>
                <th width="38%">Keterangan</th>
            </tr>`)
        }else{
            document.getElementById('tag_alternative_form').style.display='none';
            document.getElementById('approve_this_request').style.display='block';
            document.getElementById('alternate_this_request').style.display='none';
            $('#header_advanced').empty().append(`<tr>
                <th width="3%">No</th>
                <th width="19%">Tujuan</th>
                <th width="8%">Waktu Kedatangan</th>
                <th width="22%">Keterangan</th>
                <th width="16%">Driver</th>
                <th width="16%">Kendaraan</th>
                <th width="16%">Status</th>
            </tr>`)
        }
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_tujuan_detail')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data:{
                id:id,
            },
            success: function(res){
                array_provinsi=[];
                array_kota=[];
                array_kecamatan=[];
                array_desa=[];
                array_instansi=[];
                array_detail_alamat=[];
                array_tanggal_pemberangkatan=[];
                array_jam_pemberangkatan=[];
                array_tujuan_pemberangkatan=[];
                array_jarak_tempuh=[];
                array_jenis_barang=[];
                array_quantity=[];
                array_satuan=[];
                array_nama_penerima=[];
                array_keterangan_barang=[];
                array_nama_tamu=[];
                array_nomor_hp_tamu=[];
                array_karyawan_dinas_luar=[];
                array_driver=[];
                array_driver_name=[];
                array_vehicle=[];
                array_vehicle_name=[];
                array_status=[];
                array_alternative=[];
                array_keterangan=[];
                jQuery.each(res, function(key,val){
                    array_provinsi.push(val.prov_name);
                    array_kota.push(val.city_name);
                    array_kecamatan.push(val.dis_name);
                    array_desa.push(val.subdis_name);
                    array_instansi.push(val.instansi);
                    if(val.detail_alamat==null){
                        array_detail_alamat.push('');
                    }else{
                        array_detail_alamat.push(val.detail_alamat);
                    }
                    array_tanggal_pemberangkatan.push(res[0].tanggal_kedatangan);
                    array_jam_pemberangkatan.push(val.jam_kedatangan.substring(0, 5));
                    array_tujuan_pemberangkatan.push(val.tujuan_pemberangkatan);
                    if(val.jarak_tempuh==null){
                        array_jarak_tempuh.push('');
                    }else{
                        array_jarak_tempuh.push(val.jarak_tempuh);
                    }
                    if(val.jenis_barang==null){
                        array_jenis_barang.push('');
                    }else{
                        array_jenis_barang.push(val.jenis_barang);
                    }
                    if(val.quantity==null){
                        array_quantity.push('');
                    }else{
                        array_quantity.push(val.quantity);
                    }
                    if(val.satuan==null){
                        array_satuan.push('');
                    }else{
                        array_satuan.push(val.satuan);
                    }
                    if(val.nama_penerima==null){
                        array_nama_penerima.push('');
                    }else{
                        array_nama_penerima.push(val.nama_penerima);
                    }
                    if(val.keterangan_barang==null){
                        array_keterangan_barang.push('');
                    }else{
                        array_keterangan_barang.push(val.keterangan_barang);
                    }
                    if(val.nama_tamu==null){
                        array_nama_tamu.push('');
                    }else{
                        array_nama_tamu.push(val.nama_tamu);
                    }
                    if(val.nomor_hp_tamu==null){
                        array_nomor_hp_tamu.push('');
                    }else{
                        array_nomor_hp_tamu.push(val.nomor_hp_tamu);
                    }
                    if(val.karyawan_dinas_luar==null){
                        array_karyawan_dinas_luar.push('');
                    }else{
                        array_karyawan_dinas_luar.push(val.karyawan_dinas_luar);
                    }
                    array_driver.push(val.driver);
                    array_driver_name.push(val.driver_name);
                    array_vehicle.push(val.vehicle);
                    array_vehicle_name.push(val.vehicle_name);
                    array_status.push(val.status);
                    array_alternative.push(val.alternative);
                    show_destination_list(action);
                });
            },
            error: function(res){
                swal({
                    title: "Ambil data",
                    text: "Data gagal di ambil",
                    icon: "danger",
                });
            }
        });
    }
    function perubahanan_alternative(){
        document.getElementById('value_alternative').style.border="";
    }
    function perubahanan_status_2(){
        var value_status_choosed=$('#value_status_2').val();
        if(value_status_choosed==2){
            document.getElementById('value_status_2').style.border='';
            document.getElementById('tag_alternanative_choosed').style.display='block';
            jQuery.each(array_driver, function(key,val){
                array_driver[key]='';
                array_vehicle[key]='';
                array_status[key]=0;
                array_alternative[key]='';
                $('#driver_yang_ke_2_'+key).val('');
                $('#kendaraan_yang_ke_2_'+key).val('');
                $('#status_yang_ke_2_'+key).val(0);
                $('#alternative_yang_ke_2_'+key).val('');
                document.getElementById('driver_yang_ke_2_'+key).style.border='';
                document.getElementById('kendaraan_yang_ke_2_'+key).style.border='';
            });
        }else{
            $('#value_alternative').val('');
            document.getElementById('value_alternative').style.border='';
            document.getElementById('value_status_2').style.border='';
            if(value_status_choosed==0){
                document.getElementById('tag_alternanative_choosed').style.display='none';
                $('#value_alternative').val('');
                jQuery.each(array_driver, function(key,val){
                    array_driver[key]='';
                    array_vehicle[key]='';
                    array_status[key]=0;
                    array_alternative[key]='';
                    $('#driver_yang_ke_2_'+key).val('');
                    $('#kendaraan_yang_ke_2_'+key).val('');
                    $('#status_yang_ke_2_'+key).val(0);
                    $('#alternative_yang_ke_2_'+key).val('');
                    document.getElementById('driver_yang_ke_2_'+key).style.border='';
                    document.getElementById('kendaraan_yang_ke_2_'+key).style.border='';
                });
            }else if(value_status_choosed==6){
                document.getElementById('tag_alternanative_choosed').style.display='none';
                $('#value_alternative').val('');
                jQuery.each(array_driver, function(key,val){
                    array_driver[key]='';
                    array_vehicle[key]='';
                    array_status[key]=0;
                    array_alternative[key]='';
                    $('#driver_yang_ke_2_'+key).val('');
                    $('#kendaraan_yang_ke_2_'+key).val('');
                    $('#status_yang_ke_2_'+key).val(0);
                    $('#alternative_yang_ke_2_'+key).val('');
                    document.getElementById('driver_yang_ke_2_'+key).style.border='';
                    document.getElementById('kendaraan_yang_ke_2_'+key).style.border='';
                });
            }else{
                document.getElementById('tag_alternanative_choosed').style.display='none';
            }
        }
    }
    function change_status_function(id,employee_name,department_name,detail_alamat,detail_address,desa,detail_alamat_tujuan,desa_tujuan,tujuan_pemberangkatan,waktu_pemberangkatan,jarak_tempuh,status,alternative){
        $('#id_request_3').val(id);
        $("#nama_karyawan_modal_3").html(employee_name);
        $("#department_modal_3").html(department_name);
        $("#detail_alamat_modal_3").html(detail_alamat+' '+detail_address);
        $("#city_modal_3").html(desa);
        $("#detail_alamat_tujuan_modal_3").html(detail_alamat_tujuan);
        $("#city_tujuan_modal_3").html(desa_tujuan);
        $("#tujuan_pemberangkatan_modal_3").html(tujuan_pemberangkatan.toUpperCase());
        $("#waktu_pemberangkatan_modal_3").html(waktu_pemberangkatan.toUpperCase());
        $("#jarak_tempuh_modal_3").html(jarak_tempuh+' Km');
        $('#value_status_2').val(status);
        if(status==2){
            document.getElementById('tag_alternanative_choosed').style.display='block';
        }else{
            document.getElementById('tag_alternanative_choosed').style.display='none';
        }
        $('#value_alternative').val(alternative);
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_tujuan_detail')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data:{
                id:id,
            },
            success: function(res){
                array_provinsi=[];
                array_kota=[];
                array_kecamatan=[];
                array_desa=[];
                array_instansi=[];
                array_detail_alamat=[];
                array_tanggal_pemberangkatan=[];
                array_jam_pemberangkatan=[];
                array_tujuan_pemberangkatan=[];
                array_jarak_tempuh=[];
                array_jenis_barang=[];
                array_quantity=[];
                array_satuan=[];
                array_nama_penerima=[];
                array_keterangan_barang=[];
                array_nama_tamu=[];
                array_nomor_hp_tamu=[];
                array_karyawan_dinas_luar=[];
                array_driver=[];
                array_driver_name=[];
                array_vehicle=[];
                array_vehicle_name=[];
                array_status=[];
                array_alternative=[];
                array_keterangan=[];
                jQuery.each(res, function(key,val){
                    array_provinsi.push(val.prov_name);
                    array_kota.push(val.city_name);
                    array_kecamatan.push(val.dis_name);
                    array_desa.push(val.subdis_name);
                    array_instansi.push(val.instansi);
                    if(val.detail_alamat==null){
                        array_detail_alamat.push('');
                    }else{
                        array_detail_alamat.push(val.detail_alamat);
                    }
                    array_tanggal_pemberangkatan.push(val.tanggal_kedatangan);
                    array_jam_pemberangkatan.push(val.jam_kedatangan.substring(0, 5));
                    array_tujuan_pemberangkatan.push(val.tujuan_pemberangkatan);
                    if(val.jarak_tempuh==null){
                        array_jarak_tempuh.push('');
                    }else{
                        array_jarak_tempuh.push(val.jarak_tempuh);
                    }
                    if(val.jenis_barang==null){
                        array_jenis_barang.push('');
                    }else{
                        array_jenis_barang.push(val.jenis_barang);
                    }
                    if(val.quantity==null){
                        array_quantity.push('');
                    }else{
                        array_quantity.push(val.quantity);
                    }
                    if(val.satuan==null){
                        array_satuan.push('');
                    }else{
                        array_satuan.push(val.satuan);
                    }
                    if(val.nama_penerima==null){
                        array_nama_penerima.push('');
                    }else{
                        array_nama_penerima.push(val.nama_penerima);
                    }
                    if(val.keterangan_barang==null){
                        array_keterangan_barang.push('');
                    }else{
                        array_keterangan_barang.push(val.keterangan_barang);
                    }
                    if(val.nama_tamu==null){
                        array_nama_tamu.push('');
                    }else{
                        array_nama_tamu.push(val.nama_tamu);
                    }
                    if(val.nomor_hp_tamu==null){
                        array_nomor_hp_tamu.push('');
                    }else{
                        array_nomor_hp_tamu.push(val.nomor_hp_tamu);
                    }
                    if(val.karyawan_dinas_luar==null){
                        array_karyawan_dinas_luar.push('');
                    }else{
                        array_karyawan_dinas_luar.push(val.karyawan_dinas_luar);
                    }
                    array_driver.push(val.driver);
                    array_driver_name.push(val.driver_name);
                    array_vehicle.push(val.vehicle);
                    array_vehicle_name.push(val.vehicle_name);
                    array_status.push(val.status);
                    array_alternative.push(val.alternative);
                    show_destination_list_3();
                });
            },
            error: function(res){
                swal({
                    title: "Ambil data",
                    text: "Data gagal di ambil",
                    icon: "danger",
                });
            }
        });
    }
    function change_status_user_function(id,employee_name,department_name,detail_alamat,detail_address,desa,detail_alamat_tujuan,desa_tujuan,tujuan_pemberangkatan,waktu_pemberangkatan,jarak_tempuh,status,alternative){
        $("#nama_karyawan_modal_4").html(employee_name);
        $("#department_modal_4").html(department_name);
        $("#detail_alamat_modal_4").html(detail_alamat+' '+detail_address);
        $("#city_modal_4").html(desa);
        $("#detail_alamat_tujuan_modal_4").html(detail_alamat_tujuan);
        $("#city_tujuan_modal_4").html(desa_tujuan);
        $("#tujuan_pemberangkatan_modal_4").html(tujuan_pemberangkatan.toUpperCase());
        var status_string='';
        if(status==0){
            status_string="PENDING";
        }else if(status==1){
            status_string="APPROVED";
        }else if(status==2){
            status_string="ALTERNATIVE";
        }else if(status==3){
            status_string="ON THE WAY";
        }else if(status==4){
            status_string="DONE";
        }else if(status==5){
            status_string="LATE";
        }else if(status==6){
            status_string="CANCEL";
        }
        $('#status_modal_4').text(status_string);
        if(status==2){
            document.getElementById('tag_alternanative_choosed_user').style.display='block';
        }else{
            document.getElementById('tag_alternanative_choosed_user').style.display='none';
        }
        $('#alternative_modal_4').text(alternative);
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_tujuan_detail')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data:{
                id:id,
            },
            success: function(res){
                $('#tujuan_advanced_4').empty();
                jQuery.each(res, function(key,val){
                    var tanggal_pemberangkatin=new Date(val.tanggal_kedatangan);
                    var formattedDatin = tanggal_pemberangkatin.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                    var statusin=val.status;
                    var statusstring='';
                    switch(statusin) {
                        case 0:
                            statusstring='PENDING';
                            break;
                        case 1:
                            statusstring='APPROVED';
                            break;
                        case 2:
                            statusstring=`ALTERNATIVE`;
                            break;
                        case 3:
                            statusstring='ON THE WAY';
                            break;
                        case 4:
                            statusstring='DONE';
                            break;
                        case 5:
                            statusstring='LATE';
                            break;
                        case 6:
                            statusstring='CANCEL';
                            break;
                        default:
                            statusstring='undefined';
                    }
                    $('#tujuan_advanced_4').append(`<tr>
                        <td>`+(key+1)+`</td>
                        <td>`+val.instansi+` (`+val.detail_alamat+`) `+val.subdis_name+` - `+val.dis_name+` - `+val.city_name+` - `+val.prov_name+`</td>
                        <td>`+formattedDatin+` - `+val.jam_kedatangan.substring(0, 5)+`</td>
                        <td>
                            <div id="tag_keterangan_barang_ke_4_`+key+`" style="display:none">
                                <div class="row">
                                    <div class="col-5">
                                        <label class="form-label">Jenis Barang</label>
                                    </div>
                                    <div class="col-7">: `+val.jenis_barang+`</div>
                                </div>
                                <div class="row">
                                    <div class="col-5">
                                        <label class="form-label">Quantity</label>
                                    </div>
                                    <div class="col-7">: `+val.quantity+` `+val.satuan+`</div>
                                </div>
                                <div class="row">
                                    <div class="col-5">
                                        <label class="form-label"><label class="form-label">Nama Penerima</label></label>
                                    </div>
                                    <div class="col-7">: `+val.nama_penerima+`</div>
                                </div>
                                <div class="row">
                                    <div class="col-5">
                                        <label class="form-label"><label class="form-label">Keterangan Barang</label></label>
                                    </div>
                                    <div class="col-7">: `+val.keterangan_barang+`</div>
                                </div>
                            </div>
                            <div id="tag_keterangan_tamu_ke_4_`+key+`" style="display:none">
                                <div class="row">
                                    <div class="col-5">
                                        <label class="form-label"><label class="form-label">Nama Tamu</label></label>
                                    </div>
                                    <div class="col-7">: `+val.nama_tamu+`</div>
                                </div>
                                <div class="row">
                                    <div class="col-5">
                                        <label class="form-label"><label class="form-label">Nomor HP</label></label>
                                    </div>
                                    <div class="col-7">: `+val.nomor_hp_tamu+`</div>
                                </div>
                            </div>
                            <div id="tag_keterangan_dinas_ke_4_`+key+`" style="display:none">
                                <div class="row">
                                    <div class="col-5">
                                        <label class="form-label"><label class="form-label">Karyawan yg dinas luar</label></label>
                                    </div>
                                    <div class="col-7">: `+getKaryawanDinasLuar(val.karyawan_dinas_luar,key)+`</div>
                                </div>
                            </div>
                        </td>
                        <td>`+(val.driver_name==null?'':val.driver_name)+`</td>
                        <td>`+(val.vehicle_name==null?'':val.vehicle_name)+`</td>
                        <td>`+statusstring+`
                            <div id="tag_alternative_yang_ke_3_`+key+`" style="display:none">
                                <div class="row pt-1">
                                    <div class="col-12">
                                        <textarea class="form-control" id="alternative_yang_ke_3_`+key+`" style="background-color:white" readonly></textarea>
                                    </div>
                                </div>
                        </div></td>
                    </tr>`);
                    if(val.status==2){
                        document.getElementById('tag_alternative_yang_ke_3_'+key).style.display='block';
                    }
                    $('#alternative_yang_ke_3_'+key).val(val.alternative);
                    if (val.tujuan_pemberangkatan.includes("antar_tamu")||val.tujuan_pemberangkatan.includes("jemput_tamu")) {
                    document.getElementById('tag_keterangan_tamu_ke_4_'+key).style.display='block';
                    }
                    if (val.tujuan_pemberangkatan.includes("antar_barang")||val.tujuan_pemberangkatan.includes("jemput_barang")) {
                    document.getElementById('tag_keterangan_barang_ke_4_'+key).style.display='block';
                    }
                    if (val.tujuan_pemberangkatan.includes("antar_dinas")||val.tujuan_pemberangkatan.includes("jemput_dinas")) {
                    document.getElementById('tag_keterangan_dinas_ke_4_'+key).style.display='block';
                    }

                });
            },
            error: function(res){
                swal({
                    title: "Ambil data",
                    text: "Data gagal di ambil",
                    icon: "danger",
                });
            }
        });
    }
    function show_destination_list(action){
        $('#tujuan_advanced').empty();
        jQuery.each(array_provinsi, function(key,val){
            var tanggal_pemberangkat=new Date(array_tanggal_pemberangkatan[key]);
            var formattedDate = tanggal_pemberangkat.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            $('#tujuan_advanced').append(`<tr>
                    <td>`+(key+1)+`</td>
                    <td>
                        <div class="row">
                            <div class="col-12">
                                `+array_instansi[key]+ `
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" style="font-size:8pt">
                                (`+array_detail_alamat[key]+`)
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" style="font-size:8pt">
                                `+array_desa[key]+ ` - `+array_kecamatan[key]+` - `+array_kota[key]+` - `+val+`
                            </div>
                        </div>
                    </td>
                    <td>`+formattedDate+` - `+array_jam_pemberangkatan[key]+`</td>
                    <td style="padding-top:0px">
                        <div id="tag_keterangan_barang_ke_`+key+`" style="display:none">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label">Jenis Barang</label>
                                </div>
                                <div class="col-7">: `+array_jenis_barang[key]+`</div>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label">Quantity</label>
                                </div>
                                <div class="col-7">: `+array_quantity[key]+` `+array_satuan[key]+`</div>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label"><label class="form-label">Nama Penerima</label></label>
                                </div>
                                <div class="col-7">: `+array_nama_penerima[key]+`</div>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label"><label class="form-label">Keterangan Barang</label></label>
                                </div>
                                <div class="col-7">: `+array_keterangan_barang[key]+`</div>
                            </div>
                        </div>
                        <div id="tag_keterangan_tamu_ke_`+key+`" style="display:none">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label"><label class="form-label">Nama Tamu</label></label>
                                </div>
                                <div class="col-7">: `+array_nama_tamu[key]+`</div>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label"><label class="form-label">Nomor HP</label></label>
                                </div>
                                <div class="col-7">: `+array_nomor_hp_tamu[key]+`</div>
                            </div>
                        </div>
                        <div id="tag_keterangan_dinas_ke_`+key+`" style="display:none">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label"><label class="form-label">Karyawan yg dinas luar</label></label>
                                </div>
                                <div class="col-7" id="karyawan_yang_dinas_luar_ke_`+key+`"></div>
                            </div>
                        </div>
                    </td>
                    ${
                        action=='approve'
                        ? `
                            <td>
                                <select id="driver_yang_ke_`+key+`" class="form-control" onchange="driverChange(`+key+`,this.value)" style="background-color: white">
                                    <option value="">Pilih Driver</option>
                                    @foreach($drivers as $key=>$value)
                                        <option value="{{$value->enroll_id}}">{{$value->employee_name}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select id="kendaraan_yang_ke_`+key+`" class="form-control" onchange="vehicleChange(`+key+`,this.value)" style="background-color: white">
                                    <option value="">Pilih Kendaraan</option>
                                    @foreach($vehicles as $key=>$value)
                                        <option value="{{$value->id}}">{{$value->plat_no}} || {{$value->merk}} {{$value->tipe}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-12">
                                        <select class="form-control pl-2" id="status_yang_ke_`+key+`" style="background-color: white" onchange="perubahanan_status_yang_ke(`+key+`,this.value)">\
                                            <option value=0>Pilih Status</option>
                                            <option value=1>Approved</option>
                                            <option value=2>Alternative</option>
                                            <option value=3>On The Way</option>
                                            <option value=4>Done</option>
                                            <option value=5>Late</option>
                                            <option value=6>Cancel</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="tag_alternative_yang_ke_`+key+`" style="display:none">
                                    <div class="row pt-1">
                                        <div class="col-12">
                                            <textarea class="form-control" id="alternative_yang_ke_`+key+`" style="background-color:white" onchange="alternativeChange(`+key+`,this.value)" placeholder="Masukkan Alternatif"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </td>`
                        : ``
                    }

                </tr>
            `);
            $('#driver_yang_ke_'+key).val(array_driver[key]);
            $('#kendaraan_yang_ke_'+key).val(array_vehicle[key]);
            $('#status_yang_ke_'+key).val(array_status[key]);
            if(array_status[key]==2){
                document.getElementById('tag_alternative_yang_ke_'+key).style.display='block';
            }
            $('#alternative_yang_ke_'+key).val(array_alternative[key]);
            if (array_tujuan_pemberangkatan[key].includes("antar_tamu")||array_tujuan_pemberangkatan[key].includes("jemput_tamu")) {
              document.getElementById('tag_keterangan_tamu_ke_'+key).style.display='block';
            }
            if (array_tujuan_pemberangkatan[key].includes("antar_barang")||array_tujuan_pemberangkatan[key].includes("jemput_barang")) {
              document.getElementById('tag_keterangan_barang_ke_'+key).style.display='block';
            }
            if (array_tujuan_pemberangkatan[key].includes("antar_dinas")||array_tujuan_pemberangkatan[key].includes("jemput_dinas")) {
              document.getElementById('tag_keterangan_dinas_ke_'+key).style.display='block';
              getKaryawanDinasLuar(array_karyawan_dinas_luar[key],key)
            }
        });
    }
    function show_destination_list_2(){
        $('#tujuan_advanced_2').empty();
        jQuery.each(array_provinsi, function(key,val){
            var tanggal_pemberangkat=new Date(array_tanggal_pemberangkatan[key]);
            var formattedDate = tanggal_pemberangkat.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            $('#tujuan_advanced_2').append(`<tr>
                    <td>`+(key+1)+`</td>
                    <td>
                        <div class="row">
                            <div class="col-12">
                                `+array_instansi[key]+ `
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" style="font-size:8pt">
                                (`+array_detail_alamat[key]+`)
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" style="font-size:8pt">
                                `+array_desa[key]+ ` - `+array_kecamatan[key]+` - `+array_kota[key]+` - `+val+`
                            </div>
                        </div>
                    </td>
                    <td>`+formattedDate+` - `+array_jam_pemberangkatan[key]+`</td>
                    <td style="padding-top:0px">
                        <div id="tag_keterangan_barang_ke_2_`+key+`" style="display:none">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label">Jenis Barang</label>
                                </div>
                                <div class="col-7">: `+array_jenis_barang[key]+`</div>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label">Quantity</label>
                                </div>
                                <div class="col-7">: `+array_quantity[key]+` `+array_satuan[key]+`</div>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label"><label class="form-label">Nama Penerima</label></label>
                                </div>
                                <div class="col-7">: `+array_nama_penerima[key]+`</div>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label"><label class="form-label">Keterangan Barang</label></label>
                                </div>
                                <div class="col-7">: `+array_keterangan_barang[key]+`</div>
                            </div>
                        </div>
                        <div id="tag_keterangan_tamu_ke_2_`+key+`" style="display:none">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label"><label class="form-label">Nama Tamu</label></label>
                                </div>
                                <div class="col-7">: `+array_nama_tamu[key]+`</div>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label"><label class="form-label">Nomor HP</label></label>
                                </div>
                                <div class="col-7">: `+array_nomor_hp_tamu[key]+`</div>
                            </div>
                        </div>
                        <div id="tag_keterangan_dinas_ke_2_`+key+`" style="display:none">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label"><label class="form-label">Karyawan yg dinas luar</label></label>
                                </div>
                                <div class="col-7" id="karyawan_yang_dinas_luar_ke_2_`+key+`"></div>
                            </div>
                        </div>
                    </td>
                </tr>
            `);
            if (array_tujuan_pemberangkatan[key].includes("antar_tamu")||array_tujuan_pemberangkatan[key].includes("jemput_tamu")) {
              document.getElementById('tag_keterangan_tamu_ke_2_'+key).style.display='block';
            }
            if (array_tujuan_pemberangkatan[key].includes("antar_barang")||array_tujuan_pemberangkatan[key].includes("jemput_barang")) {
              document.getElementById('tag_keterangan_barang_ke_2_'+key).style.display='block';
            }
            if (array_tujuan_pemberangkatan[key].includes("antar_dinas")||array_tujuan_pemberangkatan[key].includes("jemput_dinas")) {
              document.getElementById('tag_keterangan_dinas_ke_2_'+key).style.display='block';
              getKaryawanDinasLuar(array_karyawan_dinas_luar[key],key);
            }
        });
    }
    function show_destination_list_3(){
        $('#tujuan_advanced_3').empty();
        jQuery.each(array_provinsi, function(key,val){
            var tanggal_pemberangkat=new Date(array_tanggal_pemberangkatan[key]);
            var formattedDate = tanggal_pemberangkat.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            $('#tujuan_advanced_3').append(`<tr>
                    <td>`+(key+1)+`</td>
                    <td>
                        <div class="row">
                            <div class="col-12">
                                `+array_instansi[key]+ `
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" style="font-size:8pt">
                                (`+array_detail_alamat[key]+`)
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" style="font-size:8pt">
                                `+array_desa[key]+ ` - `+array_kecamatan[key]+` - `+array_kota[key]+` - `+val+`
                            </div>
                        </div>
                    </td>
                    <td>`+formattedDate+` - `+array_jam_pemberangkatan[key]+`</td>
                    <td style="padding-top:0px">
                        <div id="tag_keterangan_barang_ke_3_`+key+`" style="display:none">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label">Jenis Barang</label>
                                </div>
                                <div class="col-7">: `+array_jenis_barang[key]+`</div>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label">Quantity</label>
                                </div>
                                <div class="col-7">: `+array_quantity[key]+` `+array_satuan[key]+`</div>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label"><label class="form-label">Nama Penerima</label></label>
                                </div>
                                <div class="col-7">: `+array_nama_penerima[key]+`</div>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label"><label class="form-label">Keterangan Barang</label></label>
                                </div>
                                <div class="col-7">: `+array_keterangan_barang[key]+`</div>
                            </div>
                        </div>
                        <div id="tag_keterangan_tamu_ke_3_`+key+`" style="display:none">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label"><label class="form-label">Nama Tamu</label></label>
                                </div>
                                <div class="col-7">: `+array_nama_tamu[key]+`</div>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label"><label class="form-label">Nomor HP</label></label>
                                </div>
                                <div class="col-7">: `+array_nomor_hp_tamu[key]+`</div>
                            </div>
                        </div>
                        <div id="tag_keterangan_dinas_ke_3_`+key+`" style="display:none">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label"><label class="form-label">Karyawan yg dinas luar</label></label>
                                </div>
                                <div class="col-7" id="karyawan_yang_dinas_luar_ke_3_`+key+`">: </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <select id="driver_yang_ke_2_`+key+`" class="form-control" onchange="driverChange2(`+key+`,this.value)">
                            <option value="">Pilih Driver</option>
                            @foreach($drivers as $key=>$value)
                                <option value="{{$value->enroll_id}}">{{$value->employee_name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select id="kendaraan_yang_ke_2_`+key+`" class="form-control" onchange="vehicleChange2(`+key+`,this.value)">
                            <option value="">Pilih Kendaraan</option>
                            @foreach($vehicles as $key=>$value)
                                <option value="{{$value->id}}">{{$value->plat_no}} || {{$value->merk}} {{$value->tipe}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-12">
                                <select class="form-control pl-2" id="status_yang_ke_2_`+key+`" style="background-color: white" onchange="perubahanan_status_yang_ke2(`+key+`,this.value)">\
                                    <option value=0>Pilih Status</option>
                                    <option value=1>Approved</option>
                                    <option value=2>Alternative</option>
                                    <option value=3>On The Way</option>
                                    <option value=4>Done</option>
                                    <option value=5>Late</option>
                                    <option value=6>Cancel</option>
                                </select>
                            </div>
                        </div>
                        <div id="tag_alternative_yang_ke_2_`+key+`" style="display:none">
                            <div class="row pt-1">
                                <div class="col-12">
                                    <textarea class="form-control" id="alternative_yang_ke_2_`+key+`" style="background-color:white" onchange="alternativeChange2(`+key+`,this.value)" placeholder="Masukkan Alternatif"></textarea>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            `);
            $('#driver_yang_ke_2_'+key).val(array_driver[key]);
            $('#kendaraan_yang_ke_2_'+key).val(array_vehicle[key]);
            $('#status_yang_ke_2_'+key).val(array_status[key]);
            if(array_status[key]==2){
                document.getElementById('tag_alternative_yang_ke_2_'+key).style.display='block';
            }
            $('#alternative_yang_ke_2_'+key).val(array_alternative[key]);
            if (array_tujuan_pemberangkatan[key].includes("antar_tamu")||array_tujuan_pemberangkatan[key].includes("jemput_tamu")) {
              document.getElementById('tag_keterangan_tamu_ke_3_'+key).style.display='block';
            }
            if (array_tujuan_pemberangkatan[key].includes("antar_barang")||array_tujuan_pemberangkatan[key].includes("jemput_barang")) {
              document.getElementById('tag_keterangan_barang_ke_3_'+key).style.display='block';
            }
            if (array_tujuan_pemberangkatan[key].includes("antar_dinas")||array_tujuan_pemberangkatan[key].includes("jemput_dinas")) {
              document.getElementById('tag_keterangan_dinas_ke_3_'+key).style.display='block';
              getKaryawanDinasLuar(array_karyawan_dinas_luar[key],key);
            }
        });
    }
    function change_status_this_request(){
        array_keterangan=[];
        var status_value=$('#value_status_2').val();
        if(status_value==2){
            document.getElementById('value_status_2').style.border="";
            if($('#value_alternative').val()==''){
                document.getElementById('value_alternative').style.border="1px solid red";
                array_keterangan.push('');
            }else{
                document.getElementById('value_alternative').style.border="";
                array_keterangan.push($('#value_alternative').val());
            }
            jQuery.each(array_driver, function(key,value){
                array_driver[key]='';
                array_vehicle[key]='';
                array_status[key]=0;
                array_alternative[key]='';
                $('#driver_yang_ke_2_'+key).val('');
                $('#kendaraan_yang_ke_2_'+key).val('');
                $('#status_yang_ke_2_'+key).val(0);
                $('#alternative_yang_ke_2_'+key).val('');
                document.getElementById('tag_alternative_yang_ke_2_'+key).style.display="none";
            });
        }else if(status_value==0){
            if($('#value_status_2').val()==0){
                document.getElementById('value_status_2').style.border="1px solid red";
                array_keterangan.push('');
            }else{
                document.getElementById('value_status_2').style.border="";
                array_keterangan.push($('#value_status_2').val());
            }
            jQuery.each(array_driver, function(key,value){
                array_driver[key]='';
                array_vehicle[key]='';
                array_status[key]=0;
                array_alternative[key]='';
                $('#driver_yang_ke_2_'+key).val('');
                $('#kendaraan_yang_ke_2_'+key).val('');
                $('#status_yang_ke_2_'+key).val(0);
                $('#alternative_yang_ke_2_'+key).val('');
                document.getElementById('tag_alternative_yang_ke_2_'+key).style.display="none";
            });
        }else{
            if(status_value!=6){
                document.getElementById('value_status_2').style.border="";
                jQuery.each(array_driver, function(key,value){
                    if(array_status[key]==2){
                        if(array_alternative[key]==null || array_alternative[key]==''){
                            document.getElementById("alternative_yang_ke_2_"+key).style.border='1px solid red';
                            array_keterangan.push('');
                        }else{
                            document.getElementById("alternative_yang_ke_2_"+key).style.border='';
                            array_keterangan.push(array_alternative[key]);
                        }
                        array_driver[key]='';
                        array_vehicle[key]='';
                        $('#driver_yang_ke_2_'+key).val('');
                        $('#kendaraan_yang_ke_2_'+key).val('');
                    }else{
                        if(array_status[key]==6){
                            array_driver[key]='';
                            array_vehicle[key]='';
                            array_alternative[key]='';
                            $('#driver_yang_ke_2_'+key).val('');
                            $('#kendaraan_yang_ke_2_'+key).val('');
                            $('#alternative_yang_ke_2_'+key).val('');
                            array_keterangan.push('cancel');
                        }else{
                            if(value==null || value==''){
                                document.getElementById("driver_yang_ke_2_"+key).style.border='1px solid red';
                                array_keterangan.push('');
                            }else{
                                document.getElementById("driver_yang_ke_2_"+key).style.border='';
                                array_keterangan.push(value);
                            }
                            if(array_vehicle[key]==null||array_vehicle[key]==''){
                                document.getElementById("kendaraan_yang_ke_2_"+key).style.border='1px solid red';
                                array_keterangan.push('');
                            }else{
                                document.getElementById("kendaraan_yang_ke_2_"+key).style.border='';
                                array_keterangan.push(array_vehicle[key]);
                            }
                        }
                    }
                });
            }else{
                jQuery.each(array_driver, function(key,value){
                    array_driver[key]='';
                    array_vehicle[key]='';
                    array_status[key]=0;
                    array_alternative[key]='';
                    $('#driver_yang_ke_2_'+key).val('');
                    $('#kendaraan_yang_ke_2_'+key).val('');
                    $('#status_yang_ke_2_'+key).val(0);
                    $('#alternative_yang_ke_2_'+key).val('');
                    document.getElementById('tag_alternative_yang_ke_2_'+key).style.display="none";
                });
                array_keterangan.push('cancel');
            }
        }
        if(!array_keterangan.includes('')){
            $.ajax({
                type:"POST",
                url: "{{route('hris.ga.update_car_request_administrator')}}",
                data: {
                    id:$('#id_request_3').val(),
                    array_driver:array_driver,
                    array_vehicle:array_vehicle,
                    array_status:array_status,
                    array_alternative:array_alternative,
                    status:$('#value_status_2').val(),
                    alternative:$('#value_alternative').val()
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(res){
                    if(res=='hapus'){
                        swal("", "Status permintaan transportasi telah di hapus oleh user", "error");
                    }else{
                        swal("", "Permintaan transportasi berhasil di update", "success");
                        var url = 'data_pengajuan_transportasi';
                        window.open(url, '_self');
                    }
                },
                error: function(error){
                    swal("", "Permintaan transportasi gagal di update", "error");
                }
            });
        }
    }
    function driverChange(key,value){
        array_driver[key]=value;
        if(value!=''){
            document.getElementById('driver_yang_ke_'+key).style.border='';
        }
        if(array_status[key]==2||array_status[key]==6){
          $('#status_yang_ke_'+key).val(0);
          $('#alternative_yang_ke_'+key).val('');
          array_status[key]=0;
          array_alternative[key]='';
          document.getElementById('alternative_yang_ke_'+key).style.border='';
          document.getElementById('tag_alternative_yang_ke_'+key).style.display='none';
        }
    }
    function vehicleChange(key,value){
        array_vehicle[key]=value;
        if(value!=''){
            document.getElementById('kendaraan_yang_ke_'+key).style.border='';
        }
        if(array_status[key]==2||array_status[key]==6){
          $('#status_yang_ke_'+key).val(0);
          $('#alternative_yang_ke_'+key).val('');
          array_status[key]=0;
          array_alternative[key]='';
          document.getElementById('alternative_yang_ke_'+key).style.border='';
          document.getElementById('tag_alternative_yang_ke_'+key).style.display='none';
        }
    }
    function perubahanan_status_yang_ke(key,value){
        array_status[key]=value;
        if(value==6){
            array_driver[key]='';
            array_vehicle[key]='';
            array_alternative[key]='';
            document.getElementById('driver_yang_ke_'+key).style.border='';
            document.getElementById('kendaraan_yang_ke_'+key).style.border='';
            $('#driver_yang_ke_'+key).val('');
            $('#kendaraan_yang_ke_'+key).val('');
            $('#alternative_yang_ke_'+key).val('');
            document.getElementById('tag_alternative_yang_ke_'+key).style.display='none';
            document.getElementById('alternative_yang_ke_'+key).style.border='';
            $('#alternative_yang_ke_'+key).val('');
        }else{
            if(value==2){
                array_driver[key]='';
                array_vehicle[key]='';
                document.getElementById('driver_yang_ke_'+key).style.border='';
                document.getElementById('kendaraan_yang_ke_'+key).style.border='';
                $('#driver_yang_ke_'+key).val('');
                $('#kendaraan_yang_ke_'+key).val('');
                document.getElementById('tag_alternative_yang_ke_'+key).style.display='block';
            }else{
                array_alternative[key]='';
                $('#alternative_yang_ke_'+key).val('');
                document.getElementById('tag_alternative_yang_ke_'+key).style.display='none';
                document.getElementById('alternative_yang_ke_'+key).style.border='';
            }
        }
    }
    function perubahanan_status_yang_ke2(key,value){
        array_status[key]=value;
        if(value==6){
            array_driver[key]='';
            array_vehicle[key]='';
            array_alternative[key]='';
            document.getElementById('driver_yang_ke_2_'+key).style.border='';
            document.getElementById('kendaraan_yang_ke_2_'+key).style.border='';
            $('#driver_yang_ke_2_'+key).val('');
            $('#kendaraan_yang_ke_2_'+key).val('');
            document.getElementById('tag_alternative_yang_ke_2_'+key).style.display='none';
            document.getElementById('alternative_yang_ke_2_'+key).style.border='';
            $('#alternative_yang_ke_2_'+key).val('');
        }else{
            if(value==2){
                array_driver[key]='';
                array_vehicle[key]='';
                document.getElementById('driver_yang_ke_2_'+key).style.border='';
                document.getElementById('kendaraan_yang_ke_2_'+key).style.border='';
                $('#driver_yang_ke_2_'+key).val('');
                $('#kendaraan_yang_ke_2_'+key).val('');
                document.getElementById('tag_alternative_yang_ke_2_'+key).style.display='block';
            }else{
                array_alternative[key]='';
                document.getElementById('tag_alternative_yang_ke_2_'+key).style.display='none';
                document.getElementById('alternative_yang_ke_2_'+key).style.border='';
                $('#alternative_yang_ke_2_'+key).val('');
            }
        }
    }
    function alternativeChange(key,value){
        array_alternative[key]=value;
        if(value!=''){
            document.getElementById('alternative_yang_ke_'+key).style.border='';
        }
    }
    function alternativeChange2(key,value){
        array_alternative[key]=value;
        if(value!=''){
            document.getElementById('alternative_yang_ke_2_'+key).style.border='';
        }
    }
    function driverChange2(key,value){
        array_driver[key]=value;
        if(value!=''){
            document.getElementById('driver_yang_ke_2_'+key).style.border='';
        }
        if(array_status[key]==2||array_status[key]==6){
          $('#status_yang_ke_2_'+key).val(0);
          $('#alternative_yang_ke_2_'+key).val('');
          array_status[key]=0;
          array_alternative[key]='';
          document.getElementById('alternative_yang_ke_2_'+key).style.border='';
          document.getElementById('tag_alternative_yang_ke_2_'+key).style.display='none';
        }
    }
    function vehicleChange2(key,value){
        array_vehicle[key]=value;
        if(value!=''){
            document.getElementById('kendaraan_yang_ke_2_'+key).style.border='';
        }
        if(array_status[key]==2||array_status[key]==6){
          $('#status_yang_ke_2_'+key).val(0);
          $('#alternative_yang_ke_2_'+key).val('');
          array_status[key]=0;
          array_alternative[key]='';
          document.getElementById('alternative_yang_ke_2_'+key).style.border='';
          document.getElementById('tag_alternative_yang_ke_2_'+key).style.display='none';
        }
    }
    function reject_function(id,employee_name,department_name,detail_alamat,detail_address,desa,detail_alamat_tujuan,desa_tujuan,tujuan_pemberangkatan,waktu_pemberangkatan,jarak_tempuh){
        $('#id_request_2').val(id);
        $("#nama_karyawan_modal_2").html(employee_name);
        $("#department_modal_2").html(department_name);
        $("#detail_alamat_modal_2").html(detail_alamat+' '+detail_address);
        $("#city_modal_2").html(desa);
        $("#detail_alamat_tujuan_modal_2").html(detail_alamat_tujuan);
        $("#city_tujuan_modal_2").html(desa_tujuan);
        $("#tujuan_pemberangkatan_modal_2").html(tujuan_pemberangkatan);
        $("#waktu_pemberangkatan_modal_2").html(waktu_pemberangkatan.toUpperCase());
        $("#jarak_tempuh_modal").html(jarak_tempuh+' Km');
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_tujuan_detail')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data:{
                id:id,
            },
            success: function(res){
                array_provinsi=[];
                array_kota=[];
                array_kecamatan=[];
                array_desa=[];
                array_instansi=[];
                array_detail_alamat=[];
                array_tanggal_pemberangkatan=[];
                array_jam_pemberangkatan=[];
                array_tujuan_pemberangkatan=[];
                array_jarak_tempuh=[];
                array_jenis_barang=[];
                array_quantity=[];
                array_satuan=[];
                array_nama_penerima=[];
                array_keterangan_barang=[];
                array_nama_tamu=[];
                array_nomor_hp_tamu=[];
                array_karyawan_dinas_luar=[];
                array_driver=[];
                array_driver_name=[];
                array_vehicle=[];
                array_vehicle_name=[];
                array_keterangan=[];
                jQuery.each(res, function(key,val){
                    array_provinsi.push(val.prov_name);
                    array_kota.push(val.city_name);
                    array_kecamatan.push(val.dis_name);
                    array_desa.push(val.subdis_name);
                    array_instansi.push(val.instansi);
                    if(val.detail_alamat==null){
                        array_detail_alamat.push('');
                    }else{
                        array_detail_alamat.push(val.detail_alamat);
                    }
                    array_tanggal_pemberangkatan.push(val.tanggal_kedatangan);
                    array_jam_pemberangkatan.push(val.jam_kedatangan.substring(0, 5));
                    array_tujuan_pemberangkatan.push(val.tujuan_pemberangkatan);
                    if(val.jarak_tempuh==null){
                        array_jarak_tempuh.push('');
                    }else{
                        array_jarak_tempuh.push(val.jarak_tempuh);
                    }
                    if(val.jenis_barang==null){
                        array_jenis_barang.push('');
                    }else{
                        array_jenis_barang.push(val.jenis_barang);
                    }
                    if(val.quantity==null){
                        array_quantity.push('');
                    }else{
                        array_quantity.push(val.quantity);
                    }
                    if(val.satuan==null){
                        array_satuan.push('');
                    }else{
                        array_satuan.push(val.satuan);
                    }
                    if(val.nama_penerima==null){
                        array_nama_penerima.push('');
                    }else{
                        array_nama_penerima.push(val.nama_penerima);
                    }
                    if(val.keterangan_barang==null){
                        array_keterangan_barang.push('');
                    }else{
                        array_keterangan_barang.push(val.keterangan_barang);
                    }
                    if(val.nama_tamu==null){
                        array_nama_tamu.push('');
                    }else{
                        array_nama_tamu.push(val.nama_tamu);
                    }
                    if(val.nomor_hp_tamu==null){
                        array_nomor_hp_tamu.push('');
                    }else{
                        array_nomor_hp_tamu.push(val.nomor_hp_tamu);
                    }
                    if(val.karyawan_dinas_luar==null){
                        array_karyawan_dinas_luar.push('');
                    }else{
                        array_karyawan_dinas_luar.push(val.karyawan_dinas_luar);
                    }
                    array_driver.push(val.driver);
                    array_driver_name.push(val.driver_name);
                    array_vehicle.push(val.vehicle);
                    array_vehicle_name.push(val.vehicle_name);
                    show_destination_list_2();
                });
            },
            error: function(res){
                swal({
                    title: "Ambil data",
                    text: "Data gagal di ambil",
                    icon: "danger",
                });
            }
        });
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
    $('#alternate_this_request_button').on('click',function(){
        var id_request=$('#id_request').val();
        var alasan_reject=$('#alternative_form').val();
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
                $('#approveModal').modal('hide');
                dataTableReload();
            },
            error: function(error){
                let err_log=error.responseJSON.errors;
                if(error.status==422){
                    if(typeof(err_log.alasan_reject)!=='undefined'){
                        document.getElementById("alternative_form").style.border = "1px solid red";
                    }else{
                        document.getElementById("alternative_form").style.border="";
                    }
                }
            }
        });
    });
    $('#approve_this_request').on('click',function(){
        array_keterangan=[];
        jQuery.each(array_driver, function(key,value){
            if(array_status[key]==6){
                array_keterangan.push(array_status[key]);
                array_driver[key]='';
                array_vehicle[key]='';
                $('#driver_yang_ke_'+key).val('');
                $('#kendaraan_yang_ke_'+key).val('');
            }else{
                if(array_status[key]==2){
                    if(array_alternative[key]==null || array_alternative[key]==''){
                        document.getElementById("alternative_yang_ke_"+key).style.border='1px solid red';
                        array_keterangan.push('');
                    }else{
                        document.getElementById("alternative_yang_ke_"+key).style.border='';
                        array_keterangan.push(array_alternative[key]);
                    }
                    array_driver[key]='';
                    array_vehicle[key]='';
                    $('#driver_yang_ke_'+key).val('');
                    $('#kendaraan_yang_ke_'+key).val('');
                }else{
                    if(value==null || value==''){
                        document.getElementById("driver_yang_ke_"+key).style.border='1px solid red';
                        array_keterangan.push('');
                    }else{
                        document.getElementById("driver_yang_ke_"+key).style.border='';
                        array_keterangan.push(value);
                    }
                    if(array_vehicle[key]==null||array_vehicle[key]==''){
                        document.getElementById("kendaraan_yang_ke_"+key).style.border='1px solid red';
                        array_keterangan.push('');
                    }else{
                        document.getElementById("kendaraan_yang_ke_"+key).style.border='';
                        array_keterangan.push(array_vehicle[key]);
                    }
                }
            }
        });
        var id_request=$('#id_request').val();
        if(!array_keterangan.includes('')){
            $.ajax({
                type:"POST",
                url: "{{route('hris.ga.approve_car_request')}}",
                data: {
                    id_request:id_request,
                    array_driver:array_driver,
                    array_vehicle:array_vehicle,
                    array_status:array_status,
                    array_alternative:array_alternative
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(res){
                    swal("", "Permintaan transportasi di approve", "success");
                    $('#id_request').val('');
                    $('#approveModal').modal('hide');
                    dataTableReload();
                },
                error: function(error){
                    swal("", "Permintaan transportasi gagal di approve", "error");
                }
            });
        }
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
    function getKaryawanDinasLuar(value,key){
        if(value!=null){
            $.ajax({
                type:"POST",
                url: "{{route('hris.ga.get_employee_dinas')}}",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data:{
                    id:value,
                },
                success: function(res){
                    $('#karyawan_yang_dinas_luar_ke_'+key).text(': '+res);
                    $('#karyawan_yang_dinas_luar_ke_2_'+key).text(': '+res);
                    $('#karyawan_yang_dinas_luar_ke_3_'+key).text(': '+res);
                }
            });
        }else{
            $('#karyawan_yang_dinas_luar_ke_'+key).text('');
        }
    }
</script>
@endsection
