<?php

namespace App\Http\Controllers\Hris;

use App\Exports\EmployeeBpjsExport;
use App\Http\Controllers\AdminBaseController;
use App\Models\BpjsSetting;
use App\Models\EmployeeBpjs;
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
class EmployeeBpjsController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'BPJS Karyawan';
    }

    public function index()
    {
        $this->periode_bpjs = $this->ajax_getperiodebpjs();
        $this->periode_payroll = $this->ajax_getperiodepayroll();
        return View::make('hris/employeebpjs', $this->data);
    }

    public function ajax_getperiodepayroll()
    {
        $query =  RekapKehadiranKaryawan::selectRaw('periode_payroll')
                                    ->groupby('periode_payroll')
                                    ->orderby('periode_payroll', 'desc')
                                    ->get();
        return $query;

    }

    public function ajax_getperiodebpjs()
    {
        $query =  BpjsSetting::selectRaw('kode_periode_bpjs, concat(substr(kode_periode_bpjs, 1, 4), " | ", kode_dasar_pot_bpjs, " | Rp ", FORMAT(dasar_pot_bpjs_rupiah, 2,"id_ID")) nama_periode_bpjs')
                                    ->groupby('kode_periode_bpjs')
                                    ->groupby('nama_periode_bpjs')
                                    ->orderby('kode_periode_bpjs', 'desc')
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

    public function ajax_empbpjs(Request $request)
    {
        $explodeKode = explode(" s/d ", $request->periode_kehadiran);
        $kodeperiode = explode("-", $explodeKode[1]);
        $periode_kehadiran = $kodeperiode[0] . "-" . $kodeperiode[1];

        $status_staff = $request->status_staff;
        if($status_staff) {
            $whereStatusStaff = ' AND status_staff = "' . $status_staff . '" ';
        } else {
            $whereStatusStaff = '';
        }

        $searchData = $request->searchData;
        if($searchData) {
            $whereSearchData = ' AND (upper( employee_atribut.enroll_id ) LIKE upper( "%' . $searchData . '%" ) OR upper( employee_atribut.employee_name ) LIKE upper( "%' . $searchData . '%" ) OR upper( department_all.sub_dept_name ) LIKE upper( "%' . $searchData . '%" ) OR upper( employee_atribut.status_aktif ) LIKE upper( "%' . $searchData . '%" ) OR upper( employee_bpjs.status_aktif_bpjs_tk ) LIKE upper( "%' . $searchData . '%" ) OR upper( employee_bpjs.status_aktif_bpjs_ks ) LIKE upper( "%' . $searchData . '%" ) OR upper( employee_bpjs.nomor_bpjs_ketenagakerjaan ) LIKE upper( "%' . $searchData . '%" ) OR upper( employee_bpjs.nomor_bpjs_kesehatan ) LIKE upper( "%' . $searchData . '%" ) ) ';
        } else {
            $whereSearchData = '';
        }

        $query =  EmployeeBpjs::selectRaw('
                    uuid, employee_bpjs.kode_bpjs, concat(substr(employee_bpjs.kode_bpjs, 1, 4), "-", substr(employee_bpjs.kode_bpjs, 5, 2)) bpjs_kehadiran, employee_atribut.enroll_id, employee_atribut.nik, 
                    employee_atribut.employee_name, department_all.site_nirwana_id, 
                    department_all.site_nirwana_name, department_all.department_id, 
                    department_all.department_name, department_all.sub_dept_id, 
                    department_all.sub_dept_name, employee_atribut.join_date, employee_atribut.tanggal_resign,
                    employee_atribut.status_aktif_bpjs_tk, employee_atribut.tanggal_bpjs_ketenagakerjaan, 
                    employee_atribut.nomor_bpjs_ketenagakerjaan, employee_atribut.status_aktif_bpjs_ks, 
                    employee_atribut.tanggal_bpjs_kesehatan, employee_atribut.nomor_bpjs_kesehatan,
                    employee_atribut.status_aktif, employee_atribut.status_staff, 
                    FORMAT(employee_bpjs.dasar_pot_bpjs_rupiah, 2,"id_ID") dasar_pot_bpjs_rupiah,
                    
                    FORMAT(employee_bpjs.bpjs_tk_jht_bruto_rupiah + employee_bpjs.bpjs_tk_jht_neto_rupiah, 2,"id_ID") bpjs_tk_jht_rupiah,
                    FORMAT(employee_bpjs.bpjs_tk_jpn_bruto_rupiah + employee_bpjs.bpjs_tk_jpn_neto_rupiah, 2,"id_ID") bpjs_tk_jpn_rupiah,
                    FORMAT(employee_bpjs.bpjs_ks_jkn_bruto_rupiah + employee_bpjs.bpjs_ks_jkn_neto_rupiah, 2,"id_ID") bpjs_ks_jkn_rupiah,
                    FORMAT(employee_bpjs.bpjs_tk_jkk_bruto_rupiah + employee_bpjs.bpjs_tk_jkm_bruto_rupiah + employee_bpjs.bpjs_tk_jht_bruto_rupiah + employee_bpjs.bpjs_tk_jht_neto_rupiah + employee_bpjs.bpjs_tk_jpn_bruto_rupiah + employee_bpjs.bpjs_tk_jpn_neto_rupiah + employee_bpjs.bpjs_ks_jkn_bruto_rupiah + employee_bpjs.bpjs_ks_jkn_neto_rupiah, 2,"id_ID") total_iuran,

                    employee_bpjs.bpjs_tk_jkm_persen, 
                    FORMAT(employee_bpjs.bpjs_tk_jkm_bruto_persen, 2,"id_ID") bpjs_tk_jkm_perusahaan_persen, 
                    FORMAT(employee_bpjs.bpjs_tk_jkm_neto_persen, 2,"id_ID") bpjs_tk_jkm_karyawan_persen,
                    FORMAT(employee_bpjs.bpjs_tk_jkm_bruto_rupiah + employee_bpjs.bpjs_tk_jkm_neto_rupiah, 2,"id_ID") bpjs_tk_jkm_rupiah, 
                    FORMAT(employee_bpjs.bpjs_tk_jkm_bruto_rupiah, 2,"id_ID") bpjs_tk_jkm_perusahaan_rupiah, 
                    FORMAT(employee_bpjs.bpjs_tk_jkm_neto_rupiah, 2,"id_ID") bpjs_tk_jkm_karyawan_rupiah,
                    
                    employee_bpjs.bpjs_tk_jkk_persen, 
                    FORMAT(employee_bpjs.bpjs_tk_jkk_bruto_persen, 2,"id_ID") bpjs_tk_jkk_perusahaan_persen, 
                    FORMAT(employee_bpjs.bpjs_tk_jkk_neto_persen, 2,"id_ID") bpjs_tk_jkk_karyawan_persen,
                    FORMAT(employee_bpjs.bpjs_tk_jkk_bruto_rupiah + employee_bpjs.bpjs_tk_jkk_neto_rupiah, 2,"id_ID") bpjs_tk_jkk_rupiah, 
                    FORMAT(employee_bpjs.bpjs_tk_jkk_bruto_rupiah, 2,"id_ID") bpjs_tk_jkk_perusahaan_rupiah, 
                    FORMAT(employee_bpjs.bpjs_tk_jkk_neto_rupiah, 2,"id_ID") bpjs_tk_jkk_karyawan_rupiah,
                    
                    employee_bpjs.bpjs_tk_jht_persen, 
                    FORMAT(employee_bpjs.bpjs_tk_jht_bruto_persen, 2,"id_ID") bpjs_tk_jht_perusahaan_persen, 
                    FORMAT(employee_bpjs.bpjs_tk_jht_neto_persen, 2,"id_ID") bpjs_tk_jht_karyawan_persen,
                    FORMAT(employee_bpjs.bpjs_tk_jht_bruto_rupiah, 2,"id_ID") bpjs_tk_jht_perusahaan_rupiah, 
                    FORMAT(employee_bpjs.bpjs_tk_jht_neto_rupiah, 2,"id_ID") bpjs_tk_jht_karyawan_rupiah,

                    employee_bpjs.bpjs_tk_jpn_persen,
                    FORMAT(employee_bpjs.bpjs_tk_jpn_bruto_persen, 2,"id_ID") bpjs_tk_jpn_perusahaan_persen, 
                    FORMAT(employee_bpjs.bpjs_tk_jpn_neto_persen, 2,"id_ID") bpjs_tk_jpn_karyawan_persen,
                    FORMAT(employee_bpjs.bpjs_tk_jpn_bruto_rupiah, 2,"id_ID") bpjs_tk_jpn_perusahaan_rupiah, 
                    FORMAT(employee_bpjs.bpjs_tk_jpn_neto_rupiah, 2,"id_ID") bpjs_tk_jpn_karyawan_rupiah,

                    employee_bpjs.bpjs_ks_jkn_persen,
                    FORMAT(employee_bpjs.bpjs_ks_jkn_bruto_persen, 2,"id_ID") bpjs_ks_jkn_perusahaan_persen, 
                    FORMAT(employee_bpjs.bpjs_ks_jkn_neto_persen, 2,"id_ID") bpjs_ks_jkn_karyawan_persen,
                    FORMAT(employee_bpjs.bpjs_ks_jkn_bruto_rupiah, 2,"id_ID") bpjs_ks_jkn_perusahaan_rupiah, 
                    FORMAT(employee_bpjs.bpjs_ks_jkn_neto_rupiah, 2,"id_ID") bpjs_ks_jkn_karyawan_rupiah,

                    employee_bpjs.operator, substr(employee_bpjs.created_at, 1, 19) created_at,
                    substr(employee_bpjs.updated_at, 1, 19) updated_at, 
                    substr(employee_bpjs.deleted_at, 1, 19) deleted_at
            ')
            ->whereRaw('
                concat(substr(employee_bpjs.kode_bpjs, 1, 4), "-", substr(employee_bpjs.kode_bpjs, 5, 2)) = "' . $periode_kehadiran . '"
                ' . $whereStatusStaff . '
                ' . $whereSearchData . '
            ')
            ->join('employee_atribut','employee_bpjs.enroll_id','=','employee_atribut.enroll_id')
            ->join('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id')
            ->orderBy('employee_atribut.employee_name','asc')
            ->get();

        return Response()->json($query);

    }

    public function ajax_bpjssetting(Request $request)
    {

        $query =  BpjsSetting::selectRaw('
                    kode_periode_bpjs, kode_dasar_pot_bpjs, nama_dasar_pot_bpjs, dasar_pot_bpjs_rupiah,
                    FORMAT(bpjs_tk_jkm_persen, 2,"id_ID") bpjs_tk_jkm_persen, 
                    FORMAT(bpjs_tk_jkk_persen, 2,"id_ID") bpjs_tk_jkk_persen, 
                    FORMAT(bpjs_tk_jht_persen, 2,"id_ID") bpjs_tk_jht_persen, 
                    FORMAT(bpjs_tk_jpn_persen, 2,"id_ID") bpjs_tk_jpn_persen,
                    FORMAT(bpjs_ks_jkn_persen, 2,"id_ID") bpjs_ks_jkn_persen,         
                    operator, substr(created_at, 1, 19) created_at,
                    substr(updated_at, 1, 19) updated_at, 
                    substr(deleted_at, 1, 19) deleted_at
            ')
            ->limit(1)
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

        $fileName = 'BPJSKaryawan_' . time() . '.xlsx';
        return (new EmployeeBpjsExport)->exportParams($periode_payroll, $status_staff, $searchData)->download($fileName);

    }

    public function update(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $operator = $loggedAdmin->email;

        $kode_bpjs = $request->kode_bpjs;
        $enroll_id = $request->enroll_id;
        $status_aktif_bpjs_tk = $request->status_aktif_bpjs_tk;
        $tanggal_bpjs_ketenagakerjaan = $request->tanggal_bpjs_ketenagakerjaan;
        $nomor_bpjs_ketenagakerjaan = $request->nomor_bpjs_ketenagakerjaan;
        $status_aktif_bpjs_ks = $request->status_aktif_bpjs_ks;
        $tanggal_bpjs_kesehatan = $request->tanggal_bpjs_kesehatan;
        $nomor_bpjs_kesehatan = $request->nomor_bpjs_kesehatan;
        
        $query = EmployeeBpjs::where('kode_bpjs','=', $kode_bpjs)
        ->update([
            'status_aktif_bpjs_tk' => $status_aktif_bpjs_tk,
            'tanggal_bpjs_ketenagakerjaan' => $tanggal_bpjs_ketenagakerjaan,
            'nomor_bpjs_ketenagakerjaan' => $nomor_bpjs_ketenagakerjaan,
            'status_aktif_bpjs_ks' => $status_aktif_bpjs_ks,
            'tanggal_bpjs_kesehatan' => $tanggal_bpjs_kesehatan,
            'nomor_bpjs_kesehatan' => $nomor_bpjs_kesehatan,
            'operator' => $operator
        ]);

        $query = EmployeeAtribut::where('enroll_id','=', $enroll_id)
        ->update([
            'status_aktif_bpjs_tk' => $status_aktif_bpjs_tk,
            'tanggal_bpjs_ketenagakerjaan' => $tanggal_bpjs_ketenagakerjaan,
            'nomor_bpjs_ketenagakerjaan' => $nomor_bpjs_ketenagakerjaan,
            'status_aktif_bpjs_ks' => $status_aktif_bpjs_ks,
            'tanggal_bpjs_kesehatan' => $tanggal_bpjs_kesehatan,
            'nomor_bpjs_kesehatan' => $nomor_bpjs_kesehatan
        ]);

        return $query;
    }

    // public function update_bpjs(Request $request)
    // {
    //     $loggedAdmin = Auth::guard('admin')->user();
    //     $email = $loggedAdmin->email;
        
    //     $explodePeriodePayroll = explode(" s/d ", $request->periode_payroll);  
    //     $periodePayroll = substr($explodePeriodePayroll[1], 0, 4) . substr($explodePeriodePayroll[1], 5, 2);
    //     $explodeKode = explode("-", $request->kode_periode_bpjs);
    //     $kode_periode_bpjs = $explodeKode[0] . $explodeKode[1];
    //     $sqlKodePeriodeBPJS = 'concat("' . $periodePayroll . '", lpad(enroll_id, 5, 0))';
    //     $status_staff = $request->status_staff;
    //     $searchData = $request->searchData;

    //     $queryEmpAtr =  EmployeeAtribut::selectRaw('
    //                 uuid() uuid,
    //                 concat(SUBSTR(DATE_ADD( LAST_DAY( DATE_SUB( MAX("' . $explodePeriodePayroll[1] . '"), INTERVAL 1 MONTH )), INTERVAL 25 DAY ), 1, 4),
    //                 SUBSTR(DATE_ADD( LAST_DAY( DATE_SUB( MAX("' . $explodePeriodePayroll[1] . '"), INTERVAL 1 MONTH )), INTERVAL 25 DAY ), 6, 2), lpad(enroll_id, 5, 0)) kode_bpjs,
    //                 substr("' . $explodePeriodePayroll[1] . '", 1, 4) periode_bpjs,
    //                 CONCAT(DATE_ADD( LAST_DAY( DATE_SUB( MAX("' . $explodePeriodePayroll[1] . '"), INTERVAL 2 MONTH )), INTERVAL 26 DAY ), " s/d ", 
    //             	DATE_ADD( LAST_DAY( DATE_SUB( MAX("' . $explodePeriodePayroll[1] . '"), INTERVAL 1 MONTH )), INTERVAL 25 DAY ))  periode_kehadiran,
    //                 enroll_id, nik, employee_name, site_nirwana_id, department_id, sub_dept_id,
    //                 status_aktif_bpjs_tk, tanggal_bpjs_ketenagakerjaan, nomor_bpjs_ketenagakerjaan,
    //                 status_aktif_bpjs_ks, tanggal_bpjs_kesehatan, nomor_bpjs_kesehatan
    //                 ')
    //                 ->whereRaw('
    //                 enroll_id is not null
    //                 AND (tanggal_resign is null OR tanggal_resign = "0000-00-00" OR 
    //                     NOT tanggal_resign < DATE_ADD( LAST_DAY( DATE_SUB( "' . $explodePeriodePayroll[1] . '", INTERVAL 2 MONTH )), INTERVAL 26 DAY ))
    //                 AND join_date < "' . $explodePeriodePayroll[1] . '"
    //                 ')
    //                 ->groupBy('enroll_id')
    //                 ->groupBy('employee_name')
    //                 ->get();

    //     foreach ($queryEmpAtr as $key => $value) {

    //         $countEmp = EmployeeBpjs::whereRaw('kode_bpjs = ' . $sqlKodePeriodeBPJS . ' AND enroll_id = "' . $value['enroll_id'] . '"')->count();

    //         if ($countEmp) {
    //             $queryEmpBpjs = EmployeeBpjs::whereRaw('kode_bpjs = ' . $sqlKodePeriodeBPJS . ' AND enroll_id = "' . $value['enroll_id'] . '"')
    //             ->update([
    //                 'status_aktif_bpjs_tk' => $value['status_aktif_bpjs_tk'],
    //                 'tanggal_bpjs_ketenagakerjaan' => $value['tanggal_bpjs_ketenagakerjaan'],
    //                 'nomor_bpjs_ketenagakerjaan' => $value['nomor_bpjs_ketenagakerjaan'],
    //                 'status_aktif_bpjs_ks' => $value['status_aktif_bpjs_ks'],
    //                 'tanggal_bpjs_kesehatan' => $value['tanggal_bpjs_kesehatan'],
    //                 'nomor_bpjs_kesehatan' => $value['nomor_bpjs_kesehatan'],
    //                 'kode_periode_bpjs' => null,
    //                 'kode_dasar_pot_bpjs' => null,
    //                 'dasar_pot_bpjs_rupiah' => 0,
    //                 'bpjs_tk_jkm_bruto_rupiah' => 0,
    //                 'bpjs_tk_jkk_bruto_rupiah' => 0,
    //                 'bpjs_ks_jkn_bruto_rupiah' => 0,
    //                 'bpjs_tk_jkm_neto_rupiah' => 0,
    //                 'bpjs_tk_jkk_neto_rupiah' => 0,
    //                 'bpjs_tk_jht_neto_rupiah' => 0,
    //                 'bpjs_tk_jpn_neto_rupiah' => 0,
    //                 'bpjs_ks_jkn_neto_rupiah' => 0,
    //                 'bpjs_tk_jkm_persen' => 0,
    //                 'bpjs_tk_jkk_persen' => 0,
    //                 'bpjs_tk_jht_persen' => 0,
    //                 'bpjs_tk_jpn_persen' => 0,
    //                 'bpjs_ks_jkn_persen' => 0,
    //                 'bpjs_tk_jkm_bruto_persen' => 0,
    //                 'bpjs_tk_jkk_bruto_persen' => 0,
    //                 'bpjs_tk_jht_bruto_persen' => 0,
    //                 'bpjs_tk_jpn_bruto_persen' => 0,
    //                 'bpjs_ks_jkn_bruto_persen' => 0,
    //                 'bpjs_tk_jkm_neto_persen' => 0,
    //                 'bpjs_tk_jkk_neto_persen' => 0,
    //                 'bpjs_tk_jht_neto_persen' => 0,
    //                 'bpjs_tk_jpn_neto_persen' => 0,
    //                 'bpjs_ks_jkn_neto_persen' => 0
    //             ]);
    //         } else {
    //             EmployeeBpjs::create([
    //                 'uuid' => Str::uuid(),
    //                 'kode_bpjs' => $value['kode_bpjs'],
    //                 'periode_bpjs' => $value['periode_bpjs'],
    //                 'periode_kehadiran' => $value['periode_kehadiran'],
    //                 'enroll_id' => $value['enroll_id'],
    //                 'nik' => $value['nik'],
    //                 'employee_name' => $value['employee_name'],
    //                 'status_aktif_bpjs_tk' => $value['status_aktif_bpjs_tk'],
    //                 'tanggal_bpjs_ketenagakerjaan' => $value['tanggal_bpjs_ketenagakerjaan'],
    //                 'nomor_bpjs_ketenagakerjaan' => $value['nomor_bpjs_ketenagakerjaan'],
    //                 'status_aktif_bpjs_ks' => $value['status_aktif_bpjs_ks'],
    //                 'tanggal_bpjs_kesehatan' => $value['tanggal_bpjs_kesehatan'],
    //                 'nomor_bpjs_kesehatan' => $value['nomor_bpjs_kesehatan'],
    //                 'kode_periode_bpjs' => null,
    //                 'kode_dasar_pot_bpjs' => null,
    //                 'dasar_pot_bpjs_rupiah' => 0,
    //                 'bpjs_tk_jkm_bruto_rupiah' => 0,
    //                 'bpjs_tk_jkk_bruto_rupiah' => 0,
    //                 'bpjs_ks_jkn_bruto_rupiah' => 0,
    //                 'bpjs_tk_jkm_neto_rupiah' => 0,
    //                 'bpjs_tk_jkk_neto_rupiah' => 0,
    //                 'bpjs_tk_jht_neto_rupiah' => 0,
    //                 'bpjs_tk_jpn_neto_rupiah' => 0,
    //                 'bpjs_ks_jkn_neto_rupiah' => 0,
    //                 'bpjs_tk_jkm_persen' => 0,
    //                 'bpjs_tk_jkk_persen' => 0,
    //                 'bpjs_tk_jht_persen' => 0,
    //                 'bpjs_tk_jpn_persen' => 0,
    //                 'bpjs_ks_jkn_persen' => 0,
    //                 'bpjs_tk_jkm_bruto_persen' => 0,
    //                 'bpjs_tk_jkk_bruto_persen' => 0,
    //                 'bpjs_tk_jht_bruto_persen' => 0,
    //                 'bpjs_tk_jpn_bruto_persen' => 0,
    //                 'bpjs_ks_jkn_bruto_persen' => 0,
    //                 'bpjs_tk_jkm_neto_persen' => 0,
    //                 'bpjs_tk_jkk_neto_persen' => 0,
    //                 'bpjs_tk_jht_neto_persen' => 0,
    //                 'bpjs_tk_jpn_neto_persen' => 0,
    //                 'bpjs_ks_jkn_neto_persen' => 0
    //             ]);    
    //         }

    //     }
    //     $query =  BpjsSetting::whereRaw(' substr(kode_periode_bpjs, 1, 4) = substr("' . $request->kode_periode_bpjs . '", 1, 4)')
    //             ->orderBy('kode_periode_bpjs','desc')
    //             ->limit(1)
    //             ->get();

    //     $kode_periode_bpjs = $query[0]->kode_periode_bpjs;
    //     $kode_dasar_pot_bpjs = $query[0]->kode_dasar_pot_bpjs;
    //     $dasar_pot_bpjs_rupiah = $query[0]->dasar_pot_bpjs_rupiah;
    //     $bpjs_tk_jkm_persen = $query[0]->bpjs_tk_jkm_persen;
    //     $bpjs_tk_jkm_perusahaan_persen = $query[0]->bpjs_tk_jkm_perusahaan_persen;
    //     $bpjs_tk_jkm_karyawan_persen = $query[0]->bpjs_tk_jkm_karyawan_persen;
    //     $bpjs_tk_jkk_persen = $query[0]->bpjs_tk_jkk_persen;
    //     $bpjs_tk_jkk_perusahaan_persen = $query[0]->bpjs_tk_jkk_perusahaan_persen;
    //     $bpjs_tk_jkk_karyawan_persen = $query[0]->bpjs_tk_jkk_karyawan_persen;
    //     $bpjs_tk_jht_persen = $query[0]->bpjs_tk_jht_persen;
    //     $bpjs_tk_jht_perusahaan_persen = $query[0]->bpjs_tk_jht_perusahaan_persen;
    //     $bpjs_tk_jht_karyawan_persen = $query[0]->bpjs_tk_jht_karyawan_persen;
    //     $bpjs_tk_jpn_persen = $query[0]->bpjs_tk_jpn_persen;
    //     $bpjs_tk_jpn_perusahaan_persen = $query[0]->bpjs_tk_jpn_perusahaan_persen;
    //     $bpjs_tk_jpn_karyawan_persen = $query[0]->bpjs_tk_jpn_karyawan_persen;
    //     $bpjs_ks_jkn_persen = $query[0]->bpjs_ks_jkn_persen;
    //     $bpjs_ks_jkn_perusahaan_persen = $query[0]->bpjs_ks_jkn_perusahaan_persen;
    //     $bpjs_ks_jkn_karyawan_persen = $query[0]->bpjs_ks_jkn_karyawan_persen;

    //     $queryEmpBpjs = DB::update('update employee_bpjs set 
    //                     kode_periode_bpjs = "' . $kode_periode_bpjs . '",
    //                     kode_dasar_pot_bpjs = "' . $kode_dasar_pot_bpjs . '",
    //                     dasar_pot_bpjs_rupiah = "' . $dasar_pot_bpjs_rupiah . '",
    //                     bpjs_tk_jkm_bruto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jkm_perusahaan_persen . '/100)), 0),
    //                     bpjs_tk_jkk_bruto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jkk_perusahaan_persen . '/100)), 0),
    //                     bpjs_tk_jht_bruto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jht_perusahaan_persen . '/100)), 0),
    //                     bpjs_tk_jpn_bruto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jpn_perusahaan_persen . '/100)), 0),
    //                     bpjs_ks_jkn_bruto_rupiah = IF(status_aktif_bpjs_ks = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_ks_jkn_perusahaan_persen . '/100)), 0),
    //                     bpjs_tk_jkm_neto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jkm_karyawan_persen . '/100)), 0),
    //                     bpjs_tk_jkk_neto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jkk_karyawan_persen . '/100)), 0),
    //                     bpjs_tk_jht_neto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jht_karyawan_persen . '/100)), 0),
    //                     bpjs_tk_jpn_neto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jpn_karyawan_persen . '/100)), 0),
    //                     bpjs_ks_jkn_neto_rupiah = IF(status_aktif_bpjs_ks = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_ks_jkn_karyawan_persen . '/100)), 0),
    //                     bpjs_tk_jkm_persen = "' . $bpjs_tk_jkm_persen . '",
    //                     bpjs_tk_jkk_persen = "' . $bpjs_tk_jkk_persen . '",
    //                     bpjs_tk_jht_persen = "' . $bpjs_tk_jht_persen . '",
    //                     bpjs_tk_jpn_persen = "' . $bpjs_tk_jpn_persen . '",
    //                     bpjs_ks_jkn_persen = "' . $bpjs_ks_jkn_persen . '",
    //                     bpjs_tk_jkm_bruto_persen = "' . $bpjs_tk_jkm_perusahaan_persen . '",
    //                     bpjs_tk_jkk_bruto_persen = "' . $bpjs_tk_jkk_perusahaan_persen . '",
    //                     bpjs_tk_jht_bruto_persen = "' . $bpjs_tk_jht_perusahaan_persen . '",
    //                     bpjs_tk_jpn_bruto_persen = "' . $bpjs_tk_jpn_perusahaan_persen . '",
    //                     bpjs_ks_jkn_bruto_persen = "' . $bpjs_ks_jkn_perusahaan_persen . '",
    //                     bpjs_tk_jkm_neto_persen = "' . $bpjs_tk_jkm_karyawan_persen . '",
    //                     bpjs_tk_jkk_neto_persen = "' . $bpjs_tk_jkk_karyawan_persen . '",
    //                     bpjs_tk_jht_neto_persen = "' . $bpjs_tk_jht_karyawan_persen . '",
    //                     bpjs_tk_jpn_neto_persen = "' . $bpjs_tk_jpn_karyawan_persen . '",
    //                     bpjs_ks_jkn_neto_persen = "' . $bpjs_ks_jkn_karyawan_persen . '",
    //                     operator = "' . $email . '"
    //                 where kode_bpjs like "' . $periodePayroll . '%"');

    //     return Response()->json($queryEmpBpjs);
    // }

    //Andri
    public function update_bpjs(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        
        $explodePeriodePayroll = explode(" s/d ", $request->periode_payroll);  
        $periodePayroll = substr($explodePeriodePayroll[1], 0, 4) . substr($explodePeriodePayroll[1], 5, 2);
        $explodeKode = explode("-", $request->kode_periode_bpjs);
        $kode_periode_bpjs = $explodeKode[0] . $explodeKode[1];
        $sqlKodePeriodeBPJS = 'concat("' . $periodePayroll . '", lpad(enroll_id, 5, 0))';
        $status_staff = $request->status_staff;
        $searchData = $request->searchData;

        $queryEmpAtr =  EmployeeAtribut::selectRaw('
                    uuid() uuid,
                    concat(SUBSTR(DATE_ADD( LAST_DAY( DATE_SUB( MAX("' . $explodePeriodePayroll[1] . '"), INTERVAL 1 MONTH )), INTERVAL 25 DAY ), 1, 4),
                    SUBSTR(DATE_ADD( LAST_DAY( DATE_SUB( MAX("' . $explodePeriodePayroll[1] . '"), INTERVAL 1 MONTH )), INTERVAL 25 DAY ), 6, 2), lpad(enroll_id, 5, 0)) kode_bpjs,
                    substr("' . $explodePeriodePayroll[1] . '", 1, 4) periode_bpjs,
                    CONCAT(DATE_ADD( LAST_DAY( DATE_SUB( MAX("' . $explodePeriodePayroll[1] . '"), INTERVAL 2 MONTH )), INTERVAL 26 DAY ), " s/d ", 
                	DATE_ADD( LAST_DAY( DATE_SUB( MAX("' . $explodePeriodePayroll[1] . '"), INTERVAL 1 MONTH )), INTERVAL 25 DAY ))  periode_kehadiran,
                    enroll_id, nik, employee_name, site_nirwana_id, department_id, sub_dept_id,
                    status_aktif_bpjs_tk, tanggal_bpjs_ketenagakerjaan, nomor_bpjs_ketenagakerjaan,
                    status_aktif_bpjs_ks, tanggal_bpjs_kesehatan, nomor_bpjs_kesehatan, join_date
                    ')
                    ->whereRaw('
                    enroll_id is not null
                    AND (tanggal_resign is null OR tanggal_resign = "0000-00-00" OR 
                        NOT tanggal_resign < DATE_ADD( LAST_DAY( DATE_SUB( "' . $explodePeriodePayroll[1] . '", INTERVAL 2 MONTH )), INTERVAL 26 DAY ))
                    AND join_date < "' . $explodePeriodePayroll[1] . '"
                    ')
                    ->groupBy('enroll_id')
                    ->groupBy('employee_name')
                    ->get();

        foreach ($queryEmpAtr as $key => $value) {

            $tanggal_masuk = $value['join_date'];

            // hitung selisih tahun antara tanggal masuk dan sekarang
            $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create($explodePeriodePayroll[1]))->y;

            // tentukan besaran tunjangan berdasarkan masa kerja
            if ($selisih_tahun < 1) {
                $tunjangan = 0;
            } elseif ($selisih_tahun < 3) {
                $tunjangan = 2500;
            } elseif ($selisih_tahun < 6) {
                $tunjangan = 5000;
            }elseif ($selisih_tahun < 9) {
                $tunjangan = 7500;
            }elseif ($selisih_tahun < 12) {
                $tunjangan = 10000;
            }else{
                $tunjangan = 12500;
            }

            $countEmp = EmployeeBpjs::whereRaw('kode_bpjs = ' . $sqlKodePeriodeBPJS . ' AND enroll_id = "' . $value['enroll_id'] . '"')->count();

            if ($countEmp) {
                $queryEmpBpjs = EmployeeBpjs::whereRaw('kode_bpjs = ' . $sqlKodePeriodeBPJS . ' AND enroll_id = "' . $value['enroll_id'] . '"')
                ->update([
                    'status_aktif_bpjs_tk' => $value['status_aktif_bpjs_tk'],
                    'tanggal_bpjs_ketenagakerjaan' => $value['tanggal_bpjs_ketenagakerjaan'],
                    'nomor_bpjs_ketenagakerjaan' => $value['nomor_bpjs_ketenagakerjaan'],
                    'status_aktif_bpjs_ks' => $value['status_aktif_bpjs_ks'],
                    'tanggal_bpjs_kesehatan' => $value['tanggal_bpjs_kesehatan'],
                    'nomor_bpjs_kesehatan' => $value['nomor_bpjs_kesehatan'],
                    'kode_periode_bpjs' => null,
                    'kode_dasar_pot_bpjs' => null,
                    'dasar_pot_bpjs_rupiah' => 0,
                    'bpjs_tk_jkm_bruto_rupiah' => 0,
                    'bpjs_tk_jkk_bruto_rupiah' => 0,
                    'bpjs_ks_jkn_bruto_rupiah' => 0,
                    'bpjs_tk_jkm_neto_rupiah' => 0,
                    'bpjs_tk_jkk_neto_rupiah' => 0,
                    'bpjs_tk_jht_neto_rupiah' => 0,
                    'bpjs_tk_jpn_neto_rupiah' => 0,
                    'bpjs_ks_jkn_neto_rupiah' => 0,
                    'bpjs_tk_jkm_persen' => 0,
                    'bpjs_tk_jkk_persen' => 0,
                    'bpjs_tk_jht_persen' => 0,
                    'bpjs_tk_jpn_persen' => 0,
                    'bpjs_ks_jkn_persen' => 0,
                    'bpjs_tk_jkm_bruto_persen' => 0,
                    'bpjs_tk_jkk_bruto_persen' => 0,
                    'bpjs_tk_jht_bruto_persen' => 0,
                    'bpjs_tk_jpn_bruto_persen' => 0,
                    'bpjs_ks_jkn_bruto_persen' => 0,
                    'bpjs_tk_jkm_neto_persen' => 0,
                    'bpjs_tk_jkk_neto_persen' => 0,
                    'bpjs_tk_jht_neto_persen' => 0,
                    'bpjs_tk_jpn_neto_persen' => 0,
                    'bpjs_ks_jkn_neto_persen' => 0,
                    'tmk'=>$tunjangan,
                ]);
            } else {
                EmployeeBpjs::create([
                    'uuid' => Str::uuid(),
                    'kode_bpjs' => $value['kode_bpjs'],
                    'periode_bpjs' => $value['periode_bpjs'],
                    'periode_kehadiran' => $value['periode_kehadiran'],
                    'enroll_id' => $value['enroll_id'],
                    'nik' => $value['nik'],
                    'employee_name' => $value['employee_name'],
                    'status_aktif_bpjs_tk' => $value['status_aktif_bpjs_tk'],
                    'tanggal_bpjs_ketenagakerjaan' => $value['tanggal_bpjs_ketenagakerjaan'],
                    'nomor_bpjs_ketenagakerjaan' => $value['nomor_bpjs_ketenagakerjaan'],
                    'status_aktif_bpjs_ks' => $value['status_aktif_bpjs_ks'],
                    'tanggal_bpjs_kesehatan' => $value['tanggal_bpjs_kesehatan'],
                    'nomor_bpjs_kesehatan' => $value['nomor_bpjs_kesehatan'],
                    'kode_periode_bpjs' => null,
                    'kode_dasar_pot_bpjs' => null,
                    'dasar_pot_bpjs_rupiah' => 0,
                    'bpjs_tk_jkm_bruto_rupiah' => 0,
                    'bpjs_tk_jkk_bruto_rupiah' => 0,
                    'bpjs_ks_jkn_bruto_rupiah' => 0,
                    'bpjs_tk_jkm_neto_rupiah' => 0,
                    'bpjs_tk_jkk_neto_rupiah' => 0,
                    'bpjs_tk_jht_neto_rupiah' => 0,
                    'bpjs_tk_jpn_neto_rupiah' => 0,
                    'bpjs_ks_jkn_neto_rupiah' => 0,
                    'bpjs_tk_jkm_persen' => 0,
                    'bpjs_tk_jkk_persen' => 0,
                    'bpjs_tk_jht_persen' => 0,
                    'bpjs_tk_jpn_persen' => 0,
                    'bpjs_ks_jkn_persen' => 0,
                    'bpjs_tk_jkm_bruto_persen' => 0,
                    'bpjs_tk_jkk_bruto_persen' => 0,
                    'bpjs_tk_jht_bruto_persen' => 0,
                    'bpjs_tk_jpn_bruto_persen' => 0,
                    'bpjs_ks_jkn_bruto_persen' => 0,
                    'bpjs_tk_jkm_neto_persen' => 0,
                    'bpjs_tk_jkk_neto_persen' => 0,
                    'bpjs_tk_jht_neto_persen' => 0,
                    'bpjs_tk_jpn_neto_persen' => 0,
                    'bpjs_ks_jkn_neto_persen' => 0,
                    'tmk'=>$tunjangan,
                ]);    
            }

        }
        $query =  BpjsSetting::whereRaw(' substr(kode_periode_bpjs, 1, 4) = substr("' . $request->kode_periode_bpjs . '", 1, 4)')
                ->orderBy('kode_periode_bpjs','desc')
                ->limit(1)
                ->get();

        $kode_periode_bpjs = $query[0]->kode_periode_bpjs;
        $kode_dasar_pot_bpjs = $query[0]->kode_dasar_pot_bpjs;
        $dasar_pot_bpjs_rupiah_gapok = $query[0]->dasar_pot_bpjs_rupiah;
        $bpjs_tk_jkm_persen = $query[0]->bpjs_tk_jkm_persen;
        $bpjs_tk_jkm_perusahaan_persen = $query[0]->bpjs_tk_jkm_perusahaan_persen;
        $bpjs_tk_jkm_karyawan_persen = $query[0]->bpjs_tk_jkm_karyawan_persen;
        $bpjs_tk_jkk_persen = $query[0]->bpjs_tk_jkk_persen;
        $bpjs_tk_jkk_perusahaan_persen = $query[0]->bpjs_tk_jkk_perusahaan_persen;
        $bpjs_tk_jkk_karyawan_persen = $query[0]->bpjs_tk_jkk_karyawan_persen;
        $bpjs_tk_jht_persen = $query[0]->bpjs_tk_jht_persen;
        $bpjs_tk_jht_perusahaan_persen = $query[0]->bpjs_tk_jht_perusahaan_persen;
        $bpjs_tk_jht_karyawan_persen = $query[0]->bpjs_tk_jht_karyawan_persen;
        $bpjs_tk_jpn_persen = $query[0]->bpjs_tk_jpn_persen;
        $bpjs_tk_jpn_perusahaan_persen = $query[0]->bpjs_tk_jpn_perusahaan_persen;
        $bpjs_tk_jpn_karyawan_persen = $query[0]->bpjs_tk_jpn_karyawan_persen;
        $bpjs_ks_jkn_persen = $query[0]->bpjs_ks_jkn_persen;
        $bpjs_ks_jkn_perusahaan_persen = $query[0]->bpjs_ks_jkn_perusahaan_persen;
        $bpjs_ks_jkn_karyawan_persen = $query[0]->bpjs_ks_jkn_karyawan_persen;

        $EmpBpjs = EmployeeBpjs::where('periode_kehadiran',$request->periode_payroll)->get();

        foreach ($EmpBpjs as $key3 => $value3) {
            $dasar_pot_bpjs_rupiah=$dasar_pot_bpjs_rupiah_gapok+$value3->tmk;
            // dd($dasar_pot_bpjs_rupiah);
        $queryEmpBpjs = DB::update('update employee_bpjs set 
                kode_periode_bpjs = "' . $kode_periode_bpjs . '",
                kode_dasar_pot_bpjs = "' . $kode_dasar_pot_bpjs . '",
                dasar_pot_bpjs_rupiah = "' . $dasar_pot_bpjs_rupiah . '",
                bpjs_tk_jkm_bruto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jkm_perusahaan_persen . '/100)), 0),
                bpjs_tk_jkk_bruto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jkk_perusahaan_persen . '/100)), 0),
                bpjs_tk_jht_bruto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jht_perusahaan_persen . '/100)), 0),
                bpjs_tk_jpn_bruto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jpn_perusahaan_persen . '/100)), 0),
                bpjs_ks_jkn_bruto_rupiah = IF(status_aktif_bpjs_ks = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_ks_jkn_perusahaan_persen . '/100)), 0),
                bpjs_tk_jkm_neto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jkm_karyawan_persen . '/100)), 0),
                bpjs_tk_jkk_neto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jkk_karyawan_persen . '/100)), 0),
                bpjs_tk_jht_neto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jht_karyawan_persen . '/100)), 0),
                bpjs_tk_jpn_neto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jpn_karyawan_persen . '/100)), 0),
                bpjs_ks_jkn_neto_rupiah = IF(status_aktif_bpjs_ks = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_ks_jkn_karyawan_persen . '/100)), 0),
                bpjs_tk_jkm_persen = "' . $bpjs_tk_jkm_persen . '",
                bpjs_tk_jkk_persen = "' . $bpjs_tk_jkk_persen . '",
                bpjs_tk_jht_persen = "' . $bpjs_tk_jht_persen . '",
                bpjs_tk_jpn_persen = "' . $bpjs_tk_jpn_persen . '",
                bpjs_ks_jkn_persen = "' . $bpjs_ks_jkn_persen . '",
                bpjs_tk_jkm_bruto_persen = "' . $bpjs_tk_jkm_perusahaan_persen . '",
                bpjs_tk_jkk_bruto_persen = "' . $bpjs_tk_jkk_perusahaan_persen . '",
                bpjs_tk_jht_bruto_persen = "' . $bpjs_tk_jht_perusahaan_persen . '",
                bpjs_tk_jpn_bruto_persen = "' . $bpjs_tk_jpn_perusahaan_persen . '",
                bpjs_ks_jkn_bruto_persen = "' . $bpjs_ks_jkn_perusahaan_persen . '",
                bpjs_tk_jkm_neto_persen = "' . $bpjs_tk_jkm_karyawan_persen . '",
                bpjs_tk_jkk_neto_persen = "' . $bpjs_tk_jkk_karyawan_persen . '",
                bpjs_tk_jht_neto_persen = "' . $bpjs_tk_jht_karyawan_persen . '",
                bpjs_tk_jpn_neto_persen = "' . $bpjs_tk_jpn_karyawan_persen . '",
                bpjs_ks_jkn_neto_persen = "' . $bpjs_ks_jkn_karyawan_persen . '",
                operator = "' . $email . '"
            where enroll_id = "'. $value3->enroll_id .'"');
        }
        

        return Response()->json($queryEmpBpjs);
    }

}
