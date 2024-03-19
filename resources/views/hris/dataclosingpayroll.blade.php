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

    <!-- Time picker css-->
    <link href="{{URL::asset('assets/plugins/time-picker/jquery.timepicker.css')}}" rel="stylesheet" />

	<!---Sweetalert Css-->
	<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

    <style>
        .table-responsive{
            height:500px;
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
    <div class="page-header p-2 shadow">
        <ol class="breadcrumb breadcrumb-arrow mt-0">
            <li><a href="#">PAYROLL</a></li>
            <li class="active"><span>DATA CLOSING</span></li>
        </ol>
        <div class="ml-auto">
            <div class="input-group">
                <a href="#" id="btn-exportexcel" class="btn btn-primary p-0 mr-1 text-white btn-icon" data-toggle="tooltip"
                    title="" data-placement="bottom" data-original-title="Export Data to Excel">
                    <span>
                        <i class="fa fa-file-excel-o"></i>
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
        <div class="col-sm-12 col-md-12 col-lg-7 col-xl-7">
            <!-- Begin Form Edit Absen Karyawan -->

            <div id="data-cari-koreksiupah" class="card shadow">
                <div class="card-header bg-primary p-3">
                    <div class="card-title">CARI DATA</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="card-body m-0">
                    <div class="table-responsive">
                        <table id="datatable-ajax-crud"
                            class="table table-sm table-striped table-hover w-100">
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
        <div class="col-sm-12 col-md-12 col-lg-5 col-xl-5">
            <!-- Begin Form Edit Absen Karyawan -->
            <div id="data-add-koreksiupah" class="card shadow">
                <div class="card-header bg-primary p-2">
                    <div class="card-title">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle pl-1 pt-0 pb-0 pr-1 mr-1 text-sm" data-toggle="dropdown">
                                <i class="fa fa-navicon"></i>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="javascript:void(0)" id="btn-add"><i class="fa fa-plus"></i> Tambah</a></li>
                                <li><a href="javascript:void(0)" id="btn-edit"><i class="fa fa-save"></i> Edit</a></li>
                                <li><a href="javascript:void(0)" id="btn-remove"><i class="fa fa-remove"></i> Hapus</a></li>
                            </ul>
                        </div>                        
                        DATA CLOSING PAYROLL
                    </div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- BEGIN FORM-->
                    {!! Form::open(['route' => 'hris.koreksiupah.create', 'id' => 'form1', 'name' => 'form1', 'method'=>'post']) !!}

                    <input id="uuid" type="hidden">
                    <input id="enroll_id" type="hidden">
                    <input id="nik" type="hidden">
                    <input id="employee_name" type="hidden">
                    <input id="site_nirwana_id" type="hidden">
                    <input id="site_nirwana_name" type="hidden">
                    <input id="department_id" type="hidden">
                    <input id="department_name" type="hidden">
                    <input id="sub_dept_id" type="hidden">
                    <input id="sub_dept_name" type="hidden">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">KODE CLOSING</label>
                                <input readonly id="kode_closing" name="kode_closing" type="text" class="form-control" placeholder="KODE CLOSING" maxlength="50" size="50">
                            </div>
                            <div class="form-group">
                                <label class="form-label">NAMA CLOSING</label>
                                <input id="nama_closing" name="nama_closing" type="text" class="form-control" placeholder="NAMA CLOSING" maxlength="50" size="50">
                            </div>
                            <div class="form-group">
                                <label class="form-label">PERIODE CLOSING : </label>
                                <input type="hidden" id="periode_payroll" name="periode_payroll">
                                <a class="p-2 nav-link card-title text-lg" id="daterange-btn1" data-toggle="tooltip"
                                title="" data-placement="bottom" data-original-title="Klik di sini untuk pilih tanggal kehadiran">
                                </a>            
                            </div>
                            <div class="form-group">
                                <label class="form-label">TEMPORARY : </label>
                                <select id="istemp" name="istemp" class="form-control">
                                    <option value="0" selected>FALSE</option>
                                    <option value="1">TRUE</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">PERMANENT : </label>
                                <select id="ispermanent" name="ispermanent" class="form-control">
                                    <option value="0" selected>FALSE</option>
                                    <option value="1">TRUE</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">CATATAN : </label>
                                <textarea class="form-control mb-2" id="catatan" name="catatan" rows="2" placeholder="Catatan"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">OPERATOR</label>
                                <input readonly disabled id="operator" name="operator" type="text" class="form-control" placeholder="Operator" maxlength="50" size="50">
                            </div>
                            <div class="form-group">
                                <label class="form-label">TANGGAL DIBUAT</label>
                                <input readonly disabled id="created_at" name="created_at" type="text" class="form-control" placeholder="Tanggal dibuat" maxlength="50" size="50">
                            </div>
                            <div class="form-group">
                                <label class="form-label">TANGGAL DIUBAH</label>
                                <input readonly disabled id="updated_at" name="updated_at" type="text" class="form-control" placeholder="Tanggal terakhir di ubah" maxlength="50" size="50">
                            </div>
                        </div>
                    </div>

                    {{-- </form> --}}
                    {!! Form::close() !!}
                    <!-- END FORM-->
                </div>
                <div class="progress progress-xs mb-0">
                    <div id="progress-show-1" class="progress-bar progress-bar-indeterminate bg-green"></div>
                    <div id="progress-hide-1" class="progress-bar"></div>
                </div>
                <div class="card-footer bg-primary pl-2 pt-1 pb-2">
                    <button type="button" id="btn-save" class="btn btn-secondary btn-app mt-1">
                        <span>
                            <i class="fa fa-save"></i>
                        </span>
                        SIMPAN</button>
                    <button type="button" id="btn-cancel" class="btn btn-warning btn-app mt-1">
                        <span>
                            <i class="fa fa-close"></i>
                        </span>
                        CANCEL</button>
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

    <!-- Timepicker js -->
    <script src="{{URL::asset('assets/plugins/time-picker/jquery.timepicker.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/time-picker/toggles.min.js')}}"></script>

    <!-- Sweet alert js-->
    <script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

    <script type="text/javascript">

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.fc-datepicker').datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'yy-mm-dd'
        });

        function defaultDate(s) {
            if(s) {
                var bits = s.split('/');
                var d = bits[2] + '-' + bits[0] + '-' + bits[1];
            }
            return d;
        }

        //Date range as a button
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
            $('#daterange-btn1').html('<span><i class="fa fa-calendar"></i> ' + start.format("D MMM YYYY").toUpperCase() + ' s/d ' + end.format("D MMM YYYY").toUpperCase() + '</span>');
            var daterange1 = start.format("YYYY-MM-DD") + " s/d " + end.format("YYYY-MM-DD");
            $('#periode_payroll').val(daterange1);

        })

        $('#progress-show-1').hide();
        $('#data-karyawan').hide();
        $("#form1 :input").prop("disabled", true);
        $("#btn-save").prop("disabled", true);
        $("#btn-cancel").prop("disabled", true);
        $("#periode_tanggal_koreksi").val('');
        
        $('body').on('keyup', '#jumlah_rp_potongan', function (event) {
            this.value = this.value.replace(/[^-0-9\.]/g,'');
        });

        $('body').on('click', '#btn-refresh-page', function (event) {
            location.reload();
        });

        $('body').on('click', '#btn-add', function (event) {
            $("#form1 :input").prop("disabled", false);
            $("#btn-save").prop("disabled", false);
            $("#btn-cancel").prop("disabled", false);
            $("#form1").trigger('reset');
            $('#btn-save').html('<i class="fa fa-save"></i> SIMPAN');
            $('#daterange-btn1').show();
            $('#daterange-btn1').removeClass('disabled');

            var start = moment().subtract(29, 'days');
            var end = moment();
            var htmlDateRange = '<span><i class="fa fa-calendar"></i> ' + start.format("D MMM YYYY").toUpperCase() + ' s/d ' + end.format("D MMM YYYY").toUpperCase() + '</span>';
            var daterange1 = start.format("YYYY-MM-DD") + " s/d " + end.format("YYYY-MM-DD");

            $('#daterange-btn1').html(htmlDateRange);
            $('#periode_payroll').text(daterange1);

        });

        $('body').on('click', '#btn-edit', function (event) {
            var kode_closing = $("#kode_closing").val();
            
            if (kode_closing.length > 0) {
                $("#form1 :input").prop("disabled", false);
                $("#btn-save").prop("disabled", false);
                $("#btn-cancel").prop("disabled", false);
                $('#btn-save').html('<i class="fa fa-save"></i> SIMPAN');
                $('#daterange-btn1').show();
                $('#daterange-btn1').removeClass('disabled');
    
            } else {
                notif({
                    msg: "<b>Warning:</b> Data yang dipilih tidak ada.",
                    type: "warning"
                });
            }
        });
        
        $('body').on('click', '#btn-cancel', function (event) {
            location.reload();
        });

        $('body').on('click', '#btn-save', function (event) {

            var kode_closing = $("#kode_closing").val();
            var nama_closing = $("#nama_closing").val();
            var periode_payroll = $("#periode_payroll").val();
            var istemp = $("#istemp").val();
            var ispermanent = $("#ispermanent").val();
            var start_periode = $("#start_periode").val();
            var end_periode = $("#end_periode").val();
            var catatan = $("#catatan").val();

            $('#btn-save').addClass("btn-loading");
            $("#btn-save").html('Please wait...');
            $("#btn-save").attr("disabled", true);
            $('#progress-show-1').show();
            $('#progress-hide-1').hide();
            
            if (kode_closing) {

                $.ajax({
                    type:"POST",
                    url: "{{route('hris.dataclosingpayroll.update')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        kode_closing:kode_closing,
                        nama_closing:nama_closing,
                        periode_payroll:periode_payroll,
                        istemp:istemp,
                        ispermanent:ispermanent,
                        start_periode:start_periode,
                        end_periode:end_periode,
                        catatan:catatan,
                    },
                    dataType: 'json',
                    success: function(res){
                        notif({
                            msg: "<b>Info:</b> Data berhasil di UPDATE.",
                            type: "info"
                        });    
                    },
                    error: function(res){
                        notif({
                            msg: "<b>Error:</b> Oops data gagal di UPDATE.",
                            type: "error"
                        });    
                    }
                });

            } else {

                $.ajax({
                    type:"POST",
                    url: "{{route('hris.dataclosingpayroll.create')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        nama_closing:nama_closing,
                        periode_payroll:periode_payroll,
                        istemp:istemp,
                        ispermanent:ispermanent,
                        start_periode:start_periode,
                        end_periode:end_periode,
                        catatan:catatan,
                    },
                    dataType: 'json',
                    success: function(res){
                        notif({
                            msg: "<b>Info:</b> Data berhasil di SIMPAN.",
                            type: "info"
                        });    
                    },
                    error: function(res){
                        notif({
                            msg: "<b>Error:</b> Oops data gagal di SIMPAN.",
                            type: "error"
                        });    
                    }
                });
            }

            $('#progress-show-1').hide();
            $('#progress-hide-1').show();
            $('#btn-save').removeClass("btn-loading");
            $("#btn-save").html('<span><i class="fa fa-save"></i></span> SIMPAN');
            $("#form1 :input").prop("disabled", true);
            $("#btn-save").prop("disabled", true);
            $("#btn-cancel").prop("disabled", true);    
            
            setTimeout(function myFunction() {
                location.reload();
            }, 3000);           
                        
        });

        $('body').on('click', '#btn-remove', function (event) {

            var kode_closing = $("#kode_closing").val();
            var periode_payroll = $("#periode_payroll").val();

            message = "Anda Yakin Ingin Menghapus Periode Closing " + periode_payroll + " !!!";
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
                    $('#progress-show-1').show();
                    $('#progress-hide-1').hide();
        
                    $.ajax({
                        type:"POST",
                        url: "{{route('hris.dataclosingpayroll.destroy')}}",
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        data: {
                            kode_closing:kode_closing,
                        },
                        dataType: 'json',
                        success: function(res){
                            notif({
                                msg: "<b>Info:</b> Data berhasil di HAPUS.",
                                type: "info"
                            });    
                        },
                        error: function(res){
                            notif({
                                msg: "<b>Error:</b> Oops data gagal di HAPUS.",
                                type: "error"
                            });    
                        }
                    });
        
                    $('#progress-show-1').hide();
                    $('#progress-hide-1').show();    

                    setTimeout(function myFunction() {
                        location.reload();
                    }, 3000);                   

                } else {
                    // else everythings
                }
            });
           
        });
        
        $(document).ready(function() {
            $('#daterange-btn1').hide();
            var table1 = $('#datatable-ajax-crud').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                destroy: true,
                "language": {
                    processing: '<center><div class="dimmer active"><div class="lds-hourglass p-0 m-0"></div></div> Mohon untuk menunggu...</center> '},
                "ajax": {
                    "url": "{{ route('hris.dataclosingpayroll.ajax_data') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                },
                columns: [
                    {
                        title: 'KODE CLOSING',
                        data: 'kode_closing',
                        name: 'kode_closing'
                    },
                    {
                        title: 'NAMA CLOSING',
                        data: 'nama_closing',
                        name: 'nama_closing'
                    },
                    {
                        title: 'PERIODE CLOSING',
                        data: 'periode_payroll_format',
                        name: 'periode_payroll_format'
                    },
                    {
                        title: 'TEMPORARY',
                        data: 'istemp_format',
                        name: 'istemp_format'
                    },
                    {
                        title: 'PERMANENT',
                        data: 'ispermanent_format',
                        name: 'ispermanent_format'
                    },
                    {
                        title: 'CATATAN',
                        data: 'catatan',
                        name: 'catatan'
                    },
                    {
                        title: 'OPERATOR',
                        data: 'operator',
                        name: 'operator'
                    },
                    {
                        title: 'PERUBAHAN TERAKHIR',
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                ],
                columnDefs: [
                    {
                        'visible': false,
                        'targets': [0,5,7]
                    },
                    {
                        orderable: false,
                        targets: [1,2,3,4,6]
                    },
                    {
                        className: "w-5 text-center text-nowrap",
                        targets: [3,4,6]
                    },
                    {
                        className: "w-50 text-nowrap",
                        targets: [1,2]
                    }
                ],
                order: [
                    [7, 'desc']
                ]
            });

            table1.draw();

            $('#datatable-ajax-crud tbody').on('click', 'tr', function () {
                var tr = $(this).closest('tr');
                var row = table1.row(tr);

                var data = row.data();
        
                $("#form1 :input").prop("disabled", true);
                $("#btn-save").prop("disabled", true);
                $("#btn-cancel").prop("disabled", true);
                $('#daterange-btn1').show();
                $('#daterange-btn1').addClass('disabled');

                $("#datatable-ajax-crud tbody tr").removeClass('bg-cyan');
                $(this).addClass('bg-cyan');

                $("#kode_closing").val(data["kode_closing"]);
                $("#nama_closing").val(data["nama_closing"]);
                var daterangebtn = data["periode_payroll_format"];
                var periode_payroll = data["periode_payroll"];

                var htmlDateRange = '<span><i class="fa fa-calendar"></i> ' + daterangebtn.toUpperCase() + '</span>'
    
                $('#daterange-btn1').html(htmlDateRange);
                $('#periode_payroll').val(periode_payroll);
    
                $("#istemp").val(data["istemp"]);
                $("#ispermanent").val(data["ispermanent"]);
                $("#start_periode").val(data["start_periode"]);
                $("#end_periode").val(data["end_periode"]);
                $("#catatan").val(data["catatan"]);
                $("#operator").val(data["operator"]);
                $("#created_at").val(data["created_at"]);
                $("#updated_at").val(data["updated_at"]);
                $("#deleted_at").val(data["deleted_at"]);
    
             });

        });
        
    </script>

@endsection
