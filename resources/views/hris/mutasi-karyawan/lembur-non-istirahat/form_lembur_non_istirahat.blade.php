@extends('admin.adminlayouts.adminlayout-mut-karyawan')

@section('head')
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{ URL::asset('assets/css/iziToast.min.css') }}">
<link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    #datatable {
        table-layout: fixed;
    }
    table.dataTable {
        table-layout: auto !important;
        width: 100% !important;
    }
    table.dataTable th,
    table.dataTable td {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }
    #datatable thead th {
        background-color: #15435A;
        color: white;
    }
</style>
@stop

@section('mainarea')
<div class="container-fluid">
    <div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
        <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
            <li><a href="{{route('flni.index')}}">Form Lembur </a></li>
            <li class="active"><span>Form Lembur Non Istirahat</span></li>
        </ol>
        <div class="ml-auto">
            <div class="input-group">
                <a href="#" id="btn-refresh-data" class="btn btn-icon btn-secondary p-0 m-0" data-toggle="tooltip" title="Refresh Halaman">
                    <span><i class="fa fa-refresh"></i></span>
                </a>
            </div>
        </div>
    </div>

 <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-clock"></i> Form Lembur Non Istirahat</h5>
        </div>
        <div class="card-body">
            <div class="row mb-2 align-items-end">
                <div class="col-md-2">
                    <label><b>Tanggal Form</b></label>
                    <input type="text" id="daterange-btn1" class="form-control" readonly>
                    <input type="hidden" id="tgl-awal">
                    <input type="hidden" id="tgl-akhir">
                </div>

                <div class="col-md-4">
                    <label><b>Search SPL</b></label>
                    <select class="form-control select2" id="no_form" name="no_form">
                        <option value="">-- Pilih Nomor SPL --</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table id="datatable" class="table table-bordered table-hover table-sm w-100">
                    <thead>
                        <tr>
                            {{-- <th>Act</th> --}}
                            <th>No. Form</th>
                            <th>Enroll ID</th>
                            <th>Nama Karyawan</th>
                            <th>Tanggal</th>
                            <th>Jam Istirahat</th>
                            <th> AKSI
                                <input type="checkbox" id="check-all" checked>
                            </th>
                        </tr>
                    </thead>
                </table>
                <div class="mt-3">
                    <button id="btn-simpan-istirahat" class="btn btn-success">
                        Simpan
                    </button>
                    {{-- <button id="btn-print" class="btn btn-primary">
                        Print
                    </button> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-clock"></i> Data Lembur Non Istirahat</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatable2" class="table table-bordered table-hover table-sm w-100">
                    <thead>
                        <tr>
                            {{-- <th>Act</th> --}}
                            <th>No. Form</th>
                            <th>Enroll ID</th>
                            <th>Nama Karyawan</th>
                            <th>Tanggal</th>
                            <th>Jam Istirahat</th>

                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
                <div class="mt-3">
                    {{-- <button id="btn-simpan-istirahat" class="btn btn-success">
                        Simpan
                    </button> --}}
                    <button id="btn-print" class="btn btn-primary">
                        Print
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('footerjs')
    <script src="{{URL::asset('assets/js/script.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/iziToast.min.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>


<script>
// $(document).ready(function(){

//     var start = moment();
//     var end = moment();

//     function updateDateRange(start,end){
//         $('#daterange-btn1').val(start.format('DD-MM-YYYY') + ' s/d ' + end.format('DD-MM-YYYY'));
//         $('#tgl-awal').val(start.format('YYYY-MM-DD'));
//         $('#tgl-akhir').val(end.format('YYYY-MM-DD'));
//         if(datatable){
//             datatable.ajax.reload();
//         }
//     }

//     // ===== DataTable =====
//     var datatable = $('#datatable').DataTable({
//         processing: true,
//         serverSide: true,
//         ajax: {
//             url: '{{ route("flni.index") }}',
//             data: function(d){
//                 d.dateFrom = $('#tgl-awal').val();
//                 d.dateTo = $('#tgl-akhir').val();
//                 d.employee_name = $('#employee_name_filter').val();
//                 d.nomor_form_lembur = $('#nomor_form_lembur').val(); // 🔥 SPL
//             }
//         },
//         columns: [
//             { data: 'no_form', name: 'no_form' },
//             { data: 'enroll_id', name: 'enroll_id' },
//             { data: 'employee_name', name: 'employee_name' },
//             { data: 'tanggal_berjalan', name: 'tanggal_berjalan' },
//             { data: 'jumlah_jam_istirahat_lembur', name: 'jumlah_jam_istirahat_lembur' },
//             { data: 'jumlah_jam_lembur', name: 'jumlah_jam_lembur' },
//             {
//                 data: null,
//                 orderable: false,
//                 searchable: false,
//                 render: function(data, type, row){
//                     return `
//                         <input type="checkbox"
//                             class="cek-istirahat"
//                             checked
//                             data-enroll="${row.enroll_id}"
//                             data-form="${row.no_form}"
//                             data-istirahat="${row.jumlah_jam_istirahat_lembur}">
//                     `;
//                 }
//             }
//         ]
//     });


//     // ===== Tombol Delete =====
//     // $('#datatable tbody').on('click', '.btn-delete-jam', function(){
//     //     var row = datatable.row($(this).closest('tr'));
//     //     var data = row.data();
//     //     var jamBaru = 0; // jam istirahat direset ke 0

//     //     if(confirm('Yakin ingin memisahkan jam istirahat ini?')){
//     //         $.ajax({
//     //             url: '{{ route("flni.hapusIstirahat") }}',
//     //             method: 'POST',
//     //             data: {
//     //                 enroll_id: data.enroll_id,
//     //                 nomor_form_lembur: data.no_form,
//     //                 _token: '{{ csrf_token() }}'
//     //             },
//     //             success: function(res){
//     //                 if(res.status === 'success'){
//     //                     // Update DataTable
//     //                     data.jumlah_jam_istirahat_lembur = jamBaru;
//     //                     data.jumlah_jam_lembur = res.jumlah_jam_lembur; // dari response backend
//     //                     row.data(data).draw(false);
//     //                     alert('Jam istirahat berhasil dipisahkan');
//     //                 } else {
//     //                     alert(res.message);
//     //                 }
//     //             },
//     //             error: function(){
//     //                 alert('Terjadi kesalahan saat update data');
//     //             }
//     //         });
//     //     }
//     // });
//         $('#btn-simpan-istirahat').on('click', function(){

//         let dataDiproses = [];

//         $('.cek-istirahat:not(:checked)').each(function(){
//             dataDiproses.push({
//                 enroll_id: $(this).data('enroll'),
//                 nomor_form_lembur: $(this).data('form'),
//                 jam_istirahat: $(this).data('istirahat')
//             });
//         });

//         if(dataDiproses.length === 0){
//             alert('Tidak ada jam istirahat yang dihapus');
//             return;
//         }

//         if(!confirm('Yakin hapus jam istirahat yang dipilih?')) return;

//         $.ajax({
//             url: '{{ route("flni.hapusIstirahat") }}',
//             method: 'POST',
//             data: {
//                 data: dataDiproses,
//                 _token: '{{ csrf_token() }}'
//             },
//             success: function(){
//                 alert('Berhasil disimpan');
//                 $('#datatable').DataTable().ajax.reload();
//             },
//             error: function(){
//                 alert('Gagal menyimpan data');
//             }
//         });
//     });
//     $('#btn-print').on('click', function () {
//     let tglAwal  = $('#tgl-awal').val();
//     let tglAkhir = $('#tgl-akhir').val();

//     let url = "{{ route('flni.printNonIstirahat') }}"
//         + "?dateFrom=" + tglAwal
//         + "&dateTo=" + tglAkhir;

//     window.open(url, '_blank');
// });

//     // ===== Daterangepicker =====
//     $('#daterange-btn1').daterangepicker({
//         startDate: start,
//         endDate: end,
//         locale: { format: 'DD-MM-YYYY' },
//         ranges: {
//             'Hari ini': [moment(), moment()],
//             'Kemarin': [moment().subtract(1,'days'), moment().subtract(1,'days')],
//             '7 Hari Kemarin': [moment().subtract(6,'days'), moment()],
//             '30 Hari Kemarin': [moment().subtract(29,'days'), moment()],
//             'Bulan Sekarang': [moment().startOf('month'), moment().endOf('month')],
//             'Bulan Kemarin': [moment().subtract(1,'month').startOf('month'), moment().subtract(1,'month').endOf('month')]
//         }
//     }, updateDateRange);

//     updateDateRange(start,end);

//     // Filter karyawan
//     $('#employee_name_filter').on('keyup', function(){
//         datatable.ajax.reload();
//     });
//         $('#nomor_form_lembur').on('keyup', function(){
//         datatable.ajax.reload();
//     });

// });

let datatable;
let tableResult;

function reloadAllTable(){
    let tgl = $('#tgl-awal').val();
    let spl = $('#no_form').val();
    if(!tgl) return;

    datatable.ajax.reload(null, false);
    tableResult.ajax.reload(null, false);
}
    $('#btn-cari').on('click', function () {
        let tgl = $('#tgl-awal').val();
        let spl = $('#no_form').val();

        if (!tgl) {
            alert('Silakan pilih tanggal terlebih dahulu');
            return;
        }

        if (!spl) {
            alert('Silakan pilih Nomor SPL');
            return;
        }

        datatable.ajax.reload(null, false);
tableResult.ajax.reload(null, false); // 🔥 jalanin DataTable
    });

    function updateSingleDate(date){
        $('#daterange-btn1').val(date.format('DD-MM-YYYY'));
        $('#tgl-awal').val(date.format('YYYY-MM-DD'));

        loadSPL();          // 🔥 ini kuncinya
        reloadAllTable();
    }
    function loadSPL() {
        let tgl = $('#tgl-awal').val();

        $.ajax({
            url: '{{ route("flni.getNoForm") }}',
            data: { tanggal: tgl },
            success: function(res){
                let $spl = $('#no_form');
                $spl.empty();
                $spl.append(`<option value="">-- Pilih Nomor SPL --</option>`);

                res.forEach(item => {
                    $spl.append(`<option value="${item.no_form}">${item.no_form}</option>`);
                });

                $spl.trigger('change'); // refresh select2
            }
        });
    }

$(document).ready(function(){

    // ===== DATATABLE ATAS =====
    datatable = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        //  pageLength: 100,
        ajax: {
            url: '{{ route("flni.index") }}',
            data: function(d){
                d.tanggal = $('#tgl-awal').val();
                d.no_form = $('#no_form').val();
            }
        },
        columns: [
            { data: 'no_form' },
            { data: 'enroll_id' },
            { data: 'employee_name' },
            { data: 'tgl_lembur' },
            { data: 'jam_lembur_istirahat' },
            {
                data: null,
                orderable: false,
                render: function(row){
                    return `
                        <input type="checkbox"
                            class="cek-istirahat"
                            checked
                            data-enroll="${row.enroll_id}"
                            data-form="${row.no_form}"
                            data-istirahat="${row.jam_lembur_istirahat}"
                            data-tanggal="${row.tgl_lembur}"
                            data-jenis="${row.jenis}">
                    `;
                }
            }
        ]
    });
    $('#check-all').on('click', function () {
    let isChecked = $(this).prop('checked');

    $('.cek-istirahat').prop('checked', isChecked);
});


    // ===== DATATABLE BAWAH =====
    tableResult = $('#datatable2').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("flni.dataNonIstirahat") }}',
            data: function(d){
                d.tanggal = $('#tgl-awal').val();
                d.no_form = $('#no_form').val();
            }
        },
        columns: [
            { data: 'no_form' },
            { data: 'enroll_id' },
            { data: 'employee_name' },
            { data: 'tgl_lembur' },
            { data: 'jam_lembur_istirahat' },

            {
                data: null,
                orderable: false,
                render: function(row){
                    return `
                        <button type="button"
                            onclick="btnDelete('${row.enroll_id}', '${row.no_form}', '${row.jam_lembur_awal_rencana}','${row.jam_lembur_akhir_rencana}','${row.jenis}','${row.tgl_lembur}')"
                            class="btn btn-danger btn-sm">
                            <i class='fa fa-trash'></i>
                        </button>`
                }
            }
        ]
    });

    // ===== DATE PICKER =====
    $('#daterange-btn1').daterangepicker({
        singleDatePicker: true,
        autoUpdateInput: false,
        showDropdowns: true,
        locale: {
            format: 'DD-MM-YYYY'
        }
    }, function(start){
        updateSingleDate(start);
        reloadAllTable();
    });

    // default hari ini
    updateSingleDate(moment());
    reloadAllTable();

    // ===== FILTER SPL =====
   $('#no_form').on('change', function () {
    let tgl = $('#tgl-awal').val();
    let spl = $(this).val();

    if (!tgl || !spl) return;

    datatable.ajax.reload(null, false);
    tableResult.ajax.reload(null, false);
});


    // ===== SIMPAN ISTIRAHAT =====
    $('#btn-simpan-istirahat').on('click', function(){

        let dataDiproses = [];
    $('.cek-istirahat:not(:checked)').each(function(){
        dataDiproses.push({
            enroll_id: $(this).data('enroll'),
            no_form: $(this).data('form'),
            jam_istirahat: $(this).data('istirahat'),
            tgl_lembur: $(this).data('tanggal'),
            jenis: $(this).data('jenis')
        });
    });

        if(dataDiproses.length === 0){
            alert('Tidak ada jam istirahat yang dihapus');
            return;
        }

        if(!confirm('Yakin hapus jam istirahat yang dipilih?')) return;

        $.ajax({
            url: '{{ route("flni.hapusIstirahat") }}',
            method: 'POST',
            data: {
                data: dataDiproses,
                _token: '{{ csrf_token() }}'
            },
            success: function(res){

                if (res.status === 'success') {
                    alert('Berhasil disimpan');
                    reloadAllTable();

                } else if (res.status === 'warning') {
                    alert(res.message);

                } else {
                    alert('Respon tidak dikenal');
                }
            },
            error: function(){
                alert('Gagal menyimpan data');
            }
        });
    });

    // ===== PRINT =====
    $('#btn-print').on('click', function(){
        let tglAwal  = $('#tgl-awal').val();
        let nomorForm = $('#no_form').val();

        let url = "{{ route('flni.printNonIstirahat') }}"
            + "?tanggal=" + tglAwal
            + "&no_form=" + nomorForm;

        window.open(url, '_blank');
    });

});
 function btnDelete(enroll_id, no_form, jam_lembur_awal_rencana, jam_lembur_akhir_rencana,jenis,tgl_lembur) {
    if (!confirm('Yakin ingin membatalkan Non Istirahat?\nJam lembur: ' + jam_lembur_awal_rencana + ' - ' + jam_lembur_akhir_rencana)) return;

    $.ajax({
        type: "POST",
        url: "{{ route('flni.deleteNonIstirahat') }}", // PASTIKAN ROUTE INI BENAR
        data: {
            _token: "{{ csrf_token() }}",
            enroll_id: enroll_id,
            no_form: no_form,
            mulai_jam: jam_lembur_awal_rencana,
            akhir_jam: jam_lembur_akhir_rencana,
            jenis: jenis,
            tanggal: tgl_lembur
        },
        success: function(res) {
            alert(res.message);
            reloadAllTable();
        },
        error: function(xhr) {
            alert('Gagal menghapus data: ' + (xhr.responseJSON?.message || 'Unknown error'));
        }
    });
}
</script>



@endsection

