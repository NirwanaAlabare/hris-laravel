<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithTitle;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

class BiayaMakanKaryawanEstimasiData3 implements FromView, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $date;
    public function __construct($date)
    {
        $this->date = $date ? $date : date('Y-m-d');
        $this->reportCutting = null;
        $this->rowCount = 0;
    }
    public function title(): string
    {
        return $this->date;
    }
    public function view(): View
    {
        return view('hris/mutasi-karyawan/form-lembur-sewing/konsumsi_karyawan_summary_estimasi_all', [
            'date' => $this->date,
        ]);
    }
}
