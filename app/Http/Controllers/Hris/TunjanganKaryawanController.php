<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use App\Models\EmployeeAtribut;
use App\Models\TunjanganKaryawan;
use GuzzleHttp\Client;
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

/**
 * Class TunjanganKaryawanController
 * @package App\Http\Controllers\Hris
 */
class TunjanganKaryawanController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Tunjangan Karyawan';
    }

    public function index()
    {
        return View::make('hris/tunjangankaryawan', $this->data);
    }

    public function ajax_getnamatunjangan(Request $request)
    {
        if(request()->ajax()) {

            $columns = array(
                0 => 'kode_tunjangan'
            );

        $query =  TunjanganKaryawan::select('nama_tunjangan')
                        ->groupby('nama_tunjangan')
                        ->orderby('nama_tunjangan', 'asc')
                        ->get();

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = 0;
        $totalFiltered = 0;

        if(empty($request->input('search.value')))
        {
            $query =  TunjanganKaryawan::groupby('nama_tunjangan')
                ->orderby('nama_tunjangan', 'asc')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalData = TunjanganKaryawan::groupby('nama_tunjangan')
                ->orderby('nama_tunjangan', 'asc')
                ->count();
            $totalFiltered = $totalData;  

        } else {
            $search = $request->input('search.value');

            $query =  TunjanganKaryawan::where('nama_tunjangan','LIKE',"%{$search}%")
                ->groupby('nama_tunjangan')
                ->orderby('nama_tunjangan', 'asc')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalData = TunjanganKaryawan::where('nama_tunjangan','LIKE',"%{$search}%")
                ->groupby('nama_tunjangan')
                ->orderby('nama_tunjangan', 'asc')
                ->count();
            $totalFiltered = $totalData;                            

        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $nestedData['nama_tunjangan'] = $q->nama_tunjangan;
        
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

    public function ajax_data(Request $request)
    {

        if(request()->ajax()) {

        $columns = array(
            0 => 'kode_tunjangan',
            1 => 'periode_payroll',
            2 => 'enroll_id',
            3 => 'nik',
            4 => 'employee_name',
            5 => 'nama_tunjangan',
            6 => 'nilai_rupiah',
            7 => 'updated_at'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = 0;
        $totalFiltered = 0;

        if(empty($request->input('search.value')))
        {
            $query =  TunjanganKaryawan::selectRaw('
                    employee_atribut.enroll_id, employee_atribut.nik, employee_atribut.employee_name,
                    tunjangan_karyawan.kode_tunjangan, tunjangan_karyawan.periode_payroll,
                    tunjangan_karyawan.periode_tahun, tunjangan_karyawan.periode_bulan,
                    tunjangan_karyawan.nama_tunjangan, 
                    FORMAT(tunjangan_karyawan.nilai_rupiah, 2,"id_ID") nilai_rupiah_format,
                    tunjangan_karyawan.nilai_rupiah, tunjangan_karyawan.updated_at
                ')
                ->join('employee_atribut', 'employee_atribut.enroll_id', '=', 'tunjangan_karyawan.enroll_id')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalData = TunjanganKaryawan::selectRaw('
                    employee_atribut.enroll_id, employee_atribut.nik, employee_atribut.employee_name,
                    tunjangan_karyawan.kode_tunjangan, tunjangan_karyawan.periode_payroll,
                    tunjangan_karyawan.periode_tahun, tunjangan_karyawan.periode_bulan,
                    tunjangan_karyawan.nama_tunjangan, 
                    FORMAT(tunjangan_karyawan.nilai_rupiah, 2,"id_ID") nilai_rupiah_format,
                    tunjangan_karyawan.nilai_rupiah, tunjangan_karyawan.updated_at
                ')
                ->join('employee_atribut', 'employee_atribut.enroll_id', '=', 'tunjangan_karyawan.enroll_id')
                ->count();
            $totalFiltered = $totalData;  

        } else {
            $search = $request->input('search.value');

            $query =  TunjanganKaryawan::selectRaw('
                    employee_atribut.enroll_id, employee_atribut.nik, employee_atribut.employee_name,
                    tunjangan_karyawan.kode_tunjangan, tunjangan_karyawan.periode_payroll,
                    tunjangan_karyawan.periode_tahun, tunjangan_karyawan.periode_bulan,
                    tunjangan_karyawan.nama_tunjangan, 
                    FORMAT(tunjangan_karyawan.nilai_rupiah, 2,"id_ID") nilai_rupiah_format,
                    tunjangan_karyawan.nilai_rupiah, tunjangan_karyawan.updated_at
                ')
                ->where('periode_payroll','LIKE',"%{$search}%")
                ->orWhere('enroll_id','LIKE',"%{$search}%")
                ->orWhere('nik','LIKE',"%{$search}%")
                ->orWhere('employee_name','LIKE',"%{$search}%")
                ->orWhere('nama_tunjangan','LIKE',"%{$search}%")
                ->join('employee_atribut', 'employee_atribut.enroll_id', '=', 'tunjangan_karyawan.enroll_id')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalData = TunjanganKaryawan::selectRaw('
                    employee_atribut.enroll_id, employee_atribut.nik, employee_atribut.employee_name,
                    tunjangan_karyawan.kode_tunjangan, tunjangan_karyawan.periode_payroll,
                    tunjangan_karyawan.periode_tahun, tunjangan_karyawan.periode_bulan,
                    tunjangan_karyawan.nama_tunjangan, 
                    FORMAT(tunjangan_karyawan.nilai_rupiah, 2,"id_ID") nilai_rupiah_format,
                    tunjangan_karyawan.nilai_rupiah, tunjangan_karyawan.updated_at
                ')
                ->where('periode_payroll','LIKE',"%{$search}%")
                ->orWhere('enroll_id','LIKE',"%{$search}%")
                ->orWhere('nik','LIKE',"%{$search}%")
                ->orWhere('employee_name','LIKE',"%{$search}%")
                ->orWhere('nama_tunjangan','LIKE',"%{$search}%")
                ->join('employee_atribut', 'employee_atribut.enroll_id', '=', 'tunjangan_karyawan.enroll_id')
                ->count();
            $totalFiltered = $totalData;                            

        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $nestedData['kode_tunjangan'] = $q->kode_tunjangan;
                $nestedData['periode_payroll'] = $q->periode_payroll;
                $nestedData['periode_tahun'] = $q->periode_tahun;
                $nestedData['periode_bulan'] = $q->periode_bulan;
                $nestedData['enroll_id'] = $q->enroll_id;
                $nestedData['nik'] = $q->nik;
                $nestedData['employee_name'] = $q->employee_name;
                $nestedData['nama_tunjangan'] = $q->nama_tunjangan;
                $nestedData['nilai_rupiah_format'] = $q->nilai_rupiah_format;
                $nestedData['nilai_rupiah'] = $q->nilai_rupiah;
                $nestedData['operator'] = $q->operator;
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

    public function ajax_getemployee(Request $request)
    {

        if(request()->ajax()) {

        $columns = array(
            0 => 'enroll_id',
            1 => 'nik',
            2 => 'employee_name',
            3 => 'status_staff',
            4 => 'status_aktif',
            5 => 'join_date',
            6 => 'tanggal_resign'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = 0;
        $totalFiltered = 0;

        if(empty($request->input('search.value')))
        {
            $query =  EmployeeAtribut::whereRaw('enroll_id is not null')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalData = EmployeeAtribut::whereRaw('enroll_id is not null')
                ->count();
            $totalFiltered = $totalData;  

        } else {
            $search = $request->input('search.value');

            $query =  EmployeeAtribut::whereRaw('enroll_id is not null')
                ->where('nik','LIKE',"%{$search}%")
                ->orWhere('employee_name','LIKE',"%{$search}%")
                ->orWhere('status_staff','LIKE',"%{$search}%")
                ->orWhere('status_aktif','LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalData = EmployeeAtribut::whereRaw('enroll_id is not null')
                ->where('nik','LIKE',"%{$search}%")
                ->orWhere('employee_name','LIKE',"%{$search}%")
                ->orWhere('status_staff','LIKE',"%{$search}%")
                ->orWhere('status_aktif','LIKE',"%{$search}%")
                ->count();
            $totalFiltered = $totalData;                            

        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $nestedData['enroll_id'] = $q->enroll_id;
                $nestedData['nik'] = $q->nik;
                $nestedData['employee_name'] = $q->employee_name;
                $nestedData['status_aktif'] = $q->status_aktif;
                $nestedData['status_staff'] = $q->status_staff;
                $nestedData['sub_dept_name'] = $q->sub_dept_name;
                $nestedData['join_date'] = $q->employee_name;
                $nestedData['operator'] = $q->operator;
                $nestedData['join_date'] = substr($q->join_date, 0, 10) . " " . substr($q->join_date, 11, 5);
                $nestedData['tanggal_resign'] = substr($q->tanggal_resign, 0, 10) . " " . substr($q->tanggal_resign, 11, 5);
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

    public function replace(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $operator = $loggedAdmin->email;

        $kode_tunjangan = $request->kode_tunjangan;
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $enroll_id = $request->enroll_id;
        $nik = $request->nik;
        $employee_name = $request->employee_name;
        $nama_tunjangan = strtoupper($request->nama_tunjangan);
        $nilai_rupiah = $request->nilai_rupiah;

        if(!$kode_tunjangan) {
            $kode_tunjangan = $tahun . $bulan . sprintf('%05s', $enroll_id);
        }

        $findDT = TunjanganKaryawan::where('kode_tunjangan','=', $kode_tunjangan)->count();

        if($findDT > 0) {

            $query = DB::update('update tunjangan_karyawan set 
                        kode_tunjangan = "' . $kode_tunjangan . '",
                        periode_payroll = CONCAT(DATE_ADD( LAST_DAY( DATE_SUB( CONCAT( "' . $tahun . '-' . $bulan . '", "-26" ), INTERVAL 2 MONTH )), INTERVAL 26 DAY )," s/d ", DATE_ADD( LAST_DAY( DATE_SUB( CONCAT( "' . $tahun . '-' . $bulan . '", "-25" ), INTERVAL 1 MONTH )), INTERVAL 25 DAY )),
                        periode_tahun = "' . $tahun . '",
                        periode_bulan = "' . $bulan . '",
                        enroll_id = "' . $enroll_id . '",
                        nik = "' . $nik . '",
                        employee_name = "' . $employee_name . '",
                        nama_tunjangan = "' . $nama_tunjangan . '",
                        nilai_rupiah = ' . $nilai_rupiah . ',
                        operator = "' . $operator . '",
                        updated_at = now()
                    where kode_tunjangan = "' . $kode_tunjangan . '"');

        } else {
            $query = DB::insert('
                        insert into tunjangan_karyawan 
                            (kode_tunjangan, periode_payroll, periode_tahun,
                            periode_bulan, enroll_id, nik, employee_name, 
                            nama_tunjangan, nilai_rupiah, operator, created_at, updated_at) 
                        values 
                            ("' . $kode_tunjangan . '", CONCAT(DATE_ADD( LAST_DAY( DATE_SUB( CONCAT( "' . $tahun . '-' . $bulan . '", "-26" ), INTERVAL 2 MONTH )), INTERVAL 26 DAY )," s/d ", DATE_ADD( LAST_DAY( DATE_SUB( CONCAT( "' . $tahun . '-' . $bulan . '", "-25" ), INTERVAL 1 MONTH )), INTERVAL 25 DAY )),
                            "' . $tahun . '", "' . $bulan . '", "' . $enroll_id . '",
                            "' . $nik . '", "' . $employee_name . '", "' . $nama_tunjangan . '", ' . $nilai_rupiah . ',
                            "' . $operator . '", now(), now())');
        }
        info($query);
        return $query;
    }

    public function edit(Request $request)
    {

        $kode_tunjangan = $request->kode_tunjangan;
        $query =  TunjanganKaryawan::selectRaw('
                                    employee_atribut.enroll_id, employee_atribut.nik, employee_atribut.employee_name,
                                    employee_atribut.sub_dept_id, department_all.sub_dept_name,
                                    tunjangan_karyawan.kode_tunjangan, tunjangan_karyawan.kode_tunjangan, 
                                    tunjangan_karyawan.periode_payroll, tunjangan_karyawan.periode_tahun, 
                                    tunjangan_karyawan.periode_bulan, tunjangan_karyawan.nama_tunjangan, 
                                    tunjangan_karyawan.nilai_rupiah, tunjangan_karyawan.operator, tunjangan_karyawan.created_at, 
                                    tunjangan_karyawan.updated_at
                                ')
                                ->whereRaw('
                                    kode_tunjangan = "'. $kode_tunjangan . '"
                                ')
                                ->join('employee_atribut','employee_atribut.enroll_id','=','tunjangan_karyawan.enroll_id')
                                ->join('department_all','department_all.sub_dept_id','=','employee_atribut.sub_dept_id')
                                ->limit(1)
                                ->get();
        
        return $query;

    }

    public function destroy(Request $request)
    {
        $kode_tunjangan = $request->kode_tunjangan;

        $findDT = TunjanganKaryawan::where('kode_tunjangan','=', $kode_tunjangan)->count();

        if($findDT > 0) {
            $query = TunjanganKaryawan::where('kode_tunjangan','=',$kode_tunjangan)->delete();
        } else {
            $query = false;
        }

        return $query;
    }

}
