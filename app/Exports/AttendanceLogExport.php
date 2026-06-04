<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceLogExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
            'IP',
            'Timestamp',
      
        ];
    }

    public function map($log): array
    {
        return [
            $log->user_id,
            $log->ip,
            $log->timestamp,
    
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
