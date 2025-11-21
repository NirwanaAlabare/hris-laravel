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


    public function cekHari($tanggal, $bahasa = 'id')
    {
        // Konversi tanggal ke timestamp
        $timestamp = strtotime($tanggal);

        if (!$timestamp) {
            return "Format tanggal tidak valid.";
        }

        // Nama hari dalam bahasa Inggris
        $hariInggris = date('l', $timestamp);

        // Array nama hari dalam bahasa Indonesia
        $hariIndonesia = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu'
        ];

        // Kembalikan nama hari sesuai bahasa
        if ($bahasa === 'id') {
            return $hariIndonesia[$hariInggris] ?? "Hari tidak ditemukan.";
        }

        return $hariInggris; // Default ke bahasa Inggris
    }

    public function ajax_rekap(Request $request)
    {

        $daterange1 = explode(" - ", $request->daterange1);
        // dd($daterange1);
        $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
        $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));

        if (request()->ajax()) {
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

            if (empty($request->input('search.value'))) {
                if (empty($daterange1)) {
                    $query =  RekapPerhitunganLembur::selectRaw('rekap_perhitungan_lembur.*,employee_atribut.*, mda.*')->offset($start)
                        ->leftJoin('employee_atribut', 'rekap_perhitungan_lembur.enroll_id', '=', 'employee_atribut.enroll_id')
                        ->leftJoin('master_data_absen_kehadiran as mda', function ($leftjoin) {
                            $leftjoin->on("mda.tanggal_berjalan", "=", "rekap_perhitungan_lembur.tanggal_berjalan")
                                ->on("mda.enroll_id", "=", "rekap_perhitungan_lembur.enroll_id");
                        })
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();
                    $totalData = RekapPerhitunganLembur::count();
                    $totalFiltered = $totalData;
                } else {
                    $query =  RekapPerhitunganLembur::selectRaw('employee_atribut.*, mda.nomor_form_lembur, mda.mulai_jam_kerja, mda.akhir_jam_kerja, mda.tanggal_berjalan, mda.absen_masuk_kerja, mda.absen_pulang_kerja, dl.mulai_jam_lembur, dl.akhir_jam_lembur, rekap_perhitungan_lembur.final_mulai_jam_lembur, rekap_perhitungan_lembur.final_selesai_jam_lembur, rekap_perhitungan_lembur.final_total_jam_lembur, rekap_perhitungan_lembur.final_total_menit_lembur, rekap_perhitungan_lembur.final_jam_lembur_roundown, rekap_perhitungan_lembur.final_menit_lembur_roundown, rekap_perhitungan_lembur.final_jam_istirahat_lembur, rekap_perhitungan_lembur.lembur_1, rekap_perhitungan_lembur.lembur_2, rekap_perhitungan_lembur.lembur_3, rekap_perhitungan_lembur.lembur_4, rekap_perhitungan_lembur.total_lembur_1234, rekap_perhitungan_lembur.lembur1_rupiah , rekap_perhitungan_lembur.lembur2_rupiah, rekap_perhitungan_lembur.lembur3_rupiah, rekap_perhitungan_lembur.lembur4_rupiah, rekap_perhitungan_lembur.total_lembur_rupiah, grading_salary.salary_bulanan')->whereRaw('
                    mda.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                ')
                        ->leftJoin('employee_atribut', 'rekap_perhitungan_lembur.enroll_id', '=', 'employee_atribut.enroll_id')
                        ->leftJoin(
                            \DB::raw('(SELECT * FROM grading_salary) AS grading_salary'),
                            function ($leftjoin) {
                                $leftjoin->on(
                                    \DB::raw('SUBSTRING(grading_salary.periode_umk, 1, 4)'),
                                    '=',
                                    \DB::raw('SUBSTRING(rekap_perhitungan_lembur.tanggal_berjalan, 1, 4)')
                                )
                                    ->on('grading_salary.kode_grade', '=', 'employee_atribut.kode_grade');
                            }
                        )
                        ->leftJoin('master_data_absen_kehadiran as mda', function ($leftjoin) {
                            $leftjoin->on("mda.tanggal_berjalan", "=", "rekap_perhitungan_lembur.tanggal_berjalan")
                                ->on("mda.enroll_id", "=", "rekap_perhitungan_lembur.enroll_id");
                        })
                        ->leftJoin('data_lembur as dl', function ($leftjoin) {
                            $leftjoin->on("dl.tanggal_berjalan", "=", "rekap_perhitungan_lembur.tanggal_berjalan")
                                ->on("dl.enroll_id", "=", "rekap_perhitungan_lembur.enroll_id");
                        })
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();

                    $totalData = RekapPerhitunganLembur::whereRaw('
                    tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                ')
                        ->count();
                    $totalFiltered = $totalData;
                }
            } else {
                $search = $request->input('search.value');
                $query =  RekapPerhitunganLembur::selectRaw('employee_atribut.*, mda.nomor_form_lembur, mda.mulai_jam_kerja, mda.akhir_jam_kerja, mda.tanggal_berjalan, mda.absen_masuk_kerja, mda.absen_pulang_kerja, dl.mulai_jam_lembur, dl.akhir_jam_lembur, rekap_perhitungan_lembur.final_mulai_jam_lembur, rekap_perhitungan_lembur.final_selesai_jam_lembur, rekap_perhitungan_lembur.final_total_jam_lembur, rekap_perhitungan_lembur.final_total_menit_lembur, rekap_perhitungan_lembur.final_jam_lembur_roundown, rekap_perhitungan_lembur.final_menit_lembur_roundown, rekap_perhitungan_lembur.final_jam_istirahat_lembur, rekap_perhitungan_lembur.lembur_1, rekap_perhitungan_lembur.lembur_2, rekap_perhitungan_lembur.lembur_3, rekap_perhitungan_lembur.lembur_4, rekap_perhitungan_lembur.total_lembur_1234, rekap_perhitungan_lembur.lembur1_rupiah , rekap_perhitungan_lembur.lembur2_rupiah, rekap_perhitungan_lembur.lembur3_rupiah, rekap_perhitungan_lembur.lembur4_rupiah, rekap_perhitungan_lembur.total_lembur_rupiah, grading_salary.salary_bulanan')->whereRaw('
                    mda.tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                    and (UPPER(employee_atribut.enroll_id) LIKE UPPER("%' . $search . '%")
                    or UPPER(employee_atribut.nik) LIKE UPPER("%' . $search . '%")
                    or UPPER(mda.nomor_form_lembur) LIKE UPPER("%' . $search . '%")
                    or UPPER(employee_atribut.employee_name) LIKE UPPER("%' . $search . '%")
                    or UPPER(employee_atribut.status_jabatan) LIKE UPPER("%' . $search . '%")
                    or UPPER(employee_atribut.department_name) LIKE UPPER("%' . $search . '%")
                    or UPPER(employee_atribut.sub_dept_name) LIKE UPPER("%' . $search . '%")
                    or UPPER(mda.nomor_form_lembur) LIKE UPPER("%' . $search . '%")
                    )
                ')
                    ->leftJoin('employee_atribut', 'rekap_perhitungan_lembur.enroll_id', '=', 'employee_atribut.enroll_id')
                    ->leftJoin(
                        \DB::raw('(SELECT * FROM grading_salary) AS grading_salary'),
                        function ($leftjoin) {
                            $leftjoin->on(
                                \DB::raw('SUBSTRING(grading_salary.periode_umk, 1, 4)'),
                                '=',
                                \DB::raw('SUBSTRING(rekap_perhitungan_lembur.tanggal_berjalan, 1, 4)')
                            )
                                ->on('grading_salary.kode_grade', '=', 'employee_atribut.kode_grade');
                        }
                    )
                    ->leftJoin('master_data_absen_kehadiran as mda', function ($leftjoin) {
                        $leftjoin->on("mda.tanggal_berjalan", "=", "rekap_perhitungan_lembur.tanggal_berjalan")
                            ->on("mda.enroll_id", "=", "rekap_perhitungan_lembur.enroll_id");
                    })
                    ->leftJoin('data_lembur as dl', function ($leftjoin) {
                        $leftjoin->on("dl.tanggal_berjalan", "=", "rekap_perhitungan_lembur.tanggal_berjalan")
                            ->on("dl.enroll_id", "=", "rekap_perhitungan_lembur.enroll_id");
                    })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalData = RekapPerhitunganLembur::whereRaw('
                    tanggal_berjalan BETWEEN "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                    and (UPPER(employee_atribut.enroll_id) LIKE UPPER("%' . $search . '%")
                    or UPPER(nomor_form_lembur) LIKE UPPER("%' . $search . '%")
                    or UPPER(nomor_form_lembur) LIKE UPPER("%' . $search . '%")
                    )
                ')
                    ->leftJoin('employee_atribut', 'rekap_perhitungan_lembur.enroll_id', '=', 'employee_atribut.enroll_id')
                    ->count();
                $totalFiltered = $totalData;
            }

            $data = array();
            if (!empty($query)) {
                foreach ($query as $q) {
                    $nestedData['uuid'] = $q->uuid;
                    $nestedData['enroll_id'] = $q->enroll_id;
                    $nestedData['nik'] = $q->nik;
                    $nestedData['nomor_form_lembur'] = $q->nomor_form_lembur;
                    $nestedData['employee_name'] = $q->employee_name;
                    $nestedData['posisi_name'] = $q->status_jabatan;
                    $nestedData['department_id'] = $q->department_id;
                    $nestedData['department_name'] = $q->department_name;
                    $nestedData['sub_dept_id'] = $q->sub_dept_id;
                    $nestedData['sub_dept_name'] = $q->sub_dept_name;
                    $nestedData['tanggal_berjalan'] = $q->tanggal_berjalan;
                    $nestedData['kode_hari'] = $q->kode_hari;
                    $nestedData['nama_hari'] = $this->cekHari($q->tanggal_berjalan);

                    $kerjalibur = "KERJA";
                    $jumlah_menit_istirahat = '01:00';
                    switch ($q->kode_hari) {
                        case '5':
                            $kerjalibur = "LIBUR";
                            $jumlah_menit_istirahat = '00:30';
                            break;
                        case '6':
                            $kerjalibur = "LIBUR";
                            $jumlah_menit_istirahat = '00:30';
                            break;
                    }

                    if ($q->holiday_name <> "") {
                        switch ($q->kode_hari) {
                            case '5':
                                $kerjalibur = "LIBUR";
                                $jumlah_menit_istirahat = '00:30';
                                break;
                            case '6':
                                $kerjalibur = "LIBUR";
                                $jumlah_menit_istirahat = '00:30';
                                break;
                            default:
                                $kerjalibur = "LIBUR NASIONAL";
                                $jumlah_menit_istirahat = '00:30';
                                break;
                        }
                    }


                    $mulai_jam_kerja = strtotime($q->mulai_jam_kerja);
                    $akhir_jam_kerja = strtotime($q->akhir_jam_kerja);
                    $jumlah_detik_istirahat = strtotime($jumlah_menit_istirahat);
                    $time = explode(":", $jumlah_menit_istirahat);
                    $minutes = intval($time[0]) * 60 + intval($time[1]);
                    $total_jam_kerja = (($akhir_jam_kerja - $mulai_jam_kerja) / 60) - $minutes;

                    $absen_masuk_kerja = strtotime($q->absen_masuk_kerja);
                    $absen_pulang_kerja = strtotime($q->absen_pulang_kerja);
                    $jumlah_detik_istirahat = strtotime($jumlah_menit_istirahat);
                    $total_efektif_jam_kerja = (($absen_pulang_kerja - $absen_masuk_kerja) / 60) - $minutes;


                    $nestedData['kerjalibur'] = $kerjalibur;
                    $nestedData['mulai_jam_kerja'] = substr($q->mulai_jam_kerja, 0, 5);
                    $nestedData['akhir_jam_kerja'] = substr($q->akhir_jam_kerja, 0, 5);
                    $nestedData['jumlah_jam_kerja'] = $total_jam_kerja;
                    $nestedData['absen_masuk_kerja'] = substr($q->absen_masuk_kerja, 0, 5);
                    $nestedData['absen_pulang_kerja'] = substr($q->absen_pulang_kerja, 0, 5);
                    $nestedData['jam_efektif_kerja'] = $total_efektif_jam_kerja;
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
                    $nestedData['salary'] = $q->salary_bulanan;
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
    /**
     * Executes the Stored Procedure for overtime calculation via AJAX.
     * * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_overtime(Request $request)
    {
        // 1. Validation (Basic checks for required data)
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date',
           
        ]);

        // 2. Data Retrieval and Setup
        try {
            // Retrieve parameters from the AJAX POST request
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
            $enrollIds = $request->enroll_ids;

            // Get the operator email from the currently authenticated user
            // NOTE: Ensure the request is handled by a route with 'auth' middleware.
            $operatorEmail = 'email';

            // 3. Stored Procedure Execution
            // 1. Get the raw PDO connection instance
            $pdo = DB::connection()->getPdo();

            // 2. Prepare the CALL statement string
            $sql = 'CALL Used_CalculateFullOvertimeRekap(?, ?, ?, ?)';

            // 3. Prepare the statement and execute it
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $tanggalAwal,
                $tanggalAkhir,
                $enrollIds,
                $operatorEmail
            ]);

            // Result Set 1: Final OK Data (table1 in SP logic, mapped to Tab2/table2 in HTML)
            $table1_ok_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->nextRowset();

            // Result Set 2: Anomalous Data (table2 in SP logic, mapped to Tab3/table3 in HTML)
            $table2_anomaly_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->nextRowset();

            // Result Set 3: All temp_rekap_result (table3 in SP logic, mapped to Tab4/table4 in HTML)
            $table3_raw_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->nextRowset();

            // Result Set 4: Dashboard Summary (table4 in SP logic, mapped to Tab1/table1 in HTML)
            $table4_summary_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            // 5. Return Combined JSON Response
            return response()->json([
                'status' => 'success',
                'message' => 'Calculation and data retrieval complete.',
                'data' => [
                    // Tab 1 (Dashboard) gets SP Result 4
                    'tab1_data' => $table4_summary_data,
                    // Tab 2 (OK Data) gets SP Result 1
                    'tab2_data' => $table1_ok_data,
                    // Tab 3 (Not OK Data) gets SP Result 2
                    'tab3_data' => $table2_anomaly_data,
                    // Tab 4 (All Data) gets SP Result 3
                    'tab4_data' => $table3_raw_data,
                ]
            ]);
        } catch (Exception $e) {
            // 5. Handle Errors
            // Log the error for debugging
            \Log::error("Overtime SP execution failed: " . $e->getMessage(), ['user' => $request->user()->id ?? 'N/A']);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process calculation. Database error occurred.'
            ], 500); // Return a 500 status code for server errors
        }
    }
}
