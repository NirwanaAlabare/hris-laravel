<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class KoreksiPotonganExport implements FromView
{
    protected $DataKoreksiPotongan;
    

    function __construct($DataKoreksiPotongan) {
        $this->DataKoreksiPotongan = $DataKoreksiPotongan;
    }   
    public function view(): View
    {
        return view('hris.Laporan.KoreksiPotonganExcel',[
            'DataKoreksiPotongan' => $this->DataKoreksiPotongan,
            
        ]);
    }
}
