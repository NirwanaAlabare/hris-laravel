@extends('admin.adminlayouts.adminlayout-mut-karyawan')

@section('head')
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{ URL::asset('assets/css/iziToast.min.css') }}">
<link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@stop
@section('mainarea')
<div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
    <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
        <li><a href="{{route('anggaran_makan.index')}}">Anggaran Makan</a></li>
        <li class="active"><span>Estimasi Anggaran Makan</span></li>
    </ol>
    <div class="ml-auto">
        <div class="input-group">
            <a href="#" id="btn-refresh-data" class="btn btn-icon btn-secondary p-0 m-0" data-toggle="tooltip"
                title="" data-placement="bottom" data-original-title="Refresh Halaman">
                <span>
                    <i class="fa fa-refresh"></i>
                </span>
            </a>
        </div>
    </div>
</div>
<div class="card card-sb">
    <div class="card-header">
        <h5 class="card-title fw-bold mb-0"><i class="fa fa-utensils"></i> ESTIMASI ANGGARAN MAKAN</h5>
    </div>
    <div class="card-body">
        <div class="row mb-3 flex justify-content-between align-items-center">
            <div class="d-flex gap-4 align-items-end">
                <div class="">'
                    <label class="form-label"><small><b>Tanggal Form</b></small></label>
                    <div class="col pl-0"><input type="date" class="form-control form-control-sm " id="tgl-awal" name="tgl_awal" oninput="dataTableReload()" onchange="dataTableReload()" value="{{ date('Y-m-d') }}" readonly style="background-color:white"></div>
                </div>
            </div>
            <div class="col-3">
                <button data-toggle="modal" data-target="#newEstimationModal" id="btn_new" class="btn btn-primary position-relative w-100">
                    <i class="fa fa-plus"></i>
                    Baru
                </button>
            </div>
        </div>
        @if(Auth::guard('admin')->user()->name=='HR' || Auth::guard('admin')->user()->name=='HRD' || Auth::guard('admin')->user()->name=='IT' || Auth::guard('admin')->user()->name=='GA')
        <div class="row mb-5">
            <div class="col-2">
                <button class="btn btn-app w-100" onclick="export_excel_konsumsi()" style="background-color: #16a34a" data-toggle="tooltip" title="Export Data ke File Transfer Excel" id="recap_labor_cost_2"><i class="fa fa-file-excel-o" aria-hidden="true"></i>Estimasi Anggaran Makan</button>
            </div>
            <div class="col-2">
                <button class="btn btn-app w-100" onclick="export_excel_overtime_recap()" style="background-color: #16a34a" data-toggle="tooltip" title="Export Data ke File Transfer Excel" id="recap_labor_cost_2"><i class="fa fa-file-excel-o" aria-hidden="true"></i>Overtime Recap</button>
            </div>
            <div class="col-2">
                <button class="btn btn-danger w-100 text-black" onclick="export_pdf_konsumsi()" style="background-color: #7a0000;" data-toggle="tooltip" title="Export Data ke File Transfer Excel" id="recap_labor_cost_2">
                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                    Estimasi Anggaran Makan
                </button>
            </div>
        </div>
        @endif

        <div class="table-responsive">
            <table id="datatable" class="table table-bordered table-sm w-100 table-hover text-nowrap">
                <thead class="bg-primary">
                    <tr style='text-align:center; vertical-align:middle'>
                        <th>TANGGAL</th>
                        <th>KETERANGAN</th>
                        <th>BAGIAN</th>
                        <th>STAFF</th>
                        <th>NON STAFF</th>
                        <th>CREATED BY</th>
                        <th><span class="fa fa-cog"></span></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="newEstimationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-sb">
                <h1 class="modal-title fs-1 px-2" id="exampleModalLabel">Tambah Data Estimasi Makan</h1>
                <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body py-0">
                <div class="row bg-gradient-light py-3 px-2">
                    <div class="col-4">
                        <h6 class="modal-title fs-1">TANGGAL</h6>
                    </div>
                    <div class="col-8">
                        <input type="date" class="form-control" id="tanggal">
                        <h6 style="margin-bottom:0"></h6>
                    </div>
                </div>
                <div class="row py-3 px-2">
                    <div class="col-4">
                        <h6 class="modal-title fs-1">KETERANGAN</h6>
                    </div>
                    <div class="form-group col-8">
                        <select class='form-control' style='width: 100%;' name='keterangan' id='keterangan' required>
                            <option value="">PILIH KETERANGAN</option>
                            <option value="LEMBUR">LEMBUR</option>
                            <option value="SHIFT MALAM">SHIFT MALAM</option>
                        </select>
                    </div>
                </div>
                <div class="row bg-gradient-light py-3 px-2">
                    <div class="col-4">
                        <h6 class="modal-title fs-1">BAGIAN</h6>
                    </div>
                    <div class="form-group col-8">
                        <select class='form-control select2' style='width: 100%;' name='bagian' id='bagian' required>
                            <option selected="selected" value="" disabled="true">Pilih Bagian</option>
                            @foreach ($dept as $d)
                                <option value="{{$d->department_id}}">{{$d->department_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row py-3 px-2">
                    <div class="col-4">
                        <h6 class="modal-title fs-1">NON STAFF</h6>
                    </div>
                    <div class="col-8">
                        <input type="number" class="form-control" id="non_staff">
                    </div>
                </div>
                <div class="row bg-gradient-light py-3 px-2">
                    <div class="col-4">
                        <h6 class="modal-title fs-1">STAFF</h6>
                    </div>
                    <div class="col-8">
                        <input type="number" class="form-control" id="staff">
                    </div>
                </div>
                <div class="row py-3 px-2">
                    <div class="col-12 text-center">
                        <button onclick="save_estimation()" class="btn btn-success fs-1" id="save_estimation">SUBMIT</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editEstimationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-sb text-light">
                <h1 class="modal-title fs-1 px-2" id="exampleModalLabel">Edit Data Estimasi Makan</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-0">
                <div class="row py-3 px-2">
                    <div class="col-4">
                        <h6 class="modal-title fs-1">TANGGAL</h6>
                    </div>
                    <div class="col-8">
                        <input type="date" class="form-control" id="edit_tanggal" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="row py-3 px-2">
                    <div class="col-4">
                        <h6 class="modal-title fs-1">KETERANGAN</h6>
                    </div>
                    <div class="col-8">
                        <input type="hidden" id="edit_id">
                        <select class="form-select" id="edit_keterangan">
                            <option value="">PILIH KETERANGAN</option>
                            <option value="LEMBUR">LEMBUR</option>
                            <option value="SHIFT MALAM">SHIFT MALAM</option>
                        </select>
                    </div>
                </div>
                <div class="row bg-gradient-light py-3 px-2">
                    <div class="col-4">
                        <h6 class="modal-title fs-1">DEPARTMENT</h6>
                    </div>
                    <div class="col-8">
                        <select class="form-select" id="edit_bagian">
                            <option value="" selected hidden>PILIH BAGIAN</option>
                            @foreach ($dept as $d)
                                <option value="{{$d->department_id}}">{{$d->department_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row py-3 px-2">
                    <div class="col-4">
                        <h6 class="modal-title fs-1">NON STAFF</h6>
                    </div>
                    <div class="col-8">
                        <input type="number" class="form-control" id="edit_non_staff">
                    </div>
                </div>
                <div class="row bg-gradient-light py-3 px-2">
                    <div class="col-4">
                        <h6 class="modal-title fs-1">STAFF</h6>
                    </div>
                    <div class="col-8">
                        <input type="number" class="form-control" id="edit_staff">
                    </div>
                </div>
                <div class="row py-3 px-2">
                    <div class="col-12 text-center">
                        <button class="btn btn-success fs-1" id="update_estimation">SAVE</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteEstimationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-light">
                <h1 class="modal-title fs-1 px-2" id="exampleModalLabel">Delete Data Estimasi Makan</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-0">
                <input type="hidden" id="delete_id">
                <div class="row bg-gradient-light py-3 px-2">
                    <div class="col-12 text-center">
                        <h6 class="modal-title fs-1">Apakah anda yakin?</h6>
                    </div>
                </div>
                <div class="row py-3 px-2">
                    <div class="col-12 text-center">
                        <button class="btn btn-danger fs-1" id="delete_estimasi">Ya</button>
                        <button class="btn btn-secondary fs-1" data-dismiss="modal">Tidak</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
@endsection
@section('footerjs')
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{URL::asset('assets/js/iziToast.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script>
       $(function(){
                'use strict';

                $('.select2').select2({
                minimumResultsForSearch: Infinity
                });

                // Select2 by showing the search
                $('.select2-show-search').select2({
                minimumResultsForSearch: ''
                });

                // Colored Hover
                $('#select2').select2({
                dropdownCssClass: 'hover-success',
                minimumResultsForSearch: Infinity // disabling search
                });

                $('#select3').select2({
                dropdownCssClass: 'hover-danger',
                minimumResultsForSearch: Infinity // disabling search
                });

                // Outline Select
                $('#select4').select2({
                containerCssClass: 'select2-outline-success',
                dropdownCssClass: 'bd-success hover-success',
                minimumResultsForSearch: Infinity // disabling search
                });

                $('#select5').select2({
                containerCssClass: 'select2-outline-info',
                dropdownCssClass: 'bd-info hover-info',
                minimumResultsForSearch: Infinity // disabling search
                });

                // Full Colored Select Box
                $('#select6').select2({
                containerCssClass: 'select2-full-color select2-primary',
                minimumResultsForSearch: Infinity // disabling search
                });

                $('#select7').select2({
                containerCssClass: 'select2-full-color select2-danger',
                dropdownCssClass: 'hover-danger',
                minimumResultsForSearch: Infinity // disabling search
                });

                // Full Colored Dropdown
                $('#select8').select2({
                dropdownCssClass: 'select2-drop-color select2-drop-primary',
                minimumResultsForSearch: Infinity // disabling search
                });

                $('#select9').select2({
                dropdownCssClass: 'select2-drop-color select2-drop-indigo',
                minimumResultsForSearch: Infinity // disabling search
                });

                // Full colored for both box and dropdown
                $('#select10').select2({
                containerCssClass: 'select2-full-color select2-primary',
                dropdownCssClass: 'select2-drop-color select2-drop-primary',
                minimumResultsForSearch: Infinity // disabling search
                });

                $('#select11').select2({
                containerCssClass: 'select2-full-color select2-indigo',
                dropdownCssClass: 'select2-drop-color select2-drop-indigo',
                minimumResultsForSearch: Infinity // disabling search
                });
            });
</script>
    <script>
        var logged_user=$('#logged_user').val();

        $(document).ready(function () {
            var selected_tanggal = $('#tgl-awal-display').val();
            if (selected_tanggal) {
                try {
                    var tanggal_date = new Date(
                        selected_tanggal.substr(6, 4) + '-' +
                        selected_tanggal.substr(3, 2) + '-' +
                        selected_tanggal.substr(0, 2)
                    );

                    var currentdate = new Date();

                    var stringCurrentDate = currentdate.getDate() + ' ' +
                        currentdate.toLocaleString('default', { month: 'long' }) + ' ' +
                        currentdate.getFullYear();

                    var stringSelectDate = tanggal_date.getDate() + ' ' +
                        tanggal_date.toLocaleString('default', { month: 'long' }) + ' ' +
                        tanggal_date.getFullYear();

                    console.log('Tanggal saat ini:', stringCurrentDate);
                    console.log('Tanggal yang dipilih:', stringSelectDate);
                } catch (error) {
                    console.error('Error saat memproses tanggal:', error.message);
                }
            } else {
                console.error('Input #tgl-awal-display tidak memiliki nilai atau elemen tidak ditemukan.');
            }
        });


        function export_excel_konsumsi() {
            let from = document.getElementById("tgl-awal").value;

            Swal.fire({
                title: 'Please Wait...',
                html: 'Exporting Data...',
                didOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false,
            });

            $.ajax({
                type: "get",
                url: '{{ route('anggaran_makan.export_excel_konsumsi_estimasi') }}',
                data: {
                    from: from
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
                    {
                        swal.close();
                        Swal.fire({
                            title: 'Data Sudah Di Export!',
                            icon: "success",
                            showConfirmButton: true,
                            allowOutsideClick: false
                        });
                        var blob = new Blob([response]);
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = from +" Laporan Budgeting Makanan.xlsx";
                        link.click();
                    }
                },
            });
        }
        function export_excel_overtime_recap() {
            let from = document.getElementById("tgl-awal").value;

            Swal.fire({
                title: 'Please Wait...',
                html: 'Exporting Data...',
                didOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false,
            });

            $.ajax({
                type: "get",
                url: '{{ route('anggaran_makan.export_excel_overtime_recap') }}',
                data: {
                    from: from
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
                    {
                        swal.close();
                        Swal.fire({
                            title: 'Data Sudah Di Export!',
                            icon: "success",
                            showConfirmButton: true,
                            allowOutsideClick: false
                        });
                        var blob = new Blob([response]);
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = from +" Laporan Rekap lembur "+Math.ceil(Math.random()*1000000)+".xlsx";
                        link.click();
                    }
                },
            });
        }
        function export_pdf_konsumsi(){
            var tanggal=$('#tgl-awal').val();
            var url = 'anggaran-makan/export_pdf_konsumsi?tanggal='+tanggal;
            window.open(url, '_blank');

        }
        $('#datatable thead tr').clone(true).appendTo('#datatable thead');
        $('#datatable thead tr:eq(1) th').each(function(i) {
            if (i != 6) {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control form-control-sm" />');

                $('input', this).on('keyup change', function() {
                    if (datatable.column(i).search() !== this.value) {
                        datatable
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            } else {
                $(this).empty();
            }
        });
        let datatable = $("#datatable").DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            paging: true,
            searching: true,
            destroy: true,
            scrollX: true,
            ajax: {
                url: '{{ route('anggaran_makan.index') }}',
                data: function(d) {
                    d.tgl_awal = $('#tgl-awal').val();
                },
            },
            columns: [
                {
                    data: 'tanggal_fix'
                },
                {
                    data: 'keterangan'
                },
                {
                    data: 'department_name'
                },
                {
                    data: 'staff'
                },
                {
                    data: 'non_staff'
                },
                {
                    data: 'created_by'
                },
            ],
            columnDefs: [{
                    "className": "dt-center",
                    "targets": [4,5,6]
                },
                {
                    targets: [6],
                    render: (data, type, row, meta) => {
                        if(logged_user!='HR' && logged_user!='GA' && logged_user!='IT'){
                            if(stringSelectDate<stringCurrentDate){
                                return `
                                <button class='btn btn-warning btn-sm' disabled>
                                    <i class='fa fa-edit' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#editEstimationModal"></i>
                                </button>
                                <button class='btn btn-danger btn-sm' disabled>
                                    <i class='fa fa-trash' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#deleteEstimationModal"></i>
                                </button>`
                            }else if(stringSelectDate==stringCurrentDate){
                                if(currentdate.getHours()>12){
                                    return `
                                    <button class='btn btn-warning btn-sm' disabled>
                                        <i class='fa fa-edit' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#editEstimationModal"></i>
                                    </button>
                                    <button class='btn btn-danger btn-sm' disabled>
                                        <i class='fa fa-trash' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#deleteEstimationModal"></i>
                                    </button>`
                                }else if(currentdate.getHours()<13){
                                    return `
                                    <button class='btn btn-warning btn-sm'>
                                        <i class='fa fa-edit' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#editEstimationModal"></i>
                                    </button>
                                    <button class='btn btn-danger btn-sm'>
                                        <i class='fa fa-trash' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#deleteEstimationModal"></i>
                                    </button>`
                                }
                            }else{
                                return `
                                <button class='btn btn-warning btn-sm'>
                                    <i class='fa fa-edit' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#editEstimationModal"></i>
                                </button>
                                <button class='btn btn-danger btn-sm'>
                                    <i class='fa fa-trash' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#deleteEstimationModal"></i>
                                </button>`
                            }
                        }else{
                            return `
                                <button class='btn btn-warning btn-sm'>
                                    <i class='fa fa-edit' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#editEstimationModal"></i>
                                </button>
                                <button class='btn btn-danger btn-sm'>
                                    <i class='fa fa-trash' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#deleteEstimationModal"></i>
                                </button>`
                        }
                        if((currentdate.getHours()>13 || selected_tanggal<currentdate)&&(logged_user!='HR' && logged_user!='GA' && logged_user!='IT')){
                            return `
                                <button class='btn btn-warning btn-sm' disabled>
                                    <i class='fa fa-edit' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#editEstimationModal"></i>
                                </button>
                                <button class='btn btn-danger btn-sm' disabled>
                                    <i class='fa fa-trash' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#deleteEstimationModal"></i>
                                </button>`
                        }else{
                            return `
                                <button class='btn btn-warning btn-sm'>
                                    <i class='fa fa-edit' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#editEstimationModal"></i>
                                </button>
                                <button class='btn btn-danger btn-sm'>
                                    <i class='fa fa-trash' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#deleteEstimationModal"></i>
                                </button>`
                        }
                    }
            }]
        });
        function dataTableReload() {
            datatable.ajax.reload();
        }
        $('#tgl-awal-display').change(function(){
            var selected_tanggals=$('#tgl-awal-display').val();
            var select_tanggal=new Date(selected_tanggals.substr(6,4)+'-'+selected_tanggals.substr(3,2)+'-'+selected_tanggals.substr(0,2));
            var stringSelectedDate=select_tanggal.getDate()+' '+select_tanggal.toLocaleString('default', { month: 'long' })+' '+select_tanggal.getFullYear();
            if(logged_user!='HR' && logged_user!='GA' && logged_user!='IT'){
                if(stringSelectedDate<stringCurrentDate){
                    $('#btn_new').attr('disabled','disabled');
                }else if(stringSelectedDate==stringCurrentDate){
                    if(currentdate.getHours()>12){
                        $('#btn_new').attr('disabled','disabled');
                    }else if(currentdate.getHours()<13){
                        $('#btn_new').removeAttr('disabled');
                    }
                }else{
                    $('#btn_new').removeAttr('disabled');
                }
            }else{
                $('#btn_new').removeAttr('disabled');
            }
        });
        $(document).ready(function() {
            function dataTableReload() {
                datatable.ajax.reload();
            }
            console.log('hallo')
            if(logged_user!='HR' && logged_user!='GA' && logged_user!='IT'){
                if(stringSelectDate<stringCurrentDate){
                    $('#btn_new').attr('disabled','disabled');
                }else if(stringCurrentDate==stringSelectDate){
                    if(currentdate.getHours()>12){
                        $('#btn_new').attr('disabled','disabled');
                    }else if(currentdate.getHours()<13){
                        $('#btn_new').removeAttr('disabled');
                    }
                }else{
                    $('#btn_new').removeAttr('disabled');
                }
            }else{
                $('#btn_new').removeAttr('disabled');
            }
        });
        function edit_estimasi(id){
            var id_estimasi=id;
            $.ajax({
                type:"POST",
                url: '{{ route('anggaran_makan.edit') }}',
                data: {
                    id:id_estimasi,
                },
                success:function(res){
                    $('#edit_id').val(res[0].id);
                    $('#edit_tanggal').val(res[0].tanggal);
                    $('#edit_keterangan').val(res[0].keterangan);
                    $('#edit_bagian').val(res[0].dept);
                    $('#edit_staff').val(res[0].staff);
                    $('#edit_non_staff').val(res[0].non_staff);
                    $('#delete_id').val(res[0].id);
                }
            });
        }
        $('#delete_estimasi').click(function(){
            var id_estimasi=$('#delete_id').val();
            $.ajax({
                type:"POST",
                url: '{{ route('anggaran_makan.delete') }}',
                data: {
                    id:id_estimasi,
                },
                success:function(res){
                    Swal.fire({
                        title: 'Data Sudah Di Hapus!',
                        icon: "success",
                        showConfirmButton: true,
                        allowOutsideClick: false
                    });
                    $('#deleteEstimationModal').modal('hide');
                    $('#datatable').DataTable().ajax.reload();
                }
            });
        });
        function save_estimation(){
            var tanggal=$('#tanggal').val();
            console.log(tanggal);
            var keterangan=$('#keterangan').val();
            var bagian=$('#bagian').val();
            var staff=$('#staff').val();
            var non_staff=$('#non_staff').val();
            $.ajax({
                type:"POST",
                url: '{{ route('anggaran_makan.store') }}',
                data: {
                    tanggal:tanggal,
                    keterangan:keterangan,
                    bagian:bagian,
                    staff:staff,
                    non_staff:non_staff
                },
                success: function(res){
                    Swal.fire({
                        title: 'Data Sudah Di Simpan!',
                        icon: "success",
                        showConfirmButton: true,
                        allowOutsideClick: false
                    });
                    $('#newEstimationModal').modal('hide');
                    $('#datatable').DataTable().ajax.reload();
                    $('#keterangan').val('');
                    $('#bagian').val('');
                    $('#staff').val('');
                    $('#non_staff').val('');
                    document.getElementById("tanggal-display").style.border = null;
                    document.getElementById("keterangan").style.border = null;
                    document.getElementById("bagian").style.border = null;
                    $('#tanggal-display').next().html('');
                    $('#keterangan').next().html('');
                    $('#bagian').next().html('');
                },
                error:function(error){
                    let err_log=error.responseJSON.errors;
                    if(error.status==422){
                        if(typeof(err_log.tanggal)!=='undefined'){
                            $('#tanggal').next().html('<span style="color:red">Tanggal '+error.responseJSON.errors.tanggal[0]+'</span>');
                            document.getElementById("tanggal-display").style.border = "1px solid red";
                        }else{
                            $('#tanggal').next().html('');
                            document.getElementById("tanggal-display").style.border = null;
                        }
                        if(typeof(err_log.keterangan)!=='undefined'){
                            $('#keterangan').next().html('<span style="color:red">Keterangan '+error.responseJSON.errors.keterangan[0]+'</span>');
                            document.getElementById("keterangan").style.border = "1px solid red";
                        }else{
                            $('#keterangan').next().html('');
                            document.getElementById("keterangan").style.border = null;
                        }
                        if(typeof(err_log.bagian)!=='undefined'){
                            $('#bagian').next().html('<span style="color:red">Bagian '+error.responseJSON.errors.bagian[0]+'</span>');
                            document.getElementById("bagian").style.border = "1px solid red";
                        }else{
                            $('#bagian').next().html('');
                            document.getElementById("bagian").style.border = null;
                        }
                    }
                }
            });
        };
        $('#update_estimation').click(function(){
            var id=$('#edit_id').val();
            var tanggal=$('#edit_tanggal').val();
            var keterangan=$('#edit_keterangan').val();
            var bagian=$('#edit_bagian').val();
            var staff=$('#edit_staff').val();
            var non_staff=$('#edit_non_staff').val();
            $.ajax({
                type:"POST",
                url: '{{ route('anggaran_makan.update') }}',
                data: {
                    id:id,
                    tanggal:tanggal,
                    keterangan:keterangan,
                    bagian:bagian,
                    staff:staff,
                    non_staff:non_staff
                },
                success: function(res){
                    Swal.fire({
                        title: 'Data Sudah Di Update!',
                        icon: "success",
                        showConfirmButton: true,
                        allowOutsideClick: false
                    });
                    $('#editEstimationModal').modal('hide');
                    $('#datatable').DataTable().ajax.reload();
                    $('#edit_id').val('');
                    $('#edit_tanggal').val('');
                    $('#edit_keterangan').val('');
                    $('#edit_bagian').val('');
                    $('#edit_staff').val('');
                    $('#edit_non_staff').val('');
                }
            });
        });
    </script>
@endsection
