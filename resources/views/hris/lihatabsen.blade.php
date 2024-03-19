@extends('admin.adminlayouts.adminlayout')

@section('head')
    <!-- Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet" />

    <!-- Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />

    <!-- Accordion Css -->
	<link href="{{URL::asset('assets/plugins/accordion/accordion.css')}}" rel="stylesheet" />
@stop
@section('mainarea')
    <!-- page-header -->
    <div class="page-header">
        <ol class="breadcrumb breadcrumb-arrow mt-0">
            <li><a href="#">Kehadiran Karyawan</a></li>
            <li class="active"><span>Lihat Absen</span></li>
        </ol>
        <div class="ml-auto">
            <div class="input-group">
                <a href="{{ url('lockscreen') }}" class="btn btn-primary text-white mr-2 btn-sm" data-toggle="tooltip"
                    title="" data-placement="bottom" data-original-title="lock">
                    <span>
                        <i class="fa fa-lock"></i>
                    </span>
                </a>
            </div>
        </div>
    </div>
    <!-- End page-header -->

    <!-- row -->
    <div class="row">
        <div class="col-md-12">
           
            <div class="card" id="datatable-data-karyawan">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable-ajax-crud"
                            class="table table-sm table-striped table-hover table-bordered w-100 text-nowrap display">
                            <thead>
                                <tr>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Hari</th>
                                    <th scope="col">NIK</th>
                                    <th scope="col">Nama Karyawan</th>
                                    <th scope="col">S</th>
                                    <th scope="col">IBY</th>
                                    <th scope="col">ITB</th>
                                    <th scope="col">M</th>
                                    <th scope="col">PC</th>
                                    <th scope="col">DT</th>
                                    <th scope="col">DTPC</th>
                                    <th scope="col">LBY</th>
                                    <th scope="col">LBY/SM</th>
                                    <th scope="col">DT</th>
                                    <th scope="col">CUTI/U</th>
                                    <th scope="col">CUTI/K</th>
                                    <th scope="col">RESMI</th>
                                    <th scope="col">Option</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footerjs')

    <!--Jquery Sparkline js-->
    <script src="{{ URL::asset('assets/plugins/vendors/jquery.sparkline.min.js') }}"></script>

    <!-- Chart Circle js-->
    <script src="{{ URL::asset('assets/plugins/vendors/circle-progress.min.js') }}"></script>

    <!--Time Counter js-->
    <script src="{{ URL::asset('assets/plugins/counters/jquery.missofis-countdown.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/counters/counter.js') }}"></script>

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

    <!---Accordion js-->
    <script src="{{URL::asset('assets/plugins/accordion/accordion.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/accordion/accordions.js')}}"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $('#datatable-ajax-crud').DataTable({ 
                processing: true,
                serverSide: true,
                "ajax": {
                    "url": "{{ route('hris.mdabsenhadir.ajax_lihatabsen') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data"
                },
                columns: [
                    {
                        data: 'tanggal_berjalan',
                        name: 'tanggal_berjalan'
                    },
                    {
                        data: 'nama_hari',
                        name: 'nama_hari'
                    },
                    {
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        data: 'employee_name',
                        name: 'employee_name'
                    },
                    {
                        data: 'absen_s_sakit',
                        name: 'absen_s_sakit'
                    },
                    {
                        data: 'absen_iby_ijin_dibayar',
                        name: 'absen_iby_ijin_dibayar'
                    },
                    {
                        data: 'absen_itb_ijin_tidak_dibayar',
                        name: 'absen_itb_ijin_tidak_dibayar'
                    },

                    {
                        data: 'absen_m_mangkir',
                        name: 'absen_m_mangkir'
                    },
                    {
                        data: 'absen_pc_pulang_cepat',
                        name: 'absen_pc_pulang_cepat'
                    },
                    {
                        data: 'absen_dt_datang_terlambat',
                        name: 'absen_dt_datang_terlambat'
                    },
                    {
                        data: 'absen_dtpc_datang_terlambat_pulang_cepat',
                        name: 'absen_dtpc_datang_terlambat_pulang_cepat'
                    },
                    {
                        data: 'absen_lby_libur_dibayar',
                        name: 'absen_lby_libur_dibayar'
                    },
                    {
                        data: 'absen_lby_libur_sabtu_minggu',
                        name: 'absen_lby_libur_sabtu_minggu'
                    },
                    {
                        data: 'absen_dt_datang_terlambat',
                        name: 'absen_dt_datang_terlambat'
                    },
                    {
                        data: 'absen_cuti_umum',
                        name: 'absen_cuti_umum'
                    },
                    {
                        data: 'absen_cuti_khusus',
                        name: 'absen_cuti_khusus'
                    },
                    {
                        data: 'absen_hari_resmi',
                        name: 'absen_hari_resmi'
                    },
                    {
                        data: 'option'
                    },
                ]
            });
        });


    </script>

@endsection
