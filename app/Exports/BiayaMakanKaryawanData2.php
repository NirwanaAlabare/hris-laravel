<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithTitle;
use DB;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

class BiayaMakanKaryawanData2 implements FromView, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $dateFrom,$dateTo,$dataLembur;
    public function __construct($dateFrom, $dateTo)
    {
        $this->dateFrom = $dateFrom ? $dateFrom : date('Y-m-d');
        $this->dateTo = $dateTo ? $dateTo : date('Y-m-d');
        $this->dataLembur = DB::select("select z.shift,z.department,count(if(z.staff_nonstaff='NON STAFF',1,null)) non_staff,8000 harga,count(if(z.staff_nonstaff='NON STAFF',1,null))*8000 jumlah,count(if(z.staff_nonstaff='STAFF',1,null)) staff,10000 harga2,count(if(z.staff_nonstaff='STAFF',1,null))*10000 jumlah2,count(if(z.staff_nonstaff='NON STAFF',1,null))+count(if(z.staff_nonstaff='STAFF',1,null)) jumlah_karyawan,(count(if(z.staff_nonstaff='NON STAFF',1,null))*8000)+(count(if(z.staff_nonstaff='STAFF',1,null))*10000) total from(select if(a.jam_lembur_awal_rencana>='04:00:00' and a.jam_lembur_akhir_rencana>='06:00:00' and a.jam_lembur_akhir_rencana<='09:00:00','SHIFT MALAM','LEMBUR') shift,a.enroll_id,d.status_staff staff_nonstaff,c.department_name department from mut_karyawan_input_non_sewing_form_lembur_det a inner join mut_karyawan_input_non_sewing_form_lembur b on a.no_form=b.no_form inner join (select*from department_all where status='AKTIF' and site_nirwana_id IN ('NAG','NAK','NAGD') group by department_id) c on b.dept=c.sub_dept_name inner join employee_atribut d on a.enroll_id=d.enroll_id where b.tgl_lembur='$dateFrom' and a.konsumsi=1
        union
        select if(a.jam_lembur_awal_rencana>='04:00:00' and a.jam_lembur_akhir_rencana>='06:00:00' and a.jam_lembur_akhir_rencana<='09:00:00','SHIFT MALAM','LEMBUR') shift,a.enroll_id,d.status_staff staff_nonstaff,c.department_name department from mut_karyawan_input_form_lembur_det a inner join mut_karyawan_input_form_lembur b on a.no_form=b.no_form inner join (select*from department_all where status='AKTIF' and site_nirwana_id IN ('NAG','NAK','NAGD') group by department_id) c on b.line=c.sub_dept_name inner join employee_atribut d on a.enroll_id=d.enroll_id where b.tgl_lembur='$dateFrom' and a.konsumsi=1)z
        group by shift,department
        order by shift,department");
    }
    public function title(): string
    {
        return 'Summary';
    }
    public function view(): View
    {
        return view('hris/mutasi-karyawan/form-lembur-sewing.konsumsi_karyawan_summary', [
            'data_lembur' => $this->dataLembur,
        ]);
    }
}
