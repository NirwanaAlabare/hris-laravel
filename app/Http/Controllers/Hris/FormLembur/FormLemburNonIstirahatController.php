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

        // $tgl_awal = $request->dateFrom ?? now()->format('Y-m-d');
        // $tgl_akhir = $request->dateTo ?? now()->format('Y-m-d');
        $tgl_awal = $request->tanggal ?? now()->format('Y-m-d');
        // dd($tgl_awal);


//  dd($tgl_awal);

        // Jika request AJAX (DataTable server-side)
        if ($request->ajax()) {

            $query = DB::table('data_lembur as dl')
                ->join('employee_atribut as e', 'dl.enroll_id', '=', 'e.enroll_id')
                ->select(
                    'dl.nomor_form_lembur as no_form',
                    'e.enroll_id',
                    'e.employee_name',
                    'dl.tanggal_berjalan',
                    'dl.jumlah_jam_istirahat_lembur',
                    'dl.jumlah_jam_lembur'
                )
                // ->whereBetween('dl.tanggal_berjalan', [$tgl_awal, $tgl_akhir]);
                ->where('dl.tanggal_berjalan', [$tgl_awal]);
        if ($request->nomor_form_lembur) {
            $query->where('dl.nomor_form_lembur', 'like', '%' . $request->nomor_form_lembur . '%');
        }

         return DataTables::of($query)->toJson();}

        // Jika request bukan AJAX, tampilkan view
        return view('hris.mutasi-karyawan.lembur-non-istirahat.form_lembur_non_istirahat', [
            'page' => 'dashboard-mut-karyawan',
            'subPageGroup' => 'proses-karyawan',
            'subPage' => 'mut-karyawan',
            'user' => $user,
        ], $this->data);
    }
public function get_dataLemburNonIstirahat(Request $request)
{
    $tgl_awal = $request->tanggal;
    $nomor_form_lembur = $request->nomor_form_lembur;

    $query = DB::table('data_lembur_tanpa_istirahart as lni')
        ->join('employee_atribut as e', 'lni.enroll_id', '=', 'e.enroll_id')
        ->join('data_lembur as l', function ($join) {
            $join->on('lni.enroll_id', '=', 'l.enroll_id')
                 ->on('lni.tanggal', '=', 'l.tanggal_berjalan');
        })
        ->select([
            'l.nomor_form_lembur as no_form',
            'lni.enroll_id',
            'e.employee_name',
            'l.tanggal_berjalan',
            'l.jumlah_jam_istirahat_lembur',
            'l.jumlah_jam_lembur'
        ]);

    // FILTER TANGGAL
    if (!empty($tgl_awal)) {
        $query->whereDate('l.tanggal_berjalan', $tgl_awal);
    }

    // FILTER SPL / NAMA
    if (!empty($nomor_form_lembur)) {
        $query->where(function ($q) use ($nomor_form_lembur) {
            $q->where('l.nomor_form_lembur', 'like', "%$nomor_form_lembur%")
              ->orWhere('e.employee_name', 'like', "%$nomor_form_lembur%");
        });
    }

    return DataTables::of($query)->make(true);
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
        //   $tanggal = $request->data;
        // dd($tanggal);
        DB::beginTransaction();

        try {
            foreach ($request->data as $row) {


                // 1️⃣ Insert ke lembur tanpa istirahat
                DB::table('data_lembur_tanpa_istirahart')->insert([
                    'tanggal' => $row['tanggal_berjalan'],
                    'enroll_id' => $row['enroll_id'],
                    // 'created_at' => now()
                ]);

                // 2️⃣ Update data lembur
                DB::table('data_lembur')
                    ->where('tanggal_berjalan', $row['tanggal_berjalan'])
                    ->where('enroll_id', $row['enroll_id'])
                    ->update([
                        'jumlah_jam_lembur' => DB::raw(
                            'jumlah_jam_lembur + '.$row['jam_istirahat']
                        ),
                        'jumlah_jam_istirahat_lembur' => 0
                    ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
public function printNonIstirahat(Request $request)
{
    // dd($query);
    // $tgl_awal  = $request->dateFrom;
    // $tgl_akhir = $request->dateTo;
        $tgl_awal  = $request->tanggal;
        $nomor_form_lembur = $request->nomor_form_lembur;

        // dd($tgl_awal, $nomor_form_lembur);

// dd($tgl_awal, $tgl_akhir);
    // WAJIB: simpan query ke variabel
    $query = DB::table('data_lembur_tanpa_istirahart as lni')
        ->join('employee_atribut as e', 'lni.enroll_id', '=', 'e.enroll_id')
        ->join('data_lembur as l', function ($join) {
            $join->on('lni.enroll_id', '=', 'l.enroll_id')
                ->on('lni.tanggal', '=', 'l.tanggal_berjalan');
        })
        ->select(
            'l.nomor_form_lembur',
            'e.nik',
            'e.employee_name',
            'l.tanggal_berjalan',
            'e.sub_dept_name'
        )
        // ->whereBetween('l.tanggal_berjalan', [$tgl_awal, $tgl_akhir]);
        ->where('l.tanggal_berjalan', [$tgl_awal]);

    // FILTER SPL (OPTIONAL)
    if ($request->filled('nomor_form_lembur')) {
        $query->where(
            'l.nomor_form_lembur',
            'like',
            '%' . $nomor_form_lembur . '%'
        );
    }

    $data = $query->get();
    $pdf = PDF::loadView('hris.mutasi-karyawan.lembur-non-istirahat.export-spl-pdf',compact('data'))->setPaper('A4', 'portrait');

return $pdf->stream('lembur-non-istirahat.pdf');



}
//
}
