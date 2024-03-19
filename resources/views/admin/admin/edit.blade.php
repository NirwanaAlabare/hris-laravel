@extends('admin.adminlayouts.adminlayout')

@section('head')
    <!-- BEGIN PAGE LEVEL STYLES -->
    {!! HTML::style("assets/global/plugins/bootstrap-select/bootstrap-select.min.css") !!}
    {!! HTML::style("assets/global/plugins/select2/select2.css") !!}
    {!! HTML::style("assets/global/plugins/jquery-multi-select/css/multi-select.css") !!}
    <!-- BEGIN THEME STYLES -->
@stop
@section('mainarea')
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">
        <i class="fa fa-edit"></i> Edit <small>{{ $admin_user->name }}</small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{route('admin.admin.index')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="{{ route('admin.admin.index') }}">Admin List</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href=""> Edit Admin</a>
            </li>
        </ul>

    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-edit"></i>Edit Admin
                    </div>
                    <div class="tools">
                    </div>
                </div>
                <div class="portlet-body form">
                    <!------------------------ BEGIN FORM---------------------->
                    {!! Form::model($admin_user, ['method' => 'PUT', 'class'=>'form-horizontal form-bordered', 'id' => 'edit_admin_form']) !!}
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Name: <span class="required">
                                * </span>
                            </label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" placeholder="User Name"
                                       value="{{ $admin_user->name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Email: <span class="required">
                                * </span>
                            </label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="email" placeholder="Email"
                                       value="{{ $admin_user->email }}">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Password: <span class="required">
                                * </span>
                            </label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="password" placeholder="Password" value="">
                                <p class="text-success">
                                    Admin will login using this password. (Leave blank to keep current password)
                                </p>
                            </div>

                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="button" onclick="updateAdmin({{$admin_user->id}});return false;"
                                            class="btn green"><i class="fa fa-check"></i> Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                    <!------------------------- END FORM ----------------------->
                    </div>
                </div>
                <!-- END EXAMPLE TABLE PORTLET-->
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    @stop
    @section('footerjs')
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        {!! HTML::script("assets/global/plugins/bootstrap-select/bootstrap-select.min.js") !!}
        {!! HTML::script("assets/global/plugins/select2/select2.min.js") !!}
        {!! HTML::script("assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js") !!}
        <!-- END PAGE LEVEL PLUGINS -->
            <script>
                // Javascript function to update the company info and Bank Info
                function updateAdmin(id) {
                    var url = "{{ route('admin.admin.update',':id') }}";
                    url = url.replace(':id', id);
                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        container: '#edit_admin_form',
                        data: $('#edit_admin_form').serialize(),

                    });
                }
            </script>
@stop
