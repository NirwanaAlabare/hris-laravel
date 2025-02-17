@extends('admin.adminlayouts.adminlayout-mut-karyawan')

@section('head')
    <link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
@stop
<style>
 .timestamp {
            display: inline-block;
            background: #4CAF50;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }
.is-invalid {
    border: 2px solid red;
    background-color: #ffe6e6;
}

</style>
@section('mainarea')
<?php ini_set('date.timezone', 'Asia/Jakarta'); ?>
    <div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
        <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
            <li><a href="{{route('flns.index')}}">Bazzar</a></li>
            <li class="active"><span>Pengajuan kupon bazzar</span></li>
            <input type="hidden" value="{{$user}}" id="username_who_access">
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
        <div class="card-header bg-primary p-3 pe-auto"  data-toggle="card-collapse">
            <div class="card-title" >BUAT PENGAJUAN KUPON KARYAWAN</div>
            <div class="card-options ">
                <a href="#" class="card-options-collapse mr-2 btn btn-icon btn-white p-0 m-0" data-toggle="card-collapse">
                    <i class="fe fe-plus text-black"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">CARI DATA : </label>
                        <select id="selectEmployeeID" name="selectEmployeeID[]" data-placeholder="Pilih karyawan" class="form-control select2 EmployeeID" onchange="cek_filter_modal();">
                            <option value="">Pilih karyawan</option>
                            @foreach ($selectemployee as $r_empl)
                                <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex mb-4 align-items-end">
                        <div class="">
                            <label class="form-label"><small><b>Tanggal Pengajuan</b></small></label>
                            <div class="col pl-0"><input value="{{ date('Y-m-d') }}" type="date" class="form-control form-control-md " readonly style="background-color:white"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label><small><b>Nama</b></small></label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control " name="txt_name" id="txt_name" disabled>
                        <input type="hidden" class="form-control " name="txt_enroll_id" id="txt_enroll_id" disabled>
                    </div>
                </div>
                <div class="col-md-3">
                    <label><small><b>Department</b></small></label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control " name="txt_department" id="txt_department" disabled>
                    </div>
                </div>
                <div class="col-md-3">
                    <label><small><b>Bagian</b></small></label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control " name="txt_bagian" id="txt_bagian" disabled>
                    </div>
                </div>
                <div class="col-md-3">
                    <label><small><b>Status Staff</b></small></label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control " name="txt_staff" id="txt_staff" disabled>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label><small><b>NIK</b></small></label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control " name="txt_nik" id="txt_nik" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <label><small><b>Jumlah</b></small></label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control " name="txt_jumlah" id="txt_jumlah" placeholder="Rp.">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group mt-5">
                        <button class="btn btn-primary w-100" onclick="onSave()">Simpan</button>
                    </div>
                </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row p-3">
        <div class="col-2">
            <button class="btn btn-app w-100" onclick="export_all()" style="background-color: #eb0a0a" data-toggle="tooltip" title="Export Data ke File Transfer Excel" id="recap_labor_cost_2"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Export Pengajuan</button>
        </div>
    </div>

    <div class="row p-3">
        <div class="col-md-12">
            <div clasl="card-header">
                <ul class="nav nav-tabs mx-0 mb-3" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="true">Waiting</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="approve-tab" data-toggle="tab" href="#approve_tab" role="tab" aria-controls="approve" aria-selected="false">approve</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="reject-tab" data-toggle="tab" href="#reject_tab" role="tab" aria-controls="reject" aria-selected="false">reject</a>
                    </li>
                </ul>
            </div>
            <div class="card card-primary card-outline tab-content">
                    <div class="card-header bg-primary p-3">
                        <div class="card-title">PENGAJUAN KUPON KARYAWAN</div>
                    </div>
                    <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-start">
                                <button type="submit" class="btn mr-2 ml-1 mb-2 btn-secondary BtnVerifikasiOt" onclick="handleApprove()" >Approve</button>
                                <button type="submit"  class="btn mr-2 ml-1 mb-2 btn-danger BtnVerifikasiOt" onclick="handleReject()" >Reject</button>
                            </div>
                            <div class="table-responsive">
                                <table id="datatable_pending" class="table  table-bordered table-sm w-100 table-hover">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>No.</th>
                                            <th> <input type="checkbox" onclick="toggle(this);"></th>
                                            <th>Enroll id</th>
                                            <th>Nama</th>
                                            <th>Department</th>
                                            <th>Bagian</th>
                                            <th>Status</th>
                                            <th>Jumlah</th>
                                            <th>Status</th>
                                            <th>Dibuat</th>
                                            <th>Act</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="approve_tab" role="tabpanel" aria-labelledby="approve-tab">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable_approve" class="table table-bordered table-sm w-100 table-hover">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>No.</th>
                                            <th>Enroll id</th>
                                            <th>Nama</th>
                                            <th>Department</th>
                                            <th>Bagian</th>
                                            <th>Status</th>
                                            <th>Jumlah</th>
                                            <th>Status</th>
                                            <th>Dibuat</th>
                                            <th>Act</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="reject_tab" role="tabpanel" aria-labelledby="reject-tab">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable_reject" class="table table-bordered table-sm w-100 table-hover">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>No.</th>
                                            <th>Enroll id</th>
                                            <th>Nama</th>
                                            <th>Department</th>
                                            <th>Bagian</th>
                                            <th>Status</th>
                                            <th>Jumlah</th>
                                            <th>Status</th>
                                            <th>Dibuat</th>
                                            <th>Act</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ajax-modal-edit1" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="row">
                <div class="col-md-12">

                    <div class="modal-content">
                        <div class="modal-header bg-primary p-2">
                            <h4 class="modal-title pl-2 font-weight-bold" id="title-modal-edit1"></h4>
                            <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Nama : </label>
                                        <div class="input-group">
                                            <input readonly class="form-control" id="employee_name_edit1" name="employee_name_edit1" type="text">
                                            <input readonly class="form-control" id="id_pengajuan" name="id_pengajuan" type="hidden">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Department </label>
                                        <div class="input-group">
                                            <input readonly class="form-control" id="department_edit1" name="department_edit1" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Bagian </label>
                                        <div class="input-group">
                                            <input readonly class="form-control" id="bagain_edit1" name="bagain_edit1" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Status Staff </label>
                                        <div class="input-group">
                                            <input readonly class="form-control" id="status_staff_edit1" name="status_staff_edit1" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">NIK </label>
                                        <div class="input-group">
                                            <input readonly class="form-control" id="nik_edit1" name="nik_edit1" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Jumlah </label>
                                        <div class="input-group">
                                            <input class="form-control" id="jumlah_edit1" name="jumlah_edit1" type="text">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer bg-primary p-1">
                            <div class="btn-list">
                                <button type="button" id="btn-update_edit1" class="btn btn-secondary btn-app">Simpan</button>
                                <button type="button" id="btn-close_edit1" class="btn btn-warning btn-app" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footerjs')
    <!-- DataTables & Plugins -->
    <script src="{{URL::asset('assets/js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/iziToast.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/html5-qrcode/html5-qrcode.min.js') }}"></script>
    <script src="{{URL::asset('assets/js/timepicker.js') }}"></script>
    <style>
    .checkbox-xl .form-check-input {
        scale: 1.5;
    }
    </style>

<script>
    function toggle(source) {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i] != source)
                checkboxes[i].checked = source.checked;
        }
    }

</script>


    <script>
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });

        $('.select2').select2();
    </script>
    <script>

        function undo() {
            location.reload();
        }

        function cek_filter_modal() {
            const data= @json($selectemployee);
            var employee = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
            const employe_data = data.filter(x => x.enroll_id == employee)[0];
            if (employe_data == '') {
                iziToast.error({
                    message: 'Terjadi kesalahan',
                    position: 'topCenter'
                });
            } else {
                document.getElementById("txt_name").value = employe_data?.employee_name;
                document.getElementById("txt_enroll_id").value = employe_data?.enroll_id;
                document.getElementById("txt_department").value = employe_data?.department_name;
                document.getElementById("txt_bagian").value = employe_data?.sub_dept_name;
                document.getElementById("txt_staff").value = employe_data?.status_staff;
                document.getElementById("txt_nik").value = employe_data?.nik;
                setTimeout(() => {
                    document.getElementById("txt_jumlah").focus();
                }, 100);
            }

        }

        function onSave() {
            var employee = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
            let txt_jumlah = document.getElementById("txt_jumlah");
            let enroll_id = document.getElementById("txt_enroll_id").value.trim();
            let jumlah_value = txt_jumlah.value.trim().replace(/Rp\s?|[^0-9]/g, "");
            console.log('jumlah_value',jumlah_value)
            txt_jumlah.classList.remove("is-invalid");
            if(employee == ""){
                notif({
                        msg: "<b>Info:</b> Harap pilih karyawan terlebih dahulu!",
                        type: "error"
                    });
                return;
            }

            if (jumlah_value === "" || isNaN(jumlah_value) || parseFloat(jumlah_value) <= 0) {
                notif({
                        msg: "<b>Info:</b> Jumlah tidak boleh kosong atau bernilai 0!",
                        type: "error"
                    });
                txt_jumlah.classList.add("is-invalid");
                txt_jumlah.focus()
                return;
            }

            $.ajax({
                type: "POST",
                url: '{{ route('bazzar.store') }}',
                data: {
                    enroll_id: enroll_id,
                    jumlah: jumlah_value,
                },
                success: function(response) {
                    dataTableReload();

                    $("#selectEmployeeID").val(null).trigger("change");
                    $("#txt_name").val('');
                    $("#txt_enroll_id").val('');
                    $("#txt_department").val('');
                    $("#txt_bagian").val('');
                    $("#txt_staff").val('');
                    $("#txt_nik").val('');
                    $("#txt_jumlah").val('');
                },
                error: function(request, status, error) {
                },
            });
        }


        $(document).ready(function() {
            $("#selectEmployeeID").val('');
            $("#txt_name").val('');
            $("#txt_enroll_id").val('');
            $("#txt_department").val('');
            $("#txt_bagian").val('');
            $("#txt_staff").val('');
            $("#txt_nik").val('');
            $("#txt_jumlah").val('');
            dataTableReload();
            $("#txt_jumlah").on("input", function() {
                let value = $(this).val().replace(/[^\d]/g, ""); // Hanya angka
                if (value) {
                    value = parseInt(value, 10); // Ubah ke angka
                    $(this).val("Rp " + value.toLocaleString("id-ID")); // Format ke Rupiah
                } else {
                    $(this).val(""); // Kosongkan jika tidak ada angka
                }
            });
            $("#jumlah_edit1").on("input", function() {
                let value = $(this).val().replace(/[^\d]/g, ""); // Hanya angka
                if (value) {
                    value = parseInt(value, 10); // Ubah ke angka
                    $(this).val("Rp " + value.toLocaleString("id-ID")); // Format ke Rupiah
                } else {
                    $(this).val(""); // Kosongkan jika tidak ada angka
                }
            });

            $("#selectEmployeeID").select2().on("select2:select", function() {
                cek_filter_modal();
            });

        })


        function dataTableReload() {
            let activeTab = $(".nav-tabs .nav-link.active").attr("id");
            let status = activeTab.replace("-tab", ""); // Ambil status dari tab yang aktif
            let tableId = "#datatable_" + status; // Tentukan ID tabel yang sesuai

            // Hapus DataTable lama jika ada
            if ($.fn.DataTable.isDataTable(tableId)) {
                $(tableId).DataTable().clear().destroy();
            }

            // Tentukan apakah kolom checkbox harus ditampilkan
            let isPending = status === "pending"; // Hanya tampil jika di tab "pending"
            let isApprove = status === "approve"; // Hanya tampil jika di tab "pending"
            let isReject = status === "reject"; // Hanya tampil jika di tab "pending"

            // Konfigurasi kolom
            let columns = [
                {
                    data: null,
                    className: "text-center",
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                }
            ];

            if (isPending) {
                columns.push({
                    data: 'id',
                    render: (data, type, row, meta) => {
                        return `
                            <div class="form-check checkbox-xl text-center">
                                <input class="form-check-input row-checkbox"
                                    type="checkbox" value="` + row.id + `"
                                    data-id="` + row.id + `" />
                            </div>
                        `;
                    }
                });
            }


            // Tambahkan kolom lainnya
            columns = columns.concat([
                { data: 'enroll_id',className: "text-center", },
                { data: 'employee_name' },
                { data: 'department_name' },
                { data: 'sub_dept_name' },
                { data: 'status_staff' },
                {
                    data: 'jumlah',
                    className: "text-center",
                    render: function(data, type, row) {
                        if (!data) return "Rp 0"; // Jika kosong, tampilkan Rp 0
                        return "Rp " + parseInt(data, 10).toLocaleString("id-ID");
                    }
                },
                {
                  data: 'status',
                  className: "text-center",
                  render: (data, type, row, meta) => {
                    let bgColor = '';

                    switch (data.toLowerCase()) {
                        case 'approve':
                            bgColor = 'bg-primary';
                            break;
                        case 'reject':
                            bgColor = 'bg-danger';
                            break;
                        case 'pending':
                            bgColor = 'bg-warning';
                            break;
                        default:
                            bgColor = 'bg-secondary';
                    }
                            return `
                            <div class="timestamp ${bgColor}">
                                       `+data+`
                            </div>
                            `
                  }
                },
                {
                    data: 'created_at',
                    className: "text-center",
                    render: (data, type, row, meta) => {
                            return `
                            <div class="">
                                        `+moment(data.created_at).format('DD MMMM YYYY - HH:mm')+`
                            </div>
                            `
                    }
                 },
                 {
                    data: 'id',
                    className: "text-center",
                    width:'10%',
                    render: (data, type, row, meta) => {
                        let excelButton = "";
                        let editButton = "";
                        if (isApprove) {
                            excelButton = `
                                <a class='btn btn-success btn-sm' data-toggle="tooltip" title="Export Data ke File Transfer Excel" id="recap_labor_cost_2" onClick="export_laporan_pengajuan(`+row.id+`, '` + row.nik + `')">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                </a>
                            `;
                        }
                        if(isPending || isApprove){
                            editButton = `<a style="text-align:center" class='btn btn-primary btn-sm'
                                    onclick="edit_data(` + row.id + `, '` + row.nik + `');">
                                    <i class='fa fa-edit text-white'></i>
                                </a>`;
                        }
                        return `
                            <div>
                                ${editButton}
                                ${excelButton}
                                <a style="text-align:center" class='btn btn-danger btn-sm' onclick="hapus('` + row.id + `');">
                                <i class='fa fa-trash'></i>
                                </a>
                            </div>
                        `;
                    }
                }

            ]);

            // Inisialisasi DataTable
            $(tableId).DataTable({
                processing: true,
                paging: false,
                searching: false,
                ordering: false,
                destroy: true,
                ajax: {
                    url: '{{ route('bazzar.get_bazzar') }}',
                    dataSrc: "data",
                    data: {
                        status: status, // Kirim status yang sesuai
                    },
                },
                columns: columns,
                initComplete: function(settings, json) {
                    if (json.data.length > 0 && status === "pending" && ($('#username_who_access').val()=='HR' || $('#username_who_access').val()=='IT') ) {
                        $(".BtnVerifikasiOt").show(); // Tampilkan tombol jika ada data
                    } else {
                        $(".BtnVerifikasiOt").hide(); // Sembunyikan tombol jika tidak ada data
                    }
                }
            });
        }

        function export_laporan_pengajuan(id_n, no_form_n) {
            var id=id_n;
            var url = 'bazzar/export_laporan_pengajuan?id='+id;
            window.open(url, '_blank');

        }
        function export_all() {
            var url = 'bazzar/export_laporan_pengajuan';
            window.open(url, '_blank');

        }


        function hapus(a) {
            let id_tmp = a;
            Swal.fire({
            icon: 'error',
            title: 'Hapus data?',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#fa4456',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('bazzar.hapus') }}',
                        type: 'POST',
                        data: {
                            id_bazzar: id_tmp
                        },
                        success: function (res) {
                            notif({
                                msg: "<b>Info:</b> Data berhasil di hapus",
                                type: "info"
                            });
                            dataTableReload();
                            $('.modal').modal('hide');

                        }, error: function (jqXHR) {
                            let res = jqXHR.responseJSON;
                            let message = '';

                            for (let key in res.errors) {
                                message = res.errors[key];
                            }
                            dataTableReload();
                            notif({
                                    msg: "<b>Info:</b> Terjadi kesalahan",
                                    type: "error"
                                })
                        }
                    })
                }
            })


        }

        function getCheckedIds(){
            let checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            let ids = Array.from(checkedBoxes).map(cb => cb.getAttribute('data-id'));
            return ids;
        }

        function handleApprove() {
            let ids = getCheckedIds();
            if (ids.length === 0) {
                notif({
                        msg: "<b>Info:</b> Pilih minimal satu data untuk dikirim.",
                        type: "error"
                    });
                return;
            }
            $.ajax({
            url: '{{ route('bazzar.approve') }}',
            type: "POST",
            data: {
                ids: ids,
            },
            success: function(response) {
                notif({
                        msg: "<b>Info:</b> Data berhasil di verifikasi.",
                        type: "info"
                    });
                dataTableReload();
            },
            error: function(xhr) {
                notif({
                        msg: "<b>Info:</b> Terjadi kesalahan : " + xhr.responseText,
                        type: "error"
                    });
                }
            });
        }

        function handleReject() {
            let ids = getCheckedIds();
            if (ids.length === 0) {
                notif({
                        msg: "<b>Info:</b> Pilih minimal satu data untuk dikirim.",
                        type: "error"
                    });
                return;
            }
            $.ajax({
            url: '{{ route('bazzar.reject') }}',
            type: "POST",
            data: {
                ids: ids,
            },
            success: function(response) {
                notif({
                        msg: "<b>Info:</b> Data berhasil di reject.",
                        type: "info"
                    });
                dataTableReload();
            },
            error: function(xhr) {
                notif({
                        msg: "<b>Info:</b> Terjadi kesalahan : " + xhr.responseText,
                        type: "error"
                    });
                }
            });
        }

        function edit_data(editData, nik)
        {
            let activeTab = $(".nav-tabs .nav-link.active").attr("id");
            let status = activeTab.replace("-tab", ""); // Ambil status dari tab yang aktif
            let tableId = "#datatable_" + status; // Tentukan ID tabel yang sesuai
            if (editData) {
                $("#ajax-modal-edit1").modal('show');
                var currentRow = $(tableId).find("tr:eq(" + editData + ")").prevObject;
                if(tableId == "#datatable_pending"){
                    var employee_name = currentRow.find("td:eq(3)").html();
                    var department_name = currentRow.find("td:eq(4)").html();
                    var bagian_name = currentRow.find("td:eq(5)").html();
                    var status_staff = currentRow.find("td:eq(6)").html();
                    var jumlah = currentRow.find("td:eq(7)").html();
                } else if(tableId == "#datatable_approve"){
                    var employee_name = currentRow.find("td:eq(2)").html();
                    var department_name = currentRow.find("td:eq(3)").html();
                    var bagian_name = currentRow.find("td:eq(4)").html();
                    var status_staff = currentRow.find("td:eq(5)").html();
                    var jumlah = currentRow.find("td:eq(6)").html();
                }



                $('#title-modal-edit1').text('EDIT PENGAJUAN KUPON : '+ employee_name);
                $('#id_pengajuan').val(editData);
                $('#employee_name_edit1').val(employee_name);
                $('#department_edit1').val(department_name);
                $('#bagain_edit1').val(bagian_name);
                $('#status_staff_edit1').val(status_staff);
                $('#nik_edit1').val(nik);
                $('#jumlah_edit1').val(jumlah);
            }

        };

        $('body').on('click', '#btn-update_edit1', function (event) {
            var jumlah_edit = $('#jumlah_edit1').val();
            var id_pengajuan = $('#id_pengajuan').val();
            console.log('jumlah_edit',jumlah_edit)
            console.log('id_pengajuan',id_pengajuan)
            $('#btn-update_edit1').addClass("btn-loading");
            $('#btn-update_edit1').html('Loading...');
            $('#btn-update_edit1').attr("disabled", true);

            $.ajax({
                type:"POST",
                url: "{{route('bazzar.edit_pengajuan')}}",
                data: {
                    id_pengajuan:id_pengajuan,
                    jumlah_edit:jumlah_edit
                },
                success: function(res){
                    notif({
                        msg: "<b>Success:</b> Data berhasil di simpan.",
                        type: "success"
                    });

                    $("#btn-update_edit1").removeClass("btn-loading");
                    $("#btn-update_edit1").html('Simpan');
                    $("#btn-update_edit1").attr("disabled", false);

                    $("#ajax-modal-edit1").modal('hide');
                    dataTableReload();
                },
                error: function(res){
                    notif({
                        msg: "<b>Oops!</b> Simpan data lembur gagal.",
                        type: "error",
                        position: "center"
                    });

                    $("#btn-update_edit1").removeClass("btn-loading");
                    $("#btn-update_edit1").html('Simpan');
                    $("#btn-update_edit1").attr("disabled", false);

                }
            });
        });

        $('.nav-tabs .nav-link').on('click', function () {
            setTimeout(dataTableReload, 100);
        });


    </script>
@endsection
