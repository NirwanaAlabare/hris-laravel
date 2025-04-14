<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapCutiKaryawanKaryawanAll implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($data_cuti)
    {
        $this->data_cuti=$data_cuti;
    }
    public function view(): View
    {
        return view('hris.Laporan.cuti_karyawan_excel',[
            'data_cuti'=>$this->data_cuti
        ]);
    }
}
