<?php

namespace App\Imports;

use App\Models\EmployeeAtribut;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\DepartmentAll;
use Carbon\Carbon;

class EmployeeImport implements ToModel, WithStartRow, WithCalculatedFormulas
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function startRow(): int
    {
        return 6;
    }
    public function model(array $row)
    {
        $arrayDeptName=DepartmentAll::where('site_nirwana_id','NAG')->pluck('department_name')->toArray();
        $arraySubDeptName=DepartmentAll::where('site_nirwana_id','NAG')->pluck('sub_dept_name')->toArray();
        $arrayEnrollId=EmployeeAtribut::pluck('enroll_id')->toArray();
        $dataArray=[];
        $department=DepartmentAll::where('status','AKTIF')->where('site_nirwana_id','NAG')->where('department_name',$row[9])->where('sub_dept_name',$row[11])->count();
        $employee=EmployeeAtribut::where('enroll_id',$row[1])->count();
        if($department==0 && ($employee==1 || $employee==0)){
            $status_department='red';
        }else{
            if($employee==0){
                $status_department='lightblue';
            }else{
                $status_department='white';
            }
        }
        $site_nirwana_id=preg_replace('/[0-9]+/', '', $row[2]);
        $department_id='';
        $sub_dept_id='';
        if(count(DepartmentAll::where('status','AKTIF')->where('site_nirwana_id','NAG')->where('department_name',$row[9])->where('sub_dept_name',$row[11])->get())>0){
            $department_id=DepartmentAll::where('status','AKTIF')->where('site_nirwana_id','NAG')->where('department_name',$row[9])->where('sub_dept_name',$row[11])->pluck('department_id')[0];
            $sub_dept_id=DepartmentAll::where('status','AKTIF')->where('site_nirwana_id','NAG')->where('department_name',$row[9])->where('sub_dept_name',$row[11])->pluck('sub_dept_id')[0];
        }
        if($row[15]==''|| $row[15]=='-'){
            $join_date=null;
        }else{
            $join_date=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[15])->format('Y-m-d');
        }
        if($row[16]==''|| $row[16]=='-'){
            $tanggal_resign=null;
        }else{
            $tanggal_resign=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[16])->format('Y-m-d');
        }
        if($row[18]==''|| $row[18]=='-'){
            $tanggal_lahir=null;
        }else{
            $tanggal_lahir=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[18])->format('Y-m-d');
        }
        if($row[44]=='' || $row[44]=='-'){
            $tanggal_bpjs_tk=null;
        }else{
            $tanggal_bpjs_tk=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[44])->format('Y-m-d');
        }
        if($row[47]=='' || $row[47]=='-'){
            $tanggal_bpjs_ks=null;
        }else{
            $tanggal_bpjs_ks=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[47])->format('Y-m-d');
        }
        if($row[54]=='' || $row[54]=='-'){
            $tanggal_vaccine1=null;
        }else{
            $tanggal_vaccine1=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[54])->format('Y-m-d');
        }
        if($row[56]=='' || $row[56]=='-'){
            $tanggal_vaccine2=null;
        }else{
            $tanggal_vaccine2=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[56])->format('Y-m-d');
        }
        if($row[62]=='' || $row[62]=='-'){
            $tanggal_expire_sim=null;
        }else{
            $tanggal_expire_sim=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[62])->format('Y-m-d');
        }
        if($row[64]=='' || $row[64]=='-'){
            $tanggal_mulai_kontrak=null;
        }else{
            $tanggal_mulai_kontrak=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[64])->format('Y-m-d');
        }
        if($row[65]=='' || $row[65]=='-'){
            $tanggal_akhir_kontrak=null;
        }else{
            $tanggal_akhir_kontrak=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[65])->format('Y-m-d');
        }
        $enroll_id=$row[1];
        $kontrak_awal=DB::select("select max(contract) contract from employee_contract where enroll_id='$enroll_id'")[0]->contract;
        $kontrak_akhir=DB::select("select max(contract_end) contract_end from employee_contract where enroll_id='$enroll_id'")[0]->contract_end;
        $timestamp = Carbon::now();
        if($kontrak_awal){
            $tanggal_mulai_kontrak=$kontrak_awal;
            $tanggal_akhir_kontrak=$kontrak_akhir;
        }else{
            if($row[64]!='' && $row[65]!=''){
                DB::insert("insert into employee_contract (id, enroll_id, contract, contract_end, created_at, updated_at) VALUES ('','$enroll_id','$tanggal_mulai_kontrak','$tanggal_akhir_kontrak','$timestamp','$timestamp')");
            }
        }
        $dataArray=[
            'employee_id'=>time().$row[1],
            'hamlet'=>$status_department,
            'enroll_id'=>$row[1],
            'nik'=>$row[2],
            'employee_name'=>$row[3],
            'jenis_kelamin'=>$row[4],
            'status_kontrak_tetap'=>$row[5],
            'status_staff'=>$row[6],
            'status_jabatan'=>$row[7],
            'department_id'=>$department_id,
            'department_name'=>$row[9],
            'sub_dept_id'=>$sub_dept_id,
            'sub_dept_name'=>$row[11],
            'sewing_nonsewing'=>$row[12],
            'direct_indirect'=>$row[13],
            'status_aktif'=>$row[14],
            'join_date'=>$join_date,
            'tanggal_resign'=>$tanggal_resign,
            'tempat_lahir'=>$row[17],
            'tanggal_lahir'=>$tanggal_lahir,
            'agama'=>$row[19],
            'ibu_kandung'=>$row[20],
            'status_kawin'=>$row[21],
            'ptkp'=>$row[22],
            'npwp'=>$row[23],
            'nomor_ktp'=>$row[24],
            'nomor_kk'=>$row[25],
            'golongan_darah'=>$row[26],
            'nomor_tlpn'=>$row[27],
            'email'=>$row[28],
            'pendidikan_terakhir'=>$row[29],
            'jurusan_pendidikan'=>$row[30],
            'nama_bank'=>$row[31],
            'nomor_rekening_bank'=>$row[32],
            'alamat_rumah'=>$row[33],
            'propinsi'=>$row[34],
            'kota_kab'=>$row[35],
            'kecamatan'=>$row[36],
            'kelurahan_desa'=>$row[37],
            'alamat_sementara'=>$row[38],
            'tunjangan'=>$row[39],
            'kode_grade'=>$row[40],
            'referensi'=>$row[41],
            'employee_name_atasan'=>$row[42],
            'status_aktif_bpjs_tk'=>$row[43],
            'tanggal_bpjs_ketenagakerjaan'=>$tanggal_bpjs_tk,
            'nomor_bpjs_ketenagakerjaan'=>$row[45],
            'status_aktif_bpjs_ks'=>$row[46],
            'tanggal_bpjs_kesehatan'=>$tanggal_bpjs_ks,
            'nomor_bpjs_kesehatan'=>$row[48],
            'pengalaman_bekerja'=>$row[49],
            'nama_kerabat'=>$row[50],
            'nomor_tlpn_kerabat'=>$row[51],
            'hubungan_kerabat'=>$row[52],
            'alamat_kerabat'=>$row[53],
            'tanggal_vaccine1'=>$tanggal_vaccine1,
            'nama_vaksin1'=>$row[55],
            'tanggal_vaccine2'=>$tanggal_vaccine2,
            'nama_vaksin2'=>$row[57],
            // 'tanggal_vaccine3'=>$tanggal_vaccine3,
            'nama_vaksin3'=>$row[59],
            'golongan_sim'=>$row[60],
            'nomor_sim'=>$row[61],
            'tanggal_expire_sim'=>$tanggal_expire_sim,
            'catatan'=>$row[63],
            'tanggal_mulai_kontrak'=>$tanggal_mulai_kontrak,
            'tanggal_akhir_kontrak'=>$tanggal_akhir_kontrak,
            'catatan_kontrak'=>$row[66],
            'no_surat'=>$row[67],
            'sebab_resign'=>$row[68]
        ];
        if($dataArray['enroll_id']=='' || $dataArray['hamlet']=='red'){

        }else if($dataArray['hamlet']=='lightblue'){
            array_shift($dataArray);
            EmployeeAtribut::create($dataArray);
        }else if($dataArray['hamlet']=='white'){
            array_shift($dataArray);
            EmployeeAtribut::where('enroll_id',$dataArray['enroll_id'])->update($dataArray);
        }
    }
}
