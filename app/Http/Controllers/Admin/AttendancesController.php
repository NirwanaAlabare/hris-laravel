<?php

namespace App\Http\Controllers\Admin;



use App\Events\AttendanceEvent;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\Attendance\UpdateRequest;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Holiday;
use App\Exports\AttendanceExport;
use App\Exports\AttendanceLogExport;
use App\Exports\AttendanceLogFormattedExport;
use App\Models\AttMachine;
use App\Models\EmployeeAtribut;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
// use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Jmrashed\Zkteco\Lib\ZKTeco;
use Maatwebsite\Excel\Facades\Excel;


/**
 * Class AttendancesController
 * @package App\Http\Controllers\Admin
 */
class AttendancesController extends AdminBaseController
{

    private $zkApi = 'http://10.10.5.60:1122';
    // private $zkApi = 'http://127.0.0.1:1122';
    // private $garmentApi = 'http://localhost:8080';
    private $garmentApi = 'http://10.10.5.2:8080';

    public function __construct()
    {
        parent::__construct();
        $this->attendanceOpen = 'active open';
        $this->pageTitle = 'Attendance';
    }

    public function index()
    {
        $this->attendances = Attendance::all();
        $this->viewAttendanceActive = 'active';

        $this->date = date('Y-m-d');
        return View::make('admin.attendances.index', $this->data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxAttendanceList()
    {
        $leaves = Attendance::absentEveryEmployee();
        $result = Employee::select('employeeID', 'profileImage', 'fullName', 'status')
            ->active();

        return datatables()->eloquent($result)
            ->filter(function ($query) {
                if (request()->search['value']) {
                    $query->where('employeeID', 'LIKE', '%' . request()->search['value'] . '%')
                        ->orWhere('email', 'LIKE', '%' . request()->search['value'] . '%')
                        ->orWhere('fullName', 'LIKE', '%' . request()->search['value'] . '%');
                }
            })
            ->editColumn('id', function ($row) {
                return $row->employeeID;
            })
            ->editColumn('profileImage', function ($row) {
                return '<img src="' . $row->profile_image_url . '" height="50px" />';
            })
            ->addColumn('last_absent', function ($row) {
                return $row->lastAbsent($row->employeeID);
            })
            ->addColumn('leaves', function ($row) use ($leaves) {
                $leaveData = '<table>';


                foreach ($leaves[$row->employeeID] as $index => $leave) {
                    $leaveData .= '<tr>
                                        <td>
                                            <strong> ' . ucfirst($index) . ' &nbsp;&nbsp;</strong>
                                        </td>
                                        <td>
                                            <strong> ' . $leave . ' </strong>
                                        </td>
                                    </tr>';
                }

                $leaveData .= '</table>';
                return $leaveData;
            })
            ->editColumn('status', function ($row) {

                if ($row->status == 'active') {
                    return '<span class="label label-sm label-success">' . $row->status . '</span>';
                } else {
                    return '<span class="label label-sm label-danger">' . $row->status . '</span>';
                }
            })
            ->addColumn('action', function ($row) {
                return '<a class="btn btn-sm purple" href="' . route('admin.attendances.show', $row->employeeID) . '">
                                        <i class="fa fa-eye"></i> View
                                    </a>';
            })
            ->escapeColumns(['action', 'status', 'leaves', 'profileImage'])
            ->removeColumn('profile_image_url')
            ->make(false);
    }


    /**
     * @return \Illuminate\Http\RedirectResponse
     * This method is called when we mark the attendance and redirects to edit page.
     */

    public function create()
    {
        $date = (\request()->date != '') ? \request()->date : date('Y-m-d');
        $date = date('Y-m-d', strtotime($date));

        $attendance_count = Attendance::where('date', '=', $date)->count();
        $employee_count = Employee::active()->count();

        if ($employee_count == $attendance_count) {
            if (!Session::get('success')) {
                Session::flash('success', '<strong>Attendance already marked</strong>');
            }
        } else {
            Session::forget('success');
        }

        return Redirect::route('admin.attendances.edit', $date);
    }

    /**
     * Display the specified attendance
     */
    public function show($id)
    {
        $this->viewAttendanceActive = 'active';

        $this->employee = Employee::where('employeeID', '=', $id)->get()->first();
        $this->attendance = Attendance::where('employeeID', '=', $id)
            ->where(function ($query) {
                $query->where('application_status', '=', 'approved')
                    ->orwhere('application_status', '=', null)
                    ->orwhere('status', '=', 'present');
            })->get();
        $this->holidays = Holiday::all();
        $this->employeeslist = Employee::pluck('fullName', 'employeeID');


        return View::make('admin.attendances.show', $this->data);
    }

    /**
     * Show the form for editing the specified attendance.
     */
    public function edit($date)
    {
        $attendanceArray = [];
        $this->attendance = Attendance::where('date', '=', $date)->get()->toArray();

        $this->todays_holidays = Holiday::where('date', '=', $date)->get()->first();

        foreach ($this->attendance as $attend) {
            $attendanceArray[$attend['employeeID']] = $attend;
        }

        $this->date = $date;
        $this->attendanceArray = $attendanceArray;


        $this->leaveTypes = Attendance::leaveTypesEmployees();
        $this->leaveTypeWithoutHalfDay = Attendance::leaveTypesEmployees('half day');
        $this->employees = Employee::active()->get();

        return View::make('admin.attendances.edit', $this->data);
    }

    /**
     * Update the specified attendance in storage.
     */
    public function update(UpdateRequest $request, $date)
    {
        $input = Request::all();

        foreach ($input['employees'] as $employeeID) {

            $user = Attendance::firstOrCreate([
                'employeeID' => $employeeID,
                'date' => $date,
            ]);
            if ($user->application_status != 'approved' || ($user->application_status == 'approved' && isset($input['checkbox'][$employeeID]) == 'on')) {
                $update = Attendance::find($user->id);
                $update->status = (isset($input['checkbox'][$employeeID]) == 'on') ? 'present' : 'absent';
                $update->leaveType = (isset($input['checkbox'][$employeeID]) == 'on') ? null : $input['leaveType'][$employeeID];
                $update->halfDayType = ((!isset($input['checkbox'][$employeeID]) == 'on') && ($input['leaveType'][$employeeID] == 'half day')) ? $input['leaveTypeWithoutHalfDay'][$employeeID] : null;
                $update->reason = (isset($input['checkbox'][$employeeID]) == 'on') ? '' : $input['reason'][$employeeID];
                $update->application_status = null;
                $update->updated_by = Auth::guard('admin')->user()->email;
                $update->save();
            }
        }

        $this->date = date('d M Y', strtotime($date));

        if ($this->setting->attendance_notification == 1) {

            $employees = Employee::select('id', 'email', 'fullName')->active()->get();

            foreach ($employees as $employee) {
                $this->employee_name = $employee->fullName;
                event(new AttendanceEvent($employee, $this->date));
            }
        }

        Session::flash('success', date('d M Y', strtotime($date)) . 'successfully Updated');
        return Redirect::route('admin.attendances.edit', $date);
    }

    public function export()
    {
        $fileName = 'Attendance-' . time() . '.xlsx';
        if (request()->filled('s')) {
            return (new AttendanceExport(request()->input('s')))->download($fileName);
        }
        return (new AttendanceExport)->download($fileName);
    }

    public function report()
    {

        $month = Request::get('month');
        $year = Request::get('year');
        $employeeID = Request::get('employeeID');

        $firstDay = $year . '-' . $month . '-01';


        $presentCount = Attendance::countPresentDays($month, $year, $employeeID);

        $totalDays = date('t', strtotime($firstDay));

        $holidaycount = count(DB::select(DB::raw('select * from holidays where MONTH(date)=' . $month)));
        $workingDays = $totalDays - $holidaycount;


        $percentage = ($presentCount / $workingDays) * 100;
        $output['success'] = 'success';
        $output['presentByWorking'] = $presentCount . '/' . $workingDays;

        $output['attendancePerReport'] = number_format((float)$percentage, 2, '.', '');
        return Response::json($output, 200);
    }

    /**
     * Remove the specified attendance from storage.
     */
    public function destroy($id)
    {
        Attendance::destroy($id);
        return Redirect::route('admin.attendances.index');
    }



    public function ajaxEmployeeList(Request $request)
    {
        $query = EmployeeAtribut::select([
            'employee_id',
            'enroll_id',
            'employee_name',
            'department_name',
            'status_aktif',
            'isDeletedInMachine'
        ]);

        // Logika Filter Biasa (Database Level)
        if ($request->has('department') && $request->department != '') {
            $query->where('department_name', $request->department);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status_aktif', $request->status);
        }

        // Ambil data menjadi Collection agar bisa dilooping PHP
        $employees = $query->get();

        // Logika Filter Status Mesin (PHP Level)
        if ($request->has('status_machine') && $request->status_machine != '') {
            $statusCari = $request->status_machine; // 'DELETED', 'NOT DELETED', atau 'QUEUED'

            $employees = $employees->filter(function ($row) use ($statusCari) {
                $logData = json_decode($row->isDeletedInMachine, true);

                // Jika data kosong
                if (!$logData) return $statusCari == 'NOT DELETED';

                $machineLogs = $logData['machine_logs'] ?? [];

                // 1. Kumpulkan semua IP unik yang pernah masuk antrian
                $allTargetIps = [];
                $anyQueueFound = false;

                foreach ($machineLogs as $batch) {
                    foreach ($batch['status'] ?? [] as $s) {
                        $allTargetIps[] = $s['ip'];
                        if (($s['status'] ?? '') === 'QUEUED') {
                            $anyQueueFound = true;
                        }
                    }
                }
                $allTargetIps = array_unique($allTargetIps);

                if (empty($allTargetIps)) return $statusCari == 'NOT DELETED';

                // 2. Cek status SUCCESS untuk setiap IP
                $successCount = 0;
                foreach ($allTargetIps as $ip) {
                    $found = false;

                    // Lapis 1: Root
                    if (isset($logData[$ip]) && end($logData[$ip])['status'] === 'SUCCESS') {
                        $found = true;
                    }

                    // Lapis 2: Nested di logs
                    if (!$found) {
                        foreach ($machineLogs as $log) {
                            foreach ($log['status'] ?? [] as $s) {
                                if (($s['ip'] ?? '') === $ip && ($s['status'] ?? '') === 'SUCCESS') {
                                    $found = true;
                                    break 2;
                                }
                            }
                        }
                    }

                    if ($found) $successCount++;
                }

                // 3. Penentuan Status Final
                $isFullyDeleted = ($successCount === count($allTargetIps));
                $isQueued = (!$isFullyDeleted && $anyQueueFound);

                if ($statusCari == 'DELETED') {
                    return $isFullyDeleted;
                } elseif ($statusCari == 'QUEUED') {
                    return $isQueued;
                } else { // NOT DELETED
                    // Not Deleted berarti tidak sedang di-queue DAN belum sukses semua
                    return !$isFullyDeleted && !$anyQueueFound;
                }
            });
        }

        // Gunakan DataTables::of() dengan Collection ($employees)
        return DataTables::of($employees)
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="row-check" data-enroll_id="' . $row->enroll_id . '">';
            })
            ->rawColumns(['checkbox', 'status_aktif'])
            ->make(true);
    }




    public function indexDeleteEmployee()
    {
        $conn = \odbc_connect("att_hris", "server", "alabare");
        if (!$conn) abort(500, 'ODBC connection failed');

        $machines = [];
        $sql = "SELECT  ID, MachineAlias, IP FROM Machines -- where ip='192.168.0.245'";
        $query = \odbc_exec($conn, $sql);

        ini_set('max_execution_time', 0);
        set_time_limit(0);

        while ($row = \odbc_fetch_array($query)) {
            $ip = $row['IP'];

            // Coba Method 1: Library Default (ZKTeco Connect)
            // $zk = new ZKTeco($ip, 4370);
            // $status = @$zk->connect();

            // // Coba Method 2: Jika Library gagal, cek via UDP Socket manual
            // if (!$status) {
            //     $status = $this->checkUdpStatus($ip);
            // }

            // // Coba Method 3: Jika masih gagal, cek via ICMP (Ping)
            // if (!$status) {
            $status = $this->pingMachine($ip);
            // }

            $row['is_online'] = $status;
            // if ($status) @$zk->disconnect();

            $machines[] = $row;
        }

        \odbc_close($conn);

        $setting = Setting::firstOrFail();
        $pageTitle = 'Hapus Karyawan';
        $loggedAdmin = Auth::guard('admin')->user();
        $departments = EmployeeAtribut::select('department_name')
            ->whereNotNull('department_name')
            ->distinct()
            ->orderBy('department_name', 'asc')
            ->get();
        // dd($machines);
        return view(
            'admin.attendances.delete-employee-machines',
            compact('machines', 'setting', 'pageTitle', 'loggedAdmin', 'departments')
        );
    }
    /**
     * Method 3: Simple ICMP Ping
     */
    private function pingMachine($ip)
    {
        $str = PHP_OS == 'WINNT' ? "ping -n 1 -w 500 $ip" : "ping -c 1 -W 1 $ip";
        exec($str, $output, $status);
        return $status === 0;
    }
    /**
     * Helper to check if the machine port (4370) is reachable
     */
    private function checkMachineStatus($ip, $port = 4370)
    {
        // 1 second timeout to prevent page hang
        $connection = @fsockopen($ip, $port, $errno, $errstr, 1);
        if ($connection) {
            fclose($connection);
            return true;
        }
        return false;
    }

    private function checkUdpStatus($ip, $port = 4370)
    {
        // Cek apakah fungsi socket tersedia sebelum dipanggil
        if (!function_exists('socket_create')) {
            // Jika tidak ada, fallback ke fsockopen sederhana
            $fp = @fsockopen("udp://$ip", $port, $errno, $errstr, 1);
            if (!$fp) return false;
            fclose($fp);
            return true;
        }

        $socket = @socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        @socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 1, 'usec' => 0));

        $hex = "\x50\x50\x82\x7d\x13\x00\x00\x00\x01\x00\x00\x00\x00\x00\x00\x00";
        @socket_sendto($socket, $hex, strlen($hex), 0, $ip, $port);

        $buf = null;
        $from = null;
        $port_res = null;
        $result = @socket_recvfrom($socket, $buf, 1024, 0, $from, $port_res);

        @socket_close($socket);
        return $result !== false;
    }

    public function _deleteEmployeeFromMachine(Request $request)
    {
        $request->validate([
            'enroll_ids' => 'required|array',
            'machine_ids' => 'required|array'
        ]);

        $enrollIds = $request->enroll_ids;
        $ips = $request->machine_ids;
        $results = [];
        $successEnrollIds = [];

        // --- BAGIAN 1: HAPUS DI DATABASE ODBC ---
        $conn = @\odbc_connect("att_hris", "server", "alabare");
        if ($conn) {
            foreach ($enrollIds as $enrollId) {
                // 1. Cari USERID internal berdasarkan Badgenumber (ID Mesin)
                $sqlLookup = "SELECT USERID FROM USERINFO WHERE Badgenumber = '$enrollId'";
                $queryLookup = \odbc_exec($conn, $sqlLookup);
                $row = \odbc_fetch_array($queryLookup);

                if ($row) {
                    $internalId = $row['USERID'];

                    // 2. Hapus Log Absensi menggunakan USERID internal
                    \odbc_exec($conn, "DELETE FROM CHECKINOUT WHERE USERID = $internalId");

                    // 3. Hapus Sidik Jari/Face Template menggunakan USERID internal
                    \odbc_exec($conn, "DELETE FROM TEMPLATE WHERE USERID = $internalId");

                    // 4. Hapus User Info (Data Utama)
                    \odbc_exec($conn, "DELETE FROM USERINFO WHERE USERID = $internalId");

                    $results[] = ["id" => $enrollId, "db_status" => "Full Wipe Success"];
                } else {
                    $results[] = ["id" => $enrollId, "db_status" => "Not found in ODBC"];
                }
            }
            \odbc_close($conn);
        }

        // --- BAGIAN 2: HAPUS DI MESIN FISIK ---
        set_time_limit(0);
        foreach ($ips as $ip) {
            $zk = new ZKTeco($ip, 4370);
            try {
                if ($zk->connect()) {
                    $zk->disableDevice();
                    foreach ($enrollIds as $enrollId) {
                        //$zk->removeUser($enrollId); // Hapus User & Finger di mesin
                        // Note: clearAttendance() hanya jika ingin hapus SEMUA log di mesin
                        if ($zk->removeUser($enrollId)) {
                            $successEnrollIds[] = $enrollId; // Tandai untuk update DB Laravel
                        }
                    }
                    $zk->enableDevice();
                    $zk->disconnect();
                    $results[] = ["ip" => $ip, "status" => "Machine Cleaned"];
                } else {
                    $results[] = ["ip" => $ip, "status" => "Connection Failed"];
                }
            } catch (\Exception $e) {
                $results[] = ["ip" => $ip, "status" => "Error: " . $e->getMessage()];
            }
        }

        if (!empty($successEnrollIds)) {
            // Hapus duplikasi ID jika ada
            $uniqueIds = array_unique($successEnrollIds);

            // Update kolom isDeletedInMachine di tabel Laravel
            // Asumsi kolom enroll_id di tabel adalah mapping dari Badgenumber
            EmployeeAtribut::whereIn('enroll_id', $uniqueIds)
                ->update(['isDeletedInMachine' => true]);
        }

        // dd($results);
        return response()->json([
            'status' => true,
            'message' => 'Karyawan dihapus dari Database dan Mesin',
            'details' => $results
        ]);
    }
    public function adeleteEmployeeFromMachine(Request $request)
    {
        // -----------------------
        // 1. Validasi input
        // -----------------------
        $request->validate([
            'enroll_ids'  => 'required|array',
            'machine_ids' => 'required|array',
        ]);

        $enrollIds = $request->enroll_ids;
        $machineIps = $request->machine_ids;

        // -----------------------
        // 2. Mapping MachineAlias dari ODBC
        // -----------------------
        $conn = @\odbc_connect("att_hris", "server", "alabare");
        $machineMap = [];
        if ($conn) {
            $sql = "SELECT IP, MachineAlias FROM Machines";
            $query = \odbc_exec($conn, $sql);
            while ($row = \odbc_fetch_array($query)) {
                $machineMap[$row['IP']] = $row['MachineAlias'];
            }
            \odbc_close($conn);
        }

        $results = [];

        // -----------------------
        // 3. Hapus di ODBC Attendance (CHECKINOUT, TEMPLATE, USERINFO)
        // -----------------------
        if ($conn = @\odbc_connect("att_hris", "server", "alabare")) {
            foreach ($enrollIds as $enrollId) {
                $sqlLookup = "SELECT USERID FROM USERINFO WHERE Badgenumber = '$enrollId'";
                $queryLookup = \odbc_exec($conn, $sqlLookup);
                $row = \odbc_fetch_array($queryLookup);

                if ($row) {
                    $internalId = $row['USERID'];
                    \odbc_exec($conn, "DELETE FROM CHECKINOUT WHERE USERID = $internalId");
                    \odbc_exec($conn, "DELETE FROM TEMPLATE WHERE USERID = $internalId");
                    \odbc_exec($conn, "DELETE FROM USERINFO WHERE USERID = $internalId");

                    $results[] = [
                        'enroll_id' => $enrollId,
                        'db_status' => 'Full Wipe Success'
                    ];
                } else {
                    $results[] = [
                        'enroll_id' => $enrollId,
                        'db_status' => 'Not found in ODBC'
                    ];
                }
            }
            \odbc_close($conn);
        }

        // -----------------------
        // 4. Hapus di Mesin via Python API
        // -----------------------
        foreach ($enrollIds as $enrollId) {
            $statusPerMachine = [];

            foreach ($machineIps as $ip) {
                // Panggil Python API untuk hapus user di mesin
                $response = Http::timeout(120)->post($this->zkApi . '/delete-user', [
                    'ip' => [$ip],       // Python API butuh array
                    'enroll_id' => $enrollId
                ]);

                $status = 'failed';
                if ($response->ok()) {
                    $json = $response->json();
                    // Python API: result per IP di json['result'][0]
                    $status = $json['result'][0] ?? 'failed';
                }

                $statusPerMachine[$ip] = $status;

                $results[] = [
                    'machine_ip' => ($machineMap[$ip] ?? null) . " ($ip)",
                    'machine_alias' => $machineMap[$ip] ?? null,
                    'enroll_id' => $enrollId,
                    'status' => $status
                ];
            }

            // -----------------------
            // 5. Update Employee (JSON + isDeletedInMachine)
            // -----------------------
            $this->updateEmployeeAfterDelete($enrollId, $statusPerMachine);
        }

        return response()->json([
            'status' => true,
            'message' => 'Delete finished',
            'data' => $results
        ]);
    }

    public function bdeleteEmployeeFromMachine(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'enroll_ids'  => 'required|array',
            'machine_ids' => 'required|array',
        ]);

        $enrollIds = $request->enroll_ids;
        $machineIps = $request->machine_ids;
        $results = [];

        // 2. Ambil Mapping Mesin (Langsung di sini agar tidak error)
        $machineMap = [];
        $connOdbc = @\odbc_connect("att_hris", "server", "alabare");
        if ($connOdbc) {
            $q = \odbc_exec($connOdbc, "SELECT IP, MachineAlias FROM Machines");
            while ($r = \odbc_fetch_array($q)) {
                $machineMap[$r['IP']] = $r['MachineAlias'];
            }
            \odbc_close($connOdbc);
        }

        foreach ($enrollIds as $enrollId) {
            $logActions = []; // Untuk mencatat detail aksi per user
            $internalUid = null;

            // --- AKSI A: OPERASI DATABASE ODBC ---
            try {
                $connOdbc = @\odbc_connect("att_hris", "server", "alabare");
                if (!$connOdbc) throw new \Exception("Koneksi ODBC Gagal");

                // Step 1: Cari USERID
                $sqlLookup = "SELECT USERID FROM USERINFO WHERE Badgenumber = '$enrollId'";
                $queryLookup = \odbc_exec($connOdbc, $sqlLookup);
                $row = \odbc_fetch_array($queryLookup);

                if ($row) {
                    $internalUid = $row['USERID'];
                    $logActions[] = [
                        'step' => 'ODBC_LOOKUP',
                        'action' => "Mencari UID untuk Badge $enrollId",
                        'status' => 'SUCCESS',
                        'detail' => "UID ditemukan: $internalUid"
                    ];

                    // Step 2: Hapus data di 3 tabel
                    @\odbc_exec($connOdbc, "DELETE FROM CHECKINOUT WHERE USERID = $internalUid");
                    @\odbc_exec($connOdbc, "DELETE FROM TEMPLATE WHERE USERID = $internalUid");
                    @\odbc_exec($connOdbc, "DELETE FROM USERINFO WHERE USERID = $internalUid");

                    $logActions[] = [
                        'step' => 'ODBC_WIPE',
                        'action' => "Membersihkan tabel CHECKINOUT, TEMPLATE, USERINFO",
                        'status' => 'SUCCESS'
                    ];
                } else {
                    $logActions[] = [
                        'step' => 'ODBC_LOOKUP',
                        'action' => "Cari UID",
                        'status' => 'NOT_FOUND',
                        'detail' => "Badge $enrollId tidak ada di DB ODBC"
                    ];
                }
                \odbc_close($connOdbc);
            } catch (\Exception $e) {
                $logActions[] = [
                    'step' => 'ODBC_ERROR',
                    'action' => 'Database Operation',
                    'status' => 'FAILED',
                    'detail' => $e->getMessage()
                ];
            }

            // --- AKSI B: OPERASI MESIN ---
            $statusForLaravelDb = [];
            foreach ($machineIps as $ip) {
                $machineSteps = [];
                $targetId = $enrollId;

                try {
                    $machineSteps[] = [
                        'step' => 'PREPARE_API',
                        'action' => "Target ID: $targetId",
                        'method' => $internalUid ? 'UID_MODE' : 'BADGE_MODE'
                    ];

                    $response = Http::timeout(45)->post($this->zkApi . '/delete-user', [
                        'ip' => [$ip],
                        'enroll_id' => $targetId
                    ]);

                    if ($response->ok()) {
                        $json = $response->json();
                        $rawResult = $json['result'][0] ?? 'No response';

                        // Cek sukses/gagal dari string response
                        $isSuccess = (str_contains(strtolower((string)$rawResult), 'success') || str_contains(strtolower((string)$rawResult), 'deleted'));

                        $machineSteps[] = [
                            'step' => 'EXECUTE_DELETE',
                            'action' => "Hapus di mesin $ip",
                            'status' => $isSuccess ? 'SUCCESS' : 'FAILED',
                            'raw' => $rawResult
                        ];
                    } else {
                        $machineSteps[] = [
                            'step' => 'API_COMMUNICATION',
                            'status' => 'HTTP_ERROR',
                            'detail' => "Code: " . $response->status()
                        ];
                    }
                } catch (\Exception $e) {
                    $machineSteps[] = [
                        'step' => 'CONNECTION',
                        'status' => 'CRITICAL_ERROR',
                        'detail' => $e->getMessage()
                    ];
                }

                // Gabungkan semua histori aksi ke dalam satu log per IP
                $finalEntry = [
                    'timestamp' => now()->toDateTimeString(),
                    'user_id' => $enrollId,
                    'odbc_history' => $logActions,
                    'machine_history' => $machineSteps,
                    'is_clean' => (isset($isSuccess) && $isSuccess)
                ];

                $statusForLaravelDb[$ip][] = $finalEntry;

                $results[] = [
                    'enroll_id' => $enrollId,
                    'machine' => ($machineMap[$ip] ?? $ip),
                    'status' => (isset($isSuccess) && $isSuccess) ? 'Success' : 'Failed'
                ];
            }

            // Simpan ke kolom isDeletedInMachine di database Laravel
            $this->updateEmployeeAfterDelete($enrollId, $statusForLaravelDb);
        }

        return response()->json([
            'status' => true,
            'message' => 'Proses selesai',
            'results' => $results
        ]);
    }

    /**
     * Menghapus Karyawan secara menyeluruh (Audit Trail Ready).
     * Proses mencakup:
     * 1. Mapping IP ke Nama Mesin (ODBC)
     * 2. Pembersihan Tabel Database (ODBC)
     * 3. Penghapusan User di Hardware (API Python)
     * * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cdeleteEmployeeFromMachine(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'enroll_ids'  => 'required|array',
            'machine_ids' => 'required|array',
        ]);

        $enrollIds = $request->enroll_ids;
        $machineIps = $request->machine_ids;
        $results = [];

        // 2. Inisialisasi Koneksi ODBC & Mapping Mesin (Internal Lookup)
        $machineMap = [];
        $dsn = "att_hris";
        $user = "server";
        $pass = "alabare";

        $connOdbc = @\odbc_connect($dsn, $user, $pass);
        if ($connOdbc) {
            $q = \odbc_exec($connOdbc, "SELECT IP, MachineAlias FROM Machines");
            while ($r = \odbc_fetch_array($q)) {
                $machineMap[$r['IP']] = $r['MachineAlias'];
            }
            // Jangan ditutup dulu karena akan dipakai untuk hapus USERINFO nanti
        }

        foreach ($enrollIds as $enrollId) {
            // Objek Audit Trail Utama
            $auditTrail = [
                'meta' => [
                    'executed_by' => auth()->user()->id ?? 'System',
                    'actor_name'  => auth()->user()->name ?? 'System',
                    'timestamp'   => now()->format('Y-m-d H:i:s.u'),
                ],
                'database_logs' => [],
                'machine_logs'  => []
            ];

            $internalUid = null;

            // --- TAHAP 1: EKSEKUSI DATABASE ODBC ---
            try {
                if (!$connOdbc) {
                    throw new \Exception("Koneksi ODBC tidak tersedia saat proses penghapusan.");
                }

                // Cari USERID (Internal UID di MS Access/SQL Server ZK)
                $sqlLookup = "SELECT USERID FROM USERINFO WHERE Badgenumber = '$enrollId'";
                $queryLookup = \odbc_exec($connOdbc, $sqlLookup);
                $userRow = \odbc_fetch_array($queryLookup);

                if ($userRow) {
                    $internalUid = $userRow['USERID'];

                    // Daftar tabel yang harus dibersihkan
                    $tables = ['CHECKINOUT', 'TEMPLATE', 'USERINFO'];
                    foreach ($tables as $table) {
                        $sqlDelete = "DELETE FROM $table WHERE USERID = $internalUid";
                        $exec = @\odbc_exec($connOdbc, $sqlDelete);

                        $auditTrail['database_logs'][] = [
                            'table'  => $table,
                            'query'  => $sqlDelete,
                            'status' => $exec ? 'SUCCESS' : 'FAILED',
                            'error'  => $exec ? null : \odbc_errormsg($connOdbc)
                        ];
                    }
                } else {
                    $auditTrail['database_logs'][] = [
                        'status' => 'NOT_FOUND',
                        'detail' => "Badge $enrollId tidak ditemukan di database ODBC."
                    ];
                }
            } catch (\Exception $e) {
                $auditTrail['database_logs'][] = [
                    'status' => 'CRITICAL_ERROR',
                    'detail' => $e->getMessage()
                ];
            }

            // --- TAHAP 2: EKSEKUSI MESIN FINGERPRINT ---
            foreach ($machineIps as $ip) {
                $isSuccess = false;
                $deviceName = $machineMap[$ip] ?? $ip;
                $apiDetail = null;
                $httpCode = null;

                try {
                    // DEBUG: Log the request
                    \Log::info("Sending delete request to ZK API", [
                        'url' => $this->zkApi . '/delete-users',
                        'payload' => [
                            'ip' => [$ip],
                            'enroll_ids' => [$enrollId]
                        ]
                    ]);

                    // Request ke API Python Proxy
                    $response = Http::timeout(45)->post($this->zkApi . '/delete-users', [
                        'ip' => [$ip],
                        'enroll_ids' => [$enrollId]
                    ]);

                    $httpCode = $response->status();

                    // DEBUG: Log full response
                    \Log::info("ZK API Response", [
                        'status_code' => $httpCode,
                        'body' => $response->body()
                    ]);

                    if ($response->ok()) {
                        $data = $response->json();

                        // FIX 1: Find the result for THIS specific IP
                        $rawResult = null;
                        foreach ($data['results'] ?? [] as $result) {
                            if (($result['machine_ip'] ?? '') === $ip) {
                                $rawResult = $result;
                                break;
                            }
                        }

                        // FIX 2: If no result found, check why
                        if (!$rawResult) {
                            $rawResult = [
                                'error' => 'No result found for this IP',
                                'available_results' => $data['results'] ?? []
                            ];
                        }

                        // PENANGANAN AMAN: Cek tipe data agar tidak "Array to String Conversion"
                        if (is_array($rawResult)) {
                            $isSuccess = ($rawResult['deleted'] ?? false) === true;
                            $apiDetail = $rawResult; // Tetap simpan sebagai array (JSON)
                        } else {
                            $isSuccess = str_contains(strtolower((string)$rawResult), 'success');
                            $apiDetail = (string)$rawResult;
                        }

                        $auditTrail['machine_logs'][] = [
                            'ip'           => $ip,
                            'device_name'  => $deviceName,
                            'status'       => $isSuccess ? 'SUCCESS' : 'FAILED',
                            'raw_response' => $apiDetail,
                            'http_code'    => $httpCode
                        ];
                    } else {
                        // FIX 3: Get the actual error message from response
                        $errorBody = $response->body();
                        $errorJson = json_decode($errorBody, true);
                        $errorMessage = $errorJson['detail'] ?? $errorBody;

                        throw new \Exception("HTTP Error {$httpCode}: {$errorMessage}");
                    }
                } catch (\Exception $e) {
                    $auditTrail['machine_logs'][] = [
                        'ip'          => $ip,
                        'device_name' => $deviceName,
                        'status'      => 'ERROR',
                        'detail'      => $e->getMessage()
                    ];
                }

                // Catat hasil ringkas untuk response frontend
                $results[] = [
                    'enroll_id' => $enrollId,
                    'machine'   => $deviceName,
                    'status'    => $isSuccess ? 'Success' : 'Failed'
                ];
            }

            // --- TAHAP 3: PERSISTENSI LOG ---
            // Simpan log lengkap ke database Laravel (Audit Trail per karyawan)
            // Laravel akan otomatis mengkonversi array $auditTrail ke JSON
            $this->updateEmployeeAfterDelete($enrollId, $auditTrail);
        }

        // Tutup koneksi ODBC di akhir loop
        if ($connOdbc) {
            @\odbc_close($connOdbc);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Batch delete processed',
            'results' => $results
        ]);
    }
    public function deleteEmployeeFromMachine(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'enroll_ids'  => 'required|array',
            'machine_ids' => 'required|array',
        ]);

        $enrollIds = $request->enroll_ids;
        $machineIps = $request->machine_ids;
        $results = [];

        // 2. Inisialisasi Koneksi ODBC & Mapping Mesin
        $machineMap = [];
        $dsn = "att_hris";
        $user = "server";
        $pass = "alabare";

        $connOdbc = @\odbc_connect($dsn, $user, $pass);
        if ($connOdbc) {
            $q = \odbc_exec($connOdbc, "SELECT IP, MachineAlias FROM Machines");
            while ($r = \odbc_fetch_array($q)) {
                $machineMap[$r['IP']] = $r['MachineAlias'];
            }
        }

        // --- TAHAP 1: EKSEKUSI DATABASE ODBC (Keep this as is) ---
        foreach ($enrollIds as $enrollId) {
            $auditTrail = [
                'meta' => [
                    'executed_by' => auth()->user()->id ?? 'System',
                    'actor_name'  => auth()->user()->name ?? 'System',
                    'timestamp'   => now()->format('Y-m-d H:i:s.u'),
                ],
                'database_logs' => [],
                'machine_logs'  => []
            ];

            try {
                if ($connOdbc) {
                    $sqlLookup = "SELECT USERID FROM USERINFO WHERE Badgenumber = '$enrollId'";
                    $queryLookup = \odbc_exec($connOdbc, $sqlLookup);
                    $userRow = \odbc_fetch_array($queryLookup);

                    if ($userRow) {
                        $internalUid = $userRow['USERID'];
                        $tables = ['CHECKINOUT', 'TEMPLATE', 'USERINFO'];
                        foreach ($tables as $table) {
                            $sqlDelete = "DELETE FROM $table WHERE USERID = $internalUid";
                            $exec = @\odbc_exec($connOdbc, $sqlDelete);
                            $auditTrail['database_logs'][] = [
                                'table'  => $table,
                                'status' => $exec ? 'SUCCESS' : 'FAILED'
                            ];
                        }
                    }
                }
            } catch (\Exception $e) {
                $auditTrail['database_logs'][] = ['status' => 'CRITICAL_ERROR', 'detail' => $e->getMessage()];
            }

            // --- TAHAP 2: EKSEKUSI MESIN (BATCH OPTIMIZED) ---
            // INSTEAD OF LOOPING HERE, WE SEND EVERYTHING TO PYTHON ONCE
            // BUT TO KEEP YOUR $results FORMAT, WE PRE-POPULATE THEM
            foreach ($machineIps as $ip) {
                $deviceName = $machineMap[$ip] ?? $ip;
                $results[] = [
                    'enroll_id' => $enrollId,
                    'machine'   => $deviceName,
                    'status'    => 'Processing' // We change status to 'Processing' since it's now Async
                ];

                // Add a placeholder log to audit trail
                $auditTrail['machine_logs'][] = [
                    'ip' => $ip,
                    'status' => 'QUEUED',
                    'detail' => 'Command sent to background worker'
                ];
            }

            // Save Audit Trail
            $this->updateEmployeeAfterDelete($enrollId, $auditTrail);
        }

        // --- SEND ONE BIG BATCH TO PYTHON ---
        try {
            // This request will return in milliseconds because Python is now Async
            Http::timeout(5)->post($this->zkApi . '/delete-users', [
                'ip' => $machineIps,          // Pass all IPs
                'enroll_ids' => $enrollIds    // Pass all IDs
            ]);
        } catch (\Exception $e) {
            \Log::error("Failed to trigger ZK Background Worker: " . $e->getMessage());
        }

        if ($connOdbc) {
            @\odbc_close($connOdbc);
        }

        // Response remains exactly as you requested
        return response()->json([
            'status'  => true,
            'message' => 'Batch delete processed',
            'results' => $results
        ]);
    }
    /**
     * Update Employee JSON deleted_in_machines + isDeletedInMachine
     */

    protected function updateEmployeeAfterDelete(string $enrollId, array $statusPerMachine)
    {
        // $statusPerMachine = [
        //     '192.168.1.100' => 'Deleted Successfully',
        //     '192.168.1.101' => 'Connection Timeout'
        // ];
        // Ambil Employee
        $employee = EmployeeAtribut::where('enroll_id', $enrollId)->first();
        if (!$employee) {
            \Log::warning("Employee not found: {$enrollId}");
            return;
        }
        // Ambil JSON lama
        $oldData = json_decode($employee->isDeletedInMachine ?? '[]', true) ?: [];

        foreach ($statusPerMachine as $ip => $status) {
            $oldData[$ip][] = [
                'status' => $status,
                'time' => now()->toDateTimeString()
            ];
        }

        DB::table('employee_atribut')
            ->where('employee_id', $employee->employee_id)
            ->where('enroll_id', $employee->enroll_id)
            ->update([
                'isDeletedInMachine' => json_encode($oldData)
            ]);
    }


    public function _checkEmployeeOnMachine(Request $request)
    {
        // Hindari timeout PHP karena proses penarikan data user cukup berat
        set_time_limit(0);

        $request->validate([
            'enroll_ids' => 'required|array',
            'machine_ids' => 'required|array'
        ]);

        $results = [];
        $enrollIds = $request->enroll_ids;

        foreach ($request->machine_ids as $ip) {
            \Log::info("=== Checking Machine: $ip ===");

            // 1. Pre-check: Fast TCP Scan (Port 4370)
            $fp = @fsockopen($ip, 4370, $errno, $errstr, 1);
            if (!$fp) {
                \Log::warning("Machine $ip is Offline (Port 4370 Closed)");
                foreach ($enrollIds as $enrollId) {
                    $results[] = [
                        'enroll_id' => $enrollId,
                        'machine_ip' => $ip,
                        'exists' => false,
                        'error' => "Offline: $errstr"
                    ];
                }
                continue;
            }
            fclose($fp);

            try {
                $zk = new ZKTeco($ip, 4370);

                \Log::info("Attempting ZK Connection to $ip...");
                // Menggunakan @ untuk meredam output error/notice dari library
                if (@$zk->connect()) {
                    \Log::info("Connected to $ip. Fetching user list...");
                    $zk->disableDevice();
                    $users = @$zk->getUser();
                    $zk->enableDevice();
                    @$zk->disconnect();

                    $userCollection = collect($users);
                    \Log::info("Fetched " . $userCollection->count() . " users from $ip.");

                    foreach ($enrollIds as $enrollId) {
                        $exists = $userCollection->contains(function ($user) use ($enrollId) {
                            return (isset($user['userid']) && (string)$user['userid'] === (string)$enrollId) ||
                                (isset($user['badgenumber']) && (string)$user['badgenumber'] === (string)$enrollId);
                        });

                        $results[] = [
                            'enroll_id' => $enrollId,
                            'machine_ip' => $ip,
                            'exists' => $exists
                        ];
                    }
                } else {
                    \Log::error("ZK Connection Failed to $ip (Handshake Rejected)");
                    throw new \Exception("Handshake Failed. Check Comm Key/Network.");
                }
            } catch (\Throwable $e) {
                \Log::error("Error on Machine $ip: " . $e->getMessage());
                foreach ($enrollIds as $enrollId) {
                    $results[] = [
                        'enroll_id' => $enrollId, // Pastikan variabel ini benar
                        'machine_ip' => $ip,
                        'exists' => false,
                        'error' => "Internal Error: " . $e->getMessage()
                    ];
                }
            }
        }
        dd($results);
        return response()->json(['data' => $results]);
    }

    public function checkEmployeeOnMachine(Request $request)
    {
        $conn = \odbc_connect("att_hris", "server", "alabare");
        if (!$conn) abort(500, 'ODBC connection failed');

        $request->validate([
            'enroll_ids' => 'required|array',
            'machine_ids' => 'required|array'
        ]);

        $response = Http::timeout(60)->post($this->zkApi . '/check-users', [
            'ip' => $request->machine_ids,
            'enroll_ids' => $request->enroll_ids
        ]);

        if (!$response->ok()) {
            return response()->json([
                'status' => false,
                'message' => 'ZK API error',
                'raw' => $response->body()
            ], 500);
        }

        $json = $response->json();

        // ✅ ambil data dengan aman
        $data = $json['results'] ?? [];

        if (!is_array($data)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid API response',
                'raw' => $json
            ], 500);
        }

        // ✅ Data sudah berupa array of objects dari API Python, langsung gunakan

        // --- Ambil mapping MachineAlias dari database ODBC ---
        $conn = @\odbc_connect("att_hris", "server", "alabare");
        $machineMap = [];
        if ($conn) {
            $sql = "SELECT IP, MachineAlias FROM Machines";
            $query = \odbc_exec($conn, $sql);
            while ($row = \odbc_fetch_array($query)) {
                $machineMap[$row['IP']] = $row['MachineAlias'];
            }
            \odbc_close($conn);
        }


        $results = [];
        foreach ($data as $item) {
            $machine_ip = $item['machine_ip'] ?? null;
            $machineAlias = $machineMap[$machine_ip] ?? null;
            $results[] = [
                'machine_ip' => $machineAlias . " (" . $item['machine_ip'] . ")",
                'enroll_id' => $item['enroll_id'] ?? null,
                'exists' => isset($item['exists']) ? (bool)$item['exists'] : false
            ];
        }

        return response()->json([
            'status' => true,
            'data' => $results
        ]);
    }



    private function fillError(&$results, $ids, $ip, $msg)
    {
        foreach ($ids as $id) {
            $results[] = ['enroll_id' => $id, 'machine_ip' => $ip, 'exists' => false, 'error' => $msg];
        }
    }

    public function handleHardwareCallback(Request $request)
    {
        $type = $request->input('type'); // "CHECK" or "DELETE"
        $allResults = $request->input('data');

        \Log::info("ZK Webhook Received: $type");

        if (is_array($allResults)) {

            // --- 1. HANDLE DATABASE UPDATES (For DELETE only) ---
            if ($type === 'DELETE') {
                $groupedResults = [];
                foreach ($allResults as $item) {
                    $enrollId = $item['enroll_id'];
                    $ip = $item['machine_ip'];
                    $status = ($item['deleted'] ?? false) ? 'SUCCESS' : 'FAILED';

                    $groupedResults[$enrollId][$ip] = $status;
                }

                foreach ($groupedResults as $enrollId => $statusMap) {
                    $this->updateEmployeeAfterDelete($enrollId, $statusMap);
                }
            }

            // --- 2. HANDLE LIVE FEEDBACK (For both CHECK and DELETE) ---
            // This is what your JavaScript is waiting for!
            \Cache::put("zk_results_{$type}", $allResults, 300);

            \Log::info("Data for $type stored in Cache for Frontend Polling.");
        }

        return response()->json(['message' => 'Callback processed successfully'], 200);
    }

    public function getLatestResults(Request $request)
    {
        $type = $request->query('type', 'CHECK'); // Default to CHECK if not specified
        $cacheKey = "zk_results_{$type}";

        $data = \Cache::get($cacheKey);

        if ($data) {
            \Cache::forget($cacheKey);
            return response()->json(['status' => 'ready', 'data' => $data]);
        }

        return response()->json(['status' => 'waiting']);
    }

    public function indexAttendanceLogs()
    {
        $conn = \odbc_connect("att_hris", "server", "alabare");
        if (!$conn) abort(500, 'ODBC connection failed');

        $machines = [];
        $sql = "SELECT  ID, MachineAlias, IP FROM Machines -- where ip='192.168.0.245'";
        $query = \odbc_exec($conn, $sql);

        ini_set('max_execution_time', 0);
        set_time_limit(0);

        while ($row = \odbc_fetch_array($query)) {
            $ip = $row['IP'];
            $status = $this->pingMachine($ip);
            $row['is_online'] = $status;
            $machines[] = $row;
        }

        \odbc_close($conn);

        $setting = Setting::firstOrFail();
        $pageTitle = 'Attendance Logs';
        $loggedAdmin = Auth::guard('admin')->user();
        $departments = EmployeeAtribut::select('department_name')
            ->whereNotNull('department_name')
            ->distinct()
            ->orderBy('department_name', 'asc')
            ->get();
        // dd($machines);
        return view(
            'admin.attendances.attendance-logs',
            compact('machines', 'setting', 'pageTitle', 'loggedAdmin', 'departments')
        );
    }

    public function getLogEmployeeFromMachine(Request $request)
    {
        $request->validate([
            'machine_ids' => 'required|array'
        ]);

        $allResults = [];
        $errors = [];
        $totalRecords = 0;

        foreach ($request->machine_ids as $machineId) {
            try {
                $response = Http::timeout(60)->post($this->garmentApi . '/api/getAttLogs', [
                    'ip' => $machineId,
                ]);

                if (!$response->ok()) {
                    $errors[] = [
                        'machine_ip' => $machineId,
                        'status' => false,
                        'message' => 'Garment API error',
                        'raw' => $response->body()
                    ];
                    continue;
                }

                $json = $response->json();

                // Check if response has success status
                if (isset($json['status']) && $json['status'] === 'success') {
                    $records = $json['data'] ?? [];

                    if (is_array($records)) {
                        $totalRecords += count($records);

                        // Add machine_ip to each record for traceability
                        foreach ($records as $record) {
                            $allResults[] = array_merge($record, [
                                'source_machine_ip' => $machineId
                            ]);
                        }
                    }

                    // Optional: Log success message from API
                    if (isset($json['message'])) {
                        // You can log this or store in success summary
                    }
                } else {
                    // API returned error status
                    $errors[] = [
                        'machine_ip' => $machineId,
                        'status' => false,
                        'message' => $json['message'] ?? 'Unknown error from API',
                        'raw' => $json
                    ];
                }
            } catch (\Exception $e) {
                $errors[] = [
                    'machine_ip' => $machineId,
                    'status' => false,
                    'message' => 'Exception: ' . $e->getMessage()
                ];
            }
        }

        return response()->json([
            'status' => empty($errors) || !empty($allResults),
            'message' => $errors
                ? "Synced {$totalRecords} records from " . (count($request->machine_ids) - count($errors)) . " out of " . count($request->machine_ids) . " machines"
                : "Successfully synced {$totalRecords} records from all machines",
            'data' => $allResults,
            'errors' => $errors,
            'summary' => [
                'total_machines' => count($request->machine_ids),
                'successful_machines' => count($request->machine_ids) - count($errors),
                'failed_machines' => count($errors),
                'total_records' => $totalRecords
            ]
        ]);
    }

    public function ajaxEmployeeListAttendace(Request $request)
    {
        // dd($request->all());
        $query = DB::table('v_att')
            ->select('enroll_id', 'Nama', 'department_name', 'Tanggal', 'Jam_Masuk', 'Jam_Pulang','created_at')
            ->whereNotNull('enroll_id');

        // Apply filters
        if ($request->filled('department')) {
            $query->where('department_name', $request->department);
        }

        if ($request->filled('date_type')) {
            switch ($request->date_type) {
                case 'daily':
                    $query->whereRaw('date_format(PunchDate, "%Y-%m-%d") = ?', [$request->start_date]);
                    break;
                case 'yearly':
                    $query->whereRaw('YEAR(PunchDate) = ?', [$request->year]);
                    break;
                case 'monthly':
                    $query->whereRaw('MONTH(PunchDate) = ?', [$request->month])
                        ->whereRaw('YEAR(PunchDate) = ?', [$request->year]);
                    break;
                case 'range':
                    $query->whereRaw('date_format(PunchDate, "%Y-%m-%d") BETWEEN ? AND ?', [$request->start_date, $request->end_date]);
                    break;
            }
        }

        // Handle search from DataTables
        if ($request->filled('search') && !empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('enroll_id', 'LIKE', "%{$searchValue}%")
                    ->orWhere('Nama', 'LIKE', "%{$searchValue}%")
                    ->orWhere('department_name', 'LIKE', "%{$searchValue}%");
            });
        }

        return DataTables::of($query)
            ->addColumn('Jam_Masuk', function ($row) {
                return $row->Jam_Masuk ? date('H:i:s', strtotime($row->Jam_Masuk)) : '-';
            })
            ->addColumn('Jam_Pulang', function ($row) {
                return $row->Jam_Pulang ? date('H:i:s', strtotime($row->Jam_Pulang)) : '-';
            })
            ->make(true);
    }

    public function exportRawLogs(Request $request)
    {
        try {
            $allResults = [];
            $errors = [];

            $query = DB::table('attendance_logs');

            // Count total records before export
            $totalRecords = $query->count();

            if ($totalRecords == 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'No records found to export'
                ], 404);
            }

            // For large datasets, use chunking to avoid memory issues
            if ($totalRecords > 10000) {
                // Stream the export for large datasets
                return $this->streamLargeExport($query, $totalRecords);
            }

            // For smaller datasets, get all records
            $allResults = $query->get()->toArray();

            // Export to Excel using Laravel-Excel
            return Excel::download(new AttendanceLogExport($allResults), 'attendance_logs_Raw' . date('Y-m-d_His') . '.xlsx');
        } catch (\Exception $e) {
            \Log::error('Export failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }
    public function exportFormattedLogs(Request $request)
    {
        try {
            $allResults = [];
            $errors = [];

            $query = DB::table('v_att')
                ->select('*')
                ->whereNotNull('enroll_id');

            // Apply filters
            if ($request->filled('department')) {
                $query->where('department_name', $request->department);
            }

            if ($request->filled('date_type')) {
                switch ($request->date_type) {
                    case 'daily':
                        $query->whereRaw('date_format(PunchDate, "%Y-%m-%d") = ?', [$request->start_date]);
                        break;
                    case 'yearly':
                        $query->whereRaw('YEAR(PunchDate) = ?', [$request->year]);
                        break;
                    case 'monthly':
                        $query->whereRaw('MONTH(PunchDate) = ?', [$request->month])
                            ->whereRaw('YEAR(PunchDate) = ?', [$request->year]);
                        break;
                    case 'range':
                        $query->whereRaw('date_format(PunchDate, "%Y-%m-%d") BETWEEN ? AND ?', [$request->start_date, $request->end_date]);
                        break;
                }
            }

            // Handle search from DataTables
            if ($request->filled('search') && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $query->where(function ($q) use ($searchValue) {
                    $q->where('enroll_id', 'LIKE', "%{$searchValue}%")
                        ->orWhere('Nama', 'LIKE', "%{$searchValue}%")
                        ->orWhere('department_name', 'LIKE', "%{$searchValue}%");
                });
            }

            $query->orderBy('department_name', 'asc')->orderBy('Nama', 'asc')->orderBy('PunchDate', 'asc');

             // Count total records before export
             $totalRecords = $query->count();

             if ($totalRecords == 0) {
                 return response()->json([
                     'status' => false,
                     'message' => 'No records found to export'
                 ], 404);
             }
            // For smaller datasets, get all records
            $allResults = $query->get()->toArray();
            
            // Export to Excel using Laravel-Excel
            return Excel::download(new AttendanceLogFormattedExport($allResults), 'attendance_logs_Formatted' . date('Y-m-d_His') . '.xlsx');
        } catch (\Exception $e) {
            \Log::error('Export failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
