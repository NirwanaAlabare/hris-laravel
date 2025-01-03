<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use DB;

class RecapLaborStaff implements WithTitle, FromView, WithColumnWidths
{
    use Exportable;
    protected $tanggal_awal,$tanggal_akhir,$staffnonstaff;
    
    public function __construct($tanggal_awal,$tanggal_akhir,$staffnonstaff)
    {
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->staffnonstaff = $staffnonstaff;
    }
    public function title(): string
    {
        return 'Summary Staff';
    }
    public function view(): View
    {
        $query=DB::select("select a.tanggal_berjalan,if(emp_hist.status_staff is null,b.status_staff,emp_hist.status_staff) status_staff,if(emp_hist.department_id is null,b.department_id,emp_hist.department_id) department_id,if(emp_hist.department_name is null,b.department_name,emp_hist.department_name) department_name,if(emp_hist.department_name is null,b.sub_dept_id,emp_hist.sub_dept_id) sub_dept_id,if(emp_hist.sub_dept_name is null,b.sub_dept_name,emp_hist.sub_dept_name) sub_dept_name,a.group_department,COUNT(IF(CASE WHEN(absen_ijin.kode_ijin_payroll is null) THEN 
        CASE WHEN(c.mulai_jam_kerja is not null and c.akhir_jam_kerja is not null) THEN 
            CASE WHEN(c.absen_masuk_kerja is not null and c.absen_pulang_kerja is not null)THEN
                CASE WHEN(c.jumlah_menit_absen_dt!=0 and c.jumlah_menit_absen_pc=0) THEN 'DT'
                WHEN(c.jumlah_menit_absen_dt=0 and c.jumlah_menit_absen_pc!=0) THEN 'PC'
                WHEN(c.jumlah_menit_absen_dt!=0 and c.jumlah_menit_absen_pc!=0) THEN 'DTPC'
                ELSE 'OK'
                END
            WHEN(c.absen_masuk_kerja is not null and c.absen_pulang_kerja is not null and c.status_absen='R') THEN 'R'
            WHEN(c.absen_masuk_kerja is not null and c.absen_pulang_kerja is null and c.status_absen!='R') THEN 'M'
            WHEN(c.absen_masuk_kerja is null and c.absen_pulang_kerja is not null and c.status_absen!='R') THEN 'M'
            WHEN(c.absen_masuk_kerja is not null and c.absen_pulang_kerja is null and c.status_absen='R') THEN 'R' 
            WHEN(c.absen_masuk_kerja is null and c.absen_pulang_kerja is null and c.status_absen!='R') THEN 'M' 
            WHEN(c.absen_masuk_kerja is null and c.absen_pulang_kerja is null and c.status_absen='R') THEN 'R'
            END
        ELSE 
            CASE WHEN(c.status_absen='LN') THEN 'LBY'
            WHEN (c.status_absen='R') THEN 'R'
            ELSE 'LSM'
            END
        END 
        WHEN(absen_ijin.kode_ijin_payroll='ITB') THEN
            CASE WHEN(c.status_absen='M')THEN 'M'
            WHEN(c.status_absen='IKS') THEN 
                CASE WHEN(c.absen_masuk_kerja is not null and c.absen_pulang_kerja is not null)THEN
                    CASE WHEN(c.jumlah_menit_absen_dt!=0 and c.jumlah_menit_absen_pc=0) THEN 'DT'
                    WHEN(c.jumlah_menit_absen_dt=0 and c.jumlah_menit_absen_pc!=0) THEN 'PC'
                    WHEN(c.jumlah_menit_absen_dt!=0 and c.jumlah_menit_absen_pc!=0) THEN 'DTPC'
                    ELSE 'OK'
                    END
                ELSE 'OK'
                END
            WHEN(c.status_absen='LP') THEN 'LP'
            WHEN(c.status_absen='S') THEN 'S'
            ELSE 'ITB'
            END
        WHEN(absen_ijin.kode_ijin_payroll='IBY') THEN
            CASE WHEN(c.status_absen='DL') THEN 'DL'
            ELSE 'IBY'
            END
        ELSE absen_ijin.kode_ijin_payroll END in ('OK','DT','PC','DTPC','IBY','IKS'),1,null)) man_power,
        SUM(CASE WHEN(c.absen_masuk_kerja is not null and c.absen_pulang_kerja is not null) THEN
            CASE WHEN(c.kode_hari not in (5,6))THEN
                CASE WHEN(TIME_TO_SEC(c.absen_masuk_kerja)<TIME_TO_SEC(c.absen_pulang_kerja))THEN
                    ((TIME_TO_SEC(c.absen_pulang_kerja)-TIME_TO_SEC(c.absen_masuk_kerja))/60)-60
                WHEN(TIME_TO_SEC(c.absen_pulang_kerja)<TIME_TO_SEC(c.absen_masuk_kerja))THEN
                    ((TIME_TO_SEC(c.absen_masuk_kerja)-TIME_TO_SEC(c.absen_pulang_kerja))/60)-60
                END
            ELSE
                CASE WHEN(TIME_TO_SEC(c.absen_masuk_kerja)<TIME_TO_SEC(c.absen_pulang_kerja))THEN
                    ((TIME_TO_SEC(c.absen_pulang_kerja)-TIME_TO_SEC(c.absen_masuk_kerja))/60)-30
                WHEN(TIME_TO_SEC(c.absen_pulang_kerja)<TIME_TO_SEC(c.absen_masuk_kerja))THEN
                    ((TIME_TO_SEC(c.absen_masuk_kerja)-TIME_TO_SEC(c.absen_pulang_kerja))/60)-30
                END
            END
        ELSE 0 END) absen_menit,
        c.mulai_jam_kerja,c.status_absen,c.absen_masuk_kerja,c.absen_pulang_kerja,c.kode_hari,sum(a.bruto) bruto,sum(a.bpjs_tk_company) bpjs_tk,sum(a.bpjs_ks_company) bpjs_ks,sum(a.thr) thr from daily_labor_costs a inner join employee_atribut b on a.enroll_id=b.enroll_id inner join master_data_absen_kehadiran c on a.enroll_id=c.enroll_id and a.tanggal_berjalan=c.tanggal_berjalan left join data_lembur d on a.enroll_id=d.enroll_id and a.tanggal_berjalan=d.tanggal_berjalan left join ref_absen_ijin absen_ijin on c.status_absen=absen_ijin.kode_absen_ijin left join employee_atribut_histories emp_hist on a.enroll_id=emp_hist.enroll_id and a.tanggal_berjalan between SUBSTRING(emp_hist.periode_payroll,1,10) and SUBSTRING(emp_hist.periode_payroll,16,10) where a.tanggal_berjalan>='".$this->tanggal_awal."' and a.tanggal_berjalan<='".$this->tanggal_akhir."'  and (b.status_staff='STAFF' or emp_hist.status_staff='STAFF') group by a.tanggal_berjalan,sub_dept_id");
        $query_2=DB::select("select tanggal_berjalan from daily_labor_costs where tanggal_berjalan>='".$this->tanggal_awal."' and tanggal_berjalan<='".$this->tanggal_akhir."' group by tanggal_berjalan order by tanggal_berjalan");
        $query_3=DB::select("select a.department_id,a.department_name,a.sub_dept_id,a.sub_dept_name, b_master_cc.group2 group_department from (SELECT * from department_all WHERE status = 'AKTIF' and site_nirwana_id = 
        'NAG') a LEFT JOIN b_master_cc ON a.sub_dept_id = b_master_cc.no_cc group by a.sub_dept_id order by a.department_id, a.sub_dept_name");
        $query_4=DB::select("select a.tanggal_berjalan,if(emp_hist.status_staff is null,b.status_staff,emp_hist.status_staff) status_staff,if(emp_hist.department_id is null,b.department_id,emp_hist.department_id) department_id,if(emp_hist.department_name is null,b.department_name,emp_hist.department_name) department_name,if(emp_hist.sub_dept_id is null,b.sub_dept_id,emp_hist.sub_dept_id) sub_dept_id,if(emp_hist.sub_dept_name is null,b.sub_dept_name,emp_hist.sub_dept_name) sub_dept_name,a.group_department,COUNT(IF(CASE WHEN(absen_ijin.kode_ijin_payroll is null) THEN 
        CASE WHEN(c.mulai_jam_kerja is not null and c.akhir_jam_kerja is not null) THEN 
            CASE WHEN(c.absen_masuk_kerja is not null and c.absen_pulang_kerja is not null)THEN
                CASE WHEN(c.jumlah_menit_absen_dt!=0 and c.jumlah_menit_absen_pc=0) THEN 'DT'
                WHEN(c.jumlah_menit_absen_dt=0 and c.jumlah_menit_absen_pc!=0) THEN 'PC'
                WHEN(c.jumlah_menit_absen_dt!=0 and c.jumlah_menit_absen_pc!=0) THEN 'DTPC'
                ELSE 'OK'
                END
            WHEN(c.absen_masuk_kerja is not null and c.absen_pulang_kerja is not null and c.status_absen='R') THEN 'R'
            WHEN(c.absen_masuk_kerja is not null and c.absen_pulang_kerja is null and c.status_absen!='R') THEN 'M'
            WHEN(c.absen_masuk_kerja is null and c.absen_pulang_kerja is not null and c.status_absen!='R') THEN 'M'
            WHEN(c.absen_masuk_kerja is not null and c.absen_pulang_kerja is null and c.status_absen='R') THEN 'R' 
            WHEN(c.absen_masuk_kerja is null and c.absen_pulang_kerja is null and c.status_absen!='R') THEN 'M' 
            WHEN(c.absen_masuk_kerja is null and c.absen_pulang_kerja is null and c.status_absen='R') THEN 'R'
            END
        ELSE 
            CASE WHEN(c.status_absen='LN') THEN 'LBY'
            WHEN (c.status_absen='R') THEN 'R'
            ELSE 'LSM'
            END
        END 
        WHEN(absen_ijin.kode_ijin_payroll='ITB') THEN
            CASE WHEN(c.status_absen='M')THEN 'M'
            WHEN(c.status_absen='IKS') THEN 
                CASE WHEN(c.absen_masuk_kerja is not null and c.absen_pulang_kerja is not null)THEN
                    CASE WHEN(c.jumlah_menit_absen_dt!=0 and c.jumlah_menit_absen_pc=0) THEN 'DT'
                    WHEN(c.jumlah_menit_absen_dt=0 and c.jumlah_menit_absen_pc!=0) THEN 'PC'
                    WHEN(c.jumlah_menit_absen_dt!=0 and c.jumlah_menit_absen_pc!=0) THEN 'DTPC'
                    ELSE 'OK'
                    END
                ELSE 'OK'
                END
            WHEN(c.status_absen='LP') THEN 'LP'
            WHEN(c.status_absen='S') THEN 'S'
            ELSE 'ITB'
            END
        WHEN(absen_ijin.kode_ijin_payroll='IBY') THEN
            CASE WHEN(c.status_absen='DL') THEN 'DL'
            ELSE 'IBY'
            END
        ELSE absen_ijin.kode_ijin_payroll END in ('OK','DT','PC','DTPC','IBY','IKS'),1,null)) man_power,
        SUM(CASE WHEN(c.absen_masuk_kerja is not null and c.absen_pulang_kerja is not null) THEN
            CASE WHEN(c.kode_hari not in (5,6))THEN
                CASE WHEN(TIME_TO_SEC(c.absen_masuk_kerja)<TIME_TO_SEC(c.absen_pulang_kerja))THEN
                    ((TIME_TO_SEC(c.absen_pulang_kerja)-TIME_TO_SEC(c.absen_masuk_kerja))/60)-60
                WHEN(TIME_TO_SEC(c.absen_pulang_kerja)<TIME_TO_SEC(c.absen_masuk_kerja))THEN
                    ((TIME_TO_SEC(c.absen_masuk_kerja)-TIME_TO_SEC(c.absen_pulang_kerja))/60)-60
                END
            ELSE
                CASE WHEN(TIME_TO_SEC(c.absen_masuk_kerja)<TIME_TO_SEC(c.absen_pulang_kerja))THEN
                    ((TIME_TO_SEC(c.absen_pulang_kerja)-TIME_TO_SEC(c.absen_masuk_kerja))/60)-30
                WHEN(TIME_TO_SEC(c.absen_pulang_kerja)<TIME_TO_SEC(c.absen_masuk_kerja))THEN
                    ((TIME_TO_SEC(c.absen_masuk_kerja)-TIME_TO_SEC(c.absen_pulang_kerja))/60)-30
                END
            END
        ELSE 0 END) absen_menit,
        c.mulai_jam_kerja,c.status_absen,c.absen_masuk_kerja,c.absen_pulang_kerja,c.kode_hari,sum(a.bruto) bruto,sum(a.bpjs_tk_company) bpjs_tk,sum(a.bpjs_ks_company) bpjs_ks,sum(a.thr) thr from daily_labor_costs a inner join employee_atribut b on a.enroll_id=b.enroll_id inner join master_data_absen_kehadiran c on a.enroll_id=c.enroll_id and a.tanggal_berjalan=c.tanggal_berjalan left join data_lembur d on a.enroll_id=d.enroll_id and a.tanggal_berjalan=d.tanggal_berjalan left join ref_absen_ijin absen_ijin on c.status_absen=absen_ijin.kode_absen_ijin left join employee_atribut_histories emp_hist on a.enroll_id=emp_hist.enroll_id and a.tanggal_berjalan between SUBSTRING(emp_hist.periode_payroll,1,10) and SUBSTRING(emp_hist.periode_payroll,16,10) where a.tanggal_berjalan>='".$this->tanggal_awal."' and a.tanggal_berjalan<='".$this->tanggal_akhir."' and (b.status_staff='STAFF' or emp_hist.status_staff='STAFF') group by a.tanggal_berjalan");
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
        $aa=[];
        foreach($query_4 as $valquery){
            $aa[]=[
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
        $ab=[];
        foreach($query_3 as $value){
            $x[]=[
                'department_id'=>$value->department_id,
                'department_name'=>$value->department_name,
                'sub_dept_id'=>$value->sub_dept_id,
                'sub_dept_name'=>$value->sub_dept_name,
                'group_department'=>$value->group_department,
                'gaji'=>collect($z)->where('sub_dept_id',$value->sub_dept_id)->sortBy('tanggal_berjalan'),
            ];
        }
        $ab=collect($aa);
        return view('hris.recap_labor_staff',compact('x','ab','dateRange'));
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
