<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class EstimasiPayrollExport implements FromView
{
    protected $data;
    protected $info;

    function __construct($data,$info) {
        $this->data = $data;
        $this->info = $info;
    }   
    public function view(): View
    {
        return view('hris.Laporan.EstimasiPayrollExcel',[
            'data' => $this->data,
            'info' => $this->info,
            
        ]);
    }
}
