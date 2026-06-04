@extends('admin.adminlayouts.adminlayout')

@section('head')
<!-- DATATABLES CSS (CDN – FIX 404) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">
<!-- DataTables Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
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
        {{-- <small class="text-muted">Attendance Logs</small> --}}
    </h3>
    {{-- <div class="card-box">
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
                    <div class="col-md-4">
                        <label class="font-weight-bold">Status di Mesin</label>
                        <select id="filterStatusMachine" class="form-control">
                            <option value="">-- Semua Status --</option>
                            <option value="DELETED">DELETED</option>
                            <option value="QUEUED">QUEUED</option>
                            <option value="NOT DELETED">NOT DELETED</option>

                        </select>
                    </div>
                </div>
            </div>

        </div>
    </div> --}}

    {{-- ================== CARD KARYAWAN ================== --}}
    <div class="card-box">
        <div class="card-header">
            <span><i class="fa fa-users"></i> Daftar Karyawan</span>

            <button class="btn btn-danger btn-sm" id="btnGetLogs" data-toggle="tooltip" title="Get Attendance Logs from Machine">
                <i class="fa fa-trash"></i> Get Logs
            </button>
            <button class="btn btn-info btn-sm" id="btnExportRawLogs" data-toggle="tooltip" title="Export Raw Attendance Logs">
                <i class="fa fa-file-export"></i> Export Raw Logs
            </button>

        </div>

        <div class="card-body">
            <table class="table table-striped table-bordered" id="employeeTable">
                <thead>
                    <tr>
                        <th>Enroll ID</th>
                        <th>Nama</th>
                        <th>Department</th>
                        <th>Tanggal</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
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



@stop

@section('footerjs')

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<!-- DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script>
$(function () {

// ================= DATATABLE =================
let table = $('#employeeTable').DataTable({
    processing: true,
    serverSide: true,
    deferRender: true,
    scroller: true,
    scrollY: '600px',
    scrollCollapse: true,
    scroller: {
        loadingIndicator: true,
        displayBuffer: 9,
        rowHeight: 35
    },
    autoWidth: false,
    pageLength: 50,
    lengthMenu: [[25, 50, 100, 250, 500], [25, 50, 100, 250, 500]],
    pagingType: 'full_numbers',
     // Add buttons for export
    dom: 'Bfrtip', // This enables buttons    
    
    ajax: {
        url: "{{ route('hris.attendance.ajaxEmployeeListAttendace') }}",
        type: "GET",
        data: function (d) {
            // Add custom search value if you have custom search input
            d.search_value = $('#employeeTable_filter input').val();
        },
        cache: false,
        timeout: 60000
    },
    
    columns: [
        {data: 'enroll_id', name: 'enroll_id'},
        {data: 'Nama', name: 'Nama'},
        {data: 'department_name', name: 'department_name'},
        {data: 'Tanggal', name: 'Tanggal', searchable: false},
        {data: 'Jam_Masuk', name: 'Jam_Masuk', searchable: false},
        {data: 'Jam_Pulang', name: 'Jam_Pulang', searchable: false},
    ],
    
    order: [[0, 'asc']],
    
    // IMPORTANT: Disable default search
    searching: true, // Keep true but we'll override behavior
    
    language: {
        // processing: '<div class="spinner-border text-primary" role="status">Loading...</div>',
        lengthMenu: 'Show _MENU_ entries per page',
        zeroRecords: 'No records found',
        info: 'Showing _START_ to _END_ of _TOTAL_ entries',
        infoEmpty: 'Showing 0 to 0 of 0 entries',
        infoFiltered: '(filtered from _MAX_ total entries)',
        search: 'Search (press Enter):',
        searchPlaceholder: 'Type and press Enter...',
        paginate: {
            first: 'First',
            last: 'Last',
            next: '→',
            previous: '←'
        }
    },
    
    stateSave: true,
    stateDuration: 7200
});

// IMPORTANT: Wait for DataTable to be fully initialized
setTimeout(function() {
    // Get the search input
    let searchInput = $('#employeeTable_filter input');
    
    // Remove ALL existing event handlers
    searchInput.off();
    
    // Add new event handler for Enter key only
    searchInput.on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            let searchValue = $(this).val();
            console.log('Searching for:', searchValue);
            table.search(searchValue).draw();
        }
    });
    
    // Optional: Add search button next to input
    if (searchInput.next('button').length === 0) {
        searchInput.after('<button id="customSearchBtn" class="btn btn-primary btn-sm ml-2" style="margin-left: 5px;">🔍</button>');
        $('#customSearchBtn').on('click', function() {
            let searchValue = searchInput.val();
            table.search(searchValue).draw();
        });
    }
}, 100);

// Filter change handlers (auto reload)
let filterTimeout;
$('#filterDept, #filterStatus, #filterStatusMachine').on('change', function() {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(function() {
        table.ajax.reload();
    }, 300);
});

// Trigger filter - Remove duplicate (you had this twice)
// $('#filterDept, #filterStatus, #filterStatusMachine').on('change', function () {
//     table.draw();
// }); // Remove this duplicate

$('#btnGetLogs').click(function() {
    currentAction = 'check';
    $('#machineModal').modal('show');
});

$('#btnReview').click(function () {
    let machineIds = $('.machine-check:checked').map(function () {
        return $(this).val();
    }).get();

    if (machineIds.length === 0) {
        alert('Pilih minimal satu mesin');
        return;
    }

    executeCheck(machineIds);
    $('#machineModal').modal('hide');
});

function executeCheck(machineIds) {
    // Show loading state
    let $btn = $('#btnGetLogs');
    let originalText = $btn.html();
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
    
    $.ajax({
        url: "{{ route('hris.attendance.getLogEmployeeFromMachine') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            machine_ids: machineIds
        },
        success: function (res) {
            if (res.status) {
                // Show success message with summary
                let message = res.message;
                if (res.summary && res.summary.total_records > 0) {
                    message = `${res.message}\n\n📊 Total Records: ${res.summary.total_records}\n✅ Successful Machines: ${res.summary.successful_machines}/${res.summary.total_machines}`;
                }
                
                // Show errors if any machines failed
                if (res.errors && res.errors.length > 0) {
                    message += `\n\n❌ Failed Machines:\n`;
                    res.errors.forEach(err => {
                        message += `- ${err.machine_ip}: ${err.message}\n`;
                    });
                }
                
                alert(message);
                
                // Reload the table to show updated data
                table.ajax.reload(null, false);
                
                // Log data for debugging
                if (res.data && res.data.length > 0) {
                    console.log('Retrieved records:', res.data.length, 'records');
                }
            } else {
                alert(res.message || 'Check completed with errors');
                if (res.errors && res.errors.length > 0) {
                    console.error('Errors:', res.errors);
                }
            }
        },
        error: function (xhr) {
            let errorMsg = xhr.responseJSON?.message || 'Failed to start check';
            alert(errorMsg);
        },
        complete: function() {
            // Reset button state
            $btn.prop('disabled', false).html(originalText);
        }
    });
}

// Optional: Add clear search button
$(document).on('click', '#clearSearchBtn', function() {
    $('#employeeTable_filter input').val('');
    table.search('').draw();
});

// Handle modal close
$('#machineModal').on('hidden.bs.modal', function () {
    // Reset any modal state if needed
});

// Select all machines functionality
$('#selectAllMachine').on('change', function() {
    let isChecked = $(this).is(':checked');
    $('.machine-check:not(:disabled)').prop('checked', isChecked);
});

// Update select all when individual checkboxes change
$(document).on('change', '.machine-check', function() {
    let totalCheckable = $('.machine-check:not(:disabled)').length;
    let checkedCount = $('.machine-check:not(:disabled):checked').length;
    
    if (checkedCount === totalCheckable) {
        $('#selectAllMachine').prop('checked', true);
    } else {
        $('#selectAllMachine').prop('checked', false);
    }
});

$('#btnExportRawLogs').click(function() {
    window.location.href = "{{ route('hris.attendance.export_raw_logs') }}";
});

});

</script>
@stop