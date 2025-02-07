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

class ExportSpl_All implements FromView, WithEvents, ShouldAutoSize, WithColumnWidths
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
        date_FORMAT(jam_lembur_awal_rencana,'%H:%i')jam_lembur_awal_rencana,
        date_FORMAT(jam_lembur_akhir_rencana,'%H:%i')jam_lembur_akhir_rencana,
        b.status,
        date_FORMAT(SEC_TO_TIME(jam_lembur_istirahat*60),'%H:%i') istirahat,
        date_FORMAT(SEC_TO_TIME((((TIMESTAMPDIFF(MINUTE,jam_lembur_awal_rencana,jam_lembur_akhir_rencana)) - jam_lembur_istirahat) * 60)),'%H:%i') total_jam,
		date_FORMAT(m.absen_masuk_kerja,'%H:%i')absen_masuk_kerja,
	    date_FORMAT(m.absen_pulang_kerja,'%H:%i')absen_pulang_kerja,
		date_format(timediff(m.absen_pulang_kerja,m.absen_masuk_kerja),'%H:%i') realisasi_lembur
        from (select * from mut_karyawan_input_form_lembur where tgl_lembur >= '$this->from' and tgl_lembur <= '$this->to') a
        inner join mut_karyawan_input_form_lembur_det b on a.no_form = b.no_form
        left join
        (
            select no_form,group_concat(ket order by ket asc SEPARATOR ', ') ket from mut_karyawan_input_form_lembur_det_ket group by no_form
        ) k on a.no_form = k.no_form
        inner join employee_atribut e on b.enroll_id = e.enroll_id
	    inner join (select * from master_data_absen_kehadiran where tanggal_berjalan >= '$this->from' and tanggal_berjalan <= '$this->to') m on b.enroll_id = m.enroll_id and a.tgl_lembur = m.tanggal_berjalan
        order by tgl_lembur asc,line asc, employee_name asc
        ");


        $this->rowCount = count($data) + 4;


        return view('hris/mutasi-karyawan/form-lembur-sewing/export_spl_all', [
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
            'A4:P' . $event->getConcernable()->rowCount,
            [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]
        );

        $event->sheet->getStyle('A4:P4')
            ->getAlignment()
            ->setHorizontal('center')
            ->setVertical('center')
            ->setWrapText(true);

        // $event->sheet->getStyle('A9:M9')
        //     ->getAlignment()
        //     ->setHorizontal('center')
        //     ->setVertical('center');
    }

    public function columnWidths(): array
    {
        return [
            'E' => 8,
            'J' => 10,
            'K' => 10,
            'L' => 10,
            'M' => 10,
            'N' => 10,
            'O' => 10,
            'P' => 10
        ];
    }
}
