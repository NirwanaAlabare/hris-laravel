@extends('admin.adminlayouts.adminlayout')

@section('head')
<!-- DATATABLES CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">
<!-- DataTables Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<!-- Date Range Picker CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
        flex-wrap: wrap;
        gap: 10px;
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

    .bg-success-soft {
        background-color: #e8f5e9;
    }

    .bg-danger-soft {
        background-color: #ffebee;
    }

    .machine-list-container::-webkit-scrollbar {
        width: 6px;
    }

    .machine-list-container::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 10px;
    }

    input.machine-check:disabled {
        cursor: not-allowed;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .filter-box {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .filter-box:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .form-select,
    .form-control {
        border-radius: 6px;
        border: 1px solid #ced4da;
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        padding: 8px 15px;
        font-weight: 500;
    }

    .btn-primary:hover {
        background-color: #0069d9;
        border-color: #0062cc;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        padding: 8px 15px;
        font-weight: 500;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    label {
        font-size: 14px;
        color: #495057;
    }

    @media (max-width: 768px) {
        .filter-box .row>div {
            margin-bottom: 15px;
        }

        .btn {
            width: 100%;
        }

        .card-header {
            flex-direction: column;
            align-items: stretch;
        }

        .card-header div {
            display: flex;
            gap: 10px;
        }

        .card-header .btn {
            flex: 1;
        }
    }

    .yearly table.table-condensed .monthselect,
    .yearly table.table-condensed thead tr:nth-child(2),
    .yearly table.table-condensed tbody,
    .monthly table.table-condensed thead tr:nth-child(2),
    .monthly table.table-condensed tbody {
        display: none;
    }

    .daterangepicker.monthly .drp-calendar,
    .daterangepicker.yearly .drp-calendar {
        width: 1000px !important;
    }

    .yearly table.table-condensed .yearselect {
        width: 100%;
    }

    .form-select,
    .monthselect,
    .yearselect {
        border-radius: 5px;
    }

    .drp-buttons {
        background: #f09494;
    }
</style>
@stop

@section('mainarea')

<div class="container-fluid my-6 py-6">
    <h3 class="page-title mb-4">{{$pageTitle}}</h3>

    <!-- Filter Box -->
    <div class="card-box">
        <div class="card-body">
            <div class="filter-box">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="font-weight-bold mb-2">Date Filter</label>
                        <div class="d-flex">
                            <select id="date_changer" class="form-select me-2" style="width: 40%;">
                                <option value="">Choose</option>
                                <option value="daily">Daily</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                                <option value="range">Range</option>
                            </select>
                            <input autocomplete="off" type="text" id="date_pick" name="date_pick" class="form-control"
                                style="width: 60%;" placeholder="Select date">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="font-weight-bold mb-2">Department</label>
                        <select id="filterDept" class="form-control">
                            <option value="">-- All Departments --</option>
                            @foreach($departments as $dept)
                            <option value="{{ $dept->department_name }}">{{ $dept->department_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="button" id="applyFilterBtn" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>

                    <div class="col-md-2">
                        <button type="button" id="resetFilterBtn" class="btn btn-secondary w-100">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Table Card -->
    <div class="card-box">
        <div class="card-header">
            <span><i class="fa fa-users"></i> Daftar Karyawan</span>
            <span id="latestCreatedDate"></span>
            <div>
                <button class="btn btn-primary btn-sm" id="btnGetLogs" title="Get Attendance Logs from Machine">
                    <i class="fa fa-download"></i> Get Logs
                </button>
                <button class="btn btn-info btn-sm" id="btnExportRawLogs" title="Export Raw Attendance Logs">
                    <i class="fa fa-file-export"></i> Export Raw Logs
                </button>
                <button class="btn btn-info btn-sm" id="btnExportFormattedLogs"
                    title="Export Formatted Attendance Logs">
                    <i class="fa fa-file-export"></i> Export Logs
                </button>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-striped table-bordered" id="employeeTable" style="width:100%">
                <thead>
                    <tr>
                        <th>No/th>
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

<!-- Machine Selection Modal -->
<div class="modal fade" id="machineModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <i class="fa fa-desktop"></i> Pilih Mesin Absensi
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnReview">Lanjut</button>
            </div>
        </div>
    </div>
</div>

@stop

@section('footerjs')

<!-- Required Dependencies -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    var startDate;
    var endDate;

    $(document).ready(function() {
    // ============================================
    // DATATABLE INITIALIZATION
    // ============================================
    let table = $('#employeeTable').DataTable({
        processing: true,
        serverSide: true,
        deferRender: true,
        scrollY: '600px',
        scrollCollapse: true,
        scrollX: true,
        scroller: {
            loadingIndicator: true,
            displayBuffer: 9,
            rowHeight: 35
        },
        autoWidth: false,
        pageLength: 50,
        lengthMenu: [[25, 50, 100, 250, 500], [25, 50, 100, 250, 500]],
        pagingType: 'full_numbers',
        dom: 'lBfrtip',
        buttons: [
            { extend: 'copy', className: 'btn-sm' },
            { extend: 'csv', className: 'btn-sm' },
            { extend: 'excel', className: 'btn-sm' },
            { extend: 'pdf', className: 'btn-sm' },
            { extend: 'print', className: 'btn-sm' }
        ],
        ajax: {
            url: "{{ route('hris.attendance.ajaxEmployeeListAttendace') }}",
            type: "GET",
            data: function(d) {           

                d.search_value = $('#employeeTable_filter input').val();
                d.department = $('#filterDept').val();
                d.start_date = startDate ? startDate.format('YYYY-MM-DD') : '';
                d.end_date = endDate ? endDate.format('YYYY-MM-DD') : '';
                d.date_type = $("#date_changer").val();
                d.month = moment(endDate).format("MM");
                d.year = moment(endDate).format("YYYY");
            },
            timeout: 60000,
            error: function(xhr, status, error) {
                console.error('DataTables Error:', error);
                alert('Failed to load data. Please refresh the page.');
            },
            dataSrc: function(json) {
                // Update latest date from server metadata (across all filtered records)
                console.log('AJAX Response Metadata:', json);
                if (json.latestCreatedAt) {
                    let formattedDate = moment(json.latestCreatedAt).format('DD-MM-YYYY HH:mm:ss');
                    $('#latestCreatedDate').text(`Latest Update: ${formattedDate}`);
                }
                return json.data;
            }
        },
        columns: [
            { 
                data: null, 
                name: 'no', 
                title: 'No.',
                render: function(data, type, row, meta) {
                    return meta.row + 1 + (meta.settings._iDisplayStart || 0);
                },
                orderable: false,
                searchable: false,
                className: 'text-center'
            },
            { data: 'enroll_id', name: 'enroll_id' },
            { data: 'Nama', name: 'Nama' },
            { data: 'department_name', name: 'department_name' },
            { data: 'Tanggal', name: 'Tanggal', searchable: false },
            { data: 'Jam_Masuk', name: 'Jam_Masuk', searchable: false },
            { data: 'Jam_Pulang', name: 'Jam_Pulang', searchable: false }
        ],
        order: false,
        searching: true,
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
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
        stateDuration: 7200,
  
    });

    // Fix search input - only search on Enter key
    setTimeout(function() {
        let searchInput = $('#employeeTable_filter input');
        if (searchInput.length) {
            searchInput.off();
            searchInput.on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    table.search($(this).val()).draw();
                }
            });
            
            if (searchInput.next('button').length === 0) {
                searchInput.after('<button id="customSearchBtn" class="btn btn-primary btn-sm ml-2">🔍</button>');
                $('#customSearchBtn').on('click', function() {
                    table.search(searchInput.val()).draw();
                });
            }
        }
    }, 100);

    // ============================================
    // FILTER FUNCTIONS
    // ============================================
    function applyFilters() {
        table.ajax.reload();
    }

    // Reset 
    setInterval(function() {
        applyFilters();
        console.log('Auto-refreshing data...');
    }, 1000 * 60 * 5); // Auto-refresh every 5 minutes

    function resetFilters() {
        $('#filterDept').val('');
        $('#date_changer').val('');
        $('#date_pick').val('');
        startDate = null;
        endDate = null;
        table.ajax.reload();
    }

    $('#applyFilterBtn').on('click', applyFilters);
    $('#resetFilterBtn').on('click', resetFilters);

    let filterTimeout;
    $('#filterDept').on('change', function() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => table.ajax.reload(), 300);
    });

    // ============================================
    // MACHINE LOGS FUNCTIONALITY
    // ============================================
    $('#btnGetLogs').click(function() {
        $('#machineModal').modal('show');
    });

    $('#selectAllMachine').on('change', function() {
        let isChecked = $(this).is(':checked');
        $('.machine-check:not(:disabled)').prop('checked', isChecked);
    });

    $(document).on('change', '.machine-check', function() {
        let totalCheckable = $('.machine-check:not(:disabled)').length;
        let checkedCount = $('.machine-check:not(:disabled):checked').length;
        $('#selectAllMachine').prop('checked', checkedCount === totalCheckable);
    });

    $('#btnReview').click(function() {
        let machineIds = $('.machine-check:checked').map(function() {
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
            success: function(res) {
                let message = res.message || '';
                if (res.status) {
                    if (res.summary && res.summary.total_records > 0) {
                        message = `${res.message}\n\n📊 Total Records: ${res.summary.total_records}\n✅ Successful Machines: ${res.summary.successful_machines}/${res.summary.total_machines}`;
                    }
                    if (res.errors && res.errors.length > 0) {
                        message += `\n\n❌ Failed Machines:\n`;
                        res.errors.forEach(err => {
                            message += `- ${err.machine_ip || err.machine_id}: ${err.message}\n`;
                        });
                    }
                    alert(message);
                    table.ajax.reload(null, false);
                } else {
                    alert(message || 'Check completed with errors');
                }
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.message || 'Failed to start check');
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    }

    $('#btnExportRawLogs').click(function() {
        location.href = "{{ route('hris.attendance.export_raw_logs') }}";
    });

    $('#btnExportFormattedLogs').click(function() {
        var $btn = $(this);
        var originalText = $btn.text();
        
        // Show loading
        $btn.prop('disabled', true).text('Exporting...');
        
        // Build query parameters
        var params = $.param({
            department: $('#filterDept').val(),
            start_date: startDate ? startDate.format('YYYY-MM-DD') : '',
            end_date: endDate ? endDate.format('YYYY-MM-DD') : '',
            date_type: $("#date_changer").val(),
            month: moment(endDate).format("MM"),
            year: moment(endDate).format("YYYY"),
            search: { value: $('input[type="search"]').val() || '' }
        });
        
        // Trigger download
        window.location.href = "{{ route('hris.attendance.export_formatted_logs') }}?" + params;
        
        // Re-enable button after short delay (since page doesn't reload)
        setTimeout(function() {
            $btn.prop('disabled', false).text(originalText);
        }, 2000);
    });

    // ============================================
    // DATE RANGE PICKER
    // ============================================
  
    var datepicker = $('input[name="date_pick"]');
    var pickerConfig = function(period) {
        var config = {};
        switch (period) {
            case "daily":
                config = {
                    showDropdowns: true,
                    singleDatePicker: true,
                    autoUpdateInput: true,
                    startDate: moment(),
                    locale: {
                    format: "DD MMMM YYYY",
                    cancelLabel: 'Clear'
                    }
                };
            break;
            case "monthly":
                config = {
                    showDropdowns: true,
                    singleDatePicker: true,
                    autoUpdateInput: true,
                    startDate: moment(),
                    locale: {
                    format: "MMMM YYYY",
                    cancelLabel: 'Clear'
                    }
                };
            break;
            case "yearly":
                config = {
                    showDropdowns: true,
                    singleDatePicker: true,
                    autoUpdateInput: true,
                    startDate: moment(),
                    locale: {
                    format: "YYYY",
                    cancelLabel: 'Clear'
                    }
                };
            break;
            case "range":
                config = {
                    showDropdowns: true,
                    autoUpdateInput: true,
                    startDate: moment(),
                    locale: {
                    format: "DD MMMM YYYY",
                    cancelLabel: 'Clear'
                    }
                };
            break;
            default:
            config = {
                    showDropdowns: true,
                    singleDatePicker: true,
                    autoUpdateInput: true,
                    locale: {
                    format: "DD MMMM YYYY",
                    cancelLabel: 'Clear'
                    }
                };
            break;
        }
        return config;
    };
    var pickerEvent = function(picker, period) {
        switch (period) {
            case "daily":
            case "range":
            picker.element.on('hide.daterangepicker', function(ev, instance) {
                /*
                * i selected the third row because there was month
                * that date 1 on second row, so i feel to keep it save
                * with choosing the third row
                */
                // var td = $(instance.container).find('.table-condensed tbody tr:nth-child(3) td:first-child');
                /*
                * the setTimeout have on purpose to delay calling trigger
                * event when choosing date on third row, if you not provide
                * the timeout, it will throw error maximum callstack
                */
                setTimeout(function() {
                    /*
                    * on the newer version to pick some date was changed into event
                    * mousedown
                    */
                    // td.trigger('mousedown');
                    /*
                    * this was optional, because in my case i need send date with
                    * starting day with 1 to keep backend neat
                    */
                    // instance.setStartDate(instance.startDate.date(1));
                    // instance.setEndDate(instance.endDate.date(1));
                    // alert("this is start " + instance.startDate.format("DD MMM YYYY"));
                    // alert("this is end " + instance.endDate.format("DD MMM YYYY"));
                    startDate=instance.startDate
                    endDate=instance.endDate

                    // filterData()
                    // endDate;
                }, 1);
            })
            break;
        

            case "monthly":
            case "yearly":
            picker.element.on('hide.daterangepicker', function(ev, instance) {
                /*
                * i selected the third row because there was month
                * that date 1 on second row, so i feel to keep it save
                * with choosing the third row
                */
                var td = $(instance.container).find('.table-condensed tbody tr:nth-child(3) td:first-child');
                /*
                * the setTimeout have on purpose to delay calling trigger
                * event when choosing date on third row, if you not provide
                * the timeout, it will throw error maximum callstack
                */
                setTimeout(function() {
                    /*
                    * on the newer version to pick some date was changed into event
                    * mousedown
                    */
                    td.trigger('mousedown');
                    /*
                    * this was optional, because in my case i need send date with
                    * starting day with 1 to keep backend neat
                    */
                    instance.setStartDate(instance.startDate.date(1));
                    instance.setEndDate(instance.endDate.date(1));
                    // alert("this is start " + instance.startDate.format("DD MMM YYYY"));
                    // alert("this is end " + instance.endDate.format("DD MMM YYYY"));
                    startDate=instance.startDate
                    endDate=instance.endDate

                    // filterData()
                    // endDate;
                }, 1);
            })
            break;
            default:
            break;
        }
    }
    var pickerInit = function(picker, period) {
        /*
        * personally, i'm not using the jquery method,
        * instead i'm using the constructor itself with purpose
        * to detect if already initialized before, it will detached
        * and destroy the picker element and reinitialize the picker
        */
        if (picker instanceof daterangepicker) {
            element = picker.element;
            picker.element.off('.daterangepicker');
            picker.element.removeData();
            picker.container.remove();
            picker = element;
        }
        datepicker = new daterangepicker(picker, pickerConfig(period));
        pickerEvent(datepicker, period);
        /* this was needed to make some change to what we see on the dom */
        datepicker.container.addClass(period);
    }

    $("#date_changer").change(function() {
        pickerInit(datepicker, this.value);
    });
});
</script>
@stop