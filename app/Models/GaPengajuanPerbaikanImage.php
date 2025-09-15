<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaPengajuanPerbaikanImage extends Model
{
    protected $table = 'ga_pengajuan_perbaikan_images';
    protected $fillable = ['pengajuan_id', 'path'];

    public function pengajuan()
    {
        return $this->belongsTo(GaPengajuanPerbaikanKendaraan::class, 'pengajuan_id');
    }
}
