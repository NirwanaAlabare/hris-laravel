<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\DepartmentAll;

class DepartmentAllFilterExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($site_nirwana,$department,$sub_department)
    {
        $this->site_nirwana=$site_nirwana;
        $this->department=$department;
        $this->sub_department=$sub_department;
    }
    public function view(): View
    {
        $inSiteNirwana='';
        $inDepartment='';
        $inSubDepartment='';
        if($this->site_nirwana==null){
            $query =  DepartmentAll::orderBy('site_nirwana_id')->get();
        }else{
            if($this->site_nirwana){
                $inSiteNirwana = ' AND site_nirwana_id = "'.$this->site_nirwana.'"';
            }
            if($this->department){
                $inDepartment = ' AND department_id = "'.$this->department.'"';
            }
            if($this->sub_department){
                $inSubDepartment = ' AND sub_dept_id = "'.$this->sub_department.'"';
            }
            $query =  DepartmentAll::whereRaw('status is not null'.$inSiteNirwana.''.$inDepartment.''.$inSubDepartment.'')->get();
        }
        return view('hris.Laporan.department_all_filter_export',[
            'query'=>$query,
        ]);
    }
}
