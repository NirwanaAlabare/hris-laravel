<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use App\Models\DataLembur;
use App\Models\MasterDataAbsenKehadiran;
use App\Models\EmployeeAtribut;
use App\Models\DepartmentAll;
use App\Models\WorkTimeTable;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Datatables;
use App\Exports\DepartmentAllExport;
use App\Exports\DataLemburExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;

/**
 * Class MdAbsenHadirController
 * @package App\Http\Controllers\Hris
 */
class DataLemburController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Master Data';
    }

    public function index()
    {
        $this->department = $this->ajax_getselectdepart();
        $this->selectemployee = $this->ajax_getallemployeeatribut();
        $this->allnfl = $this->ajax_getAllNomorFormLembur();
        $this->periode_lembur = $this->ajax_gettanggallembur();

        return View::make('hris/datalembur', $this->data);
    }

    public function ajax_gettanggallembur()
    {
        $query =  DB::select(DB::raw('
                    SELECT
                        CONCAT(
                            DATE_ADD( LAST_DAY( DATE_SUB( NOW(), INTERVAL 2 MONTH )), INTERVAL 26 DAY ),
                            " s/d ",
                            DATE_ADD( LAST_DAY( DATE_SUB( NOW(), INTERVAL 1 MONTH )), INTERVAL 25 DAY )
                        ) periode_tanggal 
                    UNION
                    SELECT
                        CONCAT(
                            DATE_ADD( LAST_DAY( DATE_SUB( CONCAT( substr( tanggal_berjalan, 1, 7 ), "-26" ), INTERVAL 2 MONTH )), INTERVAL 26 DAY ),
                            " s/d ",
                            DATE_ADD( LAST_DAY( DATE_SUB( CONCAT( substr( tanggal_berjalan, 1, 7 ), "-25" ), INTERVAL 1 MONTH )), INTERVAL 25 DAY ) 
                        ) periode_tanggal 
                    FROM
                        data_lembur 
                    GROUP BY
                        periode_tanggal 
                    ORDER BY
                        periode_tanggal DESC
                    '));
        
                    
        return $query;
    }

    public function ajax_getnomorspl(Request $request)
    {

        $periode_lembur = $request->periode_lembur;
        $array_periode_lembur = explode(' s/d ', $periode_lembur);
        $awal_bulan = substr($array_periode_lembur[0], 0, 10);
        $akhir_bulan = substr($array_periode_lembur[1], 0, 10);

        $nomor_form_lembur = $request->nomor_form_lembur;
        $arrayNomorSPL = str_replace(',','","',$nomor_form_lembur);

        if(!empty($nomor_form_lembur)) {
            $inNomorSPL = ' AND nomor_form_lembur IN ("' . $arrayNomorSPL . '")';
        } else {
            $inNomorSPL = '';
        }
        $query =  DataLembur::selectRaw('
                        CONCAT(nomor_form_lembur, " [ ", DATE_FORMAT(tanggal_berjalan, "%d %b %Y"), " ] => ", count(enroll_id), " karyawan") tanggal_nomor_spl,
                        nomor_form_lembur,
                        tanggal_berjalan,
                        mulai_jam_lembur,
                        akhir_jam_lembur,
                        jumlah_jam_istirahat,
                        jumlah_jam_lembur,
                        catatan
                        ')
                        ->whereRaw('
                            tanggal_berjalan BETWEEN "' . $awal_bulan . '" AND "' . $akhir_bulan . '"
                            ' . $inNomorSPL . '
                        ')
                        ->groupby('nomor_form_lembur')
                        ->orderby('nomor_form_lembur', 'desc')
                        ->get();
        return $query;

    }

    public function add_datalembur()
    {
        $this->department = $this->ajax_getselectdepart();
        $this->selectemployee = $this->ajax_getallemployeeatribut();
        return View::make('hris/add_datalembur', $this->data);
    }

    public function ajax_datahadir(Request $request)
    {
        $department_id = $request->department_id;
        $selectNomorFormLembur = $request->selectNomorFormLembur;
        $selectEmployeeID = $request->selectEmployeeID;

        $daterange1 = explode(" - ", $request->daterange1);
        $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
        $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));
        $dataNFL = "";
        $dataEmployee = "";
        $inNFL = "";
        $inEmployee = "";
        $inDepartmentID = "";

        $department_id = $request->department_id;
        $dataDepartment = "";

        if($department_id) {
            $dataDepartment = implode('","',$department_id);
            $inDepartmentID = '"' . $dataDepartment . '"';
        }

        if($selectNomorFormLembur) {
            $dataNFL = implode('","',$selectNomorFormLembur);
            $inNFL = '"' . $dataNFL . '"';
        }

        if($selectEmployeeID) {
            $dataEmployee = implode('","',$selectEmployeeID);
            $inEmployee = '"' . $dataEmployee . '"';
        }
        //info("Select Employee : " . $inEmployee);

        if(request()->ajax()) {

            $columns = array(
                0 => 'uuid',
                1 => 'employee_id',
                2 => 'nik',
                3 => 'enroll_id',
                4 => 'employee_name',
                5 => 'tanggal_berjalan',
                6 => 'kode_hari',
                7 => 'nama_hari',
                8 => 'kerjalibur',
                9 => 'jadwal_jam_kerja',
                10 => 'absensi_masuk_kerja',
                11 => 'absensi_pulang_kerja',
                12 => 'jumlah_jam_kerja',
                13 => 'status_absen',
                14 => 'tanggal_absen'
            );


            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if(empty($department_id) && empty($selectNomorFormLembur)) {
                $totalData = MasterDataAbsenKehadiran::
                whereRaw('
                    nomor_form_lembur is not null
                    and (substr(tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '")
                ')
                ->count();
                $totalFiltered = $totalData;

                $query = MasterDataAbsenKehadiran::
                whereRaw('
                    nomor_form_lembur is not null
                    and (substr(tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '")
                ')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
            } else if(empty($selectNomorFormLembur)) {
                $totalData = MasterDataAbsenKehadiran::
                whereRaw('
                    nomor_form_lembur is not null
                    and (substr(tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '")
                    and (department_id in (' . $inDepartmentID . '))
                    and (nik in (' . $inEmployee . '))
                ')
                ->count();
                $totalFiltered = $totalData;

                $query =  MasterDataAbsenKehadiran::
                whereRaw('
                    nomor_form_lembur is not null
                    and (substr(tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '")
                    and (department_id in (' . $inDepartmentID . '))
                    and (nik in (' . $inEmployee . '))
                ')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            } else if(empty($department_id)) {
                $totalData = MasterDataAbsenKehadiran::
                whereRaw('
                    nomor_form_lembur is not null
                    and (nomor_form_lembur in (' . $inNFL . '))
                    and (nik in (' . $inEmployee . '))
                ')
                ->count();
                $totalFiltered = $totalData;

                $query =  MasterDataAbsenKehadiran::
                whereRaw('
                    nomor_form_lembur is not null
                    and (nomor_form_lembur in (' . $inNFL . '))
                    and (nik in (' . $inEmployee . '))
                ')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
            }

            $data = array();
            if(!empty($query))
            {
                foreach ($query as $q)
                {
                    $tanggal_absen = $q->tanggal_absen;
                    $kode_hari = $q->kode_hari;
                    $kerjalibur = "KERJA";
                    if($tanggal_absen) {
                        $kerjalibur = "KERJA";
                    } else {
                        switch ($kode_hari) {
                            case '5':
                                $kerjalibur = "LIBUR";
                                break;
                            case '6':
                                $kerjalibur = "LIBUR";
                                break;
                        }
                    }

                    $nestedData['uuid'] = $q->uuid;
                    $nestedData['employee_id'] = $q->employee_id;
                    $nestedData['nik'] = $q->nik;
                    $nestedData['enroll_id'] = $q->enroll_id;
                    $nestedData['employee_name'] = $q->employee_name;
                    $nestedData['tanggal_berjalan'] = $q->tanggal_berjalan;
                    $nestedData['kode_hari'] = $q->kode_hari;
                    $nestedData['nama_hari'] = $q->nama_hari;
                    $nestedData['kerjalibur'] = $kerjalibur;
                    $nestedData['jadwal_jam_kerja'] = $q->mulai_jam_kerja . " s/d " . $q->akhir_jam_kerja;
                    $nestedData['mulai_jam_kerja'] = substr($q->mulai_jam_kerja, 0, 5);
                    $nestedData['akhir_jam_kerja'] = substr($q->akhir_jam_kerja, 0, 5);
                    $nestedData['absen_masuk_kerja'] = substr($q->absen_masuk_kerja, 0, 5);
                    $nestedData['absen_pulang_kerja'] = substr($q->absen_pulang_kerja, 0, 5);
                    $nestedData['absen_dt_datang_terlambat'] = $q->absen_dt_datang_terlambat;
                    $nestedData['absen_dtpc_datang_terlambat_pulang_cepat'] = $q->absen_dtpc_datang_terlambat_pulang_cepat;
                    $nestedData['jumlah_jam_kerja'] = $q->jumlah_jam_kerja;
                    $nestedData['status_absen'] = strtoupper($q->status_absen);
                    $nestedData['absen_alasan'] = $q->absen_alasan;
                    $nestedData['nomor_form_lembur'] = $q->nomor_form_lembur;
                    $nestedData['updated_at'] = substr($q->updated_at, 0, 10) . " " . substr($q->updated_at, 11, 8);
                    $nestedData['operator'] = $q->operator;
                    $nestedData['catatan_hrd'] = $q->catatan_hrd;
                    $nestedData['site_nirwana_name'] = $q->site_nirwana_name;
                    $nestedData['department_name'] = $q->department_name;
                    $nestedData['sub_dept_name'] = $q->sub_dept_name;
                    $nestedData['tanggal_absen'] = $q->tanggal_absen;
                    $nestedData['jumlah_jam_lembur'] = $q->jumlah_jam_lembur;
                    $nestedData['jumlah_jam_istirahat_lembur'] = $q->jumlah_jam_istirahat_lembur;
                    $nestedData['mulai_jam_lembur'] = date('Y-m-d H:i:s', strtotime($q->mulai_jam_lembur));
                    $nestedData['akhir_jam_lembur'] = date('Y-m-d H:i:s', strtotime($q->akhir_jam_lembur));
                    $nestedData['status_lembur'] = $q->status_lembur;

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

    private function ajax_getselectdepart()
    {
        $query =  DepartmentAll::selectRaw('department_id,
                                           concat("[",CASE WHEN site_nirwana_id="ADR208"
                                                            THEN "NAG" WHEN site_nirwana_id="ADR210"
                                                            THEN "SGT" ELSE "UNKNOWN SITE" END,"] "
                                                  ,department_name) department_name')
                                 ->groupby('department_name')
                                 ->orderby('department_name', 'asc')
                                 ->get();

        return $query;

    }

    public function ajax_getselectsubdept(Request $request)
    {
        $department_id = $request->department_id;
        $query =  DepartmentAll::where('department_id','=',$department_id)
                                 ->groupby('sub_dept_id')
                                 ->orderby('sub_dept_name', 'asc')
                                 ->pluck('sub_dept_id','sub_dept_name');
        return $query;

    }

    public function ajax_getallemployeeatribut()
    {
        $query =  EmployeeAtribut::selectRaw('enroll_id no_pin, nik, employee_name,
                                           concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                                    ->groupby('nik')
                                    ->orderby('employee_name', 'asc')
                                    ->get();
        return $query;

    }

    public function ajax_getselectemployee(Request $request)
    {
        $department_id = $request->department_id;

        if($department_id) {
            $query =  EmployeeAtribut::selectRaw('enroll_id no_pin, nik, employee_name,
                                            concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                                        ->where('department_id','=',$department_id)
                                        ->groupby('nik')
                                        ->orderby('employee_name', 'asc')
                                        ->get();

            return $query;
        } else {
            $query =  EmployeeAtribut::selectRaw('enroll_id no_pin, nik, employee_name,
                                            concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                                        ->groupby('nik')
                                        ->orderby('employee_name', 'asc')
                                        ->get();

            return $query;
        }

        //return $query;

    }

    public function form_datalembur(Request $request)
    {
        $uuid = $request->editData;

        $query =  MasterDataAbsenKehadiran::
                    selectRaw('uuid, employee_id, nik, employee_name, employee_id, kode_hari, nama_hari,
                                tanggal_berjalan, enroll_id, department_id, department_name,
                                site_nirwana_id, site_nirwana_name, catatan_hrd,
                                sub_dept_id, sub_dept_name, shift_work_id, time_table_name,
                                case when absen_ok_hadir IS NOT NULL then "KERJA" ELSE "LIBUR" END kerjalibur,
                                concat(time_table_name, " [", substr(mulai_jam_kerja, 1, 5), " s/d ", substr(akhir_jam_kerja, 1, 5), "]") jadwal_jam_kerja,
                                mulai_jam_kerja, akhir_jam_kerja, department_id, department_name, sub_dept_id, sub_dept_name,
                                substr(absen_masuk_kerja, 1, 5) absen_masuk_kerja, substr(absen_pulang_kerja, 1, 5) absen_pulang_kerja,
                                concat(DATE_FORMAT(mulai_jam_lembur, "%Y/%m/%d %H:%i"), " - ", DATE_FORMAT(akhir_jam_lembur, "%Y/%m/%d %H:%i")) waktu_jam_lembur,
                                nomor_form_lembur, status_lembur, jumlah_jam_lembur, mulai_jam_lembur, akhir_jam_lembur, date_format(updated_at, "%Y-%m-%d %H:%i:%s") updated_at')
                    ->where('uuid','=',$uuid)->first();

        return Response()->json($query);
    }

    public function getEmployeeLembur(Request $request)
    {
        $tanggal_lembur = $request->tanggal_lembur;
        $selectEmployeeID = $request->selectEmployeeID;
        $inEmp = '"' . implode('","', $selectEmployeeID) . '"';
        //info("inEmp : " . $inEmp);

        //$inSelectEmpID = var_dump(explode("','", $selectEmployeeID));
        //info("selectEmployeeID : " . $selectEmployeeID[0]);

        $query =  MasterDataAbsenKehadiran::
                selectRaw('uuid, employee_id, nik, employee_name, employee_id, kode_hari, nama_hari,
                            tanggal_berjalan, tanggal_absen, enroll_id, department_id, department_name,
                            site_nirwana_id, site_nirwana_name, catatan_hrd,
                            sub_dept_id, sub_dept_name, shift_work_id, time_table_name,
                            case when absen_ok_hadir IS NOT NULL then "KERJA" ELSE "LIBUR" END kerjalibur,
                            concat(time_table_name, " [", substr(mulai_jam_kerja, 1, 5), " s/d ", substr(akhir_jam_kerja, 1, 5), "]") jadwal_jam_kerja,
                            mulai_jam_kerja, akhir_jam_kerja, department_id, department_name, sub_dept_id, sub_dept_name,
                            substr(absen_masuk_kerja, 1, 5) absen_masuk_kerja, substr(absen_pulang_kerja, 1, 5) absen_pulang_kerja,
                            nomor_form_lembur, status_lembur, jumlah_jam_lembur, mulai_jam_lembur, akhir_jam_lembur, date_format(updated_at, "%Y-%m-%d %H:%i:%s") updated_at')
                ->whereRaw('
                    DATE_FORMAT(tanggal_berjalan, "%Y-%m-%d") = DATE_FORMAT("' . $tanggal_lembur . '", "%Y-%m-%d")
                    and nik in (' . $inEmp . ')
                ')
                ->get();
        //info("Query : " . $query);
        return Response()->json($query);
    }

    public function ajax_getemployeselectdeptid(Request $request)
    {
        $department_id = $request->department_id;
        $dataDepartment = "";

        if($department_id) {
            $dataDepartment = implode('","',$department_id);
        }
        $inDepartmentID = '"' . $dataDepartment . '"';

        $query =  EmployeeAtribut::
                        selectRaw('enroll_id no_pin, nik, employee_name,
                                        concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                        ->whereRaw('
                            department_id in (' . $inDepartmentID . ')
                        ')
                        ->groupby('nik')
                        ->orderby('employee_name', 'asc')
                        ->get();
        //info('Query :' . $query);
        return $query;

    }

    public function ajax_getemployeselectnfl(Request $request)
    {
        $nomor_form_lembur = $request->nomor_form_lembur;
        $dataNFL = "";

        if($nomor_form_lembur) {
            $dataNFL = implode('","',$nomor_form_lembur);
        }
        $inNFL = '"' . $dataNFL . '"';

        $query =  MasterDataAbsenKehadiran::
                        selectRaw('enroll_id no_pin, nik, employee_name,
                                        concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                        ->whereRaw('
                            nomor_form_lembur in (' . $inNFL . ')
                        ')
                        ->groupby('nik')
                        ->orderby('employee_name', 'asc')
                        ->get();

        return $query;

    }

    public function store_multi(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        $arrayHtml = $request->arrayHtml;

        $kodelembur = "SPL/HR";
        $thnbln = date("ym");

        $getlastnomorform =  DataLembur::select('nomor_form_lembur')
                                            ->groupby('nomor_form_lembur')
                                            ->orderby('nomor_form_lembur', 'desc')
                                            ->first();


        if($getlastnomorform == "") {
            //info("Count : Kosong");
            $nomor = "0000";
         } else {
            $nomor = $getlastnomorform->nomor_form_lembur;
        }

        $nomorform = str_pad(substr($nomor, -4) + 1,4,"0",STR_PAD_LEFT);
        $nomor_form_lembur = $kodelembur . "/" . $thnbln . "/" . $nomorform;
        //info("Nomor Form Lembur : " . $nomor_form_lembur);
        foreach ($arrayHtml as $key => $value) {

            try {
                DataLembur::create([
                    'uuid' => Str::uuid(),
                    'uuid_master' => $value['uuid_master'],
                    'tanggal_berjalan' => $value['tanggal_berjalan'],
                    'tanggal_absen' => $value['tanggal_absen'],
                    'nomor_form_lembur' => $nomor_form_lembur,
                    'shift_work_id' => $value['shift_work_id'],
                    'kode_hari' => $value['kode_hari'],
                    'nama_hari' => $value['nama_hari'],
                    'time_table_name' => $value['time_table_name'],
                    'mulai_jam_kerja' => $value['mulai_jam_kerja'],
                    'akhir_jam_kerja' => $value['akhir_jam_kerja'],
                    'absen_masuk_kerja' => $value['absen_masuk_kerja'],
                    'absen_pulang_kerja' => $value['absen_pulang_kerja'],
                    'enroll_id' => $value['enroll_id'],
                    'nik' => $value['nik'],
                    'employee_id' => $value['employee_id'],
                    'employee_name' => $value['employee_name'],
                    'site_nirwana_id' => $value['site_nirwana_id'],
                    'site_nirwana_name' => $value['site_nirwana_name'],
                    'department_id' => $value['department_id'],
                    'department_name' => $value['department_name'],
                    'sub_dept_id' => $value['sub_dept_id'],
                    'sub_dept_name' => $value['sub_dept_name'],
                    'mulai_jam_lembur' => $value['mulai_jam_lembur_edit'],
                    'akhir_jam_lembur' => $value['akhir_jam_lembur_edit'],
                    'jumlah_jam_lembur' => $value['jumlah_jam_lembur_edit'],
                    'jumlah_jam_istirahat' => $value['jumlah_jam_istirahat_edit'],
                    'catatan' => $value['catatan_hrd_edit'],
                    'operator' => $email
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            MasterDataAbsenKehadiran::where('uuid','=',$value['uuid_master'])
            ->update([
                'nomor_form_lembur' => $nomor_form_lembur,
                'kelebihan_jam_kerja_l1' => '0',
                'kelebihan_jam_kerja_l2' => '0',
                'kelebihan_jam_kerja_l3' => '0',
                'kelebihan_jam_kerja_l4' => '0',
                'mulai_jam_lembur' => $value['mulai_jam_lembur_edit'],
                'akhir_jam_lembur' => $value['akhir_jam_lembur_edit'],
                'jumlah_jam_lembur' => $value['jumlah_jam_lembur_edit'],
                'jumlah_jam_lembur_approved' => $value['jumlah_jam_lembur_edit'],
                'jumlah_jam_istirahat_lembur' => $value['jumlah_jam_istirahat_edit'],
                'catatan_hrd' => $value['catatan_hrd_edit'],
                'operator' => $email
            ]);

        }

        return Response()->json($nomor_form_lembur);

    }

    public function update(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        $uuid_master = $request->uuid_master;
        $nomor_form_lembur = $request->nomor_form_lembur;
        $jumlah_jam_istirahat = $request->jumlah_jam_istirahat;
        $jumlah_jam_lembur = $request->jumlah_jam_lembur;
        $mulai_jam_lembur = $request->mulai_jam_lembur;
        $akhir_jam_lembur = $request->akhir_jam_lembur;
        $catatan_hrd = $request->catatan_hrd;

        $queryMaster = MasterDataAbsenKehadiran::where('uuid','=',$uuid_master)
        ->update([
            'jumlah_jam_istirahat' => $jumlah_jam_istirahat,
            'jumlah_jam_lembur' => $jumlah_jam_lembur,
            'jumlah_jam_lembur_approved' => $jumlah_jam_lembur,
            'mulai_jam_lembur' => $mulai_jam_lembur,
            'akhir_jam_lembur' => $akhir_jam_lembur,
            'catatan_hrd' => $catatan_hrd,
            'operator' => $email
        ]);

        $queryDetail = DataLembur::where('uuid_master','=',$uuid_master)
        ->update([
            'jumlah_jam_istirahat' => $jumlah_jam_istirahat,
            'jumlah_jam_lembur' => $jumlah_jam_lembur,
            'mulai_jam_lembur' => $mulai_jam_lembur,
            'akhir_jam_lembur' => $akhir_jam_lembur,
            'catatan' => $catatan_hrd,
            'operator' => $email
        ]);

        return Response()->json($queryDetail);

    }

    public function delete(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        $uuid_master = $request->uuid_master;
        //info("Delete hapus .....");
        MasterDataAbsenKehadiran::where('uuid','=',$uuid_master)
                                            ->update([
                                                'nomor_form_lembur' => null,
                                                'jumlah_jam_istirahat' => null,
                                                'jumlah_jam_lembur' => null,
                                                'mulai_jam_lembur' => null,
                                                'akhir_jam_lembur' => null,
                                                'catatan_hrd' => null,
                                                'operator' => $email
                                            ]);

        $queryDel = DataLembur::where('uuid_master','=',$uuid_master)->delete();

        return Response()->json($queryDel);

    }

    public function ajax_getAllNomorFormLembur()
    {
        $query =  MasterDataAbsenKehadiran::
                selectRaw('uuid, employee_id, nik, employee_name, employee_id, kode_hari, nama_hari,
                            tanggal_berjalan, tanggal_absen, enroll_id, department_id, department_name,
                            site_nirwana_id, site_nirwana_name, catatan_hrd,
                            sub_dept_id, sub_dept_name, shift_work_id, time_table_name,
                            case when absen_ok_hadir IS NOT NULL then "KERJA" ELSE "LIBUR" END kerjalibur,
                            concat(time_table_name, " [", substr(mulai_jam_kerja, 1, 5), " s/d ", substr(akhir_jam_kerja, 1, 5), "]") jadwal_jam_kerja,
                            mulai_jam_kerja, akhir_jam_kerja, department_id, department_name, sub_dept_id, sub_dept_name,
                            substr(absen_masuk_kerja, 1, 5) absen_masuk_kerja, substr(absen_pulang_kerja, 1, 5) absen_pulang_kerja,
                            nomor_form_lembur, status_lembur, jumlah_jam_lembur, mulai_jam_lembur, akhir_jam_lembur, date_format(updated_at, "%Y-%m-%d %H:%i:%s") updated_at')
                ->whereRaw('
                    nomor_form_lembur is not null
                ')
                ->groupby('nomor_form_lembur')
                ->orderby('nomor_form_lembur', 'desc')
                ->get();

        return $query;
    }

    public function ajax_getNomorFormLembur(Request $request)
    {
        $nomor_form_lembur = $request->nomor_form_lembur;
        $dataNomorFormLembur = "";

        if($nomor_form_lembur) {
            $dataNomorFormLembur = implode('","',$nomor_form_lembur);
        }
        $inNomorFormLembur = '"' . $dataNomorFormLembur . '"';


        $query =  MasterDataAbsenKehadiran::
                selectRaw('uuid, employee_id, nik, employee_name, employee_id, kode_hari, nama_hari,
                            tanggal_berjalan, tanggal_absen, enroll_id, department_id, department_name,
                            site_nirwana_id, site_nirwana_name, catatan_hrd,
                            sub_dept_id, sub_dept_name, shift_work_id, time_table_name,
                            case when absen_ok_hadir IS NOT NULL then "KERJA" ELSE "LIBUR" END kerjalibur,
                            concat(time_table_name, " [", substr(mulai_jam_kerja, 1, 5), " s/d ", substr(akhir_jam_kerja, 1, 5), "]") jadwal_jam_kerja,
                            mulai_jam_kerja, akhir_jam_kerja, department_id, department_name, sub_dept_id, sub_dept_name,
                            substr(absen_masuk_kerja, 1, 5) absen_masuk_kerja, substr(absen_pulang_kerja, 1, 5) absen_pulang_kerja,
                            nomor_form_lembur, status_lembur, jumlah_jam_lembur, mulai_jam_lembur, akhir_jam_lembur, date_format(updated_at, "%Y-%m-%d %H:%i:%s") updated_at')
                ->whereRaw('
                    nomor_form_lembur in (' . $inNomorFormLembur . ')
                ')
                ->groupby('nomor_form_lembur')
                ->orderby('nomor_form_lembur', 'desc')
                ->get();

        return Response()->json($query);
    }

    public function ajax_exportexcel(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');

        $periode_lembur = $request->input('periode_lembur');

        if($request->input('selectPosisiName')) {
            $posisi = $request->input('selectPosisiName');
        } else {
            $posisi = '';
        }

       /*  $fileName = 'DepartmentAll.xlsx';
        return (new DepartmentAllExport)->download($fileName); */

        $fileName = 'DataLemburKaryawan_' . time() . '.xlsx';
        return (new DataLemburExport)->exportParams($periode_lembur)->download($fileName);

    }

    public function ajax_gettanggalnfl(Request $request)
    {
        $daterange1 = explode(" - ", $request->daterange1);
        $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
        $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));

        $query =  MasterDataAbsenKehadiran::
                selectRaw('uuid, employee_id, nik, employee_name, employee_id, kode_hari, nama_hari,
                            tanggal_berjalan, tanggal_absen, enroll_id, department_id, department_name,
                            site_nirwana_id, site_nirwana_name, catatan_hrd,
                            sub_dept_id, sub_dept_name, shift_work_id, time_table_name,
                            case when absen_ok_hadir IS NOT NULL then "KERJA" ELSE "LIBUR" END kerjalibur,
                            concat(time_table_name, " [", substr(mulai_jam_kerja, 1, 5), " s/d ", substr(akhir_jam_kerja, 1, 5), "]") jadwal_jam_kerja,
                            mulai_jam_kerja, akhir_jam_kerja, department_id, department_name, sub_dept_id, sub_dept_name,
                            substr(absen_masuk_kerja, 1, 5) absen_masuk_kerja, substr(absen_pulang_kerja, 1, 5) absen_pulang_kerja,
                            nomor_form_lembur, status_lembur, jumlah_jam_lembur, mulai_jam_lembur, akhir_jam_lembur, date_format(updated_at, "%Y-%m-%d %H:%i:%s") updated_at')
                ->whereRaw('
                    substr(tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                    and nomor_form_lembur is not null
                ')
                ->groupby('nomor_form_lembur')
                ->orderby('nomor_form_lembur', 'desc')
                ->get();

        return Response()->json($query);
    }

    /*    Screen lock controller.When screen lock button from menu is cliked this controller is called.
    *     lock variable is set to 1 when screen is locked.SET to 0  if you dont want screen variable
    */

    public function screenlock()
    {
        Session::put('lock', '1');
        return View::make('admin/screen_lock', $this->data);
    }

    public function ajax_getemployee(Request $request)
    {

        if(request()->ajax()) {

        $columns = array(
            0 => 'enroll_id',
            1 => 'nik',
            2 => 'employee_name',
            3 => 'sub_dept_name',
            4 => 'status_staff',
            5 => 'status_aktif',
            6 => 'join_date',
            7 => 'tanggal_resign'
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
                ->orWhere('sub_dept_name','LIKE',"%{$search}%")
                ->orWhere('status_staff','LIKE',"%{$search}%")
                ->orWhere('status_aktif','LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalData = EmployeeAtribut::whereRaw('enroll_id is not null')
                ->where('nik','LIKE',"%{$search}%")
                ->orWhere('employee_name','LIKE',"%{$search}%")
                ->orWhere('sub_dept_name','LIKE',"%{$search}%")
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

    public function ajax_getsubdept(Request $request)
    {

        if(request()->ajax()) {

        $columns = array(
            0 => 'site_nirwana_name',
            1 => 'department_name',
            2 => 'sub_dept_name'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = 0;
        $totalFiltered = 0;

        if(empty($request->input('search.value')))
        {
            $query =  DepartmentAll::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalData = DepartmentAll::count();
            $totalFiltered = $totalData;

        } else {
            $search = $request->input('search.value');

            $query =  DepartmentAll::where('site_nirwana_name','LIKE',"%{$search}%")
                ->orWhere('department_name','LIKE',"%{$search}%")
                ->orWhere('sub_dept_name','LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalData = DepartmentAll::where('site_nirwana_name','LIKE',"%{$search}%")
                ->orWhere('department_name','LIKE',"%{$search}%")
                ->orWhere('sub_dept_name','LIKE',"%{$search}%")
                ->count();
            $totalFiltered = $totalData;

        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $nestedData['site_nirwana_id'] = $q->site_nirwana_id;
                $nestedData['site_nirwana_name'] = $q->site_nirwana_name;
                $nestedData['department_id'] = $q->department_id;
                $nestedData['department_name'] = $q->department_name;
                $nestedData['sub_dept_id'] = $q->sub_dept_id;
                $nestedData['sub_dept_name'] = $q->sub_dept_name;

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
        $email = $loggedAdmin->email;

        $enroll_id = $request->enroll_id;
        $tanggal_berjalan = $request->tanggal_berjalan;
        $nomor_form_lembur = $request->nomor_form_lembur;
        $mulai_jam_lembur = $request->mulai_jam_lembur;
        $explodeMulaiLembur = explode(" ", $mulai_jam_lembur);
        $tanggal_lembur = substr($explodeMulaiLembur[0], 6, 4) . '-' . substr($explodeMulaiLembur[0], 3, 2) . '-' . substr($explodeMulaiLembur[0], 0, 2);
        $mulai_jam_lembur = substr($explodeMulaiLembur[0], 6, 4) . '-' . substr($explodeMulaiLembur[0], 3, 2) . '-' . substr($explodeMulaiLembur[0], 0, 2) . " " . $explodeMulaiLembur[1];

        $akhir_jam_lembur = $request->akhir_jam_lembur;
        $explodeAkhirLembur = explode(" ", $akhir_jam_lembur);
        $akhir_jam_lembur = substr($explodeAkhirLembur[0], 6, 4) . '-' . substr($explodeAkhirLembur[0], 3, 2) . '-' . substr($explodeAkhirLembur[0], 0, 2) . " " . $explodeAkhirLembur[1];
        
        $jumlah_jam_lembur = $request->jumlah_jam_lembur;
        $jumlah_jam_istirahat = $request->jumlah_jam_istirahat;
        $selectEmployee = $request->selectEmployee;
        $enroll_id_array = explode(",", $selectEmployee);
        $selectSubDept = $request->selectSubDept;
        $sub_dept_id_array = explode(",", $selectSubDept);
        $catatan = $request->catatan;

        if (empty($request->nomor_form_lembur)) {
            $kodelembur = "SPL/HR";
            $thnbln = date("ym");

            $getlastnomorform =  DataLembur::select('nomor_form_lembur')
                                                ->groupby('nomor_form_lembur')
                                                ->orderby('nomor_form_lembur', 'desc')
                                                ->first();

            if($getlastnomorform == "") {
                //info("Count : Kosong");
                $nomor = "0000";
            } else {
                $nomor = $getlastnomorform->nomor_form_lembur;
            }

            $nomorform = str_pad(substr($nomor, -4) + 1,4,"0",STR_PAD_LEFT);
            $nomor_form_lembur = $kodelembur . "/" . $thnbln . "/" . $nomorform;

            //info("Nomor Form Lembur : " . $nomor_form_lembur);

            if (!empty($enroll_id_array[0])) {

                foreach ($enroll_id_array as $key => $value) {
                    
                    $queryMaster =  MasterDataAbsenKehadiran::selectRaw('
                                    master_data_absen_kehadiran.uuid,
                                    master_data_absen_kehadiran.nomor_form_lembur,
                                    master_data_absen_kehadiran.tanggal_berjalan,
                                    master_data_absen_kehadiran.kode_hari,
                                    master_data_absen_kehadiran.nama_hari,
                                    master_data_absen_kehadiran.mulai_jam_kerja,
                                    master_data_absen_kehadiran.akhir_jam_kerja,
                                    master_data_absen_kehadiran.absen_masuk_kerja,
                                    master_data_absen_kehadiran.absen_pulang_kerja,
                                    master_data_absen_kehadiran.enroll_id,
                                    employee_atribut.nik,
                                    employee_atribut.employee_id,
                                    employee_atribut.employee_name,
                                    employee_atribut.site_nirwana_id,
                                    department_all.site_nirwana_name,
                                    employee_atribut.department_id,
                                    department_all.department_name,
                                    employee_atribut.sub_dept_id,
                                    department_all.sub_dept_name
                                ')
                                ->whereRaw('
                                    master_data_absen_kehadiran.enroll_id = "' . $value . '"
                                    AND master_data_absen_kehadiran.tanggal_berjalan = "' . $tanggal_lembur . '"
                                ')
                                ->join('employee_atribut', 'employee_atribut.enroll_id', '=', 'master_data_absen_kehadiran.enroll_id')
                                ->leftJoin('department_all', 'department_all.sub_dept_id', '=', 'master_data_absen_kehadiran.sub_dept_id')
                                ->orderBy('employee_atribut.employee_name','asc')
                                ->get();
                                
                    foreach ($queryMaster as $key1 => $value1) {
                        
                        if(empty($value1['nomor_form_lembur'])) {
                            $queryDataLembur = DataLembur::create([
                                'uuid' => Str::uuid(),
                                'uuid_master' => $value1['uuid'],
                                'tanggal_berjalan' => $value1['tanggal_berjalan'],
                                'tanggal_absen' => $value1['tanggal_berjalan'],
                                'nomor_form_lembur' => $nomor_form_lembur,
                                'kode_hari' => $value1['kode_hari'],
                                'nama_hari' => $value1['nama_hari'],
                                'mulai_jam_kerja' => $value1['mulai_jam_kerja'],
                                'akhir_jam_kerja' => $value1['akhir_jam_kerja'],
                                'absen_masuk_kerja' => $value1['absen_masuk_kerja'],
                                'absen_pulang_kerja' => $value1['absen_pulang_kerja'],
                                'enroll_id' => $value1['enroll_id'],
                                'nik' => $value1['nik'],
                                'employee_id' => $value1['employee_id'],
                                'employee_name' => $value1['employee_name'],
                                'site_nirwana_id' => $value1['site_nirwana_id'],
                                'site_nirwana_name' => $value1['site_nirwana_name'],
                                'department_id' => $value1['department_id'],
                                'department_name' => $value1['department_name'],
                                'sub_dept_id' => $value1['sub_dept_id'],
                                'sub_dept_name' => $value1['sub_dept_name'],
                                'mulai_jam_lembur' => $mulai_jam_lembur,
                                'akhir_jam_lembur' => $akhir_jam_lembur,
                                'jumlah_jam_lembur' => $jumlah_jam_lembur,
                                'jumlah_jam_istirahat' => $jumlah_jam_istirahat,
                                'catatan' => strtoupper($catatan),
                                'operator' => $email
                            ]);
                        } else {
                            $queryDataLembur = DataLembur::whereRaw('
                                tanggal_berjalan = "' . $value1['tanggal_berjalan'] . '"
                                AND enroll_id = "' . $value . '"
                            ')
                            ->update([
                                'nomor_form_lembur' => $nomor_form_lembur,
                                'mulai_jam_kerja' => $value1['mulai_jam_kerja'],
                                'akhir_jam_kerja' => $value1['akhir_jam_kerja'],
                                'absen_masuk_kerja' => $value1['absen_masuk_kerja'],
                                'absen_pulang_kerja' => $value1['absen_pulang_kerja'],
                                'mulai_jam_lembur' => $mulai_jam_lembur,
                                'akhir_jam_lembur' => $akhir_jam_lembur,
                                'jumlah_jam_lembur' => $jumlah_jam_lembur,
                                'jumlah_jam_istirahat' => $jumlah_jam_istirahat,
                                'catatan' => strtoupper($catatan),
                                'operator' => $email
                            ]);
                        }

                        if($queryDataLembur) {
                            $query = MasterDataAbsenKehadiran::whereRaw('
                                tanggal_berjalan = "' . $value1['tanggal_berjalan'] . '"
                                AND enroll_id = "' . $value . '"
                            ')
                            ->update([
                                'nomor_form_lembur' => $nomor_form_lembur,
                                'kelebihan_jam_kerja_l1' => '0',
                                'kelebihan_jam_kerja_l2' => '0',
                                'kelebihan_jam_kerja_l3' => '0',
                                'kelebihan_jam_kerja_l4' => '0',
                                'mulai_jam_lembur' => $mulai_jam_lembur,
                                'akhir_jam_lembur' => $akhir_jam_lembur,
                                'jumlah_jam_lembur' => $jumlah_jam_lembur,
                                'jumlah_jam_lembur_approved' => $jumlah_jam_lembur,
                                'jumlah_jam_istirahat_lembur' => $jumlah_jam_istirahat,
                                'catatan_hrd' => strtoupper($catatan),
                                'operator' => $email
                            ]);
                        }
                    }
                }
            } else {
                if (!empty($sub_dept_id_array[0])) {

                    foreach ($sub_dept_id_array as $key => $value) {

                        $queryMaster =  MasterDataAbsenKehadiran::selectRaw('
                                        master_data_absen_kehadiran.uuid,
                                        master_data_absen_kehadiran.nomor_form_lembur,
                                        master_data_absen_kehadiran.tanggal_berjalan,
                                        master_data_absen_kehadiran.kode_hari,
                                        master_data_absen_kehadiran.nama_hari,
                                        master_data_absen_kehadiran.mulai_jam_kerja,
                                        master_data_absen_kehadiran.akhir_jam_kerja,
                                        master_data_absen_kehadiran.absen_masuk_kerja,
                                        master_data_absen_kehadiran.absen_pulang_kerja,
                                        master_data_absen_kehadiran.enroll_id,
                                        employee_atribut.nik,
                                        employee_atribut.employee_id,
                                        employee_atribut.employee_name,
                                        employee_atribut.site_nirwana_id,
                                        department_all.site_nirwana_name,
                                        employee_atribut.department_id,
                                        department_all.department_name,
                                        employee_atribut.sub_dept_id,
                                        department_all.sub_dept_name
                                    ')
                                    ->whereRaw('
                                        master_data_absen_kehadiran.enroll_id is not null
                                        AND employee_atribut.sub_dept_id = "' . $value . '"
                                        AND master_data_absen_kehadiran.tanggal_berjalan = "' . $tanggal_lembur . '"
                                    ')
                                    ->join('employee_atribut', 'employee_atribut.enroll_id', '=', 'master_data_absen_kehadiran.enroll_id')
                                    ->leftJoin('department_all', 'department_all.sub_dept_id', '=', 'master_data_absen_kehadiran.sub_dept_id')
                                    ->orderBy('employee_name','asc')
                                    ->get();

                        foreach ($queryMaster as $key1 => $value1) {

                            if(empty($value1['nomor_form_lembur'])) {
                                $queryDataLembur = DataLembur::create([
                                    'uuid' => Str::uuid(),
                                    'uuid_master' => $value1['uuid'],
                                    'tanggal_berjalan' => $value1['tanggal_berjalan'],
                                    'tanggal_absen' => $value1['tanggal_berjalan'],
                                    'nomor_form_lembur' => $nomor_form_lembur,
                                    'kode_hari' => $value1['kode_hari'],
                                    'nama_hari' => $value1['nama_hari'],
                                    'mulai_jam_kerja' => $value1['mulai_jam_kerja'],
                                    'akhir_jam_kerja' => $value1['akhir_jam_kerja'],
                                    'absen_masuk_kerja' => $value1['absen_masuk_kerja'],
                                    'absen_pulang_kerja' => $value1['absen_pulang_kerja'],
                                    'enroll_id' => $value1['enroll_id'],
                                    'nik' => $value1['nik'],
                                    'employee_id' => $value1['employee_id'],
                                    'employee_name' => $value1['employee_name'],
                                    'site_nirwana_id' => $value1['site_nirwana_id'],
                                    'site_nirwana_name' => $value1['site_nirwana_name'],
                                    'department_id' => $value1['department_id'],
                                    'department_name' => $value1['department_name'],
                                    'sub_dept_id' => $value1['sub_dept_id'],
                                    'sub_dept_name' => $value1['sub_dept_name'],
                                    'mulai_jam_lembur' => $mulai_jam_lembur,
                                    'akhir_jam_lembur' => $akhir_jam_lembur,
                                    'jumlah_jam_lembur' => $jumlah_jam_lembur,
                                    'jumlah_jam_istirahat' => $jumlah_jam_istirahat,
                                    'catatan' => strtoupper($catatan),
                                    'operator' => $email
                                ]);
                            } else {
                                $queryDataLembur = DataLembur::whereRaw('
                                    enroll_id = "' . $value1['enroll_id'] . '"
                                    AND tanggal_berjalan = "' . $tanggal_lembur . '"
                                ')
                                ->update([
                                    'nomor_form_lembur' => $nomor_form_lembur,
                                    'mulai_jam_kerja' => $value1['mulai_jam_kerja'],
                                    'akhir_jam_kerja' => $value1['akhir_jam_kerja'],
                                    'absen_masuk_kerja' => $value1['absen_masuk_kerja'],
                                    'absen_pulang_kerja' => $value1['absen_pulang_kerja'],
                                    'mulai_jam_lembur' => $mulai_jam_lembur,
                                    'akhir_jam_lembur' => $akhir_jam_lembur,
                                    'jumlah_jam_lembur' => $jumlah_jam_lembur,
                                    'jumlah_jam_istirahat' => $jumlah_jam_istirahat,
                                    'catatan' => strtoupper($catatan),
                                    'operator' => $email
                                ]);
                            }

                            if($queryDataLembur) {
                                MasterDataAbsenKehadiran::whereRaw('
                                    enroll_id = "' . $value1['enroll_id'] . '"
                                    AND tanggal_berjalan = "' . $tanggal_lembur . '"
                                ')
                                ->update([
                                    'nomor_form_lembur' => $nomor_form_lembur,
                                    'kelebihan_jam_kerja_l1' => '0',
                                    'kelebihan_jam_kerja_l2' => '0',
                                    'kelebihan_jam_kerja_l3' => '0',
                                    'kelebihan_jam_kerja_l4' => '0',
                                    'mulai_jam_lembur' => $mulai_jam_lembur,
                                    'akhir_jam_lembur' => $akhir_jam_lembur,
                                    'jumlah_jam_lembur' => $jumlah_jam_lembur,
                                    'jumlah_jam_lembur_approved' => $jumlah_jam_lembur,
                                    'jumlah_jam_istirahat_lembur' => $jumlah_jam_istirahat,
                                    'catatan_hrd' => strtoupper($catatan),
                                    'operator' => $email
                                ]);
                            }
                        }
                    }
                }
            }
        } else {

            $queryMaster =  MasterDataAbsenKehadiran::selectRaw('
                            master_data_absen_kehadiran.uuid,
                            master_data_absen_kehadiran.nomor_form_lembur,
                            master_data_absen_kehadiran.tanggal_berjalan,
                            master_data_absen_kehadiran.kode_hari,
                            master_data_absen_kehadiran.nama_hari,
                            master_data_absen_kehadiran.mulai_jam_kerja,
                            master_data_absen_kehadiran.akhir_jam_kerja,
                            master_data_absen_kehadiran.absen_masuk_kerja,
                            master_data_absen_kehadiran.absen_pulang_kerja,
                            master_data_absen_kehadiran.enroll_id,
                            employee_atribut.nik,
                            employee_atribut.employee_id,
                            employee_atribut.employee_name,
                            employee_atribut.site_nirwana_id,
                            department_all.site_nirwana_name,
                            employee_atribut.department_id,
                            department_all.department_name,
                            employee_atribut.sub_dept_id,
                            department_all.sub_dept_name
                        ')
                        ->whereRaw('
                            master_data_absen_kehadiran.enroll_id = "' . $enroll_id . '"
                            AND master_data_absen_kehadiran.tanggal_berjalan = "' . $tanggal_berjalan .'"
                            AND master_data_absen_kehadiran.nomor_form_lembur = "' . $nomor_form_lembur .'"
                        ')
                        ->join('employee_atribut', 'employee_atribut.enroll_id', '=', 'master_data_absen_kehadiran.enroll_id')
                        ->leftJoin('department_all', 'department_all.sub_dept_id', '=', 'master_data_absen_kehadiran.sub_dept_id')
                        ->orderBy('employee_name','asc')
                        ->get();
            
            foreach ($queryMaster as $key1 => $value1) {
                $queryDataLembur = DataLembur::whereRaw('
                            nomor_form_lembur = "' . $nomor_form_lembur . '"
                            AND tanggal_berjalan = "' . $tanggal_berjalan . '"
                            AND enroll_id = "' . $enroll_id . '"
                        ')
                    ->update([
                        'nik' => $value1['nik'],
                        'employee_id' => $value1['employee_id'],
                        'employee_name' => $value1['employee_name'],
                        'site_nirwana_id' => $value1['site_nirwana_id'],
                        'site_nirwana_name' => $value1['site_nirwana_name'],
                        'department_id' => $value1['department_id'],
                        'department_name' => $value1['department_name'],
                        'sub_dept_id' => $value1['sub_dept_id'],
                        'sub_dept_name' => $value1['sub_dept_name'],
                        'mulai_jam_kerja' => $value1['mulai_jam_kerja'],
                        'akhir_jam_kerja' => $value1['akhir_jam_kerja'],
                        'absen_masuk_kerja' => $value1['absen_masuk_kerja'],
                        'absen_pulang_kerja' => $value1['absen_pulang_kerja'],
                        'mulai_jam_lembur' => $mulai_jam_lembur,
                        'akhir_jam_lembur' => $akhir_jam_lembur,
                        'jumlah_jam_lembur' => $jumlah_jam_lembur,
                        'jumlah_jam_istirahat' => $jumlah_jam_istirahat,
                        'catatan' => strtoupper($catatan),
                        'operator' => $email
                    ]);

                if($queryDataLembur) {
                    MasterDataAbsenKehadiran::whereRaw('
                            nomor_form_lembur = "' . $nomor_form_lembur . '"
                            AND tanggal_berjalan = "' . $tanggal_berjalan . '"
                            AND enroll_id = "' . $enroll_id . '"
                        ')
                        ->update([
                            'nomor_form_lembur' => $nomor_form_lembur,
                            'kelebihan_jam_kerja_l1' => '0',
                            'kelebihan_jam_kerja_l2' => '0',
                            'kelebihan_jam_kerja_l3' => '0',
                            'kelebihan_jam_kerja_l4' => '0',
                            'mulai_jam_lembur' => $mulai_jam_lembur,
                            'akhir_jam_lembur' => $akhir_jam_lembur,
                            'jumlah_jam_lembur' => $jumlah_jam_lembur,
                            'jumlah_jam_lembur_approved' => $jumlah_jam_lembur,
                            'jumlah_jam_istirahat_lembur' => $jumlah_jam_istirahat,
                            'catatan_hrd' => strtoupper($catatan),
                            'operator' => $email
                        ]);
                }
            }
        }

        return Response()->json($nomor_form_lembur);

    }

    public function ajax_datalembur(Request $request)
    {
        // $periode_lembur = $request->periode_lembur;

        // $periode_lembur = $request->periode_lembur;
        $periode_lembur="2023-01-26 s/d 2023-02-25";
        $array_periode_lembur = explode(' s/d ', $periode_lembur);
        $awal_bulan = substr($array_periode_lembur[0], 0, 10);
        $akhir_bulan = substr($array_periode_lembur[1], 0, 10);

        $inNoSPL = "";
        if($request->selectNoSPL) {
            $selectNoSPL = $request->selectNoSPL;
            $dataNoSPL = implode('","',$selectNoSPL);
            $inNoSPL = ' AND data_lembur.nomor_form_lembur in ("' . $dataNoSPL . '")';

        }

        //$explodeNoSPL = explode(',', $selectNoSPL);

        $query =  MasterDataAbsenKehadiran::selectRaw('
                data_lembur.uuid,
                data_lembur.nomor_form_lembur,
                data_lembur.is_verifikasi,
                DATE_FORMAT(master_data_absen_kehadiran.tanggal_berjalan, "%d %b %Y") format_tanggal_berjalan,
                master_data_absen_kehadiran.tanggal_berjalan,
                master_data_absen_kehadiran.kode_hari,
                master_data_absen_kehadiran.nama_hari,
                IF(master_data_absen_kehadiran.kode_hari IN (5,6), "LIBUR",
                    IF(master_data_absen_kehadiran.holiday_name is not null, "LIBUR", "KERJA")
                ) status_kerja,
                master_data_absen_kehadiran.enroll_id,
                employee_atribut.nik,
                employee_atribut.employee_name,
                substr(master_data_absen_kehadiran.mulai_jam_kerja, 1, 5) mulai_jam_kerja,
                substr(master_data_absen_kehadiran.akhir_jam_kerja, 1, 5) akhir_jam_kerja,
                substr(master_data_absen_kehadiran.absen_masuk_kerja, 1, 5) absen_masuk_kerja,
                substr(master_data_absen_kehadiran.absen_pulang_kerja, 1, 5) absen_pulang_kerja,
                master_data_absen_kehadiran.mulai_jam_lembur,
                master_data_absen_kehadiran.akhir_jam_lembur,
                master_data_absen_kehadiran.jumlah_jam_lembur,
                master_data_absen_kehadiran.jumlah_jam_istirahat_lembur,
                employee_atribut.status_aktif,
                employee_atribut.status_staff,
                employee_atribut.tanggal_resign,
                DATE_FORMAT(employee_atribut.tanggal_resign, "%d %b %Y") format_tanggal_resign,
                department_all.site_nirwana_name,
                department_all.department_name,
                department_all.sub_dept_name,
                data_lembur.catatan
            ')
            ->whereRaw('
                master_data_absen_kehadiran.tanggal_berjalan BETWEEN "' . $awal_bulan . '" AND "' . $akhir_bulan . '"
                AND master_data_absen_kehadiran.tanggal_berjalan= data_lembur.tanggal_berjalan
                AND master_data_absen_kehadiran.enroll_id = data_lembur.enroll_id
                AND master_data_absen_kehadiran.enroll_id = employee_atribut.enroll_id
                ' . $inNoSPL . '
            ')
            ->join('data_lembur','data_lembur.enroll_id','=','master_data_absen_kehadiran.enroll_id')
            ->join('employee_atribut','master_data_absen_kehadiran.enroll_id','=','employee_atribut.enroll_id')
            ->leftJoin('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id')
            ->orderBy('data_lembur.nomor_form_lembur','desc')
            ->orderBy('master_data_absen_kehadiran.tanggal_berjalan','asc')
            ->orderBy('employee_atribut.employee_name','asc')
            ->get();

        return Response()->json($query);

    }

    public function remove(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        $tanggal_berjalan = $request->tanggal_berjalan;
        $enroll_id = $request->enroll_id;
        $nomor_form_lembur = $request->nomor_form_lembur;

        $queryMaster = MasterDataAbsenKehadiran::whereRaw('
                tanggal_berjalan = "' . $tanggal_berjalan . '"
                AND enroll_id = "' . $enroll_id . '"
            ')
            ->update([
                'nomor_form_lembur' => null,
                'jumlah_jam_istirahat_lembur' => null,
                'jumlah_jam_lembur' => null,
                'jumlah_jam_lembur_approved' => null,
                'mulai_jam_lembur' => null,
                'akhir_jam_lembur' => null,
                'catatan_hrd' => null,
                'operator' => $email
            ]);

        if($queryMaster) {
            $query = DataLembur::whereRaw('
                        tanggal_berjalan = "' . $tanggal_berjalan . '"
                        AND enroll_id = "' . $enroll_id . '"
                        AND nomor_form_lembur = "' . $nomor_form_lembur . '"
                    ')->delete();
        }

        return Response()->json($query);

    }

    public function updatelembur(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        $enroll_id = $request->enroll_id;
        $nomor_form_lembur = $request->nomor_form_lembur;
        
        $mulai_jam_lembur = $request->mulai_jam_lembur;
        $explodeMulaiLembur = explode(" ", $mulai_jam_lembur);
        $tanggal_lembur = substr($explodeMulaiLembur[0], 6, 4) . '-' . substr($explodeMulaiLembur[0], 3, 2) . '-' . substr($explodeMulaiLembur[0], 0, 2);
        $mulai_jam_lembur = substr($explodeMulaiLembur[0], 6, 4) . '-' . substr($explodeMulaiLembur[0], 3, 2) . '-' . substr($explodeMulaiLembur[0], 0, 2) . " " . $explodeMulaiLembur[1];
        
        $tanggal_berjalan = $request->tanggal_berjalan;

        $akhir_jam_lembur = $request->akhir_jam_lembur;
        $explodeAkhirLembur = explode(" ", $akhir_jam_lembur);
        $akhir_jam_lembur = substr($explodeAkhirLembur[0], 6, 4) . '-' . substr($explodeAkhirLembur[0], 3, 2) . '-' . substr($explodeAkhirLembur[0], 0, 2) . " " . $explodeAkhirLembur[1];

        $jumlah_jam_lembur = $request->jumlah_jam_lembur;
        $jumlah_jam_istirahat = $request->jumlah_jam_istirahat;
        $catatan = $request->catatan;

        MasterDataAbsenKehadiran::whereRaw('
                    tanggal_berjalan = "' . $tanggal_berjalan . '"
                    AND enroll_id = "' . $enroll_id . '"
        ')
        ->update([
            'nomor_form_lembur' => null,
            'jumlah_jam_istirahat_lembur' => null,
            'jumlah_jam_lembur' => null,
            'jumlah_jam_lembur_approved' => null,
            'mulai_jam_lembur' => null,
            'akhir_jam_lembur' => null,
            'catatan_hrd' => null,
            'operator' => $email
        ]);

        $queryDataLembur = DataLembur::whereRaw('
                    tanggal_berjalan = "' . $tanggal_berjalan . '"
                    AND enroll_id = "' . $enroll_id . '"
                ')
            ->update([
                'tanggal_berjalan' => $tanggal_lembur,
                'tanggal_absen' => $tanggal_lembur,
                'mulai_jam_lembur' => $mulai_jam_lembur,
                'akhir_jam_lembur' => $akhir_jam_lembur,
                'jumlah_jam_lembur' => $jumlah_jam_lembur,
                'jumlah_jam_istirahat' => $jumlah_jam_istirahat,
                'catatan' => strtoupper($catatan),
                'operator' => $email
            ]);

        $queryMaster = 0;

        if($queryDataLembur) {

            $queryMaster = MasterDataAbsenKehadiran::whereRaw('
                    tanggal_berjalan = "' . $tanggal_lembur . '"
                    AND enroll_id = "' . $enroll_id . '"
                ')
            ->update([
                'nomor_form_lembur' => $nomor_form_lembur,
                'mulai_jam_lembur' => $mulai_jam_lembur,
                'akhir_jam_lembur' => $akhir_jam_lembur,
                'jumlah_jam_lembur' => $jumlah_jam_lembur,
                'jumlah_jam_lembur_approved' => $jumlah_jam_lembur,
                'jumlah_jam_istirahat_lembur' => $jumlah_jam_istirahat,
                'catatan_hrd' => strtoupper($catatan),
                'operator' => $email
            ]);

        }

        return Response()->json($queryMaster);

    }

    public function updatelemburall(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        $nomor_form_lembur = $request->nomor_form_lembur;
        
        $mulai_jam_lembur = $request->mulai_jam_lembur;
        $explodeMulaiLembur = explode(" ", $mulai_jam_lembur);
        $tanggal_lembur = substr($explodeMulaiLembur[0], 6, 4) . '-' . substr($explodeMulaiLembur[0], 3, 2) . '-' . substr($explodeMulaiLembur[0], 0, 2);
        $mulai_jam_lembur = substr($explodeMulaiLembur[0], 6, 4) . '-' . substr($explodeMulaiLembur[0], 3, 2) . '-' . substr($explodeMulaiLembur[0], 0, 2) . " " . $explodeMulaiLembur[1];
        
        $akhir_jam_lembur = $request->akhir_jam_lembur;
        $explodeAkhirLembur = explode(" ", $akhir_jam_lembur);
        $akhir_jam_lembur = substr($explodeAkhirLembur[0], 6, 4) . '-' . substr($explodeAkhirLembur[0], 3, 2) . '-' . substr($explodeAkhirLembur[0], 0, 2) . " " . $explodeAkhirLembur[1];

        $jumlah_jam_lembur = $request->jumlah_jam_lembur;
        $jumlah_jam_istirahat = $request->jumlah_jam_istirahat;
        $catatan = $request->catatan;

        MasterDataAbsenKehadiran::whereRaw('
            nomor_form_lembur = "' . $nomor_form_lembur . '"
        ')
        ->update([
            'nomor_form_lembur' => null,
            'jumlah_jam_istirahat_lembur' => null,
            'jumlah_jam_lembur' => null,
            'jumlah_jam_lembur_approved' => null,
            'mulai_jam_lembur' => null,
            'akhir_jam_lembur' => null,
            'catatan_hrd' => null,
            'operator' => $email
        ]);


        $queryAll = DataLembur::selectRaw('
                    tanggal_berjalan,
                    enroll_id,
                    nomor_form_lembur
                ')
                ->whereRaw('
                    nomor_form_lembur = "' . $nomor_form_lembur . '"
                ')
                ->groupBy('tanggal_berjalan')
                ->groupBy('enroll_id')
                ->groupBy('nomor_form_lembur')
                ->get();
        
        foreach ($queryAll as $key => $value) {

            $queryDataLembur = DataLembur::whereRaw('
                        nomor_form_lembur = "' . $nomor_form_lembur . '"
                        AND enroll_id = "' . $value['enroll_id'] . '"
                ')
                ->update([
                    'tanggal_berjalan' => $tanggal_lembur,
                    'tanggal_absen' => $tanggal_lembur,
                    'mulai_jam_lembur' => $mulai_jam_lembur,
                    'akhir_jam_lembur' => $akhir_jam_lembur,
                    'jumlah_jam_lembur' => $jumlah_jam_lembur,
                    'jumlah_jam_istirahat' => $jumlah_jam_istirahat,
                    'catatan' => strtoupper($catatan),
                    'operator' => $email
                ]);

            $queryMaster = 0;
            
            if($queryDataLembur) {

                $queryMaster = MasterDataAbsenKehadiran::whereRaw('
                        tanggal_berjalan = "' . $tanggal_lembur . '"
                        AND enroll_id = "' . $value['enroll_id'] . '"
                    ')
                ->update([
                    'nomor_form_lembur' => $nomor_form_lembur,
                    'mulai_jam_lembur' => $mulai_jam_lembur,
                    'akhir_jam_lembur' => $akhir_jam_lembur,
                    'jumlah_jam_lembur' => $jumlah_jam_lembur,
                    'jumlah_jam_lembur_approved' => $jumlah_jam_lembur,
                    'jumlah_jam_istirahat_lembur' => $jumlah_jam_istirahat,
                    'catatan_hrd' => strtoupper($catatan),
                    'operator' => $email
                ]);

            }
            
        }
        return Response()->json($queryMaster);

    }

    public function tambahkaryawan(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        $nomor_form_lembur = $request->nomor_form_lembur;
        $enroll_id_array = $request->selectEmp;
        
        $mulai_jam_lembur = $request->mulai_jam_lembur;
        $explodeMulaiLembur = explode(" ", $mulai_jam_lembur);
        $tanggal_lembur = substr($explodeMulaiLembur[0], 6, 4) . '-' . substr($explodeMulaiLembur[0], 3, 2) . '-' . substr($explodeMulaiLembur[0], 0, 2);
        $mulai_jam_lembur = substr($explodeMulaiLembur[0], 6, 4) . '-' . substr($explodeMulaiLembur[0], 3, 2) . '-' . substr($explodeMulaiLembur[0], 0, 2) . " " . $explodeMulaiLembur[1];
        
        $akhir_jam_lembur = $request->akhir_jam_lembur;
        $explodeAkhirLembur = explode(" ", $akhir_jam_lembur);
        $akhir_jam_lembur = substr($explodeAkhirLembur[0], 6, 4) . '-' . substr($explodeAkhirLembur[0], 3, 2) . '-' . substr($explodeAkhirLembur[0], 0, 2) . " " . $explodeAkhirLembur[1];

        $jumlah_jam_lembur = $request->jumlah_jam_lembur;
        $jumlah_jam_istirahat = $request->jumlah_jam_istirahat;
        $catatan = $request->catatan;

        $queryMaster = 0;
        if (!empty($enroll_id_array[0])) {

            foreach ($enroll_id_array as $key => $value) {

                $queryMaster =  MasterDataAbsenKehadiran::selectRaw('
                                master_data_absen_kehadiran.uuid,
                                master_data_absen_kehadiran.nomor_form_lembur,
                                master_data_absen_kehadiran.tanggal_berjalan,
                                master_data_absen_kehadiran.kode_hari,
                                master_data_absen_kehadiran.nama_hari,
                                master_data_absen_kehadiran.mulai_jam_kerja,
                                master_data_absen_kehadiran.akhir_jam_kerja,
                                master_data_absen_kehadiran.absen_masuk_kerja,
                                master_data_absen_kehadiran.absen_pulang_kerja,
                                master_data_absen_kehadiran.enroll_id,
                                employee_atribut.nik,
                                employee_atribut.employee_id,
                                employee_atribut.employee_name,
                                employee_atribut.site_nirwana_id,
                                department_all.site_nirwana_name,
                                employee_atribut.department_id,
                                department_all.department_name,
                                employee_atribut.sub_dept_id,
                                department_all.sub_dept_name
                            ')
                            ->whereRaw('
                                master_data_absen_kehadiran.enroll_id = "' . $value . '"
                                AND master_data_absen_kehadiran.tanggal_berjalan = "' . $tanggal_lembur . '"
                            ')
                            ->join('employee_atribut', 'employee_atribut.enroll_id', '=', 'master_data_absen_kehadiran.enroll_id')
                            ->leftJoin('department_all', 'department_all.sub_dept_id', '=', 'master_data_absen_kehadiran.sub_dept_id')
                            ->orderBy('employee_atribut.employee_name','asc')
                            ->get();
                            info($value . " " . $tanggal_lembur);
                foreach ($queryMaster as $key1 => $value1) {
                    
                    $queryDataLembur = DataLembur::create([
                        'uuid' => Str::uuid(),
                        'uuid_master' => $value1['uuid'],
                        'tanggal_berjalan' => $value1['tanggal_berjalan'],
                        'tanggal_absen' => $value1['tanggal_berjalan'],
                        'nomor_form_lembur' => $nomor_form_lembur,
                        'kode_hari' => $value1['kode_hari'],
                        'nama_hari' => $value1['nama_hari'],
                        'mulai_jam_kerja' => $value1['mulai_jam_kerja'],
                        'akhir_jam_kerja' => $value1['akhir_jam_kerja'],
                        'absen_masuk_kerja' => $value1['absen_masuk_kerja'],
                        'absen_pulang_kerja' => $value1['absen_pulang_kerja'],
                        'enroll_id' => $value1['enroll_id'],
                        'nik' => $value1['nik'],
                        'employee_id' => $value1['employee_id'],
                        'employee_name' => $value1['employee_name'],
                        'site_nirwana_id' => $value1['site_nirwana_id'],
                        'site_nirwana_name' => $value1['site_nirwana_name'],
                        'department_id' => $value1['department_id'],
                        'department_name' => $value1['department_name'],
                        'sub_dept_id' => $value1['sub_dept_id'],
                        'sub_dept_name' => $value1['sub_dept_name'],
                        'mulai_jam_lembur' => $mulai_jam_lembur,
                        'akhir_jam_lembur' => $akhir_jam_lembur,
                        'jumlah_jam_lembur' => $jumlah_jam_lembur,
                        'jumlah_jam_istirahat' => $jumlah_jam_istirahat,
                        'catatan' => strtoupper($catatan),
                        'operator' => $email
                    ]);            

                    $queryMaster = MasterDataAbsenKehadiran::whereRaw('
                            tanggal_berjalan = "' . $tanggal_lembur . '"
                            AND enroll_id = "' . $value . '"
                        ')
                    ->update([
                        'nomor_form_lembur' => $nomor_form_lembur,
                        'mulai_jam_lembur' => $mulai_jam_lembur,
                        'akhir_jam_lembur' => $akhir_jam_lembur,
                        'jumlah_jam_lembur' => $jumlah_jam_lembur,
                        'jumlah_jam_lembur_approved' => $jumlah_jam_lembur,
                        'jumlah_jam_istirahat_lembur' => $jumlah_jam_istirahat,
                        'catatan_hrd' => strtoupper($catatan),
                        'operator' => $email
                    ]);
                }
            }
        }

        return Response()->json($queryMaster);

    }

    public function removenospl(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        $selectNoSPL = $request->selectNoSPL;
        
        $query = 0;
        foreach ($selectNoSPL as $key1 => $nomor_form_lembur) {

            $query = DataLembur::whereRaw('
                nomor_form_lembur = "' . $nomor_form_lembur . '"
                        ')->delete();

            if($query) {
                $query = MasterDataAbsenKehadiran::whereRaw('
                nomor_form_lembur = "' . $nomor_form_lembur . '"
                ')
                ->update([
                    'nomor_form_lembur' => null,
                    'jumlah_jam_istirahat_lembur' => null,
                    'jumlah_jam_lembur' => null,
                    'jumlah_jam_lembur_approved' => null,
                    'mulai_jam_lembur' => null,
                    'akhir_jam_lembur' => null,
                    'catatan_hrd' => null,
                    'operator' => $email
                ]);
            }
        }
        
        return Response()->json($query);

    }
    
    // ============= Andri ========
    public function verifikasi(Request $request)
    {
        $count=count($request->uuid);
        for ($i=0; $i < $count ; $i++) { 
            $data=[
                'is_verifikasi'=>1];
                DataLembur::where('uuid',$request->uuid[$i])->update($data);
        }
        $a='hallo';
        return $a;
    }
}
