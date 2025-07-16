<?php

namespace App\Exports;

use App\Models\MasterDataAbsenKehadiran;
use App\Http\Controllers\Hris\DepartmentAllController;

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

class GagalAbsenExport implements FromQuery, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell, WithTitle
{
    use Exportable;

    public function exportParams(array $daterange1, string $searchData)
    {
        $this->daterange1 = $daterange1;
        $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
        $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));
        $this->tanggalMulai = $tanggalMulai;
        $this->tanggalSampai = $tanggalSampai;

        $this->searchData = strtoupper($searchData);

        $inSearchData = "";
        if($searchData) {
            $inSearchData = '
                AND (
                    UPPER(master_data_absen_kehadiran.enroll_id) LIKE ("%' . $searchData . '%")
                    OR UPPER(employee_atribut.nik) LIKE ("%' . $searchData . '%")
                    OR UPPER(employee_atribut.employee_name) LIKE ("%' . $searchData . '%")
                )
            ';

        }

        $this->inSearchData = $inSearchData;

        return $this;
    }

    public function query()
    {
        $tanggalMulai = $this->tanggalMulai;
        $tanggalSampai = $this->tanggalSampai;

        return MasterDataAbsenKehadiran::query()
                ->selectRaw('
                    master_data_absen_kehadiran.tanggal_berjalan,
                    master_data_absen_kehadiran.nama_hari,
                    employee_atribut.nik,
                    employee_atribut.enroll_id,
                    employee_atribut.employee_name,
                    employee_atribut.status_staff,
                    employee_atribut.status_staff status_staff_bck,
                    department_all.department_name,
                    department_all.sub_dept_name,
                    master_data_absen_kehadiran.mulai_jam_kerja,
                    master_data_absen_kehadiran.akhir_jam_kerja,
                    master_data_absen_kehadiran.absen_masuk_kerja,
                    master_data_absen_kehadiran.absen_pulang_kerja,
                    master_data_absen_kehadiran.holiday_name,
                    master_data_absen_kehadiran.status_absen
                ')
                ->whereRaw('
                   (
                    (substr(master_data_absen_kehadiran.tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '"  and employee_atribut.enroll_id is not null
                        AND master_data_absen_kehadiran.status_absen IN ("M","TL"))
                    or
                    (substr(master_data_absen_kehadiran.tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '"  and employee_atribut.enroll_id is not null
                        AND master_data_absen_kehadiran.mulai_jam_kerja is null AND master_data_absen_kehadiran.akhir_jam_kerja is null AND
                            ((master_data_absen_kehadiran.absen_masuk_kerja is not null AND master_data_absen_kehadiran.absen_pulang_kerja is null) or (master_data_absen_kehadiran.absen_masuk_kerja is null AND master_data_absen_kehadiran.absen_pulang_kerja is not null))
                        )
                    or
                     (substr(master_data_absen_kehadiran.tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '"  and employee_atribut.enroll_id is not null
                        AND master_data_absen_kehadiran.mulai_jam_kerja is not null AND master_data_absen_kehadiran.akhir_jam_kerja is not null AND
                            ((master_data_absen_kehadiran.absen_masuk_kerja is not null AND master_data_absen_kehadiran.absen_pulang_kerja is null) or (master_data_absen_kehadiran.absen_masuk_kerja is null AND master_data_absen_kehadiran.absen_pulang_kerja is not null))
                            AND master_data_absen_kehadiran.status_absen = "LP"
                        )
                        )
                ' . $this->inSearchData . '
            ')
                ->leftJoin('employee_atribut','master_data_absen_kehadiran.enroll_id','=','employee_atribut.enroll_id')
                ->leftJoin('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id')
                ->orderBy('master_data_absen_kehadiran.tanggal_berjalan','asc')
                ->orderBy('employee_atribut.employee_name','asc')
                ->limit(1);
    }

    public function startCell(): string
    {
        return 'A7';
    }

    public function map($Kehadiran): array
    {
        $kode_hari = $Kehadiran->kode_hari;
        $liburnasional = $Kehadiran->holiday_name;

        $absenIN = $Kehadiran->absen_masuk_kerja;
        $absenOUT = $Kehadiran->absen_pulang_kerja;

        $kerjalibur = "KERJA";
        switch ($kode_hari) {
            case '5':
                $kerjalibur = "LIBUR";
                break;
            case '6':
                $kerjalibur = "LIBUR";
                break;
        }

        if(($absenIN <> null) || ($absenIN <> "") || ($absenOUT <> null) || ($absenOUT <> "")) {
            $kerjalibur = "KERJA";
            switch ($kode_hari) {
                case '5':
                    $kerjalibur = "LIBUR";
                    break;
                case '6':
                    $kerjalibur = "LIBUR";
                    break;
            }
        }

        if($liburnasional <> "") {
            $kerjalibur = "LIBUR";
        }

        $status_absen = $Kehadiran->status_absen;

        return [
            \Carbon\Carbon::parse($Kehadiran->tanggal_berjalan)->format('d-m-Y'),
            $Kehadiran->nama_hari,
            $Kehadiran->nik,
            $Kehadiran->enroll_id,
            $Kehadiran->employee_name,
            $Kehadiran->status_staff == "" || $Kehadiran->status_staff == null ? $Kehadiran->status_staff_bck : $Kehadiran->status_staff,
            $Kehadiran->department_name,
            $Kehadiran->sub_dept_name,
            $kerjalibur,
            substr($Kehadiran->mulai_jam_kerja, 0, 5),
            substr($Kehadiran->akhir_jam_kerja, 0, 5),
            substr($Kehadiran->absen_masuk_kerja, 0, 5),
            substr($Kehadiran->absen_pulang_kerja, 0, 5),
            $status_absen,
        ];
    }

    public function title(): string
    {
        return $this->daterange1[0] . ' s/d ' . $this->daterange1[1];
    }

    public function registerEvents() : array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $sheet = $event->sheet;
                $sheet->setCellValue('A1', 'PT NIRWANA ALABARE GARMENT');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(18);
                $sheet->setCellValue('A2', 'LAPORAN GAGAL ABSEN');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(16);

                setlocale(LC_ALL, 'id-ID', 'id_ID');
                $datePeriode = strtoupper(strftime("%d %b %Y", strtotime($this->tanggalMulai)) . ' S/D ' . strftime("%d %b %Y", strtotime($this->tanggalSampai)));

                $sheet->setCellValue('A3', 'TANGGAL : ' . $datePeriode);
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A3:D3');

                $sheet->setCellValue('A5', 'TANGGAL');
                $sheet->setCellValue('B5', 'HARI');
                $sheet->setCellValue('C5', 'NIK');
                $sheet->setCellValue('D5', 'NO. ABSEN');
                $sheet->setCellValue('E5', 'NAMA KARYAWAN');
                $sheet->setCellValue('F5', 'STAFF/NON STAFF');
                $sheet->setCellValue('G5', 'DEPARTMENT');
                $sheet->setCellValue('H5', 'BAGIAN');
                $sheet->setCellValue('I5', 'KERJA/LIBUR');

                $sheet->mergeCells('J5:K5');
                $sheet->setCellValue('J5', 'JADWAL KERJA');
                $sheet->setCellValue('J6', 'IN');
                $sheet->setCellValue('K6', 'OUT');

                $sheet->mergeCells('L5:M5');
                $sheet->setCellValue('L5', 'ABSEN');
                $sheet->setCellValue('L6', 'IN');
                $sheet->setCellValue('M6', 'OUT');

                $sheet->setCellValue('N5', 'STATUS ABSEN');

                $sheet->mergeCells('A5:A6');
                $sheet->mergeCells('B5:B6');
                $sheet->mergeCells('C5:C6');
                $sheet->mergeCells('D5:D6');
                $sheet->mergeCells('E5:E6');
                $sheet->mergeCells('F5:F6');
                $sheet->mergeCells('G5:G6');
                $sheet->mergeCells('H5:H6');
                $sheet->mergeCells('I5:I6');
                $sheet->mergeCells('N5:N6');

            },
        ];
    }

    public function properties(): array
    {
        return [
            'creator'        => 'PT NAG - HRIS',
            'lastModifiedBy' => 'HRIS',
            'title'          => 'Data Gagal Absen Karyawan',
            'description'    => 'Data Gagal Absen Karyawan',
            'subject'        => 'Kehadiran Karyawan',
            'keywords'       => 'kehadiran,hr,hris,hrm,daily,report,karyawan,data',
            'category'       => 'KehadiranKaryawan',
            'manager'        => 'HRIS',
            'company'        => 'PT Nirwana Alabare Garment',
        ];
    }
}
