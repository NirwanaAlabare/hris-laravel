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
     public $importedEnrollIds = [];
    public function startRow(): int
    {
        return 4;
    }
    public function model(array $row){
        $status=EmployeeAtribut::where('enroll_id',$row[2])->get();
        $enroll_id=$row[2];
        $contract=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[9])->format('Y-m-d');
        $contract_end=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[10])->format('Y-m-d');
        $employee_contract=DB::select("select*from employee_contract where enroll_id = '$enroll_id' AND contract = '$contract'");
        $timestamp = Carbon::now();
        if($employee_contract){
            DB::delete("delete from employee_contract where enroll_id = '$enroll_id' AND contract = '$contract'");
        }
        DB::insert("insert into employee_contract (id, enroll_id, contract, contract_end, created_at, updated_at) VALUES ('','$enroll_id','$contract','$contract_end','$timestamp','$timestamp')");
        $this->importedEnrollIds[] = $enroll_id;
    }
}
