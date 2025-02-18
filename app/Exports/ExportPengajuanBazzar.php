<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\PengajuanBazzar;
use DB;

class ExportPengajuanBazzar implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($id)
    {
        $this->id = $id;

    }
    public function view(): View
    {
        if($this->id){
            $data = PengajuanBazzar::select('pengajuan_bazzar.*', 'employee_atribut.nik', 'employee_atribut.employee_name', 'employee_atribut.department_name', 'employee_atribut.sub_dept_name', 'employee_atribut.status_staff')->where('status', 'approve')->where('id', $this->id)->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'pengajuan_bazzar.enroll_id')->get();
        }else{
            $data = PengajuanBazzar::select('pengajuan_bazzar.*', 'employee_atribut.nik', 'employee_atribut.employee_name', 'employee_atribut.department_name', 'employee_atribut.sub_dept_name', 'employee_atribut.status_staff')->where('status', 'approve')->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'pengajuan_bazzar.enroll_id')->get();
        }
        $total_jumlah = $data->sum('jumlah');
        return view('hris/mutasi-karyawan.bazzar.export_pengajuan_excel', [
            'data' => $data,
            'total_jumlah' => $total_jumlah,
        ]);
    }
}
