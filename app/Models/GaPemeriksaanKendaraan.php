<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GaPemeriksaanKendaraan extends Model
{
    use HasFactory;

    protected $table = 'ga_pemeriksaan_kendaraan';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'kendaraan_id',
        'enroll_id',
        'oddometer',
        'tanggal_pemeriksaan',
    ];


    public function detail()
    {
        return $this->hasMany(GaPemeriksaanKendaraanDet::class, 'pemeriksaan_kendaraan_id');
    }

    public function kendaraan()
    {
        return $this->belongsTo(MasterKendaraan::class, 'kendaraan_id');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeAtribut::class, 'enroll_id', 'enroll_id');
    }

}
