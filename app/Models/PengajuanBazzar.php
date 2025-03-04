<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanBazzar extends Model
{
    use HasFactory;


    protected $table = 'pengajuan_bazzar';


    protected $fillable = [
        'id',
        'enroll_id',
        'jumlah',
        'status',
        'operator',
        'created_at',
        'updated_at',
        'diajukan_oleh',
        'is_print',
        'department_name',
        'department_id',
        'sub_dept_id',
        'sub_dept_name',
    ];

    protected $casts = [
        'id',
        'enroll_id',
        'jumlah',
        'status',
        'operator',
        'created_at',
        'updated_at',
        'diajukan_oleh',
        'is_print',
        'department_name',
        'department_id',
        'sub_dept_id',
        'sub_dept_name',
    ];

    public function employee(){
        return $this->belongsTo(EmployeeAtribut::class,'enroll_id','enroll_id');
    }
}
