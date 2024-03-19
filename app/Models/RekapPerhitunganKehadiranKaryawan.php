<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapPerhitunganKehadiranKaryawan extends Model
{

    protected $table = 'rekap_perhitungan_kehadiran_karyawan';

    protected $fillable = [
        'uuid',
        'periode_umk',
        'kode_rekap',
        'periode_payroll',
        'periode_tahun_bulan',
        'enroll_id',
        'employee_name',
        'kehadiran_iby',
        'kehadiran_itb',
        'kehadiran_lby',
        'kehadiran_lsm',
        'kehadiran_dt',
        'kehadiran_pc',
        'kehadiran_dtpc',
        'kehadiran_m',
        'kehadiran_r',
        'kehadiran_tk',
        'kehadiran_ok',
        'total_kehadiran',
        'total_kehadiran_net',
        'jumlah_hari',
        'jumlah_hari_kerja',
        'gaji_pokok',
        'gaji_harian',
        'gaji_menit',
        'potongan_kehadiran_rupiah',
        'kehadiran_m_estimasi',

    
    ];

}

