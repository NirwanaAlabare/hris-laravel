<?php

namespace App\Exports;


use App\Models\RekapPerhitunganPayroll;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Auth;

class payrollTransferExport implements FromView, WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($bni,$cimb,$payroll,string $periode_tahun_payroll, string $periode_bulan_payroll, string $tgl_awal)
    {
        $this->bni=$bni;
        $this->cimb=$cimb;
        $this->payroll=$payroll;
        $this->periode_tahun_payroll = $periode_tahun_payroll;
        $this->periode_bulan_payroll = $periode_bulan_payroll;
        $this->tgl_awal = $tgl_awal;
    }

    public function view(): View
    {
        $tgl_awal=$this->tgl_awal;
        return view('hris.Laporan.payroll_transfer',[
            'bni'=>$this->bni,
            'cimb'=>$this->cimb,
            'payroll' => $this->payroll,
            'periode_tahun_payroll'=>$this->periode_tahun_payroll,
            'periode_bulan_payroll'=>$this->periode_bulan_payroll
        ]);
    }
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
        ];
    }
}
