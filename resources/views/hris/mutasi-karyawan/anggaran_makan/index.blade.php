@extends('admin.adminlayouts.adminlayout-mut-karyawan')

@section('head')
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{ URL::asset('assets/css/iziToast.min.css') }}">

@stop

@section('mainarea')
    <div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
        <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
            <li><a href="{{route('fls.index')}}">Anggaran Makan</a></li>
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
    <input type="hidden" id="tgl-awal-display" name="tgl_awal-display" value="{{ date('d-m-Y') }}" >

    <div class="card card-sb">
        <div class="card-header">
            <h5 class="card-title fw-bold mb-0"><i class="fa fa-utensils"></i> ESTIMASI ANGGARAN MAKAN</h5>
        </div>
        <div class="card-body">
            <div class="row pb-2">
                <div class="col-9">
                    <input type="hidden" id="logged_user" value="{{$user}}">
                    <button class="btn btn-outline-primary position-relative" data-toggle="modal" data-target="#newEstimationModal" id="btn_new">
                        <i class="fa fa-plus"></i>
                        Baru
                    </button>
                    @if (Auth::guard('admin')->user()->email == 'indri@nag.nirwanaindonesia.com' || Auth::guard('admin')->user()->email =='ersa@ptnag.com' || Auth::guard('admin')->user()->email =='mega@ptnag.com' || Auth::guard('admin')->user()->email =='rudy@ptnag.com' || Auth::guard('admin')->user()->email =='fadli' || Auth::guard('admin')->user()->email =='GA')
                    <a onclick="export_excel_konsumsi()" class="btn btn-outline-success position-relative">
                        <i class="fa fa-file-excel"></i>
                        Estimasi Anggaran Makan
                    </a>
                    <a onclick="export_excel_overtime_recap()" class="btn btn-outline-success position-relative">
                        <i class="fa fa-file-excel"></i>
                        Overtime Recap
                    </a>
                    <a onclick="export_pdf_konsumsi()" class="btn btn-outline-danger position-relative">
                        <i class="fa fa-file-pdf"></i>
                        Approval Anggaran Makan
                    </a>
                    @endif
                </div>
                <div class="col">
                    <div class="row">
                        <div class="col-4 text-right">Tanggal :</div>
                        <div class="col pl-0"><input type="date" class="form-control form-control-sm " id="tgl-awal" name="tgl_awal" oninput="dataTableReload()" onchange="dataTableReload()" value="{{ date('Y-m-d') }}" style="background-color:white"></div>
                    </div>
                </div>
            </div>
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
                <div class="modal-header bg-sb">
                    <h1 class="modal-title fs-1 px-2" id="exampleModalLabel">Edit Data Estimasi Makan</h1>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">x</button>
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
                            <select class="form-control" id="edit_keterangan">
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
                            <select class="form-control select2" id="edit_bagian">
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
                <div class="modal-header bg-danger">
                    <h1 class="modal-title fs-1 px-2" id="exampleModalLabel">Delete Data Estimasi Makan</h1>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">x</button>
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
    <!-- DataTables & Plugins -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/iziToast.min.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script>
          $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
               // Ambil tanggal hari ini
            let today = new Date();
            let formattedDate = today.getDate().toString().padStart(2, '0') + '/' +
                                (today.getMonth() + 1).toString().padStart(2, '0') + '/' +
                                today.getFullYear();

            // Set nilai ke input
            $('#tgl-awal-display').val(formattedDate);
            if(logged_user!='INDRI FEBRIANTY' && logged_user!='GA' && logged_user!='Ersa Regina Nugraha' && logged_user!='rudy' && logged_user!='Gaga' && logged_user!='fadli'){
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

        var currentdate = new Date();
        var selected_tanggal = $('#tgl-awal-display').val();
        var tanggal_date=new Date(selected_tanggal.substr(6,4)+'-'+selected_tanggal.substr(3,2)+'-'+selected_tanggal.substr(0,2))
        var stringCurrentDate=currentdate.getDate()+' '+currentdate.toLocaleString('default', { month: 'long' })+' '+currentdate.getFullYear();
        var stringSelectDate=tanggal_date.getDate()+' '+tanggal_date.toLocaleString('default', { month: 'long' })+' '+tanggal_date.getFullYear();
        var logged_user=$('#logged_user').val();

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
            paging: false,
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
                        if(logged_user!='HR' && logged_user!='GA' && logged_user!='IT' && logged_user!='rudy' && logged_user!='Gaga' && logged_user!='fadli'){
                            if(stringSelectDate<stringCurrentDate){
                                return `
                                <button class='btn btn-warning btn-sm' disabled onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#editEstimationModal">
                                    <i class='fa fa-edit'></i>
                                </button>
                                <button class='btn btn-danger btn-sm' disabled onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#deleteEstimationModal">
                                    <i class='fa fa-trash'></i>
                                </button>`
                            }else if(stringSelectDate==stringCurrentDate){
                                if(currentdate.getHours()>12){
                                    return `
                                    <button class='btn btn-warning btn-sm' disabled onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#editEstimationModal">
                                        <i class='fa fa-edit'></i>
                                    </button>
                                    <button class='btn btn-danger btn-sm' disabled onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#deleteEstimationModal">
                                        <i class='fa fa-trash'></i>
                                    </button>`
                                }else if(currentdate.getHours()<13){
                                    return `
                                    <button class='btn btn-warning btn-sm' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#editEstimationModal">
                                        <i class='fa fa-edit'></i>
                                    </button>
                                    <button class='btn btn-danger btn-sm' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#deleteEstimationModal">
                                        <i class='fa fa-trash'></i>
                                    </button>`
                                }
                            }else{
                                return `
                                <button class='btn btn-warning btn-sm' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#editEstimationModal">
                                    <i class='fa fa-edit'></i>
                                </button>
                                <button class='btn btn-danger btn-sm' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#deleteEstimationModal">
                                    <i class='fa fa-trash'></i>
                                </button>`
                            }
                        }else{
                            return `
                                <button class='btn btn-warning btn-sm' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#editEstimationModal">
                                    <i class='fa fa-edit'></i>
                                </button>
                                <button class='btn btn-danger btn-sm' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#deleteEstimationModal">
                                    <i class='fa fa-trash'></i>
                                </button>`
                        }
                        if((currentdate.getHours()>13 || selected_tanggal<currentdate)&&(logged_user!='HR' && logged_user!='GA' && logged_user!='IT' && logged_user!='rudy' && logged_user!='Gaga' && logged_user!='fadli')){
                            return `
                                <button class='btn btn-warning btn-sm' disabled onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#editEstimationModal">
                                    <i class='fa fa-edit'></i>
                                </button>
                                <button class='btn btn-danger btn-sm' disabled onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#deleteEstimationModal">
                                    <i class='fa fa-trash'></i>
                                </button>`
                        }else{
                            return `
                                <button class='btn btn-warning btn-sm' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#editEstimationModal">
                                    <i class='fa fa-edit'></i>
                                </button>
                                <button class='btn btn-danger btn-sm' onclick=edit_estimasi(`+row.id+`) data-toggle="modal" data-target="#deleteEstimationModal">
                                    <i class='fa fa-trash'></i>
                                </button>`
                        }
                    }
            }]
        });

        $('#tgl-awal-display').change(function(){
            var selected_tanggals=$('#tgl-awal-display').val();
            var select_tanggal=new Date(selected_tanggals.substr(6,4)+'-'+selected_tanggals.substr(3,2)+'-'+selected_tanggals.substr(0,2));
            var stringSelectedDate=select_tanggal.getDate()+' '+select_tanggal.toLocaleString('default', { month: 'long' })+' '+select_tanggal.getFullYear();
            if(logged_user!='HR' && logged_user!='GA' && logged_user!='IT' && logged_user!='rudy' && logged_user!='Gaga' && logged_user!='fadli'){
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

        function dataTableReload() {
            datatable.ajax.reload();
        }
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
                    $(".modal-backdrop").remove();
                    $("body").removeClass("modal-open");
                    dataTableReload();
                }
            });
        });
        $('#save_estimation').click(function(){
            var tanggal=$('#tanggal').val();
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
                    $(".modal-backdrop").remove();
                    $("body").removeClass("modal-open");
                    dataTableReload();
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
        });
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
                    $(".modal-backdrop").remove();
                    $("body").removeClass("modal-open");
                    $('#editEstimationModal').modal('hide');
                    dataTableReload();
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
