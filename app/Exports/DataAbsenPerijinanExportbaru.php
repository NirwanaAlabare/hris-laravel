<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;



class DataAbsenPerijinanExportbaru implements FromCollection, WithHeadings, WithEvents,  WithCustomStartCell,
    ShouldAutoSize
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($row) {
            return [
                Date::stringToExcel($row->tanggal_perizinan),
                $row->nomor_form_perizinan,
                $row->nik,
                $row->employee_name,
                Date::stringToExcel($row->tanggal_mulai_ijin),
                Date::stringToExcel($row->tanggal_akhir_ijin),
                $row->kode_absen_ijin,
                $row->absen_alasan,
            ];
        });
    }
    public function startCell(): string
{
    return 'A4';
}

    public function headings(): array
{
    return [
        'Tanggal Pengajuan',
        'Nomor Form',
        'NIK',
        'Nama Karyawan',
        'Tanggal Mulai Ijin',
        'Tanggal Akhir Ijin',
        'Nama Absen Ijin',
        'Alasan',
    ];
}
  public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {

            $sheet = $event->sheet->getDelegate();

            // Judul
            $sheet->mergeCells('A1:F1');
            $sheet->setCellValue('A1', 'DATA ABSEN PERIJINAN');

            // Periode
            $sheet->mergeCells('A2:F2');
            // $sheet->setCellValue('A2', 'Periode : 01-08-2026 s/d 31-08-2026');

            // Style Judul
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

            // Style Periode
            $sheet->getStyle('A2')->getFont()->setBold(true);
            $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

            // Header
            $sheet->getStyle('A4:H4')->getFont()->setBold(true);
            $sheet->getStyle('A4:H4')->getAlignment()->setHorizontal('center');

            // Border Header
            $sheet->getStyle('A4:H4')->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ]);

            // Border Semua Data
            $lastRow = $sheet->getHighestRow();
            $sheet->getStyle("A5:A{$lastRow}")
                ->getNumberFormat()
                ->setFormatCode('dd-mm-yyyy');

            $sheet->getStyle("E5:F{$lastRow}")
                ->getNumberFormat()
                ->setFormatCode('dd-mm-yyyy');

            $sheet->getStyle("A4:H{$lastRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ]);

            // Tengah Header
            $sheet->getStyle("A4:H4")
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Tengah Tanggal
            $sheet->getStyle("A5:A{$lastRow}")
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        },
    ];
}
}
