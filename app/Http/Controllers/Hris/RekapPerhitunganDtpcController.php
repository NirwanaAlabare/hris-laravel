<?php

namespace App\Http\Controllers\Hris;

use App\Exports\RekapPerhitunganDtpcExport;
use App\Http\Controllers\AdminBaseController;
use App\Models\MasterDataAbsenKehadiran;
use App\Models\RekapPerhitunganDTPC;
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
 * Class RekapPerhitunganDtpcController
 * @package App\Http\Controllers\Hris
 */
class RekapPerhitunganDtpcController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Rekap Perhitungan DTPC';
    }

    public function index()
    {
        return View::make('hris/rekapperhitungandtpc', $this->data);
    }

    public function ajax_rekap(Request $request)
    {

        $daterange1 = explode(" - ", $request->daterange1);
        $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
        $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));

        if(request()->ajax()) {            
        $columns = array(
            0 => 'uuid',
            1 => 'tanggal_berjalan',
            2 => 'enroll_id',
            3 => 'nik',
            4 => 'employee_name',
            5 => 'status_staff',
            6 => 'status_aktif',
            7 => 'status_absen',
            8 => 'sub_dept_name',
            9 => 'gaji_pokok',
            10 => 'gaji_menit',
            11 => 'jumlah_menit_absen_dt',
            12 => 'jumlah_menit_absen_pc',
            13 => 'jumlah_menit_absen_dtpc',
            14 => 'potongan_dt_rupiah',
            15 => 'potongan_pc_rupiah',
            16 => 'potongan_dtpc_rupiah'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = 0;
        $totalFiltered = 0;

        if(empty($request->input('search.value')))
        {
            if(empty($daterange1)) {
                $query =  DB::select(DB::raw('
                SELECT
                    a.uuid,
                    a.tanggal_berjalan,
                    a.enroll_id,
                    b.nik,
                    a.employee_name,
                    b.status_staff,
                    b.status_aktif,
                    a.status_absen,
                    c.sub_dept_name,
                    FORMAT(a.gaji_pokok, 2) gaji_pokok,
                    FORMAT(a.gaji_menit, 2) gaji_menit,
                    FORMAT(SUM(a.jumlah_menit_absen_dt), 0) jumlah_menit_absen_dt,
                    FORMAT(SUM(a.jumlah_menit_absen_pc), 0) jumlah_menit_absen_pc,
                    FORMAT(SUM(a.jumlah_menit_absen_dtpc), 0) jumlah_menit_absen_dtpc,
                    FORMAT(SUM(a.potongan_dt_rupiah), 2) potongan_dt_rupiah,
                    FORMAT(SUM(a.potongan_pc_rupiah), 2) potongan_pc_rupiah,
                    FORMAT(SUM(a.potongan_dtpc_rupiah), 2) potongan_dtpc_rupiah
                FROM
                    rekap_perhitungan_dtpc a
                    LEFT JOIN employee_atribut b ON (a.enroll_id = b.enroll_id)
                    LEFT JOIN department_all c	ON (b.sub_dept_id = c.sub_dept_id)
                GROUP BY
                    a.tanggal_berjalan,
                    a.enroll_id
                ORDER BY ' . $order . ' ' . $dir . '
                LIMIT ' . $limit . '
                OFFSET ' . $start . '	                
                            '));

                $totalData = RekapPerhitunganDTPC::count();
                $totalFiltered = $totalData;  
            } else {
                $query =  DB::select(DB::raw('
                SELECT
                    a.uuid,
                    a.tanggal_berjalan,
                    a.enroll_id,
                    b.nik,
                    b.employee_name,
                    b.status_staff,
                    b.status_aktif,
                    a.status_absen,
                    c.sub_dept_name,
                    FORMAT(a.gaji_pokok, 2) gaji_pokok,
                    FORMAT(a.gaji_menit, 2) gaji_menit,
                    FORMAT(SUM(a.jumlah_menit_absen_dt), 0) jumlah_menit_absen_dt,
                    FORMAT(SUM(a.jumlah_menit_absen_pc), 0) jumlah_menit_absen_pc,
                    FORMAT(SUM(a.jumlah_menit_absen_dtpc), 0) jumlah_menit_absen_dtpc,
                    FORMAT(SUM(a.potongan_dt_rupiah), 2) potongan_dt_rupiah,
                    FORMAT(SUM(a.potongan_pc_rupiah), 2) potongan_pc_rupiah,
                    FORMAT(SUM(a.potongan_dtpc_rupiah), 2) potongan_dtpc_rupiah
                FROM
                    rekap_perhitungan_dtpc a
                    LEFT JOIN employee_atribut b ON (a.enroll_id = b.enroll_id)
                    LEFT JOIN department_all c	ON (b.sub_dept_id = c.sub_dept_id)
                WHERE
                    a.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                GROUP BY
                    a.tanggal_berjalan,
                    a.enroll_id
                ORDER BY ' . $order . ' ' . $dir . '
                LIMIT ' . $limit . '
                OFFSET ' . $start . '	                
                                        '));

                $totalData = RekapPerhitunganDTPC::whereRaw('
                    tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                ')
                ->count();
                $totalFiltered = $totalData;  
            }

        } else {
            $search = $request->input('search.value');

            $query =  DB::select(DB::raw('
            SELECT
                a.uuid,
                a.tanggal_berjalan,
                a.enroll_id,
                b.nik,
                b.employee_name,
                b.status_staff,
                b.status_aktif,
                a.status_absen,
                c.sub_dept_name,
                FORMAT(a.gaji_pokok, 2) gaji_pokok,
                FORMAT(a.gaji_menit, 2) gaji_menit,
                FORMAT(SUM(a.jumlah_menit_absen_dt), 0) jumlah_menit_absen_dt,
                FORMAT(SUM(a.jumlah_menit_absen_pc), 0) jumlah_menit_absen_pc,
                FORMAT(SUM(a.jumlah_menit_absen_dtpc), 0) jumlah_menit_absen_dtpc,
                FORMAT(SUM(a.potongan_dt_rupiah), 2) potongan_dt_rupiah,
                FORMAT(SUM(a.potongan_pc_rupiah), 2) potongan_pc_rupiah,
                FORMAT(SUM(a.potongan_dtpc_rupiah), 2) potongan_dtpc_rupiah
            FROM
                rekap_perhitungan_dtpc a
                LEFT JOIN employee_atribut b ON (a.enroll_id = b.enroll_id)
                LEFT JOIN department_all c	ON (b.sub_dept_id = c.sub_dept_id)
            WHERE
                a.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                and (UPPER(a.enroll_id) LIKE UPPER("%' . $search . '%")
                or UPPER(b.nik) LIKE UPPER("%' . $search . '%")
                or UPPER(b.employee_name) LIKE UPPER("%' . $search . '%")
                or UPPER(c.sub_dept_name) LIKE UPPER("%' . $search . '%")
                or UPPER(b.status_staff) LIKE UPPER("%' . $search . '%")
                or UPPER(b.status_aktif) LIKE UPPER("%' . $search . '%")
                or UPPER(a.status_absen) LIKE UPPER("%' . $search . '%"))
            GROUP BY
                a.tanggal_berjalan,
                a.enroll_id
            ORDER BY ' . $order . ' ' . $dir . '
            LIMIT ' . $limit . '
            OFFSET ' . $start . '	                
                        '));

            $totalData =  DB::select(DB::raw('
            SELECT
                count(*)
            FROM
                rekap_perhitungan_dtpc a
                LEFT JOIN employee_atribut b ON (a.enroll_id = b.enroll_id)
                LEFT JOIN department_all c	ON (b.sub_dept_id = c.sub_dept_id)
            WHERE
                a.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                and (UPPER(a.enroll_id) LIKE UPPER("%' . $search . '%")
                or UPPER(b.nik) LIKE UPPER("%' . $search . '%")
                or UPPER(b.employee_name) LIKE UPPER("%' . $search . '%")
                or UPPER(c.sub_dept_name) LIKE UPPER("%' . $search . '%")
                or UPPER(b.status_staff) LIKE UPPER("%' . $search . '%")
                or UPPER(b.status_aktif) LIKE UPPER("%' . $search . '%")
                or UPPER(a.status_absen) LIKE UPPER("%' . $search . '%"))
            GROUP BY
                a.tanggal_berjalan,
                a.enroll_id
                        '));
            $totalFiltered = $totalData;                            

        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $nestedData['uuid'] = $q->uuid;
                $nestedData['tanggal_berjalan'] = $q->tanggal_berjalan;
                $nestedData['enroll_id'] = $q->enroll_id;
                $nestedData['nik'] = $q->nik;
                $nestedData['employee_name'] = $q->employee_name;
                $nestedData['status_staff'] = $q->status_staff;
                $nestedData['status_aktif'] = $q->status_aktif;
                $nestedData['status_absen'] = $q->status_absen;
                $nestedData['sub_dept_name'] = $q->sub_dept_name;
                $nestedData['jumlah_menit_absen_dt'] = $q->jumlah_menit_absen_dt;
                $nestedData['jumlah_menit_absen_pc'] = $q->jumlah_menit_absen_pc;
                $nestedData['jumlah_menit_absen_dtpc'] = $q->jumlah_menit_absen_dtpc;
                $nestedData['gaji_pokok'] = $q->gaji_pokok;
                $nestedData['gaji_menit'] = $q->gaji_menit;
                $nestedData['potongan_dt_rupiah'] = $q->potongan_dt_rupiah;
                $nestedData['potongan_pc_rupiah'] = $q->potongan_pc_rupiah;
                $nestedData['potongan_dtpc_rupiah'] = $q->potongan_dtpc_rupiah;
        
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

    public function ajax_exportexcel(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');
        $daterange1 = explode(" - ", $request->input('daterange1'));

        $fileName = 'RekapPerhitunganDTPC_' . time() . '.xlsx';
        return (new RekapPerhitunganDtpcExport)->exportParams($daterange1)->download($fileName);

    }

}
