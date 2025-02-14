<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PermintaanTransportasiExport extends DefaultValueBinder implements FromView, WithColumnFormatting, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($query,$tanggal_awal,$tanggal_akhir)
    {
        $this->query=$query;
        $this->tanggal_awal=$tanggal_awal;
        $this->tanggal_akhir=$tanggal_akhir;
    }
    public function view(): View
    {
        return view('hris.Laporan.permintaan_transportasi',[
            'query'=>$this->query,
            'tanggal_awal'=>$this->tanggal_awal,
            'tanggal_akhir'=>$this->tanggal_akhir
        ]);
    }
    public function columnFormats(): array
    {
        return [
            'K' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }
}
