@extends('admin.adminlayouts.adminlayout')

@section('head')
    <!-- Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

    <!-- Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />

    <!-- Accordion Css -->
	<link href="{{URL::asset('assets/plugins/accordion/accordion.css')}}" rel="stylesheet" />
@stop
@section('mainarea')
    <!-- page-header -->
    <div class="page-header">
        <ol class="breadcrumb breadcrumb-arrow mt-0">
            <li><a href="#">Master Data</a></li>
            <li class="active"><span>Department</span></li>
        </ol>
        <div class="ml-auto">
            <div class="input-group">
                <a href="{{ url('lockscreen') }}" class="btn btn-primary text-white mr-2 btn-sm" data-toggle="tooltip"
                    title="" data-placement="bottom" data-original-title="lock">
                    <span>
                        <i class="fa fa-lock"></i>
                    </span>
                </a>
                @if($loggedAdmin->role_user=='superadmin' || $loggedAdmin->email=='firmansyah@nirwanaindonesia.com' || $loggedAdmin->email=='willy@ptnag.com'  )
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addModal">
                    <i class="fa fa-plus"></i>
                </button>
                @endif
            </div>
        </div>
    </div>
    <!-- End page-header -->

    <!-- row -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">ADD DEPARTMENT</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div id="accordion">
                    <div class="card">
                      <div class="card-header py-1 bg-primary" id="headingOne">
                          <button class="btn text-white text-left" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="font-weight: bold;width:100%">
                            <div class="row">
                                <div class="col-6">
                                    ADD DEPARTMENT
                                </div>
                                <div class="col-6 text-right">
                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                </div>
                            </div>
                          </button>
                      </div>
                  
                      <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row">
                              <div class="col-4 pt-2 pl-5">
                                  <label class="form-label" style="font-weight: bold">DEPARTMENT ID</label>
                              </div>
                              <div class="col-7">
                                  <input type="text" class="form-control" id="department_id_modal" style="background-color: white" disabled>
                                  <h6></h6>
                              </div>
                              <div class="col-1 pl-0">
                                    <button id="btnRefreshDeptId" class="btn py-1"><i class="fa fa-refresh" style="font-size: 11pt"></i></button>
                              </div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-4 pt-2 pl-5">
                                    <label class="form-label" style="font-weight: bold">DEPARTMENT NAME</label>
                                </div>
                                <div class="col-8">
                                    <input type="text" class="form-control" id="department_name_modal" style="background-color: white">
                                    <h6 style="padding-bottom: 0px;"></h6>
                                </div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-4 pt-2 pl-5">
                                    <label class="form-label" style="font-weight: bold">SUB DEPARTMENT NAME</label>
                                </div>
                                <div class="col-8">
                                    <input type="text" class="form-control" id="sub_department_name_modal" style="background-color: white">
                                    <h6 style="padding-bottom: 0px;"></h6>
                                </div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-12 text-right">
                                    <button type="button" id="save_department_modal" class="btn btn-primary py-1">SAVE</button>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-header py-1 bg-primary" id="headingTwo">
                          <button class="btn text-white text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="font-weight: bold;width:100%">
                            <div class="row">
                                <div class="col-6">
                                    ADD SUB DEPARTMENT
                                </div>
                                <div class="col-6 text-right">
                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                </div>
                            </div>
                          </button>
                      </div>
                      <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                          <div class="row pt-3">
                                <div class="col-4 pt-2 pl-5">
                                    <label class="form-label" style="font-weight: bold">DEPARTMENT NAME</label>
                                </div>
                                <div class="col-8">
                                    <select class="form-control" id="department_name_modal_sub" style="background-color: white">
                                        <option value="">SELECT DEPARTMENT</option>
                                    </select>
                                    <h6 style="padding-bottom: 0px;"></h6>
                                </div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-4 pt-2 pl-5">
                                    <label class="form-label" style="font-weight: bold">SUB DEPARTMENT NAME</label>
                                </div>
                                <div class="col-8">
                                    <input type="text" class="form-control" id="sub_department_name_modal_sub" style="background-color: white">
                                    <h6 style="padding-bottom: 0px;"></h6>
                                </div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-12 text-right">
                                    <button type="button" id="save_department_modal_sub" class="btn btn-primary py-1">SAVE</button>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
            </div>
          </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Begin Form Edit Absen Karyawan -->
            <div class="card" id="ajax-department-model-addedit">
                <!-- boostrap absen time -->
                <div class="card-body">
                    <div class="form-group">
                        <div class="table-responsive">
                            <!-- BEGIN FORM-->
                            {!! Form::open(['url' => 'javascript:void(0)', 'id' => 'formSaveChanges1', 'name' => 'formSaveChanges1']) !!}
                            <table class="table-sm display" id="ajax-department-model-addedit-display">
                                <thead>
                                    <tr>
                                        <th>
                                            <h3 class="m-0 card-title" id="ajaxEditDepartmentModel"></h3>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="datatable-absen-time-display-body">
                                    <tr>
                                        <td>
                                            <label for="name" class="col-sm-6 control-label">Site Nirwana ID :</label>
                                            <input id="site_nirwana_id_addedit" name="site_nirwana_id_addedit" type="text" class="form-control" placeholder="Site Nirwana ID" maxlength="5" size="5">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="name" class="col-sm-6 control-label">Site Nirwana Nama :</label>
                                            <input id="site_nirwana_name_addedit" name="site_nirwana_name_addedit" type="text" class="form-control" placeholder="Site Nirwana Nama" maxlength="50" size="50">            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="name" class="col-sm-6 control-label">Department ID :</label>
                                            <input id="department_id_addedit" name="department_id_addedit" type="text" class="form-control" placeholder="Department ID" maxlength="5" size="5">            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="name" class="col-sm-6 control-label">Department Nama :</label>
                                            <input id="department_name_addedit" name="department_name_addedit" type="text" class="form-control" placeholder="Site Nirwana Nama" maxlength="50" size="50">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="name" class="col-sm-6 control-label">Sub Department ID :</label>
                                            <input id="sub_dept_id_addedit" name="sub_dept_id_addedit" type="text" class="form-control" placeholder="Sub Department" maxlength="5" size="5">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="name" class="col-sm-6 control-label">Sub Department Nama :</label>
                                            <input id="sub_dept_name_addedit" name="sub_dept_name_addedit" type="text" class="form-control" placeholder="Sub Department Nama" maxlength="50" size="50">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="name" class="col-sm-6 control-label">Jumlah Karyawan :</label>
                                            <input id="jumlah_addedit" name="jumlah_addedit" type="text" class="form-control" placeholder="Jumlah Karyawan" maxlength="50" size="50" disabled>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="name" class="col-sm-6 control-label">Status :</label>
                                            <select id="status_addedit" name="status_addedit" class="form-control">
                                                <option value="">Choose Status</option>
                                                <option value="AKTIF">AKTIF</option>
                                                <option value="NONAKTIF">NONAKTIF</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="border-top">
                                        <th>
                                            <div class="btn-list">
                                                <a href="#" id="btn-save-changes" class="btn btn-primary btn-sm">Save all changes</a>
                                                <a href="javascript:void(0)" id="cancel-formSaveChanges1" class="btn btn-danger btn-sm">Cancel</a>
                                            </div>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                            {{-- </form> --}}
                            {!! Form::close() !!}
                            <!-- END FORM-->
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Edit Form Absen Karyawan -->

            <!-- Begin Form Edit Absen Karyawan -->
            <div class="card" id="datatable-data-karyawan">
                <div class="card-body">
                    <div class="row">
                        <div class="col-2">
                            <label class="form-label text-primary pt-1">Site Nirwana ID</label>
                        </div>
                        <div class="col-3">
                            <select id="selectNirwanaSite" name="selectNirwanaSite" class="form-control form-control-sm">
                                <option value="">Filter Nirwana Site</option>
                                @foreach ($site_nirwana_id as $site)
                                    <option value="{{$site->site_nirwana_id}}">{{$site->site_nirwana_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-2">
                            <label class="form-label text-primary pt-1">Department Name</label>
                        </div>
                        <div class="col-3">
                            <select class="form-control form-control-sm" id="pilih_department">
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            <label class="form-label text-primary pt-1">Sub Department Name</label>
                        </div>
                        <div class="col-3">
                            <select class="form-control form-control-sm" id="pilih_sub_department">
                            </select>
                        </div>
                    </div>
                    <div class="row py-2" style="border-bottom:1px solid rgb(180, 180, 180)">
                        <div class="col-2"></div>
                        <div class="col-5">
                            <a id="btn-import-excel" data-target="#import_department_excel" data-toggle="modal" title="" data-original-title="Import Data Dari File Excel" class="btn btn-app btn-info" title="Export data ke file rekap absen" style="padding-top: 3px;padding-bottom: 3px"><i class="fa fa-upload" aria-hidden="true"></i>  Import Excel</a>
                            <a id="btn-export-excel" class="btn btn-app btn-secondary" title="Export data ke file rekap absen" style="padding-top: 3px;padding-bottom: 3px"><i class="fa fa-download" aria-hidden="true"></i>  Export Excel</a>
                        </div>
                    </div>
                    <div class="table-responsive pt-3">
                        <table id="datatable-ajax-crud" class="table table-sm table-striped table-hover table-bordered w-100 text-nowrap display">
                            <thead>
                                <tr>
                                    <th scope="col">Site Nirwana ID</th>
                                    <th scope="col">Site Nirwana Nama</th>
                                    <th scope="col">Department ID</th>
                                    <th scope="col">Department Nama</th>
                                    <th scope="col">Sub Deptartment ID</th>
                                    <th scope="col">Sub Deptartment Nama</th>
                                    <th scope="col"><i class="fa fa-male" aria-hidden="true"></i></th>
                                    <th scope="col">Option</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- boostrap show department model -->
    
    <div class="modal fade" id="import_department_excel" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 1330px">
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-content">
                        <div class="modal-header bg-info p-2">
                            <h4 class="modal-title pl-2 font-weight-bold" >Import Department</h4>
                            <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                        <div class="modal-body p-5">
                            <div class="row">
                                <div class="col-12">
                                    <input class="form-control" ref="excel_filess" name="excel_filess" id="excel_filess" type="file" accept=".xlsx, .xls, .csv" required>
                                </div>
                            </div>
                            <div class="row pt-2" id="row_tabler">
                                <div class="col-12">
                                    <table class="table table-bordered" style="overflow-x:auto">
                                        <thead id="head_department">
                                            <tr>
                                                <td width="60px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">No</td>
                                                <td width="140px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">Site Nirwana ID</td>
                                                <td width="170px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">Site Nirwana Name</td>
                                                <td width="140px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">Department ID</td>
                                                <td width="170px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">Department Name</td>
                                                <td width="160px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">Sub Department Id</td>
                                                <td width="200px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">Sub Department Name</td>
                                                <td width="60px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px"><i class="fa fa-male"></i></td>
                                                <td width="140px" style="background-color: rgba(255, 255, 255, 0.6);font-weight:bold;padding-top:8px;padding-bottom:8px">Status</td>
                                            </tr>
                                        </thead>
                                        <tbody id="selected_department">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-12 text-center">
                                    <div id="loading_department">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="button" id="departmentImportButton" class="btn btn-success py-1" style="visibility: hidden"><i class="fa fa-upload" aria-hidden="true"></i> IMPORT</button>
                                </div>
                            </div> 
                        </div>
                        <div class="modal-footer bg-info py-2">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ajax-department-model-show" role="dialog" data-backdrop="static" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="ajaxShowDepartmentModel"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
                <label for="name" class="col-sm-6 control-label">Site Nirwana ID :</label>
                <div class="col-sm-12" id="site_nirwana_id"></div>
            </div>                
            <div class="form-group">
                <label for="name" class="col-sm-6 control-label">Site Nirwana Nama :</label>
                <div class="col-sm-12" id="site_nirwana_name"></div>
            </div>                
            <div class="form-group">
                <label for="name" class="col-sm-6 control-label">Department ID :</label>
                <div class="col-sm-12" id="department_id"></div>
            </div>                
            <div class="form-group">
                <label for="name" class="col-sm-6 control-label">Department Nama :</label>
                <div class="col-sm-12" id="department_name"></div>
            </div>                
            <div class="form-group">
                <label for="name" class="col-sm-6 control-label">Sub Department ID :</label>
                <div class="col-sm-12" id="sub_dept_id"></div>
            </div>                
            <div class="form-group">
                <label for="name" class="col-sm-6 control-label">Sub Department Nama :</label>
                <div class="col-sm-12" id="sub_dept_name"></div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-6 control-label">Status :</label>
                <div class="col-sm-12" id="status"></div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
    <!-- end bootstrap model -->
        
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
    <script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

    <!---Accordion js-->
    <script src="{{URL::asset('assets/plugins/accordion/accordion.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/accordion/accordions.js')}}"></script>
    <style>
        .error {
          background-color: red;
        }
        #head_department, #selected_department { display: block; }
        #head_department, #selected_department { display: block; }

        #selected_department {
            height: 1px;       /* Just for the demo          */
            overflow-y: auto;    /* Trigger vertical scroll    */
            overflow-x: hidden;
            font-size: 9pt; /* Hide the horizontal scroll */
        }
        .dataTables_filter {
            float: left !important;
        }
        #selected_department {
            height: 1px;       /* Just for the demo          */
            overflow-y: auto;    /* Trigger vertical scroll    */
            overflow-x: hidden;
            font-size: 9pt; /* Hide the horizontal scroll */
        }
    </style>
    <script type="text/javascript">
        $('#excel_filess').change(function() {
            fill_the_table();
        });
        function fill_the_table(){
            $('#loading_department').addClass("spinner-border");
            $('#selected_department').empty();
            var formData = new FormData();
            var excelFile=document.getElementById("excel_filess");
            var myFile=excelFile.files[0];
            formData.append("excel_file",myFile);
            document.getElementById('row_tabler').style.height='67px';
            document.getElementById('selected_department').style.height='1px';
            document.getElementById('departmentImportButton').style.visibility='hidden';
            if(typeof myFile=='undefined'){
                notif({
                    msg: "<b>Error:</b> Pilih File terlebih dahulu!",
                    type: "error"
                });
                document.getElementById('selected_department').style.height='1px';
                document.getElementById('departmentImportButton').style.visibility='hidden';
                $('#loading_department').removeClass("spinner-border");
                document.getElementById('row_tabler').style.height='67px';
            }else{
                $.ajax({
                    type: 'POST',
                    url: '{{route('hris.departmentall.import_department')}}',
                    contentType: false,
                    processData: false,
                    data: formData,
                    success:function(data){
                        count=0;
                        jQuery.each(data, function(key,value){
                            count++;
                            $('#selected_department').append("<tr>\
                                <td width='60px'>"+count+"</td>\
                                <td width='140px'>"+data[key].site_nirwana_id+"</td>\
                                <td width='170px'>"+data[key].site_nirwana_name+"</td>\
                                <td width='140px'>"+data[key].department_id+"</td>\
                                <td width='170px'>"+data[key].department_name+"</td>\
                                <td width='160px'>"+data[key].sub_dept_id+"</td>\
                                <td width='200px'>"+data[key].sub_dept_name+"</td>\
                                <td width='60px'>"+data[key].jumlah_karyawan+"</td>\
                                <td width='140px'>"+data[key].status+"</td>\
                            </tr>");
                        });
                        document.getElementById('selected_department').style.height='330px';
                        document.getElementById('departmentImportButton').style.visibility='visible';
                        $('#loading_department').removeClass("spinner-border");
                        document.getElementById('row_tabler').style.height='400px';
                    },
                    error: function(res){
                        swal("", "IMPORT DEPARTMENT GAGAL!", "error")
                    }
                });
            }
        }
        $('#departmentImportButton').click(function(){
            var formData = new FormData();
            var excelFile=document.getElementById("excel_filess");
            var myFile=excelFile.files[0];
            formData.append("excel_file",myFile);
            $('#employeeImportButton').addClass("btn-loading");
            $("#employeeImportButton").html('Please wait...');
            $("#employeeImportButton").attr("disabled", true);
            $.ajax({
                type: 'POST',
                url: '{{route('hris.departmentall.import_department_to_database')}}',
                contentType: false,
                processData: false,
                data: formData,
                success:function(data){
                    console.log(data);
                    $('#departmentImportButton').removeClass("btn-loading");
                    $("#departmentImportButton").html('<i class="fa fa-upload" aria-hidden="true"></i> IMPORT');
                    $("#departmentImportButton").attr("disabled", false);
                    swal("", "IMPORT DEPARTMENT BERHASIL!", "success");
                    $('#import_department_excel').modal('hide');
                    $('#excel_filess').val('');
                    document.getElementById('selected_department').style.height='1px';
                    document.getElementById('departmentImportButton').style.visibility='hidden';
                    document.getElementById('row_tabler').style.height='67px';
                },
                error: function(res){
                    swal("", "IMPORT DEPARTMENT GAGAL!", "error")
                    $('#departmentImportButton').removeClass("btn-loading");
                    $("#departmentImportButton").html('<i class="fa fa-upload" aria-hidden="true"></i> IMPORT');
                    $("#departmentImportButton").attr("disabled", false);
                }
            });
        });
        $(document).ready(function(){
            $("#ajax-department-model-addedit").hide();
            get_last_dept_id();
            get_dept_name();
        });
        function get_last_dept_id(){
            $.ajax({
                type: "GET",
                url: '/get_last_dept_id',
                dataType: 'json',
            }).done(function (response) {
                document.getElementById('department_id_modal').value='DEP'+(parseInt(response[0].substring(3, 5))+1);
            });
        }
        function get_dept_name(){
            $.ajax({
                type: "GET",
                url: '/get_dept_name',
                dataType: 'json',
            }).done(function (data) {
                console.log(data[0].department_name);
                jQuery.each(data, function(key,value){
                    $('#department_name_modal_sub').append('<option value="'+ data[key]['department_id'] +'">'+ data[key]['department_name'] +'</option>');
                });
            });
        }
        $('#btnRefreshDeptId').click(function(){
            get_last_dept_id();
        });
        $('#selectNirwanaSite').on('change',function(e){
            $("#pilih_department").empty();
            $("#pilih_department").val('');
            $("#pilih_sub_department").empty();
            $("#pilih_sub_department").val('');
            $.ajax({
                type:"POST",
                url: "{{route('hris.departmentall.getSelectDeptId')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    site_nirwana_id:$('#selectNirwanaSite').val(),
                },
                dataType: 'json',
                success: function(resA){
                    if(resA){
                        $("#pilih_department").append(new Option('Filter Department Name', ''));
                        for(i=0;i<resA.length;i++) {
                            $("#pilih_department").append(new Option(resA[i].department_name, resA[i].department_id));
                        }
                    }
                }
            });
            $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);
        });
        $('#pilih_department').on('change',function(e){
            $("#pilih_sub_department").empty();
            $("#pilih_sub_department").val('');
            $.ajax({
                type:"POST",
                url: "{{route('hris.departmentall.getSelectSubDeptIn')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    site_nirwana_id:$('#selectNirwanaSite').val(),
                    department_id:$('#pilih_department').val()
                },
                dataType: 'json',
                success: function(resA){
                    if(resA){
                        $("#pilih_sub_department").append(new Option('Filter Sub Department Name', ''));
                        for(i=0;i<resA.length;i++) {
                            $("#pilih_sub_department").append(new Option(resA[i].sub_dept_name, resA[i].sub_dept_id));
                        }
                    }
                }
            });
            $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);
        });
        $('#pilih_sub_department').on('change',function(e){
            $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);
        });
        
        $('#save_department_modal').click(function(){
            var data = new FormData();
            data.append('department_id', $('#department_id_modal').val());
            data.append('department_name', $('#department_name_modal').val());
            data.append('sub_department_name', $('#sub_department_name_modal').val());
            jQuery.ajax({
                url: '{{route('hris.departmentall.save_department_id')}}',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST', 
                success: function(data){
                    $('#department_name_modal').val('');
                    $('#sub_department_name_modal').val('');
                    $('#department_name_modal').next().html('');
                    document.getElementById("department_name_modal").style.border = null;
                    $('#sub_department_name_modal').next().html('');
                    document.getElementById("sub_department_name_modal").style.border = null;
                    swal({
                        title: 'TAMBAH DEPARTMENT',
                        text: 'Data department berhasil di simpan',
                        type: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    get_last_dept_id();
                    get_dept_name();
                },
                error:function(error){
                    let err_log=error.responseJSON.errors;
                    if(error.status==422){
                        if(typeof(err_log.department_id)!=='undefined'){
                            $('#department_id_modal').next().html('<span style="color:red">ID Department '+error.responseJSON.errors.department_id[0]+'</span>');
                            document.getElementById("department_id_modal").style.border = "1px solid red";
                        }else{
                            $('#department_id_modal').next().html('');
                            document.getElementById("department_id_modal").style.border = null;
                        }
                        if(typeof(err_log.department_name)!=='undefined'){
                            $('#department_name_modal').next().html('<span style="color:red">Nama department '+error.responseJSON.errors.department_name[0]+'</span>');
                            document.getElementById("department_name_modal").style.border = "1px solid red";
                        }else{
                            $('#department_name_modal').next().html('');
                            document.getElementById("department_name_modal").style.border = null;
                        }
                        if(typeof(err_log.sub_department_name)!=='undefined'){
                            $('#sub_department_name_modal').next().html('<span style="color:red">Nama sub department '+error.responseJSON.errors.sub_department_name[0]+'</span>');
                            document.getElementById("sub_department_name_modal").style.border = "1px solid red";
                        }else{
                            $('#sub_department_name_modal').next().html('');
                            document.getElementById("sub_department_name_modal").style.border = null;
                        }
                    }
                }
            });
        });
        $('#save_department_modal_sub').click(function(){
            var data = new FormData();
            data.append('department_id', $('#department_id_modal').val());
            data.append('department_name', $('#department_name_modal_sub').val());
            data.append('sub_department_name', $('#sub_department_name_modal_sub').val());
            jQuery.ajax({
                url: '{{route('hris.departmentall.save_sub_department')}}',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST', 
                success: function(data){
                    console.log(data);
                    $('#department_name_modal_sub').val('');
                    $('#sub_department_name_modal_sub').val('');
                    $('#department_name_modal_sub').next().html('');
                    document.getElementById("department_name_modal_sub").style.border = null;
                    $('#sub_department_name_modal_sub').next().html('');
                    document.getElementById("sub_department_name_modal_sub").style.border = null;
                    swal({
                        title: 'TAMBAH SUB DEPARTMENT',
                        text: 'Data sub department berhasil di simpan',
                        type: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    get_last_dept_id();
                    get_dept_name();
                },
                error:function(error){
                    let err_log=error.responseJSON.errors;
                    if(error.status==422){
                        if(typeof(err_log.department_name)!=='undefined'){
                            $('#department_name_modal_sub').next().html('<span style="color:red">Nama department '+error.responseJSON.errors.department_name[0]+'</span>');
                            document.getElementById("department_name_modal_sub").style.border = "1px solid red";
                        }else{
                            $('#department_name_modal_sub').next().html('');
                            document.getElementById("department_name_modal_sub").style.border = null;
                        }
                        if(typeof(err_log.sub_department_name)!=='undefined'){
                            $('#sub_department_name_modal_sub').next().html('<span style="color:red">Nama sub department '+error.responseJSON.errors.sub_department_name[0]+'</span>');
                            document.getElementById("sub_department_name_modal_sub").style.border = "1px solid red";
                        }else{
                            $('#sub_department_name_modal').next().html('');
                            document.getElementById("sub_department_name_modal_sub").style.border = null;
                        }
                    }
                }
            });
        });

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
                    "url": "{{ route('hris.departmentall.ajax_departmentall') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data",
                    "data": function (d) {
                        d.site_nirwana_id = $('#selectNirwanaSite').val();
                        d.department_id = $('#pilih_department').val();
                        d.sub_dept_id = $('#pilih_sub_department').val();
                    }
                },
                columns: [
                    {
                        data: 'site_nirwana_id',
                        name: 'site_nirwana_id'
                    },
                    {
                        data: 'site_nirwana_name',
                        name: 'site_nirwana_name'
                    },
                    {
                        data: 'department_id',
                        name: 'department_id'
                    },
                    {
                        data: 'department_name',
                        name: 'department_name'
                    },
                    {
                        data: 'sub_dept_id',
                        name: 'sub_dept_id'
                    },
                    {
                        data: 'sub_dept_name',
                        name: 'sub_dept_name'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'option'
                    },
                ],
                columnDefs: [
                    {
                        "orderable": false,
                        "targets": [6]
                    },
                    {
                        'visible': false,
                        'targets': []
                    }
                ],
                "createdRow": function (row, data, dataIndex) {
                    // if ((data['kode_hari'] == "5") || (data['kode_hari'] == "6") || (data['kerjalibur'] == "LIBUR")) {
                    if ((data['status'] == "NONAKTIF")) {

                        $(row).css('background', 'red');
                    }
                }
            });
        });
        $('#btn-export-excel').on('click',function(){

        });
        $('#btn-export-excel').on('click',function(){
            var site_nirwana = $('#selectNirwanaSite').val();
            var department = $('#pilih_department').val();
            var sub_department = $('#pilih_sub_department').val();
            var url = 'export_excel_department_all?site_nirwana='+site_nirwana+'&department='+department+'&section='+sub_department;
            window.open(url);
        });
        $('body').on('click', '#showData-link', function () {
            var showData = $(this).data('id');
        //alert (showData);
            // ajax
            $.ajax({
                "type":"POST",
                "url": "{{route('hris.departmentall.show_data')}}",
                "data": { showData: showData },
                "dataType": 'json',
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                "success": function(res){
                  $('#ajaxShowDepartmentModel').html("Show Department");
                  $('#ajax-department-model-show').modal('show');
                  $('#site_nirwana_id').text(res.site_nirwana_id);
                  $('#site_nirwana_name').text(res.site_nirwana_name);
                  $('#department_id').text(res.department_id);
                  $('#department_name').text(res.department_name);
                  $('#sub_dept_id').text(res.sub_dept_id);
                  $('#sub_dept_name').text(res.sub_dept_name);
                  $('#status').text(res.status);
                  //alert(res.site_nirwana_name);
               }
            });
        });

        $('body').on('click', '#addeditData-link', function () {
            var editData = $(this).data('id');
        //alert (showData);
            // ajax
            $.ajax({
                "type":"POST",
                "url": "{{route('hris.departmentall.edit_data')}}",
                "data": { editData: editData },
                "dataType": 'json',
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                "success": function(res){
                  $('#ajaxEditDepartmentModel').html("Edit Department");
                  $("#ajax-department-model-addedit").show("slow");

                  //$('#formSaveChanges1').trigger("reset");

                  $('#site_nirwana_id_addedit').val(res.site_nirwana_id);
                  $('#site_nirwana_name_addedit').val(res.site_nirwana_name);
                  $('#department_id_addedit').val(res.department_id);
                  $('#department_name_addedit').val(res.department_name);
                  $('#sub_dept_id_addedit').val(res.sub_dept_id);
                  $('#sub_dept_name_addedit').val(res.sub_dept_name);
                  $.ajax({
                    "type":"POST",
                    "url": "{{route('hris.departmentall.getJumlahKaryawan')}}",
                    "data": { sub_dept_id: res.sub_dept_id },
                    "success": function(res2){
                        $('#jumlah_addedit').val(res2);
                    }
                  });
                  $('#status_addedit').val(res.status);
                  //alert(res.site_nirwana_name);
               }
            });
        });
                
        $("#cancel-formSaveChanges1").click(function () {
            $("#ajax-department-model-addedit").hide("slow");
           });

        $('body').on('click', '#btn-save-changes', function (event) {
            var site_nirwana_id = $("#site_nirwana_id_addedit").val();
            var site_nirwana_name = $("#site_nirwana_name_addedit").val();
            var department_id = $("#department_id_addedit").val();
            var department_name = $("#department_name_addedit").val();
            var sub_dept_id = $("#sub_dept_id_addedit").val();
            var sub_dept_name = $("#sub_dept_name_addedit").val();
            var status = $("#status_addedit").val();
            $("#btn-save-change").html('Please Wait...');
            $("#btn-save-change"). attr("disabled", true);
            
            // ajax
            $.ajax({
                type:"POST",
                url: "{{route('hris.departmentall.store')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    site_nirwana_id:site_nirwana_id,
                    site_nirwana_name:site_nirwana_name,
                    department_id:department_id,
                    department_name:department_name,
                    sub_dept_id:sub_dept_id,
                    sub_dept_name:sub_dept_name,
                    status:status
                },
                dataType: 'json',
                success: function(res){
                    $("#btn-save-change").html('Save all changes');
                    $("#btn-save-change"). attr("disabled", false);
                    alert('Simpan berhasil');
                }
            });
        });


    </script>

@endsection
