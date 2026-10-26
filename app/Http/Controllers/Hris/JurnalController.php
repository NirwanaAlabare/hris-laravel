<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use App\Models\Jurnal;
use App\Models\Jurnal_akhir;
use Illuminate\Support\Facades\View;
use App\Models\RekapPerhitunganPayroll;
use App\Models\DailyLaborCost;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Datatables;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Exports\JurnalExport;
use App\Models\DataKoreksiPotongan;
use App\Models\DataKoreksiUpah;
use App\Models\DepartmentAll;

/**
 * Class RekapPerhitunganPayrollController
 * @package App\Http\Controllers\Hris
 */
class JurnalController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Rekap Perhitungan Payroll';
    }

    public function index()
    {
        $this->periode_payroll = $this->ajax_getperiodepayroll();

        $this->latestData = Jurnal::latest('updated_at')->first();

        $this->loggedAdmin = Auth::guard('admin')->user();
        return View::make('hris/jurnal', $this->data);
    }


    // public function ajax_getperiodepayroll()
    // {
    //     $query =  Jurnal::selectRaw('periode_payroll')
    //         ->groupby('periode_payroll')
    //         ->orderby('periode_payroll', 'desc')
    //         // dd($query->toSql());
    //         ->get();
    //     return $query;
    // }
    public function ajax_getperiodepayroll()
    {
    $query =  RekapPerhitunganPayroll::selectRaw('CONCAT(periode_tahun_payroll,"-",periode_bulan_payroll) periode_payroll')
                ->groupby('periode_payroll')
                ->orderby('periode_payroll', 'desc')
                ->get();
            return $query;
    }
    
     public function jurnalakhir()
    {
        $this->periode_payroll = $this->ajax_getperiodepayroll();

        $this->latestData = Jurnal::latest('updated_at')->first();

        $this->loggedAdmin = Auth::guard('admin')->user();
        return View::make('hris/jurnal_akhir', $this->data);
    }
    public function ajax_exportexcel(Request $request)
    {
        $data = Jurnal::where('periode_payroll', $request->periode_payroll)->get();

        return Excel::download(new JurnalExport($data), 'Data_jurnal' . time() . '.xlsx');
    }
    public function ajax_exportexcel_jurnalakhir(Request $request)
    {
        $data = Jurnal_akhir::where('periode_payroll', $request->periode_payroll)
        ->get();
    // dd($data->toSql());
        return Excel::download(new JurnalExport($data), 'Data_jurnal' . time() . '.xlsx');
    }


//     public function update_jurnal(Request $request)
//     {
//         $periode_payroll = $request->periode_payroll;

//         // Ambil 4 karakter pertama dari kiri (tahun)
//         $year = substr($periode_payroll, 0, 4);

//         // Ambil 2 karakter terakhir dari kanan (bulan)
//         $month = substr($periode_payroll, -2);


//         //rekap jurnal
//         $departement = DepartmentAll::get();
//         $data_potongan = $this->potongan($periode_payroll);
//         $data_koreksi = $this->koreksi($periode_payroll);
//         foreach ($departement as $key => $value) {
//             $potongan_bpjs_tk = $data_potongan->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_potongan', '1')->sum('jumlah_rp_potongan');
//             $potongan_bpjs_ks = $data_potongan->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_potongan', '2')->sum('jumlah_rp_potongan');
//             $potongan_bazzar = $data_potongan->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_potongan', '3')->sum('jumlah_rp_potongan');
//             $potongan_kasbon = $data_potongan->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_potongan', '6')->sum('jumlah_rp_potongan');
//             $potongan_lain = $data_potongan->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_potongan', '5')->sum('jumlah_rp_potongan');
//             // $koreksi_insentif = $data_koreksi->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_koreksi', '2')->sum('jumlah_rp_potongan');
//             $payroll = RekapPerhitunganPayroll::where('periode_tahun_payroll', $year)->where('periode_bulan_payroll', $month)->where('periode_umk', null)
//                 ->where('kategori_karyawan', 'NON STAFF')->where('sub_dept_id', $value->sub_dept_id)
//                 ->get();


//             $rp_cuti_tahuna = 0;
//             $potongan_kehadiran_rupiah = $payroll->sum('potongan_kehadiran_rupiah');
//             $rp_pot_jam = $payroll->sum('potongan_iks_rupiah') + $payroll->sum('potongan_dtpc_rupiah');
//             $iuran_serikat_rupiah = $payroll->sum('iuran_serikat_rupiah');
//             $iuran_koperasi = $payroll->sum('iuran_koperasi');

//             $gaji_umk = $payroll->sum('upah_per_bulan');
//             $koreksi_upah = $payroll->sum('koreksi_upah');
//             $potongan_upah = $payroll->sum('potongan_upah');

//             $total_gaji = 0;
//             $total_tunjangan_karyawan = 0;

//             foreach ($payroll as $p) {
//                 $nama_bank = $p->nama_bank ?? '-';
//                 $tunai = strtoupper($nama_bank) === 'TUNAI';

//                 $nilai_bersih = $p->upah_neto_rupiah - $p->jumlah_potongan_rupiah;
// // 924123562

//                 if ($tunai) {
//                     // Pembulatan ke atas kelipatan 500
//                     $total_upah_thp_rupiah_pembulatan = ceil($nilai_bersih / 500) * 500;
//                 } else {
//                     // Pembulatan ke atas kelipatan 100
//                     $total_upah_thp_rupiah_pembulatan = ceil($nilai_bersih / 100) * 100;
//                 }
//                 $pembulatan = $total_upah_thp_rupiah_pembulatan - $nilai_bersih;

//                 $total_gaji += ($p->upah_per_bulan + $pembulatan + $p->koreksi_upah) - $p->potongan_upah;

//                 if ($p->total_kehadiran_net <= 0 && $p->koreksi_upah_rupiah == 0 && $p->total_lembur_rupiah == 0 && ($p->total_bpjs_tk != 0 || $p->total_bpjs_ks != 0)) {
//                     continue;
//                 }

//                 if ($p->total_kehadiran_net == 0) {
//                     continue;
//                 }
//                 // Kalau lolos kondisi di atas, baru tambahkan
//                 $total_tunjangan_karyawan += $p->tunjangan_karyawan_rupiah;
//             }
//             $gaji = $total_gaji;
//             $insentif_jabatan = $payroll->sum('insentif_jabatan');
//             $premi_karyawan = $payroll->sum('premi_karyawan');
//             $koreksi_insentif = $payroll->sum('koreksi_insentif');
//             $potongan_insentif = $payroll->sum('potongan_insentif');

//             $potongan_piutang = $payroll->sum('potongan_piutang');


//             $tunjangan_karyawan_rupiah = ($total_tunjangan_karyawan + $koreksi_insentif + $insentif_jabatan + $premi_karyawan) - $potongan_insentif;
//             $total_lembur_rupiah = $payroll->sum('total_lembur_rupiah');
//             $bonus = 0;
//             $piutang_karyawan = $potongan_piutang;
//             $piutang_bazzar = $potongan_bazzar;
//             $bpjs_tk = $payroll->sum('total_bpjs_tk');
//             $bpjs_ks = $payroll->sum('total_bpjs_ks');
//             $potongan = $potongan_lain + $rp_cuti_tahuna + $potongan_kehadiran_rupiah + $rp_pot_jam + $iuran_serikat_rupiah + $iuran_koperasi;
//             $gaji_note=($gaji+$tunjangan_karyawan_rupiah+$total_lembur_rupiah+$bonus)-
//                         ($piutang_karyawan+$piutang_bazzar+$bpjs_tk+$bpjs_ks+$potongan);
//             $gaji_neto = ($gaji + $tunjangan_karyawan_rupiah + $total_lembur_rupiah) - ($piutang_karyawan + $piutang_bazzar + $bpjs_tk + $bpjs_ks + $potongan);

//             $data = [
//                 'kode_bagian' => $value->sub_dept_id,
//                 'nama_bagian' => $value->sub_dept_name,
//                 'gaji' => $gaji,
//                 'tunjangan_karyawan_rupiah' => $tunjangan_karyawan_rupiah,
//                 'total_lembur_rupiah' => $total_lembur_rupiah,
//                 'bonus' => $bonus,
//                 'piutang_karyawan' => $piutang_karyawan,
//                 'piutang_bazzar' => $piutang_bazzar,
//                 'bpjs_tk' => $bpjs_tk,
//                 'bpjs_ks' => $bpjs_ks,
//                 'potongan' => $potongan,
//                 'gaji_neto' => $gaji_neto,
//                 'jumlah_karyawn' => $payroll->count(),
//                 'periode_payroll' => $periode_payroll,
//             ];
//             $count = Jurnal::where('kode_bagian', $value->sub_dept_id)->where('periode_payroll', $periode_payroll)->count();

//             if ($count) {
//                 Jurnal::where('kode_bagian', $value->sub_dept_id)->where('periode_payroll', $periode_payroll)->update($data);
//             } else {
//                 Jurnal::create($data);
//             }
//         }
//     }

    public function update_jurnal(Request $request)
    {
        $periode_payroll = $request->periode_payroll;

        // Ambil 4 karakter pertama dari kiri (tahun)
        $year = substr($periode_payroll, 0, 4);

        // Ambil 2 karakter terakhir dari kanan (bulan)
        $month = substr($periode_payroll, -2);
        // $gajiNetoPerDept = DB::table('rekap_perhitungan_payroll as rpp')
        // ->join('employee_atribut as ea', 'ea.enroll_id', '=', 'rpp.enroll_id')
        // ->selectRaw("
        //     rpp.sub_dept_id,
        //     SUM(
        //         CASE
        //             WHEN rpp.total_kehadiran_net <= 0
        //                  AND rpp.koreksi_upah_rupiah = 0
        //                  AND rpp.total_lembur_rupiah = 0
        //                  AND (rpp.total_bpjs_tk <> 0 OR rpp.total_bpjs_ks <> 0)
        //             THEN 0
        //             ELSE
        //                 CASE
        //                     WHEN UPPER(rpp.nama_bank) = 'TUNAI'
        //                     THEN CEILING((rpp.upah_neto_rupiah - rpp.jumlah_potongan_rupiah) / 500) * 500
        //                     ELSE CEILING((rpp.upah_neto_rupiah - rpp.jumlah_potongan_rupiah) / 100) * 100
        //                 END
        //         END
        //     ) AS total_gaji
        // ")
        // ->where('rpp.periode_tahun_payroll', $year)
        // ->where('rpp.periode_bulan_payroll', $month)
        // ->whereNull('rpp.periode_umk')
        // ->where('rpp.kategori_karyawan', 'NON STAFF')
        // ->where('rpp.total_kehadiran_net', '>', 0)
        // ->where(function ($q) {
        //     $q->whereNull('ea.tanggal_resign')
        //       ->orWhere('ea.tanggal_resign', '>', '2025-11-26');
        // })
        // ->groupBy('rpp.sub_dept_id')
        // ->pluck('total_gaji', 'sub_dept_id');

        //rekap jurnal
        $departement = DepartmentAll::get();
        $data_potongan = $this->potongan($periode_payroll);
        $data_koreksi = $this->koreksi($periode_payroll);
        foreach ($departement as $key => $value) {
            $potongan_bpjs_tk = $data_potongan->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_potongan', '1')->sum('jumlah_rp_potongan');
            $potongan_bpjs_ks = $data_potongan->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_potongan', '2')->sum('jumlah_rp_potongan');
            $potongan_bazzar = $data_potongan->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_potongan', '3')->sum('jumlah_rp_potongan');
            $potongan_kasbon = $data_potongan->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_potongan', '6')->sum('jumlah_rp_potongan');
            $potongan_lain = $data_potongan->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_potongan', '5')->sum('jumlah_rp_potongan');
            // $koreksi_insentif = $data_koreksi->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_koreksi', '2')->sum('jumlah_rp_potongan');
            $payroll = RekapPerhitunganPayroll::where('periode_tahun_payroll', $year)->where('periode_bulan_payroll', $month)->where('periode_umk', null)
                ->where('kategori_karyawan', 'NON STAFF')->where('sub_dept_id', $value->sub_dept_id)
                // ->where('total_kehadiran_net', '>', 0)
                 ->where(function ($query) {
                        $query->where('total_kehadiran_net', '>', 0)
                            ->orWhere('koreksi_upah_rupiah', '!=', 0);
                    })
                ->get();
            $payroll1 = RekapPerhitunganPayroll::where('periode_tahun_payroll', $year)->where('periode_bulan_payroll', $month)->where('periode_umk', '=', '2026-01')
                ->where('kategori_karyawan', 'NON STAFF')->where('sub_dept_id', $value->sub_dept_id)
                // ->where('total_kehadiran_net', '>', 0)
                 ->where(function ($query) {
                        $query->where('total_kehadiran_net', '>', 0)
                            ->orWhere('koreksi_upah_rupiah', '!=', 0);
                    })
                 ->get();
            $gapok = $payroll1->sum('upah_per_bulan');


            // dd($payroll1->toSql(), $payroll1->getBindings());



            $rp_cuti_tahuna = 0;
            $potongan_kehadiran_rupiah = $payroll->sum('potongan_kehadiran_rupiah');
            $rp_pot_jam = $payroll->sum('potongan_iks_rupiah') + $payroll->sum('potongan_dtpc_rupiah');
            $iuran_serikat_rupiah = $payroll->sum('iuran_serikat_rupiah');
            $iuran_koperasi = $payroll->sum('iuran_koperasi');
                        $bpjs_tk = $payroll->sum('total_bpjs_tk');
            $bpjs_ks = $payroll->sum('total_bpjs_ks');
            $piutang_karyawan = $payroll->sum('piutang_karyawan');
            $total_lembur_rupiah = $payroll->sum('total_lembur_rupiah');
            $piutang_karyawan1 = $payroll->sum('koreksi_potongan_rupiah');

            $gaji_umk = $payroll->sum('upah_per_bulan');
            $koreksi_upah = $payroll->sum('koreksi_upah');
            $potongan_upah = $payroll->sum('potongan_upah');
            $gaji_neto1 = $payroll->sum('total_upah_thp_rupiah_employee');
            $total_gaji1 = ($gapok  +  $koreksi_upah) - $potongan_upah;
            $total_tunjangan_karyawan1 = $payroll->sum('tunjangan_karyawan_rupiah') + $payroll->sum('premi_karyawan')+$payroll->sum('koreksi_insentif')+$payroll->sum('insentif_jabatan');
            $gaji1 = ($gaji_neto1+ $potongan_kehadiran_rupiah + $rp_pot_jam + $iuran_serikat_rupiah + $iuran_koperasi + $bpjs_tk + $bpjs_ks +$piutang_karyawan1) - ($total_tunjangan_karyawan1+$total_lembur_rupiah+$payroll->sum('pembulatan'));
            // dd( $piutang_karyawan1);

            $total_gaji = 0;
            $total_tunjangan_karyawan = 0;

            foreach ($payroll as $p) {
                $nama_bank = $p->nama_bank ?? '-';
                $tunai = strtoupper($nama_bank) === 'TUNAI';

                $nilai_bersih = $p->upah_neto_rupiah - $p->jumlah_potongan_rupiah;


                if ($tunai) {
                    // Pembulatan ke atas kelipatan 500
                    $total_upah_thp_rupiah_pembulatan = ceil($nilai_bersih / 500) * 500;
                } else {
                    // Pembulatan ke atas kelipatan 100
                    $total_upah_thp_rupiah_pembulatan = ceil($nilai_bersih / 100) * 100;
                }
                $pembulatan = $total_upah_thp_rupiah_pembulatan - $nilai_bersih;
                $total_gaji += ($gapok + $pembulatan + $p->koreksi_upah) - $p->potongan_upah;

                // if ($p->total_kehadiran_net <= 0 && $p->koreksi_upah_rupiah == 0 && $p->total_lembur_rupiah == 0 && ($p->total_bpjs_tk != 0 || $p->total_bpjs_ks != 0)) {
                //     continue;
                // }

                // if ($p->total_kehadiran_net == 0) {
                //     continue;
                // }
                // Kalau lolos kondisi di atas, baru tambahkan
                $total_tunjangan_karyawan += $p->tunjangan_karyawan_rupiah;
            }
            $gaji = $total_gaji1;
            $insentif_jabatan = $payroll->sum('insentif_jabatan');
            $premi_karyawan = $payroll->sum('premi_karyawan');
            $koreksi_insentif = $payroll->sum('koreksi_insentif');
            $potongan_insentif = $payroll->sum('potongan_insentif');
            $potongan_lembur = $payroll->sum('potongan_lembur');

            $potongan_piutang = $payroll->sum('potongan_piutang');


            $tunjangan_karyawan_rupiah = ($total_tunjangan_karyawan + $koreksi_insentif + $insentif_jabatan + $premi_karyawan);
            $total_lembur_rupiah = $payroll->sum('total_lembur_rupiah');
            $bonus = 0;
            $piutang_karyawan = $potongan_piutang;
            $piutang_bazzar = $potongan_bazzar;
            $bpjs_tk = $payroll->sum('total_bpjs_tk');
            $bpjs_ks = $payroll->sum('total_bpjs_ks');
            $potongan = ($potongan_lain + $rp_cuti_tahuna + $potongan_kehadiran_rupiah + $rp_pot_jam + $iuran_serikat_rupiah + $iuran_koperasi)-$payroll->sum('pembulatan');
            // $gaji_note=($gaji+$tunjangan_karyawan_rupiah+$total_lembur_rupiah+$bonus)-
            //             ($piutang_karyawan+$piutang_bazzar+$bpjs_tk+$bpjs_ks+$potongan);
            $gaji_neto = ($gaji + $tunjangan_karyawan_rupiah + $total_lembur_rupiah) - ($piutang_karyawan + $piutang_bazzar + $bpjs_tk + $bpjs_ks + $potongan);


            // $gaji_neto = $gajiNetoPerDept[$value->sub_dept_id] ?? 0;

            $data = [
                'kode_bagian' => $value->sub_dept_id,
                'nama_bagian' => $value->sub_dept_name,
                'gaji' => $gaji1,
                'tunjangan_karyawan_rupiah' => $tunjangan_karyawan_rupiah,
                'total_lembur_rupiah' => $total_lembur_rupiah,
                'bonus' => $bonus,
                'piutang_karyawan' => $piutang_karyawan,
                'piutang_bazzar' => $piutang_bazzar,
                'bpjs_tk' => $bpjs_tk,
                'bpjs_ks' => $bpjs_ks,
                'potongan' => $potongan,
                'potongan_insentif' => $potongan_insentif,
                'potongan_lembur' => $potongan_lembur,
                'gaji_neto' => $gaji_neto1,
                'jumlah_karyawn' => $payroll->count(),
                'periode_payroll' => $periode_payroll,
            ];
            $count = Jurnal::where('kode_bagian', $value->sub_dept_id)->where('periode_payroll', $periode_payroll)->count();

            if ($count) {
                Jurnal::where('kode_bagian', $value->sub_dept_id)->where('periode_payroll', $periode_payroll)->update($data);
            } else {
                Jurnal::create($data);
            }
        }
    }
    public function update_jurnal_akhir(Request $request)
    {
        $periode_payroll = $request->periode_payroll; // Contoh: "2026-07"

        // Set tanggal awal ke tanggal 26 bulan tersebut
        $tanggal_awal = Carbon::parse($periode_payroll)->setDateFrom(Carbon::parse($periode_payroll))->day(26)->format('Y-m-d');
        // Atau lebih simpel:
        $tanggal_awal = Carbon::parse($periode_payroll . '-26')->format('Y-m-d');

        // Dapatkan tanggal akhir bulan (otomatis deteksi 30, 31, atau 28/29 untuk Feb)
        $tanggal_akhir = Carbon::parse($periode_payroll)->endOfMonth()->format('Y-m-d');

        // dd([
        //     'periode' => $periode_payroll,      // "2026-07"
        //     'tanggal_awal' => $tanggal_awal,   // "2026-07-26"
        //     'tanggal_akhir' => $tanggal_akhir  // "2026-07-31"
        // ]);

        // Ambil 4 karakter pertama dari kiri (tahun)
        // $year = substr($periode_payroll, 0, 4);

        // // Ambil 2 karakter terakhir dari kanan (bulan)
        // $month = substr($periode_payroll, -2);
        // $gajiNetoPerDept = DB::table('rekap_perhitungan_payroll as rpp')
        // ->join('employee_atribut as ea', 'ea.enroll_id', '=', 'rpp.enroll_id')
        // ->selectRaw("
        //     rpp.sub_dept_id,
        //     SUM(
        //         CASE
        //             WHEN rpp.total_kehadiran_net <= 0
        //                  AND rpp.koreksi_upah_rupiah = 0
        //                  AND rpp.total_lembur_rupiah = 0
        //                  AND (rpp.total_bpjs_tk <> 0 OR rpp.total_bpjs_ks <> 0)
        //             THEN 0
        //             ELSE
        //                 CASE
        //                     WHEN UPPER(rpp.nama_bank) = 'TUNAI'
        //                     THEN CEILING((rpp.upah_neto_rupiah - rpp.jumlah_potongan_rupiah) / 500) * 500
        //                     ELSE CEILING((rpp.upah_neto_rupiah - rpp.jumlah_potongan_rupiah) / 100) * 100
        //                 END
        //         END
        //     ) AS total_gaji
        // ")
        // ->where('rpp.periode_tahun_payroll', $year)
        // ->where('rpp.periode_bulan_payroll', $month)
        // ->whereNull('rpp.periode_umk')
        // ->where('rpp.kategori_karyawan', 'NON STAFF')
        // ->where('rpp.total_kehadiran_net', '>', 0)
        // ->where(function ($q) {
        //     $q->whereNull('ea.tanggal_resign')
        //       ->orWhere('ea.tanggal_resign', '>', '2025-11-26');
        // })
        // ->groupBy('rpp.sub_dept_id')
        // ->pluck('total_gaji', 'sub_dept_id');

        //rekap jurnal
        $departement = DepartmentAll::get();
        $data_potongan = $this->potongan($periode_payroll);
        $data_koreksi = $this->koreksi($periode_payroll);
        foreach ($departement as $key => $value) {
            $potongan_bpjs_tk = $data_potongan->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_potongan', '1')->sum('jumlah_rp_potongan');
            $potongan_bpjs_ks = $data_potongan->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_potongan', '2')->sum('jumlah_rp_potongan');
            $potongan_bazzar = $data_potongan->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_potongan', '3')->sum('jumlah_rp_potongan');
            $potongan_kasbon = $data_potongan->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_potongan', '6')->sum('jumlah_rp_potongan');
            $potongan_lain = $data_potongan->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_potongan', '5')->sum('jumlah_rp_potongan');
            // $koreksi_insentif = $data_koreksi->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_koreksi', '2')->sum('jumlah_rp_potongan');

            // $payroll = RekapPerhitunganPayroll::where('periode_tahun_payroll', $year)->where('periode_bulan_payroll', $month)->where('periode_umk', null)
            //     ->where('kategori_karyawan', 'NON STAFF')->where('sub_dept_id', $value->sub_dept_id) ->where(function ($query) {
            //             $query->where('total_kehadiran_net', '>', 0)
            //                 ->orWhere('koreksi_upah_rupiah', '!=', 0);
            //         })
            //         // dd($payroll->toSql(), $payroll->getBindings());
            //     ->get();
            // $payroll1 = RekapPerhitunganPayroll::where('periode_tahun_payroll', $year)->where('periode_bulan_payroll', $month)->where('periode_umk', '=', '2026-01')
            //     ->where('kategori_karyawan', 'NON STAFF')->where('sub_dept_id', $value->sub_dept_id) ->where(function ($query) {
            //             $query->where('total_kehadiran_net', '>', 0)
            //                 ->orWhere('koreksi_upah_rupiah', '!=', 0);
            //         })

            //      ->get();
            $laborCosts = DB::table('daily_labor_costs as d')
            ->leftJoin('employee_atribut as e', 'e.enroll_id', '=', 'd.enroll_id')
             ->leftJoin('rekap_perhitungan_lembur as rpl', function($join) {
                    $join->on('rpl.enroll_id', '=', 'd.enroll_id')
                        ->on('rpl.tanggal_berjalan', '=', 'd.tanggal_berjalan');
                })
            ->select(
                'd.enroll_id',
                'e.sub_dept_id',
                'e.department_id',
                'e.sub_dept_name',
                'e.department_name',
                'd.status_staff',
                DB::raw('SUM(d.gaji_perhari) as gaji'),
                DB::raw('SUM(d.total_lembur_rupiah) as lembur'),
                DB::raw('SUM(d.seniority_allowance) as seniority_allowance'),
                DB::raw('SUM(d.insentif_jabatan) as insentif_jabatan'),
                DB::raw('SUM(d.insentif_kehadiran) as insentif_kehadiran'),
                DB::raw('SUM(d.rp_pot_hari_kerja) as rp_pot_hari_kerja'),
                DB::raw('SUM(d.rp_pot_jam) as rp_pot_jam'),
                DB::raw('SUM(d.bruto) as bruto'),
                DB::raw('SUM(d.bpjs_ks) as bpjs_ks'),
                DB::raw('SUM(d.bpjs_tk) as bpjs_tk'),
                DB::raw('SUM(d.total_potongan) as total_potongan'),
                DB::raw('SUM(d.jumlah) as jumlah'),
                DB::raw('SUM(d.total_pembayaran) as total_pembayaran'),
                DB::raw('SUM(rpl.lembur1_rupiah) as rp_lembur_1'),
                DB::raw('SUM(rpl.lembur2_rupiah) as rp_lembur_2'),
                DB::raw('SUM(rpl.lembur3_rupiah) as rp_lembur_3'),
                DB::raw('SUM(rpl.lembur4_rupiah) as rp_lembur_4')
            )
            ->whereBetween('d.tanggal_berjalan', [$tanggal_awal, $tanggal_akhir])
            ->where('d.status_staff', 'NON STAFF')
            ->where('e.sub_dept_id', $value->sub_dept_id)
            ->groupBy(
                'd.enroll_id',
                'e.sub_dept_id',
                'e.department_id',
                'e.sub_dept_name',
                'e.department_name',
                'd.status_staff'
            )
    // dd($laborCosts->toSql(), $laborCosts->getBindings());
    ->get();
            // $laborCosts = DB::table('daily_labor_costs as d')
            // ->leftJoin('employee_atribut as e', 'e.enroll_id', '=', 'd.enroll_id')
            // ->select(
            //     'd.enroll_id',
            //     'e.sub_dept_id',
            //     'e.department_id',
            //     'e.sub_dept_name',
            //     'e.department_name',
            //     'd.status_staff',
            //     DB::raw('SUM(d.gaji_perhari) as gaji'),
            //     DB::raw('SUM(d.total_lembur_rupiah) as lembur'),
            //     // DB::raw('SUM(d.total_lembur_jam) as lembur_jam'),
            //     DB::raw('SUM(d.seniority_allowance) as seniority_allowance'),
            //     DB::raw('SUM(d.insentif_jabatan) as insentif_jabatan'),
            //     DB::raw('SUM(d.insentif_kehadiran) as insentif_kehadiran'),
            //     DB::raw('SUM(d.rp_pot_hari_kerja) as rp_pot_hari_kerja'),
            //     DB::raw('SUM(d.rp_pot_jam) as rp_pot_jam'),
            //     DB::raw('SUM(d.bruto) as bruto'),
            //     DB::raw('SUM(d.bpjs_ks) as bpjs_ks'),
            //     DB::raw('SUM(d.bpjs_tk) as bpjs_tk'),
            //     DB::raw('SUM(d.total_potongan) as total_potongan'),
            //     DB::raw('SUM(d.jumlah) as jumlah'),
            //     DB::raw('SUM(d.total_pembayaran) as total_pembayaran')
            // )
            // ->whereBetween('d.tanggal_berjalan', [$tanggal_awal, $tanggal_akhir])
            // ->where('d.status_staff', 'NON STAFF') // Menggunakan nama kolom yang sesuai di tabel d/e
            // ->where('e.sub_dept_id', $value->sub_dept_id)
            // ->groupBy(
            //     'd.enroll_id',
            //     'e.sub_dept_id',
            //     'e.department_id',
            //     'e.sub_dept_name',
            //     'e.department_name',
            //     'd.status_staff'
            // )
            // ->get();

// dd($tanggal_awal, $tanggal_akhir);

//                 dd($laborCosts);
            // $gapok = $payroll1->sum('upah_per_bulan');


            // dd($payroll1->toSql(), $payroll1->getBindings());


            $potongan_kehadiran_rupiah = $laborCosts->sum('rp_pot_hari_kerja');
            $rp_pot_jam = $laborCosts->sum('rp_pot_jam');
            // $iuran_serikat_rupiah = $payroll->sum('iuran_serikat_rupiah');
            // $iuran_koperasi = $payroll->sum('iuran_koperasi');
            $bpjs_tk = $laborCosts->sum('bpjs_tk');
            $bpjs_ks = $laborCosts->sum('bpjs_ks');
            // $piutang_karyawan = $payroll->sum('piutang_karyawan');
            // $total_lembur_rupiah = $payroll->sum('total_lembur_rupiah');
            $lembur1 = $laborCosts->sum('rp_lembur_1');
            $lembur2 = $laborCosts->sum('rp_lembur_2');
            $lembur3 = $laborCosts->sum('rp_lembur_3');
            $lembur4 = $laborCosts->sum('rp_lembur_4');

            $gaji = $laborCosts->sum('gaji');
            // $koreksi_upah = $payroll->sum('koreksi_upah');
            // $potongan_upah = $payroll->sum('potongan_upah');
            $gaji_neto1 = $laborCosts->sum('jumlah');
            // $total_gaji = ($laborCosts);
            // dd( $piutang_karyawan1);


            $total_gaji = 0;
            $total_tunjangan_karyawan = 0;

            // foreach ($laborCosts as $p) {
            //     $nama_bank = $p->nama_bank ?? '-';
            //     $tunai = strtoupper($nama_bank) === 'TUNAI';

            //     $nilai_bersih = $p->upah_neto_rupiah - $p->jumlah_potongan_rupiah;


            //     if ($tunai) {
            //         // Pembulatan ke atas kelipatan 500
            //         $total_upah_thp_rupiah_pembulatan = ceil($nilai_bersih / 500) * 500;
            //     } else {
            //         // Pembulatan ke atas kelipatan 100
            //         $total_upah_thp_rupiah_pembulatan = ceil($nilai_bersih / 100) * 100;
            //     }
            //     $pembulatan = $total_upah_thp_rupiah_pembulatan - $nilai_bersih;
            //     $total_gaji += ($gapok + $pembulatan + $p->koreksi_upah) - $p->potongan_upah;

            //     // if ($p->total_kehadiran_net <= 0 && $p->koreksi_upah_rupiah == 0 && $p->total_lembur_rupiah == 0 && ($p->total_bpjs_tk != 0 || $p->total_bpjs_ks != 0)) {
            //     //     continue;
            //     // }

            //     // if ($p->total_kehadiran_net == 0) {
            //     //     continue;
            //     // }
            //     // Kalau lolos kondisi di atas, baru tambahkan
            //     $total_tunjangan_karyawan += $p->tunjangan_karyawan_rupiah;
            // }
            // dd($total_tunjangan_karyawan);
            // $gaji = $total_gaji1;
            $insentif_jabatan = $laborCosts->sum('insentif_jabatan');
            $seniority_allowance = $laborCosts->sum('seniority_allowance');
            $insentif_kehadiran = $laborCosts->sum('insentif_kehadiran');
            // $total_gaji = ($laborCosts+$insentif_jabatan+$seniority_allowance+$insentif_kehadiran);

            // $premi_karyawan = $payroll->sum('premi_karyawan');
            // $koreksi_insentif = $payroll->sum('koreksi_insentif');
            // $potongan_insentif = $payroll->sum('potongan_insentif');
            // $potongan_lembur = $payroll->sum('potongan_lembur');

            // $potongan_piutang = $payroll->sum('potongan_piutang');


            $tunjangan_karyawan_rupiah = ($insentif_jabatan + $seniority_allowance + $insentif_kehadiran );
            // $total_lembur_rupiah = $laborCosts->sum('lembur');
            $total_lembur_rupiah =($lembur1+$lembur2+$lembur3+$lembur4);
            $bonus = 0;
            // $piutang_karyawan = $potongan_piutang;
            // $piutang_bazzar = $potongan_bazzar;
            $bpjs_tk = $laborCosts->sum('bpjs_tk');
            $bpjs_ks = $laborCosts->sum('bpjs_ks');
            $potongan = ($potongan_kehadiran_rupiah + $rp_pot_jam  );
            $total_gaji = ($gaji_neto1+$total_lembur_rupiah);
            // $gaji_note=($gaji+$tunjangan_karyawan_rupiah+$total_lembur_rupiah+$bonus)-
            //             ($piutang_karyawan+$piutang_bazzar+$bpjs_tk+$bpjs_ks+$potongan);
            // $gaji_neto = ($gaji + $tunjangan_karyawan_rupiah + $total_lembur_rupiah) - ($piutang_karyawan + $piutang_bazzar + $bpjs_tk + $bpjs_ks + $potongan);
            // dd($data);


            // $gaji_neto = $gajiNetoPerDept[$value->sub_dept_id] ?? 0;

            $data = [
                'kode_bagian' => $value->sub_dept_id,
                'nama_bagian' => $value->sub_dept_name,
                'gaji' => $gaji,
                'tunjangan_karyawan_rupiah' => $tunjangan_karyawan_rupiah,
                'total_lembur_rupiah' => $total_lembur_rupiah,
                'bonus' => 0,
                'piutang_karyawan' => 0,
                'piutang_bazzar' => 0,
                'bpjs_tk' => $bpjs_tk,
                'bpjs_ks' => $bpjs_ks,
                'potongan' => $potongan,
                'potongan_insentif' => 0,
                'potongan_lembur' => 0,
                'gaji_neto' => $total_gaji,
                'jumlah_karyawn' => $laborCosts->count(),
                'periode_payroll' => $periode_payroll,
            ];
            // dd($data);
            $count = Jurnal_akhir::where('kode_bagian', $value->sub_dept_id)->where('periode_payroll', $periode_payroll)->count();

            if ($count) {
                Jurnal_akhir::where('kode_bagian', $value->sub_dept_id)->where('periode_payroll', $periode_payroll)->update($data);
            } else {
                Jurnal_akhir::create($data);
            }
        }
    }



    public function potongan($periode_payroll)
    {
        $DataKoreksiPotongan = DataKoreksiPotongan::where('periode_tanggal_koreksi', $periode_payroll)->get();
        $data = [];
        foreach ($DataKoreksiPotongan as $key => $value) {
            $data[] = [
                'enroll_id' => $value->enroll_id,
                'nik' => $value->nik,
                'employee_name' => $value->employee_name,
                'site_nirwana_id' => $value->site_nirwana_id,
                'site_nirwana_name' => $value->site_nirwana_name,
                'department_id' => $value->department_id,
                'department_name' => $value->department_name,
                'sub_dept_id' => $value->sub_dept_id,
                'sub_dept_name' => $value->sub_dept_name,
                'jumlah_rp_potongan' => $value->jumlah_rp_potongan,
                'periode_tanggal_koreksi' => $value->periode_tanggal_koreksi,
                'jenis_potongan' => $value->jenis_potongan,
                'keterangan' => $value->keterangan,
                'status_jabatan' => $value->atribut->status_staff,
            ];
        }

        $koreksi_potongan = collect($data);

        return  $koreksi_potongan;
    }
    public function koreksi($periode_payroll)
    {
        $DataKoreksiUpah = DataKoreksiUpah::where('periode_tanggal_koreksi', $periode_payroll)->get();
        $data = [];
        foreach ($DataKoreksiUpah as $key => $value) {
            $data[] = [
                'enroll_id' => $value->enroll_id,
                'nik' => $value->nik,
                'employee_name' => $value->employee_name,
                'site_nirwana_id' => $value->site_nirwana_id,
                'site_nirwana_name' => $value->site_nirwana_name,
                'department_id' => $value->department_id,
                'department_name' => $value->department_name,
                'sub_dept_id' => $value->sub_dept_id,
                'sub_dept_name' => $value->sub_dept_name,
                'jumlah_rp_potongan' => $value->jumlah_rp_potongan,
                'periode_tanggal_koreksi' => $value->periode_tanggal_koreksi,
                'jenis_koreksi' => $value->jenis_koreksi,
                'keterangan' => $value->keterangan,
                'status_jabatan' => $value->atribut->status_staff,
            ];
        }

        $data_koreksi = collect($data);

        return $data_koreksi;
    }
}
