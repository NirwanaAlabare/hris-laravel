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

class ExcelPenilaianKinerjaNonstaff implements FromView, WithColumnWidths, WithColumnFormatting, WithEvents
{
    use Exportable;
    protected $query;
    public function __construct($query)
    {
        $this->query = $query;
    }
    public function view(): View
    {
        return view('hris.hrd.excel_penilaian_kinerja_nonstaff',[
            'query' => $this->query,
        ]);
    }
    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_DATE_XLSX15,
            'F' => NumberFormat::FORMAT_DATE_XLSX15,
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 30,
            'C' => 30,
            'D' => 30,
            'E' => 22,
            'F' => 22,
            'G' => 35,
            'H' => 5,
            'I' => 5,
            'J' => 5,
            'K' => 5,
            'L' => 7,
            'M' => 7,
            'N' => 20,
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
                $sheet->getDelegate()->getStyle('A4:M4')->applyFromArray($header_style);
                $sheet->getDelegate()->getStyle('K5:L5')->applyFromArray($header_style);
            }
        ];
    }
}
