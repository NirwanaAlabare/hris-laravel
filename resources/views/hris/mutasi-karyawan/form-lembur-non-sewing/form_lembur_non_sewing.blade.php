@extends('admin.adminlayouts.adminlayout-mut-karyawan')

@section('head')
<link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{ URL::asset('assets/css/iziToast.min.css') }}">


<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

<style>
  table.dataTable {
    table-layout: auto !important;
    width: 100% !important;
}

table.dataTable th,
table.dataTable td {
    white-space: nowrap; /* Mencegah teks berpindah ke baris baru */
    text-overflow: ellipsis; /* Menambahkan "..." jika teks terlalu panjang */
    overflow: hidden;
}


    #datatable thead th {
    background-color: #15435A;
    color: white;
    }
</style>
    @stop
    @section('mainarea')
    <div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
        <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
            <li><a href="{{route('flns.index')}}">Form Lembur</a></li>
            <li class="active"><span>Fowm Lembur Non Sewing</span></li>
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
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" style="max-width: 85%;">
            <div class="modal-content">
                <div class="modal-header bg-sb text-light">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div style="height: 100vh; overflow-y: auto; over-flow-x:none;">
                    <div class="row p-2">
                        <div class="col">
                            <button type="button" class="btn btn-primary rounded" onclick="myFunction()" id="tambah_karyawan"><span class="fa fa-plus">&nbsp;</span>Tambah Karyawan</button>
                        </div>
                    </div>
                    <table class="table table-striped" id="overtime_employee_header">
                        <input type="hidden" class="form-control" id="user" name="user" value="{{ $user }}">
                        <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th width="160">Employee Name</th>
                                <th width="100">NIK</th>
                                <th width="80">Jabatan</th>
                                <th width="180">Sub Department Name</th>
                                <th width="100">Absen In</th>
                                <th width="100">Absen Out</th>
                                <th width="80">Lembur In</th>
                                <th width="80">Out</th>
                                <th width="80">Istirahat</th>
                                <th width="100">Jumlah Jam</th>
                                <th width="50">Act</th>
                            </tr>
                        </thead>
                        <tbody id="add_employee">
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col text-center">
                            <button type="button" class="btn btn-success rounded" onclick="storeEmployee()" id="overtime_employee_submit"><span class="fa fa-hdd">&nbsp;</span>Simpan</button>
                        </div>
                    </div>
                    <form id="form_modal" name='form_modal' method='post' action="{{ route('flns.update_form_lembur_non_sewing') }}" onsubmit="submitForm(this, event)">
                        <div class="modal-body" id="detail">
                            <div class="form-group">
                                <label>No. Form :</label>
                                <label id = "no_form_modal" name = "no_form_modal"></label>
                                <input type="hidden" id = "no_form_modal_input" name = "no_form_modal_input" />
                                <input type="hidden" id = "dept_modal_input" name = "dept_modal_input" />
                                <input type="hidden" id = "txtket_modal_input" name = "txtket_modal_input" />
                            </div>
                            <div class="row">
                                <div class="col-md-12 table-responsive">
                                    <table id="datatable-modal" class="table table-striped table-bordered table-sm w-100">
                                        <thead>
                                            <tr class="text-center">
                                                <th rowspan="2" class="align-middle" >No</th>
                                                <th rowspan="2" class="align-middle">Nama</th>
                                                <th rowspan="2" class="align-middle">NIK</th>
                                                <th rowspan="2" class="align-middle">Jabatan</th>
                                                <th rowspan="2" class="align-middle">Sub Dept</th>
                                                <th rowspan="2" class="align-middle">Status</th>
                                                <th colspan="2" class="align-middle" style="text-align:center">Absen</th>
                                                <th colspan="5" class="align-middle">Rencana Lembur</th>
                                                <th rowspan="2" class="align-middle" style="padding-left: 2px;padding-right: 2px;color:rgb(255, 111, 0)"><i class="fa fa-utensils" aria-hidden="true"></i></th>
                                                <th rowspan="2" class="align-middle" style="padding-left: 2px;padding-right: 2px;color:rgb(255, 196, 0)"><i class="fa fa-star" aria-hidden="true"></i></th>
                                                <th rowspan="2" class="align-middle">Amount</th>
                                                <th rowspan="2" class="align-middle">Act</th>
                                            </tr>
                                            <tr class="text-center">
                                                <th>In</th>
                                                <th>Out</th>
                                                <th>Start</th>
                                                <th>Finish</th>
                                                <th>Break</th>
                                                <th>Total</th>
                                                <th>Note</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="p-2 bd-highlight">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-outline-success">Simpan </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-sb">
        <div class="card-header">
            <h5 class="card-title fw-bold mb-0"><i class="fas fa-file-archive"></i> List Form Lembur Non Sewing</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3 flex justify-content-between align-items-center">
                <div class="d-flex gap-4 align-items-end">
                    <div class="">
                        <input type="hidden" id="tgl-awal" name="tgl-awal">
                        <input type="hidden" id="tgl-akhir" name="tgl-akhir">
                        <input type="hidden" id="daterange1" name="daterange1" oninput="dataTableReload()" onchange="dataTableReload()" value="{{ date('Y-m-d') }}">
                        <label class="form-label"><small><b>Tanggal Form</b></small></label>
                        <a class="nav-link card-title py-3 pl-3" style="border: 1px solid #d8d4dc" id="daterange-btn1" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Klik di sini untuk pilih tanggal kehadiran"></a>
                    </div>
                </div>
                <div class="col-3">
                    <a href="{{ route('flns.create') }}" class="btn btn-primary position-relative w-100">
                        <i class="fa fa-plus"></i>
                        Baru
                    </a>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-2">
                    <button class="btn btn-app w-100" onclick="export_excel_all()" style="background-color: #16a34a" data-toggle="tooltip" title="Export Data ke File Transfer Excel" id="recap_labor_cost_2"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel SPL</button>
                </div>
                <div class="col-2">
                    <button class="btn btn-app w-100" onclick="export_excel_insentif()" style="background-color: #16a34a" data-toggle="tooltip" title="Export Data ke File Transfer Excel" id="recap_labor_cost_2"><i class="fa fa-file-excel-o" aria-hidden="true"></i>  Export Excel Insentif</button>
                </div>
                @if (Auth::guard('admin')->user()->name == 'HR' || Auth::guard('admin')->user()->name =='IT' || Auth::guard('admin')->user()->email =='mega@ptnag.com' || Auth::guard('admin')->user()->email =='rudy@patnag.com' || Auth::guard('admin')->user()->email =='fadli')
                <div class="col-2">
                    <button class="btn btn-app w-100" onclick="export_excel_konsumsi()" style="background-color: #16a34a" data-toggle="tooltip" title="Export Data ke File Transfer Excel" id="recap_labor_cost_2"><i class="fa fa-file-excel-o" aria-hidden="true"></i>  Anggaran Makanan</button>
                </div>
                @endif
            </div>
            <div class="table-responsive">
                <table id="datatable" class="table table-bordered table-hover text-nowrap">
                    <thead class="bg-primary">
                        <tr style='text-align:center; vertical-align:middle'>
                            <th>Act</th>
                            <th>No. Form</th>
                            <th>Tgl. Lembur</th>
                            <th>Tgl. Pengajuan</th>
                            <th>Dept</th>
                            <th>Keterangan</th>
                            <th>Karyawan Dept</th>
                            <th>Karyawan Pinjaman</th>
                            <th>Total Karyawan</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @endsection
    @section('footerjs')
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/iziToast.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <style>
        #datatable {
        table-layout: fixed;
        }

        #datatable thead th {
        background-color: #15435A;
        color: white;
    }
    </style>

    <script>
        $("#from_lembur").timepicker({
          timeFormat: "%H:%i"
        });
        var m = 0;
        function myFunction() {
            $('#tambah_karyawan').attr('disabled','true');
            m++;
            var id_c = document.getElementById("no_form_modal_input");
            var x = document.getElementById("add_employee");
            $('#add_employee').append('<tr id="row_overtime_employee_'+m+'">\
                    <td width="60" style="padding-top:2px;padding-bottom:2px;vertical-align:middle">\
                        <input name="karyawan_lembur[]" type="text" id="karyawan_'+m+'" onkeydown="fillTheField('+m+')" class="form-control py-0 px-1">\
                    </td>\
                    <td width="160" style="padding-top:2px;padding-bottom:2px;vertical-align:middle">\
                        <div id="employee_name_choosen_'+m+'"></div>\
                    </td>\
                    <td width="100" style="padding-top:2px;padding-bottom:2px;vertical-align:middle">\
                        <div id="nik_name_choosen_'+m+'"></div>\
                    </td>\
                    <td width="80" style="padding-top:2px;padding-bottom:2px;vertical-align:middle">\
                        <div id="status_jabatan_choosen_'+m+'"></div>\
                    </td>\
                    <td width="180" style="padding-top:2px;padding-bottom:2px;vertical-align:middle">\
                        <div id="sub_department_name_choosen_'+m+'"></div>\
                    </td>\
                    <td width="100" style="padding-top:2px;padding-bottom:2px;vertical-align:middle">\
                        <div id="actual_absen_in_'+m+'"></div>\
                    </td>\
                    <td width="100" style="padding-top:2px;padding-bottom:2px;vertical-align:middle">\
                        <div id="actual_absen_out_'+m+'"></div>\
                    </td>\
                    <td width="80" style="padding-top:2px;padding-bottom:2px;vertical-align:middle">\
                        <div id="lembur_dari_'+m+'"></div>\
                    </td>\
                    <td width="80" style="padding-top:2px;padding-bottom:2px;vertical-align:middle">\
                        <div id="lembur_sampai_'+m+'"></div>\
                    </td>\
                    <td width="80" style="padding-top:2px;padding-bottom:2px;vertical-align:middle">\
                        <div id="istirahat_'+m+'"></div>\
                    </td>\
                    <td width="100" style="padding-top:2px;padding-bottom:2px;vertical-align:middle">\
                        <div id="jumlah_jam_'+m+'"></div>\
                    </td>\
                    <td width="50" style="padding-top:2px;padding-bottom:2px;vertical-align:middle">\
                        <div id="trash_'+m+'"></div>\
                    </td>\
                </tr>\
            </div>');
            document.getElementById("karyawan_"+m).autofocus;
            var y = document.getElementById("overtime_employee_header");
            var f = document.getElementById("overtime_employee_submit");
            if(m>0){
                y.style.display = "";
                f.style.display = "";
            }else{
                y.style.display = "none";
                f.style.display = "none";
            }
        }
        function storeEmployee() {
            var karyawan_lembur = $("input[name='karyawan_lembur[]']").map(function(){return $(this).val();}).get();
            console.log(karyawan_lembur);
            var jam_awal_lembur = $("input[name='jam_awal_lembur_rencana[]']").map(function(){return $(this).val();}).get();
            var jam_akhir_lembur = $("input[name='jam_akhir_lembur_rencana[]']").map(function(){return $(this).val();}).get();
            var jam_istirahat_lembur = $("input[name='jam_istirahat_lembur_rencana[]']").map(function(){return $(this).val();}).get();
            var keterangan = $("input[name='keterangan[]']").map(function(){return $(this).val();}).get();
            var id_c = $('#no_form_modal_input').val();
            $.ajax({
                type:"POST",
                url: '{{ route('flns.store_tambahan_data_karyawan_lembur') }}',
                data: {
                    id_c:id_c,
                    karyawan_lembur:karyawan_lembur,
                    jam_awal_lembur:jam_awal_lembur,
                    jam_akhir_lembur:jam_akhir_lembur,
                    jam_istirahat_lembur:jam_istirahat_lembur,
                    keterangan:keterangan
                },
                success: function(res){
                    $('#tambah_karyawan').removeAttr('disabled');
                    $('#overtime_employee_header').removeAttr('disabled');{
                    $('#overtime_employee_submit').removeAttr('disabled');}
                    iziToast.success({
                        message: 'Data Berhasil Ditambahkan',
                        position: 'topCenter'
                    });
                    $('#datatable-modal').DataTable().ajax.reload();
                    dataTableReload();
                    $('#add_employee').empty();
                    var z = document.getElementById("overtime_employee_header");
                    z.style.display = "none";
                    var w = document.getElementById("overtime_employee_submit");
                    w.style.display = "none";
                    del_tmp();
                }
            });
        }
        function fillTheField(e){
            if(event.keyCode == 13) {
                var no_form=$("#no_form_modal_input").val();
                var dept=$("#dept_modal_input").val();
                var enroll_id=($('#karyawan_'+e).val());
                $.ajax({
                    type:"POST",
                    url: '{{ route('flns.view_tambahan_data_karyawan_lembur') }}',
                    data: {
                        no_form:no_form,
                        enroll_id:enroll_id,
                        dept:dept
                    },
                    success: function(res){
                        if(res=='data sudah ada'){
                            Swal.fire({
                                icon: 'warning',
                                title: 'Data Sudah Ada',
                                showConfirmButton: false,
                                closeOnConfirm: false,
                                timer: 500
                            });
                            $('#employee_name_choosen_'+e).empty();
                            $('#nik_name_choosen_'+e).empty();
                            $('#status_jabatan_choosen_'+e).empty();
                            $('#sub_department_name_choosen_'+e).empty();
                            $('#actual_absen_in_'+e).empty();
                            $('#actual_absen_out_'+e).empty();
                            $('#lembur_dari_'+e).empty();
                            $('#lembur_sampai_'+e).empty();
                            $('#istirahat_'+e).empty();
                            $('#jumlah_jam_'+e).empty();
                            $('#trash_'+e).empty();
                            $('#tambah_karyawan').attr('disabled','true');
                        }else if(res=='data tidak ada'){
                            Swal.fire({
                                icon: 'warning',
                                title: 'ID Tidak Terdaftar',
                                showConfirmButton: false,
                                closeOnConfirm: false,
                                timer: 500
                            });
                            $('#employee_name_choosen_'+e).empty();
                            $('#nik_name_choosen_'+e).empty();
                            $('#status_jabatan_choosen_'+e).empty();
                            $('#sub_department_name_choosen_'+e).empty();
                            $('#actual_absen_in_'+e).empty();
                            $('#actual_absen_out_'+e).empty();
                            $('#lembur_dari_'+e).empty();
                            $('#lembur_sampai_'+e).empty();
                            $('#istirahat_'+e).empty();
                            $('#jumlah_jam_'+e).empty();
                            $('#trash_'+e).empty();
                            $('#tambah_karyawan').attr('disabled','true');
                        }else{
                            $('#employee_name_choosen_'+e).html(res[0].employee_name);
                            $('#nik_name_choosen_'+e).html(res[0].nik);
                            $('#status_jabatan_choosen_'+e).html(res[0].status_jabatan);
                            $('#sub_department_name_choosen_'+e).html(res[0].sub_dept_name);
                            $('#actual_absen_in_'+e).html(res[0].absen_in);
                            $('#actual_absen_out_'+e).html(res[0].absen_out);
                            $('#lembur_dari_'+e).empty().append('<input type="time" name="jam_awal_lembur_rencana[]" class="form-control form-control-sm" id="jam_awal_rencana_'+e+'" onChange="showTotal('+e+')" value="'+jam_awal+'">');
                            $('#lembur_sampai_'+e).empty().append('<input type="time" name="jam_akhir_lembur_rencana[]" class="form-control form-control-sm" id="jam_akhir_rencana_'+e+'" onChange="showTotal('+e+')">');
                            $('#istirahat_'+e).empty().append('<input type="number" name="jam_istirahat_lembur_rencana[]" class="form-control form-control-sm" id="istirahatlah_'+e+'" onChange="showTotal('+e+')">');
                            $('#jumlah_jam_'+e).html('total');
                            $('#trash_'+e).empty().append('<a href="#"><span class="fa fa-trash text-danger" onClick="hapus_tambahan_karyawan_lembur('+e+','+res[0].enroll_id+')"></span></a>');
                            $('#tambah_karyawan').removeAttr('disabled');
                            $('#overtime_employee_header').removeAttr('disabled');
                            $('#overtime_employee_submit').removeAttr('disabled');
                            $("#karyawan_"+e).attr('disabled',true);
                            $.ajax({
                                type: "post",
                                url: '{{ route('flns.get_time_from_id_data_lembur') }}',
                                data: {
                                    id: no_form,
                                },
                                success: function(res) {
                                    var time1=(res[0].jam_lembur_awal_rencana).substr(0,5);
                                    var time2=(res[0].jam_lembur_akhir_rencana).substr(0,5);
                                    var istirahat=res[0].jam_lembur_istirahat;
                                    $('#jam_awal_rencana_'+e).val((res[0].jam_lembur_awal_rencana).substr(0,5));
                                    $('#jam_akhir_rencana_'+e).val((res[0].jam_lembur_akhir_rencana).substr(0,5));
                                    $('#istirahatlah_'+e).val(res[0].jam_lembur_istirahat);
                                    var result = minsToStr(strToMins(time2) - strToMins(time1) - istirahat);

                                    document.getElementById('jumlah_jam_' + e).innerText = result;
                                }
                            });
                            myFunction();
                            document.getElementById("karyawan_"+(e+1)).focus();
                        }
                        var jam_awal='';
                        var jam_akhir='';
                    }
                });
            }
        }
        function showTotal(e){
            let time1 = $("#jam_awal_rencana_" + e).val();
            let time2 = $("#jam_akhir_rencana_" + e).val();
            let istirahat = $("#istirahatlah_" + e).val();

            var result = minsToStr(strToMins(time2) - strToMins(time1) - istirahat);

            document.getElementById('jumlah_jam_' + e).innerText = result;
        }
        function hapus_tambahan_karyawan_lembur(e,enroll_ids){
            var enroll_id = $("input[name='karyawan_lembur[]']").map(function(){return $(this).val();}).get();
            enroll_id.splice(e-1, 1);
            var jawa_rencana = $("input[name='jam_awal_rencana_[]']").map(function(){return $(this).val();}).get();
            jawa_rencana.splice(e-1, 1);
            var jakir_rencana = $("input[name='jam_akhir_rencana_[]']").map(function(){return $(this).val();}).get();
            jakir_rencana.splice(e-1, 1);
            var jakir_rencana = $("input[name='jam_akhir_rencana_[]']").map(function(){return $(this).val();}).get();
            jakir_rencana.splice(e-1, 1);
            var istirahat = $("input[name='istirahatlah_[]']").map(function(){return $(this).val();}).get();
            istirahat.splice(e-1, 1);
            var row = document.getElementById('row_overtime_employee_'+e);
            row.parentNode.removeChild(row);
            del_tmp_enroll_id(enroll_ids);
        }
    </script>
    <script>
        $(document).ready(function() {
            dataTableReload();
            var z = document.getElementById("overtime_employee_header");
            z.style.display = "none";
            var w = document.getElementById("overtime_employee_submit");
            w.style.display = "none";
            del_tmp();

            let datatableFilter = document.getElementById("datatable_filter");

            datatableFilter.innerHTML = `<span> Search : </span><input type="text" class="form-control form-control-sm" id="employee_name" onkeyup="dataTableReload()">`;

            var start = moment();
            var end = moment();

            var htmlDateRange = '<span><i class="fa fa-calendar"></i> ' + start.format("D MMM YYYY").toUpperCase() + ' s/d ' + end.format("D MMM YYYY").toUpperCase() + '</span><i class="fa fa-angle-down ml-1"></i>';
            $('#daterange-btn1').html(htmlDateRange);
            var daterange1 = start.format("YYYY-MM-DD") + " s/d " + end.format("YYYY-MM-DD");
            $('#daterange1').val(daterange1);

            $('#tgl_awal').val(start.format("YYYY-MM-DD"));
            $('#tgl_akhir').val(end.format("YYYY-MM-DD"));

            $('#daterange-btn1').daterangepicker({
                ranges: {
                    'Hari ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 Hari Kemarin': [moment().subtract(6, 'days'), moment()],
                    '30 Hari Kemarin': [moment().subtract(29, 'days'), moment()],
                    'Bulan Sekarang': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan Kemarin': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment(), // Ubah ke hari ini
                endDate: moment()
            }, function (start, end) {
                $('#daterange-btn1').html('<span><i class="fa fa-calendar"></i> ' + start.format("D MMM YYYY").toUpperCase() + ' s/d ' + end.format("D MMM YYYY").toUpperCase() + '</span><i class="fa fa-angle-down ml-1"></i>');
                var daterange1 = start.format("YYYY-MM-DD") + " s/d " + end.format("YYYY-MM-DD");
                $('#daterange1').val(daterange1);
                $('#tgl_awal').val(start.format("YYYY-MM-DD"));
                $('#tgl_akhir').val(end.format("YYYY-MM-DD"));
                dataTableReload();
            });
        });
        function del_tmp_enroll_id(enroll_ids){
            var enroll_id=enroll_ids;
            let user = $('#user').val();
            $.ajax({
                type: "post",
                url: '{{ route('flns.del_tmp_non_sewing_enroll_id') }}',
                data: {
                    user: user,
                    enroll_id:enroll_id
                },success: function(response) {
                    console.log(response);
                }
            });

        }
        function del_tmp() {
            let user = $('#user').val();
            $.ajax({
                type: "post",
                url: '{{ route('flns.del_tmp_non_sewing') }}',
                data: {
                    user: user
                },
            });

        }
        $('#add_karyawan_lembur').click(function(){
            alert('add karyawan lembur');
        });
        $('#datatable thead tr').clone(true).appendTo('#datatable thead');
        $('#datatable thead tr:eq(1) th').each(function(i) {
            if (i != 0) {
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




        var daterange = $("#daterange1").val();
        var dates = daterange.split(" s/d ");
        var from = dates[0];
        var to = dates[1];
        let datatable = $("#datatable").DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            paging: true,
            searching: true,
            destroy: true,
            scrollX: true,
            ajax: {
                url: '{{ route('flns.index') }}',
                data: function(d) {
                    let daterange = $("#daterange1").val();
                    let dates = daterange.split(" s/d ");
                    let from = dates[0];
                    let to = dates[1] ? dates[1] : from;

                    d.dateFrom = from;
                    d.dateTo = to;
                    d.employee_name = $('#employee_name').val();
                },
            },
            columns: [
                {
                    data: 'id'
                }, {
                    data: 'no_form',
                },
                {
                    data: 'tgl_lembur_fix'
                },
                {
                    data: 'tgl_filter_fix'
                },
                {
                    data: 'dept'
                },
                {
                    data: 'ket'
                },
                {
                    data: 'jml_org_ttp'
                },
                {
                    data: 'jml_org_pinjam'
                },
                {
                    data: 'jml_org'
                },
            ],
            columnDefs: [{
                    "className": "dt-center",
                    "targets": "_all"
                },
                {
                    targets: [0],
                    render: (data, type, row, meta) => {
                        if(row.jml_insentif>0){
                            return `
                                <div class='d-flex gap-1 justify-content-center align-items-center'>
                                    <a class='btn btn-primary btn-sm  mr-2' style="color:white;" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                    onclick="getdetail('` + row.no_form + `','` + row.dept + `','` + row.ket + `');">
                                    <i class='fa fa-search'></i>
                                    </a>
                                    <a class='btn btn-secondary btn-sm' onclick="export_spl(` + row.id + `,'` + row.no_form + `')">
                                        <i class='fa fa-print'></i>
                                    </a>
                                    <a class='btn btn-danger btn-sm' onclick="export_pdf_insentif('` + row.no_form + `')" title="reward">
                                        <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                    </a>
                                </div>
                            `
                        }else{
                            return `
                                <div class='d-flex gap-1 justify-content-center align-items-center'>
                                    <a class='btn btn-primary btn-sm mr-2' style="color:white;" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                    onclick="getdetail('` + row.no_form + `','` + row.dept + `','` + row.ket + `');">
                                    <i class='fa fa-search'></i>
                                    </a>
                                    <a class='btn btn-secondary btn-sm' onclick="export_spl(` + row.id + `,'` + row.no_form + `')">
                                        <i class='fa fa-print'></i>
                                    </a>
                                </div>
                            `
                        }
                    }
                }
            ]
        });
        function dataTableReload() {
            datatable.ajax.reload();
        }
        function dataTableModalReload() {
            datatable_modal.ajax.reload();
        }

        var currentPageCheck = 0;
        var checkedEmployeeArr = [];
        var inputedAmountArr = [];
        function getdetail(id_c,id_d, id_k) {
            $("#exampleModalLabel").html(id_c);
            $("#txtket_modal").html(id_k);
            $("#no_form_modal").html(id_c);
            $("#no_form_modal_input").val(id_c);
            $("#dept_modal_input").val(id_d);
            $("#txtket_modal_input").val(id_k);
            datatable_modal = $("#datatable-modal").DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                info: false,
                paging: false,
                destroy: true,
                scrollCollapse: true,
                scrollY: '250px',
                ajax: {
                    url: '{{ route('flns.getdatakaryawanspl_non_sewing') }}',
                    method: 'GET',
                    data: function(d) {
                        d.no_form = id_c
                    },
                },
                "fnCreatedRow": function(row, data, index) {
                    $('td', row).eq(0).html(index + 1);
                },
                columns: [{
                        data: 'no_form'
                    },
                    {
                        data: 'employee_name'
                    },
                    {
                        data: 'nik'
                    },
                    {
                        data: 'status_jabatan'
                    },
                    {
                        data: 'sub_dept_name'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'absen_masuk_kerja'
                    },
                    {
                        data: 'absen_pulang_kerja'
                    },
                    {
                        data: 'jam_lembur_awal_rencana'
                    },
                    {
                        data: 'jam_lembur_akhir_rencana'
                    },
                    {
                        data: 'istirahat'
                    },
                    {
                        data: 'total_jam'
                    },
                    {
                        data: 'keterangan'
                    },
                    {
                        data: 'enroll_id'
                    },
                    {
                        data: 'enroll_id'
                    }
                ],
                columnDefs: [
                    {
                        width:170,
                        targets: [1,4],
                    },
                    {
                        width : 9,
                        targets: [8],
                        render: (data, type, row, meta) => {
                            return `
                            <div class='d-flex gap-1 justify-content-center'>
							<input type ='time' class='form-control form-control-sm' id="jam_lembur_awal_rencana_` + row
                                .enroll_id + `"
                            name="jam_lembur_awal_rencana[` + row.enroll_id + `]"
                            value="` +
                                row.jam_lembur_awal_rencana + `" onkeyup="calculateTotalLembur('` + row
                                .enroll_id + `')" onchange="calculateTotalLembur('` + row.enroll_id + `')">
                                </div>
                            <input type="hidden" size="4" id="enroll_id[` + row.enroll_id + `]"
                            name="enroll_id[` + row.enroll_id + `]" value = "` + row.enroll_id + `"/>
                            <input type="hidden" size="4" id="no_form[` + row.enroll_id + `]"
                            name="no_form[` + row.enroll_id + `]" value = "` + row.no_form + `"/>
                            <input type="hidden" name="uuid_koreksi_upah[` + row.enroll_id + `]" value = "` + row.id_det + `"/>
                            `
                        }
                    },
                    {
                        targets: [9],
                        render: (data, type, row, meta) => {
                            return `
                            <div class='d-flex gap-1 justify-content-center'>
                            <input type="time" size='1' class="form-control form-control-sm" id="jam_lembur_akhir_rencana_` +
                                row.enroll_id + `"
                            name="jam_lembur_akhir_rencana[` + row.enroll_id + `]" value="` +
                                row.jam_lembur_akhir_rencana + `"
                                autocomplete="off" onkeyup="calculateTotalLembur('` + row.enroll_id +
                                `')" onchange="calculateTotalLembur('` + row.enroll_id + `')">
                                </div>
                    `
                        }
                    },
                    {
                        targets: [10],
                        render: (data, type, row, meta) => {
                            return `
                            <div class='d-flex gap-1 justify-content-center'>
                            <input type="number" size='1' class="form-control form-control-sm" id="jam_lembur_istirahat_` +
                                row.enroll_id + `"
                            name="istirahat[` + row.enroll_id + `]"
                            value="` +
                                row.jam_lembur_istirahat + `"
                                autocomplete="off" onkeyup="calculateTotalLembur('` + row.enroll_id +
                                `')" onchange="calculateTotalLembur('` + row.enroll_id + `')">
                                </div>
                    `
                        }
                    },
                    {
                        targets: [11],
                        render: (data, type, row, meta) => {
                            if(data=='24:00'){
                                return '<span id="total-jam-lembur-' + row.enroll_id + '">00:00</span>';
                            }else{
                                return '<span id="total-jam-lembur-' + row.enroll_id + '">' + data + '</span>';
                            }
                        }
                    },
                    {
                        width:170,
                        targets: [12],
                        render: (data, type, row, meta) => {
                            return '<textarea id="keterangan-' + row.enroll_id + '" name="keterangan[' + row.enroll_id + ']" class="form-control form-control-sm" rows="2" cols="12">'+row.keterangan+'</textarea>';
                        }
                    },
                    {
                        targets: [13],
                        render: (data, type, row, meta) => {
                            if(row.konsumsi==0){
                                return `
                                    <div class='d-flex gap-1 justify-content-center'>
                                        <div class="form-check">
                                            <input class="form-check-input" name="konsumsi[` + row.enroll_id + `]" type="checkbox" value="" style='width: 20px; height: 20px;' id="checked_konsumsi_` + row.enroll_id + `" onchange="checked_konsumsi('` + row.enroll_id + `')" >
                                            <input name="konsumsi_value[` + row.enroll_id + `]" type="hidden" id="konsumsi_value_`+row.enroll_id+`" value=0>
                                        </div>
                                    </div>
                                `
                            }else if (row.konsumsi==1){
                                return `
                                    <div class='d-flex gap-1 justify-content-center'>
                                        <div class="form-check">
                                            <input class="form-check-input" name="konsumsi[` + row.enroll_id + `]" type="checkbox" value="" style='width: 20px; height: 20px;' id="checked_konsumsi_` + row.enroll_id + `" onchange="checked_konsumsi('` + row.enroll_id + `')" checked>
                                            <input name="konsumsi_value[` + row.enroll_id + `]" type="hidden" id="konsumsi_value_`+row.enroll_id+`" value=1>
                                        </div>
                                    </div>
                                `
                            }else{
                                return `
                                    <div class='d-flex gap-1 justify-content-center'>
                                        <div class="form-check">
                                            <input class="form-check-input" name="konsumsi[` + row.enroll_id + `]" type="checkbox" value="" style='width: 20px; height: 20px;' id="checked_konsumsi_` + row.enroll_id + `" onchange="checked_konsumsi('` + row.enroll_id + `')" >
                                            <input name="konsumsi_value[` + row.enroll_id + `]" type="hidden" id="konsumsi_value_`+row.enroll_id+`" value=0>
                                        </div>
                                    </div>
                                `
                            }

                        }
                    },
                    {
                        targets: [14],
                        render: (data, type, row, meta) => {
                            if(row.uuid_koreksi_upah!=''){
                                return `
                                    <div class='d-flex gap-1 justify-content-center'>
                                        <div class="form-check">
                                            <input class="form-check-input" name="award[` + row.enroll_id + `]" type="checkbox" value="" style='width: 20px; height: 20px;' id="checked_enroll_id_` + row.enroll_id + `" onchange="checkbox_enroll_id('` + row.enroll_id + `')" checked >
                                        </div>
                                    </div>
                                `
                            }else{
                                return `
                                    <div class='d-flex gap-1 justify-content-center'>
                                        <div class="form-check">
                                            <input class="form-check-input" name="award[` + row.enroll_id + `]" type="checkbox" value="" style='width: 20px; height: 20px;' id="checked_enroll_id_` + row.enroll_id + `" onchange="checkbox_enroll_id('` + row.enroll_id + `')" >
                                        </div>
                                    </div>
                                `
                            }

                        }
                    },
                    {
                        targets: [15],
                        render: (data, type, row, meta) => {
                            if(row.uuid_koreksi_upah!=''){
                                return `
                                    <div class='d-flex gap-1 justify-content-center'>
                                        <div class="form-check pl-0">
                                            <input class="form-control form-control-sm amount_input" type="number" style='font-size:9pt' name="amounts[` + row.enroll_id + `]" id="amount_` + row.enroll_id + `" value="` + row.uuid_koreksi_upah + `" onchange="input_amount(this,'` + row.enroll_id + `')" >\
                                        </div>
                                    </div>
                                `
                            }else{
                                return `
                                    <div class='d-flex gap-1 justify-content-center'>
                                        <div class="form-check pl-0">
                                            <input class="form-control form-control-sm amount_input" type="number" style='font-size:9pt' name="amounts[` + row.enroll_id + `]" id="amount_` + row.enroll_id + `" value="` + row.uuid_koreksi_upah + `" onkeyup="input_amount(this,'` + row.enroll_id + `')" onchange="input_amount(this,'` + row.enroll_id + `')" disabled>
                                        </div>
                                    </div>
                                `
                            }
                        }
                    },
                    {
                        targets: [16],
                        render: (data, type, row, meta) => {
                            return `
                        <div class='d-flex gap-1 justify-content-center'>
                            <a class='btn btn-danger btn-sm' onclick="del_karyawan(
                                '` + row.enroll_id + `',
                                '` + row.no_form + `');">
                                <i class='fa fa-trash'></i>
                            </a>
                        </div>
                    `
                        }
                    },
                ],
                rowCallback: function(row, data, dataIndex){
                    let currentEnrollId = data['enroll_id'];
                    checkedEmployeeArr.forEach((item, index, array) => {
                        if(array[index]["enroll_id"]===currentEnrollId){
                            currentPageCheck++;
                            $(row).find('input[type="checkbox"]').prop('checked', true);
                            $(row).find('input[id="amount_'+array[index]["enroll_id"]+'"]').prop('disabled', false);
                            $(row).find('input[id="amount_'+array[index]["enroll_id"]+'"]').val(array[index]["amount"]);
                        }
                    });
                },
            });
        };
        $('#exampleModal').on('shown.bs.modal', function () {
            dataTableModalReload();
        });
        $('#exampleModal').on('hidden.bs.modal', function () {
            checkedEmployeeArr=[]
        });
        function strToMins(t) {
            var s = t.split(":");
            return Number(s[0]) * 60 + Number(s[1]);
        }

        function minsToStr(t) {
            return Math.trunc(t / 60).toLocaleString('id-ID', {
                minimumIntegerDigits: 2,
                useGrouping: false
            }) + ':' + ('00' + t % 60).slice(-2);
        }

        function calculateTotalLembur(enroll_id) {
            let time1 = $("#jam_lembur_awal_rencana_" + enroll_id).val();
            let time2 = $("#jam_lembur_akhir_rencana_" + enroll_id).val();
            let istirahat = $("#jam_lembur_istirahat_" + enroll_id).val();

            var result = minsToStr(strToMins(time2) - strToMins(time1) - istirahat);
            let jam12='24:00';
            let jam1='00:00';

            var result2 = minsToStr(((strToMins(jam12)-strToMins(time1)) + (strToMins(time2) - strToMins(jam1))) - istirahat);

            if(time2<time1){
                document.getElementById('total-jam-lembur-' + enroll_id).innerText = result2;
            }else{
                document.getElementById('total-jam-lembur-' + enroll_id).innerText = result;
            }
        }
        function checkbox_enroll_id(enroll_id){
            var checked=document.getElementById('checked_enroll_id_'+enroll_id).checked;
            var no_form=$("#no_form_modal_input").val();
            var id=enroll_id;
            if(checked==true){
                $.ajax({
                    type: "post",
                    url: '{{ route('flns.cek_data_koreksi_upah') }}',
                    data: {
                        id: id,
                        no_form: no_form
                    },
                    success: function(res) {
                        if(res=='oke'){
                            $('#amount_'+enroll_id).removeAttr('disabled');
                            if(!checkedEmployeeArr.find((enroll_id) => enroll_id == id)) {
                                var valueToPush={};
                                valueToPush["enroll_id"]=id;
                                valueToPush["amount"]=$('#amount_'+id).val();
                                checkedEmployeeArr.push(valueToPush);
                            }
                        }else{
                            iziToast.error({
                                message: 'Reward sudah diberikan',
                                position: 'center'
                            });
                            document.getElementById('checked_enroll_id_'+id).checked=false;
                            checkedEmployeeArr.forEach((item, index, array) => {
                                if(array[index]["enroll_id"]===id){
                                    checkedEmployeeArr.splice(index,1);
                                }
                            });
                        }
                    }
                });
            }else{
                $('#amount_'+enroll_id).attr("disabled", 'disabled');
                checkedEmployeeArr.forEach((item, index, array) => {
                    if(array[index]["enroll_id"]===id){
                        checkedEmployeeArr.splice(index,1);
                    }
                });
            }
        }
        function input_amount(element,id){
            checkedEmployeeArr.forEach((item, index, array) => {
                if(array[index]["enroll_id"]===id){
                    array[index]["amount"]=element.value;
                }
            });
        }
        function checked_konsumsi(enroll_id){
            var checked=document.getElementById('checked_konsumsi_'+enroll_id).checked;
            if(checked==true){
                $('#konsumsi_value_'+enroll_id).val(1);
            }else{
                $('#konsumsi_value_'+enroll_id).val(0);
            }
        }
        function del_karyawan(id, no_form) {
            $.ajax({
                type: "post",
                url: '{{ route('flns.del_karyawan_non_sewing') }}',
                data: {
                    id: id,
                    no_form: no_form
                },
                success: async function(res) {
                    iziToast.success({
                        message: 'Data Berhasil Dihapus',
                        position: 'topCenter'
                    });
                    $('#datatable-modal').DataTable().ajax.reload();
                    dataTableReload();
                }
            });
        }
        function export_excel_insentif() {
            let from = document.getElementById("tgl-awal").value;
            let to = document.getElementById("tgl-akhir").value;
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
                url: '{{ route('flns.export_excel_insentif_non_sewing') }}',
                data: {
                    from: from,
                    to: to
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
                        link.download = from + " sampai " +
                            to + " Laporan Checking Insentif Non Sewing "+Math.ceil(Math.random()*1000000)+".xlsx";
                        link.click();

                    }
                },
            });
        }
        function export_excel_all() {
            let from = document.getElementById("tgl-awal").value;
            let to = document.getElementById("tgl-akhir").value;

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
                url: '{{ route('flns.export_excel_spl_all_non_sewing') }}',
                data: {
                    from: from,
                    to: to
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
                        link.download = from + " sampai " +
                            to + " Laporan Checking SPL Non Sewing "+Math.ceil(Math.random()*1000000)+".xlsx";
                        link.click();

                    }
                },
            });
        }

        function export_spl(id_n, no_form_n) {
            var id=id_n;
            var no_form=no_form_n;
            var url = 'flns/export_pdf_non_sewing_spl?no_form='+no_form+'&id='+id;
            window.open(url, '_blank');

        }
        function export_pdf_insentif(no_form){
            var url = 'flns/export_pdf_non_sewing_insentif?no_form='+no_form;
            window.open(url, '_blank');
        }
        function export_spl_import(id_e, no_form_e) {
            let id = id_e;
            let no_form = no_form_e;
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
                url: '{{ route('fls.export_spl_import') }}',
                data: {
                    id: id
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
                        link.download = no_form_e + ".xlsx";
                        link.click();

                    }
                },
            });
        }
    </script>
@endsection
