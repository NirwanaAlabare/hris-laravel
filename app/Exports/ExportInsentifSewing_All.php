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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use DB;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});


class ExportInsentifSewing_All implements FromView, WithEvents, ShouldAutoSize, WithColumnWidths, WithColumnFormatting
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
        e.status_staff,
        e.status_jabatan,
        b.uuid_koreksi_upah as insentif,
        a.line as bagian,
        d.department_name as department,
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
        inner join (select * from department_all where status='AKTIF' and site_nirwana_id IN ('NAG','NAK','NAGD'))d on a.line=d.sub_dept_name
        where b.uuid_koreksi_upah!=''
        order by tgl_lembur asc,line asc, employee_name asc
        ");


        $this->rowCount = count($data) + 4;


        return view('hris/mutasi-karyawan/form-lembur-sewing/export_insentif_all', [
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
            'E' => 28,
            'H' => 20,
        ];
    }
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
        ];
    }
}
