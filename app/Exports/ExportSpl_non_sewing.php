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

class ExportSpl_non_sewing implements FromView, WithEvents, ShouldAutoSize, WithDrawings, WithColumnWidths
{
    use Exportable;


    protected $data;
    protected $id;
    protected $rowCount;

    public function __construct($data, $id)
    {
        $this->rowCount = 0;
        $this->data = $data;
        $this->id = $id;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('NAG Logo');
        $drawing->setPath(public_path('/assets/dist/img/nag-logo.png'));
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(30);
        $drawing->setOffsetY(10);

        return $drawing;
    }


    public function view(): View

    {

        $this->rowCount = count($this->data) + 10;


        $master_dept = DB::select(
            "SELECT dept, tgl_lembur,no_form from mut_karyawan_input_non_sewing_form_lembur
            where id = '$this->id'"
        );
        $dept = $master_dept[0]->dept;
        $tgl_lembur = $master_dept[0]->tgl_lembur;
        $no_form = $master_dept[0]->no_form;
        // dd($this->id);
        return view('hris/mutasi-karyawan/form-lembur-sewing/export_spl_non_sewing', [
            'data' => $this->data,
            'id' => $this->id,
            'dept' => $dept,
            'tgl_lembur' => $tgl_lembur,
            'no_form' => $no_form
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

        $event->sheet->getStyle('A1:M4')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
        $event->sheet->getStyle('A6:D6')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
        $event->sheet->getStyle('A7:D7')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        $event->sheet->getStyle('A5:A8')->applyFromArray([
            'borders' => [
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
        $event->sheet->getStyle('M5')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
        $event->sheet->getStyle('M6')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
        $event->sheet->getStyle('M7')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
        $event->sheet->getStyle('M8')->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        $event->sheet->getStyle('J6:M6')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        $event->sheet->getStyle('F9:M9')
            ->getAlignment()
            ->setHorizontal('center')
            ->setWrapText(true);

        $event->sheet->getStyle('A9:M9')
            ->getAlignment()
            ->setHorizontal('center')
            ->setVertical('center');

        $event->sheet->getDelegate()->getStyle('A9:M9')
            ->getFont()
            ->setBold(true);

        // $event->sheet->getDelegate()->getRowDimension('10')->setRowHeight(25);

        // $event->sheet->protectCells('A1:F1', 'PASSWORD');
        // $event->sheet->getStyle('A2:F50')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
        // here in the place of F50 you can pass maximum range like F2000
        $event->sheet->getDelegate()->getProtection()->setSheet(true);

        $event->sheet->styleCells(
            'A9:M' . ($event->getConcernable()->rowCount % 1 == 0 ? $event->getConcernable()->rowCount : $event->getConcernable()->rowCount + 1) - 1,
            [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]
        );

        // $event->sheet->protectCells('A9:M', 'PASSWORD');
        // $event->sheet->getDelegate()->getProtection()->setSheet(true);


        // $event->sheet->styleCells(
        //     'A1:M' . ($event->getConcernable()->rowCount % 2 == 0 ? $event->getConcernable()->rowCount : $event->getConcernable()->rowCount + 1) + 1,
        //     [
        //         'borders' => [
        //             'allBorders' => [
        //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //                 'color' => ['argb' => '000000'],
        //             ],
        //         ],
        //     ]
        // );

        // $event->sheet->styleCells(
        //     'A6:F6' . ($event->getConcernable()->rowCount % 2 == 0 ? $event->getConcernable()->rowCount : $event->getConcernable()->rowCount + 1) + 1,
        //     [
        //         'borders' => [
        //             'allBorders' => [
        //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //                 'color' => ['argb' => '000000'],
        //             ],
        //         ],
        //     ]
        // );

        // $event->sheet->styleCells(
        //     'B1:B4' . ($event->getConcernable()->rowCount % 2 == 0 ? $event->getConcernable()->rowCount : $event->getConcernable()->rowCount + 1) + 1,
        //     [
        //         'borders' => [
        //             'allBorders' => [
        //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //                 'color' => ['argb' => '000000'],
        //             ],
        //         ],
        //     ]
        // );

        // $event->sheet->styleCells(
        //     'A1:A4' . ($event->getConcernable()->rowCount % 2 == 0 ? $event->getConcernable()->rowCount : $event->getConcernable()->rowCount + 1) + 1,
        //     [
        //         'borders' => [
        //             'allBorders' => [
        //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //                 'color' => ['argb' => '000000'],
        //             ],
        //         ],
        //     ]
        // );


        // $event->sheet->styleCells(
        //     'A1:I4',
        //     [
        //         'borders' => [
        //             'allBorders' => [
        //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //                 'color' => ['argb' => '000000'],
        //             ],
        //         ],
        //     ]
        // );
        // $event->sheet->styleCells(
        //     'A5:A9',
        //     [
        //         'borders' => [
        //             'left' => [
        //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //                 'color' => ['argb' => '000000'],
        //             ],
        //         ],
        //     ]
        // );

        // $event->sheet->styleCells(
        //     'A' . (($event->getConcernable()->rowCount % 2 == 0 ? $event->getConcernable()->rowCount : $event->getConcernable()->rowCount + 1) + 1) . ':A' . (($event->getConcernable()->rowCount % 2 == 0 ? $event->getConcernable()->rowCount % 2 : $event->getConcernable()->rowCount + 1) + 1 + 8),
        //     [
        //         'borders' => [
        //             'left' => [
        //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //                 'color' => ['argb' => '000000'],
        //             ],
        //         ],
        //     ]
        // );

        // $event->sheet->styleCells(
        //     'I' . (($event->getConcernable()->rowCount % 2 == 0 ? $event->getConcernable()->rowCount : $event->getConcernable()->rowCount + 1) + 1) . ':H' . (($event->getConcernable()->rowCount % 2 == 0 ? $event->getConcernable()->rowCount : $event->getConcernable()->rowCount + 1) + 1 + 8),
        //     [
        //         'borders' => [
        //             'right' => [
        //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //                 'color' => ['argb' => '000000'],
        //             ],
        //         ],
        //     ]
        // );

        // $event->sheet->styleCells(
        //     'A' . (($event->getConcernable()->rowCount % 2 == 0 ? $event->getConcernable()->rowCount : $event->getConcernable()->rowCount + 1) + 1 + 8) . ':H' . (($event->getConcernable()->rowCount % 2 == 0 ? $event->getConcernable()->rowCount : $event->getConcernable()->rowCount + 1) + 1 + 8),
        //     [
        //         'borders' => [
        //             'bottom' => [
        //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //                 'color' => ['argb' => '000000'],
        //             ],
        //         ],
        //     ]
        // );
    }

    public function columnWidths(): array
    {
        return [
            'A' => 4,
            'C' => 20,
            'D' => 15,
            'E' => 15,
            'F' => 10,
            'G' => 10,
            'H' => 10,
            'I' => 10,
            'J' => 10,
            'K' => 10,
            'L' => 10,
            'M' => 17
        ];
    }
}
