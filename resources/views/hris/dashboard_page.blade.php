@extends('admin.adminlayouts.adminlayout2')

@section('head')
<!-- Owl Theme css-->
<link href="{{URL::asset('assets/plugins/owl-carousel/owl.carousel.css')}}" rel="stylesheet">

<!-- Morris  Charts css-->
<link href="{{URL::asset('assets/plugins/morris/morris.css')}}" rel="stylesheet" />
<style>
    .card{
    border-radius: 4px;
    background: #fff;
    box-shadow: 0 6px 10px rgba(0,0,0,.08), 0 0 6px rgba(0,0,0,.05);
    transition: .3s transform cubic-bezier(.155,1.105,.295,1.12),.3s box-shadow,.3s -webkit-transform cubic-bezier(.155,1.105,.295,1.12);
    padding: 14px 80px 18px 36px;
    cursor: pointer;
    display: flex; /* Aktifkan Flexbox */
    flex-direction: column; /* Susun konten secara vertikal */
    justify-content: center; /* Posisikan konten secara vertikal */
    align-items: center; /* Posisikan konten secara horizontal */
    text-align: center; /* Pastikan teks berada di tengah */
    position: relative; /* Untuk positioning gambar */
    height: 200px;
    width: 300px;
}

.card:hover{
     transform: scale(1.05);
  box-shadow: 0 10px 20px rgba(0,0,0,.12), 0 4px 8px rgba(0,0,0,.06);
}

.card h3{
  font-weight: 600;
}

.card img{
  position: absolute;
  top: 20px;
  right: 15px;
  max-height: 120px;
}

.card-1{
    background-image: url('{{ URL::asset('assets/images/icons/attandance_payroll_icon.png') }}');
    background-repeat: no-repeat;
    background-position: right;
    background-size: 100px;
}

.card-2{
   background-image: url(https://ionicframework.com/img/getting-started/components-card.png);
      background-repeat: no-repeat;
    background-position: right;
    background-size: 80px;
}

.card-3{
   background-image: url(https://ionicframework.com/img/getting-started/theming-card.png);
      background-repeat: no-repeat;
    background-position: right;
    background-size: 80px;
}

@media(max-width: 990px){
  .card{
    margin: 20px;
  }
} 
</style>

@stop
@section('mainarea')

<!-- page-header -->
<!-- End page-header -->

<div class="row pt-7 m-0 px-3">
    <div class="col-lg-12 col-md-12 flex">
        <div class="row p-5">
            <div class="col-auto">
                <div class="" style="background-color: #FFF">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col" style="background-color: #FFF">
                                <a href="{{route('hris.dashboard.index')}}" style="color: #FF72C6">
                                <div class="card card-1">
                                            <h3>Attendance & Payroll</h3>
                                        </div>
                                </a>
                            </div>
                            @if($role=='superadmin' || $role=='absensi' || $role=='admin')
                            <div class="col">
                                <a href="{{route('hris.hrd.index')}}" style="color: #FF72C6">
                                <div class="card card-2">
                                        <h3>Kepersonaliaan</h3>
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
                    '#FF72C6 ', '#dc3545'

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
