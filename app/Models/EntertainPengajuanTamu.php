<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntertainPengajuanTamu extends Model
{
    use HasFactory;

    protected $table = 'entertain_pengajuan_tamu';

    protected $fillable = [
        'enroll_id',
        'department_id',
        'sub_dept_id',
        'tanggal_kedatangan_tamu',
        'tamu_instansi',
        'nama_tamu',
        'jabatan_tamu',
        'qty_tamu',
        'keperluan',
        'created_by',
        'realisasi_by',
        'is_realisasi',
        'jumlah_pengajuan',
        'jumlah_realisasi',
        'jumlah_sisa',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeAtribut::class, 'enroll_id', 'enroll_id');
    }
    public function pendamping()
    {
        return $this->hasMany(EntertainPengajuanPendamping::class, 'pengajuan_id');
    }

    public function keterangan()
    {
        return $this->hasMany(EntertainPengajuanKeterangan::class, 'pengajuan_id');
    }
}
