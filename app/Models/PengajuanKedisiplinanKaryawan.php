<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanKedisiplinanKaryawan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_kedisiplinan_karyawan';

   protected $fillable = [
    'tanggal_pengajuan',
    'tindakan_pendisiplinan',
    'enroll_id_diajukan_oleh',
    'enroll_id_karyawan_bermasalah',
    'pelanggaran',
    'sumber_permasalahan',
    'status_pengajuan',
    'created_by',
    'verifikator_by',
];

    protected $casts = [
        'uraian_tugas' => 'array',
    ];

}
