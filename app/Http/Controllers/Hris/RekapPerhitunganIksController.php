<?php

namespace App\Http\Controllers\Hris;

use App\Exports\RekapPerhitunganIksExport;
use App\Http\Controllers\AdminBaseController;
use App\Models\MasterDataAbsenKehadiran;
use App\Models\RekapPerhitunganIKS;
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
class RekapPerhitunganIksController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Rekap Perhitungan IKS';
    }

    public function index()
    {
        return View::make('hris/rekapperhitunganiks', $this->data);
    }

    public function ajax_rekap(Request $request)
    {

        $daterange1 = explode(" - ", $request->daterange1);
        $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
        $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));

        if(request()->ajax()) {            
        $columns = array(
            0 => 'uuid',
            1 => 'nomor_form_perizinan',
            2 => 'tanggal_berjalan',
            3 => 'enroll_id',
            4 => 'nik',
            5 => 'employee_name',
            6 => 'status_staff',
            7 => 'status_aktif',
            8 => 'sub_dept_name',
            9 => 'time_mulai_ijin',
            10 => 'time_akhir_ijin',
            11 => 'jam_mulai_istirahat',
            12 => 'jam_selesai_istirahat',
            13 => 'lama_istirahat_menit',
            14 => 'lama_ijin_menit',
            15 => 'lama_ijin_jam',
            16 => 'gaji_pokok',
            17 => 'gaji_harian',
            18 => 'gaji_menit',
            19 => 'potongan_iks_rupiah'
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
                    a.nomor_form_perizinan,
                    a.tanggal_berjalan,
                    cast(a.enroll_id as int) enroll_id,
                    b.nik,
                    a.employee_name,
                    b.status_staff,
                    b.status_aktif,
                    c.sub_dept_name,
                    SUBSTR(a.time_mulai_ijin , 1, 5 ) time_mulai_ijin,
                    SUBSTR(a.time_akhir_ijin , 1, 5 ) time_akhir_ijin,
                    SUBSTR(a.jam_mulai_istirahat , 1, 5 ) jam_mulai_istirahat,
                    SUBSTR(a.jam_selesai_istirahat , 1, 5 ) jam_selesai_istirahat,
                    FORMAT(a.lama_istirahat_menit , 0 ) lama_istirahat_menit,
                    FORMAT(a.lama_ijin_menit , 0 ) lama_ijin_menit,
                    SUBSTR(a.lama_ijin_jam , 1, 5 ) lama_ijin_jam,
                    FORMAT( a.gaji_pokok, 2 ) gaji_pokok,
                    FORMAT( a.gaji_harian, 2 ) gaji_harian,
                    FORMAT( a.gaji_menit, 2 ) gaji_menit,
                    FORMAT( a.potongan_iks_rupiah, 2 ) potongan_iks_rupiah,
                    a.absen_alasan,
                    a.created_at,
                    a.updated_at
                FROM
                    rekap_perhitungan_iks a
                    LEFT JOIN employee_atribut b ON ( a.enroll_id = b.enroll_id )
                    LEFT JOIN department_all c ON ( b.sub_dept_id = c.sub_dept_id ) 
                GROUP BY
                    a.tanggal_berjalan,
                    a.enroll_id
                ORDER BY ' . $order . ' ' . $dir . '
                LIMIT ' . $limit . '
                OFFSET ' . $start . '	                
                            '));

                $totalData = RekapPerhitunganIKS::count();
                $totalFiltered = $totalData;  
            } else {
                $query =  DB::select(DB::raw('
                SELECT
                    a.uuid,
                    a.nomor_form_perizinan,
                    a.tanggal_berjalan,
                    cast(a.enroll_id as int) enroll_id,
                    b.nik,
                    a.employee_name,
                    b.status_staff,
                    b.status_aktif,
                    c.sub_dept_name,
                    SUBSTR(a.time_mulai_ijin , 1, 5 ) time_mulai_ijin,
                    SUBSTR(a.time_akhir_ijin , 1, 5 ) time_akhir_ijin,
                    SUBSTR(a.jam_mulai_istirahat , 1, 5 ) jam_mulai_istirahat,
                    SUBSTR(a.jam_selesai_istirahat , 1, 5 ) jam_selesai_istirahat,
                    FORMAT(a.lama_istirahat_menit , 0 ) lama_istirahat_menit,
                    FORMAT(a.lama_ijin_menit , 0 ) lama_ijin_menit,
                    SUBSTR(a.lama_ijin_jam , 1, 5 ) lama_ijin_jam,
                    FORMAT( a.gaji_pokok, 2 ) gaji_pokok,
                    FORMAT( a.gaji_harian, 2 ) gaji_harian,
                    FORMAT( a.gaji_menit, 2 ) gaji_menit,
                    FORMAT( a.potongan_iks_rupiah, 2 ) potongan_iks_rupiah,
                    a.absen_alasan,
                    a.created_at,
                    a.updated_at
                FROM
                    rekap_perhitungan_iks a
                    LEFT JOIN employee_atribut b ON ( a.enroll_id = b.enroll_id )
                    LEFT JOIN department_all c ON ( b.sub_dept_id = c.sub_dept_id ) 
                GROUP BY
                    a.tanggal_berjalan,
                    a.enroll_id
                ORDER BY ' . $order . ' ' . $dir . '
                LIMIT ' . $limit . '
                OFFSET ' . $start . '	                
                                        '));

                $totalData = RekapPerhitunganIKS::whereRaw('
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
                a.nomor_form_perizinan,
                a.tanggal_berjalan,
                cast(a.enroll_id as int) enroll_id,
                b.nik,
                a.employee_name,
                b.status_staff,
                b.status_aktif,
                c.sub_dept_name,
                SUBSTR(a.time_mulai_ijin , 1, 5 ) time_mulai_ijin,
                SUBSTR(a.time_akhir_ijin , 1, 5 ) time_akhir_ijin,
                SUBSTR(a.jam_mulai_istirahat , 1, 5 ) jam_mulai_istirahat,
                SUBSTR(a.jam_selesai_istirahat , 1, 5 ) jam_selesai_istirahat,
                FORMAT(a.lama_istirahat_menit , 0 ) lama_istirahat_menit,
                FORMAT(a.lama_ijin_menit , 0 ) lama_ijin_menit,
                SUBSTR(a.lama_ijin_jam , 1, 5 ) lama_ijin_jam,
                FORMAT( a.gaji_pokok, 2 ) gaji_pokok,
                FORMAT( a.gaji_harian, 2 ) gaji_harian,
                FORMAT( a.gaji_menit, 2 ) gaji_menit,
                FORMAT( a.potongan_iks_rupiah, 2 ) potongan_iks_rupiah,
                a.absen_alasan,
                a.created_at,
                a.updated_at
            FROM
                rekap_perhitungan_iks a
                LEFT JOIN employee_atribut b ON ( a.enroll_id = b.enroll_id )
                LEFT JOIN department_all c ON ( b.sub_dept_id = c.sub_dept_id ) 
            WHERE
                a.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                and (UPPER(a.enroll_id) LIKE UPPER("%' . $search . '%")
                or UPPER(a.nomor_form_perizinan) LIKE UPPER("%' . $search . '%")
                or UPPER(b.nik) LIKE UPPER("%' . $search . '%")
                or UPPER(b.employee_name) LIKE UPPER("%' . $search . '%")
                or UPPER(c.sub_dept_name) LIKE UPPER("%' . $search . '%")
                or UPPER(b.status_staff) LIKE UPPER("%' . $search . '%")
                or UPPER(a.absen_alasan) LIKE UPPER("%' . $search . '%")
                or UPPER(b.status_aktif) LIKE UPPER("%' . $search . '%") )
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
                rekap_perhitungan_iks a
                LEFT JOIN employee_atribut b ON ( a.enroll_id = b.enroll_id )
                LEFT JOIN department_all c ON ( b.sub_dept_id = c.sub_dept_id ) 
            WHERE
                a.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                and (UPPER(a.enroll_id) LIKE UPPER("%' . $search . '%")
                or UPPER(b.nik) LIKE UPPER("%' . $search . '%")
                or UPPER(a.nomor_form_perizinan) LIKE UPPER("%' . $search . '%")
                or UPPER(b.employee_name) LIKE UPPER("%' . $search . '%")
                or UPPER(c.sub_dept_name) LIKE UPPER("%' . $search . '%")
                or UPPER(b.status_staff) LIKE UPPER("%' . $search . '%")
                or UPPER(a.absen_alasan) LIKE UPPER("%' . $search . '%")
                or UPPER(b.status_aktif) LIKE UPPER("%' . $search . '%") )
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
                $nestedData['nomor_form_perizinan'] = $q->nomor_form_perizinan;
                $nestedData['employee_name'] = $q->employee_name;
                $nestedData['status_staff'] = $q->status_staff;
                $nestedData['status_aktif'] = $q->status_aktif;
                $nestedData['sub_dept_name'] = $q->sub_dept_name;
                $nestedData['time_mulai_ijin'] = $q->time_mulai_ijin;
                $nestedData['time_akhir_ijin'] = $q->time_akhir_ijin;
                $nestedData['jam_mulai_istirahat'] = $q->jam_mulai_istirahat;
                $nestedData['jam_selesai_istirahat'] = $q->jam_selesai_istirahat;
                $nestedData['lama_istirahat_menit'] = $q->lama_istirahat_menit;
                $nestedData['lama_ijin_menit'] = $q->lama_ijin_menit;
                $nestedData['lama_ijin_jam'] = $q->lama_ijin_jam;
                $nestedData['absen_alasan'] = $q->absen_alasan;
                $nestedData['gaji_pokok'] = $q->gaji_pokok;
                $nestedData['gaji_harian'] = $q->gaji_harian;
                $nestedData['gaji_menit'] = $q->gaji_menit;
                $nestedData['potongan_iks_rupiah'] = $q->potongan_iks_rupiah;
                $nestedData['created_at'] = substr($q->created_at, 0, 10) . " " . substr($q->created_at, 11, 5);
                $nestedData['updated_at'] = substr($q->updated_at, 0, 10) . " " . substr($q->updated_at, 11, 5);
        
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

        $fileName = 'RekapPerhitunganIKS_' . time() . '.xlsx';
        return (new RekapPerhitunganIksExport)->exportParams($daterange1)->download($fileName);

    }

}
