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
        New Admin
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{route('admin.dashboard.index')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="{{ route('admin.admin.index') }}">Admin</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="">New Admin</a>
            </li>
        </ul>
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            @include('admin.common.error')

            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-user"></i>Add New Admin
                    </div>
                    <div class="tools">
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    {!! Form::open(array('class'=>'form-horizontal form-bordered','method'=>'POST', 'id' =>
                    'admin_store_form')) !!}
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Name: <span class="required">
                                * </span>
                            </label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" placeholder="Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Email: <span class="required">
                                * </span>
                            </label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="email" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Password: <span class="required">
                                * </span>
                            </label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password" placeholder="password">
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="button" onclick="addAdmin()" class="btn green">
                                                <i class="fa fa-plus"></i> Submit
                                            </button>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                    <!-- END FORM-->
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
                function addAdmin() {
                    var url = "{{ route('admin.admin.store') }}";
                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        container: '#admin_store_form',
                        file: true
                    });
                }
            </script>
@stop
