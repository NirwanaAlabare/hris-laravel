<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutKaryawanInputFormLembur extends Model
{
    use HasFactory;
    
    protected $table = 'mut_karyawan_input_form_lembur';
    protected $fillable = [
        'id',
        'tgl_filter',
        'tgl_lembur',
        'no_form',
        'status',
        'line',
        'approve',
        'created_by',
        'created_at',
        'updated_at'
    ];
    public function detail(){
        return $this->hasMany(MutKaryawanInputFormLemburDet::class, 'no_form','no_form');
    }
}
