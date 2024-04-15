<?php

namespace App\Exports;

use App\Models\RekapPerhitunganLembur;
use App\Http\Controllers\Hris\RekapPerhitunganLemburController;

use App\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithProperties;

use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Illuminate\Support\Facades\DB;

use Auth;

class RekapPerhitunganLemburExport implements FromQuery, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell, WithTitle
{
    use Exportable;

    public function exportParams(array $daterange1)
    {
        $this->daterange1 = $daterange1;

        return $this;
    }

    public function query()
    {
        $dateRange = 'rekap_perhitungan_lembur.tanggal_berjalan between concat(substr("' . $this->daterange1[0] . '", 7, 4),"-",substr("' . $this->daterange1[0] . '", 1, 2),"-",substr("' . $this->daterange1[0] . '", 4, 2)) and concat(substr("' . $this->daterange1[1] . '", 7, 4),"-",substr("' . $this->daterange1[1] . '", 1, 2),"-",substr("' . $this->daterange1[1] . '", 4, 2))';

        $q =  RekapPerhitunganLembur::query()
                 ->selectRaw('
                    rekap_perhitungan_lembur.enroll_id, 
                    rekap_perhitungan_lembur.nomor_form_lembur, 
                    employee_atribut.employee_name, 
                    employee_atribut.status_staff, 
                    rekap_perhitungan_lembur.department_name, 
                    rekap_perhitungan_lembur.sub_dept_name, 
                    rekap_perhitungan_lembur.tanggal_berjalan, 
                    rekap_perhitungan_lembur.mulai_jam_kerja, 
                    rekap_perhitungan_lembur.akhir_jam_kerja, 
                    rekap_perhitungan_lembur.jumlah_jam_kerja, 
                    rekap_perhitungan_lembur.absen_masuk_kerja, 
                    rekap_perhitungan_lembur.absen_pulang_kerja, 
                    rekap_perhitungan_lembur.jam_efektif_kerja, 
                    rekap_perhitungan_lembur.mulai_jam_lembur, 
                    rekap_perhitungan_lembur.akhir_jam_lembur, 
                    rekap_perhitungan_lembur.nama_hari, 
                    rekap_perhitungan_lembur.kerjalibur, 
                    rekap_perhitungan_lembur.final_mulai_jam_lembur, 
                    rekap_perhitungan_lembur.final_selesai_jam_lembur, 
                    rekap_perhitungan_lembur.final_total_jam_lembur, 
                    rekap_perhitungan_lembur.final_jam_istirahat_lembur, 
                    rekap_perhitungan_lembur.final_total_menit_lembur, 
                    rekap_perhitungan_lembur.final_jam_lembur_roundown, 
                    rekap_perhitungan_lembur.final_menit_lembur_roundown, 
                    rekap_perhitungan_lembur.lembur_1, 
                    rekap_perhitungan_lembur.lembur_2, 
                    rekap_perhitungan_lembur.lembur_3, 
                    rekap_perhitungan_lembur.lembur_4, 
                    rekap_perhitungan_lembur.total_lembur_1234, 
                    rekap_perhitungan_lembur.salary, 
                    rekap_perhitungan_lembur.lembur1_rupiah, 
                    rekap_perhitungan_lembur.lembur2_rupiah, 
                    rekap_perhitungan_lembur.lembur3_rupiah, 
                    rekap_perhitungan_lembur.lembur4_rupiah, 
                    rekap_perhitungan_lembur.total_lembur_rupiah
                 ')        
                 ->whereRaw('
                    ' . $dateRange . '
                ')
                ->leftjoin('employee_atribut','employee_atribut.enroll_id','=','rekap_perhitungan_lembur.enroll_id')
                ->orderBy('employee_atribut.employee_name','asc')
                ->orderBy('rekap_perhitungan_lembur.tanggal_berjalan','asc')
                ->limit(1);
       
        return $q;
    }

    public function startCell(): string
    {
        return 'A7';
    }

    public function map($Data): array
    {
        $enroll_id = $Data->enroll_id;
        $nomor_form_lembur = $Data->nomor_form_lembur;
        $employee_name = $Data->employee_name;
        $status_staff = $Data->status_staff;
        $department_name = $Data->department_name;
        $sub_dept_name = $Data->sub_dept_name;
        $tanggal_berjalan = $Data->tanggal_berjalan;
        $mulai_jam_kerja = $Data->mulai_jam_kerja;
        $akhir_jam_kerja = $Data->akhir_jam_kerja;
        $jumlah_jam_kerja = $Data->jumlah_jam_kerja;
        $absen_in = $Data->absen_masuk_kerja;
        $absen_out = $Data->absen_pulang_kerja;
        $jam_efektif_kerja = $Data->jam_efektif_kerja;
        $mulai_jam_lembur = $Data->mulai_jam_lembur;
        $akhir_jam_lembur = $Data->akhir_jam_lembur;
        $nama_hari = $Data->nama_hari;

        $kerjalibur = "KERJA";
        switch ($Data->kode_hari) {
            case '5':
                $kerjalibur = "LIBUR";
                break;
            case '6':
                $kerjalibur = "LIBUR";
                break;
        }

        if( $Data->holiday_name <> "") {
            $kerjalibur = "LIBUR";
        }

        $final_mulai_jam_lembur = $Data->final_mulai_jam_lembur;
        $final_selesai_jam_lembur = $Data->final_selesai_jam_lembur;
        $final_total_jam_lembur = $Data->final_total_jam_lembur;
        $final_jam_istirahat_lembur = $Data->final_jam_istirahat_lembur;
        $final_total_menit_lembur = $Data->final_total_menit_lembur;
        $final_jam_lembur_roundown = $Data->final_jam_lembur_roundown;
        $final_menit_lembur_roundown = $Data->final_menit_lembur_roundown;
        $lembur_1 = $Data->lembur_1;
        $lembur_2 = $Data->lembur_2;
        $lembur_3 = $Data->lembur_3;
        $lembur_4 = $Data->lembur_4;
        $total_lembur_1234 = $Data->total_lembur_1234;
        $salary = $Data->salary;
        $lembur1_rupiah = $Data->lembur1_rupiah;
        $lembur2_rupiah = $Data->lembur2_rupiah;
        $lembur3_rupiah = $Data->lembur3_rupiah;
        $lembur4_rupiah = $Data->lembur4_rupiah;
        $total_lembur_rupiah = $Data->total_lembur_rupiah;

        return [
            $enroll_id,
            $nomor_form_lembur,
            $employee_name,
            $status_staff,
            $department_name,
            $sub_dept_name,
            $tanggal_berjalan,
            $mulai_jam_kerja,
            $akhir_jam_kerja,
            $jumlah_jam_kerja,
            $absen_in,
            $absen_out,
            $jam_efektif_kerja,
            $mulai_jam_lembur,
            $akhir_jam_lembur,
            $nama_hari,
            $kerjalibur,
            $final_mulai_jam_lembur,
            $final_selesai_jam_lembur,
            $final_total_jam_lembur,
            $final_jam_istirahat_lembur,
            $final_total_menit_lembur,
            $final_jam_lembur_roundown,
            $final_menit_lembur_roundown,
            $lembur_1,
            $lembur_2,
            $lembur_3,
            $lembur_4,
            $total_lembur_1234,
            $salary,
            $lembur1_rupiah,
            $lembur2_rupiah,
            $lembur3_rupiah,
            $lembur4_rupiah,
            $total_lembur_rupiah
        ];
    }

    public function title(): string
    {
        return 'REKAPPERHITUNGANLEMBUR';
    }

    public function registerEvents() : array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $sheet = $event->sheet;
                $sheet->setCellValue('A1', 'PT NIRWANA ALABARE GARMENT');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(18);
                $sheet->setCellValue('A2', 'Laporan Perhitungan Lembur Karyawan');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(16);
                $sheet->setCellValue('A3', 'Tanggal  : ' . $this->daterange1[0] . ' s/d ' . $this->daterange1[1]);
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A3:D3');
    
                $sheet->setCellValue('A5', 'Nomor Absen');
                $sheet->setCellValue('B5', 'Nomor Form SPL');
                $sheet->setCellValue('C5', 'Nama Karyawan');
                $sheet->setCellValue('D5', 'Staff/NonStaff');
                $sheet->setCellValue('E5', 'Department');
                $sheet->setCellValue('F5', 'Bagian');
                $sheet->setCellValue('G5', 'Tanggal Lembur');
                $sheet->setCellValue('H5', 'Jadwal Kerja');
                $sheet->setCellValue('H6', 'IN');
                $sheet->setCellValue('I6', 'OUT');
                $sheet->setCellValue('J6', 'WH');
                $sheet->setCellValue('K5', 'Actual Scan');
                $sheet->setCellValue('K6', 'IN');
                $sheet->setCellValue('L6', 'OUT');
                $sheet->setCellValue('M6', 'WH');
                $sheet->setCellValue('N5', 'Waktu Lembur');
                $sheet->setCellValue('N6', 'Dari');
                $sheet->setCellValue('O6', 'Sampai');
                $sheet->setCellValue('P5', 'Nama Hari');
                $sheet->setCellValue('Q5', 'Hari Kerja');
                $sheet->setCellValue('R5', 'Final Cek Lembur');
                $sheet->setCellValue('R6', 'Dari');
                $sheet->setCellValue('S6', 'Sampai');
                $sheet->setCellValue('T6', 'Total (Time)');
                $sheet->setCellValue('U6', 'Break');
                $sheet->setCellValue('V6', 'Total (Menit)');
                $sheet->setCellValue('W6', 'Jam');
                $sheet->setCellValue('X6', 'Menit');
                $sheet->setCellValue('Y5', 'Kategori Lembur');
                $sheet->setCellValue('Y6', 'L1');
                $sheet->setCellValue('Z6', 'L2');
                $sheet->setCellValue('AA6', 'L3');
                $sheet->setCellValue('AB6', 'L4');
                $sheet->setCellValue('AC6', 'Total');
                $sheet->setCellValue('AD5', 'Salary');
                $sheet->setCellValue('AE5', 'Jumlah Rupiah Lembur');
                $sheet->setCellValue('AE6', 'L1');
                $sheet->setCellValue('AF6', 'L2');
                $sheet->setCellValue('AG6', 'L3');
                $sheet->setCellValue('AH6', 'L4');
                $sheet->setCellValue('AI6', 'Total');

                $sheet->mergeCells('A5:A6');
                $sheet->mergeCells('B5:B6');
                $sheet->mergeCells('C5:C6');
                $sheet->mergeCells('D5:D6');
                $sheet->mergeCells('E5:E6');
                $sheet->mergeCells('F5:F6');
                $sheet->mergeCells('G5:G6');
                $sheet->mergeCells('P5:P6');
                $sheet->mergeCells('Q5:Q6');
                $sheet->mergeCells('AD5:AD6');

                $sheet->mergeCells('H5:J5');
                $sheet->mergeCells('K5:M5');
                $sheet->mergeCells('N5:O5');
                $sheet->mergeCells('R5:X5');
                $sheet->mergeCells('Y5:AC5');
                $sheet->mergeCells('AE5:AI5');

            },
        ];
    }

    public function properties(): array
    {
        return [
            'creator'        => 'PT NAG - HRIS',
            'lastModifiedBy' => 'HRIS',
            'title'          => 'Rekap Perhitungan Lembur Karyawan',
            'description'    => 'Rekap Perhitungan Lembur Karyawan',
            'subject'        => 'Rekap Perhitungan Lembur Karyawan',
            'keywords'       => 'perhitungan,lembur,hr,hris,hrm,daily,report,karyawan,data',
            'category'       => 'RekapPerhitunganLemburKaryawan',
            'manager'        => 'HRIS',
            'company'        => 'PT Nirwana Alabare Garment',
        ];
    }
}
