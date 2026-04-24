<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GradingHistoryExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize,
    WithCustomStartCell
{
    protected $data;
    // protected $periode;
    private $no = 1;

    public function __construct($data, $periodeFormatted)
    {
        $this->data = $data;
        $this->periode = $periodeFormatted;
    }

    public function collection()
    {
        return $this->data;
    }

    // Mulai tabel dari baris ke-4
    public function startCell(): string
    {
        return 'A4';
    }

    public function headings(): array
    {
        return [
            'NO',
            'ID',
            'NIP',
            'NAMA KARYAWAN',
            'KODE GRADE LAMA',
            'KODE GRADE BARU'
        ];
    }

    public function map($row): array
    {
        return [
            $this->no++,
            $row->enroll_id,
            $row->nik,
            $row->employee_name,
            $row->kode_grade_lama,
            $row->kode_grade_baru
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Judul
        $sheet->setCellValue('A1', 'LAPORAN GRADING KARYAWAN');
        $sheet->setCellValue('A2', 'Periode: ' . $this->periode);

        // Merge biar tengah
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');

        return [
            // Style Judul
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => 'center']
            ],
            2 => [
                'font' => ['italic' => true],
                'alignment' => ['horizontal' => 'center']
            ],
            // Header tabel
            4 => [
                'font' => ['bold' => true]
            ]
        ];
    }
}
