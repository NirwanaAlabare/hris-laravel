<?php
namespace App\Models;

class DataKehadiranInOutEdited extends \Eloquent
{
    protected $table = 'data_kehadiran_inout_edited';

    // Don't forget to fill this array
    protected $fillable = [
        'uuid',
        'employee_name',
        'enroll_id',
        'nik',
        'tanggal_absen',
        'kode_hari',
        'nama_hari',
        'holiday_name',
        'site_nirwana_id',
        'site_nirwana_name',
        'department_id',
        'department_name',
        'sub_dept_id',
        'sub_dept_name',
        'absen_masuk_kerja',
        'absen_pulang_kerja',
        'mulai_jam_kerja',
        'akhir_jam_kerja',
        'status_absen',
        'mulai_jam_lembur',
        'akhir_jam_lembur',
        'nomor_form_lembur',
        'join_date',
        'tanggal_resign',
        'operator',
        'created_at',
        'updated_at'
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
        'employee_name',
        'enroll_id',
        'nik',
        'tanggal_absen',
        'kode_hari',
        'nama_hari',
        'holiday_name',
        'site_nirwana_id',
        'site_nirwana_name',
        'department_id',
        'department_name',
        'sub_dept_id',
        'sub_dept_name',
        'absen_masuk_kerja',
        'absen_pulang_kerja',
        'mulai_jam_kerja',
        'akhir_jam_kerja',
        'status_absen',
        'mulai_jam_lembur',
        'akhir_jam_lembur',
        'nomor_form_lembur',
        'join_date',
        'tanggal_resign',
        'operator',
        'created_at',
        'updated_at'
    ];

    protected $appends = [];
    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = ['uuid','enroll_id','tanggal_absen'];


}
