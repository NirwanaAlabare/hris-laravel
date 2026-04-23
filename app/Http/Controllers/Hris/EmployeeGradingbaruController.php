<?php

namespace App\Http\Controllers\Hris;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\View;
use DB;
use PDF;
use App\Exports\GradingHistoryExport;
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
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Yajra\DataTables\Facades\DataTables;
use App\models\GradingSalary;
use App\models\Gradinghistory;
use App\Models\RekapPerhitunganPayroll;
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



class EmployeeGradingbaruController extends AdminBaseController
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
        $grading = GradingSalary::where('periode_umk', '2026-01')
        ->orderBy('kode_grade', 'asc')
        ->get();

        $this->grading = $grading;
        $this->department =  $DepartmentAllModel;
        $this->site =  $NirwananameAllModel;
        $selectemployee = $this->ajax_getallemployeeatribut();
        $periode_payroll = $this->ajax_getperiode();
        // dd($periode_payroll);
        // dd($selectemployee);

         return view('hris/employeegradingbaru', [
            'page' => 'dashboard-mut-karyawan', "subPageGroup" => "proses-karyawan", "subPage" => "form-lembur-non-sewing",
            "data_dept" => $data_dept, "user" => $user,
            "selectemployee" => $selectemployee,
            "periode_payroll" => $periode_payroll,
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
        $grading = GradingSalary::where('periode_umk', '2026-01')
        ->get();


        $this->grading = $grading;
        $this->department =  $DepartmentAllModel;
        $this->site =  $NirwananameAllModel;
        $selectemployee = $this->ajax_getallemployeeatribut();
        $periode_payroll = $this->ajax_getperiode();
        // dd($selectemployee);

         return view('hris/employeegradingbaru', [
            'page' => 'dashboard-mut-karyawan', "subPageGroup" => "proses-karyawan", "subPage" => "form-lembur-non-sewing",
            "data_dept" => $data_dept, "user" => $user,
            "selectemployee" => $selectemployee,
        ], $this->data);
    }

    public function ajax_getallemployeeatribut()
    {
        $query =  EmployeeAtribut::selectRaw('enroll_id,kode_grade, nik, employee_name, department_name, sub_dept_name,department_id,sub_dept_id,
                                           concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                                    ->groupby('enroll_id')
                                    ->orderby('employee_name', 'asc')
                                    ->get();
        return $query;

    }
    public function ajax_getperiode ()
    {
        $query = Rekapperhitunganpayroll::select('periode_kehadiran')
        ->groupby('periode_kehadiran')
        ->orderby('periode_kehadiran', 'desc')
        ->get();
        //  dd($query->toSql());
        return $query;
    }

   public function get_detail_grading(Request $request)
{
    $id = $request->id;

    $data = DB::table('grading_history as a')
        ->select(
            'a.*',
            'b.employee_name',
            'b.department_name',
            'b.sub_dept_name',
            'b.department_id',
            'b.sub_dept_id',
            'b.nik'
        )
        ->join('employee_atribut as b', 'a.enroll_id', '=', 'b.enroll_id')
        ->where('a.id_grade', $id)
        ->first();

    return response()->json([
        'permintaan' => $data
    ]);
}
// use Maatwebsite\Excel\Facades\Excel;
// use Illuminate\Http\Request;

public function inport(Request $request)
{
    $periode1 = $request->periode;

    if (!$request->hasFile('file')) {
        return response()->json([
            'message' => 'File tidak ditemukan!'
        ], 400);
    }

    $file = $request->file('file');
    $data = Excel::toArray([], $file);
    $rows = $data[0];

    $success = 0;
    $duplicate = 0;

    foreach ($rows as $index => $row) {

        if ($index == 0) continue; // skip header

        $enroll_id = $row[0];
        $kode_grade_lama = $row[3];
        $kode_grade_baru = $row[4];

        // Cek duplikasi
        $exists = Gradinghistory::where('enroll_id', $enroll_id)
            ->where('periode_payroll', $periode1)
            ->exists();

        if ($exists) {
            $duplicate++;
            continue;
        }

        Gradinghistory::create([
            'tanggal_pengajuan' => now(),
            'enroll_id' => $enroll_id,
            'kode_grade_lama' => $kode_grade_lama,
            'kode_grade_baru' => $kode_grade_baru,
            'periode_payroll' => $periode1,
            'operator' => auth()->guard('admin')->user()->email,
            'verifikasi' => 1
        ]);
        EmployeeAtribut::where('enroll_id', $enroll_id)
            ->update([
                'kode_grade' => $kode_grade_baru,
                'operator' => auth()->guard('admin')->user()->email,
                'updated_at' => now()
            ]);

        $success++;
    }

    if ($success == 0 && $duplicate > 0) {
    return response()->json([
        'message' => "Semua data sudah ada! Tidak ada yang diimport."
    ], 400);
}

return response()->json([
    'message' => "Import selesai. Berhasil: $success, Duplikat: $duplicate"
]);
}
  public function create_grading(Request $request)
{
    $logged_admin = Auth::guard('admin')->user();

    // Cek apakah sudah ada pengajuan dengan enroll_id & periode yang sama
    $existing = Gradinghistory::where('enroll_id', $request->enroll_id)
        ->where('periode_payroll', $request->periode)
        ->first();
// dd($existing);
    if ($existing) {
        return response()->json([
            'message' => 'Pengajuan untuk enroll dan periode tersebut sudah ada!'
        ], 400);
    }

    // Jika belum ada, lanjut insert
    $pengajuan = Gradinghistory::create([
        'tanggal_pengajuan' => $request->tanggal_pengajuan,
        'enroll_id' => $request->enroll_id,
        'kode_grade_lama' => $request->kode_grade_lama,
        'kode_grade_baru' => $request->kode_grade_baru,
        'periode_payroll' => $request->periode,
        'operator' => $logged_admin->email,
        'verifikasi' => 0
    ]);

    return response()->json([
        'message' => 'Pengajuan berhasil dibuat',
        'data' => $pengajuan
    ], 201);
}
    //   public function approve_grading(Request $request){
    //     $logged_admin = Auth::guard('admin')->user();
    //     Gradinghistory::where('id', $request->id)->update([
    //         'verifikasi' => '1',
    //         'operator' => $logged_admin->email,
    //     ]);
    //     return response()->json(['message' => 'Permintaan Tenaga Kerja berhasil di approve.']);
    // }
    public function approve_grading(Request $request)
{
    // dd($request->all());
    $logged_admin = Auth::guard('admin')->user();

    // Ambil data grading history
    $grading = Gradinghistory::where('id_grade', $request->id)->first();

    if (!$grading) {
        return response()->json([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    // Update status approve di grading_history
    Gradinghistory::where('id_grade', $request->id)->update([
        'verifikasi' => '1',
        'operator' => $logged_admin->email,
    ]);

    // Update kode grade di employee_atribut
    DB::table('employee_atribut')
        ->where('enroll_id', $grading->enroll_id)
        ->update([
            'kode_grade' => $grading->kode_grade_baru,
            'operator' => $logged_admin->email,
            'updated_at' => now(),
        ]);

    return response()->json([
        'message' => 'Permintaan Grading berhasil di approve.'
    ]);
}
 public function delete_pengajuan_grading(Request $request){

        // dd($request->all());
        $logged_admin = Auth::guard('admin')->user();
//         dd(
//     $request->id,
//     Gradinghistory::where('id_grade', $request->id)->toSql(),
//     Gradinghistory::where('id_grade', $request->id)->get()
// );
        $grading = Gradinghistory::where('id_grade', $request->id)->first();
        //  dd($grading);
        $enroll_id = $grading->enroll_id;
        $kode_grade_lama = $grading->kode_grade_lama;
    // dd($grading);
        if($grading){
             EmployeeAtribut::where('enroll_id', $enroll_id)
            ->update([
                'kode_grade' => $kode_grade_lama,
                'operator' => auth()->guard('admin')->user()->email,
                'updated_at' => now()
            ]);
            ActivityLog::create([
                'action_by_id' => $logged_admin->id,
                'action_by_name' => $logged_admin->name,
                'log_name'=> 'Hapus Permintaan Grading',
                'table_name' => 'grading_history',
                'record_id' => $enroll_id,
                'action' => 'delete',
                'old_data'=> json_encode($grading),
            ]);
            Gradinghistory::where('id_grade', $request->id)->delete();

        }
        return response()->json(['message' => 'Permintaan Tenaga Kerja berhasil dihapus.']);
    }

    public function export(Request $request){
        // dd($request->all());
        $periode = $request->periode;
        $verif = $request->verifikasi;
        // dd(request()->fullUrl());
        // pisahin tanggal
        [$start, $end] = explode(' s/d ', $periode);

        // format ke Indonesia
        $startFormat = Carbon::parse($start)->translatedFormat('d F Y');
        $endFormat   = Carbon::parse($end)->translatedFormat('d F Y');

        // gabung lagi
        $periodeFormatted = $startFormat . ' s/d ' . $endFormat;

        $data = Gradinghistory::select('b.enroll_id','b.nik','b.employee_name','grading_history.kode_grade_lama','grading_history.kode_grade_baru','grading_history.periode_payroll')
        ->join('employee_atribut as b', 'grading_history.enroll_id', '=', 'b.enroll_id')
        ->where('periode_payroll', $periode)
        ->where('verifikasi', $verif)
        ->get();
        // dd($data->toSql(), $periode);
        return Excel::download(new GradingHistoryExport($data, $periodeFormatted), 'grading_history.xlsx');
    }
  public function ajax_gradinghistory(Request $request)
{
    // dd($request->all());
    $search = $request->input('search.value');
    $periode = $request->input('periode');
    $verif = $request->input('verifikasi');
    // dd($verif );

    $baseQuery = DB::table('grading_history as a')
        ->select(
            'a.*',
            'b.employee_name',
            'b.nik'

        )
        ->join('employee_atribut as b', 'a.enroll_id', '=', 'b.enroll_id')
        ->where('a.periode_payroll', $periode)
        ->where('a.verifikasi', $verif);
        // dd($baseQuery->toSql(), $baseQuery->get());

    // 🔎 search
    if (!empty($search)) {
        $baseQuery->where(function ($q) use ($search) {
            $q->where('b.employee_name', 'like', "%{$search}%")
              ->orWhere('b.nik', 'like', "%{$search}%")
              ->orWhere('a.enroll_id', 'like', "%{$search}%")
              ->orWhere('a.periode_payroll', 'like', "%{$search}%");
        });
    }

    // total data
    $recordsTotal = DB::table('grading_history')
        ->where('periode_payroll',$periode)
        ->count();

    // total setelah filter
    $recordsFiltered = DB::table(DB::raw("({$baseQuery->toSql()}) as sub"))
        ->mergeBindings($baseQuery)
        ->count();

    // pagination
    $start  = $request->input('start', 0);
    $length = $request->input('length', 10);

    $data = (clone $baseQuery)
        ->orderBy('a.created_at', 'desc')
        ->skip($start)
        ->take($length)
        ->get();

    return response()->json([
        'draw' => intval($request->input('draw')),
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data' => $data
    ]);
}


    // public function update_permintaan_tk(Request $request){
    //      PengajuanPermintaanTk::where('id', $request->id)->update([
    //         'status_permintaan' => $request->status_permintaan,
    //         'diajukan_oleh_id' => $request->diajukanOlehID,
    //     ]);
    //      foreach ($request->kualifikasi as $item) {
    //         SubPengajuanPermintaanTk::where('id', $item['id_kualifikasi'])->update([
    //             'department_kode'     => $item['selectDepartment'],
    //             'bagian_kode'         => $item['selectBagian'],
    //             'tanggal_kebutuhan'   => $item['tanggal_kebutuhan'],
    //             'jumlah_kebutuhan'    => $item['jumlah_kebutuhan'],
    //             'rencana_jabatan'     => $item['rencana_jabatan'],
    //             'rencana_jurusan'     => $item['rencana_jurusan'],
    //             'pend_minimal'        => $item['pend_minimal'],
    //             'pengalaman_kerja'    => $item['pengalaman_kerja'],
    //             'waktu_pengalaman'    => $item['waktu_pengalaman'],
    //             'besaran_gaji'        => $item['besaran_gaji'],
    //             'fasilitas'           => $item['fasilitas'],
    //             'jangka_waktu_kontrak'=> $item['jangka_waktu_kontrak'],
    //             'keterangan_tambahan' => $item['keterangan_tambahan'],
    //             'uraian_tugas'        => array_filter($item['uraianTugas']),
    //         ]);
    //     }
    //     return response()->json(['message' => 'Permintaan Tenaga Kerja berhasil diupdate.']);
    // }

    // public function approve_permintaan_tk(Request $request){
    //     $logged_admin = Auth::guard('admin')->user();
    //     PengajuanPermintaanTk::where('id', $request->id)->update([
    //         'status_pengajuan' => 'approved',
    //         'status_pengajuan_realisasi' => 'pending',
    //         'verifikator_by' => $logged_admin->email,
    //     ]);
    //     return response()->json(['message' => 'Permintaan Tenaga Kerja berhasil di approve.']);
    // }
    // public function reject_permintaan_tk(Request $request){
    //     $logged_admin = Auth::guard('admin')->user();
    //     PengajuanPermintaanTk::where('id', $request->id)->update([
    //         'status_pengajuan' => 'cancel',
    //         'verifikator_by' => $logged_admin->email,
    //     ]);
    //     return response()->json(['message' => 'Permintaan Tenaga Kerja berhasil ditolak.']);
    // }


    // public function print_pengajuan_tk_pdf(Request $request)
    // {
    //     $pengajuan_id = $request->route('id');
    //     $data = DB::select("
    //         SELECT
    //             pengajuan_permintaan_tk.*,
    //             employee_atribut.employee_name,
    //             employee_atribut.department_name,
    //             employee_atribut.sub_dept_name,
    //             employee_atribut.department_id,
    //             employee_atribut.sub_dept_id,
    //             employee_atribut.nik,
    //             (
    //                 SELECT COUNT(*)
    //                 FROM employee_atribut
    //                 WHERE employee_atribut.no_fptk = pengajuan_permintaan_tk.no_permintaan
    //             ) AS jumlah_karyawan
    //         FROM pengajuan_permintaan_tk
    //         LEFT JOIN employee_atribut ON pengajuan_permintaan_tk.diajukan_oleh_id = employee_atribut.enroll_id
    //         WHERE pengajuan_permintaan_tk.id = ?
    //     ", [$pengajuan_id]);

    //     $kualifikasi = DB::table('sub_pengajuan_permintaan_tk')
    //         ->leftJoin('department_all', 'sub_pengajuan_permintaan_tk.department_kode', '=', 'department_all.department_id')
    //         ->leftJoin('department_all as department_all2', 'sub_pengajuan_permintaan_tk.bagian_kode', '=', 'department_all2.sub_dept_id')
    //         ->where('sub_pengajuan_permintaan_tk.no_permintaan_id', $data[0]->no_permintaan)
    //         ->select(
    //             'sub_pengajuan_permintaan_tk.*',
    //             'department_all.department_name as kode_dept_name',
    //             'department_all2.sub_dept_name as kode_bagian_name'
    //         )
    //         ->distinct()
    //         ->get();

    //     foreach ($kualifikasi as $item) {
    //         $item->department_name = $data[0]->department_name ?? null;
    //         $item->diajukan_oleh = $data[0]->employee_name ?? null;
    //         $item->jumlah_karyawan = $data[0]->jumlah_karyawan ?? 0;
    //         $item->no_permintaan = $data[0]->no_permintaan ?? null;
    //         $item->status_permintaan = $data[0]->status_permintaan ?? null;
    //         $item->tanggal_pengajuan = $data[0]->tanggal_pengajuan ?? null;
    //         $item->employee_name = $data[0]->employee_name ?? null;
    //         $item->nik = $data[0]->nik ?? null;
    //         $item->sub_dept_name = $data[0]->sub_dept_name ?? null;
    //         $item->status_pengajuan_realisasi = $data[0]->status_pengajuan_realisasi ?? null;
    //         $item->status_pengajuan = $data[0]->status_pengajuan ?? null;

    //     }
    //     $pdf = PDF::loadview('hris/permintaan_tenaga_kerja/export_permintaan_tenaga_kerja_pdf',['data'=>$kualifikasi]);
    //     $date_bulan = date('ym');
    //     return $pdf->stream(' FPTK '.$date_bulan.' '.$data[0]->sub_dept_name.' .pdf');
    // }
    // public function get_employee_fptk(Request $request)
    // {
    //     $enroll_id = $request->enroll_id;
    //     $data = DB::table('employee_atribut')
    //         ->where('enroll_id', $enroll_id)
    //         ->first();
    //     if($data == null){
    //             return response()->json(['data' => $data, 'success' => false]);
    //     }
    //     return response()->json(['data' => $data, 'success' => true]);
    // }


//    public function simpan_no_fptk_karyawan(Request $request)
//     {
//         $karyawan = $request->karyawan;
//         $no_fptk = $request->no_fptk;

//         if (!$no_fptk) {
//             return response()->json(['success' => false, 'message' => 'Data tidak valid.']);
//         }
//         if (empty($karyawan)) {
//             DB::table('employee_atribut')
//                 ->where('no_fptk', $no_fptk)
//                 ->update(['no_fptk' => null]);
//             return response()->json(['success' => true, 'message' => 'karyawan berhasil dihapus dari FPTK.']);
//         }
//         $enroll_ids_dikirim = collect($karyawan)->pluck('enroll_id')->toArray();
//         DB::table('employee_atribut')
//             ->where('no_fptk', $no_fptk)
//             ->whereNotIn('enroll_id', $enroll_ids_dikirim)
//             ->update(['no_fptk' => null]);
//         $invalid_karyawan = [];

//         // Langkah 1: Validasi semua dulu
//         foreach ($karyawan as $item) {
//             $enroll_id = $item['enroll_id'];

//             $existing = DB::table('employee_atribut')
//                 ->where('enroll_id', $enroll_id)
//                 ->whereNotNull('no_fptk')
//                 ->where('no_fptk', '!=', $no_fptk)
//                 ->first();

//             if ($existing) {
//                 $invalid_karyawan[] = [
//                     'enroll_id' => $enroll_id,
//                     'employee_name' => $existing->employee_name ?? '(tidak diketahui)',
//                     'no_fptk_lain' => $existing->no_fptk,
//                 ];
//             }
//         }

//         // Langkah 2: Jika ada yang invalid, hentikan proses
//         if (!empty($invalid_karyawan)) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Beberapa karyawan sudah terdaftar di FPTK lain.',
//                 'data' => $invalid_karyawan
//             ]);
//         }

//         // Langkah 3: Jika semua valid, lakukan update
//         foreach ($karyawan as $item) {
//             DB::table('employee_atribut')
//                 ->where('enroll_id', $item['enroll_id'])
//                 ->update(['no_fptk' => $no_fptk]);
//         }
//         // Hitung jumlah karyawan dengan no_fptk untuk proses penyelesaian permintaan
//         $count_data = DB::table('employee_atribut')
//                 ->where('no_fptk', $no_fptk)
//                 ->count();
//         $jumlah_pengajuan = DB::table('sub_pengajuan_permintaan_tk')
//         ->where('no_permintaan_id', $no_fptk)
//         ->sum('jumlah_kebutuhan');
//         if($count_data == $jumlah_pengajuan){
//             DB::table('pengajuan_permintaan_tk')
//                 ->where('no_permintaan', $no_fptk)
//                 ->update(['status_pengajuan_realisasi' => 'done', 'verifikator_by' => Auth::guard('admin')->user()->email]);
//         }
//         return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui.']);
//     }
//    public function simpan_selesai_no_fptk_karyawan(Request $request)
//     {
//         $karyawan = $request->karyawan;
//         $no_fptk = $request->no_fptk;
//         if (!$no_fptk) {
//             return response()->json(['success' => false, 'message' => 'Data tidak valid.']);
//         }
//         if (empty($karyawan)) {
//             DB::table('employee_atribut')
//                 ->where('no_fptk', $no_fptk)
//                 ->update(['no_fptk' => null]);
//             return response()->json(['success' => true, 'message' => 'karyawan berhasil dihapus dari FPTK.']);
//         }
//         $enroll_ids_dikirim = collect($karyawan)->pluck('enroll_id')->toArray();

//         DB::table('employee_atribut')
//             ->where('no_fptk', $no_fptk)
//             ->whereNotIn('enroll_id', $enroll_ids_dikirim)
//             ->update(['no_fptk' => null]);
//         $invalid_karyawan = [];

//         // Langkah 1: Validasi semua dulu
//         foreach ($karyawan as $item) {
//             $enroll_id = $item['enroll_id'];

//             $existing = DB::table('employee_atribut')
//                 ->where('enroll_id', $enroll_id)
//                 ->whereNotNull('no_fptk')
//                 ->where('no_fptk', '!=', $no_fptk)
//                 ->first();

//             if ($existing) {
//                 $invalid_karyawan[] = [
//                     'enroll_id' => $enroll_id,
//                     'employee_name' => $existing->employee_name ?? '(tidak diketahui)',
//                     'no_fptk_lain' => $existing->no_fptk,
//                 ];
//             }
//         }

//         // Langkah 2: Jika ada yang invalid, hentikan proses
//         if (!empty($invalid_karyawan)) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Beberapa karyawan sudah terdaftar di FPTK lain.',
//                 'data' => $invalid_karyawan
//             ]);
//         }

//         // Langkah 3: Jika semua valid, lakukan update
//         foreach ($karyawan as $item) {
//             DB::table('employee_atribut')
//                 ->where('enroll_id', $item['enroll_id'])
//                 ->update(['no_fptk' => $no_fptk]);
//         }
//         // Selesaikan proses penyelesaian permintaan
//         DB::table('pengajuan_permintaan_tk')
//             ->where('no_permintaan', $no_fptk)
//             ->update(['status_pengajuan_realisasi' => 'done', 'verifikator_by' => Auth::guard('admin')->user()->email]);

//         return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui.']);
//     }

    // public function set_to_pending_no_fptk_karyawan(Request $request){
    //     $no_fptk = $request->no_fptk;

    //     if (!$no_fptk) {
    //         return response()->json(['success' => false, 'message' => 'Data tidak valid.']);
    //     }
    //     DB::table('pengajuan_permintaan_tk')
    //         ->where('no_permintaan', $no_fptk)
    //         ->update(['status_pengajuan_realisasi' => 'pending', 'verifikator_by' => Auth::guard('admin')->user()->email]);
    //     return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui.']);
    // }
    // public function move_to_pending_permintaan(Request $request){
    //     $no_fptk = $request->no_fptk;
    //     if (!$no_fptk) {
    //         return response()->json(['success' => false, 'message' => 'Data tidak valid.']);
    //     }
    //     DB::table('pengajuan_permintaan_tk')
    //         ->where('no_permintaan', $no_fptk)
    //         ->update(['status_pengajuan_realisasi' => null, 'verifikator_by' => Auth::guard('admin')->user()->email, 'status_pengajuan' => 'waiting_approval']);
    //     return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui.']);
    // }





}
