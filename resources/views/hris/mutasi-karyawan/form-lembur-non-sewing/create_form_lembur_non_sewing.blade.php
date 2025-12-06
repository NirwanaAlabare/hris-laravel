@extends('admin.adminlayouts.adminlayout-mut-karyawan')

@section('head')
    <link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
@stop
@section('mainarea')
<?php ini_set('date.timezone', 'Asia/Jakarta'); ?>
    <div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
        <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
            <li><a href="{{route('flns.index')}}">Form Lembur</a></li>
            <li class="active"><span>Penginputan Form Lembur Non Sewing</span></li>
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
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <form action="#" method="post" onsubmit="submitForm(this, event)" name='form_modal' id='form_modal'>
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-sb">
                        <h1 class="modal-title fs-1 text-black">Scan QR Tambah Karyawan Non Sewing</h1>
                        <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close">x</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label class="form-label label-input">Department :</label>
                                        <label id = "dep_name"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label class="form-label label-input">Scan QR</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm border-input"
                                                name="txtqr" id="txtqr" autocomplete="off" enterkeyhint="go"
                                                onkeyup="if (event.keyCode == 13)
                                                document.getElementById('scanqr').click()"
                                                autofocus>
                                            <button class="btn btn-sm btn-primary" type="button" id="scanqr"
                                                onclick="scan_qr()">Scan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div id="reader"></div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                </div>
                            </div>
                        </div>
                        <div class = "row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table id="datatable_modal" class="table table-bordered table-sm w-100 table-hover">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>No.</th>
                                                <th>ID</th>
                                                <th>Nama Karyawan</th>
                                                <th>NIK</th>
                                                <th>Jabatan</th>
                                                <th>Department</th>
                                                <th>Sub Dept Name</th>
                                                <th>Absen In</th>
                                                <th>Absen Out</th>
                                                <th>Act</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <form id="form" name='form' method='post' action="{{ route('flns.store') }}"
        onsubmit="submitForm(this, event)">
        @csrf
        <div class="card card-sb">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center ">
                    <a href="{{ route('flns.index') }}" class="btn btn-sm btn-primary">
                        <i class="fa fa-reply"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><small><b>Tanggal Lembur</b></small></label>
                            <input type="date" class="form-control" id="tgl_lembur" name="tgl_lembur"
                                value="{{ date('Y-m-d') }}" onchange="dataTableReload()">
                            <input type="hidden" class="form-control" id="tgl_filter" name="tgl_filter"
                                value="{{ date('Y-m-d') }}" onchange="dataTableReload()" readonly>
                            <input type="hidden" class="form-control" id="user" name="user"
                                value="{{ $user }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><small><b>Department</b></small></label>
                            <select class='form-control select2' style='width: 100%;' name='cbodept' id='cbodept'
                                onchange="dataTableReload();getdeptname();" required>
                                <option selected="selected" value="" disabled="true">Pilih Department</option>
                                @foreach ($data_dept as $datadept)
                                    <option value="{{ $datadept->isi }}">
                                        {{ $datadept->tampil }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" class="form-control" id="cbodept_name" name="cbodept_name"
                                autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group mb-0">
                            <label><small><b>Keterangan</b></small></label>
                            <input type="text" class="form-control" id="txtket" name="txtket" autocomplete="off">
                            <a href="#" onclick="fillallfields()" style="font-size: 8pt;font-weight:bold"><u>APPLY TO ALL</u></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><small><b>Dari</b></small></label>
                            <input class="form-control" id="from_lembur" name="from_lembur" type="text" onchange='sum();' required style="background-color: white; cursor:pointer;">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><small><b>Sampai</b></small></label>
                            <input class="form-control" id="to_lembur" name="to_lembur" type="text" onchange='sum();autominute();' required style="background-color: white; cursor:pointer;">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label><small><b>Istirahat</b></small></label>
                        <div class="input-group mb-3">
                            <input type="number" class="form-control " name="txtistirahat" id="txtistirahat" readonly
                                oninput='sum()'>
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Menit</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><small><b>Jumlah Jam</b></small></label>
                            <input type="text" class="form-control" id="jml_lembur" name="jml_lembur" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fa fa-users"></i> List Karyawan</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <a class="btn btn-outline-primary position-relative" onclick="reset();cek_filter_modal();">
                                <i class="fas fa-qrcode"></i>
                                Scan Data
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-sm w-100 table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No.</th>
                                        <th>
                                            <input type="checkbox" onclick="toggle(this);">
                                        </th>
                                        <th>ID</th>
                                        <th>Nama Karyawan</th>
                                        <th>NIK</th>
                                        <th>Jabatan</th>
                                        <th>Department</th>
                                        <th>Sub Dept</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="p-2 bd-highlight">
                                <a class="btn btn-outline-warning" onclick="undo()">
                                    <i class="fas fa-sync-alt
                                fa-spin"></i>
                                    Undo
                                </a>
                            </div>
                            <div class="p-2 bd-highlight">
                                <button type="submit" class="btn btn-outline-success" id="submitBtn" onclick="disableButton(this)">Simpan </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
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
    {{-- <script src="{{URL::asset('assets/js/timepicker.js') }}"></script> --}}
    <style>
    .checkbox-xl .form-check-input {
        scale: 1.5;
    }
    </style>
    <script>
        function submitForm(form, event) {
            event.preventDefault();  // Prevent default form submission
            // Disable the submit button while the form is being submitted
            document.getElementById('submitBtn').disabled = true;

            document.getElementById('submitBtn').innerText = 'Menyimpan...';  // Optional, you can update text

            // Perform the form submission using fetch
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // CSRF token
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 200) {
                    // Menampilkan SweetAlert sukses
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses!',
                        html: data.message, // gunakan html agar <br> muncul
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Arahkan ke halaman index setelah sukses
                        window.location.href = "{{ route('flns.index') }}"; // Redirect ke halaman index
                    });
                } else {
                    // Menampilkan SweetAlert error
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        html: data.message || 'Terjadi kesalahan!',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Enable kembali tombol submit
                        document.getElementById('submitBtn').disabled = false;
                        document.getElementById('submitBtn').innerText = 'Simpan';  // Reset text
                    });
                }
            })
            .catch(error => {
                // Tangani error jika terjadi
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat mengirim data!'
                }).then(() => {
                    // Enable kembali tombol submit
                    document.getElementById('submitBtn').disabled = false;
                    document.getElementById('submitBtn').innerText = 'Simpan';  // Reset text
                });
            });
        }
        // function disableButton(btn) {
        //     btn.disabled = true;
        //     btn.innerHTML = 'Menyimpan...'; // Optional
        //     btn.form.submit();
        // }
        // Scan QR Module :
        // Variable List :
        var html5QrcodeScanner = null;

        $("#from_lembur").timepicker({
          timeFormat: "%H:%i"
        });
        $("#to_lembur").timepicker({
          timeFormat: "%H:%i"
        });
        // Function List :
        // -Initialize Scanner-
        async function initScan() {
            if (document.getElementById("reader")) {
                if (html5QrcodeScanner) {
                    await html5QrcodeScanner.clear();
                }

                function onScanSuccess(decodedText, decodedResult) {
                    // handle the scanned code as you like, for example:
                    console.log(`Code matched = ${decodedText}`, decodedResult);

                    // store to input text
                    // let breakDecodedText = decodedText.split('-');

                    document.getElementById('txtqr').value = decodedText;

                    scan_qr();

                    html5QrcodeScanner.clear();

                }

                function onScanFailure(error) {
                    // handle scan failure, usually better to ignore and keep scanning.
                    // for example:
                    console.warn(`Code scan error = ${error}`);
                }

                html5QrcodeScanner = new Html5QrcodeScanner(
                    "reader", {
                        fps: 10,
                        qrbox: {
                            width: 200,
                            height: 200
                        }
                    },
                    /* verbose= */
                    false);


                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
            }
        }
    </script>
    <script>
        function toggle(source) {
            var checkboxes = document.querySelectorAll('input[type="checkbox"]');
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i] != source)
                    checkboxes[i].checked = source.checked;
                    ceklis(checkboxes[i]);
            }
        }
    </script>
    <script>
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });

        $('.select2').select2();
    </script>
    <script>
        function notif() {
            alert("Maaf, Fitur belum tersedia!");
        }

        function undo() {
            location.reload();
        }

        function reset() {
            $("#form_modal").trigger("reset");
            initScan();
        }

        function cek_filter_modal() {
            let cbodept = document.form.cbodept.value;
            if (cbodept == '') {
                $('#exampleModal').modal('hide');
                iziToast.error({
                    message: 'Departemen masih kosong, Silahkan pilih Departemen lebih dahulu',
                    position: 'topCenter'
                });
            } else {
                $('#exampleModal').modal('show');
                dataTableModalReload();
            }
        }

        $(document).ready(function() {
            $("#cbodept").val('').trigger('change');
            $("#from_lembur").val('');
            $("#to_lembur").val('');
            $("#jml_lembur").val('');
            $("#txtket").val('');
            $("#txtistirahat").val(0);
            del_tmp();
        })

        function scan_qr() {
            let txtqr = document.form_modal.txtqr.value;
            let cbodept = document.form.cbodept.value;
            let tgl_lembur = document.form.tgl_lembur.value;
            let html = $.ajax({
                type: "get",
                url: '{{ route('flns.cek_data_karyawan_tmp_non_sewing') }}',
                data: {
                    txtqr: txtqr,
                    cbodept: cbodept,
                    tgl_lembur: tgl_lembur
                },
                success: function(response) {
                        if(response.cek){
                            $.ajax({
                            type: "post",
                            url: '{{ route('flns.store_data_karyawan_tmp_non_sewing') }}',
                            data: {
                                txtqr: response.cek,
                                cbodept: cbodept
                            },
                            success: async function(res) {
                                iziToast.success({
                                    message: 'Data Berhasil Ditambahkan',
                                    position: 'topCenter'
                                });
                                document.getElementById('txtqr').focus();
                                initScan();
                                $("#txtqr").val('');
                                dataTableModalReload();
                                dataTableReload();
                            }
                        });
                    }
                },
                error: function(request, status, error) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Tidak Ada',
                        showConfirmButton: true,
                    })
                    $("#txtqr").val('');
                    document.getElementById('txtqr').focus();
                    dataTableModalReload();
                    dataTableReload();
                    initScan();
                },
            });
        };

        function autominute() {
        let from = document.getElementById('from_lembur').value; // waktu mulai
        let to = document.getElementById('to_lembur').value; // waktu selesai

        // Convert HH:MM ke menit
        function toMinute(t) {
            let [h, m] = t.split(':').map(Number);
            return h * 60 + m;
        }

        let start = toMinute(from);
        let end = toMinute(to);

        let istirahat = 0;

        // Cek lewat jam 12:00 - 13:00
        let IstirahatSiangStart = 12 * 60;
        let IstirahatSiangEnd = 13 * 60;

        if (end > IstirahatSiangStart && start < IstirahatSiangEnd) {
            istirahat += 60;
        }

        // Cek lewat jam 18:00
        let batasMaghrib = 18 * 60;
        if (end > batasMaghrib) {
            istirahat += 30;
        }

        $('#txtistirahat').val(istirahat);

        sum();
    }

        // function autominute() {
        //     let to_lembur = document.getElementById('to_lembur').value;
        //     if (to_lembur >= '18:01' && to_lembur <= '23:59') {
        //         $('#txtistirahat').val(30);
        //     } else if (to_lembur >= '00:00' && to_lembur <= '06:59') {
        //         $('#txtistirahat').val(30);
        //     } else {
        //         $('#txtistirahat').val(0);
        //     }
        //     sum();
        //     console.log(to_lembur);
        // }

        function sum() {
            const from = document.getElementById('from_lembur').value;
            const [hours, minutes] = from.split(':');
            // const totalSecondsfrom = (+hours) * 60 * 60 + (+minutes);
            const totalSecondsfrom = (+hours) * 60 + (+minutes);

            // console.log(totalSecondsfrom);

            const to = document.getElementById('to_lembur').value;
            const [hoursto, minutesto] = to.split(':');
            // const totalSecondsto = (+hoursto) * 60 * 60 + (+minutesto);
            const totalSecondsto = (+hoursto) * 60 + (+minutesto);

            if (totalSecondsfrom > totalSecondsto) {
                x = 1440 - parseFloat(totalSecondsfrom) + parseFloat(totalSecondsto);
            } else {
                x = parseFloat(totalSecondsto) - parseFloat(totalSecondsfrom);
            }
            // console.log(x);
            // console.log(totalSecondsfrom);

            let x_fix = x - document.getElementById("txtistirahat").value;


            let jam = x / 60;

            y = x % 3600;
            jam = x / 3600;
            menit = y / 60;

            let h = Math.floor(x_fix / 60);
            let m = x_fix % 60;

            // console.log(x);


            // result.innerHTML = Math.floor(jam) + ' Jam ' + Math.floor(menit) + ' Menit ';
            // document.getElementById("jml_lembur").value = Math.floor(jam) + ' Jam ' + Math.floor(y) + ' Menit ';
            document.getElementById("jml_lembur").value = Math.floor(h) + ' Jam ' + Math.floor(m) + ' Menit ';


            // document.getElementById("jml_lembur").value = hour;

            // console.log(from.split(':'));

            // let result_fix = Math.ceil(result)
            // if (!isNaN(result)) {
            //     document.getElementById("jml_lembur").value = result_fix;
            // }
        }

        function dataTableReload() {
            let datatable = $("#datatable").DataTable({
                processing: true,
                paging: false,
                searching: true,
                ordering: false,
                destroy: true,
                ajax: {
                    url: '{{ route('flns.show_list_karyawan_non_sewing') }}',
                    data: function(d) {
                        d.cbodept = $('#cbodept').val();
                    },
                },
                "fnCreatedRow": function(row, data, index) {
                    $('td', row).eq(0).html(index + 1);
                },
                columns: [{
                        data: 'enroll_id'

                    }, {
                        data: 'enroll_id'
                    },
                    {
                        data: 'enroll_id'
                    },
                    {
                        data: 'employee_name'
                    },
                    {
                        data: 'nik'
                    },
                    {
                        data: 'status_jabatan'
                    },
                    {
                        data: 'department_name'
                    },
                    {
                        data: 'sub_dept_name'
                    },
                    {
                        data: 'status'
                    }, {
                        data: 'enroll_id'
                    },
                ],
                columnDefs: [{
                    targets: [1],
                    render: (data, type, row, meta) => {
                        return `
                    <div
                        class="form-check checkbox-xl" style="text-align:center">
                        <input class="form-check-input checklist_`+row.enroll_id+`" type="checkbox"
                        value="` + row.enroll_id + `" id="cek_data" onchange="ceklis(this)"
                        name="cek_data[` + row.enroll_id + `] "/>
                    </div>
                    <div>
                            <input type="hidden" size="10" id="enroll_id"
                            name="enroll_id[` + row.enroll_id + `]" value = "` + row.enroll_id + `"/>
                            <input type="hidden" size="10" id="status"
                            name="status[` + row.enroll_id + `]" value = "` + row.status + `"/>
                    </div>
                    `;
                    }
                },{
                    targets: [9],
                    render: (data, type, row, meta) => {
                        return `
                        <input type="text" class="form-control" placeholder="Masukkan keterangan disini" id="keterangan_`+row.enroll_id+`" name="keterangan[` + row.enroll_id + `]">
                    `;
                    }
                } ]
            });
        }

        function fillallfields(){
            var keterangan=$('#txtket').val();
            $(".keterangan").each(function(){
                $(this).val(keterangan);
            })

        }

        function getdeptname() {
            let sub_dept_id = $('#cbodept').val();
            let html = $.ajax({
                type: "get",
                url: '{{ route('flns.getdept_name') }}',
                data: {
                    sub_dept_id: sub_dept_id
                },
                dataType: 'json',
                success: function(response) {
                    $("#dep_name").html(response.dept_name);
                    $("#cbodept_name").val(response.sub_dept_name);
                },
            });
        }

        function dataTableModalReload() {
            // $("#dep_name").html($('#cbodept').val());
            getdeptname();
            let datatable = $("#datatable_modal").DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                paging: false,
                searching: true,
                destroy: true,
                ajax: {
                    url: '{{ route('flns.show_list_karyawan_tmp_non_sewing') }}',
                    data: function(d) {
                        d.user = $('#user').val();
                        d.dept = $('#cbodept').val();
                        d.tgl = $('#tgl_lembur').val();
                    },
                },
                "fnCreatedRow": function(row, data, index) {
                    $('td', row).eq(0).html(index + 1);
                },
                columns: [{
                        data: 'enroll_id'

                    }, {
                        data: 'enroll_id'
                    },
                    {
                        data: 'employee_name'
                    },
                    {
                        data: 'nik'
                    },
                    {
                        data: 'status_jabatan'
                    },
                    {
                        data: 'department_name'
                    },
                    {
                        data: 'sub_dept_name'
                    },
                    {
                        data: 'absen_masuk_kerja'
                    },
                    {
                        data: 'absen_pulang_kerja'
                    },
                    {
                        data: 'id_tmp'
                    },
                ],
                columnDefs: [{
                    targets: [9],
                    render: (data, type, row, meta) => {
                        return `
                    <div>
                    <a style="text-align:center" class='btn btn-danger btn-sm' onclick="hapus('` + row.id_tmp + `');">
                    <i class='fa fa-trash'></i>
                    </a>
                    </div>
                    `;
                    }
                }, ]
            });
        }

        function hapus(a) {
            let id_tmp = a;
            let cbodept = document.form.cbodept.value;
            let tgl_lembur = document.form.tgl_lembur.value;
            console.log(id_tmp);
            $.ajax({
                type: "post",
                url: '{{ route('flns.hapus_data_karyawan_tmp_non_sewing') }}',
                data: {
                    id_tmp: id_tmp
                },
                success: async function(res) {
                    iziToast.success({
                        message: 'Data Berhasil Dihapus',
                        position: 'topCenter'
                    });
                    document.getElementById('txtqr').focus();
                    initScan();
                    $("#txtqr").val('');
                    dataTableModalReload();
                    dataTableReload();
                }
            });

        }

        function del_tmp() {
            let user = $('#user').val();
            $.ajax({
                type: "post",
                url: '{{ route('flns.del_tmp_non_sewing') }}',
                data: {
                    user: user
                },
            });

        }

        function ceklis(checkeds) {
            //get id..and check if checked
            var tgl_lembur=$("#tgl_lembur").val();
            var id=checkeds.value;
            if(checkeds.checked==true){
                $.ajax({
                    type: "post",
                    url: '{{ route('fls.cek_data_lembur') }}',
                    data: {
                        id: id,
                        tgl_lembur: tgl_lembur
                    },
                    success: function(res) {
                        if(res>0){
                            iziToast.error({
                                message: 'Karyawan sudah ada',
                                position: 'center'
                            });
                            $('.checklist_'+id).prop( "checked", false );
                            const element = $('#keterangan_'+id);  // Get the DIV element
                            element.classList.remove("form-control keterangan"); // Remove mystyle class from DIV
                            element.classList.add("form-control");
                        }else{
                            $('#keterangan_'+id).attr('class', 'form-control keterangan');
                        }
                    }
                });
            }else{
                // $('#amount_'+enroll_id).attr("disabled", 'disabled');
            }
            // console.log($(checkeds).attr("value"), checkeds.checked)

        }
    </script>
@endsection
