<?php

namespace App\Http\Controllers\Hris\MasterData;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\View;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use App\Models\EmployeeAtributHistory;
use App\Models\MutKaryawan;
use Carbon\Carbon;
use Dompdf\Options;
use Dompdf\FontMetrics;
use App\Models\EmployeeAtribut;
use App\Models\PengajuanBazzar;
use App\Models\VoucherBazzar;
use App\Models\RefAbsenIjin;
use App\Models\EntertainPengajuanTamu;
use App\Models\EntertainPengajuanPendamping;
use App\Models\EntertainPengajuanKeterangan;
use App\Models\DataAbsenPerijinan;
use App\Models\PengajuanDokumenLegal;
use App\Models\DepartmentAll;
use App\Models\MasterDataAbsenKehadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Yajra\DataTables\Facades\DataTables;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Exports\ExportLineSheet;
use App\Exports\ExportPengajuanKas;
use App\Exports\ExportPengajuanBazzar;
use Illuminate\Support\Facades\Storage;
use FilippoToso\PdfWatermarker\Facades\ImageWatermarker;
use FilippoToso\PdfWatermarker\Support\Position;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Exports\RekapCutiKaryawanKaryawanAll;
use \avadim\FastExcelLaravel\Excel as FastExcel;


class CutiKaryawanController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Dashboard';
    }

    public function index(){
        $tglskrg = date('Y-m-d');
        $user = Auth::guard('admin')->user()->email;
        $data_dept = DB::select("select
        d.sub_dept_id isi,
        concat(department_name,' - ', sub_dept_name) tampil
        from department_all d
        left join
        (select sub_dept_id,count(employee_id) tot from employee_atribut where status_aktif = 'aktif' group by sub_dept_id) e on d.sub_dept_id = e.sub_dept_id
        where site_nirwana_id = 'NAG'
        and sub_dept_name not like 'line%'
        and e.tot != '0'
        group by d.sub_dept_id
        order by department_name asc");

        $refabsenijin = $this->ajax_getselectrefabsenijin();

        $selectemployee = $this->ajax_getallemployeeatribut();
        return view('hris/absen/cuti_karyawan/cuti_karyawan', [
            'page' => 'dashboard-mut-karyawan', "subPageGroup" => "proses-karyawan", "subPage" => "form-lembur-non-sewing",
            "data_dept" => $data_dept, "user" => $user,
            "selectemployee" => $selectemployee,
            "refabsenijin" => $refabsenijin,
        ], $this->data);
    }


    public function index_pengajuan_perizinan_admin(){
        $tglskrg = date('Y-m-d');
        $user = Auth::guard('admin')->user()->email;
        $data_dept = DB::select("select
        d.sub_dept_id isi,
        concat(department_name,' - ', sub_dept_name) tampil
        from department_all d
        left join
        (select sub_dept_id,count(employee_id) tot from employee_atribut where status_aktif = 'aktif' group by sub_dept_id) e on d.sub_dept_id = e.sub_dept_id
        where site_nirwana_id = 'NAG'
        and sub_dept_name not like 'line%'
        and e.tot != '0'
        group by d.sub_dept_id
        order by department_name asc");

        $refabsenijin = $this->ajax_getselectrefabsenijin();

        $selectemployee = $this->ajax_getallemployeeatribut();

        return view('hris/absen/cuti_karyawan/pengajuan_perizinan_admin', [
            'page' => 'dashboard-mut-karyawan', "subPageGroup" => "proses-karyawan", "subPage" => "form-lembur-non-sewing",
            "data_dept" => $data_dept, "user" => $user,
            "selectemployee" => $selectemployee,
            "refabsenijin" => $refabsenijin,
        ], $this->data);
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

    public function store(Request $request)
    {
       $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        info('START REPLACE IZIN');
        info('Tambah Permohonan Perizinan by ' . $email);

        $uuid_master = $request->uuid;
        $tanggal_perizinan=substr($request->tanggal_perizinan,6,4).'-'.substr($request->tanggal_perizinan,3,2).'-'.substr($request->tanggal_perizinan,0,2);
        $tanggal_mulai_ijin=substr($request->tanggal_mulai_ijin,6,4).'-'.substr($request->tanggal_mulai_ijin,3,2).'-'.substr($request->tanggal_mulai_ijin,0,2);
        $tanggal_akhir_ijin=substr($request->tanggal_akhir_ijin,6,4).'-'.substr($request->tanggal_akhir_ijin,3,2).'-'.substr($request->tanggal_akhir_ijin,0,2);
        $nomor_form_perizinan = $request->nomor_form_perizinan;
        $enroll_id = $request->enroll_id;
        $nik = $request->nik;
        $employee_name = $request->employee_name;
        $kode_absen_ijin = $request->kode_absen_ijin;
        $absen_alasan = $request->absen_alasan;
        $query = false;

        switch ($kode_absen_ijin) {
            case 'DL':
                $nomor_form_perizinan = 'FPI/HR/' . substr($tanggal_perizinan, 2, 2) . substr($tanggal_perizinan, 5, 2) . '/';
                break;
            case 'I':
                $nomor_form_perizinan = 'FPI/HR/' . substr($tanggal_perizinan, 2, 2) . substr($tanggal_perizinan, 5, 2) . '/';
                break;
            case 'S':
                $nomor_form_perizinan = 'FPI/HR/' . substr($tanggal_perizinan, 2, 2) . substr($tanggal_perizinan, 5, 2) . '/';
                break;
            default:
                if ($kode_absen_ijin <> 'M') {
                    $nomor_form_perizinan = 'FPC/HR/' . substr($tanggal_perizinan, 2, 2) . substr($tanggal_perizinan, 5, 2) . '/';
                } else {
                    $nomor_form_perizinan = 'TIDAK DI KENALI';
                }
                break;
        }


        if ($kode_absen_ijin=='LP') {
            $is_verifikasi=1;
            $verifikasi_by='system';
        }
        else{
            $is_verifikasi=0;
            $verifikasi_by=null;
        }
        $query=DB::select('select nomor_form_perizinan,CAST(substring(nomor_form_perizinan,13) AS int) as nomor_form from data_absen_perijinan where nomor_form_perizinan LIKE "'.$nomor_form_perizinan.'%" order by nomor_form desc limit 1');
        if(count($query)==0) {
            $nomor = "0000";
            $nomor_form_perizinan =  $nomor_form_perizinan . $nomor;
        } else {
            $nomor = $query[0]->nomor_form_perizinan;
            if(strlen($query[0]->nomor_form)<5){
                $nomorform = str_pad(substr($nomor, -4) + 1,4,"0",STR_PAD_LEFT);
            }else{
                $nomorform = str_pad(substr($nomor, -5) + 1,4,"0",STR_PAD_LEFT);
            }
            $nomor_form_perizinan =  $nomor_form_perizinan . $nomorform;
        }

        $query = DataAbsenPerijinan::create([
            'uuid' => Str::uuid(),
            'uuid_master' => $uuid_master,
            'tanggal_perizinan' => $tanggal_perizinan,
            'nomor_form_perizinan' => $nomor_form_perizinan,
            'enroll_id' => $enroll_id,
            'kode_absen_ijin' => $kode_absen_ijin,
            'absen_alasan' => $absen_alasan,
            'tanggal_mulai_ijin' => $tanggal_mulai_ijin,
            'tanggal_akhir_ijin' => $tanggal_akhir_ijin,
            'is_verifikasi'=>$is_verifikasi,
            'verifikasi_by'=>$verifikasi_by,
            'operator' => $email
        ]);

        if ($query) {
            info('Insert data nomor [' . $nomor_form_perizinan . '] on table data_absen_perijinan is SUCCESS.');
        } else {
            info('Insert data nomor [' . $nomor_form_perizinan . '] on table data_absen_perijinan is FAILED.');
        }

        if($query) {
            if($kode_absen_ijin=='DL') {

                $query1 = MasterDataAbsenKehadiran::whereBetween('tanggal_berjalan', [$tanggal_mulai_ijin, $tanggal_akhir_ijin])
                ->where('enroll_id', $enroll_id)
                ->where(function ($query3) {
                    $query3->whereNotIn('kode_hari', [6, 5]);
                })->where(function ($query){
                    $query->where('status_absen','!=','LN')
                    ->orWhere('status_absen',null);
                })->update([
                    'nomor_absen_ijin' => $nomor_form_perizinan,
                    'status_absen' => $kode_absen_ijin,
                    'operator' => $email,
                    'jumlah_menit_absen_dt'=>0,
                    'jumlah_menit_absen_pc'=>0,
                    'jumlah_menit_absen_dtpc'=>0
                ]);

                if($query1) {
                    info('Update on table master_data_absen_kehadiran after update Permohonan Perizinan [' . $nomor_form_perizinan . '] on table data_absen_perijinan is SUCCESS' );
                } else {
                    info('Update on table master_data_absen_kehadiran after update Permohonan Perizinan [' . $nomor_form_perizinan . '] on table data_absen_perijinan is FAILED' );
                }

            }else{
                if($kode_absen_ijin=='LN'){
                    $query1 = MasterDataAbsenKehadiran::whereBetween('tanggal_berjalan', [$tanggal_mulai_ijin, $tanggal_akhir_ijin])
                    ->where('enroll_id', $enroll_id)
                    ->update([
                        'nomor_absen_ijin' => $nomor_form_perizinan,
                        'status_absen' => $kode_absen_ijin,
                        'jumlah_menit_absen_dt'=>0,
                        'jumlah_menit_absen_pc'=>0,
                        'jumlah_menit_absen_dtpc'=>0,
                        'operator' => $email
                    ]);
                }else{
                    $query1 = MasterDataAbsenKehadiran::whereBetween('tanggal_berjalan', [$tanggal_mulai_ijin, $tanggal_akhir_ijin])
                    ->where('enroll_id', $enroll_id)
                    ->where(function ($query3) {
                        $query3->whereNotIn('kode_hari', [6, 5])
                            ->orWhereNotNull('mulai_jam_kerja');
                    })->where('status_absen','!=','LN')->update([
                        'nomor_absen_ijin' => $nomor_form_perizinan,
                        'status_absen' => $kode_absen_ijin,
                        'operator' => $email,
                        'absen_masuk_kerja'=>null,
                        'absen_pulang_kerja'=>null,
                        'jumlah_menit_absen_dt'=>0,
                        'jumlah_menit_absen_pc'=>0,
                        'jumlah_menit_absen_dtpc'=>0,
                    ]);
                }
            }

        }
        info('END REPLACE IZIN');

        return $query;
    }
    public function getData(Request $request)
    {

        $enroll_ids = $request->input('selectEmployeeID', []);
        $has_filter = count($enroll_ids) > 0;

        $bindings = [];
        $filterClause = '';

        if ($has_filter) {
            $placeholders = implode(',', array_fill(0, count($enroll_ids), '?'));
            $filterClause = "AND enroll_id IN ($placeholders)";
            $bindings = $enroll_ids;
        }

        $query = "
            WITH RECURSIVE periode AS (
                SELECT
                    ea.enroll_id,
                    ea.employee_name,
                    ea.department_name,
                    ea.nik,
                    ea.status_aktif,
                    ea.tanggal_resign,
                    ea.sub_dept_name,
                    ea.join_date,
                    ea.join_date AS start_date,
                    LEAST(DATE_ADD(ea.join_date, INTERVAL 1 YEAR), CURDATE()) AS end_date
                FROM (
                    SELECT *
                    FROM employee_atribut
                    WHERE join_date IS NOT NULL
                    $filterClause
                    ORDER BY enroll_id
                    LIMIT 10
                ) ea

                UNION ALL

                SELECT
                    p.enroll_id,
                    p.employee_name,
                    p.department_name,
                    p.nik,
                    p.status_aktif,
                    p.tanggal_resign,
                    p.sub_dept_name,
                    p.join_date,
                    p.end_date AS start_date,
                    LEAST(DATE_ADD(p.end_date, INTERVAL 1 YEAR), CURDATE()) AS end_date
                FROM periode p
                WHERE p.end_date < CURDATE()
            ),

            cuti_dipakai AS (
                SELECT
                    p.enroll_id,
                    p.start_date,
                    p.end_date,
                    COUNT(d.uuid) AS used_leave
                FROM periode p
                LEFT JOIN data_absen_perijinan d
                ON d.enroll_id = p.enroll_id
                AND d.kode_absen_ijin = 'CT'
                AND d.tanggal_mulai_ijin >= p.start_date
                AND d.tanggal_mulai_ijin < p.end_date
                GROUP BY p.enroll_id, p.start_date, p.end_date
            ),

            data_cuti AS (
                SELECT
                    p.enroll_id,
                    p.employee_name,
                    p.department_name,
                    p.nik,
                    p.status_aktif,
                    p.tanggal_resign,
                    p.sub_dept_name,
                    p.join_date,
                    p.start_date,
                    p.end_date,
                    CONCAT(
                        TIMESTAMPDIFF(YEAR, p.join_date, p.end_date), ' tahun ',
                        TIMESTAMPDIFF(MONTH, p.join_date, p.end_date) % 12, ' bulan'
                    ) AS lama_bekerja,
                    CASE
                        WHEN p.start_date < DATE_ADD(p.join_date, INTERVAL 1 YEAR) THEN 0
                        ELSE 1
                    END AS is_eligible,
                    COALESCE(c.used_leave, 0) AS used_leave,
                    CASE
                        WHEN p.start_date < DATE_ADD(p.join_date, INTERVAL 1 YEAR) THEN 0
                        ELSE 12 - COALESCE(c.used_leave, 0)
                    END AS remaining_leave,
                    CASE
                        WHEN p.start_date < DATE_ADD(p.join_date, INTERVAL 1 YEAR) THEN 'Belum Berhak'
                        WHEN (12 - COALESCE(c.used_leave, 0)) > 0 THEN 'Masih Memiliki Cuti'
                        ELSE 'Cuti Habis'
                    END AS leave_status,
                    ROW_NUMBER() OVER (PARTITION BY p.enroll_id ORDER BY p.end_date DESC) AS rn
                FROM periode p
                LEFT JOIN cuti_dipakai c
                    ON p.enroll_id = c.enroll_id AND p.start_date = c.start_date
            )

            SELECT *
            FROM data_cuti
            WHERE rn = 1
            ORDER BY enroll_id
        ";

        $data_cuti = DB::select($query, $bindings);
            return datatables()->of($data_cuti)
            ->addColumn('join_date', function ($row) {
                return Carbon::parse($row->join_date)->format('d-m-Y'); // Format: DDMMYYYY
            })
            ->addColumn('tanggal_resign', function ($row) {
                return $row->tanggal_resign
                    ? Carbon::parse($row->tanggal_resign)->format('d-m-Y')
                    : '-';
            })
            ->addColumn('start_date', function ($row) {
                return Carbon::parse($row->start_date)->format('d-m-Y'); // Format: DDMMYYYY
            })
            ->addColumn('end_date', function ($row) {
                return Carbon::parse($row->end_date)->format('d-m-Y'); // Format: DDMMYYYY
            })
            ->addColumn('is_eligible', function ($row) {
                $isEligible = (int) $row->is_eligible;
                $badgeClass = $isEligible ? 'badge-success' : 'badge-danger';
                $text = $isEligible ? '12' : '0';

                return '<span class="badge ' . $badgeClass . '">' . $text . '</span>';
            })
            ->addColumn('actions', function ($row) {

                return  '<div>
                           <a class="btn btn-primary btn-sm" style="color:white;" data-toggle="tooltip" title="Export Data ke File Transfer PDF" id="open_detail_'.$row->enroll_id.'" data-nik="'.$row->nik.'" data-employee_name="'.$row->employee_name.'">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                </a>
                        </div>';
            })
            ->rawColumns(['is_eligible','actions'])
            ->make(true);
               // return  '<div> <a class="btn btn-success btn-sm" style="color:white;" data-toggle="tooltip" title="Export Data ke File Transfer PDF" id="recap_labor_cost_2">
                //                         <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                //                     </a>
                //             <a class="btn btn-danger btn-sm" style="color:white;" data-toggle="tooltip" title="Export Data ke File Transfer PDF" id="export_form_pengajuan_cuti_'.$row->enroll_id.'">
                //                 <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                //             </a>
                //             <a style="text-align:center; color:white;" class="btn btn-danger btn-sm">
                //             <i class="fa fa-trash"></i>
                //             </a></div>';
    }
    public function show_by_id(Request $request)
    {

        $enroll_id = $request->enroll_id;

        $query = "
            WITH RECURSIVE periode AS (
                SELECT
                    ea.enroll_id,
                    ea.employee_name,
                    ea.department_name,
                    ea.status_aktif,
                    ea.tanggal_resign,
                    ea.sub_dept_name,
                    ea.join_date,
                    ea.join_date AS start_date,
                    LEAST(DATE_ADD(ea.join_date, INTERVAL 1 YEAR), CURDATE()) AS end_date
                FROM (
                    SELECT *
                    FROM employee_atribut
                    WHERE join_date IS NOT NULL
                    AND enroll_id = $enroll_id
                    ORDER BY enroll_id
                    LIMIT 10
                ) ea
                UNION ALL
                SELECT
                    p.enroll_id,
                    p.employee_name,
                    p.department_name,
                    p.status_aktif,
                    p.tanggal_resign,
                    p.sub_dept_name,
                    p.join_date,
                    p.end_date AS start_date,
                    LEAST(DATE_ADD(p.end_date, INTERVAL 1 YEAR), CURDATE()) AS end_date
                FROM periode p
                WHERE p.end_date < CURDATE()
            ),
            cuti_dipakai AS (
                SELECT
                    p.enroll_id,
                    p.start_date,
                    p.end_date,
                    COUNT(d.uuid) AS used_leave
                FROM periode p
                LEFT JOIN data_absen_perijinan d
                ON d.enroll_id = p.enroll_id
                AND d.kode_absen_ijin = 'CT'
                AND d.tanggal_mulai_ijin >= p.start_date
                AND d.tanggal_mulai_ijin < p.end_date
                GROUP BY p.enroll_id, p.start_date, p.end_date
            ),
            data_cuti AS (
                SELECT
                    p.enroll_id,
                    p.employee_name,
                    p.department_name,
                    p.status_aktif,
                    p.tanggal_resign,
                    p.sub_dept_name,
                    p.join_date,
                    p.start_date,
                    p.end_date,
                    CONCAT(
                        TIMESTAMPDIFF(YEAR, p.join_date, p.end_date), ' tahun ',
                        TIMESTAMPDIFF(MONTH, p.join_date, p.end_date) % 12, ' bulan'
                    ) AS lama_bekerja,
                    CASE
                        WHEN p.start_date < DATE_ADD(p.join_date, INTERVAL 1 YEAR) THEN 0
                        ELSE 1
                    END AS is_eligible,
                    COALESCE(c.used_leave, 0) AS used_leave,
                    CASE
                        WHEN p.start_date < DATE_ADD(p.join_date, INTERVAL 1 YEAR) THEN 0
                        ELSE 12 - COALESCE(c.used_leave, 0)
                    END AS remaining_leave,
                    CASE
                        WHEN p.start_date < DATE_ADD(p.join_date, INTERVAL 1 YEAR) THEN 'Belum Berhak'
                        WHEN (12 - COALESCE(c.used_leave, 0)) > 0 THEN 'Masih Memiliki Cuti'
                        ELSE 'Cuti Habis'
                    END AS leave_status,
                    ROW_NUMBER() OVER (PARTITION BY p.enroll_id ORDER BY p.end_date DESC) AS rn
                FROM periode p
                LEFT JOIN cuti_dipakai c
                    ON p.enroll_id = c.enroll_id AND p.start_date = c.start_date
            )
            SELECT *
            FROM data_cuti
            WHERE rn = 1
            ORDER BY enroll_id
        ";

        $data_cuti = DB::select($query);
        $data_cuti = collect($data_cuti)->map(function ($cuti) {
            $perijinan = DB::table('data_absen_perijinan')
                ->where('enroll_id', $cuti->enroll_id)
                ->where('kode_absen_ijin', 'CT')
                ->where('tanggal_mulai_ijin', '>=', $cuti->start_date)
                ->orderBy('tanggal_mulai_ijin', 'asc')
                ->get();

            $cuti->perijinan = $perijinan;
            return $cuti;
        });
            return datatables()->of($data_cuti[0]->perijinan)
            ->addColumn('tanggal_perizinan', function ($row) {
                return Carbon::parse($row->tanggal_perizinan)->format('d-m-Y'); // Format: DDMMYYYY
            })
            ->addColumn('nomor_form_perizinan', function ($row) {
                return $row->nomor_form_perizinan;
            })
            ->addColumn('tanggal_mulai_ijin', function ($row) {
                return Carbon::parse($row->tanggal_mulai_ijin)->format('d-m-Y'); // Format: DDMMYYYY
            })
            ->addColumn('tanggal_akhir_ijin', function ($row) {
                return Carbon::parse($row->tanggal_akhir_ijin)->format('d-m-Y'); // Format: DDMMYYYY
            })
            ->make(true);
    }

    public function showing_list_year_period(Request $request)
    {
        ini_set("max_execution_time", 5210);
        ini_set('memory_limit', '5120000M');

        $enrollId = $request->enroll_id;
        $kodeAbsen = 'CT';

        // Ambil join_date
        $employee = DB::table('employee_atribut')
            ->where('enroll_id', $enrollId)
            ->first();

        if (!$employee) {
            return [];
        }

        $joinDate = Carbon::parse($employee->join_date);
        $today = Carbon::now();

        $results = [];
        $currentStart = $joinDate->copy();
        $periodeKe = 1;

        while ($currentStart->lessThanOrEqualTo($today)) {
            $currentEnd = $currentStart->copy()->addYear();

            // Ambil data per periode
            $data = DB::table('data_absen_perijinan as dap')
                ->leftJoin('employee_atribut as ea', 'dap.enroll_id', '=', 'ea.enroll_id')
                ->select(
                    DB::raw('YEAR(dap.tanggal_mulai_ijin) as tahun'),
                    'dap.enroll_id',
                    'dap.kode_absen_ijin',
                    'dap.tanggal_perizinan',
                    'dap.nomor_form_perizinan',
                    'dap.tanggal_mulai_ijin',
                    'dap.tanggal_akhir_ijin',
                    'dap.absen_alasan',
                    'ea.join_date',
                    DB::raw("'{$periodeKe}' as periode_ke"),
                    DB::raw("'{$currentStart->toDateString()}' as periode_mulai"),
                    DB::raw("'{$currentEnd->toDateString()}' as periode_selesai")
                )
                ->where('dap.enroll_id', $enrollId)
                ->where('dap.kode_absen_ijin', $kodeAbsen)
                ->whereBetween('dap.tanggal_mulai_ijin', [$currentStart->toDateString(), $currentEnd->toDateString()])
                ->get();

            $formattedData = $data->map(function ($item) use ($currentStart, $currentEnd, $periodeKe, $employee) {
                return [
                    'tahun' => $item->tahun,
                    'enroll_id' => $item->enroll_id,
                    'kode_absen_ijin' => $item->kode_absen_ijin,
                    'tanggal_perizinan' => Carbon::parse($item->tanggal_perizinan)->format('d-m-Y'),
                    'nomor_form_perizinan' => $item->nomor_form_perizinan,
                    'tanggal_mulai_ijin' => Carbon::parse($item->tanggal_mulai_ijin)->format('d-m-Y'),
                    'tanggal_akhir_ijin' => Carbon::parse($item->tanggal_akhir_ijin)->format('d-m-Y'),
                    'absen_alasan' => $item->absen_alasan,
                    'join_date' => Carbon::parse($item->join_date)->format('d-m-Y'),
                    'periode_ke' => $item->periode_ke,
                    'periode_mulai' => Carbon::parse($item->periode_mulai)->format('d-m-Y'),
                    'periode_selesai' => Carbon::parse($item->periode_selesai)->format('d-m-Y'),
                ];
            });

            // Jika kurang dari 12, tambahkan cuti hangus
            $jumlahSaatIni = $formattedData->count();
            if ($jumlahSaatIni < 12) {
                for ($i = $jumlahSaatIni; $i < 12; $i++) {
                    $formattedData->push([
                        'tahun' => null,
                        'enroll_id' => $enrollId,
                        'kode_absen_ijin' => '-',
                        'tanggal_perizinan' => null,
                        'nomor_form_perizinan' => '-',
                        'tanggal_mulai_ijin' => '-',
                        'tanggal_akhir_ijin' => '-',
                        'absen_alasan' => 'CUTI HANGUS',
                        'join_date' => Carbon::parse($employee->join_date)->format('d-m-Y'),
                        'periode_ke' => $periodeKe,
                        'periode_mulai' => $currentStart->format('d-m-Y'),
                        'periode_selesai' => $currentEnd->format('d-m-Y'),
                    ]);
                }
            }

            // if ($data->count()) {
            //     $results[] = [
            //         'periode' => $currentStart->format('d-m-Y') . ' - ' . $currentEnd->format('d-m-Y'),
            //         'data' => $formattedData,
            //     ];
            // }
            $results[] = [
                'periode' => $currentStart->format('d-m-Y') . ' - ' . $currentEnd->format('d-m-Y'),
                'data' => $formattedData,
            ];


            $currentStart = $currentEnd;
            $periodeKe++;
        }

        // Format ulang tanggal pada results
        foreach ($results as &$periodeItem) {
            foreach ($periodeItem['data'] as &$item) {
                // Cek dan pastikan hanya tanggal yang valid yang diparsing
                $item['tanggal_perizinan'] = ($item['tanggal_perizinan'] && $item['tanggal_perizinan'] !== '-')
                                            ? Carbon::parse($item['tanggal_perizinan'])->format('d-m-Y')
                                            : '-';
                $item['tanggal_mulai_ijin'] = ($item['tanggal_mulai_ijin'] && $item['tanggal_mulai_ijin'] !== '-')
                                              ? Carbon::parse($item['tanggal_mulai_ijin'])->format('d-m-Y')
                                              : '-';
                $item['tanggal_akhir_ijin'] = ($item['tanggal_akhir_ijin'] && $item['tanggal_akhir_ijin'] !== '-')
                                              ? Carbon::parse($item['tanggal_akhir_ijin'])->format('d-m-Y')
                                              : '-';
                $item['join_date'] = ($item['join_date'] && $item['join_date'] !== '-')
                                     ? Carbon::parse($item['join_date'])->format('d-m-Y')
                                     : '-';
                $item['periode_mulai'] = Carbon::parse($item['periode_mulai'])->format('d-m-Y');
                $item['periode_selesai'] = Carbon::parse($item['periode_selesai'])->format('d-m-Y');
            }
        }

        return response()->json($results);
    }
    public function update(Request $request, $id)
    {
        $pengajuan = EntertainPengajuanTamu::findOrFail($id);
        $pengajuan->update($request->only([
            'enroll_id', 'department', 'bagian', 'tanggal_kedatangan_tamu',
            'tamu_instansi', 'nama_tamu', 'jabatan_tamu', 'qty_tamu', 'keperluan'
        ]));

        EntertainPengajuanPendamping::where('pengajuan_id', $id)->delete();
        if ($request->has('pendamping_tamu')) {
            foreach ($request->pendamping_tamu as $pendamping) {
                EntertainPengajuanPendamping::create([
                    'pengajuan_id' => $pengajuan->id,
                    'enroll_id' => $pendamping,
                ]);
            }
        }

        EntertainPengajuanKeterangan::where('pengajuan_id', $id)->delete();
        if ($request->has('keterangan_list')) {
            foreach ($request->keterangan_list as $item) {
                EntertainPengajuanKeterangan::create([
                    'pengajuan_id' => $pengajuan->id,
                    'keterangan' => $item['keterangan'],
                    'jumlah' => preg_replace('/[^0-9]/', '', $item['jumlah']),
                ]);
            }
        }

        return response()->json(['message' => 'Pengajuan berhasil diperbarui']);
    }

    public function ajax_getallemployeeatribut()
    {
        $query =  EmployeeAtribut::selectRaw('enroll_id, nik, employee_name, department_name,department_id,sub_dept_name, sub_dept_id, status_aktif,
                                           concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                                    ->groupby('enroll_id')
                                    ->orderby('employee_name', 'asc')
                                    ->get();
        return $query;
    }

    public function export_form_pengajuan_cuti_pdf(Request $request) {
        $nomorForm = $request->input('nomor_form_perizinan');

        $data = DataAbsenPerijinan::select('employee_atribut.employee_name', 'employee_atribut.department_name','employee_atribut.sub_dept_name', 'employee_atribut.nik', 'data_absen_perijinan.*')
            ->leftJoin('employee_atribut', 'data_absen_perijinan.enroll_id', '=', 'employee_atribut.enroll_id')
            ->where('data_absen_perijinan.nomor_form_perizinan', $nomorForm)
            ->first();

        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $fileName = 'Form Pengajuan Cuti ' . date('Y-m-d') . ' ' . rand(10, 1000000);
        $pdf = PDF::loadView('hris.absen.cuti_karyawan.export-form-pengajuan-cuti-pdf', [
            'data' => $data
        ])->setPaper('F4', 'portrait');

        return $pdf->stream($fileName . '.pdf', ['Attachment' => false]);
    }

    public function export_form_pengajuan_izin_pdf(Request $request) {
        $nomorForm = $request->input('nomor_form_perizinan');

        $data = DataAbsenPerijinan::select('employee_atribut.employee_name', 'employee_atribut.department_name','employee_atribut.sub_dept_name', 'employee_atribut.nik', 'data_absen_perijinan.*')
            ->leftJoin('employee_atribut', 'data_absen_perijinan.enroll_id', '=', 'employee_atribut.enroll_id')
            ->where('data_absen_perijinan.nomor_form_perizinan', $nomorForm)
            ->first();

        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $fileName = 'Form Pengajuan Cuti ' . date('Y-m-d') . ' ' . rand(10, 1000000);
        $pdf = PDF::loadView('hris.absen.cuti_karyawan.export-form-pengajuan-izin-pdf', [
            'data' => $data
        ])->setPaper('F4', 'portrait');

        return $pdf->stream($fileName . '.pdf', ['Attachment' => false]);
    }
    public function get_data_perizinan(Request $request) {
        $uuid = $request->input('uuid');

        $data = DataAbsenPerijinan::select('employee_atribut.employee_name', 'employee_atribut.department_name','employee_atribut.sub_dept_name', 'employee_atribut.nik', 'data_absen_perijinan.*')
            ->leftJoin('employee_atribut', 'data_absen_perijinan.enroll_id', '=', 'employee_atribut.enroll_id')
            ->where('data_absen_perijinan.uuid', $uuid)
            ->first();

        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return $data;
    }

    public function export_pengajuan_permintaan_kas(Request $request)
        {
            $pengajuan = EntertainPengajuanTamu::with(['pendamping', 'keterangan'])->where('id', $request->entertain_id)->first();
            $department = DepartmentAll::where('department_id', $pengajuan->department_id)->where('sub_dept_id', $pengajuan->sub_dept_id)->first();
            $employee =  EmployeeAtribut::where('enroll_id', $pengajuan->enroll_id)->first();
            $employee_manager =  EmployeeAtribut::where('department_id', $pengajuan->department_id)->where('sub_dept_id', $pengajuan->sub_dept_id)->where('status_jabatan', 'MANAGER')->first();
            $total_jumlah = $pengajuan->keterangan->sum('jumlah');
            return view('hris/ga/entertaint/export_pengajuan_permintaan_kas_pdf', compact('pengajuan', 'department', 'employee', 'total_jumlah','employee','employee_manager'));
        }
    public function export_realisasi_permintaan_kas(Request $request)
        {
            $pengajuan = EntertainPengajuanTamu::with(['pendamping', 'keterangan'])->where('id', $request->entertain_id)->first();
            $department = DepartmentAll::where('department_id', $pengajuan->department_id)->where('sub_dept_id', $pengajuan->sub_dept_id)->first();
            $employee =  EmployeeAtribut::where('enroll_id', $pengajuan->enroll_id)->first();
            $employee_manager =  EmployeeAtribut::where('department_id', $pengajuan->department_id)->where('sub_dept_id', $pengajuan->sub_dept_id)->where('status_jabatan', 'MANAGER')->first();
            $total_jumlah = $pengajuan->keterangan->sum('jumlah');
            return view('hris/ga/entertaint/export_realisasi_permintaan_kas', compact('pengajuan', 'department', 'employee', 'total_jumlah','employee','employee_manager'));
        }

    public function show_export(Request $request)
    {
        ini_set("max_execution_time", 5210);
        ini_set('memory_limit', '5120000M');
        $enroll_ids = $request->input('selectEmployeeID', []);

        $has_filter = count($enroll_ids) > 0;

        $bindings = [];
        $filterClause = '';

        if ($has_filter) {
            $placeholders = implode(',', array_fill(0, count($enroll_ids), '?'));
            $filterClause = "AND enroll_id IN ($placeholders)";
            $bindings = $enroll_ids;
        }

        $query = "
            WITH RECURSIVE periode AS (
                SELECT
                    ea.enroll_id,
                    ea.employee_name,
                    ea.nik,
                    ea.status_staff,
                    ea.status_jabatan,
                    ea.department_name,
                    ea.status_aktif,
                    ea.tanggal_resign,
                    ea.sub_dept_name,
                    ea.join_date,
                    ea.join_date AS start_date,
                    LEAST(DATE_ADD(ea.join_date, INTERVAL 1 YEAR), CURDATE()) AS end_date
                FROM (
                    SELECT *
                    FROM employee_atribut
                    WHERE join_date IS NOT NULL
                    $filterClause
                    ORDER BY enroll_id
                ) ea

                UNION ALL

                SELECT
                    p.enroll_id,
                    p.employee_name,
                    p.nik,
                    p.status_staff,
                    p.status_jabatan,
                    p.department_name,
                    p.status_aktif,
                    p.tanggal_resign,
                    p.sub_dept_name,
                    p.join_date,
                    p.end_date AS start_date,
                    LEAST(DATE_ADD(p.end_date, INTERVAL 1 YEAR), CURDATE()) AS end_date
                FROM periode p
                WHERE p.end_date < CURDATE()
            ),

            cuti_dipakai AS (
                SELECT
                    p.enroll_id,
                    p.start_date,
                    p.end_date,
                    COUNT(d.uuid) AS used_leave
                FROM periode p
                LEFT JOIN data_absen_perijinan d
                ON d.enroll_id = p.enroll_id
                AND d.kode_absen_ijin = 'CT'
                AND d.tanggal_mulai_ijin >= p.start_date
                AND d.tanggal_mulai_ijin < p.end_date
                GROUP BY p.enroll_id, p.start_date, p.end_date
            ),

            data_cuti AS (
                SELECT
                    p.enroll_id,
                    p.employee_name,
                    p.nik,
                    p.status_staff,
                    p.status_jabatan,
                    p.department_name,
                    p.status_aktif,
                    p.tanggal_resign,
                    p.sub_dept_name,
                    p.join_date,
                    p.start_date,
                    p.end_date,
                    YEAR(p.start_date) AS periode_tahun,
                    CONCAT(DATE_FORMAT(p.start_date, '%d-%m-%Y'), ' - ', DATE_FORMAT(p.end_date, '%d-%m-%Y')) AS periode,
                    CONCAT(
                        TIMESTAMPDIFF(YEAR, p.join_date, p.end_date), ' tahun ',
                        TIMESTAMPDIFF(MONTH, p.join_date, p.end_date) % 12, ' bulan'
                    ) AS lama_bekerja,
                    CASE
                        WHEN p.start_date < DATE_ADD(p.join_date, INTERVAL 1 YEAR) THEN 0
                        ELSE 1
                    END AS is_eligible,
                    COALESCE(c.used_leave, 0) AS used_leave,
                    CASE
                        WHEN p.start_date < DATE_ADD(p.join_date, INTERVAL 1 YEAR) THEN 0
                        ELSE 12 - COALESCE(c.used_leave, 0)
                    END AS remaining_leave,
                    CASE
                        WHEN p.start_date < DATE_ADD(p.join_date, INTERVAL 1 YEAR) THEN 'Belum Berhak'
                        WHEN (12 - COALESCE(c.used_leave, 0)) > 0 THEN 'Masih Memiliki Cuti'
                        ELSE 'Cuti Habis'
                    END AS leave_status
                FROM periode p
                LEFT JOIN cuti_dipakai c
                    ON p.enroll_id = c.enroll_id AND p.start_date = c.start_date
            )

         SELECT *
        FROM data_cuti
        ORDER BY enroll_id, start_date

        ";

        $data_cuti = collect(DB::select($query, $bindings));
        $grouped = $data_cuti->groupBy('enroll_id')->map(function ($items) {
            $first = $items->first();

            $lastPeriod = $items->last();
            $lastPeriodUtama = $items->sortByDesc('end_date')->first();


            $perijinanLast = DB::table('data_absen_perijinan')
                ->where('enroll_id', $lastPeriod->enroll_id)
                ->where('kode_absen_ijin', 'CT')
                ->where('tanggal_mulai_ijin', '>=', $lastPeriod->start_date)
                ->where('tanggal_mulai_ijin', '<', $lastPeriod->end_date)
                ->orderBy('tanggal_mulai_ijin','asc')
                ->get(['tanggal_mulai_ijin', 'tanggal_akhir_ijin']);



            return (object) [
                'enroll_id'        => $first->enroll_id,
                'employee_name'    => $first->employee_name,
                'nik'              => $first->nik,
                'status_staff'     => $first->status_staff,
                'status_jabatan'   => $first->status_jabatan,
                'department_name'  => $first->department_name,
                'status_aktif'     => $first->status_aktif,
                'tanggal_resign'   => $first->tanggal_resign,
                'sub_dept_name'    => $first->sub_dept_name,
                'join_date'        => $first->join_date,
                'lama_bekerja'     => $lastPeriodUtama->lama_bekerja,
                'used_leave'       => $lastPeriodUtama->used_leave,
                'remaining_leave'  => $lastPeriodUtama->remaining_leave,
                'leave_status'     => $lastPeriodUtama->leave_status,
                'is_eligible'     => $lastPeriodUtama->is_eligible,
                'perijinan_last'   => $perijinanLast,
                'data_list_perijinan'        => $items->map(function ($item) {
                    return [
                        'periode'         => $item->periode,
                        'periode_tahun'   => $item->periode_tahun,
                        'cuti_terpakai'   => $item->used_leave,
                        'sisa_cuti'       => $item->remaining_leave,
                        'status_cuti'     => $item->leave_status,
                        'lama_bekerja'    => $item->lama_bekerja,
                        'data'            => DB::table('data_absen_perijinan')
                                            ->where('enroll_id', $item->enroll_id)
                                            ->where('kode_absen_ijin', 'CT')
                                            ->where('tanggal_mulai_ijin', '>=', $item->start_date)
                                            ->where('tanggal_mulai_ijin', '<', $item->end_date)
                                            ->orderBy('tanggal_mulai_ijin', 'asc')
                                            ->get(['tanggal_mulai_ijin', 'tanggal_akhir_ijin']),
                    ];
                })->values(),
            ];
        })->values();

        $excel = FastExcel::create('cuti karyawan');
        $sheet = $excel->getSheet();

        $area = $sheet->beginArea();

        $sheet->writeTo('A1', 'PT NIRWANA ALABARE GARMENT', ['font-size' => 18]);
        $sheet->writeTo('A2', 'REKAP CUTI KARYAWAN', ['font-size' => 16]);

        $sheet->writeTo('A5', 'NIK');

        $sheet->writeTo('B5', 'NO ABSEN');

        $sheet->writeTo('C5', 'NAMA KARYAWAN');

        $sheet->writeTo('D5', 'STAFF / NON STAFF');

        $sheet->writeTo('E5', 'JABATAN');

        $sheet->writeTo('F5', 'BAGIAN');

        $sheet->writeTo('G5', 'DEPARTMENT');

        $sheet->writeTo('H5', 'AKTIF / TIDAK AKTIF');

        $sheet->writeTo('I5', 'TANGGAL MASUK');
        $sheet->writeTo('J5', 'TANGGAL RESIGN');

        $sheet->writeTo('K5', 'MASA KERJA');

        $sheet->writeTo('L5', 'HAK CUTI');

        $sheet->writeTo('M5', 'CUTI TERPAKAI');
        $sheet->writeTo('N5', 'CUTI SISA');

        $sheet->writeTo('O5', 'REKAP PENGAMBILAN CUTI');

        $sheet->writeTo('O6', '1');
        $sheet->writeTo('P6', '2');
        $sheet->writeTo('Q6', '3');
        $sheet->writeTo('R6', '4');
        $sheet->writeTo('S6', '5');
        $sheet->writeTo('T6', '6');
        $sheet->writeTo('U6', '7');
        $sheet->writeTo('V6', '8');
        $sheet->writeTo('W6', '9');
        $sheet->writeTo('X6', '10');
        $sheet->writeTo('Y6', '11');
        $sheet->writeTo('Z6', '12');

        $sheet->writeTo('AA5', 'CUTI TIDAK DIPAKAI');

        $list = ['2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023', '2024', '2025']; // Contoh data periode
        $periodeCount = count($list); // Menghitung jumlah periode

        $startColumnIndex = 26; // Kolom AA, indeks 26
        $endColumnIndex = $startColumnIndex + $periodeCount - 1; // Kolom terakhir yang sesuai dengan jumlah periode

        // Loop untuk menulis ke baris 6 dan menggabungkan di baris 5
        for ($i = 0; $i < $periodeCount; $i++) {
            $col = $this->getExcelColumn($startColumnIndex + $i); // Menentukan kolom berdasarkan indeks
            $sheet->writeTo("{$col}6", $list[$i]); // Menulis periode ke baris 6
        }

        // Menggabungkan sel pada baris 5 dari kolom AA sampai kolom sesuai panjang $list
        $startCol = $this->getExcelColumn($startColumnIndex); // Kolom pertama (AA)
        $endCol = $this->getExcelColumn($endColumnIndex); // Kolom terakhir sesuai jumlah periode

        $sheet->mergeCells("{$startCol}5:{$endCol}5"); // Menggabungkan kolom AA5 sampai dengan kolom terakhir di baris 5



        $sheet->mergeCells('A5:A6');
        $sheet->mergeCells('B5:B6');
        $sheet->mergeCells('C5:C6');
        $sheet->mergeCells('D5:D6');
        $sheet->mergeCells('E5:E6');
        $sheet->mergeCells('F5:F6');
        $sheet->mergeCells('G5:G6');
        $sheet->mergeCells('H5:H6');
        $sheet->mergeCells('I5:I6');
        $sheet->mergeCells('J5:J6');
        $sheet->mergeCells('K5:K6');
        $sheet->mergeCells('L5:L6');
        $sheet->mergeCells('M5:M6');
        $sheet->mergeCells('N5:N6');

        $sheet->mergeCells('O5:Z5');

        $sheet->writeAreas();

        $sheet->setColOptions([

            'A' => ['width' => 15], // NIK
            'B' => ['width' => 15], // NAMA KARYAWAN
            'C' => ['width' => 18], // STAFF / NON STAFF
            'D' => ['width' => 20], // JABATAN
            'E' => ['width' => 25], // BAGIAN
            'F' => ['width' => 25], // DEPARTMENT
            'G' => ['width' => 25], // TANGGAL MASUK
            'H' => ['width' => 20], // MASA KERJA
            'I' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 20], // HAK CUTI
            'J' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 20], // CUTI TERPAKAI
            'K' => ['width' => 15], // CUTI TERPAKAI
            'L' => ['width' => 10], // CUTI SISA

            'M' => ['width' => 15], // MASA KERJA
            'N' => ['width' => 15], // MASA KERJA
            'O' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
            'P' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
            'Q' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
            'R' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
            'S' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
            'T' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
            'U' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
            'V' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
            'W' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
            'X' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
            'Y' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
            'Z' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
        ]);

        foreach($grouped as $cuti) {
            $row  = [
                $cuti->nik,
                $cuti->enroll_id,
                $cuti->employee_name,
                $cuti->status_staff,
                $cuti->status_jabatan,
                $cuti->sub_dept_name,
                $cuti->department_name,
                $cuti->status_aktif,
                $cuti->join_date,
                $cuti->tanggal_resign,
                $cuti->lama_bekerja,
                $cuti->is_eligible == 1 ? 12 : 0,
                $cuti->used_leave,
                $cuti->remaining_leave,
            ];

            $izinDates = collect($cuti->perijinan_last)
            ->pluck('tanggal_mulai_ijin')
            ->take(12)
            ->values()
            ->toArray();

            for ($i = 0; $i < 12; $i++) {
                $row[] = isset($izinDates[$i]) ? Carbon::parse($izinDates[$i])->format('Y-m-d') : '';
            }


            foreach ($list as $year) {
                $matchingPeriod = collect($cuti->data_list_perijinan)
                    ->firstWhere('periode_tahun', $year);

                $joinDate = Carbon::parse($cuti->join_date);
                $startOfYear = Carbon::parse($year . '-01-01');

                if ($joinDate > $startOfYear) {
                    $sisaCuti = 0;
                } else {
                    if ($matchingPeriod) {
                        $jumlahCutiTerpakai = count($matchingPeriod['data']);

                        $sisaCuti = 12 - $jumlahCutiTerpakai;
                    } else {
                        $sisaCuti = 12;
                    }
                }

                $row[] = $sisaCuti;
            }

            $rows[] = $row;

            $sheet->writeRow($row);
        }
        $finename='Rekap Cuti Karyawan'.'xlsx';
        ob_end_clean();
        $excel->download($finename);
    }

    public function getExcelColumn($index) {
        $letters = '';
        while ($index >= 0) {
            $letters = chr($index % 26 + 65) . $letters;
            $index = floor($index / 26) - 1;
        }
        return $letters;
    }


    public function show_export_by_join_date(Request $request)
    {
        ini_set("max_execution_time", 5210);
        ini_set('memory_limit', '5120000M');

        $type = $request->input('type');
        $tanggal = $request->input('join_date');

        $periodeLabel = '';


        if ($type === 'MONTHLY') {
            $bulan = $tanggal;

            if (!preg_match('/^(0?[1-9]|1[0-2])$/', $bulan)) {
                return response()->json(['message' => 'Format bulan tidak valid'], 422);
            }

            $filterJoinDate = "MONTH(join_date) = '$bulan'";
            $bulanNama = strtoupper(strftime("%B", mktime(0, 0, 0, (int)$bulan, 1)));
            $periodeLabel = $bulanNama;
        }
         else {
            // CUSTOM_RANGE
            $tanggal_all = explode(' - ', $tanggal);
            $tanggal_awal = Carbon::parse($tanggal_all[0])->format('Y-m-d');
            $tanggal_akhir = Carbon::parse($tanggal_all[1])->format('Y-m-d');
            $awal = Carbon::parse($tanggal_awal)->format('Y-m-d');
            $akhir = Carbon::parse($tanggal_akhir)->format('Y-m-d');

            // Buat filter untuk join_date antara dua tanggal
            $filterJoinDate = "join_date BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";

            $periodeLabel = strtoupper(strftime("%d %b %Y", strtotime($awal)) . ' s/d ' . strftime("%d %b %Y", strtotime($akhir)));
        }

        $query = "
        WITH RECURSIVE periode AS (
            SELECT
                ea.enroll_id,
                ea.employee_name,
                ea.nik,
                ea.status_staff,
                ea.status_jabatan,
                ea.department_name,
                ea.status_aktif,
                ea.tanggal_resign,
                ea.sub_dept_name,
                ea.join_date,
                ea.join_date AS start_date,
                LEAST(DATE_ADD(ea.join_date, INTERVAL 1 YEAR), CURDATE()) AS end_date
            FROM (
                SELECT *
                FROM employee_atribut
                WHERE join_date IS NOT NULL
                AND $filterJoinDate
                ORDER BY enroll_id
            ) ea

            UNION ALL

            SELECT
                p.enroll_id,
                p.employee_name,
                p.nik,
                p.status_staff,
                p.status_jabatan,
                p.department_name,
                p.status_aktif,
                p.tanggal_resign,
                p.sub_dept_name,
                p.join_date,
                p.end_date AS start_date,
                LEAST(DATE_ADD(p.end_date, INTERVAL 1 YEAR), CURDATE()) AS end_date
            FROM periode p
            WHERE p.end_date < CURDATE()
        ),

        cuti_dipakai AS (
            SELECT
                p.enroll_id,
                p.start_date,
                p.end_date,
                COUNT(d.uuid) AS used_leave
            FROM periode p
            LEFT JOIN data_absen_perijinan d
            ON d.enroll_id = p.enroll_id
            AND d.kode_absen_ijin = 'CT'
            AND d.tanggal_mulai_ijin >= p.start_date
            AND d.tanggal_mulai_ijin < p.end_date
            GROUP BY p.enroll_id, p.start_date, p.end_date
        ),

        data_cuti AS (
            SELECT
                p.enroll_id,
                p.employee_name,
                p.nik,
                p.status_staff,
                p.status_jabatan,
                p.department_name,
                p.status_aktif,
                p.tanggal_resign,
                p.sub_dept_name,
                p.join_date,
                p.start_date,
                p.end_date,
                YEAR(p.start_date) AS periode_tahun,
                CONCAT(DATE_FORMAT(p.start_date, '%d-%m-%Y'), ' - ', DATE_FORMAT(p.end_date, '%d-%m-%Y')) AS periode,
                CONCAT(
                    TIMESTAMPDIFF(YEAR, p.join_date, p.end_date), ' tahun ',
                    TIMESTAMPDIFF(MONTH, p.join_date, p.end_date) % 12, ' bulan'
                ) AS lama_bekerja,
                CASE
                    WHEN p.start_date < DATE_ADD(p.join_date, INTERVAL 1 YEAR) THEN 0
                    ELSE 1
                END AS is_eligible,
                COALESCE(c.used_leave, 0) AS used_leave,
                CASE
                    WHEN p.start_date < DATE_ADD(p.join_date, INTERVAL 1 YEAR) THEN 0
                    ELSE 12 - COALESCE(c.used_leave, 0)
                END AS remaining_leave,
                CASE
                    WHEN p.start_date < DATE_ADD(p.join_date, INTERVAL 1 YEAR) THEN 'Belum Berhak'
                    WHEN (12 - COALESCE(c.used_leave, 0)) > 0 THEN 'Masih Memiliki Cuti'
                    ELSE 'Cuti Habis'
                END AS leave_status
            FROM periode p
            LEFT JOIN cuti_dipakai c
                ON p.enroll_id = c.enroll_id AND p.start_date = c.start_date
                )

            SELECT *
            FROM data_cuti
            ORDER BY enroll_id, start_date

            ";

            $data_cuti = collect(DB::select($query));
            $grouped = $data_cuti->groupBy('enroll_id')->map(function ($items) {
                $first = $items->first();

                $lastPeriod = $items->last();
                $lastPeriodUtama = $items->sortByDesc('end_date')->first();


                $perijinanLast = DB::table('data_absen_perijinan')
                    ->where('enroll_id', $lastPeriod->enroll_id)
                    ->where('kode_absen_ijin', 'CT')
                    ->where('tanggal_mulai_ijin', '>=', $lastPeriod->start_date)
                    ->where('tanggal_mulai_ijin', '<', $lastPeriod->end_date)
                    ->orderBy('tanggal_mulai_ijin','asc')
                    ->get(['tanggal_mulai_ijin', 'tanggal_akhir_ijin']);



                return (object) [
                    'enroll_id'        => $first->enroll_id,
                    'employee_name'    => $first->employee_name,
                    'nik'              => $first->nik,
                    'status_staff'     => $first->status_staff,
                    'status_jabatan'   => $first->status_jabatan,
                    'department_name'  => $first->department_name,
                    'status_aktif'     => $first->status_aktif,
                    'tanggal_resign'   => $first->tanggal_resign,
                    'sub_dept_name'    => $first->sub_dept_name,
                    'join_date'        => $first->join_date,
                    'lama_bekerja'     => $lastPeriodUtama->lama_bekerja,
                    'used_leave'       => $lastPeriodUtama->used_leave,
                    'remaining_leave'  => $lastPeriodUtama->remaining_leave,
                    'leave_status'     => $lastPeriodUtama->leave_status,
                    'is_eligible'     => $lastPeriodUtama->is_eligible,
                    'perijinan_last'   => $perijinanLast,
                    'data_list_perijinan'        => $items->map(function ($item) {
                        return [
                            'periode'         => $item->periode,
                            'periode_tahun'   => $item->periode_tahun,
                            'cuti_terpakai'   => $item->used_leave,
                            'sisa_cuti'       => $item->remaining_leave,
                            'status_cuti'     => $item->leave_status,
                            'lama_bekerja'    => $item->lama_bekerja,
                            'data'            => DB::table('data_absen_perijinan')
                                                ->where('enroll_id', $item->enroll_id)
                                                ->where('kode_absen_ijin', 'CT')
                                                ->where('tanggal_mulai_ijin', '>=', $item->start_date)
                                                ->where('tanggal_mulai_ijin', '<', $item->end_date)
                                                ->orderBy('tanggal_mulai_ijin', 'asc')
                                                ->get(['tanggal_mulai_ijin', 'tanggal_akhir_ijin']),
                        ];
                    })->values(),
                ];
            })->values();

            $excel = FastExcel::create('cuti karyawan');
            $sheet = $excel->getSheet();

            $area = $sheet->beginArea();

            $sheet->writeTo('A1', 'PT NIRWANA ALABARE GARMENT', ['font-size' => 18]);
            $sheet->writeTo('A2', 'REKAP CUTI KARYAWAN', ['font-size' => 16]);
                $sheet->writeTo('A3', 'PERIODE : ' . $periodeLabel, ['font-size' => 14]);

            $sheet->writeTo('A5', 'NIK');

            $sheet->writeTo('B5', 'NO ABSEN');

            $sheet->writeTo('C5', 'NAMA KARYAWAN');

            $sheet->writeTo('D5', 'STAFF / NON STAFF');

            $sheet->writeTo('E5', 'JABATAN');

            $sheet->writeTo('F5', 'BAGIAN');

            $sheet->writeTo('G5', 'DEPARTMENT');

            $sheet->writeTo('H5', 'AKTIF / TIDAK AKTIF');

            $sheet->writeTo('I5', 'TANGGAL MASUK');
            $sheet->writeTo('J5', 'TANGGAL RESIGN');

            $sheet->writeTo('K5', 'MASA KERJA');

            $sheet->writeTo('L5', 'HAK CUTI');

            $sheet->writeTo('M5', 'CUTI TERPAKAI');
            $sheet->writeTo('N5', 'CUTI SISA');

            $sheet->writeTo('O5', 'REKAP PENGAMBILAN CUTI');

            $sheet->writeTo('O6', '1');
            $sheet->writeTo('P6', '2');
            $sheet->writeTo('Q6', '3');
            $sheet->writeTo('R6', '4');
            $sheet->writeTo('S6', '5');
            $sheet->writeTo('T6', '6');
            $sheet->writeTo('U6', '7');
            $sheet->writeTo('V6', '8');
            $sheet->writeTo('W6', '9');
            $sheet->writeTo('X6', '10');
            $sheet->writeTo('Y6', '11');
            $sheet->writeTo('Z6', '12');

            $sheet->writeTo('AA5', 'CUTI TIDAK DIPAKAI');

            $list = ['2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023', '2024', '2025']; // Contoh data periode
            $periodeCount = count($list); // Menghitung jumlah periode

            $startColumnIndex = 26; // Kolom AA, indeks 26
            $endColumnIndex = $startColumnIndex + $periodeCount - 1; // Kolom terakhir yang sesuai dengan jumlah periode

            // Loop untuk menulis ke baris 6 dan menggabungkan di baris 5
            for ($i = 0; $i < $periodeCount; $i++) {
                $col = $this->getExcelColumn($startColumnIndex + $i); // Menentukan kolom berdasarkan indeks
                $sheet->writeTo("{$col}6", $list[$i]); // Menulis periode ke baris 6
            }

            // Menggabungkan sel pada baris 5 dari kolom AA sampai kolom sesuai panjang $list
            $startCol = $this->getExcelColumn($startColumnIndex); // Kolom pertama (AA)
            $endCol = $this->getExcelColumn($endColumnIndex); // Kolom terakhir sesuai jumlah periode

            $sheet->mergeCells("{$startCol}5:{$endCol}5"); // Menggabungkan kolom AA5 sampai dengan kolom terakhir di baris 5



            $sheet->mergeCells('A5:A6');
            $sheet->mergeCells('B5:B6');
            $sheet->mergeCells('C5:C6');
            $sheet->mergeCells('D5:D6');
            $sheet->mergeCells('E5:E6');
            $sheet->mergeCells('F5:F6');
            $sheet->mergeCells('G5:G6');
            $sheet->mergeCells('H5:H6');
            $sheet->mergeCells('I5:I6');
            $sheet->mergeCells('J5:J6');
            $sheet->mergeCells('K5:K6');
            $sheet->mergeCells('L5:L6');
            $sheet->mergeCells('M5:M6');
            $sheet->mergeCells('N5:N6');

            $sheet->mergeCells('O5:Z5');

            $sheet->writeAreas();

            $sheet->setColOptions([

                'A' => ['width' => 15], // NIK
                'B' => ['width' => 15], // NAMA KARYAWAN
                'C' => ['width' => 18], // STAFF / NON STAFF
                'D' => ['width' => 20], // JABATAN
                'E' => ['width' => 25], // BAGIAN
                'F' => ['width' => 25], // DEPARTMENT
                'G' => ['width' => 25], // TANGGAL MASUK
                'H' => ['width' => 20], // MASA KERJA
                'I' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 20], // HAK CUTI
                'J' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 20], // CUTI TERPAKAI
                'K' => ['width' => 15], // CUTI TERPAKAI
                'L' => ['width' => 10], // CUTI SISA

                'M' => ['width' => 15], // MASA KERJA
                'N' => ['width' => 15], // MASA KERJA
                'O' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
                'P' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
                'Q' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
                'R' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
                'S' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
                'T' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
                'U' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
                'V' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
                'W' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
                'X' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
                'Y' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
                'Z' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 15], // MASA KERJA
            ]);

            foreach($grouped as $cuti) {
                $row  = [
                    $cuti->nik,
                    $cuti->enroll_id,
                    $cuti->employee_name,
                    $cuti->status_staff,
                    $cuti->status_jabatan,
                    $cuti->sub_dept_name,
                    $cuti->department_name,
                    $cuti->status_aktif,
                    $cuti->join_date,
                    $cuti->tanggal_resign,
                    $cuti->lama_bekerja,
                    $cuti->is_eligible == 1 ? 12 : 0,
                    $cuti->used_leave,
                    $cuti->remaining_leave,
                ];

                $izinDates = collect($cuti->perijinan_last)
                ->pluck('tanggal_mulai_ijin')
                ->take(12)
                ->values()
                ->toArray();

                for ($i = 0; $i < 12; $i++) {
                    $row[] = isset($izinDates[$i]) ? Carbon::parse($izinDates[$i])->format('Y-m-d') : '';
                }


                foreach ($list as $year) {
                    $matchingPeriod = collect($cuti->data_list_perijinan)
                        ->firstWhere('periode_tahun', $year);

                    $joinDate = Carbon::parse($cuti->join_date);
                    $startOfYear = Carbon::parse($year . '-01-01');

                    if ($joinDate > $startOfYear) {
                        $sisaCuti = 0;
                    } else {
                        if ($matchingPeriod) {
                            $jumlahCutiTerpakai = count($matchingPeriod['data']);

                            $sisaCuti = 12 - $jumlahCutiTerpakai;
                        } else {
                            $sisaCuti = 12;
                        }
                    }

                    $row[] = $sisaCuti;
                }

                $rows[] = $row;

                $sheet->writeRow($row);
            }
            $finename='Rekap Cuti Karyawan By Join Date'.'xlsx';
            ob_end_clean();
            $excel->download($finename);
    }
    // public function show_export_by_join_date(Request $request)
    // {
    //     ini_set("max_execution_time", 5210);
    //     ini_set('memory_limit', '5120000M');

    //     $type = $request->input('type');
    //     $tanggal = $request->input('join_date');

    //     $periodeLabel = '';


    //     if ($type === 'MONTHLY') {
    //         $bulan = $tanggal;

    //         if (!preg_match('/^(0?[1-9]|1[0-2])$/', $bulan)) {
    //             return response()->json(['message' => 'Format bulan tidak valid'], 422);
    //         }

    //         $filterJoinDate = "MONTH(join_date) = '$bulan'";
    //         $bulanNama = strtoupper(strftime("%B", mktime(0, 0, 0, (int)$bulan, 1)));
    //         $periodeLabel = $bulanNama;
    //     }
    //      else {
    //         // CUSTOM_RANGE
    //         $tanggal_all = explode(' - ', $tanggal);
    //         $tanggal_awal = Carbon::parse($tanggal_all[0])->format('Y-m-d');
    //         $tanggal_akhir = Carbon::parse($tanggal_all[1])->format('Y-m-d');
    //         $awal = Carbon::parse($tanggal_awal)->format('Y-m-d');
    //         $akhir = Carbon::parse($tanggal_akhir)->format('Y-m-d');

    //         // Buat filter untuk join_date antara dua tanggal
    //         $filterJoinDate = "join_date BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";

    //         $periodeLabel = strtoupper(strftime("%d %b %Y", strtotime($awal)) . ' s/d ' . strftime("%d %b %Y", strtotime($akhir)));
    //     }


    //     $query = "
    //         WITH RECURSIVE periode AS (
    //             SELECT
    //                 ea.enroll_id,
    //                 ea.employee_name,
    //                 ea.nik,
    //                 ea.status_staff,
    //                 ea.status_jabatan,
    //                 ea.department_name,
    //                 ea.status_aktif,
    //                 ea.tanggal_resign,
    //                 ea.sub_dept_name,
    //                 ea.join_date,
    //                 ea.join_date AS start_date,
    //                 LEAST(DATE_ADD(ea.join_date, INTERVAL 1 YEAR), CURDATE()) AS end_date
    //             FROM (
    //                 SELECT *
    //                 FROM employee_atribut
    //                 WHERE join_date IS NOT NULL
    //                 AND $filterJoinDate
    //                 ORDER BY enroll_id
    //             ) ea

    //             UNION ALL

    //             SELECT
    //                 p.enroll_id,
    //                 p.employee_name,
    //                 p.nik,
    //                 p.status_staff,
    //                 p.status_jabatan,
    //                 p.department_name,
    //                 p.status_aktif,
    //                 p.tanggal_resign,
    //                 p.sub_dept_name,
    //                 p.join_date,
    //                 p.end_date AS start_date,
    //                 LEAST(DATE_ADD(p.end_date, INTERVAL 1 YEAR), CURDATE()) AS end_date
    //             FROM periode p
    //             WHERE p.end_date < CURDATE()
    //         ),

    //         cuti_dipakai AS (
    //             SELECT
    //                 p.enroll_id,
    //                 p.start_date,
    //                 p.end_date,
    //                 COUNT(d.uuid) AS used_leave
    //             FROM periode p
    //             LEFT JOIN data_absen_perijinan d
    //             ON d.enroll_id = p.enroll_id
    //             AND d.kode_absen_ijin = 'CT'
    //             AND d.tanggal_mulai_ijin >= p.start_date
    //             AND d.tanggal_mulai_ijin < p.end_date
    //             GROUP BY p.enroll_id, p.start_date, p.end_date
    //         ),

    //         data_cuti AS (
    //             SELECT
    //                 p.enroll_id,
    //                 p.employee_name,
    //                 p.nik,
    //                 p.status_staff,
    //                 p.status_jabatan,
    //                 p.department_name,
    //                 p.status_aktif,
    //                 p.tanggal_resign,
    //                 p.sub_dept_name,
    //                 p.join_date,
    //                 p.start_date,
    //                 p.end_date,
    //                 CONCAT(
    //                     TIMESTAMPDIFF(YEAR, p.join_date, p.end_date), ' tahun ',
    //                     TIMESTAMPDIFF(MONTH, p.join_date, p.end_date) % 12, ' bulan'
    //                 ) AS lama_bekerja,
    //                 CASE
    //                     WHEN p.start_date < DATE_ADD(p.join_date, INTERVAL 1 YEAR) THEN 0
    //                     ELSE 1
    //                 END AS is_eligible,
    //                 COALESCE(c.used_leave, 0) AS used_leave,
    //                 CASE
    //                     WHEN p.start_date < DATE_ADD(p.join_date, INTERVAL 1 YEAR) THEN 0
    //                     ELSE 12 - COALESCE(c.used_leave, 0)
    //                 END AS remaining_leave,
    //                 CASE
    //                     WHEN p.start_date < DATE_ADD(p.join_date, INTERVAL 1 YEAR) THEN 'Belum Berhak'
    //                     WHEN (12 - COALESCE(c.used_leave, 0)) > 0 THEN 'Masih Memiliki Cuti'
    //                     ELSE 'Cuti Habis'
    //                 END AS leave_status,
    //                 ROW_NUMBER() OVER (PARTITION BY p.enroll_id ORDER BY p.end_date DESC) AS rn
    //             FROM periode p
    //             LEFT JOIN cuti_dipakai c
    //                 ON p.enroll_id = c.enroll_id AND p.start_date = c.start_date
    //         )

    //         SELECT *
    //         FROM data_cuti
    //         WHERE rn = 1
    //         ORDER BY enroll_id
    //     ";

    //     $data_cuti = DB::select($query);


    //     $data_cuti = collect($data_cuti)->map(function ($cuti) {
    //         $perijinan = DB::table('data_absen_perijinan')
    //             ->where('enroll_id', $cuti->enroll_id)
    //             ->where('kode_absen_ijin', 'CT')
    //             ->where('tanggal_mulai_ijin', '>=', $cuti->start_date)
    //             ->orderBy('tanggal_mulai_ijin', 'asc')
    //             ->get();

    //         $cuti->perijinan = $perijinan;
    //         return $cuti;
    //     });


    //     $excel = FastExcel::create('cuti karyawan');
    //     $sheet = $excel->getSheet();

    //     $area = $sheet->beginArea();

    //     $sheet->writeTo('A1', 'PT NIRWANA ALABARE GARMENT', ['font-size' => 18]);
    //     $sheet->writeTo('A2', 'REKAP CUTI KARYAWAN', ['font-size' => 16]);
    //     $sheet->writeTo('A3', 'PERIODE : ' . $periodeLabel, ['font-size' => 14]);


    //     $sheet->writeTo('A5', 'NIK');

    //     $sheet->writeTo('B5', 'NO ABSEN');

    //     $sheet->writeTo('C5', 'NAMA KARYAWAN');

    //     $sheet->writeTo('D5', 'STAFF / NON STAFF');

    //     $sheet->writeTo('E5', 'JABATAN');

    //     $sheet->writeTo('F5', 'BAGIAN');

    //     $sheet->writeTo('G5', 'DEPARTMENT');

    //     $sheet->writeTo('H5', 'AKTIF / TIDAK AKTIF');

    //     $sheet->writeTo('I5', 'TANGGAL MASUK');
    //     $sheet->writeTo('J5', 'TANGGAL RESIGN');

    //     $sheet->writeTo('K5', 'MASA KERJA');

    //     $sheet->writeTo('L5', 'HAK CUTI');

    //     $sheet->writeTo('M5', 'CUTI TERPAKAI');
    //     $sheet->writeTo('N5', 'CUTI SISA');

    //     $sheet->writeTo('O5', 'REKAP PENGAMBILAN CUTI');

    //     $sheet->writeTo('O6', '1');
    //     $sheet->writeTo('P6', '2');
    //     $sheet->writeTo('Q6', '3');
    //     $sheet->writeTo('R6', '4');
    //     $sheet->writeTo('S6', '5');
    //     $sheet->writeTo('T6', '6');
    //     $sheet->writeTo('U6', '7');
    //     $sheet->writeTo('V6', '8');
    //     $sheet->writeTo('W6', '9');
    //     $sheet->writeTo('X6', '10');
    //     $sheet->writeTo('Y6', '11');
    //     $sheet->writeTo('Z6', '12');



    //     $sheet->mergeCells('A5:A6');
    //     $sheet->mergeCells('B5:B6');
    //     $sheet->mergeCells('C5:C6');
    //     $sheet->mergeCells('D5:D6');
    //     $sheet->mergeCells('E5:E6');
    //     $sheet->mergeCells('F5:F6');
    //     $sheet->mergeCells('G5:G6');
    //     $sheet->mergeCells('H5:H6');
    //     $sheet->mergeCells('I5:I6');
    //     $sheet->mergeCells('J5:J6');
    //     $sheet->mergeCells('K5:K6');
    //     $sheet->mergeCells('L5:L6');
    //     $sheet->mergeCells('M5:M6');
    //     $sheet->mergeCells('N5:N6');

    //     $sheet->mergeCells('O5:Z5');


    //     $sheet->writeAreas();

    //     $sheet->setColOptions([

    //         'A' => ['width' => 15], // NIK
    //         'B' => ['width' => 15], // NAMA KARYAWAN
    //         'C' => ['width' => 18], // STAFF / NON STAFF
    //         'D' => ['width' => 20], // JABATAN
    //         'E' => ['width' => 25], // BAGIAN
    //         'F' => ['width' => 25], // DEPARTMENT
    //         'G' => ['width' => 25], // TANGGAL MASUK
    //         'H' => ['width' => 20], // MASA KERJA
    //         'I' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 20], // HAK CUTI
    //         'J' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY,'width' => 20], // CUTI TERPAKAI
    //         'K' => ['width' => 15], // CUTI TERPAKAI
    //         'L' => ['width' => 10], // CUTI SISA

    //         'M' => ['width' => 15], // MASA KERJA
    //         'N' => ['width' => 15], // MASA KERJA
    //         'O' => ['width' => 15], // MASA KERJA
    //         'P' => ['width' => 15], // MASA KERJA
    //         'Q' => ['width' => 15], // MASA KERJA
    //         'R' => ['width' => 15], // MASA KERJA
    //         'S' => ['width' => 15], // MASA KERJA
    //         'T' => ['width' => 15], // MASA KERJA
    //         'U' => ['width' => 15], // MASA KERJA
    //         'V' => ['width' => 15], // MASA KERJA
    //         'W' => ['width' => 15], // MASA KERJA
    //         'X' => ['width' => 15], // MASA KERJA
    //         'Y' => ['width' => 15], // MASA KERJA
    //         'Z' => ['width' => 15], // MASA KERJA
    //     ]);


    //     foreach($data_cuti as $cuti) {
    //         $row  = [
    //             $cuti->nik,
    //             $cuti->enroll_id,
    //             $cuti->employee_name,
    //             $cuti->status_staff,
    //             $cuti->status_jabatan,
    //             $cuti->sub_dept_name,
    //             $cuti->department_name,
    //             $cuti->status_aktif,
    //             $cuti->join_date,
    //             $cuti->tanggal_resign,
    //             $cuti->lama_bekerja,
    //             $cuti->is_eligible == 1 ? 12 : 0,
    //             $cuti->used_leave,
    //             $cuti->remaining_leave,
    //         ];

    //         $izinDates = collect($cuti->perijinan)
    //         ->pluck('tanggal_mulai_ijin')
    //         ->take(12)
    //         ->values()
    //         ->toArray();

    //     // Tambahkan 12 kolom kosong default
    //         for ($i = 0; $i < 12; $i++) {
    //             $row[] = $izinDates[$i] ?? ''; // Isi tanggal jika ada, kalau tidak isi string kosong
    //         }

    //         $rows[] = $row;

    //         $sheet->writeRow($row);
    //     }
    //     $finename='Rekap Cuti Karyawan'.'xlsx';
    //     ob_end_clean();
    //     $excel->download($finename);
    // }
    public function show_export_by_user(Request $request)
    {
        ini_set("max_execution_time", 5210);
        ini_set('memory_limit', '5120000M');

        $enrollId = $request->enroll_id;
        $kodeAbsen = 'CT';

        // Ambil join_date
        $employee = DB::table('employee_atribut')
            ->where('enroll_id', $enrollId)
            ->first();

        if (!$employee) {
            return [];
        }

        $joinDate = Carbon::parse($employee->join_date);
        $today = Carbon::now();

        $results = [];
        $currentStart = $joinDate->copy();
        $periodeKe = 1;

        while ($currentStart->lessThanOrEqualTo($today)) {
            $currentEnd = $currentStart->copy()->addYear();

            // Ambil data per periode
            $data = DB::table('data_absen_perijinan as dap')
                ->leftJoin('employee_atribut as ea', 'dap.enroll_id', '=', 'ea.enroll_id')
                ->select(
                    DB::raw('YEAR(dap.tanggal_mulai_ijin) as tahun'),
                    'dap.enroll_id',
                    'dap.kode_absen_ijin',
                    'dap.tanggal_perizinan',
                    'dap.nomor_form_perizinan',
                    'dap.tanggal_mulai_ijin',
                    'dap.tanggal_akhir_ijin',
                    'dap.absen_alasan',
                    'ea.join_date',
                    DB::raw("'{$periodeKe}' as periode_ke"),
                    DB::raw("'{$currentStart->toDateString()}' as periode_mulai"),
                    DB::raw("'{$currentEnd->toDateString()}' as periode_selesai")
                )
                ->where('dap.enroll_id', $enrollId)
                ->where('dap.kode_absen_ijin', $kodeAbsen)
                ->whereBetween('dap.tanggal_mulai_ijin', [$currentStart->toDateString(), $currentEnd->toDateString()])
                ->get();

            $formattedData = $data->map(function ($item) use ($currentStart, $currentEnd, $periodeKe, $employee) {
                return [
                    'tahun' => $item->tahun,
                    'enroll_id' => $item->enroll_id,
                    'kode_absen_ijin' => $item->kode_absen_ijin,
                    'tanggal_perizinan' => Carbon::parse($item->tanggal_perizinan)->format('d-m-Y'),
                    'nomor_form_perizinan' => $item->nomor_form_perizinan,
                    'tanggal_mulai_ijin' => Carbon::parse($item->tanggal_mulai_ijin)->format('d-m-Y'),
                    'tanggal_akhir_ijin' => Carbon::parse($item->tanggal_akhir_ijin)->format('d-m-Y'),
                    'absen_alasan' => $item->absen_alasan,
                    'join_date' => Carbon::parse($item->join_date)->format('d-m-Y'),
                    'periode_ke' => $item->periode_ke,
                    'periode_mulai' => Carbon::parse($item->periode_mulai)->format('d-m-Y'),
                    'periode_selesai' => Carbon::parse($item->periode_selesai)->format('d-m-Y'),
                ];
            });

            // Jika kurang dari 12, tambahkan cuti hangus
            $jumlahSaatIni = $formattedData->count();
            if ($jumlahSaatIni < 12) {
                for ($i = $jumlahSaatIni; $i < 12; $i++) {
                    $formattedData->push([
                        'tahun' => null,
                        'enroll_id' => $enrollId,
                        'kode_absen_ijin' => '-',
                        'tanggal_perizinan' => null,
                        'nomor_form_perizinan' => '-',
                        'tanggal_mulai_ijin' => '-',
                        'tanggal_akhir_ijin' => '-',
                        'absen_alasan' => 'CUTI HANGUS / TIDAK DIGUNAKAN',
                        'join_date' => Carbon::parse($employee->join_date)->format('d-m-Y'),
                        'periode_ke' => $periodeKe,
                        'periode_mulai' => $currentStart->format('d-m-Y'),
                        'periode_selesai' => $currentEnd->format('d-m-Y'),
                    ]);
                }
            }
            $results[] = [
                'periode' => $currentStart->format('d-m-Y') . ' - ' . $currentEnd->format('d-m-Y'),
                'data' => $formattedData,
            ];


            $currentStart = $currentEnd;
            $periodeKe++;
        }

        // Format ulang tanggal pada results
        foreach ($results as &$periodeItem) {
            foreach ($periodeItem['data'] as &$item) {
                // Cek dan pastikan hanya tanggal yang valid yang diparsing
                $item['tanggal_perizinan'] = ($item['tanggal_perizinan'] && $item['tanggal_perizinan'] !== '-')
                                            ? Carbon::parse($item['tanggal_perizinan'])->format('d-m-Y')
                                            : '-';
                $item['tanggal_mulai_ijin'] = ($item['tanggal_mulai_ijin'] && $item['tanggal_mulai_ijin'] !== '-')
                                              ? Carbon::parse($item['tanggal_mulai_ijin'])->format('d-m-Y')
                                              : '-';
                $item['tanggal_akhir_ijin'] = ($item['tanggal_akhir_ijin'] && $item['tanggal_akhir_ijin'] !== '-')
                                              ? Carbon::parse($item['tanggal_akhir_ijin'])->format('d-m-Y')
                                              : '-';
                $item['join_date'] = ($item['join_date'] && $item['join_date'] !== '-')
                                     ? Carbon::parse($item['join_date'])->format('d-m-Y')
                                     : '-';
                $item['periode_mulai'] = Carbon::parse($item['periode_mulai'])->format('d-m-Y');
                $item['periode_selesai'] = Carbon::parse($item['periode_selesai'])->format('d-m-Y');
            }
        }

        $excel = FastExcel::create('cuti karyawan');
        $sheet = $excel->getSheet();

        $area = $sheet->beginArea();

        $sheet->writeTo('A1', 'PT NIRWANA ALABARE GARMENT', ['font-size' => 18]);
        $sheet->writeTo('A2', 'REKAP CUTI KARYAWAN', ['font-size' => 14]);
        $sheet->writeTo('A3', 'Nama' . ' : ' . $employee->employee_name);
        $sheet->writeTo('C3', 'NIK' . ' : ' . $employee->nik);

        $sheet->writeTo('A5', 'PERIODE');

        $sheet->writeTo('B5', 'Tanggal Form');

        $sheet->writeTo('C5', 'No Form');

        $sheet->writeTo('D5', ' Tanggal Mulai');

        $sheet->writeTo('E5', 'Tanggal Akhir');

        $sheet->writeTo('F5', 'Kode Absen');

        $sheet->writeTo('G5', 'Keterangan');

        $sheet->writeAreas();

        $sheet->setColOptions([
            'A' => ['width' => 25], // NIK
            'B' => ['width' => 20], // NAMA KARYAWAN
            'C' => ['width' => 20], // STAFF / NON STAFF
            'D' => ['width' => 20], // JABATAN
            'E' => ['width' => 20], // BAGIAN
            'F' => ['width' => 20], // DEPARTMENT
            'G' => ['width' => 30], // TANGGAL MASUK
        ]);

        $rowIndex = 6;
        foreach ($results as $periodeItem) {
            $periode = $periodeItem['periode'];
            $data = $periodeItem['data'];
            $startRow = $rowIndex;
            $endRow = $rowIndex + count($data) - 1;

            $sheet->writeTo('A' . $startRow, $periode);
            if (count($data) > 1) {
                $sheet->mergeCells("A{$startRow}:A{$endRow}");
            }
            $sheet->writeTo('A' . $startRow, $periode, [
                'align' => 'center',
                'valign' => 'middle',
                'bold' => true
            ]);
            $first = true;

            foreach ($periodeItem['data'] as $cuti) {
                $sheet->writeTo('A' . $rowIndex, $first ? $periode : '');
                $sheet->writeTo('B' . $rowIndex, $cuti['tanggal_perizinan']);
                $sheet->writeTo('C' . $rowIndex, $cuti['nomor_form_perizinan']);
                $sheet->writeTo('D' . $rowIndex, $cuti['tanggal_mulai_ijin']);
                $sheet->writeTo('E' . $rowIndex, $cuti['tanggal_akhir_ijin']);
                $sheet->writeTo('F' . $rowIndex, $cuti['kode_absen_ijin'] ?? '');
                $sheet->writeTo('G' . $rowIndex, $cuti['absen_alasan'] ?? '');

                $rowIndex++;
                $first = false;
            }
        }



        $finename='Rekap Detail Cuti Karyawan '.'xlsx';
        ob_end_clean();
        $excel->download($finename);
    }

    public function show_export_detail_cuti_karyawan(Request $request){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '10240000000000000M');
        $enroll_id = $request->enroll_id;

        $query = "
            WITH RECURSIVE periode AS (
                SELECT
                    ea.enroll_id,
                    ea.employee_name,
                    ea.nik,
                    ea.status_staff,
                    ea.status_jabatan,
                    ea.department_name,
                    ea.sub_dept_name,
                    ea.join_date,
                    ea.join_date AS start_date,
                    LEAST(DATE_ADD(ea.join_date, INTERVAL 1 YEAR), CURDATE()) AS end_date
                FROM (
                    SELECT *
                    FROM employee_atribut
                    WHERE join_date IS NOT NULL
                    AND enroll_id = $enroll_id
                    ORDER BY enroll_id
                ) ea

                UNION ALL

                SELECT
                    p.enroll_id,
                    p.employee_name,
                    p.nik,
                    p.status_staff,
                    p.status_jabatan,
                    p.department_name,
                    p.sub_dept_name,
                    p.join_date,
                    p.end_date AS start_date,
                    LEAST(DATE_ADD(p.end_date, INTERVAL 1 YEAR), CURDATE()) AS end_date
                FROM periode p
                WHERE p.end_date < CURDATE()
            ),

            cuti_dipakai AS (
                SELECT
                    p.enroll_id,
                    p.start_date,
                    p.end_date,
                    COUNT(d.uuid) AS used_leave
                FROM periode p
                LEFT JOIN data_absen_perijinan d
                ON d.enroll_id = p.enroll_id
                AND d.kode_absen_ijin = 'CT'
                AND d.tanggal_mulai_ijin >= p.start_date
                AND d.tanggal_mulai_ijin < p.end_date
                GROUP BY p.enroll_id, p.start_date, p.end_date
            ),

            data_cuti AS (
                SELECT
                    p.enroll_id,
                    p.employee_name,
                    p.nik,
                    p.status_staff,
                    p.status_jabatan,
                    p.department_name,
                    p.sub_dept_name,
                    p.join_date,
                    p.start_date,
                    p.end_date,
                    CONCAT(
                        TIMESTAMPDIFF(YEAR, p.join_date, p.end_date), ' tahun ',
                        TIMESTAMPDIFF(MONTH, p.join_date, p.end_date) % 12, ' bulan'
                    ) AS lama_bekerja,
                    CASE
                        WHEN p.start_date < DATE_ADD(p.join_date, INTERVAL 1 YEAR) THEN 0
                        ELSE 1
                    END AS is_eligible,
                    COALESCE(c.used_leave, 0) AS used_leave,
                    CASE
                        WHEN p.start_date < DATE_ADD(p.join_date, INTERVAL 1 YEAR) THEN 0
                        ELSE 12 - COALESCE(c.used_leave, 0)
                    END AS remaining_leave,
                    CASE
                        WHEN p.start_date < DATE_ADD(p.join_date, INTERVAL 1 YEAR) THEN 'Belum Berhak'
                        WHEN (12 - COALESCE(c.used_leave, 0)) > 0 THEN 'Masih Memiliki Cuti'
                        ELSE 'Cuti Habis'
                    END AS leave_status,
                    ROW_NUMBER() OVER (PARTITION BY p.enroll_id ORDER BY p.end_date DESC) AS rn
                FROM periode p
                LEFT JOIN cuti_dipakai c
                    ON p.enroll_id = c.enroll_id AND p.start_date = c.start_date
            )

            SELECT *
            FROM data_cuti
            WHERE rn = 1
            ORDER BY enroll_id
        ";


        $data_cuti = DB::select($query);

        $perijinan = DB::table('data_absen_perijinan')
                    ->where('enroll_id', $enroll_id)
                    ->where('kode_absen_ijin', 'CT') // Tambahkan kondisi kode_absen_ijin
                    ->where('tanggal_mulai_ijin', '>=', $start_date) // Gunakan parameter start_date yang sesuai
                    ->where('tanggal_mulai_ijin', '<', $end_date) // Gunakan parameter end_date yang sesuai
                    ->orderBy('tanggal_mulai_ijin', 'desc')
                    ->get();


        $data_cuti = collect($data_cuti)->map(function ($cuti) use ($perijinan) {
            $cuti->perijinan = $perijinan;
            return $cuti;
        });
        // dd($data_cuti);
        $fileName = 'RekapCutiKaryawanKaryawan.xlsx';

        $response= Excel::download(new RekapCutiKaryawanKaryawanAll($data_cuti), $fileName, \Maatwebsite\Excel\Excel::XLSX);
        ob_end_clean();
        return $response;
    }

    public function ajax_dataabsenperizinan(Request $request)
    {
        $email = Auth::guard('admin')->user()->email;
        $status = $request->input('is_verifikasi');
        $search = $request->input('search.value');

        // Query dasar
        $query = DataAbsenPerijinan::select('employee_atribut.employee_name', 'employee_atribut.nik', 'data_absen_perijinan.*')
            ->leftJoin('employee_atribut', 'data_absen_perijinan.enroll_id', '=', 'employee_atribut.enroll_id')
            ->where('data_absen_perijinan.operator', '=', $email)
            ->where('data_absen_perijinan.is_verifikasi', '=', $status);

        // Jika ada pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('data_absen_perijinan.uuid_master', 'LIKE', "%{$search}%")
                  ->orWhere('data_absen_perijinan.tanggal_perizinan', 'LIKE', "%{$search}%")
                  ->orWhere('data_absen_perijinan.nomor_form_perizinan', 'LIKE', "%{$search}%")
                  ->orWhere('data_absen_perijinan.enroll_id', 'LIKE', "%{$search}%")
                  ->orWhere('employee_atribut.nik', 'LIKE', "%{$search}%")
                  ->orWhere('employee_atribut.employee_name', 'LIKE', "%{$search}%")
                  ->orWhere('data_absen_perijinan.kode_absen_ijin', 'LIKE', "%{$search}%")
                  ->orWhere('data_absen_perijinan.absen_alasan', 'LIKE', "%{$search}%");
            });
        }

        // Mengatur pagination dan ordering
        $limit = $request->input('length');
        $start = $request->input('start');
        $orderColumn = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir');
        $columns = [
            'uuid', 'uuid_master', 'tanggal_perizinan', 'nomor_form_perizinan', 'enroll_id',
            'nik', 'employee_name', 'created_at'
        ];

        $query->offset($start)
              ->limit($limit)
              ->orderBy($columns[$orderColumn], $orderDir);

        // Mendapatkan data
        $data = $query->get();

        // Mendapatkan total data dan data yang difilter
        $totalData = DataAbsenPerijinan::where('operator', $email)
                                        ->where('is_verifikasi', $status)
                                        ->count();

        $totalFiltered = $totalData;

        // Format hasil
        $formattedData = $data->map(function ($q) {
            return [
                'uuid' => $q->uuid,
                'uuid_master' => $q->uuid_master,
                'tanggal_perizinan' => Carbon::parse($q->tanggal_perizinan)->format('d-m-Y'),
                'nomor_form_perizinan' => $q->nomor_form_perizinan,
                'enroll_id' => $q->enroll_id,
                'nik' => $q->nik,
                'employee_name' => $q->employee_name,
                'kode_absen_ijin' => $q->kode_absen_ijin,
                'absen_alasan' => $q->absen_alasan,
                'tanggal_mulai_ijin' => Carbon::parse($q->tanggal_mulai_ijin)->format('d-m-Y'),
                'tanggal_akhir_ijin' => Carbon::parse($q->tanggal_akhir_ijin)->format('d-m-Y'),
                'time_mulai_ijin' => substr($q->time_mulai_ijin, 0, 5),
                'time_akhir_ijin' => substr($q->time_akhir_ijin, 0, 5),
                'total_time_ijin' => $q->total_time_ijin,
                'operator' => $q->operator,
                'created_at' => substr($q->created_at, 0, 10) . " " . substr($q->created_at, 11, 5),
                'updated_at' => substr($q->updated_at, 0, 10) . " " . substr($q->updated_at, 11, 5),
            ];
        });

        // Format response untuk DataTables
        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $formattedData
        ]);
    }


}
