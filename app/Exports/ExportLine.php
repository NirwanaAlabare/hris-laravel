<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use DB;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

// class ExportLaporanPemakaian implements FromCollection
// {
//     /**
//      * @return \Illuminate\Support\Collection
//      */
//     public function collection()
//     {
//         return Marker::all();
//     }
// }

class ExportLine implements FromView, WithEvents, ShouldAutoSize, WithDrawings, WithColumnWidths
{
    use Exportable;


    protected $data;
    protected $line;
    protected $rowCount;

    public function __construct($data, $line)
    {
        $this->rowCount = 0;
        $this->data = $data;
        $this->line = $line;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('NAG Logo');
        $drawing->setPath(public_path('/assets/images/brand/logo-transparent.png'));
        $drawing->setHeight(40);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(30);
        $drawing->setOffsetY(20);

        return $drawing;
    }


    public function view(): View

    {
        // $data = DB::select("
        // select *,cast(right(line,2) as UNSIGNED) urutan from
        // (
        // select max(a.id)id from
        // (
        // select * from mut_karyawan_input
        // where tgl_pindah <= '$this->from'
        // union all
        // select * from mut_karyawan_input
        // where tgl_pindah >= '$this->to'
        // ) a
        // group by a.nik
        // ) b
        // inner join  mut_karyawan_input c on b.id = c.id
        // order by tgl_pindah asc,nm_karyawan asc, urutan asc
        // ");

        $this->rowCount = count($this->data) + 10;

        return view('hris/mutasi-karyawan.exportline', [
            'data' => $this->data,
            'line' => $this->line
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => [self::class, 'afterSheet']
        ];
    }

    public static function afterSheet(AfterSheet $event)
    {

        $event->sheet->styleCells(
            'A10:H' . ($event->getConcernable()->rowCount%2 == 0 ? $event->getConcernable()->rowCount : $event->getConcernable()->rowCount+1) + 1,
            [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]
        );

        $event->sheet->styleCells(
            'A1:H4',
            [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]
        );

        $event->sheet->styleCells(
            'H5:H9',
            [
                'borders' => [
                    'right' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]
        );

        $event->sheet->styleCells(
            'A5:A9',
            [
                'borders' => [
                    'left' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]
        );

        $event->sheet->styleCells(
            'A'.(($event->getConcernable()->rowCount%2 == 0 ? $event->getConcernable()->rowCount : $event->getConcernable()->rowCount+1) + 1).':A' .(($event->getConcernable()->rowCount%2 == 0 ? $event->getConcernable()->rowCount%2 : $event->getConcernable()->rowCount+1) + 1 + 8),
            [
                'borders' => [
                    'left' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]
        );

        $event->sheet->styleCells(
            'H'.(($event->getConcernable()->rowCount%2 == 0 ? $event->getConcernable()->rowCount : $event->getConcernable()->rowCount+1) + 1).':H' .(($event->getConcernable()->rowCount%2 == 0 ? $event->getConcernable()->rowCount : $event->getConcernable()->rowCount+1) + 1 + 8),
            [
                'borders' => [
                    'right' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]
        );

        $event->sheet->styleCells(
            'A'.(($event->getConcernable()->rowCount%2 == 0 ? $event->getConcernable()->rowCount : $event->getConcernable()->rowCount+1) + 1 + 8).':H' .(($event->getConcernable()->rowCount%2 == 0 ? $event->getConcernable()->rowCount : $event->getConcernable()->rowCount+1) + 1 + 8),
            [
                'borders' => [
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]
        );
    }

    public function columnWidths(): array
    {
        return [
            'A' => 4,
            'B' => 17,
            'C' => 15,
            'D' => 15,
            'E' => 17,
            'G' => 25,
            'H' => 25
        ];
    }
}
