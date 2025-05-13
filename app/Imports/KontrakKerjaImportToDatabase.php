<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\EmployeeAtribut;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Carbon\Carbon;

class KontrakKerjaImportToDatabase implements ToModel, WithStartRow, WithCalculatedFormulas
{
    /**
    * @param Collection $collection
    */
    public function startRow(): int
    {
        return 4;
    }
    public function model(array $row){
        $status=EmployeeAtribut::where('enroll_id',$row[2])->get();
        $enroll_id=$row[2];
        $contract=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[9])->format('Y-m-d');
        $contract_end=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[10])->format('Y-m-d');
        $employee_contract=DB::select("select*from employee_contract where enroll_id = '$enroll_id' AND contract = '$contract' AND contract_end = '$contract_end'");
        $timestamp = Carbon::now();
        if($employee_contract){
            DB::delete("delete from employee_contract where enroll_id = '$enroll_id' AND contract = '$contract' AND contract_end = '$contract_end'");
        }
        DB::insert("insert into employee_contract (id, enroll_id, contract, contract_end, created_at, updated_at) VALUES ('','$enroll_id','$contract','$contract_end','$timestamp','$timestamp')");

    }
    // public function model(array $row){
    //     $status=EmployeeAtribut::where('enroll_id',$row[3])->get();
    //     $enroll_id=$row[3];
    //     $employee_contract=DB::select("select*from employee_contract where enroll_id = '$enroll_id'");
    //     $timestamp = Carbon::now();
    //     if($employee_contract){
    //         DB::delete("delete from employee_contract where enroll_id = '$enroll_id'");
    //     }
    //     for($j=15;$j<=106;$j+=2){
    //         if($row[$j]==null||$row[$j]=='-'||preg_match("/[a-z]/i", $row[$j])||strpos($row[$j], ' ') !== false){
    //             continue;
    //         }
    //         $contract=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[$j])->format('Y-m-d');
    //         $contract_end=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[$j+1])->format('Y-m-d');
    //         DB::insert("insert into employee_contract (id, enroll_id, contract, contract_end, created_at, updated_at) VALUES ('','$enroll_id','$contract','$contract_end','$timestamp','$timestamp')");
    //     }
    // }
}
