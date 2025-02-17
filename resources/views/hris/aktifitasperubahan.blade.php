@extends('admin.adminlayouts.adminlayout')

@section('head')
    <!-- Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet" />

    <!--Select2 css -->
    <link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />

	<!-- Notifications  css -->
	<link href="{{URL::asset('assets/plugins/notify-growl/css/jquery.growl.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/notify-growl/css/notifIt.css')}}" rel="stylesheet" />

    <!-- Date Picker css-->
    <link href="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.css')}}" rel="stylesheet" />

	<!---Sweetalert Css-->
	<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
    <style>
        tr#element:hover{
            color:azure;
            background-color: rgb(0, 0, 255);
            cursor: pointer;

        }
        .wrapper {
            width: 100%;
            height: 550px;
            overflow: auto;
            position: relative;
        }

        #table-responsive1 {
            table-layout: fixed;
            max-height:510px;
        }

        thead,
        tr>th {
            position: sticky;
            background: pink;
        }

        thead {
            top: 0;
            z-index: 2;
        }
        tr>th {
            left: 0;
            z-index: 1;
        }
        thead tr>th:first-child {
            z-index: 3;
        }
        .timestamp {
            display: inline-block;
            background: #4CAF50;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }
    </style>
@stop
@section('mainarea')
    <!-- page-header -->
    <div class="page-header p-2 shadow">
        <ol class="breadcrumb breadcrumb-arrow mt-0">
            <li><a href="#">Perhitungan</a></li>
            <li class="active"><span>Aktifitas Payroll Karyawan</span></li>
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

    {!! Form::open(['route' => 'hris.rekapperhitunganpayroll.ajax_exportexcel', 'id' => 'formExport', 'name' => 'formExport','method'=>'post']) !!}
    @csrf
    <input id="selDepVal" name="selDepVal" type="hidden">
    <div class="card shadow">
        <div class="card-header bg-primary py-1 pl-1">
            <ul class="nav nav-tabs mx-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active py-2" id="waiting-tab" data-toggle="tab" href="#waiting" role="tab" aria-controls="waiting" aria-selected="true" style="font-weight: bold; font-size:11pt">Aktifitas Perubahan</a>
                </li>
            </ul>
        </div>
            <div class="col-12 text-dark">
                <div  class="table-responsive">
                    <table style="width:2700px" id="datatable-ajax-crud" class="table table-sm table-striped table-hover table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th style="background-color: white" scope="col"></th>
                                <th style="background-color: white;" scope="col"></th>
                                <th style="background-color: white;" scope="col"></th>
                                <th style="background-color: white" scope="col"></th>
                                <th style="background-color: white" scope="col"></th>
                                <th style="background-color: white" scope="col"></th>
                                <th style="background-color: white" scope="col"></th>
                                <th style="background-color: white" scope="col"></th>
                                <th  style="background-color: white" scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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
    <script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>

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


    </script>
    </script>

    <!-- Andri -->
    <script>
        function rekapperhitunganpayroll(){
            var table1 = $('#datatable-ajax-crud').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                "ajax": {
                    "url": "{{ route('hris.aktifitasperubahan.ajax_data') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                },
                columns: [
                    {
                        title: 'ID',
                        data: 'activity_log_id',
                        name: 'activity_log_id'
                    },
                    {
                        title: 'Dibuat',
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        title: 'Dibuat Oleh',
                        data: 'action_by_name',
                        name: 'action_by_name',
                        width: "100px"
                    },
                    {
                        title: 'Tujuan',
                        data: 'log_name',
                        name: 'log_name'
                    },
                    {
                        title: 'Table',
                        data: 'table_name',
                        name: 'table_name'
                    },
                    {
                        title: 'Record Id',
                        data: 'record_id',
                        name: 'record_id'
                    },
                    {
                        title: 'Aksi',
                        data: 'action',
                        name: 'action'
                    },
                    {
                        title: 'Data Lama',
                        data: 'old_data',
                        name: 'old_data',
                    },
                    {
                        title: 'Data Baru',
                        data: 'new_data',
                        name: 'new_data'
                    },

                ],
                columnDefs: [
                    {
                        targets: [1],
                        render: (data, type, row, meta) => {
                            return `
                            <div class="timestamp">
                                        `+moment(data.created_at).format('DD MMMM YYYY - HH:mm')+`
                            </div>
                            `
                        }
                    },
                ],
            });
            table1.draw();
        }
        $(document).ready(function() {
            rekapperhitunganpayroll();
        })
    </script>

@endsection
