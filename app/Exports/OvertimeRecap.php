<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class OvertimeRecap implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $dateFrom,$overtime,$overtime2;
    public function __construct($dateFrom)
    {
        $this->dateFrom = $dateFrom ? $dateFrom : date('Y-m-d');
        $this->overtime = DB::select("select z.shift,z.department,count(if(z.status_staff='NON STAFF',1,null)) non_staff,count(if(z.status_staff='STAFF',1,null)) staff,count(if(z.status_staff='NON STAFF',1,null))+count(if(z.status_staff='STAFF',1,null)) jumlah from(select if(a.jam_lembur_awal_rencana>='04:00:00' and a.jam_lembur_akhir_rencana>='06:00:00' and a.jam_lembur_akhir_rencana<='09:00:00','SHIFT MALAM','LEMBUR') shift,a.enroll_id,c.department_name department,d.status_staff status_staff from mut_karyawan_input_non_sewing_form_lembur_det a inner join mut_karyawan_input_non_sewing_form_lembur b on a.no_form=b.no_form inner join (select*from department_all where status='AKTIF' and site_nirwana_id IN ('NAG','NAK','NAGD') group by sub_dept_name) c on b.dept=c.sub_dept_name inner join employee_atribut d on a.enroll_id=d.enroll_id where b.tgl_lembur='$dateFrom' having shift='LEMBUR'
        UNION
        select if(a.jam_lembur_awal_rencana>='04:00:00' and a.jam_lembur_akhir_rencana>='06:00:00' and a.jam_lembur_akhir_rencana<='09:00:00','SHIFT MALAM','LEMBUR') shift,a.enroll_id,c.department_name department,d.status_staff status_staff from mut_karyawan_input_form_lembur_det a inner join mut_karyawan_input_form_lembur b on a.no_form=b.no_form inner join (select*from department_all where status='AKTIF' and site_nirwana_id IN ('NAG','NAK','NAGD') group by sub_dept_name) c on b.line=c.sub_dept_name inner join employee_atribut d on a.enroll_id=d.enroll_id where b.tgl_lembur='$dateFrom' having shift='LEMBUR')z
        group by shift,department
        order by department");
        $this->overtime2 = DB::select("select z.shift,z.department,count(if(z.status_staff='NON STAFF',1,null)) non_staff,count(if(z.status_staff='STAFF',1,null)) staff,count(if(z.status_staff='NON STAFF',1,null))+count(if(z.status_staff='STAFF',1,null)) jumlah from(select if(a.jam_lembur_awal_rencana>='04:00:00' and a.jam_lembur_akhir_rencana>='06:00:00' and a.jam_lembur_akhir_rencana<='09:00:00','SHIFT MALAM','LEMBUR') shift,a.enroll_id,c.department_name department,d.status_staff status_staff from mut_karyawan_input_non_sewing_form_lembur_det a inner join mut_karyawan_input_non_sewing_form_lembur b on a.no_form=b.no_form inner join (select*from department_all where status='AKTIF' and site_nirwana_id IN ('NAG','NAK','NAGD') group by sub_dept_name) c on b.dept=c.sub_dept_name inner join employee_atribut d on a.enroll_id=d.enroll_id where b.tgl_lembur='$dateFrom' having shift='LEMBUR'
        UNION
        select if(a.jam_lembur_awal_rencana>='04:00:00' and a.jam_lembur_akhir_rencana>='06:00:00' and a.jam_lembur_akhir_rencana<='09:00:00','SHIFT MALAM','LEMBUR') shift,a.enroll_id,c.department_name department,d.status_staff status_staff from mut_karyawan_input_form_lembur_det a inner join mut_karyawan_input_form_lembur b on a.no_form=b.no_form inner join (select*from department_all where status='AKTIF' and site_nirwana_id IN ('NAG','NAK','NAGD') group by sub_dept_name) c on b.line=c.sub_dept_name inner join employee_atribut d on a.enroll_id=d.enroll_id where b.tgl_lembur='$dateFrom' having shift='LEMBUR')z
        group by shift");
        $this->overtime3 = DB::select("select z.shift,z.department,count(if(z.status_staff='NON STAFF',1,null)) non_staff,count(if(z.status_staff='STAFF',1,null)) staff,count(if(z.status_staff='NON STAFF',1,null))+count(if(z.status_staff='STAFF',1,null)) jumlah from(select if(a.jam_lembur_awal_rencana>='04:00:00' and a.jam_lembur_akhir_rencana>='06:00:00' and a.jam_lembur_akhir_rencana<='09:00:00','SHIFT MALAM','LEMBUR') shift,a.enroll_id,c.department_name department,d.status_staff status_staff from mut_karyawan_input_non_sewing_form_lembur_det a inner join mut_karyawan_input_non_sewing_form_lembur b on a.no_form=b.no_form inner join (select*from department_all where status='AKTIF' and site_nirwana_id IN ('NAG','NAK','NAGD') group by sub_dept_name) c on b.dept=c.sub_dept_name inner join employee_atribut d on a.enroll_id=d.enroll_id where b.tgl_lembur='$dateFrom' having shift='SHIFT MALAM'
        UNION
        select if(a.jam_lembur_awal_rencana>='04:00:00' and a.jam_lembur_akhir_rencana>='06:00:00' and a.jam_lembur_akhir_rencana<='09:00:00','SHIFT MALAM','LEMBUR') shift,a.enroll_id,c.department_name department,d.status_staff status_staff from mut_karyawan_input_form_lembur_det a inner join mut_karyawan_input_form_lembur b on a.no_form=b.no_form inner join (select*from department_all where status='AKTIF' and site_nirwana_id IN ('NAG','NAK','NAGD') group by sub_dept_name) c on b.line=c.sub_dept_name inner join employee_atribut d on a.enroll_id=d.enroll_id where b.tgl_lembur='$dateFrom' having shift='SHIFT MALAM')z
        group by shift,department
        order by department");
        $this->overtime4 = DB::select("select z.shift,z.department,count(if(z.status_staff='NON STAFF',1,null)) non_staff,count(if(z.status_staff='STAFF',1,null)) staff,count(if(z.status_staff='NON STAFF',1,null))+count(if(z.status_staff='STAFF',1,null)) jumlah from(select if(a.jam_lembur_awal_rencana>='04:00:00' and a.jam_lembur_akhir_rencana>='06:00:00' and a.jam_lembur_akhir_rencana<='09:00:00','SHIFT MALAM','LEMBUR') shift,a.enroll_id,c.department_name department,d.status_staff status_staff from mut_karyawan_input_non_sewing_form_lembur_det a inner join mut_karyawan_input_non_sewing_form_lembur b on a.no_form=b.no_form inner join (select*from department_all where status='AKTIF' and site_nirwana_id IN ('NAG','NAK','NAGD') group by sub_dept_name) c on b.dept=c.sub_dept_name inner join employee_atribut d on a.enroll_id=d.enroll_id where b.tgl_lembur='$dateFrom' having shift='SHIFT MALAM'
        UNION
        select if(a.jam_lembur_awal_rencana>='04:00:00' and a.jam_lembur_akhir_rencana>='06:00:00' and a.jam_lembur_akhir_rencana<='09:00:00','SHIFT MALAM','LEMBUR') shift,a.enroll_id,c.department_name department,d.status_staff status_staff from mut_karyawan_input_form_lembur_det a inner join mut_karyawan_input_form_lembur b on a.no_form=b.no_form inner join (select*from department_all where status='AKTIF' and site_nirwana_id IN ('NAG','NAK','NAGD') group by sub_dept_name) c on b.line=c.sub_dept_name inner join employee_atribut d on a.enroll_id=d.enroll_id where b.tgl_lembur='$dateFrom' having shift='SHIFT MALAM')z
        group by shift");
        $this->overtime5 = DB::select("select z.shift,z.department,count(if(z.status_staff='NON STAFF',1,null)) non_staff,count(if(z.status_staff='STAFF',1,null)) staff,count(if(z.status_staff='NON STAFF',1,null))+count(if(z.status_staff='STAFF',1,null)) jumlah from(select 'TOTAL' shift,a.enroll_id,c.department_name department,d.status_staff status_staff from mut_karyawan_input_non_sewing_form_lembur_det a inner join mut_karyawan_input_non_sewing_form_lembur b on a.no_form=b.no_form inner join (select*from department_all where status='AKTIF' and site_nirwana_id IN ('NAG','NAK','NAGD') group by sub_dept_name) c on b.dept=c.sub_dept_name inner join employee_atribut d on a.enroll_id=d.enroll_id where b.tgl_lembur='$dateFrom'
        UNION
        select 'TOTAL' shift,a.enroll_id,c.department_name department,d.status_staff status_staff from mut_karyawan_input_form_lembur_det a inner join mut_karyawan_input_form_lembur b on a.no_form=b.no_form inner join (select*from department_all where status='AKTIF' and site_nirwana_id IN ('NAG','NAK','NAGD') group by sub_dept_name) c on b.line=c.sub_dept_name inner join employee_atribut d on a.enroll_id=d.enroll_id where b.tgl_lembur='$dateFrom')z
        group by shift");
    }
    public function view(): View
    {
        return view('hris/mutasi-karyawan/form-lembur-sewing/overtime_recap', [
            'date_from' => $this->dateFrom,
            'overtime' => $this->overtime,
            'overtime2' => $this->overtime2,
            'overtime3' => $this->overtime3,
            'overtime4' => $this->overtime4,
            'overtime5' => $this->overtime5
        ]);
    }
}
