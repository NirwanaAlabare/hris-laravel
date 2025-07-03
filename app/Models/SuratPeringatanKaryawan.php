<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratPeringatanKaryawan extends Model
{
    use HasFactory;

    protected $table = 'surat_peringatan_karyawan';

   protected $fillable = [
    'enroll_id',
    'kode_pasal',
    'tanggal_mulai',
    'tanggal_sampai',
    'operator',
    'surat_peringatan',
    'alasan_pelanggaran',
    'no_form',
];

}
