<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubPengajuanPermintaanTk extends Model
{
    use HasFactory;

    protected $table = 'sub_pengajuan_permintaan_tk';

   protected $fillable = [
    'no_permintaan',
    'department_kode',
    'bagian_kode',
    'tanggal_kebutuhan',
    'jumlah_kebutuhan',
    'rencana_jabatan',
    'rencana_jurusan',
    'pengalaman_kerja',
    'waktu_pengalaman',
    'besaran_gaji',
    'fasilitas',
    'jangka_waktu_kontrak',
    'keterangan_tambahan',
    'uraian_tugas',
    'pend_minimal',
    'no_permintaan_id',
    ];

    protected $casts = [
        'uraian_tugas' => 'array',
    ];

}
