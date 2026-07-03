<?php

namespace App\Http\Controllers\Hris\FormLembur;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\View;
use DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportSplSheet;
use App\Exports\ExportSpl_All;
use App\Exports\ExportInsentifSewing_All;
use App\Exports\ExportSpl_Import;
use App\Exports\BiayaMakanKaryawan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use App\Providers\ActivityServiceProvider;
use App\Models\MutKaryawanInputFormLemburDet;


class FormLemburNonIstirahatController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Dashboard';
    }
public function index(Request $request)
{
    $user = Auth::guard('admin')->user()->name;
    $user_email = Auth::guard('admin')->user()->email;

    $tgl_awal = $request->tanggal ?? now()->format('Y-m-d');
    $no_form = $request->no_form;


    if ($request->ajax()) {

    $tgl_awal = $request->tanggal ?? now()->format('Y-m-d');
      if (empty($no_form)) {
            return DataTables::of(collect())->make(true);
        }

    // =======================
    // UNION QUERY
    // =======================
    $unionQuery = DB::table('mut_karyawan_input_form_lembur as h')
        ->join('mut_karyawan_input_form_lembur_det as d', 'h.no_form', '=', 'd.no_form')
        ->join('employee_atribut as e', 'd.enroll_id', '=', 'e.enroll_id')
        ->select(
            'h.no_form',
            'e.enroll_id',
            'e.employee_name',
            'h.tgl_lembur',
            'd.jam_lembur_istirahat',
            DB::raw("'SEWING' as jenis")
        )
        ->whereDate('h.tgl_lembur', $tgl_awal)
        ->where('h.no_form',$no_form)

        ->unionAll(

            DB::table('mut_karyawan_input_non_sewing_form_lembur as h')
                ->join('mut_karyawan_input_non_sewing_form_lembur_det as d', 'h.no_form', '=', 'd.no_form')
                ->join('employee_atribut as e', 'd.enroll_id', '=', 'e.enroll_id')
                ->select(
                    'h.no_form',
                    'e.enroll_id',
                    'e.employee_name',
                    'h.tgl_lembur',
                    'd.jam_lembur_istirahat',
                    DB::raw("'NON SEWING' as jenis")
                )
                ->whereDate('h.tgl_lembur', $tgl_awal)
                ->where('h.no_form',$no_form)
        );

    // =======================
    // SUBQUERY
    // =======================
    $query = DB::query()->fromSub($unionQuery, 'x');

    // =======================
    // FILTER SPL (FIX)
    // =======================
    // if ($request->no_form) {
    //     $query->where('x.no_form', 'like', '%' . $request->no_form . '%');
    // }

    return DataTables::of($query)
        ->filter(function ($query) {
            $search = request('search.value');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->orWhere('x.no_form', 'like', "%$search%")
                      ->orWhere('x.employee_name', 'like', "%$search%")
                      ->orWhere('x.enroll_id', 'like', "%$search%")
                      ->orWhere('x.tgl_lembur', 'like', "%$search%");
                });
            }
        })
        ->make(true);
}


    return view('hris.mutasi-karyawan.lembur-non-istirahat.form_lembur_non_istirahat', [
        'page' => 'dashboard-mut-karyawan',
        'subPageGroup' => 'proses-karyawan',
        'subPage' => 'mut-karyawan',
        'user' => $user,
    ], $this->data);
}

public function get_dataLemburNonIstirahat(Request $request)
{
    $tanggal = $request->tanggal; // contoh: 2026-01-19
    $no_form = $request->no_form; // contoh: 260119_HEATTRANSFER_1

    // SUBQUERY UNION (SEWING + NON SEWING)
    $subQuery = DB::raw("
        (
            SELECT
                h.no_form,
                d.enroll_id,
                h.tgl_lembur,
                d.jam_lembur_istirahat,
                d.jam_lembur_awal_rencana,
                d.jam_lembur_akhir_rencana,
                'SEWING' AS jenis
            FROM mut_karyawan_input_form_lembur h
            JOIN mut_karyawan_input_form_lembur_det d
                ON h.no_form = d.no_form

            UNION ALL

            SELECT
                h.no_form,
                d.enroll_id,
                h.tgl_lembur,
                d.jam_lembur_istirahat,
                d.jam_lembur_awal_rencana,
                d.jam_lembur_akhir_rencana,
                'NON SEWING' AS jenis
            FROM mut_karyawan_input_non_sewing_form_lembur h
            JOIN mut_karyawan_input_non_sewing_form_lembur_det d
                ON h.no_form = d.no_form
        ) AS l
    ");

    $query = DB::table($subQuery)
        ->join('data_lembur_tanpa_istirahart as lni', function ($join) {
            $join->on('l.enroll_id', '=', 'lni.enroll_id')
                 ->on('l.tgl_lembur', '=', 'lni.tanggal');
        })
        ->join('employee_atribut as e', 'l.enroll_id', '=', 'e.enroll_id')
        ->select([
            'l.no_form',
            'l.enroll_id',
            'e.employee_name',
            'l.tgl_lembur',
            'l.jam_lembur_istirahat',
            'l.jam_lembur_awal_rencana',
            'l.jam_lembur_akhir_rencana',
            'l.jenis'
        ])
        ->whereDate('l.tgl_lembur', $tanggal)
        ->where('l.no_form', $no_form);

    // 🔍 FILTER TANGGAL
    // if (!empty($tanggal)) {
    //     $query->where('l.tgl_lembur', $tanggal);
    // }

    // // 🔍 FILTER NO FORM
    // if (!empty($no_form)) {
    //     $query->where('l.no_form', $no_form);
    // }

    // 🔎 DEBUG (kalau perlu)
    // dd($query->toSql(), $query->getBindings());

    return DataTables::of($query)
        ->filter(function ($query) {
            $search = request('search.value');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->orWhere('l.no_form', 'like', "%$search%")
                      ->orWhere('e.employee_name', 'like', "%$search%")
                      ->orWhere('l.enroll_id', 'like', "%$search%")
                      ->orWhere('l.tgl_lembur', 'like', "%$search%");
                });
            }
        })
        ->make(true);
}
public function getNoForm(Request $request)
{
    $user = Auth::guard('admin')->user()->name;
    $user_email = Auth::guard('admin')->user()->email;
    // dd($user_email,$user);
    $tgl_awal = $request->tanggal;
     if($user=='HR' || $user=='IT' || $user_email == 'mega@ptnag.com' || $user_email == 'rudy@ptnag.com' || $user_email == 'dev_hris' || $user_email == 'indri@nag.nirwanaindonesia.com' || $user_email == 'ersa@ptnag.com'){
        $spl = DB::select("
            SELECT DISTINCT no_form
        FROM (
            SELECT no_form
            FROM mut_karyawan_input_form_lembur
            WHERE DATE(tgl_lembur) = ?

            UNION

            SELECT no_form
            FROM mut_karyawan_input_non_sewing_form_lembur
            WHERE DATE(tgl_lembur) = ?
        ) x
        ORDER BY no_form
    ", [$tgl_awal, $tgl_awal]);
    }else{

        $spl = DB::select("
        SELECT DISTINCT no_form
        FROM (
            SELECT no_form
            FROM mut_karyawan_input_form_lembur
            WHERE created_by = ? AND DATE(tgl_lembur) = ?

            UNION

            SELECT no_form
            FROM mut_karyawan_input_non_sewing_form_lembur
            WHERE created_by = ? AND DATE(tgl_lembur) = ?
        ) x
        ORDER BY no_form
    ", [$user,$tgl_awal,$user,$tgl_awal]);

    }

    return response()->json($spl);
}

    private function waktuKeMenit($waktu)
{
    if (!$waktu) {
        return 0;
    }

    // Pakai Carbon → AMAN untuk datetime & jam
    $time = Carbon::parse($waktu);

    return ($time->hour * 60) + $time->minute;
}

    private function hitungJamIstirahatBerdasarkanRentang($jam_mulai, $jam_akhir)
{
    // Konversi ke menit untuk perhitungan
    $menit_mulai = $this->waktuKeMenit($jam_mulai);
    $menit_akhir = $this->waktuKeMenit($jam_akhir);
    // dd($menit_mulai, $menit_akhir);

    // Jika melewati midnight (lembur malam)
    if ($menit_akhir < $menit_mulai) {
        $menit_akhir += 1440; // Tambah 24 jam (1440 menit)
    }

    // Rentang istirahat dalam menit
    $istirahat_siang_mulai = $this->waktuKeMenit('12:00');
    $istirahat_siang_akhir = $this->waktuKeMenit('13:00'); // 1 jam

    $istirahat_sore_mulai = $this->waktuKeMenit('18:00');
    $istirahat_sore_akhir = $this->waktuKeMenit('18:30'); // 0.4 jam (24 menit)

    $total_istirahat_menit = 0;

    // Cek overlap dengan istirahat siang
    if ($menit_mulai < $istirahat_siang_akhir && $menit_akhir > $istirahat_siang_mulai) {
        $overlap_start = max($menit_mulai, $istirahat_siang_mulai);
        $overlap_end = min($menit_akhir, $istirahat_siang_akhir);

        if ($overlap_start < $overlap_end) {
            $total_istirahat_menit += ($overlap_end - $overlap_start);
        }
    }

    // Cek overlap dengan istirahat sore
    if ($menit_mulai < $istirahat_sore_akhir && $menit_akhir > $istirahat_sore_mulai) {
        $overlap_start = max($menit_mulai, $istirahat_sore_mulai);
        $overlap_end = min($menit_akhir, $istirahat_sore_akhir);

        if ($overlap_start < $overlap_end) {
            $total_istirahat_menit += ($overlap_end - $overlap_start);
        }
    }
    // dd($total_istirahat_menit);

    // Konversi menit ke jam (desimal)
    return $total_istirahat_menit;
}
    // public function hapusIstirahat(Request $request){
    //     $user = Auth::guard('admin')->user()->name;
    //     $enroll_id = $request->enroll_id;
    //     $nomor_form_lembur = $request->nomor_form_lembur;

    //     DB::insert('insert into data_lembur_tanpa_istirahat (no_form_lembur, enroll_id) values ('$nomor_form_lembur', '$enroll_id')');

    //     return redirect()->back();
    // }
//     public function hapusIstirahat(Request $request){
//     $user = Auth::guard('admin')->user()->name;
//     $enroll_id = $request->enroll_id;
//     $nomor_form_lembur = $request->nomor_form_lembur;
//     // dd($nomor_form_lembur, $enroll_id);

//     DB::transaction(function() use ($nomor_form_lembur, $enroll_id, $user) {

//         // 1️⃣ Ambil data lembur asli
//         $dataLembur = DB::table('data_lembur')
//             ->where('nomor_form_lembur', $nomor_form_lembur)
//             ->where('enroll_id', $enroll_id)
//             ->first();

//         if(!$dataLembur){
//             throw new \Exception('Data lembur tidak ditemukan');
//         }

//         $jamIstirahat = $dataLembur->jumlah_jam_istirahat_lembur;

//         if($jamIstirahat > 0){
//             // 2️⃣ Insert ke data_lembur_tanpa_istirahat
//             DB::table('data_lembur_tanpa_istirahart')->insert([
//                 'no_form_lembur' => $nomor_form_lembur,
//                 'enroll_id' => $enroll_id,
//                 // 'jumlah_jam_istirahat_lembur' => $jamIstirahat, // simpan jam yang dipindahkan
//                 // 'created_by' => $user,
//                 // 'created_at' => now(),
//                 // 'updated_at' => now(),
//             ]);

//             // 3️⃣ Update data lembur asli: jam istirahat = 0
//             //    dan tambahkan jam istirahat yang dipindahkan ke total jam lembur
//             DB::table('data_lembur')
//                 ->where('nomor_form_lembur', $nomor_form_lembur)
//                 ->where('enroll_id', $enroll_id)
//                 ->update([
//                     'jumlah_jam_istirahat_lembur' => 0,
//                     'jumlah_jam_lembur' => $dataLembur->jumlah_jam_lembur + $jamIstirahat,
//                     'updated_at' => now(),
//                 ]);
//         }
//     });

//     return redirect()->back()->with('success', 'Jam istirahat berhasil dipisahkan dan total jam lembur diperbarui');
// }

public function hapusIstirahat(Request $request)
{
    DB::beginTransaction();
    // dd($request->all());

    try {

        $processed = 0;

        foreach ($request->data as $row) {

            // Tentukan tabel berdasarkan jenis
            if ($row['jenis'] === 'SEWING') {
                $table = 'mut_karyawan_input_form_lembur_det';
            } else {
                $table = 'mut_karyawan_input_non_sewing_form_lembur_det';
            }

            // Ambil data real
            $det = DB::table($table)
                ->where('no_form', $row['no_form'])
                ->where('enroll_id', $row['enroll_id'])
                ->first();

            if (!$det || $det->jam_lembur_istirahat == 0) {
                continue;
            }
             if ($det) {
                    DB::table('data_lembur_tanpa_istirahart')->insert([
                        'tanggal'   => $row['tgl_lembur'],
                        'enroll_id' => $row['enroll_id'],
                    ]);
                }

            // Update jam lembur & hapus jam istirahat
            $updated = DB::table($table)
                ->where('no_form', $row['no_form'])
                ->where('enroll_id', $row['enroll_id'])
                ->where('jam_lembur_istirahat', '>', 0)
                ->update([
                    'jam_lembur_istirahat' => 0
                ]);

            if ($updated > 0) {
                $processed++;
            }
        }

        DB::commit();

        if ($processed === 0) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Tidak ada data yang diproses'
            ]);
        }

        return response()->json([
            'status' => 'success'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}

    public function deleteNonIstirahat(Request $request)
{
    // dd($request->all());
    $enroll_id = $request->enroll_id;
    $no_form = $request->no_form;
    $mulai_jam = $request->mulai_jam; // format: 2026-01-14 17:00:00
    $akhir_jam = $request->akhir_jam;
    $jenis = $request->jenis;
    $tanggal = $request->tanggal; // format: 2026-01-14 19:00:00

    // Hitung jumlah jam istirahat
    $jam_istirahat = $this->hitungJamIstirahatBerdasarkanRentang($mulai_jam, $akhir_jam);
    // dd($jam_istirahat);

    try {
        DB::beginTransaction();

        // Update data_lembur (tambah jam istirahat)
       if ($jenis === 'SEWING') {

        DB::table('mut_karyawan_input_form_lembur_det')
            ->where('no_form', $no_form)
            ->where('enroll_id', $enroll_id)
            ->update([
                'jam_lembur_istirahat' => DB::raw(
                    "jam_lembur_istirahat + $jam_istirahat"
                )
            ]);

        } else { // NON SEWING

            DB::table('mut_karyawan_input_non_sewing_form_lembur_det')
                ->where('no_form', $no_form)
                ->where('enroll_id', $enroll_id)
                ->update([
                    'jam_lembur_istirahat' => DB::raw(
                        "jam_lembur_istirahat + $jam_istirahat"
                    )
                ]);
        }

        // Hapus dari data_lembur_tanpa_istirahart
        DB::table('data_lembur_tanpa_istirahart')
            ->where('enroll_id', $enroll_id)
            ->where('tanggal', $tanggal)
            ->delete();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus. Jam istirahat ditambahkan: ' . $jam_istirahat . ' Menit'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}
public function printNonIstirahat(Request $request)
{
    $tanggal = $request->tanggal;
    $nomor_form_lembur = $request->no_form;
    // dd($nomor_form_lembur,$request->all());

    $subQuery = DB::raw("
        (
            SELECT
                h.no_form,
                d.enroll_id,
                h.tgl_lembur,
                'SEWING' AS jenis
            FROM mut_karyawan_input_form_lembur h
            JOIN mut_karyawan_input_form_lembur_det d
                ON h.no_form = d.no_form

            UNION ALL

            SELECT
                h.no_form,
                d.enroll_id,
                h.tgl_lembur,
                'NON SEWING' AS jenis
            FROM mut_karyawan_input_non_sewing_form_lembur h
            JOIN mut_karyawan_input_non_sewing_form_lembur_det d
                ON h.no_form = d.no_form
        ) AS l
    ");

    $query = DB::table($subQuery)
        ->join('data_lembur_tanpa_istirahart as lni', function ($join) {
            $join->on('l.enroll_id', '=', 'lni.enroll_id')
                 ->on('l.tgl_lembur', '=', 'lni.tanggal');
        })
        ->join('employee_atribut as e', 'l.enroll_id', '=', 'e.enroll_id')
        ->select([
            'l.no_form as nomor_form_lembur',
            'e.nik',
            'e.employee_name',
            'l.tgl_lembur',
            'e.sub_dept_name',
            'l.jenis'
        ]);

    if (!empty($tanggal)) {
        $query->where('l.tgl_lembur', $tanggal);
    }

    if (!empty($nomor_form_lembur)) {
        $query->where('l.no_form', $nomor_form_lembur);
    }

    $data = $query->get();

// dd($data);

    $pdf = PDF::loadView(
        'hris.mutasi-karyawan.lembur-non-istirahat.export-spl-pdf',
        compact('data')
    )->setPaper('A4', 'portrait');

    return $pdf->stream('lembur-non-istirahat.pdf');
}


//
}
