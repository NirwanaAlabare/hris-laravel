<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\EmployeeAtribut;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Carbon\Carbon;

class PenilaianKinerjaStaffImport implements ToModel, WithStartRow, WithCalculatedFormulas
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
        $nik=EmployeeAtribut::where('enroll_id',$row[0])->pluck('nik');
        $employee_name=EmployeeAtribut::where('enroll_id',$row[0])->pluck('employee_name');
        $department=EmployeeAtribut::where('enroll_id',$row[0])->pluck('department_name');
        $bagian=EmployeeAtribut::where('enroll_id',$row[0])->pluck('sub_dept_name');
        $contract=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[14])->format('Y-m-d');
        $contract_end=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[15])->format('Y-m-d');
        $this->rows[]=[
            'nik'=>$nik,
            'employee_name'=>$employee_name,
            'department'=>$department,
            'bagian'=>$bagian,
            'contract'=>$contract,
            'contract_end'=>$contract_end,
            'penilaian_kinerja'=>$row[4],
            'tanggung_jawab_tugas'=>$row[5],
            'inisiatif_kerja_sama'=>$row[6],
            'akurasi_pekerjaan'=>$row[7],
            'kemauan_kegigihan'=>$row[8],
            'penyampaian_informasi'=>$row[9],
            'atitude_sikap_kerja'=>$row[10],
            'rata_rata_kompetensi'=>$row[11],
            'pengurang'=>$row[12] ? $row[12] : 0,
            'nilai_akhir'=>$row[13],
        ];
    }
    public function getRowCount()
    {
        return $this->rows;
    }
}
