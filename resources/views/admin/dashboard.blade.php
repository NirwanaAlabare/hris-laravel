@extends('admin.adminlayouts.adminlayout')

@section('head')
	<!-- Data table css -->
	<link href="{{URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}"  rel="stylesheet">
	<link href="{{URL::asset('assets/plugins/datatable/responsivebootstrap4.min.css')}}" rel="stylesheet" />

	<!-- Select2 css -->
	<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />

@stop
@section('mainarea')
<!-- page-header -->
<div class="page-header">
    <ol class="breadcrumb"><!-- breadcrumb -->
        <li class="breadcrumb-item"><a href="#">PT NAG</a></li>
        <li class="breadcrumb-item active" aria-current="page">KARYAWAN</li>
    </ol><!-- End breadcrumb -->
    <div class="ml-auto">
        <div class="input-group">
            <a href="{{url('lockscreen')}}" class="btn btn-primary text-white mr-2 btn-sm" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="lock">
                <span>
                    <i class="fa fa-lock"></i>
                </span>
            </a>
            <a href="#" id="addNew" class="btn btn-warning text-white btn-sm" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Add New">
                <span>
                    <i class="fa fa-plus"></i>
                </span>
            </a>
        </div>
    </div>
</div>
<!-- End page-header -->

<!-- row closed -->
<div class="card">
    <div class="card-header pb-0">
        <div class="card-title">DATA MASTER</div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="datatable-ajax-crud" class="table table-sm table-striped table-bordered w-100 text-nowrap display">
            <thead>
              <tr>
                <th scope="col">Enroll ID</th>
                <th scope="col">NIK</th>
                <th scope="col">Nama Karyawan</th>
                <th scope="col">Gender</th>
                <th scope="col">Tempat Lahir</th>
                <th scope="col">Tanggal Lahir</th>
                <th scope="col">Option</th>
              </tr>
            </thead>
          </table>
        </div>
    </div>
</div>

<!-- boostrap add and edit book model -->
<div class="modal fade" id="ajax-book-model" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="ajaxBookModel"></h4>
      </div>
      <div class="modal-body">
        <!-- BEGIN FORM-->
        {!! Form::open(array('url' => 'javascript:void(0)', 'id'=>'addEditForm', 'name'=>'addEditForm')) !!}

{{--          <form action="javascript:void(0)" id="addEditBookForm" name="addEditBookForm" class="form-horizontal" method="POST">
          <input type="hidden" name="ida" id="ida">
  --}}          <div class="form-group">
            <label for="name" class="col-sm-2 control-label">Enrool ID</label>
            <div class="col-sm-12">
              <input type="text" class="form-control" id="enroll_id" name="enroll_id" placeholder="Enrool ID" maxlength="50" required="">
            </div>
          </div>
          <div class="form-group">
            <label for="name" class="col-sm-2 control-label">Employee ID</label>
            <div class="col-sm-12">
              <input type="text" class="form-control" id="employee_id" name="employee_id" placeholder="Employee ID" maxlength="50" required="">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Employee Name</label>
            <div class="col-sm-12">
              <input type="text" class="form-control" id="name" name="name" placeholder="Employee Name" required="">
            </div>
          </div>
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary" id="btn-save" value="addNewBook">Save changes
            </button>
          </div>
        {{--  </form>  --}}
        {!! Form::close() !!}
        <!-- END FORM-->

      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>
<!-- end bootstrap model -->


@endsection

@section('footerjs')

<!--Jquery Sparkline js-->
<script src="{{URL::asset('assets/plugins/vendors/jquery.sparkline.min.js')}}"></script>

<!-- Chart Circle js-->
<script src="{{URL::asset('assets/plugins/vendors/circle-progress.min.js')}}"></script>

<!--Time Counter js-->
<script src="{{URL::asset('assets/plugins/counters/jquery.missofis-countdown.js')}}"></script>
<script src="{{URL::asset('assets/plugins/counters/counter.js')}}"></script>

<!-- INTERNAL Data tables -->
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.js')}}"></script>
{{--  <script src="{{URL::asset('assets/plugins/datatable/datatable-2.js')}}"></script>  --}}
{{--  <script src="{{URL::asset('assets/plugins/datatable/datatable-employee.js')}}"></script>  --}}

<script type="text/javascript">
{{--  $(document).ready(function(){
    $('#datatable-employee').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax":{
            "url": "{{route('admin.karyawan.index')}}",
            "dataType": "json",
            "type": "POST",
            "headers": {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            "dataSrc": "data",
            "success": function(data) {
                var new_data = {
                "data": data
                };
                console.log(new_data);
                }
            }
	});
});  --}}

$.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

 $(document).ready( function () {
    $('#datatable-ajax-crud').DataTable({
        processing: true,
        serverSide: true,
        "ajax":{
            "url": "{{route('admin.karyawan.ajax_karyawan')}}",
            "dataType": "json",
            "type": "POST",
            "headers": {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            "dataSrc": "data",
        },
        columns: [
            { data: 'enroll_id', name: 'enroll_id'},
            { data: 'employee_id', name: 'employee_id' },
            { data: 'name', name: 'name' },
            { data: 'gender', name: 'gender' },
            { data: 'place_of_birth', name: 'place_of_birth' },
            { data: 'date_birth', name: 'date_birth' },
            { data: 'options' },
        ],
        columnDefs: [
            { "orderable": false, "targets": 6 }
        ],
        order: [[0, 'desc']]
    });
});

$('body').on('click', '.edit', function () {
    var employee_id = $(this).data('employee_id');

    // ajax
    $.ajax({
        "type":"POST",
        "url": "{{route('admin.karyawan.edit')}}",
        "data": { employee_id: employee_id },
        "dataType": 'json',
        "headers": {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        "success": function(res){
          $('#ajaxBookModel').html("Edit Book");
          $('#ajax-book-model').modal('show');
          $('#employee_id').val(res.employee_id);
          $('#enroll_id').val(res.enroll_id);
          $('#name').val(res.name);

       }
    });
});

</script>

@endsection
