<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use DB;

class RecapLaborNonStaff implements WithTitle, FromView, WithColumnWidths
{
    use Exportable;
    protected $tanggal_awal,$tanggal_akhir,$staffnonstaff;
    
    public function __construct($tanggal_awal,$tanggal_akhir)
    {
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
    }
    public function title(): string
    {
        return 'Summary Non Staff';
    }
    public function view(): View
    {
        $query=DB::select("select a.tanggal_berjalan,b.status_staff,b.department_id,b.department_name,b.sub_dept_id,b.sub_dept_name,a.group_department,count(if(hari_kerja=1,1,null)) man_power,sum(if(c.mulai_jam_kerja is not null,ABS((TIME_TO_SEC(c.absen_pulang_kerja)-TIME_TO_SEC(c.absen_masuk_kerja))/60)-60,if(c.nomor_form_lembur is not null,((ABS(TIME_TO_SEC(substr(d.akhir_jam_lembur,11,8))-TIME_TO_SEC(substr(d.mulai_jam_lembur,11,8)))/60)-(d.jumlah_jam_istirahat*60)),0))) absen_menit,c.mulai_jam_kerja,c.status_absen,c.absen_masuk_kerja,c.absen_pulang_kerja,c.kode_hari,sum(a.bruto) bruto,sum(a.bpjs_tk_company) bpjs_tk,sum(a.bpjs_ks_company) bpjs_ks,sum(a.thr) thr from daily_labor_costs a inner join employee_atribut b on a.enroll_id=b.enroll_id inner join master_data_absen_kehadiran c on a.enroll_id=c.enroll_id and a.tanggal_berjalan=c.tanggal_berjalan left join data_lembur d on a.enroll_id=d.enroll_id and a.tanggal_berjalan=d.tanggal_berjalan where a.tanggal_berjalan>='".$this->tanggal_awal."' and a.tanggal_berjalan<='".$this->tanggal_akhir."' and b.status_staff='STAFF' group by a.tanggal_berjalan,sub_dept_id");
        $query_2=DB::select("select tanggal_berjalan from daily_labor_costs where tanggal_berjalan>='".$this->tanggal_awal."' and tanggal_berjalan<='".$this->tanggal_akhir."' group by tanggal_berjalan order by tanggal_berjalan");
        $dateRange=array_column($query_2,'tanggal_berjalan');
        $z=[];
        foreach($query as $valquery){
            $z[]=[
                'tanggal_berjalan'=>$valquery->tanggal_berjalan,
                'department_id'=>$valquery->department_id,
                'department_name'=>$valquery->department_name,
                'sub_dept_id'=>$valquery->sub_dept_id,
                'sub_dept_name'=>$valquery->sub_dept_name,
                'group_department'=>$valquery->group_department,
                'man_power'=>$valquery->man_power,
                'working_min'=>$valquery->absen_menit,
                'bruto'=>$valquery->bruto,
                'bpjs_tk'=>$valquery->bpjs_tk,
                'bpjs_ks'=>$valquery->bpjs_ks,
                'thr'=>$valquery->thr,
                'total'=>$valquery->bruto+$valquery->bpjs_tk+$valquery->bpjs_ks+$valquery->thr
            ];
        }
        //daterange harus nya diambil dari query lalu di group by tanggal_berjalan
        
        $x=[];
        foreach($query as $value){
            $x[]=[
                'department_id'=>$value->department_id,
                'department_name'=>$value->department_name,
                'sub_dept_id'=>$value->sub_dept_id,
                'sub_dept_name'=>$value->sub_dept_name,
                'group_department'=>$value->group_department,
                'gaji'=>collect($z)->where('sub_dept_id',$value->sub_dept_id)->sortBy('tanggal_berjalan'),
            ];
        }
        return view('hris.recap_labor_non_staff',compact('x','dateRange'));
    }
    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 26,
            'C' => 16,
            'D' => 31,
            'E' => 25,
        ];
    }
}
