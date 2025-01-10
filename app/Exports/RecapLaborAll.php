<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;
use DB;
use App\Models\DailyLaborCost;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class RecapLaborAll implements WithTitle, WithEvents, FromCollection, WithMapping, WithColumnWidths, WithColumnFormatting, WithCustomStartCell
{
    use Exportable;
    protected $tanggal_awal,$tanggal_akhir,$staffnonstaff,$inEnrollId,$inStatusStaff;
    public function __construct($tanggal_awal,$tanggal_akhir,$staffnonstaff,$inEnrollId,$inStatusStaff)
    {
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->staffnonstaff = $staffnonstaff;
        $this->inEnrollId = $inEnrollId;
        $this->inStatusStaff = $inStatusStaff;
    }
    public function title(): string
    {
        return 'Daily';
    }

    public function registerEvents() : array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $border_top = [
                    'borders' => [
                        'top' => ['borderStyle' => 'thin', 'color' => ['argb' => '000000']],
                        'right' => ['borderStyle' => 'thin', 'color' => ['argb' => '000000']],
                        'left' => ['borderStyle' => 'thin', 'color' => ['argb' => '000000']],
                        'bottom' => ['borderStyle' => 'thin', 'color' => ['argb' => 'FFFFFF']],
                    ],
                ];
                $border_bottom = [
                    'borders' => [
                        'top' => ['borderStyle' => 'thin', 'color' => ['argb' => 'FFFFFF']],
                        'right' => ['borderStyle' => 'thin', 'color' => ['argb' => '000000']],
                        'left' => ['borderStyle' => 'thin', 'color' => ['argb' => '000000']],
                        'bottom' => ['borderStyle' => 'thin', 'color' => ['argb' => '000000']],
                    ],
                    'font' => [
                        'bold'  =>  true,
                        'size'  =>  11,
                        'name'  =>  'Calibri'
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                    ],
                ];
                $border_all = [
                    'borders' => [
                        'top' => ['borderStyle' => 'thin', 'color' => ['argb' => '000000']],
                        'right' => ['borderStyle' => 'thin', 'color' => ['argb' => '000000']],
                        'left' => ['borderStyle' => 'thin', 'color' => ['argb' => '000000']],
                        'bottom' => ['borderStyle' => 'thin', 'color' => ['argb' => '000000']],
                    ],
                    'font' => [
                        'bold'  =>  true,
                        'size'  =>  11,
                        'name'  =>  'Calibri'
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                    ],
                ];
                $sheet = $event->sheet;
                $sheet->setCellValue('A1', 'PT NIRWANA ALABARE GARMENT');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(18)->setBold(true);
                $sheet->setCellValue('A2', 'LAPORAN ABSENSI KARYAWAN');
                $sheet->getDelegate()->getStyle('A2')->getFont()->setSize(16)->setBold(true);
                $sheet->setCellValue('A3', 'TANGGAL ABSENSI : '.Carbon::parse($this->tanggal_awal)->translatedFormat('d F Y').' S/D '.Carbon::parse($this->tanggal_akhir)->translatedFormat('d F Y'));
                $sheet->getDelegate()->getStyle('A3')->getFont()->setSize(14)->setBold(true);
                $sheet->setCellValue('A4', 'STAFF / NON STAFF : '.$this->staffnonstaff);
                $sheet->getDelegate()->getStyle('A4')->getFont()->setSize(14)->setBold(true);
                $sheet->getDelegate()->getStyle('A7')->applyFromArray($border_top);
                $sheet->setCellValue('A8', 'Tanggal');
                $sheet->getDelegate()->getStyle('A8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('B7')->applyFromArray($border_top);
                $sheet->setCellValue('B8', 'Hari');
                $sheet->getDelegate()->getStyle('B8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('C7')->applyFromArray($border_top);
                $sheet->setCellValue('C8', 'NIP');
                $sheet->getDelegate()->getStyle('C8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('D7')->applyFromArray($border_top);
                $sheet->setCellValue('D8', 'No. Absen');
                $sheet->getDelegate()->getStyle('D8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('E7')->applyFromArray($border_top);
                $sheet->setCellValue('E8', 'Nama Karyawan');
                $sheet->getDelegate()->getStyle('E8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('F7')->applyFromArray($border_top);
                $sheet->setCellValue('F8', 'Staff / Non Staff');
                $sheet->getDelegate()->getStyle('F8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('G7')->applyFromArray($border_top);
                $sheet->setCellValue('G8', 'Jabatan');
                $sheet->getDelegate()->getStyle('G8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('H7')->applyFromArray($border_top);
                $sheet->setCellValue('H8', 'Bagian');
                $sheet->getDelegate()->getStyle('H8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('I7')->applyFromArray($border_top);
                $sheet->setCellValue('I8', 'Department');
                $sheet->getDelegate()->getStyle('I8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('J7')->applyFromArray($border_top);
                $sheet->setCellValue('J8', 'Keterangan Biaya');
                $sheet->getDelegate()->getStyle('J8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('K7')->applyFromArray($border_top);
                $sheet->setCellValue('K8', 'Kerja/Libur');
                $sheet->getDelegate()->getStyle('K8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('L7', 'JADWAL KERJA');
                $sheet->mergeCells('L7:O7');
                $sheet->getDelegate()->getStyle('L7:O7')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('L8', 'In');
                $sheet->getDelegate()->getStyle('L8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('M8', 'Out');
                $sheet->getDelegate()->getStyle('M8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('N8', 'Durasi Istirahat');
                $sheet->getDelegate()->getStyle('N8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('O8', 'Durasi Kerja');
                $sheet->getDelegate()->getStyle('O8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('P7', 'PRESENSI');
                $sheet->mergeCells('P7:R7');
                $sheet->getDelegate()->getStyle('P7:R7')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('P8', 'Sc In');
                $sheet->getDelegate()->getStyle('P8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('Q8', 'Sc Out');
                $sheet->getDelegate()->getStyle('Q8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('R8', 'Efektif Kerja');
                $sheet->getDelegate()->getStyle('R8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('S7', 'IJIN KELUAR SEMENTARA (IKS)');
                $sheet->mergeCells('S7:U7');
                $sheet->getDelegate()->getStyle('S7:U7')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('S8', 'IKS (Dari)');
                $sheet->getDelegate()->getStyle('S8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('T8', 'IKS (Sampai)');
                $sheet->getDelegate()->getStyle('T8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('U8', 'IKS (Total)');
                $sheet->getDelegate()->getStyle('U8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('V7', 'POTONGAN MENIT');
                $sheet->mergeCells('V7:X7');
                $sheet->getDelegate()->getStyle('V7:X7')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('V8', 'DT');
                $sheet->getDelegate()->getStyle('V8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('W8', 'PC');
                $sheet->getDelegate()->getStyle('W8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('X8', 'Potongan Menit Total');
                $sheet->getDelegate()->getStyle('X8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('Y7')->applyFromArray($border_top);
                $sheet->setCellValue('Y8', 'Kode Absen');
                $sheet->getDelegate()->getStyle('Y8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('Z7')->applyFromArray($border_top);
                $sheet->setCellValue('Z8', 'Status Payroll');
                $sheet->getDelegate()->getStyle('Z8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('AA7')->applyFromArray($border_top);
                $sheet->setCellValue('AA8', 'Keterangan Absen');
                $sheet->getDelegate()->getStyle('AA8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('AC7')->applyFromArray($border_top);
                $sheet->setCellValue('AC8', 'Keterangan Lembur');
                $sheet->getDelegate()->getStyle('AC8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('AD7', 'DATA LEMBUR (OVERTIME)');
                $sheet->mergeCells('AD7:AH7');
                $sheet->getDelegate()->getStyle('AD7:AH7')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('AD8', 'No. SPL');
                $sheet->getDelegate()->getStyle('AD8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('AE8', 'OT Mulai');
                $sheet->getDelegate()->getStyle('AE8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('AF8', 'OT Selesai');
                $sheet->getDelegate()->getStyle('AF8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('AG8', 'OT Istirahat');
                $sheet->getDelegate()->getStyle('AG8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('AH8', 'OT Total');
                $sheet->getDelegate()->getStyle('AH8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('AJ7', 'DATA LEMBUR VERIFIKASI');
                $sheet->mergeCells('AJ7:AM7');
                $sheet->getDelegate()->getStyle('AJ7:AM7')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('AJ8', 'Mulai Jam Lembur');
                $sheet->getDelegate()->getStyle('AJ8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('AK8', 'Akhir Jam Lembur');
                $sheet->getDelegate()->getStyle('AK8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('AL8', 'Jumlah Jam Istirahat');
                $sheet->getDelegate()->getStyle('AL8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('AM8', 'Jumlah Jam Lembur');
                $sheet->getDelegate()->getStyle('AM8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('AO7')->applyFromArray($border_top);
                $sheet->setCellValue('AO8', 'UPAH UMK');
                $sheet->getDelegate()->getStyle('AO8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('AP7')->applyFromArray($border_top);
                $sheet->setCellValue('AP8', 'Upah / Hari');
                $sheet->getDelegate()->getStyle('AP8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('AQ7')->applyFromArray($border_top);
                $sheet->setCellValue('AQ8', 'Upah / Menit');
                $sheet->getDelegate()->getStyle('AQ8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('AS7')->applyFromArray($border_top);
                $sheet->setCellValue('AS8', 'IBY');
                $sheet->getDelegate()->getStyle('AS8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('AT7')->applyFromArray($border_top);
                $sheet->setCellValue('AT8', 'ITB');
                $sheet->getDelegate()->getStyle('AT8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('AU7')->applyFromArray($border_top);
                $sheet->setCellValue('AU8', 'M');
                $sheet->getDelegate()->getStyle('AU8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('AV7')->applyFromArray($border_top);
                $sheet->setCellValue('AV8', 'DT');
                $sheet->getDelegate()->getStyle('AV8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('AW7')->applyFromArray($border_top);
                $sheet->setCellValue('AW8', 'PC');
                $sheet->getDelegate()->getStyle('AW8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('AX7')->applyFromArray($border_top);
                $sheet->setCellValue('AX8', 'DTPC');
                $sheet->getDelegate()->getStyle('AX8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('AY7')->applyFromArray($border_top);
                $sheet->setCellValue('AY8', 'LBY');
                $sheet->getDelegate()->getStyle('AY8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('AZ7')->applyFromArray($border_top);
                $sheet->setCellValue('AZ8', 'LSM');
                $sheet->getDelegate()->getStyle('AZ8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('BA7')->applyFromArray($border_top);
                $sheet->setCellValue('BA8', 'R');
                $sheet->getDelegate()->getStyle('BA8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('BB7')->applyFromArray($border_top);
                $sheet->setCellValue('BB8', 'OK');
                $sheet->getDelegate()->getStyle('BB8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('BC7')->applyFromArray($border_top);
                $sheet->setCellValue('BC8', 'Hari Kerja');
                $sheet->getDelegate()->getStyle('BC8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('BD7')->applyFromArray($border_top);
                $sheet->setCellValue('BD8', 'Pot. Hari Kerja');
                $sheet->getDelegate()->getStyle('BD8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('BE7')->applyFromArray($border_top);
                $sheet->setCellValue('BE8', 'Total Absensi');
                $sheet->getDelegate()->getStyle('BE8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('BF7')->applyFromArray($border_top);
                $sheet->setCellValue('BF8', 'L1');
                $sheet->getDelegate()->getStyle('BF8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('BG7')->applyFromArray($border_top);
                $sheet->setCellValue('BG8', 'L2');
                $sheet->getDelegate()->getStyle('BG8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('BH7')->applyFromArray($border_top);
                $sheet->setCellValue('BH8', 'L3');
                $sheet->getDelegate()->getStyle('BH8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('BI7')->applyFromArray($border_top);
                $sheet->setCellValue('BI8', 'L4');
                $sheet->getDelegate()->getStyle('BI8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('BJ7')->applyFromArray($border_top);
                $sheet->setCellValue('BJ8', 'Datang Terlambat');
                $sheet->getDelegate()->getStyle('BJ8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('BK7')->applyFromArray($border_top);
                $sheet->setCellValue('BK8', 'Pulang Cepat');
                $sheet->getDelegate()->getStyle('BK8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('BL7')->applyFromArray($border_top);
                $sheet->setCellValue('BL8', 'Izin Keluar Sementara');
                $sheet->getDelegate()->getStyle('BL8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('BM7')->applyFromArray($border_top);
                $sheet->setCellValue('BM8', 'Sisa Cuti Tahunan');
                $sheet->getDelegate()->getStyle('BM8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('BO7')->applyFromArray($border_top);
                $sheet->setCellValue('BO8', 'Kode Grade');
                $sheet->getDelegate()->getStyle('BO8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);

                $sheet->getDelegate()->getStyle('BP7')->applyFromArray($border_top);
                $sheet->setCellValue('BP8', 'Upah / Hari');
                $sheet->getDelegate()->getStyle('BP8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);

                
                $sheet->getDelegate()->getStyle('BQ7')->applyFromArray($border_top);
                $sheet->setCellValue('BQ8', 'Seniority Allowance');
                $sheet->getDelegate()->getStyle('BQ8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('BR7')->applyFromArray($border_top);
                $sheet->setCellValue('BR8', 'Insentif (Kehadiran)');
                $sheet->getDelegate()->getStyle('BR8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('BS7')->applyFromArray($border_top);
                $sheet->setCellValue('BS8', 'Insentif (Jabatan)');
                $sheet->getDelegate()->getStyle('BS8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('BT7')->applyFromArray($border_top);
                $sheet->setCellValue('BT8', 'RP Lembur 1');
                $sheet->getDelegate()->getStyle('BT8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('BU7')->applyFromArray($border_top);
                $sheet->setCellValue('BU8', 'RP Lembur 2');
                $sheet->getDelegate()->getStyle('BU8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);


                $sheet->getDelegate()->getStyle('BV7')->applyFromArray($border_top);
                $sheet->setCellValue('BV8', 'RP Lembur 3');
                $sheet->getDelegate()->getStyle('BV8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('BW7')->applyFromArray($border_top);
                $sheet->setCellValue('BW8', 'RP Lembur 4');
                $sheet->getDelegate()->getStyle('BW8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('BX7', 'Lain - Lain (Koreksi +-)');
                $sheet->mergeCells('BX7:CD7');
                $sheet->getDelegate()->getStyle('BX7:CD7')->applyFromArray($border_all)->getAlignment()->setWrapText(true);


                $sheet->setCellValue('BX8', '+ Upah');
                $sheet->getDelegate()->getStyle('BX8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('BY8', '+ Lembur');
                $sheet->getDelegate()->getStyle('BY8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('BZ8', '+ Insentif');
                $sheet->getDelegate()->getStyle('BZ8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('CA8', '- Upah');
                $sheet->getDelegate()->getStyle('CA8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('CB8', '- Lembur');
                $sheet->getDelegate()->getStyle('CB8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('CC8', '- Insentif');
                $sheet->getDelegate()->getStyle('CC8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('CD8', '- Piutang');
                $sheet->getDelegate()->getStyle('CD8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('CE7')->applyFromArray($border_top);
                $sheet->setCellValue('CE8', 'RP Cuti Tahunan');
                $sheet->getDelegate()->getStyle('CE8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('CF7')->applyFromArray($border_top);
                $sheet->setCellValue('CF8', 'RP Potongan Hari Kerja');
                $sheet->getDelegate()->getStyle('CF8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('CG7')->applyFromArray($border_top);
                $sheet->setCellValue('CG8', 'RP Pot. Jam');
                $sheet->getDelegate()->getStyle('CG8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('CH7')->applyFromArray($border_top);
                $sheet->setCellValue('CH8', 'Bruto');
                $sheet->getDelegate()->getStyle('CH8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('CI7')->applyFromArray($border_top);
                $sheet->setCellValue('CI8', 'PPH');
                $sheet->getDelegate()->getStyle('CI8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('CJ7')->applyFromArray($border_top);
                $sheet->setCellValue('CJ8', 'Netto');
                $sheet->getDelegate()->getStyle('CJ8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('CK7', 'Potongan Karyawan');
                $sheet->mergeCells('CK7:CL7');
                $sheet->getDelegate()->getStyle('CK7:CL7')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('CK8', 'Bpjamsostek');
                $sheet->getDelegate()->getStyle('CK8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('CL8', 'BPJS Kesehatan');
                $sheet->getDelegate()->getStyle('CL8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('CM7')->applyFromArray($border_top);
                $sheet->setCellValue('CM8', 'Serikat');
                $sheet->getDelegate()->getStyle('CM8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('CN7')->applyFromArray($border_top);
                $sheet->setCellValue('CN8', 'Koperasi');
                $sheet->getDelegate()->getStyle('CN8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('CO7')->applyFromArray($border_top);
                $sheet->setCellValue('CO8', 'Total Potongan');
                $sheet->getDelegate()->getStyle('CO8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('CP7')->applyFromArray($border_top);
                $sheet->setCellValue('CP8', 'Pembulatan');
                $sheet->getDelegate()->getStyle('CP8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('CQ7')->applyFromArray($border_top);
                $sheet->setCellValue('CQ8', 'Jumlah');
                $sheet->getDelegate()->getStyle('CQ8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('CS7', 'Potongan Perusahaan');
                $sheet->mergeCells('CS7:CT7');
                $sheet->getDelegate()->getStyle('CS7:CT7')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('CS8', 'PP Bpjamsostek');
                $sheet->getDelegate()->getStyle('CS8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('CT8', 'PP BPJS Kesehatan');
                $sheet->getDelegate()->getStyle('CT8')->applyFromArray($border_all)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('CU7')->applyFromArray($border_top);
                $sheet->setCellValue('CU8', 'Kompensasi');
                $sheet->getDelegate()->getStyle('CU8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('CV7')->applyFromArray($border_top);
                $sheet->setCellValue('CV8', 'THR');
                $sheet->getDelegate()->getStyle('CV8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('CW7')->applyFromArray($border_top);
                $sheet->setCellValue('CW8', 'Makan Lembur');
                $sheet->getDelegate()->getStyle('CW8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
                $sheet->getDelegate()->getStyle('CY7')->applyFromArray($border_top);
                $sheet->setCellValue('CY8', 'Total Pembayaran Aktual');
                $sheet->getDelegate()->getStyle('CY8')->applyFromArray($border_bottom)->getAlignment()->setWrapText(true);
            },
        ];
    }
    public function collection()
    {
        $query=DB::select("select a.tanggal_berjalan,a.kode_hari,a.nama_hari,b.nik,a.enroll_id,b.employee_name,if(emp_hist.status_staff is null,b.status_staff,emp_hist.status_staff) status_staff,if(emp_hist.status_jabatan is null,b.status_jabatan,emp_hist.status_jabatan) status_jabatan,if(emp_hist.sub_dept_name is null,b.sub_dept_name,emp_hist.sub_dept_name) sub_dept_name,if(emp_hist.department_name is null,b.department_name,emp_hist.department_name) department_name,c.group_department,a.mulai_jam_kerja,a.akhir_jam_kerja,a.absen_masuk_kerja,a.absen_pulang_kerja,a.permits_dari_pukul,a.permits_sampai_pukul,a.total_menit_permits,a.jumlah_menit_absen_dt,a.jumlah_menit_absen_pc,a.jumlah_menit_absen_dtpc,a.status_absen,a.absen_alasan,h.kode_ijin_payroll,d.catatan,d.nomor_form_lembur,d.mulai_jam_lembur,d.akhir_jam_lembur,d.jumlah_jam_istirahat,d.jumlah_jam_lembur,e.final_mulai_jam_lembur,e.final_selesai_jam_lembur,e.final_jam_istirahat_lembur,e.final_total_jam_lembur,bpjs.dasar_pot_bpjs_rupiah upah_umk,c.gaji_perhari,c.gaji_permenit,c.iby,c.itb,c.m,c.dt,c.pc,c.dtpc,c.lby,c.lsm,c.r,c.ok,c.hari_kerja,c.pot_hari_kerja,c.total_absen,e.lembur_1,e.lembur_2,e.lembur_3,e.lembur_4,b.kode_grade,i.salary_bulanan,c.seniority_allowance,c.insentif_kehadiran,c.insentif_jabatan,e.lembur1_rupiah,e.lembur2_rupiah,e.lembur3_rupiah,e.lembur4_rupiah,if(f.jenis_koreksi=1,f.jumlah_rp_potongan,0) koreksi_upah,if(f.jenis_koreksi=3,f.jumlah_rp_potongan,0) koreksi_lembur,if(f.jenis_koreksi=4,f.jumlah_rp_potongan,0) koreksi_insentif,if(g.jenis_potongan=7,g.jumlah_rp_potongan,0) potongan_upah,if(g.jenis_potongan=8,g.jumlah_rp_potongan,0) potongan_lembur,if(g.jenis_potongan=5,g.jumlah_rp_potongan,0) potongan_insentif,if(g.jenis_potongan=4,g.jumlah_rp_potongan,0) potongan_piutang,c.rp_pot_hari_kerja,c.rp_pot_jam,c.bruto,c.bpjs_tk,c.bpjs_ks,c.total_potongan,c.pembulatan,c.jumlah,c.bpjs_tk_company,c.bpjs_ks_company,c.kompensasi,c.thr,c.konsumsi,c.total_pembayaran from master_data_absen_kehadiran a inner join employee_atribut b on a.enroll_id=b.enroll_id left join daily_labor_costs c on a.enroll_id=c.enroll_id and a.tanggal_berjalan=c.tanggal_berjalan left join data_lembur d on a.enroll_id=d.enroll_id and a.tanggal_berjalan=d.tanggal_berjalan left join rekap_perhitungan_lembur e on a.enroll_id=e.enroll_id and a.tanggal_berjalan=e.tanggal_berjalan left join data_koreksi_upah f on a.enroll_id=f.enroll_id and a.tanggal_berjalan=f.tanggal_koreksi left join data_koreksi_potongan g on a.enroll_id=g.enroll_id and a.tanggal_berjalan=g.tanggal_koreksi left join ref_absen_ijin h on a.status_absen=h.kode_absen_ijin left join (select*from grading_salary where periode_umk='2024-01')i on b.kode_grade=i.kode_grade inner join dasar_pot_bpjs bpjs on REGEXP_SUBSTR(bpjs.kode_dasar_pot_bpjs, '[0-9]+')=substring(a.tanggal_berjalan,1,4) left join employee_atribut_histories emp_hist on a.enroll_id=emp_hist.enroll_id and a.tanggal_berjalan between SUBSTRING(emp_hist.periode_payroll,1,10) and SUBSTRING(emp_hist.periode_payroll,16,10) where a.tanggal_berjalan>='".$this->tanggal_awal."' and a.tanggal_berjalan<='".$this->tanggal_akhir."'".$this->inEnrollId.$this->inStatusStaff);
        return collect($query);
    }
    public function startCell(): string
    {
        return 'A9';
    }

    public function map($Data): array
    {
        $time_mulai_kerja='';
        if($Data->mulai_jam_kerja!=null || $Data->mulai_jam_kerja!=''){
            $mulai_jam_kerja = $Data->mulai_jam_kerja;
            $timestamp = new \DateTime($mulai_jam_kerja);
            $excelTimestamp = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($timestamp);
            $excelDate = floor($excelTimestamp);
            $time_mulai_kerja = $excelTimestamp - $excelDate;
        }
        $time_akhir_kerja='';
        if($Data->akhir_jam_kerja!=null || $Data->akhir_jam_kerja!=''){
            $akhir_jam_kerja = $Data->akhir_jam_kerja;
            $timestamp2 = new \DateTime($akhir_jam_kerja);
            $excelTimestamp2 = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($timestamp2);
            $excelDate2 = floor($excelTimestamp2);
            $time_akhir_kerja = $excelTimestamp2 - $excelDate2;
        }
        $time_absen_masuk='';
        if($Data->absen_masuk_kerja!=null || $Data->absen_masuk_kerja!=''){
            $absen_masuk = $Data->absen_masuk_kerja;
            $timestamp4 = new \DateTime($absen_masuk);
            $excelTimestamp4 = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($timestamp4);
            $excelDate4 = floor($excelTimestamp4);
            $time_absen_masuk = $excelTimestamp4 - $excelDate4;
        }
        $time_absen_pulang='';
        if($Data->absen_pulang_kerja!=null || $Data->absen_pulang_kerja!=''){
            $absen_pulang = $Data->absen_pulang_kerja;
            $timestamp5 = new \DateTime($absen_pulang);
            $excelTimestamp5 = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($timestamp5);
            $excelDate5 = floor($excelTimestamp5);
            $time_absen_pulang = $excelTimestamp5 - $excelDate5;
        }
        $permits_dari_pukul='';
        if($Data->permits_dari_pukul!=null || $Data->permits_dari_pukul!=''){
            $permit_dari = $Data->permits_dari_pukul;
            $timestamp6 = new \DateTime($permit_dari);
            $excelTimestamp6 = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($timestamp6);
            $excelDate6 = floor($excelTimestamp6);
            $permits_dari_pukul = $excelTimestamp6 - $excelDate6;
        }
        $permits_sampai_pukul='';
        if($Data->permits_sampai_pukul!=null || $Data->permits_sampai_pukul!=''){
            $permit_sampai = $Data->permits_sampai_pukul;
            $timestamp7 = new \DateTime($permit_sampai);
            $excelTimestamp7 = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($timestamp7);
            $excelDate7 = floor($excelTimestamp7);
            $permits_sampai_pukul = $excelTimestamp7 - $excelDate7;
        }
        $mulai_jam_lembur='';
        if($Data->mulai_jam_lembur!=null || $Data->mulai_jam_lembur!=''){
            $mulai_jam_lmbr = substr($Data->mulai_jam_lembur,11,8);
            $timestamp8 = new \DateTime($mulai_jam_lmbr);
            $excelTimestamp8 = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($timestamp8);
            $excelDate8 = floor($excelTimestamp8);
            $mulai_jam_lembur = $excelTimestamp8 - $excelDate8;
        }
        $akhir_jam_lembur='';
        if($Data->akhir_jam_lembur!=null || $Data->akhir_jam_lembur!=''){
            $akhir_jam_lmbr = substr($Data->akhir_jam_lembur,11,8);
            $timestamp9 = new \DateTime($akhir_jam_lmbr);
            $excelTimestamp9 = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($timestamp9);
            $excelDate9 = floor($excelTimestamp9);
            $akhir_jam_lembur = $excelTimestamp9 - $excelDate9;
        }
        $hour_istirahat_lembur='';
        if($Data->jumlah_jam_istirahat!=null || $Data->jumlah_jam_istirahat!='' || $Data->jumlah_jam_istirahat!='0,0'){    
            $minute_jam_istirahat_lembur=$Data->jumlah_jam_istirahat*60;
            $hour_jam_istirahat_lembur = sprintf("%02d", intdiv($minute_jam_istirahat_lembur, 60)).':'. ($minute_jam_istirahat_lembur % 60);
            $timestamp10 = new \DateTime($hour_jam_istirahat_lembur);
            $excelTimestamp10 = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($timestamp10);
            $excelDate10 = floor($excelTimestamp10);
            $hour_istirahat_lembur = $excelTimestamp10 - $excelDate10;
        }
        $final_mulai_lembur='';
        if($Data->final_mulai_jam_lembur!=null || $Data->final_mulai_jam_lembur!=''){
            $mulai_jam_lembur_final=$Data->final_mulai_jam_lembur;
            $timestamp11 = new \DateTime($mulai_jam_lembur_final);
            $excelTimestamp11 = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($timestamp11);
            $excelDate11 = floor($excelTimestamp11);
            $final_mulai_lembur = $excelTimestamp11 - $excelDate11;
        }
        $final_selesai_lembur='';
        if($Data->final_selesai_jam_lembur!=null || $Data->final_selesai_jam_lembur!=''){
            $selesai_jam_lembur_final=$Data->final_selesai_jam_lembur;
            $timestamp12 = new \DateTime($selesai_jam_lembur_final);
            $excelTimestamp12 = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($timestamp12);
            $excelDate12 = floor($excelTimestamp12);
            $final_selesai_lembur = $excelTimestamp12 - $excelDate12;
        }
        $hour_final_istirahat_lembur='';
        if($Data->final_jam_istirahat_lembur!=null || $Data->final_jam_istirahat_lembur!='' || $Data->final_jam_istirahat_lembur!='0,0'){    
            $minute_final_jam_istirahat_lembur=$Data->final_jam_istirahat_lembur*60;
            $hour_final_jam_istirahat_lembur = sprintf("%02d", intdiv($minute_final_jam_istirahat_lembur, 60)).':'. ($minute_final_jam_istirahat_lembur % 60);
            $timestamp13 = new \DateTime($hour_final_jam_istirahat_lembur);
            $excelTimestamp13 = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($timestamp13);
            $excelDate13 = floor($excelTimestamp13);
            $hour_final_istirahat_lembur = $excelTimestamp13 - $excelDate13;
        }
        $hour_final__total_jam_lembur='';
        if($Data->final_total_jam_lembur!=null || $Data->final_total_jam_lembur!=''){
            $hour_total_jam_lembur=$Data->final_total_jam_lembur;
            $timestamp14 = new \DateTime($hour_total_jam_lembur);
            $excelTimestamp14 = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($timestamp14);
            $excelDate14 = floor($excelTimestamp14);
            $hour_final__total_jam_lembur = $excelTimestamp14 - $excelDate14;
        }
        $jumlah_menit_istirahat = '01:00';
        $jumlah_menit_istirahat_int=60;
        if(($Data->mulai_jam_kerja == null) || ($Data->status_absen == "LN" || $Data->status_absen == "CG" || $Data->status_absen == "CM" || $Data->status_absen == "CT" ||$Data->status_absen == "L") || (($Data->status_absen == "LP" ) && ($Data->absen_masuk_kerja==null) && ($Data->absen_pulang_kerja==null))) {
            $kerjalibur = "LIBUR";
        } else if(($Data->kode_hari=='6' || $Data->kode_hari=='5' ) && ($Data->mulai_jam_kerja!=null)){
            $kerjalibur = "KERJA";
        } else {
            if(($Data->absen_masuk_kerja <> null) || ($Data->absen_masuk_kerja <> "") || ($Data->absen_pulang_kerja <> null) || ($Data->absen_pulang_kerja <> "")) {
                $kerjalibur = "KERJA";
                switch ($Data->kode_hari) {
                    case '5':
                        $kerjalibur = "LIBUR";
                        $jumlah_menit_istirahat = '00:30';
                        $jumlah_menit_istirahat_int=30;
                        break;
                    case '6':
                        $kerjalibur = "LIBUR";
                        $jumlah_menit_istirahat = '00:30';
                        $jumlah_menit_istirahat_int=30;
                        break;
                }
            }else{
                $kerjalibur='LIBUR';
            }
        }
        $time_menit_istirahat='';
        if($jumlah_menit_istirahat!=null || $jumlah_menit_istirahat!=''){
            $timestamp3 = new \DateTime($jumlah_menit_istirahat);
            $excelTimestamp3 = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($timestamp3);
            $excelDate3 = floor($excelTimestamp3);
            $time_menit_istirahat = $excelTimestamp3 - $excelDate3;
        }
        $mulai_jam_kerja=strtotime($Data->mulai_jam_kerja);
        $akhir_jam_kerja=strtotime($Data->akhir_jam_kerja);
        $jumlah_detik_istirahat=strtotime($jumlah_menit_istirahat);
        $time = explode(":",$jumlah_menit_istirahat);
        $minutes = intval($time[0])*60 + intval($time[1]);
        $total_jam_kerja=(($akhir_jam_kerja-$mulai_jam_kerja)/60)-$minutes;
        $kode_ijin_payroll=$Data->kode_ijin_payroll;
        if($kode_ijin_payroll==null){
            if($Data->mulai_jam_kerja!=null && $Data->akhir_jam_kerja!=null){
                if($Data->absen_masuk_kerja!=null && $Data->absen_pulang_kerja!=null && $Data->status_absen!='R'){
                    if($Data->jumlah_menit_absen_dt!=0 && $Data->jumlah_menit_absen_pc==0){
                        $kode_ijin_payroll='DT';
                    }else if($Data->jumlah_menit_absen_dt==0 && $Data->jumlah_menit_absen_pc!=0){
                        $kode_ijin_payroll='PC';
                    }else if($Data->jumlah_menit_absen_dt!=0 && $Data->jumlah_menit_absen_pc!=0){
                        $kode_ijin_payroll='DTPC';
                    }else{
                        $kode_ijin_payroll='OK';
                    }
                }else if($Data->absen_masuk_kerja!=null && $Data->absen_pulang_kerja!=null && $Data->status_absen=='R'){
                    $kode_ijin_payroll='R';
                }else if($Data->absen_masuk_kerja!=null && $Data->absen_pulang_kerja==null && $Data->status_absen!='R'){
                    $kode_ijin_payroll='M';
                }else if($Data->absen_masuk_kerja==null && $Data->absen_pulang_kerja!=null && $Data->status_absen!='R'){
                    $kode_ijin_payroll='M';
                }else if($Data->absen_masuk_kerja!=null && $Data->absen_pulang_kerja==null && $Data->status_absen=='R'){
                    $kode_ijin_payroll='R';
                }else if($Data->absen_masuk_kerja==null && $Data->absen_pulang_kerja==null && $Data->status_absen!='R'){
                    $kode_ijin_payroll='M';
                }else if($Data->absen_masuk_kerja==null && $Data->absen_pulang_kerja==null && $Data->status_absen=='R'){
                    $kode_ijin_payroll='R';
                }
            }else{
                if($Data->status_absen=='LN'){
                    $kode_ijin_payroll='LBY';
                }else if($Data->status_absen=='R'){
                    $kode_ijin_payroll='R';
                }else if($Data->status_absen==null){
                    $kode_ijin_payroll='LSM';
                }else if($Data->status_absen=='IKS'){
                    $kode_ijin_payroll='OK';
                }else if($Data->status_absen=='TL'){
                    $kode_ijin_payroll='LSM';
                }
            }
        }else if($kode_ijin_payroll=='ITB'){
            if($Data->status_absen=='M'){
                $kode_ijin_payroll='M';
            }else if($Data->status_absen=='IKS'){
                if($Data->absen_masuk_kerja!=null && $Data->absen_pulang_kerja!=null){
                    if($Data->jumlah_menit_absen_dt!=0 && $Data->jumlah_menit_absen_pc==0){
                        $kode_ijin_payroll='DT';
                    }else if($Data->jumlah_menit_absen_dt==0 && $Data->jumlah_menit_absen_pc!=0){
                        $kode_ijin_payroll='PC';
                    }else if($Data->jumlah_menit_absen_dt!=0 && $Data->jumlah_menit_absen_pc!=0){
                        $kode_ijin_payroll='DTPC';
                    }else{
                        $kode_ijin_payroll='OK';
                    }
                }else{
                    $kode_ijin_payroll='OK';
                }
            }else if($Data->status_absen=='LP'){
                $kode_ijin_payroll='LP';
            }else if($Data->status_absen=='S'){
                $kode_ijin_payroll='S';
            }
        }else if($kode_ijin_payroll=='IBY'){
            if($Data->status_absen=='DL'){
                $kode_ijin_payroll='DL';
            }
        }
        $absen_masuk_kerja=strtotime($Data->absen_masuk_kerja);
        $absen_pulang_kerja=strtotime($Data->absen_pulang_kerja);
        if($absen_masuk_kerja<$absen_pulang_kerja && $absen_masuk_kerja!=null && $absen_pulang_kerja!=null){
        $total_absen_kerja=(($absen_pulang_kerja-$absen_masuk_kerja)/60)-$jumlah_menit_istirahat_int;
        }else if($absen_pulang_kerja<$absen_masuk_kerja && $absen_masuk_kerja!=null && $absen_pulang_kerja!=null){
            $total_absen_kerja=(($absen_masuk_kerja-$absen_pulang_kerja)/60)-$jumlah_menit_istirahat_int;
        }else{
            $total_absen_kerja='';
        }
        return [
            Date::stringToExcel($Data->tanggal_berjalan),
            $Data->nama_hari,
            $Data->nik,
            $Data->enroll_id,
            $Data->employee_name,
            $Data->status_staff,
            $Data->status_jabatan,
            $Data->sub_dept_name,
            $Data->department_name,
            $Data->group_department,
            $kerjalibur,
            $time_mulai_kerja,
            $time_akhir_kerja,
            $time_menit_istirahat,
            $total_jam_kerja>0?$total_jam_kerja:'',
            $time_absen_masuk,
            $time_absen_pulang,
            $total_absen_kerja>0?$total_absen_kerja:'',
            $permits_dari_pukul,
            $permits_sampai_pukul,
            $Data->total_menit_permits,
            $Data->jumlah_menit_absen_dt,
            $Data->jumlah_menit_absen_pc,
            $Data->jumlah_menit_absen_dtpc,
            $Data->status_absen,
            $kode_ijin_payroll,
            $Data->absen_alasan,
            '',
            $Data->catatan,
            $Data->nomor_form_lembur,
            $mulai_jam_lembur,
            $akhir_jam_lembur,
            $hour_istirahat_lembur,
            $Data->jumlah_jam_lembur,
            '',
            $final_mulai_lembur,
            $final_selesai_lembur,
            $hour_final_istirahat_lembur,
            $hour_final__total_jam_lembur,
            '',
            $Data->salary_bulanan,
            $Data->gaji_perhari,
            $Data->gaji_permenit,
            '',
            $Data->iby,
            $Data->itb,
            $Data->m,
            $Data->dt,
            $Data->pc,
            $Data->dtpc,
            $Data->lby,
            $Data->lsm,
            $Data->r,
            $Data->ok,
            $Data->hari_kerja,
            $Data->pot_hari_kerja,
            $Data->total_absen,
            $Data->lembur_1,
            $Data->lembur_2,
            $Data->lembur_3,
            $Data->lembur_4,
            $Data->jumlah_menit_absen_dt,
            $Data->jumlah_menit_absen_pc,
            $Data->total_menit_permits,
            '',
            '',
            $Data->kode_grade,
            $Data->gaji_perhari,
            $Data->seniority_allowance,
            $Data->insentif_kehadiran,
            $Data->insentif_jabatan,
            $Data->lembur1_rupiah,
            $Data->lembur2_rupiah,
            $Data->lembur3_rupiah,
            $Data->lembur4_rupiah,
            $Data->koreksi_upah,
            $Data->koreksi_lembur,
            $Data->koreksi_insentif,
            $Data->potongan_upah,
            $Data->potongan_lembur,
            $Data->potongan_insentif,
            $Data->potongan_piutang,
            '',
            $Data->rp_pot_hari_kerja,
            $Data->rp_pot_jam,
            $Data->bruto,
            '',
            $Data->bruto,
            $Data->bpjs_tk,
            $Data->bpjs_ks,
            0,
            0,
            $Data->total_potongan,
            '0',
            $Data->jumlah,
            '',
            $Data->bpjs_tk_company,
            $Data->bpjs_ks_company,
            $Data->kompensasi,
            $Data->thr,
            $Data->konsumsi,
            '',
            $Data->total_pembayaran
        ];
    }
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'L' => NumberFormat::FORMAT_DATE_TIME3,
            'M' => NumberFormat::FORMAT_DATE_TIME3,
            'N' => NumberFormat::FORMAT_DATE_TIME3,
            'P' => NumberFormat::FORMAT_DATE_TIME3,
            'Q' => NumberFormat::FORMAT_DATE_TIME3,
            'S' => NumberFormat::FORMAT_DATE_TIME3,
            'T' => NumberFormat::FORMAT_DATE_TIME3,
            'AE' => NumberFormat::FORMAT_DATE_TIME3,
            'AF' => NumberFormat::FORMAT_DATE_TIME3,
            'AG' => NumberFormat::FORMAT_DATE_TIME3,
            'AJ' => NumberFormat::FORMAT_DATE_TIME3,
            'AK' => NumberFormat::FORMAT_DATE_TIME3,
            'AL' => NumberFormat::FORMAT_DATE_TIME3,
            'AM' => NumberFormat::FORMAT_DATE_TIME3,
            'CK' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'CL' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 12,
            'B' => 8,
            'C' => 11,
            'D' => 11,
            'E' => 35,
            'F' => 11,
            'G' => 11,
            'H' => 27,
            'I' => 27,
            'J' => 26,
            'K' => 14,
            'L' => 7,
            'M' => 7,
            'N' => 13,
            'O' => 13,
            'P' => 10,
            'Q' => 10,
            'R' => 10,
            'S' => 12,
            'T' => 12,
            'U' => 12,
            'V' => 11,
            'W' => 11,
            'Y' => 16,
            'Z' => 17,
            'AA' => 21,
            'AB' => 21,
            'AC'=>39,
            'AD'=>18,
            'AE'=>12,
            'AF'=>12,
            'AG'=>12,
            'AH'=>12,
            'AI'=>1,
            'AJ'=>13,
            'AK'=>13,
            'AL'=>13,
            'AM'=>13,
            'AN'=>1,
            'AO'=>13,
            'AP'=>9,
            'AQ'=>9,
            'AR'=>5,
            'AS'=>6,
            'AT'=>6,
            'AU'=>6,
            'AV'=>6,
            'AW'=>6,
            'AZ'=>6,
            'AY'=>6,
            'AZ'=>6,
            'BA'=>6,
            'BB'=>6,
            'BC'=>6,
            'BD'=>6,
            'BE'=>8,
            'BF'=>6,
            'BG'=>6,
            'BH'=>6,
            'BI'=>6,
            'BJ'=>11,
            'BK'=>11,
            'BL'=>11,
            'BM'=>11,
            'BN'=>5,
            'BO'=>9,
            'BP'=>9,
            'BQ'=>11,
            'BR'=>12,
            'BS'=>11,
            'BT'=>11,
            'BU'=>11,
            'BV'=>11,
            'BW'=>11,
            'BX'=>11,
            'BY'=>10,
            'BZ'=>10,
            'CA'=>10,
            'CB'=>10,
            'CC'=>10,
            'CD'=>10,
            'CE'=>12,
            'CF'=>12,
            'CG'=>12,
            'CH'=>12,
            'CI'=>12,
            'CJ'=>12,
            'CK'=>13,
            'CL'=>13,
            'CM'=>10,
            'CN'=>10,
            'CO'=>13,
            'CP'=>12,
            'CQ'=>13,
            'CR'=>7,
            'CS'=>13,
            'CT'=>13,
            'CU'=>12,
            'CV'=>11,
            'CW'=>11,
            'CX'=>7,
            'CY'=>12,
        ];
    }
}
