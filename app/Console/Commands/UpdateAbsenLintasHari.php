<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MasterDataAbsenKehadiran;
use App\Models\EmployeeAtribut;
use App\Models\DataKehadiranInOutEdited;
use App\Models\LogDataGagalAbsen;

class UpdateAbsenLintasHari extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lintas_hari';

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
        $absen_lintas_hari=MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->whereColumn('akhir_jam_kerja','<','mulai_jam_kerja')->where('operator','!=','inject absen by excel file')->get();
        if(count($absen_lintas_hari) > 0 ) {
            $query = DB::connection('sqlsrv2')->table('CHECKINOUT as a')
            ->selectRaw("CONVERT(VARCHAR(10), a.CHECKTIME, 126) AS tanggal_absen,
                        b.Badgenumber AS enroll_id,
                        CONVERT(VARCHAR(5), a.CHECKTIME, 114) AS absen_log")
            ->join('USERINFO as b', 'a.USERID', '=', 'b.USERID')
            ->whereDate('a.CHECKTIME','<=', date('Y-m-d'))
            ->whereDate('a.CHECKTIME','>=', date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 days')))
            ->get();
            $results=collect($query)->groupBy(['tanggal_absen','enroll_id','absen_log']);
            $records=[];
            foreach ($results as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    foreach ($value2 as $key3 => $value3) {
                        $records[]=[
                            'tanggal_absen'=> $key1,
                            'enroll_id'=> $key2,
                            'absen_log'=> $key3,
                        ];
                    }
                }
            }
            foreach($absen_lintas_hari as $value){
                $status_staff=EmployeeAtribut::where('enroll_id',$value->enroll_id)->pluck('status_staff')[0];
                $countEditedData=DataKehadiranInOutEdited::where('tanggal_absen', date('Y-m-d', strtotime('-1 days', strtotime($value->tanggal_berjalan))))->where('enroll_id','=', $value->enroll_id)->count();
                $count=LogDataGagalAbsen::where('tanggal_absen', date('Y-m-d', strtotime('-1 days', strtotime($value->tanggal_berjalan))))->where('enroll_id',$value->enroll_id)->count();
                if($countEditedData<1 && $count<1){
                    if($value->status_absen == "TL" || $value->status_absen == "M" || $value->status_absen == "IKS" || $value->status_absen == "" || !$value->status_absen|| $value->status_absen == "LN" || $value->status_absen == "LP" || $value->status_absen == "CT" || $value->status_absen == "L") {

                        $tanggal_kemarin= date('Y-m-d', strtotime('-1 days', strtotime($value->tanggal_berjalan)));

                        $jadwal_masuk_kemarin=null;
                        $jadwal_in_min_kemarin=null;
                        $jadwal_in_max_kemarin=null;
                        $absen_masuk_kemarin=null;
                        $status_absen_kemarin=MasterDataAbsenKehadiran::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan',$tanggal_kemarin)->whereColumn('akhir_jam_kerja','<','mulai_jam_kerja')->pluck('status_absen')[0];
                        $total_DT=0;
                        if(isset(MasterDataAbsenKehadiran::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan',$tanggal_kemarin)->whereColumn('akhir_jam_kerja','<','mulai_jam_kerja')->pluck('mulai_jam_kerja')[0])){
                            $jadwal_masuk_kemarin=MasterDataAbsenKehadiran::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan',$tanggal_kemarin)->whereColumn('akhir_jam_kerja','<','mulai_jam_kerja')->pluck('mulai_jam_kerja')[0];
                            $jadwal_in_min_kemarin=date("H:i", strtotime('-2 hours', strtotime($jadwal_masuk_kemarin)));
                            $jadwal_in_max_kemarin=date("H:i", strtotime('+1 hours 59 minutes', strtotime($jadwal_masuk_kemarin)));
                            $absen_masuk_kemarin=collect($records)->where('tanggal_absen',$tanggal_kemarin)->where('enroll_id',$value->enroll_id)->max('absen_log');
            
                            $DT = date_diff(date_create($jadwal_masuk_kemarin),date_create($absen_masuk_kemarin));
                            if( $absen_masuk_kemarin!=null && $absen_masuk_kemarin>$jadwal_masuk_kemarin ){
                                $total_DT= $DT->i +($DT->h*60);
                                if($status_staff=='STAFF' && $total_DT<=10 && $status_absen_kemarin!='LN'){
                                    $total_DT = 0;
                                }
                                else if($status_staff=='STAFF' && $total_DT>10 && $status_absen_kemarin!='LN'){
                                    $total_DT= $total_DT;
                                }
                                else if($status_staff=='NON STAFF' && $total_DT>0 && $status_absen_kemarin!='LN'){
                                    $total_DT= $total_DT;
                                }
                                else if($status_staff!='' && $total_DT>0 && $status_absen_kemarin=='LN'){
                                    $total_DT= 0;
                                }
                            }else{
                                $total_DT=0;
                            }
                        }
                        $jadwal_pulang_kemarin=null;
                        $jadwal_out_min_kemarin=null;
                        $jadwal_out_max_kemarin=null;
                        $absen_pulang_kemarin=null;
                        $total_PC=0;
                        if(isset(MasterDataAbsenKehadiran::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan',$tanggal_kemarin)->whereColumn('akhir_jam_kerja','<','mulai_jam_kerja')->pluck('akhir_jam_kerja')[0])){
                            $jadwal_pulang_kemarin=MasterDataAbsenKehadiran::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan',$tanggal_kemarin)->whereColumn('akhir_jam_kerja','<','mulai_jam_kerja')->pluck('akhir_jam_kerja')[0];
                            $jadwal_out_min_kemarin=date("H:i", strtotime('-2 hours', strtotime($jadwal_pulang_kemarin)));
                            $jadwal_out_max_kemarin=date("H:i", strtotime('+1 hours 59 minutes', strtotime($jadwal_pulang_kemarin)));
                            $absen_pulang_kemarin=collect($records)->where('tanggal_absen',$value->tanggal_berjalan)->where('enroll_id',$value->enroll_id)->min('absen_log');
                            $PC = date_diff(date_create($jadwal_pulang_kemarin),date_create($absen_pulang_kemarin));
                            if( $absen_pulang_kemarin !=null && $absen_pulang_kemarin<$jadwal_pulang_kemarin){
                                $total_PC = $PC->i +($PC->h*60);
                                if($status_absen_kemarin='LN'){
                                    $total_PC=0;
                                }
                            }else{
                                $total_PC=0;
                            }
                        }
                        $kode_hari_kemarin=MasterDataAbsenKehadiran::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan',$tanggal_kemarin)->whereColumn('akhir_jam_kerja','<','mulai_jam_kerja')->pluck('kode_hari')[0];
                        if($status_absen_kemarin == "LN"){
                            $status_absen_kemarin='LN';
                        }
                        else if(( $absen_masuk_kemarin!=null && $absen_pulang_kemarin!=null)||($kode_hari_kemarin==5)||($kode_hari_kemarin==6)){
                            $status_absen_kemarin=null;
                        }
                        else if( $absen_masuk_kemarin==null && $absen_pulang_kemarin==null && $jadwal_masuk_kemarin!=null){
                            $status_absen_kemarin='M';
                        }
                        else if( $absen_masuk_kemarin==null && $absen_pulang_kemarin==null && $jadwal_masuk_kemarin==null){
                            $status_absen_kemarin=null;
                        }
                        else if( $absen_masuk_kemarin==null || $absen_pulang_kemarin==null){
                            $status_absen_kemarin='TL';
                        }
                        else{
                            $status_absen_kemarin=$value->status_absen;
                        }
                        $jumlah_menit_absen_dtpc=$total_DT+$total_PC;
                        MasterDataAbsenKehadiran::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan',$tanggal_kemarin)->whereColumn('mulai_jam_kerja','>','akhir_jam_kerja')->where('mulai_jam_kerja','!=',null)->update([
                            'absen_masuk_kerja' => $absen_masuk_kemarin,
                            'absen_pulang_kerja' => $absen_pulang_kemarin,
                            'jumlah_menit_absen_dt'=>$total_DT,
                            'jumlah_menit_absen_pc'=>$total_PC,
                            'jumlah_menit_absen_dtpc'=>$jumlah_menit_absen_dtpc,
                            'status_absen' => $status_absen_kemarin,
                            'operator'=>'system_lintashari'
                        ]);
                    }
                }
                $countEditedData2=DataKehadiranInOutEdited::where('tanggal_absen',$value->tanggal_berjalan)->where('enroll_id','=', $value->enroll_id)->count();
                $count2=LogDataGagalAbsen::where('tanggal_absen', $value->tanggal_berjalan)->where('enroll_id',$value->enroll_id)->count();
                if($countEditedData2<1 && $count2<1){
                    if($value->status_absen == "TL" || $value->status_absen == "M" || $value->status_absen == "IKS" || $value->status_absen == "" || !$value->status_absen|| $value->status_absen == "LN" || $value->status_absen == "LP" || $value->status_absen == "CT" || $value->status_absen == "L") {
                        $tanggal_sekarang=$value->tanggal_berjalan;
                        $jadwal_masuk_sekarang=null;
                        $jadwal_in_min_sekarang=null;
                        $jadwal_in_max_sekarang=null;
                        $status_absen_sekarang=MasterDataAbsenKehadiran::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan',$tanggal_sekarang)->whereColumn('akhir_jam_kerja','<','mulai_jam_kerja')->pluck('status_absen')[0];
                        if(isset(MasterDataAbsenKehadiran::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan',$tanggal_sekarang)->whereColumn('akhir_jam_kerja','<','mulai_jam_kerja')->pluck('mulai_jam_kerja')[0])){
                            $jadwal_masuk_sekarang=MasterDataAbsenKehadiran::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan',$tanggal_sekarang)->whereColumn('akhir_jam_kerja','<','mulai_jam_kerja')->pluck('mulai_jam_kerja')[0];
                            $jadwal_in_min_sekarang=date("H:i", strtotime('-2 hours', strtotime($jadwal_masuk_sekarang)));
                            $jadwal_in_max_sekarang=date("H:i", strtotime('+1 hours 59 minutes', strtotime($jadwal_masuk_sekarang)));
                            $absen_masuk_sekarang=collect($records)->where('tanggal_absen',$tanggal_sekarang)->where('enroll_id',$value->enroll_id)->max('absen_log');
                            $DT2 = date_diff(date_create($jadwal_masuk_sekarang),date_create($absen_masuk_sekarang));
                            if( $absen_masuk_sekarang!=null && $absen_masuk_sekarang>$jadwal_masuk_sekarang ){
                                $total_DT2= $DT2->i +($DT2->h*60);
                                if($status_staff=='STAFF' && $total_DT2<=10 && $status_absen_sekarang!='LN'){
                                    $total_DT2 = 0;
                                }
                                else if($status_staff=='STAFF' && $total_DT2>10 && $status_absen_sekarang!='LN'){
                                    $total_DT2= $total_DT2;
                                }
                                else if($status_staff=='NON STAFF' && $total_DT2>0 && $status_absen_sekarang!='LN'){
                                    $total_DT2= $total_DT2;
                                }
                                else if($status_staff!='' && $total_DT2>0 && $status_absen_sekarang=='LN'){
                                    $total_DT2= 0;
                                }else{
                                    $total_DT2= 0;
                                }
                            }else{
                                $total_DT2=0;
                            }
                        }
                        $kode_hari_sekarang=MasterDataAbsenKehadiran::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan',$tanggal_sekarang)->whereColumn('akhir_jam_kerja','<','mulai_jam_kerja')->pluck('kode_hari')[0];
                        if($value->status_absen == "LN"){
                            $status_absen_sekarang='LN';
                        }
                        else if( $absen_masuk_sekarang!=null&&($kode_hari_sekarang==5||$kode_hari_sekarang==6)){
                            $status_absen_sekarang=null;
                        }
                        else if( $absen_masuk_sekarang==null && $jadwal_masuk_sekarang!=null){
                            $status_absen_sekarang='M';
                        }
                        else if( $absen_masuk_sekarang==null && $jadwal_masuk_sekarang==null){
                            $status_absen_sekarang=null;
                        }
                        else if( $absen_masuk_sekarang!=null){
                            $status_absen_sekarang='TL';
                        }
                        else{
                            $status_absen_sekarang=$value->status_absen;
                        }

                        MasterDataAbsenKehadiran::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan',$tanggal_sekarang)->whereColumn('mulai_jam_kerja','>','akhir_jam_kerja')->where('mulai_jam_kerja','!=',null)->update([
                            'absen_masuk_kerja' => $absen_masuk_sekarang,
                            'jumlah_menit_absen_dt'=>$total_DT2,
                            'status_absen' => $status_absen_sekarang,
                            'operator'=>'system_lintashari'
                        ]);
                    }
                }
            }
        }
    }
}
