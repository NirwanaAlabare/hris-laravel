<?php

namespace App\Exports;

use App\Models\DataAbsenPerijinan;
use App\Models\MasterDataAbsenKehadiran;
use App\Http\Controllers\Hris\DataAbsenPerijinanController;

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

class DataAbsenPerijinanIKSExport implements FromQuery, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell, WithTitle
{
    use Exportable;

    public function exportParams(string $daterange2)
    {
        $this->daterange2 = $daterange2;
        $daterange2 = explode(" s/d ", $this->daterange2);
        $tanggalMulai = date('Y-m-d', strtotime($daterange2[0]));
        $tanggalSampai = date('Y-m-d', strtotime($daterange2[1]));
        $this->tanggalMulai = $tanggalMulai;
        $this->tanggalSampai = $tanggalSampai;

        return $this;
    }

    public function query()
    {
        $tanggalMulai = $this->tanggalMulai;
        $tanggalSampai = $this->tanggalSampai;

        $q =  MasterDataAbsenKehadiran::query()
                ->selectRaw('
                    master_data_absen_kehadiran.tanggal_berjalan,
                    master_data_absen_kehadiran.nomor_absen_ijin,
                    department_all.department_name,
                    department_all.sub_dept_name,
                    employee_atribut.enroll_id,
                    employee_atribut.nik,
                    employee_atribut.employee_name,
                    employee_atribut.status_staff,
                    master_data_absen_kehadiran.nama_hari,
                    master_data_absen_kehadiran.absen_alasan,
                    master_data_absen_kehadiran.permits_dari_pukul,
                    master_data_absen_kehadiran.permits_sampai_pukul,
                    master_data_absen_kehadiran.total_menit_permits    
                ')
                ->whereRaw('
                    substr(master_data_absen_kehadiran.tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '" 
                    and (master_data_absen_kehadiran.nomor_absen_ijin like "IKS%" )
                ')
                ->leftJoin('employee_atribut', 'master_data_absen_kehadiran.enroll_id', '=', 'employee_atribut.enroll_id')
                ->leftJoin('department_all', 'employee_atribut.sub_dept_id', '=', 'department_all.sub_dept_id')
                ->groupBy('master_data_absen_kehadiran.tanggal_berjalan')
                ->groupBy('employee_atribut.enroll_id')
                ->orderBy('master_data_absen_kehadiran.nomor_absen_ijin','asc')
                ->orderBy('employee_atribut.employee_name','asc')
                ->orderBy('master_data_absen_kehadiran.tanggal_berjalan','asc')
                ->limit(1);

        return $q;
    }

    public function startCell(): string
    {
        return 'A7';
    }

    public function map($Data): array
    {
        $tanggal_perizinan = $Data->tanggal_berjalan;
        $nomor_form_perizinan = $Data->nomor_absen_ijin;
        $department_name = $Data->department_name;
        $bagian_name = $Data->sub_dept_name;
        $enroll_id = $Data->enroll_id;
        $nik = $Data->nik;
        $employee_name = $Data->employee_name;
        $status_staff = $Data->status_staff;
        $nama_hari = $Data->nama_hari;
        $absen_alasan = $Data->absen_alasan;
        $permits_dari_pukul = $Data->permits_dari_pukul;
        $permits_sampai_pukul = $Data->permits_sampai_pukul;
        $total_menit_permits = $Data->total_menit_permits;

        return [
            $nomor_form_perizinan,
            $tanggal_perizinan,
            $nama_hari,
            $permits_dari_pukul,
            $permits_sampai_pukul,
            $total_menit_permits,
            $nik,
            $enroll_id,
            $employee_name,
            $department_name,
            $bagian_name,
            $status_staff,
            $absen_alasan,
        ];
    }

    public function title(): string
    {
        return 'DATAIZINIKS';
    }

    public function registerEvents() : array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $sheet = $event->sheet;
                $sheet->setCellValue('A1', 'PT NIRWANA ALABARE GARMENT');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(18);
                $sheet->setCellValue('A2', 'LAPORAN DATA IZIN (IKS) KARYAWAN');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(16);

                setlocale(LC_ALL, 'id-ID', 'id_ID');
                $datePeriode = strtoupper(strftime("%d %b %Y", strtotime($this->tanggalMulai)) . ' s/d ' . strftime("%d %b %Y", strtotime($this->tanggalSampai)));

                $sheet->setCellValue('A3', 'TANGGAL IZIN (IKS) : ' . $datePeriode);

                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A3:D3');

                $sheet->setCellValue('A5', 'NO. PERIZINAN');
                $sheet->setCellValue('B5', 'TANGGAL FORM');
                $sheet->setCellValue('C5', 'HARI');
                $sheet->setCellValue('D5', 'WAKTU IZIN(IKS)');
                $sheet->setCellValue('D6', 'DARI');
                $sheet->setCellValue('E6', 'SAMPAI');
                $sheet->setCellValue('F5', 'TOTAL MENIT');
                $sheet->setCellValue('G5', 'NIK');
                $sheet->setCellValue('H5', 'NO. ABSEN');
                $sheet->setCellValue('I5', 'NAMA KARYAWAN');
                $sheet->setCellValue('J5', 'DEPARTMENT');
                $sheet->setCellValue('K5', 'BAGIAN');
                $sheet->setCellValue('L5', 'STAFF/NON STAFFF');
                $sheet->setCellValue('M5', 'ALASAN');

                $sheet->mergeCells('A5:A6');
                $sheet->mergeCells('B5:B6');
                $sheet->mergeCells('C5:C6');
                $sheet->mergeCells('F5:F6');
                $sheet->mergeCells('G5:G6');
                $sheet->mergeCells('H5:H6');
                $sheet->mergeCells('I5:I6');
                $sheet->mergeCells('J5:J6');
                $sheet->mergeCells('K5:K6');
                $sheet->mergeCells('L5:L6');
                $sheet->mergeCells('M5:M6');

                $sheet->mergeCells('D5:E5');
            },
        ];
    }

    public function properties(): array
    {
        return [
            'creator'        => 'PT NAG - HRIS',
            'lastModifiedBy' => 'HRIS',
            'title'          => 'Data Perizinan (IKS) Karyawan',
            'description'    => 'Data Perizinan (IKS) Karyawan',
            'subject'        => 'Data Perizinan (IKS) Karyawan',
            'keywords'       => 'perizinan,iks,hr,hris,hrm,daily,report,karyawan,data',
            'category'       => 'PerizinanIKSKaryawan',
            'manager'        => 'HRIS',
            'company'        => 'PT Nirwana Alabare Garment',
        ];
    }
}
