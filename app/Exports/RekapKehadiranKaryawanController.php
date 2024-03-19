<?php

namespace App\Http\Controllers\Hris;

use App\Exports\RekapKehadiranKaryawanExport;
use App\Http\Controllers\AdminBaseController;
use App\Models\RekapKehadiranKaryawan;
use App\Models\EmployeeAtribut;
use App\Models\DepartmentAll;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Datatables;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Class RekapPerhitunganLemburController
 * @package App\Http\Controllers\Hris
 */
class RekapKehadiranKaryawanController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Rekap Kehadiran Karyawan';
    }

    public function index()
    {
        $this->periode_payroll = $this->ajax_getperiodepayroll();
        return View::make('hris/rekapkehadirankaryawan', $this->data);
    }

    public function ajax_getperiodepayroll()
    {
        $query =  RekapKehadiranKaryawan::selectRaw('periode_payroll')
                                    ->groupby('periode_payroll')
                                    ->orderby('periode_payroll', 'desc')
                                    ->get();
        return $query;

    }

    public function ajax_getemployeselectstaff(Request $request)
    {
        $status_staff = $request->status_staff;

        if($status_staff) {
            $query =  EmployeeAtribut::
            selectRaw('enroll_id no_pin, nik, employee_name,
                            concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
            ->whereRaw('
             status_staff = "' . $status_staff . '"
            ')
            ->groupby('enroll_id')
            ->orderby('employee_name', 'asc')
            ->get();

        } else {
            $query =  EmployeeAtribut::
            selectRaw('enroll_id no_pin, nik, employee_name,
                            concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
            ->groupby('enroll_id')
            ->orderby('employee_name', 'asc')
            ->get();
        }

        return $query;

    }

    public function ajax_rekap(Request $request)
    {

        $periode_payroll = $request->periode_payroll;

        $status_staff = $request->status_staff;
        if($status_staff) {
            $whereStatusStaff = ' AND status_staff = "' . $status_staff . '" ';
        } else {
            $whereStatusStaff = '';
        }

        $searchData = $request->searchData;
        if($searchData) {
            $whereSearchData = ' AND (upper( enroll_id ) LIKE upper( "%' . $searchData . '%" ) OR upper( employee_name ) LIKE upper( "%' . $searchData . '%" ) OR upper( sub_dept_name ) LIKE upper( "%' . $searchData . '%" ) OR upper( status_aktif ) LIKE upper( "%' . $searchData . '%" ) ) ';
        } else {
            $whereSearchData = '';
        }

        $query =  RekapKehadiranKaryawan::selectRaw('
                    uuid, kode_rekap_kehadiran, periode_payroll, periode_tahun, periode_bulan,
                    concat(periode_tahun, "-", periode_bulan) periode_tahun_bulan,
                    enroll_id, nik, employee_name, site_nirwana_id, site_nirwana_name, department_id
                    department_name, sub_dept_id, sub_dept_name, substr(join_date, 1, 10) join_date,
                    substr(tanggal_resign, 1, 10) tanggal_resign, status_aktif, status_staff, kehadiran_iby,
                    kehadiran_itb, kehadiran_lby, kehadiran_lsm, kehadiran_dt, kehadiran_pc, kehadiran_dtpc,
                    kehadiran_m, kehadiran_r, kehadiran_tk, kehadiran_ok, total_kehadiran, total_kehadiran_net,
                    jumlah_hari, jumlah_hari_kerja, (total_kehadiran - jumlah_hari) selisih,
                    operator, substr(created_at, 1, 19) created_at,
                    substr(updated_at, 1, 19) updated_at, substr(deleted_at, 1, 19) deleted_at
            ')
            ->whereRaw('
                periode_payroll = "' . $periode_payroll . '"
                ' . $whereStatusStaff . '
                ' . $whereSearchData . '
            ')
            ->orderBy('employee_name','asc')
            ->get();

        return Response()->json($query);

    }

    public function ajax_exportexcel(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');

        $periode_payroll = $request->input('periode_payroll');

        $status_staff = $request->input('status_staff');
        if(!$status_staff) { $status_staff = ""; }

        $searchData = $request->input('searchData');
        if(!$searchData) { $searchData = ""; }

        $fileName = 'RekapKehadiranKaryawan_' . time() . '.xlsx';
        return (new RekapKehadiranKaryawanExport)->exportParams($periode_payroll, $status_staff, $searchData)->download($fileName);

    }

}
