<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GaPemeriksaanKendaraanDet extends Model
{
    use HasFactory;

    protected $table = 'ga_pemeriksaan_kendaraan_det';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'pemeriksaan_kendaraan_id',
        'komponen_id',
        'catatan',
        'foto_path',
        'status',
        'original_name',
    ];

    public function header()
    {
        return $this->belongsTo(GaPemeriksaanKendaraan::class, 'pemeriksaan_kendaraan_id');
    }
}
