<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use DB;

class exportExcelHadirLayoff implements FromView, WithColumnWidths, WithColumnFormatting, WithEvents
{
    use Exportable;
    protected $hasil;
    public function __construct($hasil)
    {
        $this->hasil = $hasil;
    }
    public function view(): View
    {
        return view('hris.hrd.excel_rekap_hadir_layoff',[
            'hasil' => $this->hasil,
        ]);
    }
    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_DATE_XLSX15,
            'I' => NumberFormat::FORMAT_DATE_XLSX15,

        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 12,
            'C' => 14,
            'D' => 22,
            'E' => 20,
            'F' => 18,
            'G' => 21,
            'H' => 21,
            'I' => 11,
            'J' => 11,
            'K' => 15,
        ];
    }
    public function registerEvents() : array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $default_font_style = [
                    'font' => [
                        'name' => 'Calibri',
                        'italic' => true,
                        'size' => 12
                    ],
                ];
                $header_style = [
                    'font' => [
                        'name' => 'Calibri',
                        'bold' => true,
                        'size' => 8
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_TOP,
                        'wrapText' => true,
                    ],
                ];
                $text_style = [
                    'font' => [
                        'name' => 'Calibri',
                        'size' => 8
                    ],
                ];
                $sheet = $event->sheet;
                $sheet->getDelegate()->getStyle('A1')->applyFromArray($default_font_style);
                $sheet->getDelegate()->getStyle('A2:DG2')->applyFromArray($header_style);
                $sheet->getDelegate()->getStyle('A3:DG6000')->applyFromArray($text_style);
            }
        ];
    }
}
