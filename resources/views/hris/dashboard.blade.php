@extends('admin.adminlayouts.adminlayout')

@section('head')
<!-- Owl Theme css-->
<link href="{{URL::asset('assets/plugins/owl-carousel/owl.carousel.css')}}" rel="stylesheet">

<!-- Morris  Charts css-->
<link href="{{URL::asset('assets/plugins/morris/morris.css')}}" rel="stylesheet" />

@stop
@section('mainarea')

<!-- page-header -->
<div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
    <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
        <li><a href="#">DASHBOARD</a></li>
        <li class="active"><span>KEHADIRAN</span></li>
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
<!-- End page-header -->

<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card shadow bg-light">
            <div class="card-header custom-header pb-0 text-dark">
                <div>
                    <h3 class="card-title">MONITORING ABSENSI KARYAWAN</h3>
                    <h6 id="tanggal_kehadiran_sekarang" class="card-subtitle"></h6>
                </div>
            </div>
            <div class="card-body pl-4 pt-3 pb-0 pr-3 text-center">
                <div class="row">
                    <div class="col-lg-4 col-md-12">
                        <div class="card">
                            <div class="card-body p-0">
                                <div id="morrisBar9" class="chartsh chart-dropshadow text-sm mt-1 mb-1"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-6 col-md-12 col-sm-12">
                        <div class="card card-counter bg-gradient-primary">
                            <div class="card-header pb-0 text-center">
                                <div>
                                    <h6 class="card-title text-center text-sm">KARYAWAN (JUMLAH, STAFF / NON STAFF)</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <i class="si si-people mt-1 mb-0 text-white-transparent"></i>
                                    </div>
                                    <div class="col-8 text-center">
                                        <div class="mt-0 mb-0 text-white">
                                            <h2 id="total_karyawan_aktif" class="mb-0"></h2>
                                            <p class="text-white mt-0 mb-0">JUMLAH KARYAWAN<br>AKTIF</p>
                                        </div>
                                    </div>
                                </div>
                                <hr class="bg-white" style="height: 2px;">
                                <div class="row">
                                    <div class="col-6 text-center">
                                        <div class="mt-0 mb-0 text-white">
                                            <h2 id="jumlah_staff" class="mb-0"></h2>
                                            <p class="text-white mt-0 mb-0">STAFF</p>
                                        </div>
                                    </div>
                                    <div class="col-6 text-center">
                                        <div class="mt-0 mb-0 text-white">
                                            <h2 id="jumlah_nonstaff" class="mb-0"></h2>
                                            <p class="text-white mt-0 mb-0">NON STAFF</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- col end -->
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="card bg-gradient-primary mb-2">
                            <div class="card-body pt-3 pb-3">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h6 class="text-white">Hari Kemarin</h6>
                                        <h2 id="absen_tl_hari_kemarin" class="text-white m-0"></h2>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-white display-6"><i class="si si-people mt-1 mb-0 text-white-transparent"></i> TL</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card bg-gradient-primary mb-2">
                            <div class="card-body pt-3 pb-3">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h6 class="text-white">Hari Ini</h6>
                                        <h2 id="absen_m_hari_ini" class="text-white m-0"></h2>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-white display-6"><i class="si si-people mt-1 mb-0 text-white-transparent"></i> M</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card bg-gradient-primary mb-2">
                            <div class="card-body pt-4 pb-3">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h6 class="text-white">Minggu Kemarin</h6>
                                        <h2 id="absen_m_weekly" class="text-white m-0"></h2>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-white display-6"><i class="si si-people mt-1 mb-0 text-white-transparent"></i> M</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- col end -->
                </div>
                <!-- row end -->
            </div>
        </div>
    </div>
</div>
<!-- row end -->

@endsection

@section('footerjs')

<!--Jquery Sparkline js-->
<script src="{{URL::asset('assets/plugins/vendors/jquery.sparkline.min.js')}}"></script>

<!-- Chart Circle js-->
<script src="{{URL::asset('assets/plugins/vendors/circle-progress.min.js')}}"></script>

<!--Time Counter js-->
<script src="{{URL::asset('assets/plugins/counters/jquery.missofis-countdown.js')}}"></script>
<script src="{{URL::asset('assets/plugins/counters/counter.js')}}"></script>

<!--Morris  Charts js-->
<script src="{{URL::asset('assets/plugins/morris/raphael-min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/morris/morris.js')}}"></script>

<script>
    function getTanggalKehadiranSekarang () {
        $.ajax({
            type:"POST",
            url: "{{route('hris.mdabsenhadir.ajax_getTanggalKehadiranSekarang')}}",
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: 'json',
            success: function(res){
                $('#tanggal_kehadiran_sekarang').html(res);
            }
        });

    }

    /*---- morrisBar9----*/
    function getKehadiranValue () {
        $.ajax({
            type:"POST",
            url: "{{route('hris.mdabsenhadir.ajax_getdashkehadiran')}}",
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: 'json',
            success: function(res){
                //console.info(res['persentase_kehadiran']);
                new Morris.Donut({
                    element: 'morrisBar9',
                    data: [
                    {value: res[0]['persentase_kehadiran'], label: 'KEHADIRAN'},
                    {value: res[0]['persentase_ketidakhadiran'], label: 'TIDAK HADIR'},
                    ],
                    backgroundColor: '#fff',
                    labelColor: '#060',
                    colors: [
                    '#467fcf ', '#dc3545'

                    ],
                    formatter: function (x) { return x + "%"}
                });
                $('#total_karyawan_aktif').html(res[0]['total_karyawan_aktif']);
                $('#jumlah_staff').html(res[0]['jumlah_staff']);
                $('#jumlah_nonstaff').html(res[0]['jumlah_nonstaff']);

                $('#absen_tl_hari_kemarin').html(res[0]['absen_tl_hari_kemarin']);
                $('#absen_m_hari_ini').html(res[0]['absen_m_hari_ini']);
                $('#absen_m_weekly').html(res[0]['absen_m_weekly']);
            },
            error: function(res){

            }
        });

    }


    getKehadiranValue();
    getTanggalKehadiranSekarang();

    //$('#tanggal_kehadiran_sekarang').html('<span><i class="fa fa-calender"></i></span>' + hariini);

    setInterval(function() {
        getKehadiranValue();
        getTanggalKehadiranSekarang();
    }, 60000);

</script>

@endsection
