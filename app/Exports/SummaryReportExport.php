<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SummaryReportExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($periode_payroll,$bulan,$tahun,$daftar_karyawan,$data_tanggal_absensi,$data_daftar_karyawan,$keterangan_absensi)
    {
        $this->periode_payroll=$periode_payroll;
        $this->bulan=$bulan;
        $this->tahun=$tahun;
        $this->daftar_karyawan=$daftar_karyawan;
        $this->data_tanggal_absensi=$data_tanggal_absensi;
        $this->data_daftar_karyawan=$data_daftar_karyawan;
        $this->keterangan_absensi=$keterangan_absensi;
    }
    public function view(): View
    {
        return view('hris.Laporan.summary_report',[
            'periode_payroll'=>$this->periode_payroll,
            'bulan'=>$this->bulan,
            'tahun'=>$this->tahun,
            'daftar_karyawan'=>$this->daftar_karyawan,
            'data_tanggal_absensi'=>$this->data_tanggal_absensi,
            'data_daftar_karyawan'=>$this->data_daftar_karyawan,
            'keterangan_absensi'=>$this->keterangan_absensi,
        ]);
    }
}
