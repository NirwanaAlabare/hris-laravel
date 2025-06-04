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

class ExcelRekapPenilaianKinerja implements FromView, WithColumnWidths, WithColumnFormatting, WithEvents
{
    use Exportable;
    protected $query;
    public function __construct($query)
    {
        $this->query = $query;
    }
    public function view(): View
    {
        return view('hris.hrd.excel_rekap_penilaian_kinerja',[
            'query' => $this->query,
        ]);
    }
    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_DATE_XLSX15,
            'I' => NumberFormat::FORMAT_DATE_XLSX15,
            'W' => NumberFormat::FORMAT_DATE_XLSX15,
            'Y' => NumberFormat::FORMAT_DATE_XLSX15,
            'AA' => NumberFormat::FORMAT_DATE_XLSX15,
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 7,
            'C' => 20,
            'D' => 30,
            'E' => 30,
            'F' => 30,
            'G' => 18,
            'H' => 18,
            'I' => 18,
            'J' => 5,
            'K' => 5,
            'L' => 5,
            'M' => 12,
            'N' => 16,
            'O' => 12,
            'P' => 12,
            'Q' => 12,
            'R' => 12,
            'S' => 12,
            'T' => 12,
            'U' => 12,
            'V' => 12,
            'W' => 18,
            'X' => 12,
            'Y' => 18,
            'Z' => 12,
            'AA' => 18,
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
                        'size' => 10
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
                        'size' => 10
                    ],
                ];
                $sheet = $event->sheet;
                $sheet->getDelegate()->getStyle('A1')->applyFromArray($default_font_style);
                $sheet->getDelegate()->getStyle('A2')->applyFromArray($default_font_style);
                $sheet->getDelegate()->getStyle('A4:AA4')->applyFromArray($header_style);
            }
        ];
    }
}
