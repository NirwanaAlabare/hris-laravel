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

class BiayaMakanKaryawanData implements FromView, WithTitle, WithColumnFormatting
{
    use Exportable;
    protected $dateFrom,$dateTo,$dataLembur;
    public function __construct($dateFrom, $dateTo)
    {
        $this->dateFrom = $dateFrom ? $dateFrom : date('Y-m-d');
        $this->dateTo = $dateTo ? $dateTo : date('Y-m-d');
        $this->dataLembur = DB::select("select z.periode,z.tanggal_lembur,z.department,z.staff_nonstaff,count(z.enroll_id) jumlah_karyawan,z.harga,count(z.enroll_id)*z.harga as jumlah,z.shift from (select '2024-07' as periode,c.tgl_lembur tanggal_lembur,a.enroll_id,d.department_name department,e.status_staff staff_nonstaff,IF(e.status_staff='NON STAFF',8000,10000) harga,if(a.jam_lembur_awal_rencana>='04:00:00' and a.jam_lembur_akhir_rencana>='06:00:00' and a.jam_lembur_akhir_rencana<='09:00:00','SHIFT MALAM','LEMBUR') shift from mut_karyawan_input_non_sewing_form_lembur_det a inner join employee_atribut e on a.enroll_id=e.enroll_id inner join mut_karyawan_input_non_sewing_form_lembur c on a.no_form=c.no_form inner join department_all d on c.dept=d.sub_dept_name where c.tgl_lembur='$dateFrom' and d.status='AKTIF' and d.site_nirwana_id='NAG' and a.konsumsi=1
        UNION
        select '2024-07' as periode,c.tgl_lembur tanggal_lembur,a.enroll_id,d.department_name department,e.status_staff staff_nonstaff,IF(e.status_staff='NON STAFF',8000,10000) harga,if(a.jam_lembur_awal_rencana>='04:00:00' and a.jam_lembur_akhir_rencana>='06:00:00' and a.jam_lembur_akhir_rencana<='09:00:00','SHIFT MALAM','LEMBUR') shift from mut_karyawan_input_form_lembur_det a inner join employee_atribut e on a.enroll_id=e.enroll_id inner join mut_karyawan_input_form_lembur c on a.no_form=c.no_form inner join department_all d on c.line=d.sub_dept_name where c.tgl_lembur='$dateFrom' and d.status='AKTIF' and d.site_nirwana_id='NAG' and a.konsumsi=1) z
        group by shift,staff_nonstaff,department
        order by department");
    }
    public function title(): string
    {
        return 'Data';
    }
    public function view(): View
    {
        return view('hris/mutasi-karyawan/form-lembur-sewing.konsumsi_karyawan', [
            'data_lembur' => $this->dataLembur,
        ]);
    }
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
        ];
    }
}
