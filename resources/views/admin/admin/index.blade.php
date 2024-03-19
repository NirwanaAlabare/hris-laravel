@php
    if (($loggedAdmin->role_user == "admin") || ($loggedAdmin->role_user == "superadmin")) {    
@endphp

@extends('admin.adminlayouts.adminlayout')

@section('head')
    <!-- Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet" />

    <!-- Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />

	<!-- Notifications  css -->
	<link href="{{URL::asset('assets/plugins/notify-growl/css/jquery.growl.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/notify-growl/css/notifIt.css')}}" rel="stylesheet" />

@stop
@section('mainarea')
    <!-- page-header -->
    <div class="page-header shadow">
        <ol class="breadcrumb breadcrumb-arrow mt-0">
            <li><a href="#">Setting</a></li>
            <li class="active"><span>Admin User</span></li>
        </ol>
        <div class="ml-auto">
            <div class="input-group">
                <a href="{{ url('screenlock') }}" class="btn btn-primary text-white mr-2 btn-sm" data-toggle="tooltip"
                    title="" data-placement="bottom" data-original-title="lock">
                    <span>
                        <i class="fa fa-lock"></i>
                    </span>
                </a>
                <a href="javascript:void(0)" id="addData-link" class="btn btn-warning text-white btn-sm" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tambah">
                    <span>
                        <i class="fa fa-plus"></i>
                    </span>
                </a>                
            </div>
        </div>
    </div>
    <!-- End page-header -->

    <!-- row -->
    <div class="row">
        <div class="col-md-12">
            <!-- Begin Form Edit Absen Karyawan -->
            <div class="card shadow" id="ajax-admin-model-addedit">
                <!-- boostrap absen time -->
                <div class="card-body">
                    <div class="form-group">
                        <div class="table-responsive">
                            <!-- BEGIN FORM-->
                            {!! Form::open(['url' => 'javascript:void(0)', 'id' => 'formSaveChanges1', 'name' => 'formSaveChanges1']) !!}
                            <input id="id_addedit" name="id_addedit" type="hidden">
                            <table class="table-sm display" id="ajax-admin-model-addedit-display">
                                <thead>
                                    <tr>
                                        <th>
                                            <h3 class="m-0 card-title" id="ajaxAddEditAdminModel"></h3>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="admin-addedit-display-body">
                                    <tr>
                                        <td>
                                            <label for="name" class="col-sm-6 control-label">Nama Lengkap :</label>
                                            <input id="name_addedit" name="name_addedit" type="text" class="form-control" placeholder="Nama Lengkap" maxlength="50" size="50">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="name" class="col-sm-6 control-label">Email :</label>
                                            <input id="email_addedit" name="email_addedit" type="text" class="form-control" placeholder="Email" maxlength="50" size="50">            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="name" class="col-sm-6 control-label">Role User :</label>
                                            <select id="role_user_addedit" class="form-control">
                                                <option value="">--- Pilih Role User ---</option>
                                                <option value="guest">Guest</option>
                                                <option value="absensi">Absensi</option>
                                                <option value="payroll">Payroll</option>
                                                <option value="admin">Administrator</option>
                                                <option value="superadmin">Super Admin</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="name" class="col-sm-6 control-label">Level :</label>
                                            <select id="level_addedit" class="form-control">
                                                <option value="">--- Pilih Level User ---</option>
                                                <option value="read">Lihat</option>
                                                <option value="cread">Input dan Lihat</option>
                                                <option value="updel">Update dan Delete</option>
                                                <option value="crud">CRUD</option>
                                            </select>                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="name" class="col-sm-6 control-label">Password :</label>
                                            <input id="password_addedit" name="password_addedit" type="password" class="form-control" placeholder="Password" maxlength="50" size="50">            
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>
                                            <div class="btn-list">
                                                <a href="javascript:void(0)" id="btn-add" class="btn btn-primary mr-2 mt-0 mb-0"></a>
                                                <a href="javascript:void(0)" id="btn-update" class="btn btn-primary mr-2 mt-0 mb-0"></a>
                                                <a href="javascript:void(0)" id="cancel-formSaveChanges1" class="btn btn-danger mr-2 mt-0 mb-0">Cancel</a>
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
            <div class="card shadow" id="datatable-data-karyawan">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable-ajax-crud"
                            class="table table-sm table-striped table-hover table-bordered w-100 text-nowrap display">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role User</th>
                                    <th scope="col">Level</th>
                                    <th scope="col">Terakhir Login</th>
                                    <th scope="col">Tanggal Dibuat</th>
                                    <th scope="col">&nbsp;&nbsp;&nbsp;Menu</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- boostrap show department model -->
    <div class="modal fade" id="ajax-admin-model-show" role="dialog" data-backdrop="static" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="ajaxShowAdminModel"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                <i class="fa fa-remove"></i>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
                <label for="name" class="col-sm-6 control-label">ID :</label>
                <div class="col-sm-12" id="id"></div>
            </div>                
            <div class="form-group">
                <label for="name" class="col-sm-6 control-label">Nama Lengkap :</label>
                <div class="col-sm-12" id="name"></div>
            </div>                
            <div class="form-group">
                <label for="name" class="col-sm-6 control-label">Email :</label>
                <div class="col-sm-12" id="email"></div>
            </div>                
            <div class="form-group">
                <label for="name" class="col-sm-6 control-label">Role User :</label>
                <div class="col-sm-12" id="role_user"></div>
            </div>                
            <div class="form-group">
                <label for="name" class="col-sm-6 control-label">Level :</label>
                <div class="col-sm-12" id="level"></div>
            </div>                
            <div class="form-group">
                <label for="name" class="col-sm-6 control-label">Terakhir Login :</label>
                <div class="col-sm-12" id="last_login"></div>
            </div>                
            <div class="form-group">
                <label for="name" class="col-sm-6 control-label">Tanggal Dibuat :</label>
                <div class="col-sm-12" id="created_at"></div>
            </div>                
            <div class="form-group">
                <label for="name" class="col-sm-6 control-label">Tanggal Diubah :</label>
                <div class="col-sm-12" id="updated_at"></div>
            </div>                
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
    <!-- end bootstrap model -->

    {{--DELETE Model--}}
    <div id="deleteModal" class="modal fade" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Confirmation Dialog</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                        <i class="fa fa-remove"></i>
                    </button>
                </div>
                <div class="modal-body" id="info">
                    <p>This is a modal with small size</p>
                </div><!-- modal-body -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default mr-2 mt-0 mb-0" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger mr-2 mt-0 mb-0" data-dismiss="modal" id="btn-del">Delete</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </div><!-- modal -->

    {{--END DELETE MODAL--}}
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

    <!-- Popover js -->
    <script src="{{URL::asset('assets/js/popover.js')}}"></script>

    <!-- Notifications js -->
    <script src="{{URL::asset('assets/plugins/notify-growl/js/rainbow.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/notify-growl/js/sample.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/notify-growl/js/jquery.growl.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/notify-growl/js/notifIt.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $("#ajax-admin-model-addedit").hide();
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
                    "url": "{{ route('admin.admin.ajaxAdmin') }}",
                    "dataType": "json",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": "data"
                },
                columns: [
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role_user',
                        name: 'role_user'
                    },
                    {
                        data: 'level',
                        name: 'level'
                    },
                    {
                        data: 'last_login',
                        name: 'last_login'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
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
                    },
                    {
                        targets: 6,
                        className: 'text-center',
                        width: "5px"
                    }
                ]
            });
        });

        $('body').on('click', '#showData-link', function () {
            var showData = $(this).data('id');
        //alert (showData);
            // ajax
            $.ajax({
                "type":"POST",
                "url": "{{route('admin.admin.show_data')}}",
                "data": { showData: showData },
                "dataType": 'json',
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                "success": function(res){
                  $('#ajaxShowAdminModel').html("Show Admin User");
                  $('#ajax-admin-model-show').modal('show');
                  $('#id').text(res.id);
                  $('#name').text(res.name);
                  $('#email').text(res.email);
                  $('#role_user').text(res.role_user);
                  $('#level').text(res.level);
                  $('#last_login').text(res.last_login);
                  $('#created_at').text(res.created_at);
                  $('#updated_at').text(res.updated_at);
                  //alert(res.site_nirwana_name);
               }
            });
        });

        $('body').on('click', '#editData-link', function () {
            var editData = $(this).data('id');
            $("#btn-update").show();
            $("#btn-update").html('Update');
            $("#btn-add").hide();

        //alert (showData);
            // ajax
            $.ajax({
                "type":"POST",
                "url": "{{route('admin.admin.edit_data')}}",
                "data": { editData: editData },
                "dataType": 'json',
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                "success": function(res){
                  $('#ajaxAddEditAdminModel').html("Edit Admin User");
                  $("#ajax-admin-model-addedit").show("slow");

                  //$('#formSaveChanges1').trigger("reset");

                  $('#id_addedit').val(res.id);
                  $('#name_addedit').val(res.name);
                  $('#email_addedit').val(res.email);
                  $('#role_user_addedit').val(res.role_user);
                  $('#level_addedit').val(res.level);
                  $('#password_addedit').val(res.password);
                  $('#last_login_addedit').val(res.last_login);
                  $('#created_at_addedit').val(res.created_at);
                  $('#updated_at_addedit').val(res.updated_at);
                  //alert(res.site_nirwana_name);
               }
            });
        });

        $('body').on('click', '#addData-link', function () {
            $("#btn-add").show();
            $("#btn-add").html('Add');
            $("#btn-update").hide();
            $('#ajaxAddEditAdminModel').html("Add Admin User");
            $("#ajax-admin-model-addedit").show("slow");
        });

        $("#cancel-formSaveChanges1").click(function () {
            $("#ajax-admin-model-addedit").hide("slow");
        });

        $('body').on('click', '#btn-update', function (event) {
            var id = $("#id_addedit").val();
            var name = $("#name_addedit").val();
            var email = $("#email_addedit").val();
            var role_user = $("#role_user_addedit").val();
            var level = $("#level_addedit").val();
            var password = $("#password_addedit").val();
            //$("#btn-update").html('Please Wait...');
            $('#btn-update').addClass("btn-loading");
            $("#btn-update").attr("disabled", true);
            
            // ajax
            $.ajax({
                type:"POST",
                url: "{{route('admin.admin.update')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    id:id,
                    name:name,
                    email:email,
                    role_user:role_user,
                    level:level,
                    password:password,
                },
                dataType: 'json',
                success: function(res){
                    $('#btn-update').removeClass("btn-loading");
                    $("#btn-update").html('Update');
                    $("#btn-update").attr("disabled", false);
                    notif({
                        msg: "<b>Success:</b> Data berhasil di update.",
                        type: "success"
                    });        
                    $("#datatable-ajax-crud").DataTable().ajax.reload();
                    $("#ajax-admin-model-addedit").hide("slow");
                }
            });
        });

        $('body').on('click', '#btn-add', function (event) {
            var name = $("#name_addedit").val();
            var email = $("#email_addedit").val();
            var role_user = $("#role_user_addedit").val();
            var level = $("#level_addedit").val();
            var password = $("#password_addedit").val();
            $('#btn-add').addClass("btn-loading");
            $("#btn-add").attr("disabled", true);
            
            // ajax
            $.ajax({
                type:"POST",
                url: "{{route('admin.admin.store')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    name:name,
                    email:email,
                    role_user:role_user,
                    level:level,
                    password:password,
                },
                dataType: 'json',
                success: function(res){
                    $('#btn-add').removeClass("btn-loading");
                    $("#btn-add").html('Add Admin User');
                    $("#btn-add").attr("disabled", false);
                    notif({
                        msg: "<b>Success:</b> Data berhasil di tambahkan.",
                        type: "success"
                    });        
                    $("#datatable-ajax-crud").DataTable().ajax.reload();
                    $("#ajax-admin-model-addedit").hide("slow");
                }
            });
        });

        $('body').on('click', '#delData-link', function () {
            var delData = $(this).data('id');
            var arrayData = delData.split("/");
            var id = arrayData[0];
            var email = arrayData[1];
            $('#deleteModal').modal('show');
            $("#deleteModal").find('#info').html('Are you sure you want to delete data <b>[' + email + ']</b> ?');
            $('#deleteModal').find("#btn-del").off().on("click", function () {
                $('#btn-del').addClass("btn-loading");
                $("#btn-del").attr("disabled", true);
    
                $.ajax({
                    "type":"POST",
                    "url": "{{route('admin.admin.destroy')}}",
                    "data": { id: id },
                    "dataType": 'json',
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    "success": function(res){
                        $('#btn-del').removeClass("btn-loading");
                        $("#btn-del").html('Delete');
                        $("#btn-del").attr("disabled", false);
                        notif({
                            msg: "<b>Success:</b> Data berhasil di hapus.",
                            type: "success"
                        });        
                        $("#datatable-ajax-crud").DataTable().ajax.reload();
                        $('#deleteModal').modal('hide');
                        //alert(res.site_nirwana_name);
                    }
                });
            });
        })

    </script>

@endsection
@php
    }
@endphp
