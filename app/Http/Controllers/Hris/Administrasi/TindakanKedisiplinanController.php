<?php

namespace App\Http\Controllers\Hris\Administrasi;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\View;
use DB;
use PDF;
use Dompdf\Dompdf;
use App\Models\EmployeeAtributHistory;
use App\Models\MutKaryawan;
use Carbon\Carbon;
use Dompdf\Options;
use Dompdf\FontMetrics;
use App\Models\PengajuanPermintaanTk;
use App\Models\EmployeeAtribut;
use App\Models\PengajuanBazzar;
use App\Models\VoucherBazzar;
use App\Models\RefAbsenIjin;
use App\Models\EntertainPengajuanTamu;
use App\Models\EntertainPengajuanPendamping;
use App\Models\EntertainPengajuanKeterangan;
use App\Models\DataAbsenPerijinanDTPC;
use App\Models\SuratPeringatanKaryawan;
use App\Models\PengajuanCoachingKaryawan;
use App\Models\PengajuanKedisiplinanKaryawan;
use App\Models\Notification;
use App\Models\PasalSuratPeringatan;
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
use App\Exports\exportExcelSuratPeringatan;
use FilippoToso\PdfWatermarker\Support\Position;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use \avadim\FastExcelLaravel\Excel as FastExcel;


class TindakanKedisiplinanController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Dashboard';
    }

    public function index()
    {
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

        $DepartmentAllModel =  DepartmentAll::groupBy('department_name')
        ->orderBy('department_name','asc')
        ->get();
        $NirwananameAllModel =  DepartmentAll::groupBy('site_nirwana_name')
        ->orderBy('site_nirwana_name','asc')
        ->get();

        $this->department =  $DepartmentAllModel;
        $this->site =  $NirwananameAllModel;
        $selectemployee = $this->ajax_getallemployeeatribut();

         return view('hris/tindakan-kedisiplinan/surat_peringatan', [
            'page' => 'dashboard-mut-karyawan', "subPageGroup" => "proses-karyawan", "subPage" => "form-lembur-non-sewing",
            "data_dept" => $data_dept, "user" => $user,
            "selectemployee" => $selectemployee,
        ], $this->data);
    }
    public function surat_peringatan_hr()
    {
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

        $DepartmentAllModel =  DepartmentAll::groupBy('department_name')
        ->orderBy('department_name','asc')
        ->get();
        $NirwananameAllModel =  DepartmentAll::groupBy('site_nirwana_name')
        ->orderBy('site_nirwana_name','asc')
        ->get();

        $this->department =  $DepartmentAllModel;
        $this->site =  $NirwananameAllModel;
        $selectemployee = $this->ajax_getallemployeeatribut();
        $pasal_data = PasalSuratPeringatan::selectRaw('kode_pasal, sp, pasal, desc_surat_peringatan, deskripsi, concat("Pasal ",kode_pasal, " - ", pasal, ". " ,desc_surat_peringatan) pasal_select')
                    ->orderByRaw('CAST(SUBSTRING_INDEX(kode_pasal, "-", 1) AS UNSIGNED) ASC') // angka sebelum tanda '-'
                    ->orderByRaw('CAST(SUBSTRING_INDEX(kode_pasal, "-", -1) AS UNSIGNED) ASC')
                    ->get();
         return view('hris/tindakan-kedisiplinan/surat_peringatan_hr', [
            'page' => 'dashboard-mut-karyawan', "subPageGroup" => "proses-karyawan", "subPage" => "form-lembur-non-sewing",
            "data_dept" => $data_dept, "user" => $user,
            "selectemployee" => $selectemployee,
            "pasal_data" => $pasal_data,
        ], $this->data);
    }

    public function ajax_getallemployeeatribut()
    {
        $query =  EmployeeAtribut::selectRaw('enroll_id, nik, employee_name, department_name, sub_dept_name,department_id,sub_dept_id,status_jabatan,
                                           concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                                    ->groupby('enroll_id')
                                    ->orderby('employee_name', 'asc')
                                    ->get();
        return $query;

    }

   public function get_detail_tindakan_kedisiplinan(Request $request)
    {
        $id = $request->id;

        // Data utama pengajuan
        $data = DB::selectOne("
            SELECT
                pengajuan_kedisiplinan_karyawan.*,
                ea_1.department_name AS department_name_pengaju,
                ea_1.sub_dept_name AS bagian_name_pengaju,
                ea_2.department_name AS department_name_diajukan,
                ea_2.sub_dept_name AS bagian_name_diajukan,
                ea_2.employee_name,
                ea_2.nik,
                ea_2.status_jabatan
            FROM pengajuan_kedisiplinan_karyawan
            LEFT JOIN employee_atribut ea_1 ON pengajuan_kedisiplinan_karyawan.enroll_id_diajukan_oleh = ea_1.enroll_id
            LEFT JOIN employee_atribut ea_2 ON pengajuan_kedisiplinan_karyawan.enroll_id_karyawan_bermasalah = ea_2.enroll_id
            WHERE pengajuan_kedisiplinan_karyawan.id = ?
        ", [$id]);

        // Ambil daftar faktor dari tabel relasi
        $faktorList = DB::table('pengajuan_kedisiplinan_faktor')
            ->where('pengajuan_kedisiplinan_karyawan_id', $id)
            ->select('faktor', 'uraian')
            ->get();

        // Gabungkan dan kirim response JSON
        return response()->json([
            'id' => $data->id,
            'tanggal_pengajuan' => $data->tanggal_pengajuan,
            'tindakan_pendisiplinan' => $data->tindakan_pendisiplinan,
            'enroll_id_diajukan_oleh' => $data->enroll_id_diajukan_oleh,
            'enroll_id_karyawan_bermasalah' => $data->enroll_id_karyawan_bermasalah,
            'pelanggaran' => $data->pelanggaran,
            'sumber_permasalahan' => $data->sumber_permasalahan,
            'department_name_pengaju' => $data->department_name_pengaju,
            'bagian_name_pengaju' => $data->bagian_name_pengaju,
            'department_name_diajukan' => $data->department_name_diajukan,
            'bagian_name_diajukan' => $data->bagian_name_diajukan,
            'employee_name' => $data->employee_name,
            'nik' => $data->nik,
            'status_jabatan' => $data->status_jabatan,
            'faktor_list' => $faktorList
        ]);
    }


    public function get_detail_surat_peringatan(Request $request)
    {
        $id = $request->id;
        $date = date('Y-m-d');
        $data = DB::select("SELECT surat_peringatan_karyawan.*, ea.employee_name ,ea.nik, ea.sub_dept_name, ea.department_name, ea.status_jabatan from surat_peringatan_karyawan left join employee_atribut ea on surat_peringatan_karyawan.enroll_id = ea.enroll_id WHERE id = ?", [$id]);
        $enroll_id = $data[0]->enroll_id;
        $tanggal_berjalan = $date;

        // Query pertama
        $history_peringatan  = SuratPeringatanKaryawan::select(
            'surat_peringatan_karyawan.*',
            'employee_atribut.employee_name',
            'employee_atribut.nik',
            'employee_atribut.department_name',
            'employee_atribut.sub_dept_name',
            'employee_atribut.status_jabatan'
        )
        ->leftJoin('employee_atribut', 'surat_peringatan_karyawan.enroll_id', '=', 'employee_atribut.enroll_id')
        ->where('surat_peringatan_karyawan.enroll_id', $enroll_id)
        ->where('surat_peringatan_karyawan.tanggal_mulai', '<=', $tanggal_berjalan)
        ->where('surat_peringatan_karyawan.tanggal_sampai', '>=', $tanggal_berjalan)
        ->get();

        // Format response untuk DataTables
        return ['data' => $data[0], 'history_peringatan' => $history_peringatan];
    }


    public function create_surat_peringatan(Request $request){
        $logged_admin = Auth::guard('admin')->user();
        SuratPeringatanKaryawan::create([
            'enroll_id' => $request->enroll_id,
            'kode_pasal' => $request->selectedKodePasal,
            'surat_peringatan' => $request->tindakan_pendisiplinan,
            'tanggal_mulai' => $request->tanggal_berlaku_mulai,
            'tanggal_sampai' => $request->tanggal_berlaku_sampai,
            'operator' => $logged_admin->email,
            'alasan_pelanggaran' => $request->alasan_pelanggaran,
            'no_form' => $request->no_form,
        ]);
        return response()->json(['message' => 'Permintaan Tenaga Kerja berhasil dibuat.']);
    }

    public function simpan_form_coaching(Request $request){
        $logged_admin = Auth::guard('admin')->user();
        $tanggal_pengajuan_coaching = date('Y-m-d');
        PengajuanCoachingKaryawan::create([
                'tanggal_pengajuan_coaching' => $tanggal_pengajuan_coaching,
                'nomor_form_coaching' => $request->no_form_coaching,
                'enroll_id_karyawan_coaching' => $request->enroll_id_karyawan_coaching,
                'deskripsi_coaching' => $request->deskripsi_coaching,
                'created_by' => $logged_admin->email,
        ]);
        return response()->json(['message' => 'Form Coaching berhasil dibuat.']);
    }

    public function update_surat_peringatan(Request $request){
        $logged_admin = Auth::guard('admin')->user();
        SuratPeringatanKaryawan::where('id', $request->id_pengajuan)->update([
            'enroll_id' => $request->enroll_id,
            'kode_pasal' => $request->selectedKodePasal,
            'surat_peringatan' => $request->tindakan_pendisiplinan,
            'tanggal_mulai' => $request->tanggal_berlaku_mulai,
            'tanggal_sampai' => $request->tanggal_berlaku_sampai,
            'operator' => $logged_admin->email,
            'alasan_pelanggaran' => $request->alasan_pelanggaran,
            'no_form' => $request->no_form,
        ]);
        return response()->json(['message' => 'Permintaan Tenaga Kerja berhasil dibuat.']);
    }

    public function create_form_tindakan_kedisiplinan(Request $request){
        $logged_admin = Auth::guard('admin')->user();
        $pengajuan = PengajuanKedisiplinanKaryawan::create([
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'tindakan_pendisiplinan' => $request->tindakan_pendisiplinan,
            'enroll_id_diajukan_oleh' => $request->enroll_id_diajukan_oleh,
            'enroll_id_karyawan_bermasalah' => $request->enroll_id_karyawan_bermasalah,
            'pelanggaran' => $request->pelanggaran,
            'sumber_permasalahan' => $request->sumber_permasalahan,
            'status_pengajuan' => 'diajukan',
            'created_by' => $logged_admin->email,
        ]);

         foreach ($request->faktorList as $faktor) {
                $pengajuan->faktor()->create([
                    'faktor' => $faktor['faktor'],
                    'uraian' => $faktor['uraian'],
                ]);
        }
        $employee_data = EmployeeAtribut::where('enroll_id', $request->enroll_id_karyawan_bermasalah)->first();
        $receiverEmails = ['fadli', 'mega@ptnag.com', 'ersa@ptnag.com', 'rudy@ptnag.com', 'indri@nag.nirwanaindonesia.com','pujiprana@nag.nirwanaindonesia.com','hadiyoso@nag.nirwanaindonesia.com','ramon'];
        foreach ($receiverEmails as $receiverEmail) {
            Notification::create([
                'sender_email' => 'system',
                'receiver_email' => $receiverEmail,
                'type' => 'KEDISIPLINAN',
                'href_menu' => 'http://10.10.5.111/hris/public/index.php/hris/tindakan_kedisiplinan/index',
                'message' => 'Ada pengajuan tindakan kedisiplinan untuk ' . $employee_data->employee_name . ' (' . $employee_data->nik . ')',
                'enroll_ids' => [$request->enroll_id_karyawan_bermasalah],
                'is_read' => false,
                'is_delete' => false,
                'status_staff' => $employee_data->status_jabatan,
            ]);
        }
        return response()->json(['message' => 'Permintaan Tenaga Kerja berhasil dibuat.']);
    }

    public function update_form_tindakan_kedisiplinan(Request $request)
    {
        $logged_admin = Auth::guard('admin')->user();

        // Update data utama
        PengajuanKedisiplinanKaryawan::where('id', $request->id)->update([
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'tindakan_pendisiplinan' => $request->tindakan_pendisiplinan,
            'enroll_id_diajukan_oleh' => $request->enroll_id_diajukan_oleh,
            'enroll_id_karyawan_bermasalah' => $request->enroll_id_karyawan_bermasalah,
            'pelanggaran' => $request->pelanggaran,
            'sumber_permasalahan' => $request->sumber_permasalahan,
            'created_by' => $logged_admin->email,
        ]);

        // Hapus data faktor lama (agar tidak dobel)
        DB::table('pengajuan_kedisiplinan_faktor')
            ->where('pengajuan_kedisiplinan_karyawan_id', $request->id)
            ->delete();

        // Simpan ulang faktor yang dikirim dari form
        if ($request->has('faktorList')) {
            foreach ($request->faktorList as $faktor) {
                DB::table('pengajuan_kedisiplinan_faktor')->insert([
                    'pengajuan_kedisiplinan_karyawan_id' => $request->id,
                    'faktor' => $faktor['faktor'],
                    'uraian' => $faktor['uraian'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json(['message' => 'Pengajuan tindakan kedisiplinan berhasil diupdate.']);
    }


    public function approve_pengajuan_kedisiplinan(Request $request){
        $logged_admin = Auth::guard('admin')->user();
        $data = PengajuanKedisiplinanKaryawan::where('id', $request->id)->get()->first();
        $data->status_pengajuan = 'done';
        $data->tindakan_pendisiplinan = $request->tindakan_pendisiplinan_edit;
        $data->verifikator_by = $logged_admin->email;
        $data->save();
        return response()->json(['message' => 'Permintaan Tenaga Kerja berhasil di approve.']);
    }
    public function reject_pengajuan_kedisiplinan(Request $request){
        PengajuanKedisiplinanKaryawan::where('id', $request->id)->update([
            'status_pengajuan' => 'batal',
            'verifikator_by' => $logged_admin->email,
        ]);
        return response()->json(['message' => 'Permintaan Tenaga Kerja berhasil ditolak.']);
    }

    public function delete_pengajuan_kedisiplinan(Request $request){
        PengajuanKedisiplinanKaryawan::where('id', $request->id_pengajuan)->delete();
        return response()->json(['message' => 'Permintaan Tenaga Kerja berhasil dihapus.']);
    }

    public function delete_surat_peringatan(Request $request){
        SuratPeringatanKaryawan::where('id', $request->id_pengajuan)->delete();
        return response()->json(['message' => 'Permintaan Tenaga Kerja berhasil dihapus.']);
    }

   public function print_pengajuan_sp_pdf(Request $request)
    {
    $pengajuan_id = $request->route('id');

    // Ambil semua data pengajuan (anggap bisa lebih dari 1)
    $data = DB::select("
        SELECT pk.*,
               ea.employee_name,
               ea.department_name,
               ea.sub_dept_name,
               ea.department_id,
               ea.sub_dept_id,
               ea.nik,
               ea.status_jabatan
        FROM pengajuan_kedisiplinan_karyawan pk
        LEFT JOIN employee_atribut ea ON pk.enroll_id_karyawan_bermasalah = ea.enroll_id
        WHERE pk.id = ?
    ", [$pengajuan_id]);

    // Loop tiap pengajuan dan tambahkan faktor_list
    foreach ($data as &$item) {
        $item->faktor_list = DB::table('pengajuan_kedisiplinan_faktor')
            ->where('pengajuan_kedisiplinan_karyawan_id', $item->id)
            ->select('faktor', 'uraian')
            ->get();
    }

    // Kirim ke view
    $pdf = PDF::loadView('hris/tindakan-kedisiplinan/export_pengajuan_kedisiplinan_pdf', [
        'data' => $data
    ]);

    return $pdf->stream('form-nilai-kinerja.pdf');
    }


    public function print_sp_karyawan(Request $request)
    {
        $pengajuan_id = $request->route('id');
        $data = DB::select("
            SELECT surat_peringatan_karyawan.*, pasal_surat_peringatan.*,employee_atribut.employee_name, employee_atribut.department_name, employee_atribut.sub_dept_name, employee_atribut.department_id, employee_atribut.sub_dept_id, employee_atribut.nik, employee_atribut.status_jabatan
            FROM surat_peringatan_karyawan
            LEFT JOIN employee_atribut
                ON surat_peringatan_karyawan.enroll_id = employee_atribut.enroll_id
            LEFT JOIN pasal_surat_peringatan
                ON surat_peringatan_karyawan.kode_pasal = pasal_surat_peringatan.kode_pasal
            WHERE surat_peringatan_karyawan.id = ?
            LIMIT 1
        ", [$pengajuan_id]);


        $struktur_jabatan = [
            [
                'status_jabatan' => 'OPERATOR',
                'level' => 2
            ],
            [
                'status_jabatan' => 'Administrasi',
                'level' => 2
            ],
            [
                'status_jabatan' => 'STAFF',
                'level' => 3
            ],
            [
                'status_jabatan' => 'LEADER',
                'level' => 3
            ],
            [
                'status_jabatan' => 'SPV',
                'level' => 4
            ],
            [
                'status_jabatan' => 'CHIEF',
                'level' => 5
            ],
            [
                'status_jabatan' => 'ASST. MANAGER',
                'level' => 6
            ],
            [
                'status_jabatan' => 'MANAGER',
                'level' => 7
            ],
            [
                'status_jabatan' => 'GENERAL MANAGER',
                'level' => 8
            ],
        ];

        // Ambil data karyawan (hanya 1 data karena LIMIT 1)
        $karyawan = $data[0];

        // Langkah 1: Cari level dari status_jabatan saat ini
        $current_level = null;
        foreach ($struktur_jabatan as $item) {
            if (strtoupper($item['status_jabatan']) === strtoupper($karyawan->status_jabatan)) {
                $current_level = $item['level'];
                break;
            }
        }

        if (is_null($current_level)) {
            return response()->json(['error' => 'Level jabatan tidak ditemukan.'], 400);
        }

        $target_jabatan = collect($struktur_jabatan)
            ->filter(function ($item) use ($current_level) {
                // Ambil semua level lebih tinggi
                if ($item['level'] >= ($current_level + 1)) {
                    // Jika level 3, hanya LEADER yang diizinkan
                    if ($item['level'] === 3 && strtoupper($item['status_jabatan']) !== 'LEADER') {
                        return false;
                    }
                    return true;
                }
                return false;
            })
            ->sortBy('level')
            ->pluck('status_jabatan')
            ->values()
            ->toArray();



        // Langkah 3: Cari data approval berdasarkan status_jabatan yang ditemukan
        $approval_list = [];
        $used_levels = [];

        foreach ($target_jabatan as $jabatan) {
            // Cari level dari jabatan saat ini
            $jabatan_level = collect($struktur_jabatan)
                ->firstWhere('status_jabatan', $jabatan)['level'] ?? null;

            if (is_null($jabatan_level) || in_array($jabatan_level, $used_levels)) {
                continue; // Skip jika level tidak valid atau sudah digunakan
            }

            // Cari 1 orang dari jabatan dan level ini
            $approver = DB::table('employee_atribut')
                ->select('enroll_id', 'employee_name', 'nik', 'department_name', 'sub_dept_name', 'status_jabatan')
                ->where('department_id', $karyawan->department_id)
                ->where('sub_dept_id', $karyawan->sub_dept_id)
                ->where('status_aktif', 'aktif')
                ->whereRaw('LOWER(status_jabatan) = ?', [strtolower($jabatan)])
                ->orderBy('enroll_id')
                ->first();

            if ($approver) {
                $approval_list[] = $approver;
                $used_levels[] = $jabatan_level;
            }

            if (count($approval_list) >= 2) {
                break;
            }
        }
        // Jika hanya 1 orang dan dia MANAGER, tambahkan GENERAL MANAGER
        if (count($approval_list) === 1 && strtoupper($approval_list[0]->status_jabatan) === 'MANAGER') {
            $gm = DB::table('employee_atribut')
                ->select('enroll_id', 'employee_name', 'nik', 'department_name', 'sub_dept_name', 'status_jabatan')
                ->where('status_aktif', 'aktif')
                ->whereRaw('LOWER(status_jabatan) = ?', ['general manager'])
                ->orderBy('enroll_id')
                ->first();

            if ($gm) {
                $approval_list[] = $gm;
            }
        }
        if (count($approval_list) === 0) {
            $gm = DB::table('employee_atribut')
                ->select('enroll_id', 'employee_name', 'nik', 'department_name', 'sub_dept_name', 'status_jabatan')
                ->where('status_aktif', 'aktif')
                ->whereRaw('LOWER(status_jabatan) = ?', ['general manager'])
                ->orderBy('enroll_id')
                ->first();

            if ($gm) {
                $approval_list[] = $gm;
            }
        }


        $pdf = PDF::loadview('hris/tindakan-kedisiplinan/export_surat_peringatan_pdf',['data'=>$data,'approval_list'=>$approval_list]);
        return $pdf->stream('SP '.$data[0]->enroll_id.' '.$data[0]->employee_name.'.pdf');
    }

    public function print_form_coaching(Request $request)
    {
        $pengajuan_id = $request->route('id');
        $data = DB::select("
            SELECT * FROM pengajuan_coaching_karyawan
            LEFT JOIN employee_atribut ON pengajuan_coaching_karyawan.enroll_id_karyawan_coaching = employee_atribut.enroll_id
            WHERE pengajuan_coaching_karyawan.id = ?
        ", [$pengajuan_id]);

        $struktur_jabatan = [
            [
                'status_jabatan' => 'OPERATOR',
                'level' => 2
            ],
            [
                'status_jabatan' => 'Administrasi',
                'level' => 2
            ],
            [
                'status_jabatan' => 'STAFF',
                'level' => 3
            ],
            [
                'status_jabatan' => 'LEADER',
                'level' => 3
            ],
            [
                'status_jabatan' => 'SPV',
                'level' => 4
            ],
            [
                'status_jabatan' => 'CHIEF',
                'level' => 5
            ],
            [
                'status_jabatan' => 'ASST. MANAGER',
                'level' => 6
            ],
            [
                'status_jabatan' => 'MANAGER',
                'level' => 7
            ],
            [
                'status_jabatan' => 'GENERAL MANAGER',
                'level' => 8
            ],
        ];

        // Ambil data karyawan (hanya 1 data karena LIMIT 1)
        $karyawan = $data[0];

        // Langkah 1: Cari level dari status_jabatan saat ini
        $current_level = null;
        foreach ($struktur_jabatan as $item) {
            if (strtoupper($item['status_jabatan']) === strtoupper($karyawan->status_jabatan)) {
                $current_level = $item['level'];
                break;
            }
        }

        if (is_null($current_level)) {
            return response()->json(['error' => 'Level jabatan tidak ditemukan.'], 400);
        }

        $target_jabatan = collect($struktur_jabatan)
            ->filter(function ($item) use ($current_level) {
                // Ambil semua level lebih tinggi
                if ($item['level'] >= ($current_level + 1)) {
                    // Jika level 3, hanya LEADER yang diizinkan
                    if ($item['level'] === 3 && strtoupper($item['status_jabatan']) !== 'LEADER') {
                        return false;
                    }
                    return true;
                }
                return false;
            })
            ->sortBy('level')
            ->pluck('status_jabatan')
            ->values()
            ->toArray();



        // Langkah 3: Cari data approval berdasarkan status_jabatan yang ditemukan
        $approval_list = [];
        $used_levels = [];

        foreach ($target_jabatan as $jabatan) {
            // Cari level dari jabatan saat ini
            $jabatan_level = collect($struktur_jabatan)
                ->firstWhere('status_jabatan', $jabatan)['level'] ?? null;

            if (is_null($jabatan_level) || in_array($jabatan_level, $used_levels)) {
                continue; // Skip jika level tidak valid atau sudah digunakan
            }


            $approver = DB::table('employee_atribut')
                ->select('enroll_id', 'employee_name', 'nik', 'department_name', 'sub_dept_name', 'status_jabatan')
                ->where('department_id', $karyawan->department_id)
                ->where('sub_dept_id', $karyawan->sub_dept_id)
                ->where('status_aktif', 'aktif')
                ->whereRaw('LOWER(status_jabatan) = ?', [strtolower($jabatan)])
                ->orderBy('enroll_id')
                ->first();

            if ($approver) {
                $approval_list[] = $approver;
                $used_levels[] = $jabatan_level;
            }

            if (count($approval_list) >= 2) {
                break;
            }
        }

         if (count($approval_list) === 1 && strtoupper($approval_list[0]->status_jabatan) === 'MANAGER') {
            $gm = DB::table('employee_atribut')
                ->select('enroll_id', 'employee_name', 'nik', 'department_name', 'sub_dept_name', 'status_jabatan')
                ->where('status_aktif', 'aktif')
                ->whereRaw('LOWER(status_jabatan) = ?', ['general manager'])
                ->orderBy('enroll_id')
                ->first();

            if ($gm) {
                $approval_list[] = $gm;
            }
        }
        if (count($approval_list) === 0) {
            $gm = DB::table('employee_atribut')
                ->select('enroll_id', 'employee_name', 'nik', 'department_name', 'sub_dept_name', 'status_jabatan')
                ->where('status_aktif', 'aktif')
                ->whereRaw('LOWER(status_jabatan) = ?', ['general manager'])
                ->orderBy('enroll_id')
                ->first();

            if ($gm) {
                $approval_list[] = $gm;
            }
        }

        $pdf = PDF::loadview('hris/tindakan-kedisiplinan/export_form_coaching_pdf',['data'=>$data,'approval_list'=>$approval_list]);
        return $pdf->stream('SP '.$data[0]->enroll_id.' '.$data[0]->employee_name.'.pdf');
    }

    public function get_detail_coaching(Request $request)
    {
        $pengajuan_id = $request->id;
        $data = DB::select("
            SELECT * FROM pengajuan_coaching_karyawan
            LEFT JOIN employee_atribut ON pengajuan_coaching_karyawan.enroll_id_karyawan_coaching = employee_atribut.enroll_id
            WHERE pengajuan_coaching_karyawan.id = ?
        ", [$pengajuan_id]);
        return $data[0];
    }

    public function update_form_coaching(Request $request, $id)
    {
        $request->validate([
            'enroll_id_karyawan_coaching' => 'required',
            'deskripsi_coaching' => 'required',
            'no_form_coaching' => 'required',
        ]);

        $coaching = PengajuanCoachingKaryawan::findOrFail($id);
        $coaching->enroll_id_karyawan_coaching = $request->enroll_id_karyawan_coaching;
        $coaching->deskripsi_coaching = $request->deskripsi_coaching;
        $coaching->nomor_form_coaching = $request->no_form_coaching;
        $coaching->save();

        return response()->json(['message' => 'Berhasil diupdate']);
    }

    public function delete_form_coaching(Request $request)
    {

        DB::table('pengajuan_coaching_karyawan')
            ->where('id', $request->id_pengajuan)
            ->delete();

        return response()->json(['message' => 'Berhasil dihapus']);
    }

    public function get_last_no_form(Request $request)
    {

      $no_form = DB::table('surat_peringatan_karyawan')
    ->select('no_form')
    ->orderByRaw('CAST(no_form AS UNSIGNED) DESC')
    ->first();

        return response()->json([
            'message' => 'Berhasil dapat no form',
            'no_form' => (int) $no_form->no_form + 1
        ]);

    }


    public function ajax_data_pengajuan_sp(Request $request)
    {
        $email = Auth::guard('admin')->user()->email;
        $status = $request->input('status_pengajuan');
        $search = $request->input('search.value');

        // Query pertama
        $query  = DB::table('pengajuan_kedisiplinan_karyawan')
            ->select(
                'data_diajukan.employee_name AS data_diajukan_name',
                'data_diajukan.nik AS data_diajukan_nik',
                'data_diajukan.department_name AS data_diajukan_dept_name',
                'data_diajukan.sub_dept_name AS data_diajukan_bagian_name',
                'pengajuan_kedisiplinan_karyawan.*',
                'data_karyawan_bermasalah.employee_name AS data_karyawan_bermasalah_name',
                'data_karyawan_bermasalah.nik AS data_karyawan_bermasalah_nik',
                'data_karyawan_bermasalah.department_name AS data_karyawan_bermasalah_dept_name',
                'data_karyawan_bermasalah.sub_dept_name AS data_karyawan_bermasalah_bagian_name',
                'data_karyawan_bermasalah.status_jabatan AS data_karyawan_bermasalah_status_jabatan',
            )
            ->leftJoin('employee_atribut AS data_diajukan', 'pengajuan_kedisiplinan_karyawan.enroll_id_diajukan_oleh', '=', 'data_diajukan.enroll_id')
            ->leftJoin('employee_atribut AS data_karyawan_bermasalah', 'pengajuan_kedisiplinan_karyawan.enroll_id_karyawan_bermasalah', '=', 'data_karyawan_bermasalah.enroll_id')
            ->where('pengajuan_kedisiplinan_karyawan.status_pengajuan', $status)
            ->distinct();

        if (!in_array($email, ['mega@ptnag.com', 'rudy@ptnag.com', 'fadli', 'ersa@ptnag.com','indri@nag.nirwanaindonesia.com','hadiyoso@nag.nirwanaindonesia.com','ronald@ptnag.com','bobby','pujiprana@nag.nirwanaindonesia.com'])) {
            $query->where(function($q) use ($email) {
                $q->where('pengajuan_kedisiplinan_karyawan.created_by', $email);
            });
        }
        $data = $query->get();
        // Format response untuk DataTables
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
            'data' => $data
        ]);
    }
    public function get_status_sp(Request $request)
    {
        $email = Auth::guard('admin')->user()->email;
        $enroll_id = $request->id;
        $tanggal_berjalan = $request->tanggal_berjalan;

        // Query pertama
        $query  = SuratPeringatanKaryawan::select(
            'surat_peringatan_karyawan.*',
            'employee_atribut.employee_name',
            'employee_atribut.nik',
            'employee_atribut.department_name',
            'employee_atribut.sub_dept_name',
            'employee_atribut.status_jabatan'
        )
        ->leftJoin('employee_atribut', 'surat_peringatan_karyawan.enroll_id', '=', 'employee_atribut.enroll_id')
        ->where('surat_peringatan_karyawan.enroll_id', $enroll_id)
        ->where('surat_peringatan_karyawan.tanggal_mulai', '<=', $tanggal_berjalan)
        ->where('surat_peringatan_karyawan.tanggal_sampai', '>=', $tanggal_berjalan);

        $data = $query ->get();
        // Format response untuk DataTables
        return $data;
    }

    public function get_surat_peringatan(Request $request)
    {
        $email = Auth::guard('admin')->user()->email;
        $search = $request->input('search.value');
        $status_sp = $request->input('surat_peringatan');
        $rentan_posisi = $request->input('rentan_posisi');
        $start = $request->input('start'); // index pertama
        $length = $request->input('length'); // jumlah data per halaman

        $today = now()->format('Y-m-d');

        // Query pertama
        $query  = SuratPeringatanKaryawan::select(
            'surat_peringatan_karyawan.*',
            'employee_atribut.employee_name',
            'employee_atribut.nik',
            'employee_atribut.department_name',
            'employee_atribut.sub_dept_name',
            'employee_atribut.status_jabatan'
        )
        ->leftJoin('employee_atribut', 'surat_peringatan_karyawan.enroll_id', '=', 'employee_atribut.enroll_id')
        ->where(function($q) use ($search) {
            $q->where('employee_atribut.employee_name', 'like', '%' . $search . '%')
              ->orWhere('employee_atribut.nik', 'like', '%' . $search . '%')
              ->orWhere('surat_peringatan_karyawan.surat_peringatan', 'like', '%' . $search . '%')
              ->orWhere('surat_peringatan_karyawan.tanggal_mulai', 'like', '%' . $search . '%')
              ->orWhere('surat_peringatan_karyawan.tanggal_sampai', 'like', '%' . $search . '%');
        })
        ->orderBy('surat_peringatan_karyawan.tanggal_mulai', 'ASC');

        if (!empty($request->daterange1)) {
            $arrperiode = explode(" s/d ", $request->daterange1);
            $first_date = $arrperiode[0];
            $last_date = $arrperiode[1];

           $query->where(function($q) use ($first_date, $last_date) {
                $q->whereDate('surat_peringatan_karyawan.tanggal_mulai', '<=', $last_date)
                ->whereDate('surat_peringatan_karyawan.tanggal_sampai', '>=', $first_date);
            });
        }

         if (!empty($status_sp)) {
            $query->where('surat_peringatan_karyawan.surat_peringatan', $status_sp);
        }
        if (!empty($rentan_posisi)) {
            if ($rentan_posisi == 'dalam_rentan_waktu') {
                // Dalam masa SP (hari ini antara tanggal_mulai dan tanggal_sampai)
                $query->whereDate('surat_peringatan_karyawan.tanggal_mulai', '<=', $today)
                    ->whereDate('surat_peringatan_karyawan.tanggal_sampai', '>=', $today);
            } elseif ($rentan_posisi == 'selesai_rentan_waktu') {
                // Selesai masa SP (tanggal_sampai sudah lewat hari ini)
                $query->whereDate('surat_peringatan_karyawan.tanggal_sampai', '<', $today);
            }
        }



        $start = $request->input('start', 0);
        $length = $request->input('length', 10);

        $totalFiltered = $query->count(); // total setelah filter

        $data = $query->skip($start)->take($length)->get();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalFiltered,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    public function get_karyawan_coaching_list(Request $request)
    {
        $email = Auth::guard('admin')->user()->email;
        $search = $request->input('search.value');
        $start = $request->input('start'); // index pertama
        $length = $request->input('length'); // jumlah data per halaman

        $today = now()->format('Y-m-d');

        // Query pertama
        $query  = PengajuanCoachingKaryawan::select(
            'pengajuan_coaching_karyawan.*',
            'employee_atribut.employee_name',
            'employee_atribut.nik',
            'employee_atribut.department_name',
            'employee_atribut.sub_dept_name',
            'employee_atribut.status_jabatan'
        )
        ->leftJoin('employee_atribut', 'pengajuan_coaching_karyawan.enroll_id_karyawan_coaching', '=', 'employee_atribut.enroll_id')
        ->where(function($q) use ($search) {
            $q->where('employee_atribut.employee_name', 'like', '%' . $search . '%')
              ->orWhere('employee_atribut.nik', 'like', '%' . $search . '%');
        })
        ->orderBy('pengajuan_coaching_karyawan.enroll_id_karyawan_coaching', 'ASC');

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);

        $totalFiltered = $query->count(); // total setelah filter

        $data = $query->skip($start)->take($length)->get();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalFiltered,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    public function export_excel_surat_peringatan(Request $request)
    {
        ini_set('max_execution_time', 0);
        $today = now()->format('Y-m-d');

        $query = SuratPeringatanKaryawan::select(
            'surat_peringatan_karyawan.*',
            'employee_atribut.employee_name',
            'employee_atribut.nik',
            'employee_atribut.department_name',
            'employee_atribut.sub_dept_name',
            'employee_atribut.status_jabatan',
            'pasal_surat_peringatan.deskripsi',
            'pasal_surat_peringatan.desc_surat_peringatan',
            'pasal_surat_peringatan.pasal',
        )
        ->leftJoin('employee_atribut', 'surat_peringatan_karyawan.enroll_id', '=', 'employee_atribut.enroll_id')
        ->leftJoin('pasal_surat_peringatan', 'surat_peringatan_karyawan.kode_pasal', '=', 'pasal_surat_peringatan.kode_pasal')
        ->orderBy('surat_peringatan_karyawan.tanggal_mulai', 'ASC');

        // Filter berdasarkan daterange jika ada
        if (!empty($request->daterange1)) {
            $arrperiode = explode(" s/d ", $request->daterange1);
            $first_date = $arrperiode[0];
            $last_date = $arrperiode[1];

            // $query->whereDate('surat_peringatan_karyawan.tanggal_mulai', '>=', $first_date)
            //       ->whereDate('surat_peringatan_karyawan.tanggal_mulai', '<=', $last_date);
            $query->where(function($q) use ($first_date, $last_date) {
                    $q->whereDate('surat_peringatan_karyawan.tanggal_mulai', '<=', $last_date)
                    ->whereDate('surat_peringatan_karyawan.tanggal_sampai', '>=', $first_date);
                });
        }

        // Filter berdasarkan status_sp jika ada
        if (!empty($request->status_sp)) {
            $query->where('surat_peringatan_karyawan.surat_peringatan', $request->status_sp);
        }
        if (!empty($request->rentan_posisi)) {
                if ($request->rentan_posisi == 'dalam_rentan_waktu') {
                    // Dalam masa SP (hari ini antara tanggal_mulai dan tanggal_sampai)
                    $query->whereDate('surat_peringatan_karyawan.tanggal_mulai', '<=', $today)
                        ->whereDate('surat_peringatan_karyawan.tanggal_sampai', '>=', $today);
                } elseif ($request->rentan_posisi == 'selesai_rentan_waktu') {
                    // Selesai masa SP (tanggal_sampai sudah lewat hari ini)
                    $query->whereDate('surat_peringatan_karyawan.tanggal_sampai', '<', $today);
                }
        }

        $result = $query->get();

        return Excel::download(new exportExcelSuratPeringatan($result), 'Rekap Surat Peringatan.xlsx');
    }
}
