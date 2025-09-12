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
        'enroll_id',
        'tanggal_pengajuan',
        'status_pengajuan'
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeAtribut::class, 'enroll_id', 'enroll_id');
    }

    public function details()
    {
        return $this->hasMany(GaPengajuanPerbaikanKendaraanDetail::class, 'pengajuan_id');
    }
}
