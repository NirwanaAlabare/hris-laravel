<?php
namespace App\Models;

class RekapPerhitunganIKS extends \Eloquent
{
    protected $table = 'rekap_perhitungan_iks';

    protected $fillable = [
        'uuid',
        'periode_umk',
        'nomor_form_perizinan',
        'tanggal_berjalan',
        'enroll_id',
        'employee_name',
        'sub_dept_name',
        'time_mulai_ijin',
        'time_akhir_ijin',
        'jam_mulai_istirahat',
        'jam_selesai_istirahat',
        'lama_istirahat_menit',
        'lama_ijin_menit',
        'lama_ijin_jam',
        'absen_alasan',
        'gaji_pokok',
        'gaji_harian',
        'gaji_menit',
        'potongan_iks_rupiah',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $guarded = ['tanggal_berjalan', 'enroll_id', 'nomor_form_perizinan'];

    protected $hidden = [''];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'uuid',
        'periode_umk',
        'nomor_form_perizinan',
        'tanggal_berjalan',
        'enroll_id',
        'employee_name',
        'sub_dept_name',
        'time_mulai_ijin',
        'time_akhir_ijin',
        'jam_mulai_istirahat',
        'jam_selesai_istirahat',
        'lama_istirahat_menit',
        'lama_ijin_menit',
        'lama_ijin_jam',
        'absen_alasan',
        'gaji_pokok',
        'gaji_harian',
        'gaji_menit',
        'potongan_iks_rupiah',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [];
    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = ['tanggal_berjalan', 'enroll_id', 'nomor_form_perizinan'];


}
