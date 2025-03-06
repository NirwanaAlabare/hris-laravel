<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntertainPengajuanPendamping extends Model
{
    use HasFactory;

    protected $table = 'entertain_pengajuan_pendamping';

    protected $fillable = [
        'pengajuan_id',
        'enroll_id',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(EntertainPengajuanTamu::class, 'pengajuan_id');
    }
}
