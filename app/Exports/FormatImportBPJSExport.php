<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class FormatImportBPJSExport implements FromView, ShouldAutoSize, WithColumnFormatting
{
    protected $karyawan;
    

    function __construct($karyawan) {
        $this->karyawan = $karyawan;
    }   
    public function view(): View
    {
        return view('hris.Laporan.FormatImportBPJSExcel',[
            'karyawan' => $this->karyawan,
            
        ]);
    }
    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'I' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'L' => NumberFormat::FORMAT_TEXT,
            'N' => NumberFormat::FORMAT_DATE_DDMMYYYY,

        ];
    }
}
