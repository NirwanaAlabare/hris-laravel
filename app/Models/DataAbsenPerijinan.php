<?php
namespace App\Models;

class DataAbsenPerijinan extends \Eloquent
{
    protected $table = 'data_absen_perijinan';

    // Don't forget to fill this array
    protected $fillable = [
        'uuid',
        'uuid_master',
        'tanggal_perizinan',
        'nomor_form_perizinan',
        'enroll_id',
        'didelegasikan_enroll_id',
        'nik',
        'employee_name',
        'kode_absen_ijin',
        'absen_alasan',
        'tanggal_mulai_ijin',
        'tanggal_akhir_ijin',
        'time_mulai_ijin',
        'time_akhir_ijin',
        'total_time_ijin',
        'operator',
        'created_at',
        'updated_at',
        'deleted_at',
        'is_verifikasi',
        'is_verifikasi_pengajuan_admin',
        'diajukan_oleh',
        'keterangan_reject',
        'verifikasi_by',
    ];
    protected $guarded = [''];

    protected $hidden = [''];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'uuid',
        'uuid_master',
        'tanggal_perizinan',
        'nomor_form_perizinan',
        'enroll_id',
        'didelegasikan_enroll_id',
        'nik',
        'employee_name',
        'kode_absen_ijin',
        'absen_alasan',
        'tanggal_mulai_ijin',
        'tanggal_akhir_ijin',
        'time_mulai_ijin',
        'time_akhir_ijin',
        'total_time_ijin',
        'operator',
        'created_at',
        'updated_at',
        'deleted_at',
        'is_verifikasi_pengajuan_admin',
        'diajukan_oleh',
        'keterangan_reject',
    ];

    protected $appends = [];
    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = [];


}
