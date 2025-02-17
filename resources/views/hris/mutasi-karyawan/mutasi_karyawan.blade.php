@extends('admin.adminlayouts.adminlayout-mut-karyawan')

@section('head')
    <link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::asset('assets/css/iziToast.min.css') }}">


    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
        .button-lg {
        font-size: 1.125rem; /* Large size equivalent */
        padding: 0.45rem 1.5rem;
        border: none;
        border-radius: 0.375rem; /* Rounded corners */
        background: linear-gradient(to right, #2563eb, #3b82f6);
        color: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        width: 100%;
        }

        .button-lg:hover {
        background: linear-gradient(to right, #1d4ed8, #2563eb);
        box-shadow: 0px 6px 10px rgba(59, 130, 246, 0.25);
        }

        /* Shine effect */
        .shine-effect {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.2);
        transform: translateX(-100%);
        animation: shine 1s ease-in forwards;
        z-index: 0;
        }

        .button-lg:hover .shine-effect {
        animation: shine 1s ease-in;
        }

        /* Icon */
        .icon-scan {
        width: 1.25rem;
        height: 1.25rem;
        margin-right: 0.5rem;
        z-index: 1;
        animation: pulse 2s infinite;
        }

        /* Shine animation */
        @keyframes shine {
        from {
            transform: translateX(-100%);
        }
        to {
            transform: translateX(100%);
        }
        }

        /* Pulse animation */
        @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
        }

    </style>

@stop
@section('mainarea')

    <form id="form_m" name='form_m' method='post'>
        <div class="modal fade" id="exampleModalSet" tabindex="-1" role="dialog" aria-labelledby="exampleModalSetLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable" style="max-width: 70%;">
                <div class="modal-content">
                    <div class="modal-header bg-sb">
                        <h1 class="modal-title fs-2 text-black">Tambah Karyawan Non QR</h1>
                        <input type="hidden" value="{{$username}}" id="username_who_access">
                        <button type="button" class=" btn-danger rounded-circle" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Line</label>
                                    <div class="input-group">
                                        <select class="form-control select2bs4 " id="cboline" name="cboline"
                                            style="width: 100%;">
                                            <option value="">-- PILIH LINE --</option>
                                            @foreach ($data_line as $dataline)
                                                <option value="{{ $dataline->isi }}">
                                                    {{ $dataline->tampil }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>ID QR</label>
                                    <div class="input-group">
                                        <select class="form-control select2bs4 " id="cboqr" name="cboqr"
                                            style="width: 100%;">
                                            <option value="">-- PILIH ID --</option>
                                            @foreach ($data_karyawan as $data_k)
                                                <option value="{{ $data_k->isi }}">
                                                    {{ $data_k->tampil }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group w-100">
                                    <label>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <div class="input-group w-100">
                                    @if ($username == 'HR' || $username == 'IT')
                                        <input class="btn btn-primary w-100" type="button" value="Tambah"
                                            onclick="tambah_non_qr();">
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable_set" class="table table-bordered table-sm w-100">
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
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" style="max-width: 75%;">
            <div class="modal-content">
                <div class="modal-header bg-sb">
                    <h1 class="modal-title fs-2 text-black" id="exampleModalLabel"></h1>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="modal-body" id="detail">
                    <div class="col-md-12 table-responsive">
                        <table id="datatable-modal" class="table table-striped table-bordered  table-sm w-100 text-nowrap">
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
                                    <th width="100"></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- page-header -->
    <div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
        <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
            <li><a href="{{route('hris.dashboard.tes')}}">Mutasi Karyawan</a></li>
            <li class="active"><span>Mutasi Karyawan</span></li>
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
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header text-white bg-gradient-primary py-2">
                    <div class="card-title">EXPORT DATA KARYAWAN</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="card-body p-5">
                        <div class="row pt-2">
                            <div class="col-4">
                                <input type="hidden" id="daterange1" name="daterange1">
                                <a class="nav-link card-title py-2 pl-3" style="border: 1px solid #d8d4dc" id="daterange-btn1" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Klik di sini untuk pilih tanggal kehadiran"></a>
                            </div>
                            <div class="col-4">
                                <button onclick="exportExcel2()" class="btn btn-app w-100" style="background-color: #16a34a" data-toggle="tooltip" title="Export Data ke File Transfer Excel" id="recap_labor_cost_2"><i class="fa fa-file-excel-o" aria-hidden="true"></i> EXPORT</a>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-app w-100" onclick="exportExcel()" style="background-color: #16a34a" data-toggle="tooltip" title="Export Data ke File Transfer Excel" id="recap_labor_cost_2"><i class="fa fa-file-excel-o" aria-hidden="true"></i> ABSENSI DATA KARYAWAN</button>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header text-white bg-gradient-primary py-2">
                    <div class="card-title">SCAN</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="card-body p-5">
                    <div class="row pt-2">
                        <div class="col-6">
                            <a href="{{ route('hris.mutasi-karyawan-create') }}">
                                <button class="button-lg custom-button">
                                    <div class="shine-effect"></div>
                                    <i class="fa fa-qrcode  icon-scan animate-pulse" aria-hidden="true"></i>
                                    Scan Perpindahan
                                </button>
                            </a>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-primary w-100" data-toggle="modal"
                                data-target="#exampleModalSet">
                                <i class="fa fa-user-plus" aria-hidden="true"></i>
                                Tambah Karyawan Non QR
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12">
            <div class="card shadow">
                <div class="card-body px-6 pt-2 pb-5">
                    <div class="row">
                        <div class="col">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-bordered table-sm w-100 table-hover text-nowrap">
                                    <thead class="bg-primary">
                                        <tr style='text-align:center;'>
                                                <th width="3%" style="vertical-align: middle;font-weight:bold">No</th>
                                                <th width="15%" style="vertical-align: middle;font-weight:bold">Line</th>
                                                <th width="5%" style="vertical-align: middle;font-weight:bold">Total Karyawan</th>
                                                <th width="5%" style="vertical-align: middle;font-weight:bold">Total Karyawan Absen</th>
                                                <th width="5%" style="vertical-align: middle;font-weight:bold">Selisih</th>
                                                <th width="10%" style="vertical-align: middle;font-weight:bold;border:1px solid rgb(195, 195, 195)"><span class="fa fa-cog"></span></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
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
    <script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/iziToast.min.js')}}"></script>

    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <script src="{{URL::asset('assets/js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
        #datatable {
        table-layout: fixed;
        }

    </style>
    <script type="text/javascript">

        var start = moment();
        var end = moment();
        var htmlDateRange = '<span><i class="fa fa-calendar"></i> ' + start.format("D MMM YYYY").toUpperCase() + ' s/d ' + end.format("D MMM YYYY").toUpperCase() + '</span><i class="fa fa-angle-down ml-1"></i>'
        $('#daterange-btn1').html(htmlDateRange);
        var daterange1 = start.format("YYYY-MM-DD") + " s/d " + end.format("YYYY-MM-DD");
        $('#daterange1').val(daterange1);
        $(document).ready(function() {
            let datatableFilter = document.getElementById("datatable_filter");
            datatableFilter.innerHTML = `<span> Search : </span><input type="text" class="form-control form-control-sm" id="search_variable" onkeyup="dataTableReload()">`;
        });

        $('.data_range').daterangepicker({
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
            // startDate: moment().startOf('month'),
            // endDate: moment().endOf('month')
            }, function(start, end) {
                $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        })

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var currentPageCheck = 0;
        var checkedEmployeeArr = [];
        let datatable = $("#datatable").DataTable({
            ordering: true,
            processing: true,
            serverSide: true,
            paging: true,
            searching: true,
            destroy: true,
            scrollX: false,
            ajax: {
                url: '{{ route('hris.mutasi-karyawan-list') }}',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function(d) {
                    d.enroll_id = $("select[name='selectEmployeeID[]']").map(function() {
                        return $(this).val();
                    }).get();
                    d.search_variable = $('#search_variable').val();
                },
            },
            "fnCreatedRow": function(row, data, index) {
                    $('td', row).eq(0).html(index + 1);
                },
            columns: [{
                        data: 'line'

                    }, {
                        data: 'line'
                    },
                    {
                        data: 'tot_orang'
                    },
                    {
                        data: 'tot_absen'
                    },
                    {
                        data: 'selisih'
                    },
                ],
            order: [
                [1, 'asc']
            ],

            columnDefs: [

                {
                    targets: [5],
                    render: (data, type, row, meta) => {
                        return `
                            <div class="row">
                            <div class="col text-center">
                                <a class="btn btn-app" style="background-color: #15435A"  data-toggle="modal" data-target="#exampleModal" onclick="getdetail('` +
                            row.line + `');">
                                            <i class='fa fa-search'></i>
                                </a>
                                <a class="btn btn-app" style="background-color: #16a34a" data-toggle="tooltip" title="Export Data ke File Transfer Excel" href="{{ route('hris.export-mutasi-karyawan') }}/` +
                            row.line +
                            `">
                                            <i class='fa fa-file-excel-o'></i>
                                </a>
                            </div>
                        </div>
                        `
                    }
                }
            ],
        });

        function getdetail(id_c) {
                curEnrollId=[];
                $("#exampleModalLabel").html('List Data Karyawan');

                datatable_detail = $("#datatable-modal").DataTable({
                    ordering: false,
                    processing: true,
                    serverSide: true,
                    info: false,
                    paging: false,
                    destroy: true,
                    ajax: {
                        url: '{{ route('getdatalinekaryawan') }}',
                        method: 'GET',
                        data: function(d) {
                            d.nm_line = id_c
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
                        },
                        {
                            data: 'tgl_update_fix'
                        }
                    ],
                    columnDefs: [{
                        targets: [0,1,2,3,4,5,6,7],
                        render: (data, type, row, meta) => {
                            var color = 'black';
                            if (row.absen_masuk_kerja == null) {
                                color = 'red';
                            } else {
                                color = 'green';
                            }
                            return '<span style="color:' + color + '">' + data + '</span>';
                        }
                    },{
                        targets: [8],
                        render: (data, type, row, meta) => {
                            if(row.enroll_id!=null){
                                if($('#username_who_access').val()=='HR' || $('#username_who_access').val()=='IT' ){
                                    return `<a href='#' onclick="hapus_confirm(` + row.enroll_id + `)"><i class='fa fa-trash text-danger'></i></a>&nbsp<a href='#' id='hapus_mutasi_`+row.enroll_id+`' style='visibility:hidden;color:white;background-color:red' onclick='hapus_mutasi(`+row.id+`)'>Hapus&nbsp</a><a href='#' id='cancel_hapus_`+row.enroll_id+`' style='visibility:hidden;color:white;background-color:grey' onclick='cancel_hapus(`+row.enroll_id+`)'>&nbsp;Batal&nbsp;</a>`;
                                }else{
                                    return '';
                                }
                            }
                        }
                    }],

                });
        };


        function dataTableReload() {
            datatable.ajax.reload();
        }


        function dataTableSetReload() {
                datatable_set.ajax.reload();
        }

        function dataTabledetailSetReload() {
            datatable_detail.ajax.reload();
        }

        function export_sp_kerja(enroll_id){
            var url = 'export_sp_kehadiran_karyawan?enroll_id='+enroll_id;
            window.open(url, '_blank');
        }



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
        });

        $('#exampleModalSet').on('show.bs.modal', function(e) {
            $('.select2bs4').select2()
            $('#cboline').val('').trigger('change');
            $('#cboqr').val('').trigger('change');

        })

        var curEnrollId=[];
        function hapus_confirm(enroll_id){
            curEnrollId.push(enroll_id);
            const enrollOne=curEnrollId[0];
            const index = curEnrollId.indexOf(enrollOne);
            document.getElementById('hapus_mutasi_'+curEnrollId[0]).style.visibility='visible';
            document.getElementById('cancel_hapus_'+curEnrollId[0]).style.visibility='visible';
            if(curEnrollId.length>1){
                document.getElementById('hapus_mutasi_'+curEnrollId[index]).style.visibility='hidden';
                document.getElementById('cancel_hapus_'+curEnrollId[index]).style.visibility='hidden';
                curEnrollId.splice(index, 1);
                document.getElementById('hapus_mutasi_'+curEnrollId[0]).style.visibility='visible';
                document.getElementById('cancel_hapus_'+curEnrollId[0]).style.visibility='visible';
            }
        }
        function cancel_hapus(enroll_id){
            document.getElementById('hapus_mutasi_'+curEnrollId[0]).style.visibility='hidden';
            document.getElementById('cancel_hapus_'+curEnrollId[0]).style.visibility='hidden';
            curEnrollId=[];
        }
        datatable_set = $("#datatable_set").DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            info: false,
            paging: false,
            destroy: true,
            ajax: {
                url: '{{ route('getdatakaryawan_nonqr') }}',
                method: 'GET',
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
                    data: 'nm_karyawan'
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


        function hapus_mutasi(id){
            id_mutasi=id;
            $.ajax({
                type: "post",
                url: '{{ route('delete_mutasi') }}',
                data: {
                    id: id_mutasi,
                },
                success: function(response) {
                    iziToast.success({
                        message: 'Data Berhasil Dihapus',
                        position: 'topCenter'
                    });
                    document.getElementById('hapus_mutasi_'+curEnrollId[0]).style.visibility='hidden';
                    document.getElementById('cancel_hapus_'+curEnrollId[0]).style.visibility='hidden';
                    curEnrollId=[];
                    dataTabledetailSetReload();
                    dataTableReload();
                },
                error: function(request, status, error) {
                    iziToast.success({
                        message: 'Data Gagal Dihapus',
                        position: 'topCenter'
                    });
                },
            });
        }
        function tambah_non_qr() {
            let cboline = document.form_m.cboline.value;
            let cboqr = document.form_m.cboqr.value;
            $.ajax({
                type: "post",
                url: '{{ route('store_add_non_qr') }}',
                data: {
                    cboline: cboline,
                    cboqr: cboqr
                },
                success: function(response) {
                    if (response.icon == 'salah') {
                        iziToast.warning({
                            message: response.msg,
                            position: 'topCenter'
                        });
                    } else {
                        iziToast.success({
                            message: response.msg,
                            position: 'topCenter'
                        });
                    }
                    dataTableReload();
                    dataTableSetReload();
                },
            });
        };
    </script>

    <script>
        function exportExcel() {
            var daterange = $("#daterange1").val();
            var dates = daterange.split(" s/d ");
            var startDate = dates[0];
            var endDate = dates[1];
            window.location.href = "/hris/export_excel_mutasi?param1=" + startDate +
                    "&param2=" + endDate;
            }
        function exportExcel2() {
            var daterange = $("#daterange1").val();
            var dates = daterange.split(" s/d ");
            var startDate = dates[0];
            var endDate = dates[1];
            window.location.href = "/hris/export_excel_mut_karyawan?param1=" + startDate +
                    "&param2=" + endDate;
            }
    </script>

@endsection
