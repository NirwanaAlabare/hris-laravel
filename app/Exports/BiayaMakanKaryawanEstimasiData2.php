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

class BiayaMakanKaryawanEstimasiData2 implements FromView, WithTitle, WithColumnFormatting
{
    use Exportable;
    protected $dateFrom;
    protected $dataLembur1;
    protected $dataLembur2;
    protected $dataLembur3;
    protected $dataLembur4;
    protected $dataLembur5;
    public function __construct($dateFrom)
    {
        $this->dateFrom = $dateFrom ? $dateFrom : date('Y-m-d');

        $this->dataLembur1 = DB::select("select 'Lembur' shift,b.department_name department,non_staff,if(non_staff!=0,8000,0) harga,8000*non_staff jumlah,staff,if(staff!=0,10000,0) harga2,10000*staff jumlah2,staff+non_staff jumlah_karyawan,(non_staff*8000)+(staff*10000) total from estimasi_anggaran_makan inner join (select*from department_all where site_nirwana_id IN ('NAG','NAK','NAGD') and status='AKTIF' group by department_id) b on estimasi_anggaran_makan.dept=b.department_id where tanggal='$dateFrom' and keterangan='LEMBUR' order by keterangan,dept");
        $this->dataLembur2 = DB::select("select 'Shift Malam' shift,b.department_name department,non_staff,if(non_staff!=0,8000,0) harga,8000*non_staff jumlah,staff,if(staff!=0,10000,0) harga2,10000*staff jumlah2,staff+non_staff jumlah_karyawan,(non_staff*8000)+(staff*10000) total from estimasi_anggaran_makan inner join (select*from department_all where site_nirwana_id IN ('NAG','NAK','NAGD') and status='AKTIF' group by department_id) b on estimasi_anggaran_makan.dept=b.department_id where tanggal='$dateFrom' and keterangan='SHIFT MALAM' order by keterangan,dept");
        $this->dataLembur3 = DB::select("select 'LEMBUR TOTAL' shift,'' department,sum(non_staff) as non_staff,sum(if(non_staff!=0,1,0))*8000 harga,8000*sum(non_staff) jumlah,sum(staff) staff,sum(if(staff!=0,1,0))*10000 harga2,10000*sum(staff) jumlah2,sum(staff)+sum(non_staff) jumlah_karyawan,(sum(non_staff)*8000)+(sum(staff)*10000) total from estimasi_anggaran_makan inner join (select*from department_all where site_nirwana_id IN ('NAG','NAK','NAGD') and status='AKTIF' group by department_id) b on estimasi_anggaran_makan.dept=b.department_id where tanggal='$dateFrom' and keterangan='LEMBUR' group by keterangan order by keterangan,dept");
        $this->dataLembur4 = DB::select("select 'SHIFT MALAM TOTAL' shift,'' department,sum(non_staff) as non_staff,sum(if(non_staff!=0,1,0))*8000 harga,8000*sum(non_staff) jumlah,sum(staff) staff,sum(if(staff!=0,1,0))*10000 harga2,10000*sum(staff) jumlah2,sum(staff)+sum(non_staff) jumlah_karyawan,(sum(non_staff)*8000)+(sum(staff)*10000) total from estimasi_anggaran_makan inner join (select*from department_all where site_nirwana_id IN ('NAG','NAK','NAGD') and status='AKTIF' group by department_id) b on estimasi_anggaran_makan.dept=b.department_id where tanggal='$dateFrom' and keterangan='SHIFT MALAM' group by keterangan order by keterangan,dept");
        $this->dataLembur5 = DB::select("select 'GRAND TOTAL' shift,'' department,sum(non_staff) as non_staff,sum(if(non_staff!=0,1,0))*8000 harga,8000*sum(non_staff) jumlah,sum(staff) staff,sum(if(non_staff!=0,1,0))*10000 harga2,10000*sum(staff) jumlah2,sum(staff)+sum(non_staff) jumlah_karyawan,(sum(non_staff)*8000)+(sum(staff)*10000) total from estimasi_anggaran_makan inner join (select*from department_all where site_nirwana_id IN ('NAG','NAK','NAGD') and status='AKTIF' group by department_id) b on estimasi_anggaran_makan.dept=b.department_id where tanggal='$dateFrom' group by shift order by keterangan,dept");

    }
    public function title(): string
    {
        return 'Summary';
    }
    public function view(): View
    {
        return view('hris/mutasi-karyawan/form-lembur-sewing/konsumsi_karyawan_estimasi_summary', [
            'dateFrom' => $this->dateFrom,
            'data_lembur1' => $this->dataLembur1,
            'data_lembur2' => $this->dataLembur2,
            'data_lembur3' => $this->dataLembur3,
            'data_lembur4' => $this->dataLembur4,
            'data_lembur5' => $this->dataLembur5,
        ]);
    }
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
        ];
    }
}
