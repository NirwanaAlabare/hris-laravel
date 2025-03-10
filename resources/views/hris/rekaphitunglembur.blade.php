@extends('admin.adminlayouts.adminlayout')

@section('head')
    <!-- Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet" />

	<!-- Notifications  css -->
	<link href="{{URL::asset('assets/plugins/notify-growl/css/jquery.growl.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/notify-growl/css/notifIt.css')}}" rel="stylesheet" />

    <!-- Date Picker css-->
    <link href="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.css')}}" rel="stylesheet" />

	<!---Sweetalert Css-->
	<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

@stop
@section('mainarea')
    <!-- page-header -->
    <div class="page-header p-2 shadow">
        <ol class="breadcrumb breadcrumb-arrow mt-0">
            <li><a href="#">Master Data</a></li>
            <li class="active"><span>Rekap Lembur</span></li>
        </ol>
        <div class="ml-auto">
            <div class="input-group">
                <a href="#" id="btn-refresh-page" class="btn btn-secondary p-0 mr-0 text-white btn-icon" data-toggle="tooltip"
                    title="" data-placement="bottom" data-original-title="Refresh Page">
                    <span>
                        <i class="fa fa-refresh"></i>
                    </span>
                </a>
            </div>
        </div>
    </div>
    <!-- End page-header -->
    <div class="card shadow card-collapsed">
        <div class="card-header bg-primary p-2">
            <div class="card-title">PROSES REKAP LEMBUR</div>
            <div class="card-options ">
                <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
            </div>
        </div>
        <form id="form_proses_Lembur" method="post">
                @csrf
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">PERIODE REKAP LEMBUR  : </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                    </div>
                                </div>
                                <input name="periode_payrols" min="{{$month}}" type="month" class="form-control PriodeProses fc-datepicker" required></input>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="card-footer bg-light m-0 p-1">
                <div class="text-white">
                    <a id="BtnProsesLembur" class="btn btn-app btn-primary mr-0 mt-0 mb-0 text-white BtnProsesLembur"><span><i class="fa fa-download"></i></span> PROSES Lembur</a>
                </div>
            </div> --}}
        </form>
    </div>

    <!-- BEGIN FORM-->
    {!! Form::open(['route' => 'hris.rekapperhitunganlembur.ajax_exportexcel', 'id' => 'formExport', 'name' => 'formExport','method'=>'post']) !!}

    @csrf
    <input id="selDepVal" name="selDepVal" type="hidden">
    <div class="card shadow">
        <div class="card-header bg-primary p-3">
            <div class="card-title">FILTER DATA LEMBUR</div>
            <div class="card-options ">
                <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="expanel expanel-default mb-0">
                        <div class="expanel-body mr-0 mt-0 mb-0">
                            <div class="form-group m-0 p-0">
                                <label class="form-label">Tanggal Lembur : </label>
                                <input readonly id="daterange1" name="daterange1" type="text" class="form-control" placeholder="MM-DD-YYYY MM-DD-YYYY" maxlength="40" size="40">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="progress progress-xs mb-0">
            <div id="progress-show-1" class="progress-bar progress-bar-indeterminate bg-green"></div>
            <div id="progress-hide-1" class="progress-bar"></div>
        </div>
        <div class="card-footer bg-primary m-0 p-1">
            <div class="text-white">
                <button type="submit" id="btn-exportexcel" class="btn btn-app btn-warning mr-0 mt-0 mb-0" data-toggle="tooltip" title="Export Data ke File Excel"><i class="ion-ios7-download"></i> Export</button>
                <a href="javascript:void(0)" id="btn-caridata" class="btn btn-app btn-secondary mr-0 mt-0 mb-0" data-toggle="tooltip" title="Cari Data"><i class="ion-search"></i> Cari</a>
            </div>
        </div>
    </div>
    {{-- </form> --}}
    {!! Form::close() !!}
    <!-- END FORM-->

    <!-- row -->
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div id="data-rekap-perhitungan-lembur" class="card shadow">
                <div class="card-header bg-primary p-3">
                    <div class="card-title">REKAP PERHITUNGAN LEMBUR KARYAWAN</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="card-body m-0">
                    <div class="table-responsive">
                        <table id="datatable-ajax-crud"
                            class="table table-sm table-striped table-hover table-bordered w-100">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-primary br-br-7 br-bl-7">
                    <div class="text-white"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- row end -->
</div>

@endsection

@section('footerjs')

    <!--Jquery Sparkline js-->
    <script src="{{ URL::asset('assets/plugins/vendors/jquery.sparkline.min.js') }}"></script>

    <!-- INTERNAL Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.js') }}"></script>

    <!-- Notifications js -->
    <script src="{{URL::asset('assets/plugins/notify-growl/js/rainbow.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/notify-growl/js/sample.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/notify-growl/js/jquery.growl.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/notify-growl/js/notifIt.js')}}"></script>

    <!-- Datepicker js -->
    <script src="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/spectrum-date-picker/jquery-ui.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/input-mask/jquery.maskedinput.js')}}"></script>

    <!-- Sweet alert js-->
    <script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

    <script type="text/javascript">

        $('#progress-show-1').hide();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //Date range as a button
        $('#daterange1').daterangepicker({
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
            //
        })

        $('body').on('click', '#btn-exportexcel', function (event) {
            notif({
                msg: "<b>Info:</b> Data sedang di proses, mohon menunggu",
                type: "info"
            });
        });

        $('body').on('click', '#btn-refresh-page', function (event) {
            location.reload();
        });

        $(document).ready(function() {
            $("#data-rekap-perhitungan-lembur").hide();
        });

        function cari_rekap_lembur()
        {
            $("#data-rekap-perhitungan-lembur").show('slow');

            $('#datatable-ajax-crud').DataTable().clear();
            $('#datatable-ajax-crud').DataTable().destroy();
            $('#datatable-ajax-crud').empty();

            var daterange1 = $('#daterange1').val();
            var searchName = $('#searchName').val();

            var table1 = $('#datatable-ajax-crud').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                "ajax": {
                    "url": "{{ route('hris.rekapperhitunganlembur.ajax_rekap') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                    "data": {
                        daterange1:daterange1,
                        searchName:searchName,
                    },
                    // success: function (data) {
                    //                 console.log('data',data);
                    //                 notif({
                    //                     msg: "<b>Info:</b> Data Berhasil di Proses.",
                    //                     type: "info"
                    //                 });
                    //             },
                },
                columns: [
                    {
                        title: 'UUID',
                        data: 'uuid',
                        name: 'uuid'
                    },
                    {
                        title: 'NOMOR ABSEN',
                        data: 'enroll_id',
                        name: 'enroll_id'
                    },
                    {
                        title: 'NIK',
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        title: 'NOMOR FORM SPL',
                        data: 'nomor_form_lembur',
                        name: 'nomor_form_lembur'
                    },
                    {
                        title: 'NAMA KARYAWAN',
                        data: 'employee_name',
                        name: 'employee_name'
                    },
                    {
                        title: 'JABATAN',
                        data: 'posisi_name',
                        name: 'posisi_name'
                    },
                    {
                        title: 'DEPARTMENT',
                        data: 'department_name',
                        name: 'department_name'
                    },
                    {
                        title: 'BAGIAN',
                        data: 'sub_dept_name',
                        name: 'sub_dept_name'
                    },
                    {
                        title: 'TANGGAL',
                        data: 'tanggal_berjalan',
                        name: 'tanggal_berjalan'
                    },
                    {
                        title: 'KODE HARI',
                        data: 'kode_hari',
                        name: 'kode_hari'
                    },
                    {
                        title: 'HARI',
                        data: 'nama_hari',
                        name: 'nama_hari'
                    },
                    {
                        title: 'KERJA/LIBUR',
                        data: 'kerjalibur',
                        name: 'kerjalibur'
                    },
                    {
                        title: 'JADWAL (IN)',
                        data: 'mulai_jam_kerja',
                        name: 'mulai_jam_kerja'
                    },
                    {
                        title: 'JADWAL (OUT)',
                        data: 'akhir_jam_kerja',
                        name: 'akhir_jam_kerja'
                    },
                    {
                        title: 'JUMLAH JAM KERJA',
                        data: 'jumlah_jam_kerja',
                        name: 'jumlah_jam_kerja'
                    },
                    {
                        title: 'ABSEN (IN)',
                        data: 'absen_masuk_kerja',
                        name: 'absen_masuk_kerja'
                    },
                    {
                        title: 'ABSEN (OUT)',
                        data: 'absen_pulang_kerja',
                        name: 'absen_pulang_kerja'
                    },
                    {
                        title: 'EFEKTIF KERJA (JAM)',
                        data: 'jam_efektif_kerja',
                        name: 'jam_efektif_kerja'
                    },
                    {
                        title: 'LEMBUR (IN)',
                        data: 'mulai_jam_lembur',
                        name: 'mulai_jam_lembur'
                    },
                    {
                        title: 'LEMBUR (OUT)',
                        data: 'akhir_jam_lembur',
                        name: 'akhir_jam_lembur'
                    },
                    {
                        title: 'FINAL (IN)',
                        data: 'final_mulai_jam_lembur',
                        name: 'final_mulai_jam_lembur'
                    },
                    {
                        title: 'FINAL (OUT)',
                        data: 'final_selesai_jam_lembur',
                        name: 'final_selesai_jam_lembur'
                    },
                    {
                        title: 'FINAL JAM',
                        data: 'final_total_jam_lembur',
                        name: 'final_total_jam_lembur'
                    },
                    {
                        title: 'BREAK',
                        data: 'final_jam_istirahat_lembur',
                        name: 'final_jam_istirahat_lembur'
                    },
                    {
                        title: 'TOTAL MENIT',
                        data: 'final_total_menit_lembur',
                        name: 'final_total_menit_lembur'
                    },
                    {
                        title: 'JAM',
                        data: 'final_jam_lembur_roundown',
                        name: 'final_jam_lembur_roundown'
                    },
                    {
                        title: 'MENIT',
                        data: 'final_menit_lembur_roundown',
                        name: 'final_menit_lembur_roundown'
                    },
                    {
                        title: 'L1',
                        data: 'lembur_1',
                        name: 'lembur_1'
                    },
                    {
                        title: 'L2',
                        data: 'lembur_2',
                        name: 'lembur_2'
                    },
                    {
                        title: 'L3',
                        data: 'lembur_3',
                        name: 'lembur_3'
                    },
                    {
                        title: 'L4',
                        data: 'lembur_4',
                        name: 'lembur_4'
                    },
                    {
                        title: 'TOTAL L',
                        data: 'total_lembur_1234',
                        name: 'total_lembur_1234'
                    },
                    {
                        title: 'Salary',
                        data: 'salary',
                        name: 'salary'
                    },
                    {
                        title: 'Lembur 1 (Rp)',
                        data: 'lembur1_rupiah',
                        name: 'lembur1_rupiah'
                    },
                    {
                        title: 'Lembur 2 (Rp)',
                        data: 'lembur2_rupiah',
                        name: 'lembur2_rupiah'
                    },
                    {
                        title: 'Lembur 3 (Rp)',
                        data: 'lembur3_rupiah',
                        name: 'lembur3_rupiah'
                    },
                    {
                        title: 'Lembur 4 (Rp)',
                        data: 'lembur4_rupiah',
                        name: 'lembur4_rupiah'
                    },
                    {
                        title: 'TOTAL Lembur (Rp)',
                        data: 'total_lembur_rupiah',
                        name: 'total_lembur_rupiah'
                    },
                ],
                columnDefs: [
                    {
                        'visible': false,
                        'targets': [0,9]
                    },
                    {
                        "targets": [1,11,12,13,15,16,17,18,19,20,21,22,23,24],
                        "className": "w-5 text-center",
                    },
                    {
                        "targets": [2,3,4,5,6,7,8,10],
                        "className": "text-nowrap",
                    },
                    {
                        "targets": [14,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37],
                        "className": "w-10 text-right",
                    },
                ],
                order: [
                    [3, 'asc'],[4, 'asc']
                ],
                "createdRow": function (row, data, dataIndex) {
                    if ((data['kode_hari'] == "5") || (data['kode_hari'] == "6") || (data['holiday_name'])) {
                        $(row).css('background', 'yellow');
                    }
                    if (data['total_lembur_1234'] == "0") {
                        $(row).addClass('bg-danger');
                    }
                }
            });

            table1.draw();

            $('#datatable-ajax-crud tbody').on('click', 'tr', function () {
                var tr = $(this).closest('tr');
                var row = table1.row(tr);

                var data = row.data();

                $("#datatable-ajax-crud tbody tr").removeClass('bg-cyan');
                $(this).addClass('bg-cyan');

             });


        }
        $('body').on('click', '#btn-caridata', function (event) {
            cari_rekap_lembur()
        });

    </script>

     <!-- Andri -->

     {{-- <script>
        // $('.fc-datepickeraaaa').datepicker({
        // format: 'Y',
        // showButtonPanel: true
        // })
        // const datePicker = document.getElementById('report_date');

        jQuery(document).ready(function($) {

        $('#report_date').datepicker({
            dateFormat: 'MM yy',
            showButtonPanel: false,
        })
            const BtnProsesLembur = document.getElementsByClassName('BtnProsesLembur')[0];
            const PriodeProses = document.getElementsByClassName("PriodeProses");

            BtnProsesLembur.addEventListener('click', function(event) {
                let tmp = PriodeProses[0].value;

                if (tmp == ''||tmp==null) {
                    swal({
                        title: "Harap Pilih Periode",
                        text: "Data Periode Lembur tidak boleh kosong",
                        icon: "warning",
                        button : false,
                    });
                } else{
                    event.preventDefault();
                    const submited =document.getElementsByTagName('form')[0];
                    swal({
                        title: 'Apakah Anda Yakin ?',
                        text: 'Proses Lembur',
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'YES',
                        cancelButtonText: 'NO'
                    },function(isConfirm){
                        if(isConfirm) {
                            $('#BtnProsesLembur').addClass("btn-loading");
                            $("#BtnProsesLembur").html('Please wait...');
                            $("#BtnProsesLembur").attr("disabled", true);
                            console.log('submited',submited);
                            // $.ajax({
                            //     data: $('#form_proses_Lembur').serialize(),
                            //     url: '{{ route("hris.proses.lembur.rekap") }}',
                            //     type: "post",
                            //     success: function (data) {
                            //         console.log('data',data);
                            //         notif({
                            //             msg: "<b>Info:</b> Data Berhasil di Proses.",
                            //             type: "info"
                            //         });

                            //         $('#BtnProsesLembur').removeClass("btn-loading");
                            //         $("#BtnProsesLembur").html('<span><i class="fa fa-download"></i></span> PROSES Lembur');
                            //         $("#BtnProsesLembur").attr("disabled", false);

                            //     },
                            //     error: function (xhr, status, error) {
                            //         console.log('error',error);
                            //         notif({
                            //             msg: "<b>Error:</b> Oops data gagal di Proses.",
                            //             type: "error"
                            //         });

                            //         $('#BtnProsesLembur').removeClass("btn-loading");
                            //         $("#BtnProsesLembur").attr("disabled", false);
                            //         $("#BtnProsesLembur").html('<span><i class="fa fa-download"></i></span> PROSES Lembur');
                            //     }
                            // });

                            cari_rekap_lembur()
                        }
                    });
                }
            });
        });
    </script> --}}


@endsection
