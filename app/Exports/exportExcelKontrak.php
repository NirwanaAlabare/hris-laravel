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

class exportExcelKontrak implements FromView, WithColumnWidths, WithColumnFormatting, WithEvents
{
    use Exportable;
    protected $query;
    public function __construct($query)
    {
        $this->query = $query;
    }
    public function view(): View
    {
        return view('hris.hrd.excel_kontrak_kerja',[
            'query' => $this->query,
        ]);
    }
    public function columnFormats(): array
    {
        return [
            'K' => NumberFormat::FORMAT_DATE_XLSX15,
            'L' => NumberFormat::FORMAT_DATE_XLSX15,
            'P' => NumberFormat::FORMAT_DATE_XLSX15,
            'Q' => NumberFormat::FORMAT_DATE_XLSX15,
            'R' => NumberFormat::FORMAT_DATE_XLSX15,
            'S' => NumberFormat::FORMAT_DATE_XLSX15,
            'T' => NumberFormat::FORMAT_DATE_XLSX15,
            'U' => NumberFormat::FORMAT_DATE_XLSX15,
            'V' => NumberFormat::FORMAT_DATE_XLSX15,
            'W' => NumberFormat::FORMAT_DATE_XLSX15,
            'X' => NumberFormat::FORMAT_DATE_XLSX15,
            'Y' => NumberFormat::FORMAT_DATE_XLSX15,
            'Z' => NumberFormat::FORMAT_DATE_XLSX15,
            'AA' => NumberFormat::FORMAT_DATE_XLSX15,
            'AB' => NumberFormat::FORMAT_DATE_XLSX15,
            'AC' => NumberFormat::FORMAT_DATE_XLSX15,
            'AD' => NumberFormat::FORMAT_DATE_XLSX15,
            'AE' => NumberFormat::FORMAT_DATE_XLSX15,
            'AF' => NumberFormat::FORMAT_DATE_XLSX15,
            'AG' => NumberFormat::FORMAT_DATE_XLSX15,
            'AH' => NumberFormat::FORMAT_DATE_XLSX15,
            'AI' => NumberFormat::FORMAT_DATE_XLSX15,
            'AJ' => NumberFormat::FORMAT_DATE_XLSX15,
            'AK' => NumberFormat::FORMAT_DATE_XLSX15,
            'AL' => NumberFormat::FORMAT_DATE_XLSX15,
            'AM' => NumberFormat::FORMAT_DATE_XLSX15,
            'AN' => NumberFormat::FORMAT_DATE_XLSX15,
            'AO' => NumberFormat::FORMAT_DATE_XLSX15,
            'AP' => NumberFormat::FORMAT_DATE_XLSX15,
            'AQ' => NumberFormat::FORMAT_DATE_XLSX15,
            'AR' => NumberFormat::FORMAT_DATE_XLSX15,
            'AS' => NumberFormat::FORMAT_DATE_XLSX15,
            'AT' => NumberFormat::FORMAT_DATE_XLSX15,
            'AU' => NumberFormat::FORMAT_DATE_XLSX15,
            'AV' => NumberFormat::FORMAT_DATE_XLSX15,
            'AW' => NumberFormat::FORMAT_DATE_XLSX15,
            'AX' => NumberFormat::FORMAT_DATE_XLSX15,
            'AY' => NumberFormat::FORMAT_DATE_XLSX15,
            'AZ' => NumberFormat::FORMAT_DATE_XLSX15,
            'BA' => NumberFormat::FORMAT_DATE_XLSX15,
            'BB' => NumberFormat::FORMAT_DATE_XLSX15,
            'BC' => NumberFormat::FORMAT_DATE_XLSX15,
            'BD' => NumberFormat::FORMAT_DATE_XLSX15,
            'BE' => NumberFormat::FORMAT_DATE_XLSX15,
            'BF' => NumberFormat::FORMAT_DATE_XLSX15,
            'BG' => NumberFormat::FORMAT_DATE_XLSX15,
            'BH' => NumberFormat::FORMAT_DATE_XLSX15,
            'BI' => NumberFormat::FORMAT_DATE_XLSX15,
            'BJ' => NumberFormat::FORMAT_DATE_XLSX15,
            'BK' => NumberFormat::FORMAT_DATE_XLSX15,
            'BL' => NumberFormat::FORMAT_DATE_XLSX15,
            'BM' => NumberFormat::FORMAT_DATE_XLSX15,
            'BN' => NumberFormat::FORMAT_DATE_XLSX15,
            'BO' => NumberFormat::FORMAT_DATE_XLSX15,
            'BP' => NumberFormat::FORMAT_DATE_XLSX15,
            'BQ' => NumberFormat::FORMAT_DATE_XLSX15,
            'BR' => NumberFormat::FORMAT_DATE_XLSX15,
            'BS' => NumberFormat::FORMAT_DATE_XLSX15,
            'BT' => NumberFormat::FORMAT_DATE_XLSX15,
            'BU' => NumberFormat::FORMAT_DATE_XLSX15,
            'BV' => NumberFormat::FORMAT_DATE_XLSX15,
            'BW' => NumberFormat::FORMAT_DATE_XLSX15,
            'BX' => NumberFormat::FORMAT_DATE_XLSX15,
            'BY' => NumberFormat::FORMAT_DATE_XLSX15,
            'BZ' => NumberFormat::FORMAT_DATE_XLSX15,
            'CA' => NumberFormat::FORMAT_DATE_XLSX15,
            'CB' => NumberFormat::FORMAT_DATE_XLSX15,
            'CC' => NumberFormat::FORMAT_DATE_XLSX15,
            'CD' => NumberFormat::FORMAT_DATE_XLSX15,
            'CE' => NumberFormat::FORMAT_DATE_XLSX15,
            'CF' => NumberFormat::FORMAT_DATE_XLSX15,
            'CG' => NumberFormat::FORMAT_DATE_XLSX15,
            'CH' => NumberFormat::FORMAT_DATE_XLSX15,
            'CI' => NumberFormat::FORMAT_DATE_XLSX15,
            'CJ' => NumberFormat::FORMAT_DATE_XLSX15,
            'CK' => NumberFormat::FORMAT_DATE_XLSX15,
            'CL' => NumberFormat::FORMAT_DATE_XLSX15,
            'CM' => NumberFormat::FORMAT_DATE_XLSX15,
            'CN' => NumberFormat::FORMAT_DATE_XLSX15,
            'CO' => NumberFormat::FORMAT_DATE_XLSX15,
            'CP' => NumberFormat::FORMAT_DATE_XLSX15,
            'CQ' => NumberFormat::FORMAT_DATE_XLSX15,
            'CR' => NumberFormat::FORMAT_DATE_XLSX15,
            'CS' => NumberFormat::FORMAT_DATE_XLSX15,
            'CT' => NumberFormat::FORMAT_DATE_XLSX15,
            'CU' => NumberFormat::FORMAT_DATE_XLSX15,
            'CV' => NumberFormat::FORMAT_DATE_XLSX15,
            'CW' => NumberFormat::FORMAT_DATE_XLSX15,
            'CX' => NumberFormat::FORMAT_DATE_XLSX15,
            'CY' => NumberFormat::FORMAT_DATE_XLSX15,
            'CZ' => NumberFormat::FORMAT_DATE_XLSX15,
            'DA' => NumberFormat::FORMAT_DATE_XLSX15,
            'DB' => NumberFormat::FORMAT_DATE_XLSX15,
            'DC' => NumberFormat::FORMAT_DATE_XLSX15,
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 1,
            'C' => 7,
            'D' => 10,
            'E' => 30,
            'F' => 16,
            'G' => 21,
            'H' => 21,
            'I' => 11,
            'J' => 11,
            'K' => 15,
            'L' => 15,
            'M' => 5,
            'N' => 5,
            'O' => 5,
            'P' => 15,
            'Q' => 15,
            'R' => 15,
            'S' => 15,
            'T' => 15,
            'U' => 15,
            'V' => 15,
            'W' => 15,
            'X' => 15,
            'Y' => 15,
            'Z' => 15,
            'AA' => 15,
            'AB' => 15,
            'AC' => 15,
            'AD' => 15,
            'AE' => 15,
            'AF' => 15,
            'AG' => 15,
            'AH' => 15,
            'AI' => 15,
            'AJ' => 15,
            'AK' => 15,
            'AL' => 15,
            'AM' => 15,
            'AN' => 15,
            'AO' => 15,
            'AP' => 15,
            'AQ' => 15,
            'AR' => 15,
            'AS' => 15,
            'AT' => 15,
            'AU' => 15,
            'AV' => 15,
            'AW' => 15,
            'AX' => 15,
            'AY' => 15,
            'AZ' => 15,
            'BA' => 15,
            'BB' => 15,
            'BC' => 15,
            'BD' => 15,
            'BE' => 15,
            'BF' => 15,
            'BG' => 15,
            'BH' => 15,
            'BI' => 15,
            'BJ' => 15,
            'BK' => 15,
            'BL' => 15,
            'BM' => 15,
            'BN' => 15,
            'BO' => 15,
            'BP' => 15,
            'BQ' => 15,
            'BR' => 15,
            'BS' => 15,
            'BT' => 15,
            'BU' => 15,
            'BV' => 15,
            'BW' => 15,
            'BX' => 15,
            'BY' => 15,
            'BZ' => 15,
            'CA' => 15,
            'CB' => 15,
            'CC' => 15,
            'CD' => 15,
            'CE' => 15,
            'CF' => 15,
            'CG' => 15,
            'CH' => 15,
            'CI' => 15,
            'CJ' => 15,
            'CK' => 15,
            'CL' => 15,
            'CM' => 15,
            'CN' => 15,
            'CO' => 15,
            'CP' => 15,
            'CQ' => 15,
            'CR' => 15,
            'CS' => 15,
            'CT' => 15,
            'CU' => 15,
            'CV' => 15,
            'CW' => 15,
            'CX' => 15,
            'CY' => 15,
            'CZ' => 15,
            'DA' => 15,
            'DB' => 15,
            'DC' => 15,
        ];
    }
    public function registerEvents() : array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $default_font_style = [
                    'font' => [
                        'name' => 'Arial Nova',
                        'bold' => true,
                        'color' => [
                            'rgb' => '2a2d8c'
                        ],
                        'size' => 12
                    ],
                ];
                $header_style = [
                    'font' => [
                        'name' => 'Arial Nova',
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
                        'name' => 'Arial Nova',
                        'size' => 8
                    ],
                ];
                $sheet = $event->sheet;
                $sheet->getDelegate()->getStyle('A1')->applyFromArray($default_font_style);
                $sheet->getDelegate()->getStyle('A2:DC2')->applyFromArray($header_style);
                $sheet->getDelegate()->getStyle('M3:DC3')->applyFromArray($header_style);
                $sheet->getDelegate()->getStyle('A4:DC6000')->applyFromArray($text_style);
            }
        ];
    }
}
