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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
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

class ExportSpl_Import implements FromView, WithEvents, ShouldAutoSize, WithColumnWidths, WithColumnFormatting
{
    use Exportable;


    protected $id;

    public function __construct($id)
    {

        $this->id = $id;
        $this->rowCount = 0;
    }


    public function view(): View

    {

        // date_FORMAT(jam_lembur_awal_rencana,'%H:%i')jam_lembur_awal_rencana,
        // date_FORMAT(jam_lembur_akhir_rencana,'%H:%i')jam_lembur_akhir_rencana,
        $data = DB::select("
        select
        tgl_lembur,
        DATE_FORMAT(tgl_lembur, '%d-%m-%Y') tgl_lembur_fix,
        a.no_form,
        line,
        k.ket,
        b.enroll_id,
        e.nik,
        e.employee_name,
        e.status_jabatan,
        date_FORMAT(jam_lembur_awal_rencana,'%h:%i:%s')jam_lembur_awal_rencana,
        date_FORMAT(jam_lembur_akhir_rencana,'%h:%i:%s')jam_lembur_akhir_rencana,
        b.status,
        date_FORMAT(SEC_TO_TIME(jam_lembur_istirahat*60),'%h:%i:%s') istirahat,
        date_FORMAT(SEC_TO_TIME((((TIMESTAMPDIFF(MINUTE,jam_lembur_awal_rencana,jam_lembur_akhir_rencana)) - jam_lembur_istirahat) * 60)),'%h:%i:%s') total_jam
        from mut_karyawan_input_form_lembur a
        inner join mut_karyawan_input_form_lembur_det b on a.no_form = b.no_form
        inner join (
        select * from master_data_absen_kehadiran where tanggal_berjalan = (select tgl_lembur from mut_karyawan_input_form_lembur where id = '$this->id')
        ) m on b.enroll_id = m.enroll_id
        left join
        (
        select no_form,group_concat(ket order by ket asc SEPARATOR ', ') ket from mut_karyawan_input_form_lembur_det_ket group by no_form
        ) k on a.no_form = k.no_form
        inner join employee_atribut e on b.enroll_id = e.enroll_id
        where a.id = '$this->id'
        order by  employee_name asc
        ");


        $this->rowCount = count($data) + 4;


        return view('hris/mutasi-karyawan/form-lembur-sewing/export_spl_import', [
            'data' => $data,
            'id' => $this->id
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
            'A4:J' . $event->getConcernable()->rowCount,
            [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]
        );

        $event->sheet->getStyle('A4:J4')
            ->getAlignment()
            ->setHorizontal('center')
            ->setVertical('center')
            ->setWrapText(true);

        // $sheet = $event->getSheet();
        // $sheet->formatColumn('G', NumberFormat::FORMAT_DATE_TIME1);


        // $event->sheet->getStyle('A9:M9')
        //     ->getAlignment()
        //     ->setHorizontal('center')
        //     ->setVertical('center');
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'G' => NumberFormat::FORMAT_DATE_TIME9,
            'H' => NumberFormat::FORMAT_DATE_TIME9,
            'I' => NumberFormat::FORMAT_DATE_TIME9,
            'J' => NumberFormat::FORMAT_DATE_TIME9
        ];
    }

    public function columnWidths(): array
    {
        return [
            'F' => 13
        ];
    }
}
