<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use DB;

class BiayaMakanKaryawan implements WithMultipleSheets, ShouldQueue
{
    use Exportable;

    protected $dateFrom,$dateTo;
    public function __construct($dateFrom,$dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }
    public function sheets(): array
    {
        $sheets = [];
        $dateFrom = $this->dateFrom ? $this->dateFrom : date('Y-m-d');
        $dateTo = $this->dateTo ? $this->dateTo : date('Y-m-d');
        $sheets[0] = new BiayaMakanKaryawanData($dateFrom,$dateTo);
        $sheets[1] = new BiayaMakanKaryawanData2($dateFrom,$dateTo);
        $sheets[2] = new BiayaMakanKaryawanData3('Summary All');
        return $sheets;
    }
}
