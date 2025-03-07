<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\PengajuanBazzar;
use App\Models\VoucherBazzar;
use DB;

class ExportPengajuanKas implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($id,$sub_dept_id,$status,$tanggal)
    {
        $this->id = $id;
        $this->sub_dept_id = $sub_dept_id;
        $this->status = $status;
        $this->tanggal = $tanggal;

    }
    public function view(): View
    {


        return view('hris/ga.entertaint.export_pengajuan_kas_excel');
    }
}
