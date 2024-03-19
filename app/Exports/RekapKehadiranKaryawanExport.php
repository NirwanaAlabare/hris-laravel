<?php

namespace App\Exports;

use App\Http\Controllers\Hris\RekapKehadiranKaryawanController;
use App\Models\RekapKehadiranKaryawan;
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

class RekapKehadiranKaryawanExport implements FromQuery, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell, WithTitle
{
    use Exportable;

    public function exportParams(string $periode_payroll, string $status_staff, string $searchData, string $tgl_awal)
    {
        $this->periode_payroll = $periode_payroll;
        $this->status_staff = $status_staff;
        $this->searchData = $searchData;
        $this->tgl_awal = $tgl_awal;

        return $this;
    }

    public function query()
    {

        $periode_payroll = $this->periode_payroll;
        $tgl_awal=$this->tgl_awal;

        $status_staff = $this->status_staff;
        if($status_staff) {
            $whereStatusStaff = ' AND upper(status_staff) = upper( "' . $status_staff . '" ) ';
        } else {
            $whereStatusStaff = '';
        }

        $searchData = $this->searchData;
        if($searchData) {
            $whereSearchData = ' AND (upper( enroll_id ) LIKE upper( "%' . $searchData . '%" ) OR upper( employee_name ) LIKE upper( "%' . $searchData . '%" ) OR upper( sub_dept_name ) LIKE upper( "%' . $searchData . '%" ) OR upper( status_aktif ) LIKE upper( "%' . $searchData . '%" ) ) ';
        } else {
            $whereSearchData = '';
        }

          // $q =  RekapKehadiranKaryawan::query()
        //         ->whereRaw('
        //             periode_payroll = "' . $periode_payroll . '"
        //             ' . $whereStatusStaff . '
        //             ' . $whereSearchData . ' and
        //             total_kehadiran_net > 0
        //         ')
        //         ->orderBy('employee_name','asc')
        //         ->limit(1);


        $q = RekapKehadiranKaryawan::query()
            ->whereRaw('
                periode_payroll = "' . $periode_payroll . '"
                ' . $whereStatusStaff . '
                ' . $whereSearchData . ' 
            ')
            ->where(function ($query) use ($tgl_awal) {
                $query->orWhereNull('tanggal_resign')
                    ->orWhere('tanggal_resign', '>', $tgl_awal);
            })
            ->orderBy('employee_name', 'asc')
            ->limit(1);
        return $q;
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function map($Data): array
    {
        $enroll_id = $Data->enroll_id;
        $nik = $Data->nik;
        $employee_name = $Data->employee_name;
        $sub_dept_name = $Data->sub_dept_name;
        $status_aktif = $Data->status_aktif;
        $status_staff = $Data->status_staff;
        $kehadiran_iby = $Data->kehadiran_iby;
        $kehadiran_itb = $Data->kehadiran_itb;
        $kehadiran_lby = $Data->kehadiran_lby;
        $kehadiran_lsm = $Data->kehadiran_lsm;
        $kehadiran_dt = $Data->kehadiran_dt;
        $kehadiran_pc = $Data->kehadiran_pc;
        $kehadiran_dtpc = $Data->kehadiran_dtpc;
        $kehadiran_m = $Data->kehadiran_m;
        $kehadiran_r = $Data->kehadiran_r;
        $kehadiran_tk = $Data->kehadiran_tk;
        $kehadiran_ok = $Data->kehadiran_ok;
        $total_kehadiran = $Data->total_kehadiran;
        $total_kehadiran_net = $Data->total_kehadiran_net;
        $updated_at = $Data->updated_at;

        $kehadiran_dl=$Data->kehadiran_dl;
        $kehadiran_cb=$Data->kehadiran_cb;
        $kehadiran_cbd=$Data->kehadiran_cbd;
        $kehadiran_cg=$Data->kehadiran_cg;
        $kehadiran_ch=$Data->kehadiran_ch;
        $kehadiran_cm=$Data->kehadiran_cm;
        $kehadiran_cn=$Data->kehadiran_cn;
        $kehadiran_ct=$Data->kehadiran_ct;
        $kehadiran_ig=$Data->kehadiran_ig;
        $kehadiran_im=$Data->kehadiran_im;
        $kehadiran_ka=$Data->kehadiran_ka;
        $kehadiran_km=$Data->kehadiran_km;
        $kehadiran_kr=$Data->kehadiran_kr;
        $kehadiran_na=$Data->kehadiran_na;
        $kehadiran_pp=$Data->kehadiran_pp;
        $kehadiran_i=$Data->kehadiran_i;
        $kehadiran_lp=$Data->kehadiran_lp;
        $kehadiran_l=$Data->kehadiran_l;
        $kehadiran_tl=$Data->kehadiran_tl;
        $kehadiran_iks=$Data->kehadiran_iks;
        $kehadiran_s=$Data->kehadiran_s;
        $kehadiran_mangkir=$kehadiran_m-$kehadiran_tl;

        $kosong='';

        return [
            $enroll_id,
            $nik,
            $employee_name,
            $sub_dept_name,
            $status_aktif,
            $status_staff,
            $kehadiran_iby,
            $kehadiran_itb,
            $kehadiran_lby,
            $kehadiran_lsm,
            $kehadiran_dt,
            $kehadiran_pc,
            $kehadiran_dtpc,
            $kehadiran_m,
            $kehadiran_r,
            $kehadiran_tk,
            $kehadiran_ok,
            $total_kehadiran,
            $total_kehadiran_net,
            $updated_at,
            $kosong,

            $kehadiran_dl,
            $kehadiran_dt,
            $kehadiran_pc,
            $kehadiran_dtpc,
            $kehadiran_cb,
            $kehadiran_cbd,
            $kehadiran_cg,
            $kehadiran_ch,
            $kehadiran_cm,
            $kehadiran_cn,
            $kehadiran_ct,
            $kehadiran_ig,
            $kehadiran_im,
            $kehadiran_ka,
            $kehadiran_km,
            $kehadiran_kr,
            $kehadiran_na,
            $kehadiran_pp,
            $kehadiran_i,
            $kehadiran_lby,
            $kehadiran_lp,
            $kehadiran_lsm,
            $kehadiran_l,
            $kehadiran_mangkir,
            $kehadiran_tl,
            $kehadiran_iks,
            $kehadiran_ok,
            $kehadiran_r,
            $kehadiran_s,


        ];
    }

    public function title(): string
    {
        return 'REKAPKEHADIRANKARYAWAN';
    }

    public function registerEvents() : array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $sheet = $event->sheet;
                $sheet->setCellValue('A1', 'PT NIRWANA ALABARE GARMENT');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(18);
                $sheet->setCellValue('A2', 'Laporan Rekap Kehadiran Karyawan');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(16);
                $sheet->setCellValue('A3', 'Periode Payroll  : ' . $this->periode_payroll);
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A3:D3');

                $sheet->setCellValue('A5', 'Nomor Absen');
                $sheet->setCellValue('B5', 'NIK');
                $sheet->setCellValue('C5', 'Nama Karyawan');
                $sheet->setCellValue('D5', 'Bagian');
                $sheet->setCellValue('E5', 'Aktif');
                $sheet->setCellValue('F5', 'Staff');
                $sheet->setCellValue('G5', 'IBY');
                $sheet->setCellValue('H5', 'ITB');
                $sheet->setCellValue('I5', 'LBY');
                $sheet->setCellValue('J5', 'LSM');
                $sheet->setCellValue('K5', 'DT');
                $sheet->setCellValue('L5', 'PC');
                $sheet->setCellValue('M5', 'DTPC');
                $sheet->setCellValue('N5', 'M');
                $sheet->setCellValue('O5', 'R');
                $sheet->setCellValue('P5', 'TK');
                $sheet->setCellValue('Q5', 'OK');
                $sheet->setCellValue('R5', 'Total');
                $sheet->setCellValue('S5', 'Net');
                $sheet->setCellValue('T5', 'Perubahan Terakhir');

                $sheet->setCellValue('U5', '');

                $sheet->setCellValue('V5', 'DL');
                $sheet->setCellValue('W5', 'DT');
                $sheet->setCellValue('X5', 'PC');
                $sheet->setCellValue('Y5', 'DTPC');
                $sheet->setCellValue('Z5', 'CB');
                $sheet->setCellValue('AA5', 'CBD');
                $sheet->setCellValue('AB5', 'CG');
                $sheet->setCellValue('AC5', 'CH');
                $sheet->setCellValue('AD5', 'CM');
                $sheet->setCellValue('AE5', 'CN');
                $sheet->setCellValue('AF5', 'CT');
                $sheet->setCellValue('AG5', 'IG');
                $sheet->setCellValue('AH5', 'IM');
                $sheet->setCellValue('AI5', 'KA');
                $sheet->setCellValue('AJ5', 'KM');
                $sheet->setCellValue('AK5', 'KR');
                $sheet->setCellValue('AL5', 'NA');
                $sheet->setCellValue('AM5', 'PP');
                $sheet->setCellValue('AN5', 'I');
                $sheet->setCellValue('AO5', 'LN');
                $sheet->setCellValue('AP5', 'LP');
                $sheet->setCellValue('AQ5', 'LSM');
                $sheet->setCellValue('AR5', 'L');
                $sheet->setCellValue('AS5', 'M');
                $sheet->setCellValue('AT5', 'TL');
                $sheet->setCellValue('AU5', 'IKS');
                $sheet->setCellValue('AV5', 'OK');
                $sheet->setCellValue('AW5', 'R');
                $sheet->setCellValue('AX5', 'S');

            },
        ];
    }

    public function properties(): array
    {
        return [
            'creator'        => 'PT NAG - HRIS',
            'lastModifiedBy' => 'HRIS',
            'title'          => 'Rekap Perhitungan Kehadiran Karyawan',
            'description'    => 'Rekap Perhitungan Kehadiran Karyawan',
            'subject'        => 'Rekap Perhitungan Kehadiran Karyawan',
            'keywords'       => 'perhitungan,kehadiran,hr,hris,hrm,daily,report,karyawan,data',
            'category'       => 'RekapPerhitunganKehadiranKaryawan',
            'manager'        => 'HRIS',
            'company'        => 'PT Nirwana Alabare Garment',
        ];
    }
}
