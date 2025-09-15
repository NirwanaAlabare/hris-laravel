<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaPengajuanPerbaikanKendaraan extends Model
{
    use HasFactory;

    protected $table = 'ga_pengajuan_perbaikan_kendaraan';

    protected $fillable = [
        'kendaraan_id',
        'id_pemerliharaan',
        'enroll_id',
        'tanggal_pengajuan',
        'status_pengajuan'
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeAtribut::class, 'enroll_id', 'enroll_id');
    }

    public function pemeliharaan()
    {
        return $this->belongsTo(GaPemeriksaanKendaraan::class, 'id_pemerliharaan', 'id');
    }

    public function details()
    {
        return $this->hasMany(GaPengajuanPerbaikanKendaraanDetail::class, 'pengajuan_id');
    }

    public function images()
    {
        return $this->hasMany(GaPengajuanPerbaikanImage::class, 'pengajuan_id');
    }

}
