<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use App\Models\DataJadwalKerjaLog;
use App\Models\DepartmentAll;
use App\Models\EmployeeAtribut;
use App\Models\MasterDataAbsenKehadiran;
use App\Models\BgProcess;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Datatables;
use App\Exports\MasterDataAbsenKehadiranExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Class DataJadwalKerjaLogController
 * @package App\Http\Controllers\Hris
 */
class DataJadwalKerjaLogController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Inject Data Jadwal Kerja';
    }

    public function index()
    {
        $this->department = $this->ajax_getselectdepart();
        $this->selectemployee = $this->ajax_getallemployeeatribut();
        return View::make('hris/datajadwalkerjalog', $this->data);
    }

    private function ajax_getselectdepart()
    {
           // $query =  DepartmentAll::selectRaw('department_id,
        //                                    concat("[",CASE WHEN site_nirwana_id="ADR208"
        //                                                     THEN "NAG" WHEN site_nirwana_id="ADR210"
        //                                                     THEN "SGT" ELSE "UNKNOWN SITE" END,"] "
        //                                           ,department_name) department_name')
        //                          ->groupby('department_name')
        //                          ->orderby('department_name', 'asc')
        //                          ->get();

        $query =  DepartmentAll::selectRaw('department_id,department_name')
                       ->groupby('department_name')
                       ->orderby('department_name', 'asc')
                       ->get();

        return $query;

    }

    public function ajax_getallemployeeatribut()
    {
        $query =  EmployeeAtribut::selectRaw('enroll_id, nik, employee_name,
                                           concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                                    ->groupby('enroll_id')
                                    ->orderby('employee_name', 'asc')
                                    ->get();
        return $query;

    }

    public function ajax_getemployeselectdeptid(Request $request)
    {
        $department_id = $request->department_id;

        if($department_id) {

            $query =  EmployeeAtribut::
            selectRaw('enroll_id no_pin, nik, employee_name,
                            concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
            ->whereRaw('
                department_id = "' . $department_id . '"
            ')
            ->groupby('nik')
            ->orderby('employee_name', 'asc')
            ->get();

        } else {
            $query =  EmployeeAtribut::
            selectRaw('enroll_id no_pin, nik, employee_name,
                            concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
            ->groupby('nik')
            ->orderby('employee_name', 'asc')
            ->get();
        }
        
        //info('Query :' . $query);
        return $query;

    }

    public function ajax_datajadwalkerjalog(Request $request)
    {

        if(request()->ajax()) {

        $columns = array(
            0 => 'tanggal_berjalan',
            1 => 'nama_hari',
            2 => 'enroll_id',
            3 => 'nik',
            4 => 'employee_name',
            5 => 'department_name',
            6 => 'created_at'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = 0;
        $totalFiltered = 0;

        if(empty($request->input('search.value')))
        {
            $query =  DataJadwalKerjaLog::offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalData = DataJadwalKerjaLog::count();
            $totalFiltered = $totalData;  

        } else {
            $search = $request->input('search.value');

            $query =  DataJadwalKerjaLog::where('tanggal_berjalan','LIKE',"%{$search}%")
                            ->orWhere('nama_hari','LIKE',"%{$search}%")
                            ->orWhere('enroll_id','LIKE',"%{$search}%")
                            ->orWhere('nik','LIKE',"%{$search}%")
                            ->orWhere('employee_name','LIKE',"%{$search}%")
                            ->orWhere('department_name','LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalData = DataJadwalKerjaLog::where('tanggal_berjalan','LIKE',"%{$search}%")
                            ->orWhere('nama_hari','LIKE',"%{$search}%")
                            ->orWhere('enroll_id','LIKE',"%{$search}%")
                            ->orWhere('nik','LIKE',"%{$search}%")
                            ->orWhere('employee_name','LIKE',"%{$search}%")
                            ->orWhere('department_name','LIKE',"%{$search}%")
                            ->count();
            $totalFiltered = $totalData;                            

        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $nestedData['uuid'] = $q->uuid;
                $nestedData['tanggal_berjalan'] = $q->tanggal_berjalan;
                $nestedData['kode_hari'] = $q->kode_hari;
                $nestedData['nama_hari'] = $q->nama_hari;
                $nestedData['enroll_id'] = $q->enroll_id;
                $nestedData['nik'] = $q->nik;
                $nestedData['employee_name'] = $q->employee_name;
                $nestedData['site_nirwana_id'] = $q->site_nirwana_id;
                $nestedData['site_nirwana_name'] = $q->site_nirwana_name;
                $nestedData['department_id'] = $q->department_id;
                $nestedData['department_name'] = $q->department_name;
                $nestedData['sub_dept_id'] = $q->sub_dept_id;
                $nestedData['sub_dept_name'] = $q->sub_dept_name;
                $nestedData['tanggal_jadwal_mulai'] = $q->tanggal_jadwal_mulai;
                $nestedData['tanggal_jadwal_akhir'] = $q->tanggal_jadwal_akhir;
                $nestedData['jadwal_mulai_kerja'] = $q->jadwal_mulai_kerja;
                $nestedData['jadwal_akhir_kerja'] = $q->jadwal_akhir_kerja;
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

    public function ajax_datahadir(Request $request)
    {
        $daterange1 = explode(" - ", $request->daterange1);
        $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
        $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));

        if(request()->ajax()) {

            $columns = array(
                0 => 'tanggal_berjalan',
                1 => 'kode_hari',
                2 => 'nama_hari',
                3 => 'enroll_id',
                4 => 'nik',
                5 => 'employee_name',
                6 => 'department_name',
                7 => 'mulai_jam_kerja',
                8 => 'akhir_jam_kerja'
            );

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if(empty($request->input('search.value')))
            {
                $query =  MasterDataAbsenKehadiran::whereRaw('tanggal_berjalan between "' . $tanggalMulai . '" AND "' . $tanggalSampai . '"')
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();
    
                $totalData =  MasterDataAbsenKehadiran::whereRaw('tanggal_berjalan between "' . $tanggalMulai . '" AND "' . $tanggalSampai . '"')
                                ->count();
                $totalFiltered = $totalData;  
    
            } else {
                $search = $request->input('search.value');
    
                $query =  MasterDataAbsenKehadiran::whereRaw('
                                    (substr(tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" AND "' . $tanggalSampai . '")
                                    AND (enroll_id LIKE "%' . $search . '%"
                                    OR nik LIKE "%' . $search . '%"
                                    OR employee_name LIKE "%' . $search . '%")
                                ')                            
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();
    
                $totalData = MasterDataAbsenKehadiran::whereRaw('
                                    (substr(tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" AND "' . $tanggalSampai . '")
                                    AND (enroll_id LIKE "%' . $search . '%"
                                    OR nik LIKE "%' . $search . '%"
                                    OR employee_name LIKE "%' . $search . '%")
                                ')
                                ->count();
                $totalFiltered = $totalData;                            
    
            }            

            $data = array();
            if(!empty($query))
            {
                foreach ($query as $q)
                {

                    $nestedData['uuid'] = $q->uuid;
                    $nestedData['nik'] = $q->nik;
                    $nestedData['enroll_id'] = $q->enroll_id;
                    $nestedData['employee_name'] = $q->employee_name;
                    $nestedData['tanggal_berjalan'] = $q->tanggal_berjalan;
                    $nestedData['kode_hari'] = $q->kode_hari;
                    $nestedData['nama_hari'] = $q->nama_hari;
                    $nestedData['mulai_jam_kerja'] = $q->mulai_jam_kerja;
                    $nestedData['akhir_jam_kerja'] = $q->akhir_jam_kerja;
                    $nestedData['site_nirwana_id'] = $q->site_nirwana_id;
                    $nestedData['site_nirwana_name'] = $q->site_nirwana_name;
                    $nestedData['department_id'] = $q->department_id;
                    $nestedData['department_name'] = $q->department_name;
                    $nestedData['sub_dept_id'] = $q->sub_dept_id;
                    $nestedData['sub_dept_name'] = $q->sub_dept_name;
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

    public function process(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        $arrayHtml = $request->arrayHtml;
        foreach ($arrayHtml as $key => $value) {
                        
            $query1 = DataJadwalKerjaLog::create([
                'uuid' => Str::uuid(),
                'tanggal_berjalan' => $value['tanggal_berjalan'],
                'kode_hari' => $value['kode_hari'],
                'nama_hari' => $value['nama_hari'],
                'enroll_id' => $value['enroll_id'],
                'nik' => $value['nik'],
                'employee_name' => $value['employee_name'],
                'mulai_jam_kerja' => $value['mulai_jam_kerja'],
                'akhir_jam_kerja' => $value['akhir_jam_kerja'],
                'absen_masuk_kerja' => $value['absen_masuk_kerja'],
                'absen_pulang_kerja' => $value['absen_pulang_kerja'],
                'operator' => $email
            ]);            

            if ($query1) {
                $query2 = MasterDataAbsenKehadiran::whereRaw('
                    tanggal_berjalan = "' . $value['tanggal_berjalan'] . '" 
                    AND enroll_id = "' . $value['enroll_id'] . '"
                ')
                ->update([
                    'mulai_jam_kerja' => $value['mulai_jam_kerja'],
                    'akhir_jam_kerja' => $value['akhir_jam_kerja']
                ]);
                $absen_master = MasterDataAbsenKehadiran::where('tanggal_berjalan', $value['tanggal_berjalan'])
                    ->where('enroll_id',$value['enroll_id'])->first();
                if($value['mulai_jam_kerja']==null && $value['akhir_jam_kerja']==null && $absen_master->status_absen!='LN'){
                    $status_absen_null = MasterDataAbsenKehadiran::whereRaw('
                    tanggal_berjalan = "' . $value['tanggal_berjalan'] . '" 
                    AND enroll_id = "' . $value['enroll_id'] . '"
                    ')
                    ->update([
                        'status_absen' => null,
                    ]);
                }
            }
        }

        return $query2;

    }
    public function getEmployeeKehadiran(Request $request)
    {
        $daterange1 = explode(" - ", $request->daterange1);
        $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
        $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));

        $selectEmployeeID = $request->selectEmp;
        if ($selectEmployeeID) {
            $inDataEmp = '"' . implode('","', $selectEmployeeID) . '"';
            $inEmp = ' and master_data_absen_kehadiran.enroll_id in (' . $inDataEmp . ') ';
        } else {
            $inEmp = '';
        }

        $department_id = $request->selectDepartment;
        if ($selectEmployeeID) {
            $inDepartment = '';
        } else {
            if ($department_id) {
                $inDepartment = ' and department_all.department_id = "' . $department_id . '" ';
            } else {
                $inDepartment = '';
            }
        }

        $query =  MasterDataAbsenKehadiran::selectRaw('
                    master_data_absen_kehadiran.uuid, 
                    master_data_absen_kehadiran.nik, master_data_absen_kehadiran.employee_name, master_data_absen_kehadiran.kode_hari, master_data_absen_kehadiran.nama_hari,
                    master_data_absen_kehadiran.tanggal_berjalan, master_data_absen_kehadiran.enroll_id,                     
                    department_all.sub_dept_id, department_all.sub_dept_name, holiday_name,
                    substr(master_data_absen_kehadiran.mulai_jam_kerja,1,5) mulai_jam_kerja, substr(master_data_absen_kehadiran.akhir_jam_kerja, 1, 5) akhir_jam_kerja,
                    substr(master_data_absen_kehadiran.absen_masuk_kerja, 1, 5) absen_masuk_kerja, substr(master_data_absen_kehadiran.absen_pulang_kerja, 1, 5) absen_pulang_kerja,
                    employee_atribut.status_staff')
                ->whereRaw('
                    master_data_absen_kehadiran.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" AND "' . $tanggalSampai . '"
                    ' . $inEmp . '
                    ' . $inDepartment . '
                ')
                ->join('department_all', 'master_data_absen_kehadiran.sub_dept_id', '=', 'department_all.sub_dept_id')
                ->join('employee_atribut', 'master_data_absen_kehadiran.enroll_id', '=', 'employee_atribut.enroll_id')
                ->groupBy('master_data_absen_kehadiran.tanggal_berjalan')
                ->groupBy('master_data_absen_kehadiran.enroll_id')
                ->groupBy('department_all.sub_dept_id')
                ->groupBy('employee_atribut.enroll_id')
                ->orderBy('master_data_absen_kehadiran.employee_name','asc')
                ->orderBy('master_data_absen_kehadiran.tanggal_berjalan','asc')
                ->get();
        
        return Response()->json($query);
    }

}
