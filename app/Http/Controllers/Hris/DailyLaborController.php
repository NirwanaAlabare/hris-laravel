<?php

namespace App\Http\Controllers\Hris;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\View;
use App\Models\DailyLabor;
use App\Models\EmployeeAtribut;
use App\Models\MasterDataAbsenKehadiran;
use App\Models\RefAbsenIjin;
use App\Models\GradingSalary;
use App\Models\EmployeeBPJS;
use App\Models\RekapPerhitunganKehadiranKaryawan;
use App\Models\RekapPerhitunganLembur;
use App\Exports\dailyLaborCost;
use Maatwebsite\Excel\Facades\Excel;
use DB;

use Illuminate\Http\Request;

class DailyLaborController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Nilai Payroll Per Hari';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $data=DB::select("select a.enroll_id,a.employee_name,b.group2,a.status_absen,d.kode_ijin_payroll,a.jumlah_menit_absen_dtpc menit_dtpc,if(a.total_menit_permits is null,0,a.total_menit_permits) menit_iks,c.gaji_menit,c.gaji_harian,(a.jumlah_menit_absen_dtpc+if(a.total_menit_permits is null,0,a.total_menit_permits))*c.gaji_menit potongan_menit_rupiah,sum(c.gaji_harian-((a.jumlah_menit_absen_dtpc+if(a.total_menit_permits is null,0,a.total_menit_permits))*c.gaji_menit)) net_wage from master_data_absen_kehadiran a inner join employee_atribut e on a.enroll_id=e.enroll_id inner join b_master_cc b on e.sub_dept_id=b.no_cc left join ref_absen_ijin d on a.status_absen=d.kode_absen_ijin inner join (select enroll_id,gaji_harian,gaji_menit,periode_tahun_bulan from rekap_perhitungan_kehadiran_karyawan where periode_tahun_bulan='2024-08')c on a.enroll_id=c.enroll_id where a.tanggal_berjalan='2024-08-01' and (a.status_absen is null or d.kode_ijin_payroll in ('IBY','LBY')) and e.status_staff='NON STAFF' group by b.group2");
        $data=MasterDataAbsenKehadiran::where('tanggal_berjalan','2024-09-09')->with('employee_atribut.grading_salary','employee_atribut.group_department','ref_absen')->where(function($query){
            $query->where('status_absen',null)
            ->orWhereHas('ref_absen',function($querys){
                return $querys->where('kode_ijin_payroll','IBY');
            });
        })->get();
        
        return View::make('hris/daily_labor_cost', $this->data);
    }
    public function proses(){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '5120M');
        $ijin_bayar=RefAbsenIjin::where('kode_ijin_payroll','IBY')->get()->toArray();
        $IBY=array_column($ijin_bayar,'kode_absen_ijin');
        $LBY=['LN'];
        $arrperiode=explode(" s/d ",request()->periode_kehadiran);
        $first_date=$arrperiode[0];
        $last_date=$arrperiode[1];
        $dates= $this->getDatesBetween($first_date, $last_date);
        foreach($dates as $value){
            $tanggal_sekarang=$value;
            $bulan_sekarang=substr($tanggal_sekarang,0,8).'26';
            $bulan_sebelum=date('Y-m-d',strtotime( "-1 month", strtotime( $bulan_sekarang ) ));
            $bulan_setelah=date('Y-m-d',strtotime( "+1 month", strtotime( $bulan_sekarang ) ));
            if($tanggal_sekarang>=$bulan_sebelum && $tanggal_sekarang<$bulan_sekarang){
                $tanggal_awal=$bulan_sebelum;
            }else if($tanggal_sekarang>=$bulan_sekarang && $tanggal_sekarang<$bulan_setelah){
                $tanggal_awal=$bulan_sekarang;
            }else{
                $tanggal_awal='';
            }
            $tanggal_akhir=date('Y-m-25',strtotime("+1 month",strtotime($tanggal_awal)));
            $data_periode[]=$tanggal_awal.' s/d '.$tanggal_akhir;
        }
        foreach ($data_periode as $element) {
            $result_periode[$element] = $element;
        }
        $data_master=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$first_date)->where('tanggal_berjalan','<=',$last_date)
        ->where(function($query)use($IBY,$LBY){
            $query->where(function($queryes){
                $queryes->where('mulai_jam_kerja','!=',null)->where('akhir_jam_kerja','!=',null)->where('absen_masuk_kerja','!=',null)->where('absen_pulang_kerja','!=',null);
            })->orWhere(function($queryess){
                $queryess->where('mulai_jam_kerja',null)->where('akhir_jam_kerja',null)->where('nomor_form_lembur','!=',null);
            })->orWhereIn('status_absen',$IBY)->orWhereIn('status_absen',$LBY);
        })->with(['rekap_perhitungan_kehadiran'=>function($query)use($result_periode){
            $query->whereIn('periode_payroll',[$result_periode]);
        }])->with(['rekap_lembur'=>function($query)use($first_date,$last_date){
            $query->where('tanggal_berjalan','>=',$first_date)
            ->where('tanggal_berjalan','<=',$last_date);
        }])->with(['employee_atribut.employee_bpjs'=>function($query)use($result_periode){
            $query->whereIn('periode_kehadiran',[$result_periode]);
        }])->with(['employee_atribut.grading_salary'=>function($query){
            $query->where('periode_umk','2024-01');
        }])->with('employee_atribut.dept.b_master_cc')->where('status_staff','NON STAFF')->get();
        foreach($data_master as $value){
            $security=EmployeeAtribut::where('sub_dept_id','DEP08SUB005')->where('jenis_kelamin','LAKI-LAKI')->where('enroll_id',$value->enroll_id)->count();
            $tanggal_sekarang2=$value->tanggal_berjalan;
            $bulan_sekarang2=substr($tanggal_sekarang,0,8).'26';
            $bulan_sebelum2=date('Y-m-d',strtotime( "-1 month", strtotime( $bulan_sekarang ) ));
            $bulan_setelah2=date('Y-m-d',strtotime( "+1 month", strtotime( $bulan_sekarang ) ));
            if($tanggal_sekarang2>=$bulan_sebelum2 && $tanggal_sekarang2<$bulan_sekarang2){
                $tanggal_awal2=$bulan_sebelum2;
            }else if($tanggal_sekarang2>=$bulan_sekarang2 && $tanggal_sekarang2<$bulan_setelah2){
                $tanggal_awal2=$bulan_sekarang2;
            }else{
                $tanggal_awal2='';
            }
            $tanggal_akhir2=date('Y-m-25',strtotime("+1 month",strtotime($tanggal_awal2)));
            $periode_payroll=$tanggal_awal2.' s/d '.$tanggal_akhir2;

            if($value->mulai_jam_kerja!=null && $value->absen_masuk_kerja!=null && $value->absen_pulang_kerja!=null && ($value->status_absen==null || $value->status_absen=='IKS' || in_array($value->status_absen,$LBY) || in_array($value->status_absen,$IBY))){
                if($value->rekap_perhitungan_kehadiran()){
                    $gaji_bulanan=$value->rekap_perhitungan_kehadiran()->where('periode_payroll',$periode_payroll)->first()->gaji_pokok;
                    $gaji_harian=$value->rekap_perhitungan_kehadiran()->where('periode_payroll',$periode_payroll)->first()->gaji_harian;
                    $gaji_menit=$value->rekap_perhitungan_kehadiran()->where('periode_payroll',$periode_payroll)->first()->gaji_menit;
                    $hari_kerja=$value->rekap_perhitungan_kehadiran()->where('periode_payroll',$periode_payroll)->first()->jumlah_hari_kerja;
                }else{
                    $gaji_bulanan=0;
                    $gaji_harian=0;
                    $gaji_menit=0;
                    $hari_kerja=0;
                }
                $potongan_menit=$value->jumlah_menit_absen_dtpc+$value->total_menit_permits;
                $net_wages=$gaji_harian-($gaji_menit*$potongan_menit);
                if($value->mulai_jam_kerja!=null && $value->absen_masuk_kerja!=null && $value->absen_pulang_kerja!=null && $value->jumlah_menit_absen_dt==0 && $value->jumlah_menit_absen_pc==0 && $value->jumlah_menit_absen_dtpc==0 && $value->status_absen==null){
                    if($value->employee_atribut->grading_salary->first()->insentif!=null || $value->employee_atribut->grading_salary->first()->insentif>0){
                        $insentif=$value->employee_atribut->grading_salary->first()->insentif/21;
                    }else{
                        $insentif=0;
                    }
                }else{
                    $insentif=0;
                }
            }else{
                $net_wages=0;
                $insentif=0;
            }
            if($value->nomor_form_lembur!=null){
                if($value->rekap_lembur()){
                    $total_lembur_rupiah=$value->rekap_lembur()->where('tanggal_berjalan',$value->tanggal_berjalan)->first()->total_lembur_rupiah;
                }else{
                    $total_lembur_rupiah=0;
                }
            }else{
                $total_lembur_rupiah=0;
            }
            
            if($value->kode_hari!=5 && $value->kode_hari!=6){
                if($value->employee_atribut->status_aktif_bpjs_ks=='AKTIF'){
                    if($value->employee_atribut->employee_bpjs->where('periode_kehadiran',$periode_payroll)){
                        $bpjs_ks=$value->employee_atribut->employee_bpjs->where('periode_kehadiran',$periode_payroll)->first()->bpjs_ks_jkn_bruto_rupiah/$hari_kerja;
                    }else{
                        $bpjs_ks=0;
                    }
                }else{
                    $bpjs_ks=0;
                }
                if($value->employee_atribut->status_aktif_bpjs_tk=='AKTIF'){
                    if($value->employee_atribut->employee_bpjs->where('periode_kehadiran',$periode_payroll)){
                        $bpjs_tk=($value->employee_atribut->employee_bpjs->where('periode_kehadiran',$periode_payroll)->first()->bpjs_tk_jkm_bruto_rupiah+
                        $value->employee_atribut->employee_bpjs->where('periode_kehadiran',$periode_payroll)->first()->bpjs_tk_jht_bruto_rupiah+
                        $value->employee_atribut->employee_bpjs->where('periode_kehadiran',$periode_payroll)->first()->bpjs_tk_jkk_bruto_rupiah+
                        $value->employee_atribut->employee_bpjs->where('periode_kehadiran',$periode_payroll)->first()->bpjs_tk_jpn_bruto_rupiah)
                        /$hari_kerja;
                    }else{
                        $bpjs_tk=0;
                    }
                }else{
                    $bpjs_tk=0;
                }
                if($value->employee_atribut->employee_bpjs->where('periode_kehadiran',$periode_payroll)){
                    $tunjangan=$value->employee_atribut->employee_bpjs->where('periode_kehadiran',$periode_payroll)->first()->tmk;
                }else{
                    $tunjangan=0;
                }
                $thr=(($gaji_bulanan+$tunjangan)/12)/$hari_kerja;
            }else{
                $bpjs_ks=0;
                $bpjs_tk=0;
                $thr=0;
            }
            $count=DailyLabor::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan',$value->tanggal_berjalan)->count();
            if($count==1){
                DailyLabor::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan',$value->tanggal_berjalan)->update([
                    'net_wages'=>$net_wages,
                    'overtime'=>$total_lembur_rupiah,
                    'incentive'=>$insentif,
                    'bpjs_ks'=>$bpjs_ks,
                    'bpjs_tk'=>$bpjs_tk,
                    'thr'=>$thr
                ]);
            }else{
                DailyLabor::create([
                    'enroll_id'=>$value->enroll_id,
                    'department'=>$value["employee_atribut"]["dept"]["sub_dept_id"],
                    'group_department'=>$value["employee_atribut"]["dept"]["b_master_cc"]["group2"],
                    'tanggal_berjalan'=>$value->tanggal_berjalan,
                    'net_wages'=>$net_wages,
                    'overtime'=>$total_lembur_rupiah,
                    'incentive'=>$insentif,
                    'bpjs_ks'=>$bpjs_ks,
                    'bpjs_tk'=>$bpjs_tk,
                    'thr'=>$thr
                ]);
            }
        }
    }
    public function getDatesBetween($startDate, $endDate) {
        $dates = [];
        $currentDate = strtotime($startDate);
        $endDate = strtotime($endDate);
    
        while ($currentDate <= $endDate) {
            $dates[] = date('Y-m-d', $currentDate);
            $currentDate = strtotime('+1 day', $currentDate);
        }
        return $dates;
    }
    public function export_excel(){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1024M');
        $periode_kehadiran = request()->periode_kehadiran;
        $arrperiode=explode(" s/d ",$periode_kehadiran);
        $tanggal_awal = $arrperiode[0];
        $tanggal_akhir = $arrperiode[1];
        $daily_labor=DB::select("select tanggal_berjalan,sum(if(group_department='PRODUCTION',net_wages,0)) production,sum(if(group_department='SUPPORTING PRODUCTION',net_wages,0)) supporting_production,sum(if(group_department='SUPPORTING GENERAL',net_wages,0)) supporting_general,sum(if(group_department='PRODUCTION',net_wages,0))+sum(if(group_department='SUPPORTING PRODUCTION',net_wages,0))+sum(if(group_department='SUPPORTING GENERAL',net_wages,0)) total_wages,sum(if(group_department='PRODUCTION',overtime,0)) overtime_production,sum(if(group_department='SUPPORTING PRODUCTION',overtime,0)) supporting_production_overtime,sum(if(group_department='SUPPORTING GENERAL',overtime,0)) supporting_general_overtime,sum(if(group_department='PRODUCTION',overtime,0))+sum(if(group_department='SUPPORTING PRODUCTION',overtime,0))+sum(if(group_department='SUPPORTING GENERAL',overtime,0)) total_overtime,sum(if(group_department='PRODUCTION',incentive,0)) incentive_production,sum(if(group_department='SUPPORTING PRODUCTION',incentive,0)) incentive_supporting_production,sum(if(group_department='SUPPORTING GENERAL',incentive,0)) incentive_supporting_general,sum(if(group_department='PRODUCTION',incentive,0))+sum(if(group_department='SUPPORTING PRODUCTION',incentive,0))+sum(if(group_department='SUPPORTING GENERAL',incentive,0)) total_insentif,sum(if(group_department='PRODUCTION',bpjs_ks,0)) bpjs_ks_production,sum(if(group_department='SUPPORTING PRODUCTION',bpjs_ks,0)) bpjs_ks_supporting_production,sum(if(group_department='SUPPORTING GENERAL',bpjs_ks,0)) bpjs_ks_supporting_general,sum(if(group_department='PRODUCTION',bpjs_ks,0))+sum(if(group_department='SUPPORTING PRODUCTION',bpjs_ks,0))+sum(if(group_department='SUPPORTING GENERAL',bpjs_ks,0)) total_bpjs_ks,sum(if(group_department='PRODUCTION',bpjs_tk,0)) bpjs_tk_production,sum(if(group_department='SUPPORTING PRODUCTION',bpjs_tk,0)) bpjs_tk_supporting_production,sum(if(group_department='SUPPORTING GENERAL',bpjs_tk,0)) bpjs_tk_supporting_general,sum(if(group_department='PRODUCTION',bpjs_tk,0))+sum(if(group_department='SUPPORTING PRODUCTION',bpjs_tk,0))+sum(if(group_department='SUPPORTING GENERAL',bpjs_tk,0)) total_bpjs_tk,sum(if(group_department='PRODUCTION',thr,0)) thr_production,sum(if(group_department='SUPPORTING PRODUCTION',thr,0)) thr_supporting_production,sum(if(group_department='SUPPORTING GENERAL',thr,0)) thr_supporting_general,sum(if(group_department='PRODUCTION',thr,0))+sum(if(group_department='SUPPORTING PRODUCTION',thr,0))+sum(if(group_department='SUPPORTING GENERAL',thr,0)) total_thr,sum(if(group_department='PRODUCTION',net_wages,0))+sum(if(group_department='PRODUCTION',overtime,0))+sum(if(group_department='PRODUCTION',incentive,0))+sum(if(group_department='PRODUCTION',bpjs_ks,0))+sum(if(group_department='PRODUCTION',bpjs_tk,0))+sum(if(group_department='PRODUCTION',thr,0)) total_employee_production_cost,sum(if(group_department='SUPPORTING PRODUCTION',net_wages,0))+sum(if(group_department='SUPPORTING PRODUCTION',overtime,0))+sum(if(group_department='SUPPORTING PRODUCTION',incentive,0))+sum(if(group_department='SUPPORTING PRODUCTION',bpjs_ks,0))+sum(if(group_department='SUPPORTING PRODUCTION',bpjs_tk,0))+sum(if(group_department='SUPPORTING PRODUCTION',thr,0)) total_employee_supporting_production_cost,sum(if(group_department='SUPPORTING GENERAL',net_wages,0))+sum(if(group_department='SUPPORTING GENERAL',overtime,0))+sum(if(group_department='SUPPORTING GENERAL',incentive,0))+sum(if(group_department='SUPPORTING GENERAL',bpjs_ks,0))+sum(if(group_department='SUPPORTING GENERAL',bpjs_tk,0))+sum(if(group_department='SUPPORTING GENERAL',thr,0)) total_employee_supporting_general_cost,sum(if(group_department='PRODUCTION',net_wages,0))+sum(if(group_department='PRODUCTION',overtime,0))+sum(if(group_department='PRODUCTION',incentive,0))+sum(if(group_department='PRODUCTION',bpjs_ks,0))+sum(if(group_department='PRODUCTION',bpjs_tk,0))+sum(if(group_department='PRODUCTION',thr,0))+sum(if(group_department='SUPPORTING PRODUCTION',net_wages,0))+sum(if(group_department='SUPPORTING PRODUCTION',overtime,0))+sum(if(group_department='SUPPORTING PRODUCTION',incentive,0))+sum(if(group_department='SUPPORTING PRODUCTION',bpjs_ks,0))+sum(if(group_department='SUPPORTING PRODUCTION',bpjs_tk,0))+sum(if(group_department='SUPPORTING PRODUCTION',thr,0))+sum(if(group_department='SUPPORTING GENERAL',net_wages,0))+sum(if(group_department='SUPPORTING GENERAL',overtime,0))+sum(if(group_department='SUPPORTING GENERAL',incentive,0))+sum(if(group_department='SUPPORTING GENERAL',bpjs_ks,0))+sum(if(group_department='SUPPORTING GENERAL',bpjs_tk,0))+sum(if(group_department='SUPPORTING GENERAL',thr,0)) total_employee_cost from daily_labor where tanggal_berjalan>='$tanggal_awal' and tanggal_berjalan<='$tanggal_akhir' group by tanggal_berjalan");
        $fileName = 'Daily Labor Cost';
        $response = Excel::download(new dailyLaborCost($daily_labor), $fileName, \Maatwebsite\Excel\Excel::XLSX);
        ob_end_clean();
        return $response;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
