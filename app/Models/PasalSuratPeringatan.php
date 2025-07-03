<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasalSuratPeringatan extends Model
{
    use HasFactory;

    protected $table = 'pasal_surat_peringatan';

   protected $fillable = [
    'kode_pasal',
    'sp',
    'pasal',
    'desc_surat_peringatan',
    'deskripsi'
];

}
