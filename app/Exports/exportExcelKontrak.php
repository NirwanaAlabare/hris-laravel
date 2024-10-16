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
            'K' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'L' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'P' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'Q' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'R' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'S' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'T' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'U' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'V' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'W' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'X' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'Y' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'Z' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AA' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AB' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AC' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AD' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AE' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AF' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AG' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AH' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AI' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AJ' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AK' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AL' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AM' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AN' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AO' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AP' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AQ' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AR' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AS' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AT' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AU' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AV' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AW' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AX' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AY' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'AZ' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BA' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BB' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BC' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BD' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BE' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BF' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BG' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BH' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BI' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BJ' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BK' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BL' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BM' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BN' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BO' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BP' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BQ' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BR' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BS' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BT' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BU' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BV' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BW' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BX' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BY' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'BZ' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CA' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CB' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CC' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CD' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CE' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CF' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CG' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CH' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CI' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CJ' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CK' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CL' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CM' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CN' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CO' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CP' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CQ' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CR' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CS' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CT' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CU' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CV' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CW' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CX' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CY' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'CZ' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'DA' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'DB' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'DC' => NumberFormat::FORMAT_DATE_YYYYMMDD,
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
