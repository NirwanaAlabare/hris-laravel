@extends('admin.adminlayouts.adminlayout4')

@section('head')
    <link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

	<!---Sweetalert Css-->
	<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

    <!-- Date Picker css-->
    <link href="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.css')}}" rel="stylesheet" />

    <!-- Time picker css-->
    <link href="{{URL::asset('assets/plugins/time-picker/jquery.timepicker.css')}}" rel="stylesheet" />

    <!-- Tabs css-->
	<link href="{{URL::asset('assets/plugins/tabs/tabs-style.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::asset('assets/css/iziToast.min.css') }}">
<style>
    #datatable tr td {
        height: 4px;
        line-height: 0.3;
    }
</style>
<style>
.image-card {
    position: relative;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 15px;
    text-align: center;
    background: #f9f9f9;
}
.image-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}
.image-actions {
    position: absolute;
    top: 5px;
    right: 5px;
}
.image-actions button {
    margin-left: 3px;
}

</style>


@stop
@section('mainarea')

    <div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
        <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
            <li><a href="{{route('entertaint_tamu.index')}}">Transportasi</a></li>
            <li class="active"><span>Pengajuan Perbaikan Kendaraan</span></li>
            <input type="hidden" value="{{$user}}" id="username_who_access">
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
    <div class="row p-3">
        <div class="col-md-12">
            <div class="card card-primary card-outline tab-content">
                <div class="card-header bg-primary p-3">
                    <div class="card-title">Pengajuan Perbaikan Kendaraan</div>
                    <input type="hidden" id="daterange1" name="daterange1">
                </div>
                <div class="mt-4 ml-4 mr-5 mb-0">
                    <div class="row">
                        <div class="col-md-6">
                            <div clasl="" style="display: flex; justify-content: start; align-items: center; gap: 10px;">
                                    <div class="mt-5 p-0 w-50">
                                    <input type="" class="form-control" id="daterange-btn1" data-toggle="tooltip"
                                    title="" data-placement="bottom"  placeholder="PILIH TANGGAL" data-original-title="Klik di sini untuk pilih tanggal kehadiran">
                                    </input>
                                </div>
                                <div class="mt-5 p-0 w-50">
                                    <button type="button" class="btn btn-warning ml-2" id="clear-daterange">Clear</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div clasl="card-header mt-5 p-0" style="display: flex; justify-content: end; align-items: center;">
                                <div class="mt-5 p-0">
                                    <button class="btn btn-primary w-100" onclick="open_modal_buat_pengajuan_perbaikan()"  data-toggle="tooltip" title="BUAT PENGAJUAN" id="recap_labor_cost_2"><i class="fa fa-plus" aria-hidden="true"></i> Buat Pengajuan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div clasl="" style="display: flex; justify-content: start; align-items: center; gap: 10px;">
                                <div class="mt-5 p-0 w-50">
                                    <button type="button" class="btn btn-secondary ml-2" id="export-excel"><i class="fa fa-file-excel-o"></i> Export</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-0 p-0">
                    <div class="card-body m-0">
                        <div class="panel panel-primary  px-3 py-2 pt-5">
                            <div class="tab_wrapper first_tab">
                                <ul class="tab_list">
                                    <li class="text-sm" id="tab-waiting">Menunggu Verifikasi</li>
                                    <li class="text-sm" id="tab-verifikasi">Verifikasi</li>
                                    {{-- <li class="text-sm" id="tab-rejected">Ditolak</li> --}}
                                </ul>
                                <div class="content_wrapper">
                                    <!-- Tab Waiting -->
                                    <div class="tab_content active" id="tab-content-waiting">
                                        <button id="verifikasi-btn" class="btn btn-primary py-2 mb-4" style="display: none">Verifikasi</button>
                                        <div class="table-responsive">
                                            <table id="datatable-ajax-crud-waiting" class="table table-sm table-striped table-hover table-bordered w-100">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th scope="col">Tanggal Pengajuan</th>
                                                        <th scope="col">Merk Kendaraan</th>
                                                        <th scope="col">Nomor Polisi</th>
                                                        <th scope="col">Driver</th>
                                                        <th scope="col">NIP</th>
                                                        <th scope="col">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Tab Verifikasi -->
                                    <div class="tab_content" id="tab-content-verifikasi">
                                        <div class="table-responsive">
                                            <table id="datatable-ajax-crud-verifikasi" class="table table-sm table-striped table-hover table-bordered w-100">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th scope="col">Tanggal Pengajuan</th>
                                                        <th scope="col">Merk Kendaraan</th>
                                                        <th scope="col">Nomor Polisi</th>
                                                        <th scope="col">Driver</th>
                                                        <th scope="col">NIP</th>
                                                        <th scope="col">Aksi</th>
                                                        <th scope="col">Tgl Realisasi</th>
                                                        <th scope="col">Status Realisasi</th>
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
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ajax-modal-tambah"  role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 50%;">
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-content">
                        <div class="modal-header bg-primary p-2">
                            <h4 class="modal-title pl-2 font-weight-bold" id="title-modal-edit1">BUAT PENGAJUAN PERBAIKAN</h4>
                            <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                 <div class="col-md-12">
                                    <div class="form-group border">
                                        <label class="form-label">TANGGAL PENGAJUAN : </label>
                                        <div class="input-group">
                                            <p class="form-label" id="tanggal_pengajuan_label">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                                            <input type="hidden" id="tanggal_pengajuan_perbaikan" name="tanggal_pengajuan_perbaikan" value="{{ \Carbon\Carbon::now()->translatedFormat('Y-m-d') }}" class="form-control create-control">
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">PEMERIKSA : </label>
                                        <select id="diajukanOlehID" name="diajukanOlehID" style='width: 100%;' data-placeholder="Pilih Pemeriksa" class="form-control create-control select2 select2-show-search EmployeeID">
                                            <option value="">
                                                    Pilih Pemeriksa
                                                </option>
                                            @foreach ($selectemployee as $r_empl)
                                                <option
                                                    value="{{$r_empl->enroll_id}}"
                                                    data-department_name="{{$r_empl->department_name}}"
                                                    data-sub_dept_name="{{$r_empl->sub_dept_name}}"
                                                    data-department_data_id="{{$r_empl->department_id}}"
                                                    data-sub_dept_data_id="{{$r_empl->sub_dept_id}}"
                                                >
                                                    {{$r_empl->select_employee}}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="error-message text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">PILIH KENDARAAN : </label>
                                        <select class="form-control" id="vehicle_id">
                                            <option value="">Pilih Kendaraan</option>
                                            @foreach ($vehicles as $key=>$value)
                                                <option value="{{$value->id}}">{{$value->plat_no}} || {{$value->merk}} {{$value->tipe}}</option>
                                            @endforeach
                                        </select>
                                        <small class="error-message text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                <label class="form-label">DETAIL PEMELIHARAAN :</label>
                                <table class="table table-bordered" id="pemeliharaan-table">
                                    <thead>
                                        <tr>
                                            <th width="25%">Jenis Pemeliharaan</th>
                                            <th width="15%">Odometer</th>
                                            <th width="25%">Penyedia Jasa</th>
                                            <th width="25%">Keterangan</th>
                                            <th width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-control jenis_pemeliharaan" name="jenis_pemeliharaan[]">
                                                    <option value="">Pilih</option>
                                                    @foreach ($komponent_pemerikasaan_kendaraan as $value)
                                                        <option value="{{$value->id}}">
                                                            {{$value->nama_item_pemeriksaan_detail}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control odometer" name="odometer[]"></td>
                                            <td><input type="text" class="form-control penyedia_jasa" name="penyedia_jasa[]"></td>
                                            <td><input type="text" class="form-control keterangan" name="keterangan[]"></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-success btn-sm" id="add-row">+ Tambah</button>
                            </div>

                            </div>
                        </div>
                        <div class="modal-footer bg-primary p-1">
                            <div class="btn-list">
                                <button type="button" id="action-form" data-mode="create"  class="btn btn-secondary btn-app">Simpan</button>
                                <button type="button" id="btn-close_edit1" class="btn btn-warning btn-app" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ajax-modal-realisasi-perbaikan"  role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 50%;">
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-content">
                        <div class="modal-header bg-primary p-2">
                            <h4 class="modal-title pl-2 font-weight-bold" id="title-modal-realisasi">REALISASI PERBAIKAN</h4>
                            <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                                <form id="form-realisasi" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group border">
                                                <label class="form-label">TANGGAL PENGAJUAN : </label>
                                                <div class="input-group">
                                                    <p class="form-label" id="tanggal_pengajuan_label_realisasi">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                                                    <input type="hidden" id="tanggal_pengajuan_perbaikan_realisasi" name="tanggal_pengajuan_perbaikan_realisasi" value="{{ \Carbon\Carbon::now()->translatedFormat('Y-m-d') }}" class="form-control create-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">PEMERIKSA : </label>
                                                <select id="diajukanOlehIDRealisasi" name="diajukanOlehIDRealisasi" style='width: 100%;' data-placeholder="Pilih Pemeriksa" class="form-control create-control select2 select2-show-search EmployeeID">
                                                    <option value="">
                                                            Pilih Pemeriksa
                                                        </option>
                                                    @foreach ($selectemployee as $r_empl)
                                                        <option
                                                            value="{{$r_empl->enroll_id}}"
                                                            data-department_name="{{$r_empl->department_name}}"
                                                            data-sub_dept_name="{{$r_empl->sub_dept_name}}"
                                                            data-department_data_id="{{$r_empl->department_id}}"
                                                            data-sub_dept_data_id="{{$r_empl->sub_dept_id}}"
                                                        >
                                                            {{$r_empl->select_employee}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="error-message text-danger"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">PILIH KENDARAAN : </label>
                                                <select class="form-control" id="vehicle_id_realisasi">
                                                    <option value="">Pilih Kendaraan</option>
                                                    @foreach ($vehicles as $key=>$value)
                                                        <option value="{{$value->id}}">{{$value->plat_no}} || {{$value->merk}} {{$value->tipe}}</option>
                                                    @endforeach
                                                </select>
                                                <small class="error-message text-danger"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">DETAIL PEMELIHARAAN :</label>
                                            <table class="table table-bordered" id="pemeliharaan-table-realisasi">
                                                <thead>
                                                    <tr>
                                                        <th width="30%">Jenis Pemeliharaan</th>
                                                        <th width="15%">Odometer Realisasi</th>
                                                        <th width="25%">Penyedia Jasa</th>
                                                        <th width="30%">Keterangan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <select class="form-control jenis_pemeliharaan" name="jenis_pemeliharaan[]">
                                                                <option value="">Pilih</option>
                                                                @foreach ($komponent_pemerikasaan_kendaraan as $value)
                                                                    <option value="{{$value->id}}">
                                                                        {{$value->nama_item_pemeriksaan_detail}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control odometer" name="odometer[]"></td>
                                                        <td><input type="text" class="form-control penyedia_jasa" name="penyedia_jasa[]"></td>
                                                        <td><input type="text" class="form-control keterangan" name="keterangan[]"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">Realisasi (Bukti Foto):</label>
                                                <div class="mb-2">
                                                    <button type="button" class="btn btn-sm btn-primary" id="addImageBtn">
                                                        <i class="fa fa-plus"></i> Tambah Gambar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">Tanggal Realisasi:</label>
                                                <div class="input-group">
                                                    <input type="text" id="tanggal_realisasi" name="tanggal_realisasi" class="form-control fc-datepicker" placeholder="DD-MM-YYYY" value="{{ \Carbon\Carbon::now()->translatedFormat('d-m-Y') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row" id="preview-container"></div>
                                            <input type="file" id="imageInput" accept="image/*" style="display:none" multiple>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer bg-primary p-1">
                                <div class="btn-list">
                                    <button type="button" id="action-form-realisasi" data-mode="realisasi"  class="btn btn-secondary btn-app">Simpan</button>
                                    <button type="button" id="btn-close_edit1" class="btn btn-warning btn-app" data-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-approve" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success p-2">
                    <h5 class="modal-title text-white">Approve Pengajuan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-remove"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menyetujui pengajuan ini?</p>
                    <input type="hidden" id="approve-id">
                </div>
                <div class="modal-footer bg-light p-2">
                    <button type="button" id="confirm-approve" class="btn btn-success btn-app">Ya, Approve</button>
                    <button type="button" class="btn btn-secondary btn-app" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="modalDeleteLabel">Konfirmasi Hapus</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body text-center">
            <p>Apakah Anda yakin ingin menghapus data ini?</p>
            <input type="hidden" id="delete-id">
        </div>
        <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-danger" id="btn-confirm-delete">Hapus</button>
        </div>
        </div>
    </div>
    </div>



@endsection
@section('footerjs')
    <!-- DataTables & Plugins -->
    <script src="{{URL::asset('assets/js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/iziToast.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/html5-qrcode/html5-qrcode.min.js') }}"></script>
    <script src="{{URL::asset('assets/js/timepicker.js') }}"></script>
    <!-- Sweet alert js-->
    <script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>


    <!-- Datepicker js -->
    <script src="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/spectrum-date-picker/jquery-ui.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/input-mask/jquery.maskedinput.js')}}"></script>

    <!-- Timepicker js -->
    <script src="{{URL::asset('assets/plugins/time-picker/jquery.timepicker.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/time-picker/toggles.min.js')}}"></script>

    <!---Tabs js-->
    <script src="{{URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/tabs/tabs.js')}}"></script>

    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>

    <script>
         $('.fc-datepicker').datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'dd-mm-yy'
        });
         function open_modal_buat_pengajuan_perbaikan() {
            $("#ajax-modal-tambah").modal('show');
            $('#title-modal-list').text('PENGAJUAN PERBAIKAN KENDARAAN');
        }

    </script>
    <script>

         $(document).ready(function() {
            var start = moment().subtract(29, 'days');
            var end = moment();
            var htmlDateRange = '<span><i class="fa fa-calendar"></i> ' + start.format("D MMM YYYY").toUpperCase() + ' s/d ' + end.format("D MMM YYYY").toUpperCase() + '</span><i class="fa fa-angle-down ml-1"></i>'
            var daterange1 = start.format("YYYY-MM-DD") + " s/d " + end.format("YYYY-MM-DD");
            var dateUpdateKehadiran = end.format("DD-MM-YYYY");

            $('#daterange-btn1').html(htmlDateRange);
            $('#daterange1').val(daterange1);


            $("#data-absensi-karyawan").hide();
            $("#selectBagian").append(new Option("-- PILIH BAGIAN --", ""));
        });

        $('body').on('click', '#export-excel', function(event){
            var today=new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            var hour = today.getHours();
            var minutes = today.getMinutes();
            var seconds = today.getSeconds();
            today_date = yyyy + '-' + mm + '-' + dd;
             $("#export-excel").attr("disabled", true);
            $.ajax({
                type:"POST",
                url: "{{route('hris.ga.export_pengajuan_perbaikan_kendaraan')}}",
                data: {
                    tanggal_range: $('#daterange1').val(),
                },
                 xhrFields: {
                 responseType: 'blob' // PENTING untuk file biner
                },
                success: function(res){
                    $("#export-excel").attr("disabled", false);
                    var blob = new Blob([res]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "Rekap Pengajuan Perbaikan Kendaraan "+today_date+".xlsx";
                    link.click();
                    notif({
                        msg: "<b>Info:</b> Data berhasil di download.",
                        type: "info"
                    });
                },
                error: function(res){
                $("#export-excel").attr("disabled", false);
                notif({
                    msg: "<b>Info:</b> Data gagal di download.",
                    type: "error"
                });
                }
            });
        });
    </script>

    <script>

        $(document).ready(function() {
    let files = []; // simpan file di memory

    // Klik tombol tambah gambar
    $("#addImageBtn").on("click", function() {
        $("#imageInput").click();
    });

    // Saat pilih gambar
    $("#imageInput").on("change", function(e) {
        let newFiles = Array.from(e.target.files);

        newFiles.forEach(file => {
            let reader = new FileReader();
            reader.onload = function(ev) {
                let index = files.push(file) - 1; // simpan ke array
                $("#preview-container").append(`
                    <div class="col-md-3" id="image-card-${index}">
                        <div class="image-card">
                            <img src="${ev.target.result}" alt="preview">
                            <div class="image-actions">
                                <button type="button" class="btn btn-sm btn-danger remove-image" data-index="${index}">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-warning replace-image" data-index="${index}">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `);
            };
            reader.readAsDataURL(file);
        });

        // reset input biar bisa pilih file sama lagi
        $(this).val("");
    });

    // Hapus gambar
    $(document).on("click", ".remove-image", function() {
        let index = $(this).data("index");
        files[index] = null; // tandai null
        $(`#image-card-${index}`).remove();
    });

    // Ganti gambar
    $(document).on("click", ".replace-image", function() {
        let index = $(this).data("index");
        let input = $("<input type='file' accept='image/*' style='display:none'>");
        input.on("change", function(e) {
            let file = e.target.files[0];
            if (!file) return;

            let reader = new FileReader();
            reader.onload = function(ev) {
                files[index] = file; // update file
                $(`#image-card-${index} img`).attr("src", ev.target.result);
            };
            reader.readAsDataURL(file);
        });
        input.click();
    });

     function validateFormRealisasi() {
            let isValid = true;
            let data = {};

            data.id_detail_realisasi = [];
            data.jenis_pemeliharaan = [];
            data.odometer = [];
            data.penyedia_jasa = [];
            data.keterangan = [];

           $("#pemeliharaan-table-realisasi tbody tr").each(function () {
                let id_detail_realisasi = $(this).find(".id_detail_realisasi");
                let jenis = $(this).find(".jenis_pemeliharaan");
                let jasa  = $(this).find(".penyedia_jasa");
                let odometer  = $(this).find(".odometer");
                let ket   = $(this).find(".keterangan");

                // reset invalid dulu
                jenis.removeClass("is-invalid");
                odometer.removeClass("is-invalid");
                jasa.removeClass("is-invalid");

                // validasi per field
                if (!jenis.val()) {
                    isValid = false;
                    jenis.addClass("is-invalid");
                }
                if (!odometer.val()) {
                    isValid = false;
                    odometer.addClass("is-invalid");
                }
                if (!jasa.val()) {
                    isValid = false;
                    jasa.addClass("is-invalid");
                }

                data.id_detail_realisasi.push(id_detail_realisasi.val());
                data.jenis_pemeliharaan.push(jenis.val());
                data.penyedia_jasa.push(jasa.val());
                data.odometer.push(odometer.val());
                data.keterangan.push(ket.val());
            });

            return { isValid, data };
        }


    // Saat submit form
    $("#action-form-realisasi").on("click", function(e) {
        let id = $(this).data('id');
        e.preventDefault();

        let { isValid, data } = validateFormRealisasi();
        console.log(data);
        if (!isValid) return;

        let formEl = document.getElementById("form-realisasi");
        let formData = new FormData(formEl);
        let tanggal_realisasi = $("#tanggal_realisasi").val();
        if(tanggal_realisasi == null || tanggal_realisasi == ""){
            iziToast.error({
                message: 'Tanggal realisasi wajib diisi!',
                position: 'topCenter'
            });
            return;
        }
        formData.append("tanggal_realisasi", tanggal_realisasi);
        formData.append("details", JSON.stringify(data));

        // file baru
        files.forEach((file, i) => {
            if (file !== null) {
                formData.append("images[]", file);
            }
        });

        // id gambar yang dihapus
        deletedImages.forEach(imgId => {
            formData.append("deletedImages[]", imgId);
        });



        $.ajax({
            url: "{{ route('hris.ga.realisasi_pengajuan_perbaikan_kendaraan', ':id') }}".replace(':id', id),
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
            iziToast.success({
                message: 'Realisasi berhasil disimpan!',
                position: 'topCenter'
            });

            // tutup modal
            $('#ajax-modal-realisasi-perbaikan').modal('hide');

            // reset variabel
            deletedImages = [];
            files = [];

            // reset form
            formEl.reset();

            // kosongkan preview gambar lama & baru
            $("#image-preview-container").empty();
            $('#datatable-ajax-crud-waiting').DataTable().ajax.reload(null, false);
            $('#datatable-ajax-crud-verifikasi').DataTable().ajax.reload(null, false);
            },
            error: function(xhr) {
                iziToast.error({
                    message: 'Gagal menyimpan realisasi!',
                    position: 'topCenter'
                });
                console.error(xhr.responseJSON);
            }
        });
    });

});



        // ketika tombol delete diklik
        $(document).on("click", ".btn-delete", function () {
            let id = $(this).data("id");
            $("#delete-id").val(id);
            $("#modal-delete").modal("show");
        });

        // ketika tombol hapus di modal ditekan
        $("#btn-confirm-delete").on("click", function () {
            let id = $("#delete-id").val();

            $.ajax({
                url: "{{ route('hris.ga.delete_pengajuan_perbaikan_kendaraan', ':id') }}".replace(':id', id),
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {
                    $("#modal-delete").modal("hide");
                    $('#datatable-ajax-crud-waiting').DataTable().ajax.reload(null, false);

                    iziToast.success({
                        message: "Data berhasil dihapus!",
                        position: "topCenter"
                    });
                },
                error: function () {
                    iziToast.error({
                        message: "Gagal menghapus data!",
                        position: "topCenter"
                    });
                }
            });
        });




        $("#diajukanOlehID").select2().on("select2:select", function() {
            var selectedOption = $('#diajukanOlehID').find(':selected');
            var department = selectedOption.data('department_name');
            var department_id = selectedOption.data('department_data_id');
            var subDept = selectedOption.data('sub_dept_name');
            var subDeptID = selectedOption.data('sub_dept_data_id');
            if(department != null){
                document.getElementById('enroll_id').value = selectedOption.val();
                document.getElementById('department').value = department;
                document.getElementById('bagian').value = subDept;
                document.getElementById('department_id').value = department_id;
                document.getElementById('sub_dept_id').value = subDeptID;
            }
        });


        $(document).on('click', '#add-row', function () {
            let newRow = `
                <tr>
                    <td>
                        <select class="form-control jenis_pemeliharaan" name="jenis_pemeliharaan[]">
                            <option value="">Pilih</option>
                            @foreach ($komponent_pemerikasaan_kendaraan as $value)
                                <option value="{{$value->id}}">
                                    {{$value->nama_item_pemeriksaan_detail}}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" class="form-control odometer" name="odometer[]"></td>
                    <td><input type="text" class="form-control penyedia_jasa" name="penyedia_jasa[]"></td>
                    <td><input type="text" class="form-control keterangan" name="keterangan[]"></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                    </td>
                </tr>`;
            $('#pemeliharaan-table tbody').append(newRow);
        });

        $(document).on('click', '.remove-row', function () {
            $(this).closest('tr').remove();
        });



        function setToNull() {
            $("#tanggal_pengajuan_perbaikan").val("");
            $("#diajukanOlehID").val("").trigger("change");  // kalau pakai select2
            $("#vehicle_id").val("").trigger("change");      // kalau pakai select2
            $("#jenis_pemeliharaan").val("").trigger("change"); // kalau pakai select2
            $("#odometer").val("");
            $("#penyedia_jasa").val("");
            $("#keterangan").val("");

            // Hapus pesan error kalau ada
            $(".error-message").text("");
        }

        function validateForm() {
            let isValid = true;
            let data = {};

            data.tanggal_pengajuan_perbaikan = $("#tanggal_pengajuan_perbaikan").val();
            data.diajukanOlehID = $("#diajukanOlehID").val();
            data.vehicle_id = $("#vehicle_id").val();

            if(data.diajukanOlehID == null || data.diajukanOlehID == ""){
                isValid = false;
                $("#diajukanOlehID").addClass("is-invalid");
                 iziToast.error({
                    message: 'Pengajuan wajib diisi.',
                    position: 'topCenter'
                });
                return;
            }
            if(data.vehicle_id == null || data.vehicle_id == ""){
                isValid = false;
                $("#vehicle_id").addClass("is-invalid");
                 iziToast.error({
                    message: 'Kendaraan wajib diisi.',
                    position: 'topCenter'
                });
                return;
            }

            // Ambil data array dari tabel pemeliharaan
            data.jenis_pemeliharaan = [];
            data.odometer = [];
            data.penyedia_jasa = [];
            data.keterangan = [];

           $("#pemeliharaan-table tbody tr").each(function () {
                let jenis = $(this).find(".jenis_pemeliharaan");
                let jasa  = $(this).find(".penyedia_jasa");
                let odometer  = $(this).find(".odometer");
                let ket   = $(this).find(".keterangan");

                // reset invalid dulu
                jenis.removeClass("is-invalid");
                odometer.removeClass("is-invalid");
                jasa.removeClass("is-invalid");

                // validasi per field
                if (!jenis.val()) {
                    isValid = false;
                    jenis.addClass("is-invalid");
                }
                if (!odometer.val()) {
                    isValid = false;
                    odometer.addClass("is-invalid");
                }
                if (!jasa.val()) {
                    isValid = false;
                    jasa.addClass("is-invalid");
                }

                data.jenis_pemeliharaan.push(jenis.val());
                data.penyedia_jasa.push(jasa.val());
                data.odometer.push(odometer.val());
                data.keterangan.push(ket.val());
            });

            return { isValid, data };
        }

        $(document).on('click', '.btn-approve', function () {
            let id = $(this).data('id');
            $('#approve-id').val(id);
            $('#modal-approve').modal('show');
        });

        $(document).on('click', '#confirm-approve', function () {
            let id = $('#approve-id').val();

            $.ajax({
                url: "{{ route('hris.ga.approve_pengajuan_perbaikan_kendaraan', ':id') }}".replace(':id', id),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status_pengajuan: 'approved'
                },
                success: function () {
                    $('#modal-approve').modal('hide');
                    $('#datatable-ajax-crud-waiting').DataTable().ajax.reload(null, false);
                    $('#datatable-ajax-crud-verifikasi').DataTable().ajax.reload(null, false);
                    iziToast.success({
                                message: 'Pengajuan berhasil di-approve!',
                                position: 'topCenter'
                            });
                },
                error: function () {
                    iziToast.error({
                        message: 'Terjadi kesalahan saat approve!',
                        position: 'topCenter'
                    });
                }
            });
        });

        let deletedImages = [];

        $(document).on('click', '.btn-delete-image', function() {
            let id = $(this).data('id');
            deletedImages.push(id); // simpan id yang dihapus
            $(this).closest('.image-item').remove(); // hapus dari tampilan
        });


        // klik tombol realisasi
        $(document).on('click', '.btn-realisasi', function () {
            let id = $(this).data('id');

            $.ajax({
                url: "{{ route('hris.ga.edit_pengajuan_perbaikan_kendaraan', ':id') }}".replace(':id', id),
                type: 'GET',
                success: function (res) {
                    // ubah judul modal
                    $('#title-modal-realisasi').text('REALISASI PERBAIKAN');
                    console.log(res);
                    // isi field utama
                    $('#tanggal_pengajuan_label_realisasi').text(res.tanggal_pengajuan_format);
                    $('#tanggal_pengajuan_perbaikan_realisasi').val(res.tanggal_pengajuan);
                    $('#tanggal_realisasi').val(res.tanggal_realisasi ? moment(res.tanggal_realisasi).format('DD-MM-YYYY') : '{{ \Carbon\Carbon::now()->translatedFormat('d-m-Y') }}');
                    $('#diajukanOlehIDRealisasi').val(res.enroll_id).trigger('change').prop('disabled', true);
                    $('#vehicle_id_realisasi').val(res.kendaraan_id).trigger('change').prop('disabled', true);

                    // kosongkan table body
                    $('#pemeliharaan-table-realisasi tbody').empty();
                    // render detail
                    if (res.details && res.details.length > 0) {
                        res.details.forEach(d => {
                            $('#pemeliharaan-table-realisasi tbody').append(`
                                <tr>
                                    <td>
                                        <input type="hidden" class="form-control id_detail_realisasi" name="id_detail_realisasi[]" value="${d.id ?? ''}">
                                        <select disabled class="form-control jenis_pemeliharaan" name="jenis_pemeliharaan[]">
                                            <option value="">Pilih</option>
                                            @foreach ($komponent_pemerikasaan_kendaraan as $value)
                                                <option value="{{ $value->id }}"
                                                    ${d.komponen_pemeriksaan_kendaraan_input_id == {{ $value->id }} ? 'selected' : ''}>
                                                    {{ $value->nama_item_pemeriksaan_detail }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control odometer" name="odometer[]" value="${d.odometer_realisasi ?? ''}"></td>
                                    <td><input type="text" class="form-control penyedia_jasa" name="penyedia_jasa[]" value="${d.penyedia_jasa ?? ''}"></td>
                                    <td><input type="text" class="form-control keterangan" name="keterangan[]" value="${d.keterangan ?? ''}"></td>
                                </tr>
                            `);
                        });
                    }

                    // kosongkan preview container
                    $('#preview-container').empty();

                    // render images dari DB
                    if (res.images && res.images.length > 0) {
                        res.images.forEach((    img, index) => {
                            let url = "{{ asset('storage') }}/" + img.path;
                            $('#preview-container').append(`
                                <div class="col-md-3 mb-2 image-item" id="image-card-${index}" data-id="${img.id}">
                                    <div class="image-card">
                                            <img src="${url}" class="img-fluid mb-2 rounded shadow">
                                        <div class="image-actions card-body text-center p-1">
                                            <button type="button" class="btn btn-sm btn-danger btn-delete-image" data-id="${img.id}">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `);
                        });
                    }
                    // ubah tombol simpan → update
                    $('#action-form-realisasi')
                        .data('mode', 'realisasi')
                        .data('id', id)
                        .text('Simpan & Realisasi');

                    // tampilkan modal
                    $('#ajax-modal-realisasi-perbaikan').modal('show');
                }
            });
        });

        // klik tombol edit
        $(document).on('click', '.btn-edit', function () {
            let id = $(this).data('id');

            $.ajax({
                url: "{{ route('hris.ga.edit_pengajuan_perbaikan_kendaraan', ':id') }}".replace(':id', id),
                type: 'GET',
                success: function (res) {
                    // ubah judul modal
                    $('#title-modal-edit1').text('EDIT PENGAJUAN PERBAIKAN');

                    // isi field utama
                     $('#tanggal_pengajuan_label').text(res.tanggal_pengajuan_format);
                    $('#tanggal_pengajuan_perbaikan').val(res.tanggal_pengajuan);
                    $('#diajukanOlehID').val(res.enroll_id).trigger('change');
                    $('#vehicle_id').val(res.kendaraan_id).trigger('change');

                    // kosongkan table body
                    $('#pemeliharaan-table tbody').empty();
                    console.log(res.details);
                    // render detail
                    if (res.details && res.details.length > 0) {
                        res.details.forEach(d => {
                            $('#pemeliharaan-table tbody').append(`
                                <tr>
                                    <td>
                                        <select class="form-control jenis_pemeliharaan" name="jenis_pemeliharaan[]">
                                            <option value="">Pilih</option>
                                            @foreach ($komponent_pemerikasaan_kendaraan as $value)
                                                <option value="{{ $value->id }}"
                                                    ${d.komponen_pemeriksaan_kendaraan_input_id == {{ $value->id }} ? 'selected' : ''}>
                                                    {{ $value->nama_item_pemeriksaan_detail }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control odometer" name="odometer[]" value="${d.odometer ?? ''}"></td>
                                    <td><input type="text" class="form-control penyedia_jasa" name="penyedia_jasa[]" value="${d.penyedia_jasa ?? ''}"></td>
                                    <td><input type="text" class="form-control keterangan" name="keterangan[]" value="${d.keterangan ?? ''}"></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        // jika tidak ada detail, buat 1 baris kosong
                        $('#pemeliharaan-table tbody').append(`
                            <tr>
                                <td>
                                    <select class="form-control jenis_pemeliharaan" name="jenis_pemeliharaan[]">
                                        <option value="">Pilih</option>
                                        @foreach ($komponent_pemerikasaan_kendaraan as $value)
                                            <option value="{{ $value->id }}">{{ $value->nama_item_pemeriksaan_detail }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" class="form-control odometer" name="odometer[]"></td>
                                <td><input type="text" class="form-control penyedia_jasa" name="penyedia_jasa[]"></td>
                                <td><input type="text" class="form-control keterangan" name="keterangan[]"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                                </td>
                            </tr>
                        `);
                    }

                    // ubah tombol simpan → update
                    $('#action-form')
                        .data('mode', 'edit')
                        .data('id', id)
                        .text('Update');

                    // tampilkan modal
                    $('#ajax-modal-tambah').modal('show');
                }
            });
        });

        $('#btn-close, #btn-close_edit1').on('click', function () {
        // reset table detail
            $('#pemeliharaan-table tbody').html(`
                <tr>
                    <td>
                        <select class="form-control jenis_pemeliharaan" name="jenis_pemeliharaan[]">
                            <option value="">Pilih</option>
                            @foreach ($komponent_pemerikasaan_kendaraan as $value)
                                <option value="{{ $value->id }}">{{ $value->nama_item_pemeriksaan_detail }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" class="form-control odometer" name="odometer[]"></td>
                    <td><input type="text" class="form-control penyedia_jasa" name="penyedia_jasa[]"></td>
                    <td><input type="text" class="form-control keterangan" name="keterangan[]"></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                    </td>
                </tr>
            `);

            // reset dropdown pemeriksa
            $('#diajukanOlehID').val('').trigger('change');

            // reset dropdown kendaraan
            $('#vehicle_id').val('').trigger('change');

            // reset judul & tombol action
            $('#title-modal-edit1').text('BUAT PENGAJUAN PERBAIKAN');
            $('#action-form').data('mode', 'create').removeData('id').text('Simpan');
        });




        var perijinanChecked = [];
        var currentPageCheck = 0;

        $(document).ready(function() {

           $("#action-form").on("click", function (e) {
            e.preventDefault();

            let { isValid, data } = validateForm();
            let id   = $(this).data("id");
            if (!isValid) return;
            let mode = $(this).data("mode");

            if (mode === "create") {
                $.ajax({
                    url: "{{ route('hris.ga.pengajuan_perbaikan_kendaraan') }}",
                    type: "POST",
                    data: data, // <-- sudah bentuk array
                    success: function () {
                        iziToast.success({
                            message: 'Pengajuan berhasil disimpan.',
                            position: 'topCenter'
                        });
                        $("#ajax-modal-tambah").modal("hide");
                        $("#datatable-ajax-crud-waiting").DataTable().ajax.reload(null, false);
                        $('#datatable-ajax-crud-verifikasi').DataTable().ajax.reload(null, false);
                        setToNull();
                    }
                });
            } else if (mode === "edit") {
                $.ajax({
                    url: "{{ route('hris.ga.update_pengajuan_perbaikan_kendaraan', ':id') }}".replace(':id', id),
                    type: "POST",
                    data: data, // <-- tetap array
                    success: function () {
                        iziToast.success({
                            message: 'Data berhasil diupdate.',
                            position: 'topCenter'
                        });
                        $("#ajax-modal-tambah").modal("hide");
                        $("#datatable-ajax-crud-waiting").DataTable().ajax.reload(null, false);
                        $('#datatable-ajax-crud-verifikasi').DataTable().ajax.reload(null, false);
                        setToNull();
                    }
                });
            }
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
            $('#daterange1').val(daterange1);
            tableWaiting.ajax.reload(); // reload data table with new date range
            tableVerifikasi.ajax.reload();
        });

        $('#clear-daterange').on('click', function () {
            $('#daterange-btn1').val(''); // kosongkan input
            $('#daterange1').val('');     // kosongkan hidden input
            tableWaiting.ajax.reload();
            tableVerifikasi.ajax.reload();
        });

        var tableWaiting = $('#datatable-ajax-crud-waiting').DataTable({
            ajax: {
                url: '{{ route('hris.ga.ajax_get_pengajuan_perbaikan_kendaraan_list') }}',
                type: "POST",
                data: function (d) {
                    d.status_pengajuan = 'pending';
                    d.tanggal_range = $('#daterange1').val(); // kirim range terpilih
                }
            },
            processing: true,
            serverSide: true,
            columns: [
                { data: 'tanggal_pengajuan', name: 'tanggal_pengajuan', className: 'text-center' },
                { data: 'merk_kendaraan', name: 'merk_kendaraan' },
                { data: 'nomor_polisi', name: 'nomor_polisi', className: 'text-center' },
                { data: 'driver', name: 'driver' },
                { data: 'nip', name: 'nip', className: 'text-center' },
                {
                    data: 'id',
                    name: 'aksi',
                    className: 'text-center',
                    render: function (data, type, row) {
                        return `
                            <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="${row.id}">Edit</button>
                            <button type="button" class="btn btn-sm btn-success btn-approve" data-id="${row.id}">Approve</button>
                            <button type="button" class="btn btn-sm btn-warning btn-print-pdf" data-id="${row.id}"><i class="fa fa-file-pdf-o"></i></button>
                            <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="${row.id}"><i class="fa fa-trash"></i></button>
                        `;
                    }
                }
            ]
        });

        // Table untuk Verifikasi (is_verifikasi == 1)
        var tableVerifikasi = $('#datatable-ajax-crud-verifikasi').DataTable({
            ajax: {
                url: '{{ route('hris.ga.ajax_get_pengajuan_perbaikan_kendaraan_list') }}',
                type: "POST",
                data: function (d) {
                    d.status_pengajuan = 'approved';
                    d.tanggal_range = $('#daterange1').val(); // kirim range terpilih
                }
            },
            processing: true,
            serverSide: true,
            columns: [
                { data: 'tanggal_pengajuan', name: 'tanggal_pengajuan', className: 'text-center' },
                { data: 'merk_kendaraan', name: 'merk_kendaraan' },
                { data: 'nomor_polisi', name: 'nomor_polisi', className: 'text-center' },
                { data: 'driver', name: 'driver' },
                { data: 'nip', name: 'nip', className: 'text-center' },
                {
                    data: 'id',
                    name: 'aksi',
                    className: 'text-center',
                    render: function (data, type, row) {
                        return `
                            <button type="button" class="btn btn-sm btn-warning btn-realisasi" data-id="${row.id}">Realisasi</button>
                            <button type="button" class="btn btn-sm btn-danger btn-print-pdf" data-id="${row.id}"><i class="fa fa-file-pdf-o"></i></button>
                        `;
                    }
                },
                { data: 'tanggal_realisasi', name: 'tanggal_realisasi', className: 'text-center',
                     render: function (data, type, row) {
                            if (!data) return '-';
                            let date = new Date(data); // input: "2025-09-12"
                            return new Intl.DateTimeFormat('id-ID', {
                                day: '2-digit',
                                month: 'long',
                                year: 'numeric'
                            }).format(date);
                        }
                 },
                {
                    data: 'total_images',
                    name: 'realisasi',
                    className: 'text-center',
                    render: function (data, type, row) {
                         return `${data} foto uploaded`;
                    }
                }
            ]
        });

        // Table untuk Verifikasi (is_reject == 1)
        var tableDitolak = $('#datatable-ajax-crud-ditolak').DataTable({
            ajax: {
                url: '{{ route('hris.ga.ajax_get_pengajuan_perbaikan_kendaraan_list') }}',
                type: "POST",
                data: function (d) {
                    d.status_pengajuan = 'rejected';
                    d.tanggal_range = $('#daterange1').val(); // kirim range terpilih
                }
            },
            processing: true,
            serverSide: true,
            columns: [
                { data: 'tanggal_pengajuan', name: 'tanggal_pengajuan', className: 'text-center' },
                { data: 'merk_kendaraan', name: 'merk_kendaraan' },
                { data: 'nomor_polisi', name: 'nomor_polisi', className: 'text-center' },
                { data: 'driver', name: 'driver' },
                { data: 'nip', name: 'nip', className: 'text-center' },
                {
                    data: 'id',
                    name: 'aksi',
                    className: 'text-center',
                    render: function (data, type, row) {
                        return `
                            <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="${row.id}">Edit</button>
                        `;
                    }
                }
            ]
        });


        // Pastikan tab yang aktif saat ini memiliki DataTable yang diinisialisasi
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            var target = $(e.target).attr("href"); // Get the target tab
            if (target === "#tab-content-verifikasi") {
                tableVerifikasi.ajax.reload();
            } else if (target === "#tab-content-waiting") {
                tableWaiting.ajax.reload();
            } else if (target === "#tab-content-reject") {
                tableReject.ajax.reload();
            }
        });

        $(document).on('click', '.btn-lihat', function () {
            let uuid = $(this).data('id');
            // Lakukan sesuatu, misal tampilkan modal detail
        });

        $(document).on('click', '.btn-print-pdf', function () {
            // Redirect atau tampilkan form edit
                let id = $(this).data('id');
                let url = '{{ route('hris.ga.print_pengajuan_perbaikan_kendaraan', ':id') }}';
                url = url.replace(':id', id);
                window.open(url, '_blank');
            //   window.location.href = url;
        });
        });
    </script>
    <script>
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
            dropdownCssClass: 'hover-success',
            minimumResultsForSearch: Infinity // disabling search
            });

            $('#select3').select2({
            dropdownCssClass: 'hover-danger',
            minimumResultsForSearch: Infinity // disabling search
            });

            // Outline Select
            $('#select4').select2({
            containerCssClass: 'select2-outline-success',
            dropdownCssClass: 'bd-success hover-success',
            minimumResultsForSearch: Infinity // disabling search
            });

            $('#select5').select2({
            containerCssClass: 'select2-outline-info',
            dropdownCssClass: 'bd-info hover-info',
            minimumResultsForSearch: Infinity // disabling search
            });

            // Full Colored Select Box
            $('#select6').select2({
            containerCssClass: 'select2-full-color select2-primary',
            minimumResultsForSearch: Infinity // disabling search
            });

            $('#select7').select2({
            containerCssClass: 'select2-full-color select2-danger',
            dropdownCssClass: 'hover-danger',
            minimumResultsForSearch: Infinity // disabling search
            });

            // Full Colored Dropdown
            $('#select8').select2({
            dropdownCssClass: 'select2-drop-color select2-drop-primary',
            minimumResultsForSearch: Infinity // disabling search
            });

            $('#select9').select2({
            dropdownCssClass: 'select2-drop-color select2-drop-indigo',
            minimumResultsForSearch: Infinity // disabling search
            });

            // Full colored for both box and dropdown
            $('#select10').select2({
            containerCssClass: 'select2-full-color select2-primary',
            dropdownCssClass: 'select2-drop-color select2-drop-primary',
            minimumResultsForSearch: Infinity // disabling search
            });

            $('#select11').select2({
            containerCssClass: 'select2-full-color select2-indigo',
            dropdownCssClass: 'select2-drop-color select2-drop-indigo',
            minimumResultsForSearch: Infinity // disabling search
            });
        });
    </script>

@endsection
