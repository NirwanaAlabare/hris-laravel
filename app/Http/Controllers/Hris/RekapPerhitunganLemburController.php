<?php

namespace App\Http\Controllers\Hris;

use App\Exports\RekapPerhitunganLemburExport;
use App\Http\Controllers\AdminBaseController;
use App\Models\MasterDataAbsenKehadiran;
use App\Models\RekapPerhitunganLembur;
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
class RekapPerhitunganLemburController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Rekap Perhitungan Lembur';
    }

    public function index()
    {
    //	$this->month = '2023-07';
    	$this->month = date('Y-m');
        return View::make('hris/rekaphitunglembur', $this->data);
    }

    public function ajax_rekap(Request $request)
    {

        $daterange1 = explode(" - ", $request->daterange1);
        $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
        $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));


        if(request()->ajax()) {
        $columns = array(
            0 => 'uuid',
            1 => 'enroll_id',
            2 => 'nik',
            3 => 'nomor_form_lembur',
            4 => 'employee_name',
            5 => 'posisi_name',
            6 => 'department_name',
            7 => 'sub_dept_name',
            8 => 'tanggal_berjalan',
            9 => 'kode_hari',
            10 => 'nama_hari',
            11 => 'kerjalibur',
            12 => 'mulai_jam_kerja',
            13 => 'akhir_jam_kerja',
            14 => 'jumlah_jam_kerja',
            15 => 'absen_masuk_kerja',
            16 => 'absen_pulang_kerja',
            17 => 'jam_efektif_kerja',
            18 => 'mulai_jam_lembur',
            19 => 'akhir_jam_lembur',
            20 => 'final_mulai_jam_lembur',
            21 => 'final_selesai_jam_lembur',
            22 => 'final_total_jam_lembur',
            23 => 'final_jam_istirahat_lembur',
            24 => 'final_total_menit_lembur',
            25 => 'final_jam_lembur_roundown',
            26 => 'final_menit_lembur_roundown',
            27 => 'lembur_1',
            28 => 'lembur_2',
            29 => 'lembur_3',
            30 => 'lembur_4',
            31 => 'total_lembur_1234',
            32 => 'salary',
            33 => 'lembur1_rupiah',
            34 => 'lembur2_rupiah',
            35 => 'lembur3_rupiah',
            36 => 'lembur4_rupiah',
            37 => 'total_lembur_rupiah'
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
                $query =  RekapPerhitunganLembur::offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();

                $totalData = RekapPerhitunganLembur::count();
                $totalFiltered = $totalData;
            } else {
                $query =  RekapPerhitunganLembur::whereRaw('
                    tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                ')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

                $totalData = RekapPerhitunganLembur::whereRaw('
                    tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                ')
                ->count();
                $totalFiltered = $totalData;
            }

        } else {
            $search = $request->input('search.value');

            $query =  RekapPerhitunganLembur::whereRaw('
                    tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                    and (UPPER(enroll_id) LIKE UPPER("%' . $search . '%")
                    or UPPER(nik) LIKE UPPER("%' . $search . '%")
                    or UPPER(nomor_form_lembur) LIKE UPPER("%' . $search . '%")
                    or UPPER(employee_name) LIKE UPPER("%' . $search . '%")
                    or UPPER(posisi_name) LIKE UPPER("%' . $search . '%")
                    or UPPER(department_name) LIKE UPPER("%' . $search . '%")
                    or UPPER(sub_dept_name) LIKE UPPER("%' . $search . '%")
                    or UPPER(nama_hari) LIKE UPPER("%' . $search . '%")
                    or UPPER(nomor_form_lembur) LIKE UPPER("%' . $search . '%")
                    or UPPER(holiday_name) LIKE UPPER("%' . $search . '%"))
                ')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalData = RekapPerhitunganLembur::whereRaw('
                    tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                    and (UPPER(enroll_id) LIKE UPPER("%' . $search . '%")
                    or UPPER(nik) LIKE UPPER("%' . $search . '%")
                    or UPPER(nomor_form_lembur) LIKE UPPER("%' . $search . '%")
                    or UPPER(employee_name) LIKE UPPER("%' . $search . '%")
                    or UPPER(posisi_name) LIKE UPPER("%' . $search . '%")
                    or UPPER(department_name) LIKE UPPER("%' . $search . '%")
                    or UPPER(sub_dept_name) LIKE UPPER("%' . $search . '%")
                    or UPPER(nama_hari) LIKE UPPER("%' . $search . '%")
                    or UPPER(nomor_form_lembur) LIKE UPPER("%' . $search . '%")
                    or UPPER(holiday_name) LIKE UPPER("%' . $search . '%"))
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
                $nestedData['enroll_id'] = $q->enroll_id;
                $nestedData['nik'] = $q->nik;
                $nestedData['nomor_form_lembur'] = $q->nomor_form_lembur;
                $nestedData['employee_name'] = $q->employee_name;
                $nestedData['posisi_name'] = $q->posisi_name;
                $nestedData['department_id'] = $q->department_id;
                $nestedData['department_name'] = $q->department_name;
                $nestedData['sub_dept_id'] = $q->sub_dept_id;
                $nestedData['sub_dept_name'] = $q->sub_dept_name;
                $nestedData['tanggal_berjalan'] = $q->tanggal_berjalan;
                $nestedData['kode_hari'] = $q->kode_hari;
                $nestedData['nama_hari'] = $q->nama_hari;

                $kerjalibur = "KERJA";
                switch ($q->kode_hari) {
                    case '5':
                        $kerjalibur = "LIBUR";
                        break;
                    case '6':
                        $kerjalibur = "LIBUR";
                        break;
                }

                if( $q->holiday_name <> "") {
                    switch ($q->kode_hari) {
                        case '5':
                            $kerjalibur = "LIBUR";
                            break;
                        case '6':
                            $kerjalibur = "LIBUR";
                            break;
                        default :
                            $kerjalibur = "LIBUR NASIONAL";
                            break;
                    }
                }


                $nestedData['kerjalibur'] = $kerjalibur;
                $nestedData['mulai_jam_kerja'] = substr($q->mulai_jam_kerja, 0, 5);
                $nestedData['akhir_jam_kerja'] = substr($q->akhir_jam_kerja, 0, 5);
                $nestedData['jumlah_jam_kerja'] = substr($q->jumlah_jam_kerja, 0, 5);
                $nestedData['absen_masuk_kerja'] = substr($q->absen_masuk_kerja, 0, 5);
                $nestedData['absen_pulang_kerja'] = substr($q->absen_pulang_kerja, 0, 5);
                $nestedData['jam_efektif_kerja'] = substr($q->jam_efektif_kerja, 0, 5);
                $nestedData['mulai_jam_lembur'] = substr($q->mulai_jam_lembur, 11, 5);
                $nestedData['akhir_jam_lembur'] = substr($q->akhir_jam_lembur, 11, 5);
                $nestedData['absen_masuk_kerja'] = substr($q->absen_masuk_kerja, 0, 5);
                $nestedData['absen_pulang_kerja'] = substr($q->absen_pulang_kerja, 0, 5);
                $nestedData['final_mulai_jam_lembur'] = substr($q->final_mulai_jam_lembur, 0, 5);
                $nestedData['final_selesai_jam_lembur'] = substr($q->final_selesai_jam_lembur, 0, 5);
                $nestedData['final_total_jam_lembur'] = substr($q->final_total_jam_lembur, 0, 5);
                $nestedData['final_jam_istirahat_lembur'] = $q->final_jam_istirahat_lembur;
                $nestedData['final_total_menit_lembur'] = $q->final_total_menit_lembur;
                $nestedData['final_jam_lembur_roundown'] = $q->final_jam_lembur_roundown;
                $nestedData['final_menit_lembur_roundown'] = $q->final_menit_lembur_roundown;
                $nestedData['lembur_1'] = $q->lembur_1;
                $nestedData['lembur_2'] = $q->lembur_2;
                $nestedData['lembur_3'] = $q->lembur_3;
                $nestedData['lembur_4'] = $q->lembur_4;
                $nestedData['total_lembur_1234'] = $q->total_lembur_1234;
                $nestedData['salary'] = $q->salary;
                $nestedData['lembur1_rupiah'] = $q->lembur1_rupiah;
                $nestedData['lembur2_rupiah'] = $q->lembur2_rupiah;
                $nestedData['lembur3_rupiah'] = $q->lembur3_rupiah;
                $nestedData['lembur4_rupiah'] = $q->lembur4_rupiah;
                $nestedData['total_lembur_rupiah'] = $q->total_lembur_rupiah;
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

        $fileName = 'RekapPerhitunganLembur_' . time() . '.xlsx';
        return (new RekapPerhitunganLemburExport)->exportParams($daterange1)->download($fileName);

    }

}
