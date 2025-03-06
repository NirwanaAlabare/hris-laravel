<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntertainPengajuanKeterangan extends Model
{
    use HasFactory;

    protected $table = 'entertain_pengajuan_keterangan';

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
