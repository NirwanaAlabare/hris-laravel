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
            <li><a href="#">Payroll</a></li>
            <li class="active"><span>Tunjangan Karyawan</span></li>
        </ol>
        <div class="ml-auto">
            <div class="input-group">
                <a href="#" id="btn-add" class="btn btn-primary p-0 mr-1 text-white btn-icon" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tambah Data">
                    <span>
                        <i class="fa fa-plus"></i>
                    </span>
                </a>
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

    <!-- row -->
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <!-- Begin Form Edit Absen Karyawan -->

            <div id="data-pagging" class="card shadow">
                <div class="card-header bg-primary p-3">
                    <div class="card-title">CARI DATA</div>
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
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-primary p-1">
                </div>
            </div>

            <div id="data-add" class="card shadow">
                <div class="card-header bg-primary p-3">
                    <div class="card-title">INPUT DATA TUNJANGAN KARYAWAN</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="card-body m-0">

                    <!-- BEGIN FORM-->
                    {!! Form::open(['route' => 'hris.tunjangankaryawan.replace', 'id' => 'form1', 'name' => 'form1', 'method'=>'post']) !!}

                    <input type="hidden" id="kode_tunjangan" name="kode_tunjangan">
                    <table class="table table-striped table-sm mb-0">
                        <tr>
                            <th width="200px">PERIODE TUNJANGAN</th>
                            <th width="5px">:</th>
                            <td>
                                <div class="input-group">
                                    <select id="bulan" class="form-control">
                                        <option value="1">JAN</option>
                                        <option value="2">FEB</option>
                                        <option value="3">MAR</option>
                                        <option value="4">APR</option>
                                        <option value="5">MAY</option>
                                        <option value="6">JUN</option>
                                        <option value="7">JUL</option>
                                        <option value="8">AGU</option>
                                        <option value="9">SEP</option>
                                        <option value="10">OKT</option>
                                        <option value="11">NOV</option>
                                        <option value="12">DES</option>
                                    </select>
                                    <select id="tahun" class="form-control">
                                        <option value="2022">2022</option>
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                        <option value="2025">2025</option>
                                        <option value="2026">2026</option>
                                        <option value="2027">2027</option>
                                        <option value="2028">2028</option>
                                        <option value="2029">2029</option>
                                        <option value="2030">2030</option>
                                    </select>      
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>NOMOR ABSEN</th>
                            <th>:</th>
                            <td>
                                <div class="input-group">
                                    <input readonly type="text" class="form-control" id="enroll_id" name="enroll_id">
                                    <span class="input-group-append">
                                        <button class="btn btn-primary" type="button" id="btn-get-employee" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Pilih Dari Data Karyawan">
                                        PILIH KARYAWAN</button>
                                    </span>
                                </div>                                
                            </td>
                        </tr>                        
                        <tr>
                            <th>NIK</th>
                            <th>:</th>
                            <td><input readonly type="text" class="form-control" id="nik" name="nik"></td>
                        </tr>                        
                        <tr>
                            <th>NAMA KARYAWAN</th>
                            <th>:</th>
                            <td><input readonly type="text" class="form-control" id="employee_name" name="employee_name"></td>
                        </tr>                        
                        <tr>
                            <th>BAGIAN</th>
                            <th>:</th>
                            <td><input readonly type="text" class="form-control" id="sub_dept_name" name="sub_dept_name"></td>
                        </tr>                        
                        <tr>
                            <th>NAMA TUNJANGAN</th>
                            <th>:</th>
                            <td>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="nama_tunjangan" name="nama_tunjangan">
                                    <span class="input-group-append">
                                        <button class="btn btn-primary" type="button" id="btn-get-tunjangan" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Pilih Dari Data Tunjangan Yang Sudah Ada">PILIH TUNJANGAN</button>
                                    </span>
                                </div>                                
                            </td>
                        </tr>
                        <tr>
                            <th>NILAI (RP)</th>
                            <th>:</th>
                            <td><input type="text" class="form-control text-right" id="nilai_rupiah" name="nilai_rupiah"></td>
                        </tr>                        
                    </table>                                                    
                {{-- </form> --}}
                {!! Form::close() !!}
                <!-- END FORM-->
            </div>
            <div class="card-footer bg-primary pl-2 pt-1 pb-2">
                <button type="button" id="btn-save" class="btn btn-secondary btn-app mt-1">
                    <span>
                        <i class="fa fa-save"></i>
                    </span>
                    Simpan</button>
                <a href="{{route('hris.tunjangankaryawan.index')}}" id="btn-cancel" class="btn btn-warning btn-app mt-1">
                    <span>
                        <i class="fa fa-close"></i>
                    </span>
                    Cancel</a>
                <button type="button" id="btn-remove" class="btn btn-danger btn-app mt-1">
                    <span>
                        <i class="fa fa-trash"></i>
                    </span>
                    Hapus</button>
            </div>   
        </div>        
    </div>
    <!-- row end -->
</div>

<!-- boostrap show department model -->
<div class="modal fade" id="ajax-modal-pilih1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary p-2">
                <h4 class="modal-title pl-2"><b>DATA KARYAWAN</b></h4>
                <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                    <i class="fa fa-remove"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="datatable-ajax-modal" class="table table-sm table-striped table-hover table-bordered w-100">
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
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-primary p-1">
                <div class="btn-list">
                    <button type="button" id="btn-close" class="btn btn-warning btn-app" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
      </div>
</div>
<!-- end bootstrap model -->

<!-- boostrap show department model -->
<div class="modal fade" id="ajax-modal-pilih2" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary p-2">
                <h4 class="modal-title pl-2"><b>DATA NAMA TUNJANGAN</b></h4>
                <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                    <i class="fa fa-remove"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="datatable-ajax-modal2" class="table table-sm table-striped table-hover table-bordered w-100">
                        <thead>
                            <tr class="text-center">
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-primary p-1">
                <div class="btn-list">
                    <button type="button" id="btn-close" class="btn btn-warning btn-app" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
      </div>
</div>
<!-- end bootstrap model -->
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

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#progress-show-1').hide();
        
        $('body').on('click', '#btn-refresh-page', function (event) {
            location.reload();
        });

        $('body').on('click', '#btn-add', function (event) {
            $("#data-pagging").hide();    
            $("#data-add").show();    

            var d = new Date(), n = d.getMonth(), y = d.getFullYear(); 
            
            $("#bulan").val(n).trigger("change");
            $("#tahun").val(y).trigger("change");
            var kode_tunjangan = $("#kode_tunjangan").val()
            if(kode_tunjangan) { } else { $("#btn-remove").attr("disabled", true); }
            
        });
        
        $('body').on('change', '#bulan', function (event) {            
            var bulan = $("#bulan").val();
            var tahun = $("#tahun").val();

        });

        $('body').on('change', '#tahun', function (event) {
            var bulan = $("#bulan").val();
            var tahun = $("#tahun").val();

        });

        $('body').on('click', '#btn-get-employee', function (event) {
            $("#ajax-modal-pilih1").modal('show');    
            $('#datatable-ajax-modal').DataTable().clear();
            $('#datatable-ajax-modal').DataTable().destroy();
            $('#datatable-ajax-modal').empty();
            
            getemployee();
        });

        $('body').on('click', '#btn-get-tunjangan', function (event) {
            $("#ajax-modal-pilih2").modal('show');    
            $('#datatable-ajax-modal2').DataTable().clear();
            $('#datatable-ajax-modal2').DataTable().destroy();
            $('#datatable-ajax-modal2').empty();
            
            getnamatunjangan();
        });

        function getemployee() {
            var table1 = $('#datatable-ajax-modal').DataTable({
                processing: true,
                serverSide: true,
                lengthChange: false,
                destroy: true,
                "ajax": {
                    "url": "{{ route('hris.tunjangankaryawan.ajax_getemployee') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                },
                columns: [
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
                        title: 'NAMA KARYAWAN',
                        data: 'employee_name',
                        name: 'employee_name'
                    },
                    {
                        title: 'BAGIAN',
                        data: 'sub_dept_name',
                        name: 'sub_dept_name'
                    },
                    {
                        title: 'STAFF',
                        data: 'status_staff',
                        name: 'status_staff'
                    },
                    {
                        title: 'AKTIF',
                        data: 'status_aktif',
                        name: 'status_aktif'
                    },
                    {
                        title: 'TANGGAL MASUK',
                        data: 'join_date',
                        name: 'join_date'
                    },
                    {
                        title: 'TANGGAL RESIGN',
                        data: 'tanggal_resign',
                        name: 'tanggal_resign'
                    },
                ],
                columnDefs: [
                    {
                        'visible': false,
                        'targets': []
                    }
                ],
                order: [
                    [2, 'asc']
                ],
                "createdRow": function (row, data, dataIndex) {
                    if (data['status_aktif'] == "TIDAK AKTIF") {
                            $(row).css('background', 'red');
                    }                    
                }
            });

            table1.draw();

            $('#datatable-ajax-modal tbody').on('click', 'tr', function () {
                var tr = $(this).closest('tr');
                var row = table1.row(tr);

                var data = row.data();

                $("#datatable-ajax-modal tbody tr").removeClass('bg-cyan');
                $(this).addClass('bg-cyan');


             });      
             
            $('#datatable-ajax-modal tbody').on('dblclick', 'tr', function () {
                var tr = $(this).closest('tr');
                var row = table1.row(tr);

                var data = row.data();
                $('#enroll_id').val(data['enroll_id']);
                $('#nik').val(data['nik']);
                $('#employee_name').val(data['employee_name']);                    
                $('#sub_dept_name').val(data['sub_dept_name']);                    
                $("#ajax-modal-pilih1").modal('hide');    
                        
            });      
             
        }

        function getnamatunjangan() {
            var table1 = $('#datatable-ajax-modal2').DataTable({
                processing: true,
                serverSide: true,
                lengthChange: false,
                destroy: true,
                "ajax": {
                    "url": "{{ route('hris.tunjangankaryawan.ajax_getnamatunjangan') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                },
                columns: [
                    {
                        title: 'NAMA TUNJANGAN',
                        data: 'nama_tunjangan',
                        name: 'nama_tunjangan'
                    },
                ],
                columnDefs: [
                    {
                        'visible': false,
                        'targets': []
                    }
                ],
                order: [
                    [0, 'asc']
                ]
            });

            table1.draw();

            $('#datatable-ajax-modal2 tbody').on('click', 'tr', function () {
                var tr = $(this).closest('tr');
                var row = table1.row(tr);

                var data = row.data();

                $("#datatable-ajax-modal2 tbody tr").removeClass('bg-cyan');
                $(this).addClass('bg-cyan');


             });      
             
            $('#datatable-ajax-modal2 tbody').on('dblclick', 'tr', function () {
                var tr = $(this).closest('tr');
                var row = table1.row(tr);

                var data = row.data();
                $('#nama_tunjangan').val(data['nama_tunjangan']);
                        
                $("#ajax-modal-pilih2").modal('hide');    

            });      
             
        }
        
        $('body').on('click', '#btn-save', function (event) {
            var kode_tunjangan = $("#kode_tunjangan").val();
            var tahun = $("#tahun").val();
            var bulan = $("#bulan").val();
            var enroll_id = $("#enroll_id").val();
            var nik = $("#nik").val();
            var employee_name = $("#employee_name").val();
            var nama_tunjangan = $("#nama_tunjangan").val();
            var nilai_rupiah = $("#nilai_rupiah").val();

            $('#btn-save').addClass("btn-loading");
            $("#btn-save").html('Please wait...');
            $("#btn-save").attr("disabled", true);
            $('#progress-show-1').show();
            $('#progress-hide-1').hide();

            $.ajax({
                type:"POST",
                url: "{{route('hris.tunjangankaryawan.replace')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    kode_tunjangan:kode_tunjangan,
                    tahun:tahun,
                    bulan:bulan,
                    enroll_id:enroll_id,
                    nik:nik,
                    employee_name:employee_name,
                    nama_tunjangan:nama_tunjangan,
                    nilai_rupiah:nilai_rupiah,
                },
                dataType: 'json',
                success: function(res){
                    notif({
                        msg: "<b>Info:</b> Data berhasil di simpan.",
                        type: "info"
                    });    
                },
                error: function(res){
                    notif({
                        msg: "<b>Error:</b> Oops data gagal di simpan.",
                        type: "error"
                    });    
                }
            });

            $('#progress-show-1').hide();
            $('#progress-hide-1').show();
            $('#btn-save').removeClass("btn-loading");
            $("#btn-save").html('<span><i class="fa fa-save"></i></span> Add');
            setTimeout(function myFunction() {
                location.reload();
              }, 3000);           
                                 
        });

        $('body').on('click', '#btn-remove', function (event) {
            var kode_tunjangan = $("#kode_tunjangan").val();

            if (kode_tunjangan.length > 0) {
                message = "Anda Yakin Ingin Menghapus " + kode_tunjangan + " !!!";
                type = "warning";
                swal({
                    title: message,
                    type: type,
                    showCancelButton: true,
                    confirmButtonText: 'Saya Yakin',
                    cancelButtonText: 'Tutup'
                },function(isConfirm){
                    if(isConfirm) {

                        $("#form1 :input").prop("disabled", true);
                        $("#btn-save").prop("disabled", true);
                        $("#btn-cancel").prop("disabled", true);    
            
                        $.ajax({
                            type:"POST",
                            url: "{{route('hris.tunjangankaryawan.destroy')}}",
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            data: {
                                kode_tunjangan:kode_tunjangan,
                            },
                            dataType: 'json',
                            success: function(res){
                                notif({
                                    msg: "<b>Info:</b> Data berhasil di hapus.",
                                    type: "info"
                                });    
                            },
                            error: function(res){
                                notif({
                                    msg: "<b>Error:</b> Oops data gagal di hapus.",
                                    type: "error"
                                });    
                            }
                        });
            
                        setTimeout(function myFunction() {
                            location.reload();
                        }, 3000);                   

                    } else {
                        // else everythings
                    }
                });
            } else {
                notif({
                    msg: "<b>Warning:</b> Data yang dipilih tidak ada.",
                    type: "warning"
                });
            }
                    
        });
        
        $(document).ready(function() {

            $("#data-add").hide();    

            var table1 = $('#datatable-ajax-crud').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                "ajax": {
                    "url": "{{ route('hris.tunjangankaryawan.ajax_data') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                },
                columns: [
                    {
                        title: 'KODE TUNJANGAN',
                        data: 'kode_tunjangan',
                        name: 'kode_tunjangan'
                    },
                    {
                        title: 'PERIODE PAYROLL',
                        data: 'periode_payroll',
                        name: 'periode_payroll'
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
                        title: 'NAMA KARYAWAN',
                        data: 'employee_name',
                        name: 'employee_name'
                    },
                    {
                        title: 'NAMA TUNJANGAN',
                        data: 'nama_tunjangan',
                        name: 'nama_tunjangan'
                    },
                    {
                        title: 'NILAI (RP)',
                        data: 'nilai_rupiah_format',
                        name: 'nilai_rupiah_format'
                    },
                    {
                        title: 'TERAKHIR DI UBAH',
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                ],
                columnDefs: [
                    {
                        'visible': false,
                        'targets': [0]
                    },
                    {
                        targets: 6,
                        className: 'text-right'
                    }
                ],
                order: [
                    [1, 'desc'],[4, 'asc']
                ]
            });

            table1.draw();

            $('#datatable-ajax-crud tbody').on('click', 'tr', function () {
                var tr = $(this).closest('tr');
                var row = table1.row(tr);

                var data = row.data();
        
                $("#datatable-ajax-crud tbody tr").removeClass('bg-cyan');
                $(this).addClass('bg-cyan');

             });

            $('#datatable-ajax-crud tbody').on('dblclick', 'tr', function () {
                var tr = $(this).closest('tr');
                var row = table1.row(tr);

                var data = row.data();
                var kode_tunjangan = data['kode_tunjangan'];

                $("#btn-get-employee").attr("disabled", false);
                
                $("#data-pagging").hide();    
                $("#data-add").show();    
                        
                $.ajax({
                    type:"POST",
                    url: "{{route('hris.tunjangankaryawan.edit')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        kode_tunjangan:kode_tunjangan,
                    },
                    dataType: 'json',
                    success: function(res){          
                                
                        $("#kode_tunjangan").val(res[0].kode_tunjangan);
                        $("#bulan").val(res[0].periode_bulan).trigger("change");
                        $("#tahun").val(res[0].periode_tahun).trigger("change");
                        $("#enroll_id").val(res[0].enroll_id);
                        $("#nik").val(res[0].nik);
                        $("#employee_name").val(res[0].employee_name);
                        $("#nilai_rupiah").val(res[0].nilai_rupiah);
                        $("#nama_tunjangan").val(res[0].nama_tunjangan);
                        $("#sub_dept_name").val(res[0].sub_dept_name);
                        if(res[0].kode_tunjangan) { $("#btn-get-employee").attr("disabled", true); }
                    },
                    error: function(res){

                    }
                });

                        
            });      
             
        });
        
    </script>

@endsection
