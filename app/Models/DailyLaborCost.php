<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLaborCost extends Model
{
    use HasFactory;
    protected $fillable = [
        'id','enroll_id','status_staff','group_department','tanggal_berjalan','iby','itb','m','dt','pc','dtpc','lby','lsm','r','ok','hari_kerja','pot_hari_kerja','total_absen','gaji_perhari','gaji_permenit','total_lembur_rupiah','seniority_allowance','insentif_kehadiran','insentif_jabatan','rp_pot_hari_kerja','rp_pot_jam','bruto','bpjs_tk','bpjs_ks','total_potongan','pembulatan','jumlah','bpjs_tk_company','bpjs_ks_company','kompensasi','thr','konsumsi','total_pembayaran','created_at','updated_at'
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

}
