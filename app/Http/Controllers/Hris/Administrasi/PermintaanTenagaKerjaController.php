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
use App\Models\SubPengajuanPermintaanTk;
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
use App\Models\Notification;


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
            (
                SELECT COUNT(*)
                FROM employee_atribut
                WHERE employee_atribut.no_fptk = pengajuan_permintaan_tk.no_permintaan
            ) AS jumlah_karyawan
        FROM pengajuan_permintaan_tk
        LEFT JOIN employee_atribut ON pengajuan_permintaan_tk.diajukan_oleh_id = employee_atribut.enroll_id
        WHERE pengajuan_permintaan_tk.id = ?
    ", [$id]);


   $kualifikasi = DB::table('sub_pengajuan_permintaan_tk')
    ->leftJoin('department_all', 'sub_pengajuan_permintaan_tk.department_kode', '=', 'department_all.department_id')
    ->leftJoin('department_all as department_all2', 'sub_pengajuan_permintaan_tk.bagian_kode', '=', 'department_all2.sub_dept_id')
    ->where('sub_pengajuan_permintaan_tk.no_permintaan_id', $data[0]->no_permintaan)
    ->select(
        'sub_pengajuan_permintaan_tk.*',
        'department_all.department_name as department_name',
        'department_all2.sub_dept_name as bagian_name'
    )
    ->distinct()
    ->get();
    // $karyawan = DB::table('employee_atribut')
    //     ->where('no_fptk', $data[0]->no_permintaan)
    //     ->select('enroll_id', 'employee_name', 'nik')
    //     ->get();

    $karyawan = DB::table('sub_pengajuan_permintaan_tk')
    ->leftJoin('employee_atribut', 'sub_pengajuan_permintaan_tk.no_permintaan_id', '=', 'employee_atribut.no_fptk')
    ->select(
        'sub_pengajuan_permintaan_tk.id as kualifikasi_id',
        'employee_atribut.enroll_id',
        'employee_atribut.employee_name',
        'employee_atribut.nik',
        'employee_atribut.sub_dept_name',
        'employee_atribut.department_name'
    )
    ->where('sub_pengajuan_permintaan_tk.no_permintaan_id', $data[0]->no_permintaan)
    ->get();



    return response()->json([
        'permintaan' => $data[0],
        'karyawan' => $karyawan,
        'kualifikasi' => $kualifikasi,
    ]);
}



   public function create_permintaan_tk(Request $request){
    $logged_admin = Auth::guard('admin')->user();
    $prefix = date('ym');
    $last = PengajuanPermintaanTk::where('no_permintaan', 'LIKE', 'TK' . $prefix . '-%')
    ->orderByDesc('no_permintaan')
    ->first();

    if ($last) {
        $lastUrut = intval(substr($last->no_permintaan, -3)); // Ambil 3 digit terakhir
        $urut = str_pad($lastUrut + 1, 3, '0', STR_PAD_LEFT);
    } else {
        $urut = '001';
    }

    $no_permintaan = 'TK' . $prefix . '-' . $urut;
    // Simpan ke table utama
    $permintaan = PengajuanPermintaanTk::create([
        'no_permintaan' => $no_permintaan,
        'tanggal_pengajuan' => $request->tanggal_perizinan,
        'status_permintaan' => $request->status_permintaan,
        'diajukan_oleh_id' => $request->diajukanOlehID,
        'status_pengajuan' => 'waiting_approval',
        'created_by' => $logged_admin->email,
    ]);
    // Simpan ke sub table untuk setiap kualifikasi
    foreach ($request->kualifikasi as $item) {
        SubPengajuanPermintaanTk::create([
            'no_permintaan_id'    => $no_permintaan,
            'department_kode'     => $item['selectDepartment'],
            'bagian_kode'         => $item['selectBagian'],
            'tanggal_kebutuhan'   => $item['tanggal_kebutuhan'],
            'jumlah_kebutuhan'    => $item['jumlah_kebutuhan'],
            'rencana_jabatan'     => $item['rencana_jabatan'],
            'rencana_jurusan'     => $item['rencana_jurusan'],
            'pend_minimal'        => $item['pend_minimal'],
            'pengalaman_kerja'    => $item['pengalaman_kerja'],
            'waktu_pengalaman'    => $item['waktu_pengalaman'],
            'besaran_gaji'        => $item['besaran_gaji'],
            'fasilitas'           => $item['fasilitas'],
            'jangka_waktu_kontrak'=> $item['jangka_waktu_kontrak'],
            'keterangan_tambahan' => $item['keterangan_tambahan'],
            'uraian_tugas'        => array_filter($item['uraianTugas']),
        ]);
    }

    $receiverEmails = ['fadli', 'mega@ptnag.com', 'ersa@ptnag.com', 'rudy@ptnag.com', 'hrd','hadiyoso@nag.nirwanaindonesia.com','ramon', 'ronald@ptnag.com', 'bobby', 'pujiprana@nag.nirwanaindonesia.com' ,'indri@nag.nirwanaindonesia.com'];
    foreach ($receiverEmails as $receiverEmail) {
        Notification::create([
            'sender_email' => $logged_admin->email,
            'receiver_email' => $receiverEmail,
            'type' => 'FPTK',
            'href_menu' => 'http://10.10.5.111/hris/public/index.php/hris/permintaan_tenaga_kerja_hr/index',
            // 'href_menu' => 'http://localhost/hris/public/index.php/hris/permintaan_tenaga_kerja_hr/index',
            'message' => 'FPTK ' . $no_permintaan . ' baru saja dibuat.',
            'enroll_ids' => $no_permintaan,
            'is_read' => false,
            'is_delete' => false,
            'status_staff' => 'FPTK',
        ]);
    }

    return response()->json(['message' => 'Permintaan Tenaga Kerja berhasil dibuat.']);
}

    public function update_permintaan_tk(Request $request){
         PengajuanPermintaanTk::where('id', $request->id)->update([
            'status_permintaan' => $request->status_permintaan,
            'diajukan_oleh_id' => $request->diajukanOlehID,
        ]);
         foreach ($request->kualifikasi as $item) {
            SubPengajuanPermintaanTk::where('id', $item['id_kualifikasi'])->update([
                'department_kode'     => $item['selectDepartment'],
                'bagian_kode'         => $item['selectBagian'],
                'tanggal_kebutuhan'   => $item['tanggal_kebutuhan'],
                'jumlah_kebutuhan'    => $item['jumlah_kebutuhan'],
                'rencana_jabatan'     => $item['rencana_jabatan'],
                'rencana_jurusan'     => $item['rencana_jurusan'],
                'pend_minimal'        => $item['pend_minimal'],
                'pengalaman_kerja'    => $item['pengalaman_kerja'],
                'waktu_pengalaman'    => $item['waktu_pengalaman'],
                'besaran_gaji'        => $item['besaran_gaji'],
                'fasilitas'           => $item['fasilitas'],
                'jangka_waktu_kontrak'=> $item['jangka_waktu_kontrak'],
                'keterangan_tambahan' => $item['keterangan_tambahan'],
                'uraian_tugas'        => array_filter($item['uraianTugas']),
            ]);
        }
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
        $data_pengajuan = PengajuanPermintaanTk::find($request->id_pengajuan);
        if($data_pengajuan){
            EmployeeAtribut::where('no_fptk', $data_pengajuan->no_permintaan)->update(['no_fptk' => null]);
            PengajuanPermintaanTk::where('id', $request->id_pengajuan)->delete();
            SubPengajuanPermintaanTk::where('permintaan_tk_id', $request->no_permintaan)->delete();
        }
        return response()->json(['message' => 'Permintaan Tenaga Kerja berhasil dihapus.']);
    }

    public function print_pengajuan_tk_pdf(Request $request)
    {
        $pengajuan_id = $request->route('id');
        $data = DB::select("
            SELECT
                pengajuan_permintaan_tk.*,
                employee_atribut.employee_name,
                employee_atribut.department_name,
                employee_atribut.sub_dept_name,
                employee_atribut.department_id,
                employee_atribut.sub_dept_id,
                employee_atribut.nik,
                (
                    SELECT COUNT(*)
                    FROM employee_atribut
                    WHERE employee_atribut.no_fptk = pengajuan_permintaan_tk.no_permintaan
                ) AS jumlah_karyawan
            FROM pengajuan_permintaan_tk
            LEFT JOIN employee_atribut ON pengajuan_permintaan_tk.diajukan_oleh_id = employee_atribut.enroll_id
            WHERE pengajuan_permintaan_tk.id = ?
        ", [$pengajuan_id]);

        $kualifikasi = DB::table('sub_pengajuan_permintaan_tk')
            ->leftJoin('department_all', 'sub_pengajuan_permintaan_tk.department_kode', '=', 'department_all.department_id')
            ->leftJoin('department_all as department_all2', 'sub_pengajuan_permintaan_tk.bagian_kode', '=', 'department_all2.sub_dept_id')
            ->where('sub_pengajuan_permintaan_tk.no_permintaan_id', $data[0]->no_permintaan)
            ->select(
                'sub_pengajuan_permintaan_tk.*',
                'department_all.department_name as kode_dept_name',
                'department_all2.sub_dept_name as kode_bagian_name'
            )
            ->distinct()
            ->get();

        foreach ($kualifikasi as $item) {
            $item->department_name = $data[0]->department_name ?? null;
            $item->diajukan_oleh = $data[0]->employee_name ?? null;
            $item->jumlah_karyawan = $data[0]->jumlah_karyawan ?? 0;
            $item->no_permintaan = $data[0]->no_permintaan ?? null;
            $item->status_permintaan = $data[0]->status_permintaan ?? null;
            $item->tanggal_pengajuan = $data[0]->tanggal_pengajuan ?? null;
            $item->employee_name = $data[0]->employee_name ?? null;
            $item->nik = $data[0]->nik ?? null;
            $item->sub_dept_name = $data[0]->sub_dept_name ?? null;
            $item->status_pengajuan_realisasi = $data[0]->status_pengajuan_realisasi ?? null;
            $item->status_pengajuan = $data[0]->status_pengajuan ?? null;

        }
        $pdf = PDF::loadview('hris/permintaan_tenaga_kerja/export_permintaan_tenaga_kerja_pdf',['data'=>$kualifikasi]);
        $date_bulan = date('ym');
        return $pdf->stream(' FPTK '.$date_bulan.' '.$data[0]->sub_dept_name.' .pdf');
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
        $jumlah_pengajuan = DB::table('sub_pengajuan_permintaan_tk')
        ->where('no_permintaan_id', $no_fptk)
        ->sum('jumlah_kebutuhan');
        if($count_data == $jumlah_pengajuan){
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
    public function move_to_pending_permintaan(Request $request){
        $no_fptk = $request->no_fptk;
        if (!$no_fptk) {
            return response()->json(['success' => false, 'message' => 'Data tidak valid.']);
        }
        DB::table('pengajuan_permintaan_tk')
            ->where('no_permintaan', $no_fptk)
            ->update(['status_pengajuan_realisasi' => null, 'verifikator_by' => Auth::guard('admin')->user()->email, 'status_pengajuan' => 'waiting_approval']);
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
                DB::raw('(SELECT SUM(jumlah_kebutuhan) FROM sub_pengajuan_permintaan_tk WHERE sub_pengajuan_permintaan_tk.no_permintaan_id = pengajuan_permintaan_tk.no_permintaan) AS jumlah_kebutuhan'),
                DB::raw('(SELECT MIN(tanggal_kebutuhan) FROM sub_pengajuan_permintaan_tk WHERE sub_pengajuan_permintaan_tk.no_permintaan_id = pengajuan_permintaan_tk.no_permintaan) AS tanggal_kebutuhan'),
                DB::raw('(SELECT department_kode FROM sub_pengajuan_permintaan_tk WHERE sub_pengajuan_permintaan_tk.no_permintaan_id = pengajuan_permintaan_tk.no_permintaan LIMIT 1) AS department_kode_sub'),
                DB::raw('(SELECT bagian_kode FROM sub_pengajuan_permintaan_tk WHERE sub_pengajuan_permintaan_tk.no_permintaan_id = pengajuan_permintaan_tk.no_permintaan LIMIT 1) AS bagian_kode_sub'),
                DB::raw('(SELECT COUNT(*) FROM employee_atribut WHERE employee_atribut.no_fptk = pengajuan_permintaan_tk.no_permintaan) AS jumlah_karyawan')
            )
            ->leftJoin('employee_atribut', 'pengajuan_permintaan_tk.diajukan_oleh_id', '=', 'employee_atribut.enroll_id')
            ->leftJoin('department_all', function($join) {
                $join->on(DB::raw('(SELECT department_kode FROM sub_pengajuan_permintaan_tk WHERE sub_pengajuan_permintaan_tk.no_permintaan_id = pengajuan_permintaan_tk.no_permintaan LIMIT 1)'), '=', 'department_all.department_id');
            })
            ->leftJoin('department_all as department_all2', function($join) {
                $join->on(DB::raw('(SELECT bagian_kode FROM sub_pengajuan_permintaan_tk WHERE sub_pengajuan_permintaan_tk.no_permintaan_id = pengajuan_permintaan_tk.no_permintaan LIMIT 1)'), '=', 'department_all2.sub_dept_id');
            })
            ->where('pengajuan_permintaan_tk.status_pengajuan', $status)
            ->orderBy('pengajuan_permintaan_tk.created_at', 'asc')->distinct();


        if (!in_array($email, [
            'mega@ptnag.com',
            'rudy@ptnag.com',
            'fadli',
            'ersa@ptnag.com',
            'indri@nag.nirwanaindonesia.com',
            'hadiyoso@nag.nirwanaindonesia.com',
            'ronald@ptnag.com',
            'bobby',
            'pujiprana@nag.nirwanaindonesia.com'
        ])) {
            $query->where('pengajuan_permintaan_tk.created_by', $email);
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



}
