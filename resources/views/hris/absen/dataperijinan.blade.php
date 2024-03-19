@extends('admin.adminlayouts.adminlayout')

@section('head')
    <!-- Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet" />

    <!--Select2 css -->
    <link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />

    <!-- Date Picker css-->
    <link href="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.css')}}" rel="stylesheet" />

    <!--Mutipleselect css-->
    <link rel="stylesheet" href="{{URL::asset('assets/plugins/multipleselect/multiple-select.css')}}">

	<!-- Notifications  css -->
	<link href="{{URL::asset('assets/plugins/notify-growl/css/jquery.growl.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/notify-growl/css/notifIt.css')}}" rel="stylesheet" />

	<!---Sweetalert Css-->
	<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

    <style>
        .table-responsive{
            height:400px;
            overflow:scroll;
          }
          thead tr:nth-child(1) th{
            background: white;
            position: sticky;
            top: 0;
            z-index: 10;
          }
    </style>

@stop
@section('mainarea')
    <!-- page-header -->
    <div class="page-header p-3 shadow">
        <ol class="breadcrumb breadcrumb-arrow mt-0">
            <li><a href="#">ABSENSI KARYAWAN</a></li>
            <li class="active"><span>DATA PERIZINAN</span></li>
        </ol>
        <div class="ml-auto">
            <div class="input-group">
                <a href="#" id="btn-refresh-data" class="btn btn-secondary text-white mr-2 btn-icon" data-toggle="tooltip"
                    title="" data-placement="bottom" data-original-title="refresh data">
                    <span>
                        <i class="fa fa-refresh"></i>
                    </span>
                </a>
                <a href="{{ url('lockscreen') }}" class="btn btn-primary text-white mr-0 btn-icon" data-toggle="tooltip"
                    title="" data-placement="bottom" data-original-title="lock">
                    <span>
                        <i class="fa fa-lock"></i>
                    </span>
                </a>
            </div>
        </div>
    </div>
    <!-- End page-header -->
    <div class="card shadow">
        <div class="card-header bg-primary p-3">
            <div class="card-title">FILTER DATA PERIZINAN KARYAWAN</div>
            <div class="card-options ">
                <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">PERIODE PERIZINAN : </label>
                        <input id="periode_tanggal_perijinan" name="periode_perijinan" type="text" class="form-control"></input>
                        <input type="hidden" id="row_data_sebelumnya"></input>
                    </div>
                </div>
                <div class="col-md-6" id="inputSearch2">
                    <div class="form-group">
                        <label class="form-label">CARI DATA : </label>
                        <input id="searchData" name="searchData" class="form-control" type="text">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-primary m-0 p-1">
            <div class="text-white">
                <a id="btn-cari" class="btn btn-app btn-secondary mr-0 mt-0 mb-0" data-toggle="tooltip" title="Cari Data"><i class="ion-search"></i> Cari</a>
            </div>
        </div>
    </div>
    <div class="row margin-top">
        <div class="col-sm-12">
            <div clasl="card-header">
                <ul class="nav nav-tabs  mx-0 mb-3" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="waiting-tab" data-toggle="tab" href="#waiting" role="tab" aria-controls="waiting" aria-selected="true">Waiting</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="verifikasi-tab" data-toggle="tab" href="#verifikasi_tab" role="tab" aria-controls="verifikasi" aria-selected="false">Verifikasi</a>
                    </li>
                </ul>
            </div>
            <div id="data_perijinan" class="card shadow tab-content">
                <div class="card-header bg-primary p-3">
                    <div id="title-table1" class="card-title">DATA PERIZINAN KARYAWAN</div>
                    <div class="card-options">
                        <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="tab-pane fade show active" id="waiting" role="tabpanel" aria-labelledby="waiting-tab">
                    <form id="verifikasi" method="post">
                        @csrf

                        <div class="mr-5 d-flex">
                            <button type="button" class="btn btn-secondary btn-app BtnVerifikasiIzin" data-dismiss="modal" >Verifikasi</button>
                            <div class="checkedAll mx-3">
                                <input type="checkbox" id="checkAllVerif" class="check1 checkAllVerif" />
                                <label for="checkAllVerif" class="title-14">Select All</label>
                            </div>
                        </div>
                        <div class="card-body m-0 p-0">
                            <div class="table-responsive">
                                <table id="datatable1" class="table table-sm table-striped table-bordered table-vcenter text-nowrap table-nowrap w-100 m-0 p-0">
                                    <thead class="border text-center">
                                        <tr>
                                            <th class="bg-primary w-5 align-middle" scope="col">NO</th>
                                            <th class="bg-primary w-5 align-middle" scope="col"></th>
                                            <th class="bg-primary w-5 align-middle" scope="col">TANGGAL PERIZINAN</th>
                                            <th class="bg-primary w-5 align-middle" scope="col">NOMOR FORM PERIZINAN</th>
                                            <th class="bg-primary w-5 align-middle" scope="col">NIK</th>
                                            <th class="bg-primary w-5 align-middle" scope="col">NAMA KARYAWAN</th>
                                            <th class="bg-primary w-5 align-middle" scope="col">NOMOR<br>ABSEN</th>
                                            <th class="bg-primary w-5 align-middle" scope="col" width="50px">KODE IZIN</th>
                                            <th class="bg-primary w-5 align-middle" scope="col" width="50px">ALSAN IZIN</th>
                                            <th class="bg-primary w-5 align-middle" scope="col" width="50px">TANGGAL MULAI</th>
                                            <th class="bg-primary w-5 align-middle" scope="col" width="50px">TANGGAL SELESAI</th>
                                            <th class="bg-primary w-5 align-middle" scope="col" width="50px">WAKTU MULAI</th>
                                            <th class="bg-primary w-5 align-middle" scope="col" width="50px">WAKTU SELSEAI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <div id="loadingProcess">
                                    <div class="dimmer active">
                                        <div class="lds-hourglass mb-0"></div>
                                    </div>
                                    <h5 class="text-center m-0 p-0 text-dark"><i>data sedang di proses...</i></h5>
                                </div>
                            </div>
                            <i><div class="text-left mt-1 mb-1 text-sm ml-1" id="subtitle-table1"></div></i>
                        </div>
                        <div class="card-footer bg-primary br-br-7 br-bl-7">
                            <div class="text-white"></div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="verifikasi_tab" role="tabpanel" aria-labelledby="verifikasi-tab">
                    <div class="card-body m-0 p-0">
                        <div class="table-responsive">
                            <table id="datatable_verifikasi" class="table table-sm table-striped table-bordered table-vcenter text-nowrap table-nowrap w-100 m-0 p-0">
                                <thead class="border text-center">
                                    <tr>
                                        <th class="bg-primary w-5 align-middle" scope="col">NO</th>
                                        <th class="bg-primary w-5 align-middle" scope="col">TANGGAL PERIZINAN</th>
                                        <th class="bg-primary w-5 align-middle" scope="col">NOMOR FORM PERIZINAN</th>
                                        <th class="bg-primary w-5 align-middle" scope="col">NIK</th>
                                        <th class="bg-primary w-5 align-middle" scope="col">NAMA KARYAWAN</th>
                                        <th class="bg-primary w-5 align-middle" scope="col">NOMOR<br>ABSEN</th>
                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">KODE IZIN</th>
                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">ALSAN IZIN</th>
                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">TANGGAL MULAI</th>
                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">TANGGAL SELESAI</th>
                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">WAKTU MULAI</th>
                                        <th class="bg-primary w-5 align-middle" scope="col" width="50px">WAKTU SELSEAI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <i><div class="text-left mt-1 mb-1 text-sm ml-1" id="subtitle-table_verif"></div></i>
                    </div>
                    <div class="card-footer bg-primary br-br-7 br-bl-7">
                        <div class="text-white"></div>
                    </div>
                </div>
            </div>
        </div>
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

    <!-- Timepicker js -->
    <script src="{{URL::asset('assets/plugins/time-picker/jquery.timepicker.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/time-picker/toggles.min.js')}}"></script>

    <!-- Sweet alert js-->
    <script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

    <script type="text/javascript">
            
            $(document).ready(function() {
                var periode_perijinan =$('#periode_tanggal_perijinan').val();
                get_data_perijinan(periode_perijinan)

            });

            $('body').on('click', '#btn-cari', function (event) {
             
                var periode_perijinan =$('#periode_tanggal_perijinan').val();
                get_data_perijinan(periode_perijinan)
            });

            function get_data_perijinan(periode_perijinan) {
                var row_data_sebelumnya  =$('#row_data_sebelumnya').val();
                if (row_data_sebelumnya>0) {
                    $('#datatable_verifikasi').DataTable().clear();
                    $('#datatable_verifikasi').DataTable().destroy();
                    $('#datatable_verifikasi').empty();
                }
                    $('#row_data_sebelumnya').val(0)


                $("#data_perijinan tbody").empty();
                $("#data_perijinan").show();
                $("#loadingProcess").show();
                $("#subtitle-table1").empty();
              
                $.ajax({
                    type:"POST",
                    url: "{{route('hris.dataabsenperijinan.get')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        periode_perijinan:periode_perijinan,
                        // selectNoSPL:selectNoSPL,
                        // searchData:searchData,
                    },
                    dataType: 'json',
                    
                    success: function(res){
                        if(res.waiting.length > 0){
                            for(i=0;i<res.waiting.length;i++) {
                                var nomor_urut =i+1;
                                var uuid = res.waiting[i].uuid
                                var tanggal_perizinan = moment(res.waiting[i].tanggal_perizinan).format('DD MMM YYYY');
                                var nomor_form_perizinan = res.waiting[i].nomor_form_perizinan;
                                var enroll_id = res.waiting[i].enroll_id;
                                var nik = res.waiting[i].nik;
                                var employee_name = res.waiting[i].employee_name;
                                var kode_absen_ijin = res.waiting[i].kode_absen_ijin;
                                var absen_alasan = res.waiting[i].absen_alasan;
                                var tanggal_mulai_ijin = moment(res.waiting[i].tanggal_mulai_ijin).format('DD MMM YYYY');  res.waiting[i].tanggal_mulai_ijin;
                                var tanggal_akhir_ijin = moment(res.waiting[i].tanggal_akhir_ijin).format('DD MMM YYYY'); res.waiting[i].tanggal_akhir_ijin;
                                var time_mulai_ijin = res.waiting[i].time_mulai_ijin;
                                var time_akhir_ijin = res.waiting[i].time_akhir_ijin;
                            
                                var bgwarna = '';
                                if(kode_absen_ijin == 'IKS') { bgwarna = 'style="background: yellow"'; }
                                

                                htmlTable = '' +
                                '<tr ' + bgwarna + ' class="text-center">' +
                                '   <td class="text-nowrap text-center align-middle">' + nomor_urut  + '</td>' +

                                '   <td> <input type="checkbox" class="checked count_ceheck" name="uuid[]"  value="'+uuid+'">'+'</td>'+
                                '   <td class="text-nowrap text-center align-middle">' + tanggal_perizinan  + '</td>' +
                                '   <td class="text-nowrap text-center align-middle">' + nomor_form_perizinan  + '</td>' +
                                '   <td class="text-nowrap text-left align-middle">' + enroll_id  + '</td>' +
                                '   <td class="text-nowrap text-left align-middle">' + nik  + '</td>' +
                                '   <td class="text-nowrap text-left align-middle">' + employee_name  + '</td>' +
                                '   <td class="text-nowrap text-left align-middle">' + kode_absen_ijin  + '</td>' +
                                '   <td class="text-left align-middle">' + absen_alasan  + '</td>' +
                                '   <td class="text-nowrap text-center align-middle">' + tanggal_mulai_ijin  + '</td>' +
                                '   <td class="text-nowrap text-center align-middle">' + tanggal_akhir_ijin  + '</td>' +
                                '   <td class="text-nowrap text-center align-middle">' + time_mulai_ijin  + '</td>' +
                                '   <td class="text-nowrap text-center align-middle">' + time_akhir_ijin  + '</td>' +
                                '</tr>';

                                $("#datatable1 tbody").append(htmlTable);
                            }
                            $("#subtitle-table1").append("");
                            $("#subtitle-table1").append("Total data yang ditemukan ada: " + nomor_urut);
                        }
                        if(res.verifikasi.length > 0){
                            $('#datatable_verifikasi').DataTable({
                                data: res.verifikasi,
                                order: [[ 0, 'asc' ]],
                                columns: [
                                    {data: 'SrNo',
                                        render: function (data, type, row, meta) {
                                                return meta.row + 1;
                                        } //no urut
                                    },
                                    {   
                                        title: 'TANGGAL PERIZINAN',
                                        data: 'tanggal_perizinan', 
                                    },
                                    {   
                                        title: 'NOMOR FORM PERIZINAN',
                                        data: 'nomor_form_perizinan',
                                    },
                                    {   
                                        title: 'NO ABSEN',
                                        data: 'enroll_id', 
                                    },
                                    {   
                                        title: 'NIK',
                                        data: 'nik', 
                                    },
                                    {   
                                        title: 'NAMA KARYAWAN',
                                        data: 'employee_name',
                                    },
                                    {   
                                        title: 'KODE IZIN',
                                        data: 'kode_absen_ijin', 
                                    },
                                    {   
                                        title: 'ALSAN IZIN',
                                        data: 'absen_alasan',
                                    },
                                    {   
                                        title: 'TANGGAL MULAI',
                                        data: 'tanggal_mulai_ijin', 
                                    },
                                    {   
                                        title: 'TANGGAL SELESAI',
                                        data: 'tanggal_akhir_ijin', 
                                    },
                                    {   
                                        title: 'WAKTU MULAI',
                                        data: 'time_mulai_ijin', 
                                    },
                                    {   
                                        title: 'WAKTU SELSEAI',
                                        data: 'time_akhir_ijin', 
                                    },
                                ],
                            });
                            $("#row_data_sebelumnya").val(res.verifikasi.length);
                        }

                        if(res.waiting.length == 0 && res.verifikasi.length== 0){
                            notif({
                                msg: "<b>Warning:</b> Data tidak ditemukan.",
                                type: "warning"
                            });
                        }
                        $("#loadingProcess").hide();

                    },
                    error: function(res){
                        notif({
                            msg: "<b>Oops!</b> An Error Occurred",
                            type: "error",
                            position: "center"
                        });
                    }
                });
            }

            $('#periode_tanggal_perijinan').daterangepicker({
                ranges: {
                    'Hari ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 Hari Kemarin': [moment().subtract(6, 'days'), moment()],
                    '30 Hari Kemarin': [moment().subtract(29, 'days'), moment()],
                    'Bulan Sekarang': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan Kemarin': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                // startDate: moment().subtract(29, 'days'),
                // endDate: moment()
                startDate: moment().startOf('month'),
                endDate: moment().endOf('month')
                }, function(start, end) {
                    $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
            })

            $('#searchData').keyup(function(){
                search_table($(this).val());
            });

            function search_table(value){
                $('#data_perijinan tbody tr').each(function(){
                    var found = 'false';
                    $(this).each(function(){
                        if($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0)
                        {
                            found = 'true';
                        }
                    });
                    if(found == 'true')
                    {
                        $(this).show();
                    }
                    else
                    {
                        $(this).hide();
                    }
                });
            }

        // ----------- verifikasi-----------

            $('#checkAllVerif').click(function() {
                if (this.checked) {
                $(':checkbox').each(function() {
                    this.checked = true;            
                });
                } else {
                $(':checkbox').each(function() {
                    this.checked = false; 
                });
                } 
            });

        
            jQuery(document).ready(function($) {
                const BtnVerifikasi = document.getElementsByClassName('BtnVerifikasiIzin')[0];
                const count_ceheck = document.getElementsByClassName('count_ceheck'); 


                BtnVerifikasi.addEventListener('click', function(event) {
                    let tmp = 0;
                    Array.from(count_ceheck).forEach(function (element) {
                    if(element.checked ) {
                        tmp +=1
                    }
                    });
                    if (tmp == 0) {
                        swal({
                            title: "Harap Ceklis",
                            text: "Ceklis Data Yang Akan diVerifikasi",
                            icon: "warning",
                            button : false,
                        });
                    } 
                    else {
                        event.preventDefault();
                        const submited =document.getElementsByTagName('form')[0];
                        swal({
                            title: 'Verifikasi Perizinan',
                            text: 'Apakah Anda Yakin ?',
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonText: 'Verifikasi',
                            cancelButtonText: 'TUTUP'
                        },function(isConfirm){
                            if(isConfirm) {
                                $.ajax({
                                    data: $('#verifikasi').serialize(),
                                    url: '{{ route("hris.verifikasiperijinan.store") }}',           
                                    type: "post",
                                    dataType: 'json',           
                                    success: function (data) {
                                        console.log(data)
                                    },
                                    error: function (xhr, status, error) {
                                        alert(xhr.responseText);
                                    }
                                    
                                });  
                                notif({
                                    msg: "<b>Info:</b> Data sedang di PROSES, mohon di tunggu.",
                                    type: "info"
                                });

                                var periode_perijinan =$('#periode_tanggal_perijinan').val();
                                get_data_perijinan(periode_perijinan)
                                $('#searchData').val('');
                            }
                        });    
                    }
                });
            });

           
        </script>

@endsection