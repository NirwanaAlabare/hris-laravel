<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{

    protected $table = 'jurnal';

    protected $fillable = [
        'id',
        'periode_payroll',
        'kode_bagian',
        'nama_bagian',
        'gaji',
        'tunjangan_karyawan_rupiah',
        'total_lembur_rupiah',
        'bonus',
        'piutang_karyawan',
        'piutang_bazzar',
        'bpjs_tk',
        'bpjs_ks',
        'potongan',
        'gaji_neto',
        'jumlah_karyawn',
        'created_at',
        'updated_at',

    ];

}

