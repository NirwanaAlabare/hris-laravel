<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MasterDataAbsenKehadiran;
use App\Models\DataAbsenPerijinan;

class updateStatusAbsen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statusAbsen';

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
        $enroll_id='';
        $absen_perijinan=DataAbsenPerijinan::where('tanggal_mulai_ijin','<=',date('Y-m-d'))->where('tanggal_akhir_ijin','>=',date('Y-m-d'))->get();
        foreach($absen_perijinan as $absen){
            MasterDataAbsenKehadiran::where('enroll_id',$absen->enroll_id)->where('tanggal_berjalan','>=',date('Y-m-d'))->where('tanggal_berjalan','<=',$absen->tanggal_akhir_ijin)->where('kode_hari','!=','5')->where('kode_hari','!=','6')->update([
                'status_absen'=>$absen->kode_absen_ijin,
                'nomor_absen_ijin'=>$absen->nomor_form_perizinan,
                'tanggal_mulai_ijin'=>$absen->tanggal_mulai_ijin,
                'tanggal_akhir_ijin'=>$absen->tanggal_akhir_ijin,
                'absen_alasan'=>$absen->absen_alasan
            ]);
        }
    }
}
