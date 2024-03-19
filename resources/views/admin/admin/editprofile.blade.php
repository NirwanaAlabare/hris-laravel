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
        <li class="active"><span>Profile</span></li>
    </ol>
</div>
<!-- End page-header -->

<!-- BEGIN FORM-->
{!! Form::open(['url' => 'javascript:void(0)', 'id' => 'formSaveChanges1', 'name' => 'formSaveChanges1']) !!}
<!-- row -->
<div class="row">
    <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
        <div class="card shadow">
            <div class="card-header bg-primary p-3">
                <div class="card-title">Edit Profile & Reset Password</div>
            </div>
            <div class="card-body">
                <div class="alert alert-default m-0" role="alert">
                    <span class="alert-inner--icon"><i class="fe fe-download"></i></span>
                    <span class="alert-inner--text"><strong>Info : </strong> Panjang password baru harus 6 s/d 8 digit.</span>
                </div>    
                <div class="row">
                    <div class="col-xl-4 col-lg-12 col-md-12 userprofile">
                        <div class="userpic mb-2">
                            <img src="{{URL::asset('assets/images/users/female/5.jpg')}}" alt="" class="userpicimg">
                        </div>
                        <p class="text-center">{{ $dataArray['role_user'] . ' | ' . $dataArray['level']; }}</p>
                        <div class="form-group text-center">
                            <a href="#" id="btngantifoto" class="btn btn-primary mt-1"><i class="fe fe-camera  mr-1"></i>Ganti Foto</a>
                            <a href="#" class="btn btn-info mt-1 mb-xl-0"><i class="fe fe-camera-off mr-1 mb-2"></i>Hapus Foto</a>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-12 col-md-12">
                        <div class="form-group">
                            <label for="exampleInputname">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" placeholder="Nama Lengkap" value="{{ $dataArray['name']; }}">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email</label>
                            <input readonly type="email" class="form-control" id="email" placeholder="email" value="{{ $dataArray['email']; }}">
                        </div>        
                    </div>
                    <div class="col-xl-4 col-lg-12 col-md-12">
                        <div class="form-group">        
                            <label>New Password</label>
                            <input type="password" id="password_new" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" id="password_confirm" class="form-control">
                        </div>
                    </div>                    
                </div>
            </div>
            <div class="card-footer text-right bg-primary p-1 pb-2 pr-2">
                <a href="javascript:void(0)" id="btn-simpan" class="btn btn-success btn-icon mt-1">Simpan</a>
            </div>
        </div>
    </div>
</div>
<!-- row end -->
{{-- </form> --}}
{!! Form::close() !!}
<!-- END FORM-->

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

        $('body').on('click', '#btn-simpan', function (event) {
            var name = $("#name").val();
            var email = $("#email").val();
            var password_new = $("#password_new").val();
            var password_confirm = $("#password_confirm").val();
            $("#name").removeClass("is-invalid state-invalid");
            $("#password_new").removeClass("is-invalid state-invalid");
            $("#password_confirm").removeClass("is-invalid state-invalid");

            if (!name) {
                $("#name").addClass("is-invalid state-invalid");

                notif({
                    msg: "<b>Warning:</b> Nama belum di isi!!!",
                    type: "warning"
                });        
                return false;
            }

            if (!password_new) {
                $("#password_new").addClass("is-invalid state-invalid");

                notif({
                    msg: "<b>Warning:</b> Password baru belum di isi!!!",
                    type: "warning"
                });        
                return false;
            }

            if (!password_confirm) {
                $("#password_confirm").addClass("is-invalid state-invalid");

                notif({
                    msg: "<b>Warning:</b> Konfirmasi Password baru belum di isi!!!",
                    type: "warning"
                });        
                return false;
            }

            if (password_new !== password_confirm) {
                $("#password_new").addClass("is-invalid state-invalid");
                $("#password_confirm").addClass("is-invalid state-invalid");

                notif({
                    msg: "<b>Warning:</b> Password baru dan password konfirmasi berbeda!!!",
                    type: "warning"
                });        
                return false;
            }

            if ((password_new.length <= 5) || (password_new.length >= 9)) {
                $("#password_new").addClass("is-invalid state-invalid");
                $("#password_confirm").addClass("is-invalid state-invalid");

                notif({
                    msg: "<b>Warning:</b> Panjang password baru harus 6 s/d 8 digit!!!",
                    type: "warning"
                });        
                return false;
            }

            $("#btn-simpan").html('Please Wait...');
            $('#btn-simpan').addClass("btn-loading");
            $("#btn-simpan").attr("disabled", true);
            
            // ajax
            $.ajax({
                type:"POST",
                url: "{{route('admin.admin.ajax_resetpwd')}}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    name:name,
                    email:email,
                    password_new:password_new,
                },
                dataType: 'json',
                success: function(res){
                    $('#btn-simpan').removeClass("btn-loading");
                    $("#btn-simpan").html('Simpan');
                    $("#btn-simpan").attr("disabled", false);
                    notif({
                        msg: "<b>Success:</b> Data berhasil di simpan.",
                        type: "success"
                    });        
                },
                error: function(res){
                    $('#btn-simpan').removeClass("btn-loading");
                    $("#btn-simpan").html('Simpan');
                    $("#btn-simpan").attr("disabled", false);
                    notif({
                        msg: "<b>Oops!</b> An Error Occurred (Data belum berhasil di simpan)",
                        type: "error",
                        position: "center"
                    });
                }
            });
        });

        $("#btngantifoto").on("click",function (e){
            var fileDialog = $('<input type="file">');
            fileDialog.click();
            fileDialog.on("change",onFileSelected);

            return false;
        });
          
        var onFileSelected = function(e){
            console.log($(this)[0].files);
        };

    </script>

@endsection
