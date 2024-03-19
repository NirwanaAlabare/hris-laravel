<?php

namespace App\Exports;

use App\Models\DepartmentAll;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class DepartmentAllExport implements FromView
{
    public function view(): View
    {
        return view('hris/laporan_harian_kehadiran', [
            'department' => DepartmentAll::all()
        ]);
    }
}
