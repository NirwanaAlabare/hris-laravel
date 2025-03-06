<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanDokumenLegal extends Model
{
    use HasFactory;


    protected $table = 'pengajuan_dokumen_legal';


    protected $fillable = [
        'jenis_dokumen',
        'kode_dokumen',
        'nama_dokumen',
        'tanggal_berlaku',
        'tanggal_kadaluarsa',
        'penanggung_jawab',
        'penanggung_jawab_email',
        'revisi_ke',
        'keterangan',
        'dokumen_url',
    ];

    protected $casts = [
        'id',
        'jenis_dokumen',
        'kode_dokumen',
        'nama_dokumen',
        'tanggal_berlaku',
        'tanggal_kadaluarsa',
        'penanggung_jawab',
        'penanggung_jawab_email',
        'revisi_ke',
        'keterangan',
        'dokumen_url',
    ];
}
