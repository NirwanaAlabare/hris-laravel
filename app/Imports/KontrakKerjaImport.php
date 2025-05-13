<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\EmployeeAtribut;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Carbon\Carbon;

class KontrakKerjaImport implements ToModel, WithStartRow, WithCalculatedFormulas
{
    /**
    * @param Collection $collection
    */
    private $rows=[];
    public function startRow(): int
    {
        return 4;
    }
    public function model(array $row)
    {
        $nik=EmployeeAtribut::where('enroll_id',$row[2])->pluck('nik');
        $employee_name=EmployeeAtribut::where('enroll_id',$row[2])->pluck('employee_name');
        $department=EmployeeAtribut::where('enroll_id',$row[2])->pluck('department_name');
        $bagian=EmployeeAtribut::where('enroll_id',$row[2])->pluck('sub_dept_name');
        $contract=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[9])->format('Y-m-d');
        $contract_end=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[10])->format('Y-m-d');
        $this->rows[]=[
            'nik'=>$nik,
            'employee_name'=>$employee_name,
            'department'=>$department,
            'bagian'=>$bagian,
            'contract'=>$contract,
            'contract_end'=>$contract_end
        ];
    }

    // public function model(array $row)
    // {
    //     $nik=EmployeeAtribut::where('enroll_id',$row[3])->pluck('nik');
    //     $employee_name=EmployeeAtribut::where('enroll_id',$row[3])->pluck('employee_name');
    //     $department=EmployeeAtribut::where('enroll_id',$row[3])->pluck('department_name');
    //     $bagian=EmployeeAtribut::where('enroll_id',$row[3])->pluck('sub_dept_name');

    //     for($j=15;$j<=106;$j+=2){
    //         if($row[$j]==null||$row[$j]=='-'||preg_match("/[a-z]/i", $row[$j])||strpos($row[$j], ' ') !== false){
    //             continue;
    //         }
    //         $contract=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[$j])->format('Y-m-d');
    //         $contract_end=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[$j+1])->format('Y-m-d');
    //         $this->rows[]=[
    //             'nik'=>$nik,
    //             'employee_name'=>$employee_name,
    //             'department'=>$department,
    //             'bagian'=>$bagian,
    //             'contract'=>$contract,
    //             'contract_end'=>$contract_end
    //         ];
    //     }
    // }
    public function getRowCount()
    {
        return $this->rows;
    }
}
