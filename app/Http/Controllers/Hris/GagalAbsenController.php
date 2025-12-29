<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use App\Models\MasterDataAbsenKehadiran;
use App\Models\LogDataGagalAbsen;
use App\Models\RefHariLibur;
use App\Models\EmployeeAtribut;
use App\Models\DepartmentAll;
use App\Models\WorkTimeTable;
use App\Exports\GagalAbsenExport;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Datatables;

/**
 * Class MdAbsenHadirController
 * @package App\Http\Controllers\Hris
 */
class GagalAbsenController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Gagal Absen';
    }

    public function index()
    {
        $this->department = $this->ajax_getselectdepart();
        $this->selectemployee = $this->ajax_getallemployeeatribut();

        return View::make('hris/gagalabsen', $this->data);
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

    public function ajax_gagalabsen(Request $request)
    {
        $daterange1 = explode(" - ", $request->daterange1);
        $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
        $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));
        $searchData = strtoupper($request->searchData);

        if(request()->ajax()) {

        $limit = $request->input('length');
        $start = $request->input('start');
        $totalData = 0;
        $totalFiltered = 0;

        if(empty($tanggalMulai))
        {
            $query = null;

        } else {
            if(empty($searchData))
            {
                $query =  MasterDataAbsenKehadiran::selectRaw('
                            master_data_absen_kehadiran.uuid,
                            employee_atribut.employee_id,
                            master_data_absen_kehadiran.nomor_form_perubahan_absen,
                            employee_atribut.nik,
                            master_data_absen_kehadiran.enroll_id,
                            employee_atribut.employee_name,
                            master_data_absen_kehadiran.tanggal_berjalan,
                            master_data_absen_kehadiran.kode_hari,
                            master_data_absen_kehadiran.nama_hari,
                            master_data_absen_kehadiran.mulai_jam_kerja,
                            master_data_absen_kehadiran.akhir_jam_kerja,
                            master_data_absen_kehadiran.absen_masuk_kerja,
                            master_data_absen_kehadiran.absen_pulang_kerja,
                            master_data_absen_kehadiran.status_absen,
                            master_data_absen_kehadiran.holiday_name,
                            data_absen_perijinan.absen_alasan
                        ')
                        // ->whereRaw('
                        //     ((master_data_absen_kehadiran.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                        //     AND master_data_absen_kehadiran.enroll_id IS NOT NULL)
                        //     AND master_data_absen_kehadiran.status_absen IN ("M","TL")) OR ((master_data_absen_kehadiran.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                        //     AND master_data_absen_kehadiran.enroll_id IS NOT NULL)
                        //     AND master_data_absen_kehadiran.status_absen IN ("LN") AND master_data_absen_kehadiran.absen_masuk_kerja is not null AND master_data_absen_kehadiran.absen_pulang_kerja is null)
                        //     OR ((master_data_absen_kehadiran.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                        //     AND master_data_absen_kehadiran.enroll_id IS NOT NULL)
                        //     AND master_data_absen_kehadiran.mulai_jam_kerja is null AND master_data_absen_kehadiran.akhir_jam_kerja is null AND master_data_absen_kehadiran.absen_masuk_kerja is not null AND master_data_absen_kehadiran.absen_pulang_kerja is null)
                        // ')
                        ->whereRaw('
                              ((master_data_absen_kehadiran.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                                AND master_data_absen_kehadiran.enroll_id IS NOT NULL)
                                AND master_data_absen_kehadiran.status_absen IN ("M","TL","LP")) OR ((master_data_absen_kehadiran.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                                AND master_data_absen_kehadiran.enroll_id IS NOT NULL)
                                AND master_data_absen_kehadiran.status_absen IN ("LN") AND master_data_absen_kehadiran.absen_masuk_kerja is not null AND master_data_absen_kehadiran.absen_pulang_kerja is 														null) OR ((master_data_absen_kehadiran.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                                AND master_data_absen_kehadiran.enroll_id IS NOT NULL)
                                AND master_data_absen_kehadiran.status_absen IN ("LP") AND master_data_absen_kehadiran.absen_masuk_kerja is not null AND master_data_absen_kehadiran.absen_pulang_kerja is 														null)
                                OR ((master_data_absen_kehadiran.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                                AND master_data_absen_kehadiran.enroll_id IS NOT NULL)
                                AND master_data_absen_kehadiran.mulai_jam_kerja is null AND master_data_absen_kehadiran.akhir_jam_kerja is null AND master_data_absen_kehadiran.absen_masuk_kerja is not null AND master_data_absen_kehadiran.absen_pulang_kerja is null)
                        ')
                        ->leftJoin('employee_atribut', 'master_data_absen_kehadiran.enroll_id','=','employee_atribut.enroll_id')
                        ->leftJoin('data_absen_perijinan','master_data_absen_kehadiran.nomor_absen_ijin','data_absen_perijinan.nomor_form_perizinan')
                        ->orderBy('tanggal_berjalan','desc')
                        ->orderBy('employee_name','asc')
                        ->offset($start)
                        ->limit($limit)
                        ->get();

                $totalData = MasterDataAbsenKehadiran::whereRaw('
                            ((master_data_absen_kehadiran.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                            AND master_data_absen_kehadiran.enroll_id IS NOT NULL)
                            AND master_data_absen_kehadiran.status_absen IN ("M","TL","LP")) OR ((master_data_absen_kehadiran.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                            AND master_data_absen_kehadiran.enroll_id IS NOT NULL)
                            AND master_data_absen_kehadiran.status_absen IN ("LN") AND master_data_absen_kehadiran.absen_masuk_kerja is not null AND master_data_absen_kehadiran.absen_pulang_kerja is null)
                            OR ((master_data_absen_kehadiran.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                            AND master_data_absen_kehadiran.enroll_id IS NOT NULL)
                            AND master_data_absen_kehadiran.mulai_jam_kerja is null AND master_data_absen_kehadiran.akhir_jam_kerja is null AND master_data_absen_kehadiran.absen_masuk_kerja is not null AND master_data_absen_kehadiran.absen_pulang_kerja is null)
                        ')
                        ->count();

                $totalFiltered = $totalData;

            } else {

                $query =  MasterDataAbsenKehadiran::selectRaw('
                            master_data_absen_kehadiran.uuid,
                            employee_atribut.employee_id,
                            master_data_absen_kehadiran.nomor_form_perubahan_absen,
                            employee_atribut.nik,
                            master_data_absen_kehadiran.enroll_id,
                            employee_atribut.employee_name,
                            master_data_absen_kehadiran.tanggal_berjalan,
                            master_data_absen_kehadiran.kode_hari,
                            master_data_absen_kehadiran.nama_hari,
                            master_data_absen_kehadiran.mulai_jam_kerja,
                            master_data_absen_kehadiran.akhir_jam_kerja,
                            master_data_absen_kehadiran.absen_masuk_kerja,
                            master_data_absen_kehadiran.absen_pulang_kerja,
                            master_data_absen_kehadiran.status_absen,
                            master_data_absen_kehadiran.holiday_name,
                            data_absen_perijinan.absen_alasan
                        ')
                        ->whereRaw('
                            (
                                (master_data_absen_kehadiran.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '" AND master_data_absen_kehadiran.enroll_id IS NOT NULL)
                                AND master_data_absen_kehadiran.status_absen IN ("M","TL","LP")
                                AND
                                (
                                    upper(master_data_absen_kehadiran.enroll_id) LIKE "%' . $searchData . '%"
                                    OR upper(master_data_absen_kehadiran.nama_hari) LIKE "%' . $searchData . '%"
                                    OR upper(master_data_absen_kehadiran.status_absen) LIKE "%' . $searchData . '%"
                                    OR upper(employee_atribut.employee_name) LIKE "%' . $searchData . '%"
                                    OR upper(employee_atribut.nik) LIKE "%' . $searchData . '%"
                                )
                            ) OR
                            (
                                (master_data_absen_kehadiran.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '" AND master_data_absen_kehadiran.enroll_id IS NOT NULL)
                                AND
                                (master_data_absen_kehadiran.status_absen IN ("LN") AND master_data_absen_kehadiran.absen_masuk_kerja is not null AND master_data_absen_kehadiran.absen_pulang_kerja is null)
                                AND
                                (
                                    upper(master_data_absen_kehadiran.enroll_id) LIKE "%' . $searchData . '%"
                                    OR upper(master_data_absen_kehadiran.nama_hari) LIKE "%' . $searchData . '%"
                                    OR upper(master_data_absen_kehadiran.status_absen) LIKE "%' . $searchData . '%"
                                    OR upper(employee_atribut.employee_name) LIKE "%' . $searchData . '%"
                                    OR upper(employee_atribut.nik) LIKE "%' . $searchData . '%"
                                )
                            ) OR
                             (
                                (master_data_absen_kehadiran.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '" AND master_data_absen_kehadiran.enroll_id IS NOT NULL)
                                AND
                                (master_data_absen_kehadiran.mulai_jam_kerja is not null AND master_data_absen_kehadiran.akhir_jam_kerja is not null AND ((master_data_absen_kehadiran.absen_masuk_kerja is not null AND master_data_absen_kehadiran.absen_pulang_kerja is null) OR (
                                master_data_absen_kehadiran.absen_masuk_kerja is null AND master_data_absen_kehadiran.absen_pulang_kerja is not null)) AND master_data_absen_kehadiran.status_absen = "LP" )
                                AND
                                (
                                    upper(master_data_absen_kehadiran.enroll_id) LIKE "%' . $searchData . '%"
                                    OR upper(master_data_absen_kehadiran.nama_hari) LIKE "%' . $searchData . '%"
                                    OR upper(master_data_absen_kehadiran.status_absen) LIKE "%' . $searchData . '%"
                                    OR upper(employee_atribut.employee_name) LIKE "%' . $searchData . '%"
                                    OR upper(employee_atribut.nik) LIKE "%' . $searchData . '%"
                                )
                             )
                        ')
                        ->leftJoin('employee_atribut', 'master_data_absen_kehadiran.enroll_id','=','employee_atribut.enroll_id')
                        ->leftJoin('data_absen_perijinan','master_data_absen_kehadiran.nomor_absen_ijin','data_absen_perijinan.nomor_form_perizinan')
                        ->offset($start)
                        ->limit($limit)
                        ->get();

                $totalData = MasterDataAbsenKehadiran::whereRaw('
                                (
                                    (master_data_absen_kehadiran.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '" AND master_data_absen_kehadiran.enroll_id IS NOT NULL)
                                    AND master_data_absen_kehadiran.status_absen IN ("M","TL","LP")
                                    AND
                                    (
                                        upper(master_data_absen_kehadiran.enroll_id) LIKE "%' . $searchData . '%"
                                        OR upper(master_data_absen_kehadiran.nama_hari) LIKE "%' . $searchData . '%"
                                        OR upper(master_data_absen_kehadiran.status_absen) LIKE "%' . $searchData . '%"
                                        OR upper(employee_atribut.employee_name) LIKE "%' . $searchData . '%"
                                        OR upper(employee_atribut.nik) LIKE "%' . $searchData . '%"
                                    )
                                ) OR (
                                    (master_data_absen_kehadiran.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '" AND master_data_absen_kehadiran.enroll_id IS NOT NULL)
                                    AND
                                    (master_data_absen_kehadiran.status_absen IN ("LN") AND master_data_absen_kehadiran.absen_masuk_kerja is not null AND master_data_absen_kehadiran.absen_pulang_kerja is null)
                                    AND
                                    (
                                        upper(master_data_absen_kehadiran.enroll_id) LIKE "%' . $searchData . '%"
                                        OR upper(master_data_absen_kehadiran.nama_hari) LIKE "%' . $searchData . '%"
                                        OR upper(master_data_absen_kehadiran.status_absen) LIKE "%' . $searchData . '%"
                                        OR upper(employee_atribut.employee_name) LIKE "%' . $searchData . '%"
                                        OR upper(employee_atribut.nik) LIKE "%' . $searchData . '%"
                                    )
                                ) OR
                                (
                                    (master_data_absen_kehadiran.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '" AND master_data_absen_kehadiran.enroll_id IS NOT NULL)
                                    AND
                                    (master_data_absen_kehadiran.mulai_jam_kerja is null AND master_data_absen_kehadiran.akhir_jam_kerja is null AND master_data_absen_kehadiran.absen_masuk_kerja is not null AND master_data_absen_kehadiran.absen_pulang_kerja is null)
                                    AND
                                    (
                                        upper(master_data_absen_kehadiran.enroll_id) LIKE "%' . $searchData . '%"
                                        OR upper(master_data_absen_kehadiran.nama_hari) LIKE "%' . $searchData . '%"
                                        OR upper(master_data_absen_kehadiran.status_absen) LIKE "%' . $searchData . '%"
                                        OR upper(employee_atribut.employee_name) LIKE "%' . $searchData . '%"
                                        OR upper(employee_atribut.nik) LIKE "%' . $searchData . '%"
                                    )
                                )
                            ')
                            ->leftJoin('employee_atribut', 'master_data_absen_kehadiran.enroll_id','=','employee_atribut.enroll_id')
                            ->count();
                $totalFiltered = $totalData;

            }
        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $nestedData['uuid'] = $q->uuid;
                $nestedData['employee_id'] = $q->employee_id;
                $nestedData['nomor_form_perubahan_absen'] = $q->nomor_form_perubahan_absen;
                $nestedData['nik'] = $q->nik;
                $nestedData['enroll_id'] = $q->enroll_id;
                $nestedData['employee_name'] = $q->employee_name;

                $nestedData['tanggal_berjalan'] = $q->tanggal_berjalan;

                setlocale(LC_ALL, 'id-ID', 'id_ID');
                $tanggal_berjalan_format = strtoupper(strftime("%d %b %Y", strtotime($q->tanggal_berjalan)));

                $nestedData['tanggal_berjalan_format'] = $tanggal_berjalan_format;
                $nestedData['kode_hari'] = $q->kode_hari;
                $nestedData['nama_hari'] = $q->nama_hari;
                $nestedData['mulai_jam_kerja'] = substr($q->mulai_jam_kerja, 0, 5);
                $nestedData['akhir_jam_kerja'] = substr($q->akhir_jam_kerja, 0, 5);
                $nestedData['absen_masuk_kerja'] = substr($q->absen_masuk_kerja, 0, 5);
                $nestedData['absen_pulang_kerja'] = substr($q->absen_pulang_kerja, 0, 5);
                $nestedData['status_absen'] = strtoupper($q->status_absen);
                $nestedData['holiday_name'] = $q->holiday_name;
                $nestedData['absen_alasan'] = $q->absen_alasan;

                $data[] = $nestedData;

            }
        }
        // dd($totalData);
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
            );

        echo json_encode($json_data);
        }
    }

    public function ajax_loggagalabsen(Request $request)
    {
        if(request()->ajax()) {

        $columns = array(
            0 => 'uuid',
            1 => 'nomor_form_gagal_absen',
            2 => 'tanggal_absen',
            3 => 'kode_hari',
            4 => 'nama_hari',
            5 => 'enroll_id',
            6 => 'employee_id',
            7 => 'nik',
            8 => 'employee_name',
            9 => 'status_absen_name',
            10 => 'updated_at'
        );

        $totalData = LogDataGagalAbsen::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $query = LogDataGagalAbsen::offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();
        } else {
            $search = $request->input('search.value');

            $query =  LogDataGagalAbsen::whereRaw('
                            uuid like "%' . $search . '%" or nik like "%' . $search . '%"
                                    or employee_name like "%' . $search . '%" or enroll_id like "%' . $search . '%"
                                    or department_name like "%' . $search . '%" or sub_dept_name like "%' . $search . '%"
                                    or status_absen like "%' . $search . '%" or nomor_form_gagal_absen like "%' . $search . '%"
                                    or nama_hari like "%' . $search . '%"
                            ')
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = LogDataGagalAbsen::count();
        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $nestedData['uuid'] = $q->uuid;
                $nestedData['uuid_master'] = $q->uuid_master;
                $nestedData['nomor_form_gagal_absen'] = $q->nomor_form_gagal_absen;
                $nestedData['tanggal_absen'] = $q->tanggal_absen;
                $nestedData['kode_hari'] = $q->kode_hari;
                $nestedData['nama_hari'] = $q->nama_hari;
                $nestedData['enroll_id'] = $q->enroll_id;
                $nestedData['employee_id'] = $q->employee_id;
                $nestedData['absen_in'] = $q->absen_in;
                $nestedData['absen_out'] = $q->absen_out;
                $nestedData['absen_in_old'] = $q->absen_in_old;
                $nestedData['absen_out_old'] = $q->absen_out_old;
                $nestedData['nik'] = $q->nik;
                $nestedData['employee_name'] = $q->employee_name;
                $nestedData['status_absen'] = $q->status_absen;
                $nestedData['status_absen_old'] = $q->status_absen_old;
                $nestedData['absen_alasan'] = $q->absen_alasan;
                $nestedData['absen_alasan_old'] = $q->absen_alasan_old;
                $nestedData['operator'] = $q->operator;
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

    public function form_gagalabsen(Request $request)
    {
        $uuid = $request->editData;

        $query =  MasterDataAbsenKehadiran::
                    selectRaw('master_data_absen_kehadiran.uuid, employee_atribut.nik, employee_atribut.employee_name, employee_atribut.employee_id, kode_hari, nama_hari,
                                tanggal_berjalan, employee_atribut.enroll_id, employee_atribut.department_id, employee_atribut.department_name,
                                employee_atribut.sub_dept_id, employee_atribut.sub_dept_name,
                                case when mulai_jam_kerja IS NOT NULL then "KERJA" ELSE "LIBUR" END kerjalibur,
                                concat(" [", substr(mulai_jam_kerja, 1, 5), " s/d ", substr(akhir_jam_kerja, 1, 5), "]") jadwal_jam_kerja,
                                mulai_jam_kerja, akhir_jam_kerja, employee_atribut.department_id, employee_atribut.department_name, employee_atribut.sub_dept_id, employee_atribut.sub_dept_name,
                                substr(absen_masuk_kerja, 1, 5) absen_masuk_kerja, substr(absen_pulang_kerja, 1, 5) absen_pulang_kerja,
                                jumlah_jam_kerja,
                                data_absen_perijinan.absen_alasan, status_absen, nomor_form_perubahan_absen')
                    ->join('employee_atribut', 'employee_atribut.enroll_id', '=', 'master_data_absen_kehadiran.enroll_id')
                    ->leftJoin('data_absen_perijinan', 'master_data_absen_kehadiran.nomor_absen_ijin', '=', 'data_absen_perijinan.nomor_form_perizinan')
                    ->where('master_data_absen_kehadiran.uuid','=',$uuid)->first();

        return Response()->json($query);
    }

    public function update(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
// dd(1);
        $uuid = $request->uuid;
        $tanggal_absen = $request->tanggal_absen;
        $status_absen = $request->status_absen;
        if(isset(RefHariLibur::where('tanggal_libur',$tanggal_absen)->pluck('status_absen')[0])){
            if(RefHariLibur::where('tanggal_libur',$tanggal_absen)->pluck('status_absen')[0]=='LN'){
                $status_absen='LN';
            }
        }
        if ($status_absen == 'KERJA') { $status_absen = null; $absen_alasan = null; }

        $absen_masuk_kerja = $request->absen_masuk_kerja;
        $absen_pulang_kerja = $request->absen_pulang_kerja;
        $absen_alasan = $request->absen_alasan;
        $nomor_form_perubahan_absen = $request->nomor_form_perubahan_absen;


        $query_absen=MasterDataAbsenKehadiran::where('uuid','=',$uuid)->first();

        $jadwal_in=$query_absen->mulai_jam_kerja;
        $jadwal_out=$query_absen->akhir_jam_kerja;

        $absen_in=$absen_masuk_kerja;
        $absen_out=$absen_pulang_kerja;
        // dd($absen_in);
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

        // if( $jadwal_out !=null && $absen_out !=null && $absen_out<$jadwal_out){
        //     $total_PC1 = $PC->i +($PC->h*60);
        //     if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
        //         if($absen_out <='12:00:00'){
        //             $total_PC=$total_PC1-60;
        //         }
        //         else if($absen_out >'12:00:00' && $absen_out <='13:00:00'){
        //             $selisih_menit = strtotime('13:00:00') - strtotime($absen_out);
        //             $selisih_menit = round($selisih_menit / 60);
        //             $total_PC=$total_PC1-$selisih_menit;
        //         }
        //         else {
        //             $total_PC=$total_PC1;
        //         }
        //     }else{
        //             $total_PC=$total_PC1;
        //     }
        //     $total_PC = $total_PC < 480 ? $total_PC : 480;
        //         }else{
        //     $total_PC=0;
        // }
        if ($jadwal_out != null && $absen_out != null && $absen_out < $jadwal_out) {

            $total_PC1 = $PC->i + ($PC->h * 60);
            $absen_out_time = strtotime($absen_out);

            // default
            $total_PC = $total_PC1;

            // SHIFT 07:00 & 07:30 (istirahat 12–13)
            if ($jadwal_in == '07:00:00' || $jadwal_in == '07:30:00') {

                if ($absen_out_time <= strtotime('12:00:00')) {
                    $total_PC -= 60;
                } else if ($absen_out_time > strtotime('12:00:00') && $absen_out_time < strtotime('13:00:00')) {
                    $total_PC -= round((strtotime('13:00:00') - $absen_out_time) / 60);
                }
            }

            // 🔥 SHIFT 06:00 (istirahat 10–11) — INI YANG HILANG
            else if ($jadwal_in == '06:00:00') {

                if ($absen_out_time <= strtotime('10:00:00')) {
                    $total_PC -= 60;
                } else if ($absen_out_time > strtotime('10:00:00') && $absen_out_time < strtotime('11:00:00')) {
                    $total_PC -= round((strtotime('11:00:00') - $absen_out_time) / 60);
                }
            }

            // pengaman
            $total_PC = max(0, min($total_PC, 480));

        } else {
            $total_PC = 0;
        }
        $is_staff = EmployeeAtribut::where('enroll_id',$request->enroll_id)->pluck('status_staff')[0];
        if($is_staff=='STAFF' && $total_DT <= 10){
            $total_DT = 0;
        }

            $jumlah_menit_absen_dtpc=$total_DT+$total_PC;
            if( $jadwal_in!=null && $jadwal_out !=null){
                $jumlah_absen_menit_kerja=$durasi_kerja_menit-$jumlah_menit_absen_dtpc;
            }
            else{
                $jumlah_absen_menit_kerja=0;
            }

        $query =  MasterDataAbsenKehadiran::where('uuid','=',$uuid)
                    ->update([
                        'tanggal_absen' => $tanggal_absen,
                        'status_absen' => $status_absen,
                        'absen_masuk_kerja' => $absen_masuk_kerja,
                        'absen_pulang_kerja' => $absen_pulang_kerja,
                        'jumlah_menit_absen_dtpc'=>$jumlah_menit_absen_dtpc,
                        'jumlah_absen_menit_kerja'=>$jumlah_absen_menit_kerja,
                        'jumlah_menit_absen_dt'=>$total_DT,
                        'jumlah_menit_absen_pc'=>$total_PC,
                        'operator' => $email,
                        'nomor_form_perubahan_absen' => $nomor_form_perubahan_absen,
                    ]);

        return Response()->json($query);
    }

    public function show_image(Request $request){
        $response = array(
          'data' => $request->image,
        );
        return response()->json($response);
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
        $sub_dept_id = $request->sub_dept_id;

        if(($department_id) && (!$sub_dept_id)) {
            $query =  EmployeeAtribut::selectRaw('enroll_id no_pin, nik, employee_name,
                                            concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                                        ->where('department_id','=',$department_id)
                                        ->groupby('nik')
                                        ->orderby('employee_name', 'asc')
                                        ->get();

            return $query;
        } else if(($department_id) && ($sub_dept_id)) {
            $query =  EmployeeAtribut::selectRaw('enroll_id no_pin, nik, employee_name,
                                            concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                                        ->where('department_id','=',$department_id)
                                        ->where('sub_dept_id','=',$sub_dept_id)
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

    public function ajax_getemployeselectdeptid(Request $request)
    {
        $department_id = $request->department_id;
        $dataDepartment = "";
        $inDepartmentID = '';

        if($department_id) {
            $dataDepartment = implode('","',$department_id);
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

    public function ajax_getemployeselectposisi(Request $request)
    {
        $selectPosisiName = $request->input('selectPosisiName');
        $posisi = '';

        if($selectPosisiName) {
            $posisi = ' posisi_name = "' . $selectPosisiName . '" ';
            $query =  EmployeeAtribut::selectRaw('enroll_id no_pin, nik, employee_name,
                            concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                            ->whereRaw('
                                ' . $posisi . '
                            ')
                            ->groupby('nik')
                            ->orderby('employee_name', 'asc')
                            ->get();
            info("Posisi : " . $posisi);
        } else {
            $query =  EmployeeAtribut::selectRaw('enroll_id no_pin, nik, employee_name,
                            concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                            ->groupby('nik')
                            ->orderby('employee_name', 'asc')
                            ->get();
        }

        return $query;

    }

    public function ajax_exportexcel(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');

        $daterange1 = explode(" - ", $request->input('daterange1'));
        $searchData = strtoupper($request->searchData);

        $fileName = 'DataGagalAbsenKaryawan_' . time() . '.xlsx';
        return (new GagalAbsenExport)->exportParams($daterange1, $searchData)->download($fileName);

    }

    public function screenlock()
    {
        Session::put('lock', '1');
        return View::make('admin/screen_lock', $this->data);
    }


}
