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


class PermintaanTenagaKerjaController extends AdminBaseController
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

         return view('hris/permintaan_tenaga_kerja/permintaan_tenaga_kerja', [
            'page' => 'dashboard-mut-karyawan', "subPageGroup" => "proses-karyawan", "subPage" => "form-lembur-non-sewing",
            "data_dept" => $data_dept, "user" => $user,
            "selectemployee" => $selectemployee,
        ], $this->data);
    }
    public function index_hr()
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

         return view('hris/permintaan_tenaga_kerja/permintaan_tenaga_kerja_hr', [
            'page' => 'dashboard-mut-karyawan', "subPageGroup" => "proses-karyawan", "subPage" => "form-lembur-non-sewing",
            "data_dept" => $data_dept, "user" => $user,
            "selectemployee" => $selectemployee,
        ], $this->data);
    }

    public function ajax_getallemployeeatribut()
    {
        $query =  EmployeeAtribut::selectRaw('enroll_id, nik, employee_name, department_name, sub_dept_name,department_id,sub_dept_id,
                                           concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                                    ->groupby('enroll_id')
                                    ->orderby('employee_name', 'asc')
                                    ->get();
        return $query;

    }

    public function get_detail_permintaan_tk(Request $request)
    {
        $id = $request->id;
        $data = DB::select("
            SELECT
                pengajuan_permintaan_tk.*,
                employee_atribut.employee_name,
                employee_atribut.department_name,
                employee_atribut.sub_dept_name,
                employee_atribut.department_id,
                employee_atribut.sub_dept_id,
                employee_atribut.nik,
                department_all.department_name AS kode_dept_name,
                department_all.department_id AS kode_dept_id,
                department_all2.sub_dept_name AS kode_bagian_name,
                department_all2.sub_dept_id AS kode_bagian_id,
                (
                    SELECT COUNT(*)
                    FROM employee_atribut
                    WHERE employee_atribut.no_fptk = pengajuan_permintaan_tk.no_permintaan
                ) AS jumlah_karyawan
            FROM pengajuan_permintaan_tk
            LEFT JOIN employee_atribut
                ON pengajuan_permintaan_tk.diajukan_oleh_id = employee_atribut.enroll_id
            LEFT JOIN department_all
                ON pengajuan_permintaan_tk.department_kode = department_all.department_id
            LEFT JOIN department_all AS department_all2
                ON pengajuan_permintaan_tk.bagian_kode = department_all2.sub_dept_id
            WHERE pengajuan_permintaan_tk.id = ?
        ", [$id]);
        $karyawan = DB::table('employee_atribut')
        ->select('enroll_id', 'employee_name', 'department_name', 'sub_dept_name')
        ->where('no_fptk', $data[0]->no_permintaan)
        ->get();


    return response()->json([
        'permintaan' => $data[0],
        'karyawan' => $karyawan,
    ]);
    }


    public function create_permintaan_tk(Request $request){
        $logged_admin = Auth::guard('admin')->user();

        $prefix = date('ym'); // hasilnya 2506 (misalnya Juni 2025)

        // Hitung jumlah permintaan yang sudah ada untuk bulan dan tahun ini
        $count = PengajuanPermintaanTk::whereRaw("DATE_FORMAT(created_at, '%y%m') = ?", [$prefix])->count();
        $urut = str_pad($count + 1, 3, '0', STR_PAD_LEFT); // hasil: 001, 002, dst

        $no_permintaan = 'TK' . $prefix . '-' . $urut;
         PengajuanPermintaanTk::create([
            'no_permintaan' => $no_permintaan,
            'tanggal_pengajuan' => $request->tanggal_perizinan,
            'status_permintaan' => $request->status_permintaan,
            'diajukan_oleh_id' => $request->diajukanOlehID,
            'department_kode' => $request->selectDepartment,
            'bagian_kode' => $request->selectBagian,
            'tanggal_kebutuhan' => $request->tanggal_kebutuhan,
            'jumlah_kebutuhan' => $request->jumlah_kebutuhan,
            'rencana_jabatan' => $request->rencana_jabatan,
            'rencana_jurusan' => $request->rencana_jurusan,
            'pend_minimal' => $request->pend_minimal,
            'pengalaman_kerja' => $request->pengalaman_kerja,
            'waktu_pengalaman' => $request->waktu_pengalaman,
            'besaran_gaji' => $request->besaran_gaji,
            'fasilitas' => $request->fasilitas,
            'jangka_waktu_kontrak' => $request->jangka_waktu_kontrak,
            'keterangan_tambahan' => $request->keterangan_tambahan,
            'uraian_tugas' => array_filter($request->uraianTugas),
            'status_pengajuan' => 'waiting_approval',
            'created_by' => $logged_admin->email,
        ]);
        return response()->json(['message' => 'Permintaan Tenaga Kerja berhasil dibuat.']);
    }

    public function update_permintaan_tk(Request $request){
        PengajuanPermintaanTk::where('id', $request->id)->update([
             'tanggal_pengajuan' => $request->tanggal_perizinan,
            'status_permintaan' => $request->status_permintaan,
            'diajukan_oleh_id' => $request->diajukanOlehID,
            'department_kode' => $request->selectDepartment,
            'bagian_kode' => $request->selectBagian,
            'tanggal_kebutuhan' => $request->tanggal_kebutuhan,
            'jumlah_kebutuhan' => $request->jumlah_kebutuhan,
            'rencana_jabatan' => $request->rencana_jabatan,
            'pengalaman_kerja' => $request->pengalaman_kerja,
            'waktu_pengalaman' => $request->waktu_pengalaman,
            'rencana_jurusan' => $request->rencana_jurusan,
            'pend_minimal' => $request->pend_minimal,
            'besaran_gaji' => $request->besaran_gaji,
            'fasilitas' => $request->fasilitas,
            'jangka_waktu_kontrak' => $request->jangka_waktu_kontrak,
            'keterangan_tambahan' => $request->keterangan_tambahan,
            'uraian_tugas' => array_filter($request->uraianTugas),
        ]);
        return response()->json(['message' => 'Permintaan Tenaga Kerja berhasil diupdate.']);
    }

    public function approve_permintaan_tk(Request $request){
        $logged_admin = Auth::guard('admin')->user();
        PengajuanPermintaanTk::where('id', $request->id)->update([
            'status_pengajuan' => 'approved',
            'status_pengajuan_realisasi' => 'pending',
            'verifikator_by' => $logged_admin->email,
        ]);
        return response()->json(['message' => 'Permintaan Tenaga Kerja berhasil di approve.']);
    }
    public function reject_permintaan_tk(Request $request){
        $logged_admin = Auth::guard('admin')->user();
        PengajuanPermintaanTk::where('id', $request->id)->update([
            'status_pengajuan' => 'cancel',
            'verifikator_by' => $logged_admin->email,
        ]);
        return response()->json(['message' => 'Permintaan Tenaga Kerja berhasil ditolak.']);
    }
    public function delete_permintaan_tk(Request $request){
        PengajuanPermintaanTk::where('id', $request->id_pengajuan)->delete();
        return response()->json(['message' => 'Permintaan Tenaga Kerja berhasil dihapus.']);
    }

    public function print_pengajuan_tk_pdf(Request $request)
    {
        $pengajuan_id = $request->route('id');
        $data = DB::select("
            SELECT pengajuan_permintaan_tk.*, employee_atribut.employee_name, employee_atribut.department_name, employee_atribut.sub_dept_name, employee_atribut.department_id, employee_atribut.sub_dept_id, employee_atribut.nik, department_all.department_name kode_dept_name, department_all.department_id kode_dept_id, department_all2.sub_dept_name kode_bagian_name, department_all2.sub_dept_id kode_bagian_id
            FROM pengajuan_permintaan_tk
            LEFT JOIN employee_atribut
                ON pengajuan_permintaan_tk.diajukan_oleh_id = employee_atribut.enroll_id
            LEFT JOIN department_all
            ON pengajuan_permintaan_tk.department_kode = department_all.department_id
            LEFT JOIN department_all as department_all2
            ON pengajuan_permintaan_tk.bagian_kode = department_all2.sub_dept_id
            WHERE pengajuan_permintaan_tk.id = ?
            LIMIT 1
        ", [$pengajuan_id]);
        $pdf = PDF::loadview('hris/permintaan_tenaga_kerja/export_permintaan_tenaga_kerja_pdf',['data'=>$data]);
        return $pdf->stream('form-nilai-kinerja.pdf');
    }
    public function get_employee_fptk(Request $request)
    {
        $enroll_id = $request->enroll_id;
        $data = DB::table('employee_atribut')
            ->where('enroll_id', $enroll_id)
            ->first();
        if($data == null){
                return response()->json(['data' => $data, 'success' => false]);
        }
        return response()->json(['data' => $data, 'success' => true]);
    }


   public function simpan_no_fptk_karyawan(Request $request)
    {
        $karyawan = $request->karyawan;
        $no_fptk = $request->no_fptk;

        if (!$no_fptk) {
            return response()->json(['success' => false, 'message' => 'Data tidak valid.']);
        }
        if (empty($karyawan)) {
            DB::table('employee_atribut')
                ->where('no_fptk', $no_fptk)
                ->update(['no_fptk' => null]);
            return response()->json(['success' => true, 'message' => 'karyawan berhasil dihapus dari FPTK.']);
        }
        $enroll_ids_dikirim = collect($karyawan)->pluck('enroll_id')->toArray();

        DB::table('employee_atribut')
            ->where('no_fptk', $no_fptk)
            ->whereNotIn('enroll_id', $enroll_ids_dikirim)
            ->update(['no_fptk' => null]);
        $invalid_karyawan = [];

        // Langkah 1: Validasi semua dulu
        foreach ($karyawan as $item) {
            $enroll_id = $item['enroll_id'];

            $existing = DB::table('employee_atribut')
                ->where('enroll_id', $enroll_id)
                ->whereNotNull('no_fptk')
                ->where('no_fptk', '!=', $no_fptk)
                ->first();

            if ($existing) {
                $invalid_karyawan[] = [
                    'enroll_id' => $enroll_id,
                    'employee_name' => $existing->employee_name ?? '(tidak diketahui)',
                    'no_fptk_lain' => $existing->no_fptk,
                ];
            }
        }

        // Langkah 2: Jika ada yang invalid, hentikan proses
        if (!empty($invalid_karyawan)) {
            return response()->json([
                'success' => false,
                'message' => 'Beberapa karyawan sudah terdaftar di FPTK lain.',
                'data' => $invalid_karyawan
            ]);
        }

        // Langkah 3: Jika semua valid, lakukan update
        foreach ($karyawan as $item) {
            DB::table('employee_atribut')
                ->where('enroll_id', $item['enroll_id'])
                ->update(['no_fptk' => $no_fptk]);
        }
        // Hitung jumlah karyawan dengan no_fptk untuk proses penyelesaian permintaan
        $count_data = DB::table('employee_atribut')
                ->where('no_fptk', $no_fptk)
                ->count();
        $jumlah_pengajuan = DB::table('pengajuan_permintaan_tk')
                ->where('no_permintaan', $no_fptk)
                ->first();
        if($count_data == $jumlah_pengajuan->jumlah_kebutuhan){
            DB::table('pengajuan_permintaan_tk')
                ->where('no_permintaan', $no_fptk)
                ->update(['status_pengajuan_realisasi' => 'done', 'verifikator_by' => Auth::guard('admin')->user()->email]);
        }
        return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui.']);
    }
   public function simpan_selesai_no_fptk_karyawan(Request $request)
    {
        $karyawan = $request->karyawan;
        $no_fptk = $request->no_fptk;

        if (!$no_fptk) {
            return response()->json(['success' => false, 'message' => 'Data tidak valid.']);
        }
        if (empty($karyawan)) {
            DB::table('employee_atribut')
                ->where('no_fptk', $no_fptk)
                ->update(['no_fptk' => null]);
            return response()->json(['success' => true, 'message' => 'karyawan berhasil dihapus dari FPTK.']);
        }
        $enroll_ids_dikirim = collect($karyawan)->pluck('enroll_id')->toArray();

        DB::table('employee_atribut')
            ->where('no_fptk', $no_fptk)
            ->whereNotIn('enroll_id', $enroll_ids_dikirim)
            ->update(['no_fptk' => null]);
        $invalid_karyawan = [];

        // Langkah 1: Validasi semua dulu
        foreach ($karyawan as $item) {
            $enroll_id = $item['enroll_id'];

            $existing = DB::table('employee_atribut')
                ->where('enroll_id', $enroll_id)
                ->whereNotNull('no_fptk')
                ->where('no_fptk', '!=', $no_fptk)
                ->first();

            if ($existing) {
                $invalid_karyawan[] = [
                    'enroll_id' => $enroll_id,
                    'employee_name' => $existing->employee_name ?? '(tidak diketahui)',
                    'no_fptk_lain' => $existing->no_fptk,
                ];
            }
        }

        // Langkah 2: Jika ada yang invalid, hentikan proses
        if (!empty($invalid_karyawan)) {
            return response()->json([
                'success' => false,
                'message' => 'Beberapa karyawan sudah terdaftar di FPTK lain.',
                'data' => $invalid_karyawan
            ]);
        }

        // Langkah 3: Jika semua valid, lakukan update
        foreach ($karyawan as $item) {
            DB::table('employee_atribut')
                ->where('enroll_id', $item['enroll_id'])
                ->update(['no_fptk' => $no_fptk]);
        }
        // Selesaikan proses penyelesaian permintaan
        DB::table('pengajuan_permintaan_tk')
            ->where('no_permintaan', $no_fptk)
            ->update(['status_pengajuan_realisasi' => 'done', 'verifikator_by' => Auth::guard('admin')->user()->email]);
        return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui.']);
    }

    public function set_to_pending_no_fptk_karyawan(Request $request){
        $no_fptk = $request->no_fptk;

        if (!$no_fptk) {
            return response()->json(['success' => false, 'message' => 'Data tidak valid.']);
        }
        DB::table('pengajuan_permintaan_tk')
            ->where('no_permintaan', $no_fptk)
            ->update(['status_pengajuan_realisasi' => 'pending', 'verifikator_by' => Auth::guard('admin')->user()->email]);
        return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui.']);
    }


    public function ajax_data_permintaan_tk(Request $request)
    {
        $email = Auth::guard('admin')->user()->email;
        $status = $request->input('status_pengajuan');
        $search = $request->input('search.value');

        // Query pertama
        $query  = DB::table('pengajuan_permintaan_tk')
            ->select(
                'employee_atribut.employee_name',
                'employee_atribut.nik',
                'pengajuan_permintaan_tk.*',
                'department_all.department_name',
                'department_all2.sub_dept_name',
                 DB::raw('(SELECT COUNT(*) FROM employee_atribut WHERE employee_atribut.no_fptk = pengajuan_permintaan_tk.no_permintaan) AS jumlah_karyawan')
            )
            ->leftJoin('employee_atribut', 'pengajuan_permintaan_tk.diajukan_oleh_id', '=', 'employee_atribut.enroll_id')
            ->leftJoin('department_all', 'pengajuan_permintaan_tk.department_kode', '=', 'department_all.department_id')
            ->leftJoin('department_all as department_all2', 'pengajuan_permintaan_tk.bagian_kode', '=', 'department_all2.sub_dept_id')
            ->where('pengajuan_permintaan_tk.status_pengajuan', $status)
            ->orderBy('pengajuan_permintaan_tk.created_at', 'asc')
            ->distinct();
        if (!in_array($email, ['mega@ptnag.com', 'rudy@ptnag.com', 'fadli', 'ersa@ptnag.com','indri@nag.nirwanaindonesia.com','hadiyoso@nag.nirwanaindonesia.com','ronald@ptnag.com','bobby','pujiprana@nag.nirwanaindonesia.com'])) {
            $query ->where(function($q) use ($email) {
                $q->where('pengajuan_permintaan_tk.created_by', $email);
            });
        }
        $data = $query ->get();
        // Format response untuk DataTables
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
            'data' => $data
        ]);
    }



}
