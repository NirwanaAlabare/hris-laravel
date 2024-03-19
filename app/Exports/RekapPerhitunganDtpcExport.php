<?php

namespace App\Exports;

use App\Models\RekapPerhitunganDTPC;
use App\Http\Controllers\Hris\RekapPerhitunganDtpcController;

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

class RekapPerhitunganDtpcExport implements FromQuery, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell, WithTitle
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
        $q =  RekapPerhitunganDTPC::query()
                ->selectRaw('
                    rekap_perhitungan_dtpc.uuid,
                    rekap_perhitungan_dtpc.tanggal_berjalan,
                    rekap_perhitungan_dtpc.enroll_id,
                    employee_atribut.nik,
                    employee_atribut.employee_name,
                    employee_atribut.status_staff,
                    employee_atribut.status_aktif,
                    rekap_perhitungan_dtpc.status_absen,
                    department_all.sub_dept_name,
                    NVL(rekap_perhitungan_dtpc.gaji_pokok, 0) gaji_pokok,
                    NVL(rekap_perhitungan_dtpc.gaji_menit, 0) gaji_menit,
                    NVL(SUM(rekap_perhitungan_dtpc.jumlah_menit_absen_dt), 0) jumlah_menit_absen_dt,
                    NVL(SUM(rekap_perhitungan_dtpc.jumlah_menit_absen_pc), 0) jumlah_menit_absen_pc,
                    NVL(SUM(rekap_perhitungan_dtpc.jumlah_menit_absen_dtpc), 0) jumlah_menit_absen_dtpc,
                    NVL(SUM(rekap_perhitungan_dtpc.potongan_dt_rupiah), 0) potongan_dt_rupiah,
                    NVL(SUM(rekap_perhitungan_dtpc.potongan_pc_rupiah), 0) potongan_pc_rupiah,
                    NVL(SUM(rekap_perhitungan_dtpc.potongan_dtpc_rupiah), 0) potongan_dtpc_rupiah
                ')
                ->whereRaw('
                    rekap_perhitungan_dtpc.tanggal_berjalan BETWEEN "' .$this->tanggalMulai . '" and "' . $this->tanggalSampai . '"                
                ')
                ->leftJoin('employee_atribut','employee_atribut.enroll_id','=','rekap_perhitungan_dtpc.enroll_id')
                ->leftJoin('department_all','department_all.sub_dept_id','=','employee_atribut.sub_dept_id')
                ->groupBy('rekap_perhitungan_dtpc.tanggal_berjalan')
                ->groupBy('rekap_perhitungan_dtpc.enroll_id')
                ->orderBy('employee_atribut.employee_name','asc')
                ->orderBy('rekap_perhitungan_dtpc.tanggal_berjalan','asc')
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
        $enroll_id = $Data->enroll_id;
        $nik = $Data->nik;
        $employee_name = $Data->employee_name;
        $status_staff = $Data->status_staff;
        $status_aktif = $Data->status_aktif;
        $status_absen = $Data->status_absen;
        $sub_dept_name = $Data->sub_dept_name;
        $gaji_pokok = $Data->gaji_pokok;
        $gaji_menit = $Data->gaji_menit;
        $jumlah_menit_absen_dt = $Data->jumlah_menit_absen_dt;
        $jumlah_menit_absen_pc = $Data->jumlah_menit_absen_pc;
        $jumlah_menit_absen_dtpc = $Data->jumlah_menit_absen_dtpc;
        $potongan_dt_rupiah = $Data->potongan_dt_rupiah;
        $potongan_pc_rupiah = $Data->potongan_pc_rupiah;
        $potongan_dtpc_rupiah = $Data->potongan_dtpc_rupiah;

        return [
            $tanggal_berjalan,
            $enroll_id,
            $nik,
            $employee_name,
            $status_staff,
            $status_aktif,
            $status_absen,
            $sub_dept_name,
            $gaji_pokok,
            $gaji_menit,
            $jumlah_menit_absen_dt,
            $jumlah_menit_absen_pc,
            $jumlah_menit_absen_dtpc,
            $potongan_dt_rupiah,
            $potongan_pc_rupiah,
            $potongan_dtpc_rupiah
        ];
    }

    public function title(): string
    {
        return 'REKAPPERHITUNGANDTPC';
    }

    public function registerEvents() : array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $sheet = $event->sheet;
                $sheet->setCellValue('A1', 'PT NIRWANA ALABARE GARMENT');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(18);
                $sheet->setCellValue('A2', 'Laporan Perhitungan DTPC Karyawan');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(16);
                $sheet->setCellValue('A3', 'Tanggal  : ' . $this->daterange1[0] . ' s/d ' . $this->daterange1[1]);
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A3:D3');
    
                $sheet->setCellValue('A5', 'Tanggal');
                $sheet->setCellValue('B5', 'Nomor Absen');
                $sheet->setCellValue('C5', 'NIK');
                $sheet->setCellValue('D5', 'Nama Karyawan');
                $sheet->setCellValue('E5', 'Status');
                $sheet->setCellValue('E5', 'Staff');
                $sheet->setCellValue('F5', 'Aktif');
                $sheet->setCellValue('G5', 'Status Absen');
                $sheet->setCellValue('H5', 'Bagian');
                $sheet->setCellValue('I5', 'Gaji');
                $sheet->setCellValue('I6', 'Pokok (Rp)');
                $sheet->setCellValue('J6', 'per Menit');
                $sheet->setCellValue('K5', 'DTPC (Menit)');
                $sheet->setCellValue('K6', 'DT');
                $sheet->setCellValue('L6', 'PC');
                $sheet->setCellValue('M6', 'Total');
                $sheet->setCellValue('N5', 'Potongan (Rp)');
                $sheet->setCellValue('N6', 'DT');
                $sheet->setCellValue('O6', 'PC');
                $sheet->setCellValue('P6', 'Total');

                $sheet->mergeCells('I5:J5');
                $sheet->mergeCells('K5:M5');
                $sheet->mergeCells('N5:P5');

                $sheet->mergeCells('A5:A6');
                $sheet->mergeCells('B5:B6');
                $sheet->mergeCells('C5:C6');
                $sheet->mergeCells('D5:D6');
                $sheet->mergeCells('E5:E6');
                $sheet->mergeCells('F5:F6');
                $sheet->mergeCells('G5:G6');
                $sheet->mergeCells('H5:H6');
            },
        ];
    }

    public function properties(): array
    {
        return [
            'creator'        => 'PT NAG - HRIS',
            'lastModifiedBy' => 'HRIS',
            'title'          => 'Rekap Perhitungan DTPC Karyawan',
            'description'    => 'Rekap Perhitungan DTPC Karyawan',
            'subject'        => 'Rekap Perhitungan DTPC Karyawan',
            'keywords'       => 'perhitungan,dtpc,hr,hris,hrm,daily,report,karyawan,data',
            'category'       => 'RekapPerhitunganDTPCKaryawan',
            'manager'        => 'HRIS',
            'company'        => 'PT Nirwana Alabare Garment',
        ];
    }
}
