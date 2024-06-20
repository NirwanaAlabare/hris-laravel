<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutKaryawanInputNonSewingFormLemburDet extends Model
{
    use HasFactory;
    protected $table = 'mut_karyawan_input_non_sewing_form_lembur_det';
    protected $fillable = [
        'id_det',
        'no_form',
        'enroll_id',
        'jam_lembur_awal_rencana',
        'jam_lembur_akhir_rencana',
        'jam_lembur_istirahat',
        'status',
        'created_by',
        'created_at',
        'updated_at'
    ];
    public function form(){
        return $this->belongsTo(MutKaryawanInputNonSewingFormLembur::class, 'no_form', 'no_form');
    }
    public function employee(){
        return $this->hasOne(EmployeeAtribut::class,'enroll_id','enroll_id');
    }
    public function absen(){
        return $this->hasMany(MasterDataAbsenKehadiran::class, 'enroll_id','enroll_id');
    }
    public function keterangan(){
        return $this->belongsTo(MutKaryawanInputNonSewingFormLembur::class, 'no_form', 'no_form');
    }

}
