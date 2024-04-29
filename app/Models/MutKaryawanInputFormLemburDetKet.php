<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutKaryawanInputFormLemburDetKet extends Model
{
    use HasFactory;
    protected $table = 'mut_karyawan_input_form_lembur_det_ket';
    protected $fillable = [
        'id_det_ket',
        'no_form',
        'ket',
        'created_by',
        'created_at',
        'updated_at'
    ];
}
