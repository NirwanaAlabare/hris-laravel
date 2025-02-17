@extends('admin.adminlayouts.adminlayout-mut-karyawan')

@section('head')
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.css')}}" rel="stylesheet" />

@stop
@section('mainarea')

    <div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
        <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
            <li><a href="{{route('fls.index')}}">Mutasi Karyawan</a></li>
            <li class="active"><span>Perpindahan Karyawan</span></li>
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
    <form action="{{ route('store-mut-karyawan') }}" method="post" id="store-mut-karyawan" name='form'
        onsubmit="submitMutKaryawanForm(this, event)">
        @csrf
        <div class="card card-info">
            <div class="card-header text-white bg-gradient-primary py-2">
                <div class="card-title">PERPINDAHAN KARYAWAN</div>
                <div class="card-options ">
                </div>
            </div>
            <div class="card-body">
                <div class="row justify-content-center align-items-end">
                    <div class="col-2">
                        <div></div>
                    </div>
                    <div class="col-8">
                        <div id="reader"></div>
                    </div>
                    <div class="col-2">
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="mb-3">
                                <label class="form-label label-scan"><small><b>Bagian</b></small></label>
                                <select class="form-control" name="txtline" id="txtline" onchange="scanline()" autocomplete="off" enterkeyhint="go" autofocus>
                                    <option value="">-- PILIH BAGIAN --</option>
                                    @foreach ($departments as $dept)
                                    <option value="{{$dept->sub_dept_name}}">{{$dept->sub_dept_name}}</option>
                                @endforeach
                                </select>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="mb-3">
                            <label class="form-label label-scan"><small><b>Nama Bagian</b></small></label>
                            <input type="text" class="form-control form-control-md border-scan" name="nm_line"
                                id="nm_line" readonly>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="mb-3">
                            <label class="form-label label-scan"><small><b>Jumlah Orang</b></small></label>
                            <input type="text" class="form-control form-control-mb border-scan" name="jml_org"
                                id="jml_org" readonly>
                            <input type="hidden" class="form-control form-control-sm border-scan" name="nm_karyawan"
                                id="nm_karyawan" readonly>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label label-input"><small><b>Scan QR</b></small></label>
                            <div class="input-group">
                                @if ($username == 'HR' || $username == 'IT')
                                <input type="text" class="form-control form-control-sm border-input" name="txtenroll_id"
                                    id="txtenroll_id" autocomplete="off" enterkeyhint="go"
                                    onkeyup="if (event.keyCode == 13)
                                    document.getElementById('scan_nik').click()
                                ">
                                @else
                                <input type="text" class="form-control form-control-sm border-input" name="txtenroll_id"
                                    id="txtenroll_id" autocomplete="off" enterkeyhint="go"
                                    onkeyup="if (event.keyCode == 13)
                                    document.getElementById('scan_nik').click()
                                " readonly>
                                @endif
                                <button class="btn btn-sm btn-warning" type="button" id="scan_nik"
                                    onclick="scannik();">Scan</button>
                                <input type="hidden" class="form-control form-control-sm border-scan" name="nik"
                                    id="nik" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div></div>
                    </div>
                    <div class="col-8">
                        <div id="reader_nik"></div>
                    </div>
                    <div class="col-2">
                    </div>
                </div>
            </div>
    </form>
    <div class="card card-primary">
            <div class="card-header text-white bg-gradient-primary py-2">
                <div class="card-title">LIST KARYAWAN</div>
                <div class="card-options ">
                </div>
            </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatable" class="table table-bordered table-sm w-100 display nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tgl</th>
                            <th>Line</th>
                            <th>Jam Absen</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Line Asal</th>
                            <th>Update Terakhir</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('footerjs')
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{ asset('assets/plugins/html5-qrcode/html5-qrcode.min.js') }}"></script>
<script src="{{URL::asset('assets/js/script.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });

    $('.select2').select2()


    var html5QrcodeScanner = null;

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

                document.getElementById('txtline').value = decodedText;

                scanline();

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
                        width: 250,
                        height: 250
                    }
                },
                /* verbose= */
                false);

            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        }
    }


    var html5QrcodeScanner1 = null;

    // -Initialize Scanner-
    async function initScan1() {
        if (document.getElementById("reader_nik")) {
            if (html5QrcodeScanner) {
                await html5QrcodeScanner.clear();
            }

            if (html5QrcodeScanner1) {
                await html5QrcodeScanner1.clear();
            }

            function onScanSuccess(decodedText, decodedResult) {
                // handle the scanned code as you like, for example:
                console.log(`Code matched = ${decodedText}`, decodedResult);

                // store to input text
                // let breakDecodedText = decodedText.split('-');

                document.getElementById('txtenroll_id').value = decodedText;

                scannik();

                html5QrcodeScanner1.clear();
                // await initScan1();

            }

            function onScanFailure(error) {
                // handle scan failure, usually better to ignore and keep scanning.
                // for example:
                console.warn(`Code scan error = ${error}`);
            }

            html5QrcodeScanner1 = new Html5QrcodeScanner(
                "reader_nik", {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    }
                },
                /* verbose= */
                false);

            await html5QrcodeScanner1.render(onScanSuccess, onScanFailure);

        }
    }

</script>
<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
        $("#nm_line").val('');
        $("#jml_org").val('');
        initScan();

    })

    $(document).ready(function() {
        if (window.location.reload) {
            dataTableReload();
        }
    });


    window.addEventListener("focus", () => {
        dataTableReload();
    });

    let datatable = $("#datatable").DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        info: false,
        paging: false,
        scrollX: false,
        ajax: {
            url: '{{ route('getdatalinekaryawan') }}',
            data: function(d) {
                d.nm_line = $('#nm_line').val();
            },
        },
        "fnCreatedRow": function(row, data, index) {
            $('td', row).eq(0).html(index + 1);
        },
        columns: [{
                data: 'tgl_pindah'
            }, {
                data: 'tgl_pindah_fix'
            },
            {
                data: 'line'
            },
            {
                data: 'absen_masuk_kerja'
            },
            {
                data: 'nik'
            },
            {
                data: 'nama_karyawan'
            },
            {
                data: 'line_asal',
                render: function(data, type, row) {
                    return data ? data : '-';
                }
            },
            {
                data: 'tgl_update_fix'
            }
        ],
        columnDefs: [{
            targets: '_all',
            render: (data, type, row, meta) => {
                var color = 'black';
                if (row.absen_masuk_kerja == null) {
                    color = 'red';
                } else {
                    color = 'green';
                }
                return '<span style="color:' + color + '">' + data + '</span>';
            }
        }],

    });

    function scanline() {
        let txtline = document.form.txtline.value;
        if (txtline == '') {
            return
            Swal.fire({
                icon: 'error',
                title: 'Data Line Tidak Terdaftar',
                showConfirmButton: true,
                showCancelButton: false,
            })
        }
        let html = $.ajax({
            type: "get",
            url: '{{ route('getdataline') }}',
            data: {
                txtline: txtline
            },
            dataType: 'json',
            success: function(response) {
                document.getElementById('nm_line').value = response.nm_line;
                // document.getElementById('jml_org').value = response.urutan;
                $("#txtline").prop("disabled", true);
                document.getElementById('txtenroll_id').focus();
                gettotal();
                // updatelist();
                // Reload Order Qty Datatable

                setTimeout(() => {
                    initScan1();
                }, 2000);

                datatable.ajax.reload();
            },
            error: function(request, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Data Line Tidak Terdaftar',
                    showConfirmButton: true,
                    showCancelButton: false,

                })
                setTimeout(() => {
                    initScan();
                }, 2000);
                $("#txtline").val('');
                // alert(request.responseText);
            },
        });
    };

    function gettotal() {
        let nm_line = $("#nm_line").val();
        let html = $.ajax({
            type: "get",
            url: '{{ route('gettotal') }}',
            data: {
                nm_line: nm_line
            },
            dataType: 'json',
            success: function(response) {
                document.getElementById('jml_org').value = response.total;
            },
            error: function(request, status, error) {
                alert(request.responseText);
            },
        });
    };


    function scannik() {
        let nm_line = $("#nm_line").val();
        let txtenroll_id = document.form.txtenroll_id.value;
        let html = $.ajax({
            type: "get",
            url: '{{ route('getdatanik') }}',
            data: {
                txtenroll_id: txtenroll_id
            },
            dataType: 'json',
            success: function(response) {
                document.getElementById('nik').value = response.nik;
                document.getElementById('nm_karyawan').value = response.employee_name;
                let nm_karyawan = $("#nm_karyawan").val();
                let nik = $("#nik").val();
                $.ajax({
                    type: "post",
                    url: '{{ route('store-mut-karyawan') }}',
                    data: {
                        txtenroll_id: txtenroll_id,
                        nm_line: nm_line,
                        nik: nik,
                        nm_karyawan: nm_karyawan
                    },
                    success: async function(res) {
                        await Swal.fire({
                            icon: res.icon,
                            title: res.msg,
                            html: "NIK : " + response.nik + "<br/>" +
                                "Nama :" + response.employee_name,
                            // html: "NIK :" + $("#txtnik").val(),
                            showCancelButton: false,
                            showConfirmButton: true,
                            timer: res.timer,
                            timerProgressBar: res.prog
                        })
                        document.getElementById('txtenroll_id').focus();
                        datatable.ajax.reload();
                        gettotal();
                        $("#nik").val('');
                        $("#nm_karyawan").val('');
                        $("#txtenroll_id").val('');
                        initScan1();
                    },
                    error: function (jqXHR) {
                        console.error(jqXHR);
                    }
                });
            },
            error: function(request, status, error) {
                // alert(request.responseText);
                Swal.fire({
                    icon: 'warning',
                    title: 'Data NIK Tidak Terdaftar Silahkan hubungi Department HRD',
                    showConfirmButton: true,
                })
                document.getElementById('txtenroll_id').focus();
                $("#nik").val('');
                $("#nm_karyawan").val('');
                $("#txtenroll_id").val('');
                initScan1();

            },
        });
    };

    function dataTableReload() {
        datatable.ajax.reload();
    }
</script>
@endsection

