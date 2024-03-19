<?php
namespace App\Models;

class DataJadwalKerjaLog extends \Eloquent
{
    protected $table = 'data_jadwal_kerja_log';

    // Don't forget to fill this array
    protected $fillable = [
        'uuid',
        'tanggal_berjalan',
        'kode_hari',
        'nama_hari',
        'enroll_id',
        'nik',
        'employee_name',
        'absen_masuk_kerja',
        'absen_pulang_kerja',
        'mulai_jam_kerja',
        'akhir_jam_kerja',
        'operator',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $guarded = ['uuid'];

    protected $hidden = [''];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'uuid',
        'tanggal_berjalan',
        'kode_hari',
        'nama_hari',
        'enroll_id',
        'nik',
        'employee_name',
        'absen_masuk_kerja',
        'absen_pulang_kerja',
        'mulai_jam_kerja',
        'akhir_jam_kerja',
        'operator',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = [];
    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = ['uuid'];


}
