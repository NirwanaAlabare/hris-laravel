<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use DB;

class BiayaMakanKaryawanEstimasi implements WithMultipleSheets, ShouldQueue
{
    use Exportable;

    protected $dateFrom;
    public function __construct($dateFrom)
    {
        $this->dateFrom = $dateFrom;
    }
    public function sheets(): array
    {
        $sheets = [];
        $dateFrom = $this->dateFrom ? $this->dateFrom : date('Y-m-d');
        $sheets[0] = new BiayaMakanKaryawanEstimasiData($dateFrom);
        $sheets[1] = new BiayaMakanKaryawanEstimasiData2($dateFrom);
        $sheets[2] = new BiayaMakanKaryawanEstimasiData3('Summary All');
        return $sheets;
    }
}
