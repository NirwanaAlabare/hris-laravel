<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class VerifikasiKoreksiPotonganExport implements FromView,WithColumnFormatting
{
    protected $DataKoreksiPotongan;


    function __construct($data) {
        $this->data = $data;
    }
    public function view(): View
    {
        return view('hris.Laporan.VerifikasiKoreksiPotonganExcel',[
            'data' => $this->data,
        ]);
    }

     public function columnFormats(): array
    {
        return [
            'K' => NumberFormat::FORMAT_DATE_XLSX15,
        ];
    }
}
