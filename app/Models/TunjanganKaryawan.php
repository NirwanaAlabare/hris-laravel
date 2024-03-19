<?php
namespace App\Models;

class TunjanganKaryawan extends \Eloquent
{
    protected $table = 'tunjangan_karyawan';

    protected $fillable = [
        'kode_tunjangan',
        'periode_payroll',
        'periode_tahun',
        'periode_bulan',
        'enroll_id',
        'nik',
        'employee_name',
        'nama_tunjangan',
        'nilai_rupiah',
        'operator',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $guarded = [''];

    protected $hidden = [''];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'kode_tunjangan',
        'periode_payroll',
        'periode_tahun',
        'periode_bulan',
        'enroll_id',
        'nik',
        'employee_name',
        'nama_tunjangan',
        'nilai_rupiah',
        'operator',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [];
    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = ['kode_tunjangan'];


}
