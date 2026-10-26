<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PermintaanTenagaKerja implements FromCollection, WithHeadings, WithStyles, WithEvents, WithCustomStartCell
{
    protected $status;
    public function __construct($status)
    {
        $this->status = $status;
    }
    public function collection()
    {
        return DB::table('pengajuan_permintaan_tk')
            ->select(
                'pengajuan_permintaan_tk.no_permintaan',
                'employee_atribut.employee_name',
                DB::raw("DATE_FORMAT(pengajuan_permintaan_tk.tanggal_pengajuan, '%d-%m-%Y') as tanggal_pengajuan"),
                'department_all.department_name',
                'department_all2.sub_dept_name',
                DB::raw('(SELECT SUM(jumlah_kebutuhan) FROM sub_pengajuan_permintaan_tk WHERE sub_pengajuan_permintaan_tk.no_permintaan_id = pengajuan_permintaan_tk.no_permintaan) AS jumlah_kebutuhan'),
                DB::raw("(SELECT DATE_FORMAT(MIN(tanggal_kebutuhan), '%d-%m-%Y') FROM sub_pengajuan_permintaan_tk WHERE sub_pengajuan_permintaan_tk.no_permintaan_id = pengajuan_permintaan_tk.no_permintaan) AS tanggal_kebutuhan")
            )
            ->leftJoin('employee_atribut', 'pengajuan_permintaan_tk.diajukan_oleh_id', '=', 'employee_atribut.enroll_id')
            ->leftJoin('department_all', function($join) {
                $join->on(DB::raw('(SELECT department_kode FROM sub_pengajuan_permintaan_tk WHERE sub_pengajuan_permintaan_tk.no_permintaan_id = pengajuan_permintaan_tk.no_permintaan LIMIT 1)'), '=', 'department_all.department_id');
            })
            ->leftJoin('department_all as department_all2', function($join) {
                $join->on(DB::raw('(SELECT bagian_kode FROM sub_pengajuan_permintaan_tk WHERE sub_pengajuan_permintaan_tk.no_permintaan_id = pengajuan_permintaan_tk.no_permintaan LIMIT 1)'), '=', 'department_all2.sub_dept_id');
            })
            ->where('pengajuan_permintaan_tk.status_pengajuan',$this->status)
            ->distinct()
            ->orderBy('pengajuan_permintaan_tk.tanggal_pengajuan', 'desc')
            ->get();
    }

    // Tabel dimulai dari A3
    public function startCell(): string
    {
        return 'A3';
    }

    public function headings(): array
    {
        return [
            'Nomor Permintaan',
            'Diajukan',
            'Tanggal Pengajuan',
            'Kebutuhan Department',
            'Kebutuhan Bagian',
            'Jumlah Kebutuhan',
            'Tanggal Kebutuhan'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            3 => [ // Header ada di baris ke-3
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'color' => ['rgb' => '4472C4'],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                // Judul
                $event->sheet->mergeCells('A1:G1');
                $event->sheet->setCellValue('A1', 'LAPORAN PERMINTAAN TENAGA KERJA');

                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                    ],
                ]);

                // Border tabel
                $lastRow = $event->sheet->getHighestRow();

                $event->sheet->getStyle("A3:G{$lastRow}")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ],
                        ],
                    ]);

                // Auto width kolom
                foreach (range('A', 'G') as $column) {
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
