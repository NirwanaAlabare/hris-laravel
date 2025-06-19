<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPermintaanTk extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_permintaan_tk';

   protected $fillable = [
    'tanggal_pengajuan',
    'status_permintaan',
    'diajukan_oleh_id',
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
    'created_by',
    'verifikator_by',
    'status_pengajuan',
    'pend_minimal',
    'status_pengajuan_realisasi',
];

    protected $casts = [
        'uraian_tugas' => 'array',
    ];

}
