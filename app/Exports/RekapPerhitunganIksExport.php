<?php

namespace App\Exports;

use App\Models\RekapPerhitunganIKS;
use App\Http\Controllers\Hris\RekapPerhitunganIksController;

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

class RekapPerhitunganIksExport implements FromQuery, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell, WithTitle
{
    use Exportable;

    public function exportParams(array $daterange1)
    {
        $this->daterange1 = $daterange1;
        $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
        $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));
        $this->tanggalMulai = $tanggalMulai;
        $this->tanggalSampai = $tanggalSampai;

        return $this;
    }

    public function query()
    {
        $q =  RekapPerhitunganIKS::query()
                ->selectRaw('
                    rekap_perhitungan_iks.uuid,
                    rekap_perhitungan_iks.nomor_form_perizinan,
                    rekap_perhitungan_iks.tanggal_berjalan,
                    rekap_perhitungan_iks.enroll_id,
                    employee_atribut.nik,
                    employee_atribut.employee_name,
                    employee_atribut.status_staff,
                    employee_atribut.status_aktif,
                    department_all.sub_dept_name,
                    SUBSTR(rekap_perhitungan_iks.time_mulai_ijin , 1, 5 ) time_mulai_ijin,
                    SUBSTR(rekap_perhitungan_iks.time_akhir_ijin , 1, 5 ) time_akhir_ijin,
                    SUBSTR(rekap_perhitungan_iks.jam_mulai_istirahat , 1, 5 ) jam_mulai_istirahat,
                    SUBSTR(rekap_perhitungan_iks.jam_selesai_istirahat , 1, 5 ) jam_selesai_istirahat,
                    NVL(rekap_perhitungan_iks.lama_istirahat_menit , 0 ) lama_istirahat_menit,
                    NVL(rekap_perhitungan_iks.lama_ijin_menit , 0 ) lama_ijin_menit,
                    SUBSTR(rekap_perhitungan_iks.lama_ijin_jam , 1, 5 ) lama_ijin_jam,
                    NVL(rekap_perhitungan_iks.gaji_pokok, 0) gaji_pokok,
                    NVL(rekap_perhitungan_iks.gaji_harian, 0) gaji_harian,
                    NVL(rekap_perhitungan_iks.gaji_menit, 0) gaji_menit,
                    NVL(rekap_perhitungan_iks.potongan_iks_rupiah, 0) potongan_dt_rupiah
                ')
                ->whereRaw('
                    rekap_perhitungan_iks.tanggal_berjalan BETWEEN "' .$this->tanggalMulai . '" and "' . $this->tanggalSampai . '"                
                ')
                ->leftJoin('employee_atribut','employee_atribut.enroll_id','=','rekap_perhitungan_iks.enroll_id')
                ->leftJoin('department_all','department_all.sub_dept_id','=','employee_atribut.sub_dept_id')
                ->groupBy('rekap_perhitungan_iks.tanggal_berjalan')
                ->groupBy('rekap_perhitungan_iks.enroll_id')
                ->orderBy('employee_atribut.employee_name','asc')
                ->orderBy('rekap_perhitungan_iks.tanggal_berjalan','asc')
                ->limit(1);
       
        return $q;
    }

    public function startCell(): string
    {
        return 'A7';
    }

    public function map($Data): array
    {
        $tanggal_berjalan = $Data->tanggal_berjalan;
        $nomor_form_perizinan = $Data->nomor_form_perizinan;
        $enroll_id = $Data->enroll_id;
        $nik = $Data->nik;
        $employee_name = $Data->employee_name;
        $status_staff = $Data->status_staff;
        $status_aktif = $Data->status_aktif;
        $sub_dept_name = $Data->sub_dept_name;
        $time_mulai_ijin = $Data->time_mulai_ijin;
        $time_akhir_ijin = $Data->time_akhir_ijin;
        $jam_mulai_istirahat = $Data->jam_mulai_istirahat;
        $jam_selesai_istirahat = $Data->jam_selesai_istirahat;
        $lama_istirahat_menit = $Data->lama_istirahat_menit;
        $lama_ijin_menit = $Data->lama_ijin_menit;
        $lama_ijin_jam = $Data->lama_ijin_jam;
        $gaji_pokok = $Data->gaji_pokok;
        $gaji_harian = $Data->gaji_harian;
        $gaji_menit = $Data->gaji_menit;
        $potongan_dt_rupiah = $Data->potongan_dt_rupiah;

        return [
            $tanggal_berjalan,
            $nomor_form_perizinan,
            $enroll_id,
            $nik,
            $employee_name,
            $status_staff,
            $status_aktif,
            $sub_dept_name,
            $time_mulai_ijin,
            $time_akhir_ijin,
            $jam_mulai_istirahat,
            $jam_selesai_istirahat,
            $lama_istirahat_menit,
            $lama_ijin_menit,
            $lama_ijin_jam,
            $gaji_pokok,
            $gaji_harian,
            $gaji_menit,
            $potongan_dt_rupiah
        ];
    }

    public function title(): string
    {
        return 'REKAPPERHITUNGANIKS';
    }

    public function registerEvents() : array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $sheet = $event->sheet;
                $sheet->setCellValue('A1', 'PT NIRWANA ALABARE GARMENT');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(18);
                $sheet->setCellValue('A2', 'Rekap Perhitungan IKS Karyawan');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(16);
                $sheet->setCellValue('A3', 'Tanggal  : ' . $this->daterange1[0] . ' s/d ' . $this->daterange1[1]);
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A3:D3');
    
                $sheet->setCellValue('A5', 'Tanggal');
                $sheet->setCellValue('B5', 'Nomor Perizinan');
                $sheet->setCellValue('C5', 'Nomor Absen');
                $sheet->setCellValue('D5', 'NIK');
                $sheet->setCellValue('E5', 'Nama Karyawan');
                $sheet->setCellValue('F5', 'Status');
                $sheet->setCellValue('F6', 'Staff');
                $sheet->setCellValue('G6', 'Aktif');
                $sheet->setCellValue('H5', 'Bagian');
                $sheet->setCellValue('I5', 'Waktu Ijin (Menit)');
                $sheet->setCellValue('I6', 'OUT');
                $sheet->setCellValue('J6', 'IN');
                $sheet->setCellValue('K5', 'Waktu Istirahat');
                $sheet->setCellValue('K6', 'Dari');
                $sheet->setCellValue('L6', 'Sampai');
                $sheet->setCellValue('M6', 'Lama Istirahat (Menit)');
                $sheet->setCellValue('N5', 'Lama Ijin');
                $sheet->setCellValue('N6', 'Jumlah Menit');
                $sheet->setCellValue('O6', 'Jumlah Jam');
                $sheet->setCellValue('P5', 'Gaji (Rupiah');
                $sheet->setCellValue('P6', 'Pokok');
                $sheet->setCellValue('Q6', 'per Hari');
                $sheet->setCellValue('R6', 'per Menit');
                $sheet->setCellValue('S5', 'Potongan IKS (Rp)');

                $sheet->mergeCells('F5:G5');
                $sheet->mergeCells('I5:J5');
                $sheet->mergeCells('K5:M5');
                $sheet->mergeCells('N5:O5');
                $sheet->mergeCells('P5:R5');

                $sheet->mergeCells('A5:A6');
                $sheet->mergeCells('B5:B6');
                $sheet->mergeCells('C5:C6');
                $sheet->mergeCells('D5:D6');
                $sheet->mergeCells('E5:E6');
                $sheet->mergeCells('H5:H6');
                $sheet->mergeCells('S5:S6');
            },
        ];
    }

    public function properties(): array
    {
        return [
            'creator'        => 'PT NAG - HRIS',
            'lastModifiedBy' => 'HRIS',
            'title'          => 'Rekap Perhitungan IKS Karyawan',
            'description'    => 'Rekap Perhitungan IKS Karyawan',
            'subject'        => 'Rekap Perhitungan IKS Karyawan',
            'keywords'       => 'perhitungan,iks,hr,hris,hrm,daily,report,karyawan,data',
            'category'       => 'RekapPerhitunganIKSKaryawan',
            'manager'        => 'HRIS',
            'company'        => 'PT Nirwana Alabare Garment',
        ];
    }
}
