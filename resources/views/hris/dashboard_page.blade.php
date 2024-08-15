@extends('admin.adminlayouts.adminlayout2')

@section('head')
<!-- Owl Theme css-->
<link href="{{URL::asset('assets/plugins/owl-carousel/owl.carousel.css')}}" rel="stylesheet">

<!-- Morris  Charts css-->
<link href="{{URL::asset('assets/plugins/morris/morris.css')}}" rel="stylesheet" />

@stop
@section('mainarea')

<!-- page-header -->
<!-- End page-header -->

<div class="row pt-7 px-3">
    <div class="col-lg-12 col-md-12">
        <div class="row p-5">
            <div class="col-auto">
                <div class="card" style="background-color: rgb(41, 115, 138)">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col" style="background-color: rgb(41, 115, 138)">
                                <a href="{{route('hris.dashboard.index')}}">
                                    <div class="d-flex h-100 flex-column justify-content-between">
                                        <img src="{{URL::asset('assets/image/time-and-attendance.png')}}" class="img-fluid p-3" alt="qr code image" width="200">
                                        <p class="text-center" style="color:white"><b>ATTENDANCE AND PAYROLL</b></p>
                                    </div>
                                </a>
                            </div>
                            @if($role=='superadmin' || $role=='absensi')
                            <div class="col">
                                <a href="{{route('hris.hrd.index')}}">
                                    <div class="d-flex h-100 flex-column justify-content-between">
                                        <img src="{{URL::asset('assets/image/time-and-attendance copy.png')}}" class="img-fluid p-3" alt="qr code image" width="200">
                                        <p class="text-center" style="color:white"><b>KEPERSONALIAAN</b></p>
                                    </div>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
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
