<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceLogFormattedExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $logs;

    public function __construct($logs)
    {
        $this->logs = $logs;
    }

    public function collection()
    {
        return collect($this->logs);
    }

    public function headings(): array
    {
        return [
            'Enroll ID',
            'Nama',
            'Department',
            'Tanggal',
            'Jadwal Masuk',
            'Jadwal Pulang',
            'Jam Masuk',
            'Jam Pulang',
        ];
    }

    public function map($log): array
    {
        return [
            $log->enroll_id,
            $log->Nama,
            $log->department_name,
            $log->Tanggal,
            $log->Jadwal_Masuk,
            $log->Jadwal_Pulang,
            $log->Jam_Masuk,
            $log->Jam_Pulang,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Add company header
                $sheet->insertNewRowBefore(1, 4);

                $sheet->setCellValue('A1', 'PT NIRWANA ALABARE GARMENT');
                $sheet->setCellValue('A2', 'LAPORAN ABSENSI KARYAWAN');
                $sheet->setCellValue('A3', $this->getDateRange());
                // $sheet->setCellValue('A4', 'STAFF / NON STAFF : SEMUA KARYAWAN');

                // Merge cells for header rows
                $sheet->mergeCells('A1:H1');
                $sheet->mergeCells('A2:H2');
                $sheet->mergeCells('A3:H3');
                $sheet->mergeCells('A4:H4');

                // Style the header
                $sheet->getStyle('A1:A4')->getFont()->setBold(true);
                $sheet->getStyle('A1')->getFont()->setSize(14);
                $sheet->getStyle('A2')->getFont()->setSize(12);
                $sheet->getStyle('A3')->getFont()->setSize(11);
                // $sheet->getStyle('A4')->getFont()->setSize(11);

                // Center align headers
                $sheet->getStyle('A1:A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }

    private function getDateRange()
    {
        $datetype = request()->date_type ?? 'daily';
        $month = request()->month ?? date('m');
        $year = request()->year ?? date('Y');

        // Use the variables to format the date range display
        if ($datetype == 'monthly') {
            $date = date('M Y', strtotime("$year-$month-01"));
            return "PERIODE ABSENSI : BULAN " . strtoupper($date);
        } elseif ($datetype == 'yearly') {
            return "PERIODE ABSENSI : TAHUN " . strtoupper($year);
        } else {
            $startDate = request()->start_date ?? date('Y-m-d');
            $endDate = request()->end_date ?? date('Y-m-d');
            $startFormatted = date('d M Y', strtotime($startDate));
            $endFormatted = date('d M Y', strtotime($endDate));
            return "PERIODE ABSENSI : " . strtoupper($startFormatted) . " S/D " . strtoupper($endFormatted);
        }
    }
}
