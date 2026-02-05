<?php

namespace App\Http\Controllers\Admin;



use App\Events\AttendanceEvent;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\Attendance\UpdateRequest;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Holiday;
use App\Exports\AttendanceExport;
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
use Jmrashed\Zkteco\Lib\ZKTeco;


/**
 * Class AttendancesController
 * @package App\Http\Controllers\Admin
 */
class AttendancesController extends AdminBaseController
{


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
        $query = EmployeeAtribut::query(); // Ganti dengan Model Anda

        // Logika Filter
        if ($request->has('department') && $request->department != '') {
            $query->where('department_name', $request->department);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status_aktif', $request->status);
        }

        return DataTables::of($query)
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
        $sql = "SELECT  ID, MachineAlias, IP FROM Machines ";
        $query = \odbc_exec($conn, $sql);

        ini_set('max_execution_time', 0);
        set_time_limit(0);

        while ($row = \odbc_fetch_array($query)) {
            $ip = $row['IP'];

            // Coba Method 1: Library Default (ZKTeco Connect)
            // $zk = new ZKTeco($ip, 4370);
            // $status = @$zk->connect();

            // Coba Method 2: Jika Library gagal, cek via UDP Socket manual
            // if (!$status) {
            //     $status = $this->checkUdpStatus($ip);
            // }

            // Coba Method 3: Jika masih gagal, cek via ICMP (Ping)
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

    public function deleteEmployeeFromMachine(Request $request)
    {
        $request->validate([
            'enroll_ids' => 'required|array',
            'machine_ids' => 'required|array'
        ]);

        $enrollIds = $request->enroll_ids;
        $ips = $request->machine_ids;
        $results = [];

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
                        $zk->removeUser($enrollId); // Hapus User & Finger di mesin
                        // Note: clearAttendance() hanya jika ingin hapus SEMUA log di mesin
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

        // dd($results);
        return response()->json([
            'status' => true,
            'message' => 'Karyawan dihapus dari Database dan Mesin',
            'details' => $results
        ]);
    }
}
