<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanCoachingKaryawan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_coaching_karyawan';

   protected $fillable = [
    'tanggal_pengajuan_coaching',
    'nomor_form_coaching',
    'enroll_id_karyawan_coaching',
    'deskripsi_coaching',
    'created_by',
    ];

}
