<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;
use App\Models\MasterDataAbsenKehadiran;
use App\Models\DataKehadiranInOutEdited;
use App\Models\DepartmentAll;
use App\Models\RefAbsenIjin;
use App\Models\EmployeeAtribut;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
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
 * Class DataKehadiranInOutEditedController
 * @package App\Http\Controllers\Hris
 */
class DataKehadiranInOutEditedController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Absen IN/OUT Edited';
    }

    public function index()
    {

        //return View::make('admin/dashboard', $this->data);
    }

    public function abseninout()
    {

        $this->department = $this->ajax_getselectdepart();
        $this->selectemployee = $this->ajax_getallemployeeatribut();
        $this->refabsenijin = $this->ajax_getselectrefabsenijin();

        $loggedAdmin = Auth::guard('admin')->user();

        if ($loggedAdmin->role_user == "superadmin") {
            $this->refabsenijin = $this->ajax_getselectrefabsenijin();
            return View::make('admin/datakehadiraninout', $this->data);
        } else {
            return View::make('hris/dashboard', $this->data);
        }
    }
    public function status_kerja(){
        $jam_kerja=MasterDataAbsenKehadiran::select('mulai_jam_kerja','akhir_jam_kerja')->where('enroll_id',request()->enroll_id)->where('mulai_jam_kerja','!=','')->where('akhir_jam_kerja','!=','')->orderBy('tanggal_berjalan','desc')->limit(1)->get();
        $jam_absen=MasterDataAbsenKehadiran::select('absen_masuk_kerja','absen_pulang_kerja')->where('tanggal_berjalan',request()->tanggal_berjalan)->where('enroll_id',request()->enroll_id)->orderBy('tanggal_berjalan','desc')->limit(1)->get();
        $jam=[
            'mulai_jam_kerja'=>$jam_kerja[0]['mulai_jam_kerja'],
            'akhir_jam_kerja'=>$jam_kerja[0]['akhir_jam_kerja'],
            'absen_masuk_kerja'=>$jam_absen[0]['absen_masuk_kerja'],
            'absen_pulang_kerja'=>$jam_absen[0]['absen_pulang_kerja']
        ];
        return $jam;
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

    private function ajax_getselectrefabsenijin()
    {
        $query =  RefAbsenIjin::selectRaw('kode_absen_ijin,
                                           concat(kode_absen_ijin," - "
                                                  ,nama_absen_ijin) kode_nama_absen_ijin')
                                 ->orderby('nama_absen_ijin', 'asc')
                                 ->get();

        return $query;

    }

    public function ajax_abseninout(Request $request)
    {
        $department_id = $request->department_id;
        $daterange1 = explode(" - ", $request->daterange1);
        $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
        $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));
        $selectEmployeeID = "";
        $dataEmployee = "";

        $department_id = $request->department_id;
        $inDepartmentID = "";
        if($department_id) {
            $dataDepartment = implode('","',$department_id);
            $inDepartmentID = ' and department_id in ("' . $dataDepartment . '")';
        }
        
        $inEmployee = "";
        if($request->selectEmployeeID) {
            $selectEmployeeID = $request->selectEmployeeID;
            $dataEmployee = implode('","',$selectEmployeeID);
            $inEmployee = ' and enroll_id in ("' . $dataEmployee . '")';
            
        } 
        info($inEmployee);        
        if(request()->ajax()) {

            $columns = array(
                0 => 'uuid',
                1 => 'employee_id',
                2 => 'nik',
                3 => 'enroll_id',
                4 => 'tanggal_berjalan',
                5 => 'nama_hari',
                6 => 'employee_name',
                7 => 'kode_hari',
                8 => 'work_status',
                9 => 'tanggal_absen',
                10 => 'mulai_jam_kerja',
                11 => 'akhir_jam_kerja',
                12 => 'absen_masuk_kerja',
                13 => 'absen_pulang_kerja',
                14 => 'jumlah_menit_absen_dt',
                15 => 'jumlah_menit_absen_pc',
                16 => 'jumlah_menit_absen_dtpc',
                17 => 'status_absen'
            );


            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if(empty($department_id)) {
                $totalData = MasterDataAbsenKehadiran::
                whereRaw('
                    substr(tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                    ' . $inEmployee . '
                ')
                ->count();
                $totalFiltered = $totalData;

                $query = MasterDataAbsenKehadiran::
                whereRaw('
                    substr(tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                    ' . $inEmployee . '
                ')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
            } else {
                $totalData = MasterDataAbsenKehadiran::
                whereRaw('
                    substr(tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                    ' . $inEmployee . ' ' . $inDepartmentID . '
                ')
                ->count();
                $totalFiltered = $totalData;

                $query =  MasterDataAbsenKehadiran::
                whereRaw('
                    (substr(tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '")
                    ' . $inEmployee . ' ' . $inDepartmentID . '
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
                    $mulai_jam_kerja = $q->mulai_jam_kerja;
                    $akhir_jam_kerja = $q->akhir_jam_kerja;
                    $kode_hari = $q->kode_hari;
                    $liburnasional = $q->holiday_name;
                    $kerjalibur = "KERJA";
                    if($tanggal_absen <> "") {
                        $kerjalibur = "KERJA";
                        switch ($kode_hari) {
                            case '5':
                                $kerjalibur = "LIBUR";
                                break;
                            case '6':
                                $kerjalibur = "LIBUR";
                                break;
                        }
                    } else if($tanggal_absen <> null) {
                        $kerjalibur = "KERJA";
                        switch ($kode_hari) {
                            case '5':
                                $kerjalibur = "LIBUR";
                                break;
                            case '6':
                                $kerjalibur = "LIBUR";
                                break;
                        }
                    }else if($mulai_jam_kerja == null && $akhir_jam_kerja==null){
                        $kerjalibur = "LIBUR";
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

                    if($liburnasional <> "") {
                        $kerjalibur = "LIBUR";
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
                    $nestedData['mulai_jam_kerja'] = $q->mulai_jam_kerja;
                    $nestedData['akhir_jam_kerja'] = $q->akhir_jam_kerja;
                    $nestedData['absen_masuk_kerja'] = $q->absen_masuk_kerja;
                    $nestedData['absen_pulang_kerja'] = $q->absen_pulang_kerja;
                    $nestedData['absen_dt_datang_terlambat'] = $q->absen_dt_datang_terlambat;
                    $nestedData['absen_dtpc_datang_terlambat_pulang_cepat'] = $q->absen_dtpc_datang_terlambat_pulang_cepat;
                    $nestedData['jumlah_jam_kerja'] = $q->jumlah_jam_kerja;
                    $nestedData['status_absen'] = strtoupper($q->status_absen);
                    $nestedData['nama_absen_ijin'] = strtoupper($q->nama_absen_ijin);
                    $nestedData['kode_ijin_payroll'] = strtoupper($q->kode_ijin_payroll);
                    $nestedData['absen_alasan'] = $q->absen_alasan;
                    $nestedData['nomor_form_perubahan_absen'] = $q->nomor_form_perubahan_absen;
                    $nestedData['nomor_absen_ijin'] = $q->nomor_absen_ijin;
                    $nestedData['nomor_form_lembur'] = $q->nomor_form_lembur;                    
                    $nestedData['tanggal_mulai_ijin'] = $q->tanggal_mulai_ijin;
                    $nestedData['tanggal_akhir_ijin'] = $q->tanggal_akhir_ijin;
                    $nestedData['permits_dari_pukul'] = $q->permits_dari_pukul;
                    $nestedData['permits_sampai_pukul'] = $q->permits_sampai_pukul;
                    $nestedData['total_menit_permits'] = $q->total_menit_permits;
                    $nestedData['updated_absen_ijin'] = $q->updated_absen_ijin;
                    $nestedData['updated_at'] = substr($q->updated_at, 0, 10) . " " . substr($q->updated_at, 11, 8);
                    $nestedData['operator'] = $q->operator;
                    $nestedData['holiday_name'] = $q->holiday_name;
                    $nestedData['catatan_hrd'] = $q->catatan_hrd;
                    $nestedData['site_nirwana_name'] = $q->site_nirwana_name;
                    $nestedData['department_name'] = $q->department_name;
                    $nestedData['sub_dept_name'] = $q->sub_dept_name;
                    $nestedData['tanggal_absen'] = $q->tanggal_absen;
                    $nestedData['jumlah_menit_absen_dt'] = $q->jumlah_menit_absen_dt;
                    $nestedData['jumlah_menit_absen_pc'] = $q->jumlah_menit_absen_pc;
                    $nestedData['jumlah_menit_absen_dtpc'] = $q->jumlah_menit_absen_dtpc;
                    $nestedData['jumlah_absen_menit_kerja'] = $q->jumlah_absen_menit_kerja;
                    $nestedData['posisi_name'] = $q->status_jabatan;
                    $nestedData['work_status'] = $q->status_aktif;
                    $nestedData['employee_status'] = $q->employee_status;
                    $nestedData['mulai_jam_lembur'] = $q->mulai_jam_lembur;
                    $nestedData['akhir_jam_lembur'] = $q->akhir_jam_lembur;
                    $nestedData['jumlah_jam_lembur'] = $q->jumlah_jam_lembur;
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

    public function ajax_abseninout_edited(Request $request)
    {
        if(request()->ajax()) {

        $columns = array(
            0 => 'uuid',
            1 => 'employee_name',
            2 => 'enroll_id',
            3 => 'nik',
            4 => 'tanggal_absen',
            5 => 'kode_hari',
            6 => 'nama_hari',
            7 => 'sub_dept_name',
            8 => 'absen_masuk_kerja',
            9 => 'absen_pulang_kerja',
            10 => 'status_absen',
            11 => 'operator',
            12 => 'created_at',
            13 => 'updated_at',
        );

        $totalData = DataKehadiranInOutEdited::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $query = DataKehadiranInOutEdited::offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();
        } else {
            $search = $request->input('search.value');

            $query =  DataKehadiranInOutEdited::whereRaw('
                            uuid like "%' . $search . '%" or nik like "%' . $search . '%"
                            ')
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = DataKehadiranInOutEdited::count();
        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $nestedData['uuid'] = $q->uuid;
                $nestedData['employee_name'] = $q->employee_name;
                $nestedData['enroll_id'] = $q->enroll_id;
                $nestedData['nik'] = $q->nik;
                $nestedData['tanggal_absen'] = $q->tanggal_absen;
                $nestedData['kode_hari'] = $q->kode_hari;
                $nestedData['nama_hari'] = $q->nama_hari;
                $nestedData['holiday_name'] = $q->holiday_name;
                $nestedData['site_nirwana_id'] = $q->site_nirwana_id;
                $nestedData['site_nirwana_name'] = $q->site_nirwana_name;
                $nestedData['department_id'] = $q->department_id;
                $nestedData['department_name'] = $q->department_name;
                $nestedData['sub_dept_name'] = $q->sub_dept_name;
                $nestedData['absen_masuk_kerja'] = substr($q->absen_masuk_kerja, 0, 5);
                $nestedData['absen_pulang_kerja'] = substr($q->absen_pulang_kerja, 0, 5);
                $nestedData['status_absen'] = $q->status_absen;
                $nestedData['mulai_jam_lembur'] = $q->mulai_jam_lembur;
                $nestedData['akhir_jam_lembur'] = $q->akhir_jam_lembur;
                $nestedData['nomor_form_lembur'] = $q->nomor_form_lembur;
                $nestedData['join_date'] = $q->join_date;
                $nestedData['tanggal_resign'] = $q->tanggal_resign;
                $nestedData['lama_bekerja_bulan'] = $q->lama_bekerja_bulan;
                $nestedData['operator'] = $q->operator;
                $nestedData['created_at'] = date_format($q->created_at, 'Y-m-d H:i:s');
                $nestedData['updated_at'] = date_format($q->updated_at, 'Y-m-d H:i:s');

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

    public function form_kehadiraninout_edited(Request $request)
    {
        $tanggal_berjalan = $request->tanggal_berjalan;
        $enroll_id = $request->enroll_id;
        
        $query =  DB::select('
            SELECT
                a.employee_name,
                a.enroll_id,
                a.nik,
                b.tanggal_berjalan,
                b.kode_hari,
                b.nama_hari,
                b.holiday_name,
                a.site_nirwana_id,
                a.site_nirwana_name,
                a.department_id,
                a.department_name,
                a.sub_dept_id,
                a.sub_dept_name,
                substr(b.mulai_jam_kerja, 1, 5) mulai_jam_kerja,
                substr(b.akhir_jam_kerja, 1, 5) akhir_jam_kerja,
                substr(b.absen_masuk_kerja, 1, 5) absen_masuk_kerja,
                substr(b.absen_pulang_kerja, 1, 5) absen_pulang_kerja,
                b.status_absen,
                b.mulai_jam_lembur,
                b.akhir_jam_lembur,
                b.nomor_form_lembur,
                a.join_date,
                a.tanggal_resign,
                b.operator,
                TIMESTAMPDIFF( MONTH, a.join_date, b.tanggal_berjalan ) lama_bekerja_bulan
            FROM
                employee_atribut a,
                master_data_absen_kehadiran b
            WHERE
                a.enroll_id = b.enroll_id
                and b.tanggal_berjalan = "' . $tanggal_berjalan . '"
                and b.enroll_id = "' . $enroll_id . '"
            group by
                a.enroll_id,
                b.tanggal_absen
            LIMIT 1
        ');
        
        return Response()->json($query);
    }

    public function store(Request $request)
    {
        $tanggal_berjalan = $request->tanggal_berjalan;
        $kode_hari = $request->kode_hari;
        $nama_hari = $request->nama_hari;
        $site_nirwana_id = $request->site_nirwana_id;
        $site_nirwana_name = $request->site_nirwana_name;
        $holiday_name = $request->holiday_name;
        $nik = $request->nik;
        $enroll_id = $request->enroll_id;
        $employee_name = $request->employee_name;
        $department_id = $request->department_id;
        $department_name = $request->department_name;
        $sub_dept_id = $request->sub_dept_id;
        $sub_dept_name = $request->sub_dept_name;
        $status_absen = $request->status_absen;
        if($status_absen == "") { $status_absen = null; }
        $absen_masuk_kerja = $request->absen_masuk_kerja;
        $absen_pulang_kerja = $request->absen_pulang_kerja;
        $mulai_jam_kerja = $request->mulai_jam_kerja;
        $akhir_jam_kerja = $request->akhir_jam_kerja;
        $mulai_jam_lembur = $request->mulai_jam_lembur;
        $akhir_jam_lembur = $request->akhir_jam_lembur;
        $nomor_form_lembur = $request->nomor_form_lembur;
        $join_date = $request->join_date;
        $tanggal_resign = $request->tanggal_resign;
        $operator = $request->operator;

        if($absen_masuk_kerja!=null & $absen_pulang_kerja!=null & $status_absen==""){
            $status_absen = null;
        }
        else if($absen_masuk_kerja!=null & $absen_pulang_kerja!=null & $status_absen!=""){
            $status_absen = null;
        }
		else if((($absen_masuk_kerja==null & $absen_pulang_kerja!=null )||($absen_masuk_kerja!=null & $absen_pulang_kerja==null )) & $status_absen==""){
            $status_absen = 'TL';
        }
		else if((($absen_masuk_kerja==null & $absen_pulang_kerja!=null )||($absen_masuk_kerja!=null & $absen_pulang_kerja==null )) & $status_absen!=""){
            $status_absen = $request->status_absen;
        }
        else if($absen_masuk_kerja==null & $absen_pulang_kerja==null & $status_absen!=""){
            $status_absen = $request->status_absen;
        }
        $query = DataKehadiranInOutEdited::create([
            'uuid' => Str::uuid(),
            'tanggal_absen' => $tanggal_berjalan,
            'kode_hari' => $kode_hari,
            'nama_hari' => $nama_hari,
            'site_nirwana_id' => $site_nirwana_id,
            'site_nirwana_name' => $site_nirwana_name,
            'holiday_name' => $holiday_name,
            'nik' => $nik,
            'enroll_id' => $enroll_id,
            'employee_name' => $employee_name,
            'department_id' => $department_id,
            'department_name' => $department_name,
            'sub_dept_id' => $sub_dept_id,
            'sub_dept_name' => $sub_dept_name,
            'status_absen' => $status_absen,
            'absen_masuk_kerja' => $absen_masuk_kerja,
            'absen_pulang_kerja' => $absen_pulang_kerja,
            'mulai_jam_kerja' => $mulai_jam_kerja,
            'akhir_jam_kerja' => $akhir_jam_kerja,
            'mulai_jam_lembur' => $mulai_jam_lembur,
            'akhir_jam_lembur' => $akhir_jam_lembur,
            'nomor_form_lembur' => $nomor_form_lembur,
            'join_date' => $join_date,
            'tanggal_resign' => $tanggal_resign,
            'operator' => $operator
        ]);
        if($query) {
            // $query = MasterDataAbsenKehadiran::where('tanggal_berjalan','=', $tanggal_berjalan)
            // ->where('enroll_id','=', $enroll_id)
            // ->update([
            //     'absen_masuk_kerja' => $absen_masuk_kerja,
            //     'absen_pulang_kerja' => $absen_pulang_kerja,
            //     'status_absen' => $status_absen,
            //     'operator' => $operator
            // ]);
            
            //andri
            MasterDataAbsenKehadiran::where('enroll_id',$enroll_id)->where('tanggal_berjalan',$tanggal_berjalan)->update([
                'mulai_jam_kerja'=>$mulai_jam_kerja,
                'akhir_jam_kerja'=>$akhir_jam_kerja
            ]);
            $query=MasterDataAbsenKehadiran::where('tanggal_berjalan',$tanggal_berjalan)
                ->where('enroll_id','=', $enroll_id)->first();

                $status_absen_awal=$query->status_absen;
                $jadwal_in=$query->mulai_jam_kerja;
                $jadwal_out=$query->akhir_jam_kerja;

                $absen_in=$absen_masuk_kerja;
                $absen_out=$absen_pulang_kerja;

                $durasi_kerja=date_diff(date_create($jadwal_in),date_create($jadwal_out));
                $durasi_kerja_menit=$durasi_kerja->i +($durasi_kerja->h*60);
            
                $DT = date_diff(date_create($jadwal_in),date_create($absen_in));
                $PC = date_diff(date_create($jadwal_out),date_create($absen_out));
                if( $jadwal_in!=null && $absen_in!=null && $absen_in>$jadwal_in){
                    $total_DT1 = $DT->i +($DT->h*60);
                    if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                        if($absen_in >'13:00:00'){
                            $total_DT=$total_DT1-60;
                        }
                        else if($absen_in >'12:00:00' && $absen_in <='13:00:00'){
                            $selisih_menit = strtotime($absen_in) - strtotime('12:00:00');
                            $selisih_menit = round($selisih_menit / 60);
                            $total_DT=$total_DT1-$selisih_menit;
                        }
                        else {
                            $total_DT=$total_DT1;
                        }
                    }else{
                        $total_DT=$total_DT1;
                    } 
                    $total_DT = $total_DT < 480 ? $total_DT : 480;
                }else{
                    $total_DT=0;
                }

                if( $jadwal_out !=null && $absen_out !=null && $absen_out<$jadwal_out){
                    $total_PC1 = $PC->i +($PC->h*60);
                    if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                        if($absen_out <='12:00:00'){
                            $total_PC=$total_PC1-60;
                        }
                        else if($absen_out >'12:00:00' && $absen_out <='13:00:00'){
                            $selisih_menit = strtotime('13:00:00') - strtotime($absen_out);
                            $selisih_menit = round($selisih_menit / 60);
                            $total_PC=$total_PC1-$selisih_menit;
                        }
                        else {
                            $total_PC=$total_PC1;
                        }
                    }else{
                         $total_PC=$total_PC1;
                    }  
                    $total_PC = $total_PC < 480 ? $total_PC : 480;
                }else{
                    $total_PC=0;
                }

                $jumlah_menit_absen_dtpc=$total_DT+$total_PC;
                if( $jadwal_in!=null && $jadwal_out !=null){
                    $jumlah_absen_menit_kerja=$durasi_kerja_menit-$jumlah_menit_absen_dtpc;
                }
                else{
                    $jumlah_absen_menit_kerja=0;
                }
                if($status_absen_awal=='LN'){
                    $data_update=[
                        'jumlah_menit_absen_dtpc'=>$jumlah_menit_absen_dtpc,
                        'jumlah_absen_menit_kerja'=>$jumlah_absen_menit_kerja,
                        'jumlah_menit_absen_dt'=>$total_DT,
                        'jumlah_menit_absen_pc'=>$total_PC,
                        'absen_masuk_kerja' => $absen_masuk_kerja,
                        'absen_pulang_kerja' => $absen_pulang_kerja,
                        'operator' => $operator
                    ];
                }
                else{
                    $data_update=[
                        'jumlah_menit_absen_dtpc'=>$jumlah_menit_absen_dtpc,
                        'jumlah_absen_menit_kerja'=>$jumlah_absen_menit_kerja,
                        'jumlah_menit_absen_dt'=>$total_DT,
                        'jumlah_menit_absen_pc'=>$total_PC,
                        'absen_masuk_kerja' => $absen_masuk_kerja,
                        'absen_pulang_kerja' => $absen_pulang_kerja,
                        'status_absen' => $status_absen,
                        'operator' => $operator
                    ];
                }
            MasterDataAbsenKehadiran::where('tanggal_berjalan', $tanggal_berjalan)
                ->where('enroll_id',  $enroll_id)->update($data_update);

            //end andri
        } else {
            $query = false;
        }

        return $query;
    }

}
