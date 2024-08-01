<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class rincianKehadiranKaryawan implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($tanggal_awal_absen,$tanggal_akhir_absen,$employee,$jumlah_menit,$jumlah_absen)
    {
        $this->tanggal_awal_absen=$tanggal_awal_absen;
        $this->tanggal_akhir_absen=$tanggal_akhir_absen;
        $this->employee=$employee;
        $this->jumlah_menit = $jumlah_menit;
        $this->jumlah_absen = $jumlah_absen;
    }
    public function view(): View
    {
        return view('hris.Laporan.rincian_kehadiran_karyawan_excel',[
            'tanggal_awal_absen'=>$this->tanggal_awal_absen,
            'tanggal_akhir_absen'=>$this->tanggal_akhir_absen,
            'employee' => $this->employee,
            'jumlah_menit'=>$this->jumlah_menit,
            'jumlah_absen'=>$this->jumlah_absen
        ]);
    }
}
