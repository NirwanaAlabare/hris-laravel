<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class summaryDepartmentExport implements FromView,  WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($status_staff,$arrayData,$jumlah_karyawan_bank_bni,$jumlah_karyawan_bank_cimb,$jumlah_karyawan_no_bank,$jumlah_semua_karyawan,$gaji_bni,$gaji_cimb,$gaji_other,$all_gaji,string $periode_tahun_payroll, string $periode_bulan_payroll)
    {
        $this->status_staff=$status_staff;
        $this->payroll=$arrayData;
        $this->jumlah_karyawan_bank_bni = $jumlah_karyawan_bank_bni;
        $this->jumlah_karyawan_bank_cimb = $jumlah_karyawan_bank_cimb;
        $this->jumlah_karyawan_no_bank = $jumlah_karyawan_no_bank;
        $this->jumlah_semua_karyawan = $jumlah_semua_karyawan;
        $this->gaji_bni = $gaji_bni;
        $this->gaji_cimb = $gaji_cimb;
        $this->gaji_other = $gaji_other;
        $this->all_gaji = $all_gaji;
        $this->periode_tahun_payroll = $periode_tahun_payroll;
        $this->periode_bulan_payroll = $periode_bulan_payroll;
    }
    public function view(): View
    {
        return view('hris.Laporan.summary_department',[
            'status_staff'=>$this->status_staff,
            'arrayData' => $this->payroll,
            'jumlah_karyawan_bank_bni'=>$this->jumlah_karyawan_bank_bni,
            'jumlah_karyawan_bank_cimb'=>$this->jumlah_karyawan_bank_cimb,
            'jumlah_karyawan_no_bank'=>$this->jumlah_karyawan_no_bank,
            'jumlah_semua_karyawan'=>$this->jumlah_semua_karyawan,
            'gaji_bni'=>$this->gaji_bni,
            'gaji_cimb'=>$this->gaji_cimb,
            'gaji_other'=>$this->gaji_other,
            'all_gaji'=>$this->all_gaji,
            'periode_tahun_payroll'=>$this->periode_tahun_payroll,
            'periode_bulan_payroll'=>$this->periode_bulan_payroll
        ]);
    }
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'O' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
        ];
    }
}
