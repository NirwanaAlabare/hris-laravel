@extends('admin.adminlayouts.adminlayout-mut-karyawan')

@section('head')
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{ URL::asset('assets/css/iziToast.min.css') }}">

@stop

@section('mainarea')
<body>


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
                    @if (Auth::guard('admin')->user()->email == 'indri@nag.nirwanaindonesia.com' || Auth::guard('admin')->user()->email =='ersa@ptnag.com' || Auth::guard('admin')->user()->email =='mega@ptnag.com' || Auth::guard('admin')->user()->email =='rudy@ptnag.com' || Auth::guard('admin')->user()->email =='dev_hris' || Auth::guard('admin')->user()->email =='GA'  || Auth::guard('admin')->user()->email =='tita' || Auth::guard('admin')->user()->email =='willy@ptnag.com'|| Auth::guard('admin')->user()->email =='steven')
                    <a onclick="export_excel_konsumsi()" class="btn btn-outline-success position-relative">
                        <i class="fa fa-file-excel"></i>
                        Estimasi Anggaran Makan
                    </a>
                    {{-- <a onclick="export_excel_overtime_recap()" class="btn btn-outline-success position-relative">
                        <i class="fa fa-file-excel"></i>
                        Overtime Recap
                    </a> --}}
                    <a onclick="export_pdf_konsumsi()" class="btn btn-outline-danger position-relative">
                        <i class="fa fa-file-pdf"></i>
                        Approval Anggaran Makan
                    </a>
                    @endif
                </div>
                 <div class="d-flex gap-4 align-items-end">

                    <div class="">
                        <label class="form-label ml-3"><small><b>Tanggal Form :</b></small></label>
                        <input type="hidden" id="tgl_awal" name="tgl_awal">
                        <input type="hidden" id="tgl_akhir" name="tgl_akhir">
                        <input type="hidden" id="daterange1" name="daterange1" oninput="dataTableReload()" onchange="dataTableReload()" value="{{ date('d-m-Y') }}">
                        {{-- <label class="form-label ml-3"><small><b>Tanggal Form</b></small></label> --}}
                        <a class="nav-link card-title ml-3 py-3 pl-3" style="border: 1px solid #d8d4dc" id="daterange-btn1" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Klik di sini untuk pilih tanggal kehadiran"></a>
                    </div>
                </div>
                {{-- <div class="col">
                    <div class="row">
                        <div class="col-4 text-right">Tanggal :</div>
                        <div class="col pl-0"><input type="text" class="form-control form-control-sm fc-datepicker" id="tgl-awal" name="tgl_awal" oninput="dataTableReload()" onchange="dataTableReload()" value="{{ date('d-m-Y') }}" style="background-color:white"></div>
                    </div>
                </div> --}}
            </div>
            <div class="table-responsive">
                <table id="datatable" class="table table-bordered table-sm w-100 table-hover text-nowrap">
                    <thead class="bg-primary">
                        <tr style='text-align:center; vertical-align:middle'>
                            <th>TANGGAL</th>
                            <th>KETERANGAN</th>
                            <th>BAGIAN</th>
                            <th>SUB BAGIAN</th>
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
                            <input type="text" class="form-control fc-datepicker" id="tanggal" value="{{ date('d-m-Y') }}">
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
                        {{-- <div class="form-group col-8">
                            <select class='form-control select2' style='width: 100%;' name='bagian' id='bagian' required>
                                <option selected="selected" value="" disabled="true">Pilih Bagian</option>
                                @foreach ($dept as $d)
                                    <option value="{{$d->department_id}}">{{$d->department_name}}</option>
                                @endforeach
                            </select>
                        </div> --}}
                         <div class="form-group col-8">
                            <select class='form-control select2' style='width: 100%;' name='bagian' id='bagian' required>
                                <option selected="selected" value="" disabled="true">Pilih Bagian</option>
                                @foreach ($dept as $d)
                                    <option value="{{ $d->department_id }}|{{ $d->sub_dept_id }}">
                                        {{ $d->department_name }} - {{ $d->sub_dept_name }}
                                    </option>
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
                            <button  class="btn btn-success fs-1" id="save_estimation">SUBMIT</button>
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
                            <input type="text" class="form-control fc-datepicker" id="edit_tanggal" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="row py-3 px-2">
                        <div class="col-4">
                            <h6 class="modal-title fs-1">KETERANGAN</h6>
                        </div>
                        <div class="col-8">
                            <input type="hidden" id="edit_id" readonly>
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
                            <input type="text" class="form-control" id="edit_department" readonly>
                        </div>
                    </div>

                    <div class="row py-3 px-2">
                        <div class="col-4">
                            <h6 class="modal-title fs-1">SUB DEPARTMENT</h6>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control" id="edit_sub_dept" readonly>
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
</body>
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

     <!-- Datepicker js -->
 <script src="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.js')}}"></script>
 <script src="{{URL::asset('assets/plugins/spectrum-date-picker/jquery-ui.js')}}"></script>
 <script src="{{URL::asset('assets/plugins/input-mask/jquery.maskedinput.js')}}"></script>

 <script>
        $('.fc-datepicker').datepicker({
                showOtherMonths: true,
                selectOtherMonths: true,
                dateFormat: 'dd-mm-yy'
            });
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
            if(logged_user!='INDRI FEBRIANTY' && logged_user!='GA' && logged_user!='Ersa Regina Nugraha' && logged_user!='rudy' && logged_user!='Gaga' && logged_user!='dev_hris'){
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
        $(document).ready(function () {
            $('#bagian').select2({
                placeholder: "Pilih Bagian",
                tags: true,
                allowClear: true,
                dropdownParent: $('#newEstimationModal')
            });
        });

        var currentdate = new Date();
        var selected_tanggal = $('#tgl-awal-display').val();
        var tanggal_date=new Date(selected_tanggal.substr(6,4)+'-'+selected_tanggal.substr(3,2)+'-'+selected_tanggal.substr(0,2))
        var stringCurrentDate=currentdate.getDate()+' '+currentdate.toLocaleString('default', { month: 'long' })+' '+currentdate.getFullYear();
        var stringSelectDate=tanggal_date.getDate()+' '+tanggal_date.toLocaleString('default', { month: 'long' })+' '+tanggal_date.getFullYear();
        var logged_user=$('#logged_user').val();

        $(document).ready(function () {

            var start = moment();
            var end   = moment();

            function setTanggal(start, end) {
                let label = start.isSame(end, 'day')
                    ? start.format("D MMM YYYY").toUpperCase()
                    : start.format("D MMM YYYY").toUpperCase() + ' s/d ' + end.format("D MMM YYYY").toUpperCase();

                $('#daterange-btn1').html(
                    '<span><i class="fa fa-calendar"></i> ' + label + '</span><i class="fa fa-angle-down ml-1"></i>'
                );

                $('#tgl_awal').val(start.format('YYYY-MM-DD'));
                $('#tgl_akhir').val(end.format('YYYY-MM-DD'));

                dataTableReload();
            }

            setTanggal(start, end);

            $('#daterange-btn1').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Hari ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 Hari Kemarin': [moment().subtract(6, 'days'), moment()],
                    '30 Hari Kemarin': [moment().subtract(29, 'days'), moment()],
                    'Bulan Sekarang': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan Kemarin': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, setTanggal);
        });
        $(document).on('click', '#btn_new', function() {
            // Ambil tanggal dari filter daterange
            const tglAwal = $('#tgl_awal').val(); // Format: YYYY-MM-DD
            const tglAkhir = $('#tgl_akhir').val();

            // Jika range tanggal sama (single date), gunakan tanggal tersebut
            if (tglAwal === tglAkhir) {
                // Konversi dari YYYY-MM-DD ke DD-MM-YYYY untuk datepicker
                const parts = tglAwal.split('-');
                const formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];

                // Set nilai ke input tanggal di modal
                $('#tanggal').datepicker('setDate', formattedDate);
            } else {
                // Jika range, gunakan tanggal awal
                const parts = tglAwal.split('-');
                const formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
                $('#tanggal').datepicker('setDate', formattedDate);
            }

            // Buka modal
            $('#newEstimationModal').modal('show');
        });
        function export_excel_konsumsi() {
                let from = document.getElementById("tgl_awal").value;
                let to   = document.getElementById("tgl_akhir").value;

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
                    from: from,
                     to:to
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


        // function export_excel_konsumsi() {
        //     let from = document.getElementById("tgl-awal").value;

        //     Swal.fire({
        //         title: 'Please Wait...',
        //         html: 'Exporting Data...',
        //         didOpen: () => {
        //             Swal.showLoading()
        //         },
        //         allowOutsideClick: false,
        //     });

        //     $.ajax({
        //         type: "get",
        //         url: '{{ route('anggaran_makan.export_excel_konsumsi_estimasi') }}',
        //         data: {
        //             from: from
        //         },
        //         xhrFields: {
        //             responseType: 'blob'
        //         },
        //         success: function(response) {
        //             {
        //                 swal.close();
        //                 Swal.fire({
        //                     title: 'Data Sudah Di Export!',
        //                     icon: "success",
        //                     showConfirmButton: true,
        //                     allowOutsideClick: false
        //                 });
        //                 var blob = new Blob([response]);
        //                 var link = document.createElement('a');
        //                 link.href = window.URL.createObjectURL(blob);
        //                 link.download = from +" Laporan Budgeting Makanan.xlsx";
        //                 link.click();
        //             }
        //         },
        //     });
        // }
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
            let from = document.getElementById("tgl_awal").value;
            let to   = document.getElementById("tgl_akhir").value;
            // var tanggal=$('#tgl-awal').val();
            var url = 'anggaran-makan/export_pdf_konsumsi?from='+from+'&to='+to;
            window.open(url, '_blank');
        }
        $('#datatable thead tr').clone(true).appendTo('#datatable thead');
        $('#datatable thead tr:eq(1) th').each(function(i) {
            if (i != 7) {
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
        // function renderDateTimeCalendar(data) {
        //     const parts = data.split('-');
        //     return `${parts[2]}-${parts[1]}-${parts[0]}`; // YYYY-MM-DD
        // }
        let datatable = $("#datatable").DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            paging: false,
            searching: true,
            // destroy: true,
            scrollX: true,
            ajax: {
                url: '{{ route('anggaran_makan.index') }}',
                data: function(d) {
                    d.tgl_awal = $('#tgl_awal').val()  ;
                    d.tgl_akhir = $('#tgl_akhir').val()   ;
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
                    data: 'sub_dept_name'
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
                    "targets": [4,5,6,7]
                },
                {
                    targets: [7],
                    render: (data, type, row, meta) => {
                        if(logged_user!='HR' && logged_user!='GA' && logged_user!='IT' && logged_user!='rudy' && logged_user!='Gaga' && logged_user!='dev_hris' && logged_user!='Ersa Regina Nugraha' && logged_user!='tita'){
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
                        if((currentdate.getHours()>13 || selected_tanggal<currentdate)&&(logged_user!='HR' && logged_user!='GA' && logged_user!='IT' && logged_user!='rudy' && logged_user!='Gaga' && logged_user!='dev_hris' && logged_user!='Ersa Regina Nugraha' && logged_user!='tita')){
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
            if(logged_user!='HR' && logged_user!='GA' && logged_user!='IT' && logged_user!='rudy' && logged_user!='Gaga' && logged_user!='dev_hris' && logged_user!='Ersa Regina Nugraha' && logged_user!='tita'){
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
            datatable.ajax.reload(null, false);
        }

        function renderDateTimeCalendar(data) {
            if (!data) return '';
            let parts = data.split('-'); // yyyy-mm-dd
            return parts[2] + '-' + parts[1] + '-' + parts[0]; // dd-mm-yyyy
        }

        function edit_estimasi(id){
            $.ajax({
                type: "POST",
                url: "{{ route('anggaran_makan.edit') }}",
                data: { id },
                success: function(res){
                    let data = res[0];
                    console.log(data);

                    // tampilkan department
                    $('#edit_department').val(data.department_name);

                    // tampilkan sub dept (jika ada)
                    if (data.sub_dept_id && data.sub_dept_id !== '') {
                        $('#edit_sub_dept').val(data.sub_dept_name);
                    } else {
                        $('#edit_sub_dept').val('-');
                    }

                    // field lain
                    $('#edit_id').val(data.id);
                    $('#edit_tanggal').val(renderDateTimeCalendar(data.tanggal));
                    $('#edit_keterangan').val(data.keterangan);
                    $('#edit_staff').val(data.staff);
                    $('#edit_non_staff').val(data.non_staff);
                    $('#delete_id').val(data.id);

                    // kunci field struktur
                    $('#edit_keterangan').prop('disabled', true);
                    $('#edit_tanggal').prop('readonly', true);
                },
                error: function(){
                    alert('Gagal mengambil data!');
                }
            });
        }



        $('#delete_estimasi').click(function(){
            let tanggal = $('#edit_tanggal').val();
            sessionStorage.setItem('selectedTanggal', $('#edit_tanggal').val());
            var id_estimasi=$('#delete_id').val();
            $.ajax({
                type:"POST",
                url: '{{ route('anggaran_makan.delete') }}',
                data: {
                    id:id_estimasi,
                },
               success: function (response) {
                    Swal.fire({
                        title: 'Berhasil',
                        text: response.message || 'Data berhasil dihapus',
                        icon: 'success',
                        timer: 1000,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        closeModal('#deleteEstimationModal');
                        location.reload();
                    });
                }
            });
        });
        // function toggleInputByKeterangan() {
        //     let keterangan = $('#keterangan').val();

        //     if (keterangan === 'SHIFT MALAM') {
        //         // manual input
        //         $('#staff').prop('readonly', false).val('');
        //         $('#non_staff').prop('readonly', false).val('');
        //     } else if (keterangan === 'LEMBUR') {
        //         // otomatis dari sistem
        //         $('#staff').prop('readonly', true);
        //         $('#non_staff').prop('readonly', true);
        //         hitungEstimasiMakan(); // panggil AJAX
        //     } else {
        //         // default
        //         $('#staff').prop('readonly', false).val(0);
        //         $('#non_staff').prop('readonly', false).val(0);
        //     }
        // }
        // $('#keterangan').on('change', function () {
        //     toggleInputByKeterangan();
        // });
        // function hitungEstimasiMakan() {
        //     let bagian = $('#bagian').val();
        //     let tanggal = $('#tanggal').val(); // dd-mm-yyyy

        //     if (!bagian || !tanggal) {
        //         $('#staff').val(0);
        //         $('#non_staff').val(0);
        //         return;
        //     }

        //     let split = bagian.split('|');
        //     let sub_dept_id = split[1];

        //     $.ajax({
        //         type: 'GET',
        //         url: '{{ route("anggaran_makan.get_estimasi") }}',
        //         data: {
        //             sub_dept_id: sub_dept_id,
        //             tanggal: tanggal
        //         },
        //         success: function (res) {
        //             $('#staff').val(res.staff);
        //             $('#non_staff').val(res.non_staff);
        //         },
        //         error: function () {
        //             $('#staff').val(0);
        //             $('#non_staff').val(0);
        //         }
        //     });
        // }
        // $('#bagian').on('change', function () {
        //     hitungEstimasiMakan();
        // });

        // // saat TANGGAL berubah
        // $('#tanggal').on('change', function () {
        //     hitungEstimasiMakan();
        // });
       $('#save_estimation').click(function(){
            let tgl = $('#tanggal').val().split('-');
            let tanggal = `${tgl[2]}-${tgl[1]}-${tgl[0]}`;

            // Simpan tanggal untuk direstore setelah reload
            sessionStorage.setItem('selectedTanggal', $('#tanggal').val());

            $.ajax({
                type: "POST",
                url: "{{ route('anggaran_makan.store') }}",
                data: {
                    tanggal: tanggal,
                    keterangan: $('#keterangan').val(),
                    bagian: $('#bagian').val(),
                    staff: $('#staff').val(),
                    non_staff: $('#non_staff').val()
                },
                success: function(response){
                    Swal.fire({
                        title: 'Sukses',
                        text: response.message || 'Data berhasil disimpan',
                        icon: 'success',
                        timer: 1000,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                            closeModal('#newEstimationModal');
                            location.reload();
                    });
                },
                error: function (xhr) {
                    Swal.fire(
                        'Gagal',
                        xhr.responseJSON?.message || 'Terjadi kesalahan',
                        'error'
                    );
                }
            });
        });
        $('#update_estimation').click(function(){

            let id = $('#edit_id').val();
            let tanggal = $('#edit_tanggal').val(); // dd-mm-yyyy
            sessionStorage.setItem('selectedTanggal', $('#edit_tanggal').val());

            let parts = tanggal.split('-');
            let formatBaru = parts[2] + '-' + parts[1] + '-' + parts[0]; // yyyy-mm-dd

            $.ajax({
                type: "POST",
                url: "{{ route('anggaran_makan.update') }}",
                data: {
                    id: id,
                    tanggal: formatBaru,
                    keterangan: $('#edit_keterangan').val(),
                    bagian: $('#edit_bagian').val(),
                    staff: $('#edit_staff').val(),
                    non_staff: $('#edit_non_staff').val()
                },
               success: function (response) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: response.message || 'Data berhasil diupdate',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(() => {
                            closeModal('#editEstimationModal');
                            location.reload();
                        });
                    }
                    });
                });
    //     $('#update_estimation').click(function(){
    //         var id=$('#edit_id').val();
    //         var tanggal=$('#edit_tanggal').val();
    //         var parts = tanggal.split('-');     // hasil: ['28', '07', '2025']
    //         var formatBaru = parts[2] + '-' + parts[1] + '-' + parts[0];
    //         var keterangan=$('#edit_keterangan').val();
    //         var bagian=$('#edit_bagian').val();
    //         var staff=$('#edit_staff').val();
    //         var non_staff=$('#edit_non_staff').val();
    //         $.ajax({
    //             type:"POST",
    //             url: '{{ route('anggaran_makan.update') }}',
    //             data: {
    //                 id:id,
    //                 tanggal:formatBaru,
    //                 keterangan:keterangan,
    //                 bagian:bagian,
    //                 staff:staff,
    //                 non_staff:non_staff
    //             },
    //             success: function(res){
    //                 Swal.fire({
    //                     title: 'Data Sudah Di Update!',
    //                     icon: "success",
    //                     showConfirmButton: true,
    //                     allowOutsideClick: false
    //                 });
    //                 $(".modal-backdrop").remove();
    //                 $("body").removeClass("modal-open");
    //                 $('#editEstimationModal').modal('hide');
    //                 dataTableReload();
    //                 $('#edit_id').val('');
    //                 $('#edit_tanggal').val('');
    //                 $('#edit_keterangan').val('');
    //                 $('#edit_bagian').val('');
    //                 $('#edit_staff').val('');
    //                 $('#edit_non_staff').val('');
    //             }
    //         });
    //     });
    function closeModal(modalId){
        $(modalId).modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
    }

    function resetCreateForm(){
        $('#tanggal').val('');
        $('#keterangan').val('');
        $('#bagian').val('').trigger('change');
        $('#staff').val('');
        $('#non_staff').val('');
    }

    function formatTanggal(tgl){
        if(!tgl) return '';
        let p = tgl.split('-');
        return `${p[2]}-${p[1]}-${p[0]}`;
    }
    $(document).ready(function() {
    // Restore tanggal dari sessionStorage setelah reload
    const savedTanggal = sessionStorage.getItem('selectedTanggal');

    if (savedTanggal) {
        // Set ke datepicker
        $('#tanggal').datepicker('setDate', savedTanggal);

        // Jika ada filter daterange, update juga
        if (savedTanggal && $('#daterange-btn1').length) {
            const parts = savedTanggal.split('-');
            const yyyymmdd = parts[2] + '-' + parts[1] + '-' + parts[0];
            const momentDate = moment(yyyymmdd);

            // Update daterange filter
            $('#daterange-btn1').data('daterangepicker').setStartDate(momentDate);
            $('#daterange-btn1').data('daterangepicker').setEndDate(momentDate);

            // Update hidden inputs
            $('#tgl_awal').val(yyyymmdd);
            $('#tgl_akhir').val(yyyymmdd);

            // Update label
            const label = momentDate.format("D MMM YYYY").toUpperCase();
            $('#daterange-btn1').html(
                '<span><i class="fa fa-calendar"></i> ' + label + '</span><i class="fa fa-angle-down ml-1"></i>'
            );

            // Reload datatable dengan tanggal baru
            dataTableReload();
        }

        // Hapus sessionStorage setelah digunakan
        setTimeout(() => {
            sessionStorage.removeItem('selectedTanggal');
        }, 100);
    }
});

    // </script>


@endsection
