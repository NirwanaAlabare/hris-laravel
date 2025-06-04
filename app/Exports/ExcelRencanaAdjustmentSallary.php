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

class ExcelRencanaAdjustmentSallary implements FromView, WithColumnWidths, WithColumnFormatting, WithEvents
{
    use Exportable;
    protected $query;
    public function __construct($query)
    {
        $this->query = $query;
    }
    public function view(): View
    {
        return view('hris.hrd.excel_rencana_adjustment_sallary',[
            'query' => $this->query,
        ]);
    }
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_XLSX15,
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 30,
            'C' => 16,
            'D' => 16,
            'E' => 18,
            'F' => 5,
            'G' => 5,
            'H' => 5,
            'I' => 17,
            'J' => 17,
            'K' => 17,
            'L' => 17,
            'M' => 17,
            'N' => 17,
            'O' => 17,
            'P' => 12,
            'Q' => 12,
            'R' => 12,
            'S' => 12,
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
                $sheet->getDelegate()->getStyle('A4:R4')->applyFromArray($header_style);
            }
        ];
    }
}
