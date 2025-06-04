<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\EmployeeAtribut;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class RencanaAdjusmentSallaryImport implements ToModel, WithStartRow, WithCalculatedFormulas
{
    /**
    * @param Collection $collection
    */
    private $rows=[];
    public function startRow(): int
    {
        return 6;
    }
    public function model(array $row)
    {
        $enroll_id=$row[0];
        $nik=EmployeeAtribut::where('enroll_id',$row[0])->pluck('nik');
        $employee_name=EmployeeAtribut::where('enroll_id',$row[0])->pluck('employee_name');
        $department=EmployeeAtribut::where('enroll_id',$row[0])->pluck('department_name');
        $bagian=EmployeeAtribut::where('enroll_id',$row[0])->pluck('sub_dept_name');
        $contract=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3])->format('Y-m-d');
        $data_contract = DB::select("select * from employee_contract where enroll_id='$enroll_id' and contract='$contract' order by id desc limit 1");
        $this->rows[]=[
            'enroll_id'=>$enroll_id,
            'nik'=>$nik,
            'employee_name'=>$employee_name,
            'department'=>$department,
            'bagian'=>$bagian,
            'contract'=>$data_contract ? $data_contract[0]->contract : null,
            'contract_end'=>$data_contract ? $data_contract[0]->contract_end : null,
            'penilaian_kinerja'=>$row[9],
            'tanggung_jawab_tugas'=>$row[10],
            'inisiatif_kerja_sama'=>$row[11],
            'akurasi_pekerjaan'=>$row[12],
            'kemauan_kegigihan'=>$row[13],
            'penyampaian_informasi'=>$row[14],
            'atitude_sikap_kerja'=>$row[15],
            'nilai_akhir'=>$row[16],
            'grade_sebelumnya'=>$row[17],
            'adjustment_grade'=>$row[18],
        ];
    }
    public function getRowCount()
    {
        return $this->rows;
    }
}
