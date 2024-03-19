<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class JurnalExport implements FromView
{
    protected $data;

    function __construct($data) {
        $this->data = $data;
    }   
    public function view(): View
    {
        return view('hris.Laporan.JurnalExcel',[
            'data' => $this->data,
            
        ]);
    }
}
