<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class KoreksiUpahExport implements FromView
{
    protected $DataKoreksiUpah;
    

    function __construct($DataKoreksiUpah) {
        $this->DataKoreksiUpah = $DataKoreksiUpah;
    }   
    public function view(): View
    {
        return view('hris.Laporan.KoreksiUpahExcel',[
            'DataKoreksiUpah' => $this->DataKoreksiUpah,
            
        ]);
    }
}
