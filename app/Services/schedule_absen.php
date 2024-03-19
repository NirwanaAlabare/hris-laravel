<?php

namespace App\Services;

use App\Models\MasterDataAbsenKehadiran;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\RefAbsenIjin;
use App\Models\RekapKehadiranKaryawan;




class schedule_absen{
    public function RekapAbsen()
    {
        // $bulan_sekarang = strtotime(date('Y-m'));
        $bulan_sekarang1 = strtotime(date('2023-03'));

        $bulan_sebelum= strtotime("-1 month", $bulan_sekarang1);
        $bulan_sebelum=date('Y-m-', $bulan_sebelum);
        $bulan_sekarang=date('Y-m-', $bulan_sekarang1);

        $tanggal_awal=$bulan_sebelum.'26';
        $tanggal_akhir=$bulan_sekarang.'25';

        $bulan=date('m', $bulan_sekarang1);
        $tahun=date('Y', $bulan_sekarang1);
        $ijin_bayar=RefAbsenIjin::where('kode_ijin_payroll','IBY')->get()->toArray();
        $IBY=array_column($ijin_bayar,'kode_absen_ijin');

        $tidak_bayar=RefAbsenIjin::where('kode_ijin_payroll','ITB')->where('kode_absen_ijin','!=','M')->where('kode_absen_ijin','!=','IKS')->get()->toArray();
        $ITB=array_column($tidak_bayar,'kode_absen_ijin');

        $timestamp1 = strtotime($tanggal_awal);
        $timestamp2 = strtotime($tanggal_akhir);
        $jumlah_hari =(abs($timestamp2 - $timestamp1) / (60 * 60 * 24)+1);

        $jumlah_hari_sabtu_minggu = 0;

        for ($i = strtotime($tanggal_awal); $i <= strtotime($tanggal_akhir); $i += 86400) {
            if ((date('N', $i) == 6)||(date('N', $i) == 7)) {
                $jumlah_hari_sabtu_minggu++;
            }
        }

        // $x=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->where('department_id','DEP10')->get()->groupby('enroll_id');
        // $x=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->where('enroll_id','1065')->get()->groupby('enroll_id');
        $x=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->get()->groupby('enroll_id');

        foreach ($x as $key => $value) {
            $IBY_employe=$value->wherein('status_absen', $IBY)->count();
            $LBY_employe=$value->where('status_absen','LN')->whereNotin('kode_hari', ['5','6'])->count();
            $ITB_employe=$value->wherein('status_absen', $ITB)->count();
            $lsm_employe=$value->wherein('kode_hari', ['5','6'])->count();
            $dt_employe=$value->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','0')->count();
            $pc_employe=$value->where('jumlah_menit_absen_pc','>','0')->where('jumlah_menit_absen_dt','0')->count();

            $dtpc_employe=$value->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','>','0')->count();



            $absen_M=$value->where('status_absen','M')->count();
            $absen_R=$value->where('status_absen','R')->count();
            $absen_TL=$value->where('status_absen','TL')->count();
            $absen_IKS=$value->where('status_absen','IKS')->count();
            $absen_ok=$value->whereNotin('kode_hari', ['5','6'])->where('status_absen',null)->where('jumlah_menit_absen_dtpc','0')->count();
            $absen_ok=$absen_ok+$absen_IKS;
            $unik=$angka_string =sprintf("%04d", $key);
            $kode_rekap_kehadiran=$bulan_sebelum.$unik;
            $total_kehadiran=$IBY_employe+$ITB_employe+$lsm_employe+$dtpc_employe+$absen_M+$absen_R+$absen_ok+$LBY_employe+$dt_employe+$pc_employe+$absen_TL;
            $kehadiran_tk=$jumlah_hari-($total_kehadiran+$absen_TL);
            $y=[
                'kode_rekap_kehadiran'=> $kode_rekap_kehadiran,
                'periode_payroll'=>$tanggal_awal.' s/d '.$tanggal_akhir,
                'periode_tahun'=>$tahun,
                'periode_bulan'=>$bulan,
                'enroll_id'=>$value->first()->enroll_id,
                'nik'=>$value->first()->nik,
                'employee_name'=>$value->first()->employee_name,
                'site_nirwana_id'=>$value->first()->site_nirwana_id,
                'site_nirwana_name'=>$value->first()->site_nirwana_name,
                'department_id'=>$value->first()->department_id,
                'department_name'=>$value->first()->department_name,
                'sub_dept_id'=>$value->first()->sub_dept_id,
                'sub_dept_name'=>$value->first()->sub_dept_name,
                'join_date'=>$value->first()->join_date,
                'tanggal_resign'=>$value->first()->tanggal_resign,
                'status_aktif'=>$value->first()->status_aktif,
                'status_staff'=>$value->first()->status_staff,
                'kehadiran_iby'=>$IBY_employe,
                'kehadiran_itb'=>$ITB_employe,
                'kehadiran_lby'=>$LBY_employe,
                'kehadiran_lsm'=>$lsm_employe,
                'kehadiran_dt'=> $dt_employe,
                'kehadiran_pc'=> $pc_employe,
                'kehadiran_dtpc'=>$dtpc_employe,
                'kehadiran_m'=>$absen_M+$absen_TL,
                'kehadiran_r'=>$absen_R,
                'kehadiran_tk'=>$kehadiran_tk,
                'kehadiran_ok'=>$absen_ok,
                'total_kehadiran'=> $total_kehadiran,
                // 'total_kehadiran'=> $total_kehadiran+$kehadiran_tk,
                'total_kehadiran_net'=>$absen_ok+$dtpc_employe+$dt_employe+$pc_employe,
                'jumlah_hari'=>$jumlah_hari,
                'jumlah_hari_kerja'=>$jumlah_hari-$jumlah_hari_sabtu_minggu,
            ];
            // dd($y);
            $count=RekapKehadiranKaryawan::where( 'kode_rekap_kehadiran',$kode_rekap_kehadiran)->count();
            if($count){
                RekapKehadiranKaryawan::where( 'kode_rekap_kehadiran',$kode_rekap_kehadiran)->update($y);
            }
            else{
                RekapKehadiranKaryawan::create($y);

            }
        }
        // dd('ok');
        return('ok');
    }
}

   
