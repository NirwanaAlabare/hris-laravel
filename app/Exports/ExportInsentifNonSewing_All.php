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

class ExportInsentifNonSewing_All implements FromView, WithEvents, ShouldAutoSize, WithColumnWidths, WithColumnFormatting
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
        SELECT DISTINCT
            DATE_FORMAT(a.tgl_lembur, '%d-%m-%Y') AS tgl_lembur_fix,
            b.enroll_id,
            e.nik,
            e.employee_name,
            e.status_staff,
            d.department_name AS department,
            b.uuid_koreksi_upah AS insentif,
            'Insentif' AS keterangan,
            a.no_form,
            a.ket,
            a.dept AS bagian,
            DATE_FORMAT(a.tgl_lembur, '%Y-%m-%d') AS tgl_lembur,
            DATE_FORMAT(jam_lembur_awal_rencana, '%H:%i') AS jam_lembur_awal_rencana,
            DATE_FORMAT(jam_lembur_akhir_rencana, '%H:%i') AS jam_lembur_akhir_rencana,
            b.status,
            DATE_FORMAT(SEC_TO_TIME(jam_lembur_istirahat * 60), '%H:%i') AS istirahat,
            DATE_FORMAT(SEC_TO_TIME((
                (TIMESTAMPDIFF(MINUTE, jam_lembur_awal_rencana, jam_lembur_akhir_rencana) - jam_lembur_istirahat) * 60
            )), '%H:%i') AS total_jam,
            DATE_FORMAT(m.absen_masuk_kerja, '%H:%i') AS absen_masuk_kerja,
            DATE_FORMAT(m.absen_pulang_kerja, '%H:%i') AS absen_pulang_kerja,
            DATE_FORMAT(TIMEDIFF(m.absen_pulang_kerja, m.absen_masuk_kerja), '%H:%i') AS realisasi_lembur
        FROM mut_karyawan_input_non_sewing_form_lembur a
        INNER JOIN mut_karyawan_input_non_sewing_form_lembur_det b
            ON a.no_form = b.no_form
        INNER JOIN employee_atribut e
            ON b.enroll_id = e.enroll_id
        INNER JOIN master_data_absen_kehadiran m
            ON b.enroll_id = m.enroll_id AND a.tgl_lembur = m.tanggal_berjalan
        INNER JOIN department_all d
            ON a.dept = d.sub_dept_name
        WHERE a.tgl_lembur BETWEEN '$this->from' AND '$this->to'
            AND m.tanggal_berjalan BETWEEN '$this->from' AND '$this->to'
            AND d.status = 'AKTIF'
            AND d.site_nirwana_id IN ('NAG','NAK','NAGD')
            AND b.uuid_koreksi_upah != ''
        ORDER BY a.tgl_lembur ASC, a.dept ASC, e.employee_name ASC
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
        $event->sheet->getStyle('A6:F6')
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
            'G' => 20,
        ];
    }
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
        ];
    }
}
