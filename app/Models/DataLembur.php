<?php
namespace App\Models;

class DataLembur extends \Eloquent
{

    protected $table = 'data_lembur';

    // Don't forget to fill this array
    protected $fillable = [
        'uuid',
        'uuid_master',
        'tanggal_berjalan',
        'tanggal_absen',
        'shift_work_id',
        'kode_hari',
        'nama_hari',
        'time_table_name',
        'mulai_jam_kerja',
        'akhir_jam_kerja',
        'absen_masuk_kerja',
        'absen_pulang_kerja',
        'jumlah_jam_lembur',
        'jumlah_jam_istirahat',
        'mulai_jam_lembur',
        'akhir_jam_lembur',
        'enroll_id',
        'nik',
        'employee_id',
        'employee_name',
        'site_nirwana_id',
        'site_nirwana_name',
        'department_id',
        'department_name',
        'sub_dept_id',
        'sub_dept_name',
        'status_lembur',
        'kelebihan_jam_kerja_l1',
        'kelebihan_jam_kerja_l2',
        'kelebihan_jam_kerja_l3',
        'kelebihan_jam_kerja_l4',
        'operator',
        'catatan',
        'nomor_form_lembur',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $guarded = [];

    protected $hidden = [];

    protected $appends = [];

    protected $casts = [
        'uuid',
        'uuid_master',
        'tanggal_berjalan',
        'tanggal_absen',
        'shift_work_id',
        'kode_hari',
        'nama_hari',
        'time_table_name',
        'mulai_jam_kerja',
        'akhir_jam_kerja',
        'absen_masuk_kerja',
        'absen_pulang_kerja',
        'jumlah_jam_lembur',
        'jumlah_jam_istirahat',
        'mulai_jam_lembur',
        'akhir_jam_lembur',
        'enroll_id',
        'nik',
        'employee_id',
        'employee_name',
        'site_nirwana_id',
        'site_nirwana_name',
        'department_id',
        'department_name',
        'sub_dept_id',
        'sub_dept_name',
        'status_lembur',
        'kelebihan_jam_kerja_l1',
        'kelebihan_jam_kerja_l2',
        'kelebihan_jam_kerja_l3',
        'kelebihan_jam_kerja_l4',
        'operator',
        'catatan',
        'nomor_form_lembur',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = [
        'uuid','tanggal_lembur','nomor_form_lembur'
    ];

}
