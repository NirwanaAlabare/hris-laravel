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
            <li><a href="#">BPJS Karyawan</a></li>
            <li class="active"><span>Dasar Pot</span></li>
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
        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <!-- Begin Form Edit Absen Karyawan -->

            <div id="data-gagal-absen" class="card shadow">
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
        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <!-- Begin Form Edit Absen Karyawan -->
            <div id="data-gagal-absen" class="card shadow" id="datatable-data-karyawan">
                <div class="card-header bg-primary p-3">
                    <div class="card-title">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle pl-1 pt-0 pb-0 pr-1 mr-1 text-sm" data-toggle="dropdown">
                                <i class="fa fa-navicon"></i>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="javascript:void(0)" id="btn-print"><i class="fa fa-print"></i> Print</a></li>
                                <li><a href="javascript:void(0)" id="btn-add"><i class="fa fa-plus"></i> Tambah</a></li>
                                <li><a href="javascript:void(0)" id="btn-edit"><i class="fa fa-edit"></i> Edit</a></li>
                                <li><a href="javascript:void(0)" id="btn-remove"><i class="fa fa-remove"></i> Hapus</a></li>
                            </ul>
                        </div>                        
                        DASAR POT BPJS
                    </div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                    </div>
                </div>
                <div class="progress progress-xs mb-0">
                    <div id="progress-show-1" class="progress-bar progress-bar-indeterminate bg-green"></div>
                    <div id="progress-hide-1" class="progress-bar"></div>
                </div>
                <div class="card-body">
                    <!-- BEGIN FORM-->
                    {!! Form::open(['route' => 'hris.refharilibur.replace', 'id' => 'form1', 'name' => 'form1', 'method'=>'post']) !!}

                    <table class="table table-striped table-sm mb-0">
                        <tr>
                            <th class="w-35">KODE DASAR POT BPJS</th>
                            <td class="w-65"><input type="text" class="form-control" id="kode_dasar_pot_bpjs" name="kode_dasar_pot_bpjs"></td>
                        </tr>
                        <tr>
                            <th class="w-35">NAMA DASAR POT BPJS</th>
                            <td class="w-65"><input type="text" class="form-control" id="nama_dasar_pot_bpjs" name="nama_dasar_pot_bpjs"></td>
                        </tr>
                        <tr>
                            <th class="w-35">DASAR POT BPJS (RP)</th>
                            <td class="w-65"><input type="text" class="form-control" id="dasar_pot_bpjs_rupiah" name="dasar_pot_bpjs_rupiah"></td>
                        </tr>
                        <tr>
                            <th class="w-35">OPERATOR</th>
                            <td class="w-65"><input readonly type="text" class="form-control" id="operator" name="operator"></td>
                        </tr>
                        <tr>
                            <th class="w-35">TERAKHIR DI UBAH</th>
                            <td class="w-65"><input readonly type="text" class="form-control" id="updated_at" name="updated_at"></td>
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
                    <button type="button" id="btn-cancel" class="btn btn-warning btn-app mt-1">
                        <span>
                            <i class="fa fa-close"></i>
                        </span>
                        Cancel</button>
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

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#progress-show-1').hide();
        $("#form1 :input").prop("disabled", true);
        $("#btn-save").prop("disabled", true);
        $("#btn-cancel").prop("disabled", true);

        $('body').on('click', '#btn-refresh-page', function (event) {
            location.reload();
        });

        $('body').on('click', '#btn-add', function (event) {
            var id = $("#id").val();
            
            $("#form1 :input").prop("disabled", false);
            $("#btn-save").prop("disabled", false);
            $("#btn-cancel").prop("disabled", false);
            $("#form1").trigger('reset');
            $('#operator').val("");
            $('#updated_at').val("");    

        });
        
        $('body').on('click', '#btn-edit', function (event) {
            var kode_dasar_pot_bpjs = $("#kode_dasar_pot_bpjs").val();
            
            if (kode_dasar_pot_bpjs.length > 0) {
                $("#form1 :input").prop("disabled", false);
                $("#btn-save").prop("disabled", false);
                $("#btn-cancel").prop("disabled", false);
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
            var kode_dasar_pot_bpjs = $("#kode_dasar_pot_bpjs").val();
            var nama_dasar_pot_bpjs = $("#nama_dasar_pot_bpjs").val();
            var dasar_pot_bpjs_rupiah = $("#dasar_pot_bpjs_rupiah").val();

            $('#btn-save').addClass("btn-loading");
            $("#btn-save").html('Please wait...');
            $("#btn-save").attr("disabled", true);
            $('#progress-show-1').show();
            $('#progress-hide-1').hide();

            $.ajax({
                type:"POST",
                url: "{{route('hris.dasarpotbpjs.replace')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    kode_dasar_pot_bpjs:kode_dasar_pot_bpjs,
                    nama_dasar_pot_bpjs:nama_dasar_pot_bpjs,
                    dasar_pot_bpjs_rupiah:dasar_pot_bpjs_rupiah,
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
            $("#form1 :input").prop("disabled", true);
            $("#btn-save").prop("disabled", true);
            $("#btn-cancel").prop("disabled", true);    
            setTimeout(function myFunction() {
                location.reload();
              }, 3000);           
                                 
        });

        $('body').on('click', '#btn-remove', function (event) {
            var kode_dasar_pot_bpjs = $("#kode_dasar_pot_bpjs").val();
            var nama_dasar_pot_bpjs = $("#nama_dasar_pot_bpjs").val();

            if (kode_dasar_pot_bpjs.length > 0) {
                message = "Anda Yakin Ingin Menghapus " + nama_dasar_pot_bpjs + " !!!";
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
                            url: "{{route('hris.dasarpotbpjs.destroy')}}",
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            data: {
                                kode_dasar_pot_bpjs:kode_dasar_pot_bpjs,
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
            
                        $('#progress-show-1').hide();
                        $('#progress-hide-1').show();    
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
            var table1 = $('#datatable-ajax-crud').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                "ajax": {
                    "url": "{{ route('hris.dasarpotbpjs.ajax_data') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                },
                columns: [
                    {
                        title: 'KODE DASAR POT BPJS',
                        data: 'kode_dasar_pot_bpjs',
                        name: 'kode_dasar_pot_bpjs'
                    },
                    {
                        title: 'NAMA DASAR POT',
                        data: 'nama_dasar_pot_bpjs',
                        name: 'nama_dasar_pot_bpjs'
                    },
                    {
                        title: 'DASAR POT (RP)',
                        data: 'dasar_pot_bpjs_rupiah',
                        name: 'dasar_pot_bpjs_rupiah'
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
                        'targets': []
                    }
                ],
                order: [
                    [3, 'desc']
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
                
                $("#datatable-ajax-crud tbody tr").removeClass('bg-cyan');
                $(this).addClass('bg-cyan');

                $('#kode_dasar_pot_bpjs').val(data['kode_dasar_pot_bpjs']);
                $('#nama_dasar_pot_bpjs').val(data['nama_dasar_pot_bpjs']);
                $('#dasar_pot_bpjs_rupiah').val(data['dasar_pot_bpjs_rupiah']);
                $('#operator').val(data['operator']);
                $('#updated_at').val(data['updated_at']);

             });

        });
        
    </script>

@endsection
