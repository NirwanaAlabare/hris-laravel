<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use DB;

class BiayaMakanKaryawanEstimasi implements WithMultipleSheets, ShouldQueue
{
    use Exportable;

    // protected $dateFrom;
    // public function __construct($dateFrom)
    // {
    //     $this->dateFrom = $dateFrom;
    // }
    protected $from, $to;

public function __construct($from, $to)
{
    $this->from = $from;
    $this->to   = $to;
}
    // public function sheets(): array
    // {
    //     $sheets = [];
    //     $dateFrom = $this->dateFrom ? $this->dateFrom : date('Y-m-d');
    //     $sheets[0] = new BiayaMakanKaryawanEstimasiData($dateFrom, 'lembur');
    //     $sheets[1] = new BiayaMakanKaryawanEstimasiData($dateFrom, 'shift_malam');
    //     // $sheets[0] = new BiayaMakanKaryawanEstimasiData($dateFrom);
    //     $sheets[2] = new BiayaMakanKaryawanEstimasiData2($dateFrom);
    //     $sheets[3] = new BiayaMakanKaryawanEstimasiData3('Summary All');
    //     return $sheets;
    // }
     public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new BiayaMakanKaryawanEstimasiData($this->from, $this->to);
        // $sheets[] = new BiayaMakanKaryawanEstimasiData($this->from, $this->to, 'shift_malam');
        $sheets[] = new BiayaMakanKaryawanEstimasiData2($this->from, $this->to);
        $sheets[] = new BiayaMakanKaryawanEstimasiData3($this->from, $this->to, 'Summary All');

        return $sheets;
    }
}
