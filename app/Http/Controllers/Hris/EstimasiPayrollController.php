<?php

namespace App\Http\Controllers\Hris;

use App\Exports\RekapPerhitunganPayrollExport;
use App\Http\Controllers\AdminBaseController;
use App\Models\RekapPerhitunganPayroll;
use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Datatables;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Exports\EstimasiPayrollExport;

/**
 * Class RekapPerhitunganPayrollController
 * @package App\Http\Controllers\Hris
 */
class EstimasiPayrollController extends AdminBaseController
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

        $this->latestData = RekapPerhitunganPayroll::latest('updated_at')->first();

        $this->loggedAdmin = Auth::guard('admin')->user();
        return View::make('hris/estimasiperhitunganpayroll', $this->data);
    }


    public function ajax_getperiodepayroll()
    {
        $query =  RekapPerhitunganPayroll::selectRaw('CONCAT(periode_tahun_payroll,"-",periode_bulan_payroll) periode_payroll')
                        ->groupby('periode_payroll')
                        ->orderby('periode_payroll', 'desc')
                        ->get();
        return $query;

    }

    // public function ajax_exportexcel(Request $request)
    // {
    //     list($year, $month) = explode('-', $request->periode_payroll);

    //     $first=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('periode_umk', NULL)->first();
    //     list($tgl_awal, $tgl_akhir) = explode(' s/d ', $first->periode_kehadiran);

    //     $updatedAt = Carbon::parse($first->updated_at);

    //     // Mengambil hanya bagian tanggal dari updated_at
    //     $tanggalUpdated = $updatedAt->format('Y-m-d');

    //     $timestamp1 = strtotime($tgl_awal);
    //     $timestamp2 = strtotime($tgl_akhir);
    //     $jumlah_hari =(abs($timestamp2 - $timestamp1) / (60 * 60 * 24)+1);

    //     $jumlah_hari_sabtu_minggu = 0;
    //     for ($i = strtotime($tgl_awal); $i <= strtotime($tgl_akhir); $i += 86400) {
    //         if ((date('N', $i) == 6)||(date('N', $i) == 7)) {
    //             $jumlah_hari_sabtu_minggu++;
    //         }
    //     }
    //     $jumlah_hari_kerja= $jumlah_hari-$jumlah_hari_sabtu_minggu;

    //     $sisa_hari_kerja = 0;
    //     for ($i = strtotime($tanggalUpdated); $i <= strtotime($tgl_akhir); $i += 86400) {
    //         if ((date('N', $i) != 6)&&(date('N', $i) != 7)) {
    //             $sisa_hari_kerja++;
    //         }
    //     }
    //     $info=[
    //         'periode_payroll'=>$first->periode_kehadiran,
    //         'tanggal_awal'=>$tgl_awal,
    //         'tanggal_akhir'=>$tgl_akhir,
    //         'tanggal_update'=> $tanggalUpdated,
    //         'jumlah_hari'=>$jumlah_hari,
    //         'jumlah_hari_kerja'=>$jumlah_hari_kerja,
    //         'sisa_hari_kerja'=>$sisa_hari_kerja,
    //     ];
    //     $query=RekapPerhitunganPayroll::query();
    //     //$query->where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('total_kehadiran_net','>','0');
    //      $query->where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('periode_umk', NULL)
    //             ->where(function ($query) use ($tgl_awal) {
    //                 $query->whereNull('tanggal_resign')
    //                     ->orWhere('tanggal_resign', '>', $tgl_awal);
    //             });


    //     if($request->status_staff!==null && $request->status_staff!=='') {
    //         $query->where('kategori_karyawan',$request->status_staff);
    //     }
    //     $query=$query->get();

    //     $data=[];
    //     foreach ($query as $key => $value) {
    //         $gross_salary=($value->total_kehadiran_net+$value->kehadiran_m_estimasi)*$value->upah_per_hari;
    //         $rp_pot_jam=$value->potongan_dtpc_rupiah+$value->potongan_iks_rupiah;
    //         $thp=$gross_salary+$value->total_lembur_rupiah+ $value->tunjangan_karyawan_rupiah;
    //         $potongan=$rp_pot_jam+$value->total_bpjs_tk+$value->total_bpjs_ks;
    //         $estimasi_thp= $thp-$potongan;
    //         $data[]=[
    //             "enroll_id"         => $value->enroll_id,
    //             "nik"               => $value->nik,
    //             "employee_name"     => $value->employee_name,
    //             "nama_bagian"       => $value->nama_bagian,
    //             "aktif_karyawan"    => $value->aktif_karyawan,
    //             "kategori_karyawan" => $value->kategori_karyawan,
    //             "kehadiran_iby"     => $value->kehadiran_iby,
    //             "kehadiran_itb"     => $value->kehadiran_itb,
    //             "kehadiran_lby"     => $value->kehadiran_lby,
    //             "kehadiran_lsm"     => $value->kehadiran_lsm,
    //             "kehadiran_dt"      => $value->kehadiran_dt,
    //             "kehadiran_pc"      => $value->kehadiran_pc,
    //             "kehadiran_dtpc"    => $value->kehadiran_dtpc,
    //             "kehadiran_m"       => $value->kehadiran_m,
    //             "kehadiran_r"       => $value->kehadiran_r,
    //             "kehadiran_tk"      => $value->kehadiran_tk,
    //             "kehadiran_ok"      => $value->kehadiran_ok,
    //             "total_kehadiran"   => $value->total_kehadiran,
    //             "total_kehadiran_net" => $value->total_kehadiran_net,
    //             "updated_at"        => $value->updated_at,
    //             "kehadiran_m_estimasi" => $value->kehadiran_m_estimasi,
    //             "upah_per_bulan"    => $value->upah_per_bulan,
    //             // "upah_per_hari"     => $value->upah_per_hari,
    //             'total_estimasi'    => $value->total_kehadiran_net+$value->kehadiran_m_estimasi,
    //             'gross_salary'      => $gross_salary,
    //             "tunjangan_karyawan_rupiah" => $value->tunjangan_karyawan_rupiah,
    //             "total_lembur_rupiah" =>$value->total_lembur_rupiah,
    //             "total_bpjs_tk"     => $value->total_bpjs_tk,
    //             "total_bpjs_ks"     => $value->total_bpjs_ks,
    //             'rp_pot_jam'        => $rp_pot_jam,
    //             'estimasi_thp'      =>$estimasi_thp
    //         ];
    //     }
    //     // dd($data);
    //     return Excel::download(new EstimasiPayrollExport($data,$info),'Estimasi_Payroll'.time().'.xlsx');
    //     // return (new RekapPerhitunganPayrollExport)->exportParams($periode_payroll)->download($fileName);

    // }
        public function ajax_exportexcel(Request $request)
    {
        list($year, $month) = explode('-', $request->periode_payroll);

        $first=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('periode_umk', NULL)->first();
        list($tgl_awal, $tgl_akhir) = explode(' s/d ', $first->periode_kehadiran);
        $periode = Carbon::parse($tgl_awal)->format('m/d/Y') . ' - ' . Carbon::parse($tgl_akhir)->format('m/d/Y');

        $updatedAt = Carbon::parse($first->updated_at);

        // Mengambil hanya bagian tanggal dari updated_at
        // $tanggalUpdated = $updatedAt->format('Y-m-d');
        $today          = Carbon::today();
        $tanggalUpdated = $today->format('Y-m-d');
        // $awal  = Carbon::parse($tgl_awal)->startOfDay();
        // $akhir = Carbon::parse($tgl_akhir)->startOfDay();

        // // Batas akhir perhitungan = hari ini, tapi tidak boleh lewat tanggal akhir periode
        // $batas = $today->lessThan($akhir) ? $today->copy() : $akhir->copy();

        // // Jumlah hari & hari kerja: dari tanggal awal sampai hari ini
        // $jumlah_hari = 0;
        // $jumlah_hari_sabtu_minggu = 0;

        // if ($batas->greaterThanOrEqualTo($awal)) {   // kalau periode belum mulai, tetap 0
        //     for ($d = $awal->copy(); $d->lessThanOrEqualTo($batas); $d->addDay()) {
        //         $jumlah_hari++;
        //         if ($d->isWeekend()) {               // Sabtu / Minggu
        //             $jumlah_hari_sabtu_minggu++;
        //         }
        //     }
        // }
        // $jumlah_hari_kerja = $jumlah_hari - $jumlah_hari_sabtu_minggu;

        // // Sisa hari kerja: dari hari ini sampai tanggal akhir periode
        // $sisa_hari_kerja = 0;
        // $mulaiSisa = $today->greaterThan($awal) ? $today->copy() : $awal->copy();
        // for ($d = $mulaiSisa; $d->lessThanOrEqualTo($akhir); $d->addDay()) {
        //     if (!$d->isWeekend()) {
        //         $sisa_hari_kerja++;
        //     }
        // }

        $timestamp1 = strtotime($tgl_awal);
        $timestamp2 = strtotime($tgl_akhir);
        $jumlah_hari =(abs($timestamp2 - $timestamp1) / (60 * 60 * 24)+1);

        $jumlah_hari_sabtu_minggu = 0;
        for ($i = strtotime($tgl_awal); $i <= strtotime($tgl_akhir); $i += 86400) {
            if ((date('N', $i) == 6)||(date('N', $i) == 7)) {
                $jumlah_hari_sabtu_minggu++;
            }
        }
        $jumlah_hari_kerja= $jumlah_hari-$jumlah_hari_sabtu_minggu;

        $sisa_hari_kerja = 0;
        for ($i = strtotime($tanggalUpdated); $i <= strtotime($tgl_akhir); $i += 86400) {
            if ((date('N', $i) != 6)&&(date('N', $i) != 7)) {
                $sisa_hari_kerja++;
            }
        }
        $info=[
            'periode_payroll'=>$first->periode_kehadiran,
            'tanggal_awal'=>$tgl_awal,
            'tanggal_akhir'=>$tgl_akhir,
            'tanggal_update'=> $tanggalUpdated,
            'jumlah_hari'=>$jumlah_hari,
            'jumlah_hari_kerja'=>$jumlah_hari_kerja,
            'sisa_hari_kerja'=>$sisa_hari_kerja,
        ];
        // dd($tgl_awal,$tgl_akhir,$first->periode_kehadiran,$periode);
        $query=RekapPerhitunganPayroll::query();
        //$query->where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('total_kehadiran_net','>','0');
         $query->where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('periode_umk', NULL)
                ->where(function ($query) use ($tgl_awal) {
                    $query->whereNull('tanggal_resign')
                        ->orWhere('tanggal_resign', '>', $tgl_awal);
                });


        if($request->status_staff!==null && $request->status_staff!=='') {
            $query->where('kategori_karyawan',$request->status_staff);
        }
        $query=$query->get();
        $filterStaff = '';
            if ($request->status_staff !== null && $request->status_staff !== '') {
                $filterStaff = " AND b.status_staff = " . DB::getPdo()->quote($request->status_staff);
            }
        $sql= DB::select("SELECT a.enroll_id,b.nik,b.employee_name,b.site_nirwana_id site_nirwana_id,b.site_nirwana_name site_nirwana_name, b.department_id department_id,b.department_name department_name,b.sub_dept_id sub_dept_id,b.sub_dept_name sub_dept_name,b.join_date join_date,b.tanggal_resign tanggal_resign,b.status_aktif status_aktif,b.status_staff status_staff,b.jenis_kelamin jenis_kelamin,
                    count( IF( c.kode_ijin_payroll = 'IBY', 1, NULL )) kehadiran_iby,
                    count( IF( c.kode_ijin_payroll = 'ITB', 1, NULL )) kehadiran_itb,
                    count(IF( a.status_absen = 'LN' AND a.kode_hari NOT IN ( 5, 6 ), 1, NULL )) kehadiran_lby,
                    CASE
                        WHEN b.sub_dept_id IN ( 'DEP08SUB005', 'DEPNK01SUB001', 'DEPNK02SUB003', 'DEPNK05SUB001' )
                            AND b.jenis_kelamin = 'LAKI-LAKI'
                            AND b.enroll_id != 7445
                            AND b.status_staff != 'STAFF'
                        THEN
                            count(IF( c.kode_ijin_payroll = 'IBY', 1, NULL ))+
                            count(IF( a.mulai_jam_kerja IS NULL AND status_absen = 'M', 1, NULL ))
                        ELSE
                            count(IF( a.kode_hari IN ( 5, 6 ), 1, NULL ))
                    END lsm,
                    count(IF( a.jumlah_menit_absen_dt != 0 AND a.jumlah_menit_absen_pc = 0 AND status_absen IS NULL, 1, NULL )) kehadiran_dt,
                    count(IF( a.jumlah_menit_absen_dt = 0 AND a.jumlah_menit_absen_pc != 0 AND status_absen IS NULL, 1, NULL )) kehadiran_pc,
                    count(IF( a.jumlah_menit_absen_dt != 0 AND a.jumlah_menit_absen_pc != 0 AND status_absen IS NULL, 1, NULL )) kehadiran_dtpc,
                    CASE
                        WHEN b.sub_dept_id IN ( 'DEP08SUB005', 'DEPNK01SUB001', 'DEPNK02SUB003', 'DEPNK05SUB001' )
                            AND b.jenis_kelamin = 'LAKI-LAKI'
                            AND b.enroll_id != 7445
                            AND b.status_staff != 'STAFF'
                        THEN
                            count( IF( a.status_absen = 'M' AND a.mulai_jam_kerja IS NOT NULL, 1, NULL ))+
                            count(IF( a.status_absen = 'TL' AND a.mulai_jam_kerja IS NOT NULL, 1, NULL ))
                        ELSE
                            count( IF( a.status_absen IN ( 'M' ), 1, NULL ))+
                            count(IF( a.status_absen = 'TL' AND a.mulai_jam_kerja IS NOT NULL, 1, NULL ))
                    END kehadiran_m,
                    count(IF( a.status_absen = 'R', 1, NULL )) kehadiran_r,
                    CASE
                        WHEN b.sub_dept_id IN ( 'DEP08SUB005', 'DEPNK01SUB001', 'DEPNK02SUB003', 'DEPNK05SUB001' )
                            AND b.jenis_kelamin = 'LAKI-LAKI'
                            AND b.enroll_id != 7445
                            AND b.status_staff != 'STAFF'
                        THEN
                            count(IF(( a.status_absen IS NULL OR a.status_absen = '' )  AND a.mulai_jam_kerja IS NOT NULL  AND jumlah_menit_absen_dtpc = 0,1,NULL ))+
                            count(IF( a.status_absen = 'IKS' AND a.mulai_jam_kerja IS NOT NULL, 1, NULL ))
                        ELSE
                            count( IF ((a.status_absen IS NULL OR a.status_absen = '' ) AND jumlah_menit_absen_dtpc = 0 AND kode_hari NOT IN ( 5, 6 ),1,NULL ))+
                            count(IF( a.status_absen = 'IKS' AND a.mulai_jam_kerja IS NOT NULL, 1, NULL ))
                    END kehadiran_ok,
                    CASE
                        WHEN b.sub_dept_id IN ( 'DEP08SUB005', 'DEPNK01SUB001', 'DEPNK02SUB003', 'DEPNK05SUB001' )
                            AND b.jenis_kelamin = 'LAKI-LAKI'
                            AND b.enroll_id != 7445
                            AND b.status_staff != 'STAFF'
                        THEN count(IF((a.status_absen IS NULL OR a.status_absen = '' ) AND a.mulai_jam_kerja IS NOT NULL AND jumlah_menit_absen_dtpc = 0,1,NULL))+
                            count(IF( a.status_absen = 'IKS' AND a.mulai_jam_kerja IS NOT NULL, 1, NULL ))+
                            count(IF( a.jumlah_menit_absen_dt != 0 AND a.jumlah_menit_absen_pc = 0 AND status_absen IS NULL, 1, NULL ))+
                            count(IF( a.jumlah_menit_absen_dt = 0 AND a.jumlah_menit_absen_pc != 0 AND status_absen IS NULL, 1, NULL ))+
                            count(IF( a.jumlah_menit_absen_dt != 0 AND a.jumlah_menit_absen_pc != 0 AND status_absen IS NULL, 1, NULL ))+
                            count(IF( a.status_absen = 'LN' AND a.kode_hari NOT IN ( 5, 6 ), 1, NULL ))+
                            count(IF( c.kode_ijin_payroll = 'IBY', 1, NULL ))
                        ELSE
                            count(IF((a.status_absen IS NULL OR a.status_absen = '' ) AND jumlah_menit_absen_dtpc = 0 AND kode_hari NOT IN ( 5, 6 ),1, NULL ))+
                            count(IF( a.status_absen = 'IKS' AND a.mulai_jam_kerja IS NOT NULL, 1, NULL ))+
                            count(IF( a.jumlah_menit_absen_dt != 0 AND a.jumlah_menit_absen_pc = 0 AND status_absen IS NULL, 1, NULL ))+
                            count(IF( a.jumlah_menit_absen_dt = 0 AND a.jumlah_menit_absen_pc != 0 AND status_absen IS NULL, 1, NULL ))+
                            count(IF( a.jumlah_menit_absen_dt != 0 AND a.jumlah_menit_absen_pc != 0 AND status_absen IS NULL, 1, NULL ))+
                            count(IF( a.status_absen = 'LN' AND a.kode_hari NOT IN ( 5, 6 ), 1, NULL ))+
                            count(IF( c.kode_ijin_payroll = 'IBY', 1, NULL ))
                    END total_kehadiran_net,
                    CASE
                        WHEN b.sub_dept_id IN ( 'DEP08SUB005', 'DEPNK01SUB001', 'DEPNK02SUB003', 'DEPNK05SUB001' )
                            AND b.jenis_kelamin = 'LAKI-LAKI'
                            AND b.enroll_id != 7445
                            AND b.status_staff != 'STAFF'
                        THEN
                            count(IF((a.status_absen IS NULL OR a.status_absen = '' ) AND a.mulai_jam_kerja IS NOT NULL AND jumlah_menit_absen_dtpc = 0,1,NULL))+
                            count(IF( a.jumlah_menit_absen_dt != 0 AND a.jumlah_menit_absen_pc = 0 AND status_absen IS NULL, 1, NULL ))+
                            count(IF( a.jumlah_menit_absen_dt = 0 AND a.jumlah_menit_absen_pc != 0 AND status_absen IS NULL, 1, NULL ))+
                            count(IF( a.jumlah_menit_absen_dt != 0 AND a.jumlah_menit_absen_pc != 0 AND status_absen IS NULL, 1, NULL ))+
                            count(IF( c.kode_ijin_payroll = 'ITB', 1, NULL ))+
                            count(IF( a.status_absen = 'LN' AND a.kode_hari NOT IN ( 5, 6 ), 1, NULL ))+
                            count(IF( c.kode_ijin_payroll = 'IBY', 1, NULL ))+
                            count(IF( a.mulai_jam_kerja IS NULL AND status_absen = 'M', 1, NULL ))+
                            count(IF( a.status_absen = 'M' AND a.mulai_jam_kerja IS NOT NULL, 1, NULL ))+
                            count(IF( a.status_absen = 'TL' AND a.mulai_jam_kerja IS NOT NULL, 1, NULL ))+
                            count(IF( a.status_absen = 'R', 1, NULL ))
                        ELSE
                            count(IF((a.status_absen IS NULL OR a.status_absen = '' ) AND a.mulai_jam_kerja IS NOT NULL AND jumlah_menit_absen_dtpc = 0,1,NULL ))+
                            count(IF( a.jumlah_menit_absen_dt != 0 AND a.jumlah_menit_absen_pc = 0 AND status_absen IS NULL, 1, NULL ))+
                            count(IF( a.jumlah_menit_absen_dt = 0 AND a.jumlah_menit_absen_pc != 0 AND status_absen IS NULL, 1, NULL ))+
                            count(IF( a.jumlah_menit_absen_dt != 0 AND a.jumlah_menit_absen_pc != 0 AND status_absen IS NULL, 1, NULL ))+
                            count(IF( c.kode_ijin_payroll = 'ITB', 1, NULL ))+
                            count(IF( a.status_absen = 'LN' AND a.kode_hari NOT IN ( 5, 6 ), 1, NULL ))+
                            count(IF( c.kode_ijin_payroll = 'IBY', 1, NULL ))+
                            count(IF( a.kode_hari IN ( 5, 6 ), 1, NULL ))+
                            count(IF( a.status_absen IN ( 'M' ), 1, NULL ))+
                            count(IF( a.status_absen = 'TL' AND a.mulai_jam_kerja IS NOT NULL, 1, NULL ))+
                            count(IF( a.status_absen = 'R', 1, NULL ))
                    END kehadiran_tk,
                    CASE
                        WHEN b.sub_dept_id IN ( 'DEP08SUB005', 'DEPNK01SUB001', 'DEPNK02SUB003', 'DEPNK05SUB001' )
                            AND b.jenis_kelamin = 'LAKI-LAKI'
                            AND b.enroll_id != 7445
                            AND b.status_staff != 'STAFF'
                        THEN
                            count(IF( c.kode_ijin_payroll = 'IBY', 1, NULL ))+
                            count(IF( c.kode_ijin_payroll = 'ITB', 1, NULL ))+
                            count(IF( a.mulai_jam_kerja IS NULL AND status_absen = 'M', 1, NULL ))+
                            count(IF( a.jumlah_menit_absen_dt != 0 AND a.jumlah_menit_absen_pc != 0 AND status_absen IS NULL, 1, NULL ))+
                            count(IF( a.status_absen = 'M' AND a.mulai_jam_kerja IS NOT NULL, 1, NULL ))+
                            count(IF( a.status_absen = 'R', 1, NULL ))+
                            count(IF((a.status_absen IS NULL OR a.status_absen = '' ) AND a.mulai_jam_kerja IS NOT NULL AND jumlah_menit_absen_dtpc = 0,1,NULL))+
                            count(IF( a.status_absen = 'LN' AND a.kode_hari NOT IN ( 5, 6 ), 1, NULL ))+
                            count(IF( a.jumlah_menit_absen_dt != 0 AND a.jumlah_menit_absen_pc = 0 AND status_absen IS NULL, 1, NULL ))+
                            count(IF( a.jumlah_menit_absen_dt = 0 AND a.jumlah_menit_absen_pc != 0 AND status_absen IS NULL, 1, NULL ))+
                            count(IF( a.status_absen = 'TL' AND a.mulai_jam_kerja IS NOT NULL, 1, NULL ))
                        ELSE
                            count(IF((a.status_absen IS NULL OR a.status_absen = '' ) AND a.mulai_jam_kerja IS NOT NULL AND jumlah_menit_absen_dtpc = 0,1,NULL))+
                            count(IF( a.jumlah_menit_absen_dt != 0 AND a.jumlah_menit_absen_pc = 0 AND status_absen IS NULL, 1, NULL ))+
                            count(IF( a.jumlah_menit_absen_dt = 0 AND a.jumlah_menit_absen_pc != 0 AND status_absen IS NULL, 1, NULL ))+
                            count(IF( a.jumlah_menit_absen_dt != 0 AND a.jumlah_menit_absen_pc != 0 AND status_absen IS NULL, 1, NULL ))+
                            count(IF( c.kode_ijin_payroll = 'ITB', 1, NULL ))+
                            count(IF( a.status_absen = 'LN' AND a.kode_hari NOT IN ( 5, 6 ), 1, NULL ))+
                            count(IF( c.kode_ijin_payroll = 'IBY', 1, NULL ))+
                            count(IF( a.kode_hari IN ( 5, 6 ), 1, NULL ))+
                            count(IF( a.status_absen IN ( 'M' ), 1, NULL ))+
                            count(IF( a.status_absen = 'TL' AND a.mulai_jam_kerja IS NOT NULL, 1, NULL ))+
                            count(IF( a.status_absen = 'R', 1, NULL ))+
                            count(IF( a.status_absen = 'IKS' AND mulai_jam_kerja IS NOT NULL, 1, NULL ))
                        END total_kehadiran,
                    count(IF( a.status_absen = 'DL', 1, NULL )) kehadiran_dl,
                    count(IF( a.status_absen = 'CB', 1, NULL )) kehadiran_cb,
                    count(IF( a.status_absen = 'CBD', 1, NULL )) kehadiran_cbd,
                    count(IF( a.status_absen = 'CG', 1, NULL )) kehadiran_cg,
                    count(IF( a.status_absen = 'CH', 1, NULL )) kehadiran_ch,
                    count(IF( a.status_absen = 'CM', 1, NULL )) kehadiran_cm,
                    count(IF( a.status_absen = 'CN', 1, NULL )) kehadiran_cn,
                    count(IF( a.status_absen = 'CT', 1, NULL )) kehadiran_ct,
                    count(IF( a.status_absen = 'IG', 1, NULL )) kehadiran_ig,
                    count(IF( a.status_absen = 'IM', 1, NULL )) kehadiran_im,
                    count(IF( a.status_absen = 'KA', 1, NULL )) kehadiran_ka,
                    count(IF( a.status_absen = 'KM', 1, NULL )) kehadiran_km,
                    count(IF( a.status_absen = 'KR', 1, NULL )) kehadiran_kr,
                    count(IF( a.status_absen = 'NA', 1, NULL )) kehadiran_na,
                    count(IF( a.status_absen = 'PP', 1, NULL )) kehadiran_pp,
                    count(IF( a.status_absen = 'I', 1, NULL )) kehadiran_i,
                    count(IF( a.status_absen = 'LP', 1, NULL )) kehadiran_lp,
                    count(IF( a.status_absen = 'L', 1, NULL )) kehadiran_l,
                    count(IF( a.status_absen = 'TL', 1, NULL )) kehadiran_tl,
                    count(IF( a.status_absen = 'IKS', 1, NULL )) kehadiran_iks,
                    count(IF( a.status_absen = 'S', 1, NULL )) kehadiran_s,
                    count(IF( a.status_absen = 'TL' AND a.tanggal_berjalan >= curdate() AND a.mulai_jam_kerja IS NOT NULL, 1, NULL ))+ count(IF( a.status_absen = 'M' AND a.tanggal_berjalan >= curdate(), 1, NULL )) kehadiran_m_estimasi,
                    dk.koreksi_upah,
                    dp.potongan,
                    SUM(a.jumlah_menit_absen_dtpc) AS jumlah_menit_absen_dtpc,
                    r.lembur1_rupiah,
                    r.lembur2_rupiah,
                    r.lembur3_rupiah,
                    r.lembur4_rupiah
                FROM
                    master_data_absen_kehadiran a
                    INNER JOIN ( SELECT * FROM employee_atribut WHERE status_aktif = 'AKTIF' OR ( status_aktif = 'TIDAK AKTIF' AND tanggal_resign >= '$tgl_awal' )) b ON a.enroll_id = b.enroll_id
                    LEFT JOIN  ( SELECT * FROM ref_absen_ijin WHERE kode_absen_ijin NOT IN ( 'M', 'IKS' )) c ON a.status_absen = c.kode_absen_ijin
                    LEFT JOIN  (SELECT * FROM rekap_perhitungan_lembur WHERE tanggal_berjalan >= '$tgl_awal' and tanggal_berjalan <= '$tgl_akhir' GROUP BY enroll_id )  r ON a.enroll_id = r.enroll_id
                    LEFT JOIN  (SELECT SUM(jumlah_rp_potongan) as koreksi_upah,enroll_id,periode_tanggal_koreksi FROM data_koreksi_upah WHERE periode_tanggal_koreksi = '$periode' GROUP BY enroll_id) dk on a.enroll_id = dk.enroll_id
                    LEFT JOIN  (SELECT SUM(jumlah_rp_potongan) AS potongan, enroll_id FROM data_koreksi_potongan WHERE periode_tanggal_koreksi = '$periode' GROUP BY enroll_id) dp on a.enroll_id = dp.enroll_id
                WHERE
                    a.tanggal_berjalan >= '$tgl_awal'
                    AND a.tanggal_berjalan <= '$tgl_akhir'  $filterStaff
                GROUP BY
                    a.enroll_id
                ORDER BY
                    a.enroll_id
        ");
       $rekap = $query->keyBy('enroll_id');

$data = [];
foreach ($sql as $live) {                       // $live = hasil raw SQL (kehadiran live)
    $r = $rekap->get($live->enroll_id);         // $r    = data RekapPerhitunganPayroll (bisa null)

    $upah_per_hari = $r->upah_per_hari ?? 0;

    $kehadiran = $live->total_kehadiran_net + $live->kehadiran_m_estimasi + $live->kehadiran_itb + $live->lsm;
    $selisih   = $live->total_kehadiran - $kehadiran;
    $total_lembur = $live->lembur1_rupiah + $live->lembur2_rupiah + $live->lembur3_rupiah + $live->lembur4_rupiah;
    $sub_dept_420 = ['DEP08SUB005', 'DEPNK01SUB001', 'DEPNK02SUB003', 'DEPNK05SUB001'];

// pembagi menit kerja per hari
    $salary_bulanan = $r->upah_per_bulan ?? 0;
    $menit_per_hari = (in_array($live->sub_dept_id, $sub_dept_420, true)
                        && $live->jenis_kelamin === 'LAKI-LAKI'
                        && $live->enroll_id != 7445) ? 420 : 480;
    $pembagi = $live->total_kehadiran_net + $live->kehadiran_m_estimasi;

$gaji_menit = $pembagi > 0
    ? ($salary_bulanan / $pembagi) / $menit_per_hari
    : 0;

    $pot_dtpc   = $gaji_menit * ($live->jumlah_menit_absen_dtpc);
    //   dd( $live->jumlah_menit_absen_dtpc,$pot_dtpc);
    $koreksi_upah = $live->koreksi_upah ?? 0;
    $potongan = $live->potongan ?? 0;


    // if ($selisih == 0) {
    //     $gross_salary = ($live->total_kehadiran_net + $live->kehadiran_m_estimasi) * $upah_per_hari;
    // } else {

    // }
    $gross_salary = ($live->total_kehadiran_net + $live->kehadiran_m_estimasi ) * $upah_per_hari;
    $rp_pot_jam = ($pot_dtpc ?? 0) + ($r->potongan_iks_rupiah ?? 0);

    $thp = ($gross_salary
            + ($total_lembur ?? 0)
            + ($r->tunjangan_karyawan_rupiah ?? 0)
            + ($r->koreksi_insentif ?? 0)
            + ($r->koreksi_lembur ?? 0)
            + ($koreksi_upah ?? 0))
         - (($r->potongan_piutang ?? 0)
            + ($r->potongan_insentif ?? 0)
            + ($r->potongan_lembur ?? 0)
            + ($r->potongan_upah ?? 0)
            + ($r->total_bpjs_tk ?? 0)
            + ($r->total_bpjs_ks ?? 0)
            + $rp_pot_jam);

    $data[] = [
        // --- dari raw SQL ($live) ---
        "enroll_id"            => $live->enroll_id,
        "nik"                  => $live->nik,
        "employee_name"        => $live->employee_name,
        "nama_bagian"          => $live->sub_dept_name,
        "aktif_karyawan"       => $live->status_aktif,
        "kategori_karyawan"    => $live->status_staff,
        "kehadiran_iby"        => $live->kehadiran_iby,
        "kehadiran_itb"        => $live->kehadiran_itb,
        "kehadiran_lby"        => $live->kehadiran_lby,
        "kehadiran_lsm"        => $live->lsm,
        "kehadiran_dt"         => $live->kehadiran_dt,
        "kehadiran_pc"         => $live->kehadiran_pc,
        "kehadiran_dtpc"       => $live->kehadiran_dtpc,
        "kehadiran_m"          => $live->kehadiran_m,
        "kehadiran_r"          => $live->kehadiran_r,
        "kehadiran_tk"         => $live->kehadiran_tk,
        "kehadiran_ok"         => $live->kehadiran_ok,
        "total_kehadiran"      => $live->total_kehadiran,
        "total_kehadiran_net"  => $live->total_kehadiran_net,
        "kehadiran_m_estimasi" => $live->kehadiran_m_estimasi,
        'total_estimasi'       => $live->total_kehadiran_net + $live->kehadiran_m_estimasi,

        // --- dari Eloquent ($r) ---
        "updated_at"           => $r->updated_at ?? null,
        "upah_per_bulan"       => $r->upah_per_bulan ?? 0,
        "tunjangan_karyawan_rupiah" => $r->tunjangan_karyawan_rupiah ?? 0,
        "total_lembur_rupiah"  => $total_lembur ?? 0,
        "total_bpjs_tk"        => $r->total_bpjs_tk ?? 0,
        "total_bpjs_ks"        => $r->total_bpjs_ks ?? 0,
        "koreksi_upah"         => $koreksi_upah ?? 0,
        "potongan"             => $potongan ?? 0,

        // --- hasil hitungan ---
        'gross_salary'         => $gross_salary,
        'rp_pot_jam'           => $rp_pot_jam,
        'estimasi_thp'         => $thp,
    ];
}

        // $query=$query->get();

        // $data=[];
        // foreach ($query as $key => $value) {
        //     $kehadiran = $value->total_kehadiran_net+$value->kehadiran_m_estimasi+$value->kehadiran_kehadiran_itb+$value->kehadiran_lsm;
        //     $selisish = $value->total_kehadiran - $kehadiran;
        //     // dd($selisish);

        //     if ($selisish==0) {
        //         $gross_salary=($value->total_kehadiran_net+$value->kehadiran_m_estimasi)*$value->upah_per_hari;
        //     }else{
        //         $gross_salary=($value->total_kehadiran_net+$value->kehadiran_m_estimasi+1)*$value->upah_per_hari;
        //     }

        //     // $gross_salary=($value->total_kehadiran_net+$value->kehadiran_m_estimasi)*$value->upah_per_hari;
        //     $rp_pot_jam=$value->potongan_dtpc_rupiah+$value->potongan_iks_rupiah;
        //     // $thp=$gross_salary+$value->total_lembur_rupiah+ $value->tunjangan_karyawan_rupiah;
        //     $thp=($gross_salary+$value->total_lembur_rupiah+ $value->tunjangan_karyawan_rupiah+$value->koreksi_insentif+$value->koreksi_lembur+$value->koreksi_upah)-($value->potongan_piutang+$value->potongan_insentif+$value->potongan_lembur+$value->potongan_upah+$value->total_bpjs_tk+$value->total_bpjs_ks+$rp_pot_jam);
        //     // $potongan=$rp_pot_jam+$value->total_bpjs_tk+$value->total_bpjs_ks;
        //     $estimasi_thp= $thp;
        //     $data[]=[
        //         "enroll_id"         => $value->enroll_id,
        //         "nik"               => $value->nik,
        //         "employee_name"     => $value->employee_name,
        //         "nama_bagian"       => $value->nama_bagian,
        //         "aktif_karyawan"    => $value->aktif_karyawan,
        //         "kategori_karyawan" => $value->kategori_karyawan,
        //         "kehadiran_iby"     => $value->kehadiran_iby,
        //         "kehadiran_itb"     => $value->kehadiran_itb,
        //         "kehadiran_lby"     => $value->kehadiran_lby,
        //         "kehadiran_lsm"     => $value->kehadiran_lsm,
        //         "kehadiran_dt"      => $value->kehadiran_dt,
        //         "kehadiran_pc"      => $value->kehadiran_pc,
        //         "kehadiran_dtpc"    => $value->kehadiran_dtpc,
        //         "kehadiran_m"       => $value->kehadiran_m,
        //         "kehadiran_r"       => $value->kehadiran_r,
        //         "kehadiran_tk"      => $value->kehadiran_tk,
        //         "kehadiran_ok"      => $value->kehadiran_ok,
        //         "total_kehadiran"   => $value->total_kehadiran,
        //         "total_kehadiran_net" => $value->total_kehadiran_net,
        //         "updated_at"        => $value->updated_at,
        //         "kehadiran_m_estimasi" => $value->kehadiran_m_estimasi,
        //         "upah_per_bulan"    => $value->upah_per_bulan,
        //         // "upah_per_hari"     => $value->upah_per_hari,
        //         'total_estimasi'    => $value->total_kehadiran_net+$value->kehadiran_m_estimasi,
        //         'gross_salary'      => $gross_salary,
        //         "tunjangan_karyawan_rupiah" => $value->tunjangan_karyawan_rupiah,
        //         "total_lembur_rupiah" =>$value->total_lembur_rupiah,
        //         "total_bpjs_tk"     => $value->total_bpjs_tk,
        //         "total_bpjs_ks"     => $value->total_bpjs_ks,
        //         'rp_pot_jam'        => $rp_pot_jam,
        //         'estimasi_thp'      =>$estimasi_thp
        //     ];
        // }
        // dd($data);
        // dd($data, $info);
        return Excel::download(new EstimasiPayrollExport($data,$info),'Estimasi_Payroll'.time().'.xlsx');
        // return (new RekapPerhitunganPayrollExport)->exportParams($periode_payroll)->download($fileName);

    }


}
