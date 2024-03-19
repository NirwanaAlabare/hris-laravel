<?php
namespace App\Models;

class RekapPerhitunganDTPC extends \Eloquent
{
    protected $table = 'rekap_perhitungan_dtpc';

    protected $fillable = [
        'uuid',
        'periode_umk',
        'tanggal_berjalan',
        'employee_id',
        'enroll_id',
        'employee_name',
        'mulai_jam_kerja',
        'akhir_jam_kerja',
        'absen_masuk_kerja',
        'absen_pulang_kerja',
        'status_absen',
        'gaji_pokok',
        'gaji_menit',
        'jumlah_menit_absen_dt',
        'jumlah_menit_absen_pc',
        'jumlah_menit_absen_dtpc',
        'potongan_dt_rupiah',
        'potongan_pc_rupiah',
        'potongan_dtpc_rupiah',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $guarded = ['tanggal_berjalan', 'enroll_id'];

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
        'employee_id',
        'enroll_id',
        'employee_name',
        'mulai_jam_kerja',
        'akhir_jam_kerja',
        'absen_masuk_kerja',
        'absen_pulang_kerja',
        'status_absen',
        'gaji_pokok',
        'gaji_menit',
        'jumlah_menit_absen_dt',
        'jumlah_menit_absen_pc',
        'jumlah_menit_absen_dtpc',
        'potongan_dt_rupiah',
        'potongan_pc_rupiah',
        'potongan_dtpc_rupiah',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [];
    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = ['tanggal_berjalan', 'enroll_id'];


}
