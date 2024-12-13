<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;

class RecapDailyLaborCostExport implements WithMultipleSheets, ShouldQueue
{
    use Exportable;
    private $tanggal_awal;
    private $tanggal_akhir;
    private $staffnonstaff;
    public function __construct($tanggal_awal,$tanggal_akhir,$staffnonstaff)
    {
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->staffnonstaff = $staffnonstaff;
    }
    public function sheets(): array
    {
        $sheets = [];
        // $sheets[0] = new RecapLaborAll($this->tanggal_awal,$this->tanggal_akhir,$this->staffnonstaff);
        $sheets[0] = new RecapLaborStaff($this->tanggal_awal,$this->tanggal_akhir,$this->staffnonstaff);
        $sheets[1] = new RecapLaborNonStaff($this->tanggal_awal,$this->tanggal_akhir);
        return $sheets;
    }
}
