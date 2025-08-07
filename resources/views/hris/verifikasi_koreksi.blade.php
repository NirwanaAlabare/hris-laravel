@extends('admin.adminlayouts.adminlayout')

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

#datatable-ajax-crud thead th {
    background-color: var(--primary);
    color: white;
}
.primary-button {
    background-color: var(--primary) !important;
    color: white !important;
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
#datatable-ajax-crud-verifikasi th,
#datatable-ajax-crud-verifikasi td {
    white-space: nowrap;
}


</style>
@section('mainarea')
<?php ini_set('date.timezone', 'Asia/Jakarta'); ?>
    <div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
        <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
            <li><a href="{{route('entertaint_tamu.index')}}">Koreksi</a></li>
            <li class="active"><span>Verifikasi Koreksi</span></li>
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
                        <div class="card-title">Verifikasi Koreksi</div>
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
                                            <button type="button" class="btn btn-secondary ml-2" id="clear-daterange">Clear</button>
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
                                        </ul>
                                        <div class="content_wrapper">
                                            <!-- Tab Waiting -->
                                            <div class="tab_content active" id="tab-content-waiting">
                                                <button id="verifikasi-btn" class="btn btn-primary py-2 mb-4" style="display: none">Verifikasi</button>
                                                <div class="table-responsive">
                                                    <table id="datatable-ajax-crud-waiting" class="table table-sm table-striped table-hover table-bordered w-100">
                                                        <thead>
                                                            <tr class="text-center">
                                                                <th scope="col" width="3%">
                                                                    <input type="checkbox" id="checkAllEmployee" onchange="actionCheckAllEmployee(this)">
                                                                </th>

                                                                <th scope="col">Tanggal Koreksi</th>
                                                                <th scope="col">Jenis Koreksi</th>
                                                                <th scope="col">NIP</th>
                                                                <th scope="col">Nama Karyawan</th>
                                                                <th scope="col">Depaerment</th>
                                                                <th scope="col">Nama Koreksi</th>
                                                                <th scope="col">Jumlah</th>
                                                                <th scope="col">Keterangan</th>
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
                                                                <th scope="col">Aksi</th>
                                                                <th scope="col">Tanggal Koreksi</th>
                                                                <th scope="col">Jenis Koreksi</th>
                                                                <th scope="col">NIP</th>
                                                                <th scope="col">Nama Karyawan</th>
                                                                <th scope="col">Depaerment</th>
                                                                <th scope="col">Nama Koreksi</th>
                                                                <th scope="col">Jumlah</th>
                                                                <th scope="col">Keterangan</th>
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

    <style>
        .checkbox-xl .form-check-input {
            scale: 1.5;
        }
    </style>

    <script>
         $('.fc-datepicker').datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'dd-mm-yy'
        });
    </script>

    <script>
        $(document).ready(function () {
            function toggleTanggalForm() {
                const selected = $('#kode_absen_ijin').val();
                if (selected && selected !== 'DL' && selected !== 'I' && selected !== 'S') {
                    $('#form_delegasi').show();
                } else {
                    $('#form_delegasi').hide();
                }
            }

            // Inisialisasi saat halaman dimuat
            toggleTanggalForm();

            // Event listener saat opsi berubah
            $('#kode_absen_ijin').on('change', toggleTanggalForm);
        });
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






        $('body').on('click', '#btn-icon-refresh-izin', function(event){
            var tanggal_periz = $('#tanggal_perijinan').val();
            var tanggal_perizinan=tanggal_periz.substr(6, 4)+'-'+tanggal_periz.substr(3,2)+'-'+tanggal_periz.substr(0,2);
            var kode_absen_ijin = $('#kode_absen_ijin').val();
            if(kode_absen_ijin==''){
                notif({
                    msg: "<b>Error:</b> Jenis perizinan belum dipilih.",
                    type: "error"
                });
            }else{
                $.ajax({
                    type:"POST",
                    url: "{{route('hris.dataabsenperijinan.get_last_nomor_form_perizinan')}}",
                    data: {
                        tanggal_perizinan:tanggal_perizinan,
                        kode_ijin:kode_absen_ijin,
                    },
                    success: function(res){
                        $('#nomor_form_perizinan_izin').val(res);
                    }
                });
            }
        });

        $('body').on('click', '#btn-cancel-izin', function (event) {
            setToNull();
        });

        $('body').on('click', '#btn-cancel-iks', function (event) {
            setToNull();
        });

        $('body').on('click', '#btn-icon-refresh-iks', function(event){
            var tanggal_periz = $('#tanggal_perijinan').val();
            var tanggal_perizinan=tanggal_periz.substr(6, 4)+'-'+tanggal_periz.substr(3,2)+'-'+tanggal_periz.substr(0,2);
            $.ajax({
                type:"POST",
                url: "{{route('hris.dataabsenperijinan.get_last_nomor_form_perizinan_iks')}}",
                data: {
                    tanggal_perizinan:tanggal_perizinan,
                },
                success: function(res){
                    $('#nomor_form_perizinan_iks').val(res);
                }
            });
        });

        function hitungtotaljam() {
            var tanggal_periz = $('#tanggal_perijinan').val();
            var tglform=tanggal_periz.substr(6, 4)+'-'+tanggal_periz.substr(3,2)+'-'+tanggal_periz.substr(0,2);
            var tm1 = new Date(tglform + " " + $('#time_mulai_ijin').val());
            var tm2 = new Date(tglform + " " + $('#time_akhir_ijin').val());
            var total_time_ijin = diff_minutes(tm1, tm2);
            $('#total_time_ijin').val(total_time_ijin);
        };

        function diff_minutes(dt2, dt1)
        {

         var diff =(dt2.getTime() - dt1.getTime()) / 1000;
         diff /= 60;
         return Math.abs(Math.round(diff));

        }
    </script>

    <script>
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
        $("#didelegasikanID").select2().on("select2:select", function() {
            var selectedOption = $('#didelegasikanID').find(':selected');
            if(selectedOption.val()){
                document.getElementById('didelegasikan_enroll_id').value = selectedOption.val();
            }
        });


        function setToNull() {
            $('#tanggal_perijinan').val(null).prop('disabled', false);  // Format tanggal dan disable
            $('#diajukanOlehID').val(null).trigger('change').prop('disabled', false);  // Pilih karyawan dan disable
            $('#didelegasikanID').val(null).trigger('change').prop('disabled', false);  // Pilih karyawan dan disable
            $('#department').val(null).prop('disabled', false);  // Masukkan nama department dan disable
            $('#department_id').val(null).prop('disabled', false);  // Masukkan ID department dan disable
            $('#bagian').val(null).prop('disabled', false);  // Masukkan nama bagian/sub-department dan disable
            $('#sub_dept_id').val(null).prop('disabled', false);  // Masukkan ID bagian/sub-department dan disable

            $('#tanggal_mulai_ijin_before').val(null);
            $('#tanggal_akhir_ijin_before').val(null);

            $('#uuid').val(null);
            $('#uuid_master').val(null);

            $('#nomor_form_perizinan').val(null);
            $('#enroll_id').val(null);
            $('#nik').val(null);
            $('#employee_name').val(null);

            $("#kode_absen_ijin").val(null).trigger("change");
            $('#nomor_form_perizinan_izin').val(null);
            $('#tanggal_mulai_ijin').val(null);
            $('#tanggal_akhir_ijin').val(null);
            $('#absen_alasan_izin').val(null);
            $('#created_at_izin').text(null);
            $('#updated_at_izin').text(null);
            $("#kode_absen_ijin_iks").val(null).trigger("change");
            $('#absen_alasan_iks').val(null);
            $('#time_mulai_ijin').val(null);
            $('#time_akhir_ijin').val(null);
            $('#total_time_ijin').val(null);
            $('#created_at_iks').text(null);
            $('#updated_at_iks').text(null);
            $('#nomor_form_perizinan_iks').val(null);
        }

        function actionCheckAllEmployee(element) {
            const allowedUsers = ['ersa@ptnag.com', 'IT', 'mega@ptnag.com', 'fadli', 'rudy@ptnag.com', 'hrd','indri@nag.nirwanaindonesia.com'];
            const currentUser = $('#username_who_access').val();
            const btn = document.getElementById("verifikasi-btn");
            if (element.checked) {
                $('#datatable-ajax-crud-waiting tbody input.form-check-input').each(function() {
                        var uuid = $(this).val();

                        if (!perijinanChecked.includes(uuid)) {
                            perijinanChecked.push(uuid);
                        }

                        $(this).prop('checked', true);
                        btn.style.display = "flex";
                });

            } else {
                $('#datatable-ajax-crud-waiting tbody input.form-check-input').each(function() {
                    var uuid = $(this).val();

                    perijinanChecked = perijinanChecked.filter(item => item !== uuid);

                    $(this).prop('checked', false);
                });

                if (perijinanChecked.length === 0) {
                    btn.style.display = "none";
                }
            }
        }

        function actionThisEmployeeCheck(element) {
            const allowedUsers = ['ersa@ptnag.com', 'IT', 'mega@ptnag.com', 'fadli', 'rudy@ptnag.com', 'hrd','indri@nag.nirwanaindonesia.com'];
            const currentUser = $('#username_who_access').val();
            const btn = document.getElementById("verifikasi-btn");
                if (element.checked) {
                    if(!perijinanChecked.find((value) => value == element.value)) {
                        perijinanChecked.push(element.value);
                    }
                } else {
                    if(perijinanChecked.find((value) => value == element.value)) {
                        const index = perijinanChecked.indexOf(element.value);
                        if (index > -1) { // only splice array when item is found
                            perijinanChecked.splice(index, 1); // 2nd parameter means remove one item only
                        }
                    }
                }
                if(perijinanChecked.length>0){
                    if (allowedUsers.includes(currentUser)) {
                        btn.style.display = "flex";
                    }
                }else{
                    btn.style.display = "none";
                }
        }

        var perijinanChecked = [];
        var currentPageCheck = 0;
        $(document).ready(function() {

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
        })

        $('#clear-daterange').on('click', function () {
            $('#daterange-btn1').val(''); // kosongkan input
            $('#daterange1').val('');     // kosongkan hidden input
            tableWaiting.ajax.reload();
            tableVerifikasi.ajax.reload();
        });

            var tableWaiting = $('#datatable-ajax-crud-waiting').DataTable({
                ajax: {
                    url: '{{ route('hris.koreksipotongan.list_verifikasi_koreksi') }}',
                    type: "POST",
                    data: function (d) {
                        d.is_verifikasi_acc = 0;
                        d.tanggal_range = $('#daterange1').val(); // kirim range terpilih
                    }
                },
                processing: true,
                serverSide: true,
                columns: [
                    {
                        data: 'uuid',
                        orderable: false
                    },
                    { data: 'tanggal_koreksi',
                    width: '10%',
                      render: function(data, type, row) {
                            return moment(data).format('DD-MM-YYYY');  // Formatkan tanggal ke dmy
                        }
                    },
                    { data: 'sumber',
                    className: "text-center",
                        render: function(data, type, row) {
                            if(data == 'PENAMBAH UPAH'){
                                return `<span class="badge badge-pill w-75 badge-primary text-white">${data}</span>`;
                            }else{
                                return `<span class="badge badge-pill w-75 badge-danger text-white">${data}</span>`;
                            }
                        }
                    },
                    { data: 'nik' },
                    { data: 'employee_name' },
                    {data: 'department_name'},
                    { data: 'jenis_potongan',
                        render: function (data, type, row) {
                            // Gunakan nama_absen_ijin jika tidak null, kalau null pakai kode_absen_ijin
                            let value = data;
                            let sumber = row.sumber;
                            let label = '-';
                            const jenisPotonganMapping = {
                                1: 'POTONGAN BPJS TK',
                                2: 'POTONGAN BPJS KS',
                                3: 'POTONGAN BAZAR',
                                4: 'POTONGAN KAS BON',
                                5: 'POTONGAN LAINNYA',
                                6: 'POTONGAN KARYAWAN',
                                7: 'POTONGAN UPAH',
                                8: 'POTONGAN LEMBUR',
                            };

                            const jenisKoreksiMapping = {
                                1: 'UPAH',
                                2: 'INSENTIF JABATAN',
                                3: 'LEMBUR',
                                4: 'INSENTIF LAINNYA',
                            };

                            if (sumber === 'POTONGAN') {
                                label = jenisPotonganMapping[data] ?? '-';
                            } else if (sumber === 'PENAMBAH UPAH') {
                                label = jenisKoreksiMapping[data] ?? '-';
                            }

                            return `<h6>${label}</h6>`;

                        }
                    },
                    { data: 'jumlah_rp_potongan_format' },
                    { data: 'keterangan' },
                ],
                columnDefs: [
                {
                    'targets': [0],
                    'render' : function (data,type, row) {
                        return `
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" style='width: 20px; height: 20px;' value="`+data+`" style='width: 20px; height: 20px;' id="checked_uuid_` + row.uuid + `" onchange="actionThisEmployeeCheck(this)" >
                            </div>
                        `
                    }
                }
                ],
                rowCallback: function(row, data, dataIndex){
                    let currentEnrollId = data['uuid'];

                    perijinanChecked.forEach((item, index, array) => {
                        if(item==currentEnrollId){
                            currentPageCheck++;
                            $(row).find('input[id="checked_uuid_'+item+'"]').prop('checked', true);
                        }
                    });
                },
                drawCallback: function (settings) {
                    if (currentPageCheck == 0) {
                        $('#checkAllEmployee').prop("checked", false);
                    } else {
                        $('#checkAllEmployee').prop("checked", true);
                    }

                    currentPageCheck = 0;
                }
            });

            // Table untuk Verifikasi (is_verifikasi == 1)
            var tableVerifikasi = $('#datatable-ajax-crud-verifikasi').DataTable({
                ajax: {
                    url: '{{ route('hris.koreksipotongan.list_verifikasi_koreksi') }}',
                    type: "POST",
                    data: function (d) {
                        d.is_verifikasi_acc = 1;
                        d.tanggal_range = $('#daterange1').val(); // kirim range terpilih
                    }
                },
                processing: true,
                serverSide: true,
                columns: [
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        width: '10%',
                        className: "text-center",
                        render: function (data, type, row) {

                                if($('#username_who_access').val()=='ersa@ptnag.com' || $('#username_who_access').val()=='IT'|| $('#username_who_access').val()=='mega@ptnag.com'|| $('#username_who_access').val()=='fadli'|| $('#username_who_access').val()=='rudy@ptnag.com'|| $('#username_who_access').val()=='hrd' || $('#username_who_access').val()=='indri@nag.nirwanaindonesia.com'){
                                    return `
                                       <button class="btn btn-sm py-0 m-0 btn-danger" id="btn-unverif" data-uuid="${row.uuid}" data-sumber="${row.sumber}" title="UnVerif">
                                        Unverif
                                    </button>
                                    `;
                                }else{
                                    return ``;
                                }
                        }
                    },
                    {   data: 'tanggal_koreksi',
                        render: function(data, type, row) {
                                return moment(data).format('DD-MM-YYYY');  // Formatkan tanggal ke dmy
                        }
                    },
                    {   data: 'sumber',
                        className: "text-center",
                        render: function(data, type, row) {
                            if(data == 'PENAMBAH UPAH'){
                                return `<span class="badge badge-pill w-75 badge-primary text-white">${data}</span>`;
                            }else{
                                return `<span class="badge badge-pill w-75 badge-danger text-white">${data}</span>`;
                            }
                        }
                    },
                    { data: 'nik' },
                    { data: 'employee_name' },
                    {data: 'department_name'},
                    { data: 'jenis_potongan',
                        render: function (data, type, row) {
                            // Gunakan nama_absen_ijin jika tidak null, kalau null pakai kode_absen_ijin
                            let value = data;
                            let sumber = row.sumber;
                            let label = '-';
                            const jenisPotonganMapping = {
                                1: 'POTONGAN BPJS TK',
                                2: 'POTONGAN BPJS KS',
                                3: 'POTONGAN BAZAR',
                                4: 'POTONGAN KAS BON',
                                5: 'POTONGAN LAINNYA',
                                6: 'POTONGAN KARYAWAN',
                                7: 'POTONGAN UPAH',
                                8: 'POTONGAN LEMBUR',
                            };

                            const jenisKoreksiMapping = {
                                1: 'UPAH',
                                2: 'INSENTIF JABATAN',
                                3: 'LEMBUR',
                                4: 'INSENTIF LAINNYA',
                            };

                            if (sumber === 'POTONGAN') {
                                label = jenisPotonganMapping[data] ?? '-';
                            } else if (sumber === 'PENAMBAH UPAH') {
                                label = jenisKoreksiMapping[data] ?? '-';
                            }

                            return `<h6>${label}</h6>`;

                        }
                    },
                    { data: 'jumlah_rp_potongan_format' },
                    { data: 'keterangan' }
                ]
            });


            $('body').on('click', '#verifikasi-btn', function (event) {
                var uuid_checked = perijinanChecked;

                message = "Anda Yakin Ingin Verifikasi data ini?";
                type = "warning";
                swal({
                    title: message,
                    type: type,
                    showCancelButton: true,
                    confirmButtonText: 'Saya Yakin',
                    cancelButtonText: 'Tutup'
                },function(isConfirm){
                    if(isConfirm) {
                        $("#form1 :input").prop("disabled", true);
                        $("#btn-save-izin").prop("disabled", true);
                        $("#btn-save-iks").prop("disabled", true);
                        $("#btn-cancel-izin").prop("disabled", true);
                        $("#btn-cancel-iks").prop("disabled", true);
                        $('#progress-show-1').show();
                        $('#progress-hide-1').hide();
                        $.ajax({
                            type:"POST",
                            url: "{{route('hris.koreksipotongan.verifikasi_koreksi_data')}}",
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            data: {
                                uuid_checked:uuid_checked,
                            },
                            dataType: 'json',
                            success: function(res){
                                notif({
                                    msg: "<b>Info:</b> Data berhasil di hapus.",
                                    type: "info"
                                });
                            perijinanChecked = [];

                            // Sembunyikan tombol verifikasi
                            document.getElementById("verifikasi-btn").style.display = "none";
                            tableWaiting.ajax.reload();
                            tableVerifikasi.ajax.reload();
                            },
                            error: function(res){
                                notif({
                                    msg: "<b>Error:</b> Oops data gagal di hapus.",
                                    type: "error"
                                });
                            }
                        });

                        $('#progress-show-1').hide();
                        $('#progress-hide-1').show();
                        $("#datatable-ajax-crud").DataTable().ajax.reload();

                    } else {
                        // else everythings
                    }
                });


            });
            $('body').on('click', '#btn-unverif', function (event) {
                var uuid = $(this).data('uuid');
                var sumber = $(this).data('sumber');
                message = "Anda Yakin Ingin Un-Verif";
                type = "warning";
                swal({
                    title: message,
                    type: type,
                    showCancelButton: true,
                    confirmButtonText: 'Saya Yakin',
                    cancelButtonText: 'Tutup'
                },function(isConfirm){
                    if(isConfirm) {
                        $("#form1 :input").prop("disabled", true);
                        $("#btn-save-izin").prop("disabled", true);
                        $("#btn-save-iks").prop("disabled", true);
                        $("#btn-cancel-izin").prop("disabled", true);
                        $("#btn-cancel-iks").prop("disabled", true);
                        $('#progress-show-1').show();
                        $('#progress-hide-1').hide();
                        $.ajax({
                            type:"POST",
                            url: "{{route('hris.koreksipotongan.unverifikasi_koreksi')}}",
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            data: {
                                uuid:uuid,
                                sumber:sumber,
                            },
                            dataType: 'json',
                            success: function(res){
                                notif({
                                    msg: "<b>Info:</b> Data berhasil di hapus.",
                                    type: "info"
                                });
                            tableWaiting.ajax.reload();
                            tableVerifikasi.ajax.reload();
                            },
                            error: function(res){
                                notif({
                                    msg: "<b>Error:</b> Oops data gagal di hapus.",
                                    type: "error"
                                });
                            }
                        });

                        $('#progress-show-1').hide();
                        $('#progress-hide-1').show();
                        $("#datatable-ajax-crud").DataTable().ajax.reload();

                    } else {
                        // else everythings
                    }
                });


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

            $(document).on('click', '.btn-edit', function () {
                let uuid = $(this).data('id');
                // Redirect atau tampilkan form edit
            });

            $(document).on('click', '.btn-delete', function () {
                let uuid = $(this).data('id');
                if (confirm("Yakin ingin menghapus?")) {
                    // Kirim AJAX delete ke server
                }
            });

            $('body').on('click', '#btn-approve-pengajuan', function (event) {
                var kode_absen_ijin = $('#kode_absen_ijin_modal_approve').val();
                var uuid = $('#uuid_modal').val();
                let url = "{{route('cuti_karyawan.dataabsenperijinan.approve_hr_perizinan_menu')}}";
                $('#ajax-modal-approve-pengajuan').modal('hide');
                if(kode_absen_ijin == 'IKS'){
                    url = "{{route('cuti_karyawan.dataabsenperijinan.approve_iks')}}";
                }
                $.ajax({
                    type:"POST",
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        uuid:uuid,
                    },
                    success: function(res){
                        swal("", "approve perizinan berhasil", "success");
                        $('#uuid_modal').val(null);
                        $('#uuid_master_modal').val(null);
                        $('#enroll_id_modal').val(null);

                        $('#tanggal_perijinan_modal').text(null).prop('disabled', true);
                        $('#department_modal').text(null).prop('disabled', true);
                        $('#bagian_modal').text(null).prop('disabled', true);
                        $('#nomor_form_perijinan_modal').text(null);

                        $('#keterangan_modal').text(null);
                        $('#kode_absen_ijin_modal').text(null);
                        $('#nik_karyawan_modal').text(null);
                        $('#nama_karyawan_modal').text(null);
                        tableVerifikasi.ajax.reload();
                        tableWaiting.ajax.reload();
                        tableReject.ajax.reload();
                    },
                    error: function(res){
                        swal("", "approve perizinan gagal", "error");
                    }
                });
            })


            $('body').on('click', '#btn-reject-pengajuan', function (event) {
                var keterangan = $('#keterangan_reject_modal').val().trim();

                if (keterangan === '') {
                    $('#keterangan_reject_modal').addClass('is-invalid');
                    $('#keterangan_error').show();
                    return;
                } else {
                    $('#keterangan_reject_modal').removeClass('is-invalid');
                    $('#keterangan_error').hide();
                }

                $('#ajax-modal-approve-pengajuan').modal('hide');
                var uuid = $('#uuid_modal').val();
                $.ajax({
                    type:"POST",
                    url: "{{route('cuti_karyawan.dataabsenperijinan.reject_hr_perizinan_menu')}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        uuid: uuid,
                        keterangan: keterangan
                    },
                    success: function(res){
                        swal("", "Reject perizinan berhasil", "success");
                        $('#uuid_modal').val(null);
                        $('#uuid_master_modal').val(null);
                        $('#kode_absen_ijin_modal_approve').val(null);
                        $('#enroll_id_modal').val(null);

                        $('#tanggal_perijinan_modal').text(null).prop('disabled', true);
                        $('#department_modal').text(null).prop('disabled', true);
                        $('#bagian_modal').text(null).prop('disabled', true);
                        $('#nomor_form_perijinan_modal').text(null);

                        $('#keterangan_modal').text(null);
                        $('#kode_absen_ijin_modal').text(null);
                        $('#nik_karyawan_modal').text(null);
                        $('#nama_karyawan_modal').text(null);
                        $('#keterangan_reject_modal').val('').removeClass('is-invalid');
                        $('#keterangan_reject_modal').val(null);
                        $('#keterangan_error').hide();

                        tableVerifikasi.ajax.reload();
                        tableWaiting.ajax.reload();
                        tableReject.ajax.reload();
                    },
                    error: function(res){
                        swal("", "Reject perizinan gagal", "error");
                    }
                });
            });


            $('body').on('click', '#btn-close-modal-approve-pengajuan', function (event) {
                $('#ajax-modal-approve-pengajuan').modal('hide');
                $('#uuid_modal').val(null);
                $('#uuid_master_modal').val(null);
                $('#kode_absen_ijin_modal_approve').val(null);
                $('#enroll_id_modal').val(null);

                $('#tanggal_perijinan_modal').text(null).prop('disabled', true);
                $('#department_modal').text(null).prop('disabled', true);
                $('#bagian_modal').text(null).prop('disabled', true);
                $('#nomor_form_perijinan_modal').text(null);

                $('#keterangan_modal').text(null);
                $('#keterangan_reject_modal').val(null).removeClass('is-invalid');
                $('#kode_absen_ijin_modal').text(null);
                $('#nik_karyawan_modal').text(null);
                $('#nama_karyawan_modal').text(null);
                tableVerifikasi.ajax.reload();
                tableWaiting.ajax.reload();
                tableReject.ajax.reload();
            });


        });
    </script>

    <script>
        $('body').on('click', '#btn-save-izin', function (event) {
            var uuid = $('#uuid').val();
            var uuid_master = $('#uuid_master').val();
            var didelegasikan_enroll_id = $('#didelegasikan_enroll_id').val();
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

            var tanggal_mulai_ijin_before = $('#tanggal_mulai_ijin_before').val();
            var tanggal_akhir_ijin_before = $('#tanggal_akhir_ijin_before').val();


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

            if (kode_absen_ijin !== "DL" && kode_absen_ijin !== "I" && kode_absen_ijin !== "S") {
                if (!didelegasikan_enroll_id) {
                    notif({
                        msg: "<b>Warning:</b> Anda belum memilih karyawan untuk didelegasikan.",
                        type: "warning"
                    });
                    return false;
                }
            }



            var tanggal = tanggal_perizinan;
            $('#btn-save-izin').addClass("btn-loading");
            $("#btn-save-izin").html('Please wait...');
            $("#btn-save-izin").attr("disabled", true);
            $('#progress-show-1').show();
            $('#progress-hide-1').hide();

            // LAGI COBA TEST CLOSING PAYROLL
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
                                tanggal_mulai_ijin:tanggal_mulai_ijin_before,
                                tanggal_akhir_ijin:tanggal_akhir_ijin_before,
                            },
                            dataType: 'json',
                            success: function(res){
                                if (res.length > 0) {
                                    var uuid_res=res[0].uuid;
                                    var nomor_form_res=res[0].nomor_form_perizinan;
                                    $.ajax({
                                        type:"POST",
                                        url: "{{route('cuti_karyawan.update_perizinan_menu_admin')}}",
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
                                            didelegasikan_enroll_id:didelegasikan_enroll_id,
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
                                            $('#didelegasikan_enroll_id').val('');
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
                                }
                                // else if(res.length > 0 && res[0].nomor_form_perizinan && res[0].is_verifikasi_pengajuan_admin == 1){
                                //         notif({
                                //             msg: `<b>Info:</b> Perijinan sudah dibuat oleh ${res[0].operator}.`,
                                //             type: "info",
                                //             position: "center",
                                //             width: 800,
                                //         });
                                //         $('#progress-show-1').hide();
                                //         $('#progress-hide-1').show();
                                //         $('#btn-save-izin').removeClass("btn-loading");
                                //         $("#btn-save-izin").html('<span><i class="fa fa-save"></i></span> Save');
                                //         $("#form1 :input").prop("disabled", false);
                                //         $("#btn-save-izin").prop("disabled", false);
                                //         $("#btn-save-iks").prop("disabled", false);
                                //         $("#btn-cancel-izin").prop("disabled", false);
                                //         $("#btn-cancel-iks").prop("disabled", false);
                                //         return false;
                                // }else if(res.length > 0 && !res[0].nomor_form_perizinan){
                                //         notif({
                                //             msg: `<b>Info:</b> Perijinan pada tanggal tersebut sudah dibuat oleh ${res[0].operator}, menunggu Approval.`,
                                //             type: "info",
                                //             position: "center",
                                //             width: 800,
                                //         });
                                //         $('#progress-show-1').hide();
                                //         $('#progress-hide-1').show();
                                //         $('#btn-save-izin').removeClass("btn-loading");
                                //         $("#btn-save-izin").html('<span><i class="fa fa-save"></i></span> Save');
                                //         $("#form1 :input").prop("disabled", false);
                                //         $("#btn-save-izin").prop("disabled", false);
                                //         $("#btn-save-iks").prop("disabled", false);
                                //         $("#btn-cancel-izin").prop("disabled", false);
                                //         $("#btn-cancel-iks").prop("disabled", false);
                                //         return false;
                                // }
                                else {
                                    $.ajax({
                                        type:"POST",
                                        url: "{{route('cuti_karyawan.create_perizinan_menu_admin')}}",
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
                                            didelegasikan_enroll_id:didelegasikan_enroll_id,
                                        },
                                        dataType: 'json',
                                        success: function(res){
                                            if(res == 0){
                                                notif({
                                                    msg: "<b>Warning:</b> Karyawan telah masuk pada tanggal tersebut/Tidak ada jadwal.",
                                                    type: "error"
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
                                                $('#didelegasikan_enroll_id').val('');
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

                    setTimeout(function myFunction() {
                            location.reload();
                    }, 3000);

                },
                error: function(resA){

                }
            });

        });


        $('body').on('click', '#btn-save-iks', function (event) {
            var uuid = $('#uuid').val();
            var tanggal_periz = $('#tanggal_perijinan').val();
            var tanggal_perizinan=tanggal_periz.substr(6, 4)+'-'+tanggal_periz.substr(3,2)+'-'+tanggal_periz.substr(0,2);
            var nomor_form_perizinan = $('#nomor_form_perizinan').val();
            var enroll_id = $('#enroll_id').val();
            var nik = $('#nik').val();
            var employee_name = $('#employee_name').val();
            var kode_absen_ijin = $('#kode_absen_ijin_iks').val();
            var absen_alasan = $('#absen_alasan_iks').val();
            var time_mulai_ijin = $('#time_mulai_ijin').val();
            var time_akhir_ijin = $('#time_akhir_ijin').val();
            var total_time_ijin = $('#total_time_ijin').val();
            var tanggal_mulai = $('#tanggal_mulai_ijin_iks').val();
            var tanggal_mulai_ijin=tanggal_mulai.substr(6, 4)+'-'+tanggal_mulai.substr(3,2)+'-'+tanggal_mulai.substr(0,2);
            var tanggal_akhir = $('#tanggal_akhir_ijin_iks').val();
            var tanggal_akhir_ijin=tanggal_akhir.substr(6, 4)+'-'+tanggal_akhir.substr(3,2)+'-'+tanggal_akhir.substr(0,2);


            if (!enroll_id) {
                notif({
                    msg: "<b>Warning:</b> Anda belum memilih data karyawan.",
                    type: "warning"
                });
                return false;
            }

            if (!tanggal_perizinan) {
                notif({
                    msg: "<b>Warning:</b> Anda belum menginput Tanggal Perizinan.",
                    type: "warning"
                });
                return false;
            }

            if (!kode_absen_ijin) {
                notif({
                    msg: "<b>Warning:</b> Anda belum menginput Nama Izin.",
                    type: "warning"
                });
                return false;
            }

            if (!time_mulai_ijin) {
                notif({
                    msg: "<b>Warning:</b> Anda belum menginput Jam Mulai Izin.",
                    type: "warning"
                });
                return false;
            }

            if (!time_akhir_ijin) {
                notif({
                    msg: "<b>Warning:</b> Anda belum menginput Jam Akhir Izin.",
                    type: "warning"
                });
                return false;
            }

            if (!total_time_ijin) {
                notif({
                    msg: "<b>Warning:</b> Anda belum menginput Total Jam Izin.",
                    type: "warning"
                });
                return false;
            }
            if (!tanggal_mulai_ijin) {
                notif({
                    msg: "<b>Warning:</b> Anda belum menginput Tanggal Mulai Izin.",
                    type: "warning"
                });
                return false;
            }

            if (!tanggal_akhir_ijin) {
                notif({
                    msg: "<b>Warning:</b> Anda belum menginput Tanggal Akhir Izin.",
                    type: "warning"
                });
                return false;
            }

            var tanggal = tanggal_perizinan;
            if(kode_absen_ijin=='IKS'){
                // LAGI COBA TEST CLOSING PAYROLL
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
                    success: function(res){

                        if(res["ada"]) {
                            notif({
                                type: res["status"],
                                msg: res["message"],
                                position: "center",
                                width: 800,
                                height: 120,
                                opacity: 0.6,
                                autohide: false
                            });
                        } else {

                            $('#btn-save-izin').addClass("btn-loading");
                            $("#btn-save-izin").html('Please wait...');
                            $("#btn-save-izin").attr("disabled", true);
                            $('#progress-show-1').show();
                            $('#progress-hide-1').hide();

                            $.ajax({
                                type:"POST",
                                url: "{{route('hris.dataabsenperijinan.cekiks')}}",
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                data: {
                                    tanggal_perizinan:tanggal_perizinan,
                                    enroll_id:enroll_id,
                                },
                                dataType: 'json',
                                success: function(res){

                                    if (res > 0) {
                                        $.ajax({
                                            type:"POST",
                                            url: "{{route('cuti_karyawan.update_iks_menu_admin')}}",
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                            data: {
                                                uuid:uuid,
                                                tanggal_perizinan:null,
                                                nomor_form_perizinan:nomor_form_perizinan,
                                                enroll_id:enroll_id,
                                                nik:nik,
                                                employee_name:employee_name,
                                                kode_absen_ijin:kode_absen_ijin,
                                                absen_alasan:absen_alasan,
                                                time_mulai_ijin:time_mulai_ijin,
                                                time_akhir_ijin:time_akhir_ijin,
                                                total_time_ijin:total_time_ijin,
                                                tanggal_mulai_ijin:tanggal_mulai_ijin,
                                                tanggal_akhir_ijin:tanggal_akhir_ijin,
                                            },
                                            success: function(res){
                                                notif({
                                                    msg: "<b>Info:</b> Data berhasil di simpan.",
                                                    type: "success"
                                                });
                                            },
                                            error: function(res){
                                                notif({
                                                    msg: "<b>Error:</b> Oops data gagal di simpan.",
                                                    type: "error"
                                                });
                                            }
                                        });
                                    } else {
                                        $.ajax({
                                            type:"POST",
                                            url: "{{route('cuti_karyawan.create_iks_menu_admin')}}",
                                            dataType: 'json',
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                            data: {
                                                uuid:uuid,
                                                tanggal_perizinan:null,
                                                nomor_form_perizinan:nomor_form_perizinan,
                                                enroll_id:enroll_id,
                                                nik:nik,
                                                employee_name:employee_name,
                                                kode_absen_ijin:kode_absen_ijin,
                                                absen_alasan:absen_alasan,
                                                time_mulai_ijin:time_mulai_ijin,
                                                time_akhir_ijin:time_akhir_ijin,
                                                total_time_ijin:total_time_ijin,
                                                tanggal_mulai_ijin:tanggal_mulai_ijin,
                                                tanggal_akhir_ijin:tanggal_akhir_ijin,
                                            },
                                            dataType: 'json',
                                            success: function(res){
                                                notif({
                                                    msg: "<b>Info:</b> Data berhasil di simpan.",
                                                    type: "info"
                                                });
                                            },
                                            error: function(res){
                                                notif({
                                                    msg: "<b>Error:</b> Oops data gagal di simpan.",
                                                    type: "error"
                                                });
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

                            $('#progress-show-1').hide();
                            $('#progress-hide-1').show();
                            $('#btn-save-izin').removeClass("btn-loading");
                            $("#btn-save-izin").html('<span><i class="fa fa-save"></i></span> Save');
                            $("#form1 :input").prop("disabled", true);
                            $("#btn-save-izin").prop("disabled", true);
                            $("#btn-save-iks").prop("disabled", true);
                            $("#btn-cancel-izin").prop("disabled", true);
                            $("#btn-cancel-iks").prop("disabled", true);

                            setTimeout(function myFunction() {
                                location.reload();
                            }, 3000);

                        }

                    },
                    error: function(res){

                    }
                });
            } else {

                $('#btn-save-izin').addClass("btn-loading");
                $("#btn-save-izin").html('Please wait...');
                $("#btn-save-izin").attr("disabled", true);
                $('#progress-show-1').show();
                $('#progress-hide-1').hide();

                $.ajax({
                    type:"POST",
                    url: "{{route('cuti_karyawan.dataabsenperijinan.cek_dtpc')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        tanggal_perizinan:tanggal_perizinan,
                        enroll_id:enroll_id,
                    },
                    dataType: 'json',
                    success: function(res){
                        if (res > 0) {
                            $.ajax({
                                type:"POST",
                                url: "{{route('cuti_karyawan.dataabsenperijinan.update_dtpc_menu')}}",
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
                                    time_mulai_ijin:time_mulai_ijin,
                                    time_akhir_ijin:time_akhir_ijin,
                                    total_time_ijin:total_time_ijin,
                                    tanggal_mulai_ijin:tanggal_mulai_ijin,
                                    tanggal_akhir_ijin:tanggal_akhir_ijin,
                                },
                                success: function(res){
                                    notif({
                                        msg: "<b>Info:</b> Data berhasil di simpan.",
                                        type: "success"
                                    });
                                },
                                error: function(res){
                                    notif({
                                        msg: "<b>Error:</b> Oops data gagal di simpan.",
                                        type: "error"
                                    });
                                }
                            });
                        } else {
                            $.ajax({
                                type:"POST",
                                url: "{{route('cuti_karyawan.dataabsenperijinan.create_dtpc_menu')}}",
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
                                    time_mulai_ijin:time_mulai_ijin,
                                    time_akhir_ijin:time_akhir_ijin,
                                    total_time_ijin:total_time_ijin,
                                    tanggal_mulai_ijin:tanggal_mulai_ijin,
                                    tanggal_akhir_ijin:tanggal_akhir_ijin,
                                },
                                dataType: 'json',
                                success: function(res){
                                    notif({
                                        msg: "<b>Info:</b> Data berhasil di simpan.",
                                        type: "info"
                                    });
                                },
                                error: function(res){
                                    notif({
                                        msg: "<b>Error:</b> Oops data gagal di simpan.",
                                        type: "error"
                                    });
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

                $('#progress-show-1').hide();
                $('#progress-hide-1').show();
                $('#btn-save-izin').removeClass("btn-loading");
                $("#btn-save-izin").html('<span><i class="fa fa-save"></i></span> Save');
                $("#form1 :input").prop("disabled", true);
                $("#btn-save-izin").prop("disabled", true);
                $("#btn-save-iks").prop("disabled", true);
                $("#btn-cancel-izin").prop("disabled", true);
                $("#btn-cancel-iks").prop("disabled", true);

                setTimeout(function myFunction() {
                    location.reload();
                }, 3000);
            }
        });

    </script>

    <script>

        function searchData() {
            var selectEmployeeID = $('#selectEmployeeID').val();
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
