<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntertainRealisasiPengajuan extends Model
{
    use HasFactory;

    protected $table = 'entertain_realisasi_pengajuan';

    protected $fillable = [
        'pengajuan_id',
        'keterangan',
        'jumlah',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(EntertainPengajuanTamu::class, 'pengajuan_id');
    }
}
