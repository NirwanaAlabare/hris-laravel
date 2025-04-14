@extends('admin.adminlayouts.adminlayout')

@section('head')
    <link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

	<!---Sweetalert Css-->
	<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
@stop
<style>
 .timestamp {
            display: inline-block;
            background: #4CAF50;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }
.is-invalid {
    border: 2px solid red;
    background-color: #ffe6e6;
}

/* Misalnya, modal edit memiliki z-index lebih tinggi daripada modal list */
#ajax-modal-edit1 {
    z-index: 1060 !important;
}
#ajax-modal-edit1 .modal-dialog {
    z-index: 1070 !important;
}

#entertaintTable thead th {
    background-color: var(--primary);
    color: white;
}

.wrapper {
  margin: auto;
  text-align: center;
}

h1 {
  color: #130f40;
  font-family: 'Varela Round', sans-serif;
  letter-spacing: -.5px;
  font-weight: 700;
  padding-bottom: 10px;
}

.upload-container {
  background-color: rgb(239, 239, 239);
  border-radius: 6px;
  padding: 10px;
}

.border-container {
  border: 2px dashed rgba(198, 198, 198, 0.65);
  padding: 20px;
}

.border-container p {
  color: #130f40;
  font-weight: 600;
  font-size: 1.1em;
  letter-spacing: -1px;
  margin-top: 10px;
  margin-bottom: 0;
  opacity: 0.65;
}

#file-browser {
  text-decoration: none;
  color: rgb(22,42,255);
  border-bottom: 3px dotted rgba(22, 22, 255, 0.85);
}

#file-browser:hover {
  color: rgb(0, 0, 255);
  border-bottom: 3px dotted rgba(0, 0, 255, 0.85);
}

.icons {
  color: #95afc0;
  opacity: 0.55;
}

.drag-over {
    border: 2px dashed #007bff;
    background-color: #f8f9fa;
}

#drop-zone {
    border: 2px dashed #007bff;
    padding: 20px;
    text-align: center;
    margin-bottom: 10px;
    cursor: pointer;
}
.drag-over {
    background-color: #f0f8ff;
}
.file-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    border: 1px solid #ccc;
    margin: 5px 0;
    border-radius: 5px;
}
.file-item img {
    width: 50px;
    height: 50px;
    margin-right: 10px;
}
.file-name {
    flex-grow: 1;
}
.remove-btn {
    background: red;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 5px;
}

#total-nominal {
    font-weight: bold;
}


</style>
@section('mainarea')
<?php ini_set('date.timezone', 'Asia/Jakarta'); ?>
    <div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
        <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
            <li><a href="{{route('entertaint_tamu.index')}}">Master Data</a></li>
            <li class="active"><span>Cuti Karyawan</span></li>
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
                        <div class="card-title">Cuti Karyawan</div>
                    </div>
                        <div class="mt-4 ml-4 mr-5 mb-0">
                            {{-- <div class=""  aria-labelledby="">
                                <div clasl="card-header m-0 p-0" style="display: flex; justify-content: space-between; align-items: center;">
                                    <div class="m-0 p-0">
                                        <button class="btn btn-primary w-100" onclick="open_modal_buat_dokumen()"  data-toggle="tooltip" title="Tambah dokumen" id="recap_labor_cost_2"><i class="fa fa-plus" aria-hidden="true"></i> Buat Pengajuan</button>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">CARI DATA : </label>
                                        <select id="selectEmployeeID" name="selectEmployeeID[]" multiple data-placeholder="Pilih karyawan" class="form-control select2 EmployeeID">
                                            @foreach ($selectemployee as $r_empl)
                                            <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div clasl="" style="display: flex; justify-content: flex-start; align-items: center; gap: 10px;">
                                        <div class="mt-5 p-0">
                                            <button class="btn btn-primary w-100" onclick="searchData()"  data-toggle="tooltip" title="Cari Data" id="recap_labor_cost_2"><i class="fa fa-search" aria-hidden="true"></i> Cari</button>
                                        </div>
                                        <div class="mt-5 p-0">
                                            <button id="btn-view_excel" class="btn btn-success w-100 text-white" data-toggle="tooltip" title="Preview by excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel</button>
                                        </div>
                                    </div>
                                    <div clasl="" style="display: flex; justify-content: space-between; align-items: center;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m-0 p-0">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="entertaintTable" class="table table-bordered table-sm w-100 table-hover">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>No Absen</th>
                                                <th>Nama</th>
                                                <th>Department</th>
                                                <th>Bagian</th>
                                                <th>Tanggal Masuk</th>
                                                <th>Awal Periode</th>
                                                <th>Akhir Periode</th>
                                                <th>Masa Kerja</th>
                                                <th>Hak Cuti</th>
                                                <th>Cuti Terpakai</th>
                                                <th>Sisa Cuti</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
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
                            <h4 class="modal-title pl-2 font-weight-bold" id="title-modal-edit1">Buat Pengajuan Cuti</h4>
                            <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">TANGGAL PENGAJUAN : </label>
                                        <div class="input-group">
                                            <p class="form-label">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">DIAJUKAN OLEH : </label>
                                        <select id="diajukanOlehID" name="diajukanOlehID" style='width: 100%;' data-placeholder="Pilih karyawan" class="form-control create-control select2 select2-show-search EmployeeID">
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">DEPARTMENT : </label>
                                        <input type="text" readonly value="" class="form-control create-control" id="department" name="department">
                                        <input type="hidden" readonly value="" class="form-control create-control" id="department_id" name="department_id">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">BAGIAN : </label>
                                        <input type="text" readonly value="" class="form-control create-control" id="bagian" name="bagian">
                                        <input type="hidden" readonly value="" class="form-control create-control" id="sub_dept_id" name="sub_dept_id">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <p class="form-label">DENGAN INI BERMAKSUD UNTUK MENGAJUKAN CUTI : </p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">JENIS PERIZINAN</label>
                                        <select id="kode_absen_ijin" class="form-control" data-placeholder="-- PILIH JENIS PERIZINAN --">
                                            <option value="">-- PILIH JENIS PERIZINAN --</option>
                                            @foreach ($refabsenijin as $r_refabsenijin)
                                                @php
                                                    if (($r_refabsenijin->kode_absen_ijin <> 'IKS') && ($r_refabsenijin->kode_absen_ijin <> 'M')) {
                                                @endphp
                                                    <option value="{{$r_refabsenijin->kode_absen_ijin}}">{{$r_refabsenijin->kode_nama_absen_ijin}}</option>
                                                @php
                                                    }
                                                @endphp
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">KETERANGAN :</label>
                                        <input type="text" class="form-control keterangan" name="keterangan[]">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">TANGGAL MULAI IZIN : </label>
                                        <div class="input-group">
                                            <input type="date" readonly class="form-control create-control" id="tanggal_kedatangan_tamu" name="tanggal_kedatangan_tamu"
                                            value="{{ date('Y-m-d') }}" onchange="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group text-center align-self-center">
                                        <p class="form-label pt-4 mt-4">SAMPAI </p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">TANGGAL AKHIR IZIN : </label>
                                        <div class="input-group">
                                            <input type="date" readonly class="form-control create-control" id="tanggal_kedatangan_tamu" name="tanggal_kedatangan_tamu"
                                            value="{{ date('Y-m-d') }}" onchange="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <p class="form-label">MAKA SELAMA PELAKSANAAN CUTI TUGAS TUGAS SAYA, SAYA DELEGASIKAN KEPADA : </p>
                                        <select id="pendampingTamuID" name="pendampingTamuID" data-placeholder="Pilih karyawan" class="form-control select2 select2-show-search EmployeeID" style="width: 100%;" required>
                                            @foreach ($selectemployee as $r_empl)
                                                <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer bg-primary p-1">
                            <div class="btn-list">
                                <button type="button" id="submit-form" class="btn btn-secondary btn-app">Simpan</button>
                                <button type="button" id="btn-close_edit1" class="btn btn-warning btn-app" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
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

    <style>
    .checkbox-xl .form-check-input {
        scale: 1.5;
    }
    </style>
    <script>
        function updateFields() {
            let inputTgl = document.getElementById('tanggal_kedatangan_tamu');
            let tanggal = new Date(inputTgl.value);

            if (isNaN(tanggal)) return;

            let tahun = tanggal.getFullYear();
            let bulan = ('0' + (tanggal.getMonth() + 1)).slice(-2);
            let hari = tanggal.toLocaleDateString('id-ID', { weekday: 'long' }).toUpperCase();
            let kode = `${tahun}-${bulan}`;

            document.getElementById('tahun').value = tahun;
            document.getElementById('bulan').value = bulan;
            document.getElementById('kode').value = kode;
            document.getElementById('hari').value = hari;
        }

        document.addEventListener("DOMContentLoaded", function () {
            let today = new Date();
            let formattedDate = today.toISOString().split('T')[0];

            let inputTgl = document.getElementById('tanggal_kedatangan_tamu');
            inputTgl.value = formattedDate;

            updateFields();
        });

        document.getElementById('tanggal_kedatangan_tamu').addEventListener('change', updateFields);



        $("#diajukanOlehID").select2().on("select2:select", function() {
            var selectedOption = $('#diajukanOlehID').find(':selected');
            var department = selectedOption.data('department_name');
            var department_id = selectedOption.data('department_data_id');
            var subDept = selectedOption.data('sub_dept_name');
            var subDeptID = selectedOption.data('sub_dept_data_id');
            if(department != null){
                document.getElementById('department').value = department;
                document.getElementById('bagian').value = subDept;
                document.getElementById('department_id').value = department_id;
                document.getElementById('sub_dept_id').value = subDeptID;
            }
        });

    </script>


    <script>
        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function parseRupiah(value) {
            return parseInt(value.replace(/[^\d]/g, ""), 10) || 0;
        }
        $(document).on("input", ".jml_permintaan_uang", function () {
            let value = $(this).val().replace(/[^0-9]/g, ""); // Hanya angka
            if (value) {
                $(this).val("Rp " + formatRupiah(value));
            } else {
                $(this).val("");
            }
        });

        $(document).on("blur", ".jml_permintaan_uang", function () {
            let value = $(this).val().replace(/[^0-9]/g, "");
            $(this).val(value ? "Rp " + formatRupiah(value) : "Rp 0");
        });

        $(document).on("focus", ".jml_permintaan_uang", function () {
            let value = $(this).val().replace(/[^0-9]/g, "");
            $(this).val(value ? value : "");
        });
        document.addEventListener("DOMContentLoaded", function () {
            let formContainer = document.getElementById("form-container");
            let addButton = document.getElementById("add-form");
            let totalLabel = document.getElementById("total-label");


            function updateTotal() {
                let total = 0;
                $(".jml_permintaan_uang").each(function () {
                    let value = parseRupiah($(this).val()); // Ambil hanya angka
                    total += value ? parseInt(value) : 0;
                });

                $("#total-nominal").text("Rp " + formatRupiah(total)); // Update total
            }

            addButton.addEventListener("click", function (e) {
                e.preventDefault();
                let newForm = document.createElement("div");
                newForm.classList.add("row", "form-group-item");
                newForm.innerHTML = `
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="form-label">KETERANGAN :</label>
                            <input type="text" class="form-control keterangan" name="keterangan[]">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">JUMLAH :</label>
                            <div class="input-group">
                                <input type="text" class="form-control jml_permintaan_uang" name="jml_permintaan_uang[]" value="">
                                <button class="btn btn-danger ml-3 remove-form"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                `;
                formContainer.appendChild(newForm);
                updateTotal();
            });


            formContainer.addEventListener("click", function (e) {
                if (e.target.classList.contains("remove-form") || e.target.closest(".remove-form")) {
                    e.preventDefault();
                    let allForms = document.querySelectorAll(".form-group-item");
                    if (allForms.length > 1) {
                        e.target.closest(".form-group-item").remove();
                        updateTotal();
                    }
                }
            });

            formContainer.addEventListener("input", function (e) {
                if (e.target.classList.contains("jml_permintaan_uang")) {
                    updateTotal();
                }
            });

            updateTotal();
        });

        $("#submit-form").click(function (e) {
            e.preventDefault(); // Mencegah form submit default

            var uuid = $('#uuid').val();
            var uuid_master = $('#uuid_master').val();
            var tanggal_periz = $('#tanggal_perijinan').val();
            var tanggal_perizinan=tanggal_periz.substr(6, 4)+'-'+tanggal_periz.substr(3,2)+'-'+tanggal_periz.substr(0,2);
            var tanggal_mulai = $('#tanggal_mulai_ijin').val();
            var tanggal_mulai_ijin=tanggal_mulai.substr(6, 4)+'-'+tanggal_mulai.substr(3,2)+'-'+tanggal_mulai.substr(0,2);
            var tanggal_akhir = $('#tanggal_akhir_ijin').val();
            var tanggal_akhir_ijin=tanggal_akhir.substr(6, 4)+'-'+tanggal_akhir.substr(3,2)+'-'+tanggal_akhir.substr(0,2);
            var nomor_form_perizinan = $('#nomor_form_perizinan').val();
            var enroll_id = $('#enroll_id').val();
            var nik = $('#nik').val();
            var employee_name = $('#employee_name').val();
            var kode_absen_ijin = $('#kode_absen_ijin').val();
            var absen_alasan = $('#absen_alasan_izin').val();

            $('#btn-save-izin').addClass("btn-loading");
            $("#btn-save-izin").html('Please wait...');
            $("#btn-save-izin").attr("disabled", true);
            $('#progress-show-1').show();
            $('#progress-hide-1').hide();
            if (!enroll_id) {
                notif({
                    msg: "<b>Warning:</b> Anda belum memilih data karyawan.",
                    type: "warning"
                });
                return false;
            }

            if (!tanggal_perizinan) {
                notif({
                    msg: "<b>Warning:</b> Anda belum memilih Tanggal Perizinan.",
                    type: "warning"
                });
                return false;
            }

            if (!kode_absen_ijin) {
                notif({
                    msg: "<b>Warning:</b> Anda belum memilih Nama Izin.",
                    type: "warning"
                });
                return false;
            }

            if (!tanggal_mulai_ijin) {
                notif({
                    msg: "<b>Warning:</b> Anda belum memilih Tanggal Mulai Izin.",
                    type: "warning"
                });
                return false;
            }

            if (!tanggal_akhir_ijin) {
                notif({
                    msg: "<b>Warning:</b> Anda belum memilih Tanggal Akhir Izin.",
                    type: "warning"
                });
                return false;
            }

            var tanggal = tanggal_perizinan;
            $.ajax({
                type:"POST",
                url: "{{route('hris.dataclosingpayroll.ajax_getclosing')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    tanggal:tanggal,
                },
                dataType: 'json',
                success: function(resA){

                    if(resA["ada"]) {
                        notif({
                            type: resA["status"],
                            msg: resA["message"],
                            position: "center",
                            width: 800,
                            height: 120,
                            opacity: 0.6,
                            autohide: false
                        });
                    } else {

                        var TglMulaiIzin = new Date(tanggal_mulai);
                        var TglAkhirIzin = new Date(tanggal_akhir);
                        var TglPerizinan = new Date(tanggal_periz);

                        // if (TglMulaiIzin.getDate() != TglPerizinan.getDate()) {
                        //     notif({
                        //         msg: "<b>Warning:</b> Tanggal Mulai Izin tidak sesuai.",
                        //         type: "warning"
                        //     });

                        //     $('#tanggal_mulai_ijin').val(tanggal_periz);
                        //     $('#tanggal_akhir_ijin').val(tanggal_periz);

                        //     return false;
                        // }

                        if (TglAkhirIzin.getDate() < TglMulaiIzin.getDate()) {
                            notif({
                                msg: "<b>Warning:</b> Tanggal Akhir Izin tidak sesuai.",
                                type: "warning"
                            });

                            $('#tanggal_mulai_ijin').val(tanggal_perizinan);
                            $('#tanggal_akhir_ijin').val(tanggal_perizinan);

                            return false;
                        }


                        $.ajax({
                            type:"POST",
                            url: "{{route('hris.dataabsenperijinan.cekperizinan')}}",
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            data: {
                                enroll_id:enroll_id,
                                tanggal_mulai_ijin:tanggal_mulai_ijin,
                                tanggal_akhir_ijin:tanggal_akhir_ijin,
                            },
                            dataType: 'json',
                            success: function(res){
                                if (res.length > 0) {
                                    var uuid_res=res[0].uuid;
                                    var nomor_form_res=res[0].nomor_form_perizinan;
                                    $.ajax({
                                        type:"POST",
                                        url: "{{route('hris.dataabsenperijinan.update_perizinan_menu')}}",
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                        data: {
                                            uuid:uuid_res,
                                            uuid_master:uuid_master,
                                            tanggal_perizinan:tanggal_perizinan,
                                            nomor_form_perizinan:nomor_form_res,
                                            enroll_id:enroll_id,
                                            nik:nik,
                                            employee_name:employee_name,
                                            kode_absen_ijin:kode_absen_ijin,
                                            absen_alasan:absen_alasan,
                                            tanggal_mulai_ijin:tanggal_mulai_ijin,
                                            tanggal_akhir_ijin:tanggal_akhir_ijin,
                                        },
                                        success: function(res){
                                            $('#progress-show-1').hide();
                                            $('#progress-hide-1').show();
                                            $('#btn-save-izin').removeClass("btn-loading");
                                            $("#btn-save-izin").html('<span><i class="fa fa-save"></i></span> Save');
                                            $("#form1 :input").prop("disabled", true);
                                            $("#btn-save-izin").prop("disabled", true);
                                            $("#btn-save-iks").prop("disabled", true);
                                            $("#btn-cancel-izin").prop("disabled", true);
                                            $("#btn-cancel-iks").prop("disabled", true);
                                            $("#datatable-ajax-crud").DataTable().ajax.reload();
                                            $('#data-perizinan-iks').show();
                                            $('#data-karyawan').hide("slow");
                                            swal("", "update perizinan berhasil", "success");
                                            $('#nomor_form_perizinan').val('');
                                            $('#enroll_id').val('');
                                            $('#nik').val('');
                                            $('#employee_name').val('');
                                            $('#tanggal_mulai_ijin').val('');
                                            $('#tanggal_akhir_ijin').val('');
                                            $('#kode_absen_ijin').val('');
                                            $('#absen_alasan_izin').val('');
                                        },
                                        error: function(res){
                                            $('#progress-show-1').hide();
                                            $('#progress-hide-1').show();
                                            $('#btn-save-izin').removeClass("btn-loading");
                                            $("#btn-save-izin").html('<span><i class="fa fa-save"></i></span> Save');
                                            $("#form1 :input").prop("disabled", false);
                                            $("#btn-save-izin").prop("disabled", false);
                                            $("#btn-save-iks").prop("disabled", false);
                                            $("#btn-cancel-izin").prop("disabled", false);
                                            $("#btn-cancel-iks").prop("disabled", false);
                                            swal("", "update perizinan gagal", "error");
                                        }
                                    });
                                } else {
                                    $.ajax({
                                        type:"POST",
                                        url: "{{route('hris.dataabsenperijinan.create_perizinan_menu')}}",
                                        dataType: 'json',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                        data: {
                                            uuid:uuid,
                                            tanggal_perizinan:tanggal_perizinan,
                                            nomor_form_perizinan:nomor_form_perizinan,
                                            enroll_id:enroll_id,
                                            nik:nik,
                                            employee_name:employee_name,
                                            kode_absen_ijin:kode_absen_ijin,
                                            absen_alasan:absen_alasan,
                                            tanggal_mulai_ijin:tanggal_mulai_ijin,
                                            tanggal_akhir_ijin:tanggal_akhir_ijin,
                                        },
                                        dataType: 'json',
                                        success: function(res){
                                            if(res == 0){
                                                notif({
                                                    msg: "<b>Warning:</b> Karyawan telah masuk pada tanggal tersebut.",
                                                    type: "warning"
                                                });
                                                $("#btn-save-izin").prop("disabled", false);
                                                $("#btn-save-iks").prop("disabled", false);
                                                $("#btn-cancel-izin").prop("disabled", false);
                                                $("#btn-cancel-iks").prop("disabled", false);
                                                $('#progress-show-1').hide();
                                                $('#progress-hide-1').show();
                                                $('#btn-save-izin').removeClass("btn-loading");
                                                $("#btn-save-izin").html('<span><i class="fa fa-save"></i></span> Save');
                                                $("#datatable-ajax-crud").DataTable().ajax.reload();
                                            } else {
                                                $('#progress-show-1').hide();
                                                $('#progress-hide-1').show();
                                                $('#btn-save-izin').removeClass("btn-loading");
                                                $("#btn-save-izin").html('<span><i class="fa fa-save"></i></span> Save');
                                                $("#form1 :input").prop("disabled", true);
                                                $("#btn-save-izin").prop("disabled", true);
                                                $("#btn-save-iks").prop("disabled", true);
                                                $("#btn-cancel-izin").prop("disabled", true);
                                                $("#btn-cancel-iks").prop("disabled", true);
                                                $("#datatable-ajax-crud").DataTable().ajax.reload();
                                                $('#data-perizinan-iks').show();
                                                $('#data-karyawan').hide("slow");
                                                swal("", "create perizinan berhasil", "success");
                                                $('#nomor_form_perizinan').val('');
                                                $('#enroll_id').val('');
                                                $('#nik').val('');
                                                $('#employee_name').val('');
                                                $('#tanggal_mulai_ijin').val('');
                                                $('#tanggal_akhir_ijin').val('');
                                                $('#kode_absen_ijin').val('');
                                                $('#absen_alasan_izin').val('');
                                            }
                                        },
                                        error: function(res){
                                            swal("", "create perizinan gagal", "error");
                                        }
                                    });
                                }
                            },
                            error: function(res){
                                notif({
                                    msg: "<b>Error:</b> Oops Cek Data Perizinan GAGAL.",
                                    type: "error"
                                });
                            }
                        });
                    }

                },
                error: function(resA){

                }
            });
        });

        $(document).on("click", "[id^=export_form_pengajuan_cuti_]", function (e) {
            var id = this.id.replace('export_form_pengajuan_cuti_', '');
            var url = 'cuti_karyawan/export_form_pengajuan_cuti_pdf?enroll_id=' + id + '&periode=' + id;
            window.open(url, '_blank');
        });
        $(document).on("click", "[id^=export_detail_cuti_karyawan_]", function (e) {
            var enroll_id = this.id.replace('export_detail_cuti_karyawan_', '');
            $.ajax({
                type: 'POST',
                url: '{{route('cuti_karyawan.show_export_detail_cuti_karyawan')}}',
                data: {
                    enroll_id:enroll_id,
                },
                xhrFields: { responseType : 'blob' },
                success:function(data){
                    var blob = new Blob([data]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "cuti_karyawan.xlsx";
                    link.click();
                    swal("", "Export rekap cuti karyawan", "success");
                },
                error: function(res){
                    swal("", "Export rekap cuti karyawan", "error");
                }
            });
        });


        function open_modal_buat_dokumen() {
            $("#ajax-modal-tambah").modal('show');
            $('#title-modal-list').text('PENGAJUAN KUPON KARYAWAN');
        }
        function searchData() {
            var selectEmployeeID = $('#selectEmployeeID').val();
            $('#entertaintTable').DataTable().ajax.reload();
        }

        $('#btn-view_excel').click(function(e){
            var selectEmployeeID = $('#selectEmployeeID').val();
            $('#btn-view_excel').addClass("btn-loading");
            $("#btn-view_excel").html('Please wait...');
            $("#btn-view_excel").attr("disabled", true);
            $.ajax({
                type: 'POST',
                url: '{{route('cuti_karyawan.show_export')}}',
                data: {
                    selectEmployeeID:selectEmployeeID,
                },
                xhrFields: { responseType : 'blob' },
                success:function(data){
                    $('#btn-view_excel').removeClass("btn-loading");
                    $("#btn-view_excel").attr("disabled", false);
                    $("#btn-view_excel").html('<i class="fa fa-file-excel-o" aria-hidden="true"></i> View Excel');
                    var blob = new Blob([data]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "cuti_karyawan.xlsx";
                    link.click();
                    swal("", "Export rekap cuti karyawan", "success");
                },
                error: function(res){
                    $('#btn-view_excel').removeClass("btn-loading");
                    $("#btn-view_excel").attr("disabled", false);
                    $("#btn-view_excel").html('<i class="fa fa-file-excel-o" aria-hidden="true"></i> View Excel');
                    swal("", "Export rekap cuti karyawan", "error");
                }
            });
        })


        function open_modal_realisasi_pengajuan($row) {
            $("#ajax-modal-realisasi-pengajuan").modal('show');
            $('#realisasi_diajukan_oleh').val($row['employee']['employee_name']);
            $('#realisasi_department').val($row['employee']['department_name']);
            $('#realisasi_bagian').val($row['employee']['sub_dept_name']);
            $('#realisasi_tanggal_kedatangan_tamu').val($row['department']['department_name']);
            $('#realisasi_nama_instansi').val($row['tamu_instansi']);
            $('#realisasi_nama_tamu').val($row['nama_tamu']);
            $('#realisasi_jabatan_tamu').val($row['jabatan_tamu']);
            $('#realisasi_jumlah_tamu').val($row['qty_tamu']);
            $('#realisasi_keperluan').val($row['keperluan']);
        }


        $(document).ready(function() {
            $('#entertaintTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                paging: false,
                searching: false,
                info: false,
                ajax: {
                    url: '{{ route('cuti_karyawan.show') }}',
                    data: function(d) {
                        d.selectEmployeeID = $('#selectEmployeeID').val();
                    }
                },
                columns: [
                    { data: 'enroll_id', name: 'enroll_id' },
                    { data: 'employee_name', name: 'employee_name' },
                    { data: 'department_name', name: 'department_name' },
                    { data: 'sub_dept_name', name: 'sub_dept_name' },
                    { data: 'join_date', name: 'join_date' },
                    { data: 'start_date', name: 'start_date' },
                    { data: 'end_date', name: 'end_date' },
                    { data: 'lama_bekerja', name: 'lama_bekerja' },
                    { data: 'is_eligible', name: 'dapat_cuti' },
                    { data: 'used_leave', name: 'cuti_terpakai' },
                    { data: 'remaining_leave', name: 'remaining_leave' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });
        });


        function export_pengajuan_permintaan_kas(entertain_id) {
            var url = 'entertaint_tamu/export_pengajuan_permintaan_kas?entertain_id='+entertain_id;
            window.open(url, '_blank');
        }
        function export_realisasi_permintaan_kas(entertain_id) {
            var url = 'entertaint_tamu/export_realisasi_permintaan_kas?entertain_id='+entertain_id;
            window.open(url, '_blank');
        }
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
