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

class ExportLaporanMutasiKaryawan implements FromView, WithEvents, ShouldAutoSize
{
    use Exportable;


    protected $from, $to;

    public function __construct($from, $to)
    {

        $this->from = $from;
        $this->to = $to;
        $this->rowCount = 0;
    }


    public function view(): View

    {
        $data = DB::select("
            SELECT
            line,
            ea.enroll_id,
            ea.employee_name nm_karyawan,
            ea.nik,
            tanggal_berjalan,
            COALESCE(b.line_asal, line) AS line_asal,
            ea.status_jabatan,
            absen_masuk_kerja,
            ea.sewing_nonsewing,
            status_absen,
            a.tgl_pindah,
            DATE_FORMAT(a.tgl_pindah, '%d-%m-%Y') tgl_pindah_fix,
            DATE_FORMAT(b.updated_at, '%d-%m-%Y %H:%i:%s') tgl_update_fix,
            CASE WHEN status_absen = 'M' THEN 'No' ELSE 'Yes' END AS status_scan
            from
            (
            select tgl_pindah,max(id) id from mut_karyawan_input a where tgl_pindah >= '$this->from' and tgl_pindah <= '$this->to'
            group by tgl_pindah,nik
            )a
            inner join mut_karyawan_input b on a.id = b.id
            inner join employee_atribut ea on b.enroll_id = ea.enroll_id
            left join
            (
            select tanggal_berjalan,enroll_id,nik,absen_masuk_kerja,status_absen from master_data_absen_kehadiran where tanggal_berjalan >= '$this->from' and tanggal_berjalan <= '$this->to'
            ) mk on b.enroll_id = mk.enroll_id and a.tgl_pindah = mk.tanggal_berjalan
                    order by a.tgl_pindah asc,cast(right(line,2) as UNSIGNED) asc, ea.employee_name asc
        ");
        $this->rowCount = count($data) + 3;


        return view('hris/mutasi-karyawan.export', [
            'data' => $data,
            'from' => $this->from,
            'to' => $this->to
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
            'A3:J' . $event->getConcernable()->rowCount,
            [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]
        );
    }
}
