<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanKedisiplinanFaktor extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_kedisiplinan_faktor';

    protected $fillable = ['faktor', 'uraian'];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanKedisiplinanKaryawan::class);
    }

}
