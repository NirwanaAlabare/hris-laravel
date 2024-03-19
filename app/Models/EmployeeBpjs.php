<?php
namespace App\Models;

class EmployeeBpjs extends \Eloquent
{
    protected $table = 'employee_bpjs';

    protected $fillable = [
        'uuid',
        'kode_bpjs',
        'periode_bpjs',
        'periode_kehadiran',
        'enroll_id',
        'nik',
        'employee_name',
        'status_aktif_bpjs_tk',
        'tanggal_bpjs_ketenagakerjaan',
        'nomor_bpjs_ketenagakerjaan',
        'status_aktif_bpjs_ks',
        'tanggal_bpjs_kesehatan',
        'nomor_bpjs_kesehatan',
        'kode_periode_bpjs',
        'kode_dasar_pot_bpjs',
        'dasar_pot_bpjs_rupiah',
        'bpjs_tk_jkm_bruto_rupiah',
        'bpjs_tk_jkk_bruto_rupiah',
        'bpjs_ks_jkn_bruto_rupiah',
        'bpjs_tk_jht_bruto_rupiah',
        'bpjs_tk_jpn_bruto_rupiah',
        'bpjs_tk_jkm_neto_rupiah',
        'bpjs_tk_jkk_neto_rupiah',
        'bpjs_tk_jht_neto_rupiah',
        'bpjs_tk_jpn_neto_rupiah',
        'bpjs_ks_jkn_neto_rupiah',
        'bpjs_tk_jkm_persen',
        'bpjs_tk_jkk_persen',
        'bpjs_tk_jht_persen',
        'bpjs_tk_jpn_persen',
        'bpjs_ks_jkn_persen',
        'bpjs_tk_jkm_bruto_persen',
        'bpjs_tk_jkm_neto_persen',
        'bpjs_tk_jkk_bruto_persen',
        'bpjs_tk_jkk_neto_persen',
        'bpjs_tk_jht_bruto_persen',
        'bpjs_tk_jht_neto_persen',
        'bpjs_tk_jpn_bruto_persen',
        'bpjs_tk_jpn_neto_persen',
        'bpjs_ks_jkn_bruto_persen',
        'bpjs_ks_jkn_neto_persen',        
        'operator',
        'created_at',
        'updated_at',
        'deleted_at',
        'tmk',
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
        'kode_bpjs',
        'periode_bpjs',
        'periode_kehadiran',
        'enroll_id',
        'nik',
        'employee_name',
        'status_aktif_bpjs_tk',
        'tanggal_bpjs_ketenagakerjaan',
        'nomor_bpjs_ketenagakerjaan',
        'status_aktif_bpjs_ks',
        'tanggal_bpjs_kesehatan',
        'nomor_bpjs_kesehatan',
        'kode_periode_bpjs',
        'kode_dasar_pot_bpjs',
        'dasar_pot_bpjs_rupiah',
        'bpjs_tk_jkm_bruto_rupiah',
        'bpjs_tk_jkk_bruto_rupiah',
        'bpjs_ks_jkn_bruto_rupiah',
        'bpjs_tk_jht_bruto_rupiah',
        'bpjs_tk_jpn_bruto_rupiah',
        'bpjs_tk_jkm_neto_rupiah',
        'bpjs_tk_jkk_neto_rupiah',
        'bpjs_tk_jht_neto_rupiah',
        'bpjs_tk_jpn_neto_rupiah',
        'bpjs_ks_jkn_neto_rupiah',
        'bpjs_tk_jkm_persen',
        'bpjs_tk_jkk_persen',
        'bpjs_tk_jht_persen',
        'bpjs_tk_jpn_persen',
        'bpjs_ks_jkn_persen',
        'bpjs_tk_jkm_bruto_persen',
        'bpjs_tk_jkm_neto_persen',
        'bpjs_tk_jkk_bruto_persen',
        'bpjs_tk_jkk_neto_persen',
        'bpjs_tk_jht_bruto_persen',
        'bpjs_tk_jht_neto_persen',
        'bpjs_tk_jpn_bruto_persen',
        'bpjs_tk_jpn_neto_persen',
        'bpjs_ks_jkn_bruto_persen',
        'bpjs_ks_jkn_neto_persen',        
        'operator',
        'created_at',
        'updated_at',
        'deleted_at',
        'tmk',

    ];

    protected $appends = [];
    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = ['kode_bpjs'];


}
