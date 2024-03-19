<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MasterDataAbsenKehadiran;
use App\Models\RefAbsenIjin;

class UpdateDTPC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dtpc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ijin_bayar=RefAbsenIjin::where('kode_ijin_payroll','IBY')->get()->toArray();
        $IBY=array_column($ijin_bayar,'kode_absen_ijin');
        $masterAbsen=MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->get();
        foreach ($masterAbsen as $k => $v) {
            $enroll_id=$v->enroll_id;
            $employee_name=$v->employee_name;
            $jadwal_in=$v->mulai_jam_kerja;
            $jadwal_out=$v->akhir_jam_kerja;
            $absen_in=$v->absen_masuk_kerja;
            $absen_out=$v->absen_pulang_kerja;
            $total_dt_real='';
            $total_pc_real='';
            $status_absen=$v->status_absen;
            if($jadwal_in!=null && $absen_in!=null){
                $dt=date_diff(date_create($jadwal_in),date_create($absen_in));
            }else{
                $dt=0;
            }
            if($jadwal_out!=null && $absen_out!=null){
                $pc=date_diff(date_create($jadwal_out),date_create($absen_out));
            }else{
                $pc=0;
            }
            if($v->mulai_jam_kerja!=null && $v->akhir_jam_kerja!=null){
                $durasi_kerja=date_diff(date_create($jadwal_in),date_create($jadwal_out));
                $durasi_kerja_menit=$durasi_kerja->i+($durasi_kerja->h*60);
            }else{
                $durasi_kerja_menit=0;
            }
            if($jadwal_in!=null && $absen_in!=null && $absen_in>$jadwal_in && in_array($status_absen,$IBY)==false){
                $total_dt=$dt->i+$dt->h*60;
                if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                    if($absen_in>'13:00:00'){
                        $total_dt_real=$total_dt-60;
                    }else if($absen_in>'12:00:00' && $absen_in<='13:00:00'){
                        $selisih_menit=strtotime($absen_in)-strtotime('12:00:00');
                        $selisih_menit=round($selisih_menit/60);
                        $total_dt_real=$total_dt-$selisih_menit;
                    }else{
                        $total_dt_real=$total_dt;
                    }
                }else{
                    $total_dt_real=$total_dt;
                }
            }else{
                $total_dt_real=0;
            }
            $total_dt_real=$total_dt_real<480?$total_dt_real:480;
            if($jadwal_out!=null && $absen_out!=null && $absen_out<$jadwal_out && in_array($status_absen,$IBY)==false){
                $total_pc=$pc->i+$pc->h*60;
                if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                    if($v->absen_pulang_kerja<='12:00:00'){
                        $total_pc_real=$total_pc-60;
                    }else if($absen_out>'12:00:00' && $absen_out<='13:00:00'){
                        $selisih_menit=strtotime('13:00:00')-strtotime($absen_out);
                        $selisih_menit=round($selisih_menit/60);
                        $total_pc_real=$total_pc-$selisih_menit;
                    }else{
                        $total_pc_real=$total_pc;
                    }
                }else{
                    $total_pc_real=$total_pc;
                }
            }else{
                $total_pc_real=0;
            }
            $jumlah_menit_absen_dtpc=$total_dt_real+$total_pc_real;
            $jumlah_absen_menit_kerja=$durasi_kerja_menit-$jumlah_menit_absen_dtpc;
            MasterDataAbsenKehadiran::where('enroll_id',$enroll_id)->where('tanggal_berjalan',date('Y-m-d'))->update([
                'jumlah_menit_absen_dt'=>$total_dt_real,
                'jumlah_menit_absen_pc'=>$total_pc_real,
                'jumlah_menit_kerja'=>$durasi_kerja_menit,
                'jumlah_menit_absen_dtpc'=>$jumlah_menit_absen_dtpc,
                'jumlah_absen_menit_kerja'=>$jumlah_absen_menit_kerja
            ]);
        }
    }
}
