<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmployeeAtribut;
use App\Models\MasterDataAbsenKehadiran;

class updateTanggalResign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resign';

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
        $tanggal_akhir='';
        $tanggal_sekarang=date('Y-m-d');
        $bulan_sekarang=date('Y-m-'.'25');
        $bulan_sebelum=date('Y-m-d',strtotime( "-1 month", strtotime( $bulan_sekarang ) ));
        $bulan_setelah=date('Y-m-d',strtotime( "+1 month", strtotime( $bulan_sekarang ) ));
        if($tanggal_sekarang>$bulan_sebelum && $tanggal_sekarang<=$bulan_sekarang){
            $tanggal_akhir=$bulan_sekarang;
        }else if($tanggal_sekarang>$bulan_sekarang && $tanggal_sekarang<=$bulan_setelah){
            $tanggal_akhir=$bulan_setelah;
        }
        $resignEmployee=EmployeeAtribut::where('tanggal_resign','>=',date('Y-m-d'))->where('tanggal_resign','<=',$tanggal_akhir)->get();
        foreach($resignEmployee as $emp){
            MasterDataAbsenKehadiran::where('enroll_id',$emp->enroll_id)->where('tanggal_berjalan','>=',$emp->tanggal_resign)->where('tanggal_berjalan','<=',$tanggal_akhir)->where('kode_hari','!=','5')->where('kode_hari','!=','6')->update([
                'status_absen'=>'R',
            ]);
        }
    }
}
