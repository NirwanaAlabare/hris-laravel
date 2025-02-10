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
            <li><a href="{{route('fls.index')}}">Form Lembur</a></li>
            <li class="active"><span>Penginputan Form Lembur</span></li>
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
                    <div class="modal-header bg-sb text-black">
                        <h1 class="modal-title fs-1">Scan QR Tambah Karyawan</h1>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">x</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
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
                                                <th>Line</th>
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
    <form id="form" name='form' method='post' action="{{ route('fls.store') }}"
        onsubmit="submitForm(this, event)">
        <div class="card card-sb">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center ">
                    <a href="{{ route('fls.index') }}" class="btn btn-sm btn-primary">
                        <i class="fa fa-reply"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @if ($user != 'admin 01')
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
                    @else
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><small><b>Tanggal Filter</b></small></label>
                                <input type="date" class="form-control" id="tgl_filter" name="tgl_filter"
                                    value="{{ date('Y-m-d') }}" onchange="dataTableReload()">
                                <input type="hidden" class="form-control" id="user" name="user"
                                    value="{{ $user }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><small><b>Tanggal Lembur</b></small></label>
                                <input type="date" class="form-control" id="tgl_lembur" name="tgl_lembur"
                                    value="{{ date('Y-m-d') }}" onchange="dataTableReload()">
                            </div>
                        </div>
                    @endif
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><small><b>Line</b></small></label>
                            <select class='form-control select2' style='width: 100%;' name='cboline' id='cboline'
                                onchange="dataTableReload()" required>
                                <option selected="selected" value="" disabled="true">Pilih Line</option>
                                @foreach ($data_line as $dataline)
                                    <option value="{{ $dataline->isi }}">
                                        {{ $dataline->tampil }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <label><small><b>Keterangan</b></small></label>
                            <select class='form-control select2' multiple style='width: 100%;' name='cboket[]' id='cboket'
                                 required>
                                @foreach ($data_ket as $dataket)
                                    <option value="{{ $dataket->isi }}">
                                        {{ $dataket->tampil }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><small><b>Dari</b></small></label>
                            <input class="form-control" id="from_lembur" name="from_lembur" type="time" onchange='sum();' required style="background-color: white; cursor:pointer;" >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><small><b>Sampai</b></small></label>
                            <input class="form-control" id="to_lembur" name="to_lembur" type="time" onchange='sum();autominute();' required style="background-color: white; cursor:pointer;" >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label><small><b>Istirahat</b></small></label>
                        <div class="input-group mb-3">
                            <input type="number" class="form-control " name="txtistirahat" id="txtistirahat"
                                min = "0" oninput='sum()'>
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
                        <h5 class="card-title"><i class="fas fa-list"></i> List Karyawan</h5>
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
                                        <th>Status</th>
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
                                {{-- <a class="btn btn-outline-success" onclick="simpan()">
                                <i class="fas fa-check"></i>
                                Simpan
                            </a> --}}
                                <button type="submit" class="btn btn-outline-success">Simpan </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection


@section('footerjs')
<script src="{{URL::asset('assets/js/script.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{URL::asset('assets/js/iziToast.min.js')}}"></script>
<script src="{{ asset('assets/plugins/html5-qrcode/html5-qrcode.min.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

    <style>
        .checkbox-xl .form-check-input {
            scale: 1.5;
        }
    </style>
    <script>

        var html5QrcodeScanner = null;

        $("#from_lembur").timepicker({
          timeFormat: "%H:%i"
        });
        $("#to_lembur").timepicker({
          timeFormat: "%H:%i"
        });

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
            let cboline = document.form.cboline.value;
            if (cboline == '') {
                $('#exampleModal').modal('hide');
                iziToast.error({
                    message: 'Line masih kosong, Silahkan pilih line lebih dahulu',
                    position: 'topCenter'
                });
            } else {
                $('#exampleModal').modal('show');
                dataTableModalReload();
            }
        }

        $(document).ready(function() {
            $("#cboline").val('').trigger('change');
            $("#from_lembur").val('');
            $("#to_lembur").val('');
            $("#jml_lembur").val('');
            $("#txtistirahat").val(0);
            $("#cboket").val('').trigger('change');
            del_tmp();
        })

        function scan_qr() {
            let txtqr = document.form_modal.txtqr.value;
            let cboline = document.form.cboline.value;
            let tgl_lembur = document.form.tgl_lembur.value;
            let tgl_filter = document.form.tgl_filter.value;
            let html = $.ajax({
                type: "get",
                url: '{{ route('fls.cek_data_karyawan_tmp') }}',
                data: {
                    txtqr: txtqr,
                    cboline: cboline,
                    tgl_lembur: tgl_lembur,
                    tgl_filter: tgl_filter
                },
                success: function(response) {
                    console.log(response.cek);
                    $.ajax({
                        type: "post",
                        url: '{{ route('fls.store_data_karyawan_tmp') }}',
                        data: {
                            txtqr: txtqr,
                            cboline: cboline,
                            tgl_lembur: tgl_lembur,
                            tgl_filter: tgl_filter
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
            let to_lembur = document.getElementById('to_lembur').value;
            if (to_lembur >= '18:01' && to_lembur <= '23:59') {
                $('#txtistirahat').val(30);
            } else if (to_lembur >= '00:00' && to_lembur <= '06:59') {
                $('#txtistirahat').val(30);
            } else {
                $('#txtistirahat').val(0);
            }
            sum();
            console.log(to_lembur);
        }

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
                    url: '{{ route('fls.show_list_karyawan') }}',
                    data: function(d) {
                        d.cboline = $('#cboline').val();
                        d.tgl_filter = $('#tgl_filter').val();
                        d.tgl_lembur = $('#tgl_lembur').val();
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
                        data: 'nm_karyawan'
                    },
                    {
                        data: 'nik'
                    },
                    {
                        data: 'status_jabatan'
                    },
                    {
                        data: 'stat'
                    },
                ],
                columnDefs: [{
                    targets: [1],
                    render: (data, type, row, meta) => {
                        return `
                    <div
                        class="form-check checkbox-xl" style="text-align:center">
                        <input class="form-check-input" type="checkbox"
                        value="` + row.enroll_id + `" id="cek_data" onchange="ceklis(this)"
                        name="cek_data[` + row.enroll_id + `] "/>
                    </div>
                    <div>
                            <input type="hidden" size="10" id="enroll_id"
                            name="enroll_id[` + row.enroll_id + `]" value = "` + row.enroll_id + `"/>
                    </div>
                    <div>
                            <input type="hidden" size="10" id="stat"
                            name="stat[` + row.enroll_id + `]" value = "` + row.stat + `"/>
                    </div>
                    `;
                    }
                }, ]
            });
        }

        function dataTableModalReload() {
            let datatable = $("#datatable_modal").DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                paging: false,
                searching: true,
                destroy: true,
                ajax: {
                    url: '{{ route('fls.show_list_karyawan_tmp') }}',
                    data: function(d) {
                        d.user = $('#user').val();
                        d.tgl_filter = $('#tgl_filter').val();
                        d.tgl_lembur = $('#tgl_lembur').val();
                        d.line = $('#cboline').val();
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
                        data: 'nm_karyawan'
                    },
                    {
                        data: 'nik'
                    },
                    {
                        data: 'status_jabatan'
                    },
                    {
                        data: 'stat'
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
                    targets: [8],
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
            let cboline = document.form.cboline.value;
            let tgl_lembur = document.form.tgl_lembur.value;
            console.log(id_tmp);
            $.ajax({
                type: "post",
                url: '{{ route('fls.hapus_data_karyawan_tmp') }}',
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
                url: '{{ route('fls.del_tmp') }}',
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
                console.log(checkeds);
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
                        }
                    }
                });
            }
        }
    </script>

@endsection
