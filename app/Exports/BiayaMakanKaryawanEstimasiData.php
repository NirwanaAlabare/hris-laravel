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

class BiayaMakanKaryawanEstimasiData implements FromView, WithTitle, WithColumnFormatting
{
    use Exportable;
    protected $dateFrom,$dataLembur;
    public function __construct($dateFrom)
    {
        $this->dateFrom = $dateFrom ? $dateFrom : date('Y-m-d');
        $this->dataLembur = DB::select("select substr(tanggal,1,7) periode,tanggal,d.department_name department,'STAFF' staff_non_staff,staff jumlah_karyawan,10000 harga,10000*staff jumlah,keterangan shift from estimasi_anggaran_makan inner join (select*from department_all where site_nirwana_id IN ('NAG','NAK','NAGD') and status='AKTIF' group by department_id) d on estimasi_anggaran_makan.dept=d.department_id where tanggal='$dateFrom'
        UNION
        select substr(tanggal,1,7) periode,tanggal,d.department_name department,'NON STAFF' staff_non_staff,non_staff jumlah_karyawan,80000 harga,80000*non_staff jumlah,keterangan shift from estimasi_anggaran_makan inner join (select*from department_all where site_nirwana_id IN ('NAG','NAK','NAGD') and status='AKTIF' group by department_id) d on estimasi_anggaran_makan.dept=d.department_id where tanggal='$dateFrom'
        order by shift, department");
    }
    public function title(): string
    {
        return 'Data';
    }
    public function view(): View
    {
        return view('hris/mutasi-karyawan/form-lembur-sewing/konsumsi_karyawan_estimasi', [
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
