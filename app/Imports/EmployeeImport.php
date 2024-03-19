<?php

namespace App\Imports;

use App\Models\EmployeeAtribut;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\DepartmentAll;

class EmployeeImport implements ToModel, WithStartRow
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
        $employee=EmployeeAtribut::where('enroll_id',$row[1])->delete();
        $tanggal_mulai_kontrak=null;
        if($row[62]==''){
            $tanggal_mulai_kontrak=null;
        }else{
            $tanggal_mulai_kontrak=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[62]);
        }
        $tanggal_akhir_kontrak=null;
        if($row[63]==''){
            $tanggal_akhir_kontrak=null;
        }else{
            $tanggal_akhir_kontrak=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[63]);
        }
        $tanggal_lahir=null;
        if($row[16]==''){
            $tanggal_lahir=null;
        }else{
            $tanggal_lahir=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[16]);
        }
        $join_date=null;
        if($row[11]==''){
            $join_date=null;
        }else{
            $join_date=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[11]);
        }
        $tanggal_resign=null;
        if($row[12]==''){
            $tanggal_resign=null;
        }else{
            $tanggal_resign=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[12]);
        }
        $tanggal_bpjs_ketenagakerjaan=null;
        if($row[42]==''){
            $tanggal_bpjs_ketenagakerjaan=null;
        }else{
            $tanggal_bpjs_ketenagakerjaan=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[42]);
        }
        $tanggal_bpjs_kesehatan=null;
        if($row[45]==''){
            $tanggal_bpjs_kesehatan=null;
        }else{
            $tanggal_bpjs_kesehatan=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[45]);
        }
        $tanggal_vaccine1=null;
        if($row[52]==''){
            $tanggal_vaccine1=null;
        }else{
            $tanggal_vaccine1=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[52]);
        }
        $tanggal_vaccine2=null;
        if($row[54]==''){
            $tanggal_vaccine2=null;
        }else{
            $tanggal_vaccine2=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[54]);
        }
        $tanggal_vaccine3=null;
        if($row[56]==''){
            $tanggal_vaccine3=null;
        }else{
            $tanggal_vaccine3=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[56]);
        }
        $tanggal_expire_sim=null;
        if($row[60]==''){
            $tanggal_expire_sim=null;
        }else{
            $tanggal_expire_sim=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[60]);
        }
        $site_nirwana_id='';
        $site_nirwana_name='';
        $department_id='';
        $sub_dept_id='';
        $department=DepartmentAll::where('sub_dept_name',$row[9])->where('site_nirwana_id','NAG')->get();
        foreach($department as $dept){
            $department_id=$dept->department_id;
            $sub_dept_id=$dept->sub_dept_id;
        }
        return new EmployeeAtribut([
            'employee_id'=>rand(),
            'enroll_id'=>$row[1],
            'nik'=>$row[2],
            'employee_name'=>$row[3],
            'jenis_kelamin' => $row[4],
            'status_jabatan'=>$row[5],
            'site_nirwana_id'=>'NAG',
            'site_nirwana_name'=>'PT. NIRWANA ALABARE GARMENT',
            'department_id'=>$department_id,
            'sub_dept_id'=>$sub_dept_id,
            'department_name'=>$row[7],
            'sub_dept_name'=>$row[9],
            'status_aktif'=>$row[10],
            'join_date'=>$join_date,
            'tanggal_resign'=>$tanggal_resign,
            'status_kontrak_tetap'=>$row[13],
            'status_staff'=>$row[14],
            'tempat_lahir' => $row[15],
            'tanggal_lahir' =>$tanggal_lahir,
            'agama'=>$row[17],
            'ibu_kandung'=>$row[18],
            'status_kawin'=>$row[19],
            'ptkp'=>$row[20],
            'npwp'=>$row[21],
            'nomor_ktp'=>$row[22],
            'nomor_kk'=>$row[23],
            'golongan_darah'=>$row[24],
            'nomor_tlpn'=>$row[25],
            'email'=>$row[26],
            'pendidikan_terakhir'=>$row[27],
            'jurusan_pendidikan'=>$row[28],
            'nama_bank'=>$row[29],
            'nomor_rekening_bank'=>$row[30],
            'alamat_rumah'=>$row[31],
            'propinsi'=>$row[32],
            'kota_kab'=>$row[33],
            'kecamatan'=>$row[34],
            'kelurahan_desa'=>$row[35],
            'alamat_sementara'=>$row[36],
            'tunjangan'=>$row[37],
            'kode_grade'=>$row[38],
            'referensi'=>$row[39],
            'employee_name_atasan'=>$row[40],
            'status_aktif_bpjs_tk'=>$row[41],
            'tanggal_bpjs_ketenagakerjaan'=>$tanggal_bpjs_ketenagakerjaan,
            'nomor_bpjs_ketenagakerjaan'=>$row[43],
            'status_aktif_bpjs_ks'=>$row[44],
            'tanggal_bpjs_kesehatan'=>$tanggal_bpjs_kesehatan,
            'nomor_bpjs_kesehatan'=>$row[46],
            'pengalaman_bekerja'=>$row[47],
            'nama_kerabat'=>$row[48],
            'nomor_tlpn_kerabat'=>$row[49],
            'hubungan_kerabat'=>$row[50],
            'alamat_kerabat'=>$row[51],
            'tanggal_vaccine1'=>$tanggal_vaccine1,
            'nama_vaksin1'=>$row[53],
            'tanggal_vaccine2'=>$tanggal_vaccine2,
            'nama_vaksin2'=>$row[55],
            'tanggal_vaccine3'=>$tanggal_vaccine3,
            'nama_vaksin3'=>$row[57],
            'golongan_sim'=>$row[58],
            'nomor_sim'=>$row[59],
            'tanggal_expire_sim'=>$tanggal_expire_sim,
            'catatan'=>$row[61],
            'tanggal_mulai_kontrak'=>$tanggal_mulai_kontrak,
            'tanggal_akhir_kontrak'=>$tanggal_akhir_kontrak,
            'catatan_kontrak'=>$row[64],
        ]);
    }
}
