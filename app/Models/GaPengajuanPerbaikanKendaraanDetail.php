<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaPengajuanPerbaikanKendaraanDetail extends Model
{
    protected $table = 'ga_pengajuan_perbaikan_kendaraan_detail';

    protected $fillable = [
        'pengajuan_id',
        'komponen_pemeriksaan_kendaraan_input_id', // ganti field
        'odometer',
        'penyedia_jasa',
        'keterangan',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(GaPengajuanPerbaikanKendaraan::class, 'pengajuan_id');
    }

      public function detail_input_list()
    {
        return $this->belongsTo(KomponenPemeriksaanKendaraanInput::class, 'komponen_pemeriksaan_kendaraan_input_id','id');
    }
}
