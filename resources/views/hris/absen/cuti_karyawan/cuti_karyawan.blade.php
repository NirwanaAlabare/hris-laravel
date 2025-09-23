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
#table_detail_cuti_karyawan thead th {
    background-color: var(--primary);
    color: white;
}
#footer-primary {
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
#table_detail_cuti_karyawan {
    border-collapse: separate;
    border-spacing: 0 2px;
}


</style>
@section('mainarea')
<?php ini_set('date.timezone', 'Asia/Jakarta'); ?>
    <div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
        <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
            <li><a href="{{route('entertaint_tamu.index')}}">Master Data</a></li>
            <li class="active"><span>Cuti Tahunan</span></li>
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
                        <div class="card-title">Cuti Tahunan</div>
                    </div>
                        <div class="mt-4 ml-4 mr-5 mb-0">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">CARI DATA : </label>
                                        <select id="selectEmployeeID" name="selectEmployeeID[]" multiple data-placeholder="Pilih karyawan" class="form-control select2 EmployeeID">
                                            @foreach ($selectemployee as $r_empl)
                                            <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div clasl="" style="display: flex; justify-content: flex-start; align-items: center; gap: 10px;">
                                        <div class="mt-5 p-0">
                                            <button class="btn btn-primary w-100" onclick="searchData()"  data-toggle="tooltip" title="Cari Data" id="recap_labor_cost_2"><i class="fa fa-search" aria-hidden="true"></i> Cari</button>
                                        </div>
                                        <div class="mt-5 p-0">
                                            <button id="btn-view_excel" class="btn btn-success w-100 text-white" data-toggle="tooltip" title="Preview by excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">PILIH SKEMA : </label>
                                        <div class="input-group">
                                            <select id="skema_payroll" name="skema_payroll" class="form-control">
                                                <option value='CUSTOM_RANGE'>CUSTOM RANGE</option>
                                                <option value='MONTHLY'>MONTHLY</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">EXPORT DATA : </label>
                                        <div class="input-group" id="data_range_export_cuti_select">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                </div>
                                            </div>
                                            <input id="data_range_export_cuti" type="text" class="form-control data_range" required></input>
                                        </div>


                                        <div class="input-group" id="periode_month_cuti_select">
                                            <select name="periode_month" id="periode_month" class="form-control" required>
                                                <option value="01">Januari</option>
                                                <option value="02">Februari</option>
                                                <option value="03">Maret</option>
                                                <option value="04">April</option>
                                                <option value="05">Mei</option>
                                                <option value="06">Juni</option>
                                                <option value="07">Juli</option>
                                                <option value="08">Agustus</option>
                                                <option value="09">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div clasl="" style="display: flex; justify-content: flex-start; align-items: center; gap: 10px;">
                                        <div class="mt-5 p-0">
                                            <button id="btn_export_cuti_by_date" class="btn btn-success w-100 text-white" data-toggle="tooltip" title="Preview by excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel</button>
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
                                                <th>Status Aktif</th>
                                                <th>Tanggal Masuk</th>
                                                <th>Tanggal Resign</th>
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

    {{-- MODAL TAMBAH --}}
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


    {{-- MODAL DETAIL --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-scrollable" style="max-width: 65%;">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <p class="modal-title   " style="font-size: 18px; font-weight: bold;" id="exampleModalLabel"></p>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div style="height: 80vh; overflow-y: auto; over-flow-x:none;">
                    <div class="row p-5 d-flex justify-content-between">
                        <div class="col">
                            <div class="d-flex mb-2">
                                <p class="mb-0 me-2 fw-bold" style="width: 150px; font-size: 14px;">Nama Karyawan</p>
                                <p class="mb-0 me-2 fw-bold" style="width: 15px; font-size: 14px;">:</p>
                                <p class="mb-0" id="nama_karyawan_modal"></p>
                                <input type="hidden" class="mb-0" id="enroll_id_karyawan_modal"/>
                            </div>
                            <div class="d-flex mb-2">
                                <p class="mb-0 me-2 fw-bold" style="width: 150px; font-size: 14px;">NIK</p>
                                <p class="mb-0 me-2 fw-bold" style="width: 15px; font-size: 14px;">:</p>
                                <p class="mb-0" id="nik_karyawan_modal"></p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex mb-2 justify-content-end">
                                <button id="btn_export_cuti_by_user" class="btn btn-success w-25 text-white" data-toggle="tooltip" title="Preview by excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Download Rekap</button>
                            </div>
                        </div>
                    </div>
                    <div class="px-5 pt-2">
                        <div id="periodeTabs" class="nav nav-tabs mb-3" style="gap: 8px;" role="tablist">
                            <!-- Button tabs akan di-generate via JavaScript -->
                        </div>
                    </div>
                    <div class="table-responsive px-5">
                        <table id="table_detail_cuti_karyawan" class="table table-striped table-sm w-100 table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Form</th>
                                    <th>No Form</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Akhir</th>
                                    <th>Kode Absen</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="cutiTableBody">
                                <!-- Data akan dimasukkan via JavaScript -->
                            </tbody>
                        </table>
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
    document.addEventListener("DOMContentLoaded", function () {
        const skemaPayroll = document.getElementById("skema_payroll");
        const periodeContainer = document.getElementById("periode_month_cuti_select");
        const periodeContainerCustom = document.getElementById("data_range_export_cuti_select");

        // Sembunyikan semua input saat halaman pertama kali dimuat
        periodeContainer.style.display = "none";
        periodeContainerCustom.style.display = "none";

        skemaPayroll.addEventListener("change", function () {
            if (this.value === "MONTHLY") {
                periodeContainer.style.display = "flex"; // Tampilkan Monthly Payroll
                periodeContainerCustom.style.display = "none"; // Sembunyikan Custom Payroll
            } else if (this.value === "CUSTOM_RANGE") {
                periodeContainer.style.display = "none"; // Sembunyikan Monthly Payroll
                periodeContainerCustom.style.display = "flex"; // Tampilkan Custom Payroll
            } else {
                // Jika tidak ada yang dipilih, sembunyikan keduanya
                periodeContainer.style.display = "none";
                periodeContainerCustom.style.display = "none";
            }
        });
        skemaPayroll.dispatchEvent(new Event("change"));
    });
</script>
    <script>

        $('.data_range').daterangepicker({
                    ranges: {
                        'Hari ini': [moment(), moment()],
                        'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        '7 Hari Kemarin': [moment().subtract(6, 'days'), moment()],
                        '30 Hari Kemarin': [moment().subtract(29, 'days'), moment()],
                        'Bulan Sekarang': [moment().startOf('month'), moment().endOf('month')],
                        'Bulan Kemarin': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: moment().startOf('month'),
                    endDate: moment().endOf('month')
                    }, function(start, end) {
                        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        })

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
      $(document).on('click', '[id^=open_detail_]', function () {
            const enrollId = $(this).attr('id').replace('open_detail_', '');


            const nik = $(this).data('nik');
            const employeeName = $(this).data('employee_name');

            $('#exampleModal').modal('show');
            $("#exampleModalLabel").html('Detail Cuti');
            $("#nama_karyawan_modal").html(employeeName);
            $("#nik_karyawan_modal").html(nik);
            $("#enroll_id_karyawan_modal").val(enrollId);


            $.ajax({
                type: 'GET',
                url: '{{route('cuti_karyawan.showing_list_year_period')}}',
                data: {
                    enroll_id:enrollId,
                },
                success: function (data) {
                    const tabsContainer = document.getElementById('periodeTabs');
                    const tableBody = document.getElementById('cutiTableBody');
                    tabsContainer.innerHTML = '';
                    tableBody.innerHTML = '';

                    function renderTable(data) {
                            tableBody.innerHTML = '';

                            let totalDipakai = 0;
                            let totalHangus = 0;

                            // Jika data kosong, buat 12 baris CUTI HANGUS
                            if (data.length === 0) {
                                for (let i = 0; i < 12; i++) {
                                    totalHangus++;
                                    const row = `
                                        <tr style="background-color: #f8d7da; color: #721c24;">
                                            <td>${i + 1}</td>
                                            <td colspan="6" class="text-center fw-bold">CUTI HANGUS</td>
                                        </tr>`;
                                    tableBody.innerHTML += row;
                                }
                            } else {
                                data.forEach((item, index) => {
                                    let row = '';

                                    if (item.absen_alasan === 'CUTI HANGUS') {
                                        totalHangus++;
                                        row = `
                                            <tr style="background-color: #DDDFE2;">
                                                <td>${index + 1}</td>
                                                <td colspan="6" class="text-center fw-bold fs-1">-</td>
                                            </tr>`;
                                    } else {
                                        const days = countWorkingDaysBetween(item.tanggal_mulai_ijin, item.tanggal_akhir_ijin);
                                        totalDipakai += days;
                                        row = `
                                            <tr>
                                                <td>${index + 1}</td>
                                                <td>${item.tanggal_perizinan ?? '-'}</td>
                                                <td>${item.nomor_form_perizinan}</td>
                                                <td>${item.tanggal_mulai_ijin}</td>
                                                <td>${item.tanggal_akhir_ijin}</td>
                                                <td>${item.kode_absen_ijin}</td>
                                                <td>${item.absen_alasan}</td>
                                            </tr>`;
                                    }

                                    tableBody.innerHTML += row;
                                });
                            }

                            // Bersihkan footer sebelumnya (jika ada)
                            const tfoot = document.querySelector('#table_detail_cuti_karyawan tfoot');
                            if (tfoot) tfoot.remove();

                            // Tambahkan baris total di bawah tabel
                            const footer = document.createElement('tfoot');
                            const jatahCuti = 12;
                            const sisaCuti = Math.max(jatahCuti - totalDipakai, 0);
                            footer.innerHTML = `
                               <tr id="footer-primary" class="fw-bold">
                                    <td colspan="7" class="text-end">
                                        Total Cuti Dipakai: ${totalDipakai} &nbsp;&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;&nbsp; Cuti Hangus / Tidak Terpakai: ${sisaCuti}
                                    </td>
                                </tr>
                            `;
                            document.querySelector('#table_detail_cuti_karyawan').appendChild(footer);
                        }




                    // Buat tab untuk tiap periode
                    data.forEach((periodeObj, index) => {
                        const isActive = index === 0 ? 'active' : '';
                        const tab = document.createElement('button');
                        tab.className = `nav-link ${isActive}`;
                        tab.textContent = periodeObj.periode;
                        tab.setAttribute('data-index', index);
                        tab.setAttribute('type', 'button');
                        tab.onclick = () => {
                            document.querySelectorAll('#periodeTabs .nav-link').forEach(btn => btn.classList.remove('active'));
                            tab.classList.add('active');
                            renderTable(periodeObj.data);
                        };
                        tabsContainer.appendChild(tab);
                    });

                    // Tampilkan data periode pertama kalau ada
                    if (data.length > 0) {
                        renderTable(data[0].data);
                    }
                },
                error: function(res){
                    swal("", "Export rekap cuti karyawan", "error");
                }
            });

            // --- Helper: parse tanggal dengan aman (menghindari masalah timezone) ---
            function parseDateDMY(input) {
                if (!input) return null;
                const s = String(input).trim();
                const parts = s.split('-');
                if (parts.length === 3) {
                    const d = parseInt(parts[0], 10);
                    const m = parseInt(parts[1], 10) - 1; // bulan 0–11
                    const y = parseInt(parts[2], 10);
                    if (!isNaN(d) && !isNaN(m) && !isNaN(y)) {
                        return new Date(y, m, d);
                    }
                }
                return null;
            }

            function countWorkingDaysBetween(startInput, endInput) {
                const start = parseDateDMY(startInput);
                const end = parseDateDMY(endInput);
                if (!start || !end) return 0;

                // tukar kalau start > end
                let s = start;
                let e = end;
                if (s > e) { s = end; e = start; }

                let count = 0;
                for (let d = new Date(s); d <= e; d.setDate(d.getDate() + 1)) {
                    const day = d.getDay(); // 0 = Minggu, 6 = Sabtu
                    if (day !== 0 && day !== 6) count++;
                }
                return count;
            }



            // Helper untuk hitung jumlah hari kerja (exclude Sabtu & Minggu)
            function countWorkingDays(startDate, endDate) {
                let start = new Date(startDate);
                let end = new Date(endDate);
                let count = 0;

                while (start <= end) {
                    const day = start.getDay(); // 0 = Minggu, 6 = Sabtu
                    if (day !== 0 && day !== 6) {
                        count++;
                    }
                    start.setDate(start.getDate() + 1);
                }
                return count;
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
        });

        $('#btn_export_cuti_by_date').click(function (e) {
            const tipe = $('#skema_payroll').val();
            let tgl = '';

            if (tipe === 'CUSTOM_RANGE') {
                tgl = $('#data_range_export_cuti').val();
            } else if (tipe === 'MONTHLY') {
                tgl = $('#periode_month').val(); // Ambil dari input type="month"
            }

            if (!tgl) {
                swal("Oops!", "Silakan pilih periode terlebih dahulu!", "warning");
                return;
            }

            $('#btn_export_cuti_by_date').addClass("btn-loading");
            $("#btn_export_cuti_by_date").html('Please wait...');
            $("#btn_export_cuti_by_date").attr("disabled", true);

            $.ajax({
                type: 'POST',
                url: '{{ route('cuti_karyawan.show_export_by_join_date') }}',
                data: {
                    join_date: tgl,
                    type: tipe
                },
                xhrFields: { responseType: 'blob' },
                success: function (data) {
                    $('#btn_export_cuti_by_date').removeClass("btn-loading");
                    $("#btn_export_cuti_by_date").attr("disabled", false);
                    $("#btn_export_cuti_by_date").html('<i class="fa fa-file-excel-o" aria-hidden="true"></i> View Excel');
                    const blob = new Blob([data]);
                    const link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "cuti_karyawan.xlsx";
                    link.click();
                    swal("", "Export rekap cuti karyawan", "success");
                },
                error: function () {
                    $('#btn_export_cuti_by_date').removeClass("btn-loading");
                    $("#btn_export_cuti_by_date").attr("disabled", false);
                    $("#btn_export_cuti_by_date").html('<i class="fa fa-file-excel-o" aria-hidden="true"></i> View Excel');
                    swal("", "Export rekap cuti karyawan gagal", "error");
                }
            });
        });

        $('#btn_export_cuti_by_user').click(function (e) {
            const enroll_id = $("#enroll_id_karyawan_modal").val();
            $('#btn_export_cuti_by_user').addClass("btn-loading");
            $("#btn_export_cuti_by_user").html('Please wait...');
            $("#btn_export_cuti_by_user").attr("disabled", true);

            $.ajax({
                type: 'POST',
                url: '{{ route('cuti_karyawan.show_export_by_user') }}',
                data: {
                    enroll_id: enroll_id,
                },
                xhrFields: { responseType: 'blob' },
                success: function (data) {
                    $('#btn_export_cuti_by_user').removeClass("btn-loading");
                    $("#btn_export_cuti_by_user").attr("disabled", false);
                    $("#btn_export_cuti_by_user").html('<i class="fa fa-file-excel-o" aria-hidden="true"></i> View Excel');
                    const blob = new Blob([data]);
                    const link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "cuti_karyawan.xlsx";
                    link.click();
                    swal("", "Export rekap cuti karyawan", "success");
                },
                error: function () {
                    $('#btn_export_cuti_by_user').removeClass("btn-loading");
                    $("#btn_export_cuti_by_user").attr("disabled", false);
                    $("#btn_export_cuti_by_user").html('<i class="fa fa-file-excel-o" aria-hidden="true"></i> View Excel');
                    swal("", "Export rekap cuti karyawan gagal", "error");
                }
            });
        });


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
                    { data: 'status_aktif', name: 'status_aktif' },
                    { data: 'join_date', name: 'join_date' },
                    { data: 'tanggal_resign', name: 'tanggal_resign' },
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
