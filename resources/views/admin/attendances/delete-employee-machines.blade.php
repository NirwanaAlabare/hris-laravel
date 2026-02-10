@extends('admin.adminlayouts.adminlayout')

@section('head')
<!-- DATATABLES CSS (CDN – FIX 404) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

<style>
    .card-box {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
        margin-bottom: 20px;
    }

    .card-header {
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-body {
        padding: 20px;
    }

    .badge-status {
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 12px;
    }

    .badge-active {
        background: #d4edda;
        color: #155724;
    }

    .badge-inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .action-bar {
        background: #f9fafb;
        padding: 15px;
        border-radius: 6px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .machine-list label {
        padding: 8px 10px;
        border-radius: 6px;
        cursor: pointer;
    }

    .machine-list label:hover {
        background: #f1f5f9;
    }

    table.dataTable {
        width: 100% !important;
    }

    .dataTables_wrapper {
        width: 100%;
    }

    .dataTables_scrollBody {
        overflow-x: auto !important;
    }

    <style>

    /* Agar cursor berubah jadi jari saat diarahkan ke baris */
    .cursor-pointer {
        cursor: pointer;
    }

    .list-item-machine {
        transition: background 0.2s;
        padding: 0.75rem 1.25rem;
    }

    .list-item-machine:hover {
        background-color: #f1f4f9;
    }

    /* Badge Soft Colors */
    .bg-success-soft {
        background-color: #e8f5e9;
    }

    .bg-danger-soft {
        background-color: #ffebee;
    }

    /* Container dengan scrollbar cantik */
    .machine-list-container::-webkit-scrollbar {
        width: 6px;
    }

    .machine-list-container::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 10px;
    }

    /* Style untuk input disabled agar tidak membingungkan */
    input.machine-check:disabled {
        cursor: not-allowed;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .filter-box {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #e3e6f0;
    }
</style>
</style>
@stop

@section('mainarea')

<div class="container-fluid my-6 py-6  ">

    <h3 class="page-title mb-4">
        {{$pageTitle}}
        <small class="text-muted">Hapus Karyawan dari Mesin Absensi</small>
    </h3>
    <div class="card-box">
        <div class="card-body">
            <div class="filter-box mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <label class="font-weight-bold">Department</label>
                        <select id="filterDept" class="form-control">
                            <option value="">-- Semua Department --</option>
                            @foreach($departments as $dept)
                            <option value="{{ $dept->department_name }}">{{ $dept->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="font-weight-bold">Status Aktif</label>
                        <select id="filterStatus" class="form-control">
                            <option value="">-- Semua Status --</option>
                            <option value="AKTIF">AKTIF</option>
                            <option value="TIDAK AKTIF">TIDAK AKTIF</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ================== CARD KARYAWAN ================== --}}
    <div class="card-box">
        <div class="card-header">
            <span><i class="fa fa-users"></i> Daftar Karyawan</span>

            <button class="btn btn-danger btn-sm" id="btnCheckEmployee" disabled>
                <i class="fa fa-trash"></i> Check Employee
            </button>
            <button class="btn btn-danger btn-sm" id="btnDeleteFromMachine" disabled>
                <i class="fa fa-trash"></i> Hapus dari Mesin
            </button>
        </div>

        <div class="card-body">
            <table class="table table-striped table-bordered" id="employeeTable">
                <thead>
                    <tr>
                        <th width="30">
                            <input type="checkbox" id="checkAll">
                        </th>
                        <th>enroll_id</th>
                        <th>Nama</th>
                        <th>Department</th>
                        <th>Status Aktif</th>
                        <th>Status di Mesin</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    {{-- ================= MODAL HASIL CHECK ================= --}}
    <div class="modal fade" id="checkResultModal">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h4 class="modal-title"><i class="fa fa-search"></i> Hasil Pengecekan Mesin</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Enroll ID</th>
                                    <th>Mesin</th>
                                    <th>Status di Mesin</th>
                                </tr>
                            </thead>
                            <tbody id="checkResultBody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ================= MODAL PILIH MESIN ================= --}}
<div class="modal fade" id="machineModal">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">
                    <i class="fa fa-desktop"></i> Pilih Mesin Absensi
                </h4>
            </div>

            <div class="modal-body machine-list">
                <label class="d-block mb-2">
                    <input type="checkbox" id="selectAllMachine" checked>
                    <strong> Semua Mesin</strong>
                </label>

                <hr>

                <div class="machine-list-container"
                    style="max-height: 500px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 8px;">
                    <ul class="list-group list-group-flush">
                        @foreach($machines as $m)
                        <li
                            class="list-group-item list-item-machine {{ $m['is_online'] ? '' : 'bg-light text-muted' }}">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="form-check me-3">
                                        <input type="checkbox" class="form-check-input machine-check"
                                            id="chk-{{ $loop->index }}" value="{{ $m['IP'] }}"
                                            data-name="{{ $m['MachineAlias'] }}" {{ $m['is_online'] ? 'checked'
                                            : 'disabled' }}>
                                    </div>

                                    <label class="form-check-label mb-0 cursor-pointer" for="chk-{{ $loop->index }}">
                                        <span class="fw-bold">{{ $m['MachineAlias'] }}</span>
                                        <small class="text-secondary ms-2">({{ $m['IP'] }})</small>
                                    </label>
                                </div>

                                <div>
                                    @if($m['is_online'])
                                    <span class="badge rounded-pill bg-success-soft text-success border border-success">
                                        <i class="fas fa-check-circle me-1"></i> Online
                                    </span>
                                    @else
                                    <span class="badge rounded-pill bg-danger-soft text-danger border border-danger">
                                        <i class="fas fa-times-circle me-1"></i> Offline
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Batal</button>
                <button class="btn btn-primary" id="btnReview">Lanjut</button>
            </div>

        </div>
    </div>
</div>

{{-- ================= MODAL REVIEW ================= --}}
<div class="modal fade" id="reviewModal">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h4 class="modal-title">
                    <i class="fa fa-warning"></i> Konfirmasi Penghapusan
                </h4>
            </div>

            <div class="modal-body">
                <p><strong>Karyawan yang akan dihapus:</strong></p>
                <ul id="reviewEmployees"></ul>

                <p class="mt-3"><strong>Mesin tujuan:</strong></p>
                <ul id="reviewMachines"></ul>
            </div>

            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Kembali</button>
                <button class="btn btn-danger" id="btnExecute">
                    Ya, Hapus Sekarang
                </button>
            </div>

        </div>
    </div>
</div>

@stop

@section('footerjs')

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
    let selectedUsers = [];
    let currentAction = 'delete';
$(function () {

    // ================= DATATABLE =================
    let table = $('#employeeTable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        // scrollX: true,
        // paginate: false,
        // scrollY: "400px",
        // scrollCollapse: true,
        ajax: {
            url: "{{ route('hris.attendance.ajaxEmployeeList') }}",
            data: function (d) {
                d.department = $('#filterDept').val();
                d.status = $('#filterStatus').val();
            }
        },
        columns: [
            {data: 'checkbox', orderable: false, searchable: false},
            {data: 'enroll_id'},
            {data: 'employee_name'},
            {data: 'department_name'},
            {data: 'status_aktif'},
            { 
                data: 'isDeletedInMachine',
                render: function(data, type, row) {
                    if (!data || data === '[]' || data === '{}') return '-';
                    
                    let logData = data;
                    if (typeof data === 'string') {
                        try {
                            // Decode HTML entities jika ada (biasanya dari Laravel response)
                            let doc = new DOMParser().parseFromString(data, 'text/html');
                            logData = JSON.parse(doc.documentElement.textContent);
                            if (typeof logData === 'string') logData = JSON.parse(logData);
                        } catch(e) { return '-'; }
                    }

                    // Cek apakah machine_logs ada
                    if (!logData.machine_logs || !Array.isArray(logData.machine_logs)) return '-';

                    let html = '<div class="d-flex flex-wrap gap-1" style="max-width: 500px; line-height: 1;">';
                    let found = false;

                    // Ambil entry terbaru dari machine_logs (asumsi index terakhir adalah yang terbaru)
                    let latestLogEntry = logData.machine_logs[logData.machine_logs.length - 1];
                    let machineStatusArray = latestLogEntry.status || [];

                    machineStatusArray.forEach(item => {
                        found = true;
                        let ip = item.ip || '0.0.0.0';
                        let status = item.status; // SUCCESS, FAILED, atau ERROR
                        let raw = item.raw_response || {};
                        let isDeleted = raw.deleted === true;
                        let isNotFound = raw.error === 'NOT_FOUND';
                        
                        // Penentuan Warna
                        let badgeColor = '#dc3545'; // Default Merah (Failed)
                        let bgColor = '#ffeef3';
                        
                        if (isDeleted) {
                            badgeColor = '#28a745'; // Hijau (Success)
                            bgColor = '#e8fadf';
                        } else if (isNotFound) {
                            badgeColor = '#fd7e14'; // Orange atau Biru (Not Found)
                            bgColor = '#fff5eb';
                        }

                        let shortIp = ip.split('.').pop(); 
                        let tooltip = `IP: ${ip} | Device: ${item.device_name} | Result: ${isNotFound ? 'Not Found' : status} | Time: ${latestLogEntry.time}`;

                        html += `
                            <span title="${tooltip}" 
                                style="
                                    background: ${bgColor};
                                    color: ${badgeColor};
                                    border: 1px solid ${badgeColor}44;
                                    padding: 2px 6px;
                                    border-radius: 4px;
                                    font-size: 10px;
                                    font-weight: bold;
                                    cursor: help;
                                    white-space: nowrap;
                                    display: inline-block;
                                    margin-bottom: 2px;
                                ">
                                .${shortIp} ${isNotFound ? '∅' : ''}
                            </span>`;
                    });
                    
                    return found ? html + '</div>' : '-';
                }
            }
        ],
        language: {
            // Menyesuaikan teks pagination jika ingin lebih rapi
            paginate: {
                previous: "<i class='fa fa-chevron-left'></i>",
                next: "<i class='fa fa-chevron-right'></i>"
            }
        }
    });

    // Trigger filter
    $('#filterDept, #filterStatus').on('change', function () {
        table.draw();
    });

$('#btnCheckEmployee').click(function() {
    currentAction = 'check';
    $('#machineModal').modal('show');
});

    // ================= CHECKBOX USER =================
$(document).on('change', '.row-check', function () {
    selectedUsers = $('.row-check:checked').map(function () {
        return $(this).data('enroll_id');
    }).get();

    let isDisabled = selectedUsers.length === 0;
    $('#btnDeleteFromMachine').prop('disabled', isDisabled);
    $('#btnCheckEmployee').prop('disabled', isDisabled); // Aktifkan tombol check
});

    $('#checkAll').on('change', function () {
        $('.row-check').prop('checked', this.checked).trigger('change');
    });

    // ================= FLOW =================
$('#btnDeleteFromMachine').click(function() {
    currentAction = 'delete';
    $('#machineModal').modal('show');
});

    $('#selectAllMachine').change(function () {
        $('.machine-check:not(:disabled)').prop('checked', this.checked);
    });

$('#btnReview').click(function () {
    let machineIds = $('.machine-check:checked').map(function () {
        return $(this).val();
    }).get();

    if (machineIds.length === 0) {
        alert('Pilih minimal satu mesin');
        return;
    }

    if (currentAction === 'delete') {
        // Tampilkan Modal Konfirmasi Hapus (Existing Logic)
        $('#reviewEmployees').empty();
        $('#reviewMachines').empty();
        selectedUsers.forEach(id => $('#reviewEmployees').append(`<li>enroll_id ${id}</li>`));
        $('.machine-check:checked').each(function () {
            $('#reviewMachines').append(`<li>${$(this).data('name')}</li>`);
        });
        $('#machineModal').modal('hide');
        $('#reviewModal').modal('show');
    } else {
        // Jalankan Proses Check Langsung
        executeCheck(machineIds);
    }
});

    $('#btnExecute').click(function () {

        let machineIds = $('.machine-check:checked').map(function () {
            return $(this).val();
        }).get();

        if (selectedUsers.length === 0 || machineIds.length === 0) {
            alert('User dan mesin harus dipilih');
            return;
        }

        // 🔥 loading state
        $(this).prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm"></span> Processing...'
        );

        $.ajax({
            url: "{{ route('hris.attendance.deleteEmployeeFromMachine') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                enroll_ids: selectedUsers,
                machine_ids: machineIds
            },
            success: function (res) {

                $('#reviewModal').modal('hide');

                // reset
                selectedUsers = [];
                $('#checkAll').prop('checked', false);
                $('#btnDeleteFromMachine').prop('disabled', true);

                // reload datatable
                $('#employeeTable').DataTable().ajax.reload(null, false);

                // feedback
                alert(res.message || 'Berhasil dihapus');
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message || 'Terjadi kesalahan');
            },
            complete: function () {
                $('#btnExecute')
                    .prop('disabled', false)
                    .html('<i class="bi bi-trash"></i> Execute Delete');
            }
        });
    });

function executeCheck(machineIds) {
    let btn = $('#btnReview');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Checking...');

    $.ajax({
        url: "{{ route('hris.attendance.checkEmployeeOnMachine') }}", // Anda perlu buat route ini
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            enroll_ids: selectedUsers,
            machine_ids: machineIds
        },
        success: function (res) {
            $('#machineModal').modal('hide');
            $('#checkResultBody').empty();
            console.log(res);
            
            // Asumsi response 'res.data' berisi array object {enroll_id, machine_name, exists}
            res.data.forEach(item => {
                let statusBadge = item.exists 
                    ? '<span class="badge badge-success">Ditemukan</span>' 
                    : '<span class="badge badge-danger">Tidak Ada</span>';
                
                $('#checkResultBody').append(`
                    <tr>
                        <td>${item.enroll_id}</td>
                        <td>${item.machine_ip}</td>
                        <td class="text-center">${statusBadge}</td>
                    </tr>
                `);
            });

            $('#checkResultModal').modal('show');
        },
        error: function (xhr) {
            alert(xhr.responseJSON?.message || 'Gagal mengecek mesin');
        },
        complete: function () {
            btn.prop('disabled', false).html('Lanjut');
        }
    });
}


});
</script>
@stop