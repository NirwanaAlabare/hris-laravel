<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutKaryawanInputNonSewingFormLembur extends Model
{
    use HasFactory;
    protected $table = 'mut_karyawan_input_non_sewing_form_lembur';
    protected $fillable = [
        'id',
        'tgl_filter',
        'tgl_lembur',
        'no_form',
        'ket',
        'dept',
        'approve',
        'approve_by',
        'approve_at',
        'created_by',
        'created_at',
        'updated_at'
    ];
    public function detail(){
        return $this->hasMany(MutKaryawanInputNonSewingFormLemburDet::class, 'no_form','no_form');
    }
}
