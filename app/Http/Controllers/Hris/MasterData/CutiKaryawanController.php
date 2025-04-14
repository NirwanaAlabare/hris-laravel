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



        // Siapkan binding untuk IN clause
        // $data_cuti    = DB::select("
        //                 WITH RECURSIVE periode AS (
        //                     SELECT
        //                         ea.enroll_id,
        //                         ea.employee_name,
        //                         ea.department_name,
        //                         ea.sub_dept_name,
        //                         ea.join_date,
        //                         ea.join_date AS start_date,
        //                         LEAST(DATE_ADD(ea.join_date, INTERVAL 1 YEAR), CURDATE()) AS end_date
        //                     FROM (
        //                         SELECT *
        //                         FROM employee_atribut
        //                         WHERE join_date IS NOT NULL
        //                         ORDER BY enroll_id
        //                         LIMIT 10
        //                     ) ea
        //                     UNION ALL
        //                     SELECT
        //                         p.enroll_id,
        //                         p.employee_name,
        //                         p.department_name,
        //                         p.sub_dept_name,
        //                         p.join_date,
        //                         p.end_date AS start_date,
        //                         LEAST(DATE_ADD(p.end_date, INTERVAL 1 YEAR), CURDATE()) AS end_date
        //                     FROM periode p
        //                     WHERE p.end_date < CURDATE()
        //                 ),

        //                 cuti_dipakai AS (
        //                     SELECT
        //                         p.enroll_id,
        //                         p.start_date,
        //                         p.end_date,
        //                         COUNT(d.uuid) AS used_leave
        //                     FROM periode p
        //                     LEFT JOIN data_absen_perijinan d
        //                     ON d.enroll_id = p.enroll_id
        //                     AND d.kode_absen_ijin = 'CT'
        //                     AND d.tanggal_mulai_ijin >= p.start_date
        //                     AND d.tanggal_mulai_ijin < p.end_date
        //                     GROUP BY p.enroll_id, p.start_date, p.end_date
        //                 ),

        //                 data_cuti AS (
        //                     SELECT
        //                         p.enroll_id,
        //                         p.employee_name,
        //                         p.department_name,
        //                         p.sub_dept_name,
        //                         p.join_date,
        //                         p.start_date,
        //                         p.end_date,
        //                         CONCAT(
        //                             TIMESTAMPDIFF(YEAR, p.join_date, p.end_date), ' tahun ',
        //                             TIMESTAMPDIFF(MONTH, p.join_date, p.end_date) % 12, ' bulan'
        //                         ) AS lama_bekerja,
        //                         CASE
        //                             WHEN p.start_date < DATE_ADD(p.join_date, INTERVAL 1 YEAR) THEN 0
        //                             ELSE 1
        //                         END AS is_eligible,
        //                         COALESCE(c.used_leave, 0) AS used_leave,
        //                         CASE
        //                             WHEN p.start_date < DATE_ADD(p.join_date, INTERVAL 1 YEAR) THEN 0
        //                             ELSE 12 - COALESCE(c.used_leave, 0)
        //                         END AS remaining_leave,
        //                         CASE
        //                             WHEN p.start_date < DATE_ADD(p.join_date, INTERVAL 1 YEAR) THEN 'Belum Berhak'
        //                             WHEN (12 - COALESCE(c.used_leave, 0)) > 0 THEN 'Masih Memiliki Cuti'
        //                             ELSE 'Cuti Habis'
        //                         END AS leave_status,
        //                         ROW_NUMBER() OVER (PARTITION BY p.enroll_id ORDER BY p.end_date DESC) AS rn
        //                     FROM periode p
        //                     LEFT JOIN cuti_dipakai c
        //                         ON p.enroll_id = c.enroll_id AND p.start_date = c.start_date
        //                 )

        //                 SELECT *
        //                 FROM data_cuti
        //                 WHERE rn = 1
        //                 ORDER BY enroll_id;", [$enroll_id, $enroll_id]);

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
                // return  '<div> <a class="btn btn-success btn-sm" style="color:white;" data-toggle="tooltip" title="Export Data ke File Transfer PDF" id="recap_labor_cost_2">
                //                         <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                //                     </a>
                //             <a class="btn btn-danger btn-sm" style="color:white;" data-toggle="tooltip" title="Export Data ke File Transfer PDF" id="export_form_pengajuan_cuti_'.$row->enroll_id.'">
                //                 <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                //             </a>
                //             <a style="text-align:center; color:white;" class="btn btn-danger btn-sm">
                //             <i class="fa fa-trash"></i>
                //             </a></div>';
                return  '<div> <a class="btn btn-success btn-sm" style="color:white;" data-toggle="tooltip" title="Export Data ke File Transfer PDF" id="export_detail_cuti_karyawan_'.$row->enroll_id.'">
                                        <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                    </a>
                            <a class="btn btn-primary btn-sm" style="color:white;" data-toggle="tooltip" title="Export Data ke File Transfer PDF" id="open_detail_'.$row->enroll_id.'">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>
                          </div>';
            })
            ->rawColumns(['is_eligible','actions'])
            ->make(true);
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

    public function export_form_pengajuan_cuti_pdf() {
        $email = Auth::guard('admin')->user()->email;
        $data = DB::select("WITH RECURSIVE periode AS (
            SELECT
                enroll_id,
                employee_name,
                department_name,
                nik,
                sub_dept_name,
                join_date,
                join_date AS start_date,
                LEAST(DATE_ADD(join_date, INTERVAL 1 YEAR), CURDATE()) AS end_date
            FROM employee_atribut
            UNION ALL
            SELECT
                enroll_id,
                employee_name,
                department_name,
                nik,
                sub_dept_name,
                join_date,
                end_date AS start_date,
                LEAST(DATE_ADD(end_date, INTERVAL 1 YEAR), CURDATE()) AS end_date
            FROM periode
            WHERE end_date < CURDATE()
        ),
        cuti_dipakai AS (
            SELECT
                enroll_id,
                YEAR(tanggal_perizinan) AS periode_tahun,
                COUNT(*) AS used_leave
            FROM data_absen_perijinan
            WHERE kode_absen_ijin = 'CT'
            GROUP BY enroll_id, periode_tahun
        ),
        data_cuti AS (
            SELECT
                p.enroll_id,
                p.employee_name,
                p.department_name,
                p.nik,
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
                CASE
                    WHEN EXISTS (
                        SELECT 1 FROM periode p_next
                        WHERE p_next.enroll_id = p.enroll_id
                        AND p_next.start_date = p.end_date
                        AND (12 - COALESCE(c.used_leave, 0)) > 0
                    ) THEN 'TRUE'
                    ELSE 'FALSE'
                END AS cuti_hangus,
                ROW_NUMBER() OVER (PARTITION BY p.enroll_id ORDER BY p.end_date DESC) AS rn
            FROM periode p
            LEFT JOIN cuti_dipakai c
                ON p.enroll_id = c.enroll_id
                AND YEAR(p.end_date) = c.periode_tahun
        )
        SELECT * FROM data_cuti WHERE rn = 1 AND enroll_id = '".request()->enroll_id."'
        ORDER BY enroll_id;");

        $fileName='Form Pengajuan Cuti '.date('Y-m-d').' '.rand(10,1000000);
        $pdf = PDF::loadView('hris.absen.cuti_karyawan.export-form-pengajuan-cuti-pdf',["data" => $data[0]])->setPaper('F4', 'fotrait')->stream($fileName.'.pdf',array('Attachment'=>0));
        return $pdf;
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

        $data_cuti = DB::select($query, $bindings);

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

        $sheet->writeTo('H5', 'TANGGAL MASUK');

        $sheet->writeTo('I5', 'MASA KERJA');

        $sheet->writeTo('J5', 'HAK CUTI');

        $sheet->writeTo('K5', 'CUTI TERPAKAI');
        $sheet->writeTo('L5', 'CUTI SISA');

        $sheet->writeTo('M5', 'REKAP PENGAMBILAN CUTI', [
            'font-size' => 14,
            'halign' => 'center',
            'valign' => 'center'
        ]);

        $sheet->writeTo('M6', '1');
        $sheet->writeTo('N6', '2');
        $sheet->writeTo('O6', '3');
        $sheet->writeTo('P6', '4');
        $sheet->writeTo('Q6', '5');
        $sheet->writeTo('R6', '6');
        $sheet->writeTo('S6', '7');
        $sheet->writeTo('T6', '8');
        $sheet->writeTo('U6', '9');
        $sheet->writeTo('V6', '10');
        $sheet->writeTo('W6', '11');
        $sheet->writeTo('X6', '12');



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

        $sheet->mergeCells('M5:Y5');



        $sheet->writeAreas();

        $sheet->setColOptions([

            'A' => ['width' => 15], // NIK
            'B' => ['width' => 15], // NAMA KARYAWAN
            'C' => ['width' => 18], // STAFF / NON STAFF
            'D' => ['width' => 20], // JABATAN
            'E' => ['width' => 25], // BAGIAN
            'F' => ['width' => 25], // DEPARTMENT
            'G' => ['width' => 25], // TANGGAL MASUK
            'H' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 20], // MASA KERJA
            'I' => ['width' => 20], // HAK CUTI
            'J' => ['width' => 12], // CUTI TERPAKAI
            'K' => ['width' => 15], // CUTI TERPAKAI
            'L' => ['width' => 10], // CUTI SISA

            'M' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'N' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'O' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'P' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'Q' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'R' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'S' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'T' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'U' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'V' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'W' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'X' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
        ]);


        foreach($data_cuti as $cuti) {
            $row  = [
                $cuti->nik,
                $cuti->enroll_id,
                $cuti->employee_name,
                $cuti->status_staff,
                $cuti->status_jabatan,
                $cuti->sub_dept_name,
                $cuti->department_name,
                $cuti->join_date,
                $cuti->lama_bekerja,
                $cuti->is_eligible == 1 ? 12 : 0,
                $cuti->used_leave,
                $cuti->remaining_leave,
            ];

            $izinDates = collect($cuti->perijinan)
            ->pluck('tanggal_mulai_ijin')
            ->take(12)
            ->values()
            ->toArray();

        // Tambahkan 12 kolom kosong default
            for ($i = 0; $i < 12; $i++) {
                $row[] = $izinDates[$i] ?? ''; // Isi tanggal jika ada, kalau tidak isi string kosong
            }

            $rows[] = $row;

            $sheet->writeRow($row);
        }
        $finename='Rekap Cuti Karyawan'.'xlsx';
        ob_end_clean();
        $excel->download($finename);
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

        $sheet->writeTo('H5', 'TANGGAL MASUK');

        $sheet->writeTo('I5', 'MASA KERJA');

        $sheet->writeTo('J5', 'HAK CUTI');

        $sheet->writeTo('K5', 'CUTI TERPAKAI');
        $sheet->writeTo('L5', 'CUTI SISA');






        $sheet->writeTo('M6', '1');
        $sheet->writeTo('N6', '2');
        $sheet->writeTo('O6', '3');
        $sheet->writeTo('P6', '4');
        $sheet->writeTo('Q6', '5');
        $sheet->writeTo('R6', '6');
        $sheet->writeTo('S6', '7');
        $sheet->writeTo('T6', '8');
        $sheet->writeTo('U6', '9');
        $sheet->writeTo('V6', '10');
        $sheet->writeTo('W6', '11');
        $sheet->writeTo('X6', '12');



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

        $sheet->mergeCells('M5:Y5');

        $sheet->writeTo('M5', 'REKAP PENGAMBILAN CUTI', [
            'font-size' => 14,
            'halign' => 'center',
            'valign' => 'center'
        ]);



        $sheet->writeAreas();

        $sheet->setColOptions([

            'A' => ['width' => 15], // NIK
            'B' => ['width' => 15], // NAMA KARYAWAN
            'C' => ['width' => 18], // STAFF / NON STAFF
            'D' => ['width' => 20], // JABATAN
            'E' => ['width' => 25], // BAGIAN
            'F' => ['width' => 25], // DEPARTMENT
            'G' => ['width' => 25], // TANGGAL MASUK
            'H' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 20], // MASA KERJA
            'I' => ['width' => 20], // HAK CUTI
            'J' => ['width' => 12], // CUTI TERPAKAI
            'K' => ['width' => 15], // CUTI TERPAKAI
            'L' => ['width' => 10], // CUTI SISA

            'M' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'N' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'O' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'P' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'Q' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'R' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'S' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'T' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'U' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'V' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'W' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
            'X' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 15], // MASA KERJA
        ]);


        foreach($data_cuti as $cuti) {
            $row  = [
                $cuti->nik,
                $cuti->enroll_id,
                $cuti->employee_name,
                $cuti->status_staff,
                $cuti->status_jabatan,
                $cuti->sub_dept_name,
                $cuti->department_name,
                $cuti->join_date,
                $cuti->lama_bekerja,
                $cuti->is_eligible == 1 ? 12 : 0,
                $cuti->used_leave,
                $cuti->remaining_leave,
            ];

            $izinDates = collect($cuti->perijinan)
            ->pluck('tanggal_mulai_ijin')
            ->take(12)
            ->values()
            ->toArray();

        // Tambahkan 12 kolom kosong default
            for ($i = 0; $i < 12; $i++) {
                $row[] = $izinDates[$i] ?? ''; // Isi tanggal jika ada, kalau tidak isi string kosong
            }

            $rows[] = $row;

            $sheet->writeRow($row);
        }
        $finename='Rekap Cuti Karyawan'.'xlsx';
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

}
