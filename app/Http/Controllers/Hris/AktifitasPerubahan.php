<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use App\Models\DataClosingPayroll;
use App\Models\RekapPerhitunganPayroll;
use App\Models\ActivityLog;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Datatables;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\DepartmentAll;

/**
 * Class AktifitasPerubahan
 * @package App\Http\Controllers\Hris
 */
class AktifitasPerubahan extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'DATA CLOSING PAYROLL';
    }

    public function index()
    {
        $this->month = date('Y-m');
        $today = date('d');

        $minMonth = $this->month;
        if ($today >= 26 && $today <= 31) {
            $minMonth = date('Y-m', strtotime(date('Y-m-01') . ' +1 month'));
        }

        return View::make('hris/aktifitasperubahan', compact('minMonth'), $this->data);
    }

    public function ajax_data(Request $request){
        $periode_payroll = request()->periode_payroll;
        $periode_tahun_payroll = substr($periode_payroll,0,4);
        $periode_bulan_payroll = substr($periode_payroll,5,6);
        $department_id = request()->department_id;
        $sub_dept_id = request()->sub_dept_id;
        $status_staff = request()->status_staff;
        $periode_umk = request()->periode_umk;
        if(request()->ajax()) {
            $columns = array(
                0 => 'activity_log_id',
                1 => 'action_by_id',
                2 => 'action_by_name',
                3 => 'log_name',
                4 => 'table_name',
                5 => 'record_id',
                6 => 'action',
                7 => 'old_data',
                8 => 'new_data',
                9 => 'created_at',
                10 => 'updated_at',
            );
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            $totalData = 0;
            $totalFiltered = 0;
            $query =  ActivityLog::offset($start)->limit($limit)->orderBy('created_at')->get();
            $totalData = ActivityLog::count();
            $totalFiltered = $totalData;
            $data = array();
            foreach ($query as $q)
            {
                $nestedData['activity_log_id'] = (int)$q->activity_log_id;
                $nestedData['action_by_id'] = $q->action_by_id;
                $nestedData['action_by_name'] = $q->action_by_name;
                $nestedData['log_name'] = $q->log_name;
                $nestedData['table_name'] = $q->table_name;
                $nestedData['record_id'] = $q->record_id;
                $nestedData['action'] = $q->action;
                $nestedData['old_data'] = $q->old_data;
                $nestedData['new_data'] = $q->new_data;
                $nestedData['created_at'] = $q->created_at;
                $nestedData['updated_at'] = $q->updated_at;

                $data[] = $nestedData;

            }
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }

}
