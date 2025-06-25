<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPermintaanTk extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_permintaan_tk';

   protected $fillable = [
    'no_permintaan',
    'tanggal_pengajuan',
    'status_permintaan',
    'diajukan_oleh_id',
    'created_by',
    'verifikator_by',
    'status_pengajuan',
    'status_pengajuan_realisasi',
];

}
