<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class KoreksiPotonganExport implements FromView
{
    protected $DataKoreksiPotongan;


    function __construct($DataKoreksiPotongan, $priode_payroll) {
        $this->DataKoreksiPotongan = $DataKoreksiPotongan;
        $this->priode_payroll = $priode_payroll;
    }
    public function view(): View
    {
        return view('hris.Laporan.KoreksiPotonganExcel',[
            'DataKoreksiPotongan' => $this->DataKoreksiPotongan,
            'title' => $this->priode_payroll,

        ]);
    }
}
