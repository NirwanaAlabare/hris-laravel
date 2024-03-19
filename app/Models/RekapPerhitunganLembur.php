<?php
namespace App\Models;

class RekapPerhitunganLembur extends \Eloquent
{
    protected $table = 'rekap_perhitungan_lembur';

    protected $fillable = [
        'uuid',
        'periode_umk',
        'tanggal_berjalan',
        'kode_hari',
        'nama_hari',
        'kerjalibur',
        'holiday_name',
        'nomor_form_lembur',
        'enroll_id',
        'nik',
        'employee_name',
        'site_nirwana_id',
        'site_nirwana_name',
        'department_id',
        'department_name',
        'sub_dept_id',
        'sub_dept_name',
        'posisi_name',
        'mulai_jam_kerja',
        'akhir_jam_kerja',
        'jumlah_jam_kerja',
        'absen_masuk_kerja',
        'absen_pulang_kerja',
        'jam_efektif_kerja',
        'mulai_jam_lembur',
        'akhir_jam_lembur',
        'final_mulai_jam_lembur',
        'final_selesai_jam_lembur',
        'final_total_jam_lembur',
        'final_jam_istirahat_lembur',
        'final_total_menit_lembur',
        'final_jam_lembur_roundown',
        'final_menit_lembur_roundown',
        'lembur_1',
        'lembur_2',
        'lembur_3',
        'lembur_4',
        'total_lembur_1234',
        'salary',
        'lembur1_rupiah',
        'lembur2_rupiah',
        'lembur3_rupiah',
        'lembur4_rupiah',
        'total_lembur_rupiah',
        'operator',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $guarded = ['tanggal_berjalan', 'enroll_id', 'nomor_form_lembur'];

    protected $hidden = [''];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'uuid',
        'periode_umk',
        'tanggal_berjalan',
        'kode_hari',
        'nama_hari',
        'kerjalibur',
        'holiday_name',
        'nomor_form_lembur',
        'enroll_id',
        'nik',
        'employee_name',
        'site_nirwana_id',
        'site_nirwana_name',
        'department_id',
        'department_name',
        'sub_dept_id',
        'sub_dept_name',
        'posisi_name',
        'mulai_jam_kerja',
        'akhir_jam_kerja',
        'jumlah_jam_kerja',
        'absen_masuk_kerja',
        'absen_pulang_kerja',
        'jam_efektif_kerja',
        'mulai_jam_lembur',
        'akhir_jam_lembur',
        'final_mulai_jam_lembur',
        'final_selesai_jam_lembur',
        'final_total_jam_lembur',
        'final_jam_istirahat_lembur',
        'final_total_menit_lembur',
        'final_jam_lembur_roundown',
        'final_menit_lembur_roundown',
        'lembur_1',
        'lembur_2',
        'lembur_3',
        'lembur_4',
        'total_lembur_1234',
        'salary',
        'lembur1_rupiah',
        'lembur2_rupiah',
        'lembur3_rupiah',
        'lembur4_rupiah',
        'total_lembur_rupiah',
        'operator',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [];
    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = ['tanggal_berjalan', 'enroll_id', 'nomor_form_lembur'];


}
